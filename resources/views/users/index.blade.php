{{-- 
    File ini adalah revisi total UI/UX halaman Manajemen Pegawai.
    Desainnya ditingkatkan untuk menyamai standar kualitas halaman Master Publikasi,
    dengan fokus pada layout lebar, hierarki visual, dan ikonografi profesional.
--}}

<x-app-layout>
    {{-- CSS Kustom untuk Tampilan Premium --}}
    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.8s ease-out forwards; }
        @keyframes slideUpIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        .table-row-animated { opacity: 0; animation: slideUpIn 0.5s ease-out forwards; animation-delay: var(--delay); }
        .table tbody tr.hover:hover, .table-hover tbody tr:hover {
            background-color: hsl(var(--b2, 220 13% 91%) / 0.5);
        }
        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: hsl(var(--bc) / 0.6);
            border-bottom-width: 2px;
        }
        .btn-premium {
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        }
        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }
        .inactive-row {
            transition: all 0.3s ease-in-out;
            opacity: 0.5;
            filter: grayscale(80%);
        }
        .inactive-row:hover {
            opacity: 1;
            filter: grayscale(0);
        }
        .status-switch {
            position: relative; display: inline-block; width: 50px; height: 28px;
        }
        .status-switch input { opacity: 0; width: 0; height: 0; }
        .slider {
            position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
            background-color: #ef4444; /* Merah saat non-aktif */
            transition: .4s; border-radius: 28px;
        }
        .slider:before {
            position: absolute; content: ""; height: 20px; width: 20px; left: 4px; bottom: 4px;
            background-color: white; transition: .4s; border-radius: 50%;
        }
        input:checked + .slider { background-color: #22c55e; } /* Hijau saat aktif */
        input:checked + .slider:before { transform: translateX(22px); }
    </style>

    {{-- Header Halaman --}}
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-black leading-tight">
                {{ __('Manajemen Pegawai') }}
            </h2>
            <div class="text-sm breadcrumbs text-gray-500">
                <ul>
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li> 
                    <li>Manajemen Pegawai</li>
                </ul>
            </div>
        </div>
    </x-slot>

    {{-- Konten Utama --}}
    <div class="py-12 fade-in">
        <div class="px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div role="alert" class="alert alert-success mb-5 shadow-lg"><span>{{ session('success') }}</span></div>
            @endif

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body p-6 md:p-8">
                    
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <h3 class="text-xl font-bold text-base-content/90">Daftar Pegawai</h3>
                        <a href="{{ route('users.create') }}" class="btn btn-primary btn-premium w-full md:w-auto text-white gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                            Tambah Pegawai
                        </a>
                    </div>

                    <div class="flex justify-between items-center mb-4">
                        <div class="join">
                            <a href="{{ route('users.index', ['search' => request('search')]) }}" class="join-item btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-ghost' }}">Semua</a>
                            <a href="{{ route('users.index', ['status' => 'aktif', 'search' => request('search')]) }}" class="join-item btn btn-sm {{ request('status') == 'aktif' ? 'btn-primary' : 'btn-ghost' }}">Aktif</a>
                            <a href="{{ route('users.index', ['status' => 'tidak-aktif', 'search' => request('search')]) }}" class="join-item btn btn-sm {{ request('status') == 'tidak-aktif' ? 'btn-primary' : 'btn-ghost' }}">Tidak Aktif</a>
                        </div>
                        <form action="{{ route('users.index') }}" method="GET" class="w-full md:w-1/3 relative">
                            @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3"><svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none"><path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
                            <input name="search" type="text" placeholder="Cari nama atau NIP..." class="input input-bordered w-full pl-10" value="{{ request('search') }}" />
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr class="text-sm">
                                    <th>#</th> <th>Nama</th> <th>NIP BPS</th> <th>Peran Fungsional</th> <th class="text-center">Status</th> <th>Tanggal Dibuat</th> <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr class="table-row-animated hover {{ !$user->status ? 'inactive-row' : '' }}" style="--delay: {{ $loop->iteration * 0.05 }}s;">
                                        <th>{{ $users->firstItem() + $loop->index }}</th>
                                        <td>
                                            <div class="font-bold text-base-content">{{ $user->name }}</div>
                                            <div class="text-sm opacity-60">{{ $user->email }}</div>
                                        </td>
                                        <td>{{ $user->nip_bps }}</td>
                                        <td>
                                            @forelse($user->getRoleNames()->reject(fn($role) => in_array($role, ['Admin', 'Pegawai'])) as $role)
                                                <span class="badge badge-neutral badge-sm">{{ $role }}</span>
                                            @empty
                                                <span class="text-xs opacity-50">-</span>
                                            @endforelse
                                        </td>
                                        <td class="text-center">
                                            <label class="status-switch">
                                                <input type="checkbox" class="status-toggle" data-user-id="{{ $user->id }}" {{ $user->status ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </td>
                                        <td>{{ $user->created_at->format('d M Y') }}</td>
                                        <td class="flex gap-1 justify-center">
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-ghost btn-xs btn-circle" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-warning" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg></a>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-ghost btn-xs btn-circle" title="Hapus"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-error" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center py-16"><p class="text-xl font-semibold">Data Pegawai Tidak Ditemukan</p></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($users->hasPages())
                    <div class="mt-6 flex justify-between items-center">
                        <p class="text-sm text-base-content/70">Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} hasil</p>
                        {{ $users->withQueryString()->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const toggles = document.querySelectorAll('.status-toggle');
            toggles.forEach(toggle => {
                toggle.addEventListener('change', function () {
                    const userId = this.dataset.userId;
                    const newStatus = this.checked;
                    const row = this.closest('tr');
                    fetch(`/users/${userId}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json',
                        },
                        body: JSON.stringify({ status: newStatus })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            row.classList.toggle('inactive-row', !newStatus);
                        } else {
                            this.checked = !newStatus; alert('Gagal mengubah status.');
                        }
                    })
                    .catch(error => {
                        this.checked = !newStatus; alert('Terjadi kesalahan koneksi.');
                    });
                });
            });
        });
    </script>
</x-app-layout>

