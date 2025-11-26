<?php

namespace App\Http\Controllers;

use App\Models\SubmissionPublication;
use App\Models\Catalog;
use App\Models\SubmissionComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class SubmissionPublicationController extends Controller
{
    // =========================
    // INDEX
    // =========================
// =========================
    // INDEX (DENGAN FILTER TAHUN)
    // =========================
    public function index(Request $request)
    {
        $request->validate([
            'per_page' => 'nullable|integer|in:10,25,50',
            'search'   => 'nullable|string|max:100',
            'year'     => 'nullable|integer', // Validasi input tahun
        ]);

        $perPage = $request->input('per_page', 10);
        $search  = $request->input('search');
        $year    = $request->input('year');

        $query = SubmissionPublication::with(['user', 'catalog', 'sprp', 'comments']);

        // 1. Filter Pencarian
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul_publikasi', 'like', "%$search%")
                  ->orWhere('judul_eng', 'like', "%$search%");
            });
        }

        // 2. Filter Tahun (BARU)
        if ($year) {
            $query->whereYear('created_at', $year);
        }

        $submissions = $query->latest('created_at')->paginate($perPage)->withQueryString();

        $user = Auth::user();
        $roles = $user->getRoleNames();
        $role = $roles->contains('Penyusun') ? 'Penyusun' : ($roles->contains('Pemeriksa') ? 'Pemeriksa' : $roles->first() ?? 'Penyusun');

        $submissions->getCollection()->transform(function($item) use ($role) {
            $roleToCheck = $role === 'Penyusun' ? 'Pemeriksa' : 'Penyusun';
            $item->unread_count = $item->comments()->where('role', $roleToCheck)->where('is_read', false)->count();
            return $item;
        });

        // 3. Ambil Daftar Tahun yang Tersedia (Untuk Dropdown)
        // Mengambil tahun unik dari kolom created_at
        $availableYears = SubmissionPublication::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('pengajuan_publikasi.index', [
            'submissions' => $submissions,
            'filters' => $request->only(['search', 'per_page', 'year']),
            'availableYears' => $availableYears,
        ]);
    }

    // =========================
    // SHOW DETAIL
    // =========================
    
    // =========================
    // EDIT & UPDATE
    // =========================
    public function edit(SubmissionPublication $submission)
    {
        $submission->load(['user', 'catalog']);
        $catalogs = Catalog::orderBy('nomor_katalog')->get();
        return view('pengajuan_publikasi.edit', compact('submission', 'catalogs'));
    }

    public function update(Request $request, SubmissionPublication $submission)
    {
        $validatedData = $request->validate([
            'type_publikasi'  => 'required|string|max:50',
            'judul_publikasi' => 'required|string|max:255',
            'judul_eng'       => 'nullable|string|max:255',
            'estimasi_rilis'  => 'required|date',
            'bahasa'          => 'required|string|max:50',
            'catalog_id'      => 'required|exists:catalogs,id',
            'issn'            => 'nullable|string|max:50',
            'isbn'            => 'nullable|string|max:50',
            'fungsi_pengusul' => 'required|string|max:255',
            'tautan_publikasi'=> 'nullable|url|max:255',
            'link_publikasi_final' => 'nullable|url|max:255',
            'spnrs_ketua_tim' => 'nullable|url|max:255',
        ]);

        $submission->update($validatedData);
        return redirect()->route('pengajuan_publikasi.index')->with('success', 'Data pengajuan publikasi berhasil diperbarui.');
    }

    // =========================
    // UPDATE STATUS & AUTO UPLOAD
    // =========================
    public function updateStatus(Request $request, $id)
    {
        Log::info("Memulai updateStatus untuk ID: {$id} dengan status: {$request->status}");

        try {
            $submission = SubmissionPublication::findOrFail($id);

            $request->validate([
                'status' => ['required', Rule::in(['draft', 'sedang_diperiksa', 'disetujui', 'butuh_perbaikan', 'ditolak'])]
            ]);

            $oldStatus = $submission->status;
            
            // 1. Simpan status baru ke Database
            $submission->status = $request->status;
            $submission->save();

            Log::info("Status berhasil diubah dari {$oldStatus} ke {$submission->status}");

            // 2. TRIGGER AUTO UPLOAD: Hanya jika status 'disetujui' dan ada link drive
            if ($request->status == 'disetujui' && $submission->tautan_publikasi) {
                
                Log::info("Status disetujui. Memulai proses automasi Google Drive...");
                
                try {
                    // Panggil fungsi Helper untuk upload
                    $linkKantor = $this->processGoogleDriveUpload($submission->tautan_publikasi, $submission->judul_publikasi);
                    
                    // Simpan link hasil upload kantor ke database
                    $submission->link_publikasi_final = $linkKantor;
                    $submission->save();

                    Log::info("Automasi Selesai. Link kantor: " . $linkKantor);

                    // Berikan respon sukses penuh
                    return response()->json([
                        'success' => true, 
                        'message' => 'Publikasi disetujui & File berhasil dipindahkan ke Google Drive Kantor.'
                    ]);
                    
                } catch (\Exception $e) {
                    // Error Handling Khusus Upload
                    Log::error("GAGAL AUTO UPLOAD ke Drive Kantor: " . $e->getMessage());
                    
                    // Kita kembalikan success=true (karena status db sudah berubah), 
                    // tapi beri pesan warning agar user tahu uploadnya gagal.
                    return response()->json([
                        'success' => true, 
                        'message' => 'Status berhasil disetujui, NAMUN Gagal upload otomatis ke Drive Kantor. Cek Log error: ' . $e->getMessage()
                    ]);
                }
            }

            // Respon standar jika bukan status 'disetujui'
            return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui.']);

        } catch (\Exception $e) {
            // Error Handling General (Database/Server error)
            Log::error("Gagal update status ID {$id}: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server: ' . $e->getMessage()], 500);
        }
    }

    // =========================
    // GOOGLE DRIVE LOGIC (CORE)
    // =========================
    private function processGoogleDriveUpload($urlPenyusun, $judulPublikasi)
    {
        // 1. Setup Client menggunakan Kredensial dari .env
        $client = new Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->refreshToken(env('GOOGLE_DRIVE_REFRESH_TOKEN'));
        
        $service = new Drive($client);

        // 2. Ambil ID File dari Link Penyusun
        $fileIdPenyusun = $this->extractDriveId($urlPenyusun);
        
        if (!$fileIdPenyusun) {
            throw new \Exception("Link Google Drive penyusun tidak valid atau berbentuk Folder.");
        }

        // 3. Download File Penyusun ke Temporary Server
        $downloadUrl = "https://drive.google.com/uc?export=download&id=" . $fileIdPenyusun;
        
        // Gunakan HTTP Client Laravel (Timeout ditingkatkan untuk file besar)
        $response = Http::timeout(120)->get($downloadUrl);
        
        if ($response->failed()) {
             throw new \Exception("Gagal mendownload file penyusun. Pastikan akses link 'Anyone with the link'.");
        }

        $fileContent = $response->body();
        
        // Cek validitas konten (bukan HTML error page)
        if (str_contains(substr($fileContent, 0, 100), '<html') || str_contains(substr($fileContent, 0, 100), '<!DOCTYPE html')) {
            throw new \Exception("Gagal download. Link penyusun sepertinya Restricted (Tidak Publik/Private).");
        }

        // Setup Temporary File Path
        $safeTitle = Str::slug(Str::limit($judulPublikasi, 50));
        $tempFileName = "temp_{$safeTitle}_" . time(); // Tanpa ekstensi dulu
        Storage::put("temp_upload/{$tempFileName}", $fileContent);
        
        $localPath = Storage::path("temp_upload/{$tempFileName}");

        try {
            // Deteksi Mime Type Asli File (PDF/Doc/Excel)
            $mimeType = mime_content_type($localPath);
            
            // Tentukan ekstensi berdasarkan mime type (Opsional, untuk kerapihan nama file)
            $extension = '.pdf'; // Default
            if ($mimeType == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') $extension = '.docx';
            if ($mimeType == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') $extension = '.xlsx';
            
            // 4. Upload ke Folder Kantor
            $folderKantorId = env('GOOGLE_DRIVE_FOLDER_ID');
            
            if (!$folderKantorId || $folderKantorId == 'masukkan_id_folder_tujuan_disini') {
                throw new \Exception("ID Folder Google Drive Kantor belum disetting di .env");
            }

            $fileMetadata = new DriveFile([
                'name' => "[FINAL] " . $judulPublikasi . $extension, // Nama file di drive kantor
                'parents' => [$folderKantorId]
            ]);

            $uploadedFile = $service->files->create($fileMetadata, [
                'data' => file_get_contents($localPath),
                'mimeType' => $mimeType, 
                'uploadType' => 'multipart',
                'fields' => 'id, webViewLink, webContentLink'
            ]);

            return $uploadedFile->webViewLink; // Kembalikan link file kantor

        } catch (\Exception $e) {
            throw $e;
        } finally {
            // 5. Cleanup: Selalu hapus file temp apapun yang terjadi
            if (Storage::exists("temp_upload/{$tempFileName}")) {
                Storage::delete("temp_upload/{$tempFileName}");
            }
        }
    }

    // Helper untuk ambil ID dari URL
    private function extractDriveId($url)
    {
        // Pattern 1: /file/d/ID_FILE/view
        if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }
        // Pattern 2: id=ID_FILE
        if (preg_match('/id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }
        return null;
    }

    // =========================
    // DESTROY
    // =========================
    public function destroy(SubmissionPublication $submission)
    {
        try {
            \DB::beginTransaction();
            if ($submission->sprp) $submission->sprp->delete();
            if ($submission->spnsrSubmission) $submission->spnsrSubmission->delete();
            $submission->comments()->delete();
            $submission->delete();
            \DB::commit();
            return redirect()->route('pengajuan_publikasi.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->route('pengajuan_publikasi.index')->with('error', 'Gagal menghapus data.');
        }
    }

    // =========================
    // KOMENTAR
    // =========================
    

    public function comment(SubmissionPublication $submission)
    {
        $user = auth()->user();
        $roles = $user->getRoleNames();
        $role = $roles->contains('Penyusun') ? 'Penyusun' : ($roles->contains('Pemeriksa') ? 'Pemeriksa' : $roles->first() ?? 'Penyusun');

        // [REVISI KECIL] Hapus 'publication' dari load, ganti ke 'catalog' jika perlu
        $submission->load(['comments.user', 'catalog']);

        $roleToCheck = $role === 'Penyusun' ? 'Pemeriksa' : 'Penyusun';

        $submission->comments()
            ->where('role', $roleToCheck)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $unreadCount = $submission->comments()
            ->where('role', $roleToCheck)
            ->where('is_read', false)
            ->count();

        return view('pengajuan_publikasi.comment', compact('submission', 'unreadCount'));
    }

    /**
     * (TIDAK BERUBAH)
     * Menyimpan komentar baru.
     */
    public function storeComment(Request $request, SubmissionPublication $submission)
    {
        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $user = auth()->user();
        $roles = $user->getRoleNames();
        $role = $roles->contains('Penyusun') ? 'Penyusun' : ($roles->contains('Pemeriksa') ? 'Pemeriksa' : $roles->first() ?? 'Penyusun');

        $comment = new \App\Models\SubmissionComment();
        $comment->body = $request->body;
        $comment->user_id = $user->id;
        $comment->role = $role;
        $comment->is_read = false;

        $submission->comments()->save($comment);

        return redirect()->route('pengajuan_publikasi.comment', $submission->id)
            ->with('success', 'Komentar berhasil dikirim.');
    }
    public function show(SubmissionPublication $submission)
    {
        // Muat relasi ke sprp dan catalog
        $submission->load(['sprp', 'catalog']);

        // Ambil data sprp (jika ada)
        $sprp = $submission->sprp;

        // Kirim ke view
        return view('pengajuan_publikasi.show', compact('submission', 'sprp'));
    }
}
