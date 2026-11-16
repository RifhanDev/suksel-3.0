<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en"><!--<![endif]-->

<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="_token" content="{{ csrf_token() }}">
	@yield('meta')
	<title>Pengesahan Pendaftaran Syarikat</title>
	@if (empty($isPdf))
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
		type="text/css">
	<link href="{{ asset('css/application.css') }}" rel="stylesheet">
	<link href="{{ asset('css/receipt.css') }}" rel="stylesheet">
	@endif
	<style>
		@if (!empty($isPdf))
			@page { size: A4 portrait; margin: 0; }
			html, body { box-sizing: border-box; margin: 0; padding: 0; font-family: "Open Sans", "DejaVu Sans", sans-serif; font-size: 9.75pt; line-height: 1.25; }
			body { padding: 12mm 14mm 8mm; }
			.container { position: relative; width: 100%; margin: 0; padding: 0; border: 0; overflow: hidden; }
			header { position: relative; z-index: 2; padding: 8px 16px; text-align: center; }
			header .logo { height: auto; display: block; }
			header .logo img { display: block; width: 100%; height: auto; max-width: 100%; margin: 0 auto; }
			header .title { font-size: 13.5pt; font-weight: bold; text-transform: uppercase; margin-top: 2px; }
			section.body { position: relative; z-index: 2; margin: 0; padding: 10px 16px; line-height: 1.25; }
			.text-center { text-align: center; }
			h1 { font-size: 22pt; margin: 5px 0 2px; }
			h1 small { font-size: 9.75pt; font-weight: normal; }
			h3 { font-size: 16pt; margin: 4px 0; }
			h4 { font-size: 12pt; margin: 3px 0; }
			section.body > .text-center > br { display: none; }
			section.body > .text-center > h1 + br,
			section.body > .text-center > h3 + br,
			section.body > .text-center > h4 + br { display: block; }
			table.table { width: 100%; border-collapse: collapse; border-spacing: 0; margin: 7px 0 0; font-size: 9.25pt; }
			table.table th, table.table td { border: 1px solid #ddd; padding: 3px 5px; vertical-align: top; line-height: 1.1; }
			table.table th { text-align: left; width: 42%; }
			table.table ul { margin: 0; padding-left: 15px; }
			table.table li { margin: 0; }
			footer { position: relative; z-index: 2; padding: 8px 16px; font-size: 7pt; color: #5c001f; text-align: center; }
			#watermark { position: fixed; top: 35%; left: 0; z-index: 0; width: 100%; text-align: center; }
			#watermark .watermark-grid { display: none; }
			#bg-text { display: none; }
		@else
		.container {
			position: relative;
			overflow: hidden;
		}

		#watermark {
			position: fixed;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			width: 100%;
			height: 100%;
			z-index: 0;
			pointer-events: none;
		}

		#watermark .watermark-grid {
			position: absolute;
			top: -20%;
			left: -20%;
			width: 140%;
			height: 140%;
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
			grid-auto-rows: 180px;
			justify-items: center;
			align-items: center;
			gap: 32px;
			transform: rotate(-20deg);
			transform-origin: center;
			opacity: 0.1;
			-webkit-print-color-adjust: exact;
			print-color-adjust: exact;
		}

		#watermark .watermark-grid span {
			display: flex;
			align-items: center;
			justify-content: center;
		}

		#watermark .watermark-grid span img {
			width: 160px;
			max-width: 100%;
			opacity: 0.9;
			filter: grayscale(10%);
			-webkit-print-color-adjust: exact;
			print-color-adjust: exact;
		}

		#bg-text {
			position: relative;
			z-index: 1;
		}
		@endif
	</style>
	@if (!empty($isPdf))
	<style>
		/* Keep the same certificate content and typography hierarchy as the iframe, sized for one A4 page. */
		.container, header, section.body, footer { box-sizing: border-box; }
		#watermark .watermark-grid { display: none; }
	</style>
	@endif
	<style>
		header,
		section.body,
		footer {
			position: relative;
			z-index: 2;
		}

		@media print {
			#watermark .watermark-grid {
				opacity: 0.12 !important;
			}

			#watermark .watermark-grid span img {
				opacity: 0.9 !important;
			}
		}
	</style>
</head>

