{{-- 
    File ini adalah revisi total UI/UX halaman Tambah Pegawai.
    Desainnya disamakan dengan halaman index untuk menciptakan pengalaman yang konsisten,
    menggunakan layout lebar dan gaya form yang bersih dan profesional.
--}}

<x-app-layout>
    {{-- CSS Kustom --}}
    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.8s ease-out forwards; }
        .btn-premium {
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        }
        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }
    </style>

    {{-- Header Halaman --}}
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-black leading-tight">
                {{ __('Tambah Pegawai Baru') }}
            </h2>
            <div class="text-sm breadcrumbs text-gray-500">
                <ul>
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li> 
                    <li><a href="{{ route('users.index') }}">Manajemen Pegawai</a></li> 
                    <li>Tambah Baru</li>
                </ul>
            </div>
        </div>
    </x-slot>

    {{-- Konten Utama --}}
    <div class="py-12 fade-in">
        <div class="px-4 sm:px-6 lg:px-8">

            <div class="card bg-base-100 shadow-xl max-w-4xl mx-auto">
                <div class="card-body p-6 md:p-8">

                    <h3 class="text-xl font-bold text-base-content/90 mb-6">Formulir Pegawai Baru</h3>

                    @if ($errors->any())
                        <div class="alert alert-error mb-6 shadow-lg">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <div>
                                    <h3 class="font-bold">Terjadi Kesalahan!</h3>
                                    <ul class="list-disc pl-5 mt-1 text-xs">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text font-semibold">Nama Lengkap</span></label>
                                <input type="text" name="name" placeholder="Contoh: Budi Santoso, S.Stat." class="input input-bordered w-full" required value="{{ old('name') }}">
                            </div>

                            <div class="form-control w-full">
                                <label class="label"><span class="label-text font-semibold">NIP BPS</span></label>
                                <input type="text" name="nip_bps" placeholder="Masukkan 18 digit NIP BPS" class="input input-bordered w-full" required value="{{ old('nip_bps') }}">
                            </div>

                            <div class="form-control w-full">
                                <label class="label"><span class="label-text font-semibold">Password</span></label>
                                <input type="password" name="password" placeholder="Minimal 8 karakter" class="input input-bordered w-full" required>
                            </div>

                            <div class="form-control w-full">
                                <label class="label"><span class="label-text font-semibold">Konfirmasi Password</span></label>
                                <input type="password" name="password_confirmation" placeholder="Ketik ulang password" class="input input-bordered w-full" required>
                            </div>

                            <div class="form-control md:col-span-2">
                                <label class="label"><span class="label-text font-semibold">Peran Fungsional</span></label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-2 p-4 border rounded-lg bg-base-200/50">
                                    @forelse($roles as $role)
                                    <label class="label cursor-pointer justify-start">
                                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="checkbox checkbox-primary mr-3" {{ (is_array(old('roles')) && in_array($role->name, old('roles'))) ? ' checked' : '' }} />
                                        <span class="label-text">{{ $role->name }}</span> 
                                    </label>
                                    @empty
                                    <span class="text-gray-500 text-sm col-span-full">Tidak ada peran fungsional yang tersedia.</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end items-center mt-8 pt-6 border-t border-base-200">
                            <a href="{{ route('users.index') }}" class="btn btn-ghost mr-3">Batal</a>
                            <button type="submit" class="btn btn-primary btn-premium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                                Simpan Pegawai
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

