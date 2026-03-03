@extends('layouts.web')

@section('title', 'Tentang Kami - Repository FH UNJA')

@section('extra-css')
    <style>
        /* Styling agar persis seperti Gambar 2 */
        .header-unja {
            position: relative;
            min-height: 300px;
            display: flex;
            align-items: center;
            background-color: var(--bs-unja-darker);
        }

        .header-unja .layer-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Pastikan gambar ini ada di public/assets/img/ library-bg.jpg */
            background-image: url('{{ asset('assets/img/bg1.jpg') }}');
            background-size: cover;
            background-position: center;

            opacity: 0.4;
        }

        .header-unja .layer-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, rgba(99, 0, 0, 0.9), rgba(139, 0, 0, 0.5));
        }

        .header-unja .layer-content {
            position: relative;
            z-index: 3;
        }

        .border-unja {
            border-left: 5px solid var(--bs-unja-secondary);
        }

        .list-icon {
            color: #28a745;
        }

        .prohibition-icon {
            color: #dc3545;
        }

        p {}
    </style>
@endsection

@section('content')
    <header class="header-unja">
        <div class="layer-bg"></div>
        <div class="layer-overlay"></div>

        <div class="container layer-content">
            <h1 class="display-6 fw-bold text-white">
                <i class="fas fa-balance-scale me-2"></i> Tentang Repositori Fakultas Hukum
            </h1>
            <p class="text-white text-opacity-75">Komitmen terhadap akses terbuka dan integritas karya ilmiah di bidang ilmu
                hukum</p>
        </div>
    </header>

    <main class="container py-5">
        <div class="row">
            <div class="col-12 mb-5">
                <h3 class="border-unja ps-3 mb-4 text-unja fw-bold">Apa itu Repositori Fakultas Hukum Universitas Jambi?
                </h3>
                <p style="text-align: justify; line-height: 1.7; color: #444;">
                    Repositori Fakultas Hukum Universitas Jambi merupakan layanan pengelolaan koleksi digital berbasis akses
                    terbuka yang menghimpun dan menyimpan karya ilmiah yang dihasilkan oleh dosen, tenaga kependidikan, dan
                    mahasiswa Fakultas Hukum Universitas Jambi.
                    <br><br>
                    Repositori ini memuat berbagai bentuk karya ilmiah dan luaran akademik yang dikelola secara sistematis
                    oleh Perpustakaan Universitas Jambi bekerja sama dengan unit terkait, khususnya dalam lingkup disiplin
                    ilmu hukum.
                    <br><br>
                    Tujuan pengelolaan repositori ini adalah untuk melestarikan, mengelola, dan menyebarluaskan karya ilmiah
                    secara berkelanjutan, sehingga hasil penelitian dan karya akademik dapat diakses secara luas oleh
                    sivitas akademika maupun masyarakat umum.
                </p>
            </div>

            <div class="col-12 mb-5">
                <h3 class="border-unja ps-3 mb-4 text-unja fw-bold">Jenis Karya Ilmiah yang Diakomodasi</h3>
                <p>Repositori Fakultas Hukum Universitas Jambi mengakomodasi berbagai jenis karya ilmiah dan luaran akademik
                    yang dihasilkan dalam lingkungan Fakultas Hukum, antara lain:</p>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item border-0 px-0">
                        <i class="far fa-check-circle list-icon me-2"></i> Publikasi ilmiah yang ditulis oleh dosen atau
                        peneliti dengan afiliasi Fakultas Hukum Universitas Jambi.
                    </li>
                    <li class="list-group-item border-0 px-0">
                        <i class="far fa-check-circle list-icon me-2"></i> Skripsi, tesis dan disertasi yang diselesaikan
                        oleh mahasiswa pada jenjang pendidikan di Fakultas Hukum Universitas Jambi.
                    </li>
                    <li class="list-group-item border-0 px-0">
                        <i class="far fa-check-circle list-icon me-2"></i> Laporan magang atau kerja praktik yang dihasilkan
                        oleh mahasiswa sebagai bagian dari pemenuhan kewajiban akademik.
                    </li>
                </ul>
            </div>

            <div class="col-12">
                <h3 class="border-unja ps-3 mb-4 text-unja fw-bold">Karya yang Tidak Dapat Diunggah ke Repositori</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item border-0 px-0">
                        <i class="far fa-times-circle prohibition-icon me-2"></i> Karya yang melanggar ketentuan hak cipta
                        atau peraturan perundang-undangan yang berlaku.
                    </li>
                    <li class="list-group-item border-0 px-0">
                        <i class="far fa-times-circle prohibition-icon me-2"></i> Karya yang ditujukan untuk kepentingan
                        komersial dan tidak diperkenankan untuk disebarluaskan secara terbuka.
                    </li>
                    <li class="list-group-item border-0 px-0">
                        <i class="far fa-times-circle prohibition-icon me-2"></i> Karya yang mengandung data bersifat
                        rahasia, sensitif, atau konfidensial.
                    </li>
                    <li class="list-group-item border-0 px-0">
                        <i class="far fa-times-circle prohibition-icon me-2"></i> Karya yang tidak dapat diunggah atau
                        dikelola secara teknis karena keterbatasan atau ketidaksesuaian dengan sistem repositori.
                    </li>
                </ul>
            </div>
        </div>
    </main>
@endsection
