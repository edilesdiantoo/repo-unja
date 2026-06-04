@extends('layouts.user')

@section('title', 'Unggah Karya Ilmiah')

@section('content')
    <div class="card shadow rounded-4 border-0">
        <div class="card-body p-4">
            <div class="row align-items-center g-4 mb-4">
                <div class="col-12">
                    <h4 class="fw-bold text-dark">
                        <i class="fas fa-file-upload text-danger me-2"></i> Unggah Karya Ilmiah - Tahap 1 (Berkas)
                    </h4>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    {{-- Route mengarah ke proses penyimpanan berkas sementara --}}
                    <form action="{{ route('user.article.storeStep1') }}" method="POST" enctype="multipart/form-data"
                        id="formUploadStep1">
                        @csrf

                        {{-- File PDF --}}
                        <div class="mb-4">
                            <label for="pdf_file" class="form-label fw-bold">Dokumen (PDF) <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="file" name="pdf_file" id="pdf_file" accept="application/pdf"
                                    class="form-control @error('pdf_file') is-invalid @enderror" required>
                            </div>
                            @error('pdf_file')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <small class="text-muted d-block mt-1">Format file hanya pdf (Maks 20MB)</small>
                        </div>

                        {{-- Sampul --}}
                        <div class="mb-4">
                            <label for="cover_image" class="form-label fw-bold">Halaman Sampul <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="file" name="cover_image" accept="image/*"
                                    class="form-control @error('cover_image') is-invalid @enderror" id="cover_image"
                                    required>
                            </div>
                            @error('cover_image')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <small class="text-muted d-block mt-1">Format file hanya jpg, jpeg, dan png (Maks 2MB)</small>
                        </div>

                        <hr class="my-4">

                        <div class="mt-4">
                            <button type="button" onclick="submitStep1()" class="btn btn-danger px-4 shadow-sm">
                                <i class="fas fa-arrow-right me-1"></i> Unggah & Lanjutkan
                            </button>
                            <a href="{{ route('user.dashboard') }}" class="btn btn-secondary px-4 shadow-sm">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // 1. Logika Validasi Form & Submit via SweetAlert2 (Khusus Tahap 1)
        function submitStep1() {
            const form = document.getElementById('formUploadStep1');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            Swal.fire({
                title: 'Unggah Berkas?',
                text: "Berkas akan diunggah ke sistem sebelum Anda mengisi data metadata.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#8B0000', // Maroon UNJA
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Unggah',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Sedang Mengunggah...',
                        html: 'Mohon tunggu sebentar, file sedang diproses oleh server.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });
                    form.submit();
                }
            });
        }

        // 2. Logika Pengecekan Ukuran & Ekstensi File secara Real-time saat Diisi
        document.addEventListener('DOMContentLoaded', function() {
            const pdfInput = document.getElementById('pdf_file');

            if (pdfInput) {
                pdfInput.onchange = function() {
                    const file = this.files[0];
                    if (file) {
                        // Validasi tipe data harus PDF
                        if (file.type !== "application/pdf") {
                            Swal.fire({
                                icon: 'error',
                                title: 'Format Salah',
                                text: 'Hanya file format PDF yang diperbolehkan!',
                                confirmButtonColor: '#8B0000',
                            });
                            this.value = "";
                            return;
                        }

                        // Validasi batas ukuran file maks 20MB
                        const maxSizeInBytes = 20 * 1024 * 1024;
                        if (file.size > maxSizeInBytes) {
                            const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                            Swal.fire({
                                icon: 'warning',
                                title: 'File Terlalu Besar',
                                text: 'Ukuran file Anda (' + fileSizeMB +
                                    ' MB) melebihi batas maksimal 20 MB.',
                                confirmButtonColor: '#8B0000',
                            });
                            this.value = "";
                            return;
                        }
                    }
                };
            }
        });
    </script>
@endpush
