@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow rounded-4 border-0">
            <div class="card-body p-4">
                <div class="row align-items-center g-4 mb-4">
                    <div class="col-6">
                        <h4 class="fw-bold"><i class="fas fa-file-alt text-primary me-2"></i>Detail Karya Ilmiah</h4>
                    </div>
                    <div class="col-6 text-end">
                        <a href="{{ route('admin.repository.index') }}" class="btn btn-secondary shadow-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Koleksi
                        </a>
                    </div>
                </div>

                <div class="row">
                    {{-- Kolom Kiri: Detail Dokumen --}}
                    <div class="col-md-8">
                        {{-- Status Banner --}}
                        @php
                            $statusClass = match ($article->status) {
                                'published' => 'alert-success',
                                'revision' => 'alert-warning',
                                'rejected' => 'alert-danger',
                                default => 'alert-info',
                            };
                            $statusLabel = match ($article->status) {
                                'published' => 'Disetujui & Dipublikasikan',
                                'revision' => 'Perlu Revisi',
                                'rejected' => 'Ditolak',
                                default => 'Menunggu Verifikasi',
                            };
                        @endphp
                        <div class="alert {{ $statusClass }} d-flex align-items-center mb-4 border-0 shadow-sm">
                            <i class="fas fa-info-circle me-3 fa-lg"></i>
                            <div>
                                <strong>Status Saat Ini:</strong> {{ $statusLabel }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Karya Ilmiah</label>
                            <div class="p-3 border rounded bg-light fw-bold text-dark">
                                {{ $article->title }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Abstrak</label>
                            <div class="p-3 border rounded bg-white text-muted"
                                style="text-align: justify; line-height: 1.6;">
                                {{ $article->abstract }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">File PDF Utama</label>
                                <div class="d-flex align-items-center p-2 border rounded">
                                    <i class="fa-regular fa-file-pdf fa-2x text-danger me-2"></i>
                                    <a href="{{ asset('storage/' . $article->file_utama) }}" target="_blank"
                                        class="text-decoration-none small fw-bold">
                                        Buka Dokumen Utama
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Halaman Sampul</label>
                                <div class="d-flex align-items-center p-2 border rounded">
                                    <i class="fa-regular fa-image fa-2x text-primary me-2"></i>
                                    <a href="{{ asset('storage/' . $article->file_sampul) }}" target="_blank"
                                        class="text-decoration-none small fw-bold">
                                        Lihat Sampul
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Jika ada catatan perbaikan, tampilkan di sini --}}
                        @if ($article->catatan_revisi)
                            <div class="mt-4">
                                <label class="form-label fw-bold text-danger">Riwayat Catatan / Alasan Perbaikan:</label>
                                <div class="p-3 border border-danger rounded bg-light text-danger">
                                    <i class="fas fa-comment-dots me-2"></i>{{ $article->catatan_revisi }}
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Kolom Kanan: Metadata --}}
                    <div class="col-md-4">
                        <div class="p-4 border rounded bg-light shadow-sm">
                            <h6 class="fw-bold border-bottom pb-2 mb-3">Informasi Metadata</h6>

                            <div class="mb-3">
                                <small class="text-muted d-block small-label">PENULIS</small>
                                <span class="fw-bold text-dark">{{ $article->author }}</span>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted d-block small-label">PROGRAM STUDI</small>
                                <span class="badge bg-primary px-3">{{ $article->study_program }}</span>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block small-label">TAHUN</small>
                                    <span class="fw-bold">{{ $article->year }}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block small-label">JENIS</small>
                                    <span class="fw-bold">{{ $article->document_type }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted d-block small-label">AKSES</small>
                                <span
                                    class="badge border border-success text-success px-2">{{ $article->access_type }}</span>
                            </div>

                            <hr>

                            <div class="mb-2 small">
                                <span class="text-muted">Diupload pada:</span><br>
                                <i class="far fa-calendar-alt me-1"></i> {{ $article->created_at->format('d M Y, H:i') }}
                            </div>

                            @if ($article->verified_by)
                                <div class="mb-2 small">
                                    <span class="text-muted">Diverifikasi oleh:</span><br>
                                    <i class="fas fa-user-check me-1"></i> {{ $article->validator->name ?? 'Admin' }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .small-label {
            font-size: 0.7rem;
            letter-spacing: 1px;
            font-weight: 700;
            margin-bottom: 2px;
        }
    </style>
@endsection
