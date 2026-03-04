@extends('layouts.v3.master')

@section('content')
	{!! Former::open_for_files()->put()->route('banners.update', $banner->id) !!}
	@component('components.modern-form', [
		'title' => 'Kemaskini Banner',
		'pretitle' => 'Sistem Tender Online',
		'icon' => 'ti-pencil',
		'backUrl' => asset('banners'),
		'backLabel' => 'Kembali ke Senarai',
		'submitLabel' => 'Kemaskini Banner',
		'showViewButton' => $banner->file ? true : false,
		'viewUrl' => $banner->file ? $banner->file->url . '/' . $banner->file->name : null,
		'viewLabel' => 'Lihat Banner',
	])
		@include('banners.form')
	@endcomponent
	{!! Former::close() !!}
@endsection
