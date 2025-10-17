@extends('layouts.default')
@section('content')
	<h2>Aduan</h2>
	<hr>
	{!! Former::open(url('aduan')) !!}
		@include('complaint.form')
		<div class="well">
			{!! Former::submit('Hantar')->class('btn btn-primary') !!}
		</div>
	{!! Former::close() !!}
@stop