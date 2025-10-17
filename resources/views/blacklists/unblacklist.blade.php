@extends('layouts.default')
@section('content')
	<h2 class="tender-title">{{$vendor->name}} : Batal Senarai Hitam </h2>
	{!! Former::open(route('vendor.blacklists.cancel', [$vendor->id, $blacklist->id]))->method('PUT') !!}
		@include('blacklists.cancelform')
		
		<div class="well">
			{!! Former::submit('Hantar')->class('btn btn-primary') !!}
			<a href="{{ route('vendor.blacklists.index', $vendor->id) }}" class="btn btn-default pull-right">Maklumat Senarai Hitam</a>
		</div>
	{!! Former::close() !!}
@endsection