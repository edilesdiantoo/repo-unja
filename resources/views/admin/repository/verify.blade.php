@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow rounded-4 border-0">
            <div class="card-body p-4">
                <div class="row align-items-center g-4 mb-4">
                    <div class="col-6">
                        <h4 class="fw-bold"><i class="fas fa-check-shield text-success me-2"></i>Verifikasi Dokumen</h4>
                    </div>
                    <div class="col-6 text-end">
                        <a href="{{ route('admin.repository.pending') }}" class="btn btn-light border">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('admin.repository.update-status', $article->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="row">
                                {{-- Kolom Kiri: Detail Dokumen --}}
                                <div class="col-md-8">
                                    {{-- Dokumen Utama --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">File PDF Utama</label>
                                        <div class="d-flex align-items-center p-3 border rounded bg-light">
                                            <i class="fa-regular fa-file-pdf fa-2x text-danger me-3"></i>
                                            <div class="flex-grow-1">
                                                <div class="small text-muted">Nama File:</div>
                                                <a href="{{ asset('storage/' . $article->file_utama) }}" target="_blank"
                                                    class="fw-bold text-decoration-none">
                                                    {{ basename($article->file_utama) }}
                                                </a>
                                            </div>
                                            <a href="{{ asset('storage/' . $article->file_utama) }}" target="_blank"
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
                                                <a href="{{ asset('storage/' . $article->file_sampul) }}" target="_blank"
                                                    class="fw-bold text-decoration-none">
                                                    {{ basename($article->file_sampul) }}
                                                </a>
                                            </div>
                                            <a href="{{ asset('storage/' . $article->file_sampul) }}" target="_blank"
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

                                {{-- Kolom Kanan: Metadata & Verifikasi --}}
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

                                    <div class="p-3 border border-warning rounded bg-white mb-3">
                                        <h6 class="fw-bold text-warning border-bottom pb-2 mb-3">Panel Verifikasi</h6>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Keputusan <span
                                                    class="text-danger">*</span></label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="status"
                                                    id="statusPublished" value="published"
                                                    {{ old('status', $article->status) == 'published' ? 'checked' : '' }}
                                                    required>
                                                <label class="form-check-label text-success fw-bold"
                                                    for="statusPublished">Setujui (Publish)</label>
                                            </div>
                                            <div class="form-check border-top pt-2 mt-2">
                                                <input class="form-check-input" type="radio" name="status"
                                                    id="statusRevision" value="revision"
                                                    {{ old('status', $article->status) == 'revision' ? 'checked' : '' }}>
                                                <label class="form-check-label text-warning fw-bold"
                                                    for="statusRevision">Minta Revisi</label>
                                            </div>
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="radio" name="status"
                                                    id="statusRejected" value="rejected"
                                                    {{ old('status', $article->status) == 'rejected' ? 'checked' : '' }}>
                                                <label class="form-check-label text-danger fw-bold"
                                                    for="statusRejected">Tolak Dokumen</label>
                                            </div>
                                            @error('status')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        {{-- Container Catatan (Muncul Otomatis via JS) --}}
                                        <div class="mb-3" id="containerCatatan"
                                            style="display: {{ in_array(old('status', $article->status), ['revision', 'rejected']) ? 'block' : 'none' }};">
                                            <label class="form-label fw-bold">Catatan Perbaikan <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control border-warning" id="catatanRevisi" name="catatan_revisi" rows="4"
                                                placeholder="Jelaskan bagian mana yang perlu diperbaiki mahasiswa...">{{ old('catatan_revisi', $article->catatan_revisi) }}</textarea>
                                            @error('catatan_revisi')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="alert alert-info small py-2">
                                            <i class="fas fa-info-circle me-1"></i> Mahasiswa akan menerima notifikasi
                                            status setelah tombol submit diklik.
                                        </div>

                                        <button type="submit" class="btn btn-success w-100 fw-bold py-2">
                                            <i class="fas fa-paper-plane me-2"></i>Kirim Keputusan
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

    {{-- SCRIPT DYNAMIC FIELD --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const containerCatatan = document.getElementById('containerCatatan');
            const catatanInput = document.getElementById('catatanRevisi');
            const radioStatus = document.querySelectorAll('input[name="status"]');

            function toggleCatatan(value) {
                if (value === 'revision' || value === 'rejected') {
                    containerCatatan.style.display = 'block';
                    catatanInput.setAttribute('required', 'required');
                } else {
                    containerCatatan.style.display = 'none';
                    catatanInput.removeAttribute('required');
                }
            }

            radioStatus.forEach((radio) => {
                radio.addEventListener('change', function() {
                    toggleCatatan(this.value);
                    if (this.value !== 'published') {
                        catatanInput.focus();
                    }
                });
            });
        });
    </script>
@endsection
