@extends('layouts.user')

@section('title', 'Isi Metadata Karya Ilmiah')

@section('content')
    <div class="card shadow rounded-4 border-0">
        <div class="card-body p-4">

            {{-- Keterangan Metadata --}}
            <div class="alert alert-info border-0 rounded-3 mb-4 shadow-sm">
                <h6 class="fw-bold"><i class="fas fa-info-circle me-2"></i> Keterangan Metadata Karya Ilmiah:</h6>
                <p class="mb-0 small">Harap isi seluruh informasi data karya ilmiah di bawah ini dengan lengkap, valid, dan
                    teliti sesuai dengan dokumen fisik yang telah Anda unggah.</p>
            </div>

            <div class="row align-items-center g-4 mb-4">
                <div class="col-12">
                    <h4 class="fw-bold text-dark"><i class="fas fa-tags text-danger me-2"></i> Isi Metadata - Tahap 2</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    {{-- Route mengirimkan update teks berdasarkan ID Artikel yang baru dibuat --}}
                    <form action="{{ route('user.article.storeStep2', $article->id) }}" method="POST" id="formMetadata">
                        @csrf

                        {{-- Penulis --}}
                        <div class="mb-3">
                            <label for="author" class="form-label fw-bold">Penulis <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="author" class="form-control @error('author') is-invalid @enderror"
                                id="author" value="{{ old('author') }}" placeholder="Nama Lengkap Penulis" required>
                            @error('author')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Judul --}}
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">Judul <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                id="title" value="{{ old('title') }}" placeholder="Judul Karya Ilmiah" required>
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Abstrak --}}
                        <div class="mb-3">
                            <label for="abstract" class="form-label fw-bold">Abstrak <span
                                    class="text-danger">*</span></label>
                            <textarea name="abstract" class="form-control @error('abstract') is-invalid @enderror" id="abstract" rows="5"
                                required>{{ old('abstract') }}</textarea>
                            @error('abstract')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Kata Kunci --}}
                        <div class="mb-3">
                            <label for="keywords" class="form-label fw-bold">Kata Kunci <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="keywords" class="form-control" id="keywords"
                                value="{{ old('keywords') }}" placeholder="Contoh: Hukum, Pidana, Digital" required>
                        </div>

                        {{-- Program Studi --}}
                        <div class="mb-3">
                            <label for="study_program" class="form-label fw-bold">Program Studi <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('study_program') is-invalid @enderror" name="study_program"
                                id="study_program" required>
                                <option value="">Pilih</option>
                                <option value="Hukum Pidana" {{ old('study_program') == 'Hukum Pidana' ? 'selected' : '' }}>
                                    Hukum Pidana</option>
                                <option value="Hukum Perdata"
                                    {{ old('study_program') == 'Hukum Perdata' ? 'selected' : '' }}>Hukum Perdata</option>
                                <option value="Hukum Tatanegara"
                                    {{ old('study_program') == 'Hukum Tatanegara' ? 'selected' : '' }}>Hukum Tatanegara
                                </option>
                                <option value="Hukum Administrasi Negara"
                                    {{ old('study_program') == 'Hukum Administrasi Negara' ? 'selected' : '' }}>Hukum
                                    Administrasi Negara</option>
                                <option value="Hukum Internasional"
                                    {{ old('study_program') == 'Hukum Internasional' ? 'selected' : '' }}>Hukum
                                    Internasional</option>
                            </select>
                            @error('study_program')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Fakultas --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Fakultas</label>
                            <input type="text" class="form-control" value="Hukum" readonly>
                        </div>

                        {{-- Tahun --}}
                        <div class="mb-3">
                            <label for="year" class="form-label fw-bold">Tahun <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="year" class="form-control @error('year') is-invalid @enderror"
                                id="year" value="{{ old('year', date('Y')) }}" required>
                            @error('year')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Jenis Dokumen --}}
                        <div class="mb-3">
                            <label for="document_type" class="form-label fw-bold">Jenis Dokumen <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('document_type') is-invalid @enderror" name="document_type"
                                id="document_type" required>
                                <option value="">Pilih</option>
                                <option value="Skripsi" {{ old('document_type') == 'Skripsi' ? 'selected' : '' }}>Skripsi
                                </option>
                                <option value="Tesis" {{ old('document_type') == 'Tesis' ? 'selected' : '' }}>Tesis
                                </option>
                                <option value="Disertasi" {{ old('document_type') == 'Disertasi' ? 'selected' : '' }}>
                                    Disertasi</option>
                                <option value="Jurnal" {{ old('document_type') == 'Jurnal' ? 'selected' : '' }}>Jurnal
                                </option>
                                <option value="Laporan Magang"
                                    {{ old('document_type') == 'Laporan Magang' ? 'selected' : '' }}>Laporan Magang
                                </option>
                            </select>
                            @error('document_type')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Pembimbing (Muncul via JS) --}}
                        <div id="pembimbing_fields" class="d-none">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Pembimbing 1 <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="pembimbing_1" name="pembimbing_1"
                                        value="{{ old('pembimbing_1') }}" placeholder="Nama Pembimbing 1">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Pembimbing 2 <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="pembimbing_2" name="pembimbing_2"
                                        value="{{ old('pembimbing_2') }}" placeholder="Nama Pembimbing 2">
                                </div>
                            </div>
                        </div>

                        {{-- Akreditasi (Muncul via JS khusus Jurnal) --}}
                        <div class="mb-3" id="containerAkreditasi" style="display: none;">
                            <label for="tingkat_akreditasi" class="form-label fw-bold">
                                Tingkat Akreditasi Karya Ilmiah <span class="text-danger">* khusus Jurnal</span>
                            </label>
                            <select class="form-select" name="tingkat_akreditasi" id="tingkat_akreditasi">
                                <option value="">-- Pilih Tingkat Akreditasi --</option>

                                {{-- Kelompok Internasional (Scopus/SJR) --}}
                                <optgroup label="Jurnal Internasional (Scopus / Quartile)">
                                    <option value="Quartile (Q1)"
                                        {{ old('tingkat_akreditasi', $article->tingkat_akreditasi ?? '') == 'Quartile (Q1)' ? 'selected' : '' }}>
                                        Quartile (Q1)</option>
                                    <option value="Quartile (Q2)"
                                        {{ old('tingkat_akreditasi', $article->tingkat_akreditasi ?? '') == 'Quartile (Q2)' ? 'selected' : '' }}>
                                        Quartile (Q2)</option>
                                    <option value="Quartile (Q3)"
                                        {{ old('tingkat_akreditasi', $article->tingkat_akreditasi ?? '') == 'Quartile (Q3)' ? 'selected' : '' }}>
                                        Quartile (Q3)</option>
                                    <option value="Quartile (Q4)"
                                        {{ old('tingkat_akreditasi', $article->tingkat_akreditasi ?? '') == 'Quartile (Q4)' ? 'selected' : '' }}>
                                        Quartile (Q4)</option>
                                </optgroup>

                                {{-- Kelompok Nasional (Sinta) --}}
                                <optgroup label="Jurnal Nasional Terakreditasi (SINTA)">
                                    <option value="Sinta 1"
                                        {{ old('tingkat_akreditasi', $article->tingkat_akreditasi ?? '') == 'Sinta 1' ? 'selected' : '' }}>
                                        Sinta 1</option>
                                    <option value="Sinta 2"
                                        {{ old('tingkat_akreditasi', $article->tingkat_akreditasi ?? '') == 'Sinta 2' ? 'selected' : '' }}>
                                        Sinta 2</option>
                                    <option value="Sinta 3"
                                        {{ old('tingkat_akreditasi', $article->tingkat_akreditasi ?? '') == 'Sinta 3' ? 'selected' : '' }}>
                                        Sinta 3</option>
                                    <option value="Sinta 4"
                                        {{ old('tingkat_akreditasi', $article->tingkat_akreditasi ?? '') == 'Sinta 4' ? 'selected' : '' }}>
                                        Sinta 4</option>
                                    <option value="Sinta 5"
                                        {{ old('tingkat_akreditasi', $article->tingkat_akreditasi ?? '') == 'Sinta 5' ? 'selected' : '' }}>
                                        Sinta 5</option>
                                    <option value="Sinta 6"
                                        {{ old('tingkat_akreditasi', $article->tingkat_akreditasi ?? '') == 'Sinta 6' ? 'selected' : '' }}>
                                        Sinta 6</option>
                                </optgroup>

                                <option value="Belum Terakreditasi"
                                    {{ old('tingkat_akreditasi', $article->tingkat_akreditasi ?? '') == 'Belum Terakreditasi' ? 'selected' : '' }}>
                                    Belum Terakreditasi</option>
                            </select>
                            @error('tingkat_akreditasi')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Akses --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ketentuan Akses Dokumen</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="access_type" id="accessFull"
                                    value="Fulltext" {{ old('access_type', 'Fulltext') == 'Fulltext' ? 'checked' : '' }}>
                                <label class="form-check-label" for="accessFull">Fulltext (Bisa diunduh)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="access_type" id="accessAbstract"
                                    value="Abstrak" {{ old('access_type') == 'Abstrak' ? 'checked' : '' }}>
                                <label class="form-check-label" for="accessAbstract">Hanya Abstrak (Tidak bisa
                                    diunduh)</label>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mt-4">
                            <button type="button" onclick="submitMetadata()" class="btn btn-danger px-4 shadow-sm">
                                <i class="fas fa-save me-1"></i> Simpan Metadata
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // 1. Logika Validasi Form Metadata Lewat SweetAlert2
        function submitMetadata() {
            const form = document.getElementById('formMetadata');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            Swal.fire({
                title: 'Simpan Metadata?',
                text: "Karya ilmiah akan tersimpan sebagai draf. Jangan lupa ajukan verifikasi setelah ini.",
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Simpan',
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

        // 2. Logika Sinkronisasi Dynamic Fields (Pembimbing & Akreditasi Jurnal)
        document.addEventListener('DOMContentLoaded', function() {
            const documentTypeSelect = document.getElementById('document_type');
            const pembimbingFields = document.getElementById('pembimbing_fields');
            const containerAkreditasi = document.getElementById('containerAkreditasi');
            const inputAkreditasi = document.getElementById('tingkat_akreditasi');

            function handleDynamicFields(selectedValue) {
                const TA = ['Skripsi', 'Tesis', 'Disertasi'];

                // --- Pengaturan Tampilan Field Dosen Pembimbing ---
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

                // --- Pengaturan Tampilan Field Akreditasi Jurnal ---
                if (containerAkreditasi && inputAkreditasi) {
                    if (selectedValue === 'Jurnal') {
                        containerAkreditasi.style.display = 'block';
                        inputAkreditasi.setAttribute('required', 'required');
                    } else {
                        containerAkreditasi.style.display = 'none';
                        inputAkreditasi.removeAttribute('required');
                        inputAkreditasi.value = ''; // Reset nilai jika bukan jurnal
                    }
                }
            }

            // Jalankan deteksi sekali di awal halaman dimuat
            if (documentTypeSelect) {
                handleDynamicFields(documentTypeSelect.value);

                // Jalankan fungsi setiap kali dropdown Jenis Dokumen berubah nilai
                documentTypeSelect.addEventListener('change', function() {
                    handleDynamicFields(this.value);
                });
            }
        });
    </script>
@endpush
