{{-- MODAL: Prebiu Dokumen / Fail --}}
<div class="modal fade" id="modalPreview" tabindex="-1" aria-labelledby="modalPreviewLabel" aria-hidden="true" style="z-index: 1065;">
    <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 90%; height: 90vh;">
        <div class="modal-content h-100 border-0 shadow-lg rounded-3">
            <div class="modal-header px-4 py-3 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="rounded-2 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 40px; height: 40px; background-color: #e0f2fe;">
                        <i class="bi bi-file-earmark-pdf text-primary fs-5" id="previewIcon"></i>
                    </div>
                    <div>
                        <span class="d-block text-uppercase fw-semibold" style="font-size: 0.62rem; letter-spacing: 0.06em; color: #6b7280;">Prebiu Dokumen</span>
                        <h6 id="modalPreviewTitle" class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">-</h6>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a id="btnNewTabPreview" href="#" target="_blank" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1">
                        <i class="bi bi-box-arrow-up-right"></i> <span class="d-none d-sm-inline">Buka di Tab Baru</span>
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0 bg-light position-relative d-flex align-items-center justify-content-center" style="height: calc(100% - 75px); overflow: hidden;">
                <div id="previewSpinner" class="spinner-border text-primary position-absolute" role="status" style="z-index: 10; width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Memuatkan...</span>
                </div>
                <iframe id="previewIframe" src="" class="w-100 h-100 border-0 d-none" style="background: white;"></iframe>
                <div id="previewImageWrapper" class="w-100 h-100 d-none overflow-auto p-3 text-center">
                    <img id="previewImage" src="" class="img-fluid rounded shadow-sm" style="max-height: 100%; object-fit: contain;" />
                </div>
                <div id="previewFallback" class="text-center p-4 d-none">
                    <div class="bg-warning-subtle text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-file-earmark-zip fs-1"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Prebiu tidak disokong</h5>
                    <p class="text-muted small mx-auto" style="max-width: 400px;">Format fail ini tidak menyokong paparan terus. Sila klik butang di bawah untuk memuat turun.</p>
                    <a id="btnFallbackDownload" href="#" target="_blank" class="btn btn-primary px-4 fw-bold mt-2">
                        <i class="bi bi-download me-2"></i>Muat Turun Fail
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
