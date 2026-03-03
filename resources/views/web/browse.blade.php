@extends('layouts.web')

@section('title', 'Pencarian Lanjutan - Repository FH UNJA')

@section('extra-css')
    <style>
        /* CSS Khusus agar gaya di HTML asli tetap terjaga */
        .card-search {
            border: 1px solid var(--bs-unja-secondary);
        }

        .header-unja {
            position: relative;
            width: 100%;
            min-height: 350px;
            display: flex;
            align-items: center;
            overflow: hidden;
            color: white;
        }

        .layer-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('assets/img/bg1.jpg') }}');
            background-size: cover;
            background-position: center;
            z-index: 1;
            opacity: 0.4;
        }

        .layer-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(102, 0, 0, 0.9) 0%, rgba(153, 0, 0, 0.8) 50%, rgba(204, 51, 51, 0.6) 100%) !important;
            z-index: 2;
        }

        .layer-content {
            position: relative;
            z-index: 3;
        }

        .page-link.active {
            background-color: var(--bs-unja-primary) !important;
            border-color: var(--bs-unja-primary) !important;
        }
    </style>
@endsection

@section('content')
    {{-- Header Section --}}
    <header class="header-unja">
        <div class="layer-bg"></div>
        <div class="layer-overlay"></div>
        <div class="container layer-content">
            <h1 class="display-6 fw-bold text-white">
                <i class="fas fa-search me-2"></i> Pencarian Lanjutan Karya Ilmiah
            </h1>
            <p class="text-white text-opacity-75">Telusuri dan temukan ribuan koleksi Skripsi, Tesis, dan Jurnal Hukum</p>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="container my-5">
        <div class="row">
            {{-- Filter Sidebar --}}
            <div class="col-lg-4 mb-4">
                <div class="card card-search shadow-sm">
                    <div class="card-header py-3 bg-unja-primary text-white fw-bold"
                        style="background-color: var(--bs-unja-primary) !important;">
                        <i class="fas fa-filter me-1"></i> Filter Pencarian
                    </div>
                    <div class="card-body">
                        <form action="{{ route('web.browse') }}" method="GET">
                            <div class="mb-4">
                                <label for="subjects" class="form-label fw-medium text-capitalize">
                                    <i class="fas fa-scroll text-unja me-1"></i> Program Studi
                                </label>
                                <select name="field" class="form-select" id="subjects">
                                    <option value="">Pilih</option>
                                    @foreach (['Hukum Pidana', 'Hukum Perdata', 'Hukum Bisnis', 'Hukum Tatanegara', 'Hukum Administrasi Negara', 'Hukum Internasional'] as $field)
                                        <option value="{{ $field }}"
                                            {{ request('field') == $field ? 'selected' : '' }}>{{ $field }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="document_type" class="form-label fw-medium">
                                    <i class="fas fa-file-alt text-unja me-1"></i> Jenis Dokumen
                                </label>
                                <select name="category" class="form-select" id="document_type">
                                    <option value="">Pilih</option>
                                    @foreach (['Skripsi', 'Tesis', 'Disertasi', 'Jurnal', 'Laporan Magang'] as $cat)
                                        <option value="{{ $cat }}"
                                            {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="date" class="form-label fw-medium">
                                    <i class="far fa-calendar-alt text-unja me-1"></i> Tahun Publikasi (YYYY)
                                </label>
                                <input type="number" name="year" class="form-control" id="date"
                                    placeholder="Contoh: 2024" value="{{ request('year') }}">
                            </div>

                            <div class="mb-4">
                                <label for="keywords" class="form-label fw-medium">
                                    <i class="fas fa-tags text-unja me-1"></i> Judul / Kata Kunci
                                </label>
                                <input type="text" name="q" class="form-control" id="subject"
                                    placeholder="Masukan kata kunci judul" value="{{ request('q') }}">
                            </div>

                            {{-- Tambahan di dalam <form action="{{ route('web.browse') }}" method="GET"> --}}

                            <div class="mb-4">
                                <label for="user_type" class="form-label fw-medium">
                                    <i class="fas fa-user-graduate text-unja me-1"></i> Kategori Penulis
                                </label>
                                <select name="user_type" class="form-select" id="user_type">
                                    <option value="">Semua Kategori</option>
                                    <option value="Dosen" {{ request('user_type') == 'Dosen' ? 'selected' : '' }}>Dosen
                                    </option>
                                    <option value="Mahasiswa" {{ request('user_type') == 'Mahasiswa' ? 'selected' : '' }}>
                                        Mahasiswa</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-info-circle text-unja me-1"></i> Status Dokumen
                                </label>
                                <div class="d-flex gap-3 mt-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="pub"
                                            value="published"
                                            {{ request('status', 'published') == 'published' ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="pub">Published</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="unpub"
                                            value="unpublished" {{ request('status') == 'unpublished' ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="unpub">Unpublished</label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-unja-primary text-white w-100 fw-bold py-2 mb-2">
                                <i class="fas fa-search me-2"></i> CARI REPOSITORY
                            </button>

                            <div class="text-center small">
                                <a href="{{ route('web.browse') }}" class="btn btn-outline-danger w-100">Reset Filter</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Hasil Pencarian --}}
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="text-unja border-unja ps-3">
                                <h3>Hasil Pencarian</h3>
                                <small>(Ditemukan {{ $results->total() }} Dokumen)</small>
                            </div>
                            <div id="btn-sort"
                                class="rounded-circle d-flex align-items-center justify-content-center bg-light border"
                                style="width: 40px; height: 40px; cursor: pointer;">
                                <i id="sort-icon" class="fa fa-arrow-down-a-z"></i>
                            </div>
                        </div>
                        <hr>

                        <div class="list-group list-group-flush">
                            @forelse($results as $article)
                                <a href="{{ route('web.article.show', $article->id) }}"
                                    class="list-group-item d-flex align-items-start justify-content-between py-3">
                                    <div>
                                        <div class="mb-1 text-unja fw-bold">{{ $article->title }}</div>
                                        <div class="mb-1 small text-dark">{{ $article->author }}
                                            ({{ $article->created_at->format('Y') }})
                                        </div>
                                        <div class="text-muted small">{{ ucfirst($article->document_type) }} |
                                            {{ $article->study_program ?? 'Hukum' }}</div>
                                    </div>
                                    @php
                                        $initial = strtoupper(substr($article->category, 0, 1));
                                        $badgeColor = match ($initial) {
                                            'T' => 'bg-danger',
                                            'J' => 'bg-warning',
                                            'S' => 'bg-info',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <small class="badge {{ $badgeColor }} rounded-pill">{{ $initial }}</small>
                                </a>
                            @empty
                                <div class="text-center py-5">
                                    <p class="text-muted">Tidak ada dokumen yang ditemukan dengan kriteria tersebut.</p>
                                </div>
                            @endforelse
                        </div>

                        <hr>
                        {{-- Pagination --}}
                        <div class="d-flex justify-content-center mt-4">
                            {{ $results->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('extra-js')
    <script type="text/javascript">
        const sortBtn = document.getElementById('btn-sort');
        const sortIcon = document.getElementById('sort-icon');

        sortBtn.addEventListener('click', function() {
            if (sortIcon.classList.contains('fa-arrow-down-a-z')) {
                sortIcon.className = 'fa fa-arrow-up-z-a';
            } else {
                sortIcon.className = 'fa fa-arrow-down-a-z';
            }
        });
    </script>
@endsection
