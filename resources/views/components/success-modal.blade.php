{{--
    Canonical success dialog (Berjaya).
    Included globally from layouts.v3.master — call window.showBerjayaModal({ title, message, onClose }).
--}}
<div class="modal fade" id="stosSuccessModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 360px;">
        <div class="modal-content text-center p-4">
            <div class="mb-3">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="12" cy="12" r="10" fill="#E6F7F3" />
                    <path d="M10 14.2L7.8 12l-1.4 1.4L10 17l8-8-1.4-1.4L10 14.2z" fill="#19c1a7" />
                </svg>
            </div>
            <h5 class="fw-bold mb-2" id="stosSuccessModalTitle">Berjaya</h5>
            <p class="text-muted mb-4" id="stosSuccessModalMessage">Maklumat telah berjaya disimpan.</p>
            <button type="button" class="btn-form btn-form-primary mx-auto" id="stosSuccessModalClose" data-bs-dismiss="modal">
                Tutup
            </button>
        </div>
    </div>
</div>
