@extends('layouts.user')

@section('title', 'Riwayat Data Karya Ilmiah')

@section('content')
    <div class="card shadow rounded-4 border-0">
        <div class="card-body">
            <div class="row align-items-center g-4 mb-4">
                <div class="col-6">
                    <h4>Data Karya Ilmiah</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover align-middle border">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 50px;">No</th>
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
                                @forelse($articles as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @php
                                                $initial = substr($item->document_type, 0, 1);
                                                $color =
                                                    $item->document_type == 'Skripsi'
                                                        ? 'bg-primary'
                                                        : ($item->document_type == 'Tesis'
                                                            ? 'bg-danger'
                                                            : 'bg-success');
                                            @endphp
                                            <span class="badge {{ $color }} rounded-circle p-2"
                                                title="{{ $item->document_type }}"
                                                style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                {{ $initial }}
                                            </span>
                                        </td>
                                        <td class="small text-muted">{{ $item->created_at->format('d M Y') }}</td>
                                        <td>
                                            <small class="text-muted">{{ $item->study_program }} • Hukum •
                                                {{ $item->year }}</small>
                                            <div class="fw-bold text-dark">{{ $item->title }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $item->author }}</div>
                                        </td>
                                        <td>
                                            <span class="badge btn-outline-success border text-success px-2 py-1">
                                                {{ $item->access_type }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($item->status == 'published')
                                                <span class="badge bg-primary">Disetujui</span>
                                            @elseif($item->status == 'pending')
                                                <span class="badge bg-success">Menunggu</span>
                                            @elseif($item->status == 'rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @elseif($item->status == 'revision')
                                                <span class="badge bg-warning text-dark">Revisi</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item->status == 'revision')
                                                {{-- Tombol ini akan aktif jika Admin meminta revisi --}}
                                                <a href="{{ route('user.article.edit', $item->id) }}"
                                                    class="btn btn-danger btn-sm">Revisi</a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">Belum ada data karya ilmiah.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
