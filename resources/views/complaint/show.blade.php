@extends('layouts.v3.master')

@section('content')
	<!-- Page Header -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Lihat Aduan</h3>
			<p class="text-muted small m-0">Paparan butiran aduan yang diterima.</p>
		</div>
	</div>

	<div class="content-card">
		<div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
			<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
				stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 0 2 2z"></path>
			</svg>
			<span class="fw-bold text-dark text-uppercase small">Maklumat Aduan</span>
		</div>

		<div class="p-4">
			<div class="row g-3">
				<!-- Subjek -->
				<div class="col-12">
					<label for="subject" class="form-label fw-medium small">Subjek</label>
					<input class="form-control" id="subject" name="subject" type="text"
						value="{{ $complaint->subject }}" disabled>
				</div>

				<!-- Kandungan -->
				<div class="col-12">
					<label for="content" class="form-label fw-medium small">Kandungan</label>
					<textarea class="form-control" id="content" name="content" rows="6"
						style="height: 300px; overflow-y: auto; resize: none;" disabled>{{ $complaint->content }}</textarea>
				</div>

				<!-- Email -->
				<div class="col-12">
					<label for="email" class="form-label fw-medium small">Email</label>
					<input class="form-control" id="email" name="email" type="text"
						value="{{ $complaint->email }}" disabled>
				</div>

				<!-- Status -->
				<div class="col-md-6">
					<label for="status" class="form-label fw-medium small">Status</label>
					<input class="form-control" id="status" name="status" type="text"
						value="{{ $complaint->complaintStatus() }}" disabled>
				</div>

				<!-- Tarikh -->
				<div class="col-md-6">
					<label for="tarikh" class="form-label fw-medium small">Tarikh</label>
					<input class="form-control" id="tarikh" name="tarikh" type="text"
						value="{{ Carbon::parse($complaint->created_at)->format('j M Y h:i a') }}" disabled>
				</div>
			</div>
		</div>

		<div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center gap-2 p-4 border-top bg-light">
			<a href="{{ asset('aduan/list') }}" class="btn-form btn-form-secondary">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
					fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<line x1="19" y1="12" x2="5" y2="12"></line>
					<polyline points="12 19 5 12 12 5"></polyline>
				</svg>
				Senarai Aduan
			</a>
			@if (App\Models\Complaint::canApprove())
				<div class="d-flex flex-column flex-sm-row gap-2">
					@if ($complaint->status == 0)
						<a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 1]) }}"
							class="btn-form link-confirm" style="background:#0d9488;color:#fff;">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
								stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<polyline points="20 6 9 17 4 12"></polyline>
							</svg>
							Ambil Maklum
						</a>
						<a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 2]) }}"
							class="btn-form link-confirm" style="background:#d97706;color:#fff;">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
								stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<circle cx="12" cy="12" r="10"></circle>
								<polyline points="12 6 12 12 16 14"></polyline>
							</svg>
							Dalam Tindakan
						</a>
						<a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 3]) }}"
							class="btn-form btn-form-success link-confirm">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
								stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
								<polyline points="22 4 12 14.01 9 11.01"></polyline>
							</svg>
							Selesai
						</a>
						<a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 4]) }}"
							class="btn-form btn-form-danger link-confirm">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
								stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<circle cx="12" cy="12" r="10"></circle>
								<line x1="15" y1="9" x2="9" y2="15"></line>
								<line x1="9" y1="9" x2="15" y2="15"></line>
							</svg>
							Ditolak
						</a>
					@elseif ($complaint->status == 1)
						<a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 2]) }}"
							class="btn-form link-confirm" style="background:#d97706;color:#fff;">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
								stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<circle cx="12" cy="12" r="10"></circle>
								<polyline points="12 6 12 12 16 14"></polyline>
							</svg>
							Dalam Tindakan
						</a>
						<a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 3]) }}"
							class="btn-form btn-form-success link-confirm">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
								stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
								<polyline points="22 4 12 14.01 9 11.01"></polyline>
							</svg>
							Selesai
						</a>
						<a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 4]) }}"
							class="btn-form btn-form-danger link-confirm">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
								stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<circle cx="12" cy="12" r="10"></circle>
								<line x1="15" y1="9" x2="9" y2="15"></line>
								<line x1="9" y1="9" x2="15" y2="15"></line>
							</svg>
							Ditolak
						</a>
					@elseif ($complaint->status == 2)
						<a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 3]) }}"
							class="btn-form btn-form-success link-confirm">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
								stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
								<polyline points="22 4 12 14.01 9 11.01"></polyline>
							</svg>
							Selesai
						</a>
						<a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 4]) }}"
							class="btn-form btn-form-danger link-confirm">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
								stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<circle cx="12" cy="12" r="10"></circle>
								<line x1="15" y1="9" x2="9" y2="15"></line>
								<line x1="9" y1="9" x2="15" y2="15"></line>
							</svg>
							Ditolak
						</a>
					@endif
				</div>
			@endif
		</div>
	</div>

	<h2 class="page-title">
		<i class="ti ti-message-circle me-2"></i>Lihat Aduan
		@if ($complaint->status == 0)
			<span class="badge bg-secondary ms-2">Baru</span>
		@elseif($complaint->status == 1)
			<span class="badge bg-info ms-2">Ambil Maklum</span>
		@elseif($complaint->status == 2)
			<span class="badge bg-warning ms-2">Dalam Tindakan</span>
		@elseif($complaint->status == 3)
			<span class="badge bg-success ms-2">Selesai</span>
		@elseif($complaint->status == 4)
			<span class="badge bg-danger ms-2">Ditolak</span>
		@endif
	</h2>
	<br>

	<!-- Complaint Details Card -->
	<div class="card">
		<div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
			<h3 class="card-title mb-0">
				<i class="ti ti-info-circle me-2"></i>Maklumat Aduan
			</h3>
			<a href="{{ route('aduan.index') }}" class="btn btn-outline-primary btn-sm">
				<i class="ti ti-arrow-left me-1"></i>Kembali ke Senarai
			</a>
		</div>
		<div class="card-body">
			<div class="row mb-3">
				<div class="col-md-3 fw-bold text-muted">
					<i class="ti ti-file-text me-1"></i>Subjek:
				</div>
				<div class="col-md-9">
					{{ $complaint->subject }}
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-md-3 fw-bold text-muted">
					<i class="ti ti-notes me-1"></i>Kandungan:
				</div>
				<div class="col-md-9">
					<div
						style="max-height: 300px; overflow-y: auto; padding: 1rem; background-color: #f8f9fa; border-radius: 8px; white-space: pre-wrap;">
						{{ $complaint->content }}</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-md-3 fw-bold text-muted">
					<i class="ti ti-mail me-1"></i>Email:
				</div>
				<div class="col-md-9">
					{{ $complaint->email }}
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-md-3 fw-bold text-muted">
					<i class="ti ti-status-change me-1"></i>Status:
				</div>
				<div class="col-md-9">
					{{ $complaint->complaintStatus() }}
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-md-3 fw-bold text-muted">
					<i class="ti ti-calendar me-1"></i>Tarikh Aduan:
				</div>
				<div class="col-md-9">
					{{ Carbon::parse($complaint->created_at)->format('j M Y h:i a') }}
				</div>
			</div>
			@if ($complaint->admin_reply)
				<hr>
				<div class="row mb-3">
					<div class="col-md-3 fw-bold text-muted">
						<i class="ti ti-message-reply me-1"></i>Balasan Admin:
					</div>
					<div class="col-md-9">
						<div
							style="padding: 1rem; background-color: #e7f3ff; border-left: 4px solid #007bff; border-radius: 8px; white-space: pre-wrap;">
							{{ $complaint->admin_reply }}</div>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-md-3 fw-bold text-muted">
						<i class="ti ti-user me-1"></i>Dibalas Oleh:
					</div>
					<div class="col-md-9">
						{{ $complaint->repliedBy ? $complaint->repliedBy->name : 'N/A' }}
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-md-3 fw-bold text-muted">
						<i class="ti ti-clock me-1"></i>Tarikh Balasan:
					</div>
					<div class="col-md-9">
						{{ $complaint->replied_at ? Carbon::parse($complaint->replied_at)->format('j M Y h:i a') : 'N/A' }}
					</div>
				</div>
			@endif
		</div>
	</div>

	<!-- Reply Form Card (if admin and no reply yet) -->
	@if (App\Models\Complaint::canApprove() && !$complaint->admin_reply)
		<div class="card mt-4">
			<div class="card-header">
				<h3 class="card-title mb-0">
					<i class="ti ti-message-reply me-2"></i>Balas Aduan
				</h3>
			</div>
			<div class="card-body">
				<form action="{{ route('aduan.reply', $complaint->id) }}" method="POST">
					@csrf
					<div class="mb-3">
						<label for="admin_reply" class="form-label fw-bold">Balasan:</label>
						<textarea name="admin_reply" id="admin_reply" class="form-control" rows="6" required
						 placeholder="Masukkan balasan anda di sini..."></textarea>
					</div>
					<button type="submit" class="btn btn-primary">
						<i class="ti ti-send me-1"></i>Hantar Balasan
					</button>
				</form>
			</div>
		</div>
	@endif

	<!-- Action Buttons Card -->
	@if (App\Models\Complaint::canApprove())
		<div class="card mt-4">
			<div class="card-header">
				<h3 class="card-title mb-0">
					<i class="ti ti-settings me-2"></i>Tindakan Status
				</h3>
			</div>
			<div class="card-body">
				<div class="d-flex flex-wrap gap-2">
					@if ($complaint->status == 0)
						<a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 1]) }}" class="btn btn-info link-confirm">
							<i class="ti ti-check me-1"></i>Ambil Maklum
						</a>
						<a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 2]) }}"
							class="btn btn-warning link-confirm">
							<i class="ti ti-clock me-1"></i>Dalam Tindakan
						</a>
						<a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 3]) }}"
							class="btn btn-success link-confirm">
							<i class="ti ti-check-circle me-1"></i>Selesai
						</a>
						<a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 4]) }}"
							class="btn btn-danger link-confirm">
							<i class="ti ti-x me-1"></i>Ditolak
						</a>
					@elseif ($complaint->status == 1)
						<a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 2]) }}"
							class="btn btn-warning link-confirm">
							<i class="ti ti-clock me-1"></i>Dalam Tindakan
						</a>
						<a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 3]) }}"
							class="btn btn-success link-confirm">
							<i class="ti ti-check-circle me-1"></i>Selesai
						</a>
						<a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 4]) }}"
							class="btn btn-danger link-confirm">
							<i class="ti ti-x me-1"></i>Ditolak
						</a>
					@elseif ($complaint->status == 2)
						<a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 3]) }}"
							class="btn btn-success link-confirm">
							<i class="ti ti-check-circle me-1"></i>Selesai
						</a>
						<a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 4]) }}"
							class="btn btn-danger link-confirm">
							<i class="ti ti-x me-1"></i>Ditolak
						</a>
					@endif
				</div>
			</div>
		</div>
	@endif
@stop
