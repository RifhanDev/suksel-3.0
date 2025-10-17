@extends('layouts.default')
@section('content')
	<div class="row">
	    	<div class="col-sm-6 col-sm-offset-3">
	        	<h1 class="text-center">
	            Tukar Kata Laluan<br>
	            <small>Sila masukkan kata laluan yang baru</small>
	        	</h1>
	        	<br>

	        	{!! Former::open_vertical(action('AuthController@doResetPassword'))->autocomplete("false") !!}
	            {!! Former::hidden('token')->value($token) !!}
	            {!! Former::password('password')
	                ->label('auth.register.password')
	                ->required()
	                ->help('Sekurang-kurangnya 8 aksara, satu simbol, satu nombor, satu huruf besar dan satu huruf kecil, sila tukar kata laluan setiap 90 hari')
	                ->forceValue('') !!}
	            {!! Former::password('password_confirmation')
	                ->label('auth.register.confirm_password')
	                ->required()
	                ->help('Masukan semula kata laluan')
	                ->forceValue('') !!}
	            <div class="form-actions form-group">
	                <button type="submit" class="btn btn-primary">Kemaskini</button>
	            </div>
	        	{!! Former::close() !!}

	    	</div>
	</div>
@endsection
