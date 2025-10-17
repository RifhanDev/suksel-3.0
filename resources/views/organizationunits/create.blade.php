@extends('layouts.default')
@section('content')
	<h2>Masukkan Agensi Baru</h2>
	<hr>
	{!! Former::open(url('agencies')) !!}
		@include('organizationunits.form')
		<div class="well">
			<input type="submit" value="Hantar" class="btn btn-primary confirm">
			<a href="{{ asset('agencies') }}" class="btn btn-default pull-right">Senarai Agensi</a>
		</div>
	{!! Former::close() !!}
@endsection
