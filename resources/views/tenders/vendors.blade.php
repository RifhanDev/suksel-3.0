@extends('layouts.modern')
@section('content')
	<div class="row">
		<div class="col-lg-9">
			@include('tenders._menu')

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

			@include('tenders._notification')

			@if (Auth::user() && $tender->canShowTabs())
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

			@if (Auth::user()->hasRole('Admin'))
				<div class="card mb-3">
					<div class="card-body">
						<div class="nav nav-pills" role="tablist">
							<a href="{{ asset('tenders/' . $tender->id . '/eligibles') }}"
								class="nav-link @if (!isset($purchases)) active @endif">
								<i class="ti ti-check-circle me-2"></i>Senarai Layak
							</a>
							<a href="{{ asset('tenders/' . $tender->id . '/vendors') }}"
								class="nav-link @if (isset($purchases)) active @endif">
								<i class="ti ti-shopping-cart me-2"></i>Pembelian Dokumen
							</a>
						</div>
					</div>
				</div>
			@endif

			<div class="card">
				<div class="card-header">
					<h3 class="card-title">
						<i class="ti ti-building me-2"></i>Maklumat Syarikat
					</h3>
				</div>
				<div class="card-body">
					{!! Former::open(url('tenders/' . $tender->id . '/vendors'))->class('form-inline') !!}
					@if (count($purchases) > 0)
						<?php $count = 1; ?>
						<div class="table-responsive">
							<table class="table table-vcenter table-mobile-md">
								<thead>
									<tr>
										<th class="w-5">
											<i class="ti ti-hash me-1"></i>Bil.
										</th>
										<th>
											<i class="ti ti-building me-1"></i>Nama Syarikat
										</th>
										@if (!$tender->only_advertise)
											<th class="w-10">
												<i class="ti ti-shopping-cart me-1"></i>Beli Dokumen
											</th>
										@endif

										@if ($tender->hasBriefing())
											<th class="w-10">
												<i class="ti ti-presentation me-1"></i>Taklimat
												<input type="checkbox" class="form-check-input checker ms-2" data-target="briefing">
											</th>
										@endif

										@if (count($tender->siteVisits()->get()) > 0)
											<?php $index = 1; ?>
											@foreach ($tender->siteVisits()->orderBy('id', 'asc')->get() as $visit)
												<th class="w-10">
													<i class="ti ti-map-pin me-1"></i>LT {{ $index }}
													<input type="checkbox" class="form-check-input checker ms-2" data-target="visit-{{ $visit->id }}">
												</th>
												<?php $index++; ?>
											@endforeach
										@endif

										@if ($tender->canShowPrices())
											<th class="w-10">
												<i class="ti ti-tag me-1"></i>Label
											</th>
											<th class="w-10">
												<i class="ti ti-currency-ringgit me-1"></i>Harga
											</th>
											<th class="w-10">
												<i class="ti ti-trophy me-1"></i>Berjaya
											</th>
											{{-- Check if there are winner for this tender yet. If yes, this column will appear --}}
											@if ($count_winner > 0)
												<th class="w-10">
													<i class="ti ti-star me-1"></i>Gred / Prestasi
												</th>
											@endif
										@endif
										<th class="w-10">
											<i class="ti ti-trash me-1"></i>Padam
											<input type="checkbox" class="form-check-input checker ms-2" data-target="delete">
										</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($purchases as $purchase)
										<tr>
											<td>{{ $count }}</td>
											<td>
												<div class="d-flex align-items-center">
													<div class="flex-fill">
														<div class="font-weight-medium">{{ $purchase->vendor->name }}</div>
														@if ($purchase->ref_number)
															<div class="text-muted small">No. Siri Dokumen: {{ $purchase->ref_number }}</div>
														@endif
														@if ($purchase->exception)
															<div class="text-warning small">
																<i class="ti ti-star me-1"></i>Kebenaran Khas
															</div>
														@endif
													</div>
													<div class="ms-2">
														<a href="{{ asset('tenders/' . $tender->id . '/vendor/' . $purchase->vendor_id) }}"
															class="btn btn-primary btn-sm">
															<i class="ti ti-eye me-1"></i>Maklumat Syarikat
														</a>
													</div>
												</div>
											</td>

											@if (!$tender->only_advertise)
												<td class="text-center">
													@if ($purchase->participate)
														<span class="badge bg-success">
															<i class="ti ti-check"></i>
														</span>
													@else
														<span class="badge bg-danger">
															<i class="ti ti-x"></i>
														</span>
													@endif
												</td>
											@endif

											@if ($tender->hasBriefing())
												<td class="text-center">
													<input type="checkbox" class="form-check-input briefing"
														name="briefing[{{ $purchase->id }}]"@if ($purchase->briefing) checked @endif>
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
													<div class="form-check">
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
														<a href="{{ route('index.TenderVendor', $tender) }}" class="btn btn-success btn-sm">
															<i class="ti ti-eye me-1"></i>Papar
														</a>
													@endif
												</td>
											@endif
											<td class="text-center">
												@if ($purchase->participate == 0)
													<input type="checkbox" class="form-check-input delete" name="delete[]" value="{{ $purchase->id }}">
												@else
													<span class="badge bg-secondary">
														<i class="ti ti-ban"></i>
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
						<div class="empty">
							<div class="empty-icon">
								<i class="ti ti-building"></i>
							</div>
							<p class="empty-title">Tiada Syarikat</p>
							<p class="empty-subtitle text-muted">Tiada syarikat yang menyertai tender ini.</p>
						</div>
					@endif

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-8">
								<div class="mb-3">
									<label class="form-label">Tambah Syarikat</label>
									<input type="text" id="vendor_ids" name="vendor_ids" class="form-control"
										placeholder="Cari nama syarikat...">
									<small class="form-hint">Cari nama syarikat yang ingin ditambah dan tekan "Simpan Maklumat Syarikat"</small>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="d-flex align-items-end h-100">
									<button type="submit" class="btn btn-primary w-100 confirm">
										<i class="ti ti-device-floppy me-1"></i>Simpan Maklumat Syarikat
									</button>
								</div>
							</div>
						</div>
					</div>
					{!! Former::close() !!}
				</div>
			</div>

			<div class="card mb-3">
				<div class="card-header">
					<h3 class="card-title">
						<i class="ti ti-upload me-2"></i>Muat Naik Maklumat Syarikat
					</h3>
				</div>
				<div class="card-body">
					{!! Former::open_for_files(url('tenders/' . $tender->id . '/vendors/bulkUpdate'))->class('form-inline') !!}
					<div class="row">
						<div class="col-lg-8">
							<div class="mb-3">
								<label class="form-label">Pilih Fail CSV</label>
								<input type="file" name="file" class="form-control" accept=".csv">
								<small class="form-hint">Pilih fail CSV yang mengandungi maklumat syarikat</small>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="d-flex align-items-end h-100">
								<button type="submit" class="btn btn-warning w-100 confirm">
									<i class="ti ti-upload me-1"></i>Muat Naik
								</button>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							{{ link_to_route('tenders.template', 'Templat Dokumen (CSV)', $tender->id, ['class' => 'btn btn-success btn-sm']) }}
						</div>
					</div>
					{!! Former::close() !!}
				</div>
			</div>

			@if (Auth::user()->can('Tender:exception'))
				<div class="card mb-3">
					<div class="card-header">
						<h3 class="card-title">
							<i class="ti ti-alert-circle me-2"></i>Kebenaran Khas
						</h3>
					</div>
					<div class="card-body">
						{!! Former::open(url('tenders/' . $tender->id . '/exception'))->class('form-inline') !!}
						<div class="row">
							<div class="col-lg-8">
								<div class="mb-3">
									<label class="form-label">Tambah Kebenaran Khas</label>
									<input type="text" id="exception_id" name="exception_id" class="form-control"
										placeholder="Cari nama syarikat...">
									<small class="form-hint">Cari nama syarikat yang ingin diberikan Kebenaran Khas dan tekan "Simpan"</small>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="d-flex align-items-end h-100">
									<button type="submit" class="btn btn-warning w-100 confirm">
										<i class="ti ti-device-floppy me-1"></i>Simpan
									</button>
								</div>
							</div>
						</div>
						{!! Former::close() !!}
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
