{{--
    Generic confirm dialog — one Ya/Batal prompt reused wherever a flow needs to ask
    before acting, with the title/body/icon set dynamically via JS.
    Driven by EvaluationSession.confirmDialog({ title, html, icon, confirmText }).
--}}
<div class="modal fade" id="modalConfirmDialog" tabindex="-1" aria-labelledby="modalConfirmDialogLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-confirm">
		<div class="modal-content">
			<div class="modal-header border-bottom-0 pt-4 pb-2 px-4">
				<div class="modal-confirm-center">
					<div class="modal-confirm-icon modal-confirm-icon--warning" id="confirmDialogIconWarning">
						<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
							<line x1="12" y1="9" x2="12" y2="13"></line>
							<line x1="12" y1="17" x2="12.01" y2="17"></line>
						</svg>
					</div>
					<div class="modal-confirm-icon modal-confirm-icon--info d-none" id="confirmDialogIconInfo">
						<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="12" cy="12" r="10"></circle>
							<line x1="12" y1="16" x2="12" y2="12"></line>
							<line x1="12" y1="8" x2="12.01" y2="8"></line>
						</svg>
					</div>
					<div class="modal-confirm-icon modal-confirm-icon--danger d-none" id="confirmDialogIconDanger">
						<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="12" cy="12" r="10"></circle>
							<line x1="15" y1="9" x2="9" y2="15"></line>
							<line x1="9" y1="9" x2="15" y2="15"></line>
						</svg>
					</div>
					<div class="modal-confirm-icon modal-confirm-icon--success d-none" id="confirmDialogIconSuccess">
						<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M9 11l3 3L22 4"></path>
							<path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
						</svg>
					</div>
					<h5 class="modal-confirm-title" id="confirmDialogTitle"></h5>
					<div class="modal-confirm-desc" id="confirmDialogBody"></div>
				</div>
			</div>
			<div class="modal-footer border-top-0 px-4 pb-4 pt-2 d-flex justify-content-center gap-2">
				<button type="button" class="btn-form btn-form-secondary" id="confirmDialogCancel">Batal</button>
				<button type="button" class="btn-form btn-form-success" id="confirmDialogConfirm">Ya, Teruskan</button>
			</div>
		</div>
	</div>
</div>
