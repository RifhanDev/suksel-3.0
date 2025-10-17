@extends('layouts.default')
@section('content')

	<h2>Kategori Agensi Baru</h2>
	<hr>
	{!! Former::open( url('organizationtypes')) !!}
		@include('organizationtypes.form')
		<div class="well">
			{!! Former::submit('Hantar')->class('btn btn-primary') !!}
			<a href="{{ asset('organizationtypes')}}" class="btn btn-default pull-right">Senarai</a>
		</div>
	{!! Former::close() !!}

@endsection