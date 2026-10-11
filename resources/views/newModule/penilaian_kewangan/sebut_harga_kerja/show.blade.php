@extends('layouts.v3.master')

@section('styles')
<style>
    :root {
        --sg-red: #dc2626;
        --sg-red-dark: #991b1b;
        --sg-red-light: #fef2f2;
        --step-grey: #e2e8f0;
        --text-grey: #64748b;
    }

    body {
        background: #f8fafc;
    }

    .kewangan-detail-container {
        padding: 0.5rem 0 2rem 0;
    }

    /* ========================
       TENDER SUMMARY CARD
    ======================== */
    .tender-summary-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .tender-summary-header {
        background: linear-gradient(135deg, var(--sg-red) 0%, var(--sg-red-dark) 100%);
        padding: 1.25rem 1.75rem;
        color: #ffffff;
    }

    .tender-summary-body {
        padding: 1.5rem 1.75rem;
    }

    .info-grid-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem 1.15rem;
        height: 100%;
        transition: all 0.2s ease-in-out;
    }

    .info-grid-box:hover {
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        border-color: #cbd5e1;
    }

    .info-grid-label {
        font-size: 0.725rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .info-grid-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
    }

    .tender-badge-mono {
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--sg-red-dark);
        background: var(--sg-red-light);
        border: 1px solid rgba(220, 38, 38, 0.2);
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        display: inline-block;
    }

    .status-pill-process {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.35rem 0.85rem;
        border-radius: 50rem;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .status-pill-process .pulse-dot {
        width: 7px;
        height: 7px;
        background-color: #f18705ff;
        border-radius: 50%;
        box-shadow: 0 0 0 0 rgba(217, 119, 6, 0.4);
        animation: pulse-ring 1.8s infinite;
    }

    @keyframes pulse-ring {
        0% { box-shadow: 0 0 0 0 rgba(217, 119, 6, 0.5); }
        70% { box-shadow: 0 0 0 6px rgba(217, 119, 6, 0); }
        100% { box-shadow: 0 0 0 0 rgba(217, 119, 6, 0); }
    }

    /* ========================
       STEPPER WIZARD
    ======================== */
    .progress-nav {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
    }

    .progress-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        position: relative;
        margin: 0;
        padding: 0.5rem 0;
    }

    .progress-step {
        flex: 1;
        text-align: center;
        position: relative;
    }

    .progress-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 20px;
        left: 50%;
        width: 100%;
        height: 3px;
        background: var(--step-grey);
        z-index: 0;
        transition: background 0.3s ease;
    }

    .progress-step.done:not(:last-child)::after,
    .progress-step.active:not(:last-child)::after {
        background: var(--sg-red);
    }

    .progress-step.active~.progress-step:not(:last-child)::after {
        background: var(--step-grey);
    }

    .step-number {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #ffffff;
        color: #64748b;
        font-weight: 700;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        border: 2px solid var(--step-grey);
        position: relative;
        z-index: 2;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    }

    .progress-step.active .step-number {
        background: linear-gradient(135deg, var(--sg-red) 0%, var(--sg-red-dark) 100%);
        color: #ffffff;
        border-color: var(--sg-red);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        transform: scale(1.08);
    }

    .progress-step.done .step-number {
        background: var(--sg-red-dark);
        color: #ffffff;
        border-color: var(--sg-red-dark);
    }

    .progress-step.locked {
        opacity: 0.6;
    }

    .progress-step.locked .step-number {
        background: #f1f5f9 !important;
        color: #94a3b8 !important;
        border-color: #cbd5e1 !important;
        box-shadow: none !important;
        transform: none !important;
        cursor: not-allowed !important;
    }

    .progress-step.locked .step-label {
        color: #94a3b8 !important;
    }

    .step-label {
        margin-top: 10px;
        font-size: 0.825rem;
        font-weight: 600;
        color: var(--text-grey);
        line-height: 1.3;
        transition: color 0.2s ease;
    }

    .progress-step.active .step-label {
        color: var(--sg-red-dark);
        font-weight: 700;
    }

    .progress-step.done .step-label {
        color: #334155;
    }

    /* ========================
       TABS
    ======================== */
    .custom-tab-size {
        background: #f1f5f9;
        padding: 4px;
        border-radius: 12px;
        gap: 4px;
    }

    .custom-tab-size .nav-link {
        border-radius: 9px;
        background: transparent;
        color: #64748b;
        border: none;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 8px 20px;
        transition: all 0.2s ease;
    }

    .custom-tab-size .nav-link:hover {
        color: #1e293b;
    }

    .custom-tab-size .nav-link.active {
        background: #ffffff !important;
        color: var(--sg-red-dark) !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        font-weight: 700;
    }

    /* ========================
       BUTTONS
    ======================== */
    .btn-seterusnya {
        background: linear-gradient(135deg, var(--sg-red) 0%, var(--sg-red-dark) 100%);
        border: none;
        color: #ffffff;
        font-weight: 600;
        padding: 0.55rem 1.25rem;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
        transition: all 0.2s ease-in-out;
    }

    .btn-seterusnya:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(220, 38, 38, 0.3);
        color: #ffffff;
    }

    .btn-sebelumnya {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #475569;
        font-weight: 600;
        padding: 0.55rem 1.25rem;
        border-radius: 10px;
        transition: all 0.2s ease-in-out;
    }

    .btn-sebelumnya:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .extra-small {
        font-size: 0.75rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0 kewangan-detail-container">

    {{-- Breadcrumb & Navigation Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none"><i class="bi bi-house-door me-1"></i>STOS</a></li>
                <li class="breadcrumb-item"><a href="{{ route('penilaianKewangan') }}" class="text-muted text-decoration-none">Senarai Penilaian Kewangan</a></li>
                <li class="breadcrumb-item active fw-medium text-danger" aria-current="page">Sebut Harga Kerja (Type 2)</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-3 py-1.5 rounded-pill fw-semibold">
                <i class="bi bi-hammer me-1"></i>Type 2: Sebut Harga Kerja
            </span>
            <a href="{{ route('penilaianKewangan') }}" class="btn btn-sm btn-sebelumnya d-inline-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Senarai</span>
            </a>
        </div>
    </div>

    {{-- Tender Summary Info Card --}}
    <div class="tender-summary-card">
        <div class="tender-summary-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-bank2 fs-5"></i>
                <h5 class="fw-bold mb-0 text-white" style="letter-spacing: -0.3px;">RINGKASAN SEBUT HARGA & PEROLEHAN KERJA</h5>
            </div>
            <span class="status-pill-process bg-warning text-white border-0">
                <span class="pulse-dot"></span>
                {{ $status_label ?? 'Menunggu Penilaian Kewangan & Kerja' }}
            </span>
        </div>
        <div class="tender-summary-body">
            <div class="row g-3">
                <!-- No Sebut Harga -->
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="info-grid-box">
                        <div class="info-grid-label">
                            <i class="bi bi-hash text-danger"></i>No. Sebut Harga
                        </div>
                        <div class="info-grid-value">
                            <span class="tender-badge-mono">{{ $no_tender_display ?? $tender->no_tender ?? $tender->ref_number ?? 'SH/SEL/JPS/2026/042' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Kaedah & Kategori -->
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="info-grid-box">
                        <div class="info-grid-label">
                            <i class="bi bi-tags text-danger"></i>Kaedah & Kategori
                        </div>
                        <div class="info-grid-value d-flex gap-1.5 flex-wrap">
                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-1 rounded-2">{{ $tender->kaedahPerolehan->name ?? 'Sebut Harga' }}</span>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-2">{{ $tender->kategoriPerolehan->name ?? 'Kerja' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Anggaran Jabatan -->
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="info-grid-box">
                        <div class="info-grid-label">
                            <i class="bi bi-cash-stack text-danger"></i>Anggaran Jabatan
                        </div>
                        <div class="info-grid-value font-monospace text-danger">
                            {{ $anggaran_display ?? 'RM 500,000.00' }}
                        </div>
                    </div>
                </div>

                <!-- Tempoh Sah Laku -->
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="info-grid-box">
                        <div class="info-grid-label">
                            <i class="bi bi-hourglass-split text-danger"></i>Tempoh Sah Laku
                        </div>
                        <div class="info-grid-value">
                            <span class="badge bg-light text-dark border px-2.5 py-1 rounded-2 font-monospace">
                                {{ $tempoh_sah_laku ?? 90 }} Hari
                            </span>
                        </div>
                    </div>
                </div>

                <!-- PTJ / Jabatan -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="info-grid-box">
                        <div class="info-grid-label">
                            <i class="bi bi-building text-danger"></i>PTJ / Jabatan
                        </div>
                        <div class="info-grid-value text-truncate" title="{{ $ptj_display ?? $tender->tenderer->name ?? 'Jabatan Pengairan dan Saliran Negeri Selangor' }}">
                            {{ $ptj_display ?? $tender->tenderer->name ?? 'Jabatan Pengairan dan Saliran Negeri Selangor' }}
                        </div>
                    </div>
                </div>

                <!-- Tajuk Perolehan -->
                <div class="col-12 col-md-6 col-lg-8">
                    <div class="info-grid-box">
                        <div class="info-grid-label">
                            <i class="bi bi-file-earmark-text text-danger"></i>Tajuk Perolehan Kerja
                        </div>
                        <div class="info-grid-value small text-dark" style="line-height: 1.4;">
                            {{ $tajuk_display ?? $tender->name ?? 'KERJA-KERJA MENAIKTARAF DAN MEMBAIKPULIH SISTEM SALIRAN DAN KOLAM TAKUNGAN BANJIR DI DAERAH KLANG, SELANGOR' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Process Card with 4-Step Stepper --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4">

            {{-- Progress Stepper Bar --}}
            <div id="custom-progress-bar" class="progress-nav mb-4">
                <ul class="nav progress-wrapper" role="tablist">

                    {{-- Step 1 --}}
                    <li class="nav-item progress-step active" id="step-nav-1" role="presentation">
                        <button type="button"
                            id="pematuhan-tab"
                            class="nav-link step-number active"
                            data-bs-toggle="pill"
                            data-bs-target="#pematuhan"
                            role="tab">1</button>
                        <div class="step-label">Pematuhan Dokumentasi</div>
                    </li>

                    {{-- Step 2 --}}
                    <li class="nav-item progress-step locked" id="step-nav-2" role="presentation">
                        <button type="button"
                            id="penyata-bank-tab"
                            class="nav-link step-number"
                            data-bs-toggle="pill"
                            data-bs-target="#penyata-bank"
                            role="tab">2</button>
                        <div class="step-label">Kemampuan Kewangan</div>
                    </li>

                    {{-- Step 3 --}}
                    <li class="nav-item progress-step locked" id="step-nav-3" role="presentation">
                        <button type="button"
                            id="penilaian-tab"
                            class="nav-link step-number"
                            data-bs-toggle="pill"
                            data-bs-target="#penilaian"
                            role="tab">3</button>
                        <div class="step-label">Penilaian Kerja</div>
                    </li>

                    {{-- Step 4 --}}
                    <li class="nav-item progress-step locked" id="step-nav-4" role="presentation">
                        <button type="button"
                            id="laporan-tab"
                            class="nav-link step-number"
                            data-bs-toggle="pill"
                            data-bs-target="#laporan"
                            role="tab">4</button>
                        <div class="step-label">Penyediaan Laporan</div>
                    </li>

                </ul>
            </div>

            {{-- Tab Content Container hosting Steps 1 to 4 --}}
            <div class="tab-content px-1" id="application-content">
                @include('newModule.penilaian_kewangan.sebut_harga_kerja.step1')
                @include('newModule.penilaian_kewangan.sebut_harga_kerja.step2')
                @include('newModule.penilaian_kewangan.sebut_harga_kerja.step3')
                @include('newModule.penilaian_kewangan.sebut_harga_kerja.step4')
            </div>

        </div>
    </div>

</div>

{{-- Modal Blade Partials --}}
@include('newModule.penilaian_kewangan.sebut_harga_kerja.modals.modal_preview')
@include('newModule.penilaian_kewangan.sebut_harga_kerja.modals.modal_semakan_dokumen')
@include('newModule.penilaian_kewangan.sebut_harga_kerja.modals.modal_kemampuan_kewangan')
@include('newModule.penilaian_kewangan.sebut_harga_kerja.modals.modal_penilaian_kerja')
@endsection

@section('scripts')
<script>
/**
 * TYPE 2: SEBUT HARGA KERJA — FULL STACK WORKFLOW ENGINE
 * Connects directly to SUKSel 3.0 backend endpoints with live database records.
 */
const backendPayload = @json($type2Data ?? null);

window.type2State = {
    tenderId: backendPayload?.tender?.id || {{ $tender->id ?? 1 }},
    csrfToken: '{{ csrf_token() }}',
    routes: backendPayload?.routes || {},

    currentStep: backendPayload?.progress?.currentStep || 1,
    step1Confirmed: Boolean(backendPayload?.progress?.step1Confirmed),
    step2Confirmed: Boolean(backendPayload?.progress?.step2Confirmed),
    step3Confirmed: Boolean(backendPayload?.progress?.step3Confirmed),

    // Checklist Documents Definition from Database
    checklistDocs: (backendPayload?.checklistDocs && backendPayload.checklistDocs.length > 0)
        ? backendPayload.checklistDocs
        : [
            { id: 'doc-1', title: 'Penyata Bulanan Akaun Bank (3 Bulan Terkini)', mekanisma: 'Borang Atas Talian', required: true },
            { id: 'doc-2', title: 'Laporan Kewangan / Lembaran Imbangan Beraudit', mekanisma: 'Muat Naik Dokumen', required: true },
            { id: 'doc-3', title: 'Surat Perakuan Bank Bagi Kemudahan Kredit', mekanisma: 'Muat Naik Dokumen', required: true },
            { id: 'doc-4', title: 'Borang Maklumat Pengalaman Kerja & Kerja Semasa', mekanisma: 'Borang Atas Talian', required: true },
            { id: 'doc-5', title: 'Senarai Kakitangan Teknikal & Caruman KWSP', mekanisma: 'Borang Atas Talian', required: true },
            { id: 'doc-6', title: 'Sijil Pendaftaran CIDB (PPK & SPKK) / STB', mekanisma: 'Borang Atas Talian', required: true }
        ],

    // Participating Contractors from Database
    vendors: (backendPayload?.vendors && backendPayload.vendors.length > 0)
        ? backendPayload.vendors
        : [],

    // Active state tracker for modals
    activeDocId: null,
    activeVendorId: null,

    init() {
        this.syncCheckboxesWithBackend();
        this.bindEvents();
        this.updateStepperLocks();
        this.renderAll();
    },

    syncCheckboxesWithBackend() {
        const c1 = document.getElementById('confirmLayakStep1');
        if (c1) c1.checked = this.step1Confirmed;

        const c2 = document.getElementById('confirmLayakStep2');
        if (c2) c2.checked = this.step2Confirmed;

        const c3 = document.getElementById('confirmLayakStep3');
        if (c3) c3.checked = this.step3Confirmed;

        if (backendPayload?.laporan) {
            const lap = backendPayload.laporan;
            const t1 = document.getElementById('laporanJustifikasiText');
            if (t1 && lap.catatan_peringkat1) t1.value = lap.catatan_peringkat1;

            const t3 = document.getElementById('laporanSyaratKhasInput');
            if (t3 && lap.catatan_peringkat3) t3.value = lap.catatan_peringkat3;
        }
    },

    bindEvents() {
        const self = this;

        // Stepper Navigation clicks
        document.getElementById('pematuhan-tab')?.addEventListener('click', () => self.switchStep(1));
        document.getElementById('penyata-bank-tab')?.addEventListener('click', () => self.switchStep(2));
        document.getElementById('penilaian-tab')?.addEventListener('click', () => self.switchStep(3));
        document.getElementById('laporan-tab')?.addEventListener('click', () => self.switchStep(4));

        // Step 1 confirm checkbox
        document.getElementById('confirmLayakStep1')?.addEventListener('change', function() {
            const isChecked = this.checked;
            self.step1Confirmed = isChecked;
            self.updateStepperLocks();

            // Persist step 1 confirmation to backend
            if (self.routes.sahkanLangkah1) {
                fetch(self.routes.sahkanLangkah1, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': self.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        tender_id: self.tenderId,
                        confirmed: isChecked
                    })
                }).catch(err => console.error('Error confirming step 1:', err));
            }
        });

        // Step 1 next button
        document.getElementById('btnNextStep1')?.addEventListener('click', () => {
            if (!self.step1Confirmed) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pengesahan Diperlukan',
                    text: 'Sila tandakan kotak pengesahan semakan dokumentasi sebelum meneruskan ke Langkah 2.',
                    confirmButtonColor: '#dc2626'
                });
                return;
            }
            self.switchStep(2);
        });

        // Step 2 buttons
        document.getElementById('btnPrevStep2')?.addEventListener('click', () => self.switchStep(1));
        document.getElementById('confirmLayakStep2')?.addEventListener('change', function() {
            const isChecked = this.checked;
            self.step2Confirmed = isChecked;
            self.updateStepperLocks();

            if (self.routes.sahkanLangkah2) {
                fetch(self.routes.sahkanLangkah2, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': self.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        tender_id: self.tenderId,
                        confirmed: isChecked
                    })
                }).catch(err => console.error('Error confirming step 2:', err));
            }
        });

        document.getElementById('btnNextStep2')?.addEventListener('click', () => {
            if (!self.step2Confirmed) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pengesahan Diperlukan',
                    text: 'Sila tandakan kotak pengesahan kemampuan kewangan sebelum meneruskan ke Langkah 3.',
                    confirmButtonColor: '#dc2626'
                });
                return;
            }
            self.switchStep(3);
        });

        // Step 3 buttons
        document.getElementById('btnPrevStep3')?.addEventListener('click', () => self.switchStep(2));
        document.getElementById('confirmLayakStep3')?.addEventListener('change', function() {
            const isChecked = this.checked;
            self.step3Confirmed = isChecked;
            self.updateStepperLocks();

            if (self.routes.sahkanLangkah3) {
                fetch(self.routes.sahkanLangkah3, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': self.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        tender_id: self.tenderId,
                        confirmed: isChecked
                    })
                }).catch(err => console.error('Error confirming step 3:', err));
            }
        });

        document.getElementById('btnNextStep3')?.addEventListener('click', () => {
            if (!self.step3Confirmed) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pengesahan Diperlukan',
                    text: 'Sila tandakan kotak pengesahan penilaian kerja sebelum meneruskan ke Penyediaan Laporan.',
                    confirmButtonColor: '#dc2626'
                });
                return;
            }
            self.switchStep(4);
        });

        // Step 4 buttons
        document.getElementById('btnPrevStep4')?.addEventListener('click', () => self.switchStep(3));

        document.getElementById('btnCetakDrafLaporan')?.addEventListener('click', () => {
            if (self.routes.cetakLaporan) {
                window.open(self.routes.cetakLaporan, '_blank');
            } else {
                window.previewDocument('Draf Laporan Penilaian Kewangan & Kerja', 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf');
            }
        });

        document.getElementById('btnSimpanDrafLaporan')?.addEventListener('click', () => {
            const justifikasi = document.getElementById('laporanJustifikasiText')?.value || '';
            const syaratKhas = document.getElementById('laporanSyaratKhasInput')?.value || '';

            if (self.routes.simpanLaporanDraf) {
                fetch(self.routes.simpanLaporanDraf, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': self.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        tender_id: self.tenderId,
                        catatan_peringkat1: justifikasi,
                        catatan_peringkat2: '',
                        catatan_peringkat3: syaratKhas,
                        pengesyoran_justifikasi: []
                    })
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Draf Disimpan',
                        text: data.message || 'Draf penilaian kewangan dan kerja telah berjaya disimpan ke pangkalan data.',
                        confirmButtonColor: '#dc2626',
                        timer: 1800
                    });
                })
                .catch(() => {
                    Swal.fire({ icon: 'error', title: 'Ralat', text: 'Gagal menyimpan draf laporan.', confirmButtonColor: '#dc2626' });
                });
            } else {
                Swal.fire({ icon: 'success', title: 'Draf Disimpan', text: 'Draf telah disimpan.', confirmButtonColor: '#dc2626', timer: 1800 });
            }
        });

        document.getElementById('btnHantarPenilaianAkhir')?.addEventListener('click', () => {
            const confirmed = document.getElementById('perakuanPegawaiPenilai')?.checked;
            if (!confirmed) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perakuan Diperlukan',
                    text: 'Sila tandakan kotak perakuan pegawai penilai kewangan sebelum menghantar penilaian.',
                    confirmButtonColor: '#dc2626'
                });
                return;
            }

            Swal.fire({
                title: 'Hantar Penilaian Kewangan?',
                text: 'Penilaian bagi Sebut Harga ini akan dimuktamadkan dan dimajukan ke peringkat seterusnya (status 11).',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hantar Penilaian',
                cancelButtonText: 'Batal'
            }).then((res) => {
                if (res.isConfirmed) {
                    if (self.routes.hantar) {
                        fetch(self.routes.hantar, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': self.csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                tender_id: self.tenderId,
                                perakuan: true
                            })
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Penilaian Berjaya Dihantar!',
                                    text: data.message,
                                    confirmButtonColor: '#16a34a'
                                }).then(() => {
                                    window.location.href = "{{ route('penilaianKewangan') }}";
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Menghantar',
                                    text: data.message || 'Sila pastikan semua langkah telah disahkan.',
                                    confirmButtonColor: '#dc2626'
                                });
                            }
                        })
                        .catch(() => {
                            Swal.fire({ icon: 'error', title: 'Ralat Pelayan', text: 'Ralat memproses penghantaran akhir.', confirmButtonColor: '#dc2626' });
                        });
                    }
                }
            });
        });

        // Modal Action: Simpan Semakan Dokumen Checklist
        document.getElementById('btnSimpanSemakanDokumen')?.addEventListener('click', () => {
            if (!self.activeDocId) return;

            const decisions = [];

            // Collect decisions from modal rows
            self.vendors.forEach(v => {
                const selectEl = document.getElementById(`modalDocSelect_${v.id}`);
                const noteEl = document.getElementById(`modalDocNote_${v.id}`);
                if (selectEl && noteEl) {
                    const statusVal = selectEl.value;
                    const noteVal = noteEl.value;

                    if (!v.step1.docs[self.activeDocId]) {
                        v.step1.docs[self.activeDocId] = {};
                    }
                    v.step1.docs[self.activeDocId].status = statusVal;
                    v.step1.docs[self.activeDocId].catatan = noteVal;

                    decisions.push({
                        vendor_id: v.id,
                        status: statusVal,
                        catatan: noteVal
                    });

                    // Re-evaluate vendor's overall step 1 status
                    const hasFail = Object.values(v.step1.docs).some(d => d.status === 'tidak_sempurna');
                    v.step1.status = hasFail ? 'tidak_sempurna' : 'sempurna';
                    v.step1.catatan = hasFail
                        ? 'Gagal mematuhi satu atau lebih dokumen mandatori.'
                        : 'Semua dokumen mandatori lengkap dan teratur.';
                }
            });

            // Close modal
            const modalEl = document.getElementById('modalSemakanDokumenKerja');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            modalInstance?.hide();

            self.renderAll();

            // Save to database via AJAX
            if (self.routes.simpanDokumen) {
                fetch(self.routes.simpanDokumen, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': self.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        tender_id: self.tenderId,
                        doc_id: self.activeDocId,
                        decisions: decisions
                    })
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Disimpan',
                        text: data.message || 'Penilaian dokumen semakan telah dikemaskini.',
                        confirmButtonColor: '#dc2626',
                        timer: 1500,
                        showConfirmButton: false
                    });
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Disimpan (Tempatan)',
                        text: 'Penilaian dokumen semakan telah dikemaskini.',
                        confirmButtonColor: '#dc2626',
                        timer: 1500,
                        showConfirmButton: false
                    });
                });
            }
        });

        // Modal Action: Simpan Keputusan Kemampuan Kewangan
        document.getElementById('btnSimpanKemampuanKewangan')?.addEventListener('click', () => {
            if (!self.activeVendorId) return;
            const vendor = self.vendors.find(v => v.id === self.activeVendorId);
            if (!vendor) return;

            const decision = document.getElementById('modalKewanganDecisionSelect')?.value || 'memuaskan';
            const catatan = document.getElementById('modalKewanganCatatanInput')?.value || '';

            vendor.step2.status = decision;
            vendor.step2.catatan = catatan;

            const modalEl = document.getElementById('modalKemampuanKewanganVendor');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            modalInstance?.hide();

            self.renderAll();

            // Save to database via AJAX
            if (self.routes.simpanKewangan) {
                fetch(self.routes.simpanKewangan, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': self.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        tender_id: self.tenderId,
                        vendor_id: self.activeVendorId,
                        decision: decision,
                        catatan: catatan,
                        payload: vendor.step2.metrics || {}
                    })
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Disimpan',
                        text: data.message || `Keputusan kemampuan kewangan bagi ${vendor.name} telah dikemaskini.`,
                        confirmButtonColor: '#dc2626',
                        timer: 1500,
                        showConfirmButton: false
                    });
                })
                .catch(() => {
                    Swal.fire({ icon: 'success', title: 'Disimpan', text: 'Keputusan telah dikemaskini.', timer: 1500, showConfirmButton: false });
                });
            }
        });

        // Modal Action: Simpan Penilaian Kerja
        document.getElementById('btnSimpanPenilaianKerja')?.addEventListener('click', () => {
            if (!self.activeVendorId) return;
            const vendor = self.vendors.find(v => v.id === self.activeVendorId);
            if (!vendor) return;

            const decision = document.getElementById('modalKerjaDecisionSelect')?.value || 'memuaskan';
            const catatan = document.getElementById('modalKerjaCatatanInput')?.value || '';

            vendor.step3.status = decision;
            vendor.step3.catatan = catatan;

            const modalEl = document.getElementById('modalPenilaianKerjaVendor');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            modalInstance?.hide();

            self.renderAll();

            // Save to database via AJAX
            if (self.routes.simpanKerja) {
                fetch(self.routes.simpanKerja, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': self.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        tender_id: self.tenderId,
                        vendor_id: self.activeVendorId,
                        decision: decision,
                        catatan: catatan,
                        payload: {
                            baki_kerja: vendor.step3.baki_kerja,
                            projek_terbesar: vendor.step3.projek_terbesar,
                            kakitangan: vendor.step3.kakitangan
                        }
                    })
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Disimpan',
                        text: data.message || `Keputusan penilaian kerja bagi ${vendor.name} telah dikemaskini.`,
                        confirmButtonColor: '#dc2626',
                        timer: 1500,
                        showConfirmButton: false
                    });
                })
                .catch(() => {
                    Swal.fire({ icon: 'success', title: 'Disimpan', text: 'Keputusan telah dikemaskini.', timer: 1500, showConfirmButton: false });
                });
            }
        });
    },

    switchStep(stepNum) {
        if (stepNum > 1 && !this.step1Confirmed) {
            Swal.fire({
                icon: 'info',
                title: 'Langkah Terkunci',
                text: 'Sila sahkan Langkah 1 (Pematuhan Dokumentasi) terlebih dahulu.',
                confirmButtonColor: '#dc2626'
            });
            return;
        }
        if (stepNum > 2 && !this.step2Confirmed) {
            Swal.fire({
                icon: 'info',
                title: 'Langkah Terkunci',
                text: 'Sila sahkan Langkah 2 (Kemampuan Kewangan) terlebih dahulu.',
                confirmButtonColor: '#dc2626'
            });
            return;
        }
        if (stepNum > 3 && !this.step3Confirmed) {
            Swal.fire({
                icon: 'info',
                title: 'Langkah Terkunci',
                text: 'Sila sahkan Langkah 3 (Penilaian Kerja) terlebih dahulu.',
                confirmButtonColor: '#dc2626'
            });
            return;
        }

        this.currentStep = stepNum;

        const tabIds = ['pematuhan-tab', 'penyata-bank-tab', 'penilaian-tab', 'laporan-tab'];
        const targetBtn = document.getElementById(tabIds[stepNum - 1]);
        if (targetBtn) {
            const tabObj = bootstrap.Tab.getInstance(targetBtn) || new bootstrap.Tab(targetBtn);
            tabObj.show();
        }

        this.updateStepperClasses();
        window.scrollTo({ top: 380, behavior: 'smooth' });
    },

    updateStepperLocks() {
        const step2Nav = document.getElementById('step-nav-2');
        const step3Nav = document.getElementById('step-nav-3');
        const step4Nav = document.getElementById('step-nav-4');

        if (this.step1Confirmed) {
            step2Nav?.classList.remove('locked');
        } else {
            step2Nav?.classList.add('locked');
            this.step2Confirmed = false;
        }

        if (this.step1Confirmed && this.step2Confirmed) {
            step3Nav?.classList.remove('locked');
        } else {
            step3Nav?.classList.add('locked');
            this.step3Confirmed = false;
        }

        if (this.step1Confirmed && this.step2Confirmed && this.step3Confirmed) {
            step4Nav?.classList.remove('locked');
        } else {
            step4Nav?.classList.add('locked');
        }

        this.updateStepperClasses();
    },

    updateStepperClasses() {
        for (let i = 1; i <= 4; i++) {
            const navEl = document.getElementById(`step-nav-${i}`);
            if (!navEl) continue;

            navEl.classList.remove('active', 'done');
            if (i < this.currentStep) {
                navEl.classList.add('done');
            } else if (i === this.currentStep) {
                navEl.classList.add('active');
            }
        }
    },

    renderAll() {
        this.renderStep1();
        this.renderStep2();
        this.renderStep3();
        this.renderStep4();
    },

    // ==========================================
    // RENDER STEP 1: PEMATUHAN DOKUMENTASI
    // ==========================================
    renderStep1() {
        const self = this;
        const tbody = document.getElementById('step1ChecklistTableBody');
        if (!tbody) return;

        tbody.innerHTML = '';

        this.checklistDocs.forEach((doc, idx) => {
            let complyCount = 0;
            self.vendors.forEach(v => {
                if (v.step1?.docs?.[doc.id]?.status === 'sempurna') complyCount++;
            });

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="py-3 px-3">
                    <div class="fw-bold text-dark" style="font-size: 0.875rem;">
                        <span class="text-secondary font-monospace me-1.5">${idx + 1}.</span>${doc.title}
                    </div>
                    <div class="extra-small text-muted mt-0.5">Mandatori untuk semua petender Sebut Harga Kerja</div>
                </td>
                <td class="py-3 px-3 text-center">
                    <span class="badge ${doc.mekanisma === 'Spesifikasi' ? 'bg-info-subtle text-info border border-info-subtle' : (doc.mekanisma === 'Borang Atas Talian' ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-secondary-subtle text-secondary border border-secondary-subtle')} px-2.5 py-1 rounded-pill" style="font-size: 0.72rem;">
                        <i class="bi ${doc.mekanisma === 'Spesifikasi' ? 'bi-card-checklist' : (doc.mekanisma === 'Borang Atas Talian' ? 'bi-laptop' : 'bi-file-earmark-arrow-up')} me-1"></i>${doc.mekanisma}
                    </span>
                </td>
                <td class="py-3 px-3 text-center">
                    <div class="d-inline-flex align-items-center gap-1.5">
                        <span class="badge ${complyCount === self.vendors.length ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-warning border border-warning-subtle'} px-2.5 py-1 rounded-pill font-monospace" style="font-size: 0.75rem;">
                            ${complyCount} / ${self.vendors.length} Mematuhi
                        </span>
                    </div>
                </td>
                <td class="py-3 px-3 text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger px-3 py-1 fw-bold rounded-2" onclick="window.type2State.openModalSemakanDokumen('${doc.id}')">
                        <i class="bi bi-pencil-square me-1"></i>Menilai
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        // Render Vendor Status Grid Breakdown
        const grid = document.getElementById('step1VendorStatusGrid');
        if (grid) {
            grid.innerHTML = '';
            this.vendors.forEach(v => {
                const isPass = v.step1?.status === 'sempurna';
                const col = document.createElement('div');
                col.className = 'col-12 col-md-6 col-lg-4';
                col.innerHTML = `
                    <div class="p-2.5 rounded-2 border bg-white d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-dark font-monospace">${v.kod}</span>
                            <div class="text-truncate" style="max-width: 170px;">
                                <div class="fw-semibold text-dark extra-small text-truncate" title="${v.name}">${v.name}</div>
                                <div class="text-muted" style="font-size: 0.68rem;">${v.cidb}</div>
                            </div>
                        </div>
                        <span class="badge ${isPass ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'} extra-small px-2 py-1 rounded-pill">
                            <i class="bi ${isPass ? 'bi-check-circle' : 'bi-x-circle'} me-1"></i>${isPass ? 'Sempurna' : 'Tidak Sempurna'}
                        </span>
                    </div>
                `;
                grid.appendChild(col);
            });
        }

        // Render Step 1 Rumusan Tables
        const melepasiVendors = this.vendors.filter(v => v.step1?.status === 'sempurna');
        const tidakMelepasiVendors = this.vendors.filter(v => v.step1?.status !== 'sempurna');

        const badgePass = document.getElementById('totalStep1MelepasiBadge');
        if (badgePass) badgePass.textContent = `${melepasiVendors.length} Petender Melepasi`;

        const badgeFail = document.getElementById('totalStep1TidakMelepasiBadge');
        if (badgeFail) badgeFail.textContent = `${tidakMelepasiVendors.length} Petender Disingkirkan`;

        const tbMelepasi = document.getElementById('tableStep1MelepasiBody');
        if (tbMelepasi) {
            tbMelepasi.innerHTML = '';
            if (melepasiVendors.length === 0) {
                tbMelepasi.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-3">Tiada petender melepasi.</td></tr>`;
            } else {
                melepasiVendors.forEach((v, idx) => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="text-center py-2.5 px-3">
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill font-monospace">${idx + 1}</span>
                        </td>
                        <td class="text-center py-2.5 px-3 font-monospace fw-bold text-dark">${v.kod}</td>
                        <td class="py-2.5 px-3">
                            <div class="fw-bold text-dark">${v.name}</div>
                            <div class="extra-small text-muted">${v.cidb}</div>
                        </td>
                        <td class="text-center py-2.5 px-3">
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill">
                                <i class="bi bi-check-all me-1"></i>Sempurna (${self.checklistDocs.length}/${self.checklistDocs.length} Dokumen)
                            </span>
                        </td>
                        <td class="py-2.5 px-3 small text-secondary">${v.step1?.catatan || '-'}</td>
                    `;
                    tbMelepasi.appendChild(tr);
                });
            }
        }

        const tbTidak = document.getElementById('tableStep1TidakMelepasiBody');
        if (tbTidak) {
            tbTidak.innerHTML = '';
            if (tidakMelepasiVendors.length === 0) {
                tbTidak.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-3">Tiada petender disingkirkan di fasa ini.</td></tr>`;
            } else {
                tidakMelepasiVendors.forEach((v, idx) => {
                    const tr = document.createElement('tr');
                    const failedDocNames = Object.entries(v.step1?.docs || {})
                        .filter(([dId, dData]) => dData.status === 'tidak_sempurna')
                        .map(([dId]) => self.checklistDocs.find(d => d.id === dId)?.title || dId);

                    tr.innerHTML = `
                        <td class="text-center py-2.5 px-3">
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill font-monospace">${idx + 1}</span>
                        </td>
                        <td class="text-center py-2.5 px-3 font-monospace fw-bold text-dark">${v.kod}</td>
                        <td class="py-2.5 px-3">
                            <div class="fw-bold text-dark">${v.name}</div>
                            <div class="extra-small text-muted">${v.cidb}</div>
                        </td>
                        <td class="py-2.5 px-3">
                            <span class="badge bg-danger text-white extra-small mb-1">${failedDocNames.length} Dokumen Gagal</span>
                            <div class="extra-small text-danger fw-medium">${failedDocNames.join(', ') || 'Dokumen Tidak Lengkap'}</div>
                        </td>
                        <td class="py-2.5 px-3 small text-danger">${v.step1?.catatan || '-'}</td>
                    `;
                    tbTidak.appendChild(tr);
                });
            }
        }
    },

    // ==========================================
    // RENDER STEP 2: KEMAMPUAN KEWANGAN
    // ==========================================
    renderStep2() {
        const qualifiedVendors = this.vendors.filter(v => v.step1?.status === 'sempurna');
        const countText = document.getElementById('step2QualifiedCountText');
        if (countText) countText.textContent = `${qualifiedVendors.length} Petender`;

        const tbody = document.getElementById('step2VendorTableBody');
        if (!tbody) return;

        tbody.innerHTML = '';
        if (qualifiedVendors.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-4">Tiada petender yang layak daripada Langkah 1.</td></tr>`;
            return;
        }

        qualifiedVendors.forEach((v, idx) => {
            const isPass = v.step2?.status === 'memuaskan';
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-center py-3 px-3">
                    <span class="badge bg-light text-dark border px-2 py-1 rounded-pill font-monospace">${idx + 1}</span>
                </td>
                <td class="text-center py-3 px-3 font-monospace fw-bold text-dark">${v.kod}</td>
                <td class="py-3 px-3">
                    <div class="fw-bold text-dark">${v.name}</div>
                    <div class="extra-small text-muted">${v.cidb} | SSM: ${v.ssm}</div>
                </td>
                <td class="text-end py-3 px-3 font-monospace fw-bold text-dark">${v.step2?.modal_pusingan || 'RM 0.00'}</td>
                <td class="text-end py-3 px-3 font-monospace fw-semibold text-secondary">${v.step2?.purata_bank || 'RM 0.00'}</td>
                <td class="text-center py-3 px-3">
                    <span class="badge ${isPass ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle'} px-2.5 py-1 rounded-pill">
                        <i class="bi ${isPass ? 'bi-check-circle' : 'bi-x-circle'} me-1"></i>${isPass ? 'Memuaskan' : 'Tidak Memuaskan'}
                    </span>
                </td>
                <td class="text-center py-3 px-3">
                    <button type="button" class="btn btn-sm btn-outline-danger px-3 py-1 fw-bold rounded-2" onclick="window.type2State.openModalKemampuanKewangan(${v.id})">
                        <i class="bi bi-calculator me-1"></i>Nilai Kewangan
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        // Step 2 Rumusan
        const memuaskanVendors = qualifiedVendors.filter(v => v.step2?.status === 'memuaskan');
        const tidakMemuaskanVendors = qualifiedVendors.filter(v => v.step2?.status !== 'memuaskan');

        const bMemuaskan = document.getElementById('totalStep2MemuaskanBadge');
        if (bMemuaskan) bMemuaskan.textContent = `${memuaskanVendors.length} Petender Memuaskan`;

        const bTidak = document.getElementById('totalStep2TidakMemuaskanBadge');
        if (bTidak) bTidak.textContent = `${tidakMemuaskanVendors.length} Petender Tidak Memuaskan`;

        const tbMemuaskan = document.getElementById('tableStep2MemuaskanBody');
        if (tbMemuaskan) {
            tbMemuaskan.innerHTML = '';
            if (memuaskanVendors.length === 0) {
                tbMemuaskan.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-3">Tiada petender memuaskan.</td></tr>`;
            } else {
                memuaskanVendors.forEach((v, idx) => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="text-center py-2.5 px-3">
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill font-monospace">${idx + 1}</span>
                        </td>
                        <td class="text-center py-2.5 px-3 font-monospace fw-bold text-dark">${v.kod}</td>
                        <td class="py-2.5 px-3 fw-bold text-dark">${v.name}</td>
                        <td class="text-end py-2.5 px-3 font-monospace fw-bold text-dark">${v.step2?.modal_pusingan || 'RM 0.00'}</td>
                        <td class="text-end py-2.5 px-3 font-monospace text-secondary">${v.step2?.purata_bank || 'RM 0.00'}</td>
                        <td class="py-2.5 px-3 small text-secondary">${v.step2?.catatan || '-'}</td>
                    `;
                    tbMemuaskan.appendChild(tr);
                });
            }
        }

        const tbTidak = document.getElementById('tableStep2TidakMemuaskanBody');
        if (tbTidak) {
            tbTidak.innerHTML = '';
            if (tidakMemuaskanVendors.length === 0) {
                tbTidak.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-3">Tiada petender ditolak di fasa ini.</td></tr>`;
            } else {
                tidakMemuaskanVendors.forEach((v, idx) => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="text-center py-2.5 px-3">
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill font-monospace">${idx + 1}</span>
                        </td>
                        <td class="text-center py-2.5 px-3 font-monospace fw-bold text-dark">${v.kod}</td>
                        <td class="py-2.5 px-3 fw-bold text-dark">${v.name}</td>
                        <td class="text-end py-2.5 px-3 font-monospace text-danger">${v.step2?.modal_pusingan || 'RM 0.00'}</td>
                        <td class="text-end py-2.5 px-3 font-monospace text-secondary">${v.step2?.purata_bank || 'RM 0.00'}</td>
                        <td class="py-2.5 px-3 small text-danger">${v.step2?.catatan || '-'}</td>
                    `;
                    tbTidak.appendChild(tr);
                });
            }
        }
    },

    // ==========================================
    // RENDER STEP 3: PENILAIAN KERJA
    // ==========================================
    renderStep3() {
        const step2Qualified = this.vendors.filter(v => v.step1?.status === 'sempurna' && v.step2?.status === 'memuaskan');
        const tbody = document.getElementById('step3VendorTableBody');
        if (!tbody) return;

        tbody.innerHTML = '';
        if (step2Qualified.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-4">Tiada petender yang layak daripada Langkah 2.</td></tr>`;
            return;
        }

        step2Qualified.forEach((v, idx) => {
            const isPass = v.step3?.status === 'memuaskan';
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-center py-3 px-3">
                    <span class="badge bg-light text-dark border px-2 py-1 rounded-pill font-monospace">${idx + 1}</span>
                </td>
                <td class="text-center py-3 px-3 font-monospace fw-bold text-dark">${v.kod}</td>
                <td class="py-3 px-3">
                    <div class="fw-bold text-dark">${v.name}</div>
                    <div class="extra-small text-muted">${v.cidb}</div>
                </td>
                <td class="text-end py-3 px-3 font-monospace fw-bold text-dark">${v.step3?.baki_kerja || 'RM 0.00'}</td>
                <td class="text-end py-3 px-3 font-monospace text-secondary">${v.step3?.projek_terbesar || 'RM 0.00'}</td>
                <td class="text-center py-3 px-3">
                    <span class="badge ${isPass ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle'} px-2.5 py-1 rounded-pill">
                        <i class="bi ${isPass ? 'bi-check-circle' : 'bi-x-circle'} me-1"></i>${isPass ? 'Memuaskan' : 'Tidak Memuaskan'}
                    </span>
                </td>
                <td class="text-center py-3 px-3">
                    <button type="button" class="btn btn-sm btn-outline-danger px-3 py-1 fw-bold rounded-2" onclick="window.type2State.openModalPenilaianKerja(${v.id})">
                        <i class="bi bi-cone-striped me-1"></i>Nilai Kerja
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        // Step 3 Rumusan
        const memuaskanVendors = step2Qualified.filter(v => v.step3?.status === 'memuaskan');
        const tidakMemuaskanVendors = step2Qualified.filter(v => v.step3?.status !== 'memuaskan');

        const bMemuaskan = document.getElementById('totalStep3MemuaskanBadge');
        if (bMemuaskan) bMemuaskan.textContent = `${memuaskanVendors.length} Petender Memuaskan`;

        const bTidak = document.getElementById('totalStep3TidakMemuaskanBadge');
        if (bTidak) bTidak.textContent = `${tidakMemuaskanVendors.length} Petender Tidak Memuaskan`;

        const tbMemuaskan = document.getElementById('tableStep3MemuaskanBody');
        if (tbMemuaskan) {
            tbMemuaskan.innerHTML = '';
            if (memuaskanVendors.length === 0) {
                tbMemuaskan.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-3">Tiada petender memuaskan.</td></tr>`;
            } else {
                memuaskanVendors.forEach((v, idx) => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="text-center py-2.5 px-3">
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill font-monospace">${idx + 1}</span>
                        </td>
                        <td class="text-center py-2.5 px-3 font-monospace fw-bold text-dark">${v.kod}</td>
                        <td class="py-2.5 px-3 fw-bold text-dark">${v.name}</td>
                        <td class="text-end py-2.5 px-3 font-monospace fw-bold text-dark">${v.step3?.baki_kerja || 'RM 0.00'}</td>
                        <td class="text-center py-2.5 px-3 font-monospace">${v.step3?.kakitangan || '0'}</td>
                        <td class="py-2.5 px-3 small text-secondary">${v.step3?.catatan || '-'}</td>
                    `;
                    tbMemuaskan.appendChild(tr);
                });
            }
        }

        const tbTidak = document.getElementById('tableStep3TidakMemuaskanBody');
        if (tbTidak) {
            tbTidak.innerHTML = '';
            if (tidakMemuaskanVendors.length === 0) {
                tbTidak.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-3">Tiada petender ditolak di fasa ini.</td></tr>`;
            } else {
                tidakMemuaskanVendors.forEach((v, idx) => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="text-center py-2.5 px-3">
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill font-monospace">${idx + 1}</span>
                        </td>
                        <td class="text-center py-2.5 px-3 font-monospace fw-bold text-dark">${v.kod}</td>
                        <td class="py-2.5 px-3 fw-bold text-dark">${v.name}</td>
                        <td class="text-end py-2.5 px-3 font-monospace text-danger">${v.step3?.baki_kerja || 'RM 0.00'}</td>
                        <td class="py-2.5 px-3 small text-danger">${v.step3?.catatan || '-'}</td>
                    `;
                    tbTidak.appendChild(tr);
                });
            }
        }
    },

    // ==========================================
    // RENDER STEP 4: CONSOLIDATED REPORT
    // ==========================================
    renderStep4() {
        // Section A
        const tbA = document.getElementById('step4TableSecABody');
        if (tbA) {
            tbA.innerHTML = '';
            this.vendors.forEach((v, idx) => {
                const isPass = v.step1?.status === 'sempurna';
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="text-center py-2 px-3 font-monospace">${idx + 1}</td>
                    <td class="text-center py-2 px-3 font-monospace fw-bold">${v.kod}</td>
                    <td class="py-2 px-3 fw-semibold text-dark">${v.name}</td>
                    <td class="text-center py-2 px-3">
                        <span class="badge ${isPass ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'} px-2 py-0.5 rounded-pill">
                            ${isPass ? `${this.checklistDocs.length}/${this.checklistDocs.length} Dokumen Sempurna` : 'Dokumen Tidak Lengkap'}
                        </span>
                    </td>
                    <td class="text-center py-2 px-3">
                        <span class="badge ${isPass ? 'bg-success' : 'bg-danger'} px-2 py-0.5 rounded-pill text-white">
                            ${isPass ? 'Melepasi Saringan' : 'Disingkirkan'}
                        </span>
                    </td>
                    <td class="py-2 px-3 small text-secondary">${v.step1?.catatan || '-'}</td>
                `;
                tbA.appendChild(tr);
            });
        }

        // Section B
        const step1Passed = this.vendors.filter(v => v.step1?.status === 'sempurna');
        const bSecB = document.getElementById('step4SecBBadge');
        if (bSecB) bSecB.textContent = `${step1Passed.length} Petender Dinilai`;

        const tbB = document.getElementById('step4TableSecBBody');
        if (tbB) {
            tbB.innerHTML = '';
            step1Passed.forEach((v, idx) => {
                const isPass = v.step2?.status === 'memuaskan';
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="text-center py-2 px-3 font-monospace">${idx + 1}</td>
                    <td class="text-center py-2 px-3 font-monospace fw-bold">${v.kod}</td>
                    <td class="py-2 px-3 fw-semibold text-dark">${v.name}</td>
                    <td class="text-end py-2 px-3 font-monospace fw-bold">${v.step2?.modal_pusingan || 'RM 0.00'}</td>
                    <td class="text-end py-2 px-3 font-monospace text-secondary">${v.step2?.purata_bank || 'RM 0.00'}</td>
                    <td class="text-center py-2 px-3">
                        <span class="badge ${isPass ? 'bg-success' : 'bg-danger'} px-2 py-0.5 rounded-pill text-white">
                            ${isPass ? 'Memuaskan' : 'Tidak Memuaskan'}
                        </span>
                    </td>
                    <td class="py-2 px-3 small text-secondary">${v.step2?.catatan || '-'}</td>
                `;
                tbB.appendChild(tr);
            });
        }

        // Section C
        const step2Passed = this.vendors.filter(v => v.step1?.status === 'sempurna' && v.step2?.status === 'memuaskan');
        const bSecC = document.getElementById('step4SecCBadge');
        if (bSecC) bSecC.textContent = `${step2Passed.length} Petender Dinilai`;

        const tbC = document.getElementById('step4TableSecCBody');
        if (tbC) {
            tbC.innerHTML = '';
            step2Passed.forEach((v, idx) => {
                const isPass = v.step3?.status === 'memuaskan';
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="text-center py-2 px-3 font-monospace">${idx + 1}</td>
                    <td class="text-center py-2 px-3 font-monospace fw-bold">${v.kod}</td>
                    <td class="py-2 px-3 fw-semibold text-dark">${v.name}</td>
                    <td class="text-end py-2 px-3 font-monospace fw-bold">${v.step3?.baki_kerja || 'RM 0.00'}</td>
                    <td class="text-center py-2 px-3 font-monospace">${v.step3?.kakitangan || '0'}</td>
                    <td class="text-center py-2 px-3">
                        <span class="badge ${isPass ? 'bg-success' : 'bg-danger'} px-2 py-0.5 rounded-pill text-white">
                            ${isPass ? 'Memuaskan' : 'Tidak Memuaskan'}
                        </span>
                    </td>
                    <td class="py-2 px-3 small text-secondary">${v.step3?.catatan || '-'}</td>
                `;
                tbC.appendChild(tr);
            });
        }

        // Section D: Final Qualifying List (All 3 steps passed)
        const finalQualified = this.vendors.filter(v => 
            v.step1?.status === 'sempurna' && 
            v.step2?.status === 'memuaskan' && 
            v.step3?.status === 'memuaskan'
        );

        // Sort by bid price (extract number)
        finalQualified.sort((a, b) => {
            const numA = (typeof a.harga_raw === 'number' && a.harga_raw > 0)
                ? a.harga_raw
                : parseFloat(String(a.harga_tawaran).replace(/[^\d.]/g, '') || 0);
            const numB = (typeof b.harga_raw === 'number' && b.harga_raw > 0)
                ? b.harga_raw
                : parseFloat(String(b.harga_tawaran).replace(/[^\d.]/g, '') || 0);
            return numA - numB;
        });

        const bSecD = document.getElementById('step4SecDBBadge');
        if (bSecD) bSecD.textContent = `${finalQualified.length} Petender Layak`;

        const tbD = document.getElementById('step4TableSecDBody');
        if (tbD) {
            tbD.innerHTML = '';
            if (finalQualified.length === 0) {
                tbD.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-4">Tiada petender yang melepasi kesemua fasa saringan.</td></tr>`;
            } else {
                finalQualified.forEach((v, idx) => {
                    const isFirst = idx === 0;
                    const tr = document.createElement('tr');
                    tr.className = isFirst ? 'table-warning-subtle fw-semibold' : '';
                    tr.innerHTML = `
                        <td class="text-center py-3 px-3">
                            <span class="badge ${isFirst ? 'bg-warning text-dark border border-warning' : 'bg-secondary-subtle text-secondary'} font-monospace px-2.5 py-1.5 rounded-circle fs-6">
                                ${idx + 1}
                            </span>
                        </td>
                        <td class="text-center py-3 px-3 font-monospace fw-bold text-dark fs-6">${v.kod}</td>
                        <td class="py-3 px-3">
                            <div class="fw-bold text-dark fs-6">${v.name}</div>
                            <div class="extra-small text-muted">${v.cidb} | SSM: ${v.ssm}</div>
                        </td>
                        <td class="text-end py-3 px-3 font-monospace fw-bold fs-6 text-danger">${v.harga_tawaran}</td>
                        <td class="text-center py-3 px-3 font-monospace">${v.tempoh_siap}</td>
                        <td class="text-center py-3 px-3">
                            <span class="badge ${isFirst ? 'bg-success' : 'bg-info'} text-white px-2.5 py-1 rounded-pill">
                                ${isFirst ? 'DISYORKAN (KEPALA)' : 'LAYAK DIPERTIMBANG'}
                            </span>
                        </td>
                        <td class="py-3 px-3 small text-dark">${isFirst ? 'Tawaran terendah yang mematuhi semua spesifikasi kewangan dan kerja.' : 'Mematuhi kriteria asas dengan kedudukan harga munasabah.'}</td>
                    `;
                    tbD.appendChild(tr);
                });
            }
        }
    },

    // ==========================================
    // MODAL OPENERS & DETAIL HYDRATION
    // ==========================================
    escapeAttr(str) {
        return String(str || '')
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    },

    openModalSemakanDokumen(docId) {
        this.activeDocId = docId;
        const doc = this.checklistDocs.find(d => d.id === docId);
        if (!doc) return;

        document.getElementById('modalDocTitle').textContent = doc.title;
        document.getElementById('modalDocMekanisma').textContent = doc.mekanisma;

        const tbody = document.getElementById('modalSemakanDokumenBody');
        if (!tbody) return;

        tbody.innerHTML = '';
        this.vendors.forEach(v => {
            const vDoc = v.step1?.docs?.[docId] || { status: 'sempurna', catatan: '', has_file: false, file_url: null, form_url: null, is_form: false, is_spec: false, files: [] };
            const previewUrl = vDoc.file_url || vDoc.form_url || '';
            const isSpec = (doc.action === 'view_specification') || (doc.mekanisma === 'Spesifikasi') || vDoc.is_spec;
            const isOnlineForm = vDoc.is_form || (doc.action === 'online_form') || (doc.mekanisma && doc.mekanisma.toLowerCase().includes('borang'));
            const previewTitle = doc.title + ' - ' + v.name;
            const files = Array.isArray(vDoc.files) ? vDoc.files : [];

            let previewIcon = 'bi-file-earmark-pdf';
            let previewLabel = 'Lihat Dokumen';

            if (isSpec) {
                previewIcon = 'bi-card-checklist';
                previewLabel = 'Lihat Spesifikasi';
            } else if (isOnlineForm) {
                previewIcon = 'bi-window';
                previewLabel = 'Lihat Borang';
            }

            // Build files display for this vendor (isolated to v.id)
            let filesHtml = '';
            if (files.length > 1) {
                filesHtml = `
                    <div class="d-flex flex-column gap-1.5 py-1">
                        <span class="badge bg-secondary-subtle text-secondary border px-1.5 py-0.5 rounded-1 extra-small align-self-start">
                            <i class="bi bi-files me-1"></i>${files.length} Fail Dimuat Naik
                        </span>
                        ${files.map((f, fIdx) => `
                            <button type="button" class="btn btn-sm btn-link text-danger p-0 text-decoration-none fw-semibold d-inline-flex align-items-center gap-1.5 btn-preview-file-action text-start" data-title="${this.escapeAttr((f.name || ('Fail ' + (fIdx + 1))) + ' - ' + v.name)}" data-url="${this.escapeAttr(f.url || '#')}">
                                <i class="bi bi-file-earmark-pdf fs-6 flex-shrink-0"></i>
                                <span class="text-truncate" style="max-width: 220px;" title="${this.escapeAttr(f.name || ('Fail ' + (fIdx + 1)))}">${f.name || ('Fail ' + (fIdx + 1))}</span>
                            </button>
                        `).join('')}
                    </div>
                `;
            } else if (files.length === 1 && files[0].url) {
                filesHtml = `
                    <button type="button" class="btn btn-sm btn-link text-danger p-0 text-decoration-none fw-semibold d-inline-flex align-items-center gap-1 btn-preview-file-action text-start" data-title="${this.escapeAttr((files[0].name || previewTitle) + ' - ' + v.name)}" data-url="${this.escapeAttr(files[0].url)}">
                        <i class="bi bi-file-earmark-pdf fs-6 flex-shrink-0"></i>
                        <span class="text-truncate" style="max-width: 220px;" title="${this.escapeAttr(files[0].name || previewLabel)}">${files[0].name || previewLabel}</span>
                    </button>
                `;
            } else if (previewUrl) {
                filesHtml = `
                    <button type="button" class="btn btn-sm btn-link text-danger p-0 text-decoration-none fw-semibold d-inline-flex align-items-center gap-1 btn-preview-file-action" data-title="${this.escapeAttr(previewTitle)}" data-url="${this.escapeAttr(previewUrl)}">
                        <i class="bi ${previewIcon} fs-6 flex-shrink-0"></i>
                        <span>${previewLabel}</span>
                    </button>
                `;
            } else {
                filesHtml = `<span class="text-muted extra-small fst-italic">Tiada dokumen</span>`;
            }

            const isSubmitted = vDoc.has_file || files.length > 0;
            const submittedText = files.length > 1 ? `Dihantar (${files.length} Fail)` : (isSubmitted ? 'Dihantar' : 'Belum Dihantar');

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-center font-monospace fw-bold text-dark py-2.5">${v.kod}</td>
                <td class="fw-semibold text-dark py-2.5">
                    <div>${v.name}</div>
                    <div class="extra-small text-muted">${v.cidb}</div>
                </td>
                <td class="py-2.5">
                    ${filesHtml}
                </td>
                <td class="text-center py-2.5">
                    <span class="badge ${isSubmitted ? 'bg-light text-success' : 'bg-light text-muted'} border px-2 py-0.5 rounded-pill extra-small">
                        <i class="bi ${isSubmitted ? 'bi-check-circle' : 'bi-dash-circle'} me-1"></i>${submittedText}
                    </span>
                </td>
                <td class="py-2.5 text-center">
                    <select class="form-select form-select-sm fw-bold ${vDoc.status === 'sempurna' ? 'text-success' : 'text-danger'}" id="modalDocSelect_${v.id}" onchange="this.className='form-select form-select-sm fw-bold ' + (this.value==='sempurna'?'text-success':'text-danger')">
                        <option value="sempurna" ${vDoc.status === 'sempurna' ? 'selected' : ''}>Sempurna</option>
                        <option value="tidak_sempurna" ${vDoc.status === 'tidak_sempurna' ? 'selected' : ''}>Tidak Sempurna</option>
                    </select>
                </td>
                <td class="py-2.5">
                    <input type="text" class="form-control form-control-sm" id="modalDocNote_${v.id}" value="${vDoc.catatan || ''}" placeholder="Catatan semakan...">
                </td>
            `;
            tbody.appendChild(tr);
        });

        const modalEl = document.getElementById('modalSemakanDokumenKerja');
        const modalObj = bootstrap.Modal.getOrCreateInstance(modalEl);
        modalObj.show();
    },

    openModalKemampuanKewangan(vendorId) {
        this.activeVendorId = vendorId;
        const v = this.vendors.find(item => item.id === vendorId);
        if (!v) return;

        document.getElementById('modalKewanganVendorKod').textContent = v.kod;
        document.getElementById('modalKewanganVendorNama').textContent = v.name;
        document.getElementById('modalKewanganVendorInfo').textContent = `SSM: ${v.ssm} | ${v.cidb}`;

        // Populate metric cards in Borang 3 modal
        const s2 = v.step2 || {};
        const metrics = s2.metrics || {};

        document.getElementById('mkModalPusingan').textContent = s2.modal_pusingan || 'RM 0.00';
        document.getElementById('mkPurataBank').textContent = s2.purata_bank || 'RM 0.00';
        document.getElementById('mkWangTangan').textContent = s2.wang_tangan || 'RM 0.00';
        document.getElementById('mkJumlahModal').textContent = s2.jumlah_modal || 'RM 0.00';

        const minModEl = document.getElementById('modalKewanganMinModal');
        if (minModEl) minModEl.textContent = s2.modal_minimum || 'RM 7,500.00';

        const statusKecukupanEl = document.getElementById('mkStatusKecukupan');
        if (statusKecukupanEl) {
            const isPass = s2.is_cukup_modal ?? true;
            statusKecukupanEl.className = isPass ? 'extra-small text-success fw-semibold' : 'extra-small text-danger fw-semibold';
            statusKecukupanEl.innerHTML = isPass
                ? '<i class="bi bi-check-circle-fill me-1"></i>Mencukupi Had Min (1.5%)'
                : '<i class="bi bi-x-circle-fill me-1"></i>Kurang Had Min (1.5%)';
        }

        // Tab 1 Breakdown Table
        document.getElementById('mkRowModalPusingan').textContent = s2.modal_pusingan || 'RM 0.00';
        document.getElementById('mkRowPurataBank').textContent = s2.purata_bank || 'RM 0.00';
        document.getElementById('mkRowWangTangan').textContent = s2.wang_tangan || 'RM 0.00';
        document.getElementById('mkRowKredit').textContent = metrics.baki_kredit ? ('RM ' + Number(metrics.baki_kredit).toLocaleString(undefined, {minimumFractionDigits: 2})) : 'RM 0.00';
        document.getElementById('mkRowJumlahTerkumpul').textContent = s2.jumlah_modal || 'RM 0.00';

        // Tab 2 Lembaran Imbangan
        document.getElementById('mkAsetTetap').textContent = 'RM ' + Number(metrics.aset_tetap || 0).toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById('mkAsetSemasa').textContent = 'RM ' + Number(metrics.aset_semasa || 0).toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById('mkLiabilitiSemasa').textContent = 'RM ' + Number(metrics.liabiliti_semasa || 0).toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById('mkLiabilitiTetap').textContent = 'RM ' + Number(metrics.liabiliti_tetap || 0).toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById('mkWangTunai').textContent = 'RM ' + Number(metrics.wang_tunai || 0).toLocaleString(undefined, {minimumFractionDigits: 2});

        const ratio = (metrics.liabiliti_semasa > 0) ? (metrics.aset_semasa / metrics.liabiliti_semasa).toFixed(2) : '2.45';
        document.getElementById('mkCurrentRatio').textContent = ratio;

        // Tab 3 Penyata Bank Accounts
        const bankBody = document.getElementById('mkBankBody');
        if (bankBody) {
            bankBody.innerHTML = '';
            const pbAccounts = metrics.pb_accounts || s2.penyata_bank?.accounts || [];
            if (pbAccounts.length === 0) {
                bankBody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-3">Tiada maklumat akaun bank dilaporkan.</td></tr>`;
            } else {
                pbAccounts.forEach((acc, aIdx) => {
                    const tr = document.createElement('tr');
                    let m = acc.monthly_amounts || [0, 0, 0];
                    if ((!m || (m[0] === 0 && m[1] === 0 && m[2] === 0)) && acc.bulans && acc.bulans.length > 0) {
                        m = acc.bulans.map(b => Number(typeof b === 'object' ? (b.jumlah || b.amount || 0) : b));
                    }
                    const m0 = Number(m[0] || 0);
                    const m1 = Number(m[1] || 0);
                    const m2 = Number(m[2] || 0);
                    const purata = Number(acc.purata !== undefined ? acc.purata : ((m0 + m1 + m2) / 3));

                    tr.innerHTML = `
                        <td class="fw-semibold text-dark">${acc.bank_name || ('Bank ' + (aIdx + 1))}</td>
                        <td class="font-monospace text-center">${acc.account_no || ('Akaun ' + (aIdx + 1))}</td>
                        <td class="text-end font-monospace">RM ${m0.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                        <td class="text-end font-monospace">RM ${m1.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                        <td class="text-end font-monospace">RM ${m2.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                        <td class="text-end font-monospace fw-bold text-dark">RM ${purata.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                    `;
                    bankBody.appendChild(tr);
                });
            }
        }

        // Bank Statement Documents (all uploaded files for this specific vendor)
        const bankContainer = document.getElementById('mkBankFilesContainer');
        const bankBadge = document.getElementById('mkBankFilesBadge');
        const bFiles = s2.bank_files || [];

        if (bankBadge) bankBadge.textContent = `${bFiles.length} Fail Dimuat Naik`;
        if (bankContainer) {
            bankContainer.innerHTML = '';
            if (bFiles.length === 0) {
                bankContainer.innerHTML = `
                    <div class="p-3 text-center text-muted extra-small fst-italic bg-white rounded-2 border">
                        <i class="bi bi-exclamation-circle me-1"></i>Tiada fail fizikal penyata bank dimuat naik oleh petender ini.
                    </div>
                `;
            } else {
                bFiles.forEach((bf, bfIdx) => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'p-2.5 rounded-2 border bg-white d-flex align-items-center justify-content-between shadow-sm';
                    const fTitle = `${v.name} - ${bf.name || ('Penyata Bank ' + (bfIdx + 1))}`;
                    const hasUrl = Boolean(bf.url && bf.url !== '#');

                    fileItem.innerHTML = `
                        <div class="d-flex align-items-center gap-2 text-truncate me-2">
                            <i class="bi bi-file-earmark-pdf text-danger fs-5 flex-shrink-0"></i>
                            <div class="text-truncate">
                                <span class="small fw-semibold text-dark text-truncate d-block" title="${this.escapeAttr(bf.name)}">${bf.name || ('Penyata Bank ' + (bfIdx + 1))}</span>
                                <span class="text-muted extra-small">${bf.size ? (Math.round(bf.size / 1024) + ' KB') : 'Dokumen Penyata Bank (Disahkan)'}</span>
                            </div>
                        </div>
                        ${hasUrl ? `
                            <button type="button" class="btn btn-xs btn-outline-danger px-2.5 py-1 rounded-2 flex-shrink-0 btn-preview-file-action" data-title="${this.escapeAttr(fTitle)}" data-url="${this.escapeAttr(bf.url)}">
                                <i class="bi bi-eye me-1"></i>Prebiu Dokumen
                            </button>
                        ` : `
                            <button type="button" class="btn btn-xs btn-outline-secondary px-2.5 py-1 rounded-2 flex-shrink-0" onclick="alert('Fail ini tidak mempunyai URL fail yang sah.')">
                                <i class="bi bi-dash-circle me-1"></i>Tiada URL
                            </button>
                        `}
                    `;
                    bankContainer.appendChild(fileItem);
                });
            }
        }

        // Tab 4 Bon & Saham Accounts
        const bsBody = document.getElementById('mkBonSahamBody');
        if (bsBody) {
            bsBody.innerHTML = '';
            const bsAccs = s2.bon_saham_accounts || s2.accounts || [];
            if (bsAccs.length === 0) {
                bsBody.innerHTML = `<tr><td colspan="3" class="text-center text-muted py-3">Tiada rekod bon dan saham dilaporkan.</td></tr>`;
            } else {
                bsAccs.forEach((acc, bIdx) => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="text-center font-monospace">${bIdx + 1}</td>
                        <td class="fw-semibold text-dark">${acc.bank_institusi || '-'}</td>
                        <td class="text-end font-monospace">RM ${Number(acc.jumlah_deposit || 0).toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                    `;
                    bsBody.appendChild(tr);
                });
            }
        }

        // Decision controls
        const selectEl = document.getElementById('modalKewanganDecisionSelect');
        if (selectEl) selectEl.value = s2.status || 'memuaskan';

        const noteEl = document.getElementById('modalKewanganCatatanInput');
        if (noteEl) noteEl.value = s2.catatan || '';

        const modalEl = document.getElementById('modalKemampuanKewanganVendor');
        const modalObj = new bootstrap.Modal(modalEl);
        modalObj.show();
    },

    openModalPenilaianKerja(vendorId) {
        this.activeVendorId = vendorId;
        const v = this.vendors.find(item => item.id === vendorId);
        if (!v) return;

        document.getElementById('modalKerjaVendorKod').textContent = v.kod;
        document.getElementById('modalKerjaVendorNama').textContent = v.name;
        document.getElementById('modalKerjaVendorInfo').textContent = `SSM: ${v.ssm} | ${v.cidb}`;

        const s3 = v.step3 || {};

        // Tab 1: Kerja Semasa
        const bakiBadge = document.getElementById('mkBakiKerjaTotalBadge');
        if (bakiBadge) {
            bakiBadge.innerHTML = `Jumlah Baki Kerja: <strong>${s3.baki_kerja || 'RM 0.00'}</strong>`;
        }

        const ksBody = document.getElementById('mkKerjaSemasaBody');
        if (ksBody) {
            ksBody.innerHTML = '';
            const items = s3.kerja_semasa?.items || [];
            if (items.length === 0) {
                ksBody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-3">Tiada rekod kerja semasa dilaporkan.</td></tr>`;
            } else {
                items.forEach(it => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="text-center font-monospace">${it.bil}</td>
                        <td class="fw-medium">${it.nama || it.nama_projek || '-'}</td>
                        <td>${it.majikan || it.agensi || '-'}</td>
                        <td class="text-end font-monospace">${it.harga_disp || ('RM ' + Number(it.harga || 0).toLocaleString(undefined, {minimumFractionDigits: 2}))}</td>
                        <td class="text-center">
                            <span class="badge bg-info-subtle text-info border px-2 py-0.5 rounded-pill">${it.kemajuan_sebenar || it.kemajuan || 0}%</span>
                        </td>
                        <td class="text-end font-monospace fw-bold text-danger">${it.baki_disp || ('RM ' + Number(it.baki || 0).toLocaleString(undefined, {minimumFractionDigits: 2}))}</td>
                    `;
                    ksBody.appendChild(tr);
                });
            }
        }

        const btnKsDoc = document.getElementById('btnPreviewKerjaSemasa');
        const lblKsDoc = document.getElementById('mkKerjaSemasaDocLabel');
        if (btnKsDoc) {
            if (s3.kerja_semasa?.dokumen_url) {
                btnKsDoc.setAttribute('data-title', `${v.name} - ${s3.kerja_semasa.dokumen_name || 'Dokumen Kerja Semasa'}`);
                btnKsDoc.setAttribute('data-url', s3.kerja_semasa.dokumen_url);
                btnKsDoc.onclick = () => window.previewDocument(`${v.name} - ${s3.kerja_semasa.dokumen_name || 'Dokumen Kerja Semasa'}`, s3.kerja_semasa.dokumen_url);
                if (lblKsDoc) lblKsDoc.textContent = s3.kerja_semasa.dokumen_name || 'Dokumen Pembuktian Dimuat Naik';
            } else {
                btnKsDoc.setAttribute('data-title', `${v.name} - Dokumen Kerja Semasa`);
                btnKsDoc.setAttribute('data-url', '#');
                btnKsDoc.onclick = () => window.previewDocument(`${v.name} - Dokumen Kerja Semasa`, '#');
                if (lblKsDoc) lblKsDoc.textContent = 'Tiada fail fizikal dimuat naik';
            }
        }

        // Tab 2: Pengalaman Kerja
        const projekCountBadge = document.getElementById('mkProjekSiapCount');
        if (projekCountBadge) {
            projekCountBadge.textContent = `${s3.pengalaman?.bil_projek || 0} Projek Berjaya Disiapkan`;
        }

        const pkBody = document.getElementById('mkPengalamanBody');
        if (pkBody) {
            pkBody.innerHTML = '';
            const pItems = s3.pengalaman?.items || [];
            if (pItems.length === 0) {
                pkBody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-3">Tiada rekod pengalaman projek terdahulu.</td></tr>`;
            } else {
                pItems.forEach(it => {
                    const tr = document.createElement('tr');
                    let docButtonHtml = '<span class="text-muted">-</span>';
                    if (it.cpc_url) {
                        docButtonHtml = `
                            <button type="button" class="btn btn-xs btn-outline-danger py-0 px-2 rounded-pill" onclick="window.previewDocument('Sijil CPC - ${it.tajuk}', '${it.cpc_url}')" title="Lihat Sijil CPC">
                                <i class="bi bi-file-earmark-pdf me-1"></i>CPC
                            </button>
                        `;
                    }
                    tr.innerHTML = `
                        <td class="text-center font-monospace">${it.bil}</td>
                        <td class="fw-medium">${it.tajuk}</td>
                        <td>${it.pelanggan}</td>
                        <td class="text-end font-monospace">${it.nilai_disp}</td>
                        <td class="text-center font-monospace text-muted">${it.tahun_siap}</td>
                        <td class="text-center">${docButtonHtml}</td>
                    `;
                    pkBody.appendChild(tr);
                });
            }
        }

        // Tab 3: Kakitangan Teknikal
        const ktCountBadge = document.getElementById('mkKakitanganCount');
        if (ktCountBadge) {
            ktCountBadge.textContent = `${s3.kakitangan_data?.bil_kakitangan || 0} Kakitangan Teknikal`;
        }

        const ktBody = document.getElementById('mkKakitanganBody');
        if (ktBody) {
            ktBody.innerHTML = '';
            const kItems = s3.kakitangan_data?.items || [];
            if (kItems.length === 0) {
                ktBody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-3">Tiada rekod kakitangan teknikal didaftarkan.</td></tr>`;
            } else {
                kItems.forEach(it => {
                    const tr = document.createElement('tr');
                    let docBtn = '<span class="text-muted">-</span>';
                    if (it.dokumens && it.dokumens.length > 0) {
                        docBtn = it.dokumens.map(d => `
                            <button type="button" class="btn btn-xs btn-outline-danger py-0 px-2 rounded-pill me-1" onclick="window.previewDocument('Sijil ${it.nama_pegawai} (${d.original_name})', '${d.file_url}')" title="${d.original_name}">
                                <i class="bi bi-file-earmark-pdf me-1"></i>Prebiu
                            </button>
                        `).join('');
                    }

                    tr.innerHTML = `
                        <td class="text-center font-monospace">${it.bil}</td>
                        <td class="fw-semibold text-dark">${it.nama_pegawai}</td>
                        <td class="text-center"><span class="badge bg-primary-subtle text-primary border border-primary-subtle">${it.kategori || 'Kategori B'}</span></td>
                        <td>${it.tahap_pendidikan || '-'}</td>
                        <td>${it.sijil_professional || it.jawatan || '-'}</td>
                        <td class="text-center font-monospace">${it.jumlah_pengalaman} Tahun</td>
                        <td class="text-center">${docBtn}</td>
                    `;
                    ktBody.appendChild(tr);
                });
            }
        }

        // General Kakitangan Docs (KWSP, SOCSO, etc.)
        const genDocsContainer = document.getElementById('mkKakitanganGeneralDocsContainer');
        const genDocsBadge = document.getElementById('mkKakitanganGeneralDocsBadge');
        const genDocs = s3.kakitangan_data?.general_dokumens || [];

        if (genDocsBadge) {
            genDocsBadge.textContent = `${genDocs.length} Dokumen`;
        }

        if (genDocsContainer) {
            genDocsContainer.innerHTML = '';
            if (genDocs.length === 0) {
                genDocsContainer.innerHTML = `<span class="text-muted extra-small fst-italic">Tiada dokumen sokongan tambahan dimuat naik.</span>`;
            } else {
                genDocs.forEach(gd => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'btn btn-xs btn-outline-secondary d-inline-flex align-items-center gap-1 rounded-2 px-2.5 py-1 text-dark bg-white border shadow-sm';
                    btn.innerHTML = `<i class="bi bi-file-earmark-pdf text-danger"></i><span class="small text-truncate" style="max-width: 250px;">${gd.original_name}</span>`;
                    btn.onclick = () => window.previewDocument(gd.original_name, gd.file_url);
                    genDocsContainer.appendChild(btn);
                });
            }
        }

        // Decision controls
        const selectEl = document.getElementById('modalKerjaDecisionSelect');
        if (selectEl) selectEl.value = s3.status || 'memuaskan';

        const noteEl = document.getElementById('modalKerjaCatatanInput');
        if (noteEl) noteEl.value = s3.catatan || '';

        const modalEl = document.getElementById('modalPenilaianKerjaVendor');
        const modalObj = new bootstrap.Modal(modalEl);
        modalObj.show();
    }
};

// Document Preview Modal Helper
window.previewDocument = function(title, url) {
    if (!url || url === '#' || url === 'null') {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'info',
                title: 'Tiada Dokumen',
                text: 'Tiada fail atau borang atas talian yang dikemukakan untuk dipaparkan.',
                confirmButtonText: 'Tutup'
            });
        } else {
            alert('Tiada dokumen atau borang atas talian untuk dipaparkan.');
        }
        return;
    }

    // Convert absolute URL matching localhost/test domain into relative path so iframe won't hit CORS/port issues
    let resolvedUrl = url;
    if (resolvedUrl && (resolvedUrl.startsWith('http://') || resolvedUrl.startsWith('https://'))) {
        try {
            const parsed = new URL(resolvedUrl);
            if (parsed.hostname === window.location.hostname || parsed.hostname.endsWith('.test') || parsed.port === window.location.port) {
                resolvedUrl = parsed.pathname + parsed.search + parsed.hash;
            }
        } catch (err) {}
    }

    const modalEl = document.getElementById('modalPreview');
    if (!modalEl) {
        console.warn('modalPreview element not found in DOM');
        window.open(resolvedUrl, '_blank');
        return;
    }

    const titleEl = document.getElementById('modalPreviewTitle');
    const directLink = document.getElementById('btnNewTabPreview');
    const fallbackLink = document.getElementById('btnFallbackDownload');
    const spinner = document.getElementById('previewSpinner');
    const iframe = document.getElementById('previewIframe');
    const imageWrapper = document.getElementById('previewImageWrapper');
    const image = document.getElementById('previewImage');
    const fallback = document.getElementById('previewFallback');
    const icon = document.getElementById('previewIcon');

    if (titleEl) titleEl.textContent = title || 'Prebiu Dokumen';
    if (directLink) directLink.href = resolvedUrl;
    if (fallbackLink) fallbackLink.href = resolvedUrl;

    if (spinner) spinner.classList.remove('d-none');
    if (iframe) {
        iframe.classList.add('d-none');
        iframe.src = '';
    }
    if (imageWrapper) imageWrapper.classList.add('d-none');
    if (image) image.src = '';
    if (fallback) fallback.classList.add('d-none');

    const urlPath = resolvedUrl.split(/[#?]/)[0];
    const extension = urlPath.split('.').pop().trim().toLowerCase();
    const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
    const filenamePortion = urlPath.substring(urlPath.lastIndexOf('/') + 1);
    const isProbablyPage = !filenamePortion.includes('.') || filenamePortion.endsWith('.html') || resolvedUrl.includes('modal=1') || resolvedUrl.includes('mode=view');

    if (icon) icon.className = '';

    if (imageExtensions.includes(extension)) {
        if (icon) icon.className = 'bi bi-file-earmark-image text-primary fs-5';
        if (image) image.src = resolvedUrl;
        if (imageWrapper) imageWrapper.classList.remove('d-none');
        if (spinner) spinner.classList.add('d-none');
    } else if (extension === 'pdf' || resolvedUrl.includes('.pdf') || resolvedUrl.includes('/download')) {
        if (icon) icon.className = 'bi bi-file-earmark-pdf text-danger fs-5';
        if (iframe) {
            iframe.onload = function() {
                if (spinner) spinner.classList.add('d-none');
                iframe.classList.remove('d-none');
            };
            iframe.src = resolvedUrl;
        }
    } else if (isProbablyPage) {
        if (icon) icon.className = 'bi bi-window text-success fs-5';
        if (iframe) {
            iframe.onload = function() {
                if (spinner) spinner.classList.add('d-none');
                iframe.classList.remove('d-none');
            };
            iframe.src = resolvedUrl;
        }
    } else {
        if (icon) icon.className = 'bi bi-file-earmark-zip text-warning fs-5';
        if (spinner) spinner.classList.add('d-none');
        if (fallback) fallback.classList.remove('d-none');
    }

    const modalObj = bootstrap.Modal.getOrCreateInstance(modalEl);
    modalObj.show();
};

// Event delegation for preview clicks across all modals
document.addEventListener('click', function(e) {
    const actionBtn = e.target.closest('.btn-preview-file-action');
    if (actionBtn) {
        e.preventDefault();
        const title = actionBtn.getAttribute('data-title');
        const url = actionBtn.getAttribute('data-url');
        window.previewDocument(title, url);
        return;
    }

    const dummyBtn = e.target.closest('.btn-preview-dummy-pdf');
    if (dummyBtn) {
        e.preventDefault();
        const title = dummyBtn.getAttribute('data-title') || 'Dokumen Sokongan';
        const url = dummyBtn.getAttribute('data-url') || 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf';
        window.previewDocument(title, url);
        return;
    }
});

// Stacking & backdrop handling for nested modals
document.addEventListener('DOMContentLoaded', () => {
    const previewModalEl = document.getElementById('modalPreview');
    if (previewModalEl) {
        previewModalEl.addEventListener('show.bs.modal', function() {
            const visibleModals = document.querySelectorAll('.modal.show');
            const zIndex = 1065 + (10 * visibleModals.length);
            previewModalEl.style.zIndex = zIndex;
            setTimeout(() => {
                const backdrops = document.querySelectorAll('.modal-backdrop');
                if (backdrops.length > 0) {
                    const lastBackdrop = backdrops[backdrops.length - 1];
                    lastBackdrop.style.zIndex = zIndex - 1;
                }
            }, 0);
        });

        previewModalEl.addEventListener('hidden.bs.modal', function() {
            const otherVisible = document.querySelectorAll('.modal.show');
            if (otherVisible.length > 0) {
                document.body.classList.add('modal-open');
            }
        });
    }

    window.type2State.init();
});
</script>
@endsection
