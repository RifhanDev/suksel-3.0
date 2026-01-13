{{-- Status Transaction Cards --}}

@php $user = Auth::user(); @endphp

@if ($user->ability(['Admin', 'Registration Assessor'], ['Vendor:approve']))
	<link href="{{ asset('css/dashboard-cards.css') }}" rel="stylesheet">

	<div class="row stats-row mb-4">
		<div class="col-sm-6 col-md-4 col-lg-2 mb-3">
			<div class="stats-card success">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Berjaya</h6>
					<div class="stats-card-icon">
						<i class="ti ti-check"></i>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value" id="success_trans_count">
						<span style="font-size:14px; font-weight:400;">Sedang diproses...</span>
					</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ action('TransactionsController@successTransIndex') }}" class="stats-card-link">
						Lihat Semua <i class="ti ti-arrow-right"></i>
					</a>
				</div>
			</div>
		</div>
		<div class="col-sm-6 col-md-4 col-lg-2 mb-3">
			<div class="stats-card warning">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Belum Diterima</h6>
					<div class="stats-card-icon">
						<i class="ti ti-clock"></i>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value" id="pending_trans_count">
						<span style="font-size:14px; font-weight:400;">Sedang diproses...</span>
					</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ action('TransactionsController@pendingTransIndex') }}" class="stats-card-link">
						Lihat Semua <i class="ti ti-arrow-right"></i>
					</a>
				</div>
			</div>
		</div>
		<div class="col-sm-6 col-md-4 col-lg-2 mb-3">
			<div class="stats-card info">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Dalam Proses Pengesahan</h6>
					<div class="stats-card-icon">
						<i class="ti ti-shield-check"></i>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value" id="pending_authorization_trans_count">
						<span style="font-size:14px; font-weight:400;">Sedang diproses...</span>
					</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ action('TransactionsController@pendingAuthTransIndex') }}" class="stats-card-link">
						Lihat Semua <i class="ti ti-arrow-right"></i>
					</a>
				</div>
			</div>
		</div>
		<div class="col-sm-6 col-md-4 col-lg-2 mb-3">
			<div class="stats-card danger">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Gagal</h6>
					<div class="stats-card-icon">
						<i class="ti ti-x"></i>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value" id="failed_trans_count">
						<span style="font-size:14px; font-weight:400;">Sedang diproses...</span>
					</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ action('TransactionsController@failedTransIndex') }}" class="stats-card-link">
						Lihat Semua <i class="ti ti-arrow-right"></i>
					</a>
				</div>
			</div>
		</div>
		<div class="col-sm-6 col-md-4 col-lg-2 mb-3">
			<div class="stats-card danger">
				<div class="stats-card-header">
					<h6 class="stats-card-title">Ditolak</h6>
					<div class="stats-card-icon">
						<i class="ti ti-ban"></i>
					</div>
				</div>
				<div class="stats-card-body">
					<h2 class="stats-card-value" id="declined_trans_count">
						<span style="font-size:14px; font-weight:400;">Sedang diproses...</span>
					</h2>
				</div>
				<div class="stats-card-footer">
					<a href="{{ action('TransactionsController@declinedTransIndex') }}" class="stats-card-link">
						Lihat Semua <i class="ti ti-arrow-right"></i>
					</a>
				</div>
			</div>
		</div>
	</div>
@endif
