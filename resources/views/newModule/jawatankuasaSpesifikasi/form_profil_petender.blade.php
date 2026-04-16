@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/guideline-card.css') }}" rel="stylesheet">
    <style>
        /* ── Table grid borders for profil table ──────────────────────── */
        #tbl-profil {
            border: 1px solid #e2e8f0;
        }
        #tbl-profil th,
        #tbl-profil td {
            border-right: 1px solid #e2e8f0 !important;
        }
        #tbl-profil th:last-child,
        #tbl-profil td:last-child {
            border-right: none !important;
        }

        /* Sub-field row styling inside Butiran cells */
        .sub-field-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sub-field-row + .sub-field-row {
            margin-top: 8px;
        }
        .sub-field-label {
            flex-shrink: 0;
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
            min-width: 110px;
        }
        .sub-field-input {
            flex: 1;
            min-width: 0;
        }

        /* Numbered list items inside Butiran (aset / peralatan) */
        .numbered-input-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .numbered-input-row + .numbered-input-row {
            margin-top: 6px;
        }
        .numbered-input-num {
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #f1f5f9;
            font-size: 0.65rem;
            font-weight: 700;
            color: #64748b;
        }
        .numbered-input-row .form-control {
            flex: 1;
            min-width: 0;
        }

        /* ── Projek table borders ──────────────────────────────────── */
        #tbl-projek {
            border: 1px solid #e2e8f0;
        }
        #tbl-projek th,
        #tbl-projek td {
            border-right: 1px solid #e2e8f0 !important;
        }
        #tbl-projek th:last-child,
        #tbl-projek td:last-child {
            border-right: none !important;
        }

        /* ── Analisa tables borders ────────────────────────────────── */
        #tbl-modal-berbayar,
        #tbl-modal-dibenarkan {
            border: 1px solid #e2e8f0;
        }
        #tbl-modal-berbayar th, #tbl-modal-berbayar td,
        #tbl-modal-dibenarkan th, #tbl-modal-dibenarkan td {
            border-right: 1px solid #e2e8f0 !important;
        }
        #tbl-modal-berbayar th:last-child, #tbl-modal-berbayar td:last-child,
        #tbl-modal-dibenarkan th:last-child, #tbl-modal-dibenarkan td:last-child {
            border-right: none !important;
        }
    </style>
@endsection

