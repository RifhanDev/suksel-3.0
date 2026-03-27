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
       .table-modern thead th {
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

        .btn-circle:hover {
            animation: btnPop 0.25s ease forwards;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
    </style>

    <div class="card border shadow-sm mb-2 rounded-3">
        <div class="card-body p-3">
            <div class="row g-2 align-items-end">
                <div class="col-4 col-lg-4">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Tender</label>
                    <h6 class="text-primary">SUKSEL/PERT/2026/001</h6>
                    <!-- <input type="text" id="" class="form-control form-control-sm" placeholder="" readonly> -->
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

    <div class="stats-card mb-4">
        <div class="stats-card-header">
            <h3 class="stats-card-title">
                <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-2" style="width: 36px; height: 36px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path><line x1="8" y1="6" x2="16" y2="6"></line><line x1="8" y1="10" x2="16" y2="10"></line></svg>
                </div>
                Templat Spesifikasi
            </h3>
        </div>
        <div class="card-body p-2">
            <div class="p-4">
                <div class="small lh-sm mt-2">
                    <p class="card-title-desc text-danger fst-italic">
                        1. Sila klik untuk Garis Panduan item dan Spesifikasi.<br>
                        2. Sila klik pautan UOM untuk carian nama unit ukuran sebagai panduan. Nama unit ukuran yang dikehendaki perlu dikunci masuk di ruang medan Unit Ukuran dan pilih dari senarai 'autocomplete' yang dipaparkan.<br>
                    </p>
                </div>

                 <div class="row mb-4">
                    <div class="col-sm-12 form-group my-2">
                        <div class="row">
                            <label class="col-sm-2 control-label">Tajuk Dokumen</label>
                            <div class="col-sm-10">
                                <textarea class="form-control form-control-sm" id="" name="" row="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group my-2">
                        <div class="row">
                            <label class="col-sm-4 control-label">Jenis Barang</label>
                            <div class="col-sm-8">
                                <div class="col-sm-12">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jenisBarang" id="jenisBarang1" checked>
                                        <label class="form-check-label" for="jenisBarang1">
                                            Tempatan
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jenisBarang" id="jenisBarang2">
                                        <label class="form-check-label" for="jenisBarang2">
                                            Import
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 form-group my-2">
                        <div class="row">
                            <label class="col-sm-4 control-label">Wajaran Spesifikasi</label>
                            <div class="col-sm-8">
                                <div class="col-sm-12">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="wajaranSpesifikasi" id="wajaranSpesifikasi1" checked>
                                        <label class="form-check-label" for="wajaranSpesifikasi1">
                                            Mengikut Keutamaan
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="wajaranSpesifikasi" id="wajaranSpesifikasi2">
                                        <label class="form-check-label" for="wajaranSpesifikasi2">
                                            Secara Terus
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 form-group my-2">
                        <div class="row">
                            <label class="col-sm-4 control-label">Status</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="" name="" value="">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 form-group my-2">
                        <div class="row">
                            <label class="col-sm-4 control-label">Penghantaran Fizikal</label>
                            <div class="col-sm-8">
                                <div class="col-sm-12">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="penghantaranFizikal" id="penghantaranFizikal1" checked>
                                        <label class="form-check-label" for="penghantaranFizikal1">
                                            Ya
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="penghantaranFizikal" id="penghantaranFizikal2">
                                        <label class="form-check-label" for="penghantaranFizikal2">
                                            Tidak
                                        </label>
                                    </div>
                                </div>
                            </div>
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path><line x1="8" y1="6" x2="16" y2="6"></line><line x1="8" y1="10" x2="16" y2="10"></line></svg>
                </div>
                Templat Spesifikasi
            </h3>
        </div>
        <div class="card-body p-2">
            <div class="p-4">
                <div class="row mx-2">
                    <div class="small lh-sm mt-2">
                        <p class="card-title-desc text-danger fst-italic">
                            Klik 'ikon pensil' untuk penetapan skor
                        </p>
                    </div>
                </div>
                <div class="row mb-2 mx-2">
                    <div class="col-12 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-sm btn-secondary">
                            Muat Turun Templat
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary">
                            Muat Naik Dokumen BQ/Spesifikasi
                        </button>
                        <button type="button" class="btn btn-sm btn-success add_btn_item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Tambah Item
                        </button>
                    </div>
                </div>
                <div class="row mx-2">
                    <div class="table-responsive">
                        <table id="dt_tmpltSpec" class="table table-modern w-100 mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Spesifikasi</th>
                                    <th class="text-center">Kekerapan/Kuantiti</th>
                                    <th class="text-center">Unit Ukuran</th>
                                    <th class="text-center">Penetapan Skema</th>
                                    <th class="text-center">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="add_row_item item-row d-none">
                                    <td>
                                        <input type="text" class="form-control form-control-sm">
                                    </td>
                                    <td></td>
                                    <td class="text-center">
                                        <input type="number" class="form-control form-control-sm text-center" value="0" min="0">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm">
                                    </td>
                                    <td></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-warning btn-circle text-white add_btn_spec">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-circle delete_item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                                <path d="M10 11v6"></path>
                                                <path d="M14 11v6"></path>
                                                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="add_row_spec spec-row d-none">
                                    <td></td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm">
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <select class="form-select form-select-sm w-auto typeSelect">
                                                <option></option>
                                                <option value="1">Text</option>
                                                <option value="2">Nombor</option>
                                                <option value="3">Ya/Tidak</option>
                                            </select>
                                            <svg id="" class="text-warning edit-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                style="cursor: pointer;">
                                                <path d="M12 20h9"></path>
                                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                                            </svg>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-circle delete_spec">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                                <path d="M10 11v6"></path>
                                                <path d="M14 11v6"></path>
                                                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="no-data-row">
                                    <td colspan="6" class="text-center text-muted">
                                    Tiada rekod
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4 mx-2">
        <div class="col-12 d-flex justify-content-between">
            <div>
                <a href="{{ route('senaraiTeknikal') }}" type="button" class="btn btn-sm btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Kembali
            </a>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-primary">
                    Simpan
                </button>
                <button type="button" class="btn btn-sm btn-success">
                    Selesai
                </button>
                <button type="button" class="btn btn-sm btn-danger">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <!-- Modal for Text -->


    <div class="modal fade" id="modalText" tabindex="-1" aria-labelledby="modalTextLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTextLabel">Penetapan Skor - Text</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="p-2">
                        <div class="row">
                            <div class="col-sm-12 form-group my-2">
                                <div class="row">
                                    <label class="col-sm-2 control-label">Spesifikasi</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control form-control-sm" id="" name="" row="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 form-group my-2">
                                <div class="row">
                                    <label class="col-sm-4 control-label">Jenis Skema Maklumbalas</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm" id="" name="" value="Text" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 form-group my-2">
                                <div class="row">
                                    <label class="col-sm-4 control-label">Jenis Skor</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm" id="" name="" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 form-group my-2">
                                <div class="row">
                                    <label class="col-sm-4 control-label">Skema Maksima</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm" id="" name="" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 d-flex justify-content-end">
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M4 4h12l4 4v12H4z"/>
                                            <rect x="7" y="4" width="8" height="5"/>
                                            <rect x="7" y="14" width="10" height="6"/>
                                        </svg>
                                        Simpan
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                            <path d="M10 11v6"></path>
                                            <path d="M14 11v6"></path>
                                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                                        </svg>
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Nombor -->
    <div class="modal fade" id="modalNumber" tabindex="-1" aria-labelledby="modalNumberLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNumberLabel">Penetapan Skor - Nombor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="p-2">
                        <div class="row">
                            <div class="col-sm-12 form-group my-2">
                                <div class="row">
                                    <label class="col-sm-2 control-label">Spesifikasi</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control form-control-sm" id="" name="" row="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 form-group my-2">
                                <div class="row">
                                    <label class="col-sm-4 control-label">Jenis Skema Maklumbalas</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm" id="" name="" value="Nombor" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 form-group my-2">
                                <div class="row">
                                    <label class="col-sm-4 control-label">Jenis Skema</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm" id="" name="" value="Automatik" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 d-flex justify-content-end">
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M4 4h12l4 4v12H4z"/>
                                            <rect x="7" y="4" width="8" height="5"/>
                                            <rect x="7" y="14" width="10" height="6"/>
                                        </svg>
                                        Simpan
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                            <path d="M10 11v6"></path>
                                            <path d="M14 11v6"></path>
                                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                                        </svg>
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Ya/Tidak -->
    <div class="modal fade" id="modalYesNo" tabindex="-1" aria-labelledby="modalYesNoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalYesNoLabel">Penetapan Skor - Ya/Tidak</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="p-2">
                        <div class="row">
                            <div class="col-sm-12 form-group my-2">
                                <div class="row">
                                    <label class="col-sm-2 control-label">Spesifikasi</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control form-control-sm" id="" name="" row="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 form-group my-2">
                                <div class="row">
                                    <label class="col-sm-4 control-label">Jenis Skema Maklumbalas</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm" id="" name="" value="Ya/Tidak" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 form-group my-2">
                                <div class="row">
                                    <label class="col-sm-4 control-label">Jenis Skor</label>
                                    <div class="col-sm-8">
                                        <select name="yt_fg" id="yt_fg" class="form-control selectize" placeholder="Sila  Pilih">
                                            <option value="">Sila pilih..</option>
                                            <option value="1">Automatik</option>
                                            <option value="2">Manual</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 form-group my-2" id="yt_1">
                                <div class="row">
                                    <label class="col-sm-4 control-label">Skema Skor</label>
                                    <div class="col-sm-8">
                                        <div class="row mb-2">
                                            <label class="col-sm-4 control-label">Ya</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control form-control-sm" id="" name="" value="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-sm-4 control-label">Tidak</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control form-control-sm" id="" name="" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 form-group my-2" id="yt_2">
                                <div class="row">
                                    <label class="col-sm-4 control-label">Skor Maksima</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm" id="" name="" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12 d-flex justify-content-end">
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M4 4h12l4 4v12H4z"/>
                                            <rect x="7" y="4" width="8" height="5"/>
                                            <rect x="7" y="14" width="10" height="6"/>
                                        </svg>
                                        Simpan
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                            <path d="M10 11v6"></path>
                                            <path d="M14 11v6"></path>
                                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                                        </svg>
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">

    document.addEventListener('DOMContentLoaded', function () {
        var table = document.querySelector('#dt_tmpltSpec');
        if (!table) return;
        var tbody = table.querySelector('tbody');
        var addItemBtn = document.querySelector('.add_btn_item');

        function removeNoDataRow(){
            var noData = tbody.querySelector('.no-data-row');
            if(noData) noData.remove();
        }

        function checkNoData(){
            var rows = tbody.querySelectorAll('tr.item-row:not(.d-none)');
            if(rows.length === 0){
                var row = document.createElement('tr');
                row.className="no-data-row";
                row.innerHTML = `
                    <td colspan="6" class="text-center text-muted">
                    No data
                    </td>
                `;
                tbody.appendChild(row);
            }
        }

        /* ADD ITEM */
        addItemBtn.addEventListener('click', function(){
            removeNoDataRow();
            var template = tbody.querySelector('.add_row_item');
            var clone = template.cloneNode(true);
            clone.classList.remove('d-none','add_row_item');
            tbody.appendChild(clone);
        });

        /* TABLE ACTIONS */
        tbody.addEventListener('click', function(e){
            /* ADD SPEC */
            if(e.target.closest('.add_btn_spec')){
                var template = tbody.querySelector('.add_row_spec');
                var clone = template.cloneNode(true);
                clone.classList.remove('d-none','add_row_spec');
                var itemRow = e.target.closest('tr');
                itemRow.after(clone);
            }
            /* DELETE SPEC */
            if(e.target.closest('.delete_spec')){
                var row = e.target.closest('tr');
                row.remove();
            }
            /* DELETE ITEM + ALL ITS SPECS */
            if(e.target.closest('.delete_item')){
                var row = e.target.closest('tr');
                var next = row.nextElementSibling;
                while(next && next.classList.contains('spec-row')){
                    var temp = next.nextElementSibling;
                    next.remove();
                    next = temp;
                }
                row.remove();
                checkNoData();
            }
        });

        tbody.addEventListener('click', function(e){

            if(e.target.closest('.edit-icon')){
                let row = e.target.closest('tr');
                let select = row.querySelector('.typeSelect');
                let value = select.value;

                if(value == "1"){
                    new bootstrap.Modal(document.getElementById('modalText')).show();
                }
                else if(value == "2"){
                    new bootstrap.Modal(document.getElementById('modalNumber')).show();
                }
                else if(value == "3"){
                    new bootstrap.Modal(document.getElementById('modalYesNo')).show();
                }
                else {
                    alert('Please select a type first');
                }
            }
        });

        // ---------- YT SELECT SHOW/HIDE LOGIC ----------
        const ytSelect = document.getElementById('yt_fg');
        const yt1 = document.getElementById('yt_1');
        const yt2 = document.getElementById('yt_2');

        if(ytSelect){
            // Hide both sections initially
            yt1.style.display = 'none';
            yt2.style.display = 'none';

            ytSelect.addEventListener('change', function() {
                const value = this.value;

                if(value === "1") {
                    yt1.style.display = 'block';
                    yt2.style.display = 'none';
                }
                else if(value === "2") {
                    yt1.style.display = 'none';
                    yt2.style.display = 'block';
                }
                else {
                    // No selection
                    yt1.style.display = 'none';
                    yt2.style.display = 'none';
                }
            });
        }
    });

</script>
@endsection

