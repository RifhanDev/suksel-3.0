@extends('layouts.default')
@section('content')

	<h2 class="pull-left">
		Senarai Email SMTP
	</h2>

	<div class="clearfix"></div>
	<hr>
	<table class="DT-index table table-striped table-hover table-bordered">
		<thead class="bg-blue-selangor">
			<tr>
				<th>Server</th>
				<th>Port</th>
				<th>Username</th>
				<th>Limit Email Sehari</th>
				<th width="200px">&nbsp;</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
	<div class="well">
		<a href="{{ route('mail-manager.smtp-setting.create') }}" class="btn btn-default">Tambah Email SMTP</a>
	</div>

@endsection
@section('scripts')

<link href="{{ asset('custom_library/dataTables/jquery.dataTables.css') }}" rel="stylesheet">
<script src="{{ asset('custom_library/dataTables/jquery.dataTables.js') }}"></script>
<script type="text/javascript">

	var target = $('.DT-index');
	var path = target.data('path');
	
	let table = $('.DT-index').DataTable({
		processing: true,
		serverSide: true,
		ajax: path,
		columns: [
			{ data: 'mail_server', name: 'mail_server' },
			{ data: 'mail_port', name: 'mail_port' },
			{ data: 'mail_username', name: 'mail_username' },
			{ data: 'mail_message_ratelimit', name: 'mail_message_ratelimit' },
			{ data: 'actions', name: 'actions' },
		],
		serverSide: true,
		stateSave: true,
		language: {
			"url" : "{{ asset('custom_library/dataTables/lang/ms.json') }}"
		}
	});

</script>
	
@endsection