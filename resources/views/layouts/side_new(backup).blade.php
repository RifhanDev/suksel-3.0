<aside class="sidebar-vertical" id="sidebar">
			<div class="sidebar-header">
				<a href="/" class="sidebar-brand">
					<img src="{{ asset('images/02_selangor.png') }}" alt="Sistem Tender Online Selangor" class="sidebar-brand-image">
				</a>
			</div>
			<div class="sidebar-body">
				<nav class="sidebar-nav" id="sidebar-menu">
					<div class="nav-item">
						<a href="{{ action('HomeController@index') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
									class="icon icon-tabler icons-tabler-outline icon-tabler-home">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path d="M5 12l-2 0l9 -9l9 9l-2 0" />
									<path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
									<path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
								</svg>
							</span>
							<span class="nav-link-title">Utama</span>
						</a>
					</div>
					<div class="nav-item">
						<a href="{{ action('HomeController@prices') }}" class="nav-link {{ request()->is('prices*') ? 'active' : '' }}">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
									class="icon icon-tabler icons-tabler-filled icon-tabler-files">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path
										d="M11 2l3 .001v5.999a1 1 0 0 0 .883 .993l.117 .007h6v6a3 3 0 0 1 -3 3h-1v1a3 3 0 0 1 -3 3h-7a3 3 0 0 1 -3 -3v-10a3 3 0 0 1 3 -3h1v-1a3 3 0 0 1 3 -3m-3 6h-1a1 1 0 0 0 -1 1v10a1 1 0 0 0 1 1h7a1 1 0 0 0 1 -1v-1h-4a3 3 0 0 1 -3 -3zm12.415 -1h-4.415v-4.415z" />
								</svg>
							</span>
							<span class="nav-link-title">Carta Tender</span>
						</a>
					</div>
					<div class="nav-item">
						<a href="{{ action('HomeController@results') }}"
							class="nav-link {{ request()->is('results*') ? 'active' : '' }}">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
									class="icon icon-tabler icons-tabler-outline icon-tabler-device-ipad-check">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path d="M11.5 21h-5.5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v8" />
									<path d="M9 18h2" />
									<path d="M15 19l2 2l4 -4" />
								</svg>
							</span>
							<span class="nav-link-title">Penender Berjaya</span>
						</a>
					</div>
					<div class="nav-item">
						<a href="{{ route('circulars.public') }}" class="nav-link {{ request()->is('circulars*') ? 'active' : '' }}">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
									class="icon icon-tabler icons-tabler-outline icon-tabler-circles-relation">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path d="M9.183 6.117a6 6 0 1 0 4.511 3.986" />
									<path d="M14.813 17.883a6 6 0 1 0 -4.496 -3.954" />
								</svg>
							</span>
							<span class="nav-link-title">Pekeliling</span>
						</a>
					</div>
					<div class="nav-item">
						<a href="{{ route('aduan.create') }}" class="nav-link {{ request()->is('aduan*') ? 'active' : '' }}">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
									class="icon icon-tabler icons-tabler-outline icon-tabler-mail-spark">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path
										d="M19 22.5a4.75 4.75 0 0 1 3.5 -3.5a4.75 4.75 0 0 1 -3.5 -3.5a4.75 4.75 0 0 1 -3.5 3.5a4.75 4.75 0 0 1 3.5 3.5" />
									<path d="M11.5 19h-6.5a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v5" />
									<path d="M3 7l9 6l9 -6" />
								</svg>
							</span>
							<span class="nav-link-title">Aduan</span>
						</a>
					</div>
					<div class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button"
							aria-expanded="false">
							<span class="nav-link-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
									class="icon icon-tabler icons-tabler-outline icon-tabler-bookmark-question">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path d="M15 19l-3 -2l-6 4v-14a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v4" />
									<path d="M19 22v.01" />
									<path d="M19 19a2.003 2.003 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" />
								</svg>
							</span>
							<span class="nav-link-title">Pertanyaan</span>
						</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="{{ action('HelpsController@index') }}">Bantuan</a></li>
							<li><a class="dropdown-item" href="{{ route('manuals.show', 'pendaftaran') }}">Panduan Pengguna</a></li>
						</ul>
					</div>
					<div class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button"
							aria-expanded="false">
							<span class="nav-link-icon">
								<i class="ti ti-building"></i>
							</span>
							<span class="nav-link-title">Direktori Agensi</span>
						</a>
						<ul class="dropdown-menu">
							@php
								try {
								    $__orgTypes = App\OrganizationType::orderBy('sort_no', 'asc')->get();
								} catch (\Throwable $e) {
								    $__orgTypes = collect();
								}
							@endphp
							@foreach ($__orgTypes as $type)
								<li><a class="dropdown-item"
									href="{{ action('OrganizationUnitsController@index', ['type' => $type->id]) }}">{{ $type->name }}</a></li>
							@endforeach
						</ul>
					</div>

					{{-- ADMIN MENU SECTION --}}
					@if (Auth::check() && !Auth::user()->hasRole('Vendor'))
						<!-- Separator for Admin Section -->
						<div
							style="border-top: 1px solid rgba(255, 255, 255, 0.1); margin: var(--space-6) 0; padding-top: var(--space-3);">
							<div
								style="color: rgba(255, 255, 255, 0.5); font-size: 0.75rem; padding: 0 var(--space-4); margin-bottom: var(--space-3); text-transform: uppercase; letter-spacing: 0.05em;">
								Menu Pentadbir
							</div>
						</div>

						<!-- Pengurusan Tender -->
						<div class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button"
								aria-expanded="false">
								<span class="nav-link-title">Pengurusan Tender</span>
							</a>
							<ul class="dropdown-menu">
								@if (App\Tender::canList())
									@if (Auth::user()->ability(['Admin', 'Registration Assesor', 'Front Desk'], []))
										<li><a class="dropdown-item" href="{{ asset('tenders') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Senarai Tender
										</a></li>
									@else
										<li><a class="dropdown-item"
											href="{{ asset('agencies/' . Auth::user()->organization_unit_id) }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Senarai Tender
										</a></li>
									@endif
								@endif
								@if (App\Vendor::canList())
									<li><a class="dropdown-item" href="{{ asset('vendors') }}">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
											stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
											class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
											<path stroke="none" d="M0 0h24v24H0z" fill="none" />
											<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
										</svg> Senarai Syarikat
									</a></li>
								@endif
								@if (App\VendorBlacklist::canList())
									<li><a class="dropdown-item" href="{{ asset('blacklists') }}">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
											stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
											class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
											<path stroke="none" d="M0 0h24v24H0z" fill="none" />
											<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
										</svg> Senarai Hitam
									</a></li>
								@endif
								@if (App\News::canList())
									<li><a class="dropdown-item" href="{{ asset('news') }}">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
											stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
											class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
											<path stroke="none" d="M0 0h24v24H0z" fill="none" />
											<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
										</svg> Senarai Berita
									</a></li>
								@endif
								@if (App\Transaction::canList())
									<li><a class="dropdown-item" href="{{ asset('transactions') }}">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
											stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
											class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
											<path stroke="none" d="M0 0h24v24H0z" fill="none" />
											<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
										</svg> Senarai Transaksi
									</a></li>
								@endif
							</ul>
						</div>

						@if (App\CodeRequest::canList())
							<!-- Pengurusan Permintaan Kemaskini -->
							<div class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"
									role="button" aria-expanded="false">
									<span class="nav-link-title">Pengurusan Permintaan</span>
									{{-- <span class="nav-link-title">Pengurusan Permintaan Kemaskini</span> --}}
								</a>
								<ul class="dropdown-menu">
									<li><a class="dropdown-item" href="{{ asset('requests') }}">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
											stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
											class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
											<path stroke="none" d="M0 0h24v24H0z" fill="none" />
											<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
										</svg> Permintaan Kemaskini
									</a></li>
								</ul>
							</div>
						@endif

						@if (Auth::user()->ability(['Admin', 'Agency Admin'], []))
							<!-- Pengurusan Sistem -->
							<div class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button"
									aria-expanded="false">
									<span class="nav-link-title">Pengurusan Sistem</span>
								</a>
								<ul class="dropdown-menu">
									@if (App\User::canList())
										<li><a class="dropdown-item" href="{{ asset('users') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Senarai Pengguna
										</a></li>
									@endif
									@if (Auth::user()->canApprove())
										<li><a class="dropdown-item" href="{{ asset('users/pending-approval') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Senarai Permohonan Pengguna
										</a></li>
									@endif
									@if (App\OrganizationUnit::canList())
										<li><a class="dropdown-item" href="{{ asset('agencies') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Senarai Agensi
										</a></li>
									@endif
									@if (Auth::user()->hasRole('Admin'))
										<li><a class="dropdown-item" href="{{ asset('organizationtypes') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Kategori Agensi
										</a></li>
										<li><a class="dropdown-item" href="{{ asset('codes') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Senarai Kod Bidang
										</a></li>
										<li><a class="dropdown-item" href="{{ asset('helps') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Senarai Soalan Bantuan
										</a></li>
										<li><a class="dropdown-item" href="{{ asset('helpcategories') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Kategori Soalan Bantuan
										</a></li>
										<li><a class="dropdown-item" href="{{ asset('gateways') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Tetapan Pembayaran
										</a></li>
										<li><a class="dropdown-item" href="{{ asset('banners') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Senarai Banner
										</a></li>
									@endif
									@if (Auth::user()->can('System:histories'))
										<li><a class="dropdown-item" href="{{ asset('version-histories') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Sejarah Perubahan Sistem
										</a></li>
									@endif
									@if (App\Models\RejectTemplate::canList())
										<li><a class="dropdown-item" href="{{ asset('reject-template') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Senarai Templat Penolakan
										</a></li>
									@endif
									@if (App\Models\Circular::canList())
										<li><a class="dropdown-item" href="{{ asset('circulars') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Senarai Pekeliling
										</a></li>
									@endif
								</ul>
							</div>
						@endif

						@if (Auth::user()->hasRole('Admin'))
							<!-- Pengurusan Akses -->
							<div class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button"
									aria-expanded="false">
									<span class="nav-link-title">Pengurusan Akses</span>
								</a>
								<ul class="dropdown-menu">
									@if (App\Role::canList())
										<li><a class="dropdown-item" href="{{ asset('roles') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Tetapan Peranan
										</a></li>
									@endif
									@if (App\Permission::canList())
										<li><a class="dropdown-item" href="{{ asset('permissions') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Tetapan Kebenaran
										</a></li>
									@endif
								</ul>
							</div>
						@endif

						@if (Auth::user()->ability(['Admin', 'Refund Admin'], ['Refund:list']))
							{{-- @if (Auth::user()->hasRole('Admin')) --}}
							<!-- Pengurusan Pemulangan Semula -->
							<div class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button"
									aria-expanded="false">
									<span class="nav-link-title">Pengurusan Pemulangan Semula</span>
								</a>
								<br>
								<div class="dropdown-menu dropdown-menu-refund">
									@if (App\Models\Refund::canList())
										<li><a class="dropdown-item" href="{{ route('refunds.request.index') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right me-2 flex-shrink-0">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg>
											<span class="dropdown-item-text">Permohonan Pemulangan Semula</span>
										</a></li>
									@endif
									@if (App\Models\Refund::isRoleBKP())
										<li><a class="dropdown-item" href="{{ route('refunds.complaint.index') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right me-2 flex-shrink-0">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg>
											<span class="dropdown-item-text">Aduan Permohonan Semula</span>
										</a></li>
									@endif
								</ul>
							</div>
						@endif

						@if (Auth::user()->ability(['Admin'], ['Api:canList']))
							<!-- Pengurusan API -->
							<div class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button"
									aria-expanded="false">
									<span class="nav-link-title">Pengurusan API</span>
								</a>
								<ul class="dropdown-menu">
									@if (App\Models\ApiToken::canList())
										<li><a class="dropdown-item" href="{{ route('apitoken.index') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Senarai API Token
										</a></li>
									@endif
								</ul>
							</div>
						@endif

						@if (Auth::user()->ability(['Admin'], ['chatbot-manager:canList']))
							<!-- Pengurusan ChatBot -->
							<div class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button"
									aria-expanded="false">
									<span class="nav-link-title">Pengurusan ChatBot</span>
								</a>
								<ul class="dropdown-menu">
									@if (App\Models\FaqCategory::canList())
										<li><a class="dropdown-item" href="{{ route('chatbot-manager.category.index') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Senarai Kategori
										</a></li>
										<li><a class="dropdown-item" href="{{ route('chatbot-manager.question.index') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Senarai Soalan
										</a></li>
										<li><a class="dropdown-item" href="{{ route('chatbot-manager.chatlog.index') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Senarai Rekod Chat
										</a></li>
										<li><a class="dropdown-item" href="{{ route('chatbot-manager.newquestion.index') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Senarai Pertanyaan Tidak Wujud
										</a></li>
									@endif
								</ul>
							</div>
						@endif

						@if (Auth::user()->ability(['Admin'], ['chatbot-manager:canList']))
							<!-- Pengurusan Email SMTP -->
							<div class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button"
									aria-expanded="false">
									<span class="nav-link-title">Pengurusan Email SMTP</span>
								</a>
								<ul class="dropdown-menu">
									@if (App\Models\FaqCategory::canList())
										<li><a class="dropdown-item" href="{{ route('mail-manager.smtp-setting.index') }}">
											<i class="ti ti-settings me-2"></i> Senarai Email SMTP
										</a></li>
										<li><a class="dropdown-item" href="{{ route('mail-manager.smtp-setting.index') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Senarai Email SMTP
										</a></li>
										<li><a class="dropdown-item" href="{{ route('mail-manager.mail-queue.index') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Rekod Penghantaran Email
										</a></li>
									@endif
								</ul>
							</div>
						@endif

						@if (Auth::user()->ability(['Admin'], []))
							<!-- Aduan Admin -->
							<div class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button"
									aria-expanded="false">
									<span class="nav-link-title">Pengurusan Aduan</span>
								</a>
								<ul class="dropdown-menu">
									<li><a class="dropdown-item" href="{{ asset('aduan/list') }}">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
											stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
											class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
											<path stroke="none" d="M0 0h24v24H0z" fill="none" />
											<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
										</svg> Senarai Aduan
									</a></li>
								</ul>
							</div>
						@endif

						@if (Auth::user()->ability(['Admin', 'Admin Kewangan'], []))
							<!-- Dashboard -->
							<div class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button"
									aria-expanded="false">
									<span class="nav-link-title">Dashboard</span>
								</a>
								<ul class="dropdown-menu">
									<li><a class="dropdown-item" href="{{ asset('dashboard/hq') }}">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
											stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
											class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
											<path stroke="none" d="M0 0h24v24H0z" fill="none" />
											<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
										</svg> Dashboard Pengurusan
									</a></li>
								</ul>
							</div>
						@endif

						@if (Auth::user()->can('Report:view'))
							<!-- Laporan -->
							<div class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button"
									aria-expanded="false">
									<span class="nav-link-title">Laporan</span>
								</a>
								<ul class="dropdown-menu">
									@if (Auth::user()->can('Report:view:revenue_yearly'))
										<li><a class="dropdown-item" href="{{ asset('reports/revenue') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Hasil Transaksi Tahunan
										</a></li>
									@endif
									@if (Auth::user()->can('Report:view:agency_active'))
										<li><a class="dropdown-item" href="{{ asset('reports/agency/active') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> 10 Agensi Aktif
										</a></li>
									@endif
									@if (Auth::user()->can('Report:view:agency_transaction'))
										<li><a class="dropdown-item" href="{{ asset('reports/agency/all') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Transaksi Semua Agensi
										</a></li>
									@endif
									@if (Auth::user()->can('Report:view:agency_type'))
										<li><a class="dropdown-item" href="{{ asset('reports/agency/type') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Transaksi Mengikut Kategori Agensi
										</a></li>
									@endif
									@if (Auth::user()->can('Report:view:agency_tender') ||
											Auth::user()->can('Report:view:agency_tender:organization_unit_id'))
										<li><a class="dropdown-item" href="{{ asset('reports/agency/transaction') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Transaksi Agensi Mengikut Tender
										</a></li>
									@endif
									@if (Auth::user()->can('Report:view:agency_daily') || Auth::user()->can('Report:view:agency_daily:organization_unit_id'))
										<li><a class="dropdown-item" href="{{ asset('reports/agency/daily') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Transaksi Harian Agensi
										</a></li>
									@endif
									@if (Auth::user()->can('Report:view:gateway_daily') ||
											Auth::user()->can('Report:view:gateway_daily:organization_unit_id'))
										<li><a class="dropdown-item" href="{{ asset('reports/gateway/daily') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Transaksi Harian Gateway
										</a></li>
									@endif
									@if (Auth::user()->can('Report:view:vendor_status'))
										<li><a class="dropdown-item" href="{{ asset('reports/vendor/status') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Syarikat Mengikut Status
										</a></li>
									@endif
									@if (Auth::user()->can('Report:view:vendor_code'))
										<li><a class="dropdown-item" href="{{ asset('reports/vendor/codes') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Syarikat Mengikut Kod Bidang
										</a></li>
									@endif
									@if (Auth::user()->can('Report:view:vendor_district'))
										<li><a class="dropdown-item" href="{{ asset('reports/vendor/district') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Syarikat Mengikut Daerah
										</a></li>
									@endif
									@if (Auth::user()->can('Report:view:user_agency') || Auth::user()->can('Report:view:user_agency:organization_unit_id'))
										<li><a class="dropdown-item" href="{{ asset('reports/user/agency') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Senarai Pengguna Agensi
										</a></li>
									@endif
									@if (Auth::user()->can('Report:view:user_agency') || Auth::user()->can('Report:view:user_active:organization_unit_id'))
										<li><a class="dropdown-item" href="{{ asset('reports/user/active') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Senarai Status Pengguna Mengikut Agensi
										</a></li>
									@endif
									@if (Auth::user()->can('Report:view:user_activity'))
										<li><a class="dropdown-item" href="{{ asset('reports/user/activity') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Laporan Aktiviti Staf
										</a></li>
									@endif
									@if (Auth::user()->can('Report:view:user_login'))
										<li><a class="dropdown-item" href="{{ asset('reports/user/login') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Laporan Login Sebagai
										</a></li>
									@endif
									@if (Auth::user()->can('Report:view:vendor_registration_list'))
										<li><a class="dropdown-item" href="{{ asset('reports/vendor/registration-list') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Laporan Pendaftaran Syarikat
										</a></li>
									@endif
									@if (Auth::user()->can('Report:view:code_request'))
										<li><a class="dropdown-item" href="{{ asset('reports/vendor/request') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
												class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Laporan Permohonan Kemaskini Maklumat Syarikat
										</a></li>
									@endif
									@if (Auth::user()->can('Report:view:vendor_registration'))
										<li><a class="dropdown-item" href="{{ asset('reports/vendor/registration') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
												fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
												stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Laporan Pendaftaran Pengguna Sistem
										</a></li>
									@endif
									@if (Auth::user()->can('Report:view:staff_activity'))
										<li><a class="dropdown-item" href="{{ asset('reports/staff/activity') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
												fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
												stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Laporan Aktiviti Pengguna Sistem
										</a></li>
									@endif
									@if (Auth::user()->can('Report:view:code_district'))
										<li><a class="dropdown-item" href="{{ asset('reports/code/district') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
												fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
												stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Laporan Jumlah Berkaitan Kod Bidang
										</a></li>
									@endif
									@if (Auth::user()->can('Report:view:vendor_transaction'))
										<li><a class="dropdown-item" href="{{ asset('reports/vendor/transaction') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
												fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
												stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Laporan Transaksi
										</a></li>
									@endif
									@if (Auth::user()->can('Report:view:transaction_hasil'))
										<li><a class="dropdown-item" href="{{ asset('reports/transaction/hasil') }}">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
												fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
												stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M13 7h-6l4 5l-4 5h6l4 -5z" />
											</svg> Laporan Transaksi Mengikut Kod Akaun Hasil
										</a></li>
									@endif
								</ul>
							</div>
						@endif
					@endif

					{{-- Modul 3.0 Temporary Letak Sini --}}
					<div
						style="border-top: 1px solid rgba(255, 255, 255, 0.1); margin: var(--space-6) 0; padding-top: var(--space-3);">
						<div
							style="color: rgba(255, 255, 255, 0.5); font-size: 0.75rem; padding: 0 var(--space-4); margin-bottom: var(--space-3); text-transform: uppercase; letter-spacing: 0.05em;">
							Modul Baru (3.0)
						</div>

						<div class="nav-item">
							<a href="#" class="nav-link {{ request()->is('circulars*') ? 'active' : '' }}">
								<span class="nav-link-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
										stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
										class="icon icon-tabler icons-tabler-outline icon-tabler-circles-relation">
										<path stroke="none" d="M0 0h24v24H0z" fill="none" />
										<path d="M9.183 6.117a6 6 0 1 0 4.511 3.986" />
										<path d="M14.813 17.883a6 6 0 1 0 -4.496 -3.954" />
									</svg>
								</span>
								<span class="nav-link-title">Cipta Tender</span>
							</a>
						</div>
						<div class="nav-item">
							<a href="{{ route('pelantikanJawatankuasa') }}" class="nav-link {{ request()->is('pelantikan-jawatankuasa*') ? 'active' : '' }}">
								<span class="nav-link-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
										stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
										class="icon icon-tabler icons-tabler-outline icon-tabler-circles-relation">
										<path stroke="none" d="M0 0h24v24H0z" fill="none" />
										<path d="M9.183 6.117a6 6 0 1 0 4.511 3.986" />
										<path d="M14.813 17.883a6 6 0 1 0 -4.496 -3.954" />
									</svg>
								</span>
								<span class="nav-link-title">Modul Pelantikan JawatanKuasa</span>
							</a>
						</ul>
					</div>

					@if (!empty($user))
						<!-- Logout Button in Sidebar -->
						<div class="nav-item mt-auto">
							<a href="{{ route('logout') }}" class="nav-link text-danger">
								<span class="nav-link-icon">
									<i class="ti ti-logout"></i>
									<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-logout-fallback" width="24" height="24"
										viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
										stroke-linejoin="round">
										<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
										<path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"></path>
										<path d="M9 12h12l-3 -3"></path>
										<path d="M18 15l3 -3"></path>
									</svg>
								</span>
								<span class="nav-link-title">Logout</span>
							</a>
						</div>
					@endif
				</nav>
			</div>
		</aside>