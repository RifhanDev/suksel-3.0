{{-- @php
	dd(auth()->user());
@endphp --}}
@if (Auth::check() && !Auth::user()->hasRole('Vendor'))
	<aside id="left-sidebar" class="left-sidebar">
		<div class="navbar-nav">
			<div class="nav-item dropdown">
				<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">
					<span class="nav-link-icon d-md-none d-lg-inline-block">
						<i class="ti ti-tender"></i>
					</span>
					<span class="nav-link-title">Pengurusan Tender</span>
				</a>
				<div class="dropdown-menu">
					@if (App\Tender::canList())
						@if (Auth::user()->ability(['Admin', 'Registration Assesor', 'Front Desk'], []))
							<a class="dropdown-item" href="{{ asset('tenders') }}">
								<i class="ti ti-list"></i> Senarai Tender
							</a>
						@else
							<a class="dropdown-item" href="{{ asset('agencies/' . Auth::user()->organization_unit_id) }}">
								<i class="ti ti-list"></i> Senarai Tender
							</a>
						@endif
					@endif

					@if (App\Vendor::canList())
						<a class="dropdown-item" href="{{ asset('vendors') }}">
							<i class="ti ti-building"></i> Senarai Syarikat
						</a>
					@endif

					@if (App\VendorBlacklist::canList())
						<a class="dropdown-item" href="{{ asset('blacklists') }}">
							<i class="ti ti-ban"></i> Senarai Hitam
						</a>
					@endif

					@if (App\News::canList())
						<a class="dropdown-item" href="{{ asset('news') }}">
							<i class="ti ti-news"></i> Senarai Berita
						</a>
					@endif

					@if (App\Transaction::canList())
						<a class="dropdown-item" href="{{ asset('transactions') }}">
							<i class="ti ti-receipt"></i> Senarai Transaksi
						</a>
					@endif
				</div>
			</div>

			@if (App\CodeRequest::canList())
				<div class="nav-item dropdown">
					<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">
						<span class="nav-link-icon d-md-none d-lg-inline-block">
							<i class="ti ti-edit"></i>
						</span>
						<span class="nav-link-title">Pengurusan Permintaan Kemaskini</span>
					</a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="{{ asset('requests') }}">
							<i class="ti ti-edit"></i> Permintaan Kemaskini
						</a>
					</div>
				</div>
		</div>
	</aside>
@endif

@if (Auth::user()->ability(['Admin', 'Agency Admin'], []))
	<div class="nav-item dropdown">
		<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">
			<span class="nav-link-icon d-md-none d-lg-inline-block">
				<i class="ti ti-settings"></i>
			</span>
			<span class="nav-link-title">Pengurusan Sistem</span>
		</a>
		<div class="dropdown-menu">
			@if (App\User::canList())
				<a class="dropdown-item" href="{{ asset('users') }}">
					<i class="ti ti-users"></i> Senarai Pengguna
				</a>
			@endif
			@if (Auth::user()->canApprove())
				<a class="dropdown-item" href="{{ asset('users/pending-approval') }}">
					<i class="ti ti-user-check"></i> Senarai Permohonan Pengguna
				</a>
			@endif

			@if (App\OrganizationUnit::canList())
				<a class="dropdown-item" href="{{ asset('agencies') }}">
					<i class="ti ti-building"></i> Senarai Agensi
				</a>
			@endif

			@if (Auth::user()->hasRole('Admin'))
				<a class="dropdown-item" href="{{ asset('organizationtypes') }}">
					<i class="ti ti-category"></i> Kategori Agensi
				</a>
				<a class="dropdown-item" href="{{ asset('codes') }}">
					<i class="ti ti-code"></i> Senarai Kod Bidang
				</a>
				<a class="dropdown-item" href="{{ asset('helps') }}">
					<i class="ti ti-help"></i> Senarai Soalan Bantuan
				</a>
				<a class="dropdown-item" href="{{ asset('helpcategories') }}">
					<i class="ti ti-category"></i> Kategori Soalan Bantuan
				</a>
				<a class="dropdown-item" href="{{ asset('gateways') }}">
					<i class="ti ti-credit-card"></i> Tetapan Pembayaran
				</a>
				<a class="dropdown-item" href="{{ asset('banners') }}">
					<i class="ti ti-photo"></i> Senarai Banner
				</a>
			@endif

			@if (Auth::user()->can('System:histories'))
				<a class="dropdown-item" href="{{ asset('version-histories') }}">
					<i class="ti ti-history"></i> Sejarah Perubahan Sistem
				</a>
			@endif

			@if (App\Models\RejectTemplate::canList())
				<a class="dropdown-item" href="{{ asset('reject-template') }}">
					<i class="ti ti-x"></i> Senarai Templat Penolakan
				</a>
			@endif

			@if (App\Models\Circular::canList())
				<a class="dropdown-item" href="{{ asset('circulars') }}">
					<i class="ti ti-file-text"></i> Senarai Pekeliling
				</a>
			@endif
		</div>
	</div>
