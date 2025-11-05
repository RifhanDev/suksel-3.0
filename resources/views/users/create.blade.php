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
				<i class="ti ti-user-plus me-2"></i>Pengguna Baru
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
					{!! Former::open(url('users')) !!}
					@include('users.form')
					{!! Former::password('password')->label('Kata Laluan')->help('Sekurang-kurangnya 8 aksara, satu simbol, satu nombor, satu huruf besar dan satu huruf kecil')->required() !!}
					{!! Former::password('password_confirmation')->label('Sahkan Kata Laluan')->required() !!}
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
