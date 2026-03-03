@extends('layouts.admin')

@section('content')
    <div class="card shadow rounded-4 border-0">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4 text-dark">
                <i class="fas fa-filter text-primary me-2"></i> Filter Laporan Repositori
            </h5>

            {{-- Route mengarah ke report.generate --}}
            <form action="{{ route('admin.report.generate') }}" target="_blank" method="POST">
                @csrf
                <div class="row g-3">
                    {{-- Rentang Waktu --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Rentang Waktu Publikasi</label>
                        <div class="input-group">
                            <input type="date" class="form-control" name="start_date" value="{{ date('Y-m-01') }}">
                            <span class="input-group-text">s/d</span>
                            <input type="date" class="form-control" name="end_date" value="{{ date('Y-m-d') }}">
                        </div>
                        <small class="text-muted">Default: Bulan ini.</small>
                    </div>

                    {{-- Kategori Dokumen --}}
                    <div class="col-md-3">
                        <label for="kategori" class="form-label fw-bold">Kategori Dokumen</label>
                        <select class="form-select" name="kategori">
                            <option value="all">Semua Kategori</option>
                            <option value="Skripsi">Skripsi</option>
                            <option value="Tesis">Tesis</option>
                            <option value="Disertasi">Disertasi</option>
                            <option value="Jurnal">Jurnal</option>
                            <option value="Laporan Magang">Laporan Magang</option>
                        </select>
                    </div>

                    {{-- Program Kekhususan / Prodi --}}
                    <div class="col-md-3">
                        <label for="prodi" class="form-label fw-bold">Program Kekhususan</label>
                        <select class="form-select" name="kekhususan">
                            <option value="all">Semua Bidang</option>
                            <option value="Hukum Pidana">Hukum Pidana</option>
                            <option value="Hukum Perdata">Hukum Perdata</option>
                            <option value="Hukum Bisnis">Hukum Bisnis</option>
                            <option value="Hukum Tata Negara">Hukum Tata Negara</option>
                            <option value="Hukum Administrasi Negara">Hukum Administrasi Negara</option>
                            <option value="Hukum Internasional">Hukum Internasional</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        <i class="fas fa-info-circle me-1"></i> Data yang ditampilkan adalah karya ilmiah yang telah
                        <span class="badge bg-success">Published</span> atau <span
                            class="badge bg-warning text-dark">Revision</span>.
                    </div>
                    <div class="d-flex gap-2">
                        <button type="reset" class="btn btn-outline-secondary px-4">Reset</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="fas fa-file-pdf me-2"></i> Cetak Laporan (PDF)
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
