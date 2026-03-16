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
                Pengalaman Kerja
            </h3>
        </div>
        <div class="card-body p-2">
            <div class="p-4">
                <div class="row mx-2">
                    <div class="small lh-sm mt-2">
                        <p class="card-title-desc text-danger fst-italic">
                            Perlu diisi oleh Petender
                        </p>
                    </div>
                </div>
                <div class="row mb-2 mx-2">
                    <div class="col-12 d-flex justify-content-end gap-2">
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
                                    <th colspan="6">No. Rujukan Petender</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Bil</th>
                                    <th class="text-center">Senarai Kerja Yang Disiapkan</th>
                                    <th class="text-center">PIC</th>
                                    <th class="text-center">No. Telefon PIC</th>
                                    <th class="text-center">Nilai Kerja (RM)</th>
                                    <th class="text-center">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger text-white">
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
                                <tr class="add_row_item d-none">
                                    <td class="text-center"></td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger text-white">
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
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Jumlah</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    Laporan
                </button>
                <button type="button" class="btn btn-sm btn-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h12l4 4v12H4z"/>
                        <rect x="7" y="4" width="8" height="5"/>
                        <rect x="7" y="14" width="10" height="6"/>
                    </svg>
                    Simpan
                </button>
            </div>
        </div>
    </div>

 


<script type="text/javascript">

  document.addEventListener('DOMContentLoaded', function () {

    const table = document.querySelector('#dt_tmpltSpec');
    if (!table) return;

    const tbody = table.querySelector('tbody');
    const addItemBtn = document.querySelector('.add_btn_item');
    const totalCell = table.querySelector('.total-nilai');

    function updateRowNumbers() {
        const rows = tbody.querySelectorAll('tr:not(.add_row_item)');
        rows.forEach((row, index) => {
            row.children[0].textContent = index + 1;
        });
    }

    function updateTotal() {

        let total = 0;

        const inputs = tbody.querySelectorAll('.nilai-kerja');

        inputs.forEach(input => {
            const value = parseFloat(input.value) || 0;
            total += value;
        });

        totalCell.textContent = total.toLocaleString('en-MY', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // ADD ROW
    if (addItemBtn) {
        addItemBtn.addEventListener('click', function () {

            const template = tbody.querySelector('.add_row_item');
            const clone = template.cloneNode(true);

            clone.classList.remove('d-none', 'add_row_item');

            // clear input values
            clone.querySelectorAll('input').forEach(input => input.value = '');

            tbody.appendChild(clone);

            updateRowNumbers();
        });
    }

    // DELETE ROW
    tbody.addEventListener('click', function (e) {

        const deleteBtn = e.target.closest('.btn-danger');

        if (deleteBtn) {

            const row = deleteBtn.closest('tr');

            // prevent deleting last row
            const rows = tbody.querySelectorAll('tr:not(.add_row_item)');
            if (rows.length <= 1) return;

            row.remove();

            updateRowNumbers();
            updateTotal();
        }

    });

    // AUTO UPDATE TOTAL WHEN VALUE CHANGES
    tbody.addEventListener('input', function (e) {

        if (e.target.classList.contains('nilai-kerja')) {
            updateTotal();
        }

    });

});

</script>

  
@endsection

