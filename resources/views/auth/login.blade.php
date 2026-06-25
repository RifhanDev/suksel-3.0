@extends('layouts.modernLanding')

@section('styles')
	<style>
		.selangor-card {
			background: white;
			border-radius: var(--radius-lg);
			box-shadow: 0 10px 40px -10px rgba(196, 30, 58, 0.1);
			border: 1px solid rgba(0, 0, 0, 0.05);
			overflow: hidden;
			position: relative;
		}

		/* Top Gradient Accent */
		.selangor-card::before {
			content: "";
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 6px;
			background: linear-gradient(90deg, var(--sg-red) 50%, var(--sg-yellow) 50%);
		}

		/* =========================================
									CARD HEADER
									========================================= */
		.card-header-custom {
			padding: 2.5rem 2rem 1rem 2rem;
			text-align: center;
		}

		.card-body-custom {
			padding: 0.5rem 2.5rem 2.5rem 2.5rem;
		}

		/* =========================================
								INPUT
								========================================= */
		.form-label {
			font-size: 0.7rem;
			font-weight: 800;
			text-transform: uppercase;
			letter-spacing: 0.05em;
			color: #374151;
			margin-bottom: 0.35rem;
		}

		.form-control {
			border: 2px solid #f3f4f6;
			border-radius: var(--radius-sm);
			padding: 0.75rem 1rem;
			font-weight: 600;
			color: var(--sg-black);
			background-color: #f9fafb;
			transition: all 0.2s ease;
			font-size: 0.95rem;
		}

		.form-control::placeholder {
			color: #9ca3af;
			font-weight: 400;
		}

		.form-control:focus {
			background-color: #fff;
			border-color: var(--sg-red);
			box-shadow: 0 0 0 4px rgba(196, 30, 58, 0.1);
			color: var(--sg-black);
		}

		/* =========================================
								BUTTONS
								========================================= */
		.btn-selangor {
			background-color: var(--sg-red);
			color: white;
			font-weight: 700;
			padding: 0.85rem;
			border-radius: var(--radius-sm);
			border: 1px solid var(--sg-red);
			width: 100%;
			transition: all 0.2s ease;
			box-shadow: 0 4px 6px rgba(196, 30, 58, 0.2);
			display: flex;
			justify-content: center;
			align-items: center;
			gap: 0.5rem;
		}

		.btn-selangor:hover {
			background-color: var(--sg-red-dark);
			border-color: var(--sg-red-dark);
			color: #fff;
			transform: translateY(-1px);
			box-shadow: 0 8px 15px rgba(196, 30, 58, 0.25);
		}

		/* =========================================
								ALERTS
								========================================= */
		.custom-alert {
			border-radius: var(--radius-sm);
			border: none;
			font-size: 0.85rem;
			margin-bottom: 1.25rem;
			display: flex;
			align-items: flex-start;
			gap: 0.75rem;
			padding: 0.75rem 1rem;
			line-height: 1.4;
		}

		.custom-alert-danger {
			background-color: #fef2f2;
			color: #991b1b;
			border-left: 3px solid var(--sg-red);
		}

		/* =========================================
								REGISTER
								========================================= */
		.register-callout {
			background-color: #f8fafc;
			border: 1px dashed #cbd5e1;
			border-radius: var(--radius-md);
			padding: 1rem;
			text-align: center;
			margin-top: 1.5rem;
			position: relative;
		}

		.register-callout::before {
			content: "";
			position: absolute;
			left: -1px;
			top: 10px;
			bottom: 10px;
			width: 3px;
			background-color: var(--sg-yellow);
			border-radius: 0 4px 4px 0;
		}

		.btn-register-ghost {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			margin-top: 0.5rem;
			background: white;
			border: 1px solid #e5e7eb;
			color: var(--sg-red);
			font-weight: 700;
			font-size: 0.8rem;
			padding: 0.4rem 1rem;
			border-radius: var(--radius-sm);
			transition: all 0.2s;
			box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
			text-decoration: none;
			gap: 0.4rem;
		}

		.btn-register-ghost:hover {
			border-color: var(--sg-red);
			background: #fff1f2;
			color: var(--sg-red);
			text-decoration: none;
		}

		/* Links */
		.forgot-pass-link {
			color: var(--sg-red);
			font-size: 0.8rem;
			text-decoration: none;
			font-weight: 600;
			transition: color 0.2s;
			display: inline-flex;
			align-items: center;
			gap: 0.3rem;
		}

		.forgot-pass-link:hover {
			color: var(--sg-black);
			text-decoration: none;
		}

		.manual-link {
			font-size: 0.75rem;
			color: #9ca3af;
			text-decoration: none;
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 0.4rem;
			margin-top: 1rem;
			transition: color 0.2s;
		}

		.manual-link:hover {
			color: #374151;
			text-decoration: underline;
		}

		/* Mobile */
		@media (max-width: 768px) {
			.card-header-custom {
				padding: 2rem 1.5rem 0.5rem 1.5rem;
			}

			.card-body-custom {
				padding: 0.5rem 1.5rem 2rem 1.5rem;
			}
		}
	</style>
