<x-app-layout>

    {{-- CSS Kustom untuk Dashboard --}}
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }

        .rounded-[15px] {
            border-radius: 15px;
        }

        /* Style untuk card KPI */
        .kpi-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            background-color: hsl(var(--b1));
            border-radius: 15px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        .kpi-text h3 {
            font-size: 0.875rem;
            font-weight: 500;
            color: hsl(var(--bc) / 0.7);
            text-transform: uppercase;
        }

        .kpi-text p {
            font-size: 2.25rem;
            font-weight: 700;
            color: hsl(var(--bc));
        }

        .kpi-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* FullCalendar styling */
        .fc-day-sun,
        .fc-day-sat {
            background-color: #fdecec !important;
            /* Weekend pink */
        }

        .fc-day-today {
            background-color: #f3f0ff !important;
            /* Today soft purple */
            border: 1px solid #c5baff !important;
        }

        .fc-event {
            font-size: 12px;
            padding: 4px 6px;
            border-radius: 6px;
            cursor: pointer;
        }

        #calendar {
            min-height: 650px;
        }
        .card-title {
            font-weight: bold;
            font-size: 1.0rem;
       
           
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight fade-in">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 fade-in">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filter Tahun --}}
            <div class="mb-6 p-4 bg-base-100 rounded-[15px] shadow">
                <form action="{{ route('dashboard') }}" method="GET" class="flex items-center gap-3">
                    <label for="year" class="text-base-content/70 font-semibold text-sm">Tampilkan Data Tahun:</label>

                    <select name="year" id="year"
                        class="select select-bordered select-medium rounded-[15px] font-sm"
                        onchange="this.form.submit()">

                        @forelse ($availableYears as $year)
                        <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                        @empty
                        <option value="{{ $selectedYear }}">{{ $selectedYear }}</option>
                        @endforelse
                    </select>
                </form>
            </div>

            {{-- KPI Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="kpi-card">
                    <div class="kpi-text">
                        <h3>Publikasi ARC ({{ $currentYear }})</h3>
                        <p>{{ $kpi_arc }}</p>
                    </div>
                    <div class="kpi-icon bg-blue-100 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>

                <div class="kpi-card">
                    <div class="kpi-text">
                        <h3>Publikasi Non-ARC ({{ $currentYear }})</h3>
                        <p>{{ $kpi_non_arc }}</p>
                    </div>
                    <div class="kpi-icon bg-green-100 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0l2.25 1.75a3 3 0 013.5 0L17 7m-12 0" />
                        </svg>
                    </div>
                </div>

                <div class="kpi-card">
                    <div class="kpi-text">
                        <h3>Publikasi Rilis ({{ $currentYear }})</h3>
                        <p>{{ $kpi_rilis }}</p>
                    </div>
                    <div class="kpi-icon bg-pink-100 text-pink-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

            </div>
            <br>
            <br>
            {{-- Kalender --}}
            <div class="card bg-base-100 shadow-xl rounded-[15px] mb-6">
                <div class="card-body">
                    <h1 class="card-title text-center"><strong>Kalender Jadwal Rilis Publikasi ({{ $currentYear }})</strong></h1>
                    <div id="calendar"></div>
                </div>
            </div>


            {{-- CHARTS --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

                <div class="card bg-base-100 shadow-xl rounded-[15px] lg:col-span-3">
                    <div class="card-body">
                        <h2 class="card-title">Publikasi Tahun {{ $currentYear }} (Per Bulan Rilis)</h2>
                        <div id="bar-chart-monthly"></div>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-xl rounded-[15px]">
                    <div class="card-body">
                        <h2 class="card-title">Publikasi Menurut Status (SPRP)</h2>
                        <div id="donut-chart-status"></div>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-xl rounded-[15px]">
                    <div class="card-body">
                        <h2 class="card-title">Publikasi Menurut Kategori (SPRP)</h2>
                        <div id="donut-chart-kategori"></div>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-xl rounded-[15px]">
                    <div class="card-body">
                        <h2 class="card-title">Informasi Publication</h2>
                        <p>Lihat Informasi terkait publikasi dengan penuh menggunakan tombol di bawah ini.</p>
                        <div class="card-actions justify-end">
                            <a href="{{ route('pengajuan_publikasi.index') }}" class="btn btn-primary rounded-[15px] text-white">
                                Detail Publikasi
                            </a>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- Load ApexCharts --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    {{-- Load FullCalendar --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    @push('scripts')
    <script>
        /* ------------------------------------
           FULLCALENDAR
        ------------------------------------ */
        document.addEventListener('DOMContentLoaded', function() {

            var calendarEl = document.getElementById('calendar');

            if (calendarEl) {
                var events = @json($calendarEvents);

                // Pastikan ada event sebelum set initialDate
                var initialDate = events.length > 0 ? events[0].start : new Date().toISOString().split('T')[0];

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    // Ubah initialView menjadi listMonth atau listWeek
                    initialView: 'listMonth', // tampilkan daftar per bulan saat pertama load
                    initialDate: initialDate,

                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,listMonth' // user bisa switch ke bulan / daftar per minggu
                    },

                    events: events,
                    eventDisplay: 'block',
                    height: 650
                });

                calendar.render();
            }

        });


        /* ------------------------------------
           BAR & DONUT CHARTS
        ------------------------------------ */
        document.addEventListener('DOMContentLoaded', function() {

            // Bar Chart
            var barOptions = {
                series: [{
                    name: 'Jumlah Publikasi',
                    data: @json($barChartSeries)
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: true
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: @json($barChartMonths)
                },
                yaxis: {
                    title: {
                        text: 'Jumlah Publikasi'
                    }
                },
                fill: {
                    opacity: 1
                },
            };

            new ApexCharts(document.querySelector("#bar-chart-monthly"), barOptions).render();

            // Donut Status
            new ApexCharts(document.querySelector("#donut-chart-status"), {
                series: @json($donutStatusSeries),
                labels: @json($donutStatusLabels),
                chart: {
                    type: 'donut',
                    height: 350
                },
                legend: {
                    position: 'bottom'
                }
            }).render();

            // Donut Kategori
            new ApexCharts(document.querySelector("#donut-chart-kategori"), {
                series: @json($donutKategoriSeries),
                labels: @json($donutKategoriLabels),
                chart: {
                    type: 'donut',
                    height: 350
                },
                legend: {
                    position: 'bottom'
                }
            }).render();

        });
    </script>
    @endpush

</x-app-layout>