@extends('layouts.v3.master')
@section('content')
<div class="row">
	<div class="col-lg-3">
		<div class="card hidden-print">
			<div class="card-header">
				<h3 class="card-title">
					<i class="ti ti-book me-2"></i>Panduan Pengguna
				</h3>
			</div>
			<div class="list-group list-group-flush">
				@if ($user)
				<!-- VENDOR -->
				<a href="#syarikat" class="list-group-item list-group-item-action" data-bs-toggle="collapse">
					<i class="ti ti-building me-2"></i>SYARIKAT
					<i class="ti ti-chevron-down ms-auto"></i>
				</a>
				<div class="collapse" id="syarikat">
					<a href="{{ asset('manuals/pendaftaran') }}" class="list-group-item list-group-item-action ps-4">Daftar
						Syarikat</a>
					<a href="{{ asset('manuals/pengesahan_syarikat') }}"
						class="list-group-item list-group-item-action ps-4">Pengesahan Syarikat</a>
					<a href="{{ asset('manuals/renew') }}" class="list-group-item list-group-item-action ps-4">Perbaharui
						Langganan</a>
					<a href="{{ asset('manuals/menu_utama_syarikat') }}" class="list-group-item list-group-item-action ps-4">Menu
						Utama</a>
					<a href="{{ asset('manuals/pembelian_tender') }}" class="list-group-item list-group-item-action ps-4">Pembelian
						Tender</a>
					<a href="{{ asset('manuals/permintaan_kemaskini') }}"
						class="list-group-item list-group-item-action ps-4">Permintaan Kemaskini MOF & CIDB</a>
					<a href="{{ asset('manuals/membuat_pdf') }}" class="list-group-item list-group-item-action ps-4">Cara membuat fail
						pdf</a>
					<a href="{{ asset('manuals/kemaskini_emel') }}" class="list-group-item list-group-item-action ps-4">Kemaskini
						Alamat emel atau No pendaftaran syarikat</a>
					<a href="{{ asset('manuals/kemaskini_syarikat') }}" class="list-group-item list-group-item-action ps-4">Kemaskini
						Maklumat Lain</a>
				</div>

				@if ($user->ability(['Admin'], ['Vendor:approve']))
				<!-- REGISTRATION ASSESSOR -->
				<a href="#umum" class="list-group-item list-group-item-action" data-bs-toggle="collapse">
					<i class="ti ti-check-circle me-2"></i>PENGESAHAN
					<i class="ti ti-chevron-down ms-auto"></i>
				</a>
				<div class="collapse" id="umum">
					<a href="{{ action('ManualsController@show', 'pengesahan_pendaftaran') }}"
						class="list-group-item list-group-item-action ps-4">Pengesahan Pendaftaran</a>
					<a href="{{ action('ManualsController@show', 'pengesahan_kemaskini') }}"
						class="list-group-item list-group-item-action ps-4">Pengesahan Permintaan Kemaskini</a>
				</div>
				@endif

				@if ($user->ability(['Admin'], ['Tender:create']))
				<!-- AGENCY USER -->
				<a href="#tender" class="list-group-item list-group-item-action" data-bs-toggle="collapse">
					<i class="ti ti-file-text me-2"></i>TENDER
					<i class="ti ti-chevron-down ms-auto"></i>
				</a>
				<div class="collapse" id="tender">
					<a href="{{ action('ManualsController@show', 'tambah_tender') }}"
						class="list-group-item list-group-item-action ps-4">TAMBAH TENDER</a>
					<a href="{{ action('ManualsController@show', 'siar') }}"
						class="list-group-item list-group-item-action ps-4">SIAR/BATAL SIAR TENDER</a>
					<a href="{{ action('ManualsController@show', 'rekod') }}"
						class="list-group-item list-group-item-action ps-4">MEREKOD SYARIKAT</a>
					<a href="{{ action('ManualsController@show', 'carta') }}"
						class="list-group-item list-group-item-action ps-4">CARTA TENDER</a>
				</div>
				@endif

				@if ($user->ability(['Admin', 'Agency Admin'], []))
				<!-- AGENCY ADMIN -->
				<a href="#aadmin" class="list-group-item list-group-item-action" data-bs-toggle="collapse">
					<i class="ti ti-settings me-2"></i>PENGURUSAN TENDER
					<i class="ti ti-chevron-down ms-auto"></i>
				</a>
				<div class="collapse" id="aadmin">
					@if ($user->ability(['Admin'], []))
					<a href="{{ action('ManualsController@show', 'senarai_hitam_admin') }}"
						class="list-group-item list-group-item-action ps-4">TAMBAH SENARAI HITAM</a>
					<a href="{{ action('ManualsController@show', 'senarai_hitam_batal') }}"
						class="list-group-item list-group-item-action ps-4">BATAL SENARAI HITAM</a>
					@endif
					<a href="{{ action('ManualsController@show', 'senarai_hitam') }}"
						class="list-group-item list-group-item-action ps-4">PAPAR SENARAI HITAM</a>
					<a href="{{ action('ManualsController@show', 'senarai_berita') }}"
						class="list-group-item list-group-item-action ps-4">SENARAI BERITA</a>
				</div>

				<!-- USER ACCESS MANAGEMENT -->
				<a href="#aadmin-akses" class="list-group-item list-group-item-action" data-bs-toggle="collapse">
					<i class="ti ti-users me-2"></i>PENGURUSAN AKSES PENGGUNA
					<i class="ti ti-chevron-down ms-auto"></i>
				</a>
				<div class="collapse" id="aadmin-akses">
					<a href="{{ action('ManualsController@show', 'mohon_id_agensi') }}"
						class="list-group-item list-group-item-action ps-4">PERMOHONAN PENGGUNA AGENSI</a>
					<a href="{{ action('ManualsController@show', 'nilai_id_agensi') }}"
						class="list-group-item list-group-item-action ps-4">PENILAIAN PENGGUNA AGENSI</a>
				</div>

				<!-- USER VERIFICATION -->
				<a href="#aadmin-semak" class="list-group-item list-group-item-action" data-bs-toggle="collapse">
					<i class="ti ti-eye me-2"></i>SEMAK AKSES PENGGUNA
					<i class="ti ti-chevron-down ms-auto"></i>
				</a>
				<div class="collapse" id="aadmin-semak">
					<a href="{{ action('ManualsController@show', 'semak_akaun') }}"
						class="list-group-item list-group-item-action ps-4">SEMAK AKAUN</a>
					<a href="{{ action('ManualsController@show', 'status_semak') }}"
						class="list-group-item list-group-item-action ps-4">PAPAR STATUS SEMAK AKAUN</a>
				</div>
				@endif
				@else
				<!-- PUBLIC -->
				<a href="#public" class="list-group-item list-group-item-action" data-bs-toggle="collapse">
					<i class="ti ti-world me-2"></i>UMUM
					<i class="ti ti-chevron-down ms-auto"></i>
				</a>
				<div class="collapse" id="public">
					<a href="{{ action('ManualsController@show', 'pendaftaran') }}"
						class="list-group-item list-group-item-action ps-4">DAFTAR SYARIKAT</a>
					<a href="{{ action('ManualsController@show', 'forgot_pass') }}"
						class="list-group-item list-group-item-action ps-4">LUPA KATA LALUAN</a>
				</div>
				@endif
			</div>
		</div>

		<div class="text-center mt-3 hidden-print">
			<a href="javascript:window.print()" class="btn btn-primary w-100">
				<i class="ti ti-printer me-2"></i>Cetak Manual Ini
			</a>
		</div>
	</div>

	<div class="col-lg-9">
		<div class="card">
			<div class="card-body">
				<div id="markdown-content" class="markdown-content">{{ $content }}</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/markdown.js') }}"></script>
<script>
	$(document).ready(function() {
		$('#markdown-content').html(markdown.toHTML($('#markdown-content').html()));
	});
</script>
@endsection