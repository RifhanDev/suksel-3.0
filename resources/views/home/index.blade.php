@extends('layouts.default')

@section('content')
	<div class="row">
		<div class="col-lg-9">
			<div class="card">
				<div class="card-body p-0">
					<div id="landing-carousel" class="carousel slide" data-bs-ride="carousel">
						<!-- Indicators -->
						<div class="carousel-indicators">
							@foreach (range(0, count($banners) - 1) as $c)
								<button type="button" data-bs-target="#landing-carousel" data-bs-slide-to="{{ $c }}"
									@if ($c == 0) class="active" @endif aria-current="true"
									aria-label="Slide {{ $c + 1 }}"></button>
							@endforeach
						</div>

						<!-- Wrapper for slides -->
						<div class="carousel-inner">
							<?php $index = 1; ?>
							@foreach ($banners as $banner)
								@if (\Illuminate\Support\Facades\Schema::hasTable('uploads') && $banner->file)
									<div class="carousel-item @if ($index == 1) active @endif">
										@if ($banner->link)
											<a href="{{ $banner->link }}" title="{{ $banner->title }}">
										@endif
										<img src="{{ $banner->file->url . '/' . $banner->file->name }}" alt="{{ $banner->title }}"
											class="d-block w-100" style="height: 300px; object-fit: cover;">
										@if ($banner->link)
											</a>
										@endif
									</div>
									<?php $index++; ?>
								@endif
							@endforeach
						</div>

						<!-- Controls -->
						<button class="carousel-control-prev" type="button" data-bs-target="#landing-carousel" data-bs-slide="prev">
							<span class="carousel-control-prev-icon" aria-hidden="true"></span>
							<span class="visually-hidden">Previous</span>
						</button>
						<button class="carousel-control-next" type="button" data-bs-target="#landing-carousel" data-bs-slide="next">
							<span class="carousel-control-next-icon" aria-hidden="true"></span>
							<span class="visually-hidden">Next</span>
						</button>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-3">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">
						<i class="ti ti-news"></i> Berita Terkini
					</h3>
				</div>
				<div class="card-body">
					<div id="announcements-ticker">
						<div class="list-group list-group-flush">
							@foreach ($global_news as $news)
								<div class="list-group-item px-0">
									<div class="row align-items-center">
										<div class="col">
											<a href="{{ asset('news/' . $news->id) }}" class="text-decoration-none">
												{{ $news->title }}
											</a>
											<div class="text-muted small">
												{{ \Carbon\Carbon::parse($news->published_at ?: $news->created_at)->format('j M Y') }}
											</div>
										</div>
									</div>
								</div>
							@endforeach
						</div>
					</div>
					<div class="mt-3">
						<a href="/news" class="btn btn-primary btn-sm w-100">
							<i class="ti ti-eye"></i> Lihat Semua
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	@if (!Auth::check())
		<div class="row mt-4">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<div class="row align-items-center">
							<div class="col-lg-8">
								<h4 class="mb-0">Dapatkan maklumat tender dan sebut harga terkini di negeri Selangor!</h4>
							</div>
							<div class="col-lg-4 text-end">
								<a href="{{ asset('register') }}" class="btn btn-danger me-2">
									<i class="ti ti-user-plus"></i> Daftar Syarikat
								</a>
								<a href="{{ asset('company_search') }}" class="btn btn-primary">
									<i class="ti ti-search"></i> Semak Pendaftaran Syarikat
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	@else
		<hr class="my-4">
	@endif

	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">
						<i class="ti ti-files"></i> Tender &amp; Sebut Harga
					</h3>
				</div>
				<div class="card-body">
					<!-- Filter Tabs -->
					<ul class="nav nav-tabs nav-fill mb-4" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link @if (!Request::get('type')) active @endif" href="{{ action('HomeController@index') }}">
								<i class="ti ti-list"></i> Semua
							</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link @if (Request::get('type') == 'tenders') active @endif"
								href="{{ action('HomeController@index', ['type' => 'tenders']) }}">
								<i class="ti ti-file-text"></i> Tender
							</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link @if (Request::get('type') == 'quotations') active @endif"
								href="{{ action('HomeController@index', ['type' => 'quotations']) }}">
								<i class="ti ti-file-invoice"></i> Sebut Harga
							</a>
						</li>
					</ul>

					<!-- Data Table -->
					<div class="table-responsive">
						<table class="DT2 table table-vcenter table-mobile-md card-table" data-path="{{ $path }}">
							<thead>
								<tr>
									<th class="w-25">No / Tajuk</th>
									<th class="w-20">Kod Bidang</th>
									<th class="w-20">Tarikh Jual</th>
									<th class="w-20">Tarikh Tutup</th>
									<th class="w-15">Harga Dokumen</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/datatables.js') }}"></script>
	<script type="text/javascript">
		$('.DT2').each(function() {
			var target = $(this);
			var path = target.data('path');
			var DT = target.DataTable({
				ajax: path,
				columns: [{
						data: 'name',
						name: 'name'
					},
					{
						data: 'codes',
						name: 'codes'
					},
					{
						data: 'document_start_date',
						name: 'document_start_date'
					},
					{
						data: 'submission_datetime',
						name: 'submission_datetime'
					},
					{
						data: 'price',
						name: 'price'
					}
				],
				stateSave: true,
				language: {
					sEmptyTable: "Tiada data",
					sInfo: "Paparan dari _START_ hingga _END_ dari _TOTAL_ rekod",
					sInfoEmpty: "Paparan 0 hingga 0 dari 0 rekod",
					sInfoFiltered: "(Ditapis dari jumlah _MAX_ rekod)",
					sInfoPostFix: "",
					sInfoThousands: ",",
					sLengthMenu: "Papar _MENU_ rekod",
					sLoadingRecords: "Diproses...",
					sProcessing: "Sedang diproses...",
					sSearch: "Carian:",
					sZeroRecords: "Tiada padanan rekod yang dijumpai.",
					oPaginate: {
						sFirst: "Pertama",
						sPrevious: "Sebelum",
						sNext: "Kemudian",
						sLast: "Akhir"
					},
					oAria: {
						sSortAscending: ": diaktifkan kepada susunan lajur menaik",
						sSortDescending: ": diaktifkan kepada susunan lajur menurun"
					}
				},
				aaSorting: []
			});
		});


		$('#announcements-ticker').easyTicker({
			direction: 'up',
			easing: 'swing',
			speed: 'slow',
			interval: 2000,
			height: '200px',
			visible: 4,
			mousePause: 1,
			controls: {
				up: '',
				down: '',
				toggle: '',
				playText: 'Play',
				stopText: 'Stop'
			}
		});
	</script>
@endsection
