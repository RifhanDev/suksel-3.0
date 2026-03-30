<div class="tab-pane fade show active kertas-taklimat-tab" id="tab-kertas-taklimat" role="tabpanel">
	<style>
		.kertas-taklimat-tab .kt-table-wrap .table thead th {
			background-color: #2d3e84 !important;
			color: #fff !important;
			text-align: center;
			vertical-align: middle;
			font-weight: 600;
			border-color: #2d3e84 !important;
		}

		.kertas-taklimat-tab .kt-table-wrap .table thead th.kt-col-check {
			width: 48px;
		}

		.kertas-taklimat-tab .kt-table-wrap .table tbody td {
			vertical-align: middle;
		}

		.kertas-taklimat-tab .kt-table-wrap .table tbody td.kt-col-check {
			text-align: center;
		}

		.kertas-taklimat-tab .kt-table-wrap .table tbody td.kt-col-tindakan {
			white-space: normal;
		}

		.kertas-taklimat-tab .kt-action-link {
			color: #0d6efd;
			text-decoration: underline;
			cursor: pointer;
		}

		.kertas-taklimat-tab .kt-bar-catatan {
			background: #e8ecf2;
			border: 1px solid #dde2ea;
			border-bottom: none;
			padding: 0.65rem 1rem;
			font-weight: 700;
			font-size: 0.85rem;
			letter-spacing: 0.04em;
		}

		.kertas-taklimat-tab .kt-textarea-catatan {
			border-radius: 0 0 8px 8px;
			border: 1px solid #dde2ea;
			min-height: 120px;
			resize: vertical;
		}

		.kertas-taklimat-tab .btn-kt-teal {
			background-color: #0f766e;
			border-color: #0f766e;
			color: #fff;
		}

		.kertas-taklimat-tab .btn-kt-teal:hover {
			background-color: #0d9488;
			border-color: #0d9488;
			color: #fff;
		}

		.kertas-taklimat-tab .btn-kt-navy {
			background-color: #2d3e84;
			border-color: #2d3e84;
			color: #fff;
		}

		.kertas-taklimat-tab .btn-kt-navy:hover {
			background-color: #243566;
			border-color: #243566;
			color: #fff;
		}

		.kertas-taklimat-tab .btn-kt-coral {
			background-color: #ea580c;
			border-color: #ea580c;
			color: #fff;
		}

		.kertas-taklimat-tab .btn-kt-coral:hover {
			background-color: #c2410c;
			border-color: #c2410c;
			color: #fff;
		}
	</style>

	<div class="content-card p-4">
		<h6 class="fw-bold text-dark mb-3">Paparan Kertas Taklimat</h6>

		<div class="table-responsive kt-table-wrap">
			<table class="table table-bordered align-middle mb-0" id="ktDokumenTable">
				<thead>
					<tr>
						<th class="kt-col-check" scope="col">
							<input type="checkbox" class="form-check-input m-0" id="ktSelectAll" title="Pilih semua" aria-label="Pilih semua">
						</th>
						<th scope="col">Kandungan</th>
						<th scope="col">Tindakan</th>
					</tr>
				</thead>
				<tbody id="ktDokumenTbody">
					<tr>
						<td class="kt-col-check">
							<input type="checkbox" class="form-check-input m-0 kt-row-check" aria-label="Pilih baris">
						</td>
						<td>Laporan Jawatankuasa Pembuka</td>
						<td class="kt-col-tindakan">
							<a href="#" class="kt-action-link kt-muat-turun">Muat Turun</a>
						</td>
					</tr>
					<tr>
						<td class="kt-col-check">
							<input type="checkbox" class="form-check-input m-0 kt-row-check" aria-label="Pilih baris">
						</td>
						<td>Laporan Jawatankuasa Teknikal</td>
						<td class="kt-col-tindakan">
							<a href="#" class="kt-action-link kt-muat-turun">Muat Turun</a>
						</td>
					</tr>
					<tr>
						<td class="kt-col-check">
							<input type="checkbox" class="form-check-input m-0 kt-row-check" aria-label="Pilih baris">
						</td>
						<td>Laporan Jawatankuasa Kewangan</td>
						<td class="kt-col-tindakan">
							<a href="#" class="kt-action-link kt-muat-turun">Muat Turun</a>
						</td>
					</tr>
					<tr>
						<td class="kt-col-check">
							<input type="checkbox" class="form-check-input m-0 kt-row-check" aria-label="Pilih baris">
						</td>
						<td>Kertas Taklimat (Perakuan Jabatan)</td>
						<td class="kt-col-tindakan">
							<a href="#" class="kt-action-link kt-muat-turun me-2">Muat Turun</a>
							<a href="#" class="kt-action-link kt-muat-naik">Muat Naik</a>
							<input type="file" class="d-none kt-file-input" aria-label="Fail muat naik">
						</td>
					</tr>
					<tr>
						<td class="kt-col-check">
							<input type="checkbox" class="form-check-input m-0 kt-row-check" aria-label="Pilih baris">
						</td>
						<td>Ringkasan Kertas Taklimat (wajib untuk tender)</td>
						<td class="kt-col-tindakan">
							<a href="#" class="kt-action-link kt-muat-naik">Muat Naik</a>
							<input type="file" class="d-none kt-file-input" aria-label="Fail muat naik">
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="d-flex flex-wrap justify-content-end gap-2 mt-3">
			<button type="button" class="btn btn-sm btn-kt-teal" id="ktMuatTurunSemua">Muat Turun Semua</button>
			<button type="button" class="btn btn-sm btn-kt-navy" id="ktTambahBaris">Tambah</button>
			<button type="button" class="btn btn-sm btn-kt-coral" id="ktHapusBaris">Hapus</button>
		</div>

		<div class="mt-4">
			<div class="kt-bar-catatan rounded-top">CATATAN</div>
			<label class="visually-hidden" for="ktCatatan">Catatan</label>
			<textarea class="form-control kt-textarea-catatan" id="ktCatatan" name="catatan" rows="4" placeholder="Masukkan catatan…"></textarea>
		</div>

		<div class="d-flex justify-content-end gap-2 mt-4">
			<button type="button" class="btn btn-kt-teal" id="ktSimpan">Simpan</button>
			<button type="button" class="btn btn-kt-navy" id="ktHantar">Hantar</button>
		</div>
	</div>
