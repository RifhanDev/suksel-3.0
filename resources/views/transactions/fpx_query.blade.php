<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--><html lang="en"><!--<![endif]-->
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="_token" content="{{csrf_token()}}">
@yield('meta')
<title>Sistem Tender Online - FPX Requery</title>
	<link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
	<link href="{{ asset('css/application.css') }}" rel="stylesheet">
	<link href="{{ asset('css/report.css') }}" rel="stylesheet">
@yield('styles')
</head>
<body>

@if(isset($ac_responses) && count($ac_responses) > 0)
<h4>FPX Requery</h4>

<table class="table table-bordered">
	<tr>
		<th>Transaction No.</th>
		<td>{{ $transaction->number }}</td>
	</tr>
	<tr>
		<th>Status</th>
		<td>{{ App\Transaction::$statuses[$transaction->status] }}</td>
	</tr>
</table>

<h5>AC Response</h5>
<table class="table table-bordered table-striped">
	@foreach($ac_responses as $key => $value)
	<tr>
		<td class="col-xs-6">{{ $key }}</td>
		<td>{{ $value }}</td>
	</tr>
	@endforeach
</table>

<h5>AE Request</h5>
<table class="table table-bordered table-striped">
	@foreach($ae_requests as $key => $value)
	<tr>
		<td class="col-xs-6">{{ $key }}</td>
		<td>{{ $value }}</td>
	</tr>
	@endforeach
</table>
@endif
@if(isset($error))<div class="alert alert-danger">{{ $error }}</div>@endif

<section id="footer"><p id="copyright" class="pull-left">&copy;{{ date('Y') }} Sistem Tender Online Selangor.</p></section>
        
<!--[if lt IE 9]><script src="/assets/javascripts/ie.min.js"></script><![endif]-->
{{App\Libraries\Asset::tags('js')}}
@yield('scripts')
@if(App::environment('local'))
<script type="text/javascript">document.write('<script src="' + (location.protocol || 'http:') + '//' + (location.hostname || 'localhost') + ':35729/livereload.js?snipver=1" type="text/javascript"><\/script>')</script>
@endif
</body>
</html>
