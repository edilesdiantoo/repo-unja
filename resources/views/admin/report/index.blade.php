@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow rounded-4 border-0 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4 text-dark"><i class="fas fa-filter text-primary me-2"></i> Filter Laporan</h5>

                <form action="{{ route('admin.report.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Rentang Waktu</label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="start_date"
                                    value="{{ request('start_date', date('Y-m-01')) }}">
                                <input type="date" class="form-control" name="end_date"
                                    value="{{ request('end_date', date('Y-m-d')) }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-bold">Jenis Dokumen</label>
                            <select class="form-select" name="kategori">
                                <option value="all">Semua Jenis</option>
                                <option value="Skripsi" {{ request('kategori') == 'Skripsi' ? 'selected' : '' }}>Skripsi
                                </option>
                                <option value="Tesis" {{ request('kategori') == 'Tesis' ? 'selected' : '' }}>Tesis</option>
                                <option value="Disertasi" {{ request('kategori') == 'Disertasi' ? 'selected' : '' }}>
                                    Disertasi</option>
                                <option value="Jurnal" {{ request('kategori') == 'Jurnal' ? 'selected' : '' }}>Jurnal
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Program Studi</label>
                            <select class="form-select" name="kekhususan">
                                <option value="all">Semua Prodi</option>
                                <option value="Hukum Pidana"
                                    {{ request('kekhususan') == 'Hukum Pidana' ? 'selected' : '' }}>Hukum Pidana</option>
                                <option value="Hukum Perdata"
                                    {{ request('kekhususan') == 'Hukum Perdata' ? 'selected' : '' }}>Hukum Perdata</option>
                                <option value="Hukum Bisnis"
                                    {{ request('kekhususan') == 'Hukum Bisnis' ? 'selected' : '' }}>Hukum Bisnis</option>
                                <option value="Hukum Tatanegara"
                                    {{ request('kekhususan') == 'Hukum Tatanegara' ? 'selected' : '' }}>Hukum Tatanegara
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Status</label>
                            <select class="form-select" name="status">
                                <option value="all">Semua Status</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>
                                    Published</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="revision" {{ request('status') == 'revision' ? 'selected' : '' }}>Revision
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.report.index') }}" class="btn btn-outline-secondary px-4">Reset</a>
                        <button type="submit" class="btn btn-info text-white px-4">Filter Tabel</button>
                        <button type="submit" formaction="{{ route('admin.report.generate') }}" formmethod="POST"
                            target="_blank" class="btn btn-primary px-4">
                            @csrf <i class="fas fa-file-pdf me-1"></i> Cetak PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow rounded-4 border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4" width="5%">No</th>
                                <th width="15%">Tanggal</th>
                                <th>Judul Karya Ilmiah</th>
                                <th width="15%">Kategori</th>
                                <th width="12%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($articles as $key => $row)
                                <tr>
                                    <td class="ps-4 text-muted">{{ $articles->firstItem() + $key }}</td>
                                    <td>{{ $row->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="fw-bold text-dark small">{{ Str::limit($row->title, 70) }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ $row->study_program }}</div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border">{{ $row->document_type }}</span></td>
                                    <td>
                                        @if ($row->status == 'published')
                                            <span class="badge bg-success">Published</span>
                                        @elseif($row->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($row->status == 'revision')
                                            <span class="badge bg-info">Revision</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-folder-open fa-3x d-block mb-3 opacity-25"></i>
                                        Tidak ada data ditemukan.
                                    </td>
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
    </div>
@endsection
