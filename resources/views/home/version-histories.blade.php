@extends('layouts.v3.master')

@section('content')
    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Sejarah Versi Sistem</h3>
            <p class="text-muted small m-0">Rekod penambahbaikan dan perubahan Sistem Tender Selangor dari semasa ke semasa.</p>
        </div>
    </div>

    <div class="content-card">
        <div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="12 8 12 12 14 14"></polyline>
                <circle cx="12" cy="12" r="10"></circle>
            </svg>
            <span class="fw-bold text-dark text-uppercase small">Log Perubahan</span>
        </div>

        <div class="p-4">
            <div class="version-timeline">

                <!-- v1.3 -->
                <div class="version-entry">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="version-badge">v1.3</span>
                        <div>
                            <div class="fw-semibold text-dark small">4 September 2017</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Penambahbaikan Modul UPEN</div>
                        </div>
                        <span class="ms-auto badge rounded-pill text-bg-secondary" style="font-size: 0.7rem;">6 perubahan</span>
                    </div>
                    <div class="version-notes ps-2">
                        <p class="fw-medium small text-dark mb-2">Penambahbaikan Modul UPEN</p>
                        <ol class="ps-3 mb-0">
                            <li class="small text-muted mb-1">Menolak pendaftaran kontraktor</li>
                            <li class="small text-muted mb-1">Menerima pendaftaran kontraktor</li>
                            <li class="small text-muted mb-1">Menolak permintaan perubahan kontraktor</li>
                            <li class="small text-muted mb-1">Menerima permintaan perubahan kontraktor</li>
                            <li class="small text-muted mb-1">Menyenarai hitam kontraktor</li>
                            <li class="small text-muted mb-1">Tetapan peranan</li>
                        </ol>
                    </div>
                </div>

                <!-- v1.2 -->
                <div class="version-entry">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="version-badge">v1.2</span>
                        <div>
                            <div class="fw-semibold text-dark small">14 Oktober 2016</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Penambahbaikan pelbagai modul</div>
                        </div>
                        <span class="ms-auto badge rounded-pill text-bg-secondary" style="font-size: 0.7rem;">14 perubahan</span>
                    </div>
                    <div class="version-notes ps-2">
                        <ol class="ps-3 mb-0">
                            <li class="small text-muted mb-1">Halang Transaksi Pembayaran Agensi</li>
                            <li class="small text-muted mb-1">Mengemaskini semula data-data maklumat hubungan kontraktor</li>
                            <li class="small text-muted mb-1">Semakan pendaftaran syarikat</li>
                            <li class="small text-muted mb-1">Buang agensi pengesahan</li>
                            <li class="small text-muted mb-1">Paparan kod bidang CIDB</li>
                            <li class="small text-muted mb-1">Maklumat ralat</li>
                            <li class="small text-muted mb-1">Muat turun laporan dalam format Excel</li>
                            <li class="small text-muted mb-1">Laporan syarikat berdasarkan kod bidang</li>
                            <li class="small text-muted mb-1">Laporan produktiviti Staff</li>
                            <li class="small text-muted mb-1">Paparan notifikasi kod bidang tidak layak</li>
                            <li class="small text-muted mb-1">Penukaran alamat emel oleh pegawai syarikat</li>
                            <li class="small text-muted mb-1">Paparan status pembayaran sewaktu transaksi</li>
                            <li class="small text-muted mb-1">Medan "Daerah" dalam data syarikat</li>
                            <li class="small text-muted mb-1">Muat naik kehadiran syarikat ke taklimat &amp; lawatan tapak</li>
                        </ol>
                    </div>
                </div>

                <!-- v1.1 -->
                <div class="version-entry">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="version-badge">v1.1</span>
                        <div>
                            <div class="fw-semibold text-dark small">1 November 2015</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Penambahbaikan ciri-ciri sistem</div>
                        </div>
                        <span class="ms-auto badge rounded-pill text-bg-secondary" style="font-size: 0.7rem;">2 perubahan</span>
                    </div>
                    <div class="version-notes ps-2">
                        <ol class="ps-3 mb-0">
                            <li class="small text-muted mb-1">Masukkan Syarikat Tidak Layak Tender / Sebut Harga Menggunakan Fungsi Kebenaran Khas</li>
                            <li class="small text-muted mb-1">Cetak Resit Pembayaran Untuk Tender / Sebut Harga Secara Pukal</li>
                        </ol>
                    </div>
                </div>

                <!-- v1.0 -->
                <div class="version-entry version-entry--last">
                    <div class="d-flex align-items-center gap-3">
                        <span class="version-badge version-badge--launch">v1.0</span>
                        <div>
                            <div class="fw-semibold text-dark small">8 Jun 2015</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Pelancaran sistem</div>
                        </div>
                        <span class="ms-auto badge rounded-pill text-bg-success" style="font-size: 0.7rem;">Live</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .version-timeline {
            position: relative;
        }

        .version-timeline::before {
            content: '';
            position: absolute;
            left: 17px;
            top: 28px;
            bottom: 28px;
            width: 2px;
            background: linear-gradient(to bottom, var(--sg-red, #c0392b), #dee2e6);
        }

        .version-entry {
            position: relative;
            padding-left: 52px;
            padding-bottom: 2rem;
        }

        .version-entry--last {
            padding-bottom: 0;
        }

        .version-entry::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 7px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--sg-red, #c0392b);
            border: 3px solid #fff;
            box-shadow: 0 0 0 2px var(--sg-red, #c0392b);
        }

        .version-entry--last::before {
            background: #6c757d;
            box-shadow: 0 0 0 2px #6c757d;
        }

        .version-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--sg-red, #c0392b);
            color: #fff;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            padding: 3px 10px;
            border-radius: 20px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .version-badge--launch {
            background: #6c757d;
        }

        .version-notes {
            border-left: 3px solid #f1f3f4;
            padding-left: 1rem !important;
            margin-top: 0.25rem;
        }
    </style>
@endsection
