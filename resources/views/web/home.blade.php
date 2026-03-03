@extends('layouts.web')

@section('title', 'Beranda - Repository FH UNJA')

@section('content')
    {{-- Hero Section dengan Vanta Waves --}}
    <header class="bg-unja-hero text-white py-5 py-md-5" id="hero-vanta">
        <div class="container py-md-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <h1 class="display-5 fw-bolder mb-3 text-white">Repositori Karya Ilmiah <br> Fakultas Hukum Universitas
                        Jambi</h1>
                    <p class="lead text-capitalize mb-4 text-white text-opacity-75">
                        Menyediakan akses terbuka terhadap koleksi karya ilmiah di bidang hukum
                    </p>

                    <div class="ranking-box mt-5 mx-auto shadow-lg" style="max-width: 90%;">
                        <h2 class="text-white text-center mb-4">Pencarian Cepat Karya Ilmiah Hukum</h2>
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <form method="GET" action="{{ route('web.browse') }}">
                                    <div class="input-group input-group-lg shadow-sm">
                                        <input type="text" name="q" class="form-control border-0" id="searchInput"
                                            placeholder="Cari Judul, Penulis, atau Kata Kunci..."
                                            aria-label="Cari Repository">
                                        <button class="btn btn-warning text-white px-4" type="submit" id="searchButton">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                                <small class="form-text text-white text-center d-block mt-3">
                                    Gunakan pencarian cepat di atas, atau <a href="{{ route('web.browse') }}"
                                        class="text-white fw-bold">Pencarian Lanjutan</a> untuk filter lengkap.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="container my-5">
        <div class="row g-4">
            {{-- Bagian Kiri: List Karya Ilmiah --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h3 class="text-unja border-unja ps-3 mb-0 fw-bold">Karya Ilmiah Terbaru</h3>
                            <a href="{{ route('web.browse') }}" class="text-unja text-decoration-none fw-medium">Lihat Semua
                                <i class="fas fa-arrow-right small ms-1"></i></a>
                        </div>
                        <hr class="text-muted opacity-25">

                        <div class="list-group list-group-flush">
                            @forelse($latestArticles as $article)
                                <a href="{{ route('web.article.show', $article->id) }}"
                                    class="list-group-item list-group-item-action d-flex align-items-start justify-content-between py-3 px-0 border-bottom">
                                    <div class="pe-3">
                                        <div class="mb-1 text-unja fw-bold" style="font-size: 1.1rem; line-height: 1.4;">
                                            {{ $article->title }}</div>
                                        <div class="mb-1 small text-dark fw-medium">{{ $article->author }}
                                            ({{ $article->created_at->format('Y') }})
                                        </div>
                                        <div class="text-muted small">
                                            <i class="bi bi-tag-fill me-1"></i> {{ ucfirst($article->document_type) }}
                                            <span class="mx-2">|</span>
                                            <i class="bi bi-bookmarks-fill me-1"></i> {{ $article->field ?? 'Hukum' }}
                                        </div>
                                    </div>
                                    @php
                                        $badgeClass = match (strtolower($article->document_type)) {
                                            'skripsi' => 'bg-info',
                                            'jurnal' => 'bg-warning',
                                            'tesis' => 'bg-danger',
                                            default => 'bg-primary',
                                        };
                                    @endphp
                                    <span
                                        class="badge {{ $badgeClass }} rounded-pill px-3">{{ strtoupper(substr($article->document_type, 0, 1)) }}</span>
                                </a>
                            @empty
                                <div class="py-5 text-center">
                                    <img src="{{ asset('assets/img/empty-data.svg') }}" alt="Empty" width="100"
                                        class="mb-3 opacity-50">
                                    <p class="text-muted italic">Belum ada karya ilmiah yang dipublikasikan.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian Kanan: Akses Cepat --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h3 class="text-unja border-unja ps-3 mb-4 fw-bold">Akses Cepat</h3>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('web.browse') }}"
                                class="py-3 list-group-item list-group-item-action border-0 px-0">
                                <i class="fas fa-search-plus me-3 text-unja"></i> Pencarian Lanjutan
                            </a>
                            <a href="#" class="py-3 list-group-item list-group-item-action border-0 px-0">
                                <i class="fas fa-file-invoice me-3 text-unja"></i> Tata Cara Penggunaan
                            </a>
                            <a href="https://online-journal.unja.ac.id/" target="_blank"
                                class="py-3 list-group-item list-group-item-action border-0 px-0">
                                <i class="fas fa-external-link-alt me-3 text-unja"></i> Jurnal Online UNJA
                            </a>
                            <a href="https://unja.ac.id/" target="_blank"
                                class="py-3 list-group-item list-group-item-action border-0 px-0">
                                <i class="fas fa-university me-3 text-unja"></i> Situs Web UNJA
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Login Banner Mini --}}
                {{-- Login Banner Mini --}}
                <div class="card bg-unja-hero text-white border-0 rounded-4 overflow-hidden shadow-sm">
                    <div class="card-body p-4 text-center">
                        @guest
                            {{-- Tampilan jika belum login --}}
                            <h5 class="fw-bold mb-2">Kontribusi Karya?</h5>
                            <p class="small text-white text-opacity-75 mb-3"> Silakan masuk menggunakan akun NIM/NIDN untuk
                                mengunggah karya ilmiah Anda.</p>
                            <a href="{{ route('login') }}"
                                class="btn btn-warning text-white btn-sm px-4 fw-bold rounded-pill">LOGIN SEKARANG</a>
                        @endguest

                        @auth
                            {{-- Tampilan jika sudah login --}}
                            <h5 class="fw-bold mb-2">Halo, {{ Auth::user()->name }}!</h5>
                            <p class="small text-white text-opacity-75 mb-3">Anda telah masuk ke sistem. Klik tombol di bawah
                                untuk mengelola karya ilmiah Anda.</p>

                            {{-- Sesuaikan route dashboard berdasarkan role --}}
                            @if (Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin')
                                <a href="{{ route('admin.dashboard') }}"
                                    class="btn btn-warning text-white btn-sm px-4 fw-bold rounded-pill">KE DASHBOARD ADMIN</a>
                            @else
                                <a href="{{ route('user.dashboard') }}"
                                    class="btn btn-warning text-white btn-sm px-4 fw-bold rounded-pill">KE DASHBOARD SAYA</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik Section --}}
        <div class="row ranking-box py-5 rounded-4 mt-5 mx-0 shadow">
            <h3 class="text-center text-white fw-bold mb-2">Statistik dalam Data</h3>
            <p class="text-center mb-5 text-white text-opacity-75">Rangkuman data repositori digital FH UNJA</p>

            <div class="row justify-content-center g-4">
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 rounded-4 border-0 shadow-sm">
                        <div class="card-body text-center py-4">
                            <div class="text-muted small text-uppercase fw-bold mb-2">Koleksi Dokumen</div>
                            <i class="text-unja bi bi-collection mb-3 d-block" style="font-size: 2.5rem;"></i>
                            <div class="fs-3 fw-bold text-dark">{{ number_format($stats['total_koleksi'], 0, ',', '.') }}
                            </div>
                            <small class="text-muted">Karya ilmiah terpublikasi</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 rounded-4 border-0 shadow-sm">
                        <div class="card-body text-center py-4">
                            <div class="text-muted small text-uppercase fw-bold mb-2">Total Unduhan</div>
                            <i class="text-unja bi bi-cloud-download mb-3 d-block" style="font-size: 2.5rem;"></i>
                            <div class="fs-3 fw-bold text-dark">{{ number_format($stats['total_unduhan'], 0, ',', '.') }}
                            </div>
                            <small class="text-muted">Dokumen telah diakses</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 rounded-4 border-0 shadow-sm">
                        <div class="card-body text-center py-4">
                            <div class="text-muted small text-uppercase fw-bold mb-2">Pengunjung</div>
                            <i class="text-unja bi bi-person-check mb-3 d-block" style="font-size: 2.5rem;"></i>
                            <div class="fs-3 fw-bold text-dark">
                                {{ number_format($stats['total_pengunjung'], 0, ',', '.') }}</div>
                            <small class="text-muted">Civitas yang mengakses</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('extra-js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Inisialisasi Vanta Waves pada ID hero-vanta
            VANTA.WAVES({
                el: "#hero-vanta",
                mouseControls: true,
                touchControls: true,
                gyroControls: false,
                minHeight: 200.00,
                minWidth: 200.00,
                scale: 1.00,
                scaleMobile: 0.7,
                color: 0x690b0b, // Warna Merah UNJA
                shininess: 26.00,
                waveHeight: 30.50,
                waveSpeed: 0.30,
                zoom: 1.08
            });
        });
    </script>
@endsection
