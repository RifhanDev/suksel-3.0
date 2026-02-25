@extends('layouts.modern')

@section('styles')
	<style>
		.pdfobject-container {
			height: 60rem;
		}
	</style>
@endsection
@section('content')
	<h2 class="tender-title">Aduan Saya</h2>

	<table data-path="/my-aduan" class="DT-index table table-striped table-hover table-bordered">
		<thead class="bg-blue-selangor">
			<tr>
				<th>Subjek</th>
				<th>Isu utama / Modul</th>
				<th>Kandungan</th>
				<th>Status</th>
				<th>Tarikh Aduan</th>
				<th width="200px">&nbsp;</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>

	<div class="mt-3">
		<a href="{{ route('aduan.create') }}" class="btn btn-primary">Hantar Aduan Baru</a>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/datatables.js') }}"></script>

	<script>
		$('.DT-index').each(function() {
			var target = $(this);
			var path = target.data('path');

			var DT = target.DataTable({
				order: [
					[4, 'desc']
				],
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
						data: 'content',
						name: 'content'
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
						name: 'actions'
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
				aaSorting: []
			});
		});
	</script>
@endsection
