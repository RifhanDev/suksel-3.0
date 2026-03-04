@if (Auth::check() && !Auth::user()->hasRole('Vendor'))
	<aside class="sidebar-vertical" id="sidebar">
		<div class="sidebar-header">
			<a href="/" class="sidebar-brand">
				<img src="{{ asset('images/02_selangor.png') }}" alt="Sistem Tender Online Selangor" class="sidebar-brand-image">
			</a>
		</div>
		<div class="sidebar-body">
			<nav class="sidebar-nav" id="sidebar-menu">
				<div class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
						<span class="nav-link-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-bookmark-question">
								<path stroke="none" d="M0 0h24v24H0z" fill="none" />
								<path d="M15 19l-3 -2l-6 4v-14a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v4" />
								<path d="M19 22v.01" />
								<path d="M19 19a2.003 2.003 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" />
							</svg>
						</span>
						<span class="nav-link-title">Pengurusan Tender</span>
					</a>
					<ul class="dropdown-menu">
						@if (App\Tender::canList())
							@if (Auth::user()->ability(['Admin', 'Registration Assesor', 'Front Desk'], []))
								<li><a class="dropdown-item" href="{{ asset('tenders') }}">Senarai Tender</a></li>
							@else
								<li><a class="dropdown-item" href="{{ asset('agencies/' . Auth::user()->organization_unit_id) }}">Senarai Tender</a></li>
							@endif
						@endif

						@if (App\Vendor::canList())
							<li><a class="dropdown-item" href="{{ asset('vendors') }}">Senarai Syarikat</a></li>
						@endif

						@if (App\VendorBlacklist::canList())
							<li><a class="dropdown-item" href="{{ asset('blacklists') }}">Senarai Hitam</a></li>
						@endif

						@if (App\News::canList())
							<li><a class="dropdown-item" href="{{ asset('news') }}">Senarai Berita</a></li>
						@endif

						@if (App\Transaction::canList())
							<li><a class="dropdown-item" href="{{ asset('transactions') }}">Senarai Transaksi</a></li>
						@endif
					</ul>
				</div>
				@if (App\CodeRequest::canList())
					<div class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-bookmark-question">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path d="M15 19l-3 -2l-6 4v-14a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v4" />
									<path d="M19 22v.01" />
									<path d="M19 19a2.003 2.003 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" />
								</svg>
							</span>
							<span class="nav-link-title">Pengurusan Permintaan Kemaskini</span>
						</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="{{ asset('requests') }}">Permintaan Kemaskini</a></li>
						</ul>
					</div>
				@endif

        		@if (Auth::user()->ability(['Admin', 'Agency Admin'], []))
					<div class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-bookmark-question">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path d="M15 19l-3 -2l-6 4v-14a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v4" />
									<path d="M19 22v.01" />
									<path d="M19 19a2.003 2.003 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" />
								</svg>
							</span>
							<span class="nav-link-title">Pengurusan Sistem</span>
						</a>
						<ul class="dropdown-menu">
                			@if (App\User::canList())
								<li><a class="dropdown-item" href="{{ asset('users') }}">Senarai Pengguna</a></li>
							@endif
							@if (Auth::user()->canApprove())
								<li><a class="dropdown-item" href="{{ asset('users/pending-approval') }}">Senarai Permohonan Pengguna</a></li>
							@endif
							@if (App\OrganizationUnit::canList())
								<li><a class="dropdown-item" href="{{ asset('agencies') }}">Senarai Agensi</a></li>
							@endif
							@if (Auth::user()->hasRole('Admin'))
								<li><a class="dropdown-item" href="{{ asset('organizationtypes') }}">Kategori Agensi</a></li>
								<li><a class="dropdown-item" href="{{ asset('codes') }}">Senarai Kod Bidang</a></li>
								<li><a class="dropdown-item" href="{{ asset('helps') }}">Senarai Soalan Bantuan</a></li>
								<li><a class="dropdown-item" href="{{ asset('helpcategories') }}">Kategori Soalan Bantuan</a></li>
								<li><a class="dropdown-item" href="{{ asset('gateways') }}">Tetapan Pembayaran</a></li>
								<li><a class="dropdown-item" href="{{ asset('banners') }}">Senarai Banner</a></li>
							@endif
							@if (Auth::user()->can('System:histories'))
								<li><a class="dropdown-item" href="{{ asset('version-histories') }}">Sejarah Perubahan Sistem</a></li>
							@endif
							@if(App\Models\RejectTemplate::canList())
								<li><a class="dropdown-item" href="{{ asset('reject-template') }}">Senarai Templat Penolakan</a></li>
							@endif
							@if(App\Models\Circular::canList())
								<li><a class="dropdown-item" href="{{ asset('circulars') }}">Senarai Pekeliling</a></li>
							@endif
						</ul>
					</div>
				@endif

				@if (Auth::user()->hasRole('Admin'))
					<div class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-bookmark-question">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path d="M15 19l-3 -2l-6 4v-14a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v4" />
									<path d="M19 22v.01" />
									<path d="M19 19a2.003 2.003 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" />
								</svg>
							</span>
							<span class="nav-link-title">Pengurusan Akses</span>
						</a>
						<ul class="dropdown-menu">
							@if (App\Role::canList())
								<li><a class="dropdown-item" href="{{ asset('roles') }}">Tetapan Peranan</a></li>
							@endif
							@if (App\Permission::canList())
								<li><a class="dropdown-item" href="{{ asset('permissions') }}">Tetapan Kebenaran</a></li>
							@endif
						</ul>
					</div>
				@endif

				@if (Auth::user()->ability(['Admin', 'Refund Admin'], ['Refund:list']))
					<div class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-bookmark-question">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path d="M15 19l-3 -2l-6 4v-14a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v4" />
									<path d="M19 22v.01" />
									<path d="M19 19a2.003 2.003 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" />
								</svg>
							</span>
							<span class="nav-link-title">Pengurusan Pemulangan Semula</span>
						</a>
						<ul class="dropdown-menu">
							@if (App\Models\Refund::canList())
								<li><a class="dropdown-item" href="{{ route('refunds.request.index') }}">Permohonan Pemulangan Semula</a></li>
							@endif
							@if (App\Models\Refund::isRoleBKP())
								<li><a class="dropdown-item" href="{{ route('refunds.complaint.index') }}">Aduan Permohonan Semula</a></li>
							@endif
						</ul>
					</div>
				@endif

				@if (Auth::user()->ability(['Admin'], ['Api:canList']))
					<div class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-bookmark-question">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path d="M15 19l-3 -2l-6 4v-14a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v4" />
									<path d="M19 22v.01" />
									<path d="M19 19a2.003 2.003 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" />
								</svg>
							</span>
							<span class="nav-link-title">Pengurusan API</span>
						</a>
						<ul class="dropdown-menu">
							@if (App\Models\ApiToken::canList())
								<li><a class="dropdown-item" href="{{ route('apitoken.index') }}">Senarai API Token</a></li>
							@endif
						</ul>
					</div>
				@endif

				@if (Auth::user()->ability(['Admin'], ['chatbot-manager:canList']))
					<div class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-bookmark-question">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path d="M15 19l-3 -2l-6 4v-14a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v4" />
									<path d="M19 22v.01" />
									<path d="M19 19a2.003 2.003 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" />
								</svg>
							</span>
							<span class="nav-link-title">Pengurusan ChatBot</span>
						</a>
						<ul class="dropdown-menu">
							@if (App\Models\FaqCategory::canList())
								<li><a class="dropdown-item" href="{{ route('chatbot-manager.category.index') }}">Senarai Kategori</a></li>
								<li><a class="dropdown-item" href="{{ route('chatbot-manager.question.index') }}">Senarai Soalan</a></li>
								<li><a class="dropdown-item" href="{{ route('chatbot-manager.chatlog.index') }}">Senarai Rekod Chat</a></li>
								<li><a class="dropdown-item" href="{{ route('chatbot-manager.newquestion.index') }}">Senarai Pertanyaan Tidak Wujud</a></li>
							@endif
						</ul>
					</div>
				@endif

				@if (Auth::user()->ability(['Admin'], ['chatbot-manager:canList']))
					<div class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-bookmark-question">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path d="M15 19l-3 -2l-6 4v-14a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v4" />
									<path d="M19 22v.01" />
									<path d="M19 19a2.003 2.003 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" />
								</svg>
							</span>
							<span class="nav-link-title">Pengurusan Email SMTP</span>
						</a>
						<ul class="dropdown-menu">
							@if (App\Models\FaqCategory::canList())
								<li><a class="dropdown-item" href="{{ route('mail-manager.smtp-setting.index') }}">Senarai Email SMTP</a></li>
								<li><a class="dropdown-item" href="{{ route('mail-manager.mail-queue.index') }}">Rekod Penghantaran Email</a></li>
							@endif
						</ul>
					</div>
				@endif

				@if (Auth::user()->ability(['Admin'], []))
					<div class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-bookmark-question">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path d="M15 19l-3 -2l-6 4v-14a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v4" />
									<path d="M19 22v.01" />
									<path d="M19 19a2.003 2.003 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" />
								</svg>
							</span>
							<span class="nav-link-title">Aduan</span>
						</a>
						<ul class="dropdown-menu">
							@if (App\Models\FaqCategory::canList())
								<li><a class="dropdown-item" href="{{ asset('aduan/list') }}">Senarai Aduan</a></li>
							@endif
						</ul>
					</div>
				@endif

				@if (Auth::user()->ability(['Admin', 'Admin Kewangan'], []))
					<div class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-bookmark-question">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path d="M15 19l-3 -2l-6 4v-14a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v4" />
									<path d="M19 22v.01" />
									<path d="M19 19a2.003 2.003 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" />
								</svg>
							</span>
							<span class="nav-link-title">Dashboard</span>
						</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="{{ asset('dashboard/hq') }}">Dashboard Pengurusan</a></li>
						</ul>
					</div>
				@endif

				@if (Auth::user()->can('Report:view'))
					<div class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-bookmark-question">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path d="M15 19l-3 -2l-6 4v-14a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v4" />
									<path d="M19 22v.01" />
									<path d="M19 19a2.003 2.003 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" />
								</svg>
							</span>
							<span class="nav-link-title">Laporan</span>
						</a>
						<ul class="dropdown-menu">
							@if (Auth::user()->can('Report:view:revenue_yearly'))
								<li><a class="dropdown-item" href="{{ asset('reports/revenue') }}">Hasil Transaksi Tahunan</a></li>
							@endif
							@if (Auth::user()->can('Report:view:agency_active'))
								<li><a class="dropdown-item" href="{{ asset('reports/agency/active') }}">10 Agensi Aktif</a></li>
							@endif
							@if (Auth::user()->can('Report:view:agency_transaction'))
								<li><a class="dropdown-item" href="{{ asset('reports/agency/all') }}">Transaksi Semua Agensi</a></li>
							@endif
							@if (Auth::user()->can('reports/agency/type'))
								<li><a class="dropdown-item" href="{{ asset('reports/agency/type') }}">Transaksi Mengikut Kategori Agensi</a></li>
							@endif
							@if (Auth::user()->can('Report:view:agency_tender') || Auth::user()->can('Report:view:agency_tender:organization_unit_id'))
								<li><a class="dropdown-item" href="{{ asset('reports/agency/transaction') }}">Transaksi Agensi Mengikut Tender</a></li>
							@endif
							@if (Auth::user()->can('Report:view:agency_daily') || Auth::user()->can('Report:view:agency_daily:organization_unit_id'))
								<li><a class="dropdown-item" href="{{ asset('reports/agency/daily') }}">Transaksi Harian Agensi</a></li>
							@endif
							@if (Auth::user()->can('Report:view:gateway_daily') || Auth::user()->can('Report:view:gateway_daily:organization_unit_id'))
								<li><a class="dropdown-item" href="{{ asset('reports/gateway/daily') }}">Transaksi Harian Gateway</a></li>
							@endif
							@if (Auth::user()->can('Report:view:vendor_status'))
								<li><a class="dropdown-item" href="{{ asset('reports/vendor/status') }}">Syarikat Mengikut Status</a></li>
							@endif
							@if (Auth::user()->can('Report:view:vendor_code'))
								<li><a class="dropdown-item" href="{{ asset('reports/vendor/codes') }}">Syarikat Mengikut Kod Bidang</a></li>
							@endif
							@if (Auth::user()->can('Report:view:vendor_district'))
								<li><a class="dropdown-item" href="{{ asset('reports/vendor/district') }}">Syarikat Mengikut Daerah</a></li>
							@endif
							@if (Auth::user()->can('Report:view:user_agency') || Auth::user()->can('Report:view:user_agency:organization_unit_id'))
								<li><a class="dropdown-item" href="{{ asset('reports/user/agency') }}">Senarai Pengguna Agensi</a></li>
							@endif
							@if (Auth::user()->can('Report:view:user_agency') || Auth::user()->can('Report:view:user_active:organization_unit_id'))
								<li><a class="dropdown-item" href="{{ asset('reports/user/active') }}">Senarai Status Pengguna Mengikut Agensi</a></li>
							@endif
							@if (Auth::user()->can('Report:view:user_activity'))
								<li><a class="dropdown-item" href="{{ asset('reports/user/activity') }}">Laporan Aktiviti Staf</a></li>
							@endif
							@if (Auth::user()->can('Report:view:user_login'))
								<li><a class="dropdown-item" href="{{ asset('reports/user/login') }}">Laporan Login Sebagai</a></li>
							@endif
							@if (Auth::user()->can('Report:view:vendor_registration_list'))
								<li><a class="dropdown-item" href="{{ asset('reports/vendor/registration-list') }}">Laporan Pendaftaran Syarikat</a></li>
							@endif
							@if (Auth::user()->can('Report:view:code_request'))
								<li><a class="dropdown-item" href="{{ asset('reports/vendor/request') }}">Laporan Permohonan Kemaskini Maklumat Syarikat</a></li>
							@endif
							@if (Auth::user()->can('Report:view:vendor_registration'))
								<li><a class="dropdown-item" href="{{ asset('reports/vendor/registration') }}">Laporan Pendaftaran Pengguna Sistem</a></li>
							@endif
							@if (Auth::user()->can('Report:view:staff_activity'))
								<li><a class="dropdown-item" href="{{ asset('reports/staff/activity') }}">Laporan Aktiviti Pengguna Sistem</a></li>
							@endif
							@if (Auth::user()->can('Report:view:code_district'))
								<li><a class="dropdown-item" href="{{ asset('reports/code/district') }}">Laporan Jumlah Berkaitan Kod Bidang</a></li>
							@endif
							@if (Auth::user()->can('Report:view:vendor_transaction'))
								<li><a class="dropdown-item" href="{{ asset('reports/vendor/transaction') }}">Laporan Transaksi</a></li>
							@endif
							@if (Auth::user()->can('Report:view:transaction_hasil'))
								<li><a class="dropdown-item" href="{{ asset('reports/transaction/hasil') }}">Laporan Transaksi Mengikut Kod Akaun Hasil</a></li>
							@endif
						</ul>
					</div>
				@endif
			</nav>
		</div>
	</aside>
@else
	<aside class="sidebar-vertical" id="sidebar">
		<div class="sidebar-header">
			<a href="/" class="sidebar-brand">
				<img src="{{ asset('images/02_selangor.png') }}" alt="Sistem Tender Online Selangor" class="sidebar-brand-image">
			</a>
		</div>
		<div class="sidebar-body">
			<nav class="sidebar-nav" id="sidebar-menu">
				
			</nav>
		</div>
	</aside>
@endif
