@extends('layouts.default')
@section('content')
	<h2 class="tender-title">Kemaskini Kata Laluan</h2>
	{!! Former::open(action('ProfileController@doChangePassword'))->autocomplete("false") !!}
		{!! Former::hidden('_method', 'PUT') !!}
		{!! Former::password('old_password')
			->label('Kata Laluan Asal')
			->required() !!}
		{!! Former::password('password')
			->label('Kata Laluan Baru')
			->help('Sekurang-kurangnya 8 aksara, satu simbol, satu nombor, satu huruf besar dan satu huruf kecil. Sila tukar kata laluan setiap 90 hari. *')
			->required() !!}
		{!! Former::password('password_confirmation')
			->label('Sahkan Kata Laluan Baru')
			->help('Masukan semula kata laluan.')
			->required() !!}
		<div class="well">
			<button class="btn btn-primary">Simpan</button>
			<a href="{!! asset('profile') !!}" class="btn btn-default pull-right">Profil Saya</a>
		</div>
	{!! Former::close() !!}
@endsection

@section('scripts')

	<script src="{{ asset('js/news.js') }}"></script>

@endsection
