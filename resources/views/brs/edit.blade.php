<x-app-layout>
    {{-- CSS Kustom (Sama seperti Create) --}}
    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.8s ease-out forwards; }
        .rounded-[15px] { border-radius: 15px; }
        .btn-generete { background-color: #3d4a5fff; color: white; }
    </style>

    <x-slot name="header">
        <div class="fade-in">
            <h2 class="font-semibold text-xl text-base-content leading-tight">
                 ✏️ {{ __('Edit Data BRS') }}
            </h2>
            <div class="text-sm breadcrumbs text-base-content/70">
                 <ul>
                    <li><a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a></li>
                    <li><a href="{{ route('brs.index') }}" class="hover:text-primary">Data BRS</a></li>
                    <li>Edit Data</li>
                </ul>
            </div>
        </div>
    </x-slot>

    <div class="py-12 fade-in">
        <div class="px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
            <div class="card bg-base-100 shadow-xl rounded-[15px]">
                <div class="card-body">
                    
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

                    {{-- Form Start --}}
                    {{-- Perhatikan route mengarah ke brs.update dengan ID --}}
                    <form action="{{ route('brs.update', $brs->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Wajib untuk Update di Laravel --}}
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Judul --}}
                            <div class="form-control w-full md:col-span-2">
                                <label class="label"><span class="label-text font-semibold">Judul BRS</span></label>
                                <input type="text" name="judul" class="input input-bordered w-full rounded-[15px]" 
                                       value="{{ old('judul', $brs->judul) }}" required>
                            </div>

                            {{-- Tanggal Rilis --}}
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text font-semibold">Tanggal Rilis</span></label>
                                {{-- Format value tanggal harus Y-m-d untuk input type="date" --}}
                                <input type="date" name="bulan" id="bulan" class="input input-bordered w-full rounded-[15px]" 
                                       value="{{ old('bulan', $brs->bulan ? $brs->bulan->format('Y-m-d') : '') }}" required>
                            </div>

                            {{-- Pengelola --}}
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text font-semibold">Pengelola</span></label>
                                <select name="user_id" class="select select-bordered w-full rounded-[15px]" required>
                                    @foreach($users as $user)
                                        {{-- Logic Selected: Cek apakah ID user sama dengan user_id di data BRS --}}
                                        <option value="{{ $user->id }}" {{ old('user_id', $brs->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tombol Generate (Tetap ada jika user ingin merubah nomor) --}}
                            <div class="form-control w-full md:col-span-2">
                                <button type="button" id="btn-generate" class="btn btn-generete rounded-[15px] btn-sm md:btn-md">
                                    ⚙️ Regenerate Nomor BRS (Opsional)
                                </button>
                                <span class="text-xs text-gray-500 mt-1 ml-1">*Klik hanya jika tanggal berubah dan nomor perlu disesuaikan.</span>
                                <span class="text-error text-xs hidden mt-1" id="error-msg">Isi tanggal dulu!</span>
                            </div>

                            {{-- Hasil Nomor --}}
                            <div class="form-control w-full md:col-span-2">
                                <label class="label"><span class="label-text font-bold">Nomor BRS</span></label>
                                <input type="text" name="nomor_brs" id="nomor_brs" 
                                       class="input input-bordered bg-base-200 font-mono text-sm" 
                                       value="{{ old('nomor_brs', $brs->nomor_brs) }}" readonly required>
                            </div>

                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="mt-8 flex justify-end gap-3 border-t pt-6">
                            <a href="{{ route('brs.index') }}" class="btn btn-ghost rounded-[15px]">Batal</a>
                            <button type="submit" class="btn btn-primary text-black rounded-[15px]">
                                💾 Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- Javascript Generate (Sama Persis dengan Create) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnGenerate = document.getElementById('btn-generate');
            
            btnGenerate.addEventListener('click', function() {
                const bulan = document.getElementById('bulan').value;
                if(!bulan) { document.getElementById('error-msg').classList.remove('hidden'); return; }

                // UI Loading
                const originalText = btnGenerate.innerHTML;
                btnGenerate.innerHTML = '<span class="loading loading-spinner loading-xs"></span> Memproses...';
                btnGenerate.setAttribute('disabled', 'true');

                // Fetch API
                fetch("{{ route('brs.generateNumber') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                    body: JSON.stringify({ bulan: bulan })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success') {
                        document.getElementById('nomor_brs').value = data.nomor;
                    }
                })
                .catch(err => alert('Gagal generate nomor.'))
                .finally(() => {
                    btnGenerate.innerHTML = originalText;
                    btnGenerate.removeAttribute('disabled');
                });
            });
        });
    </script>
</x-app-layout>