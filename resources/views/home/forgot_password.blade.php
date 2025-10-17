@extends('layouts.default')

@section('styles')

	<link href="{{ asset('css/login.css') }}" rel="stylesheet">

@endsection

@section('content')

	<div class="row">
    	<div class="col-sm-6 col-sm-offset-3">
        	<h1 class="text-center">Lupa Kata Laluan</h1>
        	<br><br>
    	{!! Former::open_vertical(action('AuthController@doForgotPassword')) !!}
            <div class="form-group">
					<div class="input-append input-group">
						<input class="form-control" placeholder="{{ trans('auth.register.email') }}" type="text" name="email" id="email" value="{{{ Request::old('email') }}}">
						<span class="input-group-btn">
						<input class="btn btn-primary" type="submit" value="Seterusnya">
						</span> 
					</div>
			</div>
        	{!! Former::close() !!}
			<br><h3 class="text-center">Sila tukar kata laluan setiap 90 hari *</h3>
        	<br>
		</div>
   </div>

@endsection
	
@section('scripts')

	<script src="{{ asset('js/news.js') }}"></script>

@endsection

