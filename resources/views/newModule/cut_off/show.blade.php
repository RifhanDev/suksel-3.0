@extends('layouts.v3.master')

@section('content')

<style>
    .cutoff-tender-header {
        background: var(--sg-bg);
        border: 1px solid var(--topbar-border, #e5e7eb);
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }

    .cutoff-tender-header .badge-status {
        background: #f59e0b;
        color: #fff;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-weight: 600;
    }

    .section-title-cutoff {
        font-size: 1rem;
        font-weight: 700;
        color: var(--sg-red-dark);
        margin-bottom: 0.75rem;
        padding-bottom: 0.35rem;
        border-bottom: 2px solid var(--topbar-border, #e5e7eb);
    }

    .cutoff-table thead {
        background: #1F3A8A !important;
        color: white !important;
    }

    .cutoff-table thead th {
        background: transparent !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: white !important;
        font-weight: 600;
        padding: 12px 10px;
        font-size: 0.875rem;
        text-align: center;
        vertical-align: middle;
    }

    .cutoff-table td {
        padding: 10px 12px;
        border: 1px solid var(--topbar-border, #e5e7eb);
        font-size: 0.875rem;
        vertical-align: middle;
    }

    .cutoff-table tbody tr:nth-child(even) {
        background: #f9fafb;
    }

    .cutoff-table tbody tr:hover {
        background: #f1f5f9;
    }

    .cutoff-table .text-freak {
        color: #dc2626;
        font-weight: 600;
    }

    .cutoff-actions .btn-simpan {
        background: #0d9488;
        color: #fff;
        border: none;
    }

    .cutoff-actions .btn-simpan:hover {
        background: #0f766e;
        color: #fff;
    }

    .cutoff-actions .btn-hantar {
        background: #7c3aed;
        color: #fff;
        border: none;
    }

    .cutoff-actions .btn-hantar:hover {
        background: #6d28d9;
        color: #fff;
    }

    .cutoff-actions button:disabled,
    .cutoff-actions a.disabled {
        opacity: 0.45;
        cursor: not-allowed;
        pointer-events: none;
    }

    .dist-chart-wrap {
        max-width: 100%;
        height: 280px;
        position: relative;
    }

    .cutoff-freq-table thead th {
        text-align: center;
        background: #1F3A8A !important;
        color: white !important;
        font-weight: 600;
        padding: 10px 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .cutoff-freq-table td {
        text-align: center;
        padding: 8px 12px;
    }

    .cutoff-freak-box {
        border: 1px solid var(--topbar-border, #e5e7eb) !important;
        background: #f8fafc !important;
        min-height: 80%;
    }
</style>

<div class="card">
    <div class="card-body p-4">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="py-2 mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('cutOff.index') }}" class="text-secondary text-decoration-none">STOS</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cutOff.index') }}" class="text-secondary text-decoration-none">Cut-Off</a></li>
                <li class="breadcrumb-item active" aria-current="page">PENENTUAN HARGA CUT OFF</li>
            </ol>
        </nav>

        <div class="cutoff-tender-header">
            <div class="row g-2 mb-0">
                <div class="col-md-6"><strong>No. Sebut Harga / Tender:</strong> {{ $tender_no }}</div>
                <div class="col-md-6"><strong>Tempoh Sah Laku Tawaran (Hari):</strong> 90</div>
                <div class="col-md-6"><strong>PTJ:</strong> BAHAGIAN PENTADBIRAN - CAWANGAN KEWANGAN - KEMENTERIAN KEWANGAN</div>
                <div class="col-md-6"><strong>Tajuk Perolehan:</strong> {{ $tajuk ?? '-' }}</div>
                <div class="col-md-6"><strong>STATUS:</strong> Menunggu Pengesahan CutOff</div>
                <div class="col-md-6"><strong>Sah Laku Tawaran Tamat:</strong> 17/01/2022</div>
            </div>
        </div>

        <h4 class="fw-bold mb-4 pb-2 border-bottom" style="color: var(--sg-red-dark);">PENENTUAN HARGA CUT OFF</h4>

        {{-- Section 1 — $bins & $freq dihantar dari CutOffController --}}
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="section-title-cutoff">Tender Prices Distribution Curve</div>
                <div class="dist-chart-wrap border rounded p-3 bg-white shadow-sm">
                    <canvas id="cutoffDistChart" data-labels="{{ implode(',', $bins) }}" data-freq="{{ implode(',', $freq) }}" width="400" height="260"></canvas>
                </div>
            </div>
            <div class="col-lg-4 mt-3 mt-lg-0">
                <div class="table-responsive mt-5">
                    <table class="table table-bordered table-sm mb-0 cutoff-freq-table cutoff-table">
                        <thead>
                            <tr>
                                <th>Bin</th>
                                <th>Frequency</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bins as $i => $label)
                            <tr>
                                <td>{{ $label }}</td>
                                <td>{{ $freq[$i] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row mb-4 g-3">
            <div class="col-lg-6">
                <div class="section-title-cutoff">Anggaran Jabatan (AJ)</div>
                <div class="table-responsive">
                    <table class="table table-bordered cutoff-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 60%;">Item</th>
                                <th class="text-center">Nilai (RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Anggaran Jabatan (AJ)</strong></td>
                                <td class="text-center">{{ $aj ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>PC & Prov. Sums (PCP)</strong></td>
                                <td class="text-center">{{ $pcp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Builder's Work in AJ (Bwa)</strong></td>
                                <td class="text-center">{{ $bwa ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-title-cutoff">Penentuan dan Pengasingan Harga "Freak"</div>
                <div class="cutoff-freak-box border rounded p-3 bg-light h-40">
                    <p class="mb-0 text-secondary text-default">
                        Sesuatu harga tender dianggap 'Freak' dan secara automatik tidak diambil kira sekiranya tahap 'significance'nya (Alfa) kurang daripada 0.01, iaitu Z-score nya melebihi +2.33 atau kurang dari -2.33.
                    </p>
                </div>
            </div>
        </div>

        {{-- Section 4 & 5 --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="section-title-cutoff">Statistical Attribute</div>
                <div class="table-responsive">
                    <table class="table table-bordered cutoff-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 60%;">Attribute</th>
                                <th class="text-center">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>No. of Tender Analysed (Nt)</strong></td>
                                <td class="text-center">{{ $nt ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Mean of BW (mean)</strong></td>
                                <td class="text-center">{{ $mean ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Overall Mean</strong></td>
                                <td class="text-center">{{ $overallMean ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Standard Deviation (SD)</strong></td>
                                <td class="text-center">{{ $sd ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Coefficient of Variation (CV)</strong></td>
                                <td class="text-center">{{ $cv ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-title-cutoff">Penentuan Harga Cutoff</div>
                <div class="table-responsive h-100">
                    <table class="table table-bordered cutoff-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 55%;">Keterangan</th>
                                <th class="text-center">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Bilangan Tender Melebihi 10 iaitu Nt &gt; 10</strong></td>
                                <td class="text-center">{{ $melebihi10 ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Mean - X*Mean or Mean - X*SD (yang mana lebih tinggi)</td>
                                <td class="text-center">{{ $meanFormula ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Cut-off Bagi Builder's Works sahaja (tanpa PCP)</td>
                                <td class="text-center">{{ $cutoffTanpaPcp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Cut-off Bagi Tender (termasuk PCP)</td>
                                <td class="text-center">{{ $cutoffTermasukPcp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Harga CUT-OFF (rounded down) YANG DITETAPKAN</strong></td>
                                <td class="text-center fw-bold text-dark">{{ $hargaCutOffFinal ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Section 6 --}}
        <!-- <div class="section-title-cutoff">Senarai Tender (Z-score &amp; Peratusan)</div> -->
        <div class="table-responsive mb-4">
            <table class="table cutoff-table mb-0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Ruj. TEN.</th>
                        <th>TEND. PRICE (RM)</th>
                        <th>BW of Tender (RM)</th>
                        <th>Z-score</th>
                        <th>%BWAJ</th>
                        <th>%BWAM</th>
                        <th class="text-center" style="width:10%;">
                            <div class="d-inline-flex align-items-center gap-1">
                                Pilih
                                <div class="form-check m-0">
                                    <input type="checkbox" class="form-check-input" id="selectAllPilih"
                                        {{ ($selectionStatus ?? null) === 'submitted' ? 'disabled' : '' }}>
                                </div>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    {{-- $rows dihantar dari CutOffController (data sebenar dari STOS) --}}
                    @php $isSubmitted = ($selectionStatus ?? null) === 'submitted'; @endphp
                    @forelse($rows ?? [] as $idx => $r)
                    <tr>
                        <td class="text-center">{{ $r['no'] }}</td>
                        <td class="text-center">{{ $r['ruj'] }}</td>
                        <td class="text-center">{{ $r['price'] }}</td>
                        <td class="text-center {{ $r['freak'] ? 'text-freak' : '' }}">{{ $r['bw'] }}</td>
                        <td class="text-center">{{ $r['z'] }}</td>
                        <td class="text-center">{{ $r['pct_aj'] }}</td>
                        <td class="text-center {{ $r['freak'] ? 'text-freak' : '' }}">{{ $r['pct_mean'] }}</td>
                        <td class="text-center">
                            <input type="checkbox" class="form-check-input pilih-checkbox" name="pilih[]"
                                value="{{ $r['ruj'] }}"
                                {{ in_array($r['ruj'], $selectedRefs ?? [], true) ? 'checked' : '' }}
                                {{ $isSubmitted ? 'disabled' : '' }}>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Tiada syarikat untuk dianalisis.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mesej maklum balas Simpan/Hantar --}}
        <div id="cutoffFeedback" class="alert d-none mb-3" role="alert"></div>

        {{-- Action buttons --}}
        <div class="d-flex flex-wrap gap-2 justify-content-end cutoff-actions">
            <button type="button" class="btn btn-simpan px-4" id="btnSimpanCutoff">Simpan</button>
            <button type="button" class="btn btn-hantar px-4" id="btnHantarCutoff" disabled>Hantar</button>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function() {
        var el = document.getElementById('cutoffDistChart');
        if (!el) return;
        var labels = el.getAttribute('data-labels').split(',');
        var freqStr = el.getAttribute('data-freq').split(',');
        var freq = freqStr.map(function(n) {
            return parseInt(n, 10);
        });
        // Had paksi-Y dikira secara dinamik ikut data sebenar (bukan tetap 6) —
        // supaya puncak taburan tak terpotong/rata bila kekerapan melebihi 6.
        var maxFreq = Math.max.apply(null, freq.concat([0]));
        var yMax = maxFreq + Math.max(1, Math.ceil(maxFreq * 0.15));
        new Chart(el.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Frequency',
                    data: freq,
                    borderColor: 'rgb(31, 58, 138)',
                    backgroundColor: 'rgba(31, 58, 138, 0.12)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.25,
                    pointBackgroundColor: 'rgb(31, 58, 138)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: yMax,
                        ticks: {
                            precision: 0
                        },
                        title: {
                            display: true,
                            text: 'Frequency'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Price Classification: % of Mean'
                        }
                    }
                }
            }
        });
    })();

    (function() {
        var selectAll = document.getElementById('selectAllPilih');
        if (!selectAll) return;
        var checkboxes = document.querySelectorAll('.pilih-checkbox');

        selectAll.addEventListener('change', function() {
            var checked = selectAll.checked;
            checkboxes.forEach(function(cb) {
                if (!cb.disabled) cb.checked = checked;
            });
        });
    })();

    (function() {
        var cutoffConfig = {
            tenderUuid: @json($tenderUuid ?? ''),
            csrfToken: @json(csrf_token()),
            simpanUrl: @json(route('cutOff.simpan')),
            hantarUrl: @json(route('cutOff.hantar')),
            indexUrl: @json(route('cutOff.index')),
            requireAll: @json($requireAll ?? false),
            minSelect: @json($minSelect ?? 10),
            totalCount: @json($totalCount ?? 0),
            selectionStatus: @json($selectionStatus ?? null) // null | 'draft' | 'submitted'
        };

        var btnSimpan = document.getElementById('btnSimpanCutoff');
        var btnHantar = document.getElementById('btnHantarCutoff');
        var feedbackEl = document.getElementById('cutoffFeedback');

        function getCheckboxes() {
            return document.querySelectorAll('.pilih-checkbox');
        }

        function getSelectedRefs() {
            var refs = [];
            getCheckboxes().forEach(function(cb) {
                if (cb.checked) refs.push(cb.value);
            });
            return refs;
        }

        function showFeedback(message, type) {
            if (!feedbackEl) return;
            feedbackEl.textContent = message;
            feedbackEl.className = 'alert mb-3 alert-' + (type === 'error' ? 'danger' : 'success');
        }

        function clearFeedback() {
            if (!feedbackEl) return;
            feedbackEl.className = 'alert d-none mb-3';
            feedbackEl.textContent = '';
        }

        // Padan peraturan yang sama di STOS Api\CutOffController::simpan() — pengesahan
        // di sini hanya untuk maklum balas segera; STOS tetap pihak berkuasa muktamad.
        function validateSelection(selectedCount) {
            if (cutoffConfig.requireAll) {
                if (selectedCount < cutoffConfig.totalCount) {
                    return 'Sila tandakan SEMUA baris (' + cutoffConfig.totalCount + ') — jumlah tender terhad atau semua tender adalah \'FREAK\'.';
                }
                return null;
            }
            if (selectedCount < cutoffConfig.minSelect) {
                return 'Sila tandakan sekurang-kurangnya ' + cutoffConfig.minSelect + ' baris (ditandakan: ' + selectedCount + ').';
            }
            return null;
        }

        function lockPageAsSubmitted() {
            getCheckboxes().forEach(function(cb) { cb.disabled = true; });
            var selectAll = document.getElementById('selectAllPilih');
            if (selectAll) selectAll.disabled = true;
            if (btnSimpan) btnSimpan.disabled = true;
            if (btnHantar) btnHantar.disabled = true;
        }

        function unlockAfterSimpan() {
            if (btnHantar) btnHantar.disabled = false;
            cutoffConfig.selectionStatus = 'draft';
        }

        // Inisialisasi status butang ikut state sedia ada (bila page dimuat semula).
        if (cutoffConfig.selectionStatus === 'submitted') {
            lockPageAsSubmitted();
        } else if (cutoffConfig.selectionStatus === 'draft') {
            unlockAfterSimpan();
        }

        if (btnSimpan) {
            btnSimpan.addEventListener('click', function() {
                clearFeedback();
                var refs = getSelectedRefs();
                var error = validateSelection(refs.length);
                if (error) {
                    showFeedback(error, 'error');
                    return;
                }

                btnSimpan.disabled = true;
                btnSimpan.textContent = 'Menyimpan...';

                fetch(cutoffConfig.simpanUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': cutoffConfig.csrfToken
                        },
                        body: JSON.stringify({
                            tender: cutoffConfig.tenderUuid,
                            selected_refs: refs
                        })
                    })
                    .then(function(res) {
                        return res.json().then(function(body) {
                            return { ok: res.ok, body: body };
                        });
                    })
                    .then(function(result) {
                        btnSimpan.disabled = false;
                        btnSimpan.textContent = 'Simpan';

                        if (!result.ok) {
                            showFeedback(result.body.message || 'Gagal menyimpan.', 'error');
                            return;
                        }

                        showFeedback(result.body.message || 'Perincian cut-off telah disimpan.', 'success');
                        unlockAfterSimpan();
                    })
                    .catch(function() {
                        btnSimpan.disabled = false;
                        btnSimpan.textContent = 'Simpan';
                        showFeedback('Ralat rangkaian. Sila cuba lagi.', 'error');
                    });
            });
        }

        if (btnHantar) {
            btnHantar.addEventListener('click', function() {
                if (btnHantar.disabled) return;
                if (!confirm('Sahkan Hantar cut-off? Tindakan ini muktamad dan tidak boleh diubah selepas ini.')) {
                    return;
                }

                clearFeedback();
                btnHantar.disabled = true;
                btnHantar.textContent = 'Menghantar...';

                fetch(cutoffConfig.hantarUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': cutoffConfig.csrfToken
                        },
                        body: JSON.stringify({ tender: cutoffConfig.tenderUuid })
                    })
                    .then(function(res) {
                        return res.json().then(function(body) {
                            return { ok: res.ok, body: body };
                        });
                    })
                    .then(function(result) {
                        btnHantar.textContent = 'Hantar';

                        if (!result.ok) {
                            btnHantar.disabled = false;
                            showFeedback(result.body.message || 'Gagal menghantar.', 'error');
                            return;
                        }

                        cutoffConfig.selectionStatus = 'submitted';
                        lockPageAsSubmitted();

                        // Redirect ke senarai cut-off selepas modal ditutup.
                        if (typeof showBerjayaModal === 'function') {
                            showBerjayaModal({
                                message: 'Maklumat telah berjaya dihantar.',
                                onClose: function() {
                                    window.location.href = cutoffConfig.indexUrl;
                                }
                            });
                        }
                    })
                    .catch(function() {
                        btnHantar.disabled = false;
                        btnHantar.textContent = 'Hantar';
                        showFeedback('Ralat rangkaian. Sila cuba lagi.', 'error');
                    });
            });
        }

    })();
</script>

@endsection