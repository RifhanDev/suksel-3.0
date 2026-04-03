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
    </style>

    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <!-- Title -->
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Semak Kewangan</h3>
            <p class="text-muted small m-0">Paparan senarai  semak bahagian kewangan bagi tender/sebutharga.</p>
        </div>
    </div>

    <div class="card border shadow-sm mb-2 rounded-3">
        <div class="card-body p-3">
            <div class="row g-2 align-items-end">
                <div class="col-4 col-lg-4">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Tender</label>
                    <h6 class="text-primary">SUKSEL/PERT/2026/001</h6>
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
                Senarai Semak Kewangan
            </h3>
        </div>
        <div class="card-body p-2">
            <div class="p-4">
                <div class="row mb-3 mx-3">
                    <div class="alert-selangor mb-4">
                        <div class="alert-selangor-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>

                        </div>
                        <div class="small lh-sm mt-2">
                            <strong>Penyediaan Spesifikasi & Skor</strong>
                            <p class="card-title-desc text-danger fst-italic mt-2">
                                1. Senarai semak dokumen tawaran (Teknikal) dijana berdasarkan Kategori Perolehan.<br>
                                2. Sila pilih kotak semak dalam lajur skor jika hendak memilih senarai semak tersebut untuk dinilai.<br>
                                3. Klik "ikon pensil" untuk kunci masuk skema skor penilaian atau pinda spesifikasi.<br>
                                4. Klik butang Cipta Spesifikasi untuk cipta templat dan spesifikasi baru. Sila klik untuk Panduan Penyediaan Item dan Spesifikasi.<br>
                                5. Klik butang Tambah untuk kunci masuk senarai semak baru.<br>
                                6. Senarai semak dengan tindakan Muatnaik dokumen oleh pembekal, secara automatik menjadi dokumen pematuhan.<br>
                                7. Klik butang Senarai Semak Standard dan pilih senarai semak yang diperlukan.<br>
                                8. Untuk perkhidmatan yang memerlukan bayaran secara progresif, sila pilih tempat perkhidmatan.<br>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row mb-2 mx-2">
                    <div class="col-12 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-sm btn-success btn-tambah-kewangan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger btn-hapus-kewangan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                <path d="M10 11v6"></path>
                                <path d="M14 11v6"></path>
                                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="row mx-2">
                    <div class="table-responsive">
                        <table id="datatable-buttons" data-path="" class=" table table-modern w-100 mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center"><input type="checkbox" class="form-check-input px-0 check-all-kewangan"></th>
                                    <th>Tajuk / Dokumen</th>
                                    <th class="text-center">Mekanisma</th>
                                    <th class="text-center">Tindakan Pembekal</th>
                                    <th class="text-center">Skema</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Rujukan</th>
                                    <th class="text-center">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-kewangan"></td>
                                    <td>Perkhidmatan Penilaian Forensik Keatas Sistem XXXX</td>
                                    <td class="text-center">Spesifikasi</td>
                                    <td class="text-center">Kunci Masuk</td>
                                    <!-- <td class="text-center"><input type="checkbox" class="form-check-input" name="" id="" checkdate></td> -->
                                    <td><input type="text" class="form-control form-control-sm" id="" name="" value=""></td>
                                    <td class="text-center"><span class="badge bg-warning">Belum Lengkap</span></td>
                                    <td></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-warning text-white">Kemaskini</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-kewangan"></td>
                                    <td>Maklumat Profil Petender</td>
                                    <td class="text-center">Borang Atas Talian</td>
                                    <td class="text-center">Kunci Masuk</td>
                                    <td><input type="text" class="form-control form-control-sm" id="" name="" value=""></td>
                                    <td class="text-center"><span class="badge bg-warning">Belum Lengkap</span></td>
                                    <td></td>
                                    <td class="text-center">
                                        <a href="{{ route('prflPetender') }}" type="button" class="btn btn-sm btn-warning text-white">Kemaskini</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-kewangan"></td>
                                    <td>Penyata Bank Terkini (3 Bulan Terakhir) Syarikat</td>
                                    <td class="text-center">Borang Atas Talian</td>
                                    <td class="text-center">Kunci Masuk</td>
                                    <td><input type="text" class="form-control form-control-sm" id="" name="" value=""></td>
                                    <td class="text-center"><span class="badge bg-warning">Belum Lengkap</span></td>
                                    <td></td>
                                    <td class="text-center">
                                    <a href="{{ route('pnytBank') }}" type="button" class="btn btn-sm btn-warning text-white">Kemaskini</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-kewangan"></td>
                                    <td>Salinan Sijil Pendaftaran dengan Kementerian Kewangan</td>
                                    <td class="text-center">
                                        <select name="" id="" class="form-control">
                                            <option value="" class="text-center">Petender Muat Naik</option>
                                        </select>
                                    </td>
                                    <td class="text-center">Muat Naik</td>
                                    <td><input type="text" class="form-control form-control-sm" id="" name="" value=""></td>
                                    <td class="text-center"><span class="badge bg-success">Lengkap</span></td>
                                    <td></td>
                                    <td class="text-center"></td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-kewangan"></td>
                                    <td>Salinan Sijil Akuan Syarikat Bumiputera dengan Kementerian Kewangan</td>
                                    <td class="text-center">
                                        <select name="" id="" class="form-control">
                                            <option value="" class="text-center">Petender Muat Naik</option>
                                        </select>
                                    </td>
                                    <td class="text-center">Muat Naik</td>
                                    <td><input type="text" class="form-control form-control-sm" id="" name="" value=""></td>
                                    <td class="text-center"><span class="badge bg-success">Lengkap</span></td>
                                    <td></td>
                                    <td class="text-center"></td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-kewangan"></td>
                                    <td>Surat Akuan Pembida Berjaya (Lampiran B)</td>
                                    <td class="text-center">
                                        <select name="" id="" class="form-control">
                                            <option value="" class="text-center">PTJ Muat Naik</option>
                                        </select>
                                    </td>
                                    <td class="">
                                        <select name="" id="" class="form-control">
                                            <option value="" class="text-center">Muat Turun</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm" id="" name="" value=""></td>
                                    <td class="text-center"><span class="badge bg-success">Lengkap</span></td>
                                    <td><a href="javascript: void(0);">Surat Akuan Pembida Berjaya.docx</a></td>
                                    <td class="text-center"></td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-kewangan"></td>
                                    <td>Surat Akuan Sumpah Syarikat (Lampiran C)</td>
                                    <td class="text-center">
                                        <select name="" id="" class="form-control">
                                            <option value="" class="text-center">PTJ Muat Naik</option>
                                        </select>
                                    </td>
                                    <td class="">
                                        <select name="" id="" class="form-control">
                                            <option value="" class="text-center">Muat Turun</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm" id="" name="" value=""></td>
                                    <td class="text-center"><span class="badge bg-success">Lengkap</span></td>
                                    <td><a href="javascript: void(0);">Surat Akuan Sumpah Syarikat.docx</a></td>
                                    <td class="text-center"></td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" class="form-check-input row-check-kewangan"></td>
                                    <td>Penyata Kewangan (2 Tahun) Syarikat yang telah diaudit</td>
                                    <td class="text-center">
                                        <select name="" id="" class="form-control">
                                            <option value="" class="text-center">Petender Muat Naik</option>
                                        </select>
                                    </td>
                                    <td class="">
                                        <select name="" id="" class="form-control">
                                            <option value="" class="text-center">Muat Turun</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control form-control-sm" id="" name="" value=""></td>
                                    <td class="text-center"><span class="badge bg-success">Lengkap</span></td>
                                    <td></td>
                                    <td class="text-center"></td>
                                </tr>
                                <tr class="row-template-kewangan d-none">
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input row-check-kewangan">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm"
                                            placeholder="Tajuk / Dokumen">
                                    </td>
                                    <td class="text-center">
                                        <select class="form-select form-select-sm">
                                            <option>Wajib</option>
                                            <option>Pilihan</option>
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <select class="form-select form-select-sm">
                                            <option>Muat Naik</option>
                                            <option>Isi Borang</option>
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <input type="number" class="form-control form-control-sm text-center"
                                            value="0" min="0">
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">Baharu</span>
                                    </td>
                                    <td class="text-center"></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-warning text-white">Kemaskini</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mb-5 mx-2">
                    <div class="col-12 d-flex justify-content-end gap-2">
                        <a href="#" class="btn btn-sm btn-sm-cust btn-secondary" data-bs-toggle="modal" data-bs-target="#senaraiSemakStandard">
                            Senarai Semak Standard
                        </a>
                        <a href="#" class="btn btn-sm btn-sm-cust btn-secondary" data-bs-toggle="modal" data-bs-target="#ciptaSpesifikasi">
                            Cipta Spesifikasi
                        </a>
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
                Harga Indikatif
            </h3>
        </div>
        <div class="card-body p-2">
            <div class="p-4">
                <div class="row mb-3 mx-3">
                    <div class="col-sm-6 form-group my-2">
                        <div class="row">
                            <label class="col-sm-4 control-label">Harga Indikatif (RM) :</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="" name="" value="29,000.00">
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
                Penetepan Penanda Aras Tahap Lulus (%)
            </h3>
        </div>
        <div class="card-body p-2">
            <div class="p-4">
                <div class="row mb-3 mx-3">
                    <div class="col-sm-6 form-group my-2">
                        <div class="row">
                            <label class="col-sm-4 control-label">Penilaian Kewangan :</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="" name="" value="">
                            </div> 
                            <div class="col-sm-2">
                                <span style="font-size:15px">/ 70</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3 mx-3">
                    <div class="col-sm-6 form-group my-2">
                        <div class="row">
                            <label class="col-sm-4 control-label">Tahap Lulus : </label>
                            <div class="col-sm-8 text-primary">
                                <span id="">70</span> Peratus
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
                Dokumen Sokongan / Rujukan 
            </h3>
        </div>
        <div class="card-body p-2">
            <div class="p-4">
                <div class="row mb-3 mx-3">
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4 mx-2">
        <div class="col-12 d-flex justify-content-end gap-2">
            <button type="button" class="btn-md-sm btn btn-success btn-simpan">
                Simpan
            </button>
            <button type="button" class="btn-md-sm btn btn-primary btn-hantar">
                Hantar
            </button>
        </div>
    </div>

    <!-- ===================== SUCCESS MODAL ===================== -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4">

                <div class="mb-3">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="10" fill="#E6F7F3"/>
                        <path d="M10 14.2L7.8 12l-1.4 1.4L10 17l8-8-1.4-1.4L10 14.2z"
                            fill="#19c1a7"/>
                    </svg>
                </div>

                <h5 class="fw-bold mb-2">Berjaya</h5>
                <p class="text-muted mb-4">
                    Maklumat telah berjaya disimpan.
                </p>

                <button type="button"
                        class="btn btn-primary px-4"
                        data-bs-dismiss="modal">
                    Tutup
                </button>

            </div>
        </div>
    </div>

 

    <div class="modal fade" id="senaraiSemakStandard" tabindex="-1" aria-labelledby="senaraiSemakStandardLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="senaraiSemakStandardLabel">Senarai Semak Standard</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row d-flex justify-content-center">
                        <div class="col-10">
                            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100" 
                                data-table-sort="id"
                                data-table-order="asc"
                                data-page="1">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="form-check-input check-all-standard"></th>
                                        <th>Tajuk / Dokumen</th>
                                    </tr>
                                </thead>
                               <tbody>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input row-check-standard"></td>
                                        <td>Pengalaman Syarikat Dengan Kerajaan Persekutuan (Bilangan Kontrak yang pernah diikat)</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input row-check-standard"></td>
                                        <td>Pengalaman Syarikat Dengan Bukan Kerajaan Persekutuan (Bilangan Kontrak yang pernah diikat)</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input row-check-standard"></td>
                                        <td>Skop Bekalan Dan Perkhidmatan</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input row-check-standard"></td>
                                        <td>Salinan Borang KWSP A setiap pekerja bagi bulan caruman terakhir</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input row-check-standard"></td>
                                        <td>Bilangan Kakitangan</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input row-check-standard"></td>
                                        <td>Brosur / Risalah</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input row-check-standard"></td>
                                        <td>Surat pengesahan pendaftaran dengan Pertubuhan Keselamatan Sosial (Perkeso) yang telah dikeluarkan mengikut Akta Keselamatan Sosial Pekerja 1969. Jadual Caruman Bulanan (Borang 8A) dan Resit Bayaran Caruman yang terbaru</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input row-check-standard"></td>
                                        <td>Cadangan Bertulis</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input row-check-standard"></td>
                                        <td>Lesen Premis oleh PBT</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input row-check-standard"></td>
                                        <td>Jadual Pelaksanaan</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-sm btn-success m-1">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    width="14" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 3l7 18 2-7 7-2L3 3z"></path>
                                </svg>
                                Pilih
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ciptaSpesifikasi" tabindex="-1" aria-labelledby="ciptaSpesifikasiLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ciptaSpesifikasiLabel">Cipta Spesifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="modern-card mb-4">
                        <div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                style="margin-right:6px;color:#6c757d;">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <span class="fw-bold text-dark text-uppercase small" style="letter-spacing: 0.5px;">Cari</span>
                        </div>
                        <div class="p-4 bg-white rounded-bottom">
                            <div class="row mb-4">
                                <div class="col-sm-12 form-group my-2">
                                    <div class="row">
                                        <label class="col-sm-4 control-label">Klon Spesifikasi Daripada <span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <div class="col-sm-12">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="klonSpesifikasi" id="klonSpesifikasi1" checked>
                                                    <label class="form-check-label" for="klonSpesifikasi1">
                                                        Templat Standard / Kosong
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="klonSpesifikasi" id="klonSpesifikasi2">
                                                    <label class="form-check-label" for="klonSpesifikasi2">
                                                        Sebut Harga / Tender Yang Lepas
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 form-group">
                                    <div class="row">
                                        <label class="col-sm-4 control-label">Jenis Item</label>
                                        <div class="col-sm-8">
                                            <select name="" id="" class="form-control selectize" placeholder="Sila  Pilih">
                                                <option value="">Sila pilih..</option>
                                                <option value="">Bekalan</option>
                                                <option value="">Perkhidmatan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-end">
                                    <button type="button" class="btn btn-sm btn-info" data-bs-dismiss="modal">
                                        <svg xmlns="http://www.w3.org/2000/svg" 
                                            width="18" 
                                            height="18"
                                            viewBox="0 0 24 24" 
                                            fill="none" 
                                            stroke="currentColor"
                                            stroke-width="2" 
                                            stroke-linecap="round" 
                                            stroke-linejoin="round"
                                            style="color:#f7f7f7;">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                        </svg>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            width="18"
                                            height="18"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round">
                                        <polyline points="23 4 23 10 17 10"></polyline>
                                        <polyline points="1 20 1 14 7 14"></polyline>
                                        <path d="M3.5 9a9 9 0 0 1 14.5-3L23 10"></path>
                                        <path d="M20.5 15a9 9 0 0 1-14.5 3L1 14"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                     <div class="modern-card mb-4">
                        <div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                width="18"
                                height="18"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M16 2H8a2 2 0 0 0-2 2v12"></path>
                                <rect x="8" y="6" width="14" height="16" rx="2"></rect>
                                <polyline points="14 6 14 12 20 12"></polyline>
                            </svg>
                            <span class="fw-bold text-dark text-uppercase small" style="letter-spacing: 0.5px;">Templat</span>
                        </div>
                        <div class="p-4 bg-white rounded-bottom">
                            <table id="templateTable" class="table table-bordered dt-responsive nowrap w-100" 
                                data-table-sort="id"
                                data-table-order="asc"
                                data-page="1">
                                <thead>
                                <tr>
                                <th class="text-center">
                                    <input type="checkbox"
                                        class="form-check-input check-all-template">
                                        </th>
                                        <th class="text-center">Tajuk / Dokumen</th>
                                        <th class="text-center">Skor Maksima</th>
                                        <th class="text-center">Jenis Item</th>
                                        <th class="text-center">Dicipta Oleh</th>
                                        <th class="text-center">Tindakan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <!-- ROW 1 -->
                                    <tr class="template-row">
                                        <td class="text-center">
                                            <input type="checkbox"
                                                class="form-check-input row-check-template">
                                        </td>
                                        <td>Pengalaman Syarikat Dengan Kerajaan</td>
                                        <td class="text-center">20</td>
                                        <td class="text-center">Perkhidmatan</td>
                                        <td class="text-center">Admin Sistem</td>
                                        <td class="text-center">
                                            <a href="{{ route('spesifikasiForm') }}" class="btn btn-sm btn-sm-cust btn-outline-warning">
                                                Cipta 
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- ROW 2 -->
                                    <tr class="template-row">
                                        <td class="text-center">
                                            <input type="checkbox"
                                                class="form-check-input row-check-template">
                                        </td>
                                        <td>Skop Bekalan dan Perkhidmatan</td>
                                        <td class="text-center">30</td>
                                        <td class="text-center">Bekalan</td>
                                        <td class="text-center">Urusetia</td>
                                        <td class="text-center">
                                            <a href="{{ route('spesifikasiForm') }}" class="btn btn-sm btn-sm-cust btn-outline-warning">
                                                Cipta 
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- ROW 3 -->
                                    <tr class="template-row">
                                        <td class="text-center">
                                            <input type="checkbox"
                                                class="form-check-input row-check-template">
                                        </td>
                                        <td>Jadual Pelaksanaan Projek</td>
                                        <td class="text-center">25</td>
                                        <td class="text-center">Perkhidmatan</td>
                                        <td class="text-center">Admin Sistem</td>
                                        <td class="text-center">
                                            <a href="{{ route('spesifikasiForm') }}" class="btn btn-sm btn-sm-cust btn-outline-warning">
                                                Cipta 
                                            </a>
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
    

