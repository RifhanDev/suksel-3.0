@push('styles')
<link href="{{ asset('css/components/file-upload.css') }}" rel="stylesheet">
<style>
    /* ── Stepper ── */
    .iklan-stepper-wrapper {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
        padding: 0 40px;
    }
    .iklan-stepper-track {
        position: absolute;
        top: 17px;
        left: 40px;
        right: 40px;
        height: 4px;
        background: #e2e8f0;
        border-radius: 4px;
        z-index: 0;
        overflow: hidden;
    }
    .iklan-stepper-progress {
        position: absolute;
        top: 0; left: 0;
        height: 100%;
        width: 0%;
        background: var(--sg-red, #c41e3a);
        border-radius: 4px;
        transition: width 0.4s ease;
    }
    .iklan-stepper-wrapper[data-step="1"] .iklan-stepper-progress { width: 0%;   }
    .iklan-stepper-wrapper[data-step="2"] .iklan-stepper-progress { width: 50%;  background: var(--sg-red, #c41e3a); }
    .iklan-stepper-wrapper[data-step="3"] .iklan-stepper-progress { width: 100%; background: linear-gradient(90deg, #10b981 0%, #10b981 10%, var(--sg-red, #c41e3a) 100%); }

    .iklan-step-item {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
    }
    .iklan-step-counter {
        width: 36px; height: 36px;
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
    }
    .iklan-step-name {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        transition: color 0.3s ease;
        text-align: center;
    }
    .iklan-step-item.active .iklan-step-counter {
        border-color: var(--sg-red, #c41e3a);
        background: var(--sg-red, #c41e3a);
        color: #fff;
        box-shadow: 0 0 0 5px rgba(196,30,58,0.12), 0 2px 8px rgba(196,30,58,0.3);
        transform: scale(1.05);
    }
    .iklan-step-item.active .iklan-step-name { color: var(--sg-red, #c41e3a); }
    .iklan-step-item.completed .iklan-step-counter {
        border-color: #10b981;
        background: #10b981;
        color: #fff;
        box-shadow: 0 2px 6px rgba(16,185,129,0.3);
    }
    .iklan-step-item.completed .iklan-step-counter::after { content: '✓'; font-size: 0.9rem; }
    .iklan-step-item.completed .iklan-step-counter span { display: none; }
    .iklan-step-item.completed .iklan-step-name { color: #10b981; }

    /* ── Step section header ── */
    .iklan-step-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .iklan-step-header span { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; color: #475569; }

    /* ── Kebenaran Khas checkbox ── */
    .kebenaran-check-card {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        cursor: pointer;
        transition: border-color 0.15s, background 0.15s;
    }
    .kebenaran-check-card:has(input:checked) {
        border-color: var(--sg-red, #c41e3a);
        background: #fef2f2;
    }
    .kebenaran-check-card input[type=checkbox] {
        width: 17px; height: 17px;
        accent-color: var(--sg-red, #c41e3a);
        flex-shrink: 0;
        margin-top: 2px;
        cursor: pointer;
    }
    .kebenaran-check-card .kc-label { font-size: 0.85rem; font-weight: 600; color: #1e293b; }
    .kebenaran-check-card .kc-desc  { font-size: 0.75rem; color: #64748b; margin-top: 2px; }

    /* ── Selectize dropdown inside modal z-index fix ── */
    .selectize-dropdown { z-index: 9999 !important; }

    /* ── Clickable perihal cell ── */
    .perihal-clickable { color: #1d4ed8; cursor: pointer; text-decoration: underline dotted; font-weight: 500; }
    .perihal-clickable:hover { color: #1e40af; }

    /* ── Step nav footer ── */
    .iklan-step-footer {
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        padding: 14px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
@endpush

<!-- SECTION: PENYEDIAAN IKLAN -->
<div class="content-card mb-4 p-0">

    <div class="review-section-header">
        <div class="section-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>
            </svg>
        </div>
        <div>
            <h6>Penyediaan Iklan</h6>
            <small>Lengkapkan maklumat iklan tender sebelum diterbitkan</small>
        </div>
    </div>

    <!-- Stepper -->
    <div class="px-4 pt-4 pb-2">
        <div class="iklan-stepper-wrapper" data-step="1" id="iklanStepperWrapper">
            <div class="iklan-stepper-track">
                <div class="iklan-stepper-progress" id="iklanStepperProgress"></div>
            </div>
            <div class="iklan-step-item active" id="iklanStep1Indicator">
                <div class="iklan-step-counter"><span>1</span></div>
                <div class="iklan-step-name">Penyediaan Iklan</div>
            </div>
            <div class="iklan-step-item" id="iklanStep2Indicator">
                <div class="iklan-step-counter"><span>2</span></div>
                <div class="iklan-step-name">Langkah 2</div>
            </div>
            <div class="iklan-step-item" id="iklanStep3Indicator">
                <div class="iklan-step-counter"><span>3</span></div>
                <div class="iklan-step-name">Taklimat Tender</div>
            </div>
        </div>
    </div>

    <form id="formPenyediaanIklan" action="{{ route('penyediaanIklan.store') }}" method="POST">
    @csrf

    <!-- ══ STEP 1: PENYEDIAAN IKLAN ══ -->
    <div id="iklanStep1Content">

        <div class="iklan-step-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--sg-red,#c41e3a)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <span>Maklumat Tarikh &amp; Tempoh</span>
        </div>

        <div class="p-4">
            <div class="row g-3">

                {{-- Row 1: Tarikh Iklan | Tarikh Tutup --}}
                <div class="col-md-6">
                    <div class="row g-2">
                        <div class="col-7">
                            <label class="form-label fw-semibold">Tarikh Iklan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg datepicker" name="tarikh_iklan"
                                placeholder="Pilih tarikh..." readonly>
                        </div>
                        <div class="col-5">
                            <label class="form-label fw-semibold">Masa <span class="text-danger">*</span></label>
                            <input type="time" class="form-control form-control-lg" name="masa_iklan">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row g-2">
                        <div class="col-7">
                            <label class="form-label fw-semibold">Tarikh Tutup <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg datepicker" name="tarikh_tutup"
                                placeholder="Pilih tarikh..." readonly>
                        </div>
                        <div class="col-5">
                            <label class="form-label fw-semibold">Masa <span class="text-danger">*</span></label>
                            <input type="time" class="form-control form-control-lg" name="masa_tutup">
                        </div>
                    </div>
                </div>

                {{-- Row 2: Tarikh Jual | Tempoh Iklan --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tarikh Jual <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg datepicker" name="tarikh_jual"
                        placeholder="Pilih tarikh..." readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tempoh Iklan (Hari)</label>
                    <input type="number" class="form-control form-control-lg" name="tempoh_iklan"
                        min="0" placeholder="cth: 14">
                </div>

                {{-- Row 3: Tempoh Sah Laku | Sah Laku Tawaran Tamat --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tempoh Sah Laku Tawaran (Hari)</label>
                    <input type="number" class="form-control form-control-lg" name="tempoh_sah_laku"
                        min="0" placeholder="cth: 90">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Sah Laku Tawaran Tamat</label>
                    <input type="text" class="form-control form-control-lg datepicker" name="sah_laku_tamat"
                        placeholder="Pilih tarikh..." readonly>
                </div>

                {{-- Row 4: Kebenaran Khas --}}
                <div class="col-12 mt-1">
                    <label class="form-label fw-semibold">Kebenaran Khas</label>
                    <label class="kebenaran-check-card">
                        <input type="checkbox" name="kebenaran_khas" value="1">
                        <div>
                            <div class="kc-label">Kebenaran Khas Diperlukan</div>
                            <div class="kc-desc">Tandakan jika tender ini memerlukan kebenaran khas sebelum iklan diterbitkan.</div>
                        </div>
                    </label>
                </div>

            </div>
        </div>

        <!-- Step footer nav -->
        <div class="iklan-step-footer">
            <div></div>
            <button type="button" class="btn-form btn-form-primary" id="iklanBtnNext1">
                Simpan &amp; Seterusnya
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </button>
        </div>

    </div>
    {{-- End Step 1 --}}

    <!-- ══ STEP 2 ══ -->
    <div id="iklanStep2Content" style="display:none;">

        {{-- ── Section 1: Syarat Tender ── --}}
        <div class="iklan-step-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--sg-red,#c41e3a)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
            </svg>
            <span>Syarat Tender</span>
        </div>
        <div class="p-4">
            <div class="alert alert-warning d-flex align-items-center gap-2 mb-3 py-2 px-3" style="font-size:0.82rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                Syarat tender wajib diisi sebelum iklan boleh diterbitkan.
            </div>
            <textarea id="syarat_tender" name="syarat_tender" rows="8"></textarea>
        </div>

        {{-- ── Section 2: Syarat Khas ── --}}
        <div class="iklan-step-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--sg-red,#c41e3a)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 11 12 14 22 4"></polyline>
                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
            </svg>
            <span>Syarat Khas</span>
        </div>
        <div class="p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="d-flex flex-column gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="syarat_khas[]" value="selangor_sahaja" id="sk_selangor">
                            <label class="form-check-label" for="sk_selangor">Syarikat Selangor Sahaja</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="syarat_khas[]" value="bumiputera_sahaja" id="sk_bumi">
                            <label class="form-check-label" for="sk_bumi">Bumiputera Sahaja</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="syarat_khas[]" value="selangor_lain_negeri" id="sk_sel_lain">
                            <label class="form-check-label" for="sk_sel_lain">Syarikat Selangor Dan Lain-lain Negeri</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="syarat_khas[]" value="tender_terhad" id="sk_terhad">
                            <label class="form-check-label" for="sk_terhad">Tender Terhad</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="syarat_khas[]" value="seluruh_malaysia" id="sk_malaysia">
                            <label class="form-check-label" for="sk_malaysia">Seluruh Malaysia</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="syarat_khas[]" value="iklan_sahaja" id="sk_iklan">
                            <label class="form-check-label" for="sk_iklan">Iklan Sahaja</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Syarikat Daerah/Negeri</label>
                    <select class="form-select" name="syarikat_daerah_negeri">
                        <option value="">— Pilih —</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- ── Section 3: Dokumen Sokongan Terawal ── --}}
        <div class="iklan-step-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--sg-red,#c41e3a)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                <polyline points="13 2 13 9 20 9"></polyline>
            </svg>
            <span>Dokumen Sokongan Terawal</span>
        </div>
        <div class="p-4">
            <label class="upload-zone w-100" id="upload-zone-iklan" for="input-dokumen-iklan">
                <div class="upload-zone-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="16 16 12 12 8 16"></polyline>
                        <line x1="12" y1="12" x2="12" y2="21"></line>
                        <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path>
                    </svg>
                </div>
                <span class="upload-zone-label">Klik atau seret fail ke sini untuk muat naik</span>
                <span class="upload-zone-sub">PDF, Word, Excel, Imej, ZIP — saiz maksimum 10 MB setiap fail</span>
                <input type="file" id="input-dokumen-iklan" name="dokumen_sokongan_terawal[]" multiple hidden
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.zip,.rar">
            </label>
            <div class="file-chip-list" id="file-chip-list-iklan"></div>
        </div>

        <div class="iklan-step-footer">
            <button type="button" class="btn-form btn-form-secondary" id="iklanBtnBack2">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Sebelum
            </button>
            <button type="button" class="btn-form btn-form-primary" id="iklanBtnNext2">
                Simpan &amp; Seterusnya
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </button>
        </div>
    </div>

    <!-- ══ STEP 3: TAKLIMAT TENDER ══ -->
    <div id="iklanStep3Content" style="display:none;">

        <div class="iklan-step-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--sg-red,#c41e3a)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <span>Perincian Taklimat Tender (Pra-Tender / Pra-Sebut Harga) / Lawatan Tapak</span>
        </div>

        <!-- Toolbar -->
        <div class="d-flex justify-content-end gap-2 px-3 py-2 border-bottom bg-light">
            <button type="button" class="btn btn-sm btn-success" id="btnTambahTaklimat">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Tambah
            </button>
            <button type="button" class="btn btn-sm btn-danger" id="btnHapusTaklimat" style="display:none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                    <path d="M10 11v6"></path><path d="M14 11v6"></path>
                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                </svg>
                Hapus
            </button>
        </div>

        <!-- Info label -->
        <div class="px-3 py-2 bg-light border-bottom d-flex align-items-center gap-2" style="font-size:0.75rem; color:#64748b;">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
            Klik pada <span class="fw-semibold" style="color:#1d4ed8;">nama perihal</span> untuk melihat maklumat Pegawai Untuk Dihubungi.
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered mb-0" id="tblTaklimat" style="font-size:0.82rem;">
                <thead>
                    <tr>
                        <th style="width:40px;" class="text-center">
                            <input type="checkbox" id="chkSelectAllTaklimat" title="Pilih semua">
                        </th>
                        <th>Perihal</th>
                        <th style="width:145px;">Tarikh / Masa</th>
                        <th style="min-width:160px;">Lokasi / Alamat</th>
                        <th style="width:110px;" class="text-center">Kehadiran</th>
                    </tr>
                </thead>
                <tbody id="tblTaklimatBody">
                    <tr id="taklimatEmptyRow">
                        <td colspan="5" class="text-center text-muted py-4" style="font-size:0.82rem;">
                            Tiada rekod. Klik <strong>Tambah</strong> untuk menambah taklimat.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="iklan-step-footer">
            <button type="button" class="btn-form btn-form-secondary" id="iklanBtnBack3">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Sebelum
            </button>
            <button type="submit" class="btn-form btn-form-success" id="iklanBtnSimpan">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                </svg>
                Simpan
            </button>
        </div>

    </div>

    </form>

</div>
<!-- End Penyediaan Iklan -->

@push('modals')

{{-- Success Modal --}}
<div class="modal fade" id="modalBerjayaIklan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:360px;">
        <div class="modal-content text-center p-4">
            <div class="mb-3">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="10" fill="#E6F7F3"/>
                    <path d="M10 14.2L7.8 12l-1.4 1.4L10 17l8-8-1.4-1.4L10 14.2z" fill="#19c1a7"/>
                </svg>
            </div>
            <h5 class="fw-bold mb-2">Berjaya</h5>
            <p class="text-muted mb-4">Maklumat telah berjaya disimpan.</p>
            <button type="button" class="btn-form btn-form-primary mx-auto" data-bs-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTaklimat" tabindex="-1" aria-labelledby="modalTaklimatLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header" style="background:linear-gradient(135deg,#c41e3a 0%,#a01830 100%); color:#fff;">
                <h5 class="modal-title fw-bold" id="modalTaklimatLabel" style="font-size:0.95rem;">
                    Borang Perincian Taklimat Tender (Pra-Tender / Pra-Sebut Harga)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">

                    {{-- Row 1: Perihal --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">Perihal <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="taklimat_perihal" name="taklimat_perihal" rows="2" placeholder="Huraikan perihal taklimat..."></textarea>
                    </div>

                    {{-- Row 2: Tarikh | Masa | Kehadiran --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tarikh <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg datepicker" id="taklimat_tarikh" placeholder="Pilih tarikh..." readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Masa</label>
                        <input type="time" class="form-control form-control-lg" id="taklimat_masa">
                    </div>
                    <div class="col-md-5 d-flex flex-column justify-content-end">
                        <label class="form-label fw-semibold">Kehadiran</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="taklimat_kehadiran" value="1">
                            <label class="form-check-label fw-semibold" for="taklimat_kehadiran">Wajib</label>
                            <span class="text-muted ms-1" style="font-size:0.72rem;">(Nyahtanda jika tidak wajib)</span>
                        </div>
                    </div>

                    {{-- Row 3: Lokasi / Alamat --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">Lokasi / Alamat</label>
                        <textarea class="form-control" id="taklimat_lokasi" name="taklimat_lokasi" rows="2" placeholder="Masukkan lokasi atau alamat penuh..."></textarea>
                    </div>

                    {{-- Row 4: Pegawai Penyelaras --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">Pegawai Penyelaras</label>
                        <input type="text" id="taklimat_pegawai_input" placeholder="Taip nama pegawai...">
                    </div>

                    {{-- Row 5: Auto-filled pegawai details --}}
                    <div class="col-md-4">
                        <label class="form-label text-muted" style="font-size:0.75rem;">No. Telefon</label>
                        <input type="text" class="form-control form-control-sm bg-light" id="taklimat_telefon" readonly placeholder="-">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted" style="font-size:0.75rem;">No. Faks</label>
                        <input type="text" class="form-control form-control-sm bg-light" id="taklimat_faks" readonly placeholder="-">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted" style="font-size:0.75rem;">E-mel</label>
                        <input type="text" class="form-control form-control-sm bg-light" id="taklimat_email" readonly placeholder="-">
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-form btn-form-primary" id="btnSimpanTaklimat">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    </svg>
                    Simpan
                </button>
            </div>

        </div>
    </div>
</div>
@endpush

@push('scripts')
<script src="{{ asset('custom_library/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('js/components/file-upload.js') }}"></script>
<script>
$(document).ready(function () {

    var iklanCurrentStep  = 1;
    var iklanTotalSteps   = 3;
    var ckEditorInitialised = false;

    var iklanStepContents   = ['#iklanStep1Content', '#iklanStep2Content', '#iklanStep3Content'];
    var iklanStepIndicators = ['#iklanStep1Indicator', '#iklanStep2Indicator', '#iklanStep3Indicator'];

    function iklanGoToStep(step) {
        if (step < 1 || step > iklanTotalSteps) return;

        $.each(iklanStepContents, function (i, sel) { $(sel).hide(); });
        $(iklanStepContents[step - 1]).show();

        $.each(iklanStepIndicators, function (i, sel) {
            var $el = $(sel);
            $el.removeClass('active completed');
            if (i + 1 < step)        $el.addClass('completed');
            else if (i + 1 === step) $el.addClass('active');
        });

        $('#iklanStepperWrapper').attr('data-step', step);
        iklanCurrentStep = step;

        /* Init step 2 components once */
        if (step === 2) { initFileUpload(); }

        /* Init CKEditor once when step 2 first shown.
           Wrapped in setTimeout so the element is fully painted before CKEditor
           accesses its computed styles (avoids "setting 'dir'" error on some browsers). */
        if (step === 2 && !ckEditorInitialised && typeof CKEDITOR !== 'undefined') {
            ckEditorInitialised = true; /* set early to prevent double-init on rapid clicks */
            setTimeout(function () {
                try {
                    if (CKEDITOR.instances['syarat_tender']) {
                        CKEDITOR.instances['syarat_tender'].destroy(true);
                    }
                    CKEDITOR.replace('syarat_tender', {
                        toolbarGroups: [
                            { name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
                            { name: 'paragraph',   groups: ['list', 'indent', 'blocks', 'align', 'paragraph'] },
                            { name: 'links',       groups: ['links'] },
                            { name: 'insert',      groups: ['insert'] },
                            '/',
                            { name: 'styles',  groups: ['styles'] },
                            { name: 'colors',  groups: ['colors'] },
                            { name: 'tools',   groups: ['tools'] },
                        ],
                        removeButtons: 'Flash,Iframe,Form,TextField,Checkbox,Radio,Textarea,Select,Button,ImageButton,HiddenField,Subscript,Superscript',
                        height: 250,
                    });
                } catch (e) {
                    console.warn('CKEditor init error:', e);
                }
            }, 50);
        }

        var offset = $('#iklanStepperWrapper').offset();
        if (offset) $('html, body').animate({ scrollTop: offset.top - 80 }, 200);
    }

    /* ── Sync CKEditor + serialize taklimat, then show success modal ── */
    $('#formPenyediaanIklan').on('submit', function (e) {
        e.preventDefault();

        if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['syarat_tender']) {
            CKEDITOR.instances['syarat_tender'].updateElement();
        }

        $('#formPenyediaanIklan #taklimat_rows_hidden').remove();
        $('<input>')
            .attr({ type: 'hidden', id: 'taklimat_rows_hidden', name: 'taklimat_rows' })
            .val(JSON.stringify(taklimatRows))
            .appendTo('#formPenyediaanIklan');

        new bootstrap.Modal(document.getElementById('modalBerjayaIklan')).show();
    });

    /* ── Stepper button wiring ── */
    $('#iklanBtnNext1').on('click', function () { iklanGoToStep(2); });
    $('#iklanBtnBack2').on('click', function () { iklanGoToStep(1); });
    $('#iklanBtnNext2').on('click', function () { iklanGoToStep(3); });
    $('#iklanBtnBack3').on('click', function () { iklanGoToStep(2); });

    /* ── Datepicker (step 1 + modal) ── */
    $('#iklanStep1Content .datepicker').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
    });
    $('#taklimat_tarikh').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
    });

    /* ════════════════════════════════════════════
       STEP 3 — TAKLIMAT TENDER
    ════════════════════════════════════════════ */

    /* Dummy pegawai data (replace with AJAX later) */
    var pegawaiDummy = [
        { id: 1, nama: 'Ahmad Hafizuddin bin Roslan',    telefon: '03-5544 1234', faks: '03-5544 1235', email: 'ahmad.hafiz@selangor.gov.my' },
        { id: 2, nama: 'Siti Nurhaliza binti Kamarudin', telefon: '03-5544 2345', faks: '03-5544 2346', email: 'siti.nurhaliza@selangor.gov.my' },
        { id: 3, nama: 'Mohd Faizal bin Abdullah',        telefon: '03-5544 3456', faks: '03-5544 3457', email: 'mohd.faizal@selangor.gov.my' },
        { id: 4, nama: 'Nurul Ain binti Hashim',          telefon: '03-5544 4567', faks: '03-5544 4568', email: 'nurul.ain@selangor.gov.my' },
        { id: 5, nama: 'Zulkifli bin Mohd Yusoff',        telefon: '03-5544 5678', faks: '03-5544 5679', email: 'zulkifli.yusoff@selangor.gov.my' },
    ];
    var taklimatRowCount = 0;
    var selectedPegawai  = null;
    var taklimatRows     = [];   /* stores each row's data for form submission */

    /* Selectize — search by nama */
    $('#taklimat_pegawai_input').selectize({
        valueField  : 'id',
        labelField  : 'nama',
        searchField : 'nama',
        options     : pegawaiDummy,
        maxItems    : 1,
        create      : false,
        placeholder : 'Taip nama pegawai...',
        dropdownParent: 'body',
        render: {
            option: function (item, escape) {
                return '<div><strong>' + escape(item.nama) + '</strong>' +
                    '<br><small class="text-muted">' + escape(item.email) + '</small></div>';
            }
        },
        onChange: function (value) {
            if (!value) {
                selectedPegawai = null;
                $('#taklimat_telefon, #taklimat_faks, #taklimat_email').val('');
                return;
            }
            selectedPegawai = pegawaiDummy.find(function (p) { return String(p.id) === String(value); });
            if (selectedPegawai) {
                $('#taklimat_telefon').val(selectedPegawai.telefon);
                $('#taklimat_faks').val(selectedPegawai.faks);
                $('#taklimat_email').val(selectedPegawai.email);
            }
        }
    });

    /* Open modal & reset */
    $('#btnTambahTaklimat').on('click', function () {
        $('#taklimat_perihal').val('');
        $('#taklimat_tarikh').val('');
        $('#taklimat_masa').val('');
        $('#taklimat_lokasi').val('');
        $('#taklimat_kehadiran').prop('checked', false);
        $('#taklimat_telefon, #taklimat_faks, #taklimat_email').val('');
        selectedPegawai = null;
        var sel = document.getElementById('taklimat_pegawai_input');
        if (sel && sel.selectize) sel.selectize.clear();
        var modal = new bootstrap.Modal(document.getElementById('modalTaklimat'));
        modal.show();
    });

    /* Simpan — build table rows */
    $('#btnSimpanTaklimat').on('click', function () {
        var perihal = $('#taklimat_perihal').val().trim();
        var tarikh  = $('#taklimat_tarikh').val().trim();
        var masa    = $('#taklimat_masa').val().trim();
        var lokasi  = $('#taklimat_lokasi').val().trim() || '-';
        var wajib   = $('#taklimat_kehadiran').is(':checked');

        if (!perihal || !tarikh) {
            alert('Sila isi sekurang-kurangnya Perihal dan Tarikh.');
            return;
        }

        taklimatRowCount++;
        var rId     = 'tr_' + taklimatRowCount;
        var tarikhMasa = tarikh + (masa ? ' | ' + masa : '');
        var badge   = wajib
            ? '<span class="badge-status badge-status-danger">Wajib</span>'
            : '<span class="badge-status badge-status-neutral">Tidak Wajib</span>';

        var pNama  = selectedPegawai ? selectedPegawai.nama    : '-';
        var pTel   = selectedPegawai ? selectedPegawai.telefon : '-';
        var pFaks  = selectedPegawai ? selectedPegawai.faks    : '-';
        var pEmail = selectedPegawai ? selectedPegawai.email   : '-';

        var mainRow = [
            '<tr class="taklimat-main-row" data-rid="' + rId + '">',
            '<td class="text-center"><input type="checkbox" class="chk-taklimat-row form-check-input" style="accent-color:#c41e3a;"></td>',
            '<td style="white-space:normal;max-width:220px;"><span class="perihal-clickable" data-rid="' + rId + '">' + perihal + '</span></td>',
            '<td>' + tarikhMasa + '</td>',
            '<td style="white-space:pre-wrap;word-break:break-word;">' + lokasi + '</td>',
            '<td class="text-center">' + badge + '</td>',
            '</tr>'
        ].join('');

        var detailRow = [
            '<tr class="taklimat-detail-row" id="detail_' + rId + '" style="display:none;">',
            '<td colspan="5" class="p-0">',
                '<div class="d-flex align-items-center justify-content-between px-4 pt-3 pb-1">',
                    '<div class="d-flex align-items-center gap-1">',
                        '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>',
                        '<span class="text-secondary fw-bold text-uppercase" style="font-size:0.68rem;letter-spacing:0.5px;">Pegawai Untuk Dihubungi</span>',
                    '</div>',
                    '<button type="button" class="btn btn-sm btn-outline-danger btn-close-detail" data-rid="' + rId + '" style="font-size:0.72rem; padding:2px 10px;">Tutup</button>',
                '</div>',
                '<div class="row g-0 px-4 pb-3">',
                    '<div class="col-md-3 pe-3"><span class="text-muted d-block" style="font-size:0.7rem;">Nama</span><span class="fw-semibold" style="font-size:0.82rem;">' + pNama + '</span></div>',
                    '<div class="col-md-3 pe-3"><span class="text-muted d-block" style="font-size:0.7rem;">No. Telefon</span><span class="fw-semibold" style="font-size:0.82rem;">' + pTel + '</span></div>',
                    '<div class="col-md-3 pe-3"><span class="text-muted d-block" style="font-size:0.7rem;">No. Faks</span><span class="fw-semibold" style="font-size:0.82rem;">' + pFaks + '</span></div>',
                    '<div class="col-md-3"><span class="text-muted d-block" style="font-size:0.7rem;">E-mel</span><span class="fw-semibold" style="font-size:0.82rem;">' + pEmail + '</span></div>',
                '</div>',
            '</td>',
            '</tr>'
        ].join('');

        /* Store row data for submission */
        taklimatRows.push({
            rid      : rId,
            perihal  : perihal,
            tarikh   : tarikh,
            masa     : masa,
            lokasi   : lokasi,
            kehadiran: wajib ? 'Wajib' : 'Tidak Wajib',
            peg_nama : pNama,
            peg_tel  : pTel,
            peg_faks : pFaks,
            peg_email: pEmail,
        });

        $('#taklimatEmptyRow').hide();
        $('#tblTaklimatBody').append(mainRow + detailRow);
        syncHapusTaklimat();

        bootstrap.Modal.getInstance(document.getElementById('modalTaklimat')).hide();
    });

    /* Click perihal to expand detail row */
    $(document).on('click', '.perihal-clickable', function () {
        var rId = $(this).data('rid');
        $('#detail_' + rId).toggle();
    });

    /* Close button inside detail row */
    $(document).on('click', '.btn-close-detail', function () {
        var rId = $(this).data('rid');
        $('#detail_' + rId).hide();
    });

    /* Checkbox select-all (taklimat) */
    $('#chkSelectAllTaklimat').on('change', function () {
        $('#tblTaklimatBody .chk-taklimat-row').prop('checked', $(this).is(':checked'));
    });
    $(document).on('change', '#tblTaklimatBody .chk-taklimat-row', function () {
        var total   = $('#tblTaklimatBody .chk-taklimat-row').length;
        var checked = $('#tblTaklimatBody .chk-taklimat-row:checked').length;
        $('#chkSelectAllTaklimat').prop('checked', total > 0 && total === checked);
    });

    /* Hapus selected rows */
    function syncHapusTaklimat() {
        var hasRows = $('#tblTaklimatBody .taklimat-main-row').length > 0;
        hasRows ? $('#btnHapusTaklimat').show() : $('#btnHapusTaklimat').hide();
        if (!hasRows) $('#taklimatEmptyRow').show();
    }
    $('#btnHapusTaklimat').on('click', function () {
        $('#tblTaklimatBody .taklimat-main-row').each(function () {
            if ($(this).find('.chk-taklimat-row').is(':checked')) {
                var rId = $(this).data('rid');
                taklimatRows = taklimatRows.filter(function (r) { return r.rid !== rId; });
                $('#detail_' + rId).remove();
                $(this).remove();
            }
        });
        $('#chkSelectAllTaklimat').prop('checked', false);
        syncHapusTaklimat();
    });

    /* ── Dokumen Sokongan Terawal: FileUpload zone (init when step 2 shown) ── */
    var fileUploadInitialised = false;
    function initFileUpload() {
        if (!fileUploadInitialised && typeof FileUpload !== 'undefined') {
            FileUpload.init({
                zoneId     : 'upload-zone-iklan',
                inputId    : 'input-dokumen-iklan',
                chipListId : 'file-chip-list-iklan',
            });
            fileUploadInitialised = true;
        }
    }

});
</script>
@endpush
