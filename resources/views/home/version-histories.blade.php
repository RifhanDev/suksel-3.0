@extends('layouts.v3.master')

@section('content')
	<div class="row">
		<div class="col-lg-9">
			<!-- Page Header -->
			<div class="page-header-modern">
				<div class="page-pretitle">
					<i class="ti ti-info-circle me-2"></i>Sistem Tender Online Selangor
				</div>
				<h2>
					<i class="ti ti-history me-2"></i>Sejarah Perubahan Sistem
				</h2>
			</div>

			<!-- Main Card -->
			<div class="card modern-card">
				<div class="card-header" style="background: white; border-bottom: 1px solid #e9ecef;">
					<h3 class="card-title mb-0">
						<i class="ti ti-versions me-2"></i>Rekod Versi
					</h3>
				</div>
				<div class="card-body p-0">
					@if ($versionHistories->isEmpty())
						<div class="empty p-5">
							<div class="empty-icon">
								<i class="ti ti-history"></i>
							</div>
							<p class="empty-title">Tiada rekod versi</p>
							<p class="empty-subtitle text-muted">
								Tiada sejarah perubahan sistem pada masa ini.
							</p>
						</div>
					@else
						<div class="table-responsive">
							<table class="table modern-table table-hover mb-0">
								<thead>
									<tr>
										<th class="text-nowrap" style="width: 120px;">
											<i class="ti ti-tag me-1"></i>Versi
										</th>
										<th class="text-nowrap" style="width: 140px;">
											<i class="ti ti-calendar me-1"></i>Tarikh
										</th>
										<th><i class="ti ti-notes me-1"></i>Nota</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($versionHistories as $item)
										<tr>
											<td><span class="badge bg-primary">{{ $item->version }}</span></td>
											<td>{{ $item->formatted_date }}</td>
											<td>
												@if (count($item->notes_lines) > 0)
													<ol class="mb-0 ps-3">
														@foreach ($item->notes_lines as $line)
															<li>{!! nl2br(e($line)) !!}</li>
														@endforeach
													</ol>
												@else
													{{ $item->notes ?: '—' }}
												@endif
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					@endif
				</div>
			</div>
		</div>

		<!-- Sidebar -->
		<div class="col-lg-3">
			@include('layouts._register')
			@include('layouts._news')
		</div>
	</div>
@endsection
