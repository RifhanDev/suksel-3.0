<!-- TOPBAR -->
<nav class="topbar">
	<!-- Left: Mobile Toggle & Title -->
	<div class="topbar-left">
		<button class="mobile-toggle mobile-menu-toggle">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
		</button>
		@if (!empty($user))
		<div class="page-title-group">
			<h1 class="page-title">Selamat Datang, {{ data_get($user, 'name') }}!</h1>
			<span class="current-date">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
				{{ \Carbon\Carbon::now()->format('l, d F Y') }}
			</span>
		</div>
		@else
		<h1 class="page-title">Sistem Tender Online Selangor</h1>
		@endif
	</div>

	<!-- Right: User Menu -->
	<div class="topbar-right">
		@if (!empty($user))
		<div class="dropdown">
			<button class="user-menu-btn" data-bs-toggle="dropdown" aria-expanded="false">
				<div class="user-info">
					<span class="user-name">{{ Str::limit(data_get($user, 'name'), 30) }}</span>
					<span class="user-role">{{ Auth::user()->roles->first()->name ?? 'Pengguna' }}</span>
				</div>
				<div class="user-avatar">
					{{ strtoupper(substr(data_get($user, 'name'), 0, 1)) }}
				</div>
			</button>
			<ul class="dropdown-menu dropdown-menu-end custom-dropdown-menu">
				<li><h6 class="dropdown-header-custom">Akaun Saya</h6></li>
				<li>
					<a class="dropdown-item" href="{{ asset('profile') }}">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
						Profil & Tetapan
					</a>
				</li>
				<li>
					<a class="dropdown-item" href="{{ route('manuals.show', 'pendaftaran') }}" target="_blank">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
						Pusat Bantuan
					</a>
				</li>
				<li><hr class="dropdown-divider"></li>
				<li>
					<a class="dropdown-item text-danger" href="{{ route('logout') }}">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-danger"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
						Log Keluar
					</a>
				</li>
			</ul>
		</div>
		@else
		<div class="d-flex">
			<a href="{{ route('registration') }}" class="btn-guest btn-guest-outline">Daftar</a>
			<a href="{{ route('login') }}" class="btn-guest btn-guest-solid">Masuk</a>
		</div>
		@endif
	</div>
</nav>