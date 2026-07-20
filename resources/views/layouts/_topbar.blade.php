<div class="navbar navbar-expand-md navbar-light d-print-none">
				<div class="container-xl">
					<h1 class="navbar-brand navbar-brand-autodark">
						Welcome {{ data_get($user, 'name') }} !
					</h1>
					<button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse"
						data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false"
						aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="navbar-nav flex-row order-md-last ms-auto">
						@if (!empty($user))
							<!-- USER DROPDOWN - SUPER VISIBLE -->
							<div class="dropdown">
								<button class="btn btn-primary dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
									<span class="avatar avatar-sm" style="background-image: url({{ asset('images/user-avatar.png') }});"></span>
									<div class="text-start">
										<div class="fw-bold">{{ $user->name }}</div>
										<div class="small opacity-75">{{ $user->email }}</div>
									</div>
									<i class="ti ti-chevron-down"></i>
								</button>
								<ul class="dropdown-menu dropdown-menu-end">
									<li><a href="{{ asset('profile') }}" class="dropdown-item">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
												fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-user">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M12 2a5 5 0 1 1 -5 5l.005 -.217a5 5 0 0 1 4.995 -4.783z" />
												<path d="M14 14a5 5 0 0 1 5 5v1a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-1a5 5 0 0 1 5 -5h4z" />
											</svg>
											<i class="ti ti-user me-2"></i> Profil Saya
										</a></li>
									<li><a href="{{ route('logout') }}" class="dropdown-item">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
												fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-logout">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"></path>
												<path d="M9 12h12l-3 -3"></path>
												<path d="M18 15l3 -3"></path>
											</svg>
											<i class="ti ti-logout me-2"></i> Daftar Keluar
										</a></li>
									<li><a href="{{ route('manuals.show', 'pendaftaran') }}" target="_blank" class="dropdown-item">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
												fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
												stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-files">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M15 3v4a1 1 0 0 0 1 1h4" />
												<path d="M18 17h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h4l5 5v7a2 2 0 0 1 -2 2z" />
												<path d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h2" />
											</svg>
											<i class="ti ti-book me-2"></i> Panduan Pengguna
										</a></li>
								</ul>
							</div>
						@else
							<div class="nav-item">
								<a href="{{ route('registration') }}" class="btn btn-outline-primary me-2">Daftar Akaun</a>
								<button type="button" class="btn btn-primary" id="loginButton" onclick="openLoginModal()">
									Daftar Masuk
								</button>
							</div>
						@endif
					</div>
				</div>
			</div>