@extends('layouts.modern')

@section('content')

<?php $user = Auth::user(); ?>

	<div class="row">
		<div class="col-lg-12">
			<div class="page-header-modern">
				<div class="page-pretitle">
					<i class="ti ti-chart-line me-2"></i>Sistem Tender Online
				</div>
				<h2>
					<i class="ti ti-chart-line me-2"></i>Tukar Kata Laluan
				</h2>
			</div>

			{{-- <div class="page-header-modern">
				<div class="page-title">
					<div class="page-pretitle">
						Sistem Tender Online
					</div>
				</div>
			</div>

			<h2 class="page-title">
				<i class="ti ti-key me-2"></i>Tukar Kata Laluan
			</h2>
			<br> --}}

			<div class="card">
				<div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
					<h3 class="card-title mb-0">
						<i class="ti ti-shield-lock me-2"></i>Maklumat Kata Laluan
					</h3>
					@if ($currentUser->hasRole('Vendor'))
						<a href="{{ asset('vendors/' . $currentUser->vendor_id) }}" class="btn btn-outline-primary btn-sm">
							<i class="ti ti-building me-1"></i>Maklumat Syarikat
						</a>
					@else
						@if (isset($currentUser) && $user->canUpdate())
							<a href="{{ asset('users/' . $currentUser->id . '/edit') }}" class="btn btn-outline-primary btn-sm">
								<i class="ti ti-user-edit me-1"></i>Maklumat Pengguna
							</a>
						@endif
					@endif
				</div>
				<div class="card-body">
					{!! Former::open(url('users/' . $currentUser->id . '/reset_password')) !!}
					{!! Former::hidden('_method', 'PUT') !!}
					{!! Former::populate($currentUser) !!}

					@if ($currentUser->hasRole('Vendor'))
						{!! Former::text('vendor')->forceValue($currentUser->vendor->name)->label('Nama Syarikat')->disabled() !!}
					@endif

					{!! Former::text('name')->disabled()->label('Nama') !!}
					{!! Former::text('email')->disabled()->label('Alamat Emel') !!}
					{!! Former::password('password')->required()->pattern('.{8,}')->autocomplete('new-password')->help(
					        'Sekurang-kurangnya 8 aksara dan kombinasi antara abjad dan nombor, huruf besar dan kecil. Sila tukar kata laluan setiap 6 bulan',
					    )->label('Kata Laluan') !!}
					{!! Former::password('password_confirmation')->required()->autocomplete('new-password')->label('Pastikan Kata Laluan') !!}

					<div class="form-group mt-4">
						<div class="d-flex justify-content-start gap-2 flex-wrap">
							@if ($currentUser->hasRole('Vendor'))
								<a href="{{ asset('vendors/' . $currentUser->vendor_id) }}" class="btn btn-outline-secondary">
									<i class="ti ti-arrow-left me-1"></i>Kembali
								</a>
							@else
								@if (App\User::canList())
									<a href="{{ asset('users') }}" class="btn btn-outline-secondary">
										<i class="ti ti-arrow-left me-1"></i>Kembali ke Senarai
									</a>
								@elseif (isset($currentUser) && $user->canUpdate())
									<a href="{{ asset('users/' . $currentUser->id . '/edit') }}" class="btn btn-outline-secondary">
										<i class="ti ti-arrow-left me-1"></i>Kembali
									</a>
								@endif
							@endif
							<button type="submit" class="btn btn-primary">
								<i class="ti ti-device-floppy me-1"></i>Kemaskini
							</button>
						</div>
					</div>
					{!! Former::close() !!}
				</div>
			</div>
		</div>
	@endsection
