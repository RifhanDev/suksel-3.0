@extends('layouts.v3.master')

@section('styles')
	<link href="{{ asset('css/components/tender-show.css') }}" rel="stylesheet">
@endsection

@section('content')
	<div class="row">
		<div class="col-12">
			@include('tenders._menu')

			{{-- Notifications & Alerts --}}
			@include('tenders._notification')

			{{-- Header & Tabs Card --}}
			<div class="tender-header-card d-print-none mb-4">
				{{-- Page Header --}}
				<div class="tender-page-header">
					<div class="tender-ref-label">
						<span class="tender-type-label">{{ App\Tender::$types[$tender->type] }}</span>
						<span class="tender-ref-sep">·</span>
						<span class="tender-ref-no">{{ $tender->ref_number }}</span>
					</div>
					<h2 class="tender-title-main">{{ $tender->name }}</h2>
				</div>

				@if (Auth::user() && $tender->canShowTabs())
					<div class="tender-top-tabs mt-4">
						<ul class="nav nav-tabs" data-bs-toggle="tabs">
							<li class="nav-item">
								<a href="{{ asset('tender/' . $tender->id) }}" class="nav-link">
									<svg xmlns="http://www.w3.org/2000/svg" class="me-1 mb-1" width="20" height="20" viewBox="0 0 24 24">
										<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
											<path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0-18 0m9-3h.01" />
											<path d="M11 12h1v4h1" />
										</g>
									</svg>
									Maklumat Tender
								</a>
							</li>
							<li class="nav-item">
								<a href="{{ asset('tenders/' . $tender->id . '/vendors') }}" class="nav-link active">
									<svg xmlns="http://www.w3.org/2000/svg" class="me-1 mb-1" width="20" height="20" viewBox="0 0 24 24">
										<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
											d="M3 21h18M9 8h1m-1 4h1m-1 4h1m4-8h1m-1 4h1m-1 4h1M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16" />
									</svg>
									Maklumat Syarikat
								</a>
							</li>
							@if (Auth::check() &&
									$tender->canException() &&
									auth()->user()->ability(['Admin', 'Agency Admin', 'Agency User'], ['ExceptionTender:list']))
								<li class="nav-item">
									<a href="{{ asset('tenders/' . $tender->id . '/exceptions') }}" class="nav-link">
										<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="16" height="16" viewBox="0 0 24 24"
											fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
										</svg>
										Maklumat Kebenaran Khas
										<span class="badge bg-danger ms-1">{{ $tender->exceptions()->where('status', 0)->count() }}</span>
									</a>
								</li>
							@endif
						</ul>
					</div>
				@endif
			</div>

			{{-- Admin Sub-Navigation --}}
			@if (Auth::user()->hasRole('Admin'))
				<div class="tender-tab-card mb-4">
					<div class="card-body" style="padding: 0.75rem 1rem;">
						<div class="d-flex align-items-center gap-2">
							<a href="{{ asset('tenders/' . $tender->id . '/eligibles') }}"
								class="tender-menu-btn @if (!isset($purchases)) tender-menu-btn-primary @else tender-menu-btn-ghost @endif">
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<polyline points="20 6 9 17 4 12" />
								</svg>
								Senarai Layak
							</a>
							<a href="{{ asset('tenders/' . $tender->id . '/vendors') }}"
								class="tender-menu-btn @if (isset($purchases)) tender-menu-btn-primary @else tender-menu-btn-ghost @endif">
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<circle cx="9" cy="21" r="1" />
									<circle cx="20" cy="21" r="1" />
									<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
								</svg>
								Pembelian Dokumen
							</a>
						</div>
					</div>
				</div>
			@endif

			{{-- Maklumat Syarikat --}}
			<div class="tender-tab-card mb-4">
				<div class="card-header">
					<h3 class="card-title">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="var(--sg-red, #c41e3a)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
							<circle cx="9" cy="7" r="4" />
							<path d="M23 21v-2a4 4 0 0 0-3-3.87" />
							<path d="M16 3.13a4 4 0 0 1 0 7.75" />
						</svg>
						Maklumat Syarikat
					</h3>
				</div>

				{!! Former::open(url('tenders/' . $tender->id . '/vendors'))->class('form-inline') !!}

				@if (count($purchases) > 0)
					<?php $count = 1; ?>
					<div class="table-responsive">
						<table class="table tender-doc-table table-vcenter mb-0">
							<thead>
								<tr>
									<th style="width: 4%">#</th>
									<th>
										<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="13" height="13" viewBox="0 0 24 24"
											fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
											<polyline points="9 22 9 12 15 12 15 22" />
										</svg>
										Nama Syarikat
									</th>
									@if (!$tender->only_advertise)
										<th class="text-center" style="width: 10%">
											<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="13" height="13" viewBox="0 0 24 24"
												fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
												<circle cx="9" cy="21" r="1" />
												<circle cx="20" cy="21" r="1" />
												<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
											</svg>
											Beli Dokumen
										</th>
									@endif

									@if ($tender->hasBriefing())
										<th class="text-center" style="width: 10%">
											<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="13" height="13" viewBox="0 0 24 24"
												fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
												<rect x="2" y="3" width="20" height="14" rx="2" />
												<line x1="8" y1="21" x2="16" y2="21" />
												<line x1="12" y1="17" x2="12" y2="21" />
											</svg>
											Taklimat
											<input type="checkbox" class="form-check-input checker ms-2" data-target="briefing">
										</th>
									@endif

									@if (count($tender->siteVisits()->get()) > 0)
										<?php $index = 1; ?>
										@foreach ($tender->siteVisits()->orderBy('id', 'asc')->get() as $visit)
											<th class="text-center" style="width: 10%">
												<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="13" height="13" viewBox="0 0 24 24"
													fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
													<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
													<circle cx="12" cy="10" r="3" />
												</svg>
												LT {{ $index }}
												<input type="checkbox" class="form-check-input checker ms-2" data-target="visit-{{ $visit->id }}">
											</th>
											<?php $index++; ?>
										@endforeach
									@endif

									@if ($tender->canShowPrices())
										<th style="width: 10%">
											<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="13" height="13" viewBox="0 0 24 24"
												fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
												<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
												<line x1="7" y1="7" x2="7.01" y2="7" />
											</svg>
											Label
										</th>
										<th style="width: 10%">
											<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="13" height="13" viewBox="0 0 24 24"
												fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
												<line x1="12" y1="1" x2="12" y2="23" />
												<path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
											</svg>
											Harga
										</th>
										<th class="text-center" style="width: 10%">
											<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="13" height="13" viewBox="0 0 24 24"
												fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
												<polygon
													points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
											</svg>
											Berjaya
										</th>
										{{-- Check if there are winner for this tender yet. If yes, this column will appear --}}
										@if ($count_winner > 0)
											<th style="width: 10%">
												<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="13" height="13" viewBox="0 0 24 24"
													fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
													<path d="M14.5 10c-.83 0-1.5-.67-1.5-1.5v-5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5v5c0 .83-.67 1.5-1.5 1.5z" />
													<path d="M20.5 10H19V8.5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z" />
													<path d="M9.5 14c.83 0 1.5.67 1.5 1.5v5c0 .83-.67 1.5-1.5 1.5S8 21.33 8 20.5v-5c0-.83.67-1.5 1.5-1.5z" />
													<path d="M3.5 14H5v1.5c0 .83-.67 1.5-1.5 1.5S2 16.33 2 15.5 2.67 14 3.5 14z" />
													<path
														d="M14 14.5c0-.83.67-1.5 1.5-1.5h5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5h-5c-.83 0-1.5-.67-1.5-1.5z" />
													<path d="M15.5 19H14v1.5c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5-.67-1.5-1.5-1.5z" />
													<path d="M10 9.5C10 8.67 9.33 8 8.5 8h-5C2.67 8 2 8.67 2 9.5S2.67 11 3.5 11h5c.83 0 1.5-.67 1.5-1.5z" />
													<path d="M8.5 5H10V3.5C10 2.67 9.33 2 8.5 2S7 2.67 7 3.5 7.67 5 8.5 5z" />
												</svg>
												Gred / Prestasi
											</th>
										@endif
									@endif

									<th class="text-center" style="width: 8%">
										<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="13" height="13" viewBox="0 0 24 24"
											fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<polyline points="3 6 5 6 21 6" />
											<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
										</svg>
										Padam
										<input type="checkbox" class="form-check-input checker ms-2" data-target="delete">
									</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($purchases as $purchase)
									<tr>
										<td class="text-muted">{{ $count }}</td>
										<td>
											<div class="d-flex align-items-center gap-2">
												<div class="flex-fill">
													<div class="fw-medium" style="color: #1e293b;">
														{{ $purchase->vendor->name }}</div>
													@if ($purchase->ref_number)
														<div class="text-muted small">No. Siri Dokumen:
															{{ $purchase->ref_number }}</div>
													@endif
													@if ($purchase->exception)
														<div class="small d-flex align-items-center gap-1 mt-1" style="color: #d97706;">
															<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
																fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
																stroke-linejoin="round">
																<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
															</svg>
															Kebenaran Khas
														</div>
													@endif
												</div>
												<div>
													<a href="{{ asset('tenders/' . $tender->id . '/vendor/' . $purchase->vendor_id) }}"
														class="tender-menu-btn tender-menu-btn-primary">
														<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
															fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
															<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
															<circle cx="12" cy="12" r="3" />
														</svg>
														Maklumat Syarikat
													</a>
												</div>
											</div>
										</td>

										@if (!$tender->only_advertise)
											<td class="text-center">
												@if ($purchase->participate)
													<span class="badge"
														style="background: #dcfce7; color: #166534; font-size: 0.7rem; padding: 0.35em 0.6em; border-radius: 6px;">
														<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24"
															fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
															stroke-linejoin="round">
															<polyline points="20 6 9 17 4 12" />
														</svg>
													</span>
												@else
													<span class="badge"
														style="background: #fee2e2; color: #991b1b; font-size: 0.7rem; padding: 0.35em 0.6em; border-radius: 6px;">
														<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24"
															fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
															stroke-linejoin="round">
															<line x1="18" y1="6" x2="6" y2="18" />
															<line x1="6" y1="6" x2="18" y2="18" />
														</svg>
													</span>
												@endif
											</td>
										@endif

										@if ($tender->hasBriefing())
											<td class="text-center">
												<input type="checkbox" class="form-check-input briefing" name="briefing[{{ $purchase->id }}]"
													@if ($purchase->briefing) checked @endif>
											</td>
										@endif

										@if (count($tender->siteVisits()->orderBy('id', 'asc')->get()) > 0)
											@foreach ($tender->siteVisits()->get() as $visit)
												<td class="text-center">
													<input type="checkbox" class="form-check-input visit-{{ $visit->id }}"
														name="visits[{{ $visit->id }}][]" value="{{ $purchase->vendor_id }}"
														@if (App\TenderVisitor::hasVisit($visit->id, $purchase->vendor_id)) checked @endif>
												</td>
											@endforeach
										@endif

										@if ($tender->canShowPrices())
											<td>
												<input type="text" name="label[{{ $purchase->id }}]" value="{{ $purchase->label }}"
													class="form-control form-control-sm">
											</td>
											<td>
												<input type="text" name="price[{{ $purchase->id }}]" value="{{ $purchase->price }}"
													class="form-control form-control-sm">
											</td>
											<td class="text-center">
												<div class="form-check d-flex justify-content-center">
													<input type="radio" name="winner" value="{{ $purchase->id }}" class="form-check-input"
														@if ($purchase->winner) checked @endif>
												</div>
												<input type="text" name="project_timeline"
													value="{{ $purchase->winner ? $purchase->project_timeline : '' }}"
													@if (!$purchase->winner) disabled="disabled" @endif placeholder="Tempoh Siap"
													class="form-control form-control-sm mt-2">
											</td>
										@endif

										{{-- Check if there are winner for this tender yet. If yes, this column will appear --}}
										@if ($count_winner > 0)
											<td class="text-center">
												{{-- Check if the Petender Performance that has been created match with the vendor listed here. If yes, the button will appear. --}}
												@if ($purchase->winner == 1)
													<a href="{{ route('index.TenderVendor', $tender) }}" class="tender-menu-btn tender-menu-btn-primary">
														<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
															fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
															<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
															<circle cx="12" cy="12" r="3" />
														</svg>
														Papar
													</a>
												@endif
											</td>
										@endif

										<td class="text-center">
											@if ($purchase->participate == 0)
												<input type="checkbox" class="form-check-input delete" name="delete[]" value="{{ $purchase->id }}">
											@else
												<span class="badge"
													style="background: #fee2e2; color: #991b1b; font-size: 0.7rem; padding: 0.35em 0.6em; border-radius: 6px;">
													<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24"
														fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
														<circle cx="12" cy="12" r="10" />
														<line x1="4.93" y1="4.93" x2="19.07" y2="19.07" />
													</svg>
												</span>
											@endif
										</td>
									</tr>
									<?php $count++; ?>
								@endforeach
							</tbody>
						</table>
					</div>
				@else
					<div class="d-flex flex-column align-items-center justify-content-center py-5 text-center">
						<div class="mb-3"
							style="width: 52px; height: 52px; background: #f8fafc; border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0;">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
								stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
								<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
								<circle cx="9" cy="7" r="4" />
								<path d="M23 21v-2a4 4 0 0 0-3-3.87" />
								<path d="M16 3.13a4 4 0 0 1 0 7.75" />
							</svg>
						</div>
						<p class="fw-semibold mb-1" style="color: #334155;">Tiada Syarikat</p>
						<p class="small mb-0" style="color: #94a3b8;">Tiada syarikat yang menyertai tender ini.</p>
					</div>
				@endif

				{{-- Add Vendor Form --}}
				<div class="border-top px-3 py-3" style="border-color: #f1f5f9 !important;">
					<div class="row g-3">
						<div class="col-lg-8">
							<label class="form-label fw-semibold" style="font-size: 0.82rem; color: #334155;">Tambah Syarikat</label>
							<input type="text" id="vendor_ids" name="vendor_ids" placeholder="Cari nama syarikat...">
							<small class="text-muted" style="font-size: 0.78rem;">Cari nama syarikat yang ingin
								ditambah dan tekan "Simpan Maklumat Syarikat"</small>
						</div>
						<div class="col-lg-4">
							<div class="d-flex align-items-center h-100">
								<button type="submit" class="btn w-100 confirm"
									style="background: var(--sg-red, #c41e3a); border-color: var(--sg-red, #c41e3a); color: white; font-size: 0.85rem; font-weight: 600; border-radius: 8px; padding: 0.55rem 1rem;">
									<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="15" height="15" viewBox="0 0 24 24"
										fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
										<polyline points="17 21 17 13 7 13 7 21" />
										<polyline points="7 3 7 8 15 8" />
									</svg>
									Simpan Maklumat Syarikat
								</button>
							</div>
						</div>
					</div>
				</div>

				{!! Former::close() !!}
			</div>

			{{-- Muat Naik Maklumat Syarikat --}}
			<div class="tender-tab-card mb-4">
				<div class="card-header">
					<h3 class="card-title">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="var(--sg-red, #c41e3a)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<polyline points="16 16 12 12 8 16" />
							<line x1="12" y1="12" x2="12" y2="21" />
							<path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3" />
						</svg>
						Muat Naik Maklumat Syarikat
					</h3>
				</div>
				<div class="card-body">
					{!! Former::open_for_files(url('tenders/' . $tender->id . '/vendors/bulkUpdate'))->class('form-inline') !!}
					<div class="row g-3">
						<div class="col-lg-8">
							<label class="form-label fw-semibold" style="font-size: 0.82rem; color: #334155;">Pilih Fail CSV</label>
							<input type="file" name="file" class="form-control" accept=".csv">
							<small class="text-muted" style="font-size: 0.78rem;">Pilih fail CSV yang mengandungi
								maklumat syarikat</small>
						</div>
						<div class="col-lg-4">
							<div class="d-flex align-items-center h-100">
								<button type="submit" class="btn w-100 confirm"
									style="background: #f59e0b; border-color: #f59e0b; color: white; font-size: 0.85rem; font-weight: 600; border-radius: 8px; padding: 0.55rem 1rem;">
									<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="15" height="15" viewBox="0 0 24 24"
										fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<polyline points="16 16 12 12 8 16" />
										<line x1="12" y1="12" x2="12" y2="21" />
										<path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3" />
									</svg>
									Muat Naik
								</button>
							</div>
						</div>
					</div>
					<div class="mt-3">
						{!! link_to_route(
						    'tenders.template',
						    '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:0.35rem;vertical-align:-2px"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>Templat Dokumen (CSV)',
						    $tender->id,
						    ['class' => 'tender-menu-btn', 'style' => 'background:#1d6f42;color:#fff;border-color:#1d6f42;'],
						) !!}
					</div>
					{!! Former::close() !!}
				</div>
			</div>

			{{-- Kebenaran Khas --}}
			@if (Auth::user()->can('Tender:exception'))
				<div class="tender-tab-card mb-4">
					<div class="card-header">
						<h3 class="card-title">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
								stroke="var(--sg-red, #c41e3a)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
							</svg>
							Kebenaran Khas
						</h3>
					</div>
					<div class="card-body">
						{!! Former::open(url('tenders/' . $tender->id . '/exception'))->class('form-inline') !!}
						<div class="row g-3">
							<div class="col-lg-8">
								<label class="form-label fw-semibold" style="font-size: 0.82rem; color: #334155;">Tambah Kebenaran Khas</label>
								<input type="text" id="exception_id" name="exception_id" placeholder="Cari nama syarikat...">
								<small class="text-muted" style="font-size: 0.78rem;">Cari nama syarikat yang ingin
									diberikan Kebenaran Khas dan tekan "Simpan"</small>
							</div>
							<div class="col-lg-4">
								<div class="d-flex align-items-center h-100">
									<button type="submit" class="btn w-100 confirm"
										style="background: #f59e0b; border-color: #f59e0b; color: white; font-size: 0.85rem; font-weight: 600; border-radius: 8px; padding: 0.55rem 1rem;">
										<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="15" height="15" viewBox="0 0 24 24"
											fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
											<polyline points="17 21 17 13 7 13 7 21" />
											<polyline points="7 3 7 8 15 8" />
										</svg>
										Simpan
									</button>
								</div>
							</div>
						</div>
						{!! Former::close() !!}
					</div>
				</div>
			@endif

		</div>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/tender-vue.js') }}"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("input[name=winner]").change(function() {
				if ($(this).is(':checked')) {
					$('input[name=project_timeline]').each(function(elem) {
						$(elem).attr('disabled', 'disabled');
					});
					$(this).parents('td').find('input[name=project_timeline]').attr('disabled', false);
				}
			});
			$("input.checker").change(function() {
				target = $(this).data('target');
				var checked = this.checked;
				$('input.' + target).each(function() {
					$(this).prop('checked', checked);
				});
			});
			$('input.checker').each(function() {
				target = $(this).data('target');
				countInput = $('input.' + target).length;
				countChecked = $('input.' + target + ':checked').length;
				if (countInput != 0 && countInput == countChecked) $(this).prop('checked', true);
			});
			$("#vendor_ids").selectize({
				valueField: 'id',
				labelField: 'name',
				searchField: 'name',
				create: false,
				render: {
					option: function(item, escape) {
						return '<div>' +
							'<strong>' + escape(item.registration) + '</strong> ' + escape(item.name) +
							'<br><small>Alamat Emel: <strong>' + escape(item.email) +
							'</strong> &bullet; Tarikh Tamat Langganan: <strong>' +
							moment(item.expiry_date, 'YYYY-MM-DD').format('DD/MM/YY') +
							'</strong></small>' +
							'</div>';
					}
				},
				load: function(query, callback) {
					if (!query.length) return callback();
					$.ajax({
						url: '/vendors/select?q=' + query,
						type: 'GET',
						success: function(res) {
							callback(res);
						},
						error: function() {
							callback();
						}
					})
				}
			});
			$("#exception_id").selectize({
				valueField: 'id',
				labelField: 'name',
				searchField: 'name',
				maxItems: 1,
				create: false,
				render: {
					option: function(item, escape) {
						return '<div>' +
							'<strong>' + escape(item.registration) + '</strong> ' + escape(item.name) +
							'<br><small>Alamat Emel: <strong>' + escape(item.email) +
							'</strong> &bullet; Tarikh Tamat Langganan: <strong>' +
							moment(item.expiry_date, 'YYYY-MM-DD').format('DD/MM/YY') +
							'</strong></small>' +
							'</div>';
					}
				},
				load: function(query, callback) {
					if (!query.length) return callback();
					$.ajax({
						url: '/vendors/select?q=' + query,
						type: 'GET',
						success: function(res) {
							callback(res);
						},
						error: function() {
							callback();
						}
					})
				}
			});
		});
	</script>
@endsection
