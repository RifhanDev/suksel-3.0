@extends('layouts.default')
@section('content')

	<h2 class="pull-left">
		Senarai Rekod Chat
	</h2>

	<div class="clearfix"></div>
	<hr>
	<table class="DT-index table table-striped table-hover table-bordered">
		<thead class="bg-blue-selangor">
			<tr>
				<th>No.</th>
				<th>Kategori Soalan</th>
				<th>Soalan</th>
				<th>Chat ID</th>
				<th>Maklumbalas Pengguna</th>
				<th>Tarikh</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>

@endsection
@section('scripts')

<link href="{{ asset('custom_library/dataTables/jquery.dataTables.css') }}" rel="stylesheet">
<script src="{{ asset('custom_library/dataTables/jquery.dataTables.js') }}"></script>
<script type="text/javascript">
	
	let table = $('.DT-index').DataTable({
		processing: true,
		serverSide: true,
		ajax: "{{ route('chatbot-manager.chatlog.index') }}",
		columns: [
			{ data: 'id', name: 'id'},
			{ data: 'faq_category_name', name: 'faq_category_name' },
			{ data: 'question', name: 'question' },
			{ data: 'chat_id', name: 'chat_id' },
			{ data: 'user_response', name: 'user_response' },
			{ data: 'created_at', name: 'created_at' },
		],
		serverSide: true,
		stateSave: true,
		language: {
			"url" : "{{ asset('custom_library/dataTables/lang/ms.json') }}"
		},
        columnDefs: [
            {
                searchable: false,
                orderable: false,
                targets: 0,
            },
        ],
        order: [[1, 'asc']],
	});
 
    table.on('order.dt search.dt', function () {
        let i = 1;
 
        table.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
            this.data(i++);
        });
    }).draw();

</script>

@endsection