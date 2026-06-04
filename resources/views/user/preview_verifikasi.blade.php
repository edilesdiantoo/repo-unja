@extends('layouts.user')

@section('title', 'Pratinjau Pengajuan Verifikasi')

@section('content')
    <div class="container-fluid">
        <div class="card shadow rounded-4 border-0">
            <div class="card-body p-4">
                <div class="row align-items-center g-4 mb-4">
                    <div class="col-6">
                        <h4 class="fw-bold text-dark"><i class="fas fa-file-signature text-danger me-2"></i>Pratinjau Data
                            Karya Ilmiah</h4>
                    </div>
                    <div class="col-6 text-end">
                        <a href="{{ route('user.article.statusVerifikasi') }}" class="btn btn-light border">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="row">
                    {{-- Kiri: Detail File & Dokumen --}}
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-bold">File PDF Utama</label>
                            <div class="d-flex align-items-center p-3 border rounded bg-light">
                                <i class="fa-regular fa-file-pdf fa-2x text-danger me-3"></i>
                                <div class="flex-grow-1">
                                    <a href="{{ asset('storage/' . $article->pdf_file) }}" target="_blank"
                                        class="fw-bold text-decoration-none">
                                        {{ basename($article->pdf_file) }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        @if ($article->cover_image)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Halaman Sampul (Cover)</label>
                                <div class="d-flex align-items-center p-3 border rounded bg-light">
                                    <i class="fa-regular fa-image fa-2x text-primary me-3"></i>
                                    <div class="flex-grow-1">
                                        <a href="{{ asset('storage/' . $article->cover_image) }}" target="_blank"
                                            class="fw-bold text-decoration-none">
                                            {{ basename($article->cover_image) }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Karya Ilmiah</label>
                            <textarea class="form-control bg-white" rows="2" readonly>{{ $article->title }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Abstrak</label>
                            <textarea class="form-control bg-white" rows="5" readonly>{{ $article->abstract }}</textarea>
                        </div>
                    </div>

                    {{-- Kanan: Informasi Metadata & Tombol Aksi Final --}}
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light mb-4">
                            <h6 class="fw-bold border-bottom pb-2 mb-3">Metadata Dokumen</h6>
                            <div class="mb-2">
                                <small class="text-muted d-block">Penulis:</small>
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
                            @if ($article->accreditation_level)
                                <div class="mb-2">
                                    <small class="text-muted d-block">Akreditasi Jurnal:</small>
                                    <span class="badge bg-info text-dark">{{ $article->accreditation_level }}</span>
                                </div>
                            @endif
                            <div class="mb-2">
                                <small class="text-muted d-block">Ketentuan Akses:</small>
                                <span class="text-success fw-bold">{{ $article->access_type }}</span>
                            </div>
                        </div>

                        {{-- PANEL UTAMA EKSEKUSI MAHASISWA --}}
                        <div class="p-3 border border-danger rounded bg-white text-center shadow-sm">
                            <h6 class="fw-bold text-danger mb-3">Konfirmasi Pengajuan</h6>
                            <p class="text-muted small">Pastikan seluruh data di samping sudah valid sebelum dikirim ke tim
                                verifikator perpustakaan.</p>

                            <div class="d-grid gap-2">
                                {{-- Tombol Kirim Form Utama via SweetAlert --}}
                                <form action="{{ route('user.article.submitVerification', $article->id) }}" method="POST"
                                    id="formFinalSubmit">
                                    @csrf
                                    <button type="button" onclick="executeSubmit()"
                                        class="btn btn-danger w-100 fw-bold py-2">
                                        <i class="fas fa-paper-plane me-2"></i>Ajukan ke Admin
                                    </button>
                                </form>

                                {{-- Tombol Edit Dialihkan Langsung ke Halaman Revisi 1 Halaman Penuh --}}
                                <a href="{{ route('user.article.edit', $article->id) }}"
                                    class="btn btn-outline-secondary fw-bold py-2">
                                    <i class="fas fa-edit me-2"></i>Perbaiki Data (Edit)
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function executeSubmit() {
                Swal.fire({
                    title: 'Kunci & Kirim Berkas?',
                    text: "Setelah diajukan, status draf akan dikunci menjadi 'Menunggu Verifikasi' dan data tidak dapat diubah selama proses pemeriksaan oleh Admin.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#8B0000', // Maroon
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Kirim Sekarang',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Mengirimkan Dokumen...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        });
                        document.getElementById('formFinalSubmit').submit();
                    }
                });
            }
        </script>
    @endpush
@endsection
