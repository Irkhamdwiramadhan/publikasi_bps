<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Brs;
use App\Models\BrsComment; // PENTING: Import model komentar
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

// Import Library Google
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class BrsController extends Controller
{
    public function index()
    {
        // Eager load comments untuk efisiensi
        $brs_list = Brs::with(['user', 'comments'])->latest('bulan')->paginate(10);

        // LOGIC NOTIFIKASI CHAT (Sama seperti Publikasi)
        $user = Auth::user();
        $userRoles = $user->getRoleNames();
        // Tentukan peran saat ini
        $role = $userRoles->contains('Penyusun') ? 'Penyusun' : ($userRoles->contains('Pemeriksa') ? 'Pemeriksa' : $userRoles->first());

        $brs_list->getCollection()->transform(function($item) use ($role) {
            // Hitung pesan yang belum dibaca dari "lawan bicara"
            $roleToCheck = $role === 'Penyusun' ? 'Pemeriksa' : 'Penyusun';
            $item->unread_count = $item->comments()->where('role', $roleToCheck)->where('is_read', false)->count();
            return $item;
        });

        return view('brs.index', compact('brs_list'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('brs.create', compact('users'));
    }

    // --- API GENERATE NOMOR ---
    public function generateNumber(Request $request)
    {
        $request->validate(['bulan' => 'required|date']);
        $date = Carbon::parse($request->bulan);
        
        $jumlahDataTahunIni = Brs::whereYear('bulan', $date->year)->count();
        $urutan = str_pad($jumlahDataTahunIni + 1, 2, '0', STR_PAD_LEFT);
        $bulanAngka = $date->format('m');
        $kodeBps = '3328';
        $tahunDasar = 2023; 
        $selisihTahun = $date->year - $tahunDasar;
        $angkaRomawi = $selisihTahun > 0 ? $selisihTahun : 1; 
        $thRomawi = $this->numberToRoman($angkaRomawi);
        $tanggalRilis = $date->locale('id')->isoFormat('D MMMM Y');
        $nomorBrs = "No. {$urutan}/{$bulanAngka}/{$kodeBps}/Th. {$thRomawi}, {$tanggalRilis}";

        return response()->json(['status' => 'success', 'nomor' => $nomorBrs]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul'     => 'required|string|max:255',
            'bulan'     => 'required|date', 
            'nomor_brs' => 'required|string',
            'user_id'   => 'required|exists:users,id',
        ]);

        Brs::create([
            'judul'     => $validatedData['judul'],
            'nomor_brs' => $validatedData['nomor_brs'],
            'user_id'   => $validatedData['user_id'],
            'bulan'     => $validatedData['bulan'],
            'status'    => 'draft', // Default status
        ]);

        return redirect()->route('brs.index')->with('success', 'Data BRS berhasil dibuat.');
    }

    // --- FITUR UPLOAD (GOOGLE DRIVE) ---
    public function uploadFiles(Request $request, $id)
    {
        $brs = Brs::findOrFail($id);

        // Validasi
        $request->validate([
            'pdf'          => 'nullable|file|mimes:pdf|max:51200', // Ubah jadi nullable agar bisa upload parsial jika revisi cuma 1 file
            'zip'          => 'nullable|file|mimes:zip,rar|max:20480',
            'excel'        => 'nullable|file|mimes:xlsx,xls|max:20480',
            'infografis'   => 'nullable|array',
            'infografis.*' => 'image|mimes:jpeg,png,jpg|max:5120'
        ]);

        try {
            $service = $this->getGoogleDriveService();
            $parentFolderId = env('GOOGLE_DRIVE_BRS_FOLDER_ID');
            if (!$parentFolderId) throw new \Exception("ID Folder BRS belum disetting.");

            // Format Nama Folder & File
            $tahun = Carbon::parse($brs->bulan)->format('Y');
            $cleanJudul = Str::limit(preg_replace('/[^A-Za-z0-9 \-]/', '', $brs->judul), 50);
            
            // Cari/Buat Folder
            $subFolderName = "[{$tahun}] " . trim($cleanJudul);
            $subFolderId = $this->findOrCreateFolder($service, $subFolderName, $parentFolderId);

            $updates = [];
            
            // Nama file dinamis berdasarkan status
            // Jika masih draft/revisi, beri label [DRAFT]. Jika disetujui, [FINAL].
            $statusLabel = ($brs->status == 'disetujui') ? '[FINAL]' : '[DRAFT]';
            $baseName = "{$subFolderName} {$statusLabel}";

            // --- 1. UPLOAD PDF (REPLACE) ---
            if ($request->hasFile('pdf')) {
                // Hapus file lama di Drive jika ada
                if ($brs->pdf_path) {
                    $this->deleteFileFromDrive($service, $brs->pdf_path);
                }
                
                $fileName = "{$baseName} [BRS].pdf";
                $updates['pdf_path'] = $this->uploadToDrive($service, $request->file('pdf'), $subFolderId, $fileName);
            }

            // --- 2. UPLOAD ZIP (REPLACE) ---
            if ($request->hasFile('zip')) {
                if ($brs->zip_path) {
                    $this->deleteFileFromDrive($service, $brs->zip_path);
                }

                $fileName = "{$baseName} [DATA].zip";
                $updates['zip_path'] = $this->uploadToDrive($service, $request->file('zip'), $subFolderId, $fileName);
            }

            // --- 3. UPLOAD EXCEL (REPLACE) ---
            if ($request->hasFile('excel')) {
                if ($brs->excel_path) {
                    $this->deleteFileFromDrive($service, $brs->excel_path);
                }

                $ext = $request->file('excel')->getClientOriginalExtension();
                $fileName = "{$baseName} [TABEL].{$ext}";
                $updates['excel_path'] = $this->uploadToDrive($service, $request->file('excel'), $subFolderId, $fileName);
            }

            // --- 4. UPLOAD INFOGRAFIS (APPEND/REPLACE?) ---
            // Infografis agak tricky karena array. 
            // Saran: Jika upload baru, hapus SEMUA gambar lama, ganti dengan yang baru.
            if ($request->hasFile('infografis')) {
                
                // Hapus semua gambar lama di Drive
                if ($brs->infografis_paths && is_array($brs->infografis_paths)) {
                    foreach ($brs->infografis_paths as $oldLink) {
                        $this->deleteFileFromDrive($service, $oldLink);
                    }
                }

                $infografisLinks = [];
                foreach ($request->file('infografis') as $index => $file) {
                    $urutan = $index + 1;
                    $ext = $file->getClientOriginalExtension();
                    $fileName = "{$baseName} [INFO-{$urutan}].{$ext}";
                    $infografisLinks[] = $this->uploadToDrive($service, $file, $subFolderId, $fileName);
                }
                $updates['infografis_paths'] = $infografisLinks;
            }

            // Update status otomatis jika masih draft
            if ($brs->status == 'draft' || $brs->status == 'butuh_perbaikan') {
                $updates['status'] = 'sedang_diperiksa';
            }

            $brs->update($updates);

            return redirect()->back()->with('success', 'File berhasil diperbarui (File lama telah dihapus dari Drive).');

        } catch (\Exception $e) {
            Log::error("Gagal Upload BRS: " . $e->getMessage());
            return back()->with('error', 'Gagal upload: ' . $e->getMessage());
        }
    }

    // --- FITUR BARU: UPDATE STATUS ---
    public function updateStatus(Request $request, $id)
    {
        try {
            $brs = Brs::findOrFail($id);
            $request->validate([
                'status' => ['required', Rule::in(['draft', 'sedang_diperiksa', 'disetujui', 'butuh_perbaikan', 'ditolak'])]
            ]);

            // Jika status berubah jadi DISETUJUI, kita Rename file di Drive
            if ($request->status == 'disetujui' && $brs->status != 'disetujui') {
                $this->renameFilesToFinal($brs);
            }

            $brs->status = $request->status;
            $brs->save();
            
            return response()->json(['success' => true, 'message' => 'Status diperbarui & File di-rename (jika disetujui).']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal: ' . $e->getMessage()], 500);
        }
    }

    // --- FITUR BARU: HALAMAN KOMENTAR (CHAT) ---
    public function comment($id)
    {
        $brs = Brs::with(['comments.user', 'user'])->findOrFail($id);
        
        $user = Auth::user();
        $roles = $user->getRoleNames();
        $role = $roles->contains('Penyusun') ? 'Penyusun' : ($roles->contains('Pemeriksa') ? 'Pemeriksa' : 'Penyusun');

        // Tandai pesan sudah dibaca
        $roleToCheck = $role === 'Penyusun' ? 'Pemeriksa' : 'Penyusun';
        $brs->comments()->where('role', $roleToCheck)->where('is_read', false)->update(['is_read' => true]);

        return view('brs.comment', compact('brs', 'role'));
    }
    private function deleteFileFromDrive($service, $fileUrl)
    {
        try {
            // Ekstrak ID dari URL
            $fileId = $this->extractDriveId($fileUrl);
            if ($fileId) {
                $service->files->delete($fileId);
                Log::info("File lama dihapus dari Drive: " . $fileId);
            }
        } catch (\Exception $e) {
            // Jangan crash kalau file lama gak ketemu (mungkin sudah dihapus manual)
            Log::warning("Gagal hapus file lama di Drive: " . $e->getMessage());
        }
    }
    private function renameFilesToFinal($brs)
    {
        try {
            $service = $this->getGoogleDriveService();
            
            // Fungsi kecil untuk rename satu file
            $doRename = function($url, $suffix) use ($service) {
                if (!$url) return;
                $id = $this->extractDriveId($url);
                if (!$id) return;

                // Ambil nama file asli
                $file = $service->files->get($id, ['fields' => 'name']);
                $oldName = $file->getName();
                
                // Ganti [DRAFT] jadi [FINAL]
                $newName = str_replace('[DRAFT]', '[FINAL]', $oldName);
                
                // Update ke Google
                $fileMetadata = new DriveFile(['name' => $newName]);
                $service->files->update($id, $fileMetadata);
            };

            // Jalankan rename untuk semua file yang ada
            $doRename($brs->pdf_path, '[BRS].pdf');
            $doRename($brs->zip_path, '[DATA].zip');
            $doRename($brs->excel_path, '[TABEL].xlsx');
            
            if ($brs->infografis_paths) {
                foreach ($brs->infografis_paths as $path) {
                    $doRename($path, '[INFO].jpg');
                }
            }

        } catch (\Exception $e) {
            Log::error("Gagal Rename ke FINAL: " . $e->getMessage());
        }
    }

    private function extractDriveId($url)
    {
        if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) return $matches[1];
        if (preg_match('/id=([a-zA-Z0-9_-]+)/', $url, $matches)) return $matches[1];
        return null;
    }

    // --- FITUR BARU: KIRIM KOMENTAR ---
    public function storeComment(Request $request, $id)
    {
        $request->validate(['body' => 'required|string|max:1000']);
        
        $brs = Brs::findOrFail($id);
        $user = Auth::user();
        $roles = $user->getRoleNames();
        $role = $roles->contains('Penyusun') ? 'Penyusun' : ($roles->contains('Pemeriksa') ? 'Pemeriksa' : 'Penyusun');

        BrsComment::create([
            'brs_id' => $brs->id,
            'user_id' => $user->id,
            'body' => $request->body,
            'role' => $role,
            'is_read' => false
        ]);

        return redirect()->route('brs.comment', $id)->with('success', 'Pesan terkirim.');
    }

    // --- HELPER GOOGLE DRIVE ---
    private function findOrCreateFolder($service, $folderName, $parentId)
    {
        $query = "mimeType='application/vnd.google-apps.folder' and name='" . str_replace("'", "\'", $folderName) . "' and '{$parentId}' in parents and trashed=false";
        $files = $service->files->listFiles(['q' => $query, 'fields' => 'files(id, name)']);

        if (count($files->getFiles()) > 0) {
            return $files->getFiles()[0]->getId();
        }

        $folderMetadata = new DriveFile([
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => [$parentId]
        ]);

        $folder = $service->files->create($folderMetadata, ['fields' => 'id']);
        return $folder->id;
    }

    private function getGoogleDriveService()
    {
        $client = new Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        
        $refreshToken = env('GOOGLE_DRIVE_REFRESH_TOKEN');
        if (!$refreshToken) throw new \Exception("Refresh Token tidak ditemukan.");

        $client->refreshToken($refreshToken);
        
        try {
            $newAccessToken = $client->fetchAccessTokenWithRefreshToken($refreshToken);
            if (isset($newAccessToken['error'])) throw new \Exception("Gagal Refresh Token.");
            $client->setAccessToken($newAccessToken);
        } catch (\Exception $e) {
             throw new \Exception("Error Auth Google: " . $e->getMessage());
        }

        return new Drive($client);
    }

    private function uploadToDrive($service, $fileObject, $folderId, $fileName)
    {
        $content = file_get_contents($fileObject->getRealPath());
        $mimeType = $fileObject->getMimeType();

        $fileMetadata = new DriveFile([
            'name' => $fileName,
            'parents' => [$folderId]
        ]);

        $uploadedFile = $service->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => $mimeType,
            'uploadType' => 'multipart',
            'fields' => 'id, webViewLink'
        ]);

        return $uploadedFile->webViewLink;
    }

    // --- STANDAR LAINNYA ---
    public function show(string $id)
    {
        $brs = Brs::findOrFail($id);
        $brs->load('user');
        return view('brs.show', compact('brs'));
    }

    public function edit(string $id)
    {
        $brs = Brs::findOrFail($id);
        $users = User::orderBy('name')->get();
        return view('brs.edit', compact('brs', 'users'));
    }

    public function update(Request $request, string $id)
    {
        $brs = Brs::findOrFail($id);
        $validatedData = $request->validate([
            'judul'     => 'required|string|max:255',
            'bulan'     => 'required|date', 
            'nomor_brs' => 'required|string',
            'user_id'   => 'required|exists:users,id',
        ]);

        $brs->update([
            'judul'     => $validatedData['judul'],
            'nomor_brs' => $validatedData['nomor_brs'],
            'user_id'   => $validatedData['user_id'],
            'bulan'     => $validatedData['bulan'],
        ]);

        return redirect()->route('brs.index')->with('success', 'Data BRS berhasil diperbarui.');
    }

    private function numberToRoman($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if ($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
}