<body>
	<div class="container">
		<div id="watermark">
			<div class="watermark-grid">
				@if (empty($isPdf))
					@for ($i = 0; $i < 36; $i++)
						<span><img src="{{ asset('images/02_selangor.png') }}" alt="Selangor watermark"></span>
					@endfor
				@endif
			</div>
			<p id="bg-text">{{ $type }}</p>
		</div>
		<header>
			<div class="logo"><img src="{{ !empty($isPdf) ? 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/header.png'))) : asset('images/header.png') }}"></div>
			<div class="title" style="margin-top: 30px;">Pengesahan Pendaftaran Syarikat</div>
			<div class="clearfix"></div>
		</header>

		<section class="body">
			<div class="text-center">
				Dengan Ini Disahkan Bahawa

				<br><br>

				<h1><strong>{{ $vendor->name }}</strong><br>

					<small>No. Pendaftaran: {{ $vendor->registration }}</small>
				</h1>

				<br><br>

				telah berdaftar dengan

				<br><br>

				<h3>Unit Perancang Ekonomi (UPEN)<br>
					Selangor</h3>

				<br><br>

				pada

				<br><br>

				<h4>{{ \Carbon\Carbon::parse($vendor->approval_date)->format('d/m/Y') }}</h4>

				<br>
				<h4>Tarikh Tamat Langganan :
					<span class=>{{ \Carbon\Carbon::parse($vendor->expiry_date)->format('d/m/Y') }}</span>
				</h4>
				<h3>Status Sijil :
					<span class=>{{ $vendor->status }}</span>

				</h3>





			</div>

			<br>

			<table class="table table-bordered">
				<tr>
					<th class="col-xs-6">No Pendaftaran</th>
					<td>{{ $vendor->registration }}<br>
						@if ($vendor->organization_type != 'ROC: SENDIRIAN BERHAD')
							<span>Tarikh Luput SSM : </span>
							@if ($vendor->ssm_expiry !== '0000-00-00')
								{{ Carbon\Carbon::parse($vendor->ssm_expiry)->format('d M Y') }}
							@else
								Tiada Maklumat
							@endif
						@endif
					</td>
				</tr>
				<tr>
					<th>Daerah</th>
					<td>
						@if ($vendor->district_id > 0)
							{{ App\Vendor::$districts[$vendor->district_id] }}
						@else
							Luar Negeri Selangor
						@endif
					</td>
				</tr>
				<tr>
					<th class="col-xs-6">No Rujukan Sijil CIDB</th>
					<td>
						@if ($vendor->cidb_ref_no)
							{{ $vendor->cidb_ref_no }}
						@else
							<span class="glyphicon glyphicon-remove"></span>
						@endif
					</td>
				</tr>
				<tr>
					<th class="col-xs-6">Tarikh Sahlaku Sijil CIDB</th>
					<td>
						@if ($vendor->cidb_start_date && $vendor->cidb_end_date)
							{{ Carbon\Carbon::parse($vendor->cidb_start_date)->format('d M Y') }} -
							{{ Carbon\Carbon::parse($vendor->cidb_end_date)->format('d M Y') }}
						@else
							<span class="glyphicon glyphicon-remove"></span>
						@endif
					</td>
				</tr>
				<tr>
					<th class="col-xs-6">No Rujukan Pendaftaran MOF</th>
					<td>
						@if ($vendor->mof_ref_no)
							{{ $vendor->mof_ref_no }}
						@else
							<span class="glyphicon glyphicon-remove"></span>
						@endif
					</td>
				</tr>
				<tr>
					<th class="col-xs-6">Tarikh Sahlaku Pendaftaran MOF</th>
					<td>
						@if ($vendor->mof_start_date && $vendor->mof_end_date)
							{{ Carbon\Carbon::parse($vendor->mof_start_date)->format('d M Y') }} -
							{{ Carbon\Carbon::parse($vendor->mof_end_date)->format('d M Y') }}
						@else
							<span class="glyphicon glyphicon-remove"></span>
						@endif
					</td>
				</tr>
				<tr>
					@if ($vendor->cidb_ref_no)
						<th>Gred</th>
						<td>
							<ul>
								@forelse($vendor->cidbGrades()->orderBy('id')->get() as $grade)
									<li><b>{{ $grade->code->label }}</b></li>
									<small>Jumlah Bidang Pengkhususan: {{ count($grade->children) }}</small><br>
								@empty
									<span class="glyphicon glyphicon-remove"></span>
								@endforelse
							</ul>
						</td>
					@elseif($vendor->mof_ref_no)
						<th>Kelas</th>
						<td>MOF</td>
					@else
						<span class="glyphicon glyphicon-remove"></span>
					@endif
				</tr>
				<tr>
					<th>Kod Pengesahan Sijil</th>
					<td>{{ $vendor->token }}</td>
				</tr>
			</table>
		</section>
		<footer class="text-center">
			Maklumat dihasilkan oleh komputer. Tiada tandatangan diperlukan.<br>
			Tarikh &amp; Waktu Janaan: {{ date('d/m/Y H:i:s') }}
		</footer>
		<!-- </div> -->
		{{-- <br><a style="text-decoration: none;" class="hidden-print pull-right" href="javascript:window:print()"><span
			class="glyphicon glyphicon-print"></span> Cetak</a> --}}
	</div>
</body>

</html>
