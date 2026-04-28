@extends('layouts.v3.master')

@section('content')
	<style>
		.form-control:focus,
		.form-select:focus {
			border-color: var(--sg-red);
			box-shadow: 0 0 0 0.2rem rgba(196, 30, 58, 0.25);
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
			vertical-align: top;
		}

		.project-table tbody tr {
			background: white;
		}

		.project-table tbody tr:hover {
			background: var(--sg-bg);
		}

		.version-number {
			font-weight: 700;
			color: var(--sg-red-dark);
			font-family: 'Courier New', monospace;
		}

		.version-notes-list {
			margin: 0;
			padding-left: 1rem;
		}

		.version-notes-list li {
			margin-bottom: 0.25rem;
			color: #6b7280;
			font-size: 0.85rem;
		}

		@media (max-width: 768px) {
			.project-table {
				font-size: 12px;
			}

			.project-table th,
			.project-table td {
				padding: 10px;
			}
		}
	</style>

	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div>
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Sejarah Versi Sistem</h3>
			<p class="text-muted small m-0">Rekod penambahbaikan dan perubahan Sistem Tender Selangor dari semasa ke semasa.</p>
		</div>
	</div>

	<div class="card border shadow-sm mb-3 rounded-3">
		<div class="card-body p-3">
			<div class="row g-2 align-items-end">
				<div class="col-12 col-lg-3">
					<label class="form-label small fw-bold text-secondary text-uppercase mb-1">Versi</label>
					<input type="text" id="filter_version" class="form-control form-control-sm" placeholder="Cth: v1.3">
				</div>

				<div class="col-12 col-lg-3">
					<label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tarikh</label>
					<input type="text" id="filter_tarikh" class="form-control form-control-sm" placeholder="Cari tarikh...">
				</div>

				<div class="col-12 col-lg-4">
					<label class="form-label small fw-bold text-secondary text-uppercase mb-1">Nota</label>
					<input type="text" id="filter_nota" class="form-control form-control-sm" placeholder="Cari nota perubahan...">
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

	<div class="bg-white rounded overflow-hidden mb-4">
		<div class="table-responsive">
			<table id="tbl-version-histories" class="table project-table mb-0 w-100">
				<thead>
					<tr>
						<th style="width: 15%;">Versi</th>
						<th style="width: 20%;">Tarikh</th>
						<th style="width: 65%;">Nota Perubahan</th>
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
			const backendData = @json(
				($versionHistories ?? collect())->map(function ($item) {
					return [
						'version' => $item->version,
						'released_at' => optional($item->released_at)->format('j M Y'),
						'notes' => $item->notes,
					];
				}));

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

			function formatNotes(notes) {
				const text = (notes || '').toString().trim();
				if (!text) {
					return '<span class="text-muted">-</span>';
				}

				const lines = text.split(/\r?\n/).map(function(line) {
					return line.trim();
				}).filter(Boolean);

				if (!lines.length) {
					return '<span class="text-muted">-</span>';
				}

				return '<ul class="version-notes-list">' + lines.map(function(line) {
					return '<li>' + escapeHtml(line) + '</li>';
				}).join('') + '</ul>';
			}

			const tableData = backendData.map(function(row) {
				return {
					version: row.version || '-',
					released_at: row.released_at || '-',
					notes: row.notes || '',
				};
			});

			const DT = $('#tbl-version-histories').DataTable({
				data: tableData,
				columns: [{
						data: 'version',
						render: function(data, type) {
							if (type === 'display') {
								return '<span class="version-number">' + escapeHtml(data) + '</span>';
							}
							return data;
						}
					},
					{
						data: 'released_at',
						render: function(data, type) {
							if (type === 'display') {
								return '<span class="text-muted small">' + escapeHtml(data) +
								'</span>';
							}
							return data;
						}
					},
					{
						data: 'notes',
						render: function(data, type) {
							if (type === 'display') {
								return formatNotes(data);
							}
							return (data || '').replace(/\r?\n/g, ' ');
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
				order: [
					[1, 'desc']
				],
				dom: 'lrtip'
			});

			function applyFilters() {
				const filterVersion = ($('#filter_version').val() || '').toString().trim();
				const filterTarikh = ($('#filter_tarikh').val() || '').toString().trim();
				const filterNota = ($('#filter_nota').val() || '').toString().trim();

				DT.column(0).search(filterVersion, false, true);
				DT.column(1).search(filterTarikh, false, true);
				DT.column(2).search(filterNota, false, true);
				DT.draw();
			}

			$('#btn_apply_filter').on('click', function() {
				applyFilters();
			});

			$('#filter_version, #filter_tarikh, #filter_nota').on('keydown', function(e) {
				if (e.key === 'Enter') {
					e.preventDefault();
					applyFilters();
				}
			});

			$('#btn_reset_filter').on('click', function() {
				$('#filter_version').val('');
				$('#filter_tarikh').val('');
				$('#filter_nota').val('');

				DT.column(0).search('');
				DT.column(1).search('');
				DT.column(2).search('');
				DT.draw();
			});
		});
	</script>
@endsection
