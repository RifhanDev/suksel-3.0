@extends('layouts.default')
@section('content')

	<h2 class="pull-left">
		Senarai Soalan ChatBot
	</h2>

	<div class="clearfix"></div>
	<hr>
	<table class="DT-index table table-striped table-hover table-bordered">
		<thead class="bg-blue-selangor">
			<tr>
				<th>No.</th>
				<th>Soalan</th>
				<th>Jawapan</th>
				<th>Kategori Soalan</th>
				<th width="200px">&nbsp;</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
	<div class="well">
		<a href="{{ route('chatbot-manager.question.create') }}" class="btn btn-default">Tambah Soalan</a>
	</div>

@endsection
@section('scripts')

<link href="{{ asset('custom_library/dataTables/jquery.dataTables.css') }}" rel="stylesheet">
<script src="{{ asset('custom_library/dataTables/jquery.dataTables.js') }}"></script>
<script type="text/javascript">
	
	let table = $('.DT-index').DataTable({
		processing: true,
		serverSide: true,
		ajax: "{{ route('chatbot-manager.question.index') }}",
		columns: [
			{ data: 'id', name: 'id'},
			{ data: 'question', name: 'question' },
			{ data: 'answer', name: 'answer' },
			{ data: 'faq_category_name', name: 'faq_category_name' },
			{ data: 'actions', name: 'actions' },
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

<script>
	function popupDelete(id)
	{
		$("#modal_title").html('<h4 class="modal-title"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>&emsp;<b>Amaran</b></h4>');
		$("#button_cancel").html("Tidak");
		$("#button_confirm").html("Ya");
		$("#button_confirm").attr("type", "submit");


		let modal_body = "<div><h5><b>Adakah anda pasti untuk memadam rekod ini ?</b></h5>"+ '@csrf @method("delete")' + "</div>";
		$("#modal_body").html(modal_body);
		$("#myPopupForm").attr("method", "POST");
		$("#myPopupForm").attr("action", "{{ route('chatbot-manager.question.index') }}" + "/" + id);



		$('#myPopup').modal('show');
	}
</script>
@endsection