@endif

@if (Auth::user()->hasRole('Admin'))
	<div class="nav-item dropdown">
		<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">
			<span class="nav-link-icon d-md-none d-lg-inline-block">
				<i class="ti ti-shield"></i>
			</span>
			<span class="nav-link-title">Pengurusan Akses</span>
		</a>
		<div class="dropdown-menu">
			@if (App\Role::canList())
				<a class="dropdown-item" href="{{ asset('roles') }}">
					<i class="ti ti-user"></i> Tetapan Peranan
				</a>
			@endif

			@if (App\Permission::canList())
				<a class="dropdown-item" href="{{ asset('permissions') }}">
					<i class="ti ti-key"></i> Tetapan Kebenaran
				</a>
			@endif
		</div>
	</div>
@endif

@if (Auth::user()->ability(['Admin', 'Refund Admin'], ['Refund:list']))
	<div class="nav-item dropdown">
		<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button"
			aria-expanded="false">
			<span class="nav-link-icon d-md-none d-lg-inline-block">
				<i class="ti ti-refresh"></i>
			</span>
			<span class="nav-link-title">Pengurusan Pemulangan Semula</span>
		</a>
		<div class="dropdown-menu">
			@if (App\Models\Refund::canList())
				<a class="dropdown-item" href="{{ route('refunds.request.index') }}">
					<i class="ti ti-refresh"></i> Permohonan Pemulangan Semula
				</a>
			@endif

			@if (App\Models\Refund::isRoleBKP())
				<a class="dropdown-item" href="{{ route('refunds.complaint.index') }}">
					<i class="ti ti-alert-circle"></i> Aduan Permohonan Semula
				</a>
			@endif
		</div>
	</div>
@endif

@if (Auth::user()->ability(['Admin'], ['Api:canList']))
	<div class="nav-item dropdown">
		<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button"
			aria-expanded="false">
			<span class="nav-link-icon d-md-none d-lg-inline-block">
				<i class="ti ti-api"></i>
			</span>
			<span class="nav-link-title">Pengurusan API</span>
		</a>
		<div class="dropdown-menu">
			@if (App\Models\ApiToken::canList())
				<a class="dropdown-item" href="{{ route('apitoken.index') }}">
					<i class="ti ti-key"></i> Senarai API Token
				</a>
			@endif
		</div>
	</div>
@endif

@if (Auth::user()->ability(['Admin'], ['chatbot-manager:canList']))
	<div class="nav-item dropdown">
		<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button"
			aria-expanded="false">
			<span class="nav-link-icon d-md-none d-lg-inline-block">
				<i class="ti ti-robot"></i>
			</span>
			<span class="nav-link-title">Pengurusan ChatBot</span>
		</a>
		<div class="dropdown-menu">
			@if (App\Models\FaqCategory::canList())
				<a class="dropdown-item" href="{{ route('chatbot-manager.category.index') }}">
					<i class="ti ti-category"></i> Senarai Kategori
				</a>
				<a class="dropdown-item" href="{{ route('chatbot-manager.question.index') }}">
					<i class="ti ti-help"></i> Senarai Soalan
				</a>
				<a class="dropdown-item" href="{{ route('chatbot-manager.chatlog.index') }}">
					<i class="ti ti-message"></i> Senarai Rekod Chat
				</a>
				<a class="dropdown-item" href="{{ route('chatbot-manager.newquestion.index') }}">
					<i class="ti ti-question-mark"></i> Senarai Pertanyaan Tidak Wujud
				</a>
			@endif
		</div>
	</div>
