@extends('layouts.v3.master')
@section('content')
	@php($forcePasswordChange = $forcePasswordChange ?? false)
	<h2 class="tender-title">{{ $forcePasswordChange ? 'Tetapkan Kata Laluan Anda' : 'Kemaskini Kata Laluan' }}</h2>
	@if ($forcePasswordChange)
		<div class="alert alert-warning">
			Sila tetapkan kata laluan baharu sebelum meneruskan penggunaan sistem.
		</div>
	@endif
	{!! Former::open($forcePasswordChange ? url('profile/force_password_change') : url('profile/change_password'))->autocomplete('off') !!}
	{!! Former::hidden('_method', 'PUT') !!}
	@if (!$forcePasswordChange)
		{!! Former::password('old_password')->label('Kata Laluan Asal')->required()->autocomplete('new-password') !!}
	@endif
	{!! Former::password('password')->label('Kata Laluan Baru')->autocomplete('new-password')->help(
	        'Sekurang-kurangnya 8 aksara, satu simbol, satu nombor, satu huruf besar dan satu huruf kecil. Sila tukar kata laluan setiap 6 bulan. *',
	    )->required() !!}
	{!! Former::password('password_confirmation')->label('Sahkan Kata Laluan Baru')->autocomplete('new-password')->help('Masukan semula kata laluan.')->required() !!}
	<div class="well">
		<button class="btn btn-primary">Simpan</button>
		@if (!$forcePasswordChange)
			<a href="{!! asset('profile') !!}" class="btn btn-default pull-right">Profil Saya</a>
		@endif
	</div>
	{!! Former::close() !!}
@endsection

@section('scripts')
	<script src="{{ asset('js/news.js') }}"></script>
@endsection