@section('content')

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Maklumat Profil Petender</h3>
            <p class="text-muted small m-0">Isi maklumat profil syarikat petender bagi tender / sebutharga ini.</p>
        </div>
    </div>

    <form id="form-profil-petender" action="{{ route('jawatankuasa.hantarProfilPetender') }}" method="POST">
    @csrf

        <!-- TENDER INFO CARD -->
        <div class="content-card mb-4 p-0">
            <div class="content-card-body p-4">

                <!-- Tajuk Tender -->
                <div class="mb-3 pb-3 border-bottom">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size: 0.67rem; letter-spacing: 0.5px;">Tajuk Tender</span>
                    <h5 class="fw-bold text-dark mb-0" style="line-height: 1.45; font-size: 1rem;">
                        TENDER PERKHIDMATAN DIGITAL FORENSIK
                    </h5>
                </div>

                <!-- Metadata: No. Tender · PTJ · Status -->
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                            style="font-size: 0.67rem; letter-spacing: 0.5px;">No. Tender</span>
                        <span class="fw-semibold text-dark" style="font-size: 0.875rem;">T/2026/014</span>
                    </div>
                    <div class="col-6 col-md-3">
                        <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                            style="font-size: 0.67rem; letter-spacing: 0.5px;">PTJ</span>
                        <span class="fw-semibold text-dark" style="font-size: 0.875rem;">100-007</span>
                    </div>
                    <div class="col-12 col-md-6 d-md-flex justify-content-md-end align-items-md-center">
                        <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2 fw-semibold"
                            style="background: #fef9c3; color: #854d0e; font-size: 0.8rem; border: 1px solid #fde68a;">
                            <span class="d-inline-block rounded-circle"
                                style="width:7px;height:7px;background:#ca8a04;flex-shrink:0;"></span>
                            Dalam Proses
                        </span>
                    </div>
                </div>

            </div>
        </div>

        <!-- ===================== SECTION 1: MAKLUMAT PROFIL PETENDER ===================== -->
        <div class="content-card mb-4 p-0">
            <div class="content-card-header p-4 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="content-card-icon" style="width: 38px; height: 38px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <div>
                        <h3 class="content-card-title mb-0" style="font-size: 1rem;">Maklumat Profil Petender</h3>
                        {{-- <p class="text-muted mb-0" style="font-size: 0.78rem;">Diisi oleh Petender</p> --}}
                    </div>
                </div>
            </div>

            <div class="content-card-body p-4">

                <!-- Panduan Pengisian -->
                <div class="guideline-card mb-4">
                    <div class="guideline-card-header">
                        <div class="guideline-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                        </div>
                        <span class="guideline-card-title">Panduan Pengisian</span>
                    </div>
                    <div class="guideline-card-body">
                        <div class="row g-2">
                            <div class="col-12 col-md-6">
                                <div class="guideline-item">
                                    <span class="guideline-num">1</span>
                                    <span class="guideline-item-text">Borang ini hendaklah diisi dengan <span class="highlight">lengkap secara bertaip</span> bagi memudahkan Lembaga Perolehan menimbangkan tender ini.</span>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="guideline-item">
                                    <span class="guideline-num">2</span>
                                    <span class="guideline-item-text">Jangan gunakan atau merujuk kepada <span class="highlight">lampiran lain</span>, kecuali diminta di ruang berkenaan.</span>
                                </div>
                            </div>
                            </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table id="tbl-profil" class="table table-modern align-middle mb-0 w-100">
                        <thead>
                            <tr>
                                <th class="text-center py-3" style="width:55px;">Bil</th>
                                <th class="py-3" style="width:280px;">Perkara</th>
                                <th class="py-3">Butiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- 1. Nama Syarikat -->
                            <tr>
                                <td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">1</td>
                                <td class="fw-semibold" style="font-size:0.85rem;">Nama Syarikat</td>
                                <td><input type="text" name="nama_syarikat" class="form-control form-control-sm" placeholder="Masukkan nama syarikat..."></td>
                            </tr>
                            <!-- 2. Jenis Syarikat -->
                            <tr>
                                <td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">2</td>
                                <td class="fw-semibold" style="font-size:0.85rem;">Jenis Syarikat</td>
                                <td>
                                    <select name="jenis_syarikat" class="form-select form-select-sm">
                                        <option value="">— Sila pilih —</option>
                                        <option value="persendirian">Persendirian</option>
                                        <option value="perkongsian">Perkongsian</option>
                                        <option value="koperasi">Koperasi</option>
                                        <option value="sdn_bhd">Sdn Bhd</option>
                                    </select>
                                </td>
                            </tr>
                            <!-- 3. Taraf Petender -->
                            <tr>
                                <td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">3</td>
                                <td class="fw-semibold" style="font-size:0.85rem;">Taraf Petender <span class="fw-normal text-muted" style="font-size:0.75rem;">(Sertakan salinan sijil)</span></td>
                                <td>
                                    <select name="taraf_petender" class="form-select form-select-sm">
                                        <option value="">— Sila pilih —</option>
                                        <option value="bumiputera">Bumiputera</option>
                                        <option value="bukan_bumiputera">Bukan Bumiputera</option>
                                    </select>
                                </td>
                            </tr>
                            <!-- 4. Alamat -->
                            <tr>
                                <td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">4</td>
                                <td class="fw-semibold" style="font-size:0.85rem;">Alamat</td>
                                <td><textarea name="alamat_syarikat" class="form-control form-control-sm" rows="3" placeholder="Masukkan alamat penuh syarikat..."></textarea></td>
                            </tr>
                            <!-- 5. Pegawai untuk dihubungi -->
                            <tr>
                                <td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">5</td>
                                <td class="fw-semibold" style="font-size:0.85rem;">Pegawai untuk dihubungi</td>
                                <td>
                                    <div class="sub-field-row">
                                        <span class="sub-field-label">Nama</span>
                                        <input type="text" name="pegawai_nama" class="form-control form-control-sm sub-field-input" placeholder="Nama pegawai...">
                                    </div>
                                    <div class="sub-field-row">
                                        <span class="sub-field-label">No. Telefon</span>
                                        <input type="text" name="pegawai_telefon" class="form-control form-control-sm sub-field-input" placeholder="Cth: 012-3456789">
                                    </div>
                                    <div class="sub-field-row">
                                        <span class="sub-field-label">E-mel</span>
                                        <input type="email" name="pegawai_emel" class="form-control form-control-sm sub-field-input" placeholder="contoh@email.com">
                                    </div>
                                </td>
                            </tr>
                            <!-- 6. Maklumat Pendaftaran -->
                            <tr>
                                <td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">6</td>
                                <td class="fw-semibold" style="font-size:0.85rem;">Maklumat Pendaftaran Petender</td>
                                <td>
                                    <div class="sub-field-row">
                                        <span class="sub-field-label">No. SSM</span>
                                        <input type="text" name="no_ssm" class="form-control form-control-sm sub-field-input" placeholder="No. SSM...">
                                    </div>
                                    <div class="sub-field-row">
                                        <span class="sub-field-label">No. MOF</span>
                                        <input type="text" name="no_mof" class="form-control form-control-sm sub-field-input" placeholder="No. MOF...">
                                    </div>
                                    <div class="sub-field-row">
                                        <span class="sub-field-label">Tempoh Sah MOF</span>
                                        <input type="text" name="tempoh_sah_mof" class="form-control form-control-sm sub-field-input" placeholder="">
                                    </div>
                                </td>
                            </tr>
                            <!-- 7. Bilangan Pekerja Sekarang -->
                            <tr>
                                <td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">7</td>
                                <td class="fw-semibold" style="font-size:0.85rem;">Bilangan Pekerja Sekarang</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2" style="max-width:200px;">
                                        <input type="number" name="bil_pekerja_sekarang" class="form-control form-control-sm text-center" placeholder="0" min="0">
                                        <span class="text-muted small flex-shrink-0">orang</span>
                                    </div>
                                </td>
                            </tr>
                            <!-- 8. Bilangan Pekerja Teknikal -->
                            <tr>
                                <td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">8</td>
                                <td class="fw-semibold" style="font-size:0.85rem;">Bilangan Pekerja Teknikal <span class="fw-normal text-muted" style="font-size:0.75rem;">(Cadangan bagi melaksanakan Kontrak)</span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2" style="max-width:200px;">
                                        <input type="number" name="bil_pekerja_teknikal" class="form-control form-control-sm text-center" placeholder="0" min="0">
                                        <span class="text-muted small flex-shrink-0">orang</span>
                                    </div>
                                </td>
                            </tr>
                            <!-- 9. Modal Berbayar -->
                            <tr>
                                <td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">9</td>
                                <td class="fw-semibold" style="font-size:0.85rem;">Modal Berbayar (RM)</td>
                                <td>
                                    <input type="text" name="modal_berbayar" class="form-control form-control-sm text-end amount-input" style="max-width:220px;" placeholder="0.00">
                                </td>
                            </tr>
                            <!-- 10. Modal Dibenarkan -->
                            <tr>
                                <td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">10</td>
                                <td class="fw-semibold" style="font-size:0.85rem;">Modal Dibenarkan / Diaudit (RM)</td>
                                <td>
                                    <input type="text" name="modal_dibenarkan" class="form-control form-control-sm text-end amount-input" style="max-width:220px;" placeholder="0.00">
                                </td>
                            </tr>
                            <!-- 11. Kedudukan Kewangan Semasa -->
                            <tr style="background:#f1f5f9;">
                                <td class="text-center fw-semibold text-dark" style="font-size:0.85rem; background:#f1f5f9;" rowspan="4">11</td>
                                <td colspan="2" class="fw-semibold text-dark" style="font-size:0.9rem; background:#f1f5f9;">
                                    Kedudukan Kewangan Semasa
                                </td>
                            </tr>
                            <!-- 11.1 Harta (Aset) Syarikat -->
                            <tr>
                                <td class="fw-semibold" style="font-size:0.82rem;">
                                    11.1 Harta (Aset) Syarikat
                                    <span class="d-block fw-normal text-muted" style="font-size:0.72rem;">Senarai 5 aset yang terbesar</span>
                                </td>
                                <td>
                                    <p class="text-muted fst-italic mb-2" style="font-size:0.72rem;">Contoh: 1. Rumah Kedai - 1 unit</p>
                                    <div class="numbered-input-row">
                                        <span class="numbered-input-num">1</span>
                                        <input type="text" name="aset_1" class="form-control form-control-sm" placeholder="Aset 1...">
                                    </div>
                                    <div class="numbered-input-row">
                                        <span class="numbered-input-num">2</span>
                                        <input type="text" name="aset_2" class="form-control form-control-sm" placeholder="Aset 2...">
                                    </div>
                                    <div class="numbered-input-row">
                                        <span class="numbered-input-num">3</span>
                                        <input type="text" name="aset_3" class="form-control form-control-sm" placeholder="Aset 3...">
                                    </div>
                                    <div class="numbered-input-row">
                                        <span class="numbered-input-num">4</span>
                                        <input type="text" name="aset_4" class="form-control form-control-sm" placeholder="Aset 4...">
                                    </div>
                                    <div class="numbered-input-row">
                                        <span class="numbered-input-num">5</span>
                                        <input type="text" name="aset_5" class="form-control form-control-sm" placeholder="Aset 5...">
                                    </div>
                                </td>
                            </tr>
                            <!-- 11.2 Peralatan -->
                            <tr>
                                <td class="fw-semibold" style="font-size:0.82rem;">
                                    11.2 Peralatan
                                    <span class="d-block fw-normal text-muted" style="font-size:0.72rem;">Nyatakan nilai dan senaraikan 5 peralatan berkaitan tender ini</span>
                                </td>
                                <td>
                                    <p class="text-muted fst-italic mb-2" style="font-size:0.72rem;">Contoh: 1. Kereta Perdana V6 (2002) - RM40,000.00</p>
                                    <div class="numbered-input-row">
                                        <span class="numbered-input-num">1</span>
                                        <input type="text" name="peralatan_1" class="form-control form-control-sm" placeholder="Peralatan 1...">
                                    </div>
                                    <div class="numbered-input-row">
                                        <span class="numbered-input-num">2</span>
                                        <input type="text" name="peralatan_2" class="form-control form-control-sm" placeholder="Peralatan 2...">
                                    </div>
                                    <div class="numbered-input-row">
                                        <span class="numbered-input-num">3</span>
                                        <input type="text" name="peralatan_3" class="form-control form-control-sm" placeholder="Peralatan 3...">
                                    </div>
                                    <div class="numbered-input-row">
                                        <span class="numbered-input-num">4</span>
                                        <input type="text" name="peralatan_4" class="form-control form-control-sm" placeholder="Peralatan 4...">
                                    </div>
                                    <div class="numbered-input-row">
                                        <span class="numbered-input-num">5</span>
                                        <input type="text" name="peralatan_5" class="form-control form-control-sm" placeholder="Peralatan 5...">
                                    </div>
                                </td>
                            </tr>
                            <!-- 11.3 & 11.4 combined row area -->
                            <tr>
                                <td>
                                    <div class="fw-semibold mb-2" style="font-size:0.82rem;">11.3 Tanggungan / Liabilities (RM)</div>
                                    <div class="fw-semibold" style="font-size:0.82rem;">11.4 Baki Wang Dalam Bank (RM)</div>
                                </td>
                                <td>
                                    <div class="mb-2">
                                        <input type="text" name="tanggungan" class="form-control form-control-sm text-end amount-input" style="max-width:220px;" placeholder="0.00">
                                    </div>
                                    <div>
                                        <input type="text" name="baki_wang_bank" class="form-control form-control-sm text-end amount-input" style="max-width:220px;" placeholder="0.00">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- ── 12. Senarai Projek Terdahulu ─────────────────────────── -->
                <div class="mt-4 pt-4 border-top">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="fw-bold text-dark mb-0" style="font-size:0.9rem;">12. Senarai Projek-projek Terdahulu</h6>
                            <p class="text-muted mb-0" style="font-size:0.75rem;">Dalam tempoh 2 tahun sebelum</p>
                        </div>
                        <button type="button" id="btn-tambah-projek"
                            class="btn btn-sm btn-success d-inline-flex align-items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Tambah
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table id="tbl-projek" class="table table-modern align-middle mb-0 w-100">
                            <thead>
                                <tr>
                                    <th class="text-center py-3" style="width:50px;">Bil</th>
                                    <th class="py-3" style="min-width:200px;">Nama Projek</th>
                                    <th class="py-3" style="min-width:160px;">Agensi</th>
                                    <th class="text-end py-3" style="width:160px;">Nilai Projek (RM)</th>
                                    <th class="text-center py-3" style="width:60px;">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody id="tbl-projek-body">
                                <!-- seeded by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <!-- ===================== SECTION 2: ANALISA KECUKUPAN MODAL BERBAYAR ===================== -->
        <div class="content-card mb-4 p-0">
            <div class="content-card-header p-4 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="content-card-icon" style="width: 38px; height: 38px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2c5.523 0 10 4.477 10 10a10 10 0 0 1-19.995.324L2 12l.004-.28C2.152 6.327 6.57 2 12 2m0 9h-1l-.117.007a1 1 0 0 0 0 1.986L11 13v3l.007.117a1 1 0 0 0 .876.876L12 17h1l.117-.007a1 1 0 0 0 .876-.876L14 16l-.007-.117a1 1 0 0 0-.764-.857l-.112-.02L13 15v-3l-.007-.117a1 1 0 0 0-.876-.876zm.01-3l-.127.007a1 1 0 0 0 0 1.986L12 10l.127-.007a1 1 0 0 0 0-1.986z"/></svg>
                    </div>
                    <div>
                        <h3 class="content-card-title mb-0" style="font-size: 1rem;">Analisa Kecukupan Modal Berbayar</h3>
                        <p class="text-muted mb-0" style="font-size: 0.78rem;">Penetapan skema pemarkahan</p>
                    </div>
                </div>
            </div>
            <div class="content-card-body p-4">

                <!-- Jenis Skor -->
                <div class="row mb-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label fw-semibold small">Jenis Skor <span class="text-danger">*</span></label>
                        <select name="jenis_skor_berbayar" class="form-select form-select-sm jenis-skor-select-berbayar">
                            <option value="">— Sila pilih —</option>
                            <option value="manual">Manual</option>
                            <option value="automatik">Automatik</option>
                        </select>
                    </div>
                </div>

                <!-- Automatik panel — calculated read-only rows -->
                <div id="panel-berbayar-automatik" class="d-none">
                    <div class="table-responsive">
                        <table class="table table-modern align-middle mb-0 w-100" style="border:1px solid #e2e8f0;">
                            <thead>
                                <tr>
                                    <th class="text-center py-3" style="width:55px;">Bil</th>
                                    <th class="py-3">Dari (RM)</th>
                                    <th class="py-3">Hingga (RM)</th>
                                    <th class="py-3 text-center" style="width:120px;">Skema</th>
                                </tr>
                            </thead>
                            <tbody id="tbl-berbayar-auto-body">
                                <!-- rendered by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Manual panel (table) — user-editable rows -->
                <div id="panel-berbayar" class="d-none">
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 btn-tambah-berbayar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Tambah
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table id="tbl-modal-berbayar" class="table table-modern align-middle mb-0 w-100">
                            <thead>
                                <tr>
                                    <th class="text-center py-3" style="width:55px;">Bil</th>
                                    <th class="py-3">Dari</th>
                                    <th class="py-3">Hingga</th>
                                    <th class="py-3" style="width:140px;">Skema</th>
                                    <th class="text-center py-3" style="width:60px;">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody id="tbl-berbayar-body">
                                <!-- seeded by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===================== SECTION 3: ANALISA KECUKUPAN MODAL DIBENARKAN ===================== -->
        <div class="content-card mb-4 p-0">
            <div class="content-card-header p-4 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="content-card-icon" style="width: 38px; height: 38px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2c5.523 0 10 4.477 10 10a10 10 0 0 1-19.995.324L2 12l.004-.28C2.152 6.327 6.57 2 12 2m0 9h-1l-.117.007a1 1 0 0 0 0 1.986L11 13v3l.007.117a1 1 0 0 0 .876.876L12 17h1l.117-.007a1 1 0 0 0 .876-.876L14 16l-.007-.117a1 1 0 0 0-.764-.857l-.112-.02L13 15v-3l-.007-.117a1 1 0 0 0-.876-.876zm.01-3l-.127.007a1 1 0 0 0 0 1.986L12 10l.127-.007a1 1 0 0 0 0-1.986z"/></svg>
                    </div>
                    <div>
                        <h3 class="content-card-title mb-0" style="font-size: 1rem;">Analisa Kecukupan Modal Dibenarkan</h3>
                        <p class="text-muted mb-0" style="font-size: 0.78rem;">Penetapan skema pemarkahan</p>
                    </div>
                </div>
            </div>
            <div class="content-card-body p-4">

                <!-- Jenis Skor -->
                <div class="row mb-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label fw-semibold small">Jenis Skor <span class="text-danger">*</span></label>
                        <select name="jenis_skor_dibenarkan" class="form-select form-select-sm jenis-skor-select-dibenarkan">
                            <option value="">— Sila pilih —</option>
                            <option value="manual">Manual</option>
                            <option value="automatik">Automatik</option>
                        </select>
                    </div>
                </div>

                <!-- Automatik panel — calculated read-only rows -->
                <div id="panel-dibenarkan-automatik" class="d-none">
                    <div class="table-responsive">
                        <table class="table table-modern align-middle mb-0 w-100" style="border:1px solid #e2e8f0;">
                            <thead>
                                <tr>
                                    <th class="text-center py-3" style="width:55px;">Bil</th>
                                    <th class="py-3">Dari (RM)</th>
                                    <th class="py-3">Hingga (RM)</th>
                                    <th class="py-3 text-center" style="width:120px;">Skema</th>
                                </tr>
                            </thead>
                            <tbody id="tbl-dibenarkan-auto-body">
                                <!-- rendered by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Manual panel (table) — user-editable rows -->
                <div id="panel-dibenarkan" class="d-none">
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 btn-tambah-dibenarkan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Tambah
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table id="tbl-modal-dibenarkan" class="table table-modern align-middle mb-0 w-100">
                            <thead>
                                <tr>
                                    <th class="text-center py-3" style="width:55px;">Bil</th>
                                    <th class="py-3">Dari</th>
                                    <th class="py-3">Hingga</th>
                                    <th class="py-3" style="width:140px;">Skema</th>
                                    <th class="text-center py-3" style="width:60px;">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody id="tbl-dibenarkan-body">
                                <!-- seeded by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===================== ACTION BUTTONS ===================== -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <a href="{{ route('senaraiKewanganBekalan') }}" class="btn-form btn-form-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali
            </a>
            <div class="d-flex gap-2">
                {{-- <button type="button" class="btn-form btn-form-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    Laporan
                </button> --}}
                <button type="button" class="btn-form btn-form-success btn-simpan-profil">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Simpan
                </button>
            </div>
        </div>

    </form>

