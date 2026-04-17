@extends('layouts.admin')

@section('content')
    <div class="card shadow rounded-4 border-0 mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4 text-dark">
                <i class="fas fa-filter text-primary me-2"></i> Filter Laporan Repositori
            </h5>

            {{-- Gunakan method GET agar filter tampil di tabel bawah --}}
            <form action="{{ route('admin.report.index') }}" method="GET" id="filterForm">
                <div class="row g-3">
                    {{-- Rentang Waktu --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Rentang Waktu</label>
                        <div class="input-group">
                            <input type="date" class="form-control" name="start_date"
                                value="{{ request('start_date', date('Y-m-01')) }}">
                            <input type="date" class="form-control" name="end_date"
                                value="{{ request('end_date', date('Y-m-d')) }}">
                        </div>
                    </div>

                    {{-- Jenis Dokumen (Kategori) --}}
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Jenis Dokumen</label>
                        <select class="form-select" name="kategori">
                            <option value="all">Semua Jenis</option>
                            <option value="Skripsi" {{ request('kategori') == 'Skripsi' ? 'selected' : '' }}>Skripsi
                            </option>
                            <option value="Tesis" {{ request('kategori') == 'Tesis' ? 'selected' : '' }}>Tesis</option>
                            <option value="Disertasi" {{ request('kategori') == 'Disertasi' ? 'selected' : '' }}>Disertasi
                            </option>
                            <option value="Jurnal" {{ request('kategori') == 'Jurnal' ? 'selected' : '' }}>Jurnal</option>
                            <option value="Laporan Magang" {{ request('kategori') == 'Laporan Magang' ? 'selected' : '' }}>
                                Laporan Magang</option>
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Status</label>
                        <select class="form-select" name="status">
                            <option value="all">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published
                            </option>
                            <option value="revision" {{ request('status') == 'revision' ? 'selected' : '' }}>Revision
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                            </option>
                        </select>
                    </div>

                    {{-- Program Studi (Kekhususan) --}}
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Program Studi</label>
                        <select class="form-select" name="kekhususan">
                            <option value="all">Semua Prodi</option>
                            <option value="Hukum Pidana" {{ request('kekhususan') == 'Hukum Pidana' ? 'selected' : '' }}>
                                Hukum Pidana</option>
                            <option value="Hukum Perdata" {{ request('kekhususan') == 'Hukum Perdata' ? 'selected' : '' }}>
                                Hukum Perdata</option>
                            <option value="Hukum Bisnis" {{ request('kekhususan') == 'Hukum Bisnis' ? 'selected' : '' }}>
                                Hukum Bisnis</option>
                            <option value="Hukum Tatanegara"
                                {{ request('kekhususan') == 'Hukum Tatanegara' ? 'selected' : '' }}>Hukum Tatanegara
                            </option>
                            <option value="Hukum Administrasi Negara"
                                {{ request('kekhususan') == 'Hukum Administrasi Negara' ? 'selected' : '' }}>Hukum
                                Administrasi Negara</option>
                            <option value="Hukum Internasional"
                                {{ request('kekhususan') == 'Hukum Internasional' ? 'selected' : '' }}>Hukum Internasional
                            </option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.report.index') }}" class="btn btn-outline-secondary px-4">Reset</a>

                    {{-- Tombol Filter Tabel --}}
                    <button type="submit" class="btn btn-info text-white px-4 shadow-sm">
                        <i class="fas fa-search me-2"></i> Filter Tabel
                    </button>

                    {{-- Tombol Cetak PDF (Mengarah ke route generate) --}}
                    <button type="submit" formaction="{{ route('admin.report.generate') }}" formmethod="POST"
                        target="_blank" class="btn btn-primary px-4 shadow-sm">
                        @csrf
                        <i class="fas fa-file-pdf me-2"></i> Cetak Laporan (PDF)
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Preview --}}
    <div class="card shadow rounded-4 border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Tanggal</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Kekhususan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($articles as $key => $article)
                            <tr>
                                <td class="ps-4">{{ $articles->firstItem() + $key }}</td>
                                <td>{{ $article->created_at->format('d/m/Y') }}</td>
                                <td class="small fw-bold">{{ Str::limit($article->title, 60) }}</td>
                                <td><span class="badge bg-secondary">{{ $article->document_type }}</span></td>
                                <td>{{ $article->study_program }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Data tidak ditemukan untuk filter
                                    ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            {{ $articles->links() }}
        </div>
    </div>
@endsection
