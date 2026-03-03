<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password Baru - Repository FH UNJA</title>

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

        .update-card {
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
                <div class="card update-card p-4">
                    <div class="card-body">

                        <div class="text-center mb-4">
                            <img src="https://law.unja.ac.id/wp-content/uploads/2025/11/cropped-cropped-cropped-Blue-Green-Red-and-Pink-Playful-Creative-Studio-Logo-7-1-768x249.png"
                                alt="Logo FH UNJA" width="60%" class="mb-3">
                            <h4 class="text-unja fw-bold">Atur Password Baru</h4>
                            <p class="text-muted small">Mengubah password untuk: <span
                                    class="badge bg-secondary">{{ session('reset_identity') }}</span></p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger small">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="newPassword" class="form-label small fw-medium">
                                    <i class="fas fa-key me-1 text-unja"></i> Password Baru
                                </label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control" id="newPassword"
                                        placeholder="Minimum 8 karakter" required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Input konfirmasi password (opsional tapi disarankan) --}}
                            <div class="mb-4">
                                <label for="confirmPassword" class="form-label small fw-medium text-unja">Konfirmasi
                                    Password</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    id="confirmPassword" placeholder="Ulangi password baru" required>
                            </div>

                            <button type="submit" class="btn btn-danger w-100 fw-bold py-2 mb-3 shadow">
                                <i class="fas fa-redo-alt me-2"></i> GANTI PASSWORD
                            </button>

                            <div class="text-center">
                                <a class="text-danger small text-decoration-none"
                                    href="{{ route('password.request') }}">Kembali ke halaman sebelumnya</a>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setupPasswordToggle(inputId, toggleButtonId) {
            const passwordInput = document.getElementById(inputId);
            const toggleButton = document.getElementById(toggleButtonId);

            if (passwordInput && toggleButton) {
                toggleButton.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);

                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('fa-eye');
                        icon.classList.toggle('fa-eye-slash');
                    }
                });
            }
        }
        setupPasswordToggle('newPassword', 'toggleNewPassword');
    </script>
</body>

</html>
