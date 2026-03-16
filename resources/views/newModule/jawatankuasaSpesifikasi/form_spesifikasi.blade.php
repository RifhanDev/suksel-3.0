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
                    <h6 class="text-warning fw-bold heartbeat">DALAM PROSES</h6>
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
                        <table id="dt_tmpltSpec" data-path="" class=" table table-modern w-100 mb-0">
                            <thead>
                                <tr>
                                    <th colspan="1" class="text-center">Item</th>
                                    <th colspan="1" class="text-center">Spesifikasi</th>
                                    <th rowspan="2" class="text-center">Kekerapan/Kuantiti</th>
                                    <th rowspan="2" class="text-center">Unit Ukuran</th>
                                    <th rowspan="2" class="text-center">Penetapan Skema</th>
                                    <th rowspan="2" class="text-center">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="add_row_item d-none">
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
                                        <button type="button" class="btn btn-sm btn-warning text-white add_btn_spec">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="add_row_spec d-none">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-center">
                                        <select class="form-select form-select-sm">
                                            <option></option>
                                            <option>Text</option>
                                            <option>Nombor</option>
                                            <option>Ya/Tidak</option>
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger">
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
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mx-2">
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
            </div>
        </div>
    </div>


 


<script type="text/javascript">

    document.addEventListener('DOMContentLoaded', function () {

        var table = document.querySelector('#dt_tmpltSpec');
        if (!table) return;
        var tbody = table.querySelector('tbody');
        var addItemBtn = document.querySelector('.add_btn_item');

        if (addItemBtn) {
            addItemBtn.addEventListener('click', function () {

                var template = tbody.querySelector('.add_row_item');
                var clone = template.cloneNode(true);

                clone.classList.remove('d-none', 'add_row_item');
                tbody.appendChild(clone);

            });
        }

        tbody.addEventListener('click', function (e) {
            if (e.target.closest('.add_btn_spec')) {

                var template2 = tbody.querySelector('.add_row_spec');
                var clone2 = template2.cloneNode(true);

                clone2.classList.remove('d-none', 'add_row_spec');
                var currentRow = e.target.closest('tr');
                currentRow.after(clone2);

            }
        });
    });

</script>

  
@endsection

