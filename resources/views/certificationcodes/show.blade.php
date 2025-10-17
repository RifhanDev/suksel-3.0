@extends('layouts.default')
@section('content')
	<h2>Lihat Kod Bidang</h2>
	<hr>
	{!! Former::open() !!}
	{!! Former::populate($certificationcode) !!}
	@include('certificationcodes.form')
	@include('certificationcodes.actions-footer')
@endsection