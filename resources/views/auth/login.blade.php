<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Repository Fakultas Hukum UNJA</title>

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
        }

        .login-card {
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
            <div class="col-md-7 col-lg-5">
                <div class="card login-card p-4">
                    <div class="card-body">

                        <div class="text-center mb-3">
                            {{-- Gunakan asset() agar path gambar benar --}}
                            <img src="{{ asset('assets/img/logounjahukum.png') }}" style="width: 125px;" class="mb-3"
                                alt="Logo UNJA">
                            <h4 class="text-unja fw-bold">Login Sistem Repositori Karya Ilmiah</h4>
                            <div class="text-muted small">Fakultas Hukum Universitas Jambi <br> Masuk Menggunakan Akun
                                Civitas Akademika Untuk Mengakses Sistem Repositor</div>
                        </div>

                        {{-- Menampilkan Pesan Error jika login gagal --}}
                        @if (session('error'))
                            <div class="alert alert-danger py-2 small text-center">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('login') }}" method="POST">
                            @csrf {{-- Token keamanan wajib Laravel --}}

                            <div class="mb-3">
                                <label for="login" class="form-label small fw-medium">
                                    <i class="far fa-user me-1 text-unja"></i> Username / Email
                                </label>
                                <input type="text" name="login"
                                    class="form-control @error('login') is-invalid @enderror" id="login"
                                    value="{{ old('login') }}" placeholder="NIM / NIDN / Email" required autofocus>
                                @error('login')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label for="password" class="form-label small fw-medium">
                                    <i class="fas fa-key me-1 text-unja"></i> Password
                                </label>
                                <div class="input-group">
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror" id="password"
                                        placeholder="Minimum 8 karakter" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 text-end">
                                <a href="{{ route('password.request') }}"
                                    class="text-danger small text-decoration-none">Lupa Password?</a>
                            </div>

                            <button type="submit" class="btn btn-danger w-100 fw-bold py-2 mb-3"
                                style="background-color: #8B0000;">
                                <i class="fas fa-sign-in-alt me-2"></i> LOGIN
                            </button>

                            <div class="text-center">
                                <a class="text-danger small text-decoration-none" href="/">Kembali ke halaman
                                    beranda</a>
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

    <script>
        // Script untuk Toggle Password
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function(e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>
