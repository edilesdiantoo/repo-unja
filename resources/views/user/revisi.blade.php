@extends('layouts.user')

@section('content')
    <div class="card shadow rounded-4 border-0">
        <div class="card-body">
            <div class="row align-items-center g-4 mb-4">
                <div class="col-6">
                    <h4>Revisi Dokumen</h4>
                </div>
            </div>

            {{-- Menampilkan Catatan dari Admin --}}
            @if ($article->catatan_revisi)
                <div class="alert alert-warning border-warning">
                    <h6 class="fw-bold"><i class="fa fa-info-circle me-2"></i>Catatan dari Validator:</h6>
                    <p class="mb-0 italic">"{{ $article->catatan_revisi }}"</p>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <form action="{{ route('user.article.update', $article->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">Dokumen Asli</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $article->pdf_file }}" readonly>
                                <a href="{{ asset('storage/' . $article->pdf_file) }}"
                                    class="input-group-text btn btn-outline-secondary" target="_blank">Download</a>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Dokumen Perbaikan <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('pdf_file') is-invalid @enderror"
                                name="pdf_file" accept=".pdf">
                            <small class="text-muted">Upload file baru jika ingin mengganti dokumen. Format: PDF.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Halaman Sampul</label>
                            <div class="mb-2">
                                @if ($article->cover_image)
                                    <img src="{{ asset('storage/' . $article->cover_image) }}" class="rounded shadow-sm"
                                        style="width: 80px; height: 120px; object-fit: cover;">
                                @else
                                    <span class="badge bg-secondary">Tidak ada sampul</span>
                                @endif
                            </div>
                            <input type="file" class="form-control @error('cover_image') is-invalid @enderror"
                                name="cover_image" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG.</small>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Simpan Perbaikan</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Penulis</label>
                            <input type="text" class="form-control" name="author"
                                value="{{ old('author', $article->author) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul</label>
                            <input type="text" class="form-control" name="title"
                                value="{{ old('title', $article->title) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Abstrak</label>
                            <textarea class="form-control" name="abstract" rows="4" required>{{ old('abstract', $article->abstract) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Kata Kunci</label>
                            <input type="text" class="form-control" name="keywords"
                                value="{{ old('keywords', $article->keywords) }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Program Studi</label>
                                <select class="form-select" name="study_program" required>
                                    <option value="Hukum Pidana"
                                        {{ $article->study_program == 'Hukum Pidana' ? 'selected' : '' }}>Hukum Pidana
                                    </option>
                                    <option value="Hukum Perdata"
                                        {{ $article->study_program == 'Hukum Perdata' ? 'selected' : '' }}>Hukum Perdata
                                    </option>
                                    {{-- Tambahkan opsi lainnya sesuai database --}}
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tahun</label>
                                <input type="number" class="form-control" name="year"
                                    value="{{ old('year', $article->year) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jenis Dokumen</label>
                            <select class="form-select" name="document_type" required>
                                <option value="Skripsi" {{ $article->document_type == 'Skripsi' ? 'selected' : '' }}>
                                    Skripsi</option>
                                <option value="Tesis" {{ $article->document_type == 'Tesis' ? 'selected' : '' }}>Tesis
                                </option>
                                <option value="Jurnal" {{ $article->document_type == 'Jurnal' ? 'selected' : '' }}>Jurnal
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Pesan untuk Validator (Opsional)</label>
                            <textarea class="form-control" name="pesan_revisi_user" rows="2"
                                placeholder="Jelaskan perbaikan yang Anda lakukan..."></textarea>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-danger">Submit Revisi</button>
                            <a href="{{ route('user.article.history') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
