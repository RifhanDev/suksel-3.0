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
				<div class="tender-page-header">
					<div class="tender-ref-label">
						<span class="tender-type-label">{{ App\Tender::$types[$tender->type] }}</span>
						<span class="tender-ref-sep">·</span>
						<span class="tender-ref-no">{{ $tender->displayNoTender() }}</span>
					</div>
					<h2 class="tender-title-main">{{ $tender->name }}</h2>
				</div>

				@if (Auth::user() && $tender->canShowTabs())
					<div class="tender-top-tabs mt-4">
						<ul class="nav nav-tabs" data-bs-toggle="tabs">
							<li class="nav-item">
								<a href="{{ asset('tenders/' . $tender->id) }}" class="nav-link">
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
									<a href="{{ asset('tenders/' . $tender->id . '/exceptions') }}" class="nav-link active">
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

			{{-- Maklumat Kebenaran Khas --}}
			<div class="tender-tab-card mb-4">
				<div class="card-header">
					<h3 class="card-title">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="var(--sg-red, #c41e3a)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
						</svg>
						Maklumat Kebenaran Khas
					</h3>
				</div>

				@if (count($exceptions) > 0)
					<div class="table-responsive">
						<table class="table tender-doc-table table-vcenter mb-0">
							<thead>
								<tr>
									<th style="width: 5%">Bil.</th>
									<th>Nama Syarikat</th>
									<th style="width: 14%">Tarikh Permohonan</th>
									<th style="width: 26%">Status</th>
									<th class="text-center" style="width: 10%">Fail</th>
									<th class="text-center" style="width: 14%">Tindakan</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($exceptions as $exception)
									<tr>
										<td>{{ $loop->index + 1 }}</td>
										<td class="fw-semibold">{{ $exception->vendor->name }}</td>
										<td class="text-nowrap">
											{{ $exception->updated_at ? Carbon\Carbon::parse($exception->updated_at)->format('d/m/Y') : Carbon\Carbon::parse($exception->created_at)->format('d/m/Y') }}
										</td>
										<td>
											<span class="badge-status {{ [0 => 'badge-status-warning', 1 => 'badge-status-success', 2 => 'badge-status-danger'][$exception->status] ?? 'badge-status-neutral' }}">
												{{ $exception->getStatus() }}
											</span>
											@if ($exception->status == 2)
												<div class="small mt-2" style="color: #64748b;">
													<div class="fw-semibold" style="color: #334155;">Alasan:</div>
													@if ($exception->rejection_reason)
														<div>Catatan: {{ $exception->rejection_reason }}</div>
													@endif
													@if ($exception->rejection_template_id)
														<ol class="mb-0 ps-3 mt-1">
															@foreach (json_decode($exception->rejection_template_id, true) as $reject_id)
																@foreach ($templates as $template)
																	@if ($template['id'] == $reject_id)
																		<li class="fw-semibold">{{ $template['title'] }}</li>
																		{!! $template['content'] !!}
																	@endif
																@endforeach
															@endforeach
														</ol>
													@endif
												</div>
											@endif
										</td>
										<td class="text-center">
											<div class="d-flex gap-2 justify-content-center flex-nowrap">
												@forelse ($exception->files as $key => $value)
													<button type="button" class="btn btn-sm btn-primary rounded-8 px-3 text-nowrap btn-file-view"
														data-url="{{ method_exists($value, 'fileUrl') ? $value->fileUrl() : ($value->url . '/' . $value->name) }}">Lihat</button>
												@empty
													<span style="color: #94a3b8;">Tiada Fail</span>
												@endforelse
											</div>
										</td>
										<td class="text-center">
											@if ($exception->status == 0 && $exception->canApprove())
												<div class="d-flex gap-2 justify-content-center flex-nowrap">
													<a href="{{ action('TendersController@approve_exception', [$exception->id]) }}"
														data-vendor="{{ $exception->vendor->name }}"
														class="btn btn-sm btn-success rounded-8 px-3 text-nowrap btn-lulus-exception">Lulus</a>
													<button type="button" data-id="{{ $exception->id }}" data-vendor="{{ $exception->vendor->name }}"
														class="btn btn-sm btn-danger rounded-8 px-3 text-nowrap btn-tolak-exception">Tolak</button>
												</div>
											@else
												-
											@endif
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>

					@if ($exception->canApprove())
						<div class="modal fade" id="modalTolakKebenaran" tabindex="-1" aria-labelledby="tolakKebenaranTitle" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-confirm">
								<div class="modal-content">
									<div class="modal-header border-bottom-0 pt-4 pb-2 px-4">
										<div class="modal-confirm-center">
											<div class="modal-confirm-icon modal-confirm-icon--danger">
												<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none"
													stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
													<circle cx="12" cy="12" r="10"></circle>
													<line x1="15" y1="9" x2="9" y2="15"></line>
													<line x1="9" y1="9" x2="15" y2="15"></line>
												</svg>
											</div>
											<h5 class="modal-confirm-title" id="tolakKebenaranTitle">Tolak Permohonan</h5>
											<p class="modal-confirm-desc">Nyatakan alasan penolakan kebenaran khas bagi
												<strong id="tolakKebenaranVendor"></strong>.</p>
										</div>
									</div>
									<div class="modal-body px-4 pt-2 pb-0">
										<form id="formTolakKebenaran">
											<label for="tolakKebenaranReason" class="form-label fw-medium small">Alasan Penolakan</label>
											<textarea class="form-control" id="tolakKebenaranReason" name="reason" rows="3"
												placeholder="Sila nyatakan sebab penolakan..."></textarea>

											@if (count($templates) > 0)
												<div class="d-flex align-items-center gap-2 my-3">
													<hr class="flex-grow-1 my-0">
													<span class="small fw-medium" style="color: #94a3b8;">atau pilih templat</span>
													<hr class="flex-grow-1 my-0">
												</div>
												<div class="border rounded p-3" style="max-height: 180px; overflow-y: auto; border-color: #e5e7eb !important;">
													@foreach ($templates as $template)
														<div class="form-check mb-2">
															<input class="form-check-input" type="checkbox" id="tolakTemplat{{ $template->id }}"
																name="template" value="{{ $template->id }}">
															<label class="form-check-label" for="tolakTemplat{{ $template->id }}" data-bs-toggle="tooltip"
																data-bs-html="true" data-bs-placement="right" title="{{ $template->content }}">
																{{ $template->title }}
															</label>
														</div>
													@endforeach
												</div>
											@endif

											<div class="text-danger small mt-2 d-none" id="tolakKebenaranError"></div>
										</form>
									</div>
									<div class="modal-footer border-top-0 px-4 pb-4 pt-3 d-flex justify-content-center gap-2">
										<button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Batal</button>
										<button type="button" class="btn-form btn-form-danger" id="btnSahkanTolak">Tolak</button>
									</div>
								</div>
							</div>
						</div>
					@endif
				@else
					<div class="d-flex flex-column align-items-center justify-content-center py-5 text-center">
						<div class="mb-3"
							style="width: 52px; height: 52px; background: #f8fafc; border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0;">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
								stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
								<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
							</svg>
						</div>
						<p class="fw-semibold mb-1" style="color: #334155;">Tiada Permohonan</p>
						<p class="small mb-0" style="color: #94a3b8;">Tiada syarikat memohon kebenaran khas.</p>
					</div>
				@endif
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script>
		document.querySelectorAll('#modalTolakKebenaran [data-bs-toggle="tooltip"]').forEach(function(el) {
			new bootstrap.Tooltip(el);
		});

		$('.btn-lulus-exception').on('click', function(e) {
			e.preventDefault();
			var href = this.href;

			showConfirmModal({
				title: 'Sahkan Kelulusan',
				message: 'Luluskan permohonan kebenaran khas bagi ' + $(this).data('vendor') + '?',
				icon: 'success',
				confirmText: 'Ya, Luluskan'
			}).then(function(confirmed) {
				if (confirmed) window.location.href = href;
			});
		});

		var rejectExceptionId = null;

		$('.btn-tolak-exception').on('click', function() {
			rejectExceptionId = $(this).data('id');
			$('#tolakKebenaranVendor').text($(this).data('vendor'));
			$('#formTolakKebenaran')[0].reset();
			$('#tolakKebenaranError').addClass('d-none').text('');
			bootstrap.Modal.getOrCreateInstance(document.getElementById('modalTolakKebenaran')).show();
		});

		$('#btnSahkanTolak').on('click', function() {
			var reason = $.trim($('#tolakKebenaranReason').val());
			var template = $('#formTolakKebenaran input[name="template"]:checked').map(function() {
				return this.value;
			}).get();

			if (reason === '' && template.length === 0) {
				$('#tolakKebenaranError').removeClass('d-none').text('Sila nyatakan alasan atau pilih templat penolakan.');
				return;
			}

			var $btn = $(this).prop('disabled', true).text('Menolak...');

			$.post('/tenders/{{ $tender->id }}/reject/' + rejectExceptionId, {
					reason: reason,
					template: template
				})
				.done(function() {
					window.location.href = '{{ route('tender.exceptions', $tender->id) }}';
				})
				.fail(function() {
					$('#tolakKebenaranError').removeClass('d-none').text('Permohonan tidak dapat ditolak. Sila cuba lagi.');
					$btn.prop('disabled', false).text('Tolak');
				});
		});
	</script>
@endsection
