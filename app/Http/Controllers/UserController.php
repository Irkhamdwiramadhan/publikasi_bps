<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // [MODIFIKASI] Memulai query dengan filter WAJIB: hanya user dengan peran 'Pegawai'
        $query = User::whereHas('roles', function ($query) {
            $query->where('name', 'Pegawai');
        });

        // Menerapkan filter pencarian jika ada
        if ($request->filled('search')) {
            $search = $request->search;
            // Kurung `where()` ini penting agar `orWhere` tidak mengganggu query `whereHas` di atas.
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('nip_bps', 'like', '%' . $search . '%');
            });
        }

        // Menerapkan filter status jika ada
        if ($request->filled('status')) {
            if ($request->status == 'aktif') {
                $query->where('status', 1);
            } elseif ($request->status == 'tidak-aktif') {
                $query->where('status', 0);
            }
        }

        // Mengambil data dengan paginasi
        $users = $query->with('roles')->latest()->paginate(10);
        
        // Mengembalikan view dengan data
        return view('users.index', compact('users'));
    }

    public function create()
    {
        // Logika ini masih sama, tidak perlu diubah
        $roles = Role::whereNotIn('name', ['Admin', 'Pegawai'])->get();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // Validasi input (ini sudah benar)
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'nip_bps' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roles' => ['nullable', 'array'],
        ]);

        // PERBAIKAN UTAMA ADA DI BLOK INI
        $user = User::create([
            'name' => $request->name,
            'nip_bps' => $request->nip_bps, // PASTIKAN 'nip_bps' menerima data dari 'nip_bps'
            'email' => null,                // PASTIKAN 'email' diisi dengan null
            'password' => Hash::make($request->password),
            'status' => true,
        ]);
        // PERBAIKAN SELESAI

        // Memberi peran (ini sudah benar)
        $user->assignRole('Pegawai');
        if ($request->has('roles')) {
            $user->assignRole($request->roles);
        }

        // Kembali ke halaman daftar (ini sudah benar)
        return redirect()->route('users.index')->with('success', 'Pegawai baru berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        // Logika ini masih sama, tidak perlu diubah
        $roles = Role::whereNotIn('name', ['Admin', 'Pegawai'])->get();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // [REVISI] Menambahkan 'unique' rule untuk 'nip_bps'
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:'.User::class.',name,'.$user->id],
            'nip_bps' => ['required', 'string', 'max:255', 'unique:'.User::class.',nip_bps,'.$user->id], // <-- REVISI
            'roles' => ['nullable', 'array'],
            'status' => ['required', 'boolean'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'name' => $request->name,
            'nip_bps' => $request->nip_bps,
            'status' => $request->status,
        ]);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        $functionalRoles = Role::whereNotIn('name', ['Admin', 'Pegawai'])->pluck('name');
        // Hapus semua peran fungsional lama
        $user->removeRole(...$functionalRoles);
        // Tambahkan peran fungsional baru
        if ($request->has('roles')) {
            $user->assignRole($request->roles);
        }

        return redirect()->route('users.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Logika ini masih sama, tidak perlu diubah
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Data pegawai berhasil dihapus.');
    }

     public function updateStatus(Request $request, User $user)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|boolean',
        ]);

        try {
            // Update status user
            $user->status = $request->status;
            $user->save();
            
            // Kirim response sukses dalam format JSON
            return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui.']);
        } catch (\Exception $e) {
            // Kirim response error jika gagal
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui status.'], 500);
        }
    }
}

