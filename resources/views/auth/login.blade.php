@extends('layouts.default')

@section('content')
	<div class="page page-center">
		<div class="container container-tight py-4">
			<div class="text-center mb-4">
				<img src="{{ asset('images/header.png') }}" alt="Sistem Tender Online Selangor" class="navbar-brand-image"
					style="max-width: 200px; height: auto;">
			</div>
			<div class="card card-md">
				<div class="card-body">
					<h2 class="h2 text-center mb-4">Daftar Masuk</h2>
					<p class="text-muted text-center mb-4">Sistem Tender Online Selangor</p>

					@if ($errors->any())
						<div class="alert alert-danger">
							<ul class="mb-0">
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					@if (session('error'))
						<div class="alert alert-danger">
							{{ session('error') }}
						</div>
					@endif

					<form method="POST" action="{{ action('AuthController@doLogin') }}" autocomplete="off">
						@csrf
						<div class="mb-3">
							<label class="form-label">Alamat Emel</label>
							<input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
								placeholder="Alamat Emel" value="{{ old('email') }}" required autocomplete="email" autofocus>
							@error('email')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>

						<div class="mb-2">
							<label class="form-label">Kata Laluan</label>
							<input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
								placeholder="Kata Laluan" required autocomplete="current-password">
							@error('password')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>

						@if ($errors->has('login'))
							<div class="alert alert-danger mt-3">
								{{ $errors->first('login') }}
							</div>
						@endif

						<div class="form-footer">
							<button type="submit" class="btn btn-primary w-100">
								<i class="ti ti-login me-2"></i>Daftar Masuk
							</button>
						</div>
					</form>

					<div class="text-center text-muted mt-3">
						<a href="{{ action('AuthController@forgotPassword') }}" class="text-decoration-none">
							<i class="ti ti-key me-1"></i>Lupa Kata Laluan?
						</a> &bullet;
						<a href="{{ route('registration') }}" class="text-decoration-none">
							<i class="ti ti-user-plus me-1"></i>Daftar Akaun!
						</a> &bullet;
						<a href="{{ route('manuals.show', 'pendaftaran') }}" target="_blank" class="text-decoration-none">
							<i class="ti ti-help me-1"></i>Cara Mendaftar
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
