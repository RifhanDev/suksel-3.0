@extends('layouts.modern')

@section('content')
	<div class="row">
		<div class="col-lg-9">
			<!-- Page Header -->
			<div class="page-header-modern">
				<div class="page-pretitle">
					<i class="ti ti-history me-2"></i>Sistem Tender Online Selangor
				</div>
				<div class="d-flex justify-content-between align-items-center">
					<h2>
						<i class="ti ti-eye me-2"></i>Lihat Rekod Versi
					</h2>
					<div class="d-flex gap-2">
						<a href="{{ route('version-histories.edit', $versionHistory->id) }}" class="btn btn-primary btn-modern">
							<i class="ti ti-edit me-1"></i>Kemaskini
						</a>
						<a href="{{ route('version-histories.index') }}" class="btn btn-outline-secondary btn-modern">
							<i class="ti ti-arrow-left me-1"></i>Kembali ke Senarai
						</a>
					</div>
				</div>
			</div>

			<div class="card modern-card">
				<div class="card-header" style="background: white; border-bottom: 1px solid #e9ecef;">
					<h3 class="card-title mb-0">
						<i class="ti ti-versions me-2"></i>Versi {{ $versionHistory->version }}
					</h3>
				</div>
				<div class="card-body">
					<dl class="row mb-0">
						<dt class="col-sm-3 text-muted">
							<i class="ti ti-tag me-1"></i>Versi
						</dt>
						<dd class="col-sm-9">
							<span class="badge bg-primary fs-6">{{ $versionHistory->version }}</span>
						</dd>

						<dt class="col-sm-3 text-muted">
							<i class="ti ti-calendar me-1"></i>Tarikh
						</dt>
						<dd class="col-sm-9">
							{{ $versionHistory->formatted_date }}
						</dd>

						<dt class="col-sm-3 text-muted">
							<i class="ti ti-notes me-1"></i>Nota
						</dt>
						<dd class="col-sm-9">
							@if (count($versionHistory->notes_lines) > 0)
								<ol class="mb-0 ps-3">
									@foreach ($versionHistory->notes_lines as $line)
										<li>{!! nl2br(e($line)) !!}</li>
									@endforeach
								</ol>
							@else
								<span class="text-muted">{{ $versionHistory->notes ?: '—' }}</span>
							@endif
						</dd>
					</dl>
				</div>
				<div class="card-footer d-flex justify-content-between">
					{!! Former::open(route('version-histories.destroy', $versionHistory->id))->class('d-inline') !!}
					{!! Former::hidden('_method', 'DELETE') !!}
					{{ csrf_field() }}
					<button type="button" class="btn btn-danger btn-modern confirm-delete">
						<i class="ti ti-trash me-1"></i>Padam
					</button>
					{!! Former::close() !!}
					<a href="{{ route('version-histories.index') }}" class="btn btn-outline-secondary btn-modern">
						<i class="ti ti-arrow-left me-1"></i>Kembali ke Senarai
					</a>
				</div>
			</div>
		</div>

		<div class="col-lg-3">
			@include('layouts._register')
			@include('layouts._news')
		</div>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/news.js') }}"></script>
@endsection
