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
                    <div class="table-responsive">
                        <table id="example" class="table table-hover align-middle border">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 50px">No</th>
                                    <th>Jenis</th>
                                    <th>Tanggal</th>
                                    <th>Judul Karya Ilmiah</th>
                                    <th>Penulis</th>
                                    <th>Akses</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
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
                                            <small class="text-muted">{{ $item->study_program }} • Hukum •
                                                {{ $item->year }}</small>
                                            <div class="fw-bold text-dark">{{ $item->title }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $item->author }}</div>
                                            <small class="text-muted italic" style="font-size: 0.75rem;">Oleh:
                                                {{ $item->user->name ?? 'User' }}</small>
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
                                            <a href="{{ route('admin.repository.detailPublikasi', $item->id) }}"
                                                class="btn btn-primary btn-sm rounded-3 fw-bold">
                                                <i class="fas fa-paper-plane me-1"></i> Rilis
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
