<!-- Navbar -->
<header class="navbar navbar-expand-md navbar-light d-print-none">
	<div class="container-xl">
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
			aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
			<!-- Inline SVG hamburger to avoid relying on external CSS background icons -->
			<svg class="navbar-toggler-icon" width="24" height="24" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg"
				aria-hidden="true" focusable="false">
				<path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"
					d="M4 7h22M4 15h22M4 23h22" />
			</svg>
		</button>

		<!-- Sidebar toggle (desktop) -->
		<button id="sidebarToggle" class="btn btn-icon btn-light ms-2 d-none d-lg-inline-block" title="Toggle sidebar"
			aria-label="Toggle sidebar">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
				stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<path d="M3 12h18"></path>
				<path d="M3 6h18"></path>
				<path d="M3 18h18"></path>
			</svg>
		</button>
		<h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
			<a href="/" title="Utama">
				<img src="{{ asset('images/header.png') }}" width="110" height="32" alt="Sistem Tender Online Selangor"
					class="navbar-brand-image">
			</a>
		</h1>
		<div class="navbar-nav flex-row order-md-last">
			@if (!empty($user) && $user->hasRole('Vendor'))
				<div class="nav-item dropdown">
					<a href="{{ asset('cart') }}" class="nav-link d-flex lh-1 text-reset p-0">
						<span class="avatar avatar-sm" style="background-image: url({{ asset('images/cart-icon.png') }})"></span>
						<div class="d-none d-xl-block ps-2">
							<div>Senarai Tempahan</div>
							<div class="mt-1 small text-muted">{{ App\Cart::count() }} item</div>
						</div>
					</a>
				</div>
			@endif

			@if (!empty($user))
				<div class="nav-item dropdown">
					<a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown">
						<span class="avatar avatar-sm" style="background-image: url({{ asset('images/user-avatar.png') }})"></span>
						<div class="d-none d-xl-block ps-2">
							<div>{{ $user->vendor ? $user->vendor->name : $user->name }}</div>
							<div class="mt-1 small text-muted">{{ $user->email }}</div>
						</div>
					</a>
					<div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
						@if ($user && $user->hasRole('Vendor') && $user->vendor)
							<a href="/dashboard" class="dropdown-item">
								<i class="ti ti-dashboard me-2"></i> Akaun Saya
							</a>
							<a href="{{ action('VendorsController@certificate', $user->vendor->id) }}" target="_blank" class="dropdown-item">
								<i class="ti ti-certificate me-2"></i> Papar Sijil Pengesahan
							</a>
							<a
								href="{{ action('ReportVendorSummaryController@index', ['year' => date('Y'), 'vendor_id' => $user->vendor->id]) }}"
								class="dropdown-item">
								<i class="ti ti-chart-pie me-2"></i> Laporan Transaksi Syarikat
							</a>
						@endif
						<a href="{{ asset('profile') }}" class="dropdown-item">
							<i class="ti ti-user me-2"></i> Profil Saya
						</a>
						@if ($user && $user->hasRole('Vendor') && Auth::user()->vendor->registration_paid)
							<a href="{{ asset('vendor/' . Auth::user()->vendor_id . '/requests') }}" class="dropdown-item">
								<i class="ti ti-heart me-2"></i> Permintaan Kemaskini
							</a>
						@endif
						@if (Session::has('original_user_id'))
							<a href="{{ route('release_user') }}" class="dropdown-item">
								<i class="ti ti-key me-2"></i> Kembali ke Pengguna Asal
							</a>
						@endif
						<div class="dropdown-divider"></div>
						<a href="{{ route('manuals.show', 'pendaftaran') }}" target="_blank" class="dropdown-item">
							<i class="ti ti-book me-2"></i> Panduan Pengguna
						</a>
						<a href="{{ asset('auth/logout') }}" class="dropdown-item">
							<i class="ti ti-logout me-2"></i> Daftar Keluar
						</a>
					</div>
				</div>
			@else
				<div class="nav-item">
					<a href="{{ route('registration') }}" class="btn btn-outline-primary me-2">Daftar Akaun</a>
					<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">Daftar
						Masuk</button>
				</div>
			@endif
		</div>
		<div class="collapse navbar-collapse" id="navbar-menu">
			<div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" href="{{ action('HomeController@index') }}">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<i class="ti ti-home"></i>
							</span>
							<span class="nav-link-title">Utama</span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ action('HomeController@prices') }}">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<i class="ti ti-chart-line"></i>
							</span>
							<span class="nav-link-title">Carta Tender</span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ action('HomeController@results') }}">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<i class="ti ti-trophy"></i>
							</span>
							<span class="nav-link-title">Penender Berjaya</span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ route('circulars.public') }}">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<i class="ti ti-file-text"></i>
							</span>
							<span class="nav-link-title">Pekeliling</span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ route('aduan.create') }}">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<i class="ti ti-message-circle"></i>
							</span>
							<span class="nav-link-title">Aduan</span>
						</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown" role="button"
							aria-expanded="false">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<i class="ti ti-help-circle"></i>
							</span>
							<span class="nav-link-title">Pertanyaan</span>
						</a>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="{{ action('HelpsController@index') }}">Bantuan</a>
							<a class="dropdown-item" href="{{ route('manuals.show', 'pendaftaran') }}">Panduan Pengguna</a>
						</div>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#navbar-agencies" data-bs-toggle="dropdown" role="button"
							aria-expanded="false">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<i class="ti ti-building"></i>
							</span>
							<span class="nav-link-title">Direktori Agensi</span>
						</a>
						<div class="dropdown-menu">
							@php
								// Guard DB access in case database is unavailable during rendering
								try {
								    $__orgTypes = App\OrganizationType::orderBy('sort_no', 'asc')->get();
								} catch (\Throwable $e) {
								    $__orgTypes = collect();
								}
							@endphp
							@foreach ($__orgTypes as $type)
								<a class="dropdown-item"
									href="{{ action('OrganizationUnitsController@index', ['type' => $type->id]) }}">{{ $type->name }}</a>
							@endforeach
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
</header>

<!-- Login Modal -->
@if (empty($user))
	<div class="modal modal-blur fade" id="loginModal" tabindex="-1" role="dialog"
		aria-labelledby="loginModalLabel">
		<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="loginModalLabel">Daftar Masuk</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form method="POST" action="{{ action('AuthController@doLogin') }}">
					@csrf
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">Alamat Emel</label>
							<input type="email" class="form-control" name="email" placeholder="Alamat Emel"
								value="{{ old('email') }}" required autocomplete="email">
							@error('email')
								<div class="text-danger small">{{ $message }}</div>
							@enderror
						</div>
						<div class="mb-3">
							<label class="form-label">Kata Laluan</label>
							<input type="password" class="form-control" name="password" placeholder="Kata Laluan" required
								autocomplete="current-password">
							@error('password')
								<div class="text-danger small">{{ $message }}</div>
							@enderror
						</div>
						@if ($errors->has('login'))
							<div class="alert alert-danger">
								{{ $errors->first('login') }}
							</div>
						@endif
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary">Daftar Masuk</button>
					</div>
				</form>
				<div class="modal-body pt-0">
					<div class="text-center">
						<a href="{{ action('AuthController@forgotPassword') }}" class="text-muted">Lupa Kata Laluan?</a> &bullet;
						<a href="{{ route('registration') }}" class="text-muted">Daftar Akaun!</a> &bullet;
						<a href="{{ route('manuals.show', 'pendaftaran') }}" target="_blank" class="text-muted">Cara Mendaftar</a>
					</div>
				</div>
			</div>
		</div>
	</div>
@endif
