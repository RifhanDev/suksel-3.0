@extends('layouts.v3.master')

@section('styles')
<style>
    :root {
        --sg-red: #dc2626;
        --sg-red-dark: #991b1b;
        --sg-red-light: #fef2f2;
        --sg-teal: #19c1a7;
        --sg-teal-dark: #0d9488;
        --sg-blue: #2563eb;
        --sg-green: #16a34a;
    }

    .b10-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        background: #ffffff;
        overflow: hidden;
    }

    .b10-header-banner {
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

    /* Table Styling */
    .table-modern-wrapper {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.02);
    }

    .table-borang12-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .table-borang12-modern th {
        background: #1e293b;
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        vertical-align: middle;
        text-align: center;
        padding: 0.85rem 0.75rem;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .table-borang12-modern td {
        padding: 0.85rem 1rem;
        vertical-align: middle;
        border: 1px solid #f1f5f9;
        font-size: 0.825rem;
        color: #334155;
    }

    .table-borang12-modern tbody tr:hover {
        background-color: #f8fafc;
    }

    .confirmation-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
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
    $backToTenderUrl = $tenderParam 
        ? route('penilaianKewanganKerja.show', $tenderParam) 
        : (str_contains(url()->previous(), '/penilaian-kewangan') ? url()->previous() : route('penilaianKewangan'));
    $readOnly = $readOnly ?? false;

    // Build or fallback vendor summary for Borang 12
    $b12List = $b12VendorSummary ?? [
        1 => [
            'vendor_id'    => 1,
            'kod_pembekal' => 'V001',
            'vendor_name'  => 'Syarikat Bina Jaya Sdn Bhd',
            'status_bumi'  => 'Bumiputera',
            'status_pb'    => 'PB',
            'harga_asal'   => 'RM 1,250,000.00',
            'markah'       => '73.50',
            'keputusan'    => 'LULUS',
            'is_evaluated' => true,
        ],
        2 => [
            'vendor_id'    => 2,
            'kod_pembekal' => 'V002',
            'vendor_name'  => 'Pembinaan Utama Sdn Bhd',
            'status_bumi'  => 'Bukan Bumiputera',
            'status_pb'    => 'PBP',
            'harga_asal'   => 'RM 1,400,000.00',
            'markah'       => '80.00',
            'keputusan'    => 'LULUS',
            'is_evaluated' => true,
        ],
        3 => [
            'vendor_id'    => 3,
            'kod_pembekal' => 'V003',
            'vendor_name'  => 'Perusahaan Gagah Budi Enterprise',
            'status_bumi'  => 'Bumiputera',
            'status_pb'    => 'PB',
            'harga_asal'   => 'RM 1,650,000.00',
            'markah'       => '40.00',
            'keputusan'    => 'GAGAL',
            'is_evaluated' => false,
        ],
    ];
    $tenderIdentifier = isset($tender) ? ($tender->uuid ?: $tender->id ?: ($tender_no ?? '')) : ($tender_no ?? '');
@endphp

<div class="container-fluid px-0 py-2">

    {{-- Breadcrumb & Navigation Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none"><i class="bi bi-house-door me-1"></i>STOS</a></li>
                <li class="breadcrumb-item"><a href="{{ route('penilaianKewangan') }}" class="text-muted text-decoration-none">Penilaian Kewangan</a></li>
                <li class="breadcrumb-item"><a href="{{ $backToTenderUrl }}" class="text-muted text-decoration-none">Penilaian Kewangan (Kerja)</a></li>
                <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Borang 12</li>
            </ol>
        </nav>
        <a href="{{ $backToTenderUrl }}" class="btn btn-sm btn-sebelumnya d-inline-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Borang Penilaian</span>
        </a>
    </div>

    {{-- Header Banner Card --}}
    <div class="b10-card mb-4">
        <div class="b10-header-banner d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-white px-2.5 py-1 rounded-pill small fw-semibold">Peringkat Kedua</span>
                    @if($readOnly ?? false)
                        <span class="badge bg-light text-dark px-2.5 py-1 rounded-pill small fw-semibold"><i class="bi bi-lock-fill me-1"></i>Mod Paparan Sahaja</span>
                    @endif
                </div>
                <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px;">BORANG 12 - Jadual Keputusan Peringkat Kedua</h3>
                <p class="text-white-50 mb-0 small">Jadual perincian keputusan penilaian keupayaan tender bagi peringkat kedua.</p>
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
                        <div class="info-item-value text-danger font-monospace">{{ $no_tender_display ?? ($tender_no ?? 'TENDER-2026-001') }}</div>
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
                        <div class="info-item-value text-dark">{{ $ptj_display ?? 'JABATAN KERJA RAYA (JKR)' }}</div>
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

    {{-- Main Section Card: Vendor Participant List Table --}}
    <div class="b10-card p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <div class="bg-primary-subtle p-2 rounded-2 me-3">
                    <i class="bi bi-clipboard-data-fill text-primary fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Jadual Keputusan Peringkat Kedua (Penilaian Keupayaan Tender)</h5>
                    <p class="text-secondary small mb-0">Senarai rujukan petender, harga asal tender, markah keseluruhan dan keputusan penilaian.</p>
                </div>
            </div>
            <span class="section-badge-pill-primary ms-auto">
                <i class="bi bi-people me-1"></i>{{ count($b12List) }} Petender Berdaftar
            </span>
        </div>

        {{-- Table Participating Vendors --}}
        <div class="table-modern-wrapper mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-borang12-modern">
                    <thead>
                        <tr>
                            <th rowspan="3" style="width: 5%; text-align: center;">BIL</th>
                            <th rowspan="3" style="width: 15%; text-align: center;">RUJUKAN PETENDER</th>
                            <th rowspan="3" style="width: 18%; text-align: center;">STATUS PETENDER<br><span class="text-white-50 small">(Bumiputera / Bukan Bumiputera)</span></th>
                            <th rowspan="3" style="width: 18%; text-align: right;" class="pe-3">HARGA ASAL TENDER (RM)</th>
                            <th rowspan="3" style="width: 18%; text-align: center;">MARKAH KESELURUHAN PENILAIAN KEUPAYAAN PETENDER<br><span class="text-white-50 small">(Dari Borang 11)</span></th>
                            <th style="width: 6%; text-align: center;">PB*</th>
                            <th style="width: 6%; text-align: center;">PBP#</th>
                        </tr>
                        <tr style="background: #0f172a; color: #ffffff;">
                            <th style="background: #0f172a; text-align: center;" class="font-monospace fw-bold text-warning">50.00</th>
                            <th style="background: #0f172a; text-align: center;" class="font-monospace fw-bold text-warning">50.00</th>
                        </tr>
                        <tr style="background: #0f172a; color: #ffffff;">
                            <th colspan="2" style="text-align: center;">KEPUTUSAN<br><span class="text-white-50 small">(LULUS/GAGAL)</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($b12List as $index => $v)
                            @php
                                $vId = $v['vendor_id'] ?? $loop->iteration;
                                $kodText = $v['kod_pembekal'] ?? ('V' . str_pad($vId, 3, '0', STR_PAD_LEFT));
                                $statusBumi = $v['status_bumi'] ?? 'Bumiputera';
                                $statusPb = $v['status_pb'] ?? 'PB';
                                $hargaAsal = $v['harga_asal'] ?? 'RM 1,250,000.00';
                                $markah = $v['markah'] ?? '73.50';
                                $keputusan = $v['keputusan'] ?? 'LULUS';
                            @endphp
                            <tr>
                                <td class="text-center font-monospace fw-bold">{{ $loop->iteration }}</td>
                                <td class="text-center font-monospace fw-bold text-primary">{{ $kodText }}</td>
                                <td class="text-center">
                                    @if($statusBumi === 'Bumiputera')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 rounded-pill fw-semibold">
                                            Bumiputera
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2.5 py-1 rounded-pill fw-semibold">
                                            Bukan Bumiputera
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end font-monospace fw-semibold pe-3 text-dark">{{ $hargaAsal }}</td>
                                <td class="text-center font-monospace fw-bold fs-6 {{ (float)$markah >= 50 ? 'text-success' : 'text-danger' }}">{{ $markah }}</td>
                                <td colspan="2" class="text-center font-monospace text-muted">
                                     @if($keputusan === 'LULUS')
                                        <span class="badge bg-success text-white px-3 py-1.5 rounded-pill fw-bold" style="font-size: 0.775rem;">
                                            <i class="bi bi-check-lg me-1"></i>LULUS
                                        </span>
                                    @else
                                        <span class="badge bg-danger text-white px-3 py-1.5 rounded-pill fw-bold" style="font-size: 0.775rem;">
                                            <i class="bi bi-x-lg me-1"></i>GAGAL
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Tiada petender ditemui.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Legend Footer Box --}}
        <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between text-muted extra-small mb-4">
            <div>
                <span class="fw-bold text-dark me-2">* P.B.</span> Petender Berpengalaman
                <span class="mx-3">&bull;</span>
                <span class="fw-bold text-dark me-2"># P.B.P</span> Petender Belum Berpengalaman
            </div>
            <div class="font-monospace">Sistem E-Penilaian Suksel 3.0</div>
        </div>

        {{-- Form Actions & Confirmation Box --}}
        <div class="confirmation-box">
            <div class="row g-3 align-items-center">
                <div class="col-12 col-md-8">
                    <div class="form-check p-3 bg-white rounded-3 border mb-0">
                        <input class="form-check-input ms-0 me-2" type="checkbox" id="chkSahB12" onchange="checkB12CompletionState()" {{ ($readOnly ?? false) ? 'disabled checked' : '' }}>
                        <label class="form-check-label fw-semibold text-dark small" for="chkSahB12">
                            Saya mengesahkan jadual keputusan peringkat kedua (Borang 12) ini telah disemak secara teliti.
                        </label>
                    </div>
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <div class="d-flex justify-content-md-end gap-2">
                        <a href="{{ $backToTenderUrl }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </a>
                        <button type="button" class="btn btn-submit-danger px-4 rounded-3" id="btnSimpanMuktamadB12" onclick="simpanB12Main()">
                            <i class="bi bi-floppy me-1"></i>Simpan Keputusan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function checkB12CompletionState() {
        const btn = document.getElementById('btnSimpanMuktamadB12');
        if (!btn) return;

        const isReadOnly = {{ json_encode($readOnly ?? false) }};
        if (isReadOnly) {
            btn.disabled = true;
            return;
        }
        btn.disabled = false;
    }

    function simpanB12Main() {
        const chk = document.getElementById('chkSahB12');
        if (chk && !chk.checked) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Sila tandakan pengesahan terlebih dahulu.',
                confirmButtonColor: '#dc2626',
                customClass: {
                    popup: 'rounded-4 p-4',
                    confirmButton: 'px-4 py-2 rounded-3'
                }
            });
            return;
        }

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('chk_sah', '1');

        fetch('{{ route("penilaianKewanganKerja.borang12.simpanMuktamad", $tenderIdentifier) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berjaya Disimpan!',
                    text: data.message || 'Maklumat Borang 12 telah berjaya disahkan.',
                    confirmButtonText: 'Teruskan',
                    confirmButtonColor: '#dc2626',
                    customClass: {
                        popup: 'rounded-4 p-4',
                        confirmButton: 'px-4 py-2 rounded-3'
                    }
                }).then(() => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message || 'Ralat berlaku semasa menyimpan.',
                    confirmButtonColor: '#dc2626'
                });
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Ralat',
                text: 'Ralat pelayan berlaku.',
                confirmButtonColor: '#dc2626'
            });
        });
    }

    function showSuccessModal(msg){
        Swal.fire({
            icon: 'success',
            title: 'Berjaya Disimpan!',
            text: msg || 'Maklumat borang 12 telah berjaya disimpan ke dalam sistem.',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#19c1a7',
            customClass: {
                popup: 'rounded-4 p-4',
                confirmButton: 'px-4 py-2 rounded-3'
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        checkB12CompletionState();
    });
</script>
@endsection