@endsection

@push('modals')
    <!-- ===================== MODAL: SUCCESS ===================== -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4">
                <div class="mb-3">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="10" fill="#E6F7F3" />
                        <path d="M10 14.2L7.8 12l-1.4 1.4L10 17l8-8-1.4-1.4L10 14.2z" fill="#19c1a7" />
                    </svg>
                </div>
                <h5 class="fw-bold mb-2">Berjaya</h5>
                <p class="text-muted mb-4">Maklumat telah berjaya disimpan.</p>
                <button type="button" class="btn-form btn-form-primary mx-auto" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
@endpush

@section('scripts')
<script>
$(document).ready(function () {

    // ── DELETE BUTTON SVG (shared) ───────────────────────────────────────────
    var DELETE_BTN =
        '<button type="button" class="btn btn-sm btn-hapus-row d-inline-flex align-items-center justify-content-center p-0" ' +
        'style="width:28px;height:28px;border-radius:6px;background:#fee2e2;color:#ef4444;border:none;" title="Buang baris">' +
        '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path></svg>' +
        '</button>';

    // ══════════════════════════════════════════════════════════════════════════
    // 12. SENARAI PROJEK TERDAHULU
    // ══════════════════════════════════════════════════════════════════════════
    function buildProjekRow(bil) {
        return $('<tr class="projek-row">' +
            '<td class="text-center row-bil fw-semibold text-muted" style="font-size:0.8rem;">' + bil + '</td>' +
            '<td><input type="text" name="projek_nama[]" class="form-control form-control-sm" placeholder="Nama projek..."></td>' +
            '<td><input type="text" name="projek_agensi[]" class="form-control form-control-sm" placeholder="Nama agensi..."></td>' +
            '<td><input type="text" name="projek_nilai[]" class="form-control form-control-sm text-end amount-input" placeholder="0.00"></td>' +
            '<td class="text-center">' + DELETE_BTN + '</td>' +
        '</tr>');
    }

    // Seed first row
    $('#tbl-projek-body').append(buildProjekRow(1));

    function reNumberProjek() {
        $('#tbl-projek-body .projek-row').each(function (i) {
            $(this).find('.row-bil').text(i + 1);
        });
    }

    $('#btn-tambah-projek').on('click', function () {
        var bil = $('#tbl-projek-body .projek-row').length + 1;
        $('#tbl-projek-body').append(buildProjekRow(bil));
    });

    $('#tbl-projek-body').on('click', '.btn-hapus-row', function () {
        if ($('#tbl-projek-body .projek-row').length <= 1) return;
        $(this).closest('tr').remove();
        reNumberProjek();
    });

    // ══════════════════════════════════════════════════════════════════════════
    // ANALISA KECUKUPAN — shared builder for Modal Berbayar & Modal Dibenarkan
    // ══════════════════════════════════════════════════════════════════════════
    function initAnalisa(bodySelector, btnSelector, namePrefix) {
        var $body = $(bodySelector);

        function buildRow(bil) {
            return $('<tr class="analisa-row">' +
                '<td class="text-center row-bil fw-semibold text-muted" style="font-size:0.8rem;">' + bil + '</td>' +
                '<td><input type="text" name="' + namePrefix + '_dari[]" class="form-control form-control-sm amount-input" placeholder="0.00"></td>' +
                '<td><input type="text" name="' + namePrefix + '_hingga[]" class="form-control form-control-sm amount-input" placeholder="0.00"></td>' +
                '<td><input type="text" name="' + namePrefix + '_skema[]" class="form-control form-control-sm" placeholder="0"></td>' +
                '<td class="text-center">' + DELETE_BTN + '</td>' +
            '</tr>');
        }

        function reNumber() {
            $body.find('.analisa-row').each(function (i) {
                $(this).find('.row-bil').text(i + 1);
            });
        }

        // Seed first row
        $body.append(buildRow(1));

        $(btnSelector).on('click', function () {
            var bil = $body.find('.analisa-row').length + 1;
            $body.append(buildRow(bil));
        });

        $body.on('click', '.btn-hapus-row', function () {
            if ($body.find('.analisa-row').length <= 1) return;
            $(this).closest('tr').remove();
            reNumber();
        });

        // Range validation: Dari < Hingga, each row Dari > previous row Hingga
        $body.on('blur', '.amount-input', function () {
            var $input  = $(this);
            var $td     = $input.closest('td');
            var $row    = $td.closest('tr');
            var colIdx  = $td.index(); // 1=Dari, 2=Hingga

            if (colIdx === 1) {
                var $prevRow = $row.prev('tr.analisa-row');
                if ($prevRow.length) {
                    var prevHingga = parseAmt($prevRow.find('td:eq(2) input').val());
                    var dari = parseAmt($input.val());
                    $input.toggleClass('is-invalid', prevHingga > 0 && dari > 0 && dari <= prevHingga);
                }
            } else if (colIdx === 2) {
                var $nextRow = $row.next('tr.analisa-row');
                if ($nextRow.length) {
                    var hingga = parseAmt($input.val());
                    var $nextDari = $nextRow.find('td:eq(1) input');
                    var nextDari = parseAmt($nextDari.val());
                    $nextDari.toggleClass('is-invalid', hingga > 0 && nextDari > 0 && nextDari <= hingga);
                }
                // Also: Hingga must be >= Dari on same row
                var dariSame = parseAmt($row.find('td:eq(1) input').val());
                var hinggaSame = parseAmt($input.val());
                $input.toggleClass('is-invalid', dariSame > 0 && hinggaSame > 0 && hinggaSame < dariSame);
            }
        });

        // Auto-fill next row Dari on Tambah
        $(btnSelector).off('click');
        $(btnSelector).on('click', function () {
            var bil = $body.find('.analisa-row').length + 1;
            var $newRow = buildRow(bil);
            var $lastRow = $body.find('tr.analisa-row').last();
            if ($lastRow.length) {
                var lastHingga = parseAmt($lastRow.find('td:eq(2) input').val());
                if (lastHingga > 0) {
                    $newRow.find('td:eq(1) input').val(fmtAmt(lastHingga + 1));
                }
            }
            $body.append($newRow);
        });
    }

    function parseAmt(s) { return parseFloat(String(s).replace(/,/g, '')) || 0; }
    function fmtAmt(n) { return n.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }

    initAnalisa('#tbl-berbayar-body', '.btn-tambah-berbayar', 'berbayar');
    initAnalisa('#tbl-dibenarkan-body', '.btn-tambah-dibenarkan', 'dibenarkan');

    // ══════════════════════════════════════════════════════════════════════════
    // ANGGARAN JABATAN — hardcoded dummy; abg wan will replace with real value from backend later
    // e.g. var anggaranJabatan = { $anggaranJabatan };
    // ══════════════════════════════════════════════════════════════════════════
    var anggaranJabatan = 300000.00;

    // Calculate Hingga for row 1 based on AJ
    function calculateHingga1(aj) {
        if (aj > 50 && aj <= 500000) {
            return (0.015 * aj) - 0.01;
        } else if (aj > 500000) {
            return (0.030 * aj) - 0.01;
        }
        return 0;
    }

    // Render the 2 automatik rows into a given tbody
    // Includes hidden inputs so values are submitted with the form.
    // Row 2 Hingga is submitted as "" (empty) — backend treats null/empty = ke atas / no upper limit.
    function renderAutomatikRows(tbodyId, namePrefix) {
        var $tbody  = $('#' + tbodyId);
        var hingga1 = calculateHingga1(anggaranJabatan);
        var dari2   = hingga1 + 0.01;

        var keAtasBadge =
            '<span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-2 fw-semibold" ' +
            'style="background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;font-size:0.78rem;">' +
                '<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'11\' height=\'11\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2.5\' stroke-linecap=\'round\' stroke-linejoin=\'round\'>' +
                    '<line x1=\'12\' y1=\'19\' x2=\'12\' y2=\'5\'></line>' +
                    '<polyline points=\'5 12 12 5 19 12\'></polyline>' +
                '</svg>' +
                'Ke Atas' +
            '</span>';

        $tbody.html(
            // Row 1
            '<tr>' +
                '<td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">1</td>' +
                '<td class="fw-semibold" style="font-size:0.85rem;">' +
                    fmtAmt(0) +
                    '<input type="hidden" name="' + namePrefix + '_dari[]" value="0">' +
                '</td>' +
                '<td class="fw-semibold" style="font-size:0.85rem;">' +
                    fmtAmt(hingga1) +
                    '<input type="hidden" name="' + namePrefix + '_hingga[]" value="' + hingga1.toFixed(2) + '">' +
                '</td>' +
                '<td class="text-center fw-semibold" style="font-size:0.85rem;">' +
                    '0<input type="hidden" name="' + namePrefix + '_skema[]" value="0">' +
                '</td>' +
            '</tr>' +
            // Row 2 — hingga submitted as "" (ke atas / no upper limit)
            '<tr>' +
                '<td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">2</td>' +
                '<td class="fw-semibold" style="font-size:0.85rem;">' +
                    fmtAmt(dari2) +
                    '<input type="hidden" name="' + namePrefix + '_dari[]" value="' + dari2.toFixed(2) + '">' +
                '</td>' +
                '<td>' +
                    keAtasBadge +
                    '<input type="hidden" name="' + namePrefix + '_hingga[]" value="">' +
                '</td>' +
                '<td class="text-center fw-semibold" style="font-size:0.85rem;">' +
                    '10<input type="hidden" name="' + namePrefix + '_skema[]" value="10">' +
                '</td>' +
            '</tr>'
        );
    }

    // ══════════════════════════════════════════════════════════════════════════
    // JENIS SKOR — toggle panels, disable hidden panel inputs to prevent
    //              null submissions from the panel that's not active
    // ══════════════════════════════════════════════════════════════════════════
    function handleJenisSkor(val, manualPanelId, autoPanelId, autoTbodyId, namePrefix) {
        var $manual = $('#' + manualPanelId);
        var $auto   = $('#' + autoPanelId);

        // Hide both first
        $manual.addClass('d-none');
        $auto.addClass('d-none');

        // Disable all inputs in both panels (prevent stale submissions)
        $manual.find('input, select').prop('disabled', true);
        $auto.find('input').prop('disabled', true);

        if (val === 'manual') {
            $manual.removeClass('d-none');
            $manual.find('input, select').prop('disabled', false);
        } else if (val === 'automatik') {
            renderAutomatikRows(autoTbodyId, namePrefix);
            $auto.removeClass('d-none');
            $auto.find('input').prop('disabled', false);
        }
    }

    $('.jenis-skor-select-berbayar').on('change', function () {
        handleJenisSkor($(this).val(), 'panel-berbayar', 'panel-berbayar-automatik', 'tbl-berbayar-auto-body', 'berbayar');
    });

    $('.jenis-skor-select-dibenarkan').on('change', function () {
        handleJenisSkor($(this).val(), 'panel-dibenarkan', 'panel-dibenarkan-automatik', 'tbl-dibenarkan-auto-body', 'dibenarkan');
    });

    // ══════════════════════════════════════════════════════════════════════════
    // AMOUNT INPUT — auto comma formatting (delegated for dynamic rows too)
    // ══════════════════════════════════════════════════════════════════════════
    function formatAmount(value) {
        var num = parseFloat(value.replace(/,/g, '')) || 0;
        return num.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    $(document).on('focus', '.amount-input', function () {
        var raw = $(this).val().replace(/,/g, '');
        if (parseFloat(raw) === 0) raw = '';
        $(this).val(raw);
    });

    $(document).on('blur', '.amount-input', function () {
        var val = $(this).val();
        if (val === '') return;
        $(this).val(formatAmount(val));
    });

    $(document).on('input', '.amount-input', function () {
        $(this).val($(this).val().replace(/[^\d.]/g, ''));
    });

    // ══════════════════════════════════════════════════════════════════════════
    // FORM SUBMIT — strip commas from amount fields so backend gets clean nums
    // TODO: restore real submit after demo — currently intercepted for demo purposes
    // ══════════════════════════════════════════════════════════════════════════
    // $('#form-profil-petender').on('submit', function () {
    //     $(this).find('.amount-input').each(function () {
    //         $(this).val($(this).val().replace(/,/g, ''));
    //     });
    // });

    // DEMO: Simpan button shows success modal instead of submitting
    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
    $('.btn-simpan-profil').on('click', function () {
        successModal.show();
    });

});
</script>
@endsection
