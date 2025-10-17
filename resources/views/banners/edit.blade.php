@extends('layouts.default')
@section('styles')

	<link href="{{ asset('css/form.css') }}" rel="stylesheet">

@endsection
@section('content')

	<h2 class="tender-title">Kemaskini Banner Baru</h2>
	
	{!! Former::open_for_files(url('banners/'.$banner->id)) !!}
		{!! Former::hidden('_method', 'PUT') !!}
		@include('banners.form')
	
		<div class="well">
			{!! Former::submit('Kemaskini')->class('btn btn-primary') !!}
			@if($banner->file)
				<a href=" {{ $banner->file->url . '/' . $banner->file->name }}" class="btn btn-success btn-show-banner" target="_blank">Lihat Banner</a>
			@endif
			<a href="{{ asset('banners') }}" class="btn btn-default pull-right">Senarai Banner</a>
		</div>
	{!! Former::close() !!}

@endsection