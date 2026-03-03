<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Repository FH UNJA</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        :root {
            --bs-unja-primary: #8B0000;
            --bs-unja-secondary: #FF8C00;
            --bs-unja-light-shade: #B22222;
            --bs-unja-darker: #630000;
        }

        .bg-unja-hero {
            background: linear-gradient(135deg, var(--bs-unja-darker) 0%, var(--bs-unja-primary) 50%, var(--bs-unja-light-shade) 100%) !important;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .reset-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.2);
        }

        .text-unja {
            color: var(--bs-unja-primary) !important;
        }

        .btn-unja {
            background-color: var(--bs-unja-primary);
            border: none;
            color: white;
        }

        .btn-unja:hover {
            background-color: var(--bs-unja-darker);
            color: white;
        }
    </style>
</head>

<body class="bg-unja-hero">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="card reset-card p-4">
                    <div class="card-body">

                        <div class="text-center mb-4">
                            <img src="https://law.unja.ac.id/wp-content/uploads/2025/11/cropped-cropped-cropped-Blue-Green-Red-and-Pink-Playful-Creative-Studio-Logo-7-1-768x249.png"
                                alt="Logo FH UNJA" width="70%" class="mb-3">
                            <h4 class="text-unja fw-bold">Reset Password</h4>
                            <p class="text-muted small">Masukkan NIM atau NIDN Anda untuk memverifikasi akun.</p>
                        </div>

                        {{-- Alert Error/Success --}}
                        @if (session('status'))
                            <div class="alert alert-success small">{{ session('status') }}</div>
                        @endif
                        @if ($errors->has('identity_number'))
                            <div class="alert alert-danger small">{{ $errors->first('identity_number') }}</div>
                        @endif

                        <form action="{{ route('password.email') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="nim_nidn" class="form-label small fw-medium">
                                    <i class="far fa-id-card me-1 text-unja"></i> No Identitas (NIM/NIDN)
                                </label>
                                <input type="text" name="identity_number"
                                    class="form-control @error('identity_number') is-invalid @enderror" id="nim_nidn"
                                    placeholder="Contoh: B10021001" value="{{ old('identity_number') }}" required>
                            </div>

                            <button type="submit" class="btn btn-unja w-100 fw-bold py-2 mb-3 shadow-sm">
                                <i class="far fa-paper-plane me-2"></i> KIRIM PERMINTAAN
                            </button>

                            <div class="text-center">
                                <a class="text-danger small text-decoration-none" href="{{ route('login') }}">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali ke halaman login
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
