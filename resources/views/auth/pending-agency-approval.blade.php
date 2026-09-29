@extends('layouts.modernLanding')

@section('content')
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
			<div class="text-center" style="padding: 2rem 1rem;">
				<div class="mb-4">
					<svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" viewBox="0 0 24 24" fill="none"
						stroke="#19c1a7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<circle cx="12" cy="12" r="10"></circle>
						<polyline points="12 6 12 12 16 14"></polyline>
					</svg>
				</div>
				<h1 class="fw-bold text-dark" style="font-size: 1.75rem;">Menunggu Kelulusan Agensi Admin</h1>
				<p class="text-muted mt-3 mb-4" style="line-height: 1.6; font-size: 1rem;">
					Kata laluan anda telah berjaya ditetapkan. Akaun anda <strong>belum boleh digunakan</strong> sehingga
					Agensi Admin meluluskan permohonan anda di sistem.
				</p>
				<p class="text-muted small mb-4">
					Emel pemberitahuan telah dihantar kepada pentadbir agensi berkenaan. Anda akan menerima emel
					selepas permohonan diluluskan.
				</p>
				<a href="{{ url('auth/login') }}" class="btn btn-primary btn-lg">Kembali ke Log Masuk</a>
			</div>
		</div>
	</div>
@endsection
