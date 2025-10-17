@extends('layouts.default')
@section('content')
	<h2 class="tender-title">{{ $vendor->name }} : Kemaskini Senarai Hitam</h2>
	
	{!! Former::open_for_files(route('vendor.blacklists.update', [$vendor->id, $blacklist->id])) !!}
	{!! Former::hidden('_method', 'PUT') !!}
	@include('blacklists.form')
	
	<div class="well">
		{!! Former::submit('Simpan')->class('btn btn-primary') !!}
		@if($blacklist->file)
			<a href="{{ route('vendor.blacklists.file', [$vendor->id, $blacklist->id]) }}" target="_blank" class="btn btn-default">Lihat Lampiran</a>
		@endif
	
	@if($blacklist->canCancel())
		{{-- {{Former::open(route('vendor.blacklists.cancel', [$vendor->id, $blacklist->id]))->class('form-inline')}}
		{!! Former::hidden('_method', 'PUT') !!}
		<button type="button" class="btn btn-danger confirm">Batal</button>
		{!! Former::close() !!} --}}
		<a href="{{ route('vendor.blacklists.unblacklist', [$blacklist->vendor_id, $blacklist->id]) }}" class="btn btn-danger">Batal</a>
	@endif
	
	<a href="{{ route('vendor.blacklists.index', $vendor->id) }}" class="btn btn-default pull-right">Maklumat Senarai Hitam</a>
	</div>
	{!! Former::close() !!}
@endsection