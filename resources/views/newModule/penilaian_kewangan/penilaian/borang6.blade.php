@extends('layouts.v3.master')

@section('styles')
<style>
    :root {
        --sg-red: #dc2626;
        --sg-red-dark: #991b1b;
        --sg-red-light: #fef2f2;
    }

    .b6-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        background: #ffffff;
        overflow: hidden;
    }

    .b6-header-banner {
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

    /* Table Styling */
    .table-modern-wrapper {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        background: #ffffff;
    }

    .table-modern-b1 {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-modern-b1 thead th {
        background: #1e293b;
        color: #ffffff;
        font-weight: 700;
        font-size: 0.775rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.85rem 1.25rem;
        border-bottom: none;
        white-space: nowrap;
    }

    .table-modern-b1 tbody tr {
        transition: background-color 0.15s ease-in-out;
    }

    .table-modern-b1 tbody tr:hover {
        background-color: #f8fafc;
    }

    .table-modern-b1 tbody td {
        padding: 0.85rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
        color: #334155;
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
    $backToTenderUrl = $tenderParam 
        ? route('penilaianKewanganKerja.show', $tenderParam) 
        : (str_contains(url()->previous(), '/penilaian-kewangan') ? url()->previous() : route('penilaianKewangan'));

    $passingVendors = $b6PassingVendors ?? [];
@endphp

<div class="container-fluid px-0 py-2">

    {{-- Breadcrumb & Navigation Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none"><i class="bi bi-house-door me-1"></i>STOS</a></li>
                <li class="breadcrumb-item"><a href="{{ route('penilaianKewangan') }}" class="text-muted text-decoration-none">Penilaian Kewangan</a></li>
                <li class="breadcrumb-item"><a href="{{ $backToTenderUrl }}" class="text-muted text-decoration-none">Penilaian Kewangan (Kerja)</a></li>
                <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Borang 6</li>
            </ol>
        </nav>
        <a href="{{ $backToTenderUrl }}" class="btn btn-sm btn-sebelumnya d-inline-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Borang Penilaian</span>
        </a>
    </div>

    {{-- Header Banner Card --}}
    <div class="b6-card mb-4">
        <div class="b6-header-banner d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-white px-2.5 py-1 rounded-pill small fw-semibold">Peringkat Pertama</span>
                    @if($readOnly)
                        <span class="badge bg-light text-dark px-2.5 py-1 rounded-pill small fw-semibold"><i class="bi bi-lock-fill me-1"></i>Mod Paparan Sahaja</span>
                    @endif
                </div>
                <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px;">BORANG 6 - Senarai Petender Yang Lulus Penilaian Peringkat Pertama</h3>
                <p class="text-white-50 mb-0 small">Senarai turutan harga tender bagi petender yang lulus penilaian peringkat pertama.</p>
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
    <div class="b6-card p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <div class="bg-primary-subtle p-2 rounded-2 me-3">
                    <i class="bi bi-card-checklist text-primary fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Senarai Petender Yang Lulus Penilaian Peringkat Pertama</h5>
                    <p class="text-secondary small mb-0">Disusun mengikut turutan harga tender terendah hingga tertinggi.</p>
                </div>
            </div>
            <span class="section-badge-pill-success ms-auto">
                <i class="bi bi-trophy me-1"></i>{{ count($passingVendors) }} Petender Lulus
            </span>
        </div>

        {{-- Table --}}
        <div class="table-modern-wrapper mb-4">
            <div class="table-responsive">
                <table class="table table-modern-b1 align-middle">
                    <thead>
                        <tr>
                            <th style="width: 80px;" class="text-center">Bil</th>
                            <th style="width: 320px;"><i class="bi bi-person-vcard text-danger me-1"></i> Rujukan Petender (Kod Pembekal)</th>
                            <th class="text-end pe-4"><i class="bi bi-currency-dollar text-danger me-1"></i> Harga Tender Asal (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($passingVendors as $r)
                            <tr>
                                <td class="text-center font-monospace fw-bold" style="background-color: #efeff0ff; color: #3f3f3fff;">
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="bg-danger bg-opacity-10 text-danger p-2 rounded-2 d-inline-flex align-items-center justify-content-center" style="width:32px; height:32px; margin-right:10px">
                                            <i class="bi bi-building"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold font-monospace text-dark d-block" style="font-size: 0.9rem;">{{ $r['kod_pembekal'] }}</span>
                                            @if(!empty($r['vendor_name']))
                                                <span class="small text-muted">{{ $r['vendor_name'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end pe-4 font-monospace fw-bold text-dark" style="font-size: 0.925rem;">
                                    {{ $r['harga_display'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">
                                    <div class="d-flex flex-column align-items-center gap-2">
                                        <i class="bi bi-exclamation-circle text-warning display-6"></i>
                                        <span class="fw-semibold">Tiada petender yang lulus Penilaian Peringkat Pertama (Borang 5).</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Form Actions & Confirmation Box --}}
        <div class="confirmation-box">
            <div class="form-check p-3 bg-white rounded-3 border mb-3">
                <input class="form-check-input ms-0 me-2" type="checkbox" id="chkSah" {{ $readOnly ? 'disabled checked' : '' }}>
                <label class="form-check-label fw-semibold text-dark small" for="chkSah">
                    Saya mengesahkan petender di atas layak untuk penilaian peringkat seterusnya.
                </label>
            </div>
            <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                <a href="{{ $backToTenderUrl }}" class="btn btn-outline-secondary px-3 rounded-3 fw-semibold">
                    <i class="bi bi-x-circle me-1"></i>Batal
                </a>
                <button type="button" class="btn btn-submit-danger px-4 rounded-3" id="btnSimpanMuktamad" {{ $readOnly ? 'disabled' : '' }}>
                    <i class="bi bi-floppy me-1"></i>Simpan Keputusan
                </button>
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const tenderNo = "{{ $tenderParam }}";

    document.addEventListener('DOMContentLoaded', function () {
        const btnSimpanMuktamad = document.getElementById('btnSimpanMuktamad');
        if (btnSimpanMuktamad) {
            btnSimpanMuktamad.addEventListener('click', function () {
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

                btnSimpanMuktamad.disabled = true;
                btnSimpanMuktamad.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';

                fetch(`/penilaian-kewangan-kerja/${encodeURIComponent(tenderNo)}/borang/borang6/simpan-muktamad`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        chk_sah: 1
                    })
                })
                .then(res => res.json())
                .then(data => {
                    btnSimpanMuktamad.disabled = false;
                    btnSimpanMuktamad.innerHTML = '<i class="bi bi-floppy me-1"></i>Simpan Keputusan';

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berjaya Disimpan!',
                            text: data.message || 'Maklumat Borang 6 telah berjaya disahkan dan disimpan!',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#047857'
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
                            text: data.message || 'Gagal mengesahkan keputusan Borang 6.',
                            confirmButtonColor: '#dc2626'
                        });
                    }
                })
                .catch(err => {
                    btnSimpanMuktamad.disabled = false;
                    btnSimpanMuktamad.innerHTML = '<i class="bi bi-floppy me-1"></i>Simpan Keputusan';
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Ralat Sistem!',
                        text: 'Berlaku masalah semasa berhubung dengan pelayan.',
                        confirmButtonColor: '#dc2626'
                    });
                });
            });
        }
    });
</script>
@endsection
