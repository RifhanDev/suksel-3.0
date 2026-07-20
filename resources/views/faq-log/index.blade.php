@extends('layouts.v3.master')

@section('content')
	<!-- Page Header -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Rekod Chat</h3>
			<p class="text-muted small m-0">Paparan log rekod perbualan ChatBot.</p>
		</div>
	</div>

	<!-- Table -->
	<div class="content-card p-0">
		<div class="content-card-header p-4 pb-3 border-bottom">
			<div class="d-flex align-items-center gap-3">
				<div class="content-card-icon" style="width: 38px; height: 38px;">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
						stroke-linecap="round" stroke-linejoin="round">
						<line x1="8" y1="6" x2="21" y2="6"></line>
						<line x1="8" y1="12" x2="21" y2="12"></line>
						<line x1="8" y1="18" x2="21" y2="18"></line>
						<line x1="3" y1="6" x2="3.01" y2="6"></line>
						<line x1="3" y1="12" x2="3.01" y2="12"></line>
						<line x1="3" y1="18" x2="3.01" y2="18"></line>
					</svg>
				</div>
				<h3 class="content-card-title" style="font-size: 1rem;">Senarai Rekod</h3>
			</div>
		</div>

		<div class="content-card-body p-2">
			<div class="table-responsive">
				<table class="DT-index table table-hover align-middle mb-0 w-100">
					<thead class="bg-light">
						<tr>
							<th class="text-uppercase text-muted small fw-bold py-3 ps-4" style="width: 50px;">No.</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Kategori Soalan</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Soalan</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Chat ID</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Maklumbalas Pengguna</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Tarikh</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	{{-- <link href="{{ asset('custom_library/dataTables/jquery.dataTables.css') }}" rel="stylesheet"> --}}
	{{-- <script src="{{ asset('custom_library/dataTables/jquery.dataTables.js') }}"></script> --}}
	<script type="text/javascript">
		let table = $('.DT-index').DataTable({
			processing: true,
			serverSide: true,
			ajax: "{{ route('chatbot-manager.chatlog.index') }}",
			columns: [{
					data: 'id',
					name: 'id'
				},
				{
					data: 'faq_category_name',
					name: 'faq_category_name'
				},
				{
					data: 'question',
					name: 'question'
				},
				{
					data: 'chat_id',
					name: 'chat_id'
				},
				{
					data: 'user_response',
					name: 'user_response'
				},
				{
					data: 'created_at',
					name: 'created_at'
				},
			],
			stateSave: true,
			language: {
				// "url": "{{ asset('custom_library/dataTables/lang/ms.json') }}"
				sEmptyTable: "Tiada data",
				sInfo: "Paparan dari _START_ hingga _END_ dari _TOTAL_ rekod",
				sInfoEmpty: "Paparan 0 hingga 0 dari 0 rekod",
				sInfoFiltered: "(Ditapis dari jumlah _MAX_ rekod)",
				sInfoPostFix: "",
				sInfoThousands: ",",
				sLengthMenu: "Papar _MENU_ rekod",
				sLoadingRecords: "Diproses...",
				sProcessing: "Sedang diproses...",
				sSearch: "Carian:",
				sZeroRecords: "Tiada padanan rekod yang dijumpai.",
				oPaginate: {
					sFirst: "Pertama",
					sPrevious: "Sebelum",
					sNext: "Kemudian",
					sLast: "Akhir"
				},
				oAria: {
					sSortAscending: ": diaktifkan kepada susunan lajur menaik",
					sSortDescending: ": diaktifkan kepada susunan lajur menurun"
				}
			},
			autoWidth: false,
			columnDefs: [{
				searchable: false,
				orderable: false,
				targets: 0,
				width: '50px',
			}, ],
			order: [
				[1, 'asc']
			],
		});

		table.on('order.dt search.dt', function() {
			let i = 1;

			table.cells(null, 0, {
				search: 'applied',
				order: 'applied'
			}).every(function(cell) {
				this.data(i++);
			});
		}).draw();
	</script>
@endsection
