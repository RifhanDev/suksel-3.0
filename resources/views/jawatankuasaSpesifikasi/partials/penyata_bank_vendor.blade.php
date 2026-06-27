<div class="content-card mb-4 p-0">
    <div class="borang-title-bar">Penyata Bank</div>
    <div class="content-card-body p-4">
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
    </div>
</div>
