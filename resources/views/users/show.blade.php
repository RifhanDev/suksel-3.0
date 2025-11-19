@extends('layouts.modern')

@section('content')
	<?php $currentUser = Auth::user(); ?>

	<div class="row">
		<div class="col-lg-9">
			<div class="page-header">
				<div class="page-title">
					<div class="page-pretitle">
						Sistem Tender Online
					</div>
				</div>
			</div>

			<h2 class="page-title">
				<i class="ti ti-user me-2"></i>Lihat Pengguna
				@if ($user->confirmed === 1)
					<span class="badge bg-success ms-2">{{ $user->status() }}</span>
				@else
					<span class="badge bg-warning ms-2">{{ $user->status() }}</span>
				@endif
			</h2>
			<br>

			<div class="card">
				<div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
					<h3 class="card-title mb-0">
						<i class="ti ti-info-circle me-2"></i>Maklumat Pengguna
					</h3>
					<div class="d-flex gap-2">
						<a href="{{ asset('users/' . $user->id . '/edit') }}" class="btn btn-primary btn-sm">
							<i class="ti ti-edit me-1"></i>Kemaskini
						</a>
						<a href="{{ asset('users') }}" class="btn btn-outline-primary btn-sm">
							<i class="ti ti-arrow-left me-1"></i>Kembali ke Senarai
						</a>
					</div>
				</div>
				<div class="card-body">
					<div class="row mb-3">
						<div class="col-md-3 fw-bold text-muted">
							<i class="ti ti-user me-1"></i>Nama:
						</div>
						<div class="col-md-9">
							{{ $user->name }}
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-md-3 fw-bold text-muted">
							<i class="ti ti-mail me-1"></i>Alamat Emel:
						</div>
						<div class="col-md-9">
							{{ $user->email }}
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-md-3 fw-bold text-muted">
							<i class="ti ti-shield me-1"></i>Peranan:
						</div>
						<div class="col-md-9">
							@foreach ($user->roles as $role)
								<span class="badge bg-primary me-1">{{ $role->name }}</span>
							@endforeach
						</div>
					</div>
					@if ($user->organization_unit_id)
						<div class="row mb-3">
							<div class="col-md-3 fw-bold text-muted">
								<i class="ti ti-building me-1"></i>Agensi:
							</div>
							<div class="col-md-9">
								{{ $user->organizationUnit->name ?? '-' }}
							</div>
						</div>
					@endif
					<div class="row mb-3">
						<div class="col-md-3 fw-bold text-muted">
							<i class="ti ti-status-change me-1"></i>Status:
						</div>
						<div class="col-md-9">
							@if ($user->confirmed === 1)
								<span class="badge bg-success">{{ $user->status() }}</span>
							@else
								<span class="badge bg-warning">{{ $user->status() }}</span>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-3">
			<div class="row">
				<div class="col-12">
					@include('layouts._register')
				</div>
				<div class="col-12">
					@include('layouts._news')
				</div>
			</div>
		</div>
	</div>
@endsection
