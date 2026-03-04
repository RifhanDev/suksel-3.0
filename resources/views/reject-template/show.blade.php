@extends('layouts.v3.master')

@section('content')
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Paparan Templat Penolakan</h3>
			<p class="text-muted small m-0">Sistem Tender Online Selangor</p>
		</div>
		<div class="d-flex flex-wrap align-items-center gap-3">
			@if ($template->canUpdate())
				<a href="{{ asset('reject-template/' . $template->id . '/edit') }}" class="btn btn-info d-flex align-items-center gap-2 text-white">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
					Kemaskini
				</a>
			@endif
			<div class="bg-white px-3 py-2 rounded-2 shadow-sm border d-flex align-items-center gap-2">
				<span class="badge bg-light text-dark border">TARIKH</span>
				<span class="small text-muted fw-bold">{{ $template->updated_at ? $template->updated_at->format('d/m/Y') : date('d/m/Y') }}</span>
			</div>
		</div>
	</div>

	<div class="stats-card mb-4">
		<div class="stats-card-header p-4 border-bottom">
			<div class="d-flex align-items-center gap-3">
				<div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-2" style="width: 38px; height: 38px;">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
				</div>
				<h3 class="m-0 fw-bold" style="font-size: 1.1rem; color: #1e293b;">{{ $template->title }}</h3>
			</div>
		</div>
		<div class="card-body p-4">
			<dl class="row mb-0">
				<dt class="col-sm-3 text-muted">Tajuk</dt>
				<dd class="col-sm-9">{{ $template->title }}</dd>
				<dt class="col-sm-3 text-muted">Kandungan</dt>
				<dd class="col-sm-9">{!! nl2br(e($template->content)) !!}</dd>
				<dt class="col-sm-3 text-muted">Digunapakai</dt>
				<dd class="col-sm-9">{{ is_array($template->applicable) ? implode(', ', $template->applicable) : $template->applicable }}</dd>
			</dl>
		</div>
	</div>

	<div class="stats-card">
		<div class="card-body p-4">
			<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
				<a href="{{ asset('reject-template') }}" class="btn btn-secondary d-flex align-items-center gap-2">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
					Kembali ke Senarai
				</a>
				<div class="d-flex gap-2 flex-wrap">
					@if ($template->canUpdate())
						<a href="{{ asset('reject-template/' . $template->id . '/edit') }}" class="btn btn-selangor d-flex align-items-center gap-2">Kemaskini</a>
					@endif
					@if ($template->canDelete())
						<form action="{{ url('reject-template/' . $template->id) }}" method="POST" class="d-inline">
							@csrf
							@method('DELETE')
							<button type="button" class="btn btn-danger d-flex align-items-center gap-2 confirm-delete">Padam</button>
						</form>
					@endif
				</div>
			</div>
		</div>
	</div>
@endsection
