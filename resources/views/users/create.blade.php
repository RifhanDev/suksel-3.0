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
					<div class="form-group">
						<label class="form-label">Cara Menetapkan Kata Laluan</label>
						<div class="form-selectgroup form-selectgroup-boxes d-flex flex-column gap-2">
							<label class="form-selectgroup-item">
								<input type="radio" name="password_option" value="assign" class="form-selectgroup-input" checked>
								<span class="form-selectgroup-label d-flex align-items-center p-3">
									<span class="me-3">
										<span class="form-selectgroup-check"></span>
									</span>
									<span>
										<strong>Tetapkan Kata Laluan Sekarang</strong>
										<span class="d-block text-muted small">Masukkan kata laluan untuk pengguna</span>
									</span>
								</span>
							</label>
							<label class="form-selectgroup-item">
								<input type="radio" name="password_option" value="reset" class="form-selectgroup-input">
								<span class="form-selectgroup-label d-flex align-items-center p-3">
									<span class="me-3">
										<span class="form-selectgroup-check"></span>
									</span>
									<span>
										<strong>Hantar Emel Reset Kata Laluan</strong>
										<span class="d-block text-muted small">Pengguna akan menerima emel untuk menetapkan kata laluan sendiri</span>
									</span>
								</span>
							</label>
						</div>
					</div>
					<div id="password-fields">
						{!! Former::password('password')->label('Kata Laluan')->help('Sekurang-kurangnya 8 aksara, satu simbol, satu nombor, satu huruf besar dan satu huruf kecil')->required()->autocomplete('new-password') !!}
						{!! Former::password('password_confirmation')->label('Sahkan Kata Laluan')->required()->autocomplete('new-password') !!}
					</div>
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

@section('scripts')
	@parent
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const passwordOption = document.querySelectorAll('input[name="password_option"]');
			const passwordFields = document.getElementById('password-fields');
			const passwordInputs = passwordFields.querySelectorAll('input[type="password"]');

			passwordOption.forEach(radio => {
				radio.addEventListener('change', function() {
					if (this.value === 'reset') {
						passwordFields.style.display = 'none';
						passwordInputs.forEach(input => {
							input.removeAttribute('required');
							input.value = '';
						});
					} else {
						passwordFields.style.display = 'block';
						passwordInputs.forEach(input => {
							input.setAttribute('required', 'required');
						});
					}
				});
			});
		});
	</script>
@endsection
