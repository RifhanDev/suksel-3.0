@extends('layouts.v3.master')

@section('styles')
<style>
    :root {
        --sg-red: #dc2626;
        --sg-red-dark: #991b1b;
        --sg-red-light: #fef2f2;
    }

    .b8-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        background: #ffffff;
        overflow: hidden;
    }

    .b8-header-banner {
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

    .section-badge-pill-success {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
        font-weight: 600;
        font-size: 0.725rem;
        padding: 0.25rem 0.65rem;
        border-radius: 50rem;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
        box-shadow: 0 2px 4px rgba(4, 120, 87, 0.05);
    }

    .table-modern-wrapper {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        background: #ffffff;
    }

    .table-borang-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .table-borang-modern th {
        background: #1e293b;
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        vertical-align: middle;
        text-align: center;
        padding: 0.85rem 0.75rem;
    }

    .table-borang-modern td {
        padding: 0.85rem 0.8rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.85rem;
        color: #334155;
    }

    .table-borang-modern tbody tr:hover {
        background-color: #f8fafc;
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
        transition: all 0.2s ease-in-out;
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
    $backToTenderUrl = route('penilaianKewanganKerja.show', ['tender_no' => $tenderIdentifier, 'tab' => 'p2']);
@endphp

<div class="container-fluid px-0 py-2">

    {{-- Breadcrumb & Navigation Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none"><i class="bi bi-house-door me-1"></i>STOS</a></li>
                <li class="breadcrumb-item"><a href="{{ route('penilaianKewangan') }}" class="text-muted text-decoration-none">Penilaian Kewangan</a></li>
                <li class="breadcrumb-item"><a href="{{ $backToTenderUrl }}" class="text-muted text-decoration-none">Penilaian Kewangan (Kerja Kecil M&amp;E)</a></li>
                <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Borang 8</li>
            </ol>
        </nav>
        <a href="{{ $backToTenderUrl }}" class="btn btn-sm btn-sebelumnya d-inline-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Peringkat 2</span>
        </a>
    </div>

    {{-- Header Banner Card --}}
    <div class="b8-card mb-4">
        <div class="b8-header-banner d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-white px-2.5 py-1 rounded-pill small fw-semibold">Muktamad (M&amp;E)</span>
                    @if($readOnly ?? false)
                        <span class="badge bg-light text-dark px-2.5 py-1 rounded-pill small fw-semibold"><i class="bi bi-lock-fill me-1"></i>Mod Paparan Sahaja</span>
                    @endif
                </div>
                <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px;">BORANG 8 - KEPUTUSAN PENILAIAN KESELURUHAN TEKNIKAL &amp; KEWANGAN</h3>
                <p class="text-white-50 mb-0 small">Rumusan keputusan muktamad kelayakan petender bagi perolehan Kerja Kecil (M&amp;E).</p>
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
                        <div class="info-item-value text-danger font-monospace">{{ $no_tender_display ?? '-' }}</div>
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
                        <div class="info-item-value text-dark">{{ $ptj_display ?? '-' }}</div>
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
                                {{ $status_label ?? 'Menunggu Penilaian' }}
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
                        <div class="info-item-value text-dark font-monospace">{{ $sah_laku_tamat ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Section Card --}}
    <div class="b8-card p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <div class="bg-primary-subtle p-2 rounded-2 me-3">
                    <i class="bi bi-award text-primary fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Senarai Keputusan Penilaian Keseluruhan</h5>
                    <p class="text-secondary small mb-0">Ringkasan kelayakan kewangan dan teknikal bagi perolehan Kerja Kecil.</p>
                </div>
            </div>
            <span class="section-badge-pill-primary ms-auto">
                <i class="bi bi-people me-1"></i>{{ count($participants) }} Petender Berdaftar
            </span>
        </div>

        <div class="table-modern-wrapper mb-4">
            <div class="table-responsive">
                <table class="table table-borang-modern align-middle mb-0">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 80px;" class="text-center">Ruj.<br>Petender</th>
                            <th rowspan="2" style="min-width: 180px;" class="text-start ps-3">Nama Syarikat</th>
                            <th rowspan="2" style="width: 130px;" class="text-end pe-3">Harga Tender<br>Asal</th>
                            <th rowspan="2" style="width: 100px;" class="text-center">% BWAM<br>/ BWAJ</th>
                            <th rowspan="2" style="width: 90px;" class="text-center">Tempoh<br>Tender</th>
                            <th colspan="2" class="text-center" style="border-bottom: 1px solid rgba(255, 255, 255, 0.15);">KRITERIA PENILAIAN PERINGKAT KEDUA</th>
                            <th rowspan="2" style="width: 130px;" class="text-center">*KEPUTUSAN<br>PENILAIAN</th>
                        </tr>
                        <tr>
                            <th style="min-width: 220px;" class="text-center">Penilaian Keupayaan Kewangan, Prestasi,<br>Beban Kerja &amp; Pengalaman (Dari Borang 6)</th>
                            <th style="min-width: 180px;" class="text-center">Keputusan Penilaian Teknikal<br>(Dari Borang 7)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $b7EvalsMap = \App\Models\TenderKewanganKerjaEvaluation::query()
                                ->where('tender_id', $tender->id)
                                ->where('borang_code', 'borang7')
                                ->get()
                                ->keyBy('vendor_id');

                            $passingVendors = [];
                        @endphp

                        @forelse($participants as $idx => $p)
                            @php
                                $vId = $p->vendor_id;
                                $kodPembekal = $p->kod_pembekal ?: ($p->ref_number ?: ('V' . str_pad($idx + 1, 3, '0', STR_PAD_LEFT)));
                                $vendorName = $p->vendor->name ?? $p->vendor->company_name ?? $p->name ?? ('Petender ' . ($idx + 1));
                                $rocNo = $p->vendor->registration ?? $p->vendor->registration_no ?? $p->vendor->roc_no ?? $p->vendor->ic_no ?? null;

                                $hargaTawaran = (float)($p->harga_tawaran ?: ($p->tawaran_harga ?: ($p->price ?? 0)));
                                $ajVal = (float)($tender->harga_indikatif ?? ($tender->anggaran_jabatan ?? 0));

                                $pctBwam = null;
                                if ($ajVal > 0 && $hargaTawaran > 0) {
                                    $pctBwam = (($hargaTawaran - $ajVal) / $ajVal) * 100.0;
                                }

                                $tempohVal = $p->tempoh_tawaran ?: ($p->tempoh ?: ($tender->tempoh_siap_val ?: null));
                                $tempohDisp = $tempohVal ? ($tempohVal . ' Minggu') : '-';

                                // Borang 6 (Keseluruhan Peringkat Pertama - Kewangan & Pengalaman)
                                $b3Data = $b3VendorData[$vId] ?? null;
                                $isModalCukup = ($b3Data && ($b3Data['mudah_cair_m'] ?? 0) >= 0);

                                $b4Data = $b4VendorData[$vId] ?? null;
                                $isPrestasiMemuaskan = ($b4Data['status_pematuhan'] ?? 1) === 1;

                                $isBebanBerupaya = true;
                                $isPengalamanMemenuhi = true;

                                $isBorang6Lulus = $isModalCukup && $isPrestasiMemuaskan && $isBebanBerupaya && $isPengalamanMemenuhi;

                                // Borang 7 (Keputusan Teknikal M&E)
                                $b7EvalRec = $b7EvalsMap->get($vId);
                                $b7Payload = $b7EvalRec ? (is_string($b7EvalRec->payload) ? json_decode($b7EvalRec->payload, true) : $b7EvalRec->payload) : [];
                                $isBorang7Lulus = ($b7EvalRec && (int)$b7EvalRec->status_pematuhan === 1) || (($b7Payload['keputusan'] ?? '') === 'Lulus');

                                // Overall Keputusan
                                $isOverallLulus = $isBorang6Lulus && $isBorang7Lulus;

                                if ($isOverallLulus) {
                                    $passingVendors[] = [
                                        'vId'          => $vId,
                                        'kodPembekal'  => $kodPembekal,
                                        'vendorName'   => $vendorName,
                                        'rocNo'        => $rocNo,
                                        'hargaTawaran' => $hargaTawaran,
                                        'tempohDisp'   => $tempohDisp,
                                    ];
                                }
                            @endphp
                            <tr>
                                <td class="text-center font-monospace fw-bold text-dark">{{ $kodPembekal }}</td>
                                <td class="ps-3">
                                    <div class="fw-bold text-dark">{{ $vendorName }}</div>
                                    @if($rocNo)
                                        <span class="small text-muted font-monospace">ROC: {{ $rocNo }}</span>
                                    @endif
                                </td>
                                <td class="text-end font-monospace fw-bold text-dark pe-3">
                                    {{ number_format($hargaTawaran, 2) }}
                                </td>
                                <td class="text-center font-monospace fw-semibold">
                                    @if($pctBwam !== null)
                                        @if($pctBwam < 0)
                                            <span class="text-danger">{{ number_format($pctBwam, 2) }}%</span>
                                        @elseif($pctBwam > 0)
                                            <span class="text-success">+{{ number_format($pctBwam, 2) }}%</span>
                                        @else
                                            <span class="text-dark">0.00%</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center font-monospace">{{ $tempohDisp }}</td>

                                {{-- Penilaian Keupayaan Kewangan, Prestasi, Beban Kerja & Pengalaman (Dari Borang 6) --}}
                                <td class="text-center">
                                    @if($isBorang6Lulus)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1"><i class="bi bi-check-circle-fill me-1"></i>LULUS</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1"><i class="bi bi-x-circle-fill me-1"></i>GAGAL</span>
                                    @endif
                                </td>

                                {{-- Keputusan Penilaian Teknikal (Dari Borang 7) --}}
                                <td class="text-center">
                                    @if($isBorang7Lulus)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1"><i class="bi bi-check-circle-fill me-1"></i>LULUS</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1"><i class="bi bi-x-circle-fill me-1"></i>GAGAL</span>
                                    @endif
                                </td>

                                {{-- Keputusan Penilaian --}}
                                <td class="text-center">
                                    @if($isOverallLulus)
                                        <span class="badge bg-success text-white px-3 py-1.5 rounded-pill fw-bold"><i class="bi bi-award-fill me-1"></i>LULUS</span>
                                    @else
                                        <span class="badge bg-danger text-white px-3 py-1.5 rounded-pill fw-bold"><i class="bi bi-x-octagon-fill me-1"></i>GAGAL</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <div class="d-flex flex-column align-items-center gap-2">
                                        <i class="bi bi-exclamation-circle text-warning display-6"></i>
                                        <span class="fw-semibold">Tiada petender berdaftar ditemui bagi Borang 8.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Qualified Vendors Section for Kerja Kecil M&E (Borang 8) --}}
        <div class="mt-4 pt-4 border-top">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 text-success p-2.5 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="bi bi-person-check-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Senarai Petender Yang Layak Lulus ke Peringkat Seterusnya</h5>
                        <p class="text-secondary small mb-0">Petender yang memenuhi semua kriteria penilaian kewangan dan teknikal M&amp;E serta disyorkan lulus.</p>
                    </div>
                </div>
                <span class="section-badge-pill-success ms-auto">
                    <i class="bi bi-check-circle-fill me-1"></i>{{ count($passingVendors) }} Petender Layak
                </span>
            </div>

            <div class="table-modern-wrapper mb-4">
                <div class="table-responsive">
                    <table class="table table-borang-modern align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 60px;" class="text-center">BIL</th>
                                <th style="width: 140px;" class="text-center">KOD PETENDER</th>
                                <th class="text-start ps-3">NAMA SYARIKAT</th>
                                <th style="width: 170px;" class="text-end pe-3">HARGA TAWARAN (RM)</th>
                                <th style="width: 130px;" class="text-center">TEMPOH TAWARAN</th>
                                <th style="width: 160px;" class="text-center">KEPUTUSAN PENILAIAN</th>
                                <th style="width: 180px;" class="text-center">STATUS KELAYAKAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($passingVendors as $pIdx => $pV)
                                <tr>
                                    <td class="text-center font-monospace fw-bold text-dark">{{ $pIdx + 1 }}</td>
                                    <td class="text-center font-monospace fw-bold text-dark">{{ $pV['kodPembekal'] }}</td>
                                    <td class="ps-3">
                                        <div class="fw-bold text-dark">{{ $pV['vendorName'] }}</div>
                                        @if($pV['rocNo'])
                                            <span class="small text-muted font-monospace">ROC: {{ $pV['rocNo'] }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end font-monospace fw-bold text-dark pe-3">
                                        {{ number_format($pV['hargaTawaran'], 2) }}
                                    </td>
                                    <td class="text-center font-monospace">{{ $pV['tempohDisp'] }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-success text-white px-3 py-1.5 rounded-pill fw-bold">
                                            <i class="bi bi-award-fill me-1"></i>LULUS
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 rounded-pill fw-semibold">
                                            <i class="bi bi-arrow-right-circle me-1"></i>LAYAK
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <div class="d-flex flex-column align-items-center gap-2">
                                            <i class="bi bi-info-circle text-secondary display-6"></i>
                                            <span class="fw-semibold">Tiada petender yang layak / lulus penilaian pada peringkat ini.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Final Confirmation Box --}}
        <div class="confirmation-box">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="form-check">
                    <input class="form-check-input border-secondary" type="checkbox" id="chkSah" {{ $readOnly ? 'disabled' : '' }}>
                    <label class="form-check-label text-dark fw-semibold small" for="chkSah">
                        Saya mengesahkan bahawa keputusan penilaian keseluruhan teknikal &amp; kewangan M&amp;E ini adalah muktamad dan betul.
                    </label>
                </div>
                <button type="button" id="btnSimpanMuktamad" class="btn btn-submit-danger px-4 py-2.5 rounded-3 d-inline-flex align-items-center gap-2" {{ $readOnly ? 'disabled' : '' }}>
                    <i class="bi bi-check-all fs-5"></i>
                    <span>Simpan Keputusan Muktamad</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnSimpanMuktamad = document.getElementById('btnSimpanMuktamad');
        if (btnSimpanMuktamad) {
            btnSimpanMuktamad.addEventListener('click', function() {
                const chkSah = document.getElementById('chkSah');
                if (chkSah && !chkSah.checked) {
                    Swal.fire({ icon: 'warning', title: 'Pengesahan Diperlukan', text: 'Sila tandakan kotak pengesahan terlebih dahulu.' });
                    return;
                }

                btnSimpanMuktamad.disabled = true;
                btnSimpanMuktamad.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan Muktamad...';

                fetch('{{ route('penilaianKewanganKerja.borang8.simpanMuktamad', $tenderIdentifier) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ chk_sah: 1 })
                })
                .then(r => r.json())
                .then(data => {
                    btnSimpanMuktamad.disabled = false;
                    btnSimpanMuktamad.innerHTML = '<i class="bi bi-check-all fs-5 me-1"></i>Simpan Keputusan Muktamad';

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Penilaian Muktamad Disahkan!',
                            text: data.message || 'Penilaian Kewangan Kerja Kecil M&E telah disahkan dan selesai!',
                            confirmButtonText: 'Kembali ke Senarai',
                            confirmButtonColor: '#dc2626'
                        }).then(() => {
                            window.location.href = data.redirect || '{{ route('penilaianKewangan') }}';
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Ralat!', text: data.message });
                    }
                })
                .catch(err => {
                    btnSimpanMuktamad.disabled = false;
                    btnSimpanMuktamad.innerHTML = '<i class="bi bi-check-all fs-5 me-1"></i>Simpan Keputusan Muktamad';
                    Swal.fire({ icon: 'error', title: 'Ralat Sistem!', text: 'Masalah berhubung dengan pelayan.' });
                });
            });
        }
    });
</script>
@endsection
