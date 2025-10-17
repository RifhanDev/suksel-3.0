@extends('layouts.default')
@section('content')
	<h2>Tambah Kebenaran Baru</h2>
	<hr>
	{!! Former::open( url('permissions')) !!}
	@include('permissions.form')
	<div class="well">
		{!! Former::submit('Hantar')->addClass('btn btn-primary') !!}
		@if(App\Permission::canList())
			<a href="{{ asset('permissions') }}" class="btn btn-default pull-right">Senarai Kebenaran</a>
		@endif
	</div>
	{!! Former::close() !!}
@endsection
