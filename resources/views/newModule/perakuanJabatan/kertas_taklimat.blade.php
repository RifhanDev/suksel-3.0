@php
	$tenderId = $tender->id;
	$simpanUrl = route('perakuanjabatan.kertasTaklimat.simpan', ['tender' => $tenderId]);
	$hantarUrl = route('perakuanjabatan.kertasTaklimat.hantar', ['tender' => $tenderId]);
@endphp
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

		.kertas-taklimat-tab .kt-file-pending {
			font-size: 0.8rem;
			color: #0f766e;
		}
	</style>

	<div class="content-card p-4" id="kt-root" data-tender-id="{{ $tenderId }}">
		<h6 class="fw-bold text-dark mb-3">Seksyen Laporan</h6>
		<div class="alert d-none py-2 px-3 mb-3" id="ktAlert" role="alert"></div>

		<div class="table-responsive kt-table-wrap">
			<table class="table table-bordered align-middle mb-0" id="ktDokumenTable">
				<thead>
					<tr>
						<th class="kt-col-check" scope="col">
							<input type="checkbox" class="form-check-input m-0" id="ktSelectAll" title="Pilih semua"
								aria-label="Pilih semua">
						</th>
						<th scope="col">Kandungan</th>
						<th scope="col">Tindakan</th>
					</tr>
				</thead>
				<tbody id="ktDokumenTbody">
					@foreach ($kertasItems as $item)
						@php
							$ui = \App\Models\PerakuanJabatanKertasTaklimatItem::actionUi($item->slot_key);
						@endphp
						<tr data-item-id="{{ $item->id }}" data-slot-key="{{ $item->slot_key }}" data-row-ui="{{ $ui }}">
							<td class="kt-col-check">
								<input type="checkbox" class="form-check-input m-0 kt-row-check" aria-label="Pilih baris"
									data-slot-key="{{ $item->slot_key }}">
							</td>
							<td>
								@if ($item->slot_key === null)
									<input type="text" class="form-control form-control-sm kt-kandungan" value="{{ $item->kandungan }}">
								@else
									<span class="kt-kandungan-label">{{ $item->kandungan }}</span>
									<input type="hidden" class="kt-kandungan" value="{{ $item->kandungan }}">
								@endif
							</td>
							<td class="kt-col-tindakan">
								@foreach ($item->files as $f)
									<div class="d-flex flex-wrap align-items-center gap-2 mb-1 kt-file-row" data-file-id="{{ $f->id }}">
										<a href="{{ route('perakuanjabatan.kertasTaklimat.download', $f) }}" class="kt-action-link kt-muat-turun">Muat
											Turun</a>
										<span class="small text-muted text-break">{{ $f->file_original_name }}</span>
										<button type="button" class="btn btn-link btn-sm p-0 kt-remove-file text-danger">Buang</button>
									</div>
								@endforeach
								<div class="d-flex flex-wrap align-items-center gap-2 kt-upload-actions">
									@if (in_array($ui, ['download_only', 'upload_download'], true))
										@if ($item->files->isEmpty())
											<span class="text-muted small">Tiada dokumen untuk dimuat turun</span>
										@endif
									@endif
									@if (in_array($ui, ['upload_only', 'upload_download'], true))
										<a href="#" class="kt-action-link kt-muat-naik">Muat Naik</a>
										<input type="file" class="d-none kt-file-input" multiple aria-label="Fail muat naik (boleh berbilang)">
									@endif
								</div>
								<div class="kt-pending-names small mt-1"></div>
							</td>
						</tr>
					@endforeach
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
			<textarea class="form-control kt-textarea-catatan" id="ktCatatan" name="catatan" rows="4"
			 placeholder="Masukkan catatan…">{{ old('catatan', $header->catatan) }}</textarea>
		</div>

		<div class="d-flex justify-content-end gap-2 mt-4">
			<button type="button" class="btn btn-kt-teal" id="ktSimpan">Simpan</button>
			<button type="button" class="btn btn-kt-navy" id="ktHantar">Hantar</button>
		</div>
	</div>
</div>

