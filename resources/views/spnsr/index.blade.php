<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">🧾 Cetak Surat SPNSR</h2>
    </x-slot>

    <div class="p-6 max-w-3xl mx-auto bg-white rounded shadow">
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('spnsr.generate') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block font-medium">Nomor Surat</label>
                <input type="text" name="nomor" value="{{ old('nomor') }}" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-medium">Tanggal (teks lengkap)</label>
                <textarea name="tanggal" rows="2" class="w-full border rounded px-3 py-2" required>{{ old('tanggal') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block font-medium">Judul Buku</label>
                <input type="text" name="judul" value="{{ old('judul') }}" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-medium">Jenis</label>
                <select name="jenis" class="w-full border rounded px-3 py-2">
                    <option value="ARC" {{ old('jenis')=='ARC' ? 'selected' : '' }}>ARC</option>
                    <option value="Non ARC" {{ old('jenis')=='Non ARC' ? 'selected' : '' }}>Non ARC</option>
                </select>
            </div>

            {{-- Optional: penandatangan --}}
            <div class="mb-4">
                <label class="block font-medium">Nama Penandatangan (opsional)</label>
                <input type="text" name="nama_penandatangan" value="{{ old('nama_penandatangan') }}" class="w-full border rounded px-3 py-2">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">🖨 Cetak SPNSR</button>
        </form>
    </div>
</x-app-layout>
