@extends('layouts.default')
@section('content')
	<h2 class="tender-title">Masukan Tetapan Pembayaran</h2>
	
	{!! Former::open(url('gateways')) !!}
		@include('gateways.form')
		<div class="well">
		{!! Former::submit('Hantar')->addClass('btn btn-primary') !!} 
	
		@if(App\Gateway::canList())<a href="{{ asset('gateways')!!}" class="btn btn-default pull-right">Senarai Tetapan Pembayaran</a>@endif
		</div>
	{!! Former::close() !!}
@endsection