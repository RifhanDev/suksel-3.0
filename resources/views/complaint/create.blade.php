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
		<i class="ti ti-message-circle me-2"></i>Aduan
	</h2>
	<br>

	<div class="card">
		<div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
			<h3 class="card-title mb-0">
				<i class="ti ti-file-text me-2"></i>Hantar Aduan
			</h3>
			<a href="{{ auth()->check() ? route('my.aduan.index') : url('/') }}" class="btn btn-outline-secondary btn-sm">
				<i class="ti ti-arrow-left me-1"></i>Kembali
			</a>
		</div>
		<div class="card-body">
			{!! Former::open(url('aduan')) !!}
			@include('complaint.form')
			<div class="d-flex justify-content-end gap-2 mt-4">
				{!! Former::submit('Hantar')->class('btn btn-primary') !!}
			</div>
			{!! Former::close() !!}
		</div>
	</div>
@stop
