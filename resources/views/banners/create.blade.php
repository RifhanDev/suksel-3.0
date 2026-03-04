@extends('layouts.v3.master')

@section('content')
	{!! Former::open_for_files(url('banners')) !!}
	@component('components.modern-form', [
		'title' => 'Masukkan Banner Baru',
		'pretitle' => 'Sistem Tender Online',
		'icon' => 'ti-photo',
		'backUrl' => asset('banners'),
		'backLabel' => 'Kembali ke Senarai',
		'submitLabel' => 'Hantar Banner',
		'showViewButton' => false,
	])
		@include('banners.form')
	@endcomponent
	{!! Former::close() !!}
@endsection
