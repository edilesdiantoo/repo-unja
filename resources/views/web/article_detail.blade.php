@extends('layouts.web')

@section('title', $article->title . ' - Repository FH UNJA')

@section('extra-css')
    <style>
        .badge-type {
            background-color: var(--bs-unja-secondary);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .metadata-label {
            font-weight: bold;
            color: #555;
            width: 150px;
            display: inline-block;
        }

        .header-unja {
            position: relative;
            width: 100%;
            min-height: 300px;
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
    </style>
@endsection

@section('content')
    <header class="header-unja">
        <div class="layer-bg"></div>
        <div class="layer-overlay"></div>
        <div class="container layer-content">
            <h1 class="display-6 fw-bold text-white">
                <i class="fas fa-info-circle me-2"></i> Detail Karya Ilmiah
            </h1>
            <p class="text-white text-opacity-75">Detail informasi karya ilmiah secara mendalam</p>
        </div>
    </header>

    <main class="container my-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-unja text-decoration-none">Beranda</a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('web.browse') }}"
                        class="text-unja text-decoration-none">Koleksi</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Karya</li>
            </ol>
        </nav>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4 rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <span
                            class="badge-type mb-3 d-inline-block">{{ ucfirst($article->category ?? $article->document_type) }}</span>
                        <h2 class="fw-bold text-unja mb-3" style="line-height: 1.4;">{{ $article->title }}</h2>

                        <div class="d-flex flex-wrap align-items-center mb-4 text-muted small">
                            <span class="me-3 mb-2">
                                <i class="fas fa-user me-1 text-unja"></i> {{ $article->author }}
                                @if (optional($article->user)->identity_number)
                                    <span class="badge bg-light text-secondary border px-2 py-0.5 ms-1"
                                        style="font-size: 0.75rem;">
                                        <i class="far fa-id-card me-1 text-danger"></i>
                                        {{ $article->user->identity_number }}
                                    </span>
                                @endif
                            </span>
                            <span class="me-3 mb-2"><i class="fas fa-calendar-alt me-1 text-unja"></i>
                                {{ $article->created_at->translatedFormat('d F Y') }}</span>
                            <span class="mb-2"><i class="fas fa-eye me-1 text-unja"></i>
                                {{ number_format($article->views ?? 0) }} Dilihat</span>
                        </div>

                        <hr class="opacity-25">

                        <h5 class="fw-bold mb-3"><i class="fas fa-align-left me-2 text-unja"></i>Abstrak</h5>
                        <p class="text-secondary" style="text-align: justify; line-height: 1.8;">
                            {{ $article->abstract ?? 'Abstrak tidak tersedia untuk dokumen ini.' }}
                        </p>

                        <div class="mt-3">
                            <h5>Kata Kunci</h5>
                            @foreach (explode(',', $article->keywords) as $kw)
                                <a href="{{ route('web.browse', ['keyword' => trim($kw)]) }}"
                                    class="btn btn-outline-secondary btn-sm me-1 mb-1">
                                    {{ trim($kw) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white fw-bold py-3">Informasi Detail</div>
                    <div class="card-body p-4">
                        {{-- Penulis & NIM/NIDN Detail --}}
                        <div class="mb-2">
                            <span class="metadata-label">Penulis</span>:
                            <strong>{{ $article->author }}</strong>
                            @if (optional($article->user)->identity_number)
                                <span class="badge bg-light text-secondary border px-2 py-0.5 ms-2"
                                    style="font-size: 0.75rem;">
                                    <i class="far fa-id-card me-1 text-danger"></i> {{ $article->user->identity_number }}
                                </span>
                            @endif
                        </div>

                        <div class="mb-2">
                            <span class="metadata-label">Pembimbing 1</span>:
                            @if ($article->pembimbing_1)
                                <a href="{{ route('web.browse', ['dosen' => $article->pembimbing_1]) }}"
                                    class="text-decoration-none fw-bold text-primary">
                                    {{ $article->pembimbing_1 }}
                                </a>
                            @else
                                -
                            @endif
                        </div>

                        <div class="mb-2">
                            <span class="metadata-label">Pembimbing 2</span>:
                            @if ($article->pembimbing_2)
                                <a href="{{ route('web.browse', ['dosen' => $article->pembimbing_2]) }}"
                                    class="text-decoration-none fw-bold text-primary">
                                    {{ $article->pembimbing_2 }}
                                </a>
                            @else
                                -
                            @endif
                        </div>

                        <div class="mb-2"><span class="metadata-label">Fakultas</span>: Hukum</div>

                        <div class="mb-2">
                            <span class="metadata-label">Program Studi</span>:
                            <a href="{{ route('web.browse', ['field' => $article->study_program ?? 'Ilmu Hukum']) }}"
                                class="text-decoration-none fw-bold text-danger">
                                {{ $article->study_program ?? 'Ilmu Hukum' }}
                            </a>
                        </div>

                        <div class="mb-2"><span class="metadata-label">Penerbit</span>: Universitas Jambi</div>
                        <div class="mb-2"><span class="metadata-label">Bahasa</span>: Indonesia</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                    <img src="{{ $article->cover_image && Storage::disk('public')->exists($article->cover_image)
                        ? asset('storage/' . $article->cover_image)
                        : asset('assets/img/default-cover.jpg') }}"
                        class="w-100 shadow-sm rounded" style="object-fit: cover; height: 400px;"
                        alt="Cover {{ $article->title }}">
                </div>

                <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">
                    <div class="card-header bg-unja-hero text-white fw-bold py-3">
                        <i class="fas fa-download me-2"></i> Berkas File
                    </div>
                    <div class="card-body p-4">
                        <div class="list-group list-group-flush">
                            <div
                                class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                                <div class="text-truncate me-2">
                                    <i class="far fa-file-pdf text-danger me-2 fa-lg"></i>
                                    <span class="small fw-bold">Full Text.pdf</span>
                                </div>

                                @auth
                                    <a href="{{ route('web.article.view', $article->id) }}" target="_blank"
                                        class="btn btn-sm btn-unja-primary rounded-pill px-3">
                                        <i class="fas fa-eye me-1"></i> Lihat
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-sm btn-secondary rounded-pill px-3">
                                        <i class="fas fa-lock me-1"></i> Login untuk Lihat
                                    </a>
                                @endauth
                            </div>
                        </div>

                        @guest
                            <div class="mt-3 p-3 bg-light rounded-3 small text-muted border-start border-warning border-4">
                                <i class="fas fa-info-circle me-1 text-warning"></i> File <b>Full Text</b> hanya dapat diakses
                                oleh civitas akademika FH UNJA melalui login.
                            </div>
                            <a href="{{ route('login') }}" class="btn btn-outline-danger btn-sm w-100 mt-3 rounded-pill">Login
                                untuk Mengunduh</a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
