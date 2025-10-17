@extends('layouts.default')
@section('content')

	<h2>Kemaskini Agensi</h2>
	<hr>
	{!! Former::open(route('agencies.update', $organizationunit->id)) !!}
	{!! Former::populate($organizationunit) !!}
	{!! Former::hidden('_method', 'PUT') !!}
	@include('organizationunits.form')
	<div class="well">
		<input type="submit" value="Simpan" class="btn btn-primary confirm">
		<a href="{{ asset('agencies/'.$organizationunit->id) }}" class="btn btn-default pull-right">Lihat Agensi</a>
	</div>
	{!! Former::close() !!}

@endsection
