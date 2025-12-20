{{-- Transaction Type Stats Cards --}}

@php $user = Auth::user(); @endphp

@if($user->ability(['Admin', 'Registration Assessor'], ['Vendor:approve']))
	<div class="row g-4 mb-4">
		<!-- Card 1: Langganan (Subscription) -->
		<div class="col-sm-6 col-xl-4">
			<div class="stats-card">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Langganan</h6>
					<div class="stats-card-icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value" id="subscribe_trans_count">
						<span class="text-muted small">Sedang diproses...</span>
					</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ action('TransactionsController@subscriptionIndex') }}" class="stats-card-link">
						Lihat Semua
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
					</a>
				</div>
			</div>
		</div>

		<!-- Card 2: Pembelian Dokumen (Document Purchase) -->
		<div class="col-sm-6 col-xl-4">
			<div class="stats-card">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Pembelian Dokumen</h6>
					<div class="stats-card-icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value" id="purchase_trans_count">
						<span class="text-muted small">Sedang diproses...</span>
					</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ action('TransactionsController@purchaseIndex') }}" class="stats-card-link">
						Lihat Semua
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
					</a>
				</div>
			</div>
		</div>

		<!-- Card 3: Jumlah Transaksi (Total Transactions) -->
		<div class="col-sm-6 col-xl-4">
			<div class="stats-card status-primary">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Jumlah Transaksi</h6>
					<div class="stats-card-icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value" id="total_trans_count">
						<span class="text-muted small">Sedang diproses...</span>
					</h2>
				</div>
				<div class="stats-card-footer">
					<a href="/transactions" class="stats-card-link">
						Lihat Semua
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
					</a>
				</div>
			</div>
		</div>
	</div>
@endif
