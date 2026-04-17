@extends('layouts.user')


@section('title', 'Upload Karya Ilmiah')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@section('content')
    <div class="card shadow rounded-4 border-0">
        <div class="card-body">
            <div class="row align-items-center g-4 mb-4">
                <div class="col-6">
                    <h4>Upload Dokumen</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    {{-- WAJIB: Atribut action, method POST, dan enctype untuk upload file --}}
                    <form action="{{ route('user.article.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- File PDF --}}
                        <div class="mb-3">
                            <label for="pdf_file" class="form-label fw-bold">Dokumen (PDF)</label>
                            <div class="input-group">
                                <input type="file" name="pdf_file" id="pdf_file" accept="application/pdf"
                                    class="form-control @error('pdf_file') is-invalid @enderror">
                            </div>
                            @error('pdf_file')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <small class="text-muted d-block">Format file hanya pdf (Maks 20MB)</small>
                        </div>

                        {{-- Sampul --}}
                        <div class="mb-3">
                            <label for="cover_image" class="form-label fw-bold">Halaman Sampul</label>
                            <div class="input-group">
                                <input type="file" name="cover_image"
                                    class="form-control @error('cover_image') is-invalid @enderror" id="cover_image">
                            </div>
                            @error('cover_image')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <small class="text-muted d-block">Format file hanya jpg, jpeg, dan png (Maks 2MB)</small>
                        </div>

                        {{-- Penulis --}}
                        <div class="mb-3">
                            <label for="author" class="form-label fw-bold">Penulis</label>
                            <input type="text" name="author" class="form-control @error('author') is-invalid @enderror"
                                id="author" value="{{ old('author') }}" placeholder="Nama Lengkap Penulis">
                            @error('author')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Judul --}}
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">Judul</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                id="title" value="{{ old('title') }}" placeholder="Judul Karya Ilmiah">
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Abstrak --}}
                        <div class="mb-3">
                            <label for="abstract" class="form-label fw-bold">Abstrak</label>
                            <textarea name="abstract" class="form-control @error('abstract') is-invalid @enderror" id="abstract" rows="4">{{ old('abstract') }}</textarea>
                            @error('abstract')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Kata Kunci --}}
                        <div class="mb-3">
                            <label for="keywords" class="form-label fw-bold">Kata Kunci</label>
                            <input type="text" name="keywords" class="form-control" id="keywords"
                                value="{{ old('keywords') }}" placeholder="Contoh: Hukum, Pidana, Digital">
                        </div>

                        {{-- Program Studi --}}
                        <div class="mb-3">
                            <label for="study_program" class="form-label fw-bold">Program Studi</label>
                            <select class="form-select @error('study_program') is-invalid @enderror" name="study_program"
                                id="study_program">
                                <option value="">Pilih</option>
                                <option value="Hukum Pidana"
                                    {{ old('study_program') == 'Hukum Pidana' ? 'selected' : '' }}>Hukum Pidana</option>
                                <option value="Hukum Perdata"
                                    {{ old('study_program') == 'Hukum Perdata' ? 'selected' : '' }}>Hukum Perdata</option>
                                <option value="Hukum Bisnis"
                                    {{ old('study_program') == 'Hukum Bisnis' ? 'selected' : '' }}>Hukum Bisnis</option>
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
                            <label for="year" class="form-label fw-bold">Tahun</label>
                            <input type="number" name="year" class="form-control @error('year') is-invalid @enderror"
                                id="year" value="{{ old('year', date('Y')) }}">
                            @error('year')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Jenis Dokumen --}}
                        <div class="mb-3">
                            <label for="document_type" class="form-label fw-bold">Jenis Dokumen</label>
                            <select class="form-select @error('document_type') is-invalid @enderror" name="document_type"
                                id="document_type">
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
                        <div id="pembimbing_fields"
                            class="{{ in_array(old('document_type'), ['Skripsi', 'Tesis', 'Disertasi']) ? '' : 'd-none' }}">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Pembimbing 1</label>
                                    <input type="text" class="form-control" name="pembimbing_1"
                                        value="{{ old('pembimbing_1') }}" placeholder="Nama Pembimbing 1">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Pembimbing 2</label>
                                    <input type="text" class="form-control" name="pembimbing_2"
                                        value="{{ old('pembimbing_2') }}" placeholder="Nama Pembimbing 2">
                                </div>
                            </div>
                        </div>

                        {{-- Akreditasi --}}
                        <div class="mb-3">
                            <label for="tingkat_akreditasi" class="form-label fw-bold">Tingkat Akreditasi Karya Ilmiah
                                (Khusus Jurnal)</label>
                            <select class="form-select" name="tingkat_akreditasi" id="tingkat_akreditasi">
                                <option value="">Pilih</option>
                                <option value="Sinta 1" {{ old('tingkat_akreditasi') == 'Sinta 1' ? 'selected' : '' }}>
                                    Sinta 1</option>
                                <option value="Sinta 2" {{ old('tingkat_akreditasi') == 'Sinta 2' ? 'selected' : '' }}>
                                    Sinta 2</option>
                                <option value="Belum Terakreditasi"
                                    {{ old('tingkat_akreditasi') == 'Belum Terakreditasi' ? 'selected' : '' }}>Belum
                                    Terakreditasi</option>
                            </select>
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

                        <hr>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-danger">Simpan</button>
                            <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('document_type').addEventListener('change', function() {
            const fields = document.getElementById('pembimbing_fields');
            const TA = ['Skripsi', 'Tesis', 'Disertasi'];
            if (TA.includes(this.value)) {
                fields.classList.remove('d-none');
            } else {
                fields.classList.add('d-none');
                fields.querySelectorAll('input').forEach(i => i.value = '');
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pdfInput = document.getElementById('pdf_file');

            if (pdfInput) {
                pdfInput.onchange = function() {
                    const file = this.files[0];

                    if (file) {
                        // 1. Validasi Format (Harus PDF)
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

                        // 2. Validasi Ukuran (Kembali ke 20MB)
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
