@extends('layouts.default')

@section('content')
	<div class="container-xl">
		<div class="row justify-content-center">
			<div class="col-md-6 col-lg-4">
				<div class="card">
					<div class="card-body">
						<div class="text-center mb-4">
							<img src="{{ asset('images/header.png') }}" alt="Sistem Tender Online Selangor" class="mb-3"
								style="max-width: 200px;">
							<h2 class="card-title">Daftar Masuk</h2>
							<p class="text-muted">Sistem Tender Online Selangor</p>
						</div>

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

						<form method="POST" action="{{ action('AuthController@doLogin') }}">
							@csrf
							<div class="mb-3">
								<label for="email" class="form-label">Alamat Emel</label>
								<input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
									value="{{ old('email') }}" placeholder="Alamat Emel" required autocomplete="email" autofocus>
								@error('email')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>

							<div class="mb-3">
								<label for="password" class="form-label">Kata Laluan</label>
								<input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
									name="password" placeholder="Kata Laluan" required autocomplete="current-password">
								@error('password')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>

							<div class="d-grid">
								<button type="submit" class="btn btn-primary btn-lg">
									Daftar Masuk
								</button>
							</div>
						</form>

						<div class="text-center mt-4">
							<p class="mb-2">
								<a href="{{ action('AuthController@forgotPassword') }}" class="text-decoration-none">
									Lupa Kata Laluan?
								</a>
							</p>
							<p class="mb-0">
								<a href="{{ route('registration') }}" class="text-decoration-none">
									Daftar Akaun Baru
								</a>
							</p>
							<p class="mb-0">
								<a href="{{ route('manuals.show', 'pendaftaran') }}" target="_blank" class="text-decoration-none">
									Cara Mendaftar
								</a>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
