@extends('layouts.v3.master')

@section('styles')
	{{-- Old CSS --}}
	{{-- <link href="{{ asset('css/form.css') }}" rel="stylesheet"> --}}
	{{-- <link href="{{ asset('css/tender.form.css') }}" rel="stylesheet"> --}}
	<link href="{{ asset('css/components/tender-show.css') }}" rel="stylesheet">
	<link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
@endsection

@section('content')
	@php
		$vendorCanEdit = Auth::check()
			&& Auth::user()->vendor_id
			&& $tender->hasParticipate(Auth::user()->vendor_id);
		$dokumenMode = $vendorCanEdit ? 'vendor' : 'admin';
		$dokumenList = $tenderDokumen->items(
			$dokumenMode,
			$vendorCanEdit ? (int) Auth::user()->vendor_id : null
		);
	@endphp

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

				@if ($tender->canShowTabs())
					<div class="tender-top-tabs mt-4">
						<ul class="nav nav-tabs" data-bs-toggle="tabs">
							<li class="nav-item">
								<a href="{{ asset('tenders/' . $tender->id) }}" class="nav-link active">
									<svg xmlns="http://www.w3.org/2000/svg" class="me-1 mb-1" width="20" height="20" viewBox="0 0 24 24">
										<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
											<path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0-18 0m9-3h.01" />
											<path d="M11 12h1v4h1" />
										</g>
									</svg>
									Maklumat {{ App\Tender::$types[$tender->type] }}
								</a>
							</li>
							<li class="nav-item">
								<a href="{{ asset('tenders/' . $tender->id . '/vendors') }}" class="nav-link">
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
										<svg xmlns="http://www.w3.org/2000/svg" class="me-1 mb-1" width="20" height="20" viewBox="0 0 24 24">
											<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
												<path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0-18 0m9-3h.01" />
												<path d="M11 12h1v4h1" />
											</g>
										</svg>
										Maklumat Kebenaran Khas
										<span class="badge bg-danger ms-2">{{ $tender->exceptions()->where('status', 0)->count() }}</span>
									</a>
								</li>
							@endif
						</ul>
					</div>
				@endif
			</div>

			<div class="row stacked-form">
				{{-- Side Navigation --}}
				<div class="col-lg-3 d-print-none">
					<div class="tender-side-nav-card">
						<div class="nav flex-column nav-pills tender-side-nav" role="tablist">
							<a href="#tf-main" aria-controls="home" role="tab" data-bs-toggle="tab"
								class="nav-link @if (!Session::get('ErrorRequest') and !isset($active_prestasi_tab)) active @endif">
								<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18" height="18" viewBox="0 0 24 24">
									<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
										<path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0-18 0m9-3h.01" />
										<path d="M11 12h1v4h1" />
									</g>
								</svg>
								Maklumat {{ App\Tender::$types[$tender->type] }}
							</a>
							<a href="#tf-syarat" aria-controls="home" role="tab" data-bs-toggle="tab" class="nav-link">
								<svg xmlns="http://www.w3.org/2000/svg" class="me-1 mb-1" width="18" height="18" viewBox="0 0 24 24">
									<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
										<path d="M14 3v4a1 1 0 0 0 1 1h4" />
										<path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2m-8-4h6m-6-4h6" />
									</g>
								</svg>
								Syarat {{ App\Tender::$types[$tender->type] }}
							</a>
							@if (count($tender->siteVisits) > 0)
								<a href="#tf-lawatan" aria-controls="messages" role="tab" data-bs-toggle="tab" class="nav-link">
									<svg xmlns="http://www.w3.org/2000/svg" class="me-1 mb-1" width="18" height="18" viewBox="0 0 24 24">
										<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
											<path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0-6 0" />
											<path d="M17.657 16.657L13.414 20.9a2 2 0 0 1-2.827 0l-4.244-4.243a8 8 0 1 1 11.314 0" />
										</g>
									</svg>
									Lawatan Tapak
								</a>
							@endif
							@if (count($tender->mof_codes) > 0 || count($tender->cidb_grades) > 0 || count($tender->cidb_codes) > 0)
								<a href="#tf-kod" aria-controls="settings" role="tab" data-bs-toggle="tab"
									class="nav-link {{ Auth::check() && Auth::user()->vendor && $tender->codeErrors(Auth::user()->vendor_id) ? 'text-danger' : '' }}">
									<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18" height="18" viewBox="0 0 24 24">
										<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
											d="m7 8l-4 4l4 4m10-8l4 4l-4 4M14 4l-4 16" />
									</svg>
									Kod-Kod Bidang
								</a>
							@endif
							@if (count($tender->table_files) > 0)
								<a href="#tf-doc1" aria-controls="settings" role="tab" data-bs-toggle="tab" class="nav-link">
									<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18" height="18" viewBox="0 0 24 24">
										<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
											d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2" />
									</svg>
									Dokumen Meja Terkawal
									<span class="badge bg-primary ms-2">{{ $tender->files()->where('public', 1)->count() }}</span>
								</a>
							@endif
							@if ($tender->canShowDokumenSenaraiTab(Auth::user()->vendor_id))
								<a href="#tf-dokumen-tawaran" aria-controls="settings" role="tab" data-bs-toggle="tab" class="nav-link">
									<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18" height="18" viewBox="0 0 24 24">
										<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
											d="M9 11l3 3l8-8M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
									</svg>
									{{ $tender->dokumenSenaraiTabLabel() }}
									<span class="badge bg-primary ms-2">{{ count($dokumenList) }}</span>
								</a>
							@endif
							@if (!$tender->showDokumenSenaraiTab() && Auth::check() && $tender->canShowFiles(Auth::user()->vendor_id))
								<a href="#tf-doc2" aria-controls="settings" role="tab" data-bs-toggle="tab" class="nav-link">
									<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18" height="18" viewBox="0 0 24 24">
										<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
											<path d="M15 3v4a1 1 0 0 0 1 1h4" />
											<path d="M18 17h-7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4l5 5v7a2 2 0 0 1-2 2" />
											<path d="M16 17v2a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h2" />
										</g>
									</svg>
									Dokumen {{ App\Tender::$types[$tender->type] }}
									<span class="badge bg-primary ms-2">{{ $tender->files()->where('public', 0)->count() }}</span>
								</a>
							@endif
							@if (Auth::check() && $tender->canUpdate() && $tender->invitation)
								<a href="#tf-invites" aria-controls="settings" role="tab" data-bs-toggle="tab" class="nav-link">
									<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18" height="18" viewBox="0 0 24 24">
										<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
											<path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
											<path d="m3 7l9 6l9-6" />
										</g>
									</svg>
									Senarai Jemputan
								</a>
							@endif
							@if (Auth::check() && $tender->canUpdate())
								<a href="#tf-history" aria-controls="settings" role="tab" data-bs-toggle="tab" class="nav-link">
									<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18" height="18" viewBox="0 0 24 24">
										<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
											<path d="M12 8v4l2 2" />
											<path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5" />
										</g>
									</svg>
									Sejarah Pengubahan
								</a>
							@endif
							<a href="#tf-news" aria-controls="home" role="tab" data-bs-toggle="tab" class="nav-link">
								<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18" height="18" viewBox="0 0 24 24">
									<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3H4a4 4 0 0 0 2-3v-3a7 7 0 0 1 4-6M9 17v1a3 3 0 0 0 6 0v-1" />
								</svg>
								Makluman / Ralat
								<span class="badge bg-warning ms-2">{{ $tender->news()->count() }}</span>
							</a>
							@if (auth()->check())
								@if (
									!$tender->matchCidbCodesInverse(Auth::user()->vendor_id) &&
										$tender->matchCidbGrade(auth()->user()->vendor_id) &&
										$tender->attendVisits(auth()->user()->vendor_id) &&
										$tender->attendBriefing(auth()->user()->vendor_id))
									<a href="#tf-exception" aria-controls="home" role="tab" data-bs-toggle="tab" class="nav-link">
										<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18" height="18" viewBox="0 0 24 24">
											<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
												stroke-width="2"
												d="m16.555 3.843l3.602 3.602a2.877 2.877 0 0 1 0 4.069l-2.643 2.643a2.877 2.877 0 0 1-4.069 0l-.301-.301l-6.558 6.558a2 2 0 0 1-1.239.578L5.172 21H4a1 1 0 0 1-.993-.883L3 20v-1.172a2 2 0 0 1 .467-1.284l.119-.13L4 17h2v-2h2v-2l2.144-2.144l-.301-.301a2.877 2.877 0 0 1 0-4.069l2.643-2.643a2.877 2.877 0 0 1 4.069 0M15 9h.01" />
										</svg>
										Kebenaran Khas
									</a>
								@endif
							@endif
							<a href="#tf-officer" aria-controls="home" role="tab" data-bs-toggle="tab" class="nav-link">
								<svg xmlns="http://www.w3.org/2000/svg" class="me-1 mb-1" width="18" height="18" viewBox="0 0 24 24">
									<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0-8 0M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" />
								</svg>
								Pegawai Bertanggungjawab
							</a>
							{{-- Tab - Penilaian Prestasi Syarikat --}}
							@if ($tender_winner)
								<a href="#tf-penilaian-prestasi" aria-controls="home" role="tab" data-bs-toggle="tab"
									class="nav-link @if (Session::get('ErrorRequest') or isset($active_prestasi_tab)) active @endif">
									<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18" height="18" viewBox="0 0 24 24">
										<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
											d="M3 13a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1zm12-4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1zM9 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1zM4 20h14" />
									</svg>
									Penilaian Prestasi Syarikat
								</a>
							@endif
						</div>{{-- /.nav.tender-side-nav --}}
					</div>{{-- /.tender-side-nav-card --}}
				</div>

				{{-- Tab Content --}}
				<div class="tab-content col-lg-9">

					{{-- === TAB: Pegawai Bertanggungjawab === --}}
					<div role="tabpanel" class="tab-pane" id="tf-officer">
						<div class="tender-tab-card">
							<div class="card-header">
								<h3 class="card-title">
									<svg xmlns="http://www.w3.org/2000/svg" class="me-1 mb-1" width="18" height="18"
										viewBox="0 0 24 24">
										<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
											d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0-8 0M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" />
									</svg>
									Pegawai Bertanggungjawab
								</h3>
							</div>
							<div class="card-body p-0">
								<div class="table-responsive">
									@include('tenders._pegawai_bertanggungjawab_table', [
										'tableClass' => 'table tender-info-table mb-0',
										'headerBg' => '',
									])
								</div>
							</div>
						</div>
					</div>

					{{-- === TAB: Kebenaran Khas === --}}
					@if (auth()->check())
						<div role="tabpanel" class="tab-pane" id="tf-exception">
							@if ($tender->canException())
								<div class="tender-tab-card mb-3">
									<div class="card-body p-0">
										<div class="table-responsive">
											<table class="table tender-doc-table mb-0">
												<thead>
													<tr>
														<th>Tarikh Permohonan</th>
														<th>Tajuk</th>
														<th>Status</th>
													</tr>
												</thead>
												<tbody>
													@if ($exception)
														<tr>
															<td>
																{{ $exception->updated_at ? Carbon\Carbon::parse($exception->updated_at)->format('d/m/Y') : Carbon\Carbon::parse($exception->created_at)->format('d/m/Y') }}
															</td>
															<td>{{ $exception->files[0]->label ?? '' }}</td>
															<td>
																@if ($exception->status == 2)
																	<b>{{ $exception->getStatus() }}</b> <br> Alasan :-
																	<br>
																	@if ($exception->rejection_reason)
																		Catatan : {{ $exception->rejection_reason }}
																	@endif
																	@if ($exception->rejection_template_id)
																		<br>
																		<ol>
																			@foreach (json_decode($exception->rejection_template_id, true) as $reject_id)
																				@foreach ($templates as $template)
																					@if ($template['id'] == $reject_id)
																						<li style="text-decoration: underline;">
																							{{ $template['title'] }}
																						</li>
																						{!! $template['content'] !!}
																					@endif
																				@endforeach
																			@endforeach
																	@endif
																@else
																	<b>{{ $exception->getStatus() }}</b>
																@endif
															</td>
														</tr>
													@else
														<tr>
															<td colspan="4" class="text-center text-muted py-3">Tiada
																Surat Kebenaran Khas</td>
														</tr>
													@endif
												</tbody>
											</table>
										</div>
									</div>
								</div>

								@if (!$exception || $exception->status == 2)
									<div class="tender-tab-card">
										<div class="card-header">
											<h3 class="card-title">
												<svg xmlns="http://www.w3.org/2000/svg" class="me-1 mb-1" width="18" height="18"
													viewBox="0 0 24 24">
													<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
														stroke-width="2"
														d="m16.555 3.843l3.602 3.602a2.877 2.877 0 0 1 0 4.069l-2.643 2.643a2.877 2.877 0 0 1-4.069 0l-.301-.301l-6.558 6.558a2 2 0 0 1-1.239.578L5.172 21H4a1 1 0 0 1-.993-.883L3 20v-1.172a2 2 0 0 1 .467-1.284l.119-.13L4 17h2v-2h2v-2l2.144-2.144l-.301-.301a2.877 2.877 0 0 1 0-4.069l2.643-2.643a2.877 2.877 0 0 1 4.069 0M15 9h.01" />
												</svg>
												Kebenaran Khas
											</h3>
										</div>
										<div class="card-body">
											<form action="{{ route('tender.store.exception') }}" method="post" enctype="multipart/form-data">
												@csrf
												<div class="mb-3">
													<label for="exception_letter" class="form-label fw-bold">Surat
														Kebenaran Khas <sup class="text-danger">*</sup></label>
													<input type="file" name="exception_letter" id="exception_letter" class="form-control" required>
												</div>
												<input type="hidden" name="tender_id" id="tender_id" value="{{ $tender->id }}">
												<div class="text-end">
													<button type="submit" class="btn btn-selangor confirm">
														<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="16" height="16"
															viewBox="0 0 24 24">
															<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
																stroke-width="2"
																d="M10 14l11 -11M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />
														</svg>
														Hantar
													</button>
												</div>
											</form>
										</div>
									</div>
								@endif
							@else
								<div class="alert alert-info">Kebenaran Khas tidak dibenarkan bagi tender/sebut harga ini.
								</div>
							@endif
						</div>
					@endif

					{{-- === TAB: Makluman / Ralat === --}}
					<div role="tabpanel" class="tab-pane" id="tf-news">

						@php
							$list_ralat_news = $tender->news()->wherePublish(1)->orderBy('published_at', 'asc')->get() ?? [];
						@endphp

						@if (count($list_ralat_news) > 0)
							<div class="tender-tab-card mb-3">
								<div class="card-body p-0">
									<div class="table-responsive">
										<table class="table tender-doc-table mb-0">
											<thead>
												<tr>
													<th>Tarikh</th>
													<th>Tajuk</th>
													<th>&nbsp;</th>
												</tr>
											</thead>
											<tbody>
												@forelse($tender->news()->wherePublish(1)->orderBy('published_at', 'asc')->get() as $news)
													<tr>
														<td>{{ Carbon\Carbon::parse($news->published_at)->format('d/m/Y') }}
														</td>
														<td>{{ $news->title }}</td>
														<td>{{ link_to_route('news.show', 'Selanjutnya', $news->id, ['class' => 'btn btn-sm btn-selangor']) }}
														</td>
													</tr>
												@empty
													<tr>
														<td colspan="3" class="text-muted text-center py-3">Tiada
															makluman / ralat</td>
													</tr>
												@endforelse
											</tbody>
										</table>
									</div>
								</div>
							</div>
						@else
							@if (Auth::check() && $tender->canUpdate())
								<div class="tender-tab-card">
									<div class="card-header">
										<h3 class="card-title">
											<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18" height="18" viewBox="0 0 24 24">
												<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
													stroke-width="2"
													d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3H4a4 4 0 0 0 2-3v-3a7 7 0 0 1 4-6M9 17v1a3 3 0 0 0 6 0v-1" />
											</svg>
											Berita Baru
										</h3>
									</div>
									<div class="card-body">
										{!! Former::open(url('news')) !!}
										{!! Former::text('title')->label('Tajuk')->required() !!}

										<div class="my-3">
											<label for="notification" class="form-label fw-bold">Kandungan <sup class="text-danger">*</sup></label>
											<textarea class="form-control" rows="4" required="true" id="notification" name="notification">{!! isset($news) ? $news->notification : '' !!}</textarea>
											<div id="notification-editor" class="summernote">{!! isset($news) ? $news->notification : '' !!}</div>
										</div>

										@if (Auth::user()->hasRole('Admin'))
											{!! Former::select('organization_unit_id')->label('Agensi')->options(App\OrganizationUnit::all()->pluck('name', 'id'))->required() !!}
										@endif

										<input type="hidden" name="tender_id" id="tender_id" value="{{ $tender->id }}">
										<input type="hidden" name="fromTenderRequest" id="fromTenderRequest" value="999">

										<div class="text-end">
											<button type="submit" class="btn btn-success confirm">
												<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="16" height="16"
													viewBox="0 0 24 24">
													<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
														stroke-width="2"
														d="M10 14l11 -11M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />
												</svg>
												Hantar
											</button>
										</div>
										{!! Former::close() !!}
									</div>
								</div>
							@else
								<div class="tender-tab-card">
									<div class="card-body p-0">
										<div class="table-responsive">
											<table class="table tender-doc-table mb-0">
												<thead>
													<tr>
														<th>Tarikh</th>
														<th>Tajuk</th>
														<th>&nbsp;</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td colspan="3" class="text-muted text-center py-3">Tiada
															makluman / ralat</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							@endif

						@endif


						{{-- <table class="table table-bordered">
				<thead>
					<tr>
						<th class="col-xs-1">Tarikh</th>
						<th>Tajuk</th>
						<th class="col-xs-1">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					@forelse($tender->news()->wherePublish(1)->orderBy('published_at', 'asc')->get() as $news)
						<tr>
							<td>{{ Carbon\Carbon::parse($news->published_at)->format('d/m/Y')}}</td>
							<td>{{ $news->title }}</td>
							<td>{{ link_to_route('news.show', 'Selanjutnya', $news->id, ['class' => 'btn btn-xs btn-primary']) }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="3">Tiada makluman / ralat</td>
						</tr>
					@endforelse
				</tbody>
			</table> --}}
					</div>

					{{-- === TAB: Maklumat Tender (Main) === --}}
					<div role="tabpanel" class="tab-pane @if (!Session::get('ErrorRequest') and !isset($active_prestasi_tab)) ) active @endif" id="tf-main">
						<div class="tender-tab-card">
							<div class="card-header">
								<h3 class="card-title">
									<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18" height="18" viewBox="0 0 24 24">
										<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
											d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2M9 5a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2M14 10h-4M14 14h-4M10 18h-1" />
									</svg>
									Maklumat {{ App\Tender::$types[$tender->type] }}
								</h3>
							</div>
							<div class="card-body p-0">
								<div class="table-responsive">
									<table class="table tender-info-table mb-0">
										<tr>
											<th>Petender</th>
											<td>{{ $tender->tenderer->name }}</td>
										</tr>
										<tr>
											<th>No. {{ App\Tender::$types[$tender->type] }}</th>
											<td>{{ $tender->ref_number }}</td>
										</tr>
										<tr>
											<th>Tarikh Iklan</th>
											<td>{{ $tender->tarikh_iklan_display }}</td>
										</tr>
										<tr>
											<th>Tarikh Jual</th>
											<td>{{ $tender->tarikh_jual_display }}</td>
										</tr>
										<tr>
											<th>Tarikh Tutup</th>
											<td>{{ $tender->tarikh_tutup_display }}</td>
										</tr>
										<tr>
											<th>Masa Tutup</th>
											<td>{{ $tender->masa_tutup_display }}</td>
										</tr>
										<tr>
											<th>Tempat Hantar</th>
											<td>
												{!! nl2br($tender->submission_location_address) !!}
											</td>
										</tr>
										@if ($tender->hasBriefing())
											<tr>
												<th>Tarikh &amp; Masa Taklimat</th>
												<td>{{ \Carbon\Carbon::parse($tender->briefing_datetime)->format('j M Y H:i') }}
												</td>
											</tr>
											<tr>
												<th>Alamat Taklimat</th>
												<td>
													{!! nl2br($tender->briefing_address) !!}
													@if ($tender->briefing_required)
														<br><br><small><span class="glyphicon glyphicon-ok"></span>
															Kehadiran taklimat adalah
															diwajibkan</small>
													@endif
												</td>
											</tr>
										@endif
										<tr>
											<th>Kebenaran Khas</th>
											<td>
												@if ($tender->allow_exception)
													<span class="badge bg-success d-inline-flex align-items-center">
														<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="16" height="16"
															viewBox="0 0 24 24">
															<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
																stroke-width="2">
																<path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0-18 0" />
																<path d="m9 12l2 2l4-4" />
															</g>
														</svg>
														Ya
													</span>
												@else
													<span class="badge bg-danger d-inline-flex align-items-center">
														<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="16" height="16"
															viewBox="0 0 24 24">
															<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
																stroke-width="2" d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0-18 0m7-4l4 8m-4 0l4-8" />
														</svg>
														Tidak
													</span>
												@endif
											</td>
										</tr>
										@if ($tender->only_bumiputera)
											<tr>
												<th>Syarikat Bumiputera Sahaja</th>
												<td><span class="badge bg-success d-inline-flex align-items-center">
														<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="16" height="16"
															viewBox="0 0 24 24">
															<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
																stroke-width="2">
																<path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0-18 0" />
																<path d="m9 12l2 2l4-4" />
															</g>
														</svg>
														Ya
													</span></td>
											</tr>
										@endif

										@if ($tender->only_selangor == 2)
											<tr>
												<th>Syarikat Negeri</th>
												<td>
													<span class="badge bg-info">{{ strtoupper($tender->getNegeriList()) }}
														SAHAJA</span>
												</td>
											</tr>
										@elseif ($tender->only_selangor == 3)
											<tr>
												<th>Syarikat Negeri</th>
												<td>
													<span class="badge bg-info">SELURUH MALAYSIA</span>
												</td>
											</tr>
										@endif

										@if ($tender->district_id != null && $tender->district_id > 0)
											<tr>
												<th>Syarikat Dibawah Daerah Sahaja</th>
												<td>
													<span class="badge bg-info">{{ strtoupper(App\Vendor::$districts[$tender->district_id]) }}
														SAHAJA</span>
												</td>
											</tr>
										@elseif($tender->district_id == null && $tender->getDaerahListExist() === true && $tender->only_selangor != 3)
											<tr>
												<th>Syarikat Dibawah Daerah Sahaja</th>
												<td>
													<span class="badge bg-info">{{ strtoupper($tender->getDaerahList()) }}
														SAHAJA</span>
												</td>
											</tr>
										@elseif($tender->district_id == null && $tender->district_list_rule === '[]' && $tender->only_selangor == 1)
											<tr>
												<th>Syarikat Dibawah Daerah Sahaja</th>
												<td>
													<span class="badge bg-info">SELURUH SELANGOR</span>
												</td>
											</tr>
										@endif

										<tr>
											<th>Harga Dokumen</th>
											<td><strong>RM {{ number_format($tender->price, 2) }}</strong></td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>

					{{-- === TAB: Syarat Tender === --}}
					<div role="tabpanel" class="tab-pane" id="tf-syarat">
						<div class="tender-tab-card">
							<div class="card-header">
								<h3 class="card-title">
									<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18" height="18" viewBox="0 0 24 24">
										<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
											d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2M9 5a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2M9 12l6 0M9 16l6 0" />
									</svg>
									Syarat {{ App\Tender::$types[$tender->type] }}
								</h3>
							</div>
							<div class="card-body">
								{{-- {!! nl2br( $tender->tender_rules, '<b><strong><i><em><u><p><ul><ol><li>' ) !!} --}}
								{!! $tender->tender_rules !!}
							</div>
						</div>
					</div>

					{{-- === TAB: Lawatan Tapak === --}}
					@if (count($tender->siteVisits) > 0)
						<?php $index = 1; ?>
						<div role="tabpanel" class="tab-pane" id="tf-lawatan">
							<div class="tender-tab-card">
								<div class="card-header">
									<h3 class="card-title">
										<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18" height="18" viewBox="0 0 24 24">
											<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
												stroke-width="2"
												d="M12 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0" />
										</svg>
										Lawatan Tapak
									</h3>
								</div>
								<div class="card-body p-0">
									<div class="table-responsive">
										<table class="table tender-doc-table mb-0">
											<thead>
												<tr>
													<th>Bil.</th>
													<th>Tempat Berkumpul</th>
													<th>Alamat Lawatan Tapak</th>
													<th>Tarikh &amp; Waktu</th>
													<th>Wajib Hadir</th>
												</tr>
											</thead>
											<tbody>
												@foreach ($tender->siteVisits->sortBy('id') as $visit)
													<tr>
														<td>{{ $index }}</td>
														<td>{!! nl2br($visit->meetpoint) !!}</td>
														<td>{!! nl2br($visit->address) !!}</td>
														<td>{{ Carbon\Carbon::parse($visit->datetime)->format('j M Y H:i') }}
														</td>
														<td>
															@if ($visit->required)
																<span class="badge bg-success d-inline-flex align-items-center">
																	<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="16" height="16"
																		viewBox="0 0 24 24">
																		<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
																			stroke-width="2">
																			<path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0-18 0" />
																			<path d="m9 12l2 2l4-4" />
																		</g>
																	</svg>
																	Ya
																</span>
															@else
																<span class="badge bg-danger d-inline-flex align-items-center">
																	<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="16" height="16"
																		viewBox="0 0 24 24">
																		<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
																			stroke-width="2" d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0-18 0m7-4l4 8m-4 0l4-8" />
																	</svg>
																	Tidak
																</span>
															@endif
														</td>
													</tr>
													<?php $index++; ?>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					@endif

					{{-- === TAB: Kod-Kod Bidang === --}}
					@if (count($tender->mof_codes) > 0 || count($tender->cidb_grades) > 0 || count($tender->cidb_codes) > 0)
						<div role="tabpanel" class="tab-pane" id="tf-kod">
							<div class="card-header mb-2">
								{{-- <h3 class="card-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="me-1 mb-1" width="18"
                                        height="18" viewBox="0 0 24 24">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2"
                                            d="M7 8l-4 4l4 4M17 8l4 4l-4 4M14 4l-4 16" />
                                    </svg>
                                    Kod-Kod Bidang
                                </h3> --}}
							</div>
							@if (count($tender->mof_codes) > 0)
								<div class="tender-tab-card mb-3">
									<div class="card-body p-0">
										<div class="table-responsive">
											<table class="table tender-info-table mb-0">
												<?php $max_count = count($tender->mof_code_groups); ?>
												<tr>
													<th>Kod Bidang MOF</th>
													<td>
														@foreach ($tender->mof_code_groups as $order => $data)
															@foreach ($data['codes'] as $id => $label)
																@if ($order < $max_count)
																	<br>
																@endif
															@endforeach

															{!! implode(
															    '<br>' . App\VendorCode::$rule[$data['inner_rule']] . '<br>',
															    tender_vendor_codes($data['codes'], Auth::user()),
															) !!}
															@if ($order != $max_count)
																<br><br>{!! App\VendorCode::$rule[$data['join_rule']] !!}<br><br>
															@endif
														@endforeach
													</td>
												</tr>
											</table>
										</div>
									</div>
								</div>
								@if (count($tender->cidb_grades) > 0)
									<div class="text-center my-2">
										<span class="badge bg-success">{{ $tender->mof_cidb_rule == 'or' ? 'ATAU' : 'DAN' }}</span>
									</div>
								@endif
							@endif

							@if (count($tender->cidb_grades) > 0)
								<div class="tender-tab-card mb-3">
									<div class="card-body p-0">
										<div class="table-responsive">
											<table class="table tender-info-table mb-0">
												<tr>
													<th>Gred CIDB</th>
													<td>
														<ul class="mb-0">
															@foreach ($tender->cidb_grades as $code)
																<li>{!! tender_cidb_grade($code->code, Auth::user()) !!}</li>
															@endforeach
														</ul>
													</td>
												</tr>

												@if (count($tender->cidb_codes) > 0)
													<?php $max_count = count($tender->cidb_code_groups); ?>
													<tr>
														<th>Bidang Pengkhususan CIDB</th>
														<td>
															@foreach ($tender->cidb_code_groups as $order => $data)
																{!! implode(
																    '<br>' . App\VendorCode::$rule[$data['inner_rule']] . '<br>',
																    tender_vendor_codes($data['codes'], Auth::user()),
																) !!}
																@if ($order != $max_count)
																	<br><br>{!! App\VendorCode::$rule[$data['join_rule']] !!}<br><br>
																@endif
															@endforeach
														</td>
													</tr>
												@endif
											</table>
										</div>
									</div>
								</div>
							@endif
						</div>
					@endif

					{{-- === TAB: Dokumen Meja Terkawal === --}}
					<div role="tabpanel" class="tab-pane" id="tf-doc1">
						<div class="tender-tab-card">
							<div class="card-header">
								<h3 class="card-title">
									<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18" height="18" viewBox="0 0 24 24">
										<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
											d="M14 3v4a1 1 0 0 0 1 1h4M5 8v-3a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-5M2 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1M4 18a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
									</svg>
									Dokumen Meja Terkawal
								</h3>
							</div>
							<div class="card-body p-0">
								<div class="table-responsive">
									<table class="table tender-doc-table mb-0">
										<thead>
											<tr>
												<th>Nama</th>
												<th>Saiz</th>
												<th>Jenis</th>
												<th>&nbsp;</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($tender->tableFiles as $upload)
												<tr>
													<td>{{ $upload->label }}</td>
													<td>{{ $upload->size }}</td>
													<td>{{ $upload->type }}</td>
													<td>
														<a href="{{ $upload->url }}" class="btn btn-sm btn-selangor" download>
															<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="16" height="16"
																viewBox="0 0 24 24">
																<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
																	stroke-width="2" d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2M7 11l5 5l5 -5M12 4l0 12" />
															</svg>
															Muat Turun
														</a>
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>

					{{-- === TAB: Dokumen Tender/Tawaran atau Sebut Harga (senarai semak) === --}}
					@if ($tender->canShowDokumenSenaraiTab(Auth::user()->vendor_id))
						<div role="tabpanel" class="tab-pane" id="tf-dokumen-tawaran">
							<div class="tender-tab-card">
								<div class="card-header">
									<h3 class="card-title">
										<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18" height="18" viewBox="0 0 24 24">
											<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
												stroke-width="2" d="M9 11l3 3l8-8M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
										</svg>
										{{ $tender->dokumenSenaraiTabLabel() }}
									</h3>
									<p class="text-muted small mb-0 mt-1">Senarai dokumen yang diperlukan daripada petender</p>
								</div>
								<div class="card-body p-0">
									@include('tenders._dokumen_tender_checklist_table', [
										'tenderDokumen' => $tenderDokumen,
										'tender' => $tender,
										'mode' => $dokumenMode,
										'vendorCanEdit' => $vendorCanEdit,
									])
								</div>
							</div>
						</div>
					@endif

					{{-- === TAB: Dokumen muat turun (legacy) === --}}
					@if (!$tender->showDokumenSenaraiTab() && Auth::check() && $tender->canShowFiles(Auth::user()->vendor_id))
						<div role="tabpanel" class="tab-pane" id="tf-doc2">
							@if (count($tender->tender_files) > 0)
								<div class="tender-tab-card">
									<div class="card-header">
										<h3 class="card-title">
											<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18" height="18" viewBox="0 0 24 24">
												<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
													<path d="M15 3v4a1 1 0 0 0 1 1h4" />
													<path d="M18 17h-7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4l5 5v7a2 2 0 0 1-2 2" />
													<path d="M16 17v2a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h2" />
												</g>
											</svg>
											Dokumen {{ App\Tender::$types[$tender->type] }}
										</h3>
									</div>
									<div class="card-body p-0">
										<div class="table-responsive">
											<table class="table tender-doc-table mb-0">
												<thead>
													<tr>
														<th>Nama</th>
														<th>Saiz</th>
														<th>Jenis</th>
														<th>&nbsp;</th>
													</tr>
												</thead>
												<tbody>
													@foreach ($tender->tenderFiles as $upload)
														<tr>
															<td>{{ $upload->label }}</td>
															<td>{{ $upload->size }}</td>
															<td>{{ $upload->type }}</td>
															<td>
																<a href="{{ $upload->url }}" class="btn btn-sm btn-selangor" download>
																	<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="16" height="16"
																		viewBox="0 0 24 24">
																		<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
																			stroke-width="2" d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2M7 11l5 5l5 -5M12 4l0 12" />
																	</svg>
																	Muat Turun
																</a>
															</td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
							@else
								<div class="tender-tab-card">
									<div class="card-header">
										<h3 class="card-title">
											<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18" height="18" viewBox="0 0 24 24">
												<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
													<path d="M15 3v4a1 1 0 0 0 1 1h4" />
													<path d="M18 17h-7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4l5 5v7a2 2 0 0 1-2 2" />
													<path d="M16 17v2a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h2" />
												</g>
											</svg>
											Dokumen {{ App\Tender::$types[$tender->type] }}
										</h3>
									</div>
									<div class="card-body">
										<div class="alert alert-info mb-0">Tiada fail untuk dimuat turun, sila rujuk syarat
											tender atau
											berhubung dengan agensi yang berkenaan.</div>
									</div>
								</div>
							@endif
						</div>
					@endif

					{{-- === TAB: Senarai Jemputan === --}}
					@if ($tender->invitation && $tender->canUpdate())
						<div role="tabpanel" class="tab-pane" id="tf-invites">
							<div class="tender-tab-card">
								<div class="card-header">
									<h3 class="card-title">
										<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18" height="18" viewBox="0 0 24 24">
											<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
												stroke-width="2" d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2zm0 0l9 6l9-6" />
										</svg>
										Senarai Jemputan
									</h3>
								</div>
								<div class="card-body">
									{!! Former::open(action('TendersController@updateInvites', $tender->id))->class('form-inline') !!}
									@if (count($invites) > 0)
										<div class="table-responsive mb-3">
											<table class="table tender-doc-table mb-0">
												<thead>
													<tr>
														<th>Nama Syarikat</th>
														<th style="width: 80px;">Padam</th>
													</tr>
												</thead>
												<tbody>
													@foreach ($invites as $invite)
														<tr>
															<td>
																<strong>{{ $invite->vendor->name }}</strong>
																<br><a href="{{ route('tenders.vendor', ['tender_id' => $tender->id, 'id' => $invite->vendor_id]) }}"
																	class="btn btn-sm btn-outline-danger mt-1">Maklumat
																	Syarikat</a>
															</td>
															<td>
																@if ($tender->hasParticipate($invite->vendor_id))
																	<span class="text-muted"><svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="16"
																			height="16" viewBox="0 0 24 24">
																			<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
																				stroke-width="2" d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0M5.7 5.7l12.6 12.6" />
																		</svg></span>
																@else
																	<input type="checkbox" class="form-check-input" name="deleted_invites[]"
																		value="{{ $invite->vendor_id }}">
																@endif
															</td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									@else
										<div class="alert alert-info mb-3">Tiada Syarikat dijemput.</div>
									@endif
									<div class="row align-items-end">
										<div class="col-lg-6 mb-2">
											<input type="text" id="invite_ids" name="invite_ids" placeholder="Tambah syarikat"
												class="form-control">
											<small class="text-muted">Cari nama syarikat yang ingin dijemput dan tekan
												"Simpan Maklumat Jemputan"</small>
										</div>
										<div class="col-lg-6 mb-2 text-end">
											<button type="submit" class="btn btn-selangor confirm">
												<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="16" height="16"
													viewBox="0 0 24 24">
													<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
														stroke-width="2"
														d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2M10 14a2 2 0 1 0 4 0a2 2 0 1 0 -4 0M14 4l0 4l-6 0l0 -4" />
												</svg>
												Simpan Maklumat Jemputan
											</button>
										</div>
									</div>
									{!! Former::close() !!}
								</div>
							</div>
						</div>
					@endif

					{{-- === TAB: Sejarah Pengubahan === --}}
					@if (Auth::check() && $tender->canUpdate())
						<div role="tabpanel" class="tab-pane hidden-print" id="tf-history">
							<div class="tender-tab-card">
								<div class="card-header">
									<h3 class="card-title">
										<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="18" height="18" viewBox="0 0 24 24">
											<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
												stroke-width="2" d="M12 8v4l2 2M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5" />
										</svg>
										Sejarah Pengubahan
									</h3>
								</div>
								@if (count($histories) > 0)
									<div class="card-body p-0">
										<div class="table-responsive">
											<table class="table tender-doc-table mb-0">
												<thead>
													<tr>
														<th>Tarikh</th>
														<th>Keterangan</th>
														<th>Pengguna</th>
													</tr>
												</thead>
												<tbody>
													@foreach ($histories as $history)
														<tr>
															<td>{{ Carbon\Carbon::parse($history->created_at)->format('d/m/Y H:i') }}
															</td>
															<td>{{ $history->label }}</td>
															<td>
																@if ($history->user)
																	{{ $history->user->name }}
																@else
																	{{ boolean_icon($history->user) }}
																@endif
															</td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								@else
									<div class="card-body">
										<div class="alert alert-info mb-0">Tiada maklumat sejarah pengubahan.</div>
									</div>
								@endif
							</div>
						</div>
					@endif

					{{-- === TAB: Penilaian Prestasi Syarikat === --}}
					@if (isset($tender_winner->vendor))
						{{-- START:Content - Tab - Penilaian Prestasi Syarikat --}}
						<div role="tabpanel" class="tab-pane @if (Session::get('ErrorRequest') or isset($active_prestasi_tab)) active @endif"
							id="tf-penilaian-prestasi">

							{{-- Error Box --}}
							@if (count($errors) > 0)
								<div class="alert alert-danger mb-3">
									<strong><i class="ti ti-alert-triangle"></i>
										Amaran!</strong>
									<ul class="mb-0 mt-2">
										@foreach ($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
								</div>
							@endif

							{{-- START:Accordion - Borang Penilaian Syarikat --}}
							@include('tenders.petender-performance.form')

							{{-- Table - Senarai Prestasi Syarikat based on Tender --}}
							@include('tenders.petender-performance.table')

						</div>
						{{-- END:Content - Tab - Penilaian Prestasi Syarikat --}}
					@endif

				</div>
			</div>

			@if ($tender->canPurchase())
				<div class="text-end mt-3">
					<a href="{{ route('tenders.buy', [$tender->id]) }}" class="btn btn-selangor">Tambah Kepada Senarai Tempahan</a>
				</div>
			@endif

		</div>
	</div>

@endsection

@section('scripts')
	{{-- DataTables JS already loaded in master.blade.php --}}
	{{-- <script src="{{ asset('js/datatables.js') }}"></script> --}}
	{{-- <script src="https://cdn.ckeditor.com/4.20.2/full/ckeditor.js"></script> --}}
	<script src="{{ asset('custom_library/ckeditor/ckeditor.js') }}"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			// Initialize selectize only if element exists
			if ($("#invite_ids").length) {
				$("#invite_ids").selectize({
					valueField: 'id',
					labelField: 'name',
					searchField: 'name',
					create: false,
					render: {
						option: function(item, escape) {
							return '<div>' +
								'<strong>' + escape(item.registration) + '</strong> ' + escape(item
									.name) +
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
			}

			// Initialize CKEditor only if element exists
			if (document.getElementById('notification')) {
				CKEDITOR.replace('notification', {
					toolbarGroups: [{
							name: 'document',
							groups: ['mode', 'document', 'doctools']
						},
						{
							name: 'clipboard',
							groups: ['clipboard', 'undo']
						},
						{
							name: 'editing',
							groups: ['find', 'selection', 'spellchecker', 'editing']
						},
						{
							name: 'forms',
							groups: ['forms']
						},
						{
							name: 'insert',
							groups: ['insert']
						},
						'/',
						{
							name: 'basicstyles',
							groups: ['basicstyles', 'cleanup']
						},
						{
							name: 'paragraph',
							groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']
						},
						{
							name: 'links',
							groups: ['links']
						},
						'/',
						{
							name: 'styles',
							groups: ['styles']
						},
						{
							name: 'colors',
							groups: ['colors']
						},
						{
							name: 'tools',
							groups: ['tools']
						},
						{
							name: 'others',
							groups: ['others']
						},
						{
							name: 'about',
							groups: ['about']
						}
					],
					removeButtons: 'Flash,Iframe,Form,TextField,Checkbox,Radio,Textarea,Select,Button,ImageButton,HiddenField'
				});
			}
		});
	</script>

	{{-- jQuery already loaded in master.blade.php --}}
	{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> --}}
	<script>
		let sum = 0;
		const scales = {
			scale_1: 0,
			scale_2: 0,
			scale_3: 0,
			scale_4: 0,
			scale_5: 0,
			scale_6: 0
		}

		const sumEle = window.document.getElementById('sum');
		const calcEle = window.document.getElementById('calc');
		const scaleInputNoOne = window.document.getElementsByName('scale_1');
		const scaleInputNoTwo = window.document.getElementsByName('scale_2');
		const scaleInputNoThree = window.document.getElementsByName('scale_3');
		const scaleInputNoFour = window.document.getElementsByName('scale_4');
		const scaleInputNoFive = window.document.getElementsByName('scale_5');
		const scaleInputNoSix = window.document.getElementsByName('scale_6');

		const updateMarks = (key, preScale, nextScale) => {
			const updateSum = sum - preScale + Number(nextScale)
			if (sumEle) {
				sumEle.value = updateSum
				sumEle.innerHTML = updateSum
			}
			scales[key] = nextScale;
			sum = updateSum
			if (calcEle) {
				calcEle.value = Number(updateSum / 30 * 100).toFixed(2)
			}
			// calcEle.innerHTML = Number(updateSum / 30 * 100).toFixed(2)
		}

		if (scaleInputNoOne && scaleInputNoOne.length > 0) {
			scaleInputNoOne.forEach(element => {
				if (element) {
					element.addEventListener('click', (e) => {
						updateMarks('scale_1', scales.scale_1, e.target.value)
					})
				}
			});
		}
		if (scaleInputNoTwo && scaleInputNoTwo.length > 0) {
			scaleInputNoTwo.forEach(element => {
				if (element) {
					element.addEventListener('click', (e) => {
						updateMarks('scale_2', scales.scale_2, e.target.value)
					})
				}
			});
		}
		if (scaleInputNoThree && scaleInputNoThree.length > 0) {
			scaleInputNoThree.forEach(element => {
				if (element) {
					element.addEventListener('click', (e) => {
						updateMarks('scale_3', scales.scale_3, e.target.value)
					})
				}
			});
		}
		if (scaleInputNoFour && scaleInputNoFour.length > 0) {
			scaleInputNoFour.forEach(element => {
				if (element) {
					element.addEventListener('click', (e) => {
						updateMarks('scale_4', scales.scale_4, e.target.value)
					})
				}
			});
		}
		if (scaleInputNoFive && scaleInputNoFive.length > 0) {
			scaleInputNoFive.forEach(element => {
				if (element) {
					element.addEventListener('click', (e) => {
						updateMarks('scale_5', scales.scale_5, e.target.value)
					})
				}
			});
		}
		if (scaleInputNoSix && scaleInputNoSix.length > 0) {
			scaleInputNoSix.forEach(element => {
				if (element) {
					element.addEventListener('click', (e) => {
						updateMarks('scale_6', scales.scale_6, e.target.value)
					})
				}
			});
		}
	</script>
	<script>
		$(function() {
			$('#criteria_1, #value2').keyup(function() {
				var value1 = parseFloat($('#value1').val()) || 0;
				var value2 = parseFloat($('#value2').val()) || 0;
				$('#sum').val(value1 + value2);
			});
		});
	</script>
	<script>
		const jenisSlectEle = window.document.getElementById("jenis-select")
		const jenisInputEle = window.document.getElementById("jenis-input")

		if (jenisSlectEle && jenisInputEle) {
			jenisSlectEle.addEventListener('change', (e) => {
				if (e.target.value === 'Lain - lain') {
					jenisInputEle.classList.replace("hidden", "display")
				} else {
					jenisInputEle.classList.replace("display", "hidden")
					jenisInputEle.value = "";
				}
			})
		}
	</script>
	@if ($tender->canShowDokumenSenaraiTab(Auth::user()->vendor_id))
		@include('tenders.forms._online_form_modal')
	@endif
@endsection
