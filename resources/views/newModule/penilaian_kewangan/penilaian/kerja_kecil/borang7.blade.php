@extends('layouts.v3.master')

@section('styles')
<style>
    :root {
        --sg-red: #dc2626;
        --sg-red-dark: #991b1b;
        --sg-red-light: #fef2f2;
    }

    .b7-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        background: #ffffff;
        overflow: hidden;
    }

    .b7-header-banner {
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

    // Standard checklist item UUID for Spesifikasi Komponen Mekanikal/Elektrikal
    $specChecklistItem = \Illuminate\Support\Facades\DB::table('standard_checklist_items')
        ->where('title', 'Spesifikasi Komponen Mekanikal/Elektrikal')
        ->first();
    $specUuid = $specChecklistItem->uuid ?? null;

    // Retrieve qualified participants from previous Borang (Borang 6)
    $qualifiedParticipants = $participants->filter(function($p) use ($b3VendorData, $b4VendorData, $b7VendorSummary, $b9VendorSummary) {
        $vId = $p->vendor_id;
        $b3Data = $b3VendorData[$vId] ?? null;
        $isModalCukup = ($b3Data && ($b3Data['mudah_cair_m'] ?? 0) >= 0);

        $b4Data = $b4VendorData[$vId] ?? null;
        $isPrestasiMemuaskan = ($b4Data['status_pematuhan'] ?? 1) === 1;

        $isBebanBerupaya = true;
        $isPengalamanMemenuhi = true;

        return $isModalCukup && $isPrestasiMemuaskan && $isBebanBerupaya && $isPengalamanMemenuhi;
    })->values();

    // Fallback if no participants filtered
    if ($qualifiedParticipants->isEmpty() && $participants->isNotEmpty()) {
        $qualifiedParticipants = $participants;
    }
@endphp

{{-- Top-Right Save Confirmation Toast Container --}}
<div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;"></div>

<div class="container-fluid px-0 py-2">

    {{-- Breadcrumb & Navigation Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none"><i class="bi bi-house-door me-1"></i>STOS</a></li>
                <li class="breadcrumb-item"><a href="{{ route('penilaianKewangan') }}" class="text-muted text-decoration-none">Penilaian Kewangan</a></li>
                <li class="breadcrumb-item"><a href="{{ $backToTenderUrl }}" class="text-muted text-decoration-none">Penilaian Kewangan (Kerja Kecil M&amp;E)</a></li>
                <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Borang 7</li>
            </ol>
        </nav>
        <a href="{{ $backToTenderUrl }}" class="btn btn-sm btn-sebelumnya d-inline-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Peringkat 2</span>
        </a>
    </div>

    {{-- Header Banner Card --}}
    <div class="b7-card mb-4">
        <div class="b7-header-banner d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-white px-2.5 py-1 rounded-pill small fw-semibold">Peringkat 2</span>
                    @if($readOnly ?? false)
                        <span class="badge bg-light text-dark px-2.5 py-1 rounded-pill small fw-semibold"><i class="bi bi-lock-fill me-1"></i>Mod Paparan Sahaja</span>
                    @endif
                </div>
                <h3 class="fw-bold mb-1 text-white" style="letter-spacing: -0.5px;">BORANG 7 - RINGKASAN KEPUTUSAN PENILAIAN TEKNIKAL</h3>
                <p class="text-white-50 mb-0 small">Penilaian keupayaan teknikal &amp; spesifikasi komponen bagi perolehan Kerja Kecil M&amp;E.</p>
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
    <div class="b7-card p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <div class="bg-primary-subtle p-2 rounded-2 me-3">
                    <i class="bi bi-tools text-primary fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Senarai Penilaian Teknikal (Spesifikasi Komponen M&amp;E)</h5>
                    <p class="text-secondary small mb-0">Penilaian dokumen spesifikasi komponen dan keputusan teknikal bagi petender yang layak dari Borang terdahulu.</p>
                </div>
            </div>
            <span class="section-badge-pill-primary ms-auto">
                <i class="bi bi-people me-1"></i>{{ count($qualifiedParticipants) }} Petender Layak
            </span>
        </div>

        <div class="table-modern-wrapper mb-4">
            <table class="table table-borang-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 130px;" class="text-center">Kod Pembekal</th>
                        <th style="min-width: 220px;" class="text-start ps-3">Nama Syarikat</th>
                        <th style="width: 200px;" class="text-center">Dokumen</th>
                        <th style="width: 170px;" class="text-center">Keputusan</th>
                        <th style="min-width: 240px;" class="text-start ps-3">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($qualifiedParticipants as $idx => $p)
                        @php
                            $vId = $p->vendor_id;
                            $kodPembekal = $p->kod_pembekal ?: ($p->ref_number ?: ('V' . str_pad($idx + 1, 3, '0', STR_PAD_LEFT)));
                            $vendorName = $p->vendor->name ?? $p->vendor->company_name ?? $p->name ?? ('Petender ' . ($idx + 1));
                            $rocNo = $p->vendor->registration ?? $p->vendor->registration_no ?? $p->vendor->roc_no ?? null;

                            // Retrieve uploaded document for status_process_id = 5 under Spesifikasi Komponen Mekanikal/Elektrikal
                            $vDocFile = \App\Models\TenderVendorDokumenFile::query()
                                ->where('tender_id', $tender->id)
                                ->where('vendor_id', $vId)
                                ->where(function($q) use ($specUuid) {
                                    if ($specUuid) {
                                        $q->orWhere('checklist_item_uuid', $specUuid);
                                    }
                                    $q->orWhere('section', 'like', '%spesifikasi%')
                                      ->orWhere('section', 'like', '%mekanikal%')
                                      ->orWhere('original_name', 'like', '%spesifikasi%')
                                      ->orWhere('original_name', 'like', '%mekanikal%');
                                })
                                ->first();

                            if (!$vDocFile) {
                                $vDocFile = \App\Models\TenderVendorDokumenFile::query()
                                    ->where('tender_id', $tender->id)
                                    ->where('vendor_id', $vId)
                                    ->first();
                            }

                            // Saved evaluation record
                            $evalRec = $evaluations[$vId] ?? null;
                            $payload = $evalRec ? (is_string($evalRec->payload) ? json_decode($evalRec->payload, true) : $evalRec->payload) : [];
                            
                            $keputusanVal = $payload['keputusan'] ?? ($evalRec ? ($evalRec->status_pematuhan === 1 ? 'Lulus' : ($evalRec->status_pematuhan === 0 ? 'Tidak Lulus' : '')) : '');
                            $catatanVal = $evalRec->catatan ?? $payload['catatan'] ?? '';
                        @endphp
                        <tr>
                            <td class="text-center font-monospace fw-bold text-dark">{{ $kodPembekal }}</td>
                            <td class="ps-3">
                                <div class="fw-bold text-dark">{{ $vendorName }}</div>
                                @if($rocNo)
                                    <span class="small text-muted font-monospace">ROC: {{ $rocNo }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($vDocFile)
                                    <a href="{{ route('tenderDokumen.download', $vDocFile->uuid) }}" target="_blank" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-2">
                                        <i class="bi bi-file-earmark-pdf fs-6"></i>
                                        <span class="text-truncate" style="max-width: 140px;" title="{{ $vDocFile->original_name ?: 'Dokumen Spesifikasi' }}">{{ $vDocFile->original_name ?: 'Dokumen Spesifikasi' }}</span>
                                    </a>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2.5 py-1 rounded-pill small">
                                        <i class="bi bi-file-earmark-x me-1"></i>Tiada Dokumen
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <select class="form-select form-select-sm sel-keputusan font-monospace fw-semibold" data-vendor-id="{{ $vId }}" {{ $readOnly ? 'disabled' : '' }}>
                                    <option value="">-- Pilih --</option>
                                    <option value="Lulus" {{ $keputusanVal === 'Lulus' ? 'selected' : '' }} class="text-success fw-bold">Lulus</option>
                                    <option value="Tidak Lulus" {{ $keputusanVal === 'Tidak Lulus' ? 'selected' : '' }} class="text-danger fw-bold">Tidak Lulus</option>
                                </select>
                            </td>
                            <td class="ps-3">
                                <textarea class="form-control form-control-sm txt-catatan" data-vendor-id="{{ $vId }}" rows="2" placeholder="Catatan (Wajib sekiranya Tidak Lulus)" {{ $readOnly ? 'disabled' : '' }}>{{ $catatanVal }}</textarea>
                                <div class="invalid-feedback err-catatan d-none" id="errCatatan-{{ $vId }}">
                                    Catatan wajib diisi sekiranya Keputusan adalah Tidak Lulus.
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <i class="bi bi-info-circle text-secondary display-6"></i>
                                    <span class="fw-semibold">Tiada petender yang layak / lulus dari Borang terdahulu.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Final Confirmation Box --}}
        <div class="confirmation-box">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="form-check">
                    <input class="form-check-input border-secondary" type="checkbox" id="chkSah" {{ $readOnly ? 'disabled' : '' }}>
                    <label class="form-check-label text-dark fw-semibold small" for="chkSah">
                        Saya mengesahkan bahawa ringkasan keputusan penilaian teknikal M&amp;E bagi Borang 7 ini telah disahkan dan lengkap.
                    </label>
                </div>
                <button type="button" id="btnSimpanMuktamad" class="btn btn-submit-danger px-4 py-2.5 rounded-3 d-inline-flex align-items-center gap-2" {{ $readOnly ? 'disabled' : '' }}>
                    <i class="bi bi-arrow-right-circle fs-5"></i>
                    <span>Simpan Keputusan</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tenderIdentifier = @json($tenderIdentifier);
        const readOnly = @json((bool)($readOnly ?? false));
        let debounceTimer = null;

        // Show green Bootstrap toast alert at top-right
        function showSaveToast(message) {
            const container = document.getElementById('toastContainer');
            if (!container) return;

            let toastEl = document.getElementById('saveToastNotification');
            if (!toastEl) {
                toastEl = document.createElement('div');
                toastEl.id = 'saveToastNotification';
                toastEl.className = 'toast align-items-center text-bg-success border-0 shadow-sm show';
                toastEl.setAttribute('role', 'alert');
                toastEl.setAttribute('aria-live', 'assertive');
                toastEl.setAttribute('aria-atomic', 'true');
                toastEl.innerHTML = `
                    <div class="d-flex">
                        <div class="toast-body fw-semibold d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill fs-5"></i>
                            <span id="saveToastText">Perubahan berjaya disimpan.</span>
                        </div>
                    </div>
                `;
                container.appendChild(toastEl);
            }

            document.getElementById('saveToastText').textContent = message || 'Perubahan berjaya disimpan.';

            toastEl.classList.add('show');
            if (toastEl._hideTimeout) clearTimeout(toastEl._hideTimeout);
            toastEl._hideTimeout = setTimeout(() => {
                toastEl.classList.remove('show');
            }, 2500);
        }

        // Perform AJAX auto-save
        function autoSaveEvaluation(vendorId) {
            if (readOnly) return;

            const selKeputusan = document.querySelector(`.sel-keputusan[data-vendor-id="${vendorId}"]`);
            const txtCatatan = document.querySelector(`.txt-catatan[data-vendor-id="${vendorId}"]`);
            const errDiv = document.getElementById(`errCatatan-${vendorId}`);

            const keputusan = selKeputusan ? selKeputusan.value : '';
            const catatan = txtCatatan ? txtCatatan.value.trim() : '';

            // Validation: If Tidak Lulus, Catatan is mandatory
            if (keputusan === 'Tidak Lulus' && catatan === '') {
                if (txtCatatan) txtCatatan.classList.add('is-invalid');
                if (errDiv) {
                    errDiv.classList.remove('d-none');
                    errDiv.style.display = 'block';
                }
                return;
            } else {
                if (txtCatatan) txtCatatan.classList.remove('is-invalid');
                if (errDiv) {
                    errDiv.classList.add('d-none');
                    errDiv.style.display = 'none';
                }
            }

            fetch('{{ route('penilaianKewanganKerja.borang7.simpanPenilaian', $tenderIdentifier) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    vendor_id: vendorId,
                    keputusan: keputusan,
                    catatan: catatan
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showSaveToast(data.message || 'Perubahan berjaya disimpan.');
                } else {
                    if (txtCatatan && data.message && data.message.includes('Catatan')) {
                        txtCatatan.classList.add('is-invalid');
                        if (errDiv) {
                            errDiv.textContent = data.message;
                            errDiv.classList.remove('d-none');
                            errDiv.style.display = 'block';
                        }
                    } else {
                        Swal.fire({ icon: 'error', title: 'Ralat!', text: data.message });
                    }
                }
            })
            .catch(err => {
                console.error('Ralat simpan penilaian Borang 7:', err);
            });
        }

        // Event listener for Keputusan dropdown change
        document.querySelectorAll('.sel-keputusan').forEach(select => {
            select.addEventListener('change', function() {
                const vendorId = this.getAttribute('data-vendor-id');
                autoSaveEvaluation(vendorId);
            });
        });

        // Event listener for Catatan textarea debounced input (600ms)
        document.querySelectorAll('.txt-catatan').forEach(textarea => {
            textarea.addEventListener('input', function() {
                const vendorId = this.getAttribute('data-vendor-id');
                if (debounceTimer) clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    autoSaveEvaluation(vendorId);
                }, 600);
            });
        });

        // Simpan Muktamad button handler
        const btnSimpanMuktamad = document.getElementById('btnSimpanMuktamad');
        if (btnSimpanMuktamad) {
            btnSimpanMuktamad.addEventListener('click', function() {
                const chkSah = document.getElementById('chkSah');
                if (chkSah && !chkSah.checked) {
                    Swal.fire({ icon: 'warning', title: 'Pengesahan Diperlukan', text: 'Sila tandakan kotak pengesahan terlebih dahulu.' });
                    return;
                }

                // Check all Tidak Lulus have Catatan before finalizing
                let hasValidationError = false;
                document.querySelectorAll('.sel-keputusan').forEach(select => {
                    const vendorId = select.getAttribute('data-vendor-id');
                    const txtCatatan = document.querySelector(`.txt-catatan[data-vendor-id="${vendorId}"]`);
                    const errDiv = document.getElementById(`errCatatan-${vendorId}`);
                    const keputusan = select.value;
                    const catatan = txtCatatan ? txtCatatan.value.trim() : '';

                    if (keputusan === 'Tidak Lulus' && catatan === '') {
                        hasValidationError = true;
                        if (txtCatatan) txtCatatan.classList.add('is-invalid');
                        if (errDiv) {
                            errDiv.classList.remove('d-none');
                            errDiv.style.display = 'block';
                        }
                    }
                });

                if (hasValidationError) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Maklumat Tidak Lengkap',
                        text: 'Sila pastikan Catatan diisi bagi setiap petender yang berstatus Tidak Lulus.'
                    });
                    return;
                }

                btnSimpanMuktamad.disabled = true;
                btnSimpanMuktamad.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

                fetch('{{ route('penilaianKewanganKerja.borang7.simpanMuktamad', $tenderIdentifier) }}', {
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
                    btnSimpanMuktamad.innerHTML = '<i class="bi bi-arrow-right-circle fs-5 me-1"></i>Simpan &amp; Teruskan Ke Borang 8';

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Borang 7 Disahkan!',
                            text: data.message || 'Maklumat Borang 7 telah berjaya disimpan. Sila teruskan ke Borang 8.',
                            confirmButtonText: 'Seterusnya (Borang 8)',
                            confirmButtonColor: '#dc2626'
                        }).then(() => {
                            window.location.href = data.redirect || '{{ $backToTenderUrl }}';
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Ralat!', text: data.message });
                    }
                })
                .catch(err => {
                    btnSimpanMuktamad.disabled = false;
                    btnSimpanMuktamad.innerHTML = '<i class="bi bi-arrow-right-circle fs-5 me-1"></i>Simpan &amp; Teruskan Ke Borang 8';
                    Swal.fire({ icon: 'error', title: 'Ralat Sistem!', text: 'Masalah berhubung dengan pelayan.' });
                });
            });
        }
    });
</script>
@endsection
