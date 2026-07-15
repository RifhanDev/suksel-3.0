<div class="content-card mb-4 p-0">
    <div class="borang-title-bar">
        @if ($showPeriodConfig ?? false)
            Penyata Bank Terkini (3 Bulan Terakhir) Syarikat
        @else
            Penyata Bank
        @endif
    </div>
    <div class="content-card-body p-4">
        @if ($showPeriodConfig ?? false)
            <div class="rounded-2 px-3 py-2 mb-3 d-inline-flex align-items-center gap-2"
                style="background:#eff6ff; border:1px solid #bfdbfe; font-size:0.78rem; color:#1e40af;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                Tetapkan tempoh penyata bank sekali sahaja. Tempoh ini akan dikenakan kepada semua akaun apabila petender mengisi borang.
            </div>
        @endif

        {{-- Global period: set once by admin, shared by all akaun --}}
        @if ($showPeriodConfig || ($showVendorForm ?? false))
            <div id="penyata-global-period" class="mb-4 pb-3 border-bottom">
                <p class="text-muted small mb-3">Sila pilih bulan pertama penyata bank yang perlu dikemukakan oleh petender</p>
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small">Dari (Bulan) <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <div class="col-6">
                                <select id="penyata-dari-bulan" class="form-select form-select-sm" @disabled($viewOnly ?? false)>
                                    <option value="">Pilih Bulan</option>
                                    @for ($mf = 1; $mf <= 12; $mf++)
                                        <option value="{{ $mf }}">{{ ['','Januari','Februari','Mac','April','Mei','Jun','Julai','Ogos','September','Oktober','November','Disember'][$mf] }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-6">
                                <select id="penyata-dari-tahun" class="form-select form-select-sm" @disabled($viewOnly ?? false)>
                                    <option value="">Pilih Tahun</option>
                                    @foreach (range(now()->year - 10, now()->year) as $yf)
                                        <option value="{{ $yf }}">{{ $yf }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small">Hingga (Bulan)</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="text" id="penyata-hingga-bulan-display" class="form-control form-control-sm bg-light" readonly tabindex="-1">
                            </div>
                            <div class="col-6">
                                <input type="text" id="penyata-hingga-tahun-display" class="form-control form-control-sm bg-light" readonly tabindex="-1">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="penyata-global-period-preview" class="rounded-2 px-3 py-2 mt-3"
                    style="background:#f8fafc;border:1px solid #e2e8f0;font-size:0.78rem;color:#64748b;">
                    Tempoh bulan akan dipaparkan selepas Dari (Bulan) dipilih.
                </div>
            </div>
        @endif

        @if ($showVendorForm ?? false)
            <div id="penyata-akaun-container"></div>

            @unless ($viewOnly ?? false)
                <button type="button" id="btn-tambah-akaun" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Tambah Akaun
                </button>
            @endunless

            <div class="row justify-content-end mt-4 pt-3 border-top">
                <div class="col-12 col-md-5">
                    <div class="d-flex align-items-center gap-3">
                        <label class="fw-semibold text-muted mb-0 flex-shrink-0" style="font-size:0.82rem;">
                            Jumlah Keseluruhan Penyata Bank (RM)
                        </label>
                        <input type="text" class="form-control form-control-sm bg-light text-end fw-semibold"
                            id="penyata-grand-total" readonly tabindex="-1">
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
