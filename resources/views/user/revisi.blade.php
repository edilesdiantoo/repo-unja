@extends('layouts.user')

@section('content')
    <div class="card shadow rounded-4 border-0">
        <div class="card-body p-4">
            <div class="row align-items-center g-4 mb-4">
                <div class="col-6">
                    <h4 class="fw-bold text-dark"><i class="fas fa-edit text-warning me-2"></i>Form Revisi Karya Ilmiah</h4>
                </div>
                <div class="col-6 text-end">
                    <a href="{{ route('user.article.history') }}" class="btn btn-light border">
                        <i class="fas fa-arrow-left me-1"></i> Batal
                    </a>
                </div>
            </div>

            {{-- SINKRONISASI BARU: Menampilkan Catatan dari Admin / Validator secara Informatif --}}
            @if ($article->catatan_revisi)
                <div class="alert alert-warning border-0 rounded-3 mb-4 shadow-sm p-3"
                    style="border-left: 5px solid #ffc107 !important;">
                    <h5 class="fw-bold text-warning-dark mb-2">
                        <i class="fas fa-exclamation-triangle me-2"></i> Catatan dari Validator Admin:
                    </h5>
                    <div class="p-3 bg-white rounded-2 border border-warning-subtle text-dark fw-semibold shadow-inner mb-1"
                        style="font-style: italic; line-height: 1.5;">
                        "{{ $article->catatan_revisi }}"
                    </div>
                    <small class="text-muted d-block mt-2">* Harap perbaiki data metadata atau dokumen file di bawah ini
                        sesuai dengan instruksi catatan di atas.</small>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <form action="{{ route('user.article.update', $article->id) }}" method="POST"
                        enctype="multipart/form-data" id="formRevisi">
                        @csrf
                        @method('PUT')

                        {{-- Metadata Utama --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Penulis <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="author"
                                value="{{ old('author', $article->author) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Karya Ilmiah <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title"
                                value="{{ old('title', $article->title) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Abstrak <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="abstract" rows="4" required>{{ old('abstract', $article->abstract) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Kata Kunci <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="keywords"
                                value="{{ old('keywords', $article->keywords) }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Program Studi <span class="text-danger">*</span></label>
                                <select class="form-select" name="study_program" required>
                                    <option value="Hukum Pidana"
                                        {{ old('study_program', $article->study_program) == 'Hukum Pidana' ? 'selected' : '' }}>
                                        Hukum Pidana</option>
                                    <option value="Hukum Perdata"
                                        {{ old('study_program', $article->study_program) == 'Hukum Perdata' ? 'selected' : '' }}>
                                        Hukum Perdata</option>
                                    <option value="Hukum Tatanegara"
                                        {{ old('study_program', $article->study_program) == 'Hukum Tatanegara' ? 'selected' : '' }}>
                                        Hukum Tatanegara</option>
                                    <option value="Hukum Administrasi Negara"
                                        {{ old('study_program', $article->study_program) == 'Hukum Administrasi Negara' ? 'selected' : '' }}>
                                        Hukum Administrasi Negara</option>
                                    <option value="Hukum Internasional"
                                        {{ old('study_program', $article->study_program) == 'Hukum Internasional' ? 'selected' : '' }}>
                                        Hukum Internasional</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tahun Kelulusan / Publikasi <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="year"
                                    value="{{ old('year', $article->year) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="document_type" class="form-label fw-bold">Jenis Dokumen <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" name="document_type" id="document_type" required>
                                <option value="Skripsi"
                                    {{ old('document_type', $article->document_type) == 'Skripsi' ? 'selected' : '' }}>
                                    Skripsi</option>
                                <option value="Tesis"
                                    {{ old('document_type', $article->document_type) == 'Tesis' ? 'selected' : '' }}>Tesis
                                </option>
                                <option value="Disertasi"
                                    {{ old('document_type', $article->document_type) == 'Disertasi' ? 'selected' : '' }}>
                                    Disertasi</option>
                                <option value="Jurnal"
                                    {{ old('document_type', $article->document_type) == 'Jurnal' ? 'selected' : '' }}>
                                    Jurnal</option>
                                <option value="Laporan Magang"
                                    {{ old('document_type', $article->document_type) == 'Laporan Magang' ? 'selected' : '' }}>
                                    Laporan Magang</option>
                            </select>
                        </div>

                        {{-- Dosen Pembimbing Fields (Dinamis via JS) --}}
                        <div id="pembimbing_fields" class="d-none">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Pembimbing 1</label>
                                    <input type="text" class="form-control" id="pembimbing_1" name="pembimbing_1"
                                        value="{{ old('pembimbing_1', $article->pembimbing_1) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Pembimbing 2</label>
                                    <input type="text" class="form-control" id="pembimbing_2" name="pembimbing_2"
                                        value="{{ old('pembimbing_2', $article->pembimbing_2) }}">
                                </div>
                            </div>
                        </div>

                        {{-- Dropdown Akreditasi Jurnal (Dinamis via JS) --}}
                        <div class="mb-3" id="containerAkreditasi" style="display: none;">
                            <label for="tingkat_akreditasi" class="form-label fw-bold">Tingkat Akreditasi Jurnal <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" name="tingkat_akreditasi" id="tingkat_akreditasi">
                                <option value="">-- Pilih Tingkat Akreditasi --</option>
                                <optgroup label="Jurnal Internasional (Scopus / Quartile)">
                                    <option value="Quartile (Q1)"
                                        {{ old('tingkat_akreditasi', $article->accreditation_level) == 'Quartile (Q1)' ? 'selected' : '' }}>
                                        Quartile (Q1)</option>
                                    <option value="Quartile (Q2)"
                                        {{ old('tingkat_akreditasi', $article->accreditation_level) == 'Quartile (Q2)' ? 'selected' : '' }}>
                                        Quartile (Q2)</option>
                                    <option value="Quartile (Q3)"
                                        {{ old('tingkat_akreditasi', $article->accreditation_level) == 'Quartile (Q3)' ? 'selected' : '' }}>
                                        Quartile (Q3)</option>
                                    <option value="Quartile (Q4)"
                                        {{ old('tingkat_akreditasi', $article->accreditation_level) == 'Quartile (Q4)' ? 'selected' : '' }}>
                                        Quartile (Q4)</option>
                                </optgroup>
                                <optgroup label="Jurnal Nasional Terakreditasi (SINTA)">
                                    <option value="Sinta 1"
                                        {{ old('tingkat_akreditasi', $article->accreditation_level) == 'Sinta 1' ? 'selected' : '' }}>
                                        Sinta 1</option>
                                    <option value="Sinta 2"
                                        {{ old('tingkat_akreditasi', $article->accreditation_level) == 'Sinta 2' ? 'selected' : '' }}>
                                        Sinta 2</option>
                                    <option value="Sinta 3"
                                        {{ old('tingkat_akreditasi', $article->accreditation_level) == 'Sinta 3' ? 'selected' : '' }}>
                                        Sinta 3</option>
                                    <option value="Sinta 4"
                                        {{ old('tingkat_akreditasi', $article->accreditation_level) == 'Sinta 4' ? 'selected' : '' }}>
                                        Sinta 4</option>
                                    <option value="Sinta 5"
                                        {{ old('tingkat_akreditasi', $article->accreditation_level) == 'Sinta 5' ? 'selected' : '' }}>
                                        Sinta 5</option>
                                    <option value="Sinta 6"
                                        {{ old('tingkat_akreditasi', $article->accreditation_level) == 'Sinta 6' ? 'selected' : '' }}>
                                        Sinta 6</option>
                                </optgroup>
                                <option value="Belum Terakreditasi"
                                    {{ old('tingkat_akreditasi', $article->accreditation_level) == 'Belum Terakreditasi' ? 'selected' : '' }}>
                                    Belum Terakreditasi</option>
                            </select>
                        </div>

                        {{-- Ketentuan Akses Dokumen --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Ketentuan Akses Dokumen</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="access_type" id="accessFull"
                                    value="Fulltext"
                                    {{ old('access_type', $article->access_type) == 'Fulltext' ? 'checked' : '' }}>
                                <label class="form-check-label" for="accessFull">Fulltext (Bisa diunduh)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="access_type" id="accessAbstract"
                                    value="Abstrak"
                                    {{ old('access_type', $article->access_type) == 'Abstrak' ? 'checked' : '' }}>
                                <label class="form-check-label" for="accessAbstract">Hanya Abstrak (Tidak bisa
                                    diunduh)</label>
                            </div>
                        </div>

                        {{-- Berkas File Dokumen --}}
                        <div class="row p-3 bg-light rounded-3 border mb-4 mx-1">
                            <h6 class="fw-bold text-muted mb-3"><i class="fas fa-file-invoice me-2"></i>Manajemen Berkas
                                File</h6>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Ganti Dokumen Perbaikan (PDF)</label>
                                <input type="file" class="form-control @error('pdf_file') is-invalid @enderror"
                                    name="pdf_file" accept=".pdf" id="pdf_file">
                                <small class="text-muted d-block mt-1">Biarkan kosong jika tidak ingin mengganti file.
                                    Format: PDF.</small>
                                <small class="text-primary d-block mt-1 small">File saat ini: <a
                                        href="{{ asset('storage/' . $article->pdf_file) }}" target="_blank"
                                        class="fw-bold">{{ basename($article->pdf_file) }}</a></small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Ganti Halaman Sampul (Cover)</label>
                                <input type="file" class="form-control @error('cover_image') is-invalid @enderror"
                                    name="cover_image" accept="image/*">
                                <small class="text-muted d-block mt-1">Format: JPG, PNG.</small>
                                @if ($article->cover_image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $article->cover_image) }}"
                                            class="rounded shadow-sm"
                                            style="width: 50px; height: 75px; object-fit: cover;">
                                        <small class="text-muted ms-2">{{ basename($article->cover_image) }}</small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Pesan untuk Validator (Opsional)</label>
                            <textarea class="form-control" name="pesan_revisi_user" rows="2"
                                placeholder="Jelaskan perbaikan yang Anda lakukan..."></textarea>
                        </div>

                        {{-- Tombol Submit Tunggal --}}
                        <div class="mt-4">
                            <button type="button" onclick="submitRevisi()"
                                class="btn btn-danger px-4 shadow-sm fw-bold">
                                <i class="fas fa-paper-plane me-1"></i> Submit Hasil Revisi
                            </button>
                            <a href="{{ route('user.article.history') }}"
                                class="btn btn-secondary px-4 shadow-sm">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // 1. Validasi Kirim via SweetAlert2
        function submitRevisi() {
            const form = document.getElementById('formRevisi');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            Swal.fire({
                title: 'Submit Hasil Perbaikan?',
                text: "Data karya ilmiah akan dikirim ulang ke verifikator admin untuk diperiksa kembali.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Submit',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Sedang Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });
                    form.submit();
                }
            });
        }

        // 2. Handler Kolom Pembimbing & Akreditasi Jurnal secara Sinkron
        document.addEventListener('DOMContentLoaded', function() {
            const documentTypeSelect = document.getElementById('document_type');
            const pembimbingFields = document.getElementById('pembimbing_fields');
            const containerAkreditasi = document.getElementById('containerAkreditasi');
            const inputAkreditasi = document.getElementById('tingkat_akreditasi');

            function handleDynamicFields(selectedValue) {
                const TA = ['Skripsi', 'Tesis', 'Disertasi'];

                // Handler Dosen Pembimbing
                if (pembimbingFields) {
                    if (TA.includes(selectedValue)) {
                        pembimbingFields.classList.remove('d-none');
                        pembimbingFields.querySelectorAll('input').forEach(i => i.setAttribute('required',
                            'required'));
                    } else {
                        pembimbingFields.classList.add('d-none');
                        pembimbingFields.querySelectorAll('input').forEach(i => {
                            i.removeAttribute('required');
                            i.value = '';
                        });
                    }
                }

                // Handler Akreditasi Jurnal
                if (containerAkreditasi && inputAkreditasi) {
                    if (selectedValue === 'Jurnal') {
                        containerAkreditasi.style.display = 'block';
                        inputAkreditasi.setAttribute('required', 'required');
                    } else {
                        containerAkreditasi.style.display = 'none';
                        inputAkreditasi.removeAttribute('required');
                        inputAkreditasi.value = '';
                    }
                }
            }

            // Jalankan deteksi awal
            if (documentTypeSelect) {
                handleDynamicFields(documentTypeSelect.value);

                // Jalankan deteksi tiap kali opsi diubah user
                documentTypeSelect.addEventListener('change', function() {
                    handleDynamicFields(this.value);
                });
            }

            // Validasi format file PDF jika di-upload ulang
            const pdfInput = document.getElementById('pdf_file');
            if (pdfInput) {
                pdfInput.onchange = function() {
                    const file = this.files[0];
                    if (file && file.type !== "application/pdf") {
                        Swal.fire({
                            icon: 'error',
                            title: 'Format Salah',
                            text: 'Hanya file format PDF yang diperbolehkan!',
                            confirmButtonColor: '#8B0000',
                        });
                        this.value = "";
                    }
                };
            }
        });
    </script>
@endpush
