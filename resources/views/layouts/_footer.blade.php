{{-- Footer Section --}}
@if(isset($global_ou))
	<footer class="footer py-5">
		<div class="container">
			<div class="row g-4">
				{{-- Commented: Map Section
				<div class="col-lg-3 footer-map">
					<a href="{{ $global_ou->gmap_url }}" target="_blank">
						<img src="{{ $global_ou->gmap_src }}" alt="{{ $global_ou->name }}">
					</a>
				</div>
				--}}

				{{-- Commented: Dynamic Agency Info
				<div class="col-lg-4">
					<div class="agency-info">
						<h5 class="agency-title">{{ $global_ou->name }}</h5>
						<p>{!! nl2br($global_ou->address) !!}</p>
						@if($global_ou->tel)
							<h6 class="agency-subtitle">No. Telefon</h6>
							<p>{!! $global_ou->tel !!}</p>
						@endif
						@if($global_ou->email)
							<h6 class="agency-subtitle">Alamat Emel</h6>
							<p>{!! $global_ou->email !!}</p>
						@endif
						<div class="visitors">
							Jumlah Pengunjung: <span>{{ App\Visit::getCount() }}</span> (Sejak 24 April 2017)
						</div>
					</div>
				</div>
				--}}

				{{-- UPEN Contact Info --}}
				<div class="col-lg-4 col-md-6">
					<div class="agency-info">
						<h5 class="agency-title">Maklumat Berkaitan Pendaftaran / Kemaskini</h5>
						<p class="mb-3">
							Unit Perancang Ekonomi Negeri (UPEN) <br>
							Tingkat 4, Bangunan Sultan Salahuddin Abdul Aziz Shah <br>
							40503 Shah Alam. Selangor Darul Ehsan
						</p>

						<h6 class="agency-subtitle">Waktu Urusan :</h6>
						<p class="mb-3">8.00 Pagi-5.00 Petang , Kecuali Sabtu, Ahad dan Cuti Umum</p>

						<h6 class="agency-subtitle">Alamat Emel :</h6>
						<p class="mb-3">
							<a href="mailto:tenderadmin@selangor.gov.my" class="footer-link">tenderadmin@selangor.gov.my</a>
						</p>

						<ul class="list-unstyled small mb-0 footer-list">
							<li><svg xmlns="http://www.w3.org/2000/svg" width="6" height="6" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg> Masalah berkaitan pendaftaran</li>
							<li><svg xmlns="http://www.w3.org/2000/svg" width="6" height="6" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg> Masalah berkaitan kemaskini</li>
							<li><svg xmlns="http://www.w3.org/2000/svg" width="6" height="6" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg> Pendaftaran / Kemaskini mengambil 3 hari waktu bekerja</li>
						</ul>
					</div>
				</div>

				{{-- BPM Contact Info --}}
				<div class="col-lg-4 col-md-6">
					<div class="agency-info">
						<h5 class="agency-title">Maklumat Berkaitan Masalah Teknikal Sistem</h5>
						<p class="mb-3">
							Bahagian Pengurusan Maklumat (BPM) <br>
							Tingkat 2, Bangunan Sultan Salahuddin Abdul Aziz Shah <br>
							40503 Shah Alam. Selangor Darul Ehsan
						</p>

						<h6 class="agency-subtitle">Waktu Urusan :</h6>
						<p class="mb-3">8.00 Pagi - 5.00 Petang , Kecuali Sabtu, Ahad dan Cuti Umum</p>

						<h6 class="agency-subtitle">Alamat Emel :</h6>
						<p class="mb-3">
							<a href="mailto:tenderadmin@selangor.gov.my" class="footer-link">tenderadmin@selangor.gov.my</a>
						</p>

						<ul class="list-unstyled small mb-0 footer-list">
							<li><svg xmlns="http://www.w3.org/2000/svg" width="6" height="6" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg> Mesti ada tajuk e-mail</li>
							<li><svg xmlns="http://www.w3.org/2000/svg" width="6" height="6" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg> Nyatakan masalah dengan jelas</li>
							<li><svg xmlns="http://www.w3.org/2000/svg" width="6" height="6" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg> Lampirkan Screen Shot</li>
							<li><svg xmlns="http://www.w3.org/2000/svg" width="6" height="6" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg> Untuk masalah pembayaran, sila lampirkan salinan transaksi bank.</li>
							<li><svg xmlns="http://www.w3.org/2000/svg" width="6" height="6" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg> Masalah transaksi pembayaran dengan PBT diuruskan oleh PBT.</li>
							<li><svg xmlns="http://www.w3.org/2000/svg" width="6" height="6" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg> Masalah transaksi dengan Pejabat SUK, diuruskan oleh Bahagian Khidmat Pengurusan (BKP, Unit Kewangan, Tingkat 17)</li>
						</ul>
					</div>
				</div>

				{{-- Payment Methods & System Info --}}
				<div class="col-lg-4 col-md-12">
					<div class="agency-info text-center text-lg-start">
						<div class="payment-info mb-4">
							<p class="small mb-3">
								Pendaftaran Syarikat, Pembaharuan dan Pembelian Dokumen Tender / Sebut Harga boleh dilakukan menggunakan
							</p>
							<div class="payment-icons d-flex justify-content-center justify-content-lg-start gap-2 flex-wrap">
								@if($pay_by_cc)
									<i class="icon icon-visa"></i>
									<i class="icon icon-mastercard"></i>
								@endif
								@if($pay_by_fpx)
									<i class="temp-icon temp-icon-fpx"></i>
								@endif
							</div>
						</div>

						<div class="system-info text-center">
							<img src="{{ asset('images/sirim3.png') }}" width="100" height="80" alt="SIRIM Certification" class="mb-3">
							<p class="small fw-bold mb-0">Versi Sistem : STOS Ver 2.3</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</footer>
@endif

{{-- Footer Bottom Bar --}}
<div class="footer-bottom py-3">
	<div class="container">
		<div class="row align-items-center g-2">
			<div class="col-lg-6 text-center text-lg-start">
				<span>&copy; {{ date('Y') }} Setiausaha Kerajaan Negeri Selangor. Hak Cipta Terpelihara.</span>
				<span class="mx-1">|</span>
				<a href="{{ action('HomeController@privacy') }}" class="footer-bottom-link">Polisi Keselamatan</a>
			</div>
			<div class="col-lg-6 text-center text-lg-end">
				<span class="small">Sesuai dipapar menggunakan Edge 2023, Firefox 110.0, Google Chrome 110.0 ke atas dengan resolusi 1024 x 768.</span>
			</div>
		</div>
	</div>
</div>
