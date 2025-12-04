@extends('layouts.modern')

@section('styles')
	<style>
		@keyframes fadeInUp {
			from {
				opacity: 0;
				transform: translateY(30px);
			}
			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		@keyframes countUp {
			from {
				opacity: 0;
				transform: scale(0.8);
			}
			to {
				opacity: 1;
				transform: scale(1);
			}
		}

		.stat-card {
			background: white;
			border-radius: 12px;
			box-shadow: 0 2px 8px rgba(0,0,0,0.08);
			overflow: hidden;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			animation: fadeInUp 0.6s ease-out backwards;
			margin-bottom: 20px;
		}

		.stat-card:hover {
			transform: translateY(-5px);
			box-shadow: 0 8px 24px rgba(0,0,0,0.15);
		}

		.stat-card .card-body {
			padding: 30px 20px;
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			position: relative;
			overflow: hidden;
		}

		.stat-card .card-body::before {
			content: '';
			position: absolute;
			top: -50%;
			right: -50%;
			width: 200%;
			height: 200%;
			background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
			transition: all 0.6s ease;
		}

		.stat-card:hover .card-body::before {
			transform: translate(-25%, -25%);
		}

		.stat-card .card-body h2 {
			color: white;
			font-size: 2.5rem;
			font-weight: 700;
			margin: 0;
			animation: countUp 0.6s ease-out;
			position: relative;
			z-index: 1;
		}

		.stat-card .card-footer {
			padding: 15px 20px;
			background: #f8f9fa;
			border-top: none;
		}

		.stat-card .card-footer span {
			color: #495057;
			font-weight: 600;
			font-size: 0.95rem;
			letter-spacing: 0.3px;
		}

		.section-title {
			font-size: 1.75rem;
			font-weight: 700;
			color: #2d3748;
			margin-bottom: 25px;
			padding-bottom: 12px;
			border-bottom: 3px solid #667eea;
			display: inline-block;
			animation: fadeInUp 0.5s ease-out;
		}

		.nav-tabs-modern {
			border: none;
			background: white;
			border-radius: 12px;
			padding: 8px;
			box-shadow: 0 2px 8px rgba(0,0,0,0.08);
			margin-bottom: 30px;
		}

		.nav-tabs-modern > li {
			flex: 1;
		}

		.nav-tabs-modern > li > a {
			border: none;
			color: #64748b;
			font-weight: 600;
			padding: 12px 24px;
			border-radius: 8px;
			transition: all 0.3s ease;
			text-align: center;
		}

		.nav-tabs-modern > li > a:hover {
			background: #f1f5f9;
			color: #667eea;
		}

		.nav-tabs-modern > li.active > a {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: white !important;
			box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
		}

		.print-btn {
			background: white;
			color: #667eea;
			border: 2px solid #667eea;
			padding: 10px 24px;
			border-radius: 8px;
			font-weight: 600;
			transition: all 0.3s ease;
			cursor: pointer;
			display: inline-block;
		}

		.print-btn:hover {
			background: #667eea;
			color: white;
			transform: translateY(-2px);
			box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
		}

		.form-card {
			background: white;
			border-radius: 12px;
			padding: 20px;
			box-shadow: 0 2px 8px rgba(0,0,0,0.08);
			margin-bottom: 25px;
			animation: fadeInUp 0.6s ease-out;
		}

		.form-card .form-control,
		.form-card select {
			border-radius: 8px;
			border: 2px solid #e2e8f0;
			transition: all 0.3s ease;
			padding: 10px 16px;
		}

		.form-card .form-control:focus,
		.form-card select:focus {
			border-color: #667eea;
			box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
		}

		.btn-generate {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			border: none;
			color: white;
			padding: 10px 24px;
			border-radius: 8px;
			font-weight: 600;
			transition: all 0.3s ease;
		}

		.btn-generate:hover {
			transform: translateY(-2px);
			box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
		}

		.chart-container {
			background: white;
			border-radius: 12px;
			padding: 25px;
			box-shadow: 0 2px 8px rgba(0,0,0,0.08);
			animation: fadeInUp 0.6s ease-out;
		}

		/* Stagger animation delays */
		.stat-card:nth-child(1) { animation-delay: 0.1s; }
		.stat-card:nth-child(2) { animation-delay: 0.2s; }
		.stat-card:nth-child(3) { animation-delay: 0.3s; }
		.stat-card:nth-child(4) { animation-delay: 0.4s; }
		.stat-card:nth-child(5) { animation-delay: 0.5s; }
		.stat-card:nth-child(6) { animation-delay: 0.6s; }

		/* Responsive adjustments */
		@media (max-width: 768px) {
			.stat-card .card-body h2 {
				font-size: 2rem;
			}

			.section-title {
				font-size: 1.5rem;
			}

			.nav-tabs-modern > li > a {
				padding: 10px 16px;
				font-size: 0.9rem;
			}
		}

		@media print {
			.default-dashboard {
				page-break-after: always;
			}

			.chart-dashboard {
				page-break-after: always;
			}

			.panel, .stat-card {
				page-break-inside: avoid;
			}

			.stat-card {
				box-shadow: none;
				border: 1px solid #ddd;
			}
		}
	</style>
@endsection

@section('content')
	<div class="row mb-3">
		<div class="col-sm-12 text-right">
			<a onclick="window.print()" class="print-btn hidden-print" target="_new">
				<i class="fa fa-print"></i> Cetak
			</a>
		</div>
	</div>

	<ul class="nav nav-tabs nav-tabs-modern nav-justified hidden-print d-flex">
		<li id="li_default" class="active">
			<a href="{{ asset('dashboard/hq') }}">
				<i class="fa fa-dashboard"></i> Dashboard Ringkasan
			</a>
		</li>
		<li id="li_chart">
			<a href="{{ asset('dashboard/hq?view=chart') }}">
				<i class="fa fa-bar-chart"></i> Dashboard Carta
			</a>
		</li>
	</ul>

	<div class="row">
		<div class="col-sm-12 col-lg-6">
			<h3 class="section-title"><i class="fa fa-users"></i> Pengguna</h3>
			<div class="default-dashboard">
				<div class="row">
					<div class="col-sm-6 col-md-6">
						<div class="stat-card text-center">
							<div class="card-body">
								<h2>{{ number_format(App\User::active()->count(), 0) }}</h2>
							</div>
							<div class="card-footer">
								<span><i class="fa fa-check-circle"></i> Aktif</span>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-6">
						<div class="stat-card text-center">
							<div class="card-body">
								<h2>{{ number_format(App\User::notActive()->count(), 0) }}</h2>
							</div>
							<div class="card-footer">
								<span><i class="fa fa-times-circle"></i> Tidak Aktif</span>
							</div>
						</div>
					</div>
					<div class="col-sm-12">
						<div class="stat-card text-center">
							<div class="card-body">
								<h2>{{ number_format(App\User::count(), 0) }}</h2>
							</div>
							<div class="card-footer">
								<span><i class="fa fa-calculator"></i> Jumlah</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="chart-dashboard">
				<div class="chart-container">
					<div id="chart_users" style="width: 100%; height: 350px;"></div>
				</div>
			</div>
		</div>

		<div class="col-sm-12 col-lg-6">
			<h3 class="section-title"><i class="fa fa-building"></i> Syarikat</h3>
			<div class="default-dashboard">
				<div class="row">
					<div class="col-sm-6 col-md-6">
						<div class="stat-card text-center">
							<div class="card-body">
								<h2>{{ number_format(App\Vendor::activeSubscriptionCount(), 0) }}</h2>
							</div>
							<div class="card-footer">
								<span><i class="fa fa-check-circle"></i> Aktif</span>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-6">
						<div class="stat-card text-center">
							<div class="card-body">
								<h2>{{ number_format(App\Vendor::nonActiveSubscriptionCount(), 0) }}</h2>
							</div>
							<div class="card-footer">
								<span><i class="fa fa-times-circle"></i> Tidak Aktif</span>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-6">
						<div class="stat-card text-center">
							<div class="card-body">
								<h2>{{ number_format(App\Vendor::pendingRegistrationCount(), 0) }}</h2>
							</div>
							<div class="card-footer">
								<span><i class="fa fa-clock-o"></i> Belum Daftar</span>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-6">
						<div class="stat-card text-center">
							<div class="card-body">
								<h2>{{ number_format(App\Vendor::count(), 0) }}</h2>
							</div>
							<div class="card-footer">
								<span><i class="fa fa-calculator"></i> Jumlah</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="chart-dashboard">
				<div class="chart-container">
					<div id="chart_vendors" style="width: 100%; height: 350px;"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="row mt-4">
		<div class="col-sm-12">
			<h3 class="section-title"><i class="fa fa-file-text"></i> Tender & Sebutharga</h3>
			<div class="default-dashboard">
				<div class="form-card hidden-print">
					<form id="tender_summary" class="form-inline">
						<div class="form-group">
							<label class="mr-2"><strong>Tahun :</strong></label>
							<input class="form-control mr-2" id="year_summary" type="text" name="year_summary" placeholder="Masukkan tahun">
							<button type="submit" class="btn-generate">
								<i class="fa fa-refresh"></i> Jana
							</button>
						</div>
					</form>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-4">
						<div class="stat-card text-center">
							<div class="card-body">
								<div id="tenderCount">
									<h2></h2>
								</div>
							</div>
							<div class="card-footer">
								<span><i class="fa fa-folder-open"></i> Tender</span>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-4">
						<div class="stat-card text-center">
							<div class="card-body">
								<div id="quotationCount">
									<h2></h2>
								</div>
							</div>
							<div class="card-footer">
								<span><i class="fa fa-file-text-o"></i> Sebutharga</span>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-4">
						<div class="stat-card text-center">
							<div class="card-body">
								<div id="tenderTotalCount">
									<h2></h2>
								</div>
							</div>
							<div class="card-footer">
								<span><i class="fa fa-calculator"></i> Jumlah</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="chart-dashboard">
				<div class="form-card">
					<form id="tender_form" class="form-inline">
						<div class="form-group mr-3">
							<label class="mr-2"><strong>Lihat :</strong></label>
							<select class="form-control" name="tender_view_type" id="tender_view_type">
								<option value="tender_yearly" selected>Tahunan</option>
								<option value="tender_monthly">Bulanan</option>
								<option value="tender_weekly">Mingguan</option>
							</select>
						</div>
						<div class="form-group mr-3">
							<div id="tender_yearly">
								<div class="input-group">
									<div class="input-group-addon">
										<span class="input-group-text">Tahun</span>
									</div>
									<input class="form-control" id="year_start" type="text" name="year_start">
								</div>
							</div>
							<div id="tender_weekly" class="hide">
								<div class="input-group">
									<div class="input-group-addon">
										<span class="input-group-text">Suku</span>
									</div>
									<input class="form-control x-uppercase" id="quarter_start" type="number" name="quarter_start"
										min="1" max="4" value="1">
									<div class="input-group-addon">Tahun</div>
									<input class="form-control" id="year_quarter" type="text" name="year_quarter">
								</div>
							</div>
							<div id="tender_monthly" class="hide">
								<div class="input-group">
									<div class="input-group-addon">
										<span class="input-group-text">Tarikh</span>
									</div>
									<input class="form-control x-uppercase" id="monthly_start" type="text" name="monthly_start">
								</div>
							</div>
						</div>
						<button type="submit" class="btn-generate">
							<i class="fa fa-refresh"></i> Jana
						</button>
					</form>
				</div>
				<div class="chart-container">
					<div id="chart_tenders" style="width: 100%; height: 350px;"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="row mt-4">
		<div class="col-sm-12">
			<h3 class="section-title"><i class="fa fa-exchange"></i> Transaksi</h3>
			<div class="default-dashboard">
				<div class="form-card hidden-print">
					<form id="transaction_summary" class="form-inline">
						<div class="form-group">
							<label class="mr-2"><strong>Tahun :</strong></label>
							<input class="form-control mr-2" id="year_summary" type="text" name="year_summary" placeholder="Masukkan tahun">
							<button type="submit" class="btn-generate">
								<i class="fa fa-refresh"></i> Jana
							</button>
						</div>
					</form>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-6 col-lg-4">
						<div class="stat-card text-center">
							<div class="card-body">
								<div id="subscriptionCount">
									<h2></h2>
								</div>
							</div>
							<div class="card-footer">
								<span><i class="fa fa-tag"></i> # Langganan</span>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-6 col-lg-4">
						<div class="stat-card text-center">
							<div class="card-body">
								<div id="purchaseCount">
									<h2></h2>
								</div>
							</div>
							<div class="card-footer">
								<span><i class="fa fa-shopping-cart"></i> # Pembelian Dokumen</span>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-6 col-lg-4">
						<div class="stat-card text-center">
							<div class="card-body">
								<div id="transactionTotal">
									<h2></h2>
								</div>
							</div>
							<div class="card-footer">
								<span><i class="fa fa-calculator"></i> Jumlah Transaksi</span>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-6 col-lg-4">
						<div class="stat-card text-center">
							<div class="card-body">
								<div id="subscriptionValueSum">
									<h2></h2>
								</div>
							</div>
							<div class="card-footer">
								<span><i class="fa fa-money"></i> Nilai Langganan</span>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-6 col-lg-4">
						<div class="stat-card text-center">
							<div class="card-body">
								<div id="purchaseValueSum">
									<h2></h2>
								</div>
							</div>
							<div class="card-footer">
								<span><i class="fa fa-credit-card"></i> Nilai Pembelian</span>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-6 col-lg-4">
						<div class="stat-card text-center">
							<div class="card-body">
								<div id="transactionValueTotal">
									<h2></h2>
								</div>
							</div>
							<div class="card-footer">
								<span><i class="fa fa-calculator"></i> Jumlah Nilai</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="chart-dashboard">
				<div class="form-card">
					<form id="transaction_form" class="form-inline">
						<div class="form-group mr-3">
							<label class="mr-2"><strong>Lihat :</strong></label>
							<select class="form-control" name="transaction_view_type" id="transaction_view_type">
								<option value="transaction_yearly" selected>Tahunan</option>
								<option value="transaction_monthly">Bulanan</option>
								<option value="transaction_weekly">Mingguan</option>
							</select>
						</div>
						<div class="form-group mr-3">
							<div id="transaction_yearly">
								<div class="input-group">
									<div class="input-group-addon">
										<span class="input-group-text">Tahun</span>
									</div>
									<input class="form-control" id="year_start" type="text" name="year_start">
								</div>
							</div>
							<div id="transaction_weekly" class="hide">
								<div class="input-group">
									<div class="input-group-addon">
										<span class="input-group-text">Suku</span>
									</div>
									<input class="form-control x-uppercase" id="quarter_start" type="number" name="quarter_start"
										min="1" max="4" value="1">
									<div class="input-group-addon">Tahun</div>
									<input class="form-control" id="year_quarter" type="text" name="year_quarter">
								</div>
							</div>
							<div id="transaction_monthly" class="hide">
								<div class="input-group">
									<div class="input-group-addon">
										<span class="input-group-text">Tarikh</span>
									</div>
									<input class="form-control x-uppercase" id="monthly_start" type="text" name="monthly_start">
								</div>
							</div>
						</div>
						<button type="submit" class="btn-generate">
							<i class="fa fa-refresh"></i> Jana
						</button>
					</form>
				</div>
				<div class="chart-container mb-4">
					<div id="chart_transactions" style="width: 100%; height: 350px;"></div>
				</div>
				<div class="chart-container">
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
							$('#' + ids[index]).removeClass('hide');
						} else {
							$('#' + ids[index]).addClass('hide');
						}
					});
				});

				$('#transaction_view_type').change(function() {
					var view = $(this).val();
					const ids = ['transaction_yearly', 'transaction_weekly', 'transaction_monthly'];
					ids.forEach(function(item, index) {
						if (ids[index] == view) {
							$('#' + ids[index]).removeClass('hide');
						} else {
							$('#' + ids[index]).addClass('hide');
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
