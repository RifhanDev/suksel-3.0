@extends('layouts.default')
@section('content')
	<h2>Maklumat Email SMTP</h2>
	<hr>
	<form class="form-horizontal">
		<div class="form-group">
			<label for="mail_server" class="control-label col-lg-3 col-sm-3">Mail Server</label>
			<div class="col-lg-9 col-sm-9">
				<input class="form-control" readonly id="mail_server" name="mail_server" type="text" value="{{ $data->mail_server }}">
			</div>
		</div>

		<div class="form-group">
			<label for="mail_port" class="control-label col-lg-3 col-sm-3">Mail Port</label>
			<div class="col-lg-9 col-sm-9">
				<input class="form-control" readonly id="mail_port" name="mail_port" type="number" max="65535" value="{{ $data->mail_port }}">
			</div>
		</div>

		<div class="form-group">
			<label for="mail_crypto" class="control-label col-lg-3 col-sm-3">Mail Encryption</label>
			<div class="col-lg-9 col-sm-9">
				<select name="mail_crypto" id="mail_crypto" class="form-control form-select" disabled>
					<option value="0" {{ ($data->mail_crypto ?? 0) == 0 ? "selected" : ""  }}>NONE</option>
					<option value="1" {{ ($data->mail_crypto ?? 0) == 1 ? "selected" : ""  }}>TLS</option>
					<option value="2" {{ ($data->mail_crypto ?? 0) == 2 ? "selected" : ""  }}>SSL</option>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label for="mail_username" class="control-label col-lg-3 col-sm-3">Mail Username</label>
			<div class="col-lg-9 col-sm-9">
				<input class="form-control" readonly id="mail_username" name="mail_username" type="text" value="{{ $data->mail_username }}">
			</div>
		</div>

		<div class="form-group">
			<label for="mail_password" class="control-label col-lg-3 col-sm-3">Mail Password</label>
			<div class="col-lg-9 col-sm-9">
				<input class="form-control" readonly id="mail_password" name="mail_password" type="password" value="********" readonly>
			</div>
		</div>

		<div class="form-group">
			<label for="mail_message_ratelimit" class="control-label col-lg-3 col-sm-3">Daily Messages Limit</label>
			<div class="col-lg-9 col-sm-9">
				<input class="form-control" readonly id="mail_message_ratelimit" name="mail_message_ratelimit" type="number" value="{{ $data->mail_message_ratelimit }}">
			</div>
		</div>

		<div class="well">
			<a href="{{ route('mail-manager.smtp-setting.edit', ['smtp_setting' => $data->enc_id]) }}" class="btn btn-primary pull-left">Kemaskini</a>
			<a href="{{ route('mail-manager.smtp-setting.index') }}" class="btn btn-default pull-right">Senarai Email SMTP</a><br/>&nbsp;
		</div>
	</form>

@endsection