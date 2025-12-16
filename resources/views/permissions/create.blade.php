@extends('layouts.modern')

@section('content')
	{!! Former::open(url('permissions')) !!}
	@component('components.modern-form', [
		'title' => 'Tambah Kebenaran Baru',
		'pretitle' => 'Sistem Tender Online',
		'icon' => 'ti-key',
		'backUrl' => route('permissions.index'),
		'backLabel' => 'Kembali ke Senarai',
		'submitLabel' => 'Hantar Kebenaran',
		'showViewButton' => false,
	])
	@include('permissions.form')
	@endcomponent
	{!! Former::close() !!}
@endsection
