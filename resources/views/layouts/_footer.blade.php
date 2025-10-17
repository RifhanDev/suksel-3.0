@if(isset($global_ou))
	<section class="footer">
	  	<div class="container">
	    	<div class="row">
				{{-- <div class="col-lg-3 footer-map">
						<!--<a href="{{ $global_ou->gmap_url }}" target="_blank">
						<img src="{{ $global_ou->gmap_src }}" alt="{{ $global_ou->name }}">
						</a>-->
				</div> --}}

				{{-- <div class="col-lg-4 agency-info">
						<h1>{{ $global_ou->name }}</h1>
						<p>{!! nl2br($global_ou->address) !!}</p>
						@if($global_ou->tel)
							<h2>No. Telefon</h2>
							<p>{!! $global_ou->tel !!}</p>
						@endif
						@if($global_ou->email)
							<h2>Alamat Emel</h2>
							<p>{!! $global_ou->email !!}</p>
						@endif

						<div class="visitors">
						Jumlah Pengunjung: <span>{{ App\Visit::getCount() }}</span> (Sejak 24 April 2017)
						</div>
				</div> --}}

				<div class="col-lg-4 agency-info">
					<h1>Maklumat Berkaitan Pendaftaran / Kemaskini</h1>
					<p>
						Unit Perancang Ekonomi Negeri (UPEN) <br>
						Tingkat 4, Bangunan Sultan Salahuddin Abdul Aziz Shah <br>
						40503 Shah Alam. Selangor Darul Ehsan <br>
					</p>

					<h1>Waktu Urusan :</h1> <p>8.00 Pagi-5.00 Petang , Kecuali Sabtu, Ahad dan Cuti Umum</p>

					<h1>Alamat Emel  :</h1> <p><a href="mailto:tenderadmin@selangor.gov.my">tenderadmin@selangor.gov.my</a></p>

					<p>
						- Masalah berkaitan pendaftaran <br>
						- Masalah berkaitan kemaskini <br>
						- Pendaftaran / Kemaskini mengambil 3 hari waktu bekerja
					</p>
				</div>

				<div class="col-lg-4 agency-info">
					<h1>Maklumat Berkaitan Masalah Teknikal Sistem</h1>
					<p>
						Bahagian Pengurusan Maklumat (BPM) <br>
						Tingkat 2, Bangunan Sultan Salahuddin Abdul Aziz Shah <br>
						40503 Shah Alam. Selangor Darul Ehsan
					</p>
					
					<h1>Waktu Urusan :</h1> <p> 8.00 Pagi - 5.00 Petang , Kecuali Sabtu, Ahad dan Cuti Umum</p>
					
					<h1>Alamat Emel  :</h1> <p><a href="mailto:tenderadmin@selangor.gov.my">tenderadmin@selangor.gov.my</a></p>

					<p>
						- Mesti ada tajuk e-mail <br>
						- Nyatakan masalah dengan jelas <br>
						- Lampirkan Screen Shot  <br>
						- Untuk masalah pembayaran, sila lampirkan salinan transaksi bank. <br>
						- Masalah transaksi pembayaran dengan PBT diuruskan oleh PBT. <br>
						- Masalah transaksi dengan Pejabat SUK, diuruskan oleh Bahagian Khidmat Pengurusan (BKP, Unit Kewangan, Tingkat 17)
					</p>
				</div>

				<div class="col-lg-3">
					<p id="payment-kinds">
						<span>Pendaftaran Syarikat, Pembaharuan dan Pembelian Dokumen Tender / Sebut Harga boleh dilakukan menggunakan</span><br><br>
						@if($pay_by_cc)<i class="icon icon-visa"></i><i class="icon icon-mastercard"></i>@endif
						@if($pay_by_fpx)<i class="temp-icon temp-icon-fpx"></i>@endif
					</p>
					<p class="text-center">
						<!-- saiz asal width="210px" width="190px" -->
						<img src="{{ asset('images/sirim3.png') }}" width="100px" width="80px" style="margin-bottom: -5px" alt="sirim">
						<br> <br>
						<span class="text-center">
							<b>Versi Sistem  : STOS Ver 2.3</b>
						</span>
						<br>
					</p>
					<p class="text-center">
					</p>
				</div>
	    	</div>
	  	</div>
	</section>
@endif

<div class="footer-darker">
    	<div class="container">
        	<div class="row">
            <div class="col-lg-6">&copy;{{date('Y')}} Setiausaha Kerajaan Negeri Selangor. Hak Cipta Terpelihara. | 
            <a href="{{ action('HomeController@privacy') }}">Polisi Keselamatan</a>
            </div>
            <div class="col-lg-6 right">Sesuai dipapar menggunakan Edge 2023, Firefox 110.0, Google Chrome 110.0 ke atas dengan resolusi 1024 x 768.</div>
        	</div>
    	</div>
</div>