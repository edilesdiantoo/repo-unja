@extends('layouts.admin')

@section('title', 'Tambah Pengguna Baru')

@section('content')
    <div class="card shadow rounded-4 border-0">
        <div class="card-body">
            <div class="row align-items-center g-4 mb-4">
                <div class="col-6">
                    <h4>Tambah Pengguna</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf {{-- Wajib untuk keamanan di Laravel --}}

                        {{-- Peran Pengguna --}}
                        <div class="mb-3">
                            <label for="inputRole" class="form-label fw-bold">Peran Pengguna <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" id="inputRole" name="role"
                                required>
                                <option value="">Pilih</option>
                                <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Mahasiswa</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Superadmin
                                </option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nama Lengkap --}}
                        <div class="mb-3">
                            <label for="inputNama" class="form-label fw-bold">Nama Lengkap <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="inputNama"
                                name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nomor Identitas --}}
                        <div class="mb-3">
                            <label for="inputIdentitas" class="form-label fw-bold">Nomor Identitas (NIM/NIDN) <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('identity_number') is-invalid @enderror"
                                id="inputIdentitas" name="identity_number" value="{{ old('identity_number') }}"
                                placeholder="Masukkan NIM atau NIDN" required>
                            @error('identity_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Gunakan NIM untuk Mahasiswa dan NIDN untuk Dosen.</small>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="inputEmail" class="form-label fw-bold">Email <span
                                    class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="inputEmail"
                                name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="inputPassword" class="form-label fw-bold">Password <span
                                    class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="inputPassword" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary px-4">Simpan</button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary px-4">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
