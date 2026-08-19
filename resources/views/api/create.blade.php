@extends('layouts.v3.master')

@section('styles')
	<link href="{{ asset('css/components/form-components.css') }}" rel="stylesheet">
@endsection

@section('content')
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Klien &amp; Token Baharu</h3>
			<p class="text-muted small m-0">Token Sanctum dijana automatik selepas simpan, dan boleh dilihat semula dalam senarai.</p>
		</div>
	</div>

	<form action="{{ route('apitoken.store') }}" method="POST">
		@csrf

		<div class="content-card">
			<div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
					stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
					<path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
				</svg>
				<span class="fw-bold text-dark text-uppercase small">Maklumat Klien</span>
			</div>

			<div class="p-4">
				<div class="row g-3">
					<div class="col-12">
						<label for="name" class="form-label fw-medium small">
							Nama Klien <span class="text-danger">*</span>
						</label>
						<input type="text" name="name" id="name" class="form-control" required
							placeholder="cth. AUFA, MBSA, Client A"
							value="{{ old('name') }}">
						<div class="form-text">Nama mesti unik. Klien A dan Klien B tidak boleh berkongsi token.</div>
					</div>

					<div class="col-12">
						<label for="organization_unit_id" class="form-label fw-medium small">Agensi</label>
						<select name="organization_unit_id" id="organization_unit_id" class="form-select">
							<option value="">— Tiada —</option>
							@foreach ($agencies as $agency)
								<option value="{{ $agency->id }}" @selected(old('organization_unit_id') == $agency->id)>
									{{ $agency->name }}
								</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>

			<div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
				<a href="{{ route('apitoken.index') }}" class="btn-form btn-form-secondary">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<line x1="19" y1="12" x2="5" y2="12"></line>
						<polyline points="12 19 5 12 12 5"></polyline>
					</svg>
					Batal
				</a>
				<button type="submit" class="btn-form btn-form-primary">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
						<polyline points="17 21 17 13 7 13 7 21"></polyline>
						<polyline points="7 3 7 8 15 8"></polyline>
					</svg>
					Jana Token Sanctum
				</button>
			</div>
		</div>
	</form>
@endsection
