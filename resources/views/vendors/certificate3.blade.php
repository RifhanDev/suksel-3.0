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
		<meta name="_token" content="{{csrf_token()}}">
		@yield('meta')
		<title>Pengesahan Pendaftaran Syarikat</title>
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
		<link href="{{ asset('css/application.css') }}" rel="stylesheet">
		<link href="{{ asset('css/receipt.css') }}" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<div id="watermark">
				<p id="bg-text">{{$type}}</p>
			</div>
			<header>
				<div class="logo"><img src="{{ asset('images/header.png') }}"></div>
				<div class="title">Pengesahan Pendaftaran Syarikat</div>
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
				</div>
			
				<br>
			
				<table class="table table-bordered">
					<tr>
						<th class="col-xs-6">No Pendaftaran</th>
						<td>{{ $vendor->registration }}<br>
							@if($vendor->organization_type != 'ROC: SENDIRIAN BERHAD')
								<span>Tarikh Luput SSM : </span>
								@if($vendor->ssm_expiry !== '0000-00-00')
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
							@if($vendor->district_id > 0)
								{{ App\Vendor::$districts[$vendor->district_id] }}
							@else
								Luar Negeri Selangor
							@endif
						</td>
					</tr>
					<tr>
						<th class="col-xs-6">No Rujukan Sijil CIDB</th>
						<td>
							@if($vendor->cidb_ref_no)
								{{$vendor->cidb_ref_no}}
							@else
								<span class="glyphicon glyphicon-remove"></span>
							@endif
						</td>
					</tr>
					<tr>
					<th class="col-xs-6">Tarikh Sahlaku Sijil CIDB</th>
						<td>
							@if($vendor->cidb_start_date && $vendor->cidb_end_date)
								{{ Carbon\Carbon::parse($vendor->cidb_start_date)->format('d M Y') }} - {{ Carbon\Carbon::parse($vendor->cidb_end_date)->format('d M Y') }}
							@else
								<span class="glyphicon glyphicon-remove"></span>
							@endif
						</td>
					</tr>
					<tr>
						<th class="col-xs-6">No Rujukan Pendaftaran MOF</th>
						<td>
							@if($vendor->mof_ref_no)
								{{$vendor->mof_ref_no}}
							@else
								<span class="glyphicon glyphicon-remove"></span>
							@endif
						</td>
					</tr>
					<tr>
						<th class="col-xs-6">Tarikh Sahlaku Pendaftaran MOF</th>
						<td>
							@if($vendor->mof_start_date && $vendor->mof_end_date)
								{{ Carbon\Carbon::parse($vendor->mof_start_date)->format('d M Y') }} - {{ Carbon\Carbon::parse($vendor->mof_end_date)->format('d M Y') }}
							@else
								<span class="glyphicon glyphicon-remove"></span>
							@endif
						</td>
					</tr>
					<tr>
						@if($vendor->cidb_ref_no)
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
