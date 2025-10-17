@extends('layouts.default')
@section('content')
	<h2>Kemaskini Kod Bidang</h2>
	<hr>
	{!! Former::open(url('codes/'.$certificationcode->id)) !!}
		{!! Former::populate($certificationcode) !!}
		{!! Former::hidden('_method', 'PUT') !!}
		@include('certificationcodes.form')
		<div class="well">
			{!! Former::submit('Hantar')->class('btn btn-primary') !!}
			<a href="{{ asset('codes') }}" class="btn btn-default pull-right">Senarai Kod Bidang</a>
		</div>
	{!! Former::close() !!}
@endsection
