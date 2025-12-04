<x-app-layout>
    <style>
        /* Style tetap sama */
        .rounded-[15px] { border-radius: 15px; }
        .btn-generete { background-color: #3d4a5fff; color: white; }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
             📝 {{ __('Tambah Data BRS Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
            <div class="card bg-base-100 shadow-xl rounded-[15px]">
                <div class="card-body">
                    
                    {{-- Form Start --}}
                    <form action="{{ route('brs.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Judul --}}
                            <div class="form-control w-full md:col-span-2">
                                <label class="label"><span class="label-text">Judul BRS</span></label>
                                <input type="text" name="judul" class="input input-bordered w-full rounded-[15px]" required>
                            </div>

                            {{-- Tanggal --}}
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text">Tanggal Rilis</span></label>
                                <input type="date" name="bulan" id="bulan" class="input input-bordered w-full rounded-[15px]" required>
                            </div>

                            {{-- Pengelola --}}
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text">Pengelola</span></label>
                                <select name="user_id" class="select select-bordered w-full rounded-[15px]" required>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tombol Generate --}}
                            <div class="form-control w-full md:col-span-2">
                                <button type="button" id="btn-generate" class="btn btn-generete rounded-[15px] text-center w-full">
                                    ⚙️ Generate Nomor BRS
                                </button>
                                <span class="text-error text-xs hidden mt-1" id="error-msg">Isi tanggal dulu!</span>
                            </div>

                            {{-- Hasil Nomor --}}
                            <div class="form-control w-full md:col-span-2">
                                <label class="label"><span class="label-text font-bold">Nomor BRS</span></label>
                                <input type="text" name="nomor_brs" id="nomor_brs" class="input input-bordered bg-base-200 rounded-[15px]" readonly required>
                            </div>

                        </div>

                        {{-- Tombol Simpan (Awalnya Hidden/Disabled agar user generate dulu) --}}
                        <div class="mt-8 flex justify-end gap-3">
                            <a href="{{ route('brs.index') }}" class="btn btn-ghost rounded-[15px]">Batal</a>
                            <button type="submit" id="btn-submit" class="btn btn-primary text-black rounded-[15px]" disabled>
                                Simpan & Lanjut ke Upload
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnGenerate = document.getElementById('btn-generate');
            const btnSubmit = document.getElementById('btn-submit');
            
            btnGenerate.addEventListener('click', function() {
                const bulan = document.getElementById('bulan').value;
                if(!bulan) { document.getElementById('error-msg').classList.remove('hidden'); return; }

                // Fetch API logic (sama seperti sebelumnya)
                fetch("{{ route('brs.generateNumber') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                    body: JSON.stringify({ bulan: bulan })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success') {
                        document.getElementById('nomor_brs').value = data.nomor;
                        // Aktifkan tombol simpan setelah generate sukses
                        btnSubmit.removeAttribute('disabled');
                    }
                });
            });
        });
    </script>
</x-app-layout>