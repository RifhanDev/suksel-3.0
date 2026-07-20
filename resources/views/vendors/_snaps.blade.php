@if (Auth::user()->ability(['Admin', 'Registration Assessor'], ['Vendor:approve']))
	
	<link href="{{ asset('css/dashboard-cards.css') }}" rel="stylesheet">

	<div class="row g-4 mb-4">
		
        <!-- Card 1: Pending Registration -->
		<div class="col-sm-6 col-xl-3">
			<div class="stats-card">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Pendaftaran Belum Selesai</h6>
					<div class="stats-card-icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value">{{ number_format(App\Vendor::pendingRegistrationCount(), 0) }}</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ action('VendorsController@pendingRegistrationIndex') }}" class="stats-card-link">
						Lihat Semua
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
					</a>
				</div>
			</div>
		</div>

		<!-- Card 2: Pending Approval -->
		<div class="col-sm-6 col-xl-3">
			<div class="stats-card">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Belum Diluluskan</h6>
					<div class="stats-card-icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="9" y1="15" x2="15" y2="15"></line></svg>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value">{{ number_format(App\Vendor::pendingNewApproval1Count(), 0) }}</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ action('VendorsController@approvalNew1Index') }}" class="stats-card-link">
						Lihat Semua
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
					</a>
				</div>
			</div>
		</div>

		<!-- Card 3: Update Requests -->
		<div class="col-sm-6 col-xl-3">
			<div class="stats-card">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Permintaan Kemaskini</h6>
					<div class="stats-card-icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value">{{ number_format(App\CodeRequest::pendingCount(), 0) }}</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ asset('requests') }}" class="stats-card-link">
						Lihat Semua
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
					</a>
				</div>
			</div>
		</div>

		<!-- Card 4: Total Companies -->
		<div class="col-sm-6 col-xl-3">
			<div class="stats-card">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Jumlah Syarikat</h6>
					<div class="stats-card-icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect><path d="M9 22v-4h6v4"></path><path d="M8 6h.01"></path><path d="M16 6h.01"></path><path d="M12 6h.01"></path><path d="M12 10h.01"></path><path d="M12 14h.01"></path><path d="M16 10h.01"></path><path d="M16 14h.01"></path><path d="M8 10h.01"></path><path d="M8 14h.01"></path></svg>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value">{{ number_format(App\Vendor::count(), 0) }}</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ action('VendorsController@index') }}" class="stats-card-link">
						Lihat Semua
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
					</a>
				</div>
			</div>
		</div>
	</div>

	<!-- Second Row: 2 Cards -->
	<div class="row g-4 mb-4">
		<!-- Card 5: Active Companies -->
		<div class="col-sm-6">
			<div class="stats-card status-success h-100">
				<div class="d-flex align-items-center">
					<div class="stats-card-icon me-3">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
					</div>
					<div>
						<h6 class="stats-card-title mb-1">Syarikat Aktif</h6>
						<h2 class="stats-card-value" style="font-size: 1.5rem;">{{ number_format(App\Vendor::activeSubscriptionCount(), 0) }}</h2>
                        <small class="text-success fw-bold">Langganan Aktif</small>
					</div>
				</div>
			</div>
		</div>

		<!-- Card 6: Inactive Companies -->
		<div class="col-sm-6">
			<div class="stats-card status-danger h-100">
				<div class="d-flex align-items-center">
					<div class="stats-card-icon me-3">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
					</div>
					<div>
						<h6 class="stats-card-title mb-1">Syarikat Tidak Aktif</h6>
						<h2 class="stats-card-value" style="font-size: 1.5rem;">{{ number_format(App\Vendor::nonActiveSubscriptionCount(), 0) }}</h2>
                        <small class="text-danger fw-bold">Langganan Tamat</small>
					</div>
				</div>
			</div>
		</div>
	</div>
@endif