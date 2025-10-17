@extends('layouts.default')
@section('content')
	<h2>Template Penolakan Baru</h2>
	<hr>
	{!! Former::open(url('reject-template')) !!}
		@include('reject-template.form')
		<div class="well">
			{!! Former::submit('Hantar')->class('btn btn-primary') !!}
			<a href="{{ asset('reject-template')}}" class="btn btn-default pull-right">Senarai Templat Penolakan</a>
		</div>
	{!! Former::close() !!}
@stop