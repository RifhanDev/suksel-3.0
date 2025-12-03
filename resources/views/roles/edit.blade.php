@extends('layouts.modern')

@section('content')
	{!! Former::open(url('roles/' . $role->id)) !!}
	{!! Former::populate($role) !!}
	{!! Former::hidden('_method', 'PUT') !!}
	@component('components.modern-form', [
		'title' => 'Kemaskini Peranan',
		'pretitle' => 'Sistem Tender Online',
		'icon' => 'ti-pencil',
		'backUrl' => route('roles.index'),
		'backLabel' => 'Kembali ke Senarai',
		'submitLabel' => 'Kemaskini Peranan',
		'showViewButton' => false,
	])
		@include('roles.form')
	@endcomponent
	{!! Former::close() !!}
@endsection
