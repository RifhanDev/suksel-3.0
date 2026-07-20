@extends('layouts.v3.master')
@section('content')
	<h2>Lihat Kod Bidang</h2>
	<hr>
	{!! Former::open() !!}
	{!! Former::populate($certificationcode) !!}
	@include('certificationcodes.form')
	@include('certificationcodes.actions-footer')
@endsection
