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

        .btn-circle:hover {
            animation: btnPop 0.25s ease forwards;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        
        .nested-tabs {
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
        }

        .nested-tab-btn {
            border: none;
            background: transparent;
            padding: 6px 20px;
            font-weight: 600;
            cursor: pointer;
            margin-right: 3px;
        }

        /* Active nested tab */
        .nested-tab-btn.active {
            background: #c0392b;
            color: #fff;
            border-radius: 4px 4px 0 0;
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

    <div class="content-card mb-4 p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width: 38px; height: 38px;">
                    <svg viewBox="0 0 25 25" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.6 5c.6 0 1.2.2 1.6.7.4.4.7 1 .7 1.6 0 1.3-1 2.3-2.3 2.3s-2.3-1-2.3-2.3S11.3 5 12.6 5z"/>
                        <path d="M10.3 12.9h4.7c1.6.1 2.9 1.4 2.9 3s-1.3 2.9-2.9 3h-4.7c-1.6-.1-2.9-1.4-2.9-3s1.3-2.9 2.9-3z"/>
                        <path d="M19 7.3c.5 0 .9.2 1.2.5.3.3.5.7.5 1.2 0 1-0.8 1.8-1.7 1.8-1 0-1.8-.8-1.8-1.8 0-1 .8-1.7 1.8-1.7z"/>
                        <path d="M6.1 7.3c1 0 1.8.8 1.8 1.7 0 1-.8 1.8-1.8 1.8S4.3 10 4.3 9s.8-1.7 1.8-1.7z"/>
                        <path d="M19.4 12.8h1.3c1.7 0 3 1.4 3 3.1s-1.3 3-3 3h-1.3M5.6 12.8H4.3c-1.7 0-3 1.4-3 3.1s1.3 3 3 3h1.3"/>
                    </svg>
                </div>
                <div>
                    <h3 class="content-card-title mb-0" style="font-size: 1rem;">Senarai Ahli Jawatankuasa Pembuka</h3>
                    <p class="text-muted mb-0" style="font-size: 0.78rem;">Diisi oleh Petender</p>
                </div>
            </div>
        </div>

        <div class="content-card-body p-4">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <label for="status_dummy" class="form-label small fw-bold text-secondary text-uppercase mb-1">Status</label>
                    <select id="status_dummy" name="status_dummy" class="form-select form-select-sm">
                        <option value="">Sila Pilih</option>
                        <option value="">Menunggu Penyerahan Pembentukan Jawatankuasa</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="untuk_kelulusan_pembuka" name="untuk_kelulusan_pembuka">
                        <label class="form-check-label small fw-bold text-secondary" for="untuk_kelulusan_pembuka">
                            Untuk Kelulusan
                        </label>
                    </div>
                </div>
            </div>
            <!-- Table -->
            <div class="table-responsive">
                <table id="tbl-jkpembuka" class="table table-modern align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th class="text-center py-3">No. Kad Pengenalan</th>
                            <th class="text-center py-3">Nama</th>
                            <th class="text-center py-3">Jawatan</th>
                            <th class="text-center py-3">E-mel</th>
                            <th class="text-center py-3">Gred</th>
                            <th class="text-center py-3">P&P</th>
                            <th class="text-center py-3">Peranan</th>
                            <th class="text-center py-3" style="width:50px;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody id="tbl-jkpembuka-body">
                        <!-- initial row rendered by JS below -->
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <!-- ACTION BUTTONS -->
    <div class="d-flex justify-content-end align-items-center mb-4 flex-wrap gap-2">

        <div class="d-flex gap-2">
            <button type="button" class="btn-form btn-form-success">
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
<script type="text/javascript">

    document.addEventListener('DOMContentLoaded', function () {

        const dummyJKPembukaData = [
            {
                ic: '890101-14-5555',
                nama: 'Ahmad bin Abu',
                jawatan: 'Jurutera',
                emel: 'ahmad@example.com',
                gred: 'J41',
                pp: 'Ya',
                peranan: 'Pengerusi'
            },
            {
                ic: '920304-10-6666',
                nama: 'Siti binti Ali',
                jawatan: 'Penolong Pengarah',
                emel: 'siti@example.com',
                gred: 'M44',
                pp: 'Ya',
                peranan: 'Setiausaha'
            },
            {
                ic: '850505-01-7777',
                nama: 'Mutu a/l Ramasamy',
                jawatan: 'Pegawai Tadbir',
                emel: 'mutu@example.com',
                gred: 'N41',
                pp: 'Tidak',
                peranan: 'Ahli'
            }
        ];

        const $jkPembukaBody = $('#tbl-jkpembuka-body');
        if ($jkPembukaBody.length) {
            let rowsHtml = '';
            dummyJKPembukaData.forEach(item => {
                rowsHtml += `
                    <tr>
                        <td class="text-center">${item.ic}</td>
                        <td>${item.nama}</td>
                        <td>${item.jawatan}</td>
                        <td>${item.emel}</td>
                        <td class="text-center">${item.gred}</td>
                        <td class="text-center">${item.pp}</td>
                        <td class="text-center">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill">
                                ${item.peranan}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="form-check d-flex justify-content-center mb-0">
                                <input class="form-check-input" type="checkbox" name="pilih_ahli[]" value="${item.ic}">
                            </div>
                        </td>
                    </tr>
                `;
            });
            $jkPembukaBody.html(rowsHtml);
        }

        document.querySelectorAll('.nested-tabs').forEach(wrapper => {

            wrapper.addEventListener('click', function (e) {

                const btn = e.target.closest('.nested-tab-btn');
                if (!btn) return;

                const tab = btn.dataset.tab;
                const contentWrapper = wrapper.nextElementSibling;

                // remove active
                wrapper.querySelectorAll('.nested-tab-btn')
                    .forEach(b => b.classList.remove('active'));

                btn.classList.add('active');

                // toggle content
                contentWrapper.querySelectorAll('.tab-content')
                    .forEach(div => {
                        div.style.display =
                            (div.dataset.tab === tab) ? 'block' : 'none';
                    });
            });
        });

    });

    

</script>

  
@endsection

