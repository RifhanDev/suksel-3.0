@extends('layouts.default')
@section('content')
	<h2 class="tender-title">Kemaskini Tetapan Pembayaran</h2>
	
	{!! Former::open(url('gateways/'.$gateway->id)) !!}
		{!! Former::hidden('_method', 'PUT') !!}
		@include('gateways.form')
		<div class="well">
		{!! Former::submit('Simpan')->addClass('btn btn-primary confirm') !!} 
	{!! Former::close() !!}
		
	@if($gateway->canDelete())
	{!! Former::open( url('gateways/'.$gateway->id))->class('form-inline') !!} 
		{!! Former::hidden('_method', 'DELETE') !!} 
		<button type="button" class="btn btn-danger confirm-delete">Padam</button>
	{!! Former::close() !!}
	@endif
	
	@if(App\Gateway::canList())
		<a href="{{ asset('gateways') }}" class="btn btn-default pull-right">Senarai Tetapan Pembayaran</a>
	@endif
	</div>
@endsection
