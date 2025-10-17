@extends('layouts.default')
@section('content')
	<h2>Kemaskini Templat Penolakan</h2>
	<hr>
	{!! Former::open(url('reject-template/'.$template->id)) !!}
		{!! Former::populate($template) !!}
		{!! Former::hidden('_method', 'PUT') !!}
		@include('reject-template.form')
		<div class="well">
			{!! Former::submit('Hantar')->class('btn btn-primary') !!}
			<a href="{{ asset('reject-template') }}" class="btn btn-default pull-right">Senarai Templat Penolakan</a>
		</div>
	{!! Former::close() !!}
@endsection
