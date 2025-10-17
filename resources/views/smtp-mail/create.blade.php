@extends('layouts.default')
@section('content')
	<h2>Tambah Email SMTP</h2>
	<hr>
	@if ($errors->any())
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
	<form action="{{ route('mail-manager.smtp-setting.store') }}" method="POST" class="form-horizontal">
		@csrf
		<div class="form-group">
			<label for="mail_server" class="control-label col-lg-3 col-sm-3">Mail Server</label>
			<div class="col-lg-9 col-sm-9">
				<input class="form-control" required="" id="mail_server" name="mail_server" type="text" value="{{ old('mail_server') }}">
				@error('mail_server')
					<div class="alert alert-danger p-0 m-0">** {{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="form-group">
			<label for="mail_port" class="control-label col-lg-3 col-sm-3">Mail Port</label>
			<div class="col-lg-9 col-sm-9">
				<input class="form-control" required="" id="mail_port" name="mail_port" type="number" max="65535" value="{{ old('mail_port') }}">
				@error('mail_port')
					<div class="alert alert-danger p-0 m-0">** {{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="form-group">
			<label for="mail_crypto" class="control-label col-lg-3 col-sm-3">Mail Encryption</label>
			<div class="col-lg-9 col-sm-9">
				<select name="mail_crypto" id="mail_crypto" class="form-control form-select">
					<option value="0" {{ (old('mail_crypto') ?? 0) == 0 ? "selected" : ""  }}>NONE</option>
					<option value="1" {{ (old('mail_crypto') ?? 0) == 1 ? "selected" : ""  }}>TLS</option>
					<option value="2" {{ (old('mail_crypto') ?? 0) == 2 ? "selected" : ""  }}>SSL</option>
				</select>
				@error('mail_crypto')
					<div class="alert alert-danger p-0 m-0">** {{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="form-group">
			<label for="mail_username" class="control-label col-lg-3 col-sm-3">Mail Username</label>
			<div class="col-lg-9 col-sm-9">
				<input class="form-control" required="" id="mail_username" name="mail_username" type="text" value="{{ old('mail_username') }}">
				@error('mail_username')
					<div class="alert alert-danger p-0 m-0">** {{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="form-group">
			<label for="mail_password" class="control-label col-lg-3 col-sm-3">Mail Password</label>
			<div class="col-lg-9 col-sm-9">
				<input class="form-control" id="mail_password" name="mail_password" type="password" value="{{ old('mail_password') }}">
				@error('mail_password')
					<div class="alert alert-danger p-0 m-0">** {{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="form-group">
			<label for="mail_message_ratelimit" class="control-label col-lg-3 col-sm-3">Daily Messages Limit</label>
			<div class="col-lg-9 col-sm-9">
				<input class="form-control" required="" id="mail_message_ratelimit" name="mail_message_ratelimit" type="number" value="{{ old('mail_message_ratelimit') }}">
				@error('mail_message_ratelimit')
					<div class="alert alert-danger p-0 m-0">** {{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="well">
			<button type="submit" class="btn btn-primary">Simpan</button>
			<button type="button" class="btn btn-primary" onclick="openModalTestMail()">Uji Tetapan SMTP Email</button>
			<a href="{{ route('mail-manager.smtp-setting.index') }}" class="btn btn-default pull-right">Senarai Email SMTP</a>
		</div>
	</form>

@endsection

@section('scripts')
	<script>
	function openModalTestMail()
	{
		$("#modal_title").html("<b>Pengujiaan tetapan SMTP mail</b>");
		$("#button_cancel").html("TUTUP");
		$("#button_confirm").html("TERUSKAN");		
		$('#button_confirm').attr('type', "submit");
		$('#myPopupForm').attr('action', '{{ env("MAIL_SAMPLER") }}');
		$('#myPopupForm').attr('method', "POST");

		$("#modal_body").empty();
		$("#modal_body").append("Sila Masukkan Destinasi Email yang ingin dihantar : <input class='form-control' type='email' id='target_email' name='target_email' value='' />");
		$("#modal_body").append("<input type='hidden' id='mail_host' name='mail_host' value='" + $("#mail_server").val() + "' />");
		$("#modal_body").append("<input type='hidden' id='mail_port' name='mail_port' value='" + $("#mail_port").val() + "' />");
		$("#modal_body").append("<input type='hidden' id='mail_crypto' name='mail_crypto' value='" + $("#mail_crypto option:selected").text() + "' />");
		$("#modal_body").append("<input type='hidden' id='mail_username' name='mail_username' value='" + $("#mail_username").val() + "' />");
		$("#modal_body").append("<input type='hidden' id='mail_password' name='mail_password' value='" + $("#mail_password").val() + "' />");

		$('#myPopup').modal('show');
	}
	</script>
@endsection