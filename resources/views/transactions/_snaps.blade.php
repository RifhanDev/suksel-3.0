{{-- Type Transaction Cards --}}

@php $user = Auth::user(); @endphp

@if ($user->ability(['Admin', 'Registration Assessor'], ['Vendor:approve']))
	<div class="row stats-row mb-4">
		<div class="col-sm-6 col-md-4 mb-3">
			<div class="stats-card">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Langganan</h6>
					<div class="stats-card-icon">
						<i class="ti ti-repeat"></i>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value" id="subscribe_trans_count">
						<span style="font-size:14px; font-weight:400;">Sedang diproses...</span>
					</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ action('TransactionsController@subscriptionIndex') }}" class="stats-card-link">
						Lihat Semua <i class="ti ti-arrow-right"></i>
					</a>
				</div>
			</div>
		</div>
		<div class="col-sm-6 col-md-4 mb-3">
			<div class="stats-card warning">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Pembelian Dokumen</h6>
					<div class="stats-card-icon">
						<i class="ti ti-shopping-cart"></i>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value" id="purchase_trans_count">
						<span style="font-size:14px; font-weight:400;">Sedang diproses...</span>
					</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ action('TransactionsController@purchaseIndex') }}" class="stats-card-link">
						Lihat Semua <i class="ti ti-arrow-right"></i>
					</a>
				</div>
			</div>
		</div>
		<div class="col-sm-6 col-md-4 mb-3">
			<div class="stats-card success">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Jumlah Transaksi</h6>
					<div class="stats-card-icon">
						<i class="ti ti-chart-bar"></i>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value" id="total_trans_count">
						<span style="font-size:14px; font-weight:400;">Sedang diproses...</span>
					</h2>
				</div>
				<div class="stats-card-footer">
					<a href="/transactions" class="stats-card-link">
						Lihat Semua <i class="ti ti-arrow-right"></i>
					</a>
				</div>
			</div>
		</div>
	</div>
@endif
