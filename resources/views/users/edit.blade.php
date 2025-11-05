@extends('layouts.modern')

@section('content')
	<?php $user = Auth::user(); ?>

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
				<i class="ti ti-user-edit me-2"></i>Kemaskini Pengguna
				@if ($currentUser->confirmed == 1)
					<span class="badge bg-success ms-2">{{ $currentUser->status() }}</span>
				@else
					<span class="badge bg-secondary ms-2">{{ $currentUser->status() }}</span>
				@endif
			</h2>
			<br>

			<div class="card">
				<div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
					<h3 class="card-title mb-0">
						<i class="ti ti-forms me-2"></i>Maklumat Pengguna
					</h3>
					<a href="{{ asset('users') }}" class="btn btn-outline-primary btn-sm">
						<i class="ti ti-arrow-left me-1"></i>Kembali ke Senarai
					</a>
				</div>
				<div class="card-body">
					{!! Former::open(url('users/' . $currentUser->id)) !!}
					{!! Former::populate($currentUser) !!}
					{!! Former::hidden('_method', 'PUT') !!}
					@include('users.form')
					<div class="form-group mt-4">
						<div class="d-flex gap-2">
							<button type="submit" class="btn btn-primary">
								<i class="ti ti-device-floppy me-1"></i>Simpan
							</button>
							<a href="{{ asset('users') }}" class="btn btn-outline-secondary">
								<i class="ti ti-x me-1"></i>Batal
							</a>
						</div>
					</div>
					{!! Former::close() !!}
				</div>
			</div>

			<div class="card mt-3">
				<div class="card-header">
					<h3 class="card-title mb-0">
						<i class="ti ti-settings me-2"></i>Tindakan Pengguna
					</h3>
				</div>
				<div class="card-body">
					<div class="d-flex flex-wrap gap-2">
						@if ($currentUser->canLogin())
							<a href="{{ asset('users/' . $currentUser->id . '/login') }}" class="btn btn-danger">
								<i class="ti ti-login me-1"></i>Login Sebagai
							</a>
						@endif

						@if (Auth::user()->hasRole('Admin') && !$currentUser->confirmed)
							<a href="{{ action('UsersController@resendConfirmation', $currentUser->id) }}" class="btn btn-outline-primary">
								<i class="ti ti-mail me-1"></i>Hantar Emel Pengesahan
							</a>
						@endif

						@if ($currentUser->canSetPassword())
							<a href="{{ action('UsersController@getSetPassword', $currentUser->id) }}" class="btn btn-outline-primary">
								<i class="ti ti-key me-1"></i>Tukar Kata Laluan
							</a>
						@endif

						@if ($currentUser->canSetConfirmation())
							{!! Former::open(action('UsersController@putSetConfirmation', $currentUser->id))->class('d-inline') !!}
							{!! Former::hidden('_method', 'PUT') !!}
							{!! Former::hidden('confirmed', !$currentUser->confirmed) !!}
							<button type="submit" class="btn btn-warning">
								<i class="ti ti-{{ $currentUser->confirmed ? 'user-off' : 'user-check' }} me-1"></i>
								{{ $currentUser->confirmed ? 'Nyahaktif' : 'Aktifkan' }}
							</button>
							{!! Former::close() !!}
						@endif

						@if ($currentUser->canDelete())
							{!! Former::open(route('users.destroy', $currentUser->id))->class('d-inline') !!}
							{!! Former::hidden('_method', 'DELETE') !!}
							<button type="button" class="btn btn-danger confirm-delete">
								<i class="ti ti-trash me-1"></i>Padam
							</button>
							{!! Former::close() !!}
						@endif
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
