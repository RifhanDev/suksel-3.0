@extends('layouts.default')
@section('content')
	<h2 class="tender-title">{{ $vendor->name }} : Masukkan Senarai Hitam Baru</h2>
	
	{!! Former::open_for_files(route('vendor.blacklists.store', $vendor->id)) !!}
		@include('blacklists.form')
		
		<div class="well">
			{!! Former::submit('Hantar')->class('btn btn-primary') !!}
			<a href="{{ route('vendor.blacklists.index', $vendor->id) }}" class="btn btn-default pull-right">Maklumat Senarai Hitam</a>
		</div>
	{!! Former::close() !!}
@endsection