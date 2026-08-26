@extends('layouts.admin')

@section('content')
    <div class="card shadow rounded-4 border-0">
        <div class="card-body">
            <div class="row align-items-center g-4 mb-4">
                <div class="col-12">
                    <h4 class="fw-bold text-dark"><i class="fas fa-bullhorn text-primary me-2"></i>Antrean Publikasi Karya
                        Ilmiah</h4>
                    <p class="text-muted small mb-0">Daftar karya ilmiah mahasiswa yang telah lolos verifikasi berkas dan
                        siap dirilis ke publik.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    @if ($articles->isEmpty())
                        <div class="text-center py-5 text-muted fw-semibold border rounded-3 bg-light">
                            <i class="fas fa-folder-open fa-3x mb-3 opacity-25 d-block text-secondary"></i>
                            <h5 class="fw-bold text-secondary mb-1">Tidak Ada Antrean Publikasi</h5>
                            <p class="small mb-0 text-muted">Belum ada karya ilmiah terverifikasi yang siap dirilis saat
                                ini.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table id="example" class="table table-hover align-middle border">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 50px">No</th>
                                        <th>Jenis</th>
                                        <th>Tanggal</th>
                                        <th>Judul Karya Ilmiah</th>
                                        <th>Penulis & Identitas</th>
                                        <th>Akses</th>
                                        <th>Status</th>
                                        <th class="text-center" style="width: 180px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($articles as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                @php
                                                    $initial = strtoupper(substr($item->document_type, 0, 1));
                                                    $typeColor = match ($item->document_type) {
                                                        'Skripsi' => 'bg-primary',
                                                        'Tesis' => 'bg-danger',
                                                        'Disertasi' => 'bg-dark',
                                                        'Jurnal' => 'bg-success',
                                                        default => 'bg-secondary',
                                                    };
                                                @endphp
                                                <span class="badge {{ $typeColor }} rounded-circle p-2"
                                                    title="{{ $item->document_type }}"
                                                    style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                    {{ $initial }}
                                                </span>
                                            </td>
                                            <td class="small text-muted">{{ $item->created_at->format('d M Y') }}</td>
                                            <td>
                                                @if ($item->accreditation_level && $item->accreditation_level != 'Belum Terakreditasi')
                                                    <span
                                                        class="badge bg-info border-secondary text-dark">{{ $item->accreditation_level }}</span>
                                                @endif
                                                <small class="text-muted d-block">{{ $item->study_program }} • Hukum •
                                                    {{ $item->year }}</small>
                                                <div class="fw-bold text-dark">{{ $item->title }}</div>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $item->author }}</div>

                                                {{-- Menampilkan NIM / NIDN Pengunggah --}}
                                                <div class="badge bg-light text-secondary border px-2 py-1 my-1">
                                                    <i class="far fa-id-card me-1 text-danger"></i>
                                                    {{ $item->user->identity_number ?? '-' }}
                                                </div>

                                                <small class="text-muted d-block italic" style="font-size: 0.75rem;">
                                                    Akun: {{ $item->user->name ?? 'User' }}
                                                </small>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge border {{ $item->access_type == 'Fulltext' ? 'text-success border-success' : 'text-primary border-primary' }} px-2 py-1">
                                                    {{ $item->access_type }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info text-white"> Terverifikasi </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-1">
                                                    {{-- Tombol Rilis Publikasi --}}
                                                    <a href="{{ route('admin.repository.detailPublikasi', $item->id) }}"
                                                        class="btn btn-primary btn-sm rounded-3 fw-bold"
                                                        title="Rilis Publikasi">
                                                        <i class="fas fa-paper-plane me-1"></i> Rilis
                                                    </a>

                                                    {{-- Tombol Edit Data --}}
                                                    <a href="{{ route('admin.repository.edit', $item->id) }}"
                                                        class="btn btn-warning btn-sm rounded-3 fw-bold text-dark"
                                                        title="Edit Data">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    {{-- Tombol Hapus Data --}}
                                                    <form id="delete-form-{{ $item->id }}"
                                                        action="{{ route('admin.repository.destroy', $item->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-sm rounded-3"
                                                            onclick="confirmDelete('{{ $item->id }}')"
                                                            title="Hapus Karya">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        if (typeof $.fn.dataTable !== 'undefined') {
            $.fn.dataTable.ext.errMode = 'none';
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Karya ilmiah ini beserta file dokumennya akan dihapus permanen dari sistem!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#8B0000',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus Data',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endpush
