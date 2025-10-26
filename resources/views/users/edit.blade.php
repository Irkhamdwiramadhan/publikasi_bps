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
            {{-- REVISI: Mengganti text-black menjadi text-base-content --}}
            <h2 class="font-semibold text-xl text-base-content leading-tight">
                {{ __('Edit Data Pegawai') }}
            </h2>
            {{-- REVISI: Mengganti text-gray-500 menjadi text-base-content/70 dan perbaiki hover --}}
            <div class="text-sm breadcrumbs text-base-content/70">
                <ul>
                    <li><a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a></li> 
                    <li><a href="{{ route('users.index') }}" class="hover:text-primary">Manajemen Pegawai</a></li> 
                    <li>Edit Pegawai</li>
                </ul>
            </div>
        </div>
    </x-slot>

    {{-- Konten Utama --}}
    <div class="py-12 fade-in">
        {{-- REVISI: Menyamakan max-w-5xl seperti halaman create --}}
        <div class="px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto"> 

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body p-6 md:p-8">

                    <h3 class="text-xl font-bold text-base-content/90 mb-6">Formulir Edit Pegawai: {{ $user->name }}</h3>

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

                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        {{-- REVISI: Menggunakan space-y-6 untuk memisahkan grup --}}
                        <div class="space-y-6">

                            {{-- Grup 1: Informasi Pegawai --}}
                            <section>
                                <h4 class="text-lg font-medium text-base-content/80 border-b border-base-200 pb-2 mb-4">Informasi Pegawai</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                    {{-- Nama Lengkap --}}
                                    <div class="form-control w-full">
                                        <label for="name" class="block text-sm font-semibold text-base-content/80 mb-1">Nama Lengkap</label>
                                        <input id="name" name="name" type="text" class="input input-bordered w-full rounded-[15px] {{ $errors->has('name') ? 'input-error' : '' }}" required value="{{ old('name', $user->name) }}">
                                        @error('name')
                                            <p class="text-error text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- NIP BPS --}}
                                    <div class="form-control w-full">
                                        <label for="nip_bps" class="block text-sm font-semibold text-base-content/80 mb-1">NIP BPS</label>
                                        <input id="nip_bps" name="nip_bps" type="text" class="input input-bordered w-full rounded-[15px] {{ $errors->has('nip_bps') ? 'input-error' : '' }}" required value="{{ old('nip_bps', $user->nip_bps) }}">
                                        @error('nip_bps')
                                            <p class="text-error text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </section>

                            {{-- Grup 2: Akses & Peran --}}
                            <section>
                                <h4 class="text-lg font-medium text-base-content/80 border-b border-base-200 pb-2 mb-4">Akses & Peran</h4>
                                <div class="space-y-4">
                                    {{-- Status Akun --}}
                                    <div class="form-control w-full md:w-1/2">
                                        <label for="status" class="block text-sm font-semibold text-base-content/80 mb-1">Status Akun</label>
                                        <select id="status" name="status" class="select select-bordered rounded-[15px] {{ $errors->has('status') ? 'select-error' : '' }}">
                                            <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>Aktif</option>
                                            <option value="0" {{ old('status', $user->status) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                                        </select>
                                        @error('status')
                                            <p class="text-error text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Peran Fungsional --}}
                                    <div class="form-control w-full">
                                        <label class="block text-sm font-semibold text-base-content/80 mb-1">Peran Fungsional</label>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-2 p-4 border rounded-[15px] bg-base-200/50">
                                            @foreach($roles as $role)
                                            <label class="label cursor-pointer justify-start">
                                                <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="checkbox checkbox-primary mr-3" {{ $user->hasRole($role->name) ? 'checked' : '' }} />
                                                <span class="label-text">{{ $role->name }}</span> 
                                            </label>
                                            @endforeach
                                        </div>
                                        @error('roles')
                                            <p class="text-error text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </section>

                            {{-- Grup 3: Keamanan --}}
                            <section>
                                <h4 class="text-lg font-medium text-base-content/80 border-b border-base-200 pb-2 mb-4">Ubah Password <span class="text-gray-400 font-normal">(Opsional)</span></h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                    {{-- Password Baru --}}
                                    <div class="form-control w-full">
                                        <label for="password" class="block text-sm font-semibold text-base-content/80 mb-1">Password Baru</label>
                                        <input id="password" type="password" name="password" placeholder="Isi jika ingin diubah" class="input input-bordered w-full rounded-[15px] {{ $errors->has('password') ? 'input-error' : '' }}">
                                        @error('password')
                                            <p class="text-error text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Konfirmasi Password Baru --}}
                                    <div class="form-control w-full">
                                        <label for="password_confirmation" class="block text-sm font-semibold text-base-content/80 mb-1">Konfirmasi Password Baru</label>
                                        <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Ketik ulang password baru" class="input input-bordered w-full rounded-[15px]">
                                    </div>
                                </div>
                            </section>
                        </div>
                        
                        {{-- Tombol Aksi --}}
                        <div class="flex justify-end items-center mt-8 pt-6 border-t border-base-200">
                            <a href="{{ route('users.index') }}" class="btn btn-ghost mr-3">Batal</a>
                            <button type="submit" class="btn btn-primary btn-premium rounded-[15px]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                </svg>
                                Update Pegawai
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>