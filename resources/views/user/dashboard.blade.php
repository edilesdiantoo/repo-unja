dashboard.blade.php
@extends('layouts.user') {{-- Kita pinjam layout user dulu supaya tidak kosong --}}

@section('title', 'Selamat Datang')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card stat-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small>Total Karya</small>
                        <h4 class="fw-bold mb-0">{{ number_format($totalKarya, 0, ',', '.') }}</h4>
                    </div>
                    <i class="fa fa-book"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stat-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small>Unduhan</small>
                        <h4 class="fw-bold">{{ number_format($totalDownloadUser, 0, ',', '.') }}</h4>
                    </div>
                    <i class="fa fa-download"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="card stat-card p-4">
        <h5 class="fw-semibold mb-3">Aktivitas Terbaru</h5>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aktivitasTerbaru as $item)
                        <tr>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->author }}</td>
                            <td>{{ $item->created_at->format('d M Y') }}</td>
                            <td>
                                @if ($item->status == 'published')
                                    <span class="badge bg-success">Publish</span>
                                @elseif($item->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($item->status == 'revision')
                                    <span class="badge bg-info text-dark">Revisi</span> {{-- Tambahan untuk status Revisi --}}
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada karya yang diunggah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
