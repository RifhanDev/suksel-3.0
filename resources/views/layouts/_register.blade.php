@if(!Auth::check())
	<a id="btn-register-vendor" href="{{ asset('register') }}" class="btn btn-lg btn-block btn-danger">Daftar Syarikat</a>
@endif