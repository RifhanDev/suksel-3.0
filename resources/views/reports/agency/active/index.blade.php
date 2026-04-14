@extends('layouts.v3.master')

@section('content')

	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div>
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Laporan 10 Agensi Aktif</h3>
			<p class="text-muted small m-0">Jana laporan agensi aktif sistem tender online.</p>
		</div>
	</div>

	<div class="content-card p-4">
		<form action="{{ url('reports/agency/active') }}" method="POST" target="_blank">
			@csrf

			<div class="mb-3">
				<label for="type" class="form-label fw-semibold">Jenis Laporan</label>
				<select name="type" id="type" class="form-select">
					<option value="">Pilih jenis laporan yang ingin dihasilkan...</option>
					@foreach ($select_type as $value => $label)
						<option value="{{ $value }}">{{ $label }}</option>
					@endforeach
				</select>
			</div>

			<div class="mb-4">
				<label for="year" class="form-label fw-semibold">Tahun</label>
				<select name="year" id="year" class="form-select">
					<option value="">Pilih tahun laporan yang ingin dihasilkan...</option>
					@foreach ($select_year as $value => $label)
						<option value="{{ $value }}">{{ $label }}</option>
					@endforeach
				</select>
			</div>

			<div>
				<button type="submit" class="btn-form btn-form-primary">Jana Laporan</button>
			</div>
		</form>
	</div>

@endsection
