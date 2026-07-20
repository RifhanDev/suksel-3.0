@extends('layouts.modernLanding')

@section('styles')
	<style>
		.selangor-card {
			background: white;
			border-radius: var(--radius-lg);
			box-shadow: 0 5px 25px -5px rgba(196, 30, 58, 0.08);
			border: 1px solid rgba(0, 0, 0, 0.05);
			overflow: hidden;
			position: relative;
		}

		.selangor-card::before {
			content: "";
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 5px;
			background: linear-gradient(90deg, var(--sg-red) 50%, var(--sg-yellow) 50%);
		}

		.card-header-custom {
			padding: 2rem 2rem 1rem 2rem;
			display: flex;
			align-items: center;
			gap: 1rem;
			border-bottom: 1px dashed #f3f4f6;
		}

		.card-body-custom {
			padding: 1.5rem 2rem 2rem 2rem;
		}

		/* =========================================
					FORM ELEMENTS
								========================================= */
		.form-label {
			font-size: 0.7rem;
			font-weight: 800;
			text-transform: uppercase;
			letter-spacing: 0.05em;
			color: #374151;
			margin-bottom: 0.3rem;
		}

		.form-control {
			border: 1px solid #e5e7eb;
			border-radius: var(--radius-sm);
			padding: 0.6rem 0.9rem;
			font-weight: 600;
			font-size: 0.85rem;
			color: var(--sg-black);
			background-color: #f9fafb;
			transition: all 0.2s ease;
		}

		.form-control:focus {
			background-color: #fff;
			border-color: var(--sg-red);
			box-shadow: 0 0 0 3px rgba(196, 30, 58, 0.1);
			color: var(--sg-black);
		}

		textarea.form-control {
			resize: vertical;
			min-height: 120px;
		}

		/* =========================================
								BUTTONS & ALERTS
								========================================= */
		.btn-selangor {
			background-color: var(--sg-red);
			color: white;
			font-weight: 700;
			padding: 0.7rem 1.5rem;
			border-radius: var(--radius-sm);
			border: 1px solid var(--sg-red);
			transition: all 0.2s ease;
			font-size: 0.85rem;
			display: inline-flex;
			align-items: center;
			gap: 0.5rem;
		}

		.btn-selangor:hover {
			background-color: var(--sg-red-dark);
			border-color: var(--sg-red-dark);
			color: #fff;
			transform: translateY(-1px);
		}

		.note-box {
			background-color: #fffbeb;
			border: 1px solid #fde68a;
			border-radius: var(--radius-sm);
			padding: 0.75rem 1rem;
			font-size: 0.75rem;
			color: #92400e;
			display: flex;
			gap: 0.75rem;
			align-items: flex-start;
			margin-bottom: 1.5rem;
		}

		.header-icon {
			width: 48px;
			height: 48px;
			background: rgba(196, 30, 58, 0.08);
			color: var(--sg-red);
			border-radius: 12px;
			display: flex;
			align-items: center;
			justify-content: center;
			flex-shrink: 0;
		}

		@media (max-width: 768px) {

			.card-header-custom,
			.card-body-custom {
				padding: 1.5rem;
			}
		}
	</style>
@endsection

@section('content')
	<div class="row justify-content-center py-4">
		<div class="col-lg-8 col-xl-7">

			<div class="selangor-card">

				<!-- Header -->
				<div class="card-header-custom">
					<div class="header-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M8 9h8" />
							<path d="M8 13h6" />
							<path d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12z" />
						</svg>
					</div>
					<div>
						<h1 class="h5 fw-bold mb-0 text-dark text-uppercase" style="letter-spacing: -0.2px;">
							Aduan & Maklum Balas
						</h1>
						<p class="text-muted small mb-0" style="font-size: 0.75rem;">
							Sila lengkapkan butiran di bawah.
						</p>
					</div>
				</div>

				<!-- Body -->
				<div class="card-body-custom">

					<!-- Info Note -->
					<div class="note-box">
						<i class="ti ti-info-circle fs-5 mt-1"></i>
						<div>
							<strong>Perhatian:</strong> Pastikan maklumat tepat untuk memudahkan kami menghubungi anda semula.
						</div>
					</div>

					<form method="POST" action="{{ url('aduan') }}">
						@csrf

						@include('complaint.form')

						<!-- Action Bar -->
						<div class="d-flex justify-content-end pt-3 border-top mt-2">
							<button type="submit" class="btn-selangor">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M10 14l11 -11" />
									<path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />
								</svg>
								Hantar Aduan
							</button>
						</div>

					</form>
				</div>
			</div>

		</div>
	</div>
@endsection

@section('scripts')
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection
