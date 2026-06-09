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

    /* ── Syarat Khas radio cards ── */
    .sk-radio-card { cursor: pointer; transition: border-color 0.15s, background 0.15s; }
    .sk-radio-card:has(input:checked) { border-color: var(--sg-red, #c41e3a) !important; background: #fef2f2; }
    .sk-radio-card input[type=radio] { width: 16px; height: 16px; accent-color: var(--sg-red,#c41e3a); flex-shrink: 0; cursor: pointer; }
    .sk-radio-card .sk-radio-label { font-size: 0.85rem; font-weight: 500; color: #1e293b; line-height: 1.4; }
    .sk-section-label { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: #94a3b8; margin-bottom: 10px; }
    .sk-check-row { display: flex; align-items: flex-start; gap: 10px; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; background: #fff; transition: border-color 0.15s; cursor: pointer; }
    .sk-check-row:has(input:checked) { border-color: var(--sg-red,#c41e3a); background: #fef2f2; }
    .sk-check-row input[type=checkbox] { width: 16px; height: 16px; accent-color: var(--sg-red,#c41e3a); flex-shrink: 0; margin-top: 2px; cursor: pointer; }
    .sk-check-row .sk-check-label { font-size: 0.85rem; font-weight: 500; color: #1e293b; }
    .sk-daerah-label { font-size: 0.75rem; font-weight: 600; color: #475569; margin: 14px 0 8px; }
    .sk-atau-divider { display: flex; align-items: center; gap: 10px; margin: 8px 0; }
    .sk-atau-divider::before, .sk-atau-divider::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }
    .sk-atau-divider span { font-size: 0.68rem; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; }

    /* ── Selectize dropdown inside modal z-index ── */
    .selectize-dropdown { z-index: 9999 !important; }

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
                <div class="iklan-step-name">Syarat Tender</div>
            </div>
            <div class="iklan-step-item" id="iklanStep3Indicator">
                <div class="iklan-step-counter"><span>3</span></div>
                <div class="iklan-step-name">Taklimat Tender</div>
            </div>
        </div>
    </div>

@php
    $iklan = $meta['iklan'] ?? [];
    $syarat = $iklan['syarat'] ?? [];

    $onlySelangor = $syarat['only_selangor'] ?? $tender->only_selangor ?? null;
    $onlyBumiputera = array_key_exists('only_bumiputera', $syarat)
        ? $syarat['only_bumiputera']
        : (bool) ($tender->only_bumiputera ?? false);
    $invitation = array_key_exists('invitation', $syarat)
        ? $syarat['invitation']
        : (bool) ($tender->invitation ?? false);
    $onlyAdvertise = array_key_exists('only_advertise', $syarat)
        ? $syarat['only_advertise']
        : (bool) ($tender->only_advertise ?? false);

    $districtRules = $syarat['district_list_rule'] ?? [];
    if ($districtRules === [] && ! empty($tender->district_list_rule)) {
        $decodedRules = json_decode($tender->district_list_rule, true);
        if (is_array($decodedRules)) {
            foreach ($decodedRules as $rule) {
                $districtRules[] = [
                    'district_id' => is_array($rule) ? ($rule['district_id'] ?? null) : ($rule->district_id ?? null),
                    'state_id' => is_array($rule) ? ($rule['state_id'] ?? '0') : ($rule->state_id ?? '0'),
                ];
            }
        }
    }

    $isChecked = static fn ($value): bool => filter_var($value, FILTER_VALIDATE_BOOLEAN);
@endphp

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
                                value="{{ $iklan['tarikh_iklan'] ?? '' }}" placeholder="Pilih tarikh..." readonly>
                        </div>
                        <div class="col-5">
                            <label class="form-label fw-semibold">Masa <span class="text-danger">*</span></label>
                            <input type="time" class="form-control form-control-lg" name="masa_iklan" value="{{ $iklan['masa_iklan'] ?? '' }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row g-2">
                        <div class="col-7">
                            <label class="form-label fw-semibold">Tarikh Tutup <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg datepicker" name="tarikh_tutup"
                                value="{{ $iklan['tarikh_tutup'] ?? '' }}" placeholder="Pilih tarikh..." readonly>
                        </div>
                        <div class="col-5">
                            <label class="form-label fw-semibold">Masa <span class="text-danger">*</span></label>
                            <input type="time" class="form-control form-control-lg" name="masa_tutup" value="{{ $iklan['masa_tutup'] ?? '' }}">
                        </div>
                    </div>
                </div>

                {{-- Row 2: Tarikh Jual | Tempoh Iklan --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tarikh Jual <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg datepicker" name="tarikh_jual"
                        value="{{ $iklan['tarikh_jual'] ?? '' }}" placeholder="Pilih tarikh..." readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tempoh Iklan (Hari)</label>
                    <input type="number" class="form-control form-control-lg" name="tempoh_iklan"
                        value="{{ $iklan['tempoh_iklan'] ?? '' }}"
                        min="0" placeholder="cth: 14">
                </div>

                {{-- Row 3: Tempoh Sah Laku | Sah Laku Tawaran Tamat --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tempoh Sah Laku Tawaran (Hari)</label>
                    <input type="number" class="form-control form-control-lg" name="tempoh_sah_laku"
                        value="{{ $iklan['tempoh_sah_laku'] ?? '' }}"
                        min="0" placeholder="cth: 90">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Sah Laku Tawaran Tamat</label>
                    <input type="text" class="form-control form-control-lg datepicker" name="sah_laku_tamat"
                        value="{{ $iklan['sah_laku_tamat'] ?? '' }}"
                        placeholder="Pilih tarikh..." readonly>
                </div>

                {{-- Row 4: Kebenaran Khas --}}
                <div class="col-12 mt-1">
                    <label class="form-label fw-semibold">Kebenaran Khas</label>
                    <label class="kebenaran-check-card">
                        <input type="checkbox" name="kebenaran_khas" value="1"
                            @checked(!empty($iklan['kebenaran_khas']))>
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
            <textarea id="syarat_tender" name="syarat_tender" rows="8">{{ $iklan['syarat_tender'] ?? $tender->tender_rules ?? '' }}</textarea>
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
            <div class="row g-4">

                {{-- Part 1: Skop Syarikat --}}
                <div class="col-lg-8">
                    <div class="sk-section-label">Skop Syarikat</div>

                    <div class="d-flex flex-column gap-2">
                        <label class="sk-radio-card d-flex align-items-center gap-3 px-3 py-2 border rounded-2">
                            <input type="radio" name="only_selangor" id="only_selangor1" value="1"
                                @checked((string) $onlySelangor === '1')>
                            <span class="sk-radio-label">Syarikat Selangor Sahaja</span>
                        </label>
                        <label class="sk-radio-card d-flex align-items-center gap-3 px-3 py-2 border rounded-2">
                            <input type="radio" name="only_selangor" id="only_selangor2" value="2"
                                @checked((string) $onlySelangor === '2')>
                            <span class="sk-radio-label">Syarikat Selangor Dan Lain-lain Negeri</span>
                        </label>
                        <label class="sk-radio-card d-flex align-items-center gap-3 px-3 py-2 border rounded-2">
                            <input type="radio" name="only_selangor" id="only_selangor3" value="3"
                                @checked((string) $onlySelangor === '3')>
                            <span class="sk-radio-label">Seluruh Malaysia</span>
                        </label>
                    </div>

                    {{-- Daerah / Negeri (hidden when Seluruh Malaysia) --}}
                    <div id="main_district_div" style="display:none;">
                        <div class="sk-daerah-label">Syarikat Daerah / Negeri</div>

                        <div id="skDaerahRows">
                            <div id="custom-label" style="display:none;"></div>
                            <div class="d-flex align-items-center gap-2">
                                <div id="district_id_div" class="flex-grow-1">
                                    <select class="form-select form-select-sm district_select" id="district_id_new" name="district_id_new[]">
                                        <option value="" disabled selected>— Pilihan Daerah —</option>
                                        @foreach (App\Vendor::$districts as $districtId => $districtName)
                                            @if ($districtId != 0)
                                                <option value="{{ $districtId }}">{{ $districtName }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div id="state_id_div" style="display:none; min-width:180px;">
                                    <select class="form-select form-select-sm" id="state_id_new" name="state_id_new[]">
                                        <option value="">— Pilihan Negeri —</option>
                                        @foreach ($country_states as $state)
                                            <option value="{{ $state->id }}">{{ $state->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-sm btn-success mt-3" id="btnTambahDaerah">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Tambah
                        </button>
                    </div>
                </div>

                {{-- Part 2: Syarat Tambahan --}}
                <div class="col-lg-4">
                    <div class="sk-section-label">Syarat Tambahan</div>

                    <div class="d-flex flex-column gap-2">
                        <label class="sk-check-row">
                            <input type="checkbox" name="only_bumiputera" id="sk_bumi" value="1"
                                @checked($isChecked($onlyBumiputera))>
                            <span class="sk-check-label">Bumiputera Sahaja</span>
                        </label>
                        <label class="sk-check-row">
                            <input type="checkbox" name="invitation" id="sk_terhad" value="1"
                                @checked($isChecked($invitation))>
                            <span class="sk-check-label">Tender Terhad</span>
                        </label>
                        <label class="sk-check-row">
                            <input type="checkbox" name="only_advertise" id="sk_iklan" value="1"
                                @checked($isChecked($onlyAdvertise))>
                            <span class="sk-check-label">Iklan Sahaja</span>
                        </label>
                    </div>

                    <div class="d-flex align-items-start gap-2 mt-3 p-3 rounded-2" style="font-size:0.76rem; color:#0369a1; line-height:1.5; background:#eff6ff; border:1px solid #bae6fd;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:1px;">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <span>Sila tandakan <strong>Iklan Sahaja</strong> sekiranya penjualan dibuat secara manual.</span>
                    </div>
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
        sessionStorage.setItem('penyediaanIklanStep_{{ $tender->id }}', String(step));

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
                        contentsCss: [CKEDITOR.getUrl('contents.css'), 'html { overflow-y: hidden; }'],
                    });
                } catch (e) {
                    console.warn('CKEditor init error:', e);
                }
            }, 50);
        }

        var offset = $('#iklanStepperWrapper').offset();
        if (offset) $('html, body').animate({ scrollTop: offset.top - 80 }, 200);
    }

    function preparePenyediaanIklanForm() {
        var form = document.getElementById('formPenyediaanIklan');
        if (!form) return null;
        form.action = '{{ route("penyediaanIklan.simpan", $tender) }}';
        if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['syarat_tender']) {
            CKEDITOR.instances['syarat_tender'].updateElement();
        }
        if (typeof taklimatRows !== 'undefined') {
            $('#formPenyediaanIklan #taklimat_rows_hidden').remove();
            // Only send taklimat on step 3 (or when rows exist) so step 1/2 draft
            // saves do not wipe previously saved lawatan tapak / taklimat data.
            if (taklimatRows.length > 0 || iklanCurrentStep === 3) {
                $('<input>').attr({ type: 'hidden', id: 'taklimat_rows_hidden', name: 'taklimat_rows' })
                    .val(JSON.stringify(taklimatRows)).appendTo('#formPenyediaanIklan');
            }
        }
        return form;
    }

    function savePenyediaanIklanDraft(done) {
        var form = preparePenyediaanIklanForm();
        if (!form) return;

        var $btn = $(document.activeElement);
        $btn.prop('disabled', true);

        $.ajax({
            url: form.action,
            method: 'POST',
            data: new FormData(form),
            processData: false,
            contentType: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            success: function () {
                if (typeof done === 'function') done();
            },
            error: function (xhr) {
                var message = (xhr.responseJSON && xhr.responseJSON.message)
                    ? xhr.responseJSON.message
                    : 'Gagal menyimpan maklumat iklan.';
                alert(message);
            },
            complete: function () {
                $btn.prop('disabled', false);
            },
        });
    }

    /* ── Sync CKEditor + serialize taklimat, then show success modal ── */
    $('#iklanBtnSimpan').on('click', function (e) {
        e.preventDefault();
        var form = preparePenyediaanIklanForm();
        if (!form) return;
        form.submit();
    });

    /* ── Stepper button wiring ── */
    $('#iklanBtnNext1').on('click', function () {
        savePenyediaanIklanDraft(function () { iklanGoToStep(2); });
    });
    $('#iklanBtnBack2').on('click', function () { iklanGoToStep(1); });
    $('#iklanBtnNext2').on('click', function () {
        savePenyediaanIklanDraft(function () { iklanGoToStep(3); });
    });
    $('#iklanBtnBack3').on('click', function () { iklanGoToStep(2); });

    var savedIklanStep = sessionStorage.getItem('penyediaanIklanStep_{{ $tender->id }}');
    if (savedIklanStep && $('#tab-iklan-btn').hasClass('active')) {
        var stepNum = parseInt(savedIklanStep, 10);
        if (stepNum >= 1 && stepNum <= iklanTotalSteps) {
            iklanGoToStep(stepNum);
        }
    }
    $('#tab-iklan-btn').on('shown.bs.tab', function () {
        var step = sessionStorage.getItem('penyediaanIklanStep_{{ $tender->id }}');
        if (step) {
            var stepNum = parseInt(step, 10);
            if (stepNum >= 1 && stepNum <= iklanTotalSteps) {
                iklanGoToStep(stepNum);
            }
        }
    });

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

    var taklimatRowCount = 0;
    var taklimatRows     = [];
    var savedTaklimat    = @json($iklan['taklimat'] ?? []);

    function appendTaklimatRow(row) {
        taklimatRowCount++;
        var rId = row.rid || ('tr_' + taklimatRowCount);
        var tarikhMasa = (row.tarikh || '') + (row.masa ? ' | ' + row.masa : '');
        var badge = (row.kehadiran === 'Wajib')
            ? '<span class="badge-status badge-status-danger">Wajib</span>'
            : '<span class="badge-status badge-status-neutral">Tidak Wajib</span>';
        var mainRow = '<tr class="taklimat-main-row" data-rid="' + rId + '">' +
            '<td class="text-center"><input type="checkbox" class="chk-taklimat-row form-check-input"></td>' +
            '<td>' + (row.perihal || '') + '</td>' +
            '<td>' + tarikhMasa + '</td>' +
            '<td>' + (row.lokasi || '-') + '</td>' +
            '<td class="text-center">' + badge + '</td></tr>';
        row.rid = rId;
        taklimatRows.push(row);
        $('#taklimatEmptyRow').hide();
        $('#tblTaklimatBody').append(mainRow);
        syncHapusTaklimat();
    }

    savedTaklimat.forEach(function (row) { appendTaklimatRow(row); });

    /* Open modal & reset */
    $('#btnTambahTaklimat').on('click', function () {
        $('#taklimat_perihal').val('');
        $('#taklimat_tarikh').val('');
        $('#taklimat_masa').val('');
        $('#taklimat_lokasi').val('');
        $('#taklimat_kehadiran').prop('checked', false);
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

        appendTaklimatRow({
            perihal  : perihal,
            tarikh   : tarikh,
            masa     : masa,
            lokasi   : lokasi,
            kehadiran: wajib ? 'Wajib' : 'Tidak Wajib',
        });

        bootstrap.Modal.getInstance(document.getElementById('modalTaklimat')).hide();
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

    /* ════════════════════════════════════════════
       STEP 2 — SYARAT KHAS
    ════════════════════════════════════════════ */

    var skBaseDaerahOpts = '<option value="" disabled selected>— Pilihan Daerah —</option>';
    @foreach (App\Vendor::$districts as $districtId => $districtName)
        @if ($districtId != 0)
        skBaseDaerahOpts += '<option value="{{ $districtId }}">{{ $districtName }}</option>';
        @endif
    @endforeach

    var skNegeriOpts = '<option value="">— Pilihan Negeri —</option>';
    @foreach ($country_states as $state)
    skNegeriOpts += '<option value="{{ $state->id }}">{{ $state->description }}</option>';
    @endforeach

    var skRowCounter = 1; /* first row is idx "", new rows get "2", "3", ... */

    /* ── District select change ── */
    $(document).on('change', '.district_select', function () {
        var select_id = this.id;
        var val       = this.value;
        var unique_idx = select_id.replace('district_id_new', '');

        if (val == '0') {
            /* Luar Negeri Selangor — show state select */
            $('#district_id_div' + unique_idx).css('flex', '0 0 auto').css('min-width', '48%');
            $('#state_id_div'    + unique_idx).show();
            $('#state_id_new'    + unique_idx).show();
        } else {
            /* Any Selangor district — hide state select */
            $('#district_id_div' + unique_idx).css('flex', '1').css('min-width', '');
            $('#state_id_div'    + unique_idx).hide();
            $('#state_id_new'    + unique_idx).hide().val('');
        }
    });

    /* ── Radio change ── */
    $('[name="only_selangor"]').on('change', function () {
        var val = $(this).val();

        if (val == '2') {
            /* Syarikat Selangor Dan Lain-lain Negeri */
            $('#main_district_div').show();
            $('[name="district_id_new[]"]').find('option[value="0"]').remove();
            $('[name="district_id_new[]"]').append('<option value="0">Luar Negeri Selangor</option>');

        } else if (val == '1') {
            /* Syarikat Selangor Sahaja */
            $('#main_district_div').show();
            $('[name="district_id_new[]"]').find('option[value="0"]').remove();
            /* Hide all negeri selects */
            $('[id^="state_id_div"]').hide();
            $('[id^="state_id_new"]').hide().val('');
            /* Reset district divs to full width */
            $('[id^="district_id_div"]').css('flex', '1').css('min-width', '');

        } else if (val == '3') {
            /* Seluruh Malaysia */
            $('#main_district_div').hide();
        }
    });

    /* ── Tambah daerah row ── */
    $('#btnTambahDaerah').on('click', function () {
        skRowCounter++;
        var idx = skRowCounter;
        var isLainNegeri = $('[name="only_selangor"]:checked').val() == '2';
        var luarOpt = isLainNegeri ? '<option value="0">Luar Negeri Selangor</option>' : '';

        var row = [
            '<div class="sk-atau-divider my-2"><span>ATAU</span></div>',
            '<div class="d-flex align-items-center gap-2 mb-2">',
                '<div id="district_id_div' + idx + '" class="flex-grow-1">',
                    '<select class="form-select form-select-sm district_select" id="district_id_new' + idx + '" name="district_id_new[]">',
                        skBaseDaerahOpts + luarOpt,
                    '</select>',
                '</div>',
                '<div id="state_id_div' + idx + '" style="display:none;min-width:180px;">',
                    '<select class="form-select form-select-sm" id="state_id_new' + idx + '" name="state_id_new[]">',
                        skNegeriOpts,
                    '</select>',
                '</div>',
                '<button type="button" class="btn btn-sm btn-outline-danger btn-remove-sk flex-shrink-0">',
                    '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">',
                        '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
                    '</svg>',
                '</button>',
            '</div>'
        ].join('');
        $('#skDaerahRows').append(row);
    });

    /* ── Remove daerah row ── */
    $(document).on('click', '.btn-remove-sk', function () {
        var $row = $(this).closest('.d-flex');
        $row.prev('.sk-atau-divider').remove();
        $row.remove();
    });

    var savedDistrictRules = @json($districtRules);
    var savedOnlySelangor = @json($onlySelangor !== null ? (string) $onlySelangor : null);

    function applyDistrictRule(idx, rule) {
        if (!rule || rule.district_id === null || rule.district_id === '') {
            return;
        }

        var suffix = idx > 1 ? String(idx) : '';
        var $district = $('#district_id_new' + suffix);
        var $state = $('#state_id_new' + suffix);

        $district.val(String(rule.district_id)).trigger('change');
        if (String(rule.district_id) === '0' && $state.length) {
            $state.val(rule.state_id ? String(rule.state_id) : '').show();
        }
    }

    function restoreSyaratKhas() {
        if (savedOnlySelangor) {
            $('[name="only_selangor"][value="' + savedOnlySelangor + '"]').prop('checked', true).trigger('change');
        }

        if (!savedDistrictRules.length || !savedOnlySelangor || savedOnlySelangor === '3') {
            return;
        }

        applyDistrictRule(1, savedDistrictRules[0]);

        for (var i = 1; i < savedDistrictRules.length; i++) {
            $('#btnTambahDaerah').trigger('click');
            applyDistrictRule(skRowCounter, savedDistrictRules[i]);
        }
    }

    restoreSyaratKhas();

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
