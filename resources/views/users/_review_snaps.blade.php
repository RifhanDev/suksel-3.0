@if ($user->ability(['Admin', 'Registration Assessor'], []))
	<div class="row mb-4">
		<div class="col-sm-6">
			<div class="card">
				<div class="card-body text-center">
					<div class="display-6 fw-bold text-warning mb-2">
						{{ number_format(App\User::pendingReviewCount(), 0) }}
					</div>
					<div class="text-muted">
						<i class="ti ti-clock me-1"></i>
						Semak Akaun Belum Selesai
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="card">
				<div class="card-body text-center">
					<div class="display-6 fw-bold text-success mb-2">
						{{ number_format(App\User::reviewedCount(), 0) }}
					</div>
					<div class="text-muted">
						<i class="ti ti-circle-check me-1"></i>
						Semak Akaun Selesai
					</div>
				</div>
			</div>
		</div>
	</div>
@endif
