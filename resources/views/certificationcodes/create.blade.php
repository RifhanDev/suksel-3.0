@extends('layouts.default')
@section('content')
	<h2>Kod Bidang Baru</h2>
	<hr>
	{!! Former::open(url('codes')) !!}
		@include('certificationcodes.form')
		<div class="well">
			{!! Former::submit('Hantar')->class('btn btn-primary') !!}
			<a href="{{ asset('codes')}}" class="btn btn-default pull-right">Senarai Kod Bidang</a>
		</div>
	{!! Former::close() !!}
@stop