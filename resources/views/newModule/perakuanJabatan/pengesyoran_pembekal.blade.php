<div class="tab-pane fade" id="tab-pengesyoran-pembekal" role="tabpanel">
	<div class="content-card p-4">
		<h6 class="fw-bold">KEPUTUSAN PIHAK BERKUASA MELULUS</h6>
		<div class="row mb-3">
			<div class="col-md-4">
				<label class="form-label">Keputusan Mesyuarat</label>
				<select class="form-select">
					<option selected>Pengesyoran Pembekal</option>
					<option>Penilaian Semula</option>
					<option>Iklan Semula</option>
					<option>Kemukakan kepada Pihak Berkuasa Yang Lebih Tinggi</option>
					<option>Batal</option>
				</select>
			</div>
			<div class="col-md-4">
				<label class="form-label">Kaedah Memuktamadkan Pembekal</label>
				<select class="form-select">
					<option selected>Bidaan</option>
					<option>Pemilihan Terus</option>
					<option>Pemilihan Lebih Daripada Satu Syarikat</option>
				</select>
			</div>
		</div>

		<h6 class="fw-bold">SENARAI ITEM</h6>
		<div class="table-responsive">
			<table class="table table-bordered">
				<thead class="text-white text-center" style="background-color:#2d3e84;">
					<tr>
						<th></th>
						<th>Item</th>
						<th>Jenis Item</th>
						<th>Unit Ukuran</th>
						<th>Jenis Harga</th>
						<th>Dibatalkan</th>
						<th>Pembekal Dipilih</th>
						<th>Kuantiti</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="text-center"><input type="checkbox"></td>
						<td>Tender Perkhidmatan Penilaian Forensik Keatas Sistem XXXX</td>
						<td>Perkhidmatan</td>
						<td>Activity Unit</td>
						<td>Biasa Standard</td>
						<td>
							<select class="form-select">
								<option selected>Tidak</option>
								<option>Ya</option>
							</select>
						</td>
						<td>2</td>
						<td>1</td>
					</tr>
				</tbody>
			</table>
			<button class="btn btn-success">Terapkan untuk semua</button>
		</div>

		<h6 class="fw-bold mt-4">SENARAI PEMBEKAL</h6>
		<div class="table-responsive">
			<table class="table table-bordered text-center">
				<thead class="text-white" style="background-color:#2d3e84;">
					<tr>
						<th>Bil</th>
						<th>Status Bumiputra</th>
						<th>Harga Tawaran (RM)</th>
						<th>Jumlah Skor</th>
						<th>Kedudukan Penilaian Teknikal Kewangan</th>
						<th>Status Pendaftaran MOF</th>
						<th colspan="2">Maklumat Tambahan</th>
						<th>Keputusan oleh Urusetia</th>
						<th>Catatan Urusetia</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>2/2</td>
						<td>Ya</td>
						<td>360,000.00</td>
						<td>96.43</td>
						<td>1</td>
						<td>Aktif</td>
						<td>Tindakan Disiplin Diambil</td>
						<td><button class="btn btn-light"><i class="bi bi-file-earmark"></i></button></td>
						<td>Disyorkan</td>
						<td></td>
					</tr>
					<tr>
						<td>1/2</td>
						<td>Tidak</td>
						<td>330,000.00</td>
						<td>94.53</td>
						<td>2</td>
						<td>Aktif</td>
						<td></td>
						<td><button class="btn btn-light"><i class="bi bi-file-earmark"></i></button></td>
						<td>Disyorkan</td>
						<td></td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="form-check mb-3">
			<input class="form-check-input" type="checkbox" id="pengakuan">
			<label class="form-check-label" for="pengakuan">Saya mengesahkan petender diatas layak untuk
				menyertai Bidaan.</label>
		</div>

		<div class="d-flex justify-content-end gap-2">
			<button class="btn btn-success">Simpan</button>
			<button class="btn btn-selangor">Hantar</button>
		</div>
	</div>
</div>
