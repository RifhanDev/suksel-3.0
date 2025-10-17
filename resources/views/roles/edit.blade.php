@extends('layouts.default')
@section('content')
	<h2>Kemaskini Peranan</h2>
	<hr>
	{!! Former::open(url('roles/'.$role->id)) !!}
		{!! Former::populate($role) !!}
		{!! Former::hidden('_method', 'PUT') !!}
		@include('roles.form')
		<div class="well">
		{!! Former::submit('Simpan')->addClass('btn btn-primary confirm') !!}
		
		@if(App\Role::canList())
			<a href="{{ asset('roles') }}" class="btn btn-default pull-right">Senarai Peranan</a>
		@endif
		</div>
	{!! Former::close() !!}
@endsection