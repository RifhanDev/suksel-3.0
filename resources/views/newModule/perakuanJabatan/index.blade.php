@extends('layouts.v3.master')

@section('content')
	<style>
		.form-control:focus,
		.form-select:focus {
			border-color: var(--sg-red);
			box-shadow: 0 0 0 0.2rem rgba(196, 30, 58, 0.25);
		}

		.btn-tapis {
			background: var(--sg-red);
			border-color: var(--sg-red);
			color: white;
		}

		.btn-tapis:hover {
			background: var(--sg-red-dark);
			border-color: var(--sg-red-dark);
			color: white;
		}

		.project-table thead {
			background: #1F3A8A !important;
			color: white !important;
		}

		.project-table thead th {
			background: transparent !important;
			border: 1px solid rgba(255, 255, 255, 0.2) !important;
			color: white !important;
			font-weight: bold;
			text-align: center;
			padding: 12px;
		}

		.project-table td {
			padding: 12px;
			text-align: left;
			border: 1px solid var(--topbar-border, #e5e7eb);
			color: var(--sg-black);
		}

		.project-table tbody tr {
			background: white;
		}

		.project-table tbody tr:hover {
			background: var(--sg-bg);
		}

		.tender-number {
			font-weight: 600;
			color: var(--sg-red-dark);
			font-family: 'Courier New', monospace;
		}

		@media (max-width: 768px) {
			.project-table {
				font-size: 12px;
			}

			.project-table th,
			.project-table td {
				padding: 12px 10px;
			}
		}
	</style>
	<!-- HEADER -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div>
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Perakuan Jabatan</h3>
			<p class="text-muted small m-0">Senarai tender / sebut harga untuk perakuan jabatan.</p>
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

	{{-- Tender Table (DataTables) --}}
	<div class="bg-white rounded overflow-hidden mb-4">
		<div class="table-responsive">
			<table id="tbl-perakuan-jabatan" class="table project-table mb-0 w-100">
				<thead>
					<tr>
						<th style="width: 20%;">No. Tender/Sebut Harga</th>
						<th style="width: 45%;">Tajuk Perolehan</th>
						<th style="width: 15%;">Tarikh</th>
						<th style="width: 20%;">Status</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
			const backendData = @json($tenders ?? []);

			function escapeHtml(text) {
				return String(text ?? '').replace(/[&<>"']/g, function(m) {
					return ({
						'&': '&amp;',
						'<': '&lt;',
						'>': '&gt;',
						'"': '&quot;',
						"'": '&#039;'
					})[m];
				});
			}

			const tableData = backendData.map(function(row) {
				const statusLabel = row.status_label || 'Dalam Proses';
				const statusMap = {
					'Dalam Proses': 'Bidaan',
					'Selesai': 'Selesai'
				};
				const statusText = statusMap[statusLabel] || statusLabel;

				return {
					id: row.id || '-',
					no_tender: row.no_tender || '-',
					tajuk: row.tajuk || '-',
					tarikh: row.tarikh || '-',
					status: statusText,
					show_url: row.show_url || '#'
				};
			});

			var DT = $('#tbl-perakuan-jabatan').DataTable({
				data: tableData,
				columns: [{
						data: 'no_tender',
						render: function(data, type, row) {
							if (type === 'display') {
								return '<a href="' + escapeHtml(row.show_url) +
									'" class="text-decoration-none">' +
									'<span class="tender-number">' + escapeHtml(data) + '</span></a>';
							}
							return data;
						}
					},
					{
						data: 'tajuk',
						render: function(data, type) {
							if (type === 'display') {
								return '<span class="fw-medium">' + escapeHtml(data) + '</span>';
							}
							return data;
						}
					},
					{
						data: 'tarikh',
						render: function(data, type) {
							if (type === 'display') {
								return '<span class="text-muted small">' + escapeHtml(data) +
									'</span>';
							}
							return data;
						}
					},
					{
						data: 'status',
						orderable: false
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
				order: [],
				dom: 'lrtip',
				createdRow: function(row, data) {
					$(row).addClass('perakuanjabatan-row-link').css('cursor', 'pointer').attr('data-href',
						data.show_url);
				}
			});

			$('#tbl-perakuan-jabatan tbody').on('click', 'tr.perakuanjabatan-row-link', function(e) {
				if ($(e.target).closest('a').length) {
					return;
				}
				var href = $(this).attr('data-href');
				if (href) {
					window.location = href;
				}
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
					DT.column(3).search(escapeRegex(statusSearch), true, false);
				} else {
					DT.column(3).search('');
				}

				DT.draw();
			}

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

			$('#btn_reset_filter').on('click', function() {
				$('#filter_no_tender').val('');
				$('#filter_tajuk').val('');
				$('#filter_status').val('');
				$('#filter_tarikh').val('');

				DT.column(0).search('');
				DT.column(1).search('');
				DT.column(2).search('');
				DT.column(3).search('');
				DT.draw();
			});
		});
	</script>
@endsection
