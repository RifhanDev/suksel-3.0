{{--
    Akuan Pengakuan — shown once per member per tender before evaluating.
    Blocking (static backdrop); the agree button unlocks only after the text is
    scrolled to the end. Shared by every jawatankuasa flow.

    @param string $committeeLabel  e.g. "Jawatankuasa Pembuka"
    @param string $tenderNo
--}}
<div class="modal fade" id="modalAkuan" tabindex="-1" aria-labelledby="modalAkuanLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content border-0 shadow-lg rounded-3">
			<div class="modal-header px-4 pt-4 border-0">
				<div class="d-flex align-items-center gap-3">
					<div class="content-card-icon flex-shrink-0" style="width: 42px; height: 42px;">
						<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
						</svg>
					</div>
					<div>
						<span class="akuan-eyebrow">Sebelum Penilaian Bermula</span>
						<h5 class="modal-title fw-bold text-dark mb-0" id="modalAkuanLabel" style="font-size: 1.05rem; letter-spacing: -0.2px;">Akuan Pengakuan Ahli {{ $committeeLabel }}</h5>
					</div>
				</div>
			</div>

			<div class="modal-body px-4 pb-0">
				<div class="akuan-meta mb-3">
					<div>
						<span class="akuan-meta-label">Tender</span>
						<span class="akuan-meta-value">{{ $tenderNo }}</span>
					</div>
					<div>
						<span class="akuan-meta-label">Peranan Anda</span>
						<span class="akuan-meta-value" id="akuanPeranan">&mdash;</span>
					</div>
				</div>

				<div class="akuan-scroll" id="akuanScroll" tabindex="0">
					<p>Saya, sebagai ahli {{ $committeeLabel }} yang dilantik bagi tender/sebut harga di atas, dengan ini mengaku dan berjanji seperti berikut:</p>

					<h6>1. Kerahsiaan Maklumat</h6>
					<p>Saya akan merahsiakan segala maklumat, dokumen, harga tawaran, dan apa-apa butiran petender yang saya perolehi sepanjang proses penilaian ini. Saya tidak akan mendedahkan maklumat tersebut kepada mana-mana pihak yang tidak berkenaan, sama ada secara lisan, bertulis, elektronik atau apa-apa cara lain, semasa mahupun selepas proses ini selesai.</p>

					<h6>2. Percanggahan Kepentingan</h6>
					<p>Saya mengesahkan bahawa saya tidak mempunyai apa-apa kepentingan peribadi, keluarga, kewangan atau perniagaan dengan mana-mana petender yang terlibat dalam tender/sebut harga ini. Sekiranya wujud apa-apa percanggahan kepentingan, sama ada sedia ada atau yang timbul kemudian, saya akan segera memaklumkan kepada Pengerusi Jawatankuasa dan menarik diri daripada penilaian berkaitan.</p>

					<h6>3. Ketelusan dan Kesaksamaan</h6>
					<p>Saya akan menjalankan penilaian dengan adil, telus dan saksama berdasarkan semata-mata kepada dokumen yang dikemukakan oleh petender serta kriteria penilaian yang telah ditetapkan. Saya tidak akan memihak kepada mana-mana petender atas apa-apa sebab selain daripada merit tawaran mereka.</p>

					<h6>4. Larangan Menerima Sebarang Bentuk Suapan</h6>
					<p>Saya tidak akan meminta, menerima atau bersetuju untuk menerima apa-apa bentuk suapan, hadiah, keraian, komisen atau apa-apa manfaat daripada mana-mana petender atau wakil mereka. Saya faham bahawa perbuatan sedemikian adalah satu kesalahan di bawah Akta Suruhanjaya Pencegahan Rasuah Malaysia 2009.</p>

					<h6>5. Integriti Rekod Penilaian</h6>
					<p>Saya mengaku bahawa setiap penilaian yang saya rekodkan dalam sistem ini adalah hasil pertimbangan saya sendiri terhadap dokumen yang telah saya semak. Saya tidak akan merekodkan sebarang keputusan penilaian bagi dokumen yang tidak saya semak, dan tidak akan membenarkan mana-mana pihak lain merekodkan penilaian bagi pihak saya.</p>

					<h6>6. Penggunaan Akaun Sendiri</h6>
					<p>Saya bertanggungjawab sepenuhnya ke atas akaun pengguna saya. Saya tidak akan berkongsi kata laluan atau membenarkan mana-mana individu lain mengakses sistem ini menggunakan akaun saya. Saya faham bahawa segala tindakan yang direkodkan melalui akaun saya akan dianggap sebagai tindakan saya sendiri.</p>

					<h6>7. Rekod Aktiviti</h6>
					<p>Saya memahami dan bersetuju bahawa setiap tindakan saya dalam proses penilaian ini &mdash; termasuk masa akuan ini diterima, dokumen yang saya buka, penilaian yang saya rekodkan, dan penghantaran akhir &mdash; akan direkodkan secara automatik oleh sistem untuk tujuan audit dan boleh dirujuk pada bila-bila masa oleh pihak berkuasa yang berkenaan.</p>

					<h6>8. Tanggungjawab</h6>
					<p>Saya faham bahawa keputusan {{ $committeeLabel }} memberi kesan langsung kepada kelayakan petender untuk meneruskan ke peringkat seterusnya. Saya menerima tanggungjawab tersebut dengan penuh amanah.</p>

					<p class="akuan-closing">Saya mengaku bahawa segala maklumat dan pengakuan di atas adalah benar. Saya faham bahawa sekiranya saya melanggar mana-mana pengakuan ini, tindakan tatatertib dan/atau tindakan undang-undang boleh diambil terhadap saya.</p>

					<div class="akuan-end" id="akuanEnd" aria-hidden="true"></div>
				</div>

				<div class="akuan-hint" id="akuanHint">
					<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M12 5v14"></path><path d="m19 12-7 7-7-7"></path>
					</svg>
					<span>Sila tatal sehingga ke penghujung teks untuk meneruskan.</span>
				</div>
			</div>

			<div class="modal-footer px-4 pb-4 pt-3 border-0">
				<button type="button" class="btn-form btn-form-secondary" id="btnAkuanTolak">Keluar</button>
				<button type="button" class="btn-form btn-form-success" id="btnAkuanSetuju" disabled>Saya Faham dan Bersetuju</button>
			</div>
		</div>
	</div>
</div>
