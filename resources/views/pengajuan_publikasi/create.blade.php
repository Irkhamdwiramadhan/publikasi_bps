<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            ➕ Tambah Pengajuan Publikasi
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="bg-white shadow rounded-xl p-6 max-w-3xl mx-auto">
            {{-- Pesan sukses atau error --}}
            @if (session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-error mb-4">
                    <ul class="list-disc pl-6 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pengajuan_publikasi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Pilih publikasi --}}
                <div class="form-control mb-4">
                    <label class="label font-medium">Judul Publikasi</label>
                    <select name="publication_id" class="select select-bordered w-full" required>
                        <option value="">-- Pilih Publikasi --</option>
                        @foreach ($publications as $pub)
                            <option value="{{ $pub->id }}">
                                {{ $pub->title_ind }} ({{ $pub->publication_type }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Fungsi pengusul --}}
                <div class="form-control mb-4">
                    <label class="label font-medium">Fungsi Pengusul</label>
                    <input type="text" name="fungsi_pengusul" class="input input-bordered w-full"
                        placeholder="Contoh: Fungsi Statistik Sosial" value="{{ old('fungsi_pengusul') }}" required>
                </div>
                
                

                {{-- Nama penyusun (otomatis user login) --}}
                <div class="form-control mb-4">
                    <label class="label font-medium">Nama Penyusun</label>
                    <input type="text" class="input input-bordered w-full bg-gray-100"
                        value="{{ Auth::user()->name }}" readonly>
                </div>

                {{-- Tautan publikasi --}}
                <div class="form-control mb-4">
                    <label class="label font-medium">Tautan File Publikasi</label>
                    <input type="url" name="tautan_publikasi" class="input input-bordered w-full"
                        placeholder="https://contoh.bps.go.id/file-publikasi.pdf"
                        value="{{ old('tautan_publikasi') }}">
                </div>

                {{-- SPNRS Ketua Tim --}}
                <div class="form-control mb-6">
                    <label class="label font-medium">SPNRS dari Ketua Tim</label>
                    <input type="url" name="spnrs_ketua_tim" class="input input-bordered w-full"
                        placeholder="https://contoh.bps.go.id/spnrs.pdf" value="{{ old('spnrs_ketua_tim') }}">
                </div>

                {{-- Tombol aksi --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('pengajuan_publikasi.index') }}" class="btn btn-ghost">Batal</a>
                    <button type="submit" class="btn btn-primary text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
