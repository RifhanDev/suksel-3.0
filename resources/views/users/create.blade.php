@extends('layouts.default')
@section('content')

	<h2>Pengguna Baru</h2>
	<br>
	{!! Former::open(url('users')) !!}
		@include('users.form')
		{!! Former::password('password')
			->label('Kata Laluan')
			->help('Sekurang-kurangnya 8 aksara, satu simbol, satu nombor, satu huruf besar dan satu huruf kecil')
			->required() !!}
		{!! Former::password('password_confirmation')
			->label('Sahkan Kata Laluan')
			->required() !!}
		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-3">
				<input type="submit" class="btn btn-primary" value="Simpan">
				<a href="{{ asset('users') !!}" class="btn btn-default">Senarai Pengguna</a>
			</div>
		</div>
	{!! Former::close() !!}

@endsection
