@extends('layouts.v3.master')


@section('content')
    <style>
        .stats-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            position: relative;
        }
        .stats-card::before {
            content: ''; position: absolute; top: -25px; right: -25px; width: 80px; height: 80px;
            background: var(--sg-red); opacity: 0.03; border-radius: 20px; transform: rotate(45deg); pointer-events: none;
        }
        .stats-card-header {
            padding: 20px 16px;
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; justify-content: space-between;
        }
        .stats-card-title {
            margin: 0; font-size: 1.1rem; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 10px;
        }
       .table-modern thead th, .table-modern tfoot th {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.5px;
            padding: 14px 20px;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
            vertical-align: middle;
        }

        .table-modern tbody td {
            padding: 16px 20px;
            vertical-align: middle;
            color: #334155;
            font-size: 0.9rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-modern tbody tr:hover {
            background-color: #fff9f9;
        }
        .btn-primary {
            background: #405189;
        }
        .card-title-grey {
            background: #D9D9D9;
            padding: 5px 15px;
        }
        hr {
            border:1px solid #E9EBEC;
        }
        .btn-sm-cust {
            font-size: 10px !important;
            padding: 3px 3px 3px 3px;
            height: max-content;
        }
        .heartbeat {
            display: inline-block;
            animation: heartbeat 1.2s infinite;
        }

        @keyframes heartbeat {
            0% {
                transform: scale(1);
            }
            25% {
                transform: scale(1.05);
            }
            40% {
                transform: scale(1);
            }
            60% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }
        .btn-circle {
            width: 25px;
            height: 25px;
            padding: 0;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes btnPop {
            0% {
                transform: scale(1);
            }
            40% {
                transform: scale(1.25);
            }
            100% {
                transform: scale(1.1);
            }
        }

        /* Success modal (same UI as other modules) */
        .lawatan-tapak-modal-card {
            border-radius: 10px;
            border: 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 18px 18px 14px;
            text-align: center;
            min-height: 200px;
        }
        .lawatan-tapak-modal-card .lawatan-tapak-modal-icon {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .lawatan-tapak-modal-card .lawatan-tapak-modal-text {
            text-align: center;
        }

        .lawatan-tapak-modal-card .lawatan-tapak-modal-btn-wrap {
            text-align: center;
        }

        .lawatan-tapak-modal-card .confetti {
            width: 44px;
            height: 44px;
            margin: 6px auto 8px;
        }

        .lawatan-tapak-modal-card .btn-modal {
            background: #3a4f8a;
            color: #fff;
            border: 0;
            padding: 8px 14px;
            border-radius: 6px;
            font-weight: 700;
            width: 80px;
        }

        /* SST Modal Tabs Customization */
        .nav-tabs-custom .nav-link {
            color: #64748b;
            transition: all 0.3s ease;
        }
        .nav-tabs-custom .nav-link.active {
            color: #405189 !important;
        }
        .nav-tabs-custom .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: #405189;
            border-radius: 3px 3px 0 0;
        }
        .tab-number {
            width: 22px;
            height: 22px;
            background: #e2e8f0;
            color: #64748b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            transition: all 0.3s ease;
        }
        .nav-link.active .tab-number {
            background: #405189;
            color: #fff;
        }
        .border-dashed {
            border-style: dashed !important;
        }
    </style>

    <div class="card border shadow-sm mb-2 rounded-3">
        <div class="card-body p-3">
            <div class="row g-2 align-items-end">
                <div class="col-4 col-lg-4">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Tender</label>
                    <h6 class="text-primary">SUKSEL/PERT/2026/001</h6>
                    <!-- <input type="text" id="" class="form-control form-control-sm" placeholder=""> -->
                </div>
                <div class="col-4 col-lg-4">
                    <label for="filter_tajuk" class="form-label small fw-bold text-secondary text-uppercase mb-1">PTJ</label>
                    <h6 class="text-primary">100-007</h6>
                </div>
                <div class="col-4 col-lg-4">
                    <label for="filter_status" class="form-label small fw-bold text-secondary text-uppercase mb-1">Status</label>
                    <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 fw-bold text-uppercase heartbeat" style="font-size: 0.8rem;">
                        Dalam Proses
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="content-card mb-4 p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width: 38px; height: 38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                        <line x1="7" y1="8" x2="17" y2="8"></line>
                        <line x1="7" y1="12" x2="17" y2="12"></line>
                        <line x1="7" y1="16" x2="17" y2="16"></line>
                    </svg>
                </div>
                <div>
                    <h3 class="content-card-title mb-0" style="font-size: 1rem;">Senarai Cadangan Pembekal</h3>
                    <p class="text-muted mb-0" style="font-size: 0.78rem;">Kemaskini maklumat cadangan pembekal</p>
                </div>
            </div>
        </div>

        <div class="content-card-body p-4">

            <div class="row g-2 align-items-end mb-4">
                <div class="col-6 col-lg-6">
                    <label for="pemilihan_berdasarkan" class="form-label small fw-bold text-secondary text-uppercase mb-1">Pemilihan Berdasarkan</label>
                    <input type="text" id="pemilihan_berdasarkan" class="form-control form-control-sm">
                </div>
                <div class="col-6 col-lg-6">
                    <label for="kaedah_memuktamadkan_pembekal" class="form-label small fw-bold text-secondary text-uppercase mb-1">Kaedah Memuktamadkan Pembekal</label>
                    <input type="text" id="kaedah_memuktamadkan_pembekal" class="form-control form-control-sm">
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table id="dt_pembekal" class="table table-modern w-100 mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">Pembekal</th>
                            <th class="text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">OPHL HOLDING SDN BHD</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalSST">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    Sedia Surat Setuju Terima
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table id="dt_loa" class="table table-modern w-100 mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">No. LOI/LOA</th>
                            <th class="text-center">Jenis</th>
                            <th class="text-center">Item</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="dt-loa-no-data">
                            <td colspan="5" class="text-center text-muted py-4 small">Tiada Data</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="d-flex justify-content-end align-items-center mb-4 flex-wrap gap-2">

        <div class="d-flex gap-2">
            <button type="button" class="btn-form btn-form-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
                Laporan
            </button>
            <button type="button" class="btn-form btn-form-success" id="btn-simpan">
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

    </div>
@endsection

@push('modals')
    <!-- Modal SST -->
    <div class="modal fade" id="modalSST" tabindex="-1" aria-labelledby="modalSSTLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header px-4 pt-4 border-0">
                    <div>
                        <h5 class="modal-title fw-bold" id="modalSSTLabel">Penyediaan Surat Setuju Terima (SST)</h5>
                        <p class="text-muted small mb-0">Lengkapkan maklumat mengikut tab di bawah</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="px-4 border-bottom">
                        <ul class="nav nav-tabs nav-tabs-custom border-0 gap-4" id="sstTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-bold py-3 border-0 bg-transparent position-relative" id="pembekal-tab" data-bs-toggle="tab" data-bs-target="#pembekal-pane" type="button" role="tab" aria-controls="pembekal-pane" aria-selected="true">
                                    <span class="d-flex align-items-center gap-2">
                                        <div class="tab-number">1</div>
                                        Perincian Pembekal
                                    </span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold py-3 border-0 bg-transparent position-relative" id="sst-tab" data-bs-toggle="tab" data-bs-target="#sst-pane" type="button" role="tab" aria-controls="sst-pane" aria-selected="false">
                                    <span class="d-flex align-items-center gap-2">
                                        <div class="tab-number">2</div>
                                        Surat Setuju Terima dan Lampiran
                                    </span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold py-3 border-0 bg-transparent position-relative" id="bon-tab" data-bs-toggle="tab" data-bs-target="#bon-pane" type="button" role="tab" aria-controls="bon-pane" aria-selected="false">
                                    <span class="d-flex align-items-center gap-2">
                                        <div class="tab-number">3</div>
                                        Bon Pelaksanaan
                                    </span>
                                </button>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="tab-content p-4" id="sstTabContent">
                        <!-- Tab 1: Perincian Pembekal -->
                        <div class="tab-pane fade show active" id="pembekal-pane" role="tabpanel" aria-labelledby="pembekal-tab">
                            <!-- <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Nama Syarikat</label>
                                    <input type="text" class="form-control" value="OPHL HOLDING SDN BHD">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Pendaftaran</label>
                                    <input type="text" class="form-control" value="1234567-X">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Alamat Berdaftar</label>
                                    <textarea class="form-control" rows="3">NO 1, JALAN PINANG, 50450 KUALA LUMPUR</textarea>
                                </div>
                            </div> -->
                            <div class="stats-card mb-4">
                                <div class="stats-card-header mb-4">
                                    <h3 class="stats-card-title">
                                        <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-2" style="width: 36px; height: 36px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                        </div>
                                        Perincian Pembekal
                                    </h3>
                                </div>

                                <div class="card-body p-4">
                                    <div class="row g-4">
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Pembekal</label>
                                            <input type="text" class="form-control" id="pembekal_nama" name="pembekal_nama" value="">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">No. MOF</label>
                                            <input type="text" class="form-control" id="pembekal_mof" name="pembekal_mof" value="">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Pendaftaran CBP</label>
                                            <input type="text" class="form-control" id="pembekal_cbp" name="pembekal_cbp">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tarikh Kuatkuasa Pendaftaran CBP</label>
                                            <input type="date" class="form-control" id="pembekal_cbp_date" name="pembekal_cbp_date">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tarikh Pengisytiharan di eP</label>
                                            <input type="date" class="form-control" id="pembekal_ep_date" name="pembekal_ep_date">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Cawangan</label>
                                            <select class="form-select" id="pembekal_cawangan" name="pembekal_cawangan">
                                                <option value="" selected>Sila Pilih</option>
                                                <option value="hq">HQ</option>
                                                <option value="cawangan">Cawangan</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Alamat</label>
                                            <textarea class="form-control" id="pembekal_alamat" name="pembekal_alamat" rows="3"></textarea>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Telefon</label>
                                            <input type="text" class="form-control" id="pembekal_tel" name="pembekal_tel" value="">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Faks</label>
                                            <input type="text" class="form-control" id="pembekal_fax" name="pembekal_fax" value="">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Telefon Bimbit</label>
                                            <input type="text" class="form-control" id="pembekal_hp" name="pembekal_hp">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Pegawai Untuk Dihubungi</label>
                                            <input type="text" class="form-control" id="pembekal_pic" name="pembekal_pic">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Email</label>
                                            <input type="email" class="form-control" id="pembekal_email" name="pembekal_email">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="stats-card mb-4">
                                <div class="stats-card-header">
                                    <h3 class="stats-card-title">
                                        <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-2" style="width: 36px; height: 36px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                        </div>
                                        Maklumat Surat Setuju Terima
                                    </h3>
                                </div>

                                <div class="card-body p-4">
                                    <div class="row g-4">
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">No Dokumen</label>
                                            <input type="text" class="form-control" id="sst_no_dokumen" name="sst_no_dokumen" value="">
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tajuk Surat Setuju Terima</label>
                                            <textarea class="form-control" id="sst_tajuk" name="sst_tajuk" rows="3"></textarea>
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Kaedah Perolehan</label>
                                            <input type="text" class="form-control" id="sst_kaedah_perolehan" name="sst_kaedah_perolehan" value="">
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Jenis Sebut Harga / Tender</label>
                                            <input type="text" class="form-control" id="sst_jenis_tender" name="sst_jenis_tender" value="">
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Kategori Jenis Perolehan</label>
                                            <input type="text" class="form-control" id="sst_kategori_perolehan" name="sst_kategori_perolehan" value="">
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Rujukan Fail</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="sst_fail_prefix" name="sst_fail_prefix" value="">
                                                <input type="text" class="form-control" id="sst_fail_no" name="sst_fail_no" value="" style="max-width: 100px;">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Harga Cawangan Tawaran (RM)</label>
                                            <input type="text" class="form-control text-end" id="sst_harga_tawaran" name="sst_harga_tawaran" value="">
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Cukai Jualan/Cukai Perkhidmatan (RM)</label>
                                            <input type="text" class="form-control text-end" id="sst_cukai" name="sst_cukai">
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Amaun Keseluruhan Kontrak (RM)</label>
                                            <input type="text" class="form-control text-end fw-bold" id="sst_total" name="sst_total" value="">
                                        </div>

                                        <!-- Radios -->
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Insurans / Jaminan <span class="text-danger">*</span></label>
                                            <div class="d-flex gap-4 mt-1">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="insurans" id="insurans_ya" value="ya">
                                                    <label class="form-check-label small" for="insurans_ya">Ya</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="insurans" id="insurans_tidak" value="tidak" checked>
                                                    <label class="form-check-label small" for="insurans_tidak">Tidak</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Bon Pelaksanaan <span class="text-danger">*</span></label>
                                            <div class="d-flex gap-4 mt-1">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="bon" id="bon_ya" value="ya" checked>
                                                    <label class="form-check-label small" for="bon_ya">Ya</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="bon" id="bon_tidak" value="tidak">
                                                    <label class="form-check-label small" for="bon_tidak">Tidak</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Perlu Semakan Atas Talian <span class="text-danger">*</span></label>
                                            <div class="d-flex gap-4 mt-1">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="semakan" id="semakan_ya" value="ya">
                                                    <label class="form-check-label small" for="semakan_ya">Ya</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="semakan" id="semakan_tidak" value="tidak" checked>
                                                    <label class="form-check-label small" for="semakan_tidak">Tidak</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Program Skim Latihan 1Malaysia (SLIM) <span class="text-danger">*</span></label>
                                            <div class="d-flex gap-4 mt-1">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="slim" id="slim_ya" value="ya">
                                                    <label class="form-check-label small" for="slim_ya">Ya</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="slim" id="slim_tidak" value="tidak" checked>
                                                    <label class="form-check-label small" for="slim_tidak">Tidak</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="stats-card mb-4">
                                <div class="stats-card-header">
                                    <h3 class="stats-card-title">
                                        <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-2" style="width: 36px; height: 36px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                        </div>
                                        Perincian Kontrak
                                    </h3>
                                </div>

                                <div class="card-body p-4">
                                    <div class="row g-4">
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Jenis Kontrak</label>
                                            <input type="text" class="form-control" id="kontrak_jenis" name="kontrak_jenis" value="">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Agensi</label>
                                            <input type="text" class="form-control" id="kontrak_agensi" name="kontrak_agensi" value="">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Pentadbir Kontrak <span class="text-danger">*</span></label>
                                            <select class="form-select" id="kontrak_pentadbir" name="kontrak_pentadbir">
                                                <option value="ptj248" selected>Pentadbir Kontrak Kementerian Ptj248</option>
                                                <option value="lain">Lain-lain</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tempoh Kontrak (Bulan)</label>
                                            <input type="number" class="form-control" id="kontrak_tempoh" name="kontrak_tempoh" value="">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tarikh Kuatkuasa Kontrak</label>
                                            <input type="date" class="form-control" id="kontrak_mula" name="kontrak_mula" value="">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tarikh Tamat Kontrak</label>
                                            <input type="date" class="form-control" id="kontrak_tamat" name="kontrak_tamat" value="">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Perjanjian Diperlukan <span class="text-danger">*</span></label>
                                            <div class="d-flex gap-4 mt-1">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kontrak_perjanjian" id="perjanjian_ya" value="ya" checked>
                                                    <label class="form-check-label small" for="perjanjian_ya">Ya</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kontrak_perjanjian" id="perjanjian_tidak" value="tidak">
                                                    <label class="form-check-label small" for="perjanjian_tidak">Tidak</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tab 2: SST dan Lampiran -->
                        <div class="tab-pane fade" id="sst-pane" role="tabpanel" aria-labelledby="sst-tab">
                            <div class="stats-card mb-4">
                                <div class="stats-card-header">
                                    <h3 class="stats-card-title">
                                        <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-2" style="width: 36px; height: 36px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                        </div>
                                        Penandatangan Suat Setuju Terima
                                    </h3>
                                </div>

                                <div class="card-body p-4">
                                    <div class="row g-4 mb-4">
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tarikh SST Ditandatangani Oleh Kerajaan <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="sst_tarikh_kerajaan" name="sst_tarikh_kerajaan">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Penandatangan Surat Setuju Terima <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="sst_penandatangan" name="sst_penandatangan">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Jawatan Penandatangan Surat Setuju Terima <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="sst_jawatan_penandatangan" name="sst_jawatan_penandatangan">
                                        </div>
                                    </div>

                                    <div class="table-responsive mb-4">
                                        <table class="table table-bordered table-modern mb-0">
                                            <thead>
                                                <tr class="bg-primary text-white">
                                                    <th class="text-center py-3" style="width: 70%; background-color: #405189 !important; color: white;">Kandungan</th>
                                                    <th class="text-center py-3" style="width: 30%; background-color: #405189 !important; color: white;">Tindakan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="ps-3">Surat Setuju Terima (Termasuk Lampiran A)</td>
                                                    <td class="text-center">
                                                        <a href="javascript:void(0)" class="text-primary text-decoration-none small fw-bold">Muat Turun</a><br>
                                                        <span class="text-muted small" style="font-size: 10px;">Surat Setuju Terima Kajian Forensik.pdf</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="ps-3">Perakuan Penerimaan Surat Setuju Terima</td>
                                                    <td class="text-center">
                                                        <a href="javascript:void(0)" class="text-primary text-decoration-none small fw-bold">Muat Turun</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="ps-3">Lampiran B - Surat Akuan Pembidaan Berjaya</td>
                                                    <td class="text-center">
                                                        <a href="javascript:void(0)" class="text-primary text-decoration-none small fw-bold">Muat Turun</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="ps-3">Lampiran C - Surat Akuan Sumpah Syarikat</td>
                                                    <td class="text-center">
                                                        <a href="javascript:void(0)" class="text-primary text-decoration-none small fw-bold">Muat Turun</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="ps-3">Maklumat Insurance</td>
                                                    <td class="text-center">
                                                        <a href="javascript:void(0)" class="text-primary text-decoration-none small fw-bold">Muat Turun</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-sm btn-success px-3" style="background-color: #17a2b8; border-color: #17a2b8;">Muat Turun Semua</button>
                                        <button type="button" class="btn btn-sm btn-primary px-3" style="background-color: #405189; border-color: #405189;">Tambah</button>
                                        <button type="button" class="btn btn-sm btn-danger px-3" style="background-color: #f06548; border-color: #f06548;">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tab 3: Bon Pelaksanaan -->
                        <div class="tab-pane fade" id="bon-pane" role="tabpanel" aria-labelledby="bon-tab">
                            <div class="stats-card mb-4">
                                <div class="stats-card-header">
                                    <h3 class="stats-card-title">
                                        <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-2" style="width: 36px; height: 36px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                        </div>
                                        Bon Pelaksanaan
                                    </h3>
                                </div>

                                <div class="card-body p-4">
                                    <div class="row g-4 mb-4">
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tempoh Kontrak (Bulan)</label>
                                            <input type="number" class="form-control" id="bon_tempoh" name="bon_tempoh" value="12">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Nilai Kontrak (RM)</label>
                                            <input type="text" class="form-control text-end" id="bon_nilai_kontrak" name="bon_nilai_kontrak" value="360,000.00">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Peratusan Bon (%)</label>
                                            <input type="text" class="form-control" id="bon_peratus" name="bon_peratus" value="2.5">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Nilai Bon (RM)</label>
                                            <input type="text" class="form-control text-end" id="bon_nilai" name="bon_nilai" value="9,000.00">
                                        </div>
                                    </div>

                                    <div class="table-responsive mb-0">
                                        <table class="table table-bordered table-modern mb-0">
                                            <thead>
                                                <tr class="bg-primary text-white">
                                                    <th class="text-center py-3" style="width: 70%; background-color: #405189 !important; color: white;">Kandungan</th>
                                                    <th class="text-center py-3" style="width: 30%; background-color: #405189 !important; color: white;">Tindakan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="ps-3">Surat Setuju Terima (Termasuk Lampiran A)</td>
                                                    <td class="text-center">
                                                        <a href="javascript:void(0)" class="text-primary text-decoration-none small fw-bold">Muat Turun</a><br>
                                                        <span class="text-muted small" style="font-size: 10px;">Surat Setuju Terima Kajian Forensik.pdf</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 mt-3">
                    <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-form btn-form-primary btn-simpan-sst">Simpan & Jana Surat</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
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
    <script type="text/javascript">
        $(document).ready(function() {
            // Success Modal initialization
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));

            // Click handler for Simpan button
            $('#btn-simpan').on('click', function() {
                successModal.show();
            });

            // Click handler for Simpan & Jana Surat button in SST Modal
            $('.btn-simpan-sst').on('click', function() {
                // Close SST Modal first then show success modal
                var sstModal = bootstrap.Modal.getInstance(document.getElementById('modalSST'));
                if (sstModal) sstModal.hide();
                
                successModal.show();
            });
        });
    </script>
@endsection
