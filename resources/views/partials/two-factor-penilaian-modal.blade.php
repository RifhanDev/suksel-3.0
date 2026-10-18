{{-- 2FA code prompt for penilaian Hantar; driven by public/js/two-factor-penilaian.js. A div, not a form, so prevent-double-submit.js never locks it. --}}
<div class="modal fade" id="modalTwoFactorPenilaian" tabindex="-1" aria-labelledby="tfpModalTitle" aria-hidden="true"
     data-bs-backdrop="static" data-bs-keyboard="false">
	<div class="modal-dialog modal-dialog-centered modal-confirm">
		<div class="modal-content">
			<div class="modal-header border-bottom-0 pt-4 pb-2 px-4">
				<div class="modal-confirm-center w-100">
					<div class="modal-confirm-icon modal-confirm-icon--info">
						<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
							<path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
						</svg>
					</div>
					<h5 class="modal-confirm-title" id="tfpModalTitle">Pengesahan Dua Faktor</h5>
					<div class="modal-confirm-desc" id="tfpModalDesc"></div>

					{{-- Code entry --}}
					<div id="tfpCodeArea" class="mt-3 text-start">
						<label for="tfpCodeInput" class="form-label small fw-bold text-uppercase text-muted" id="tfpCodeLabel">Kod 6 digit daripada aplikasi pengesah</label>
						<input type="text" class="form-control form-control-lg text-center" id="tfpCodeInput"
						       inputmode="numeric" autocomplete="one-time-code" maxlength="6" placeholder="000000">
						<div class="text-danger small mt-2 d-none" id="tfpError" role="alert"></div>
						<div class="text-center mt-3">
							<button type="button" class="btn-action btn-action-blue bg-transparent border-0" id="tfpToggleRecovery">
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="7.5" cy="15.5" r="5.5"></circle><path d="m21 2-9.6 9.6"></path><path d="m15.5 7.5 3 3L22 7l-3-3"></path></svg>
								<span id="tfpToggleRecoveryText">Guna kod pemulihan</span>
							</button>
						</div>
					</div>

					{{-- Not enrolled yet --}}
					<div id="tfpSetupArea" class="mt-3 d-none">
						<a href="{{ route('2fa.setup') }}" target="_blank" rel="noopener" class="btn-form btn-form-primary d-inline-block" id="tfpSetupLink">Aktifkan 2FA</a>
						<p class="text-muted small mt-2 mb-0">Halaman tetapan 2FA dibuka di tab baharu. Selepas selesai, tekan Hantar semula di halaman ini.</p>
					</div>
				</div>
			</div>
			<div class="modal-footer border-top-0 px-4 pb-4 pt-2 d-flex justify-content-center gap-2">
				<button type="button" class="btn-form btn-form-secondary" id="tfpCancel">Batal</button>
				<button type="button" class="btn-form btn-form-success" id="tfpConfirm">Sahkan</button>
			</div>
		</div>
	</div>
</div>
