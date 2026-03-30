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

	{{-- Tender Table --}}
	<div class="bg-white rounded overflow-hidden mb-4">
		<div class="table-responsive">
			<table class="table project-table mb-0">
				<thead>
					<tr>
						<th style="width: 20%;">No. Tender/Sebut Harga</th>
						<th style="width: 45%;">Tajuk Perolehan</th>
						<th style="width: 15%;">Tarikh</th>
						<th style="width: 20%;">Status</th>
					</tr>
				</thead>
				<tbody>
					<tr class="perakuanjabatan-row-link" style="cursor: pointer;"
						data-href="{{ route('perakuanjabatan.show', 'QT210000000023741') }}">
						<td><a href="{{ route('perakuanjabatan.show', 'QT210000000023741') }}" class="text-decoration-none"><span
									class="tender-number">QT210000000023741</span></a></td>
						<td><span class="fw-medium">TENDER PERKHIDMATAN DIGITAL FORENSIK KE ATAS ALIRAN PROSES SISTEM XXXX</span></td>
						<td><span class="text-muted small">3/3/2024</span></td>
						<td></td>
					</tr>
					<tr class="perakuanjabatan-row-link" style="cursor: pointer;"
						data-href="{{ route('perakuanjabatan.show', 'QT210000000023740') }}">
						<td><a href="{{ route('perakuanjabatan.show', 'QT210000000023740') }}" class="text-decoration-none"><span
									class="tender-number">QT210000000023740</span></a></td>
						<td><span class="fw-medium">TAJUK PEROLEHAN 1</span></td>
						<td><span class="text-muted small">2/2/2024</span></td>
						<td></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
@endsection

@section('scripts')
	<script>
		document.querySelectorAll('.perakuanjabatan-row-link').forEach(function(row) {
			row.addEventListener('click', function(e) {
				if (e.target.closest('a')) return;
				var href = this.getAttribute('data-href');
				if (href) window.location = href;
			});
		});
	</script>
@endsection