</div>

@once
	@push('scripts')
		<script>
			(function () {
				const root = document.getElementById('tab-kertas-taklimat');
				if (!root) return;

				const tbody = root.querySelector('#ktDokumenTbody');
				const selectAll = root.querySelector('#ktSelectAll');

				function refreshSelectAllState() {
					const checks = root.querySelectorAll('.kt-row-check');
					if (!checks.length || !selectAll) return;
					const n = [...checks].filter(c => c.checked).length;
					selectAll.checked = n === checks.length;
					selectAll.indeterminate = n > 0 && n < checks.length;
				}

				if (selectAll) {
					selectAll.addEventListener('change', function () {
						root.querySelectorAll('.kt-row-check').forEach(c => { c.checked = selectAll.checked; });
						selectAll.indeterminate = false;
					});
				}

				root.addEventListener('change', function (e) {
					if (e.target && e.target.classList && e.target.classList.contains('kt-row-check')) {
						refreshSelectAllState();
					}
				});

				root.addEventListener('click', function (e) {
					const a = e.target.closest('a.kt-muat-turun');
					if (a) {
						e.preventDefault();
						// Sambungkan ke endpoint muat turun sebenar apabila tersedia
						return;
					}
					const up = e.target.closest('a.kt-muat-naik');
					if (up) {
						e.preventDefault();
						const cell = up.closest('td');
						const input = cell && cell.querySelector('.kt-file-input');
						if (input) input.click();
					}
				});

				root.querySelector('#ktMuatTurunSemua')?.addEventListener('click', function () {
					root.querySelectorAll('a.kt-muat-turun').forEach(function (link) {
						link.click();
					});
				});

				root.querySelector('#ktTambahBaris')?.addEventListener('click', function () {
					if (!tbody) return;
					const tr = document.createElement('tr');
					tr.innerHTML =
						'<td class="kt-col-check">' +
						'<input type="checkbox" class="form-check-input m-0 kt-row-check" aria-label="Pilih baris">' +
						'</td>' +
						'<td><input type="text" class="form-control form-control-sm" name="kandungan[]" placeholder="Kandungan dokumen"></td>' +
						'<td class="kt-col-tindakan">' +
						'<a href="#" class="kt-action-link kt-muat-turun me-2">Muat Turun</a>' +
						'<a href="#" class="kt-action-link kt-muat-naik">Muat Naik</a>' +
						'<input type="file" class="d-none kt-file-input" aria-label="Fail muat naik">' +
						'</td>';
					tbody.appendChild(tr);
					refreshSelectAllState();
				});

				root.querySelector('#ktHapusBaris')?.addEventListener('click', function () {
					if (!tbody) return;
					const toRemove = [...tbody.querySelectorAll('tr')].filter(function (tr) {
						const cb = tr.querySelector('.kt-row-check');
						return cb && cb.checked;
					});
					if (!toRemove.length) return;
					toRemove.forEach(function (tr) { tr.remove(); });
					if (selectAll) {
						selectAll.checked = false;
						selectAll.indeterminate = false;
					}
				});

				root.querySelector('#ktSimpan')?.addEventListener('click', function () {
					// Sambungkan ke simpan (AJAX/form) apabila backend tersedia
				});

				root.querySelector('#ktHantar')?.addEventListener('click', function () {
					// Sambungkan ke hantar apabila backend tersedia
				});
			})();
		</script>
	@endpush
@endonce
