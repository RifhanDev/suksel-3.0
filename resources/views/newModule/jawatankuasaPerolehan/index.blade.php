@extends('layouts.v3.master')

@section('content')
	<style>
		#tbl-perolehan {
			border-collapse: collapse !important;
		}

		#tbl-perolehan thead th {
			background: #2f4b95 !important;
			color: #fff !important;
			font-size: 13px;
			font-weight: 600;
			text-transform: none !important;
			border: 1px solid #7c7c7c !important;
			padding: 10px 8px !important;
			vertical-align: middle;
		}

		#tbl-perolehan tbody td {
			border: 1px solid #9a9a9a !important;
			font-size: 13px;
			color: #222;
			padding: 12px 10px !important;
			vertical-align: top;
		}

		#tbl-perolehan tbody tr:hover {
			background: #f8f9fc !important;
		}

		.jp-status-text {
			font-size: 20px;
			font-weight: 500;
			line-height: 1.1;
			display: inline-block;
			margin-top: 2px;
		}

		.jp-kuiti-wrap {
			display: flex;
			flex-direction: column;
			align-items: center;
			gap: 8px;
		}

		.jp-kuiti-input {
			width: 100%;
			max-width: 130px;
			height: 34px;
			border: 1px solid #b8b8b8;
			border-radius: 4px;
			background: #fff;
		}

		.jp-kuiti-btn {
			height: 22px;
			line-height: 1;
			padding: 0 14px;
			border: 0;
			border-radius: 4px;
			background: #14b8a6;
			color: #fff;
			font-size: 12px;
		}
	</style>

	<!-- HEADER -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div>
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Jawatankuasa Perolehan</h3>
			<p class="text-muted small m-0">Senarai tender / sebut harga yang memerlukan tindakan jawatankuasa.</p>
		</div>
	</div>

	<!-- FILTER -->
	<div class="card border shadow-sm mb-3 rounded-3">
		<div class="card-body p-3">
			<div class="row g-2 align-items-end">

				<div class="col-12 col-lg-2">
					<label class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Tender</label>
					<input type="text" id="filter_no_tender" class="form-control form-control-sm" placeholder="Cth: QT21000...">
				</div>

				<div class="col-12 col-lg-4">
					<label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tajuk Perolehan</label>
					<input type="text" id="filter_tajuk" class="form-control form-control-sm" placeholder="Cari tajuk projek...">
				</div>

				<div class="col-6 col-lg-2">
					<label class="form-label small fw-bold text-secondary text-uppercase mb-1">Status</label>
					<select id="filter_status" class="form-select form-select-sm">
						<option value="">Semua</option>
						<option value="Dalam Proses">Dalam Proses</option>
						<option value="Selesai">Selesai</option>
					</select>
				</div>

				<div class="col-6 col-lg-2">
					<label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tarikh Serahan</label>
					<input type="text" id="filter_tarikh" class="form-control form-control-sm" placeholder="dd/mm/yyyy">
				</div>

				<div class="col-12 col-lg-2">
					<div class="d-flex gap-2">
						<button type="button" id="btn_reset_filter" class="btn btn-md btn-light border w-100">Reset</button>
						<button type="button" id="btn_apply_filter" class="btn btn-md btn-selangor fw-medium w-100">Tapis</button>
					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- TABLE -->
	<div class="content-card p-0">
		<div class="content-card-header p-4 pb-3 border-bottom">
			<div class="d-flex align-items-center gap-3">
				<div class="content-card-icon" style="width: 38px; height: 38px;">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
						stroke-linecap="round" stroke-linejoin="round">
						<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
						<polyline points="14 2 14 8 20 8"></polyline>
						<line x1="16" y1="13" x2="8" y2="13"></line>
						<line x1="16" y1="17" x2="8" y2="17"></line>
					</svg>
				</div>
				<h3 class="content-card-title" style="font-size: 1rem;">Senarai Tender</h3>
			</div>
		</div>

		<div class="content-card-body p-2">
			<div class="table-responsive">
				<table id="tbl-perolehan" class="table table-hover align-middle mb-0 w-100">
					<thead class="bg-light">
						<tr>
							<th class="ps-2">No. Tender/Sebut Harga</th>
							<th>Tajuk Perolehan</th>
							<th width="140px">Tarikh Serahan</th>
							<th width="140px">Tempoh Sah Laku</th>
							<th width="130px">Status</th>
							<th class="text-center" width="150px">Tindakan</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function() {

			const formUrl = "{{ route('jawatankuasa.perolehan.form') }}";
			const backendData = @json($tenders ?? []);
			const tableData = backendData.map(function(row) {
				const statusLabel = row.status_label || 'Dalam Proses';
				const statusMap = {
					'Dalam Proses': 'Bidaan',
					'Selesai': 'Selesai'
				};
				const statusText = statusMap[statusLabel] || statusLabel;

				return {
					id: row.id || '',
					uuid: row.uuid || '',
					no_tender: row.no_tender || '-',
					tajuk: row.tajuk || '-',
					tarikh_serahan: row.tarikh_serahan || '-',
					tempoh_sah_laku: row.tempoh_sah_laku || '-',
					status: statusText,
					tindakan: '<div class="jp-kuiti-wrap"><input type="text" class="jp-kuiti-input" /><a href="' +
						formUrl +
						'?tender=' + encodeURIComponent(row.id || '') +
						'" class="jp-kuiti-btn d-inline-flex align-items-center justify-content-center">Kuiti</a></div>'
				};
			});

			var DT = $('#tbl-perolehan').DataTable({
				data: tableData,
				columns: [{
						data: 'no_tender'
					},
					{
						data: 'tajuk'
					},
					{
						data: 'tarikh_serahan'
					},
					{
						data: 'tempoh_sah_laku'
					},
					{
						data: 'status',
						orderable: false
					},
					{
						data: 'tindakan',
						orderable: false,
						searchable: false,
						className: 'text-center'
					}
				],
				columnDefs: [{
						targets: 4,
						render: function(data) {
							return data;
						}
					},
					{
						targets: 5,
						render: function(data) {
							return data;
						}
					}
				],
				language: {
					sEmptyTable: "Tiada data",
					sInfo: "Paparan dari _START_ hingga _END_ dari _TOTAL_ rekod",
					sInfoEmpty: "Paparan 0 hingga 0 dari 0 rekod",
					sInfoFiltered: "(Ditapis dari jumlah _MAX_ rekod)",
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
					}
				},
				pageLength: 25,
				responsive: true,
				order: []
			});

			function escapeRegex(value) {
				return (value || '').replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
			}

			function applyFilters() {
				const filterNoTender = ($('#filter_no_tender').val() || '').toString().trim();
				const filterTajuk = ($('#filter_tajuk').val() || '').toString().trim();
				const filterStatus = ($('#filter_status').val() || '').toString().trim();
				const filterTarikh = ($('#filter_tarikh').val() || '').toString().trim();

				DT.column(0).search(filterNoTender, false, true);
				DT.column(1).search(filterTajuk, false, true);
				DT.column(2).search(filterTarikh, false, true);

				if (filterStatus) {
					const statusSearch = filterStatus === 'Dalam Proses' ? 'Bidaan' : filterStatus;
					DT.column(4).search(escapeRegex(statusSearch), true, false);
				} else {
					DT.column(4).search('');
				}

				DT.draw();
			}

			// Apply Filter
			$('#btn_apply_filter').on('click', function() {
				applyFilters();
			});

			$('#filter_no_tender, #filter_tajuk, #filter_tarikh').on('keydown', function(e) {
				if (e.key === 'Enter') {
					e.preventDefault();
					applyFilters();
				}
			});

			$('#filter_status').on('change', function() {
				applyFilters();
			});

			// Reset Filter
			$('#btn_reset_filter').on('click', function() {
				$('#filter_no_tender').val('');
				$('#filter_tajuk').val('');
				$('#filter_status').val('');
				$('#filter_tarikh').val('');

				DT.column(0).search('');
				DT.column(1).search('');
				DT.column(2).search('');
				DT.column(4).search('');
				DT.draw();
			});
		});
	</script>
@endsection
