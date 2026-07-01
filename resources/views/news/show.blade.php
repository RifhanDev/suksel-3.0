@extends('layouts.v3.master')

@section('content')
	@php
		$showEditButton = $news->canUpdate();
		$showDeleteButton = $news->canDelete();
	@endphp

	<!-- Page Header -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Paparan Berita</h3>
			<p class="text-muted small m-0">Sistem Perolehan</p>
		</div>
		<div class="d-flex flex-wrap align-items-center gap-3 bg-white px-3 py-2 rounded-2 shadow-sm border">
			<div class="d-flex align-items-center gap-2">
				<span class="badge bg-light text-dark border">TARIKH</span>
				<span class="small text-muted fw-bold">{{ \Carbon\Carbon::parse($news->published_at ?: $news->created_at)->format('d/m/Y') }}</span>
			</div>
		</div>
	</div>

	<!-- News Content Card -->
	<div class="stats-card mb-4">
		<div class="stats-card-header p-4 border-bottom">
			<div class="d-flex align-items-center gap-3">
				<div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-2" style="width: 38px; height: 38px;">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 20l-7-7-7 7V4a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>
				</div>
				<div>
					<h3 class="m-0 fw-bold" style="font-size: 1.1rem; color: #1e293b;">{{ $news->title }}</h3>
					<div class="text-muted small mt-1">
						<i class="ti ti-building me-1"></i>
						Oleh <a href="{{ asset('agencies/' . $news->agency->id) }}" class="text-decoration-none" style="color: #c41e3a;">{{ $news->agency->name }}</a>
						@if ($news->tender)
							&bullet;
							<i class="ti ti-file-text me-1"></i>
							untuk <a href="{{ asset('tenders/' . $news->tender->id) }}" class="text-decoration-none" style="color: #c41e3a;">{{ $news->tender->name }}</a>
						@endif
					</div>
				</div>
			</div>
		</div>
		<div class="card-body p-4">
			<div class="news-content" style="line-height: 1.8; color: #495057;">
				{!! $news->notification !!}
			</div>
		</div>
	</div>

	<!-- Action Buttons -->
	<div class="stats-card">
		<div class="card-body p-4">
			<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
				<a href="{{ asset('news') }}" class="btn btn-secondary d-flex align-items-center gap-2">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
					Arkib Berita
				</a>
				<div class="d-flex gap-2 flex-wrap">
					@if ($showEditButton)
						<a href="{{ asset('news/' . $news->id . '/edit') }}" class="btn btn-info d-flex align-items-center gap-2 text-white">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
							Kemaskini
						</a>
						@if (!$news->publish)
							<a href="{{ asset('news/' . $news->id . '/publish') }}" class="btn btn-success d-flex align-items-center gap-2 link-confirm">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
								Siar
							</a>
						@else
							<a href="{{ asset('news/' . $news->id . '/publish') }}" class="btn btn-warning d-flex align-items-center gap-2 link-confirm">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
								Batal Siar
							</a>
						@endif
					@endif
					@if ($showDeleteButton)
						<form action="{{ action('NewsController@destroy', $news->id) }}" method="POST" class="d-inline">
							@csrf
							@method('DELETE')
							<button type="button" class="btn btn-danger d-flex align-items-center gap-2 confirm-delete">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
								Padam
							</button>
						</form>
					@endif
				</div>
			</div>
		</div>
	</div>
@endsection
