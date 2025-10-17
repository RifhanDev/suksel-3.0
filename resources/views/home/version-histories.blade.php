@extends('layouts.default')

@section('content')

	<h1>Sejarah Perubahan Sistem Tender Selangor</h1>

	<table class="table table-bordered table-hover">
		<thead class="bg-blue-selangor">
			<tr>
				<th class="col-lg-2">Versi</th>
				<th class="col-lg-2">Tarikh</th>
				<th>Nota</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>1.0</td>
				<td>8 Jun 2015</td>
				<td>Live</td>
			</tr>
			<tr>
				<td>1.1</td>
				<td>1 November 2015</td>
				<td>
					<ol>
						<li>Masukkan Syarikat Tidak Layak Tender / Sebut Harga Menggunakan Fungsi Kebenaran Khas</li>
						<li>Cetak Resit Pembayaran Untuk Tender / Sebut Harga Secara Pukal</li>
					</ol>
				</td>
			</tr>
			<tr>
				<td>1.2</td>
				<td>14 Oktober 2016</td>
				<td>
					<ol>
						<li>Halang Transaksi Pembayaran Agensi</li>
						<li>Mengemaskini semula data-data maklumat hubungan kontraktor</li>
						<li>Semakan pendaftaran syarikat</li>
						<li>Buang agensi pengesahan</li>
						<li>Paparan kod bidang CIDB</li>
						<li>Maklumat ralat</li>
						<li>Muat turun laporan dalam format Excel</li>
						<li>Laporan syarikat berdasarkan kod bidang</li>
						<li>Laporan produktiviti Staff</li>
						<li>Paparan notifikasi kod bidang tidak layak</li>
						<li>Penukaran alamat emel oleh pegawai syarikat</li>
						<li>Paparan status pembayaran sewaktu transaksi</li>
						<li>Medan “Daerah” dalam data syarikat</li>
						<li>Muat naik kehadiran syarikat ke taklimat &amp; lawatan tapak</li>
					</ol>
				</td>
			</tr>
			<tr>
				<td>1.3</td>
				<td>4 September 2017</td>
				<td>
					<strong>Penambahbaikan Modul UPEN</strong><br><br>
				
					<ol>
						<li>Menolak pendaftaran kontraktor</li>
						<li>Menerima pendaftaran kontraktor</li>
						<li>Menolak permintaan perubahan kontraktor</li>
						<li>Menerima permintaan perubahan kontraktor</li>
						<li>Menyenarai hitam kontraktor</li>
						<li>Tetapan peranan</li>
					</ol>
				</td>
			</tr>
		</tbody>
	</table>
@endsection
