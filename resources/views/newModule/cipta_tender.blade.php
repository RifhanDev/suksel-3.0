@extends('layouts.v3.master')

@section('styles')
<style>
    /* --- TIMELINE --- */
    .stepper-wrapper {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
        padding: 0 40px;
    }

    /* Track container for the line */
    .stepper-track {
        position: absolute;
        top: 15px;
        left: 40px;
        right: 40px;
        height: 4px;
        background: #e2e8f0;
        border-radius: 4px;
        z-index: 0;
        overflow: hidden;
    }

    /* Progress fill inside the track */
    .stepper-progress {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 0%;
        background: var(--sg-red);
        border-radius: 4px;
        transition: width 0.4s ease;
    }

    /* Progress states */
    .stepper-wrapper[data-step="1"] .stepper-progress {
        width: 50%;
        background: var(--sg-red);
    }
    .stepper-wrapper[data-step="2"] .stepper-progress {
        width: 100%;
        background: linear-gradient(90deg, #10b981 0%, #10b981 10%, var(--sg-red) 100%);
    }

    .stepper-item {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
    }

    .step-counter {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #fff;
        border: 3px solid #cbd5e1;
        color: #94a3b8;
        font-weight: 700;
        font-size: 0.85rem;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 8px;
        transition: all 0.3s ease;
        position: relative;
    }

    .step-name {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        transition: color 0.3s ease;
    }

    /* Active Step */
    .stepper-item.active .step-counter {
        border-color: var(--sg-red);
        background: var(--sg-red);
        color: #fff;
        box-shadow: 0 0 0 5px var(--sg-red-soft), 0 2px 8px rgba(196, 30, 58, 0.3);
        transform: scale(1.05);
    }
    .stepper-item.active .step-name {
        color: var(--sg-red);
    }

    /* Completed Step */
    .stepper-item.completed .step-counter {
        border-color: #10b981;
        background: #10b981;
        color: #fff;
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);
    }
    .stepper-item.completed .step-counter::after {
        content: '✓';
        font-size: 0.9rem;
    }
    .stepper-item.completed .step-counter span {
        display: none;
    }
    .stepper-item.completed .step-name {
        color: #10b981;
    }

    /* --- CONDITION BUILDER --- */
    .condition-builder {
        background: #f8fafc;
        border-radius: 12px;
        /* padding: 20px; */
    }

    .condition-group {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
    }

    .condition-group-header {
        background: linear-gradient(135deg, var(--sg-red) 0%, #a01830 100%);
        color: #fff;
        padding: 14px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 12px 12px 0 0;
    }

    .condition-group-header .group-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .condition-group-header .group-title .group-icon {
        width: 32px;
        height: 32px;
        background: rgba(255,255,255,0.2);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .condition-group-header .group-title h6 {
        margin: 0;
        font-size: 0.9rem;
        font-weight: 700;
    }

    .condition-group-header .group-title small {
        display: block;
        font-size: 0.7rem;
        font-weight: 400;
        opacity: 0.85;
        margin-top: 2px;
    }

    .condition-group-body {
        padding: 0;
    }

    /* Condition Row */
    .condition-row {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        position: relative;
        display: flex;
        align-items: flex-end;
        gap: 16px;
    }

    .condition-row:last-child {
        border-bottom: none;
    }

    .condition-row:hover {
        background: #fafbfc;
    }

    .condition-row .row-number {
        width: 28px;
        height: 28px;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        color: #64748b;
        flex-shrink: 0;
    }

    .condition-row .row-fields {
        flex: 1;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .condition-row .row-fields .form-label {
        height: 20px;
        margin-bottom: 6px;
    }

    .condition-row .row-actions {
        flex-shrink: 0;
        align-self: flex-end;
        padding-bottom: 1px;
    }

    .condition-row .btn-remove {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        border: none;
        background: var(--sg-red);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .condition-row .btn-remove:hover {
        background: #a01830;
        color: #fff;
    }

    /* Row Connector Inline */
    .condition-connector {
        display: flex;
        align-items: center;
        padding: 0 20px;
        background: #f8fafc;
    }

    .condition-connector::before,
    .condition-connector::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }

    .connector-pill {
        background: var(--sg-yellow);
        color: #1a1a1a;
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        width: 90px;
        padding: 6px 12px;
        padding-right: 26px;
        border-radius: 16px;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        border: none;
        margin: 8px 12px;
        text-align: center;
        transition: all 0.2s ease;
    }

    .connector-pill:focus {
        outline: none;
        box-shadow: 0 0 0 3px var(--sg-red-soft);
    }

    .connector-pill-wrapper {
        position: relative;
        display: inline-flex;
        align-items: center;
    }

    .connector-pill-wrapper::after {
        content: '';
        position: absolute;
        right: 22px;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 5px solid #1a1a1a;
        pointer-events: none;
    }

    /* Field sizing within condition rows */
    .condition-row .field-logic {
        width: 140px;
        flex-shrink: 0;
    }

    .condition-row .field-main {
        flex: 1;
        min-width: 200px;
    }

    .condition-row .field-grade {
        width: 120px;
        flex-shrink: 0;
    }

    /* Info text */
    .condition-info {
        font-size: 0.75rem;
        color: #94a3b8;
        padding: 12px 20px;
        background: #fafbfc;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .condition-info svg {
        flex-shrink: 0;
    }

    /* CIDB Layout */
    .cidb-fields {
        flex-direction: column;
        gap: 12px;
    }

    .cidb-row-top {
        width: 100%;
    }

    .cidb-row-top .field-grade-full {
        width: 100%;
    }

    .cidb-row-bottom {
        display: flex;
        gap: 12px;
        width: 100%;
        align-items: flex-start;
    }

    .cidb-row-bottom .form-label {
        height: 20px;
        margin-bottom: 6px;
    }

    .cidb-row-bottom .field-logic {
        width: 160px;
        flex-shrink: 0;
    }

    .cidb-row-bottom .field-main {
        flex: 1;
    }

    /* Utilities */
    .text-balance { text-wrap: balance; }

    /* --- MOBILE --- */
    @media (max-width: 991px) {
        /* Timeline */
        .stepper-wrapper {
            padding: 0 20px;
        }
        .stepper-track {
            left: 40px;
            right: 40px;
        }
        .step-name {
            font-size: 0.65rem;
        }
        .step-counter {
            width: 30px;
            height: 30px;
            font-size: 0.75rem;
        }

        /* Condition Builder */
        .condition-group-header {
            padding: 12px 16px;
        }
        .condition-group-header .group-title .group-icon {
            width: 28px;
            height: 28px;
        }
        .condition-group-header .group-title h6 {
            font-size: 0.8rem;
        }
        .condition-group-header .group-title small {
            font-size: 0.65rem;
        }

        /* Condition Rows */
        .condition-row {
            padding: 14px 16px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .condition-row .row-number {
            width: 24px;
            height: 24px;
            font-size: 0.7rem;
        }
        .condition-row .row-fields {
            flex-direction: column;
            width: 100%;
            gap: 10px;
        }
        .condition-row .field-logic,
        .condition-row .field-grade,
        .condition-row .field-main {
            width: 100%;
            flex: none;
        }
        .condition-row .row-actions {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .condition-row .btn-remove {
            width: 28px;
            height: 28px;
        }

        /* Connectors */
        .section-connector {
            padding: 16px 0;
        }
        .section-connector-select {
            width: 100px;
            font-size: 0.7rem;
            padding: 8px 12px;
            padding-right: 28px;
        }
        .section-connector-select-wrapper::after {
            right: 10px;
        }
        .condition-connector {
            padding: 0 16px;
        }
        .connector-pill {
            width: 85px;
            font-size: 0.65rem;
            padding: 5px 10px;
            padding-right: 22px;
        }
        .connector-pill-wrapper::after {
            right: 18px;
        }

        /* Add Button */
        .btn-add-condition {
            padding: 10px;
            font-size: 0.75rem;
        }

        /* Info */
        .condition-info {
            font-size: 0.7rem;
            padding: 10px 16px;
        }

        /* CIDB Layout Mobile */
        .cidb-row-bottom {
            flex-direction: column;
            gap: 10px;
        }
        .cidb-row-bottom .field-logic {
            width: 100%;
        }

        /* Header Area */
        .d-flex.justify-content-between.align-items-center.mb-4 {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 12px;
        }
        .d-flex.gap-3.align-items-center.bg-white.px-3.py-2.rounded-2.shadow-sm.border {
            width: 100%;
            flex-wrap: wrap;
            gap: 8px !important;
            padding: 10px !important;
        }
    }

    @media (max-width: 576px) {
        .stepper-wrapper {
            padding: 0 10px;
        }
        .stepper-track {
            left: 30px;
            right: 30px;
        }
        .step-name {
            font-size: 0.6rem;
        }
        .condition-group-header .group-title small {
            display: none;
        }
    }
</style>
@endsection

@section('content')
    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <!-- Title -->
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Cipta Tender Baru</h3>
            <p class="text-muted small m-0">Sila lengkapkan maklumat di bawah.</p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-3 bg-white px-3 py-2 rounded-2 shadow-sm border w-lg-auto">
            <!-- Item 1 -->
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-dark border">NO. SEBUT HARGA</span>
                <span class="small text-primary fw-bold text-muted">Belum Dijana</span>
            </div>
            <!-- Divider (Desktop Only) -->
            <div class="vr d-none d-lg-block text-muted opacity-25" style="height: 20px;"></div>
            <!-- Item 2 -->
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-dark border">PTJ</span>
                <span id="ptj-display" class="small text-primary fw-bold text-truncate" style="max-width: 280px;">-</span>
            </div>
            <!-- Divider (Desktop Only) -->
            <div class="vr d-none d-lg-block text-muted opacity-25" style="height: 20px;"></div>
            <!-- Item 3 -->
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-dark border">STATUS</span>
                <span class="small text-muted fw-bold">Tiada</span>
            </div>
        </div>
    </div>

    <!-- TIMELINE -->
    <div class="stepper-wrapper" data-step="1" id="stepper-wrapper">
        <!-- Progress -->
        <div class="stepper-track">
            <div class="stepper-progress" id="stepper-progress"></div>
        </div>

        <div class="stepper-item active" id="step1-indicator">
            <div class="step-counter"><span>1</span></div>
            <div class="step-name">Maklumat Asas</div>
        </div>
        <div class="stepper-item" id="step2-indicator">
            <div class="step-counter"><span>2</span></div>
            <div class="step-name">Kod Bidang</div>
        </div>
    </div>

    <form id="createTenderForm" action="{{ route('storeCiptaTender') }}" method="POST">
        @csrf
        
        <div class="modern-card">
            
            <!-- ======================= STEP 1: MAKLUMAT UMUM ======================= -->
            <div id="step1-content">
                <!-- Header -->
                <div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    <span class="fw-bold text-dark text-uppercase small">Maklumat Umum</span>
                </div>

                <div class="p-4">
                    
                    <!-- Alert -->
                    <div class="alert-selangor mb-4">
                        <div class="alert-selangor-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        </div>
                        <div class="small lh-sm">
                            <strong>Perhatian</strong>
                            Untuk perkhidmatan yang memerlukan bayaran secara progresif, sila pilih Jenis Pemenuhan sebagai <u>Bermasa (Bila Perlu)</u>.
                        </div>
                    </div>

                    <!-- ROW 1 -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Kaedah Perolehan</label>
                            <select class="form-select" name="type">
                                <option selected disabled>Pilih...</option>
                                @foreach ($kaedahPerolehan as $kaedahOlehan)
                                    <option value="{{ $kaedahOlehan->id }}" {{ old('type') == $kaedahOlehan->id ? 'selected' : '' }}>{{ $kaedahOlehan->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategori Jenis Perolehan<span class="text-danger">*</span></label>
                            <select class="form-select" name="kategori_perolehan" required>
                                <option selected disabled>Pilih...</option>
                                @foreach ($kategoriPerolehan as $kategoriOlehan)
                                    <option value="{{ $kategoriOlehan->id }}" {{ old('kategori_perolehan') == $kategoriOlehan->id ? 'selected' : '' }}>{{ $kategoriOlehan->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- ROW 2 -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Tajuk Perolehan <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="name" rows="2" placeholder="Masukkan tajuk tender/sebut harga..." required>{{ old('name') }}</textarea>
                        </div>
                    </div>

                    <!-- ROW 3 -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="form-label">Disediakan Untuk PTJ<span class="text-danger">*</span></label>
                            <select class="form-select" name="ptj_id" id="ptj-select" required>
                                <option selected disabled>Pilih...</option>
                                @foreach ($organizations as $org)
                                    <option value="{{ $org->id }}" {{ old('ptj_id') == $org->id ? 'selected' : '' }}>{{ strtoupper($org->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- ROW 4 -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label">No. Rujukan Fail</label>
                            <input type="text" class="form-control" name="ref_number" value="{{ old('ref_number') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">No. Tender / Sebut Harga<span class="text-danger">*</span></label>
                            <input type="text" class="form-control fw-bold" name="no_tender" value="{{ old('no_tender') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Harga Indikatif Jabatan</label>
                            <div class="input-group">
                                <span class="input-group-text">RM</span>
                                <input type="number" class="form-control text-end" name="harga_indikatif" value="{{ old('harga_indikatif') }}" min="0" step="0.01" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Anggaran Jabatan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-dark">RM</span>
                                <input type="number" class="form-control text-end fw-bold" name="anggaran_jabatan" value="{{ old('anggaran_jabatan') }}" min="0" step="0.01" placeholder="0.00" required>
                            </div>
                        </div>
                    </div>

                    <!-- ROW 5 -->
                    <div class="row mb-4 g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tarikh Dicipta<span class="text-danger">*</span></label>
                            <input type="text" class="form-control datepicker" name="tarikh_dicipta" value="{{ old('tarikh_dicipta') }}" placeholder="Pilih tarikh..." required readonly>
                        </div>
                        <div class="col-md-6">
                            <!-- For Kerja -->
                            <div id="jenis_tender_group" class="d-none">
                                <label class="form-label">Jenis Tender / Sebut Harga<span class="text-danger">*</span></label>
                                <select class="form-select" name="jenis_tender" id="jenis_tender">
                                    <option selected disabled>Pilih...</option>
                                    @foreach ($jenisTender as $jt)
                                        <option value="{{ $jt->id }}" {{ old('jenis_tender') == $jt->id ? 'selected' : '' }}>{{ $jt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- For Perkhidmatan / Bekalan -->
                            <div id="jenis_kontrak_group" class="d-none">
                                <label class="form-label">Jenis Kontrak<span class="text-danger">*</span></label>
                                <select class="form-select" name="jenis_kontrak" id="jenis_kontrak">
                                    <option selected disabled>Pilih...</option>
                                    <option value="1" {{ old('jenis_kontrak') == '1' ? 'selected' : '' }}>Kementerian</option>
                                    <option value="2" {{ old('jenis_kontrak') == '2' ? 'selected' : '' }}>Jabatan</option>
                                    <option value="3" {{ old('jenis_kontrak') == '3' ? 'selected' : '' }}>Agensi</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr class="border-light mb-4">

                    <!-- ROW 6: 3 LAYOUT GRID -->
                    <div class="row g-3">
                        
                        <!-- 1. Peruntukan (Radio) -->
                        <div class="col-lg-4">
                            <div class="settings-grid-box">
                                <label class="form-label mb-2">Sumber Peruntukan <span class="text-danger">*</span></label>
                                <div class="d-flex flex-column gap-2 mb-2">
                                    <div class="segmented-control">
                                        <input type="radio" name="sumber_peruntukan" id="sp_pem" value="pembangunan" {{ old('sumber_peruntukan', 'pembangunan') == 'pembangunan' ? 'checked' : '' }}>
                                        <label for="sp_pem">Pembangunan</label>

                                        <input type="radio" name="sumber_peruntukan" id="sp_meng" value="mengurus" {{ old('sumber_peruntukan') == 'mengurus' ? 'checked' : '' }}>
                                        <label for="sp_meng">Mengurus</label>

                                        <input type="radio" name="sumber_peruntukan" id="sp_lain" value="lain" {{ old('sumber_peruntukan') == 'lain' ? 'checked' : '' }}>
                                        <label for="sp_lain">Lain</label>
                                    </div>
                                    <textarea class="form-control d-none" id="sumber_lain_input" name="sumber_lain_text" rows="2" placeholder="Nyatakan...">{{ old('sumber_lain_text') }}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <!-- For Kerja -->
                                        <div id="tempoh_siap_group" class="d-none">
                                            <label class="form-label">Tempoh Siap Maksima</label>
                                            <div class="input-group input-group-md">
                                                <input type="number" class="form-control" name="tempoh_siap_val" value="{{ old('tempoh_siap_val') }}" placeholder="0">
                                                <select class="form-select" name="tempoh_siap_unit" style="max-width: 100px;">
                                                    <option value="1" {{ old('tempoh_siap_unit') == '1' ? 'selected' : '' }}>Minggu</option>
                                                    <option value="2" {{ old('tempoh_siap_unit') == '2' ? 'selected' : '' }}>Bulan</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- For Perkhidmatan / Bekalan -->
                                        <div id="tempoh_kontrak_group" class="d-none">
                                            <label class="form-label">Tempoh Kontrak / Penyiapan (Bulan)</label>
                                            <input type="number" class="form-control" name="tempoh_kontrak_bulan" value="{{ old('tempoh_kontrak_bulan') }}" placeholder="0" min="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Scope & Duration -->
                        <div class="col-lg-4">
                            <div class="settings-grid-box">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="form-label">Kategori Perolehan<span class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" name="kategori_perolehan_detail" id="kategori_perolehan_detail">
                                            <option selected disabled>Pilih...</option>
                                            <!-- Options will be set dynamically by JavaScript -->
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">No. Kontrak Sedia Ada <i>(Jika Ada)</i></label>
                                        <input type="text" class="form-control fw-bold" name="no_kontrak" value="{{ old('no_kontrak') }}">
                                    </div>
                                    <!-- Lokaliti -->
                                    <div class="col-6">
                                        <label class="form-label">Lokaliti Liputan</label>
                                        <select class="form-select form-select-sm" name="lokaliti_id">
                                            <option selected disabled>Pilih...</option>
                                            @foreach($lokalitis as $lokaliti)
                                                <option value="{{ $lokaliti->id }}" {{ old('lokaliti_id') == $lokaliti->id ? 'selected' : '' }}>{{ $lokaliti->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Zon Toggle -->
                                    <div class="col-6">
                                        <label class="form-label">Zon/Lokasi</label>
                                        <div class="segmented-control">
                                            <input type="radio" name="zon_lokasi" id="z_y" value="1" {{ old('zon_lokasi') == '1' ? 'checked' : '' }}>
                                            <label for="z_y">Ya</label>
                                            <input type="radio" name="zon_lokasi" id="z_t" value="0" {{ old('zon_lokasi', '0') == '0' ? 'checked' : '' }}>
                                            <label for="z_t">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Lokaliti, Zon, JK, etc. -->
                        <div class="col-lg-4">
                            <div class="settings-grid-box">
                                <div class="row g-2">
                                    <!-- JK Toggle -->
                                    <div class="col-12" id="jawatankuasa_group">
                                        <label class="form-label">Jawatankuasa Spesifikasi<span class="text-danger">*</span></label>
                                        <div class="segmented-control">
                                            <input type="radio" name="jawatankuasa" id="jk_y" value="1" {{ old('jawatankuasa') == '1' ? 'checked' : '' }}>
                                            <label for="jk_y">Ya</label>
                                            <input type="radio" name="jawatankuasa" id="jk_t" value="0" {{ old('jawatankuasa', '0') == '0' ? 'checked' : '' }}>
                                            <label for="jk_t">Tidak</label>
                                        </div>
                                    </div>
                                    <!-- Lawatan Toggle -->
                                    <div class="col-12">
                                        <label class="form-label">Taklimat Tender/Lawatan Tapak<span class="text-danger">*</span></label>
                                        <div class="segmented-control">
                                            <input type="radio" name="lawatan_tapak" id="lt_y" value="1" {{ old('lawatan_tapak') == '1' ? 'checked' : '' }}>
                                            <label for="lt_y">Ada</label>
                                            <input type="radio" name="lawatan_tapak" id="lt_t" value="0" {{ old('lawatan_tapak', '0') == '0' ? 'checked' : '' }}>
                                            <label for="lt_t">Tiada</label>
                                        </div>
                                    </div>
                                    <!-- Fizikal Toggle -->
                                    <div class="col-6">
                                        <label class="form-label">Penghantaran Fizikal<span class="text-danger">*</span></label>
                                        <div class="segmented-control">
                                            <input type="radio" name="fizikal" id="pf_y" value="1" {{ old('fizikal') == '1' ? 'checked' : '' }}>
                                            <label for="pf_y">Ya</label>
                                            <input type="radio" name="fizikal" id="pf_t" value="0" {{ old('fizikal', '0') == '0' ? 'checked' : '' }}>
                                            <label for="pf_t">Tidak</label>
                                        </div>
                                    </div>
                                    <!-- Terbuka Toggle -->
                                    <div class="col-6">
                                        <label class="form-label">Terbuka Kepada<span class="text-danger">*</span></label>
                                        <div class="segmented-control">
                                            <input type="radio" name="terbuka_kepada" id="tk_b" value="bumiputera" {{ old('terbuka_kepada') == 'bumiputera' ? 'checked' : '' }}>
                                            <label for="tk_b">Bumiputera</label>
                                            <input type="radio" name="terbuka_kepada" id="tk_s" value="semua" {{ old('terbuka_kepada', 'semua') == 'semua' ? 'checked' : '' }}>
                                            <label for="tk_s">Semua</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div> <!-- End Settings Grid -->

                </div>
            </div>
            <!-- END STEP 1 -->


            <!-- ======================= STEP 2: KOD BIDANG ======================= -->
            <div id="step2-content" class="d-none">
                <!-- Header -->
                <div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    <span class="fw-bold text-dark text-uppercase small">Kod Bidang & Syarat</span>
            </div>

                <div class="p-4">

                    <div class="alert-selangor mb-4">
                        <div class="alert-selangor-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        </div>
                        <div class="small mt-1">
                            Syarikat Selangor Sahaja atau Syarikat Selangor dan Lain-lain Negeri wajib di isi.
                        </div>
                    </div>

                    <div class="condition-builder">
                        <!-- MOF SECTION -->
                        <div class="condition-group">
                            <div class="condition-group-header">
                                <div class="group-title">
                                    <div class="group-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                    </div>
                                    <div>
                                        <h6>Kod Bidang MOF</h6>
                                        <small>Kementerian Kewangan Malaysia</small>
                                    </div>
                                </div>
                            </div>
                            <div class="condition-group-body">
                                <div id="mof-wrapper">
                                    <!-- Initial Row -->
                                    <div class="condition-row mof-row" data-index="0">
                                        <div class="row-number">1</div>
                                        <div class="row-fields">
                                            <div class="field-logic">
                                                <label class="form-label">Hubungan</label>
                                                <div class="segmented-control">
                                                    <input type="radio" name="mof[0][logic_mid]" id="mof0_or" value="OR" {{ old('mof.0.logic_mid', 'OR') == 'OR' ? 'checked' : '' }}>
                                                    <label for="mof0_or">ATAU</label>
                                                    <input type="radio" name="mof[0][logic_mid]" id="mof0_and" value="AND" {{ old('mof.0.logic_mid') == 'AND' ? 'checked' : '' }}>
                                                    <label for="mof0_and">DAN</label>
                                                </div>
                                            </div>
                                            <div class="field-main">
                                                <label class="form-label">Kod Bidang</label>
                                                <select class="selectize" name="mof[0][code][]" multiple>
                                                    @foreach(App\Code::where('type', 'mof')->orderBy('code')->get() as $code)
                                                        <option value="{{ $code->id }}" {{ (is_array(old('mof.0.code')) && in_array($code->id, old('mof.0.code'))) ? 'selected' : '' }}>{{ $code->label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="condition-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                    Syarikat mesti mempunyai pendaftaran MOF yang sah dengan kod bidang yang dipilih
                                </div>
                            </div>
                            <button type="button" class="btn-add-condition" id="btn-add-mof">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                Tambah Kod Bidang MOF
                            </button>
                        </div>

                        <!-- SECTION CONNECTOR -->
                        <div class="position-relative w-100 py-5 d-flex justify-content-center align-items-center">
                            
                            <!-- Vertical Line -->
                            <div class="position-absolute h-100 border-start border-2 border-secondary opacity-25" 
                                style="left: 50%; transform: translateX(-50%); z-index: 0;">
                            </div>

                            <!-- Connector Box -->
                            <div class="col-12 col-md-6 position-relative z-1">
                                
                                <div class="bg-white rounded-3 shadow-sm border" style="border-color: #e2e8f0;">
                                    <!-- Top -->
                                    <div class="d-flex align-items-center justify-content-between p-3 gap-3">
                                        <!-- Left: Label & Icon -->
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle bg-light border text-secondary p-1" style="width: 32px; height: 32px; flex-shrink: 0;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                                </svg>
                                            </div>
                                            <span class="fw-bold text-uppercase text-muted small ms-2 text-nowrap">
                                                Hubungan
                                            </span>
                                        </div>

                                        <!-- Right: Dropdown -->
                                        <div class="flex-grow-1">
                                            <select name="section_logic" class="form-select form-select-sm fw-bold text-dark border-secondary bg-light" style="cursor: pointer;">
                                                <option value="AND" {{ old('section_logic', 'AND') == 'AND' ? 'selected' : '' }}>DAN</option>
                                                <option value="OR" {{ old('section_logic') == 'OR' ? 'selected' : '' }}>ATAU</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Bottom: Hint Text -->
                                    <div class="bg-light rounded-bottom-3 px-3 py-2 border-top d-flex align-items-start gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-1 flex-shrink-0">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="16" x2="12" y2="12"></line>
                                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                        </svg>
                                        <p class="m-0 text-muted lh-sm" style="font-size: 0.75rem;">
                                            Pilih <strong>'DAN'</strong> jika syarikat wajib mematuhi kedua-dua kelayakan (MOF &amp; CIDB) secara serentak.
                                        </p>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <!-- CIDB SECTION -->
                        <div class="condition-group">
                            <div class="condition-group-header">
                                <div class="group-title">
                                    <div class="group-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"></rect><path d="M12 12h.01"></path><path d="M17 12h.01"></path><path d="M7 12h.01"></path></svg>
                                    </div>
                                    <div>
                                        <h6>Kod Bidang</h6>
                                        <small>Lembaga Pembangunan Industri Pembinaan</small>
                                    </div>
                                </div>
                            </div>
                            <div class="condition-group-body">
                                <div id="cidb-wrapper">
                                    <!-- Initial Row -->
                                    <div class="condition-row cidb-row" data-index="0">
                                        <div class="row-number">1</div>
                                        <div class="row-fields cidb-fields">
                                            <!-- Row 1: Gred CIDB -->
                                            <div class="cidb-row-top">
                                                <div class="field-grade-full">
                                                    <label class="form-label">Gred CIDB</label>
                                                    <select class="selectize" name="cidb[0][grade]" multiple>
                                                        <option value="" selected disabled>Pilih Gred...</option>
                                                        @foreach(App\Code::where('type', 'cidb-g')->orderBy('code')->get() as $code)
                                                            <option value="{{ $code->id }}" {{ (is_array(old('cidb.0.grade')) && in_array($code->id, old('cidb.0.grade'))) ? 'selected' : '' }}>{{ $code->label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- Row 2: Pengkhususan -->
                                            <div class="cidb-row-bottom">
                                                <div class="field-logic">
                                                    <label class="form-label">Hubungan</label>
                                                    <div class="segmented-control">
                                                        <input type="radio" name="cidb[0][logic_mid]" id="cidb0_and" value="AND" {{ old('cidb.0.logic_mid', 'AND') == 'AND' ? 'checked' : '' }}>
                                                        <label for="cidb0_and">DAN</label>
                                                        <input type="radio" name="cidb[0][logic_mid]" id="cidb0_or" value="OR" {{ old('cidb.0.logic_mid') == 'OR' ? 'checked' : '' }}>
                                                        <label for="cidb0_or">ATAU</label>
                                                    </div>
                                                </div>
                                                <div class="field-main">
                                                    <label class="form-label">Pengkhususan</label>
                                                    <select class="selectize" name="cidb[0][spec][]" multiple>
                                                        @foreach(App\Code::where('type', 'cidb-c')->orderBy('code')->get() as $code)
                                                            <option value="{{ $code->id }}" {{ (is_array(old('cidb.0.spec')) && in_array($code->id, old('cidb.0.spec'))) ? 'selected' : '' }}>{{ $code->label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="condition-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                    Untuk kerja pembinaan, syarikat mesti berdaftar dengan CIDB mengikut gred dan pengkhususan
                                </div>
                            </div>
                            <button type="button" class="btn-add-condition" id="btn-add-cidb">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                Tambah Pengkhususan
                            </button>
                        </div>
                    </div>

            </div>

            </div>
            <!-- END STEP 2 -->

            <!-- FOOTER -->
            <div class="bg-light p-4 border-top d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-outline-secondary fw-bold px-4 d-none rounded-3" id="btn-back">
                    Kembali
                </button>

                    
                <div class="ms-auto">
                    <button type="button" class="btn btn-selangor fw-bold px-4 rounded-3 d-flex align-items-center gap-2" id="btn-next">
                        Seterusnya 
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>

                    <button type="submit" class="btn btn-success fw-bold px-4 d-none rounded-3 d-flex align-items-center gap-2" id="btn-submit">
                        Hantar Tender
                    </button>
                </div>
            </div>

        </div>
    </form>
@endsection

@section('scripts')
    <script>
        // --- DROPDOWN OPTIONS FOR DYNAMIC ROWS FOR SECTION 2 FORM ---
        var mofOptions = `@foreach(App\Code::where('type', 'mof')->orderBy('code')->get() as $code)<option value="{{ $code->id }}">{{ $code->label }}</option>@endforeach`;
        var cidbSpecOptions = `@foreach(App\Code::where('type', 'cidb-c')->orderBy('code')->get() as $code)<option value="{{ $code->id }}">{{ $code->label }}</option>@endforeach`;

        $(document).ready(function()
        {
            const kategoriPerolehanDropdown = $('select[name="kategori_perolehan"]');
            const kategoriDetailDropdown = $('select[name="kategori_perolehan_detail"]');

            function updateKategoriPerolehanOptions()
            {
                const selectedKategori = kategoriPerolehanDropdown.val();
                kategoriDetailDropdown.empty();
                kategoriDetailDropdown.append('<option selected disabled>Pilih...</option>');
                
                if (!selectedKategori)
                {
                    return;
                }

                $.ajax(
                {
                    url: '{{ route("getTypeOfPerolehanByKategori") }}',
                    type: 'GET',
                    data: { kategori_id: selectedKategori },
                    success: function(data)
                    {
                        data.forEach(option =>
                        {
                            kategoriDetailDropdown.append(`<option value="${option.id}">${option.name}</option>`);
                        });
                    },
                    error: function(xhr, status, error)
                    {
                        console.error('Error fetching type of perolehan:', error);
                    }
                });
            }

            function updateJenisField() {
                const selectedKategori = kategoriPerolehanDropdown.val();
                const jenisTenderGroup = $('#jenis_tender_group');
                const jenisKontrakGroup = $('#jenis_kontrak_group');
                const jenisTenderSelect = $('#jenis_tender');
                const jenisKontrakSelect = $('#jenis_kontrak');

                if (selectedKategori === '1' || selectedKategori === '2')
                {
                    // Show Jenis Kontrak for old values (1 and 2), hide Jenis Tender
                    jenisTenderGroup.addClass('d-none');
                    jenisTenderSelect.removeAttr('required');
                    
                    jenisKontrakGroup.removeClass('d-none');
                    jenisKontrakSelect.attr('required', 'required');
                }
                else
                {
                    // Show Jenis Tender for other new values, hide Jenis Kontrak
                    jenisTenderGroup.removeClass('d-none');
                    jenisTenderSelect.attr('required', 'required');
                    
                    jenisKontrakGroup.addClass('d-none');
                    jenisKontrakSelect.removeAttr('required');
                }
            }

            // Function to toggle between Tempoh Siap and Tempoh Kontrak
            function updateTempohField()
            {
                const selectedKategori = kategoriPerolehanDropdown.val();
                const tempohSiapGroup = $('#tempoh_siap_group');
                const tempohKontrakGroup = $('#tempoh_kontrak_group');

                if (selectedKategori === '1' || selectedKategori === '2')
                {
                    // Show Tempoh Kontrak for old values (1 and 2), hide Tempoh Siap
                    tempohSiapGroup.addClass('d-none');
                    tempohKontrakGroup.removeClass('d-none');
                }
                else
                {
                    // Show Tempoh Siap for other new values, hide Tempoh Kontrak
                    tempohSiapGroup.removeClass('d-none');
                    tempohKontrakGroup.addClass('d-none');
                }
            }

            // Function to toggle Jawatankuasa Spesifikasi field
            function updateJawatankuasaField()
            {
                const selectedKategori = kategoriPerolehanDropdown.val();
                const jawatankuasaGroup = $('#jawatankuasa_group');
                const jawatankuasaInput = $('input[name="jawatankuasa"]');

                if (selectedKategori === '1' || selectedKategori === '2')
                {
                    // Hide Jawatankuasa for old values (1 and 2)
                    jawatankuasaGroup.addClass('d-none');
                    jawatankuasaInput.removeAttr('required');
                }
                else
                {
                    // Show Jawatankuasa for other new values
                    jawatankuasaGroup.removeClass('d-none');
                    jawatankuasaInput.attr('required', 'required');
                }
            }

            // Initial setup on page load
            updateKategoriPerolehanOptions();
            updateJenisField();
            updateTempohField();
            updateJawatankuasaField();

            // Listen for changes
            kategoriPerolehanDropdown.change(function()
            {
                updateKategoriPerolehanOptions();
                updateJenisField();
                updateTempohField();
                updateJawatankuasaField();
            });

            // --- SELECTIZE INITIALIZATION ---
            $('#step2-content select.selectize').each(function() {
                if (!this.selectize) {
                    $(this).selectize();
                }
            });

            // --- DATEPICKER INITIALIZATION ---
            $('.datepicker').datepicker({
                format: 'd M yyyy',
                autoclose: true,
                todayHighlight: true,
                todayBtn: 'linked'
            });

            // --- PTJ DROPDOWN LISTENER ---
            $('#ptj-select').change(function() {
                var selectedText = $(this).find('option:selected').text();
                if (selectedText !== 'Pilih...') {
                    $('#ptj-display').text(selectedText);
                }
            });

            // --- WIZARD NAVIGATION ---
            let currentStep = 1;

            function updateWizardUI() {
                // Update data-step attribute for progress bar
                $('#stepper-wrapper').attr('data-step', currentStep);

                if(currentStep === 1) {
                    $('#step1-content').removeClass('d-none');
                    $('#step2-content').addClass('d-none');

                    $('#btn-back').addClass('d-none');
                    $('#btn-next').removeClass('d-none');
                    $('#btn-submit').addClass('d-none');

                    $('#step1-indicator').removeClass('completed').addClass('active');
                    $('#step2-indicator').removeClass('active');
                } else {
                    $('#step1-content').addClass('d-none');
                    $('#step2-content').removeClass('d-none');

                    $('#btn-back').removeClass('d-none');
                    $('#btn-next').addClass('d-none');
                    $('#btn-submit').removeClass('d-none');

                    $('#step1-indicator').removeClass('active').addClass('completed');
                    $('#step2-indicator').addClass('active');
                }
            }

            $('#btn-next').click(function() {
                // Validate Step 1 required fields before proceeding
                var isValid = true;
                $('#step1-content [required]').each(function() {
                    if (!this.checkValidity()) {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                if (!isValid) {
                    // Focus on first invalid field
                    $('#step1-content [required]:invalid').first().focus();
                    return;
                }

                currentStep = 2;
                updateWizardUI();
                $('html, body').animate({
                    scrollTop: $('#stepper-wrapper').offset().top - 20
                }, 400);
            }); // nanti akan ditambah lagi validation check

            $('#btn-back').click(function() {
                currentStep = 1;
                updateWizardUI();
                // Scroll to timeline when click "Seterusnya"
                $('html, body').animate({
                    scrollTop: $('#stepper-wrapper').offset().top - 20
                }, 400);
            });

            // --- TOGGLE LAIN-LAIN ---
            $('input[name="sumber_peruntukan"]').change(function() {
                if($(this).val() === 'lain') {
                    $('#sumber_lain_input').removeClass('d-none').attr('required', true).focus();
                } else {
                    $('#sumber_lain_input').addClass('d-none').removeAttr('required').val('');
                }
            });

            // --- DYNAMIC ROWS (for condition builder layout) ---
            let mofCount = 1;

            function updateMofRowNumbers() {
                $('#mof-wrapper .condition-row').each(function(i) {
                    $(this).find('.row-number').text(i + 1);
                });
            }

            $('#btn-add-mof').click(function() {
                let index = mofCount++;
                let rowNum = $('#mof-wrapper .condition-row').length + 1;

                // Row Connector
                let connectorHtml = `
                    <div class="condition-connector" id="mof-logic-${index}">
                        <div class="connector-pill-wrapper">
                            <select class="connector-pill" name="mof_logic_${index}">
                                <option value="OR">ATAU</option>
                                <option value="AND">DAN</option>
                            </select>
                        </div>
                    </div>`;

                // New Row
                let rowHtml = `
                    <div class="condition-row mof-row" id="mof-row-${index}" data-index="${index}">
                        <div class="row-number">${rowNum}</div>
                        <div class="row-fields">
                            <div class="field-logic">
                                <label class="form-label">Hubungan</label>
                                <div class="segmented-control">
                                    <input type="radio" name="mof[${index}][logic_mid]" id="mof${index}_or" value="OR" checked>
                                    <label for="mof${index}_or">ATAU</label>
                                    <input type="radio" name="mof[${index}][logic_mid]" id="mof${index}_and" value="AND">
                                    <label for="mof${index}_and">DAN</label>
                                </div>
                            </div>
                            <div class="field-main">
                                <label class="form-label">Kod Bidang</label>
                                <select class="selectize" name="mof[${index}][code][]" multiple>
                                    ${mofOptions}
                                </select>
                            </div>
                        </div>
                        <div class="row-actions">
                            <button type="button" class="btn-remove" onclick="removeMofRow(${index})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                        </div>
                    </div>`;

                $('#mof-wrapper').append(connectorHtml + rowHtml);
                $('#mof-row-' + index + ' select.selectize').each(function() {
                    if (!this.selectize) $(this).selectize();
                });
            });

            window.removeMofRow = function(index) {
                $('#mof-row-' + index).remove();
                $('#mof-logic-' + index).remove();
                updateMofRowNumbers();
            };

            // --- CIDB ---
            let cidbCount = 1;

            function updateCidbRowNumbers() {
                $('#cidb-wrapper .condition-row').each(function(i) {
                    $(this).find('.row-number').text(i + 1);
                });
            }

            $('#btn-add-cidb').click(function() {
                let index = cidbCount++;
                let rowNum = $('#cidb-wrapper .condition-row').length + 1;

                // Row Connector
                let connectorHtml = `
                    <div class="condition-connector" id="cidb-logic-${index}">
                        <div class="connector-pill-wrapper">
                            <select class="connector-pill" name="cidb_logic_${index}">
                                <option value="OR">ATAU</option>
                                <option value="AND">DAN</option>
                            </select>
                        </div>
                    </div>`;

                let rowHtml = `
                    <div class="condition-row cidb-row" id="cidb-row-${index}" data-index="${index}">
                        <div class="row-number">${rowNum}</div>
                        <div class="row-fields cidb-fields">
                            <div class="cidb-row-bottom">
                                <div class="field-logic">
                                    <label class="form-label">Hubungan</label>
                                    <div class="segmented-control">
                                        <input type="radio" name="cidb[${index}][logic_mid]" id="cidb${index}_and" value="AND" checked>
                                        <label for="cidb${index}_and">DAN</label>
                                        <input type="radio" name="cidb[${index}][logic_mid]" id="cidb${index}_or" value="OR">
                                        <label for="cidb${index}_or">ATAU</label>
                                    </div>
                                </div>
                                <div class="field-main">
                                    <label class="form-label">Pengkhususan</label>
                                    <select class="selectize" name="cidb[${index}][spec][]" multiple>
                                        ${cidbSpecOptions}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row-actions">
                            <button type="button" class="btn-remove" onclick="removeCidbRow(${index})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                        </div>
                    </div>`;

                $('#cidb-wrapper').append(connectorHtml + rowHtml);
                $('#cidb-row-' + index + ' select.selectize').each(function() {
                    if (!this.selectize) $(this).selectize();
                });
            });

            window.removeCidbRow = function(index) {
                $('#cidb-row-' + index).remove();
                $('#cidb-logic-' + index).remove();
                updateCidbRowNumbers();
            };
        });
    </script>
@endsection 