@endsection

@section('content')
	<div class="row justify-content-center align-items-center" style="min-height: 80vh;">

		<div class="col-lg-4 col-md-6 col-sm-10">
			<div class="selangor-card">

				<!-- HEADER -->
				<div class="card-header-custom">
					<!-- Icon Circle -->
					<div class="mb-3 d-inline-flex align-items-center justify-content-center"
						style="width: 56px; height: 56px; background: #fff; border: 2px solid var(--sg-yellow); border-radius: 50%; box-shadow: 0 4px 6px rgba(0,0,0,0.05); padding-left: 5px;">
						<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
							stroke="#c41e3a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
							<path d="M20 12h-13l3 -3m0 6l-3 -3" />
						</svg>
					</div>

					<h1 class="h4 fw-bold mb-1 text-uppercase" style="letter-spacing: -0.5px; color:var(--sg-red)">Daftar Masuk</h1>
					<p class="text-muted small fw-semibold mb-0">Sistem Perolehan Selangor</p>
				</div>

				<div class="card-body-custom">

					<!-- ERROR HANDLING -->
					@if ($errors->any() || session('error'))
						<div class="custom-alert custom-alert-danger">
							<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
								stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-1">
								<path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
								<path d="M12 9v4" />
								<path d="M12 16h.01" />
							</svg>
							<div>
								@if ($errors->any())
									<ul class="mb-0 ps-3">
										@foreach ($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
								@else
									{{ session('error') }}
								@endif
							</div>
						</div>
					@endif

					<!-- FORM -->
					<form method="POST" action="{{ action('AuthController@doLogin') }}" autocomplete="off">
						@csrf

						<div class="mb-3">
							<label class="form-label">Alamat Emel</label>
							<input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
								placeholder="nama@syarikat.com" value="{{ old('email') }}" required autocomplete="email" autofocus>
							@error('email')
								<div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
							@enderror
						</div>

						<div class="mb-4">
							<div class="d-flex justify-content-between align-items-center mb-1">
								<label class="form-label mb-0">Kata Laluan</label>
								<a href="{{ action('AuthController@forgotPassword') }}" class="forgot-pass-link"
									style="font-size: 0.7rem; font-weight: 500;">
									Lupa Kata Laluan?
								</a>
							</div>
							<input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
								placeholder="••••••••" required autocomplete="current-password">
							@error('password')
								<div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
							@enderror
						</div>

						<div class="mt-2">
							<button type="submit" class="btn-selangor">
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M15 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
									<path d="M21 12h-13l3 -3m0 6l-3 -3" />
								</svg>
								Log Masuk
							</button>
						</div>
					</form>

					<!-- REGISTER BOX -->
					<div class="register-callout">
						<div class="d-flex flex-column align-items-center">
							<div class="small fw-bold text-dark">Syarikat Belum Berdaftar?</div>
							<div class="text-muted mb-2" style="font-size: 0.75rem;">Sertai kami untuk peluang tender.</div>

							<a href="{{ route('registration') }}" class="btn-register-ghost">
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
									<path d="M16 19h6" />
									<path d="M19 16v6" />
									<path d="M6 21v-2a4 4 0 0 1 4 -3.85" />
								</svg>
								Daftar Akaun Baru
							</a>
						</div>
					</div>

					<!-- MANUAL -->
					<a href="{{ route('manuals.show', 'pendaftaran') }}" target="_blank" class="manual-link">
						<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
							<path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
							<path d="M3 6v13" />
							<path d="M12 6v13" />
							<path d="M21 6v13" />
						</svg>
						Panduan Pengguna
					</a>

				</div> <!-- End Card Body -->
			</div> <!-- End Card -->
		</div>
	</div>
@endsection
