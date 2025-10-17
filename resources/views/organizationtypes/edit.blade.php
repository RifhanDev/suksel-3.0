@extends('layouts.default')
@section('content')
	<h2>Kemaskini Kategori Agensi</h2>
	<hr>
	{!! Former::open( url('organizationtypes/'.$type->id)) !!}
		{!! Former::populate($type) !!}
		{!! Former::hidden('_method', 'PUT') !!}
		@include('organizationtypes.form')
		<div class="well">
			{{-- {!! Former::button('Simpan')->class('btn btn-primary') !!} --}}
			<button id="btn-simpan" class="btn btn-primary" type="button">Simpan</button>
			<a href="{{ asset('organizationtypes') }}" class="btn btn-default pull-right">Senarai</a>
		</div>
	{!! Former::close() !!}
@endsection