<script type="text/javascript">

// CHECKBOX FUNCTIONALITY Table Senarai Semak Teknikal
document.addEventListener('DOMContentLoaded', function () {

    const table = document.querySelector('#datatable-buttons');
    if (!table) return;

    const checkAll = table.querySelector('.check-all-kewangan');
    if (!checkAll) return;

    // CHECK / UNCHECK ALL
    checkAll.addEventListener('change', function () {
        table.querySelectorAll('.row-check-kewangan').forEach(cb => {
            cb.checked = this.checked;
        });
    });

    // UPDATE HEADER CHECKBOX WHEN ROW CHANGES
    table.querySelectorAll('.row-check-kewangan').forEach(cb => {
        cb.addEventListener('change', function () {
            const total = table.querySelectorAll('.row-check-kewangan').length;
            const checked = table.querySelectorAll('.row-check-kewangan:checked').length;
            checkAll.checked = total === checked;
        });
    });

});

// CHECKBOX FUNCTIONALITY Modal Senarai Semak Standard
document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('senaraiSemakStandard');

    if (!modal) return;

    const checkAll = modal.querySelector('.check-all-standard');

    // CHECK / UNCHECK ALL
    checkAll.addEventListener('change', function () {
        const rows = modal.querySelectorAll('.row-check-standard');
        rows.forEach(cb => cb.checked = this.checked);
    });

    // UPDATE HEADER CHECKBOX WHEN ROW CHANGES
    modal.querySelectorAll('.row-check-standard').forEach(cb => {
        cb.addEventListener('change', function () {
            const rows = modal.querySelectorAll('.row-check-standard');
            const checked = modal.querySelectorAll('.row-check-standard:checked');
            checkAll.checked = rows.length === checked.length;
        });
    });

});

