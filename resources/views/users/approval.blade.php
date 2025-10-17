@extends('layouts.default')
@section('content')

	<?php $currentUser = $currentUser ?: Auth::user(); ?>
	<h2>
		Sahkan Pengguna
		@if($currentUser->confirmed == 1)
			<?php $class = "pull-right label label-lg label-success pull-right" ?>
		@else
			<?php $class = "pull-right label label-lg label-default pull-right" ?>
		@endif
		<span class="{{ $class }}">{{ $currentUser->status() }}</span>
	</h2>
	<br>

	{!! Former::open(route('users.store-approval', $currentUser->id)) !!}
		{!! Former::populate($currentUser) !!}
		{!! Former::hidden('_method', 'PUT') !!}
		@include('users.approval-form')
		{!! Former::radios('approved')
			->label('Kelulusan')
			->radios([
				'Lulus' => ['name' => 'approved', 'value' => '1'],
				'Tolak' => ['name' => 'approved', 'value' => '0']
			])
			->required() !!}
		
		<div id="remarkDropdown" style="display: none">
			{!! Former::select('remark_dropdown')
				->id('remarkDropdown')
				->label('Catatan')
				->placeholder('Pilih Catatan')
				->options(App\PredefinedRemark::all()->pluck('remark', 'remark'), '') !!}
		</div>
		
		<div id="remarkTxt" style="display: none">
			{!! Former::textarea('remark_txt')
				->label('Catatan')
				->rows(5) !!}
		</div>
		
		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-3">
				<input type="submit" class="btn btn-primary" value="Simpan">
			</div>
		</div>

	{!! Former::close() !!}
	
	<div class="well">
		@if(Auth::user()->hasRole('Admin') && !$currentUser->confirmed)
			<a href="{{ asset('users/'.$currentUser->id.'/resend_confirmation') }}" class="btn btn-default">Hantar
			Emel Pengesahan</a>
		@endif
		
		@if($currentUser->canSetPassword())
			<a href="{{ asset('users/'.$currentUser->id.'/reset_password') }}" class="btn btn-default">
				Tukar Kata Laluan
			</a>
		@endif
		
		@if($currentUser->canSetConfirmation())
			{!! Former::inline_open(url('users/'.$currentUser->id.'/confirm')) !!}
				{!! Former::hidden('_method', 'PUT') !!}
				{!! Former::hidden('confirmed', !$currentUser->confirmed) !!}
				<button type="submit"
				class="btn btn-warning">{{ $currentUser->confirmed ? 'Nyahaktif' : 'Aktifkan' }}</button>
			{!! Former::close() !!}
		@endif
		
		@if($currentUser->canDelete())
			{!! Former::inline_open(url('users/'.$currentUser->id)) !!}
				{!! Former::hidden('_method', 'DELETE') !!}
				<button type="button" class="btn btn-danger confirm-delete">Padam</button>
			{!! Former::close() !!}
		@endif
		
		<a href="{{ asset('users') }}" class="btn btn-default pull-right">Senarai Pengguna</a>
	</div>
@endsection

@section('scripts')

	@parent
	<script type="text/javascript">
		var approved = $('input:radio[name=approved]')
		showRemark(approved)
		
		approved.on('change', function () {
			showRemark(this)
		})
		
		function showRemark (approved) {
			if (approved.value === '1') {
				$('#remarkTxt').show()
				$('#remarkDropdown').hide()
			} else if (approved.value === '0') {
				$('#remarkDropdown').show()
				$('#remarkTxt').hide()
			}
		}
	</script>

@endsection
