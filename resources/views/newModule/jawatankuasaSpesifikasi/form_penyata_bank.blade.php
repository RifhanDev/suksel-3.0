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
                Maklumat Penyata Bank
            </h3>
        </div>
        <div class="card-body p-2">
            <div class="p-4">
                <div class="row mx-2">
                    <div class="small lh-sm mt-2">
                        <p class="card-title-desc text-info fst-italic">
                            <i>Sila pilih bulan pertama penyata bank yang perlu dikemukakan oleh petender</i>
                        </p>
                    </div>
                </div>
                <div class="row mb-3 mx-3">
                    @php
                        $penyataBankDari = now()->subMonths(2);
                        $penyataBankHingga = $penyataBankDari->copy()->addMonths(2);
                    @endphp
                    <div class="col-sm-6 form-group my-2">
                        <div class="row">
                            <label class="col-sm-4 control-label">Dari (Bulan)</label>
                            <div class="col-sm-8 text-primary">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <select name="" id="penyata_dari_bulan" class="form-control">
                                            <option value="">Pilih Bulan</option>
                                            @for ($mf = 1; $mf <= 12; $mf++)
                                                <option value="{{ $mf }}" @selected((int) $mf === (int) $penyataBankDari->month)>{{ $mf }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <select name="" id="penyata_dari_tahun" class="form-control">
                                            <option value="">Pilih Tahun</option>
                                            @foreach (range(now()->year - 10, now()->year) as $yf)
                                                <option value="{{ $yf }}" @selected((int) $yf === (int) $penyataBankDari->year)>{{ $yf }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 form-group my-2">
                        <div class="row">
                            <label class="col-sm-4 control-label">Hingga (Bulan)</label>
                            <div class="col-sm-8 text-primary">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-sm bg-light" id="penyata_hingga_bulan_display" name="" value="{{ $penyataBankHingga->month }}" readonly tabindex="-1" title="Auto: 2 bulan selepas Dari">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-sm bg-light" id="penyata_hingga_tahun_display" name="" value="{{ $penyataBankHingga->year }}" readonly tabindex="-1" title="Auto: 2 bulan selepas Dari">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mx-2">
                    <div class="small lh-sm mt-2">
                        <p class="card-title-desc text-info fst-italic">
                            <i>Perlu diisi oleh petender</i>
                        </p>
                    </div>
                </div>
                <div class="row mb-3 mx-3" id="penyata_bank_bulan_rows_wrapper">
                    @php
                        $penyataBankBulanList = [];
                        $curBulan = $penyataBankDari->copy()->startOfMonth();
                        $akhirBulan = $penyataBankHingga->copy()->startOfMonth();
                        while ($curBulan->lte($akhirBulan)) {
                            $penyataBankBulanList[] = $curBulan->copy();
                            $curBulan->addMonth();
                        }
                    @endphp
                    @foreach ($penyataBankBulanList as $pbRow)
                    <div class="row">
                        <div class="col-sm-6 form-group my-2 penyata-bank-bulan-item" data-ym="{{ $pbRow->format('Y-m') }}">
                            <div class="row">
                                <label class="col-sm-4 control-label">Penyata Bank Bulan {{ $pbRow->month }} - {{ $pbRow->year }} (RM)</label>
                                <div class="col-sm-8 text-primary">
                                    <input type="text" class="form-control form-control-sm penyata-bank-bulan-input" name="" value=""
                                    data-bulan="{{ $pbRow->month }}" data-tahun="{{ $pbRow->year }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <hr>
                <div class="row mx-2">
                    <div class="col-sm-6 form-group my-2">
                        <div class="row">
                            <label class="col-sm-4 control-label">Jumlah Keseluruhan Penyata Bank (RM)</label>
                            <div class="col-sm-8 text-primary">
                                <input type="text" class="form-control form-control-sm bg-light" id="penyata_bank_jumlah_keseluruhan" name="" value="" readonly tabindex="-1">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mx-2">
                    <div class="col-sm-6 form-group my-2">
                        <div class="row">
                            <label class="col-sm-4 control-label">Purata Penyata Bank (RM)</label>
                            <div class="col-sm-8 text-primary">
                                <input type="text" class="form-control form-control-sm bg-light" id="penyata_bank_purata" name="" value="" readonly tabindex="-1">
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
                Purata Penyata Bank
            </h3>
        </div>
        <div class="card-body p-2">
            <div class="p-4">
            <div class="row mb-2 mx-2">
                    <div class="col-12 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-sm btn-success add_avg_penyata_bank_btn">
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
                        <table id="dt_avg_penyata_bank" data-path="" class=" table table-modern w-100 mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">Bil</th>
                                    <th class="text-center">Dari</th>
                                    <th class="text-center">Hingga</th>
                                    <th class="text-center">Skema</th>
                                    <th></th>
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
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger btn-circle text-white">
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
                                <tr class="add_avg_penyata_bank_row d-none">
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
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger btn-circle text-white">
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
            </div>
        </div>
    </div>
    <div class="stats-card mb-4">
        <div class="stats-card-header">
            <h3 class="stats-card-title">
                <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-2" style="width: 36px; height: 36px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path><line x1="8" y1="6" x2="16" y2="6"></line><line x1="8" y1="10" x2="16" y2="10"></line></svg>
                </div>
                Modal Cair / Laporan Audit
            </h3>
        </div>
        <div class="card-body p-2">
            <div class="p-4">
            </div>
        </div>
    </div>
    <div class="stats-card mb-4">
        <div class="stats-card-header">
            <h3 class="stats-card-title">
                <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-2" style="width: 36px; height: 36px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path><line x1="8" y1="6" x2="16" y2="6"></line><line x1="8" y1="10" x2="16" y2="10"></line></svg>
                </div>
                Kemudahan Kredit
            </h3>
        </div>
        <div class="card-body p-2">
            <div class="p-4">
            </div>
        </div>
    </div>
    <div class="row mb-4 mx-2">
        <div class="col-12 d-flex justify-content-between">
            <div>
                <a href="{{ route('senaraiKewangan') }}" type="button" class="btn btn-sm btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Kembali
                </a>
            </div>
            <div class="d-flex gap-2">
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

    function penyataBankParseRm(raw) {
        if (raw == null || String(raw).trim() === '') return 0;
        var s = String(raw).replace(/,/g, '').replace(/^\s*RM\s*/i, '').replace(/\s+/g, '').trim();
        var n = parseFloat(s);
        return isNaN(n) ? 0 : n;
    }

    function penyataBankFormatRm2(n) {
        if (isNaN(n) || !isFinite(n)) return '';
        return n.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function updatePenyataBankTotals() {
        var wrap = document.getElementById('penyata_bank_bulan_rows_wrapper');
        var totalEl = document.getElementById('penyata_bank_jumlah_keseluruhan');
        var avgEl = document.getElementById('penyata_bank_purata');
        if (!wrap || !totalEl || !avgEl) return;
        var inputs = wrap.querySelectorAll('.penyata-bank-bulan-input');
        var sum = 0;
        var n = inputs.length;
        inputs.forEach(function (inp) {
            sum += penyataBankParseRm(inp.value);
        });
        var avg = n > 0 ? sum / n : 0;
        totalEl.value = penyataBankFormatRm2(sum);
        avgEl.value = penyataBankFormatRm2(avg);
    }

    document.addEventListener('DOMContentLoaded', function () {

        (function syncHinggaDuaBulanSelepasDari() {
            var db = document.getElementById('penyata_dari_bulan');
            var dt = document.getElementById('penyata_dari_tahun');
            var hb = document.getElementById('penyata_hingga_bulan_display');
            var hyEl = document.getElementById('penyata_hingga_tahun_display');
            var wrap = document.getElementById('penyata_bank_bulan_rows_wrapper');
            if (!db || !dt || !hb || !hyEl) return;

            function bulanSelepas(dy, dm, n) {
                var d = new Date(dy, dm - 1 + n, 1);
                return { y: d.getFullYear(), m: d.getMonth() + 1 };
            }

            function rebuildPenyataBankBulanRows() {
                var dm = parseInt(db.value, 10);
                var dy = parseInt(dt.value, 10);
                var hm = parseInt(hb.value, 10);
                var hy = parseInt(hyEl.value, 10);
                if (!wrap) return;
                while (wrap.firstChild) wrap.removeChild(wrap.firstChild);
                if (!dm || !dy || !hm || !hy || dm < 1 || dm > 12 || hm < 1 || hm > 12) {
                    updatePenyataBankTotals();
                    return;
                }
                var start = new Date(dy, dm - 1, 1);
                var end = new Date(hy, hm - 1, 1);
                if (start > end) {
                    updatePenyataBankTotals();
                    return;
                }
                var cur = new Date(start.getTime());
                while (cur <= end) {
                    var m = cur.getMonth() + 1;
                    var y = cur.getFullYear();
                    var ym = y + '-' + String(m).padStart(2, '0');
                    var outerRow = document.createElement('div');
                    outerRow.className = 'row';
                    var col = document.createElement('div');
                    col.className = 'col-sm-6 form-group my-2 penyata-bank-bulan-item';
                    col.setAttribute('data-ym', ym);
                    var innerRow = document.createElement('div');
                    innerRow.className = 'row';
                    var label = document.createElement('label');
                    label.className = 'col-sm-4 control-label';
                    label.textContent = 'Penyata Bank Bulan ' + m + ' - ' + y + ' (RM)';
                    var col8 = document.createElement('div');
                    col8.className = 'col-sm-8 text-primary';
                    var input = document.createElement('input');
                    input.type = 'text';
                    input.className = 'form-control form-control-sm penyata-bank-bulan-input';
                    input.setAttribute('name', '');
                    input.setAttribute('value', '');
                    input.setAttribute('data-bulan', String(m));
                    input.setAttribute('data-tahun', String(y));
                    col8.appendChild(input);
                    innerRow.appendChild(label);
                    innerRow.appendChild(col8);
                    col.appendChild(innerRow);
                    outerRow.appendChild(col);
                    wrap.appendChild(outerRow);
                    cur.setMonth(cur.getMonth() + 1);
                }
                updatePenyataBankTotals();
            }

            function update() {
                var dm = parseInt(db.value, 10);
                var dy = parseInt(dt.value, 10);
                if (!dm || !dy || dm < 1 || dm > 12) {
                    hb.value = '';
                    hyEl.value = '';
                    rebuildPenyataBankBulanRows();
                    return;
                }
                var h = bulanSelepas(dy, dm, 2);
                hb.value = String(h.m);
                hyEl.value = String(h.y);
                rebuildPenyataBankBulanRows();
            }

            db.addEventListener('change', update);
            dt.addEventListener('change', update);
            update();
        })();

        var wrapPenyataBulan = document.getElementById('penyata_bank_bulan_rows_wrapper');
        if (wrapPenyataBulan) {
            wrapPenyataBulan.addEventListener('input', updatePenyataBankTotals);
            wrapPenyataBulan.addEventListener('change', updatePenyataBankTotals);
        }
        updatePenyataBankTotals();

        function initTables(param1, param2, param3) {
            const table = document.querySelector(param1);
            if (!table) return;

            const tbody = table.querySelector('tbody');
            const addItemBtn = document.querySelector(param2);
            const templateSelector = 'tr.' + param3;

            function updateRowNumbers() {
                const rows = tbody.querySelectorAll('tr:not(.' + param3 + ')');
                rows.forEach((row, index) => {
                    row.children[0].textContent = index + 1;
                });
            }

            if (addItemBtn) {
                addItemBtn.addEventListener('click', function () {
                    const template = tbody.querySelector(templateSelector);
                    if (!template) return;
                    const clone = template.cloneNode(true);
                    clone.classList.remove('d-none', param3);
                    clone.querySelectorAll('input').forEach(function (input) { input.value = ''; });
                    tbody.appendChild(clone);
                    updateRowNumbers();
                });
            }

            tbody.addEventListener('click', function (e) {
                const deleteBtn = e.target.closest('.btn-danger');
                if (!deleteBtn) return;
                const row = deleteBtn.closest('tr');
                const rows = tbody.querySelectorAll('tr:not(.' + param3 + ')');
                if (rows.length <= 1) return;
                row.remove();
                updateRowNumbers();
            });
        }

        initTables('#dt_avg_penyata_bank', '.add_avg_penyata_bank_btn', 'add_avg_penyata_bank_row');
    });

</script>

  
@endsection