// CHECKBOX FUNCTIONALITY Modal Cipta Spesifikasi
document.addEventListener('DOMContentLoaded', function () {

    const table = document.getElementById('templateTable');
    if (!table) return;

    const checkAll = table.querySelector('.check-all-template');
    const rows = table.querySelectorAll('.row-check-template');

    /* =========================
       CLICK ROW TO TOGGLE CHECK
    ========================= */
    table.querySelectorAll('.template-row').forEach(row => {
        row.addEventListener('click', function (e) {

            // Prevent double toggle when clicking checkbox or button
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'BUTTON' || e.target.closest('button')) {
                return;
            }

            const checkbox = row.querySelector('.row-check-template');
            checkbox.checked = !checkbox.checked;
            checkbox.dispatchEvent(new Event('change'));
        });
    });

    /* =========================
       CHECK / UNCHECK ALL
    ========================= */
    checkAll.addEventListener('change', function () {
        rows.forEach(cb => cb.checked = this.checked);
    });

    /* =========================
       UPDATE HEADER CHECKBOX
    ========================= */
    rows.forEach(cb => {
        cb.addEventListener('change', function () {
            const total = rows.length;
            const checked = table.querySelectorAll('.row-check-template:checked').length;
            checkAll.checked = total === checked;
        });
    });

});

