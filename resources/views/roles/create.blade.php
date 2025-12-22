@extends('layouts.v3.master')

@section('content')
	{!! Former::open(url('roles')) !!}
	@component('components.modern-form', [
		'title' => 'Tambah Peranan Baru',
		'pretitle' => 'Sistem Tender Online',
		'icon' => 'ti-user',
		'backUrl' => route('roles.index'),
		'backLabel' => 'Kembali ke Senarai',
		'submitLabel' => 'Hantar Peranan',
		'showViewButton' => false,
	])
	@include('roles.form')
	@endcomponent
	{!! Former::close() !!}
@endsection
