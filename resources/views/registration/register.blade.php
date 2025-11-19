@extends('layouts.modern')
@section('content')
	<div class="row justify-content-center">
		<div class="col-lg-8">
			<div class="page-header text-center mb-4">
				<h1 class="page-title">
					<i class="ti ti-building me-2"></i>Pendaftaran Syarikat
				</h1>
				<p class="page-subtitle text-muted">Daftar syarikat anda untuk menyertai sistem tender online Selangor</p>
			</div>

			<!-- Registration Steps -->
			<div class="card mb-4">
				<div class="card-body">
					<div class="steps steps-sm">
						<div class="step-item active">
							<div class="step-item-icon">
								<span class="step-item-icon-text">1</span>
							</div>
							<div class="step-item-content">
								<div class="step-item-title">Pengesahan Alamat Emel</div>
								<div class="step-item-subtitle">Masukkan maklumat asas syarikat</div>
							</div>
						</div>
						<div class="step-item">
							<div class="step-item-icon">
								<span class="step-item-icon-text">2</span>
							</div>
							<div class="step-item-content">
								<div class="step-item-title">Lengkapkan Maklumat Syarikat</div>
								<div class="step-item-subtitle">Isi maklumat terperinci syarikat</div>
							</div>
						</div>
						<div class="step-item">
							<div class="step-item-icon">
								<span class="step-item-icon-text">3</span>
							</div>
							<div class="step-item-content">
								<div class="step-item-title">Pembayaran Pendaftaran</div>
								<div class="step-item-subtitle">Selesaikan pembayaran yuran</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Existing Registration Check -->
			<div class="alert alert-info d-flex align-items-center">
				<i class="ti ti-info-circle me-2"></i>
				<div class="flex-fill">
					<strong>Pernah mendaftar dengan Sistem Tender Online Selangor?</strong>
				</div>
				<a href="{{ action('HomeController@companySearch') }}" class="btn btn-primary btn-sm">
					<i class="ti ti-search me-1"></i>Semak Pendaftaran Syarikat
				</a>
			</div>

			<!-- Registration Form -->
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">
						<i class="ti ti-mail me-2"></i>Pengesahan Alamat Emel
					</h3>
					<div class="card-subtitle">Langkah 1: Masukkan maklumat asas syarikat anda</div>
				</div>
				<div class="card-body">
					{!! Former::open(url('register'))->addClass('form-uppercase')->autocomplete('false') !!}

					<div class="row">
						<div class="col-lg-6">
							<div class="mb-3">
								{!! Former::text('company_no')->label('No Pendaftaran Syarikat')->required()->addClass('form-control')->placeholder('Masukkan nombor pendaftaran syarikat') !!}
							</div>
						</div>
						<div class="col-lg-6">
							<div class="mb-3">
								{!! Former::text('company_name')->label('Nama Syarikat')->required()->addClass('form-control')->placeholder('Masukkan nama syarikat') !!}
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-6">
							<div class="mb-3">
								{!! Former::text('name')->label('Nama Pendaftar')->required()->addClass('form-control')->placeholder('Masukkan nama pendaftar') !!}
							</div>
						</div>
						<div class="col-lg-6">
							<div class="mb-3">
								{!! Former::email('email')->label('Alamat Emel')->required()->addClass('form-control x-uppercase')->placeholder('contoh@email.com') !!}
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-6">
							<div class="mb-3">
								{!! Former::password('password')->label('Kata Laluan')->help('Sekurang-kurangnya 8 aksara, satu simbol, satu nombor, satu huruf besar dan satu huruf kecil')->required()->addClass('form-control x-uppercase')->placeholder('Masukkan kata laluan') !!}
							</div>
						</div>
						<div class="col-lg-6">
							<div class="mb-3">
								{!! Former::password('password_confirmation')->label('Pengesahan Kata Laluan')->help('Masukan semula kata laluan')->required()->addClass('form-control x-uppercase')->placeholder('Masukkan semula kata laluan') !!}
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<div class="d-flex justify-content-center">
								<button type="submit" class="btn btn-primary btn-lg">
									<i class="ti ti-mail me-2"></i>Sahkan Alamat Emel
								</button>
							</div>
						</div>
					</div>

					{!! Former::close() !!}
				</div>
			</div>
		</div>
	</div>
@endsection
