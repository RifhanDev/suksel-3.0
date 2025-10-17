@extends('layouts.default')

@section('styles')

	<link href="{{ asset('css/form.css') }}" rel="stylesheet">

@endsection

@section('content')
	<div class="row">
		<div class="col-lg-9 col-xs-12">
			<ul class="nav nav-tabs nav-justified">
				<li><a href="{{ asset('dashboard') }}">Maklumat Tender / Sebut Harga</a></li>
				<li class="active"><a href="{{ asset('vendor') }}">Maklumat Syarikat</a></li>
			</ul>

			@include('vendors.vendor')

			<div class="well">
				@if(!$vendor->completed)
					<a href="{{ asset('register/company') }}" class="btn btn-default">Lengkapkan Maklumat Syarikat</a>
				@endif
				@if($vendor->approval_1_id)
					<a href="{{ asset('vendors/'.$vendor->id.'/edit')}}" class="btn btn-primary">Kemaskini</a>
				@endif
				@if($vendor->completed && $vendor->approval_1_id > 0 && !$vendor->registration_paid)
					<a href="{{ asset('register/payment') }}" class="btn btn-danger">Pembayaran Pendaftaran</a>
				@endif
				@if($vendor->registration_paid)
					<a href="{{ asset('vendor/'.$vendor->id.'/requests') }}" class="btn btn-default">Permintaan Kemaskini</a>
				@endif
				@if($vendor->require_renewal)
					<a href="{{ asset('renewal') }}" class="btn btn-danger">Pembaharuan Langganan</a>
				@endif
			</div>
		</div>


		<div class="col-lg-3 col-xs-12">
			@include('layouts._news')
		</div>
	</div>
@endsection

@section('scripts')

	<script src="{{ asset('js/news.js') }}"></script>

@endsection
