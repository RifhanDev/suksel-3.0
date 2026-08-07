{{-- Step 3: Pematuhan Spesifikasi Kewangan (Kewangan + Rumusan) --}}
<ul class="nav nav-pills custom-tab-size mb-3" role="tablist">
    <li class="nav-item waves-effect waves-light">
        <a class="nav-link active" data-bs-toggle="tab" href="#kewangan-3" role="tab" aria-selected="true">Kewangan</a>
    </li>
    <li class="nav-item waves-effect waves-light">
        <a class="nav-link" data-bs-toggle="tab" href="#rumusan-3" role="tab" aria-selected="false">Rumusan</a>
    </li>
</ul>

<div class="tab-content mt-4">
    <div class="tab-pane fade show active step3-kewangan-pane" id="kewangan-3" role="tabpanel" aria-labelledby="kewangan-3-tab">
        <div class="d-flex align-items-center mb-4">
            <div class="bg-primary-subtle p-2 rounded-2 me-3">
                <i class="bi bi-file-earmark-text text-primary fs-4"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0">Pematuhan Spesifikasi Kewangan</h5>
                <p class="text-secondary small mb-0">Papar maklumat penilaian cadangan kewangan.</p>
            </div>
        </div>
        <div class="rounded-2 px-3 py-2 d-inline-flex align-items-center gap-2 mb-3" style="background:#eff6ff; border:1px solid #bfdbfe; font-size:0.78rem; color:#1e40af; animation: alertPopBuzz 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) both;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            <div>
                <span class="small fw-medium text-info-emphasis"><strong>Informasi:</strong></span>
                <p class="mb-0 small">Klik butang <strong>Papar</strong> untuk meneruskan penilaian.</p>
            </div>
        </div>

        <div class="table-responsive mb-3 rounded-3 shadow-sm border bg-white">
            <table class="table table-hover align-middle mb-0 w-100 step3-kewangan-table">
                <thead>
                    <tr class="bg-light">
                        <th class="py-3 px-3 text-start fw-bold text-secondary text-uppercase" style="width: 42%; font-size: 0.75rem; letter-spacing: 0.5px;">
                            <i class="bi bi-file-earmark-text text-danger me-1"></i>Tajuk / Dokumen
                        </th>
                        <th class="py-3 px-3 text-center fw-bold text-secondary text-uppercase" style="width: 22%; font-size: 0.75rem; letter-spacing: 0.5px;">
                            <i class="bi bi-gear text-danger me-1"></i>Mekanisma
                        </th>
                        <th class="py-3 px-3 text-center fw-bold text-secondary text-uppercase" style="width: 20%; font-size: 0.75rem; letter-spacing: 0.5px;">
                            <i class="bi bi-shield-check text-danger me-1"></i>Status Penilaian
                        </th>
                        <th class="py-3 px-3 text-center fw-bold text-secondary text-uppercase" style="width: 16%; font-size: 0.75rem; letter-spacing: 0.5px;">
                            <i class="bi bi-sliders text-danger me-1"></i>Tindakan
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kewanganItems as $index => $item)
                        @php
                            $itemTitle = $item['title'] ?? $item['nama'] ?? 'Item Senarai Semak Kewangan';
                            $itemMekanisma = $item['tindakan'] ?? $item['mekanisma'] ?? 'Spesifikasi';
                            $itemUuid = $item['uuid'] ?? '';

                            $itemPayload = $semakPayload[$itemUuid] ?? null;
                            $itemVendors = $itemPayload['vendors'] ?? [];

                            $failedStep1VendorIds = collect($semakPayload ?? [])
                                ->reject(function ($payload, $uuid) use ($penyataBankItems) {
                                    return in_array($uuid, collect($penyataBankItems ?? [])->pluck('uuid')->all(), true);
                                })
                                ->flatMap(function ($payload) {
                                    return collect($payload['vendors'] ?? [])
                                        ->filter(fn ($v) => $v['status_pematuhan'] === 'tidak_mematuhi' || $v['status_pematuhan'] === 0 || $v['status_pematuhan'] === '0')
                                        ->pluck('vendor_id');
                                })
                                ->unique()
                                ->toArray();

                            $eligibleVendors = collect($itemVendors)->reject(function ($v) use ($failedStep1VendorIds) {
                                return in_array((int) $v['vendor_id'], $failedStep1VendorIds, true);
                            });

                            $totalEligible = $eligibleVendors->count();

                            $reviewedCount = $eligibleVendors->filter(function($v) {
                                return $v['status_pematuhan'] !== null && $v['status_pematuhan'] !== '';
                            })->count();

                            $isItemSelesai = ($totalEligible > 0 && $reviewedCount === $totalEligible);
                        @endphp
                        <tr data-item-uuid="{{ $itemUuid }}">
                            <td class="px-3 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-success bg-opacity-10 p-2 rounded-2 text-primary d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="bi bi-file-earmark-text fs-6 text-success"></i>
                                    </div>
                                    <span class="fw-semibold text-dark">{{ $itemTitle }}</span>
                                </div>
                            </td>
                            <td class="text-center px-3">
                                <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-2 font-monospace fw-medium">
                                    {{ $itemMekanisma }}
                                </span>
                            </td>
                            <td class="status-penilaian text-center px-3">
                                @if($isItemSelesai)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-2.5 py-1.5 rounded-pill">
                                        <i class="bi bi-check-circle me-1"></i>Selesai
                                    </span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning border-opacity-20 px-2.5 py-1.5 rounded-pill">
                                        <i class="bi bi-clock me-1"></i>Menunggu Penilaian
                                    </span>
                                @endif
                            </td>
                            <td class="text-center px-3">
                                <button type="button" class="btn btn-sm btn-success btn-papar-semakan-kewangan px-3 py-1.5 d-inline-flex align-items-center gap-1"
                                    data-bs-toggle="modal" data-bs-target="#modalSemakanKetepatanDokumenKewangan"
                                    data-dokumen="{{ $itemTitle }}"
                                    data-uuid="{{ $itemUuid }}">
                                    <i class="bi bi-pencil-square"></i>
                                    <span>Menilai</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-1 fs-5"></i>Tiada item spesifikasi kewangan dijumpai bagi tender ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="row mb-3 mt-2">
            <div class="col-12 d-flex justify-content-between flex-wrap gap-2">
                <button type="button" class="btn btn-primary btn-sebelumnya">Sebelumnya</button>
                <button type="button" class="btn btn-primary btn-seterusnya">Seterusnya</button>
            </div>
        </div>
    </div>

    <div class="tab-pane fade step3-rumusan-pane" id="rumusan-3" role="tabpanel" aria-labelledby="rumusan-3-tab">
        <div class="px-0">
            <div class="row mb-2 align-items-center g-2">
                <label class="col-sm-auto col-form-label">Paparan mengikut</label>
                <div class="col-sm-3 col-md-2">
                    <select class="form-select form-select-sm" aria-label="Paparan mengikut">
                        <option selected>Item</option>
                    </select>
                </div>
            </div>

            <div class="step3-rumusan-strip mt-2">SENARAI ITEM</div>
            <div class="table-responsive">
                <table class="table table-bordered mb-3 step3-rumusan-table">
                    <thead class="text-white text-center">
                        <tr>
                            <th>Item</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">Perkhidmatan Penilaian Forensik Keatas Sistem XXXX.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="step3-rumusan-strip">SENARAI PEMBEKAL MELEPASI PENILAIAN KEWANGAN</div>
            <div class="table-responsive">
                <table class="table table-bordered mb-3 step3-rumusan-table">
                    <thead class="text-white text-center">
                        <tr>
                            <th>Kedudukan</th>
                            <th>Bil</th>
                            <th>Jumlah Skor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                            <td class="text-center">1/2</td>
                            <td class="text-center">100</td>
                        </tr>
                        <tr>
                            <td class="text-center">1</td>
                            <td class="text-center">2/2</td>
                            <td class="text-center">100</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row mb-3 align-items-center g-2">
                <div class="col-auto ps-0">
                    <label class="form-label mb-0">Penetapan Penanda Aras<br>Tahap Lulus (%)</label>
                </div>
                <div class="col-sm-2 col-md-1">
                    <input type="text" class="form-control form-control-sm text-center" value="51" readonly aria-label="Penanda aras tahap lulus">
                </div>
                <div class="col-auto ms-md-4">
                    <label class="form-label mb-0">Bilangan Pembekal</label>
                </div>
                <div class="col-sm-2 col-md-1">
                    <input type="text" class="form-control form-control-sm text-center" value="2" readonly aria-label="Bilangan pembekal melepasi step 3">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmLayakStep3">
                        <label class="form-check-label" for="confirmLayakStep3">
                            Saya mengesahkan petender diatas layak untuk disyorkan kepada Lembaga.
                        </label>
                    </div>
                </div>
            </div>

            <div class="step3-rumusan-strip">SENARAI PEMBEKAL TIDAK MELEPASI PENILAIAN KEWANGAN</div>
            <div class="table-responsive">
                <table class="table table-bordered mb-3 step3-rumusan-table">
                    <thead class="text-white text-center">
                        <tr>
                            <th>Kod Pembekal</th>
                            <th>Jumlah Skor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center" colspan="2">Tiada rekod dijumpai</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row my-3 mb-4 align-items-center g-2">
                <div class="col-sm-auto">Bilangan Pembekal</div>
                <div class="col-sm-2 col-md-1">
                    <input type="text" class="form-control form-control-sm text-center" value="0" readonly aria-label="Bilangan pembekal tidak melepasi step 3">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 d-flex justify-content-between flex-wrap gap-2">
                    <button type="button" class="btn btn-primary btn-sebelumnya">Sebelumnya</button>
                    <button type="button" class="btn btn-primary btn-seterusnya">Seterusnya</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPaparCadanganKewanganStep3" tabindex="-1" aria-labelledby="modalPaparCadanganKewanganStep3Label" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content step3-papar-modal">
            <div class="modal-body p-4">
                <div class="step3-modal-section-title mb-3">PENILAIAN CADANGAN KEWANGAN</div>
                <p class="mb-4">
                    <strong>Tajuk / Dokumen :</strong>
                    <span id="step3ModalDokumenTitle">Perkhidmatan Penilaian Forensik Keatas Sistem XXXX.</span>
                </p>

                <div class="step3-modal-section-title mb-2">SENARAI PEMBEKAL</div>
                <p class="step3-modal-hint mb-2">Pemohon semua vendor semak lengkap dahulu dan butang simpan bertukar kepada lulus</p>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle mb-0 step3-modal-table">
                        <thead class="text-white text-center">
                            <tr>
                                <th>Bil</th>
                                <th>Status Bumiputra</th>
                                <th>Harga Tawaran (RM)</th>
                                <th>Jumlah Skor</th>
                                <th>Perbezaan Harga (RM)</th>
                                <th>Perbezaan (%)</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1/2</td>
                                <td>Bukan</td>
                                <td>330,500.00</td>
                                <td class="text-center">50/60</td>
                                <td>5,000.00</td>
                                <td class="text-center"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-papar-semakan-kewangan btn-step3-papar-vendor"
                                        data-vendor-kod="1/2"
                                        data-vendor-status="Bukan"
                                        data-harga="330,500.00"
                                        data-skor-automatik="50/60"
                                        data-perbezaan-harga="5,000.00"
                                        data-perbezaan-peratus="">
                                        Papar
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">2/2</td>
                                <td>Ya</td>
                                <td>365,500.00</td>
                                <td class="text-center">60/60</td>
                                <td>30,000.00</td>
                                <td class="text-center">9.09</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-papar-semakan-kewangan btn-step3-papar-vendor"
                                        data-vendor-kod="2/2"
                                        data-vendor-status="Ya"
                                        data-harga="365,500.00"
                                        data-skor-automatik="60/60"
                                        data-perbezaan-harga="30,000.00"
                                        data-perbezaan-peratus="9.09">
                                        Papar
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="step3-modal-section-title mb-2">SKEMA SKOR</div>
                <div class="row g-2 align-items-center mb-4">
                    <label class="col-sm-auto col-form-label fw-semibold">Anggaran Jabatan Sebenar (RM)</label>
                    <div class="col-sm-3 col-md-2">
                        <input type="text" class="form-control text-center" value="335,500.00" readonly>
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-papar-semakan-kewangan px-4 py-2" data-bs-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPaparVendorCadanganKewanganStep3" tabindex="-1" aria-labelledby="modalPaparVendorCadanganKewanganStep3Label" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content step3-papar-modal">
            <div class="modal-body p-3 p-md-4">
                <div class="step3-modal-section-title mb-3">PENILAIAN CADANGAN KEWANGAN</div>
                <p class="mb-1"><strong>Kod Pembekal:</strong> <span id="step3VendorKod">1/2</span></p>
                <p class="mb-4"><strong>Tajuk /Dokumen:</strong> <span id="step3VendorDokumenTitle">Perkhidmatan Penilaian Forensik Keatas Sistem XXXX.</span></p>

                <div class="step3-modal-section-title mb-2">HARGA TAWARAN</div>
                <p class="step3-modal-hint mb-2">Pastikan semua senarai semak lengkap dahulu dan butang hantar bertukar kepada lulus</p>

                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle mb-0 step3-modal-table-detail">
                        <thead class="text-white text-center">
                            <tr>
                                <th rowspan="2">Item</th>
                                <th rowspan="2">Kekerapan / Kuantiti</th>
                                <th rowspan="2">Unit Ukuran</th>
                                <th rowspan="2">Anggaran Jabatan (RM)</th>
                                <th rowspan="2">Tawaran Harga (RM)</th>
                                <th rowspan="2">Catatan Pembekal</th>
                                <th colspan="2">Skor</th>
                                <th rowspan="2">Catatan Penilai</th>
                            </tr>
                            <tr>
                                <th>Automatik</th>
                                <th>Manual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="step3DetailItem">Perkhidmatan Penilaian Forensik Keatas Sistem XXXX.</td>
                                <td class="text-center">1</td>
                                <td class="text-center">Activity Unit</td>
                                <td>335,500.00</td>
                                <td id="step3DetailHarga">330,500.00</td>
                                <td></td>
                                <td class="text-center" id="step3DetailSkorAuto">50/60</td>
                                <td class="text-center" id="step3DetailSkorManual">-</td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" aria-label="Catatan Penilai">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-center fw-semibold">JUMLAH</td>
                                <td class="fw-semibold">335,500.00</td>
                                <td id="step3DetailJumlahHarga" class="fw-semibold">330,500.00</td>
                                <td></td>
                                <td colspan="2" class="text-center" id="step3DetailJumlahSkor">50/60</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-papar-semakan-kewangan px-4 py-2" data-bs-dismiss="modal">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .step3-kewangan-pane .step3-kewangan-table {
        border-color: #bfc5d1;
    }

    .step3-kewangan-pane .step3-kewangan-table thead th {
        background-color: #324e9b;
        border-color: #3b5398;
        font-weight: 500;
        font-size: 13px;
        padding: 10px 12px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .step3-kewangan-pane .step3-kewangan-table tbody td {
        border-color: #c9ced8;
        font-size: 13px;
        padding: 11px 12px;
        vertical-align: middle;
        background-color: #fff;
    }

    .step3-kewangan-pane .step3-kewangan-table th:nth-child(1),
    .step3-kewangan-pane .step3-kewangan-table td:nth-child(1) {
        width: 63%;
    }

    .step3-kewangan-pane .step3-kewangan-table th:nth-child(2),
    .step3-kewangan-pane .step3-kewangan-table td:nth-child(2) {
        width: 14%;
    }

    .step3-kewangan-pane .step3-kewangan-table th:nth-child(3),
    .step3-kewangan-pane .step3-kewangan-table td:nth-child(3) {
        width: 15%;
    }

    .step3-kewangan-pane .step3-kewangan-table th:nth-child(4),
    .step3-kewangan-pane .step3-kewangan-table td:nth-child(4) {
        width: 8%;
        min-width: 110px;
    }

    .step3-kewangan-pane .btn-papar-semakan-kewangan,
    .step3-papar-modal .btn-papar-semakan-kewangan {
        background-color: #16A34A;
        border-color: #16A34A;
        color: #fff;
        min-width: 110px;
        font-size: 13px;
        line-height: 1.25;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 8px;
    }

    .step3-kewangan-pane .btn-papar-semakan-kewangan:hover,
    .step3-kewangan-pane .btn-papar-semakan-kewangan:focus,
    .step3-papar-modal .btn-papar-semakan-kewangan:hover,
    .step3-papar-modal .btn-papar-semakan-kewangan:focus {
        background-color: #15803D;
        border-color: #15803D;
        color: #fff;
    }

    .step3-rumusan-pane .form-select-sm,
    .step3-rumusan-pane .form-control-sm {
        height: 32px;
        font-size: 12px;
    }

    .step3-rumusan-strip {
        background-color: #d9d9d9;
        border: 1px solid #d2d4da;
        border-bottom: 0;
        color: #222;
        font-size: 12px;
        font-weight: 700;
        padding: 5px 10px;
        text-transform: uppercase;
    }

    .step3-rumusan-table {
        border-color: #a6adba;
    }

    .step3-rumusan-table thead th {
        background-color: #324e9b;
        border-color: #5f6fa4;
        color: #fff;
        font-weight: 500;
        font-size: 12px;
        padding: 6px 10px;
        vertical-align: middle;
    }

    .step3-rumusan-table td {
        border-color: #a6adba;
        font-size: 12px;
        padding: 7px 10px;
        vertical-align: middle;
        background-color: #fff;
    }

    .step3-rumusan-pane .form-check-input {
        width: 12px;
        height: 12px;
        border-radius: 0;
        margin-top: 0.35em;
    }

    .step3-rumusan-pane .form-check-label {
        font-size: 14px;
    }

    .step3-papar-modal .modal-body {
        background: #f6f7f9;
    }

    .step3-modal-section-title {
        background-color: #d9d9d9;
        font-size: 14px;
        font-weight: 700;
        padding: 6px 10px;
        color: #222;
    }

    .step3-modal-hint {
        font-size: 12px;
        color: #2b5cba;
        font-style: italic;
    }

    .step3-modal-table {
        border-color: #9aa3b3;
    }

    .step3-modal-table thead th {
        background-color: #324e9b;
        border-color: #51629a;
        font-weight: 500;
        font-size: 13px;
        padding: 8px 10px;
        white-space: nowrap;
        vertical-align: middle;
    }

    .step3-modal-table td {
        border-color: #9aa3b3;
        font-size: 13px;
        padding: 8px 10px;
        background: #fff;
    }

    .step3-modal-table-detail {
        border-color: #9aa3b3;
    }

    .step3-modal-table-detail thead th {
        background-color: #324e9b;
        border-color: #51629a;
        font-size: 13px;
        font-weight: 500;
        padding: 8px 8px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .step3-modal-table-detail td {
        border-color: #9aa3b3;
        font-size: 13px;
        padding: 8px 8px;
        background-color: #fff;
        vertical-align: middle;
    }

    .step3-modal-table-detail th:nth-child(1),
    .step3-modal-table-detail td:nth-child(1) {
        min-width: 220px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#kewangan-3 .btn-papar-semakan-kewangan:not(.btn-open-profil-petender-readonly)').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const dokumen = btn.getAttribute('data-dokumen') || '';
            const titleEl = document.getElementById('step3ModalDokumenTitle');
            if (titleEl && dokumen) {
                titleEl.textContent = `${dokumen}.`;
            }
        });
    });

    const firstModalEl = document.getElementById('modalPaparCadanganKewanganStep3');
    const secondModalEl = document.getElementById('modalPaparVendorCadanganKewanganStep3');
    const firstModal = firstModalEl ? bootstrap.Modal.getOrCreateInstance(firstModalEl) : null;
    const secondModal = secondModalEl ? bootstrap.Modal.getOrCreateInstance(secondModalEl) : null;

    document.querySelectorAll('.btn-step3-papar-vendor').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const vendorKod = btn.getAttribute('data-vendor-kod') || '';
            const vendorHarga = btn.getAttribute('data-harga') || '';
            const vendorSkorAuto = btn.getAttribute('data-skor-automatik') || '-';
            const vendorSkorManual = btn.getAttribute('data-skor-manual') || '-';
            const dokumen = document.getElementById('step3ModalDokumenTitle')?.textContent?.trim() || '';

            const elKod = document.getElementById('step3VendorKod');
            const elDokumen = document.getElementById('step3VendorDokumenTitle');
            const elItem = document.getElementById('step3DetailItem');
            const elHarga = document.getElementById('step3DetailHarga');
            const elJumlahHarga = document.getElementById('step3DetailJumlahHarga');
            const elSkorAuto = document.getElementById('step3DetailSkorAuto');
            const elJumlahSkor = document.getElementById('step3DetailJumlahSkor');
            const elSkorManual = document.getElementById('step3DetailSkorManual');

            if (elKod) elKod.textContent = vendorKod;
            if (elDokumen && dokumen) elDokumen.textContent = dokumen;
            if (elItem && dokumen) elItem.textContent = dokumen;
            if (elHarga && vendorHarga) elHarga.textContent = vendorHarga;
            if (elJumlahHarga && vendorHarga) elJumlahHarga.textContent = vendorHarga;
            if (elSkorAuto) elSkorAuto.textContent = vendorSkorAuto;
            if (elJumlahSkor) elJumlahSkor.textContent = vendorSkorAuto;
            if (elSkorManual) elSkorManual.textContent = vendorSkorManual;

            if (firstModal && secondModal) {
                firstModalEl.addEventListener('hidden.bs.modal', function handleHidden() {
                    secondModal.show();
                    firstModalEl.removeEventListener('hidden.bs.modal', handleHidden);
                });
                firstModal.hide();
            }
        });
    });

    document.querySelectorAll('#penilaian .btn-sebelumnya').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const tab = document.querySelector('#penyata-bank-tab');
            if (tab) tab.click();
        });
    });
});
</script>