@endif

@if (Auth::user()->ability(['Admin'], ['chatbot-manager:canList']))
	<div class="nav-item dropdown">
		<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button"
			aria-expanded="false">
			<span class="nav-link-icon d-md-none d-lg-inline-block">
				<i class="ti ti-mail"></i>
			</span>
			<span class="nav-link-title">Pengurusan Email SMTP</span>
		</a>
		<div class="dropdown-menu">
			@if (App\Models\FaqCategory::canList())
				<a class="dropdown-item" href="{{ route('mail-manager.smtp-setting.index') }}">
					<i class="ti ti-settings"></i> Senarai Email SMTP
				</a>
				<a class="dropdown-item" href="{{ route('mail-manager.mail-queue.index') }}">
					<i class="ti ti-clock"></i> Rekod Penghantaran Email
				</a>
			@endif
		</div>
	</div>
@endif

@if (Auth::user()->ability(['Admin'], []))
	<div class="nav-item dropdown">
		<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button"
			aria-expanded="false">
			<span class="nav-link-icon d-md-none d-lg-inline-block">
				<i class="ti ti-alert-triangle"></i>
			</span>
			<span class="nav-link-title">Aduan</span>
		</a>
		<div class="dropdown-menu">
			<a class="dropdown-item" href="{{ asset('aduan/list') }}">
				<i class="ti ti-list"></i> Senarai Aduan
			</a>
		</div>
	</div>
@endif

@if (Auth::user()->ability(['Admin', 'Admin Kewangan'], []))
	<div class="nav-item dropdown">
		<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button"
			aria-expanded="false">
			<span class="nav-link-icon d-md-none d-lg-inline-block">
				<i class="ti ti-dashboard"></i>
			</span>
			<span class="nav-link-title">Dashboard</span>
		</a>
		<div class="dropdown-menu">
			<a class="dropdown-item" href="{{ asset('dashboard/hq') }}">
				<i class="ti ti-chart-bar"></i> Dashboard Pengurusan
			</a>
		</div>
	</div>
@endif

