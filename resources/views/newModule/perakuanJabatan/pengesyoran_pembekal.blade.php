@php
	$ppSimpanUrl = route('perakuanjabatan.pengesyoranPembekal.simpan', ['tender' => $tender->id]);
	$ppHantarUrl = route('perakuanjabatan.pengesyoranPembekal.hantar', ['tender' => $tender->id]);
@endphp
<div class="tab-pane fade pengesyoran-pembekal-tab" id="tab-pengesyoran-pembekal" role="tabpanel">
	<style>
		.pengesyoran-pembekal-tab .pp-section-bar {
			background: #e8ecf2;
			border: 1px solid #dde2ea;
			border-bottom: none;
			padding: 0.65rem 1rem;
			font-weight: 700;
			font-size: 0.85rem;
			letter-spacing: 0.04em;
		}

		.pengesyoran-pembekal-tab .pp-table-wrap .table {
			border: 1px solid #ddd;
		}

		.pengesyoran-pembekal-tab .pp-table-wrap .table thead th {
			background-color: #2d3e84 !important;
			color: #fff !important;
			text-align: center;
			vertical-align: middle;
			font-weight: 600;
			border-color: #ddd !important;
		}

		.pengesyoran-pembekal-tab .pp-table-wrap .table tbody td {
			vertical-align: middle;
			border-color: #ddd;
		}

		.pengesyoran-pembekal-tab .pp-table-wrap .table.pp-item-table thead th.pp-col-check {
			width: 48px;
		}

		.pengesyoran-pembekal-tab .pp-hint {
			font-size: 0.8125rem;
		}

		.pengesyoran-pembekal-tab .pp-textarea-catatan {
			border-radius: 0 0 8px 8px;
			border: 1px solid #dde2ea;
			min-height: 120px;
			resize: vertical;
			max-width: 420px;
		}

		.pengesyoran-pembekal-tab .pp-btn-teal {
			background-color: #0f766e;
			border-color: #0f766e;
			color: #fff;
		}

		.pengesyoran-pembekal-tab .pp-btn-teal:hover {
			background-color: #0d9488;
			border-color: #0d9488;
			color: #fff;
		}
	</style>

	<div class="content-card p-4" id="pp-root">
		<div class="alert d-none py-2 px-3 mb-3" id="ppAlert" role="alert"></div>

		<div class="pp-section-bar rounded-top">SENARAI ITEM</div>
		<div class="border border-top-0 border-secondary-subtle p-3 rounded-bottom mb-4" style="border-color:#dde2ea!important;">
			<p class="pp-hint text-primary mb-2 mb-md-3">Sila klik pada item untuk melihat senarai pembekal</p>
			<div class="table-responsive pp-table-wrap">
				<table class="table table-bordered align-middle mb-0 pp-item-table">
					<thead>
						<tr>
							<th class="pp-col-check text-white text-center" style="background-color:#2d3e84;">
								<input type="checkbox" class="form-check-input m-0" title="Pilih semua" aria-label="Pilih semua" disabled>
							</th>
							<th class="text-white text-center" style="background-color:#2d3e84;">Item</th>
							<th class="text-white text-center" style="background-color:#2d3e84;">Jenis Item</th>
							<th class="text-white text-center" style="background-color:#2d3e84;">Unit Ukuran</th>
							<th class="text-white text-center" style="background-color:#2d3e84;">Jenis Harga</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="text-center">
								<input type="checkbox" class="form-check-input m-0" aria-label="Pilih item" disabled>
							</td>
							<td>{{ $tender->name }}</td>
							<td class="text-center">—</td>
							<td class="text-center">—</td>
							<td class="text-center">—</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="pp-section-bar rounded-top">SENARAI PEMBEKAL</div>
		<div class="border border-top-0 border-secondary-subtle p-3 rounded-bottom mb-4" style="border-color:#dde2ea!important;">
			<p class="text-muted small mb-3">Paparan senarai pembekal akan disambungkan dalam kemaskini akan datang.</p>
			<div class="table-responsive pp-table-wrap">
				<table class="table table-bordered align-middle mb-0 text-center">
					<thead>
						<tr>
							<th rowspan="2">Bil</th>
							<th rowspan="2">Status Bumiputra</th>
							<th rowspan="2">Harga Tawaran (RM)</th>
							<th rowspan="2">Skor Teknikal</th>
							<th colspan="2">Kedudukan Penilaian</th>
							<th rowspan="2">Status Pendaftaran MOF</th>
							<th colspan="2">Maklumat Tambahan</th>
							<th rowspan="2">Keputusan Urusetia</th>
							<th rowspan="2">Catatan Urusetia</th>
						</tr>
						<tr>
							<th>Teknikal</th>
							<th>Kewangan</th>
							<th>Tindakan Disiplin Diambil</th>
							<th>Lembaga Pengarah</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="11" class="text-muted py-4">Tiada data (dummy)</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="pp-section-bar rounded-top">CATATAN</div>
		<textarea class="form-control pp-textarea-catatan mb-4" id="ppCatatan" name="catatan" rows="4"
			placeholder="">{{ old('catatan', $pengesyoranPembekal->catatan) }}</textarea>

		<div class="form-check mb-3">
			<input class="form-check-input" type="checkbox" name="sahkan_petender_layak" id="ppPengakuan" value="1"
				{{ old('sahkan_petender_layak', $pengesyoranPembekal->sahkan_petender_layak) ? 'checked' : '' }}>
			<label class="form-check-label" for="ppPengakuan">Saya mengesahkan petender diatas layak untuk
				menyertai Bidaan.</label>
		</div>

		<div class="d-flex justify-content-end gap-2">
			<button type="button" class="btn pp-btn-teal" id="ppSimpan">Simpan</button>
			<button type="button" class="btn btn-selangor" id="ppHantar">Hantar</button>
		</div>
	</div>
