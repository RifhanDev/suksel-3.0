@extends('layouts.default')
@section('content')

	<?php $user = Auth::user(); ?>
	<h2 class="tender-title">Profil Saya</h2>

	<table class="table table-bordered">
		<tr>
			<th class="col-lg-3">Nama</th>
			<td>{{ $user->name }}</td>
		</tr>
		<tr>
			<th class="col-lg-3">Alamat Emel</th>
			<td>{{ $user->email }}</td>
		</tr>
		@if($user->hasRole('Vendor'))
			<tr>
				<th>Nama Syarikat</th>
				<td>{{ $user->vendor->name }}</td>
			</tr>
		@endif
		@if($user->agency)
			<tr>
				<th>Agensi</th>
				<td>{{$user->agency->name}}</td>
			</tr>
		@endif
		
			<tr>
			<th>Tarikh Didaftarkan</th>
			<td>{{ \Carbon\Carbon::parse($user->created_at)->format('j M Y') }}</td>
		</tr>
	</table>

	<a href="{{ asset('profile/change_password') }}" class="btn btn-primary">Kemaskini Kata Laluan</a>
@endsection

@section('scripts')

	<script src="{{ asset('js/news.js') }}"></script>
	<script src="{{ asset('js/show.js') }}"></script>

@endsection
