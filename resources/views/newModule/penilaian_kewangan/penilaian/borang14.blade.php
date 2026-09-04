@extends('layouts.v3.master')

@section('styles')
<style>
    :root {
        --sg-red: #dc2626;
        --sg-red-dark: #991b1b;
        --sg-red-light: #fef2f2;
    }

    .b14-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        background: #ffffff;
        overflow: hidden;
    }

    .b14-header-banner {
        background: linear-gradient(135deg, var(--sg-red) 0%, var(--sg-red-dark) 100%);
        padding: 1.5rem 1.75rem;
        color: #ffffff;
    }

    .btn-sebelumnya {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #475569;
        border-radius: 10px;
        font-weight: 600;
        padding: 0.45rem 1rem;
        transition: all 0.2s ease-in-out;
    }

    .btn-sebelumnya:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .info-top-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    }

    .info-item-label {
        font-size: 0.725rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .info-item-value {
        font-size: 0.9rem;
        font-weight: 700;
        color: #1e293b;
    }

    .section-badge-pill-primary {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        font-weight: 600;
        font-size: 0.725rem;
        padding: 0.25rem 0.65rem;
        border-radius: 50rem;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
        box-shadow: 0 2px 4px rgba(29, 78, 216, 0.05);
    }

    /* Table Styling from Borang 3 */
    .table-modern-wrapper {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        background: #ffffff;
    }

    .table-borang3-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .table-borang3-modern th {
        background: #1e293b;
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        vertical-align: middle;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.15);
        padding: 0.75rem 0.6rem;
    }

    .table-borang3-modern th.subhead-main {
        background: #1e293b;
        color: #ffffff;
        font-size: 0.75rem;
    }

    .table-borang3-modern th.subhead-level2 {
        background: #6d6d79ff;
        color: #ffffff;
        font-size: 0.725rem;
        font-weight: 700;
        border-color: rgba(255, 255, 255, 0.15);
    }

    .table-borang3-modern td {
        padding: 0.75rem 0.6rem;
        vertical-align: middle;
        border: 1px solid #f1f5f9;
        font-size: 0.825rem;
        color: #334155;
    }

    .table-borang3-modern tbody tr:hover {
        background-color: #f8fafc;
    }

    .formula-tag {
        display: inline-block;
        background: rgba(58, 57, 57, 0.2);
        color: #ffffff;
        font-size: 0.675rem;
        font-weight: 600;
        padding: 0.1rem 0.4rem;
        border-radius: 4px;
        margin-top: 0.2rem;
        font-family: monospace;
    }

    .notes-box {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 12px;
        padding: 1.25rem;
    }

    .confirmation-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
    }

    .btn-submit-danger {
        background: linear-gradient(135deg, var(--sg-red) 0%, var(--sg-red-dark) 100%);
        border: none;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
        font-weight: 700;
    }

    .btn-submit-danger:hover {
        color: #ffffff;
        box-shadow: 0 6px 16px rgba(220, 38, 38, 0.3);
        transform: translateY(-1px);
    }
</style>
@endsection

@section('content')
@php
    $tenderParam = request('tender') ?: request('tender_no') ?: ($tender_no ?? '');
    $tenderIdentifier = isset($tender) ? ($tender->uuid ?: $tender->id ?: $tenderParam) : $tenderParam;
    $backToTenderUrl = $tenderParam 
        ? route('penilaianKewanganKerja.show', ['tender_no' => $tenderParam, 'tab' => 'p3']) 
        : (str_contains(url()->previous(), '/penilaian-kewangan') ? url()->previous() : route('penilaianKewangan'));

    $displayVendors = !empty($b14VendorSummary) ? array_values($b14VendorSummary) : (
        !empty($b6PassingVendors) ? $b6PassingVendors : (
            !empty($b8VendorSummary) ? array_values($b8VendorSummary) : []
        )
    );

    // Compute rank by price ascending as fallback
    $vendorPrices = [];
    foreach ($displayVendors as $k => $v) {
        $rawPrice = $v['harga_asal_num'] ?? (float) preg_replace('/[^0-9.]/', '', $v['harga_display'] ?? ($v['harga_asal'] ?? (isset($v['harga_tawaran']) ? $v['harga_tawaran'] : 0)));
        $vendorPrices[$k] = $rawPrice;
    }
    asort($vendorPrices);
    $priceRanks = [];
    $rankCounter = 1;
    foreach ($vendorPrices as $k => $priceVal) {
        $priceRanks[$k] = $rankCounter++;
    }
@endphp

<div class="container-fluid px-0 py-2">

    {{-- Breadcrumb & Navigation Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none"><i class="bi bi-house-door me-1"></i>STOS</a></li>
                <li class="breadcrumb-item"><a href="{{ route('penilaianKewangan') }}" class="text-muted text-decoration-none">Penilaian Kewangan</a></li>
                <li class="breadcrumb-item"><a href="{{ $backToTenderUrl }}" class="text-muted text-decoration-none">Penilaian Kewangan (Kerja)</a></li>
                <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Borang 14</li>
            </ol>
        </nav>
        <a href="{{ $backToTenderUrl }}" class="btn btn-sm btn-sebelumnya d-inline-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Borang Penilaian</span>
        </a>
    </div>

    {{-- Header Banner Card --}}
    <div class="b14-card mb-4">
        <div class="b14-header-banner d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-white px-2.5 py-1 rounded-pill small fw-semibold">Peringkat Ketiga</span>
                    @if(!empty($readOnly))
                        <span class="badge bg-light text-dark px-2.5 py-1 rounded-pill small fw-semibold"><i class="bi bi-lock-fill me-1"></i>Mod Paparan Sahaja</span>
                    @endif
                </div>
                <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px;">BORANG 14 - Jadual Keputusan Penilaian Peringkat Ketiga</h3>
                <p class="text-white-50 mb-0 small">Analisa keputusan keseluruhan penilaian keupayaan minimum, pelaras cut-off, markah terlaras, dan kedudukan petender.</p>
            </div>
        </div>
    </div>

    {{-- Top Info Grid Card --}}
    <div class="info-top-card p-3.5 mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-12 col-sm-6 col-md-3 border-end">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: #fef2f2; color: #dc2626;">
                        <i class="bi bi-archive fs-5"></i>
                    </div>
                    <div>
                        <div class="info-item-label">No. Sebut Harga / Tender</div>
                        <div class="info-item-value text-danger font-monospace">{{ $no_tender_display ?? ($tenderParam ?: '-') }}</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3 border-end">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: #eff6ff; color: #2563eb;">
                        <i class="bi bi-building fs-5"></i>
                    </div>
                    <div>
                        <div class="info-item-label">PTJ Perolehan</div>
                        <div class="info-item-value text-dark">{{ $ptj_display ?? 'JABATAN PENGAIRAN DAN SALIRAN' }}</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3 border-end">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: #fffbeb; color: #d97706;">
                        <i class="bi bi-hourglass-split fs-5"></i>
                    </div>
                    <div>
                        <div class="info-item-label">Status Proses</div>
                        <div class="mt-1">
                            <span class="badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning border-opacity-25 px-2.5 py-1 rounded-pill fw-semibold" style="font-size: 0.72rem;">
                                {{ $status_label ?? 'Dalam Penilaian' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: #ecfdf5; color: #059669;">
                        <i class="bi bi-calendar-event fs-5"></i>
                    </div>
                    <div>
                        <div class="info-item-label">Sah Laku Tamat</div>
                        <div class="info-item-value text-dark font-monospace">{{ $sah_laku_tamat ?? '17/01/2022' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Section Card: Decision Table --}}
    <div class="b14-card p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <div class="bg-primary-subtle p-2 rounded-2 me-3">
                    <i class="bi bi-bar-chart-line text-primary fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Senarai Keputusan Penilaian Peringkat Ketiga</h5>
                    <p class="text-secondary small mb-0">Jadual keputusan kedudukan petender, markah keupayaan minimum terlaras, dan score CIDB.</p>
                </div>
            </div>
            <span class="section-badge-pill-primary ms-auto">
                <i class="bi bi-people me-1"></i>{{ count($displayVendors) }} Petender Berdaftar
            </span>
        </div>

        {{-- Modern Table Wrapper --}}
        <div class="table-modern-wrapper mb-4">
            <div class="table-responsive">
                <table class="table-borang3-modern align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="subhead-main text-start ps-3.5 py-3 align-middle" colspan="3" rowspan="3" style="width: 420px; background: #1e293b; color: #ffffff;">
                                <div class="d-flex flex-column gap-1">
                                    <span class="text-white-50 small font-monospace text-uppercase" style="letter-spacing: 0.5px;">TENDER UNTUK :</span>
                                    <span class="fw-bold fs-6 text-white" style="line-height: 1.35;">{{ $tajuk_display ?? ($tender->tajuk_tender ?? 'PERKHIDMATAN MEMBEKAL DAN MENGHANTAR PERALATAN') }}</span>
                                </div>
                            </th>
                            <th class="subhead-main text-center py-2.5">KEDUDUKAN TENDER</th>
                            <th class="subhead-main text-center py-2.5" colspan="2">Markah Keseluruhan Penilaian Keupayaan Minimum</th>
                        </tr>
                        <tr>
                            <th class="subhead-level2 text-center py-2">Petender Dibawah Pelaras CutOff</th>
                            <th class="subhead-level2 text-center py-2" style="width: 90px;">PB*</th>
                            <th class="subhead-level2 text-center py-2" style="width: 90px;">PBP**</th>
                        </tr>
                        <tr>
                            <th class="subhead-level2 text-center py-2">Petender Diatas Pelaras CutOff</th>
                            <th class="subhead-level2 text-center py-2 font-monospace fw-bold" style="color: #ef733aff;">50.00</th>
                            <th class="subhead-level2 text-center py-2 font-monospace fw-bold" style="color: #ef733aff;">50.00</th>
                        </tr>
                        <tr style="background: #d7d7d9; color: #6d6d79ff;">
                            <th style="width: 170px; background: #d7d7d9; color: #6d6d79ff;" class="text-center">No. Rujukan. Tender</th>
                            <th style="width: 150px; background: #d7d7d9; color: #6d6d79ff;" class="text-end">Harga Tender Asal<br><span class="formula-tag">(RM)</span></th>
                            <th style="width: 140px; background: #d7d7d9; color: #6d6d79ff;" class="text-center">Status Petender</th>
                            <th style="width: 260px; background: #d7d7d9; color: #6d6d79ff;" class="text-center">Markah Keseluruhan Penilaian Keupayaan Terlaras<br><span class="formula-tag">(Dari Borang 13)</span></th>
                            <th style="width: 160px; background: #d7d7d9; color: #6d6d79ff;" class="text-center">Kedudukan Petender</th>
                            <th style="width: 120px; background: #d7d7d9; color: #6d6d79ff;" class="text-center">Score CIDB</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($displayVendors as $idx => $v)
                            @php
                                $vId = $v['vendor_id'] ?? ($v['id'] ?? ($idx + 1));
                                $statusBumi = $v['status_bumi'] ?? ($b12VendorSummary[$vId]['status_bumi'] ?? ($b8VendorSummary[$vId]['status_bumi'] ?? (($vId % 2 != 0) ? 'Bumiputera' : 'Bukan Bumiputera')));
                                $markahTerlaras = $v['markah_terlaras'] ?? ($b13VendorSummary[$vId]['markah_terlaras'] ?? '0.00');
                                $kodPembekal = $v['kod_pembekal'] ?? ('45/' . str_pad($idx+1, 2, '0', STR_PAD_LEFT));
                                $hargaDisplay = $v['harga_display'] ?? ($v['harga_asal'] ?? (isset($v['harga_tawaran']) ? 'RM ' . number_format($v['harga_tawaran'], 2) : 'RM 0.00'));
                                $kedudukan = (!empty($v['kedudukan']) && $v['kedudukan'] !== '-') ? $v['kedudukan'] : ($priceRanks[$idx] ?? ($idx + 1));
                            @endphp
                            <tr>
                                <td class="text-center font-monospace fw-bold text-dark">
                                    {{ $kodPembekal }}
                                </td>
                                <td class="text-end font-monospace text-dark fw-semibold">
                                    {{ $hargaDisplay }}
                                </td>
                                <td class="text-center fw-semibold {{ $statusBumi === 'Bumiputera' ? 'text-success' : 'text-primary' }}">
                                    {{ $statusBumi }}
                                </td>
                                <td class="text-center font-monospace text-danger fw-bold">
                                    {{ $markahTerlaras }}
                                </td>
                                <td class="text-center font-monospace fw-bold text-dark">
                                    {{ $kedudukan }}
                                </td>
                                <td class="text-center px-2" style="width: 140px;">
                                    <input type="text"
                                           class="form-control form-control-sm text-center font-monospace fw-semibold score-cidb-input"
                                           data-vendor-id="{{ $vId }}"
                                           name="score_cidb[{{ $vId }}]"
                                           placeholder="Sila isi"
                                           value="{{ $v['score_cidb'] ?? '' }}"
                                           {{ !empty($readOnly) ? 'readonly' : '' }}>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <div class="d-flex flex-column align-items-center gap-2">
                                        <i class="bi bi-exclamation-circle text-warning display-6"></i>
                                        <span class="fw-semibold">Tiada petender yang lulus Peringkat Pertama untuk Borang 14.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Notes Section --}}
        <div class="notes-box mb-4">
            <div class="d-flex align-items-center gap-2 fw-bold mb-2" style="color: #92400e;">
                <i class="bi bi-info-circle-fill text-warning fs-5"></i>
                <span>Catatan &amp; Singkatan :</span>
            </div>
            <ul class="list-unstyled mb-0 small text-secondary">
                <li class="d-flex gap-2 mb-1">
                    <span class="fw-bold text-dark">* PB</span>
                    <span>- Petender Berpengalaman</span>
                </li>
                <li class="d-flex gap-2">
                    <span class="fw-bold text-dark">** PBP</span>
                    <span>- Petender Belum Berpengalaman</span>
                </li>
            </ul>
        </div>

        {{-- Form Actions & Confirmation Box --}}
        <div class="confirmation-box">
            <div class="row g-3 align-items-center">
                <div class="col-12 col-md-8">
                    <div class="form-check p-3 bg-white rounded-3 border mb-0">
                        <input class="form-check-input ms-0 me-2" type="checkbox" id="chkSah" {{ !empty($readOnly) ? 'disabled checked' : '' }}>
                        <label class="form-check-label fw-semibold text-dark small" for="chkSah">
                            Saya mengesahkan keputusan penilaian peringkat ketiga ini telah disemak secara teliti.
                        </label>
                    </div>
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <div class="d-flex justify-content-md-end gap-2">
                        <a href="{{ $backToTenderUrl }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </a>
                        <button type="button" class="btn btn-submit-danger px-4 rounded-3" id="btnSimpanMuktamad" onclick="simpanB14()">
                            <i class="bi bi-floppy me-1"></i>Simpan Keputusan
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const tenderNo = "{{ $tenderParam }}";

    function simpanB14() {
        const chkSah = document.getElementById('chkSah');
        if (chkSah && !chkSah.checked) {
            Swal.fire({
                icon: 'warning',
                title: 'Pengesahan Diperlukan',
                html: '<p class="mb-1 text-secondary fs-6">Sila tandakan <strong>kotak pengesahan</strong> terlebih dahulu sebelum menyimpan keputusan.</p>',
                confirmButtonText: 'Faham',
                confirmButtonColor: '#dc2626',
                customClass: {
                    popup: 'rounded-4 shadow',
                    confirmButton: 'px-4 py-2 rounded-3 fw-semibold'
                }
            });
            return;
        }

        const btnSimpan = document.getElementById('btnSimpanMuktamad');
        btnSimpan.disabled = true;
        btnSimpan.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';

        const scoreInputs = document.querySelectorAll('.score-cidb-input');
        const scoreCidbData = {};
        scoreInputs.forEach(input => {
            const vId = input.dataset.vendorId;
            if (vId) {
                scoreCidbData[vId] = input.value.trim();
            }
        });

        fetch(`/penilaian-kewangan-kerja/${encodeURIComponent(tenderNo)}/borang/borang14/simpan-muktamad`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                chk_sah: 1,
                score_cidb: scoreCidbData
            })
        })
        .then(res => res.json())
        .then(data => {
            btnSimpan.disabled = false;
            btnSimpan.innerHTML = '<i class="bi bi-floppy me-1"></i>Simpan Keputusan';

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berjaya Disimpan!',
                    text: data.message || 'Maklumat Borang 14 (Jadual Keputusan Penilaian Peringkat Ketiga) telah berjaya disahkan dan disimpan!',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#047857',
                    customClass: {
                        popup: 'rounded-4 shadow',
                        confirmButton: 'px-4 py-2 rounded-3 fw-semibold'
                    }
                }).then(() => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.reload();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Ralat!',
                    text: data.message || 'Gagal mengesahkan keputusan Borang 14.',
                    confirmButtonColor: '#dc2626'
                });
            }
        })
        .catch(err => {
            btnSimpan.disabled = false;
            btnSimpan.innerHTML = '<i class="bi bi-floppy me-1"></i>Simpan Keputusan';
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Ralat Sistem!',
                text: 'Berlaku masalah semasa berhubung dengan pelayan.',
                confirmButtonColor: '#dc2626'
            });
        });
    }
</script>
@endsection