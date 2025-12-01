<x-app-layout>
    {{-- CSS Kustom --}}
    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.8s ease-out forwards; }
        .rounded-[15px] { border-radius: 15px; }
        /* Transisi halus untuk munculnya form upload */
        #upload-section {
            transition: all 0.5s ease-in-out;
        }
        .btn-generete {
            background-color: #3d4a5fff;
            color: white;
        }
    </style>

    <x-slot name="header">
        <div class="fade-in">
            <h2 class="font-semibold text-xl text-base-content leading-tight">
                📝 {{ __('Tambah Data BRS Baru') }}
            </h2>
            <div class="text-sm breadcrumbs text-base-content/70">
                 <ul>
                    <li><a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a></li>
                    <li><a href="{{ route('brs.index') }}" class="hover:text-primary">Data BRS</a></li>
                    <li>Tambah Baru</li>
                </ul>
            </div>
        </div>
    </x-slot>

    <div class="py-12 fade-in">
        <div class="px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto"> 
            
            <div class="card bg-base-100 shadow-xl rounded-[15px]">
                <div class="card-body p-6 md:p-8">

                    {{-- Error Display --}}
                    @if ($errors->any())
                        <div role="alert" class="alert alert-error mb-6 shadow-lg rounded-[15px]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <div>
                                <h3 class="font-bold">Terjadi Kesalahan!</h3>
                                <ul class="list-disc pl-5 mt-1 text-xs">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('brs.store') }}" method="POST" enctype="multipart/form-data" id="brsForm">
                        @csrf
                        
                        <div class="space-y-8">

                            {{-- BAGIAN 1: INFORMASI DASAR (Selalu Muncul) --}}
                            <section>
                                <h3 class="text-lg font-semibold text-base-content mb-4 border-b pb-2">1️⃣ Informasi Awal</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    
                                    {{-- Judul --}}
                                    <div class="form-control w-full md:col-span-2">
                                        <label for="judul" class="label"><span class="label-text">Judul BRS</span></label>
                                        <input type="text" name="judul" id="judul" class="input input-bordered w-full rounded-[15px]" value="{{ old('judul') }}" placeholder="Contoh: Perkembangan Indeks Harga Konsumen..." required>
                                    </div>

                                    {{-- Tanggal Rilis (PENTING: Type Date untuk logic '10 November') --}}
                                    <div class="form-control w-full">
                                        <label for="bulan" class="label"><span class="label-text">Tanggal Rilis</span></label>
                                        <input type="date" name="bulan" id="bulan" class="input input-bordered w-full rounded-[15px]" value="{{ old('bulan') }}" required>
                                    </div>

                                    {{-- Pengelola --}}
                                    <div class="form-control w-full">
                                        <label for="user_id" class="label"><span class="label-text">Pengelola</span></label>
                                        <select name="user_id" id="user_id" class="select select-bordered w-full rounded-[15px]" required>
                                            <option value="" disabled selected>-- Pilih Pengelola --</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- TOMBOL GENERATE --}}
                                    <div class="form-control w-full md:col-span-2 mt-2">
                                        <button type="button" id="btn-generate" class="btn btn-generete rounded-[15px] w-full md:w-1px">
                                            ⚙️ Generate Nomor BRS
                                        </button>
                                        <label class="label">
                                            <span class="label-text-alt text-error hidden" id="error-msg">Silakan isi Tanggal Rilis terlebih dahulu!</span>
                                        </label>
                                    </div>

                                    {{-- Hasil Generate Nomor (Readonly) --}}
                                    <div class="form-control w-full md:col-span-2">
                                        <label for="nomor_brs" class="label"><span class="label-text font-bold">Nomor BRS (Otomatis)</span></label>
                                        <input type="text" name="nomor_brs" id="nomor_brs" class="input input-bordered w-full rounded-[15px] bg-base-200 font-mono text-sm" placeholder="Tekan tombol generate di atas..." readonly required>
                                    </div>

                                </div>
                            </section>

                            {{-- BAGIAN 2: UPLOAD FILE (HIDDEN DEFAULT) --}}
                            {{-- Kita gunakan class 'hidden' dari Tailwind untuk menyembunyikan ini --}}
                            <section id="upload-section" class="hidden opacity-0">
                                <h3 class="text-lg font-semibold text-base-content mb-4 border-b pb-2 mt-8">2️⃣ Upload Dokumen</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                    {{-- Upload PDF --}}
                                    <div class="form-control w-full">
                                        <label for="pdf" class="label"><span class="label-text">File PDF</span></label>
                                        <input type="file" name="pdf" id="pdf" class="file-input file-input-bordered w-full rounded-[15px]" required>
                                    </div>
                                    
                                    {{-- Upload ZIP --}}
                                    <div class="form-control w-full">
                                        <label for="zip" class="label"><span class="label-text">File ZIP</span></label>
                                        <input type="file" name="zip" id="zip" class="file-input file-input-bordered w-full rounded-[15px]" required>
                                    </div>

                                    {{-- Upload Infografis --}}
                                    <div class="form-control w-full md:col-span-2">
                                        <label for="infografis" class="label">
                                            <span class="label-text">Infografis (Multiple)</span>
                                        </label>
                                        <input type="file" name="infografis[]" id="infografis" class="file-input file-input-bordered w-full rounded-[15px]" required multiple>
                                    </div>

                                    {{-- Upload Excel --}}
                                    <div class="form-control w-full md:col-span-2">
                                        <label for="excel" class="label">
                                            <span class="label-text">File Excel (Opsional)</span>
                                        </label>
                                        <input type="file" name="excel" id="excel" class="file-input file-input-bordered w-full rounded-[15px]">
                                    </div>
                                </div>

                                {{-- Tombol Simpan Akhir --}}
                                <div class="flex justify-end items-center mt-10 pt-6 border-t border-base-200 gap-3">
                                    <a href="{{ route('brs.index') }}" class="btn btn-ghost rounded-[15px]">Batal</a>
                                    <button type="submit" class="btn btn-primary btn-premium rounded-[15px] text-black">
                                        💾 Simpan Semua Data
                                    </button>
                                </div>
                            </section>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT LOGIC --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnGenerate = document.getElementById('btn-generate');
            const inputBulan = document.getElementById('bulan');
            const inputNomor = document.getElementById('nomor_brs');
            const uploadSection = document.getElementById('upload-section');
            const errorMsg = document.getElementById('error-msg');

            btnGenerate.addEventListener('click', function() {
                // 1. Validasi Client Side: Pastikan tanggal diisi
                const bulanValue = inputBulan.value;
                if (!bulanValue) {
                    inputBulan.classList.add('input-error');
                    errorMsg.classList.remove('hidden');
                    return;
                }

                // Reset Validasi
                inputBulan.classList.remove('input-error');
                errorMsg.classList.add('hidden');
                
                // Ubah tombol jadi loading state
                const originalText = btnGenerate.innerHTML;
                btnGenerate.innerHTML = '<span class="loading loading-spinner"></span> Memproses...';
                btnGenerate.setAttribute('disabled', 'true');

                // 2. Fetch API ke Backend
                fetch("{{ route('brs.generateNumber') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}" // Token wajib Laravel
                    },
                    body: JSON.stringify({
                        bulan: bulanValue
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // 3. Masukkan hasil generate ke input
                    if(data.status === 'success') {
                        inputNomor.value = data.nomor;

                        // 4. Munculkan Section Upload dengan Animasi
                        uploadSection.classList.remove('hidden');
                        // Trik sedikit delay agar transisi opacity jalan
                        setTimeout(() => {
                            uploadSection.classList.remove('opacity-0');
                        }, 50);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal generate nomor. Silakan coba lagi.');
                })
                .finally(() => {
                    // Kembalikan tombol seperti semula
                    btnGenerate.innerHTML = originalText;
                    btnGenerate.removeAttribute('disabled');
                });
            });
        });
    </script>
</x-app-layout>