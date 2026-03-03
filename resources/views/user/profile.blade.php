@extends('layouts.user') {{-- Sesuaikan nama layout user kamu --}}

@section('content')
    <div class="container py-4">
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
        @endif

        <div class="card shadow rounded-4 border-0">
            <div class="card-body p-4">
                <div class="row align-items-center mb-4">
                    <div class="col-6">
                        <h4 class="fw-bold"><i class="fas fa-user-cog me-2 text-primary"></i>Pengaturan Profil</h4>
                    </div>
                </div>

                <form method="POST" action="{{ route('user.profile.update') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="inputNama" class="form-label fw-bold">Nama Lengkap <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror"
                                id="inputNama" name="nama_lengkap" value="{{ old('nama_lengkap', $user->name) }}" required>
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="inputIdentitas" class="form-label fw-bold">Nomor Identitas (NIM/NIDN) <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('identity_number') is-invalid @enderror"
                                id="inputIdentitas" name="identity_number"
                                value="{{ old('identity_number', $user->identity_number) }}" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="inputEmail" class="form-label fw-bold">Email <span
                                    class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="inputEmail"
                                name="email" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="col-md-12 mb-4">
                            <label for="inputPassword" class="form-label fw-bold">Password Baru</label>
                            <input type="password" class="form-control" id="inputPassword" name="password"
                                placeholder="Kosongkan jika tidak ingin mengubah password">
                            <small class="text-muted">Minimal 6 karakter.</small>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-danger px-4 shadow-sm">Simpan Perubahan</button>
                            <a href="{{ route('user.dashboard') }}" class="btn btn-secondary px-4 shadow-sm">Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
