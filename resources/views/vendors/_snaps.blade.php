@if (Auth::user()->ability(['Admin', 'Registration Assessor'], ['Vendor:approve']))
	<!-- Dashboard Cards Stylesheet -->
	<link href="{{ asset('css/dashboard-cards.css') }}" rel="stylesheet">

	<!-- First Row: 4 Cards -->
	<div class="row stats-row">
		<!-- Card 1: Pending Registration -->
		<div class="col-sm-6 col-md-3 mb-3">
			<div class="stats-card">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Pendaftaran Belum Selesai</h6>
					<div class="stats-card-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="12" cy="12" r="10"></circle>
							<polyline points="12 6 12 12 16 14"></polyline>
						</svg>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value">{{ number_format(App\Vendor::pendingRegistrationCount(), 0) }}</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ action('VendorsController@pendingRegistrationIndex') }}" class="stats-card-link">
						Lihat Semua
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<polyline points="9 18 15 12 9 6"></polyline>
						</svg>
					</a>
				</div>
			</div>
		</div>

		<!-- Card 2: Pending Approval -->
		<div class="col-sm-6 col-md-3 mb-3">
			<div class="stats-card warning">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Belum Diluluskan</h6>
					<div class="stats-card-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M5 22h14"></path>
							<path d="M5 2h14"></path>
							<path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22"></path>
							<path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2"></path>
						</svg>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value">{{ number_format(App\Vendor::pendingNewApproval1Count(), 0) }}</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ action('VendorsController@approvalNew1Index') }}" class="stats-card-link">
						Lihat Semua
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<polyline points="9 18 15 12 9 6"></polyline>
						</svg>
					</a>
				</div>
			</div>
		</div>

		<!-- Card 3: Update Requests -->
		<div class="col-sm-6 col-md-3 mb-3">
			<div class="stats-card success">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Permintaan Kemaskini</h6>
					<div class="stats-card-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M21 2v6h-6"></path>
							<path d="M3 12a9 9 0 0 1 15-6.7L21 8"></path>
							<path d="M3 22v-6h6"></path>
							<path d="M21 12a9 9 0 0 1-15 6.7L3 16"></path>
						</svg>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value">{{ number_format(App\CodeRequest::pendingCount(), 0) }}</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ asset('requests') }}" class="stats-card-link">
						Lihat Semua
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<polyline points="9 18 15 12 9 6"></polyline>
						</svg>
					</a>
				</div>
			</div>
		</div>

		<!-- Card 4: Total Companies -->
		<div class="col-sm-6 col-md-3 mb-3">
			<div class="stats-card info">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Jumlah Syarikat</h6>
					<div class="stats-card-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect>
							<path d="M9 22v-4h6v4"></path>
							<path d="M8 6h.01"></path>
							<path d="M16 6h.01"></path>
							<path d="M12 6h.01"></path>
							<path d="M12 10h.01"></path>
							<path d="M12 14h.01"></path>
							<path d="M16 10h.01"></path>
							<path d="M16 14h.01"></path>
							<path d="M8 10h.01"></path>
							<path d="M8 14h.01"></path>
						</svg>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value">{{ number_format(App\Vendor::count(), 0) }}</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ action('VendorsController@index') }}" class="stats-card-link">
						Lihat Semua
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<polyline points="9 18 15 12 9 6"></polyline>
						</svg>
					</a>
				</div>
			</div>
		</div>
	</div>

	<!-- Second Row: 2 Cards -->
	<div class="row stats-row">
		<!-- Card 5: Active Companies -->
		<div class="col-sm-6 mb-3">
			<div class="stats-card success">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Syarikat Aktif</h6>
					<div class="stats-card-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="12" cy="12" r="10"></circle>
							<path d="m9 12 2 2 4-4"></path>
						</svg>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value">{{ number_format(App\Vendor::activeSubscriptionCount(), 0) }}</h2>
				</div>
				<div class="stats-card-footer">
					<span class="stats-badge">
						<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<line x1="12" y1="19" x2="12" y2="5"></line>
							<polyline points="5 12 12 5 19 12"></polyline>
						</svg>
						Langganan Aktif
					</span>
				</div>
			</div>
		</div>

		<!-- Card 6: Inactive Companies -->
		<div class="col-sm-6 mb-3">
			<div class="stats-card warning">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Syarikat Tidak Aktif</h6>
					<div class="stats-card-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="12" cy="12" r="10"></circle>
							<line x1="12" y1="8" x2="12" y2="12"></line>
							<line x1="12" y1="16" x2="12.01" y2="16"></line>
						</svg>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value">{{ number_format(App\Vendor::nonActiveSubscriptionCount(), 0) }}</h2>
				</div>
				<div class="stats-card-footer">
					<span class="stats-badge warning">
						<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="12" cy="12" r="10"></circle>
							<line x1="15" y1="9" x2="9" y2="15"></line>
							<line x1="9" y1="9" x2="15" y2="15"></line>
						</svg>
						Langganan Tamat
					</span>
				</div>
			</div>
		</div>
	</div>
@endif
