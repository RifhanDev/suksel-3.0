@extends('layouts.v3.master')

@section('content')
	{!! Former::open(url('permissions/' . $permission->id)) !!}
	{!! Former::populate($permission) !!}
	{!! Former::hidden('_method', 'PUT') !!}
	@component('components.modern-form', [
		'title' => 'Kemaskini Kebenaran',
		'pretitle' => 'Sistem Tender Online',
		'icon' => 'ti-pencil',
		'backUrl' => route('permissions.index'),
		'backLabel' => 'Kembali ke Senarai',
		'submitLabel' => 'Kemaskini Kebenaran',
		'showViewButton' => false,
	])
	@include('permissions.form')
	@endcomponent
	{!! Former::close() !!}
@endsection
