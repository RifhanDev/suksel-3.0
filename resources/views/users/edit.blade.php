@extends('layouts.default')
@section('content')
	<h2>
		Kemaskini Pengguna
		@if($currentUser->confirmed == 1)
			<span class="pull-right label label-lg label-success pull-right">
		@else
			<span class="pull-right label label-lg label-default pull-right">
		@endif
		{{$currentUser->status()}}</span>
	</h2>
	<br>
	{!! Former::open(url('users/'.$currentUser->id)) !!}
		{!! Former::populate($currentUser) !!}
		{!! Former::hidden('_method', 'PUT') !!}
		@include('users.form')
		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-3">
				<input type="submit" class="btn btn-primary" value="Simpan">
			</div>
		</div>
	{!! Former::close() !!}
	<div class="well">
		@if($currentUser->canLogin())
			<a href="{{ asset('users/'.$currentUser->id.'/login') }}" class="btn btn-danger">Login Sebagai</a>
		@endif

		@if(Auth::user()->hasRole('Admin') && !$currentUser->confirmed)
			<a href="{{ action('UsersController@resendConfirmation', $currentUser->id) }}" class="btn btn-default">Hantar Emel Pengesahan</a>
		@endif

		@if($currentUser->canSetPassword())
			<a href="{{ action('UsersController@getSetPassword', $currentUser->id) }}" class="btn btn-default">Tukar Kata Laluan</a>
		@endif

		@if($currentUser->canSetConfirmation())
			{!! Former::open(action('UsersController@putSetConfirmation', $currentUser->id))->class('form-inline') !!}
			{!! Former::hidden('_method', 'PUT') !!}
			{!! Former::hidden('confirmed', !$currentUser->confirmed) !!}
			<button type="submit" class="btn btn-warning">{{ $currentUser->confirmed ? 'Nyahaktif' : 'Aktifkan' }}</button>
			{!! Former::close() !!}
		@endif

		@if($currentUser->canDelete())
			{!! Former::open(route('users.destroy', $currentUser->id))->class('form-inline') !!}
			{!! Former::hidden('_method', 'DELETE') !!}
			<button type="button" class="btn btn-danger confirm-delete">Padam</button>
			{!! Former::close() !!}
		@endif

		<a href="{{ asset('users') }}" class="btn btn-default pull-right">Senarai Pengguna</a>
	</div>
@endsection

