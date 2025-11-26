@extends('layouts.modernLanding')

@section('content')
	<div class="row gy-4">
		<!-- Main carousel section -->
		<div class="col-lg-9">
			<!-- Enhanced carousel card with better spacing and styling -->
			<div class="card overflow-hidden border-0 shadow-sm">
				<div id="landing-carousel" class="carousel slide" data-bs-ride="carousel">
					<!-- Carousel indicators -->
					<div class="carousel-indicators">
						@foreach (range(0, count($banners) - 1) as $c)
							<button type="button" data-bs-target="#landing-carousel" data-bs-slide-to="{{ $c }}"
								@if ($c == 0) class="active" @endif aria-current="true"
								aria-label="Slide {{ $c + 1 }}"></button>
						@endforeach
					</div>

					<!-- Carousel slides -->
					<div class="carousel-inner">
						<?php $index = 1; ?>
						@foreach ($banners as $banner)
							@if (\Illuminate\Support\Facades\Schema::hasTable('uploads') && $banner->file)
								<div class="carousel-item @if ($index == 1) active @endif">
									@if ($banner->link)
										<a href="{{ $banner->link }}" title="{{ $banner->title }}" class="d-block">
									@endif
									<img src="{{ $banner->file->url . '/' . $banner->file->name }}" alt="{{ $banner->title }}"
										class="d-block w-100" style="height: 400px; object-fit: cover;">
									@if ($banner->link)
										</a>
									@endif
								</div>
								<?php $index++; ?>
							@endif
						@endforeach
					</div>

					<!-- Carousel controls -->
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

		<!-- Latest news sidebar -->
		<div class="col-lg-3">
			<!-- Enhanced news card with better styling and spacing -->
			<div class="card border-0 shadow-sm h-100">
				<div class="card-header bg-white border-bottom">
					<h3 class="card-title mb-0">
						<i class="ti ti-newspaper text-danger"></i> Berita Terkini
					</h3>
				</div>
				<div class="card-body p-0">
					<div id="announcements-ticker">
						<div class="list-group list-group-flush">
							@foreach ($global_news as $news)
								<a href="{{ asset('news/' . $news->id) }}" class="list-group-item list-group-item-action px-4 py-3">
									<h6 class="mb-2 fw-600">{{ $news->title }}</h6>
									<small class="text-muted">
										{{ \Carbon\Carbon::parse($news->published_at ?: $news->created_at)->format('j M Y') }}
									</small>
								</a>
							@endforeach
						</div>
					</div>
				</div>
				<div class="card-footer bg-white border-top">
					<a href="/news" class="btn btn-primary btn-sm w-100">
						<i class="ti ti-eye"></i> Lihat Semua Berita
					</a>
				</div>
			</div>
		</div>
	</div>

	<!-- Call to action section -->
	@if (!Auth::check())
		<div class="row mt-4">
			<div class="col-12">
				<!-- Improved CTA card with better visual hierarchy -->
				<div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);">
					<div class="card-body py-4">
						<div class="row align-items-center g-3">
							<div class="col-lg-7">
								<h4 class="mb-2 fw-bold text-danger">📋 Daftar Sebagai Penender Sekarang</h4>
								<p class="text-muted mb-0">Dapatkan akses ke semua tender dan sebut harga terkini di negeri Selangor. Proses pendaftaran mudah, cepat, dan selamat.</p>
							</div>
							<div class="col-lg-5 text-lg-end">
								<a href="{{ asset('register') }}" class="btn btn-danger me-2 mb-2 mb-lg-0">
									<i class="ti ti-user-plus"></i> Daftar Syarikat
								</a>
								<a href="{{ asset('company_search') }}" class="btn btn-outline-danger">
									<i class="ti ti-search"></i> Semak Pendaftaran
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endif

	<!-- Tender & Quotations section -->
	<div class="row mt-4">
		<div class="col-12">
			<!-- Enhanced tender section with better card styling -->
			<div class="card border-0 shadow-sm">
				<div class="card-header bg-white border-bottom">
					<div class="row align-items-center">
						<div class="col">
							<h3 class="card-title mb-0">
								<i class="ti ti-files-check text-danger"></i> Tender &amp; Sebut Harga
							</h3>
						</div>
						<div class="col-auto">
							<small class="text-muted">
								<i class="ti ti-info-circle"></i> Paparan 20 item terbaru
							</small>
						</div>
					</div>
				</div>
				<div class="card-body">
					<!-- Filter Tabs -->
					<ul class="nav nav-tabs nav-fill mb-4 border-bottom" role="tablist">
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
								<i class="ti ti-receipt"></i> Sebut Harga
							</a>
						</li>
					</ul>

					<!-- Data Table -->
					<div class="table-responsive">
						<table class="DT2 table table-hover card-table" data-path="{{ $path }}">
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

		// News ticker animation
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