@push('scripts')
	<script>
		(function() {
			const root = document.getElementById('kt-root');
			if (!root) return;

			const simpanUrl = @json($simpanUrl);
			const hantarUrl = @json($hantarUrl);
			const token = document.querySelector('meta[name="_token"]')?.getAttribute('content');
			const tbody = root.querySelector('#ktDokumenTbody');
			const selectAll = root.querySelector('#ktSelectAll');
			const catatanEl = root.querySelector('#ktCatatan');
			const alertEl = root.querySelector('#ktAlert');

			const pendingDeleteFileIds = new Set();
			const pendingDeleteItemIds = new Set();
			const pendingRowFiles = new WeakMap();

			function showAlert(msg, ok) {
				if (!alertEl) return;
				alertEl.textContent = msg;
				alertEl.classList.remove('d-none', 'alert-success', 'alert-danger');
				alertEl.classList.add(ok ? 'alert-success' : 'alert-danger');
			}

			function refreshSelectAllState() {
				const checks = root.querySelectorAll('.kt-row-check');
				if (!checks.length || !selectAll) return;
				const n = [...checks].filter(c => c.checked).length;
				selectAll.checked = n === checks.length;
				selectAll.indeterminate = n > 0 && n < checks.length;
			}

			if (selectAll) {
				selectAll.addEventListener('change', function() {
					root.querySelectorAll('.kt-row-check').forEach(c => {
						c.checked = selectAll.checked;
					});
					selectAll.indeterminate = false;
				});
			}

			root.addEventListener('change', function(e) {
				if (e.target && e.target.classList && e.target.classList.contains('kt-row-check')) {
					refreshSelectAllState();
				}
			});

			root.addEventListener('change', function(e) {
				const input = e.target;
				if (!input || !input.classList || !input.classList.contains('kt-file-input')) return;
				const tr = input.closest('tr');
				if (!tr) return;
				const files = input.files ? [...input.files] : [];
				pendingRowFiles.set(tr, files);
				const wrap = tr.querySelector('.kt-pending-names');
				if (wrap) {
					wrap.innerHTML = files.length ?
						'<span class="kt-file-pending">Akan dimuat naik: ' + files.map(f => f.name).join(', ') +
						'</span>' :
						'';
				}
			});

			root.addEventListener('click', function(e) {
				const del = e.target.closest('.kt-remove-file');
				if (del) {
					e.preventDefault();
					const row = del.closest('.kt-file-row');
					if (!row) return;
					const fid = row.getAttribute('data-file-id');
					if (fid) pendingDeleteFileIds.add(fid);
					row.remove();
					return;
				}
				const a = e.target.closest('a.kt-muat-turun');
				if (a) {
					const h = a.getAttribute('href');
					if (!h || h === '#' || h === '') {
						e.preventDefault();
						return;
					}
				}
				const up = e.target.closest('a.kt-muat-naik');
				if (up) {
					e.preventDefault();
					const cell = up.closest('td');
					const input = cell && cell.querySelector('.kt-file-input');
					if (input) input.click();
				}
			});

			root.querySelector('#ktMuatTurunSemua')?.addEventListener('click', function() {
				root.querySelectorAll('a.kt-muat-turun[href]:not([href="#"])').forEach(function(link) {
					window.open(link.href, '_blank');
				});
			});

			root.querySelector('#ktTambahBaris')?.addEventListener('click', function() {
				if (!tbody) return;
				const tr = document.createElement('tr');
				tr.setAttribute('data-item-id', '');
				tr.setAttribute('data-slot-key', '');
				tr.setAttribute('data-row-ui', 'upload_download');
				tr.innerHTML =
					'<td class="kt-col-check">' +
					'<input type="checkbox" class="form-check-input m-0 kt-row-check" aria-label="Pilih baris" data-slot-key="">' +
					'</td>' +
					'<td><input type="text" class="form-control form-control-sm kt-kandungan" value="" placeholder="Kandungan dokumen"></td>' +
					'<td class="kt-col-tindakan">' +
					'<div class="d-flex flex-wrap align-items-center gap-2 kt-upload-actions">' +
					'<a href="#" class="kt-action-link kt-muat-naik">Muat Naik</a>' +
					'<input type="file" class="d-none kt-file-input" multiple aria-label="Fail muat naik (boleh berbilang)">' +
					'</div>' +
					'<div class="kt-pending-names small mt-1"></div>' +
					'</td>';
				tbody.appendChild(tr);
				refreshSelectAllState();
			});

			root.querySelector('#ktHapusBaris')?.addEventListener('click', function() {
				if (!tbody) return;
				const toRemove = [...tbody.querySelectorAll('tr')].filter(function(tr) {
					const cb = tr.querySelector('.kt-row-check');
					return cb && cb.checked && cb.getAttribute('data-slot-key') === '';
				});
				if (!toRemove.length) return;
				toRemove.forEach(function(tr) {
					const persistedId = tr.getAttribute('data-item-id');
					if (persistedId) {
						pendingDeleteItemIds.add(persistedId);
					}
					tr.remove();
				});
				if (selectAll) {
					selectAll.checked = false;
					selectAll.indeterminate = false;
				}
			});

			function buildFormData() {
				const fd = new FormData();
				fd.append('_token', token);
				fd.append('catatan', catatanEl ? catatanEl.value : '');

				[...pendingDeleteFileIds].forEach(id => fd.append('deleted_file_ids[]', id));
				[...pendingDeleteItemIds].forEach(id => fd.append('deleted_item_ids[]', id));

				const rows = [...tbody.querySelectorAll('tr')];
				rows.forEach(function(tr, idx) {
					const idVal = tr.getAttribute('data-item-id');
					if (idVal) fd.append('rows[' + idx + '][id]', idVal);
					const kInp = tr.querySelector('.kt-kandungan');
					fd.append('rows[' + idx + '][kandungan]', kInp ? kInp.value.trim() : '');

					const files = pendingRowFiles.get(tr);
					if (files && files.length) {
						files.forEach(f => fd.append('rows[' + idx + '][files][]', f, f.name));
					}
				});

				return fd;
			}

			function postForm(url) {
				const fd = buildFormData();
				fetch(url, {
						method: 'POST',
						body: fd,
						headers: {
							'X-Requested-With': 'XMLHttpRequest',
							'Accept': 'application/json'
						}
					})
					.then(r => r.json().then(j => ({
						ok: r.ok,
						status: r.status,
						body: j
					})).catch(() => ({
						ok: r.ok,
						status: r.status,
						body: {}
					})))
					.then(res => {
						if (res.ok) {
							showAlert(res.body.message || 'Berjaya.', true);
							pendingDeleteFileIds.clear();
							pendingDeleteItemIds.clear();
							setTimeout(() => window.location.reload(), 600);
						} else {
							let msg = res.body.message || 'Ralat simpan.';
							if (res.body.errors) {
								msg = Object.values(res.body.errors).flat().join(' ');
							}
							showAlert(msg, false);
						}
					})
					.catch(() => showAlert('Ralat rangkaian.', false));
			}

			root.querySelector('#ktSimpan')?.addEventListener('click', function() {
				postForm(simpanUrl);
			});
			root.querySelector('#ktHantar')?.addEventListener('click', function() {
				postForm(hantarUrl);
			});
		})();
	</script>
@endpush
