<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Repository Fakultas Hukum UNJA</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        :root {
            --bs-unja-primary: #8B0000;
            --bs-unja-secondary: #FF8C00;
            --bs-unja-light-shade: #B22222;
            --bs-unja-darker: #630000;
            --bs-unja-accent: #f8f9fa;
        }

        .bg-unja-hero {
            background: linear-gradient(135deg, var(--bs-unja-darker) 0%, var(--bs-unja-primary) 50%, var(--bs-unja-light-shade) 100%) !important;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .register-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.2);
        }

        .text-unja {
            color: var(--bs-unja-primary) !important;
        }
    </style>
</head>

<body class="bg-unja-hero">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="card register-card p-4">
                    <div class="card-body">

                        <div class="text-center mb-3">
                            <img src="{{ asset('assets/img/logounjahukum.png') }}" style="width: 110px;" class="mb-2"
                                alt="Logo UNJA">
                            <h4 class="text-unja fw-bold mb-1">Registrasi Akun</h4>
                            <div class="text-muted small">Lengkapi data identitas civitas akademika Fakultas Hukum UNJA
                            </div>
                        </div>

                        <form action="{{ route('register') }}" method="POST">
                            @csrf

                            {{-- Nama Lengkap --}}
                            <div class="mb-3">
                                <label for="name" class="form-label small fw-medium">
                                    <i class="far fa-user me-1 text-unja"></i> Nama Lengkap
                                </label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                    value="{{ old('name') }}" placeholder="Contoh: Edi Lesdianto" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- NIM / NIDN (identity_number) --}}
                            <div class="mb-3">
                                <label for="identity_number" class="form-label small fw-medium">
                                    <i class="far fa-id-card me-1 text-unja"></i> NIM / NIDN
                                </label>
                                <input type="text" name="identity_number"
                                    class="form-control @error('identity_number') is-invalid @enderror"
                                    id="identity_number" value="{{ old('identity_number') }}"
                                    placeholder="Nomor Induk Mahasiswa / Dosen" required>
                                @error('identity_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-medium">Status Civitas</label>
                                <select name="role" class="form-select" required>
                                    <option value="user">Mahasiswa</option>
                                    <option value="dosen">Dosen / Tenaga Pengajar</option>
                                </select>
                            </div>

                            {{-- Email Kampus / Aktif --}}
                            <div class="mb-3">
                                <label for="email" class="form-label small fw-medium">
                                    <i class="far fa-envelope me-1 text-unja"></i> Alamat Email
                                </label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" id="email"
                                    value="{{ old('email') }}" placeholder="nama@mail.unja.ac.id" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="mb-3">
                                <label for="password" class="form-label small fw-medium">
                                    <i class="fas fa-key me-1 text-unja"></i> Password
                                </label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" id="password"
                                    placeholder="Minimum 8 karakter" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label small fw-medium">
                                    <i class="fas fa-check-double me-1 text-unja"></i> Konfirmasi Password
                                </label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    id="password_confirmation" placeholder="Ulangi password di atas" required>
                            </div>

                            <button type="submit" class="btn btn-danger w-100 fw-bold py-2 mb-3 mt-2"
                                style="background-color: #8B0000;">
                                <i class="fas fa-user-plus me-2"></i> DAFTAR SEKARANG
                            </button>

                            <div class="text-center">
                                <span class="small text-muted">Sudah punya akun?</span>
                                <a href="{{ route('login') }}"
                                    class="text-danger small fw-bold text-decoration-none">Login di sini</a>
                            </div>
                        </form>

                    </div>
                </div>
                <div class="text-white text-center pt-3">
                    <small>© {{ date('Y') }} Fakultas Hukum Universitas Jambi</small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
