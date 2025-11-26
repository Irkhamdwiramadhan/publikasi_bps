<x-app-layout>
    {{-- 🎨 Custom Styles --}}
    <style>
        .pdf-frame {
            border: none;
            width: 100%;
            height: 100%;
            border-radius: 0.75rem;
        }
        
        .tab-btn {
            position: relative;
            overflow: hidden;
        }
        
        .tab-active {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            border-color: #2563eb;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        
        .tab-inactive {
            background-color: #f9fafb;
            color: #374151;
            border-color: #e5e7eb;
            transition: all 0.3s ease;
        }
        
        .tab-inactive:hover {
            background-color: #f3f4f6;
            border-color: #2563eb;
            transform: translateY(-2px);
        }
        
        .header-gradient {
            background: linear-gradient(135deg, #3b4a69ff 0%, #343c52ff 100%);
        }
        
        .btn-download-custom {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-download-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(67, 84, 119, 0.4);
        }
    </style>

    <x-slot name="header">
        <div class=" rounded-b-xl px-6 py-4 shadow-lg">
            <h2 class="text-2xl font-bold text-black leading-tight flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Pusat Bantuan & Pedoman
            </h2>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 h-[calc(100vh-100px)] bg-gray-50">
        <div class="max-w-7xl mx-auto h-full flex flex-col gap-6">

            {{-- Controls Area --}}
            <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                    
                    {{-- Tab Buttons --}}
                    <div class="flex gap-3 w-full md:w-auto overflow-x-auto">
                        <button onclick="switchPdf('aplikasi', this)" 
                            class="tab-btn tab-inactive px-6 py-3 rounded-xl font-semibold text-sm transition-all duration-300 shadow-sm border flex items-center gap-2 whitespace-nowrap"
                            data-src="{{ asset('panduan/panduan_aplikasi.pdf') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            Pedoman Aplikasi
                        </button>
                        <button onclick="switchPdf('publikasi', this)" 
                            class="tab-btn tab-active px-6 py-3 rounded-xl font-semibold text-sm transition-all duration-300 shadow-sm border flex items-center gap-2 whitespace-nowrap"
                            data-src="{{ asset('panduan/pedoman_publikasi.pdf') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                            Panduan Publikasi
                        </button>
                    </div>

                    {{-- Download Button --}}
                    <a id="btn-download" href="{{ asset('panduan/pedoman_publikasi.pdf') }}" download 
                        class="btn-download-custom px-6 py-3 rounded-xl font-semibold text-sm shadow-md border-0 flex items-center gap-2 whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        Download PDF
                    </a>
                </div>
            </div>

            {{-- PDF Viewer Area --}}
            <div class="bg-white shadow-xl rounded-2xl border border-gray-100 flex-grow relative overflow-hidden">
                {{-- Loading Spinner --}}
                <div id="loader" class="absolute inset-0 flex items-center justify-center bg-white z-10 hidden">
                    <span class="loading loading-spinner loading-lg text-blue-600"></span>
                </div>

                {{-- Iframe --}}
                <iframe 
                    id="pdf-viewer"
                    src="{{ asset('panduan/pedoman_publikasi.pdf') }}" 
                    class="pdf-frame"
                    title="PDF Viewer">
                </iframe>
            </div>

        </div>
    </div>

    {{-- Script --}}
    <script>
        function switchPdf(type, btn) {
            const viewer = document.getElementById('pdf-viewer');
            const downloadBtn = document.getElementById('btn-download');
            const newSrc = btn.getAttribute('data-src');
            const allBtns = document.querySelectorAll('.tab-btn');

            allBtns.forEach(b => {
                b.classList.remove('tab-active');
                b.classList.add('tab-inactive');
            });
            btn.classList.remove('tab-inactive');
            btn.classList.add('tab-active');

            viewer.src = newSrc;
            downloadBtn.href = newSrc;
        }
    </script>
</x-app-layout>