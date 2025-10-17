<!-- Modal -->
<div class="modal fade" id="myPopup" tabindex="-1" role="dialog" aria-labelledby="myPopupLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<form id="myPopupForm" action="" method="">
			@csrf
			<div class="modal-content">
				<div id="modal_header" class="modal-header">
					<h5 id="modal_title" class="modal-title" id="myPopupLabel">Modal title</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div id="modal_body" class="modal-body">
				</div>
				<div class="modal-footer">
					<button id="button_cancel" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button id="button_confirm" type="button" class="btn btn-primary">Save changes</button>
				</div>
			</div>
		</form>
	</div>
</div>
