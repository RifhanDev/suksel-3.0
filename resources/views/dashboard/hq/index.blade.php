@extends('layouts.v3.master')

@section('styles')
	<link href="{{ asset('css/dashboard-cards.css') }}" rel="stylesheet">
	<style>
		/* Tab switcher */
		.dashboard-tabs {
			display: inline-flex;
			background: #fff;
			border: 1px solid #e2e8f0;
			border-radius: 12px;
			padding: 5px;
			gap: 4px;
			box-shadow: 0 2px 6px rgba(0,0,0,0.04);
		}

		.dashboard-tabs li.nav-item {
			list-style: none;
			margin-bottom: 0;
		}

		.dashboard-tabs .nav-link {
			display: flex;
			align-items: center;
			gap: 8px;
			padding: 9px 20px;
			border-radius: 9px;
			font-weight: 600;
			font-size: 0.875rem;
			color: #64748b;
			text-decoration: none;
			transition: all 0.2s ease;
			border: none;
			white-space: nowrap;
		}

		.dashboard-tabs .nav-link:hover {
			background: #f8fafc;
			color: #1e293b;
		}

		/* JS adds .active to the <li> */
		.dashboard-tabs li.active .nav-link {
			background: var(--sg-red);
			color: #fff !important;
			box-shadow: 0 4px 12px rgba(196, 30, 58, 0.25);
		}

		/* Section header banner */
		.section-header {
			display: flex;
			align-items: center;
			gap: 14px;
			padding: 14px 18px;
			background: linear-gradient(135deg, #fff1f2 0%, #fff8f8 60%, #ffffff 100%);
			border: 1px solid #ffe4e6;
			border-radius: 12px;
			margin-bottom: 1.25rem;
		}

		.section-header-icon {
			width: 42px;
			height: 42px;
			background: var(--sg-red);
			border-radius: 10px;
			display: flex;
			align-items: center;
			justify-content: center;
			flex-shrink: 0;
			box-shadow: 0 4px 10px rgba(196, 30, 58, 0.25);
		}

		.section-header h5 {
			font-weight: 700;
			color: #1e293b;
			margin: 0 0 2px;
			font-size: 1rem;
			letter-spacing: -0.3px;
		}

		.section-header p {
			font-size: 0.75rem;
			color: #94a3b8;
			margin: 0;
		}

		@media print {
			.default-dashboard { page-break-after: always; }
			.chart-dashboard { page-break-after: always; }
			.stats-card { page-break-inside: avoid; box-shadow: none; border: 1px solid #ddd; }
		}
	</style>
@endsection

@section('content')
	<!-- Page Header -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Dashboard HQ</h3>
			<p class="text-muted small m-0">Ringkasan statistik dan carta analisis sistem.</p>
		</div>
		<div>
			<a onclick="window.print()" class="btn-form btn-form-secondary d-print-none" style="cursor: pointer;" aria-label="Cetak dashboard">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<polyline points="6 9 6 2 18 2 18 9"></polyline>
					<path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
					<rect x="6" y="14" width="12" height="8"></rect>
				</svg>
				Cetak
			</a>
		</div>
	</div>

	<!-- Tabs Nav -->
	<div class="d-print-none mb-4">
		<ul class="dashboard-tabs">
			<li id="li_default" class="nav-item">
				<a class="nav-link" href="{{ asset('dashboard/hq') }}">
					<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect>
						<rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect>
					</svg>
					Dashboard Ringkasan
				</a>
			</li>
			<li id="li_chart" class="nav-item">
				<a class="nav-link" href="{{ asset('dashboard/hq?view=chart') }}">
					<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<line x1="18" y1="20" x2="18" y2="10"></line>
						<line x1="12" y1="20" x2="12" y2="4"></line>
						<line x1="6" y1="20" x2="6" y2="14"></line>
					</svg>
					Dashboard Carta
				</a>
			</li>
		</ul>
	</div>

	<div class="row">
		<!-- Pengguna -->
		<div class="col-sm-12 col-lg-6 mb-4">
			<div class="section-header">
				<div class="section-header-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
						<circle cx="9" cy="7" r="4"></circle>
						<path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
						<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
					</svg>
				</div>
				<div>
					<h5>Pengguna</h5>
					<p>Statistik akaun pengguna sistem</p>
				</div>
			</div>
			<div class="default-dashboard">
				<div class="row g-3 mb-0">
					<div class="col-sm-6">
						<div class="stats-card status-success">
							<div class="stats-card-header">
								<h6 class="stats-card-title">Aktif</h6>
								<div class="stats-card-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
								</div>
							</div>
							<div class="stats-card-body">
								<h2 class="stats-card-value">{{ number_format(App\User::active()->count(), 0) }}</h2>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="stats-card status-danger">
							<div class="stats-card-header">
								<h6 class="stats-card-title">Tidak Aktif</h6>
								<div class="stats-card-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
								</div>
							</div>
							<div class="stats-card-body">
								<h2 class="stats-card-value">{{ number_format(App\User::notActive()->count(), 0) }}</h2>
							</div>
						</div>
					</div>
					<div class="col-12">
						<div class="stats-card">
							<div class="stats-card-header">
								<h6 class="stats-card-title">Jumlah Pengguna</h6>
								<div class="stats-card-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="9" x2="20" y2="9"></line><line x1="4" y1="15" x2="20" y2="15"></line><line x1="10" y1="3" x2="8" y2="21"></line><line x1="16" y1="3" x2="14" y2="21"></line></svg>
								</div>
							</div>
							<div class="stats-card-body">
								<h2 class="stats-card-value">{{ number_format(App\User::count(), 0) }}</h2>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="chart-dashboard mt-3">
				<div class="content-card p-4">
					<div id="chart_users" style="width: 100%; height: 350px;"></div>
				</div>
			</div>
		</div>

		<!-- Syarikat -->
		<div class="col-sm-12 col-lg-6 mb-4">
			<div class="section-header">
				<div class="section-header-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
						<polyline points="9 22 9 12 15 12 15 22"></polyline>
					</svg>
				</div>
				<div>
					<h5>Syarikat</h5>
					<p>Statistik langganan dan pendaftaran syarikat</p>
				</div>
			</div>
			<div class="default-dashboard">
				<div class="row g-3 mb-0">
					<div class="col-sm-6">
						<div class="stats-card status-success">
							<div class="stats-card-header">
								<h6 class="stats-card-title">Aktif</h6>
								<div class="stats-card-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
								</div>
							</div>
							<div class="stats-card-body">
								<h2 class="stats-card-value">{{ number_format(App\Vendor::activeSubscriptionCount(), 0) }}</h2>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="stats-card status-danger">
							<div class="stats-card-header">
								<h6 class="stats-card-title">Tidak Aktif</h6>
								<div class="stats-card-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
								</div>
							</div>
							<div class="stats-card-body">
								<h2 class="stats-card-value">{{ number_format(App\Vendor::nonActiveSubscriptionCount(), 0) }}</h2>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="stats-card">
							<div class="stats-card-header">
								<h6 class="stats-card-title">Belum Daftar</h6>
								<div class="stats-card-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
								</div>
							</div>
							<div class="stats-card-body">
								<h2 class="stats-card-value">{{ number_format(App\Vendor::pendingRegistrationCount(), 0) }}</h2>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="stats-card">
							<div class="stats-card-header">
								<h6 class="stats-card-title">Jumlah Syarikat</h6>
								<div class="stats-card-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="9" x2="20" y2="9"></line><line x1="4" y1="15" x2="20" y2="15"></line><line x1="10" y1="3" x2="8" y2="21"></line><line x1="16" y1="3" x2="14" y2="21"></line></svg>
								</div>
							</div>
							<div class="stats-card-body">
								<h2 class="stats-card-value">{{ number_format(App\Vendor::count(), 0) }}</h2>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="chart-dashboard mt-3">
				<div class="content-card p-4">
					<div id="chart_vendors" style="width: 100%; height: 350px;"></div>
				</div>
			</div>
		</div>
	</div>

	<!-- Tender & Sebutharga -->
	<div class="row mb-4">
		<div class="col-12">
			<div class="section-header">
				<div class="section-header-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
						<polyline points="14 2 14 8 20 8"></polyline>
						<line x1="16" y1="13" x2="8" y2="13"></line>
						<line x1="16" y1="17" x2="8" y2="17"></line>
					</svg>
				</div>
				<div>
					<h5>Tender & Sebutharga</h5>
					<p>Jumlah tender dan sebutharga mengikut tahun</p>
				</div>
			</div>
			<div class="default-dashboard">
				<div class="content-card p-4 mb-3 d-print-none">
					<form id="tender_summary" class="d-flex flex-wrap align-items-end gap-3">
						<div>
							<label class="form-label fw-medium small mb-1"><strong>Tahun :</strong></label>
							<input class="form-control" id="year_summary" type="text" name="year_summary" placeholder="Masukkan tahun">
						</div>
						<button type="submit" class="btn-form btn-form-primary">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline>
								<path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
							</svg>
							Jana
						</button>
					</form>
				</div>
				<div class="row g-3">
					<div class="col-sm-12 col-md-4">
						<div class="stats-card">
							<div class="stats-card-header">
								<h6 class="stats-card-title">Tender</h6>
								<div class="stats-card-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
								</div>
							</div>
							<div class="stats-card-body">
								<div id="tenderCount"><h2 class="stats-card-value"></h2></div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-4">
						<div class="stats-card">
							<div class="stats-card-header">
								<h6 class="stats-card-title">Sebutharga</h6>
								<div class="stats-card-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
								</div>
							</div>
							<div class="stats-card-body">
								<div id="quotationCount"><h2 class="stats-card-value"></h2></div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-4">
						<div class="stats-card">
							<div class="stats-card-header">
								<h6 class="stats-card-title">Jumlah</h6>
								<div class="stats-card-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="9" x2="20" y2="9"></line><line x1="4" y1="15" x2="20" y2="15"></line><line x1="10" y1="3" x2="8" y2="21"></line><line x1="16" y1="3" x2="14" y2="21"></line></svg>
								</div>
							</div>
							<div class="stats-card-body">
								<div id="tenderTotalCount"><h2 class="stats-card-value"></h2></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="chart-dashboard mt-3">
				<div class="content-card p-4 mb-3">
					<form id="tender_form" class="d-flex flex-wrap align-items-end gap-3">
						<div>
							<label class="form-label fw-medium small mb-1"><strong>Lihat :</strong></label>
							<select class="form-select pe-4" name="tender_view_type" id="tender_view_type">
								<option value="tender_yearly" selected>Tahunan</option>
								<option value="tender_monthly">Bulanan</option>
								<option value="tender_weekly">Mingguan</option>
							</select>
						</div>
						<div>
							<div id="tender_yearly">
								<label class="form-label fw-medium small mb-1">Tahun</label>
								<div class="input-group">
									<span class="input-group-text">Tahun</span>
									<input class="form-control" id="year_start" type="text" name="year_start">
								</div>
							</div>
							<div id="tender_weekly" class="d-none">
								<label class="form-label fw-medium small mb-1">Suku / Tahun</label>
								<div class="input-group">
									<span class="input-group-text">Suku</span>
									<input class="form-control x-uppercase" id="quarter_start" type="number" name="quarter_start" min="1" max="4" value="1">
									<span class="input-group-text">Tahun</span>
									<input class="form-control" id="year_quarter" type="text" name="year_quarter">
								</div>
							</div>
							<div id="tender_monthly" class="d-none">
								<label class="form-label fw-medium small mb-1">Tarikh</label>
								<div class="input-group">
									<span class="input-group-text">Tarikh</span>
									<input class="form-control x-uppercase" id="monthly_start" type="text" name="monthly_start">
								</div>
							</div>
						</div>
						<button type="submit" class="btn-form btn-form-primary">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline>
								<path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
							</svg>
							Jana
						</button>
					</form>
				</div>
				<div class="content-card p-4">
					<div id="chart_tenders" style="width: 100%; height: 350px;"></div>
				</div>
			</div>
		</div>
	</div>

	<!-- Transaksi -->
	<div class="row mb-4">
		<div class="col-12">
			<div class="section-header">
				<div class="section-header-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<polyline points="17 1 21 5 17 9"></polyline>
						<path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
						<polyline points="7 23 3 19 7 15"></polyline>
						<path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
					</svg>
				</div>
				<div>
					<h5>Transaksi</h5>
					<p>Nilai dan bilangan transaksi langganan & pembelian</p>
				</div>
			</div>
			<div class="default-dashboard">
				<div class="content-card p-4 mb-3 d-print-none">
					<form id="transaction_summary" class="d-flex flex-wrap align-items-end gap-3">
						<div>
							<label class="form-label fw-medium small mb-1"><strong>Tahun :</strong></label>
							<input class="form-control" id="year_summary" type="text" name="year_summary" placeholder="Masukkan tahun">
						</div>
						<button type="submit" class="btn-form btn-form-primary">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline>
								<path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
							</svg>
							Jana
						</button>
					</form>
				</div>
				<div class="row g-3">
					<div class="col-sm-12 col-md-6 col-lg-4">
						<div class="stats-card">
							<div class="stats-card-header">
								<h6 class="stats-card-title"># Langganan</h6>
								<div class="stats-card-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
								</div>
							</div>
							<div class="stats-card-body">
								<div id="subscriptionCount"><h2 class="stats-card-value"></h2></div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-6 col-lg-4">
						<div class="stats-card">
							<div class="stats-card-header">
								<h6 class="stats-card-title"># Pembelian Dokumen</h6>
								<div class="stats-card-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
								</div>
							</div>
							<div class="stats-card-body">
								<div id="purchaseCount"><h2 class="stats-card-value"></h2></div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-6 col-lg-4">
						<div class="stats-card">
							<div class="stats-card-header">
								<h6 class="stats-card-title">Jumlah Transaksi</h6>
								<div class="stats-card-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="9" x2="20" y2="9"></line><line x1="4" y1="15" x2="20" y2="15"></line><line x1="10" y1="3" x2="8" y2="21"></line><line x1="16" y1="3" x2="14" y2="21"></line></svg>
								</div>
							</div>
							<div class="stats-card-body">
								<div id="transactionTotal"><h2 class="stats-card-value"></h2></div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-6 col-lg-4">
						<div class="stats-card">
							<div class="stats-card-header">
								<h6 class="stats-card-title">Nilai Langganan</h6>
								<div class="stats-card-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
								</div>
							</div>
							<div class="stats-card-body">
								<div id="subscriptionValueSum"><h2 class="stats-card-value"></h2></div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-6 col-lg-4">
						<div class="stats-card">
							<div class="stats-card-header">
								<h6 class="stats-card-title">Nilai Pembelian</h6>
								<div class="stats-card-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
								</div>
							</div>
							<div class="stats-card-body">
								<div id="purchaseValueSum"><h2 class="stats-card-value"></h2></div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-6 col-lg-4">
						<div class="stats-card">
							<div class="stats-card-header">
								<h6 class="stats-card-title">Jumlah Nilai</h6>
								<div class="stats-card-icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="9" x2="20" y2="9"></line><line x1="4" y1="15" x2="20" y2="15"></line><line x1="10" y1="3" x2="8" y2="21"></line><line x1="16" y1="3" x2="14" y2="21"></line></svg>
								</div>
							</div>
							<div class="stats-card-body">
								<div id="transactionValueTotal"><h2 class="stats-card-value"></h2></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="chart-dashboard mt-3">
				<div class="content-card p-4 mb-3">
					<form id="transaction_form" class="d-flex flex-wrap align-items-end gap-3">
						<div>
							<label class="form-label fw-medium small mb-1"><strong>Lihat :</strong></label>
							<select class="form-select pe-4" name="transaction_view_type" id="transaction_view_type">
								<option value="transaction_yearly" selected>Tahunan</option>
								<option value="transaction_monthly">Bulanan</option>
								<option value="transaction_weekly">Mingguan</option>
							</select>
						</div>
						<div>
							<div id="transaction_yearly">
								<label class="form-label fw-medium small mb-1">Tahun</label>
								<div class="input-group">
									<span class="input-group-text">Tahun</span>
									<input class="form-control" id="year_start" type="text" name="year_start">
								</div>
							</div>
							<div id="transaction_weekly" class="d-none">
								<label class="form-label fw-medium small mb-1">Suku / Tahun</label>
								<div class="input-group">
									<span class="input-group-text">Suku</span>
									<input class="form-control x-uppercase" id="quarter_start" type="number" name="quarter_start" min="1" max="4" value="1">
									<span class="input-group-text">Tahun</span>
									<input class="form-control" id="year_quarter" type="text" name="year_quarter">
								</div>
							</div>
							<div id="transaction_monthly" class="d-none">
								<label class="form-label fw-medium small mb-1">Tarikh</label>
								<div class="input-group">
									<span class="input-group-text">Tarikh</span>
									<input class="form-control x-uppercase" id="monthly_start" type="text" name="monthly_start">
								</div>
							</div>
						</div>
						<button type="submit" class="btn-form btn-form-primary">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline>
								<path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
							</svg>
							Jana
						</button>
					</form>
				</div>
				<div class="content-card p-4 mb-3">
					<div id="chart_transactions" style="width: 100%; height: 350px;"></div>
				</div>
				<div class="content-card p-4">
					<div id="chart_transactions_value" style="width: 100%; height: 350px;"></div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.14.3/xlsx.full.min.js"></script>
	<script src="{{ asset('js/jspdf.umd.min.js') }}"></script>
	<script src="{{ asset('js/html2canvas.min.js') }}"></script>
	<script src="{{ asset('packages/echarts/dist/echarts.js') }}"></script>
	<script src="{{ asset('packages/echarts/theme/shine.js') }}"></script>
	<script src="{{ asset('js/dashboard-chart.js') }}"></script>
	<script type="text/javascript">
		$(document).ready(function() {

			@if ($view == 'chart')
				var user_active = "{{ App\User::active()->count() }}";
				var user_unactive = "{{ App\User::notActive()->count() }}";
				var vendor_active = "{{ App\Vendor::activeSubscriptionCount() }}";
				var vendor_unactive = "{{ App\Vendor::nonActiveSubscriptionCount() }}";
				var vendor_unregister = "{{ App\Vendor::pendingRegistrationCount() }}";

				$('#li_default').removeClass('active');
				$('#li_chart').addClass('active');
				$('.default-dashboard').hide();
				$('.chart-dashboard').show();

				$('#tender_view_type').change(function() {
					var view = $(this).val();
					const ids = ['tender_yearly', 'tender_weekly', 'tender_monthly'];
					ids.forEach(function(item, index) {
						if (ids[index] == view) {
							$('#' + ids[index]).removeClass('d-none');
						} else {
							$('#' + ids[index]).addClass('d-none');
						}
					});
				});

				$('#transaction_view_type').change(function() {
					var view = $(this).val();
					const ids = ['transaction_yearly', 'transaction_weekly', 'transaction_monthly'];
					ids.forEach(function(item, index) {
						if (ids[index] == view) {
							$('#' + ids[index]).removeClass('d-none');
						} else {
							$('#' + ids[index]).addClass('d-none');
						}
					});
				});

				userChart(user_active, user_unactive);
				vendorChart(vendor_active, vendor_unactive, vendor_unregister);
				dashboardTender();
				dashboardTransaction();
			@else
				$('#li_default').addClass('active');
				$('#li_chart').removeClass('active');
				$('.default-dashboard').show();
				$('.chart-dashboard').hide();

				dashboardTenderSummary();
				dashboardTransactionSummary();
			@endif

		});

		$('#tender_summary').submit(function(e) {
			e.preventDefault();
			var formData = new FormData(this);
			dashboardTenderSummary(formData);
		});

		$('#tender_form').submit(function(e) {
			e.preventDefault();
			var formData = new FormData(this);
			dashboardTender(formData);
		});

		$('#transaction_summary').submit(function(e) {
			e.preventDefault();
			var formData = new FormData(this);
			dashboardTransactionSummary(formData);
		});

		$('#transaction_form').submit(function(e) {
			e.preventDefault();
			var formData = new FormData(this);
			dashboardTransaction(formData);
		});

		function dashboardTenderSummary(formData) {

			$.ajax({
				url: "{{ route('dashboard.tender.summary') }}",
				type: "post",
				data: formData,
				processData: false,
				contentType: false,
				beforeSend: function() {
					$("#tenderCount h2").text('Loading..');
					$("#quotationCount h2").text('Loading..');
					$("#tenderTotalCount h2").text('Loading..');
				},
				success: function(response) {
					$("#tenderCount h2").text(response.tender_count);
					$("#quotationCount h2").text(response.quotation_count);
					$("#tenderTotalCount h2").text(response.total_tender);
				}
			})
		}

		function dashboardTender(formData) {

			$.ajax({
				url: "{{ route('dashboard.tender') }}",
				type: "post",
				data: formData,
				processData: false,
				contentType: false,
				beforeSend: function() {
					tendersChart.showLoading();
				},
				success: function(response) {
					tenderChart(response);
					tendersChart.hideLoading();
				}
			})
		}

		function dashboardTransactionSummary(formData) {

			$.ajax({
				url: "{{ route('dashboard.transaction.summary') }}",
				type: "post",
				data: formData,
				processData: false,
				contentType: false,
				beforeSend: function() {
					$("#subscriptionCount h2").text('Loading..');
					$("#purchaseCount h2").text('Loading..');
					$("#transactionTotal h2").text('Loading..');
				},
				success: function(response) {
					$("#subscriptionCount h2").text(response.subscription_count);
					$("#purchaseCount h2").text(response.purchase_count);
					$("#transactionTotal h2").text(response.total_transaction);
				}
			});

			$.ajax({
				url: "{{ route('dashboard.transaction-value.summary') }}",
				type: "post",
				data: formData,
				processData: false,
				contentType: false,
				beforeSend: function() {
					$("#subscriptionValueSum h2").text('Loading..');
					$("#purchaseValueSum h2").text('Loading..');
					$("#transactionValueTotal h2").text('Loading..');
				},
				success: function(response) {
					$("#subscriptionValueSum h2").text('RM' + response.subscription_sum);
					$("#purchaseValueSum h2").text('RM' + response.purchase_sum);
					$("#transactionValueTotal h2").text('RM' + response.total_transaction);
				}
			});
		}

		function dashboardTransaction(formData) {

			$.ajax({
				url: "{{ route('dashboard.transaction') }}",
				type: "post",
				data: formData,
				processData: false,
				contentType: false,
				beforeSend: function() {
					transactionsChart.showLoading();
				},
				success: function(response) {
					transactionChart(response);
					transactionsChart.hideLoading();
				}
			});

			$.ajax({
				url: "{{ route('dashboard.transaction-value') }}",
				type: "post",
				data: formData,
				processData: false,
				contentType: false,
				beforeSend: function() {
					transactionsValueChart.showLoading();
				},
				success: function(response) {
					transactionValueChart(response);
					transactionsValueChart.hideLoading();
				}
			});
		}
	</script>
@endsection
