<!DOCTYPE html>
<html>

<head>
    <title>Laporan Data Karya Ilmiah</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        .text-center {
            text-align: center;
        }

        .mb-4 {
            margin-bottom: 1.5rem;
        }

        .mt-5 {
            margin-top: 3rem;
        }

        hr {
            border: 0.5px solid #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th {
            background-color: #f2f2f2;
            padding: 8px;
            font-size: 10px;
        }

        td {
            padding: 6px;
            vertical-align: top;
        }

        .footer-table {
            border: none !important;
            width: 100%;
            margin-top: 50px;
        }

        .footer-table td {
            border: none !important;
        }
    </style>
</head>

<body>

    <div class="report-header text-center mb-4">
        <h3 style="margin-bottom: 5px; text-transform: uppercase;">Laporan Data Karya Ilmiah</h3>
        <h4 style="margin-bottom: 5px; font-weight: normal;">REPOSITORI FAKULTAS HUKUM UNIVERSITAS JAMBI</h4>
        <p style="font-size: 10px; color: #555;">
            Dicetak pada: {{ now()->translatedFormat('d F Y') }} | Oleh: {{ Auth::user()->name }}
        </p>
        <hr>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="10%">Publikasi</th>
                <th>Judul</th>
                <th width="12%">Penulis</th>
                <th width="10%">Program Studi</th>
                <th width="15%">Pembimbing</th>
                <th width="8%">Akreditasi</th>
                <th width="7%">Akses</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($results as $index => $article)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($article->created_at)->format('d/m/Y') }}</td>
                    <td>{{ $article->title }}</td>
                    <td>{{ $article->author }}</td>
                    <td>{{ $article->study_program }}</td>
                    <td>
                        {{-- Menggunakan nama variabel pembimbing sesuai permintaan --}}
                        1. {{ $article->pembimbing_1 ?? '-' }}<br>
                        2. {{ $article->pembimbing_2 ?? '-' }}
                    </td>
                    <td class="text-center">{{ $article->accreditation_level }}</td>
                    <td class="text-center">{{ $article->access_type }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Data tidak ditemukan untuk filter ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        <p>Total Data: <strong>{{ count($results) }}</strong> Dokumen</p>
    </div>

    <table class="footer-table">
        <tr>
            <td width="60%">
                <p style="font-style: italic; font-size: 9px;">
                    * Laporan ini dihasilkan secara otomatis oleh sistem Repositori FH UNJA.<br>
                    * Dokumen ini sah dan diakui sebagai laporan internal Fakultas Hukum.
                </p>
            </td>
            <td width="40%" class="text-center">
                <p>
                    Jambi, {{ now()->translatedFormat('d F Y') }}<br>
                    <strong>Mengetahui,</strong><br>
                    Admin Repositori FH UNJA
                </p>
                <br><br><br><br>
                {{-- Nama diambil dari user yang sedang login atau bisa diketik manual --}}
                <p><strong>( {{ Auth::user()->name }} )</strong></p>
                <p>NIP. {{ Auth::user()->identity_number ?? '____________________' }}</p>
            </td>
        </tr>
    </table>

</body>

</html>
