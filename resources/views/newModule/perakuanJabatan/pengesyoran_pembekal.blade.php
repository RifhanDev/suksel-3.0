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

	<div class="content-card p-4">
		<div class="pp-section-bar rounded-top">SENARAI ITEM</div>
		<div class="border border-top-0 border-secondary-subtle p-3 rounded-bottom mb-4" style="border-color:#dde2ea!important;">
			<p class="pp-hint text-primary mb-2 mb-md-3">Sila klik pada item untuk melihat senarai pembekal</p>
			<div class="table-responsive pp-table-wrap">
				<table class="table table-bordered align-middle mb-0 pp-item-table">
					<thead>
						<tr>
							<th class="pp-col-check text-white text-center" style="background-color:#2d3e84;">
								<input type="checkbox" class="form-check-input m-0" title="Pilih semua" aria-label="Pilih semua">
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
								<input type="checkbox" class="form-check-input m-0" aria-label="Pilih item">
							</td>
							<td>Tender Perkhidmatan Penilaian Forensik Keatas Sistem XXXX</td>
							<td class="text-center">Perkhidmatan</td>
							<td class="text-center">Activity Unit</td>
							<td class="text-center">Biasa Standard</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="pp-section-bar rounded-top">SENARAI PEMBEKAL</div>
		<div class="border border-top-0 border-secondary-subtle p-3 rounded-bottom mb-4" style="border-color:#dde2ea!important;">
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
							<td>2/2</td>
							<td>Ya</td>
							<td>360,000.00</td>
							<td>96.43</td>
							<td>1</td>
							<td>1</td>
							<td>Aktif</td>
							<td class="text-start small">Tindakan Disiplin Diambil</td>
							<td>
								<button type="button" class="btn btn-link text-primary p-0" title="Lembaga Pengarah" aria-label="Lembaga Pengarah">
									<i class="bi bi-folder2-open fs-5"></i>
								</button>
							</td>
							<td>
								<select class="form-select form-select-sm mx-auto" style="max-width: 11rem;" aria-label="Keputusan Urusetia">
									<option value="disyorkan" selected>Disyorkan</option>
									<option value="ditolak">Ditolak</option>
									<option value="ditimbang">Ditimbang</option>
								</select>
							</td>
							<td>
								<input type="text" class="form-control form-control-sm" placeholder="" aria-label="Catatan Urusetia">
							</td>
						</tr>
						<tr>
							<td>1/2</td>
							<td>Tidak</td>
							<td>330,000.00</td>
							<td>94.53</td>
							<td>2</td>
							<td>2</td>
							<td>Aktif</td>
							<td></td>
							<td>
								<button type="button" class="btn btn-link text-primary p-0" title="Lembaga Pengarah" aria-label="Lembaga Pengarah">
									<i class="bi bi-folder2-open fs-5"></i>
								</button>
							</td>
							<td>
								<select class="form-select form-select-sm mx-auto" style="max-width: 11rem;" aria-label="Keputusan Urusetia">
									<option value="disyorkan" selected>Disyorkan</option>
									<option value="ditolak">Ditolak</option>
									<option value="ditimbang">Ditimbang</option>
								</select>
							</td>
							<td>
								<input type="text" class="form-control form-control-sm" placeholder="" aria-label="Catatan Urusetia">
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="pp-section-bar rounded-top">CATATAN</div>
		<textarea class="form-control pp-textarea-catatan mb-4" rows="4" placeholder=""></textarea>

		<div class="form-check mb-3">
			<input class="form-check-input" type="checkbox" id="pengakuan">
			<label class="form-check-label" for="pengakuan">Saya mengesahkan petender diatas layak untuk
				menyertai Bidaan.</label>
		</div>

		<div class="d-flex justify-content-end gap-2">
			<button type="button" class="btn pp-btn-teal">Simpan</button>
			<button type="button" class="btn btn-selangor">Hantar</button>
		</div>
	</div>
</div>
