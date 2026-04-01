@extends('layouts.v3.master')

@section('styles')
	<style>
		.card-accent-top {
			border-top: 4px solid var(--sg-red);
		}

		.info-sidebar {
			background: linear-gradient(135deg, var(--sg-red) 0%, var(--sg-red-dark) 100%);
			color: white;
			position: relative;
			overflow: hidden;
		}

		/* Sidebar Geometric Accents */
		.info-sidebar::after {
			content: '';
			position: absolute;
			bottom: -50px;
			right: -50px;
			width: 150px;
			height: 150px;
			background: var(--sg-yellow);
			opacity: 0.15;
			border-radius: 30px;
			transform: rotate(45deg);
			pointer-events: none;
		}

		.info-sidebar::before {
			content: '';
			position: absolute;
			top: -30px;
			left: -30px;
			width: 100px;
			height: 100px;
			background: white;
			opacity: 0.05;
			border-radius: 50%;
			pointer-events: none;
		}

		/* Text Adjustments */
		.info-sidebar h5,
		.info-sidebar .fw-bold {
			color: white !important;
		}

		.info-sidebar .text-muted {
			color: rgba(255, 255, 255, 0.7) !important;
		}

		.icon-box-sidebar {
			background: rgba(255, 255, 255, 0.15);
			border: 1px solid rgba(255, 255, 255, 0.2);
			color: white;
			width: 50px;
			height: 50px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			border-radius: 50%;
			margin-bottom: 1rem;
			backdrop-filter: blur(5px);
		}

		.link-slide-underline {
			position: relative;
			text-decoration: none !important;
			transition: color 0.3s ease-in-out;
			padding-bottom: 3px;
		}

		.link-slide-underline:hover {
			color: var(--sg-red) !important;
		}

		.link-slide-underline::after {
			content: '';
			position: absolute;
			width: 0;
			height: 2px;
			bottom: 0;
			left: 0;
			background-color: var(--sg-red);
			transition: width 0.3s ease-in-out;
		}

		.link-slide-underline:hover::after {
			width: 95%;
		}
	</style>
@endsection

@section('content')

	<?php $user = Auth::user(); ?>

	<!-- PAGE HEADER -->
	<div class="d-flex justify-content-between align-items-center mb-4">
		<div>
			<div class="text-uppercase text-muted fw-bold small mb-1" style="letter-spacing: 1px;">Pengurusan Pengguna</div>
			<h2 class="fw-bold text-dark m-0">
				Tukar Kata Laluan
			</h2>
		</div>
	</div>

	<form action="{{ url('users/' . $currentUser->id . '/reset_password') }}" method="POST">
		@csrf
		@method('PUT')

		<!-- SPLIT LAYOUT CARD -->
		<div class="card border-0 shadow-sm rounded-3 overflow-hidden card-accent-top mb-5">
			<div class="row g-0">

				<!-- LEFT PANEL: IDENTITY (Read-Only) -->
				<div class="col-lg-4 info-sidebar">
					<div class="p-5 h-100 d-flex flex-column justify-content-center position-relative z-1">

						<div class="mb-5">
							<div class="icon-box-sidebar">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
									<path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
								</svg>
							</div>
							<h5 class="fw-bold mb-2">Maklumat Akaun</h5>
							<p class="text-muted small mb-0">Pastikan anda menukar kata laluan setiap 90 hari untuk keselamatan.</p>
						</div>

						<!-- Vendor Name (If Vendor) -->
						@if ($currentUser->hasRole('Vendor'))
							<div class="mb-4">
								<label class="small text-uppercase text-muted fw-bold mb-1" style="letter-spacing: 1px;">Nama Syarikat</label>
								<div class="fw-bold fs-5">{{ $currentUser->vendor->name }}</div>
							</div>
						@endif

						<div class="mb-4">
							<label class="small text-uppercase text-muted fw-bold mb-1" style="letter-spacing: 1px;">Nama Pengguna</label>
							<div class="fw-bold fs-5">{{ $currentUser->name }}</div>
						</div>

						<div>
							<label class="small text-uppercase text-muted fw-bold mb-1" style="letter-spacing: 1px;">Alamat Emel</label>
							<div class="fw-bold fs-6 text-white-50">{{ $currentUser->email }}</div>
						</div>

					</div>
				</div>

				<!-- RIGHT PANEL: FORM -->
				<div class="col-lg-8 bg-white">
					<div class="p-5">

						<div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
								stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary">
								<path
									d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4">
								</path>
							</svg>
							<h6 class="fw-bold text-dark mb-0">Tetapan Kata Laluan Baru</h6>
						</div>

						<div class="row g-4">
							<!-- Password -->
							<div class="col-12">
								<label for="password" class="form-label fw-bold text-dark">Kata Laluan Baru <span
										class="text-danger">*</span></label>
								<input type="password" class="form-control form-control-lg fs-6 @error('password') is-invalid @enderror"
									id="password" name="password" pattern=".{8,}" required>

								@error('password')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
								<div class="form-text text-muted small mt-2">
									<i class="ti ti-info-circle me-1"></i> Sekurang-kurangnya 8 aksara dan kombinasi antara abjad dan nombor, huruf
									besar dan kecil.
								</div>
							</div>

							<!-- Confirm Password -->
							<div class="col-12">
								<label for="password_confirmation" class="form-label fw-bold text-dark">Pastikan Kata Laluan <span
										class="text-danger">*</span></label>
								<input type="password"
									class="form-control form-control-lg fs-6 @error('password_confirmation') is-invalid @enderror"
									id="password_confirmation" name="password_confirmation" required>
								@error('password_confirmation')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>

		<!-- ACTION BAR -->
		<div class="w-100 bg-white border border-top-0 shadow-lg rounded-3 p-3 z-3">
			<div class="d-flex justify-content-between align-items-center">

				<!-- LEFT -->
				<div>
					@if ($currentUser->hasRole('Vendor'))
						<a href="{{ asset('vendors/' . $currentUser->vendor_id) }}"
							class="text-secondary text-decoration-none d-flex align-items-center gap-2 ps-0 link-slide-underline">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
								stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<line x1="19" y1="12" x2="5" y2="12"></line>
								<polyline points="12 19 5 12 12 5"></polyline>
							</svg>
							<span class="fw-bold">Maklumat Syarikat</span>
						</a>
					@else
						<div class="d-flex gap-3">
							@if (App\User::canList())
								<a href="{{ asset('users') }}"
									class="text-secondary text-decoration-none d-flex align-items-center gap-2 ps-0 link-slide-underline">
									<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
										stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<line x1="19" y1="12" x2="5" y2="12"></line>
										<polyline points="12 19 5 12 12 5"></polyline>
									</svg>
									<span class="fw-bold">Senarai Pengguna</span>
								</a>
							@endif

							@if (isset($currentUser) && $user->canUpdate())
								<a href="{{ asset('users/' . $currentUser->id . '/edit') }}"
									class="btn btn-light border d-flex align-items-center gap-2 fw-medium">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
										stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
										<circle cx="12" cy="7" r="4"></circle>
									</svg>
									Maklumat Pengguna
								</a>
							@endif
						</div>
					@endif
				</div>

				<!-- RIGHT: SUBMIT BUTTON -->
				<div>
					<button type="submit" class="btn btn-selangor d-flex align-items-center gap-2 fw-medium px-4 py-2">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
							<polyline points="17 21 17 13 7 13 7 21"></polyline>
							<polyline points="7 3 7 8 15 8"></polyline>
						</svg>
						Kemaskini Kata Laluan
					</button>
				</div>

			</div>
		</div>

	</form>

@endsection
