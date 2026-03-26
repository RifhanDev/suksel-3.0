@extends('layouts.v3.master')

@section('styles')
	<link href="{{ asset('css/form.css') }}" rel="stylesheet">
	<link href="{{ asset('css/dashboard-cards.css') }}" rel="stylesheet">
@endsection

@section('content')

	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Dashboard Vendor</h3>
			<p class="text-muted small m-0">
				Ringkasan tender / sebut harga, dokumen dibeli, jemputan terhad dan pemulangan semula.
			</p>
		</div>
		<div class="d-flex gap-2">
			<a href="{{ asset('dashboard') }}" class="btn-form btn-form-secondary">
				Maklumat Tender / Sebut Harga
			</a>
			<a href="{{ asset('vendor') }}" class="btn-form btn-form-outline">
				Maklumat Syarikat
			</a>
		</div>
	</div>

	<div class="row g-4">
		<div class="col-lg-9 col-xs-12">
			<div class="stats-card p-0 h-100">
				<div class="stats-card-header border-bottom px-4 pt-3 pb-0">
					<ul class="nav nav-tabs card-header-tabs flex-wrap" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link active text-uppercase small fw-semibold" href="#db-recom" data-bs-toggle="tab" role="tab"
								aria-controls="db-recom" aria-selected="true">
								Anggaran Layak
								<span class="badge bg-secondary ms-1">{{ count($eligibles) }}</span>
							</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link text-uppercase small fw-semibold" href="#db-docs" data-bs-toggle="tab" role="tab"
								aria-controls="db-docs" aria-selected="false">
								Dokumen Dibeli
								<span class="badge bg-secondary ms-1">{{ count($purchases) }}</span>
							</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link text-uppercase small fw-semibold" href="#db-invites" data-bs-toggle="tab" role="tab"
								aria-controls="db-invites" aria-selected="false">
								Tender Terhad
								<span class="badge bg-secondary ms-1">{{ count($invites) }}</span>
							</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link text-uppercase small fw-semibold" href="#db-refund" data-bs-toggle="tab" role="tab"
								aria-controls="db-refund" aria-selected="false">
								Pemulangan Semula
							</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link text-uppercase small fw-semibold" href="#db-penilaian-prestasi" data-bs-toggle="tab"
								role="tab" aria-controls="db-penilaian-prestasi" aria-selected="false">
								Penilaian Prestasi
							</a>
						</li>
					</ul>
				</div>

				<div class="stats-card-body p-4">
					<div class="tab-content">
						<div class="tab-pane active" id="db-recom">
							@if (count($eligibles) > 0)
								<div class="table-responsive">
									<table class="DT2 table table-hover table-bordered align-middle mb-0">
										<thead class="bg-light">
											<tr>
												<th class="text-uppercase text-muted small fw-bold">Tender / Sebut Harga</th>
												<th class="text-uppercase text-muted small fw-bold" style="width: 180px;">Tarikh Tutup</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($eligibles as $tender)
												<tr>
													<td>
														<div class="fw-semibold">{{ $tender->tenderer->name }}</div>
														<div class="small text-muted">
															<strong>{{ $tender->ref_number }}</strong>
														</div>
														<a href="{{ asset('tenders/' . $tender->id) }}" class="text-primary">
															{{ $tender->name }}
														</a>
													</td>
													<td class="text-nowrap">
														{{ \Carbon\Carbon::parse($tender->submission_datetime)->format('j M Y') }}
														<span class="text-muted small d-block">12:00 PM</span>
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							@else
								<div class="alert alert-info mb-0">Tiada tender yang layak buat masa ini.</div>
							@endif
						</div>

						<div class="tab-pane" id="db-docs">
							@if (count($purchases) > 0)
								<div class="table-responsive">
									<table class="DT3 table table-hover table-bordered align-middle mb-0">
										<thead class="bg-light">
											<tr>
												<th class="text-uppercase text-muted small fw-bold">Tender / Sebut Harga</th>
												<th class="text-uppercase text-muted small fw-bold" style="width: 180px;">Tarikh Tutup</th>
												<th class="text-uppercase text-muted small fw-bold" style="width: 220px;">Tindakan</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($purchases as $purchase)
												<tr>
													<td>
														<div class="fw-semibold">{{ $purchase->tender->tenderer->name }}</div>
														<div class="small text-muted">
															<strong>{{ $purchase->tender->ref_number }}</strong>
														</div>
														<a href="{{ asset('tenders/' . $purchase->tender->id) }}" class="text-primary">
															{{ $purchase->tender->name }}
														</a>
													</td>
													<td class="text-nowrap">
														{{ \Carbon\Carbon::parse($purchase->tender->submission_datetime)->format('j M Y') }}
														<span class="text-muted small d-block">12:00 PM</span>
													</td>
													<td>
														<div class="d-flex flex-column gap-1">
															<a href="{{ asset('tenders/' . $purchase->tender_id . '/receipt/' . $purchase->id) }}" target="_blank"
																class="btn btn-xs btn-outline-primary text-start">
																<i class="icon-printer"></i> Resit
															</a>
															<a href="{{ asset('tenders/' . $purchase->tender_id . '/document/' . $purchase->id) }}" target="_blank"
																class="btn btn-xs btn-outline-secondary text-start">
																<i class="icon-doc"></i> No. Siri Dokumen
															</a>
															<a href="{{ asset('tenders/' . $purchase->tender_id) }}#tf-doc2" target="_blank"
																class="btn btn-xs btn-outline-info text-start">
																<i class="icon-list"></i> Muat Turun
															</a>
														</div>
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							@else
								<div class="alert alert-info mb-0">Tiada dokumen yang dibeli.</div>
							@endif
						</div>

						<div class="tab-pane" id="db-invites">
							@if (count($invites) > 0)
								<div class="table-responsive">
									<table class="DT2 table table-hover table-bordered align-middle mb-0">
										<thead class="bg-light">
											<tr>
												<th class="text-uppercase text-muted small fw-bold">Tender / Sebut Harga</th>
												<th class="text-uppercase text-muted small fw-bold" style="width: 180px;">Tarikh Tutup</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($invites as $invite)
												<tr>
													<td>
														<div class="fw-semibold">{{ $invite->tender->tenderer->name }}</div>
														<div class="small text-muted">
															<strong>{{ $invite->tender->ref_number }}</strong>
														</div>
														<a href="{{ asset('tenders/' . $invite->tender->id) }}" class="text-primary">
															{{ $invite->tender->name }}
														</a>
													</td>
													<td class="text-nowrap">
														{{ \Carbon\Carbon::parse($invite->tender->submission_datetime)->format('j M Y') }}
														<span class="text-muted small d-block">12:00 PM</span>
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							@else
								<div class="alert alert-info mb-0">Tiada jemputan tender.</div>
							@endif
						</div>

						<div class="tab-pane" id="db-refund">
							<div class="mb-3">
								<div class="alert alert-info mb-3">
									<h5 class="fw-bold mb-2">Arahan / Makluman Berkaitan</h5>
									<ol class="mb-0 small">
										<li>Muat turun 'Templat Surat Permohonan' yang disediakan.</li>
										<li>Sila <b>tukar</b> kandungan dokumen tersebut yang berwarna
											<span style="color: red;">merah</span> dengan maklumat pemohon dan <b>hitamkan</b> semula.
										</li>
										<li>Selepas permohonan diluluskan oleh BPM,
											<span style="text-decoration: underline">
												semua penyata, resit, surat dan borang yang lengkap wajib perlu dicetak dan dihantar secara
												pos / fizikal
											</span>
											ke:
											<br>
											<b>Bahagian Khidmat Pengurusan,<br>Unit Kewangan, Tingkat 17,<br>Bangunan Sultan Salahuddin Abdul
												Aziz Shah,<br>40503 Shah Alam, Selangor Darul Ehsan</b>
										</li>
									</ol>
								</div>
								<div class="d-flex flex-wrap gap-2 mb-3">
									<a href="{{ route('refunds.create') }}" class="btn-form btn-form-create">
										Permohonan Baru
									</a>
									<a download href="{{ asset('file/Template Surat Permohonan Pelanggan 2022.docx') }}"
										class="btn-form btn-form-secondary">
										Templat Surat Permohonan
									</a>
								</div>
							</div>

							<div class="table-responsive">
								<table class="DT4 table table-hover table-bordered align-middle mb-0">
									<thead class="bg-light">
										<tr>
											<th class="text-uppercase text-muted small fw-bold">No Rujukan</th>
											<th class="text-uppercase text-muted small fw-bold">Tarikh Dimohon</th>
											<th class="text-uppercase text-muted small fw-bold">No Resit</th>
											<th class="text-uppercase text-muted small fw-bold">Tarikh Dikemaskini</th>
											<th class="text-uppercase text-muted small fw-bold">Status</th>
											<th class="text-uppercase text-muted small fw-bold">Amaun</th>
											<th class="text-uppercase text-muted small fw-bold text-center" style="width: 120px;">Tindakan</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($refunds as $refund)
											<tr>
												<td>{{ $refund->ref_num }}</td>
												<td>{{ date('d-m-Y', strtotime($refund->created_at)) }}</td>
												<td>{{ $refund->receipt }}</td>
												<td>{{ date('d-m-Y', strtotime($refund->updated_at)) }}</td>
												<td>{{ $refund->status }}</td>
												<td>{{ $refund->amount }}</td>
												<td class="text-center">
													<a href="{{ route('refunds.show', $refund->id) }}" class="btn btn-xs btn-primary">
														Papar
													</a>
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>

						{{-- Content Tab - Penilaian Prestasi Syarikat --}}
						<div class="tab-pane" id="db-penilaian-prestasi">
							@include('home.tab-contents.penilaian-prestasi')
						</div>
					</div>
				</div>
			</div>
		</div>

        <div class="col-lg-3 col-xs-12">
            @include('layouts._news')
        </div>
    </div>

@endsection

@section('scripts')
	{{-- DataTables 2.x is already loaded in layouts/v3/master.blade.php - do not load datatables.js (1.x) --}}
	<script src="{{ asset('js/easy-ticker.js') }}"></script>
	<script src="{{ asset('js/news.js') }}"></script>
	<script>
		$(function() {
			$('.DT2').DataTable({
				order: [
					[1, 'asc']
				]
			});
			$('.DT3').DataTable({
				order: [
					[1, 'desc']
				]
			});
			$('.DT4').DataTable({
				order: [
					[1, 'desc']
				]
			});
		});
	</script>
@endsection
