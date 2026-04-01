@if (App\Models\Refund::canList())

	<link href="{{ asset('css/dashboard-cards.css') }}" rel="stylesheet">

	<div class="row g-4 mb-4">

		<!-- Card 1: Aduan Baru -->
		<div class="col-sm-6 col-xl-4">
			<div class="stats-card">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Aduan Baru</h6>
					<div class="stats-card-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
							<polyline points="14 2 14 8 20 8"></polyline>
							<line x1="12" y1="18" x2="12" y2="12"></line>
							<line x1="9" y1="15" x2="15" y2="15"></line>
						</svg>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value">{{ number_format(App\Models\Refund::pendingRefundComplaintCount(), 0) }}</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ action('RefundController@pendingRefundComplaintIndex') }}" class="stats-card-link">
						Lihat Semua
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline>
						</svg>
					</a>
				</div>
			</div>
		</div>

		<!-- Card 2: Aduan Ditolak -->
		<div class="col-sm-6 col-xl-4">
			<div class="stats-card warning">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Aduan Ditolak</h6>
					<div class="stats-card-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="12" cy="12" r="10"></circle>
							<line x1="15" y1="9" x2="9" y2="15"></line>
							<line x1="9" y1="9" x2="15" y2="15"></line>
						</svg>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value">{{ number_format(App\Models\Refund::rejectRefundComplaintCount(), 0) }}</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ action('RefundController@rejectRefundComplaintIndex') }}" class="stats-card-link">
						Lihat Semua
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline>
						</svg>
					</a>
				</div>
			</div>
		</div>

		<!-- Card 3: Selesai -->
		<div class="col-sm-6 col-xl-4">
			<div class="stats-card success">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Selesai Pemulangan Semula</h6>
					<div class="stats-card-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
							<polyline points="22 4 12 14.01 9 11.01"></polyline>
						</svg>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value">{{ number_format(App\Models\Refund::successRefundComplaintCount(), 0) }}</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ asset('refunds/complaint') }}" class="stats-card-link">
						Lihat Semua
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline>
						</svg>
					</a>
				</div>
			</div>
		</div>

	</div>
@endif