// TABLE FUNCTIONALITY Senarai Semak Teknikal
document.addEventListener('DOMContentLoaded', function () {

    const table = document.querySelector('#datatable-buttons');
    if (!table) return;

    const tbody = table.querySelector('tbody');

    /* =========================
       TAMBAH ROW
    ========================= */
    document.querySelector('.btn-tambah-kewangan')
        .addEventListener('click', function () {

            const template = tbody.querySelector('.row-template-kewangan');
            const clone = template.cloneNode(true);

            clone.classList.remove('d-none', 'row-template-kewangan');
            tbody.appendChild(clone);
        });

    /* =========================
       HAPUS ROW (CHECKED ONLY)
    ========================= */
    document.querySelector('.btn-hapus-kewangan')
        .addEventListener('click', function () {

            const checkedRows = tbody.querySelectorAll('.row-check-kewangan:checked');

            if (checkedRows.length === 0) {
                alert('Sila pilih sekurang-kurangnya satu rekod untuk dihapus.');
                return;
            }

            checkedRows.forEach(cb => {
                cb.closest('tr').remove();
            });

            // Uncheck header checkbox
            const checkAll = table.querySelector('.check-all-kewangan');
            if (checkAll) checkAll.checked = false;
        });

});

// SUCCESS MODAL FOR SIMPAN & HANTAR BUTTONS
document.addEventListener('DOMContentLoaded', function () {

    const successModal = new bootstrap.Modal(
        document.getElementById('successModal')
    );

    // SIMPAN
    document.querySelector('.btn-simpan')
        .addEventListener('click', function () {
            successModal.show();
        });

    // HANTAR
    document.querySelector('.btn-hantar')
        .addEventListener('click', function () {
            successModal.show();
        });

});
</script>

  
@endsection

