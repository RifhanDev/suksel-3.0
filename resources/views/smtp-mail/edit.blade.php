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
	
	<form id="saveForm" name="saveForm" action="{{ route('mail-manager.smtp-setting.update', ["smtp_setting" => $data->enc_id]) }}" method="POST" class="form-horizontal">
		@csrf
		<input type="hidden" name="_method" value="PUT">
		<div class="form-group">
			<label for="mail_server" class="control-label col-lg-3 col-sm-3">Mail Server</label>
			<div class="col-lg-9 col-sm-9">
				<input class="form-control" required="" id="mail_server" name="mail_server" type="text" value="{{ old('mail_server') ?? $data->mail_server }}">
				@error('mail_server')
					<div class="alert alert-danger p-0 m-0">** {{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="form-group">
			<label for="mail_port" class="control-label col-lg-3 col-sm-3">Mail Port</label>
			<div class="col-lg-9 col-sm-9">
				<input class="form-control" required="" id="mail_port" name="mail_port" type="number" max="65535" value="{{ old('mail_port') ?? $data->mail_port }}">
				@error('mail_port')
					<div class="alert alert-danger p-0 m-0">** {{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="form-group">
			<label for="mail_crypto" class="control-label col-lg-3 col-sm-3">Mail Encryption</label>
			<div class="col-lg-9 col-sm-9">
				<select name="mail_crypto" id="mail_crypto" class="form-control form-select">
					<option value="0" {{ (old('mail_crypto') ?? $data->mail_crypto) == 0 ? "selected" : ""  }}>NONE</option>
					<option value="1" {{ (old('mail_crypto') ?? $data->mail_crypto) == 1 ? "selected" : ""  }}>TLS</option>
					<option value="2" {{ (old('mail_crypto') ?? $data->mail_crypto) == 2 ? "selected" : ""  }}>SSL</option>
				</select>
				@error('mail_crypto')
					<div class="alert alert-danger p-0 m-0">** {{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="form-group">
			<label for="mail_username" class="control-label col-lg-3 col-sm-3">Mail Username</label>
			<div class="col-lg-9 col-sm-9">
				<input class="form-control" required="" id="mail_username" name="mail_username" type="text" value="{{ old('mail_username') ?? $data->mail_username }}">
				@error('mail_username')
					<div class="alert alert-danger p-0 m-0">** {{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="form-group">
			<label for="mail_password" class="control-label col-lg-3 col-sm-3">Mail Password</label>
			<div class="col-lg-9 col-sm-9">
				<input class="form-control" id="mail_password" name="mail_password" type="password" value="{{ old('mail_password') ?? '********' }}">
				@error('mail_password')
					<div class="alert alert-danger p-0 m-0">** {{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="form-group">
			<label for="mail_message_ratelimit" class="control-label col-lg-3 col-sm-3">Daily Messages Limit</label>
			<div class="col-lg-9 col-sm-9">
				<input class="form-control" required="" id="mail_message_ratelimit" name="mail_message_ratelimit" type="number" value="{{ old('mail_message_ratelimit') ?? $data->mail_message_ratelimit }}">
				@error('mail_message_ratelimit')
					<div class="alert alert-danger p-0 m-0">** {{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="well">
			<button type="submit" form="saveForm" class="btn btn-primary">Kemaskini</button>
			<button type="button" class="btn btn-danger" id="delete" name="delete" data-toggle="modal" data-target="#exampleModal">Padam</button>

			<a href="{{ route('mail-manager.smtp-setting.index') }}" class="btn btn-default pull-right">Senarai Email SMTP</a>
		</div>
	</form>




	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"><b>Amaran</b></h4>
				</div>
				<div class="modal-body">
					<h5>Adakah anda pasti untuk memadam rekod ini ?</h5>
				</div>
				<div class="modal-footer">
					<div class="pull-right">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
					</div>
					<form action="{{ route('mail-manager.smtp-setting.destroy', ['smtp_setting' => $data->enc_id]) }}" method="POST">
						@csrf
						<input type="hidden" name="_method" value="DELETE">
						<div class="pl-1">
							<button type="submit" class="btn btn-primary">Ya</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

@endsection