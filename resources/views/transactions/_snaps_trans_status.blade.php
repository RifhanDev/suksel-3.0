{{-- Transaction Status Stats Cards --}}

@php $user = Auth::user(); @endphp

@if ($user->ability(['Admin', 'Registration Assessor'], ['Vendor:approve']))
	<div class="row g-4 mb-4">
		<!-- Card 1: Berjaya (Success) -->
		<div class="col-sm-6 col-xl-4">
			<div class="stats-card status-success">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Berjaya</h6>
					<div class="stats-card-icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
							stroke-linecap="round" stroke-linejoin="round">
							<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
							<polyline points="22 4 12 14.01 9 11.01"></polyline>
						</svg>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value" id="success_trans_count">
						<span class="text-muted small">Sedang diproses...</span>
					</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ action('TransactionsController@successTransIndex') }}" class="stats-card-link">
						Lihat Semua
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<line x1="5" y1="12" x2="19" y2="12"></line>
							<polyline points="12 5 19 12 12 19"></polyline>
						</svg>
					</a>
				</div>
			</div>
		</div>

		<!-- Card 2: Belum Diterima (Pending) -->
		<div class="col-sm-6 col-xl-4">
			<div class="stats-card status-warning">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Belum Diterima</h6>
					<div class="stats-card-icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
							stroke-linecap="round" stroke-linejoin="round">
							<circle cx="12" cy="12" r="10"></circle>
							<polyline points="12 6 12 12 16 14"></polyline>
						</svg>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value" id="pending_trans_count">
						<span class="text-muted small">Sedang diproses...</span>
					</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ action('TransactionsController@pendingTransIndex') }}" class="stats-card-link">
						Lihat Semua
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<line x1="5" y1="12" x2="19" y2="12"></line>
							<polyline points="12 5 19 12 12 19"></polyline>
						</svg>
					</a>
				</div>
			</div>
		</div>

		<!-- Card 3: Dalam Proses Pengesahan (Pending Authorization) -->
		<div class="col-sm-6 col-xl-4">
			<div class="stats-card status-info">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Dalam Proses Pengesahan</h6>
					<div class="stats-card-icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
							stroke-linecap="round" stroke-linejoin="round">
							<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
							<polyline points="14 2 14 8 20 8"></polyline>
							<line x1="9" y1="15" x2="15" y2="15"></line>
						</svg>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value" id="pending_authorization_trans_count">
						<span class="text-muted small">Sedang diproses...</span>
					</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ action('TransactionsController@pendingAuthTransIndex') }}" class="stats-card-link">
						Lihat Semua
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<line x1="5" y1="12" x2="19" y2="12"></line>
							<polyline points="12 5 19 12 12 19"></polyline>
						</svg>
					</a>
				</div>
			</div>
		</div>

		<!-- Card 4: Gagal (Failed) -->
		<div class="col-sm-6 col-xl-4">
			<div class="stats-card status-danger">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Gagal</h6>
					<div class="stats-card-icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
							stroke-linecap="round" stroke-linejoin="round">
							<circle cx="12" cy="12" r="10"></circle>
							<line x1="15" y1="9" x2="9" y2="15"></line>
							<line x1="9" y1="9" x2="15" y2="15"></line>
						</svg>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value" id="failed_trans_count">
						<span class="text-muted small">Sedang diproses...</span>
					</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ action('TransactionsController@failedTransIndex') }}" class="stats-card-link">
						Lihat Semua
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<line x1="5" y1="12" x2="19" y2="12"></line>
							<polyline points="12 5 19 12 12 19"></polyline>
						</svg>
					</a>
				</div>
			</div>
		</div>

		<!-- Card 5: Ditolak (Declined) -->
		<div class="col-sm-6 col-xl-4">
			<div class="stats-card status-secondary">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Ditolak</h6>
					<div class="stats-card-icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
							stroke-linecap="round" stroke-linejoin="round">
							<path
								d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17">
							</path>
						</svg>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value" id="declined_trans_count">
						<span class="text-muted small">Sedang diproses...</span>
					</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ action('TransactionsController@declinedTransIndex') }}" class="stats-card-link">
						Lihat Semua
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<line x1="5" y1="12" x2="19" y2="12"></line>
							<polyline points="12 5 19 12 12 19"></polyline>
						</svg>
					</a>
				</div>
			</div>
		</div>
	</div>
@endif
