<!DOCTYPE html>
	<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
	<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
	<!--[if !IE]><!--><html lang="en"><!--<![endif]-->
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<meta http-equiv="X-Accel-Buffering" content="no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="_token" content="{{csrf_token()}}">
		@yield('meta')
		<title>Laporan Sistem Tender Online Selangor</title>
		<link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
		<link href="{{ asset('css/application.css') }}" rel="stylesheet">
		<link href="{{ asset('css/report.css') }}" rel="stylesheet">
		@yield('styles')
	</head>
	<body>
		@yield('content')

		<section id="footer">
		<p id="copyright" class="pull-left">&copy;{{ date('Y') }} Sistem Tender Online Selangor.</p>
		<p id="service" class="pull-right">Tarikh Dijana: {{ date('d M Y H:i:s') }}</p>
		</section>
				
		<script src="{{ asset('js/application.js') }}"></script>
		@yield('scripts')
		@if(App::environment('local'))
		<script type="text/javascript">document.write('<script src="' + (location.protocol || 'http:') + '//' + (location.hostname || 'localhost') + ':35729/livereload.js?snipver=1" type="text/javascript"><\/script>')</script>
		@endif
	</body>
	</html>