</div>

@push('scripts')
	<script>
		(function() {
			const root = document.getElementById('pp-root');
			if (!root) return;

			const simpanUrl = @json($ppSimpanUrl);
			const hantarUrl = @json($ppHantarUrl);
			const token = document.querySelector('meta[name="_token"]')?.getAttribute('content');
			const catatanEl = document.getElementById('ppCatatan');
			const pengakuanEl = document.getElementById('ppPengakuan');
			const alertEl = document.getElementById('ppAlert');

			function showAlert(msg, ok) {
				if (!alertEl) return;
				alertEl.textContent = msg;
				alertEl.classList.remove('d-none', 'alert-success', 'alert-danger');
				alertEl.classList.add(ok ? 'alert-success' : 'alert-danger');
			}

			function buildFormData() {
				const fd = new FormData();
				fd.append('_token', token);
				fd.append('catatan', catatanEl ? catatanEl.value : '');
				if (pengakuanEl && pengakuanEl.checked) {
					fd.append('sahkan_petender_layak', '1');
				}
				return fd;
			}

			function post(url) {
				fetch(url, {
						method: 'POST',
						body: buildFormData(),
						headers: {
							'X-Requested-With': 'XMLHttpRequest',
							'Accept': 'application/json'
						}
					})
					.then(r => r.json().then(j => ({
						ok: r.ok,
						body: j
					})).catch(() => ({
						ok: r.ok,
						body: {}
					})))
					.then(res => {
						if (res.ok) {
							showAlert(res.body.message || 'Berjaya.', true);
							if (url === hantarUrl) {
								setTimeout(() => window.location.reload(), 800);
							}
						} else {
							let msg = res.body.message || 'Ralat.';
							if (res.body.errors) {
								msg = Object.values(res.body.errors).flat().join(' ');
							}
							showAlert(msg, false);
						}
					})
					.catch(() => showAlert('Ralat rangkaian.', false));
			}

			document.getElementById('ppSimpan')?.addEventListener('click', function() {
				post(simpanUrl);
			});
			document.getElementById('ppHantar')?.addEventListener('click', function() {
				post(hantarUrl);
			});
		})();
	</script>
@endpush
