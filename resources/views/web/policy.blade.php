@extends('layouts.web')

@section('title', 'Panduan & Kebijakan - Repository FH UNJA')

@section('extra-css')
    <style>
        /* Styling persis seperti struktur About yang kamu kirim sebelumnya */
        .header-unja {
            position: relative;
            min-height: 300px;
            display: flex;
            align-items: center;
            background-color: #630000;
        }

        .header-unja .layer-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
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
            border-left: 5px solid #ffc107;
        }

        .step-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .step-number {
            width: 30px;
            height: 30px;
            background-color: #630000;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .section-title {
            border-left: 5px solid #ffc107;
            padding-left: 15px;
            margin-bottom: 25px;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <header class="header-unja">
        <div class="layer-bg"></div>
        <div class="layer-overlay"></div>

        <div class="container layer-content">
            <h1 class="display-6 fw-bold text-white">
                <i class="fas fa-shield-alt me-2"></i>PANDUAN & KEBIJAKAN
            </h1>
            <p class="text-white text-opacity-75">Repositori Ilmiah Fakultas Hukum Universitas Jambi</p>
        </div>
    </header>

    <main class="container py-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="tab-content" id="v-pills-tabContent">

                    <div class="tab-pane mb-5 fade show active" id="panduan">
                        <div class="card rounded-3 border-0 p-0">
                            <h3 class="section-title">Panduan Unggah Karya Ilmiah</h3>

                            <h5 class="fw-bold mt-4">A. Ketentuan Umum</h5>
                            <p class="text-muted" style="text-align: justify;">Repositori Ilmiah Fakultas Hukum Universitas
                                Jambi digunakan untuk menyimpan dan mendiseminasikan karya ilmiah civitas akademika secara
                                digital. Setiap pengguna wajib memperhatikan ketentuan sebelum melakukan unggah dokumen.</p>

                            <h5 class="fw-bold mt-4">B. Jenis Dokumen</h5>
                            <div class="row g-2 mb-4">
                                <div class="col-md-4">
                                    <div class="p-2 border rounded shadow-sm text-center">Skripsi</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-2 border rounded shadow-sm text-center">Tesis</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-2 border rounded shadow-sm text-center">Disertasi</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-2 border rounded shadow-sm text-center">Artikel Jurnal</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-2 border rounded shadow-sm text-center">Laporan Penelitian</div>
                                </div>
                            </div>

                            <h5 class="fw-bold mt-4">C. Format & Ketentuan File</h5>
                            <div class="alert alert-info border-0">
                                <i class="fas fa-info-circle me-2"></i> Format dokumen wajib <strong>PDF</strong> dengan
                                ukuran file sesuai kapasitas sistem.
                            </div>
                            <ul class="list-group list-group-flush mb-4">
                                <li class="list-group-item border-0 px-0"><i class="fas fa-check text-success me-2"></i>
                                    Bebas dari Plagiarisme</li>
                                <li class="list-group-item border-0 px-0"><i class="fas fa-check text-success me-2"></i>
                                    Tidak melanggar hukum</li>
                                <li class="list-group-item border-0 px-0"><i class="fas fa-check text-success me-2"></i>
                                    Bukan informasi rahasia</li>
                            </ul>

                            <h5 class="fw-bold mt-4">D. Tahapan Unggah</h5>
                            <div class="step-item">
                                <div class="step-number">1</div>
                                <h6>Pilih Menu Upload Dokumen</h6>
                            </div>
                            <div class="step-item">
                                <div class="step-number">2</div>
                                <h6>Unggah File Utama (PDF)</h6>
                            </div>
                            <div class="step-item">
                                <div class="step-number">3</div>
                                <h6>Lengkapi Metadata (Judul, Penulis, Abstrak, Bidang Hukum)</h6>
                            </div>
                            <div class="step-item">
                                <div class="step-number">4</div>
                                <h6>Simpan dan Kirim ke Validator</h6>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane mb-5 fade show active" id="template">
                        <div class="card rounded-3 border-0 p-4 text-center bg-light">
                            <i class="fas fa-file-word fa-4x text-primary mb-3"></i>
                            <h3 class="fw-bold">Template Skripsi FH UNJA</h3>
                            <p class="text-muted mx-auto" style="max-width: 600px;">Gunakan template ini untuk memastikan
                                keseragaman format penulisan karya ilmiah sesuai standar akademik Fakultas Hukum.</p>

                            <a href="{{ asset('assets/file/panduan.pdf') }}" target="_blank"
                                class="btn btn-primary mt-4 py-3 px-5 rounded-pill"><i class="fas fa-download me-2"></i>
                                Download Template</a>
                        </div>
                    </div>

                    <div class="tab-pane mb-4 fade show active" id="kebijakan">
                        <div class="card rounded-3 border-0 p-0">
                            <h3 class="section-title">Kebijakan Repositori Ilmiah</h3>

                            <div class="accordion accordion-flush border rounded" id="policyAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#c1">
                                            A. Prinsip Akses Terbuka
                                        </button>
                                    </h2>
                                    <div id="c1" class="accordion-collapse collapse show">
                                        <div class="accordion-body text-muted small">Menerapkan prinsip open access untuk
                                            kepentingan pendidikan dan penelitian global.</div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-bold" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#c2">
                                            B. Hak Cipta
                                        </button>
                                    </h2>
                                    <div id="c2" class="accordion-collapse collapse">
                                        <div class="accordion-body text-muted small">Hak cipta tetap pada penulis. Penulis
                                            memberikan izin non-eksklusif kepada fakultas untuk mendigitalisasi karya.</div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-bold" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#c3">
                                            C. Tanggung Jawab Penulis
                                        </button>
                                    </h2>
                                    <div id="c3" class="accordion-collapse collapse">
                                        <div class="accordion-body text-muted small">Penulis bertanggung jawab atas keaslian
                                            karya dan kepatuhan terhadap etika akademik.</div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-bold" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#c4">
                                            D. Pembatasan Akses & Sanksi
                                        </button>
                                    </h2>
                                    <div id="c4" class="accordion-collapse collapse">
                                        <div class="accordion-body text-muted small">
                                            Fakultas berhak menarik dokumen jika ditemukan pelanggaran hukum/plagiarisme.
                                            Penyalahgunaan sistem dapat dikenakan sanksi akademik sesuai peraturan UNJA.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection
