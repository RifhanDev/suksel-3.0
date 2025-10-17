@extends('layouts.default')
@section('content')
	<h2>Pendaftaran Syarikat</h2>
	<ul class="nav nav-tabs nav-justified">
		<li class="disabled">
			<a href="#"><span class="badge">1</span> Pengesahan Alamat Emel</a>
		</li>
		<li class="active">
			<a href="#"><span class="badge">2</span> Lengkapkan Maklumat Syarikat</a>
		</li>
		<li class="disabled">
			<a href="#"><span class="badge">3</span> Pembayaran Pendaftaran</a>
		</li>
	</ul>

	{!! Former::open_for_files(action('RegistrationController@storeCompany'))->addClass('form-uppercase jq-validate') !!}
		{!! Former::populate($vendor) !!}
		{!! Former::hidden('_method', 'PUT') !!}
		@include('vendors.form')
		<div class="well">
			<a href="#" id="submit" class="btn btn-primary">Hantar</a>
			<button type="button" id="next" class="btn btn-primary">Seterusnya</button>
			<a href="{{ asset('dashboard')}}" class="btn btn-default pull-right">Kembali ke Dashboard</a>
		</div>
	{!! Former::close() !!}
@endsection