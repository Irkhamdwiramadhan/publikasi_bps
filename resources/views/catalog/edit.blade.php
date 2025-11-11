<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ __('Edit Katalog') }}
        </h2>
        <div class="text-sm breadcrumbs text-base-content/70">
            <ul>
                <li><a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a></li> 
                <li><a href="{{ route('catalog.index') }}" class="hover:text-primary">Master Katalog</a></li>
                <li>Edit</li>
            </ul>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="px-4 sm:px-6 lg:px-8 max-w-2xl mx-auto">
             <div class="card bg-base-100 shadow-xl rounded-[20px]">
                <div class="card-body p-6 md:p-8">
                    
                    @if ($errors->any())
                        <div class="alert alert-error mb-4">
                            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('catalog.update', $catalog->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-control w-full">
                            <label for="nomor_katalog" class="label"><span class="label-text">Nomor Katalog</span></label>
                            <input id="nomor_katalog" type="text" name="nomor_katalog" value="{{ old('nomor_katalog', $catalog->nomor_katalog) }}" 
                                   class="input input-bordered w-full rounded-[15px]" required>
                        </div>
                        
                        <div class="form-control w-full">
                            <label for="judul_katalog" class="label"><span class="label-text">Judul Katalog</span></label>
                            <input id="judul_katalog" type="text" name="judul_katalog" value="{{ old('judul_katalog', $catalog->judul_katalog) }}" 
                                   class="input input-bordered w-full rounded-[15px]" required>
                        </div>

                        <div class="flex justify-end items-center mt-8 pt-6 border-t border-base-200 gap-3">
                            <a href="{{ route('catalog.index') }}" class="btn btn-ghost rounded-[15px]">Batal</a>
                            <button type="submit" class="btn btn-primary text-black rounded-[15px]">
                                Perbarui Katalog
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>