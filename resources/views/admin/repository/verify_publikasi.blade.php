@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow rounded-4 border-0">
            <div class="card-body p-4">
                <div class="row align-items-center g-4 mb-4">
                    <div class="col-6">
                        <h4 class="fw-bold"><i class="fas fa-globe text-primary me-2"></i>Form Publikasi Karya Ilmiah</h4>
                    </div>
                    <div class="col-6 text-end">
                        <a href="{{ route('admin.repository.publikasi') }}" class="btn btn-light border">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        {{-- Form Aksi Publikasikan --}}
                        <form action="{{ route('admin.repository.konfirmasiPublikasi', $article->id) }}" method="POST"
                            id="formPublikasi">
                            @csrf
                            <div class="row">
                                {{-- Kolom Kiri: Detail Dokumen (Sama persis seperti verify kamu) --}}
                                <div class="col-md-8">
                                    {{-- Dokumen Utama --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">File PDF Utama</label>
                                        <div class="d-flex align-items-center p-3 border rounded bg-light">
                                            <i class="fa-regular fa-file-pdf fa-2x text-danger me-3"></i>
                                            <div class="flex-grow-1">
                                                <div class="small text-muted">Nama File:</div>
                                                <a href="{{ asset('storage/' . $article->pdf_file) }}" target="_blank"
                                                    class="fw-bold text-decoration-none">
                                                    {{ basename($article->pdf_file) }}
                                                </a>
                                            </div>
                                            <a href="{{ asset('storage/' . $article->pdf_file) }}" target="_blank"
                                                class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-external-link-alt"></i> Buka File
                                            </a>
                                        </div>
                                    </div>

                                    {{-- Halaman Sampul --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Halaman Sampul (Cover)</label>
                                        <div class="d-flex align-items-center p-3 border rounded bg-light">
                                            <i class="fa-regular fa-image fa-2x text-primary me-3"></i>
                                            <div class="flex-grow-1">
                                                <div class="small text-muted">Nama File:</div>
                                                <a href="{{ asset('storage/' . $article->cover_image) }}" target="_blank"
                                                    class="fw-bold text-decoration-none">
                                                    {{ basename($article->cover_image) }}
                                                </a>
                                            </div>
                                            <a href="{{ asset('storage/' . $article->cover_image) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Judul Karya Ilmiah</label>
                                        <textarea class="form-control bg-white" rows="2" readonly>{{ $article->title }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Abstrak</label>
                                        <textarea class="form-control bg-white" rows="6" readonly>{{ $article->abstract }}</textarea>
                                    </div>
                                </div>

                                {{-- Kolom Kanan: Metadata & Tombol Eksekusi Final --}}
                                <div class="col-md-4">
                                    <div class="p-3 border rounded bg-light mb-3">
                                        <h6 class="fw-bold border-bottom pb-2 mb-3">Informasi Penulis</h6>
                                        <div class="mb-2">
                                            <small class="text-muted d-block">Nama Penulis:</small>
                                            <span class="fw-bold">{{ $article->author }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted d-block">Program Studi:</small>
                                            <span class="badge bg-secondary">{{ $article->study_program }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted d-block">Tahun / Jenis:</small>
                                            <span>{{ $article->year }} / {{ $article->document_type }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted d-block">Akses Dokumen:</small>
                                            <span class="text-success fw-bold"><i
                                                    class="fas fa-lock-open me-1"></i>{{ $article->access_type }}</span>
                                        </div>
                                    </div>

                                    {{-- PANEL UTAMA PUBLIKASI --}}
                                    <div class="p-3 border border-primary rounded bg-white mb-3">
                                        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Panel Publikasi Resmi</h6>

                                        <div class="alert alert-warning small py-2">
                                            <i class="fas fa-exclamation-triangle me-1"></i> Menekan tombol di bawah akan
                                            merilis dokumen ini ke publik sehingga dapat dicari di halaman utama repositori
                                            kampus.
                                        </div>

                                        {{-- Tombol dengan SweetAlert Trigger --}}
                                        <button type="button" onclick="confirmPublish()"
                                            class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                                            <i class="fas fa-globe me-2"></i>Publikasikan Karya Ilmiah
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SweetAlert2 JS untuk Konfirmasi Interaktif --}}
    @push('scripts')
        <script>
            function confirmPublish() {
                Swal.fire({
                    title: 'Rilis & Publikasikan?',
                    text: "Dokumen resmi diterbitkan. Sistem otomatis mengirimkan notifikasi sukses ke akun pengunggah karya ilmiah ini.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0d6efd', // Biru Primary
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Rilis Sekarang!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Sedang Mempublikasikan...',
                            html: 'Mohon tunggu, sistem sedang memperbarui status repositori.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        });
                        // Submit form asli
                        document.getElementById('formPublikasi').submit();
                    }
                });
            }
        </script>
    @endpush
@endsection