@if (Auth::user()->can('Report:view'))
	<div class="nav-item dropdown">
		<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button"
			aria-expanded="false">
			<span class="nav-link-icon d-md-none d-lg-inline-block">
				<i class="ti ti-chart-bar"></i>
			</span>
			<span class="nav-link-title">Laporan</span>
		</a>
		<div class="dropdown-menu">
			@if (Auth::user()->can('Report:view:revenue_yearly'))
				<a class="dropdown-item" href="{{ asset('reports/revenue') }}">
					<i class="ti ti-currency-dollar"></i> Hasil Transaksi Tahunan
				</a>
			@endif

			@if (Auth::user()->can('Report:view:agency_active'))
				<a class="dropdown-item" href="{{ asset('reports/agency/active') }}">
					<i class="ti ti-building"></i> 10 Agensi Aktif
				</a>
			@endif

			@if (Auth::user()->can('Report:view:agency_transaction'))
				<a class="dropdown-item" href="{{ asset('reports/agency/all') }}">
					<i class="ti ti-receipt"></i> Transaksi Semua Agensi
				</a>
			@endif

			@if (Auth::user()->can('Report:view:agency_type'))
				<a class="dropdown-item" href="{{ asset('reports/agency/type') }}">
					<i class="ti ti-category"></i> Transaksi Mengikut Kategori Agensi
				</a>
			@endif

			@if (Auth::user()->can('Report:view:agency_tender') ||
					Auth::user()->can('Report:view:agency_tender:organization_unit_id'))
				<a class="dropdown-item" href="{{ asset('reports/agency/transaction') }}">
					<i class="ti ti-tender"></i> Transaksi Agensi Mengikut Tender
				</a>
			@endif

			@if (Auth::user()->can('Report:view:agency_daily') || Auth::user()->can('Report:view:agency_daily:organization_unit_id'))
				<a class="dropdown-item" href="{{ asset('reports/agency/daily') }}">
					<i class="ti ti-calendar"></i> Transaksi Harian Agensi
				</a>
			@endif

			@if (Auth::user()->can('Report:view:gateway_daily') ||
					Auth::user()->can('Report:view:gateway_daily:organization_unit_id'))
				<a class="dropdown-item" href="{{ asset('reports/gateway/daily') }}">
					<i class="ti ti-credit-card"></i> Transaksi Harian Gateway
				</a>
			@endif

			@if (Auth::user()->can('Report:view:vendor_status'))
				<a class="dropdown-item" href="{{ asset('reports/vendor/status') }}">
					<i class="ti ti-building"></i> Syarikat Mengikut Status
				</a>
			@endif

			@if (Auth::user()->can('Report:view:vendor_code'))
				<a class="dropdown-item" href="{{ asset('reports/vendor/codes') }}">
					<i class="ti ti-code"></i> Syarikat Mengikut Kod Bidang
				</a>
			@endif

			@if (Auth::user()->can('Report:view:vendor_district'))
				<a class="dropdown-item" href="{{ asset('reports/vendor/district') }}">
					<i class="ti ti-map-pin"></i> Syarikat Mengikut Daerah
				</a>
			@endif

			@if (Auth::user()->can('Report:view:user_agency') || Auth::user()->can('Report:view:user_agency:organization_unit_id'))
				<a class="dropdown-item" href="{{ asset('reports/user/agency') }}">
					<i class="ti ti-users"></i> Senarai Pengguna Agensi
				</a>
			@endif

			@if (Auth::user()->can('Report:view:user_agency') || Auth::user()->can('Report:view:user_active:organization_unit_id'))
				<a class="dropdown-item" href="{{ asset('reports/user/active') }}">
					<i class="ti ti-user-check"></i> Senarai Status Pengguna Mengikut Agensi
				</a>
			@endif

			@if (Auth::user()->can('Report:view:user_activity'))
				<a class="dropdown-item" href="{{ asset('reports/user/activity') }}">
					<i class="ti ti-activity"></i> Laporan Aktiviti Staf
				</a>
			@endif

			@if (Auth::user()->can('Report:view:user_login'))
				<a class="dropdown-item" href="{{ asset('reports/user/login') }}">
					<i class="ti ti-login"></i> Laporan Login Sebagai
				</a>
			@endif

			@if (Auth::user()->can('Report:view:vendor_registration_list'))
				<a class="dropdown-item" href="{{ asset('reports/vendor/registration-list') }}">
					<i class="ti ti-user-plus"></i> Laporan Pendaftaran Syarikat
				</a>
			@endif

			@if (Auth::user()->can('Report:view:code_request'))
				<a class="dropdown-item" href="{{ asset('reports/vendor/request') }}">
					<i class="ti ti-edit"></i> Laporan Permohonan Kemaskini Maklumat Syarikat
				</a>
			@endif

			@if (Auth::user()->can('Report:view:vendor_registration'))
				<a class="dropdown-item" href="{{ asset('reports/vendor/registration') }}">
					<i class="ti ti-user-plus"></i> Laporan Pendaftaran Pengguna Sistem
				</a>
			@endif

			@if (Auth::user()->can('Report:view:staff_activity'))
				<a class="dropdown-item" href="{{ asset('reports/staff/activity') }}">
					<i class="ti ti-activity"></i> Laporan Aktiviti Pengguna Sistem
				</a>
			@endif

			@if (Auth::user()->can('Report:view:code_district'))
				<a class="dropdown-item" href="{{ asset('reports/code/district') }}">
					<i class="ti ti-map-pin"></i> Laporan Jumlah Berkaitan Kod Bidang
				</a>
			@endif

			@if (Auth::user()->can('Report:view:vendor_transaction'))
				<a class="dropdown-item" href="{{ asset('reports/vendor/transaction') }}">
					<i class="ti ti-receipt"></i> Laporan Transaksi
				</a>
			@endif

			@if (Auth::user()->can('Report:view:transaction_hasil'))
				<a class="dropdown-item" href="{{ asset('reports/transaction/hasil') }}">
					<i class="ti ti-currency-dollar"></i> Laporan Transaksi Mengikut Kod Akaun Hasil
				</a>
			@endif
		</div>
	</div>
@endif
</div>
@endif
