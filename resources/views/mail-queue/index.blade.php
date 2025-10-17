@extends('layouts.default')
@section('content')

	<h2 class="pull-left">
		Rekod Penghantaran Email
	</h2>

	<div class="clearfix"></div>
	<hr>
	<table class="DT-index table table-striped table-hover table-bordered">
		<thead class="bg-blue-selangor">
			<tr>
				<th>Tajuk</th>
				<th>Tetapan</th>
				<th>Dijana Pada</th>
				<th>Dihantar pada</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
	<div class="well">
		{{-- <a href="{{ route('mail-manager.create') }}" class="btn btn-default">Tambah Email SMTP</a> --}}
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
		columnDefs: [
			{
			"targets": [1],
			"orderable": false
		}],
		columns: [
			{ data: 'subject', name: 'subject' },
			{ data: 'smtp_mail_id', name: 'smtp_mail_id' },
			{ data: 'created_at', name: 'created_at' },
			{ data: 'email_send_at', name: 'email_send_at' },
			{ data: 'status', name: 'status' },
		],
		serverSide: true,
		stateSave: true,
		language: {
			"url" : "{{ asset('custom_library/dataTables/lang/ms.json') }}"
		}
	});

</script>
	
@endsection