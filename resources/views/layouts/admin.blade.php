<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Repositori FH UNJA</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.1.7/css/dataTables.bootstrap5.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --unja-primary: #8B0000;
            --unja-secondary: #FF8C00;
            --unja-dark: #630000;
            font-size: 14px;
        }

        body {
            background-color: #f5f6fa;
        }

        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, var(--unja-dark), var(--unja-primary));
            color: #fff;
            position: fixed;
            left: 0;
            top: 0;
            transition: transform .3s ease-in-out;
            z-index: 1050;
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
            background: rgba(255, 140, 0, .2);
        }

        .content {
            margin-left: 260px;
            padding: 20px;
            transition: margin-left .3s ease-in-out;
        }

        .topbar {
            background: #fff;
            border-radius: 12px;
            padding: 12px 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
        }

        .stat-card {
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .08);
            border: none;
        }

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
    </style>
    @stack('styles')
</head>

<body>

    {{-- Sidebar --}}
    @include('layouts.partials.admin-sidebar')

    <div class="content">
        {{-- Topbar --}}
        @include('layouts.partials.admin-topbar')

        {{-- Isi Konten --}}
        <main class="mt-4">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jquery datatable -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.bootstrap5.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable()
        })

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
    </script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif
    @stack('scripts')
</body>

</html>
