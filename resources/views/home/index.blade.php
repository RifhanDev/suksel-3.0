@extends('layouts.modernLanding')

@section('styles')
<style>
    .hero-card {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        border: none;
        background: white;
        height: 100%;
        position: relative;
    }

    .carousel-item img {
        height: 420px;
        object-fit: cover;
        border-radius: 16px;
    }

    .news-card-header {
        background: #fff;
        padding: 1.5rem;
        border-bottom: 2px solid #f3f4f6;
        display: flex;
        align-items: center;
    }

    .news-title-group {
        display: flex;
        flex-direction: column;
        border-left: 4px solid var(--sg-red);
        padding-left: 1rem;
    }

    .news-title {
		padding-top: 5px;
        font-size: 1.1rem;
        font-weight: 800;
        color: #111827;
        margin: 0;
        line-height: 1.1;
    }

    .news-subtitle {
        font-size: 0.7rem;
        color: #6b7280;
        margin-top: 3px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .news-item {
        border-left: 3px solid transparent;
        transition: all 0.2s ease;
        padding: 1rem 1.25rem !important;
        background: transparent;
    }

    .news-item:hover {
        background-color: #fff1f2;
        border-left-color: var(--sg-red);
        padding-left: 1.5rem !important;
    }

    .news-date {
        font-size: 0.7rem;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        display: block;
        margin-top: 4px;
    }
    
    .btn-see-all {
        color: #374151;
        background: white;
        border: 2px solid #e5e7eb;
        font-weight: 700;
        border-radius: 8px;
        padding: 0.6rem 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        display: inline-block;
    }

    .btn-see-all:hover {
        background-color: var(--sg-red);
        border-color: var(--sg-red);
        color: white;
        box-shadow: 0 8px 15px rgba(196, 30, 58, 0.25);
        transform: translateY(-2px);
    }

    /* --- CTA SECTION --- */
    .cta-card {
        background: linear-gradient(135deg, var(--sg-red) 0%, #8b1428 100%);
        border-radius: 16px;
        border: none;
        box-shadow: 0 10px 25px rgba(196, 30, 58, 0.25);
        position: relative;
        overflow: hidden;
        color: white;
    }

    .cta-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: radial-gradient(rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 20px 20px;
        opacity: 0.3;
    }

    .cta-btn-white {
        background: white;
        color: var(--sg-red);
        font-weight: 700;
        border: 2px solid white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }
    .cta-btn-white:hover { background: transparent; color: white; transform: translateY(-2px); }

    .cta-btn-outline {
        background: transparent;
        color: white;
        font-weight: 700;
        border: 2px solid rgba(255,255,255,0.5);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }
    .cta-btn-outline:hover { border-color: white; background: rgba(255,255,255,0.1); color: white; }

    /* --- TENDER TABLE CONTAINER --- */
    .tender-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        overflow: hidden;
        background: white;
    }

    /* --- TENDER HEADER --- */
    .tender-header {
        background: #fff;
        border-bottom: 2px solid #f3f4f6;
        padding: 1.5rem 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    /* Title */
    .header-title-group {
        display: flex;
        flex-direction: column;
        border-left: 4px solid var(--sg-red);
        padding-left: 1rem;
    }

    .header-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #111827;
        margin: 0;
        line-height: 1.1;
    }

    .header-subtitle {
        font-size: 0.8rem;
        color: #6b7280;
        margin-top: 4px;
        font-weight: 500;
    }

    /* Connected Tabs Styling */
    .nav-connected {
        display: inline-flex;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
    }

    .nav-connected .nav-item {
        margin: 0;
        border-right: 1px solid #e5e7eb;
    }
    
    .nav-connected .nav-item:last-child { border-right: none; }

    .nav-connected .nav-link {
        border: none;
        border-radius: 0;
        padding: 0.7rem 1.5rem;
        color: #4b5563;
        font-weight: 600;
        font-size: 0.85rem;
        background: #f9fafb;
        transition: all 0.2s ease;
        position: relative;
    }

    .nav-connected .nav-link:hover {
        background: #f3f4f6;
        color: var(--sg-red);
    }

    .nav-connected .nav-link.active {
        background: var(--sg-red);
        color: white;
        box-shadow: inset 0 -3px 0 rgba(0,0,0,0.1);
    }

    .nav-connected .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--sg-yellow);
    }

    /* Table */
    .table-modern thead th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
        padding: 1.2rem 1rem;
    }
    .table-modern tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
    }
    .table-modern tbody tr:hover { background-color: #fef2f2; }

	.fade.show {
		opacity: 1 !important;
	}

    /* Mobile Tweaks */
    @media (max-width: 768px) {
        .tender-header {
            flex-direction: column;
            align-items: stretch;
            padding: 1.25rem;
        }
        .header-title-group {
            margin-bottom: 1rem;
            border-left-width: 4px;
        }
        .nav-connected {
            display: flex;
            width: 100%;
        }
        .nav-connected .nav-item {
            flex: 1;
            text-align: center;
        }
        .nav-connected .nav-link {
            width: 100%;
            padding: 0.7rem 0.5rem;
            font-size: 0.75rem;
        }
        .carousel-item img { height: 250px; }
        
        /* Mobile News Header */
        .news-card-header { padding: 1.25rem; }
    }
</style>
@endsection

@section('content')
	
    <!-- ALERTS -->
    @if(session('notice'))
		<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
			<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg> 
            {{ session('notice') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	@if(session('error'))
		<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
			<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 8v4" /><path d="M12 16h.01" /></svg>
            {{ session('error') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	<div class="row gy-4 mb-5">
		
        <!-- LEFT: CAROUSEL -->
		<div class="col-lg-9">
			<div class="hero-card">
				<div id="landing-carousel" class="carousel slide" data-bs-ride="carousel">
					<div class="carousel-indicators">
						@foreach (range(0, count($banners) - 1) as $c)
							<button type="button" data-bs-target="#landing-carousel" data-bs-slide-to="{{ $c }}"
								@if ($c == 0) class="active" @endif aria-current="true"
								aria-label="Slide {{ $c + 1 }}"></button>
						@endforeach
					</div>

					<div class="carousel-inner">
						<?php $index = 1; ?>
						@foreach ($banners as $banner)
							@if (\Illuminate\Support\Facades\Schema::hasTable('uploads') && $banner->file)
								<div class="carousel-item @if ($index == 1) active @endif">
									@if ($banner->link)
										<a href="{{ $banner->link }}" title="{{ $banner->title }}" class="d-block">
									@endif
									<img src="{{ $banner->file->url . '/' . $banner->file->name }}" alt="{{ $banner->title }}" class="d-block w-100">
                                    @if ($banner->link)
										</a>
									@endif
								</div>
								<?php $index++; ?>
							@endif
						@endforeach
					</div>

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

		<!-- RIGHT: NEWS SIDEBAR -->
		<div class="col-lg-3">
			<div class="hero-card h-100 d-flex flex-column">

				<div class="news-card-header">
					<div class="news-title-group">
						<h5 class="news-title">Berita Terkini</h5>
                        <p class="news-subtitle">Pengumuman & Makluman</p>
					</div>
				</div>
				
				<div class="card-body p-0 flex-grow-1">
					<div id="announcements-ticker" style="height: 320px; overflow: hidden;">
						<div class="list-group list-group-flush">
							@foreach ($global_news as $news)
								<a href="{{ asset('news/' . $news->id) }}" class="list-group-item list-group-item-action news-item">
									<div class="d-flex w-100 justify-content-between">
										<h6 class="mb-1 fw-bold text-dark" style="font-size: 0.9rem;">{{ Str::limit($news->title, 55) }}</h6>
									</div>
									<span class="news-date">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1" style="vertical-align: -1px;"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
										{{ \Carbon\Carbon::parse($news->published_at ?: $news->created_at)->format('j M Y') }}
									</span>
								</a>
							@endforeach
						</div>
					</div>
				</div>
				
				<div class="p-3 bg-light border-top text-center">
					<a href="/news" class="btn btn-see-all btn-sm w-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
						Lihat Semua Berita
					</a>
				</div>
			</div>
		</div>
	</div>

	<!-- CTA SECTION -->
	@if (!Auth::check())
		<div class="row mb-5">
			<div class="col-12">
				<div class="cta-card">
					<div class="card-body py-4 px-4 px-lg-5">
						<div class="row align-items-center g-4 text-center text-lg-start">
							<div class="col-lg-7 position-relative" style="z-index: 2;">
								<h3 class="fw-bold mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 text-warning" style="vertical-align: -4px;"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M9 8l1 0" /><path d="M9 12l1 0" /><path d="M9 16l1 0" /><path d="M14 8l1 0" /><path d="M14 12l1 0" /><path d="M14 16l1 0" /><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" /></svg>
                                    Daftar Sebagai Vendor Sekarang
                                </h3>
								<p class="mb-0 text-white-50">
                                    Dapatkan akses penuh ke semua tender dan sebut harga terkini kerajaan negeri Selangor.
                                </p>
							</div>

							<div class="col-lg-5 position-relative" style="z-index: 2;">
								<div class="d-flex flex-column flex-sm-row justify-content-center justify-content-lg-end gap-2">
									<a href="{{ asset('register') }}" class="cta-btn-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M16 19h6" /><path d="M19 16v6" /><path d="M6 21v-2a4 4 0 0 1 4 -3.85" /></svg>
										Daftar Syarikat
									</a>
									<a href="{{ asset('company_search') }}" class="cta-btn-outline">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
										Semak Status
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endif

	<!-- TENDER TABLE SECTION -->
	<div class="row mb-5">
		<div class="col-12">
			<div class="tender-card bg-white">
				<div class="tender-header">
					
                    <div class="header-title-group">
						<h3 class="header-title">
							Senarai Tender & Sebut Harga
						</h3>
						<p class="header-subtitle">Paparan 20 item terkini yang sedang aktif</p>
					</div>
                    
                    <!-- TABS -->
                    <ul class="nav nav-connected" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link @if (!Request::get('type')) active @endif" href="{{ action('HomeController@index') }}">
                                Semua
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if (Request::get('type') == 'tenders') active @endif"
                                href="{{ action('HomeController@index', ['type' => 'tenders']) }}">
                                Tender
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if (Request::get('type') == 'quotations') active @endif"
                                href="{{ action('HomeController@index', ['type' => 'quotations']) }}">
                                Sebut Harga
                            </a>
                        </li>
                    </ul>
				</div>

				<div class="card-body p-0">
					<div class="table-responsive p-2">
						<table class="DT2 table table-modern table-hover w-100 mb-0" data-path="{{ $path }}">
							<thead>
								<tr>
									<th class="w-30">No / Tajuk Dokumen</th>
									<th class="w-15">Kod Bidang</th>
									<th class="w-15 text-center">Tarikh Jual</th>
									<th class="w-15 text-center">Tarikh Tutup</th>
									<th class="w-15 text-end">Harga (RM)</th>
								</tr>
							</thead>
							<tbody>
                                <!-- Ajax loads here -->
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
	<script src="{{ asset('js/easy-ticker.js') }}"></script>
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

    <!-- Botman Chatbot Widget -->
	<script>
		@php $chat_id = Str::random(8); @endphp

		var botmanWidget = {
			title: 'Lela (Bot)',
			introMessage: 'Hi, saya Lela. Saya di sini untuk membantu anda dan menjawab persoalan anda.',
			mainColor: '#c32508',
			aboutText: '',
			bubbleBackground: '#c32508',
			headerTextColor: '#fff',
			desktopHeight: 500,
			desktopWidth: 400,
			bubbleAvatarUrl: '{{ asset('images/chatbot.png') }}',
			placeholderText: 'Hantar Pesanan..',
			frameEndpoint: "{{ route('chat_widget',['chat_id' => $chat_id]) }}",
			userId: "{{ $chat_id }}"
		};

		window.addEventListener("message", (event) => {

			// console.log(event);

			if (event.data != "")
			{
				let data = event.data;

				if(data.status == 200)
				{
					let messages = data.messages;

					messages.forEach(row => {

						if (row.text == "DataACK")
						{
							sender_response_detail = row.additionalParameters;

							if (sender_response_detail.sender == "user_chat")
							{
								if (sender_response_detail.type == "image_only")
								{
									botmanChatWidget.say('<img src="' + sender_response_detail.response + '" alt="attach" width="120" height="120">');
								}

								if (sender_response_detail.type == "text_only")
								{
									botmanChatWidget.say(sender_response_detail.response);
								}
							}

							if (sender_response_detail.sender == "bot")
							{
								if (sender_response_detail.type == "image_only")
								{
									botmanChatWidget.sayAsBot('<img src="' + sender_response_detail.response + '" alt="attach" width="120" height="120">');
								}

								if (sender_response_detail.type == "text_only")
								{
									botmanChatWidget.sayAsBot(sender_response_detail.response);
								}
							}
						}
					});
					// botmanChatWidget.sayAsBot('TQ. <img src="https://botman.io/img/logo.png" alt="botsaywhat" width="20" height="20">');
				}
			}
		});
	</script>
	{{-- <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script> --}}
	<script src='{{ asset('packages/botman/build/js/widget.js') }}'></script>
@endsection