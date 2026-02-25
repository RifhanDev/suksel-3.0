@extends('layouts.modern')
@section('content')
	<div class="page-header">
		<div class="page-title">
			<div class="page-pretitle">
				Sistem Tender Online
			</div>
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
			@if ($complaint->module_label)
			<div class="row mb-3">
				<div class="col-md-3 fw-bold text-muted">
					<i class="ti ti-category me-1"></i>Isu utama / Modul:
				</div>
				<div class="col-md-9">
					{{ $complaint->module_label }}
				</div>
			</div>
			@endif
			@if ($complaint->tender_id && $complaint->tender)
			<div class="row mb-3">
				<div class="col-md-3 fw-bold text-muted">
					<i class="ti ti-file-text me-1"></i>Tender / Sebut Harga:
				</div>
				<div class="col-md-9">
					<a href="{{ url('tenders/' . $complaint->tender_id) }}">{{ $complaint->tender->ref_number }} – {{ $complaint->tender->name }}</a>
				</div>
			</div>
			@endif
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
