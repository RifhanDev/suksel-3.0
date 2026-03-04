@extends('layouts.v3.master')

@section('content')
	@include('components.modern-index', [
		'title' => 'Senarai Aduan',
		'pretitle' => 'Sistem Tender Online',
		'icon' => 'ti-message-circle',
		'cardTitle' => 'Maklumat Aduan',
		'createUrl' => null,
		'createLabel' => '',
		'showCreate' => false,
		'dataPath' => '/aduan/list',
		'columns' => [
			[
				'data' => 'subject',
				'name' => 'subject',
				'label' => 'Subjek',
				'icon' => 'ti-file-text',
			],
			[
				'data' => 'module',
				'name' => 'module',
				'label' => 'Isu utama / Modul',
				'icon' => 'ti-category',
				'width' => 'w-20',
			],
			[
				'data' => 'tender_id',
				'name' => 'tender_id',
				'label' => 'Tender',
				'icon' => 'ti-file-text',
				'width' => 'w-20',
			],
			[
				'data' => 'content',
				'name' => 'content',
				'label' => 'Kandungan',
				'icon' => 'ti-notes',
			],
			[
				'data' => 'email',
				'name' => 'email',
				'label' => 'Email',
				'icon' => 'ti-mail',
				'width' => 'w-20',
			],
			[
				'data' => 'status',
				'name' => 'status',
				'label' => 'Status',
				'icon' => 'ti-status-change',
				'width' => 'w-15',
				'class' => 'text-center',
			],
			[
				'data' => 'created_at',
				'name' => 'created_at',
				'label' => 'Tarikh Aduan',
				'icon' => 'ti-calendar',
				'width' => 'w-20',
				'class' => 'text-center',
			],
			[
				'data' => 'actions',
				'name' => 'actions',
				'label' => 'Tindakan',
				'icon' => 'ti-settings',
				'width' => 'w-15',
				'orderable' => false,
				'searchable' => false,
			],
		],
		'defaultOrder' => [[6, 'desc']],
		'pageLength' => 25,
	])
@endsection

@section('scripts')
	<script src="{{ asset('js/datatables.js') }}"></script>
	<script type="text/javascript">
		$('.DT-index').each(function() {
			var target = $(this);
			var path = target.data('path');

			var DT = target.DataTable({
				ajax: path,
				columns: [{
						data: 'subject',
						name: 'subject'
					},
					{
						data: 'module',
						name: 'module'
					},
					{
						data: 'tender_id',
						name: 'tender_id'
					},
					{
						data: 'content',
						name: 'content'
					},
					{
						data: 'email',
						name: 'email'
					},
					{
						data: 'status',
						name: 'status'
					},
					{
						data: 'created_at',
						name: 'created_at'
					},
					{
						data: 'actions',
						name: 'actions',
						orderable: false,
						searchable: false
					}
				],
				serverSide: true,
				stateSave: true,
				language: {
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
				aaSorting: [],
				dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
				pageLength: 25,
				responsive: true,
				order: [
					[6, 'desc']
				]
			});
		});
	</script>
@endsection
