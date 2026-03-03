@extends('layouts.admin')

@section('content')
    <style>
        .filter-btn {
            transition: all 0.3s ease;
        }

        .filter-btn.active {
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card stat-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Total Karya</small>
                        <h4 class="fw-bold mb-0">{{ number_format($stats['total_karya'], 0, ',', '.') }}</h4>
                    </div>
                    <i class="fa fa-book text-danger fs-4"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Pengguna</small>
                        <h4 class="fw-bold mb-0">{{ number_format($stats['total_user'], 0, ',', '.') }}</h4>
                    </div>
                    <i class="fa fa-users text-primary fs-4"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Total Unduhan</small>
                        <h4 class="fw-bold mb-0">{{ number_format($stats['total_download'], 0, ',', '.') }}</h4>
                    </div>
                    <i class="fa fa-download text-success fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card stat-card rounded-4 h-100">
                <div class="card-body p-4 text-center">
                    <div class="text-start mb-4">
                        <h6 class="fw-bold mb-1">Statistik Dokumen</h6>
                        <p class="text-muted small mb-0">Perbandingan jenis dokumen</p>
                    </div>
                    {{-- Tempat Render Chart Donut --}}
                    <div id="chart-dokumen"></div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card stat-card rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                        <div>
                            <h6 class="fw-bold mb-1">Statistik Unduhan</h6>
                            <p class="text-muted small mb-0">Tren akses dokumen harian</p>
                        </div>
                        <div class="bg-light p-1 rounded-3">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary filter-btn active"
                                    data-range="hari">Hari</button>
                                <button class="btn btn-sm btn-outline-primary filter-btn" data-range="bulan">Bulan</button>
                                <button class="btn btn-sm btn-outline-primary filter-btn" data-range="tahun">Tahun</button>

                                {{-- <button class="btn btn-sm btn-white shadow-sm px-3 active-filter"
                                    data-range="hari">Hari</button>
                                <button class="btn btn-sm text-muted px-3" data-range="bulan">Bulan</button>
                                <button class="btn btn-sm text-muted px-3" data-range="tahun">Tahun</button> --}}

                            </div>
                        </div>
                    </div>
                    {{-- Tempat Render Chart Multi Smooth --}}
                    <div id="chart-multi-smooth"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card stat-card border-0 rounded-4 p-4 shadow-sm">
        <h5 class="fw-bold mb-4">Publikasi Terbaru</h5>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Judul Karya Ilmiah</th>
                        <th>Penulis</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aktivitas as $item)
                        <tr>
                            <td class="fw-semibold">{{ Str::limit($item->title, 50) }}</td>
                            <td>{{ $item->author }}</td>
                            <td>{{ $item->created_at->format('d M Y') }}</td>
                            <td>
                                @if ($item->status == 'published')
                                    <span class="badge bg-success">Published</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Belum ada aktivitas terbaru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .active-filter {
            background: white !important;
            font-weight: bold;
            color: var(--unja-primary) !important;
        }

        .btn-sm {
            transition: all 0.3s ease;
            border: none;
            font-size: 0.85rem;
        }
    </style>
@endsection

@push('scripts')
    {{-- Load Library ApexCharts --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        $(document).ready(function() {
            // 1. Logic Chart Donut
            var optionsDonut = {
                series: [
                    {{ $chartData['Skripsi'] ?? 0 }},
                    {{ $chartData['Tesis'] ?? 0 }},
                    {{ $chartData['Disertasi'] ?? 0 }},
                    {{ $chartData['Jurnal'] ?? 0 }},
                    {{ $chartData['Laporan'] ?? 0 }}
                ],
                chart: {
                    type: 'donut',
                    height: 350
                },
                labels: ['Skripsi', 'Tesis', 'Disertasi', 'Jurnal', 'Laporan Magang'],
                colors: ['#8B0000', '#FF8C00', '#4361ee', '#ffcc00', '#1a237e'],
                legend: {
                    position: 'bottom'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    formatter: function(w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                    }
                                }
                            }
                        }
                    }
                }
            };
            new ApexCharts(document.querySelector("#chart-dokumen"), optionsDonut).render();

            // 2. Logic Chart Multi Area
            var optionsMulti = {
                series: [{
                        name: 'Skripsi',
                        data: @json($dataSeries['Skripsi'])
                    },
                    {
                        name: 'Tesis',
                        data: @json($dataSeries['Tesis'])
                    },
                    {
                        name: 'Disertasi',
                        data: @json($dataSeries['Disertasi'])
                    },
                    {
                        name: 'Jurnal',
                        data: @json($dataSeries['Jurnal'])
                    },
                    {
                        name: 'Laporan Magang',
                        data: @json($dataSeries['Laporan Magang'])
                    }
                ],
                chart: {
                    height: 380,
                    type: 'area',
                    toolbar: {
                        show: false
                    },
                    fontFamily: 'inherit'
                },
                colors: ['#8B0000', '#FF8C00', '#06d6a0', '#4361ee', '#7209b7'],
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                xaxis: {
                    categories: @json($days),
                },
                tooltip: {
                    y: {
                        formatter: (val) => val + " Unggahan"
                    }
                }
            };

            // 3. INISIALISASI: Simpan ke variabel global chartArea
            // JANGAN gunakan 'var' atau 'let' lagi di depan chartArea di sini
            chartArea = new ApexCharts(document.querySelector("#chart-multi-smooth"), optionsMulti);
            chartArea.render();
        });

        $('.filter-btn').on('click', function() {
            // 1. Reset semua tombol: hilangkan class 'active' dan 'btn-primary', kembalikan ke 'btn-outline-primary'
            $('.filter-btn').removeClass('active btn-primary').addClass('btn-outline-primary');

            // 2. Set tombol yang diklik: tambahkan class 'active' dan 'btn-primary', hilangkan 'btn-outline-primary'
            $(this).addClass('active btn-primary').removeClass('btn-outline-primary');

            let range = $(this).data('range');

            // 3. Jalankan AJAX (Kode yang sudah kita buat sebelumnya)
            $.ajax({
                url: "{{ url('admin/chart-data') }}/" + range,
                method: 'GET',
                success: function(response) {
                    if (typeof chartArea !== 'undefined') {
                        chartArea.updateOptions({
                            xaxis: {
                                categories: response.categories
                            }
                        });
                        chartArea.updateSeries(response.series);
                    }
                },
                error: function() {
                    console.error("Gagal mengambil data statistik.");
                }
            });
        });
    </script>
@endpush
