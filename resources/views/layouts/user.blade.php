<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Repositori Fakultas Hukum UNJA - Karya Ilmiah Hukum</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <style>
        :root {
            --unja-primary: #8b0000;
            --unja-secondary: #ff8c00;
            --unja-dark: #630000;
            font-size: 14px;
        }

        body {
            background-color: #f5f6fa;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg,
                    var(--unja-dark),
                    var(--unja-primary));
            color: #fff;
            position: fixed;
            left: 0;
            top: 0;
            transition: transform 0.3s ease-in-out;
            z-index: 1050;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
        }

        .sidebar .menu a {
            display: block;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 5px;
        }

        .sidebar .menu a:hover,
        .sidebar .menu a.active {
            background: rgba(255, 140, 0, 0.2);
        }

        /* Content */
        .content {
            margin-left: 260px;
            padding: 20px;
            transition: margin-left 0.3s ease-in-out;
        }

        /* Topbar */
        .topbar {
            background: #fff;
            border-radius: 12px;
            padding: 12px 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* Mobile */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
            }
        }

        .stat-card {
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .bg-unja-hero {
            /* GRADIENT MERAH BARU */
            background: linear-gradient(135deg, var(--bs-unja-darker) 0%, var(--bs-unja-primary) 50%, var(--bs-unja-light-shade) 100%) !important;

            background-size: cover;
            background-position: center;
        }

        .text-unja {
            /* Teks utama (H2, H3, Link) -> Merah Maroon */
            color: var(--bs-unja-primary) !important;
        }

        .text-unja-secondary {
            /* Teks Aksen (Judul Hero) -> Oranye/Emas */
            color: var(--bs-unja-secondary) !important;
        }

        .border-unja {
            /* Border samping -> Oranye/Emas */
            border-left: 5px solid var(--bs-unja-secondary);
        }

        .ranking-box {
            /* DIUBAH MENJADI BACKGROUND TERANG agar mudah dibaca di atas gradien gelap, seperti di gambar */
            background-color: #660000;
            border-radius: .5rem;
            backdrop-filter: blur(5px);
            padding: 20px;
            border: 1px solid var(--bs-unja-secondary);
            color: #000;
            /* Pastikan teks di ranking box gelap */
        }

        /* Style tambahan untuk elemen di dalam ranking box */
        .ranking-box h4,
        .ranking-box .badge {
            color: #000 !important;
            /* Memastikan teks gelap */
        }

        .ranking-box .badge {
            background-color: var(--bs-unja-secondary) !important;
            /* Badge menggunakan warna Oranye/Emas */
        }

        /* Mengganti warna tombol Success (Hijau) ke warna Primary (Merah) */
        .btn-unja-primary,
        .btn-primary,
        .btn-success {
            background-color: var(--bs-unja-primary) !important;
            border-color: var(--bs-unja-primary) !important;
            color: white !important;
        }

        .btn-outline-success {
            border-color: var(--bs-unja-primary) !important;
            color: var(--bs-unja-primary) !important;
        }

        .header-unja {
            position: relative;
            width: 100%;
            min-height: 400px;
            display: flex;
            align-items: center;
            /* Teks ke tengah vertikal */
            overflow: hidden;
            color: white;
        }

        .layer-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('assets/img/bg1.jpg');
            background-size: cover;
            background-position: center;
            z-index: 1;
        }

        .layer-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Gunakan gradient Anda di sini */
            background: linear-gradient(135deg,
                    rgba(102, 0, 0, 0.9) 0%,
                    rgba(153, 0, 0, 0.8) 50%,
                    rgba(204, 51, 51, 0.6) 100%) !important;
            z-index: 2;
        }

        .layer-content {
            position: relative;
            z-index: 3;
            /* Angka paling tinggi agar di atas semua */
            padding-top: 50px;
            padding-bottom: 50px;
        }
    </style>
    @stack('styles')
</head>

<body>

    <div class="container-fluid">
        @include('layouts.partials.user-sidebar')

        <div class="content">
            @include('layouts.partials.user-topbar')

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
    </script>
    @stack('scripts')
</body>

</html>
