@extends('layouts.default')
@section('content')
	<h2>Kemaskini Transaksi</h2>
	<hr>
	{!! Former::open(url('transactions/'.$transaction->id)) !!}
	{!! Former::populate($transaction) !!}
	{!! Former::hidden('_method', 'PUT') !!}
	@include('transactions.form')
	
	<div class="well">
		<input type="submit" class="btn btn-primary" value="Kemaskini">
		
		<a href="{{ asset('transactions') }}" class="btn btn-default pull-right">Senarai Transaksi</a>
	</div>
	{!! Former::close() !!}
@endsection

@section('scripts')
	<script src="{{ asset('js/application.js') }}"></script>
@endsection