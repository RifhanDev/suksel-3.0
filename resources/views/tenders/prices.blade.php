@extends('layouts.modern')
@section('styles')
	<link href="{{ asset('css/form.css') }}" rel="stylesheet">
@endsection
@section('content')
	<div class="row">
		<div class="col-lg-9">
			<div class="page-header">
				<div class="page-title">
					<div class="page-pretitle">
						{{ $tender->ref_number }}
					</div>
				</div>
			</div>

			<h3>
				<b>{{ $tender->name }}</b>
			</h3>
			<br>

			<div class="btn-group mb-3" role="group">
				<a href="{{ asset('agencies/' . $tender->tenderer->id) }}" class="btn btn-warning btn-sm">
					<i class="ti ti-building me-1"></i> Senarai Tender oleh {{ $tender->tenderer->name }}
				</a>

				@if (Auth::check())
					@if (Auth::user()->hasRole('Admin'))
						<a href="{{ asset('tenders') }}" class="btn btn-outline-secondary btn-sm">
							<i class="ti ti-arrow-up me-1"></i> Senarai Tender
						</a>
					@endif
					@if ($tender->canUpdate())
						<a href="{{ asset('tenders/' . $tender->id . '/edit') }}" class="btn btn-primary btn-sm">
							<i class="ti ti-edit me-1"></i> Kemaskini
						</a>
					@endif
				@endif
			</div>

			@if ($tender->canShowTabs())
				<div class="card mb-3">
					<div class="card-header">
						<ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
							<li class="nav-item">
								<a href="{{ asset('tenders/' . $tender->id) }}" class="nav-link">
									<i class="ti ti-info-circle me-2"></i>Maklumat Tender / Sebut Harga
								</a>
							</li>
							<li class="nav-item">
								<a href="{{ asset('tenders/' . $tender->id . '/vendors') }}" class="nav-link active">
									<i class="ti ti-building me-2"></i>Maklumat Syarikat
								</a>
							</li>
							@if (Auth::check() &&
									$tender->canException() &&
									auth()->user()->ability(['Admin', 'Agency Admin', 'Agency User'], ['ExceptionTender:list']))
								<li class="nav-item">
									<a href="{{ asset('tenders/' . $tender->id . '/exceptions') }}" class="nav-link">
										<i class="ti ti-alert-circle me-2"></i>Maklumat Kebenaran Khas
										<span class="badge bg-danger ms-2">{{ $tender->exceptions()->where('status', 0)->count() }}</span>
									</a>
								</li>
							@endif
						</ul>
					</div>
				</div>
			@endif

			@if ($tender->publish_winner)
				<div class="card mb-3">
					<div class="card-body">
						<div class="nav nav-pills" role="tablist">
							<a href="{{ asset('tenders/' . $tender->id . '/vendors') }}"
								class="nav-link @if (!Request::get('show')) active @endif">
								<i class="ti ti-chart-bar me-2"></i>Carta Tender
							</a>
							<a href="{{ route('tenders.vendors', [$tender->id, 'show' => 'winner']) }}"
								class="nav-link @if (Request::get('show') == 'winner') active @endif">
								<i class="ti ti-trophy me-2"></i>Penender Berjaya
							</a>
						</div>
					</div>
				</div>
			@endif

			@if (Request::get('show') == 'winner')
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">
							<i class="ti ti-trophy me-2"></i>Penender Berjaya
						</h3>
					</div>
					<div class="card-body">
						@if (isset($winner) && $tender->publish_winner)
							<div class="table-responsive">
								<table class="table table-vcenter">
									<tbody>
										<tr>
											<th class="w-25">
												<i class="ti ti-building me-1"></i>Nama Syarikat
											</th>
											<td>
												<div class="font-weight-medium">{{ $winner->vendor->name }}</div>
											</td>
										</tr>
										<tr>
											<th>
												<i class="ti ti-clock me-1"></i>Tempoh Siap
											</th>
											<td>
												@if ($winner->project_timeline)
													{{ $winner->project_timeline }}
												@else
													<span class="text-muted">
														<i class="ti ti-x me-1"></i>Tidak dinyatakan
													</span>
												@endif
											</td>
										</tr>
										<tr>
											<th>
												<i class="ti ti-currency-ringgit me-1"></i>Harga Tawaran
											</th>
											<td>
												@if ($winner->price)
													<div class="h3 text-success mb-0">RM {{ number_format($winner->price, 2) }}</div>
												@else
													<span class="text-muted">
														<i class="ti ti-x me-1"></i>Tidak dinyatakan
													</span>
												@endif
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						@else
							<div class="empty">
								<div class="empty-icon">
									<i class="ti ti-trophy"></i>
								</div>
								<p class="empty-title">Penender Berjaya Belum Diumumkan</p>
								<p class="empty-subtitle text-muted">Keputusan tender akan diumumkan kemudian.</p>
							</div>
						@endif
					</div>
				</div>
			@else
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">
							<i class="ti ti-chart-bar me-2"></i>Carta Tender
						</h3>
					</div>
					<div class="card-body">
						@if (count($prices) > 0)
							<div class="table-responsive">
								<table class="table table-vcenter table-mobile-md">
									<thead>
										<tr>
											<th>
												<i class="ti ti-tag me-1"></i>Label Syarikat
											</th>
											<th class="w-25">
												<i class="ti ti-currency-ringgit me-1"></i>Harga
											</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($prices as $purchase)
											<tr>
												<td>
													<div class="font-weight-medium">{{ $purchase->label }}</div>
												</td>
												<td>
													<div class="h4 text-primary mb-0">RM {{ number_format($purchase->price, 2) }}</div>
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						@else
							<div class="empty">
								<div class="empty-icon">
									<i class="ti ti-building"></i>
								</div>
								<p class="empty-title">Tiada Syarikat</p>
								<p class="empty-subtitle text-muted">Tiada syarikat yang menyertai tender ini.</p>
							</div>
						@endif
					</div>
				</div>
			@endif
		</div>

		<div class="col-lg-3">
			@include('layouts._register')
			@include('layouts._news')
		</div>
	</div>

@endsection
@section('scripts')
	<script src="{{ asset('js/tender-vue.js') }}"></script>
@endsection
