<!-- SIDEBAR -->
@if (Auth::check() && !Auth::user()->hasRole('Vendor'))
    <aside class="sidebar" id="sidebar">
        <!-- Header -->
        <div class="sidebar-header">
            <a href="/" class="sidebar-brand">
                <div class="sidebar-logo-container">
                    <img src="{{ asset('images/Jata_Negeri_Selangor_2025.png') }}" alt="Logo" class="sidebar-logo">
                </div>
                <div class="sidebar-brand-text">
                    <span class="brand-title">
                        <span class="brand-title-top">Perolehan</span>
                        <span class="brand-title-bottom">Selangor</span>
                    </span>
                    <span class="brand-subtitle">Sistem Perolehan Selangor</span>
                </div>
            </a>
        </div>

        <!-- Scrollable Area -->
        <div class="sidebar-scroll-area">
            <ul class="sidebar-nav">

                <!-- 1. PENGURUSAN TENDER -->
                @php
                    $isTenderMenuActive =
                        request()->routeIs('ciptaTender') ||
                        request()->is('tender*') ||
                        request()->is('agencies/*') ||
                        request()->is('vendors*') ||
                        request()->is('blacklists*') ||
                        request()->is('news*') ||
                        request()->is('transactions*');
                @endphp
                <li class="nav-item">
                    <a class="sidebar-link {{ $isTenderMenuActive ? 'active' : 'collapsed' }}" data-bs-toggle="collapse"
                        data-bs-target="#menuTender" aria-expanded="{{ $isTenderMenuActive ? 'true' : 'false' }}"
                        style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                            <line x1="9" y1="9" x2="10" y2="9" />
                            <line x1="9" y1="13" x2="15" y2="13" />
                            <line x1="9" y1="17" x2="15" y2="17" />
                        </svg>
                        <span class="nav-text">Pengurusan Tender</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-arrow" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                    <div class="collapse {{ $isTenderMenuActive ? 'show' : '' }}" id="menuTender">
                        <ul class="sidebar-submenu">
                            @if ($user->can('Tender:execute'))
                                <!-- new permission -->
                                <li>
                                    <a class="submenu-item" href="{{ route('ciptaTender') }}">
                                        <div class="submenu-icon"
                                            style="{{ request()->routeIs('ciptaTender') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                        </div>
                                        <span class="{{ request()->routeIs('ciptaTender') ? 'text-white' : '' }}">Cipta
                                            Tender/Sebut Harga</span>
                                    </a>
                                </li>
                            @endif

                            @if (App\Tender::canList())
                                @if (Auth::user()->ability(['Admin', 'Registration Assesor', 'Front Desk'], []))
                                    <li><a class="submenu-item" href="{{ asset('tender') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->is('tender*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div>
                                            <span class="{{ request()->is('tender*') ? 'text-white' : '' }}">Senarai
                                                Tender</span>
                                        </a></li>
                                @else
                                    <li>
                                        <a class="submenu-item" href="{{ asset('agency/' . Auth::user()->organization_unit_id) }}">
                                            <div class="submenu-icon" style="{{ request()->is('agency/*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}"></div>
                                            <span class="{{ request()->is('agency/*') ? 'text-white' : '' }}">Senarai Tender</span>
                                        </a>
                                    </li>
                                @endif
                            @endif
                            @if (App\Vendor::canList())
                                <li><a class="submenu-item" href="{{ asset('vendors') }}">
                                        <div class="submenu-icon"
                                            style="{{ request()->is('vendors*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                        </div>
                                        <span class="{{ request()->is('vendors*') ? 'text-white' : '' }}">Senarai
                                            Syarikat</span>
                                    </a></li>
                            @endif
                            @if (App\VendorBlacklist::canList())
                                <li><a class="submenu-item" href="{{ asset('blacklists') }}">
                                        <div class="submenu-icon"
                                            style="{{ request()->is('blacklists*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                        </div>
                                        <span class="{{ request()->is('blacklists*') ? 'text-white' : '' }}">Senarai
                                            Hitam</span>
                                    </a></li>
                            @endif
                            @if (App\News::canList())
                                <li><a class="submenu-item" href="{{ asset('news') }}">
                                        <div class="submenu-icon"
                                            style="{{ request()->is('news*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                        </div>
                                        <span class="{{ request()->is('news*') ? 'text-white' : '' }}">Senarai
                                            Berita</span>
                                    </a></li>
                            @endif
                            @if (App\Transaction::canList())
                                <li><a class="submenu-item" href="{{ asset('transactions') }}">
                                        <div class="submenu-icon"
                                            style="{{ request()->is('transactions*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                        </div>
                                        <span class="{{ request()->is('transactions*') ? 'text-white' : '' }}">Senarai
                                            Transaksi</span>
                                    </a></li>
                            @endif
                        </ul>
                    </div>
                </li>

                <!-- 2. PERMINTAAN KEMASKINI -->
                @if (App\CodeRequest::canList())
                    @php
                        $isRequestMenuActive = request()->is('requests*') || request()->routeIs('requests*');
                    @endphp
                    <li class="nav-item">
                        <a class="sidebar-link {{ $isRequestMenuActive ? 'active' : 'collapsed' }}"
                            data-bs-toggle="collapse" data-bs-target="#menuRequest"
                            aria-expanded="{{ $isRequestMenuActive ? 'true' : 'false' }}" style="cursor: pointer;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                            </svg>
                            <span class="nav-text">Permintaan Kemaskini</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="nav-arrow" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                        <div class="collapse {{ $isRequestMenuActive ? 'show' : '' }}" id="menuRequest">
                            <ul class="sidebar-submenu">
                                <li><a class="submenu-item {{ request()->is('requests*') || request()->routeIs('requests*') ? 'active' : '' }}"
                                        href="{{ asset('requests') }}">
                                        <div class="submenu-icon"
                                            style="{{ request()->is('requests*') || request()->routeIs('requests*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                        </div><span
                                            class="{{ request()->is('requests*') || request()->routeIs('requests*') ? 'text-white' : '' }}">Permintaan
                                            Kemaskini</span>
                                    </a></li>
                            </ul>
                        </div>
                    </li>
                @endif

                <!-- 3. PENGURUSAN SISTEM -->
                @if (Auth::user()->ability(['Admin', 'Agency Admin'], []))
                    @php
                        $isSystemMenuActive =
                            request()->is('users*') ||
                            request()->is('agencies*') ||
                            request()->is('organizationtypes*') ||
                            request()->is('codes*') ||
                            request()->is('helps*') ||
                            request()->is('helpcategories*') ||
                            request()->is('gateways*') ||
                            request()->is('banners*') ||
                            request()->is('version-histories*') ||
                            request()->is('reject-template*') ||
                            request()->is('circulars*');
                    @endphp
                    <li class="nav-item">
                        <a class="sidebar-link {{ $isSystemMenuActive ? 'active' : 'collapsed' }}"
                            data-bs-toggle="collapse" data-bs-target="#menuSystem"
                            aria-expanded="{{ $isSystemMenuActive ? 'true' : 'false' }}" style="cursor: pointer;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="3" />
                                <path
                                    d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                            </svg>
                            <span class="nav-text">Pengurusan Sistem</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="nav-arrow" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                        <div class="collapse {{ $isSystemMenuActive ? 'show' : '' }}" id="menuSystem">
                            <ul class="sidebar-submenu">
                                @if (App\User::canList())
                                    <li><a class="submenu-item" href="{{ asset('users') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->is('users') || request()->is('users/create') || request()->is('users/*/edit') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->is('users') || request()->is('users/create') || request()->is('users/*/edit') ? 'text-white' : '' }}">Senarai
                                                Pengguna</span>
                                        </a></li>
                                @endif
                                @if (Auth::user()->canApprove())
                                    <li><a class="submenu-item" href="{{ asset('users/pending-approval') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->is('users/pending-approval') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->is('users/pending-approval') ? 'text-white' : '' }}">Permohonan
                                                Pengguna</span>
                                        </a></li>
                                @endif
                                @if (App\OrganizationUnit::canList())
                                    <li><a class="submenu-item" href="{{ asset('agencies') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->is('agencies') || request()->is('agencies/create') || request()->is('agencies/*/edit') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->is('agencies') || request()->is('agencies/create') || request()->is('agencies/*/edit') ? 'text-white' : '' }}">Senarai
                                                Agensi</span>
                                        </a></li>
                                @endif
                                @if (Auth::user()->hasRole('Admin'))
                                    <li><a class="submenu-item" href="{{ asset('organizationtypes') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->is('organizationtypes*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->is('organizationtypes*') ? 'text-white' : '' }}">Kategori
                                                Agensi</span>
                                        </a></li>
                                    <li><a class="submenu-item" href="{{ asset('codes') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->is('codes*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->is('codes*') ? 'text-white' : '' }}">Senarai Kod
                                                Bidang</span>
                                        </a></li>
                                    <li><a class="submenu-item" href="{{ asset('helps') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->is('helps*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->is('helps*') ? 'text-white' : '' }}">Soalan
                                                Bantuan</span>
                                        </a></li>
                                    <li><a class="submenu-item" href="{{ asset('helpcategories') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->is('helpcategories*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->is('helpcategories*') ? 'text-white' : '' }}">Kategori
                                                Bantuan</span>
                                        </a></li>
                                    <li><a class="submenu-item" href="{{ asset('gateways') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->is('gateways*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->is('gateways*') ? 'text-white' : '' }}">Tetapan
                                                Pembayaran</span>
                                        </a></li>
                                    <li><a class="submenu-item" href="{{ asset('banners') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->is('banners*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->is('banners*') ? 'text-white' : '' }}">Senarai
                                                Banner</span>
                                        </a></li>
                                @endif
                                @if (Auth::user()->can('System:histories'))
                                    <li><a class="submenu-item" href="{{ asset('version-histories') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->is('version-histories*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->is('version-histories*') ? 'text-white' : '' }}">Sejarah
                                                Sistem</span>
                                        </a></li>
                                @endif
                                @if (App\Models\RejectTemplate::canList())
                                    <li><a class="submenu-item" href="{{ asset('reject-template') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->is('reject-template*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->is('reject-template*') ? 'text-white' : '' }}">Templat
                                                Penolakan</span>
                                        </a></li>
                                @endif
                                @if (App\Models\Circular::canList())
                                    <li><a class="submenu-item" href="{{ asset('circulars') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->is('circulars*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->is('circulars*') ? 'text-white' : '' }}">Senarai
                                                Pekeliling</span>
                                        </a></li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                <!-- 4. PENGURUSAN AKSES -->
                @if (Auth::user()->hasRole('Admin'))
                    @php
                        $isAccessMenuActive =
                            request()->is('roles*') ||
                            request()->is('permissions*');
                    @endphp
                    <li class="nav-item">
                        <a class="sidebar-link {{ $isAccessMenuActive ? 'active' : 'collapsed' }}"
                            data-bs-toggle="collapse" data-bs-target="#menuAccess"
                            aria-expanded="{{ $isAccessMenuActive ? 'true' : 'false' }}" style="cursor: pointer;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                            <span class="nav-text">Pengurusan Akses</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="nav-arrow" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                        <div class="collapse {{ $isAccessMenuActive ? 'show' : '' }}" id="menuAccess">
                            <ul class="sidebar-submenu">
                                @if (App\Role::canList())
                                    <li><a class="submenu-item" href="{{ asset('roles') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->is('roles*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->is('roles*') ? 'text-white' : '' }}">Tetapan Peranan</span>
                                        </a></li>
                                @endif
                                @if (App\Permission::canList())
                                    <li><a class="submenu-item" href="{{ asset('permissions') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->is('permissions*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->is('permissions*') ? 'text-white' : '' }}">Tetapan Kebenaran</span>
                                        </a></li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                <!-- 5. PEMULANGAN SEMULA -->
                @if (Auth::user()->ability(['Admin', 'Refund Admin'], ['Refund:list']))
                    @php
                        $isRefundMenuActive = request()->is('refunds*');
                    @endphp
                    <li class="nav-item">
                        <a class="sidebar-link {{ $isRefundMenuActive ? 'active' : 'collapsed' }}"
                            data-bs-toggle="collapse" data-bs-target="#menuRefund"
                            aria-expanded="{{ $isRefundMenuActive ? 'true' : 'false' }}" style="cursor: pointer;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="23 4 23 10 17 10" />
                                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10" />
                            </svg>
                            <span class="nav-text">Pengurusan Pemulangan Semula</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="nav-arrow" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                        <div class="collapse {{ $isRefundMenuActive ? 'show' : '' }}" id="menuRefund">
                            <ul class="sidebar-submenu">
                                @if (App\Models\Refund::canList())
                                    <li><a class="submenu-item" href="{{ route('refunds.request.index') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->routeIs('refunds.request.*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->routeIs('refunds.request.*') ? 'text-white' : '' }}">Permohonan Pemulangan Semula</span>
                                        </a></li>
                                @endif
                                @if (App\Models\Refund::isRoleBKP())
                                    <li><a class="submenu-item" href="{{ route('refunds.complaint.index') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->routeIs('refunds.complaint.*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->routeIs('refunds.complaint.*') ? 'text-white' : '' }}">Aduan Permohonan Semula</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                <!-- 6. API -->
                @if (Auth::user()->ability(['Admin'], ['Api:canList']))
                    @php $isApiMenuActive = request()->routeIs('apitoken.*'); @endphp
                    <li class="nav-item">
                        <a class="sidebar-link {{ $isApiMenuActive ? 'active' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#menuApi"
                            aria-expanded="{{ $isApiMenuActive ? 'true' : 'false' }}" style="cursor: pointer;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="16 18 22 12 16 6" />
                                <polyline points="8 6 2 12 8 18" />
                            </svg>
                            <span class="nav-text">Pengurusan API</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="nav-arrow" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                        <div class="collapse {{ $isApiMenuActive ? 'show' : '' }}" id="menuApi">
                            <ul class="sidebar-submenu">
                                @if (App\Models\ApiToken::canList())
                                    <li><a class="submenu-item" href="{{ route('apitoken.index') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->routeIs('apitoken.*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->routeIs('apitoken.*') ? 'text-white' : '' }}">Senarai API Token</span>
                                        </a></li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                <!-- 7. PENGURUSAN CHATBOT -->
                @if (Auth::user()->ability(['Admin'], ['chatbot-manager:canList']))
                    @php $isChatbotMenuActive = request()->routeIs('chatbot-manager.*'); @endphp
                    <li class="nav-item">
                        <a class="sidebar-link {{ $isChatbotMenuActive ? 'active' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#menuChatbot"
                            aria-expanded="{{ $isChatbotMenuActive ? 'true' : 'false' }}" style="cursor: pointer;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                            </svg>
                            <span class="nav-text">Pengurusan ChatBot</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="nav-arrow" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                        <div class="collapse {{ $isChatbotMenuActive ? 'show' : '' }}" id="menuChatbot">
                            <ul class="sidebar-submenu">
                                @if (App\Models\FaqCategory::canList())
                                    <li><a class="submenu-item" href="{{ route('chatbot-manager.category.index') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->routeIs('chatbot-manager.category.*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->routeIs('chatbot-manager.category.*') ? 'text-white' : '' }}">Senarai Kategori</span>
                                        </a></li>
                                    <li><a class="submenu-item" href="{{ route('chatbot-manager.question.index') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->routeIs('chatbot-manager.question.*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->routeIs('chatbot-manager.question.*') ? 'text-white' : '' }}">Senarai Soalan</span>
                                        </a></li>
                                    <li><a class="submenu-item" href="{{ route('chatbot-manager.chatlog.index') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->routeIs('chatbot-manager.chatlog.*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->routeIs('chatbot-manager.chatlog.*') ? 'text-white' : '' }}">Senarai Rekod Chat</span>
                                        </a></li>
                                    <li><a class="submenu-item"
                                            href="{{ route('chatbot-manager.newquestion.index') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->routeIs('chatbot-manager.newquestion.*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->routeIs('chatbot-manager.newquestion.*') ? 'text-white' : '' }}">Pertanyaan Baru</span>
                                        </a></li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                <!-- 8. PENGURUSAN EMAIL SMTP -->
                @if (Auth::user()->ability(['Admin'], ['chatbot-manager:canList']))
                    <li class="nav-item">
                        <a class="sidebar-link {{ request()->routeIs('mail-manager.*') ? 'active' : 'collapsed' }}"
                            data-bs-toggle="collapse" data-bs-target="#menuEmail"
                            aria-expanded="{{ request()->routeIs('mail-manager.*') ? 'true' : 'false' }}"
                            style="cursor: pointer;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                <polyline points="22,6 12,13 2,6" />
                            </svg>
                            <span class="nav-text">Tetapan Email SMTP</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="nav-arrow" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                        <div class="collapse {{ request()->routeIs('mail-manager.*') ? 'show' : '' }}"
                            id="menuEmail">
                            <ul class="sidebar-submenu">
                                @if (App\Models\FaqCategory::canList())
                                    <li><a class="submenu-item"
                                            href="{{ route('mail-manager.smtp-setting.index') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->routeIs('mail-manager.smtp-setting.*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->routeIs('mail-manager.smtp-setting.*') ? 'text-white' : '' }}">Senarai
                                                Email SMTP</span>
                                        </a></li>
                                    <li><a class="submenu-item" href="{{ route('mail-manager.mail-queue.index') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->routeIs('mail-manager.mail-queue.*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->routeIs('mail-manager.mail-queue.*') ? 'text-white' : '' }}">Rekod Email</span>
                                        </a></li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                <!-- 9. ADUAN -->
                @if (Auth::user()->ability(['Admin'], []))
                    @php $isAduanMenuActive = request()->is('aduan*'); @endphp
                    <li class="nav-item">
                        <a class="sidebar-link {{ $isAduanMenuActive ? 'active' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#menuAduan"
                            aria-expanded="{{ $isAduanMenuActive ? 'true' : 'false' }}" style="cursor: pointer;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                            <span class="nav-text">Aduan</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="nav-arrow" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                        <div class="collapse {{ $isAduanMenuActive ? 'show' : '' }}" id="menuAduan">
                            <ul class="sidebar-submenu">
                                @if (App\Models\FaqCategory::canList())
                                    <li><a class="submenu-item" href="{{ asset('aduan/list') }}">
                                            <div class="submenu-icon"
                                                style="{{ request()->is('aduan*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                            </div><span
                                                class="{{ request()->is('aduan*') ? 'text-white' : '' }}">Senarai Aduan</span>
                                        </a></li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                <!-- 10. DASHBOARD -->
                {{-- @if (Auth::user()->ability(['Admin', 'Admin Kewangan', 'Admin UPEN'], [])) --}}
                @if (Auth::user()->ability(['Admin', 'Admin Kewangan'], []))
                    <li class="nav-item">
                        <a class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#menuDashboard"
                            aria-expanded="false" style="cursor: pointer;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="9" />
                                <rect x="14" y="3" width="7" height="5" />
                                <rect x="14" y="12" width="7" height="9" />
                                <rect x="3" y="16" width="7" height="5" />
                            </svg>
                            <span class="nav-text">Dashboard</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="nav-arrow" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                        <div class="collapse" id="menuDashboard">
                            <ul class="sidebar-submenu">
                                @if (Auth::user()->hasRole('Admin') && Auth::user()->hasRole('Admin Kewangan'))
                                    <li>
                                        <a class="submenu-item" href="{{ asset('dashboard/hq') }}">
                                            <div class="submenu-icon"></div>
                                            <span>Dashboard</span>
                                        </a>
                                    </li>
                                @else
                                    {{-- <li>
								        <a class="submenu-item" href="{{ route('dashboard', ['id' => Auth::user()->organization_unit_id]) }}">
                                            <div class="submenu-icon"></div>
                                            <span>Dashboard</span>
                                        </a>
                                    </li> --}}
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                <!-- 11. LAPORAN -->
                @if (Auth::user()->can('Report:view'))
                    <li class="nav-item">
                        <a class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#menuReport"
                            aria-expanded="false" style="cursor: pointer;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                                <line x1="16" y1="13" x2="8" y2="13" />
                                <line x1="16" y1="17" x2="8" y2="17" />
                                <polyline points="10 9 9 9 8 9" />
                            </svg>
                            <span class="nav-text">Laporan</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="nav-arrow" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                        <div class="collapse" id="menuReport">
                            <ul class="sidebar-submenu">
                                @if (Auth::user()->can('Report:view:revenue_yearly'))
                                    <li><a class="submenu-item" href="{{ asset('reports/revenue') }}">
                                            <div class="submenu-icon"></div><span>Hasil Transaksi Tahunan</span>
                                        </a></li>
                                @endif
                                @if (Auth::user()->can('Report:view:agency_active'))
                                    <li><a class="submenu-item" href="{{ asset('reports/agency/active') }}">
                                            <div class="submenu-icon"></div><span>10 Agensi Aktif</span>
                                        </a></li>
                                @endif
                                @if (Auth::user()->can('Report:view:agency_transaction'))
                                    <li><a class="submenu-item" href="{{ asset('reports/agency/all') }}">
                                            <div class="submenu-icon"></div><span>Transaksi Semua Agensi</span>
                                        </a></li>
                                @endif
                                @if (Auth::user()->can('reports/agency/type'))
                                    <li><a class="submenu-item" href="{{ asset('reports/agency/type') }}">
                                            <div class="submenu-icon"></div><span>Transaksi Mengikut Kategori
                                                Agensi</span>
                                        </a></li>
                                @endif
                                @if (Auth::user()->can('Report:view:agency_tender') ||
                                        Auth::user()->can('Report:view:agency_tender:organization_unit_id'))
                                    <li><a class="submenu-item" href="{{ asset('reports/agency/transaction') }}">
                                            <div class="submenu-icon"></div><span>Transaksi Agensi Mengikut
                                                Tender</span>
                                        </a></li>
                                @endif
                                @if (Auth::user()->can('Report:view:agency_daily') || Auth::user()->can('Report:view:agency_daily:organization_unit_id'))
                                    <li><a class="submenu-item" href="{{ asset('reports/agency/daily') }}">
                                            <div class="submenu-icon"></div><span>Transaksi Harian Agensi</span>
                                        </a></li>
                                @endif
                                @if (Auth::user()->can('Report:view:gateway_daily') ||
                                        Auth::user()->can('Report:view:gateway_daily:organization_unit_id'))
                                    <li><a class="submenu-item" href="{{ asset('reports/gateway/daily') }}">
                                            <div class="submenu-icon"></div><span>Transaksi Harian Gateway</span>
                                        </a></li>
                                @endif
                                @if (Auth::user()->can('Report:view:vendor_status'))
                                    <li><a class="submenu-item" href="{{ asset('reports/vendor/status') }}">
                                            <div class="submenu-icon"></div><span>Syarikat Mengikut Status</span>
                                        </a></li>
                                @endif
                                @if (Auth::user()->can('Report:view:vendor_code'))
                                    <li><a class="submenu-item" href="{{ asset('reports/vendor/codes') }}">
                                            <div class="submenu-icon"></div><span>Syarikat Mengikut Kod Bidang</span>
                                        </a></li>
                                @endif
                                @if (Auth::user()->can('Report:view:vendor_district'))
                                    <li><a class="submenu-item" href="{{ asset('reports/vendor/district') }}">
                                            <div class="submenu-icon"></div><span>Syarikat Mengikut Daerah</span>
                                        </a></li>
                                @endif
                                @if (Auth::user()->can('Report:view:user_agency') || Auth::user()->can('Report:view:user_agency:organization_unit_id'))
                                    <li><a class="submenu-item" href="{{ asset('reports/user/agency') }}">
                                            <div class="submenu-icon"></div><span>Senarai Pengguna Agensi</span>
                                        </a></li>
                                @endif
                                @if (Auth::user()->can('Report:view:user_agency') || Auth::user()->can('Report:view:user_active:organization_unit_id'))
                                    <li><a class="submenu-item" href="{{ asset('reports/user/active') }}">
                                            <div class="submenu-icon"></div><span>Senarai Status Pengguna Mengikut
                                                Agensi</span>
                                        </a></li>
                                @endif
                                @if (Auth::user()->can('Report:view:user_activity'))
                                    <li><a class="submenu-item" href="{{ asset('reports/user/activity') }}">
                                            <div class="submenu-icon"></div><span>Laporan Aktiviti Staf</span>
                                        </a></li>
                                @endif
                                @if (Auth::user()->can('Report:view:user_login'))
                                    <li><a class="submenu-item" href="{{ asset('reports/user/login') }}">
                                            <div class="submenu-icon"></div><span>Laporan Login Sebagai</span>
                                        </a></li>
                                @endif
                                @if (Auth::user()->can('Report:view:vendor_registration_list'))
                                    <li><a class="submenu-item"
                                            href="{{ asset('reports/vendor/registration-list') }}">
                                            <div class="submenu-icon"></div><span>Laporan Pendaftaran Syarikat</span>
                                        </a></li>
                                @endif
                                @if (Auth::user()->can('Report:view:code_request'))
                                    <li><a class="submenu-item" href="{{ asset('reports/vendor/request') }}">
                                            <div class="submenu-icon"></div><span>Laporan Permohonan Kemaskini Maklumat
                                                Syarikat</span>
                                        </a></li>
                                @endif
                                @if (Auth::user()->can('Report:view:vendor_registration'))
                                    <li><a class="submenu-item" href="{{ asset('reports/vendor/registration') }}">
                                            <div class="submenu-icon"></div><span>Laporan Pendaftaran Pengguna
                                                Sistem</span>
                                        </a></li>
                                @endif
                                @if (Auth::user()->can('Report:view:staff_activity'))
                                    <li><a class="submenu-item" href="{{ asset('reports/staff/activity') }}">
                                            <div class="submenu-icon"></div><span>Laporan Aktiviti Pengguna
                                                Sistem</span>
                                        </a></li>
                                @endif
                                @if (Auth::user()->can('Report:view:code_district'))
                                    <li><a class="submenu-item" href="{{ asset('reports/code/district') }}">
                                            <div class="submenu-icon"></div><span>Laporan Jumlah Berkaitan Kod
                                                Bidang</span>
                                        </a></li>
                                @endif
                                @if (Auth::user()->can('Report:view:vendor_transaction'))
                                    <li><a class="submenu-item" href="{{ asset('reports/vendor/transaction') }}">
                                            <div class="submenu-icon"></div><span>Laporan Transaksi</span>
                                        </a></li>
                                @endif
                                @if (Auth::user()->can('Report:view:transaction_hasil'))
                                    <li><a class="submenu-item" href="{{ asset('reports/transaction/hasil') }}">
                                            <div class="submenu-icon"></div><span>Laporan Transaksi Mengikut Kod Akaun
                                                Hasil</span>
                                        </a></li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                <!-- ============================================= -->
                <!-- NEW SECTION: MODUL 3.0 (ADMIN) -->
                <!-- ============================================= -->

                <li class="nav-section-header my-3">Modul 3.0</li>

                <!-- Menu: Pelantikan Jawatankuasa -->
                <li class="nav-item">
                    <a class="sidebar-link {{ request()->is('pelantikan-jawatankuasa*') ? 'active' : '' }}"
                        href="{{ route('pelantikanJawatankuasa') }}" style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span class="nav-text">Pelantikan Jawatankuasa</span>
                    </a>
                </li>

                <!-- Menu : Jawatankuasa Spesifikasi /  Pengurusan -->
                <li class="nav-item">
                    <a class="sidebar-link collapsed" data-bs-toggle="collapse"
                        data-bs-target="#menuJawatankusaSpesifikasi" aria-expanded="false" style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span class="nav-text">Jawatankuasa Spesifikasi / Pengurusan </span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-arrow" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                    <div class="collapse" id="menuJawatankusaSpesifikasi">
                        <ul class="sidebar-submenu">
                            <li>
                                <a class="submenu-item" href="{{ route('senaraiSemak') }}">
                                    <div class="submenu-icon"></div><span>Senarai Semak</span>
                                </a>
                            </li>
                            <!-- <li><a class="submenu-item" href="{{ route('senaraiTeknikal') }}">
                                    <div class="submenu-icon"></div><span>Senarai Semak Teknikal</span>
                                </a></li> -->
                            <!-- <li><a class="submenu-item" href="{{ route('senaraiKewangan') }}">
                                    <div class="submenu-icon"></div><span>Senarai Semak Kewangan</span>
                                </a></li> -->
                        </ul>
                    </div>
                </li>

                <!-- Menu : Syarikat / Pembekal -->
                <li class="nav-item">
                    <a class="sidebar-link collapsed" data-bs-toggle="collapse"
                        data-bs-target="#menuSyarikatPembekal" aria-expanded="false" style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span class="nav-text">Syarikat / Pembekal</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-arrow" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                    <div class="collapse" id="menuSyarikatPembekal">
                        <ul class="sidebar-submenu">
                            <li><a class="submenu-item" href="{{ route('lawatanTapak') }}">
                                    <div class="submenu-icon"></div><span>Lawatan Tapak</span>
                                </a></li>
                            <li><a class="submenu-item" href="">
                                    <div class="submenu-icon"></div><span>Coming soon</span>
                                </a></li>
                        </ul>
                    </div>
                </li>

                <!-- Menu : Jawatankuasa Pembuka -->
                <li class="nav-item">
                    <a class="sidebar-link {{ request()->is('jawatankuasa-pembuka*') ? 'active' : '' }}"
                        href="{{ route('jawatankuasaPembuka') }}" style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span class="nav-text">Jawatankuasa Pembuka</span>
                    </a>
                </li>

                <!-- Menu : Penilaian Teknikal & Kewangan -->
                <li class="nav-item">
                    <a class="sidebar-link collapsed" data-bs-toggle="collapse"
                        data-bs-target="#menuPenilaianTeknikalKewangan" aria-expanded="false"
                        style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span class="nav-text">Penilaian Teknikal & Kewangan</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-arrow" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                    <div class="collapse" id="menuPenilaianTeknikalKewangan">
                        <ul class="sidebar-submenu">
                            <li>
                                <a class="submenu-item" href="{{ route('penilaianTeknikalKerja') }}">
                                    <div class="submenu-icon"></div><span>Penilaian Teknikal</span>
                                </a>
                            </li>
                            <li>
                                <a class="submenu-item" href="{{ route('penilaianKewangan') }}">
                                    <div class="submenu-icon"></div><span>Penilaian Kewangan</span>
                                </a>
                            </li>
                            <li>
                                {{-- MAIN SUBMENU --}}
                                <a class="submenu-item d-flex justify-content-between align-items-center"
                                    data-bs-toggle="collapse" href="#menuKewanganKerja" role="button"
                                    aria-expanded="false" aria-controls="menuKewanganKerja">
                                    <span>
                                        <div class="submenu-icon"></div>
                                        Penilaian Kewangan Kerja
                                    </span>
                                    <span>▾</span>
                                </a>

                                <div class="collapse" id="menuKewanganKerja">
                                    <ul class="sidebar-submenu sub-submenu">

                                        <li><a class="submenu-item" href="{{ route('borang1') }}">Borang 1</a></li>
                                        <li><a class="submenu-item" href="{{ route('borang2') }}">Borang 2</a></li>

                                        {{-- BORANG 3 WITH CHILD --}}
                                        <li>
                                            <a class="submenu-item d-flex justify-content-between align-items-center"
                                                data-bs-toggle="collapse" href="#menuBorang3" role="button">
                                                <span>Borang 3</span>
                                                <span>▸</span>
                                            </a>

                                            <div class="collapse" id="menuBorang3">
                                                <ul class="sidebar-submenu sub-submenu-level-2">
                                                    <li><a class="submenu-item" href="{{ route('borang3') }}">Borang
                                                            3</a></li>
                                                    <li><a class="submenu-item"
                                                            href="{{ route('lembaran') }}">Lembaran Imbangan</a></li>
                                                    <li><a class="submenu-item"
                                                            href="{{ route('akaunBank') }}">Akaun Bank</a></li>
                                                    <li><a class="submenu-item" href="{{ route('bonSaham') }}">Bon
                                                            atau Saham</a></li>
                                                </ul>
                                            </div>
                                        </li>

                                        <li><a class="submenu-item" href="{{ route('borang4') }}">Borang 4</a></li>
                                        <li><a class="submenu-item" href="{{ route('borang5') }}">Borang 5</a></li>
                                        <li><a class="submenu-item" href="{{ route('borang6') }}">Borang 6</a></li>

                                        <li>
                                            <a class="submenu-item d-flex justify-content-between align-items-center"
                                                data-bs-toggle="collapse" href="#menuBorang7" role="button">
                                                <span>Borang 7</span>
                                                <span>▸</span>
                                            </a>
                                            <div class="collapse" id="menuBorang7">
                                                <ul class="sidebar-submenu sub-submenu-level-2">
                                                    <li><a class="submenu-item"
                                                            href="{{ route('serupa') }}">Serupa</a></li>
                                                    <li><a class="submenu-item"
                                                            href="{{ route('sebanding') }}">Sebanding</a></li>
                                                </ul>
                                            </div>
                                        </li>

                                        <li><a class="submenu-item" href="{{ route('borang8') }}">Borang 8</a></li>

                                        <li>
                                            <a class="submenu-item d-flex justify-content-between align-items-center"
                                                data-bs-toggle="collapse" href="#menuBorang9" role="button">
                                                <span>Borang 9</span>
                                                <span>▸</span>
                                            </a>
                                            <div class="collapse" id="menuBorang9">
                                                <ul class="sidebar-submenu sub-submenu-level-2">
                                                    <li><a class="submenu-item" href="{{ route('borang9') }}">Borang
                                                            9</a></li>
                                                    <li><a class="submenu-item"
                                                            href="{{ route('kerjaSerupa') }}">Kerja Serupa</a></li>
                                                    <li><a class="submenu-item"
                                                            href="{{ route('kerjaSebanding') }}">Kerja Sebanding</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>

                                        <li><a class="submenu-item" href="{{ route('borang10') }}">Borang 10</a>
                                        </li>
                                        <li><a class="submenu-item" href="{{ route('borang11') }}">Borang 11</a>
                                        </li>
                                        <li><a class="submenu-item" href="{{ route('borang12') }}">Borang 12</a>
                                        </li>
                                        <li><a class="submenu-item" href="{{ route('borang13') }}">Borang 13</a>
                                        </li>
                                        <li><a class="submenu-item" href="{{ route('borang14') }}">Borang 14</a>
                                        </li>
                                        <li><a class="submenu-item" href="{{ route('borang15') }}">Borang 15</a>
                                        </li>

                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Menu: Cut Off -->
                <li class="nav-item">
                    <a class="sidebar-link {{ request()->is('cut-off*') ? 'active' : '' }}"
                        href="{{ route('cutOff.index') }}" style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span class="nav-text">Cut Off</span>
                    </a>
                </li>

                <!-- Menu : E-Bidding -->
                <li class="nav-item">
                    <a class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#menuEBidding"
                        aria-expanded="false" style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span class="nav-text">E-Bidding</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-arrow" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                    <div class="collapse" id="menuEBidding">
                        <ul class="sidebar-submenu">
                            <li><a class="submenu-item" href="{{ route('keputusanMesyuarat') }}">
                                    <div class="submenu-icon"></div><span>Keputusan Mesyuarat</span>
                                </a></li>
                            <li><a class="submenu-item" href="#">
                                    <div class="submenu-icon"></div><span>Comming Soon</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Menu: Pembelian Terus -->
                <li class="nav-item">
                    <a class="sidebar-link {{ request()->is('pembelian-terus*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" data-bs-target="#menuPembelianTerus"
                        aria-expanded="{{ request()->is('pembelian-terus*') ? 'true' : 'false' }}"
                        style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span class="nav-text">Pembelian Terus</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-arrow" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                    <div class="collapse {{ request()->is('pembelian-terus*') ? 'show' : '' }}"
                        id="menuPembelianTerus">
                        <ul class="sidebar-submenu">
                            <li>
                                <a class="submenu-item {{ request()->routeIs('pembelianTerus.createProject') || request()->is('pembelian-terus/cipta-projek*') ? 'active' : '' }}"
                                    href="{{ route('pembelianTerus.createProject') }}" style="cursor: pointer;">
                                    <div class="submenu-icon"
                                        style="{{ request()->routeIs('pembelianTerus.createProject') || request()->is('pembelian-terus/cipta-projek*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                    </div><span
                                        class="{{ request()->routeIs('pembelianTerus.createProject') || request()->is('pembelian-terus/cipta-projek*') ? 'text-white' : '' }}">Cipta
                                        Projek</span>
                                </a>
                            </li>
                            <li>
                                <a class="submenu-item {{ request()->routeIs('pembelianTerus.quoteProject') || request()->is('pembelian-terus/sebut-harga*') ? 'active' : '' }}"
                                    href="{{ route('pembelianTerus.quoteProject') }}" style="cursor: pointer;">
                                    <div class="submenu-icon"
                                        style="{{ request()->routeIs('pembelianTerus.quoteProject') || request()->is('pembelian-terus/sebut-harga*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                    </div><span
                                        class="{{ request()->routeIs('pembelianTerus.quoteProject') || request()->is('pembelian-terus/sebut-harga*') ? 'text-white' : '' }}">Sebut
                                        Harga</span>
                                </a>
                            </li>
                            <li>
                                <a class="submenu-item {{ request()->routeIs('pembelianTerus.cutOffProject') || request()->routeIs('pembelianTerus.cutOffDetails') || request()->is('pembelian-terus/cut-off-projek*') || request()->is('pembelian-terus/cut-off-details*') ? 'active' : '' }}"
                                    href="{{ route('pembelianTerus.cutOffProject') }}" style="cursor: pointer;">
                                    <div class="submenu-icon"
                                        style="{{ request()->routeIs('pembelianTerus.cutOffProject') || request()->routeIs('pembelianTerus.cutOffDetails') || request()->is('pembelian-terus/cut-off-projek*') || request()->is('pembelian-terus/cut-off-details*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                    </div><span
                                        class="{{ request()->routeIs('pembelianTerus.cutOffProject') || request()->routeIs('pembelianTerus.cutOffDetails') || request()->is('pembelian-terus/cut-off-projek*') || request()->is('pembelian-terus/cut-off-details*') ? 'text-white' : '' }}">
                                        Cut Off Projek
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="submenu-item {{ request()->routeIs('pembelianTerus.pemilihanSyarikat') || request()->routeIs('pembelianTerus.pemilihanSyarikatDetails') || request()->is('pembelian-terus/pemilihan-syarikat*') ? 'active' : '' }}"
                                    href="{{ route('pembelianTerus.pemilihanSyarikat') }}" style="cursor: pointer;">
                                    <div class="submenu-icon"
                                        style="{{ request()->routeIs('pembelianTerus.pemilihanSyarikat') || request()->routeIs('pembelianTerus.pemilihanSyarikatDetails') || request()->is('pembelian-terus/pemilihan-syarikat*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                    </div><span
                                        class="{{ request()->routeIs('pembelianTerus.pemilihanSyarikat') || request()->routeIs('pembelianTerus.pemilihanSyarikatDetails') || request()->is('pembelian-terus/pemilihan-syarikat*') ? 'text-white' : '' }}">Pemilihan
                                        Syarikat</span>
                                </a>
                            </li>
                            <li>
                                <a class="submenu-item {{ request()->routeIs('pembelianTerus.keputusanSyarikat') || request()->routeIs('pembelianTerus.keputusanSyarikatDetails') || request()->is('pembelian-terus/keputusan-syarikat*') ? 'active' : '' }}"
                                    href="{{ route('pembelianTerus.keputusanSyarikat') }}" style="cursor: pointer;">
                                    <div class="submenu-icon"
                                        style="{{ request()->routeIs('pembelianTerus.keputusanSyarikat') || request()->routeIs('pembelianTerus.keputusanSyarikatDetails') || request()->is('pembelian-terus/keputusan-syarikat*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                    </div><span
                                        class="{{ request()->routeIs('pembelianTerus.keputusanSyarikat') || request()->routeIs('pembelianTerus.keputusanSyarikatDetails') || request()->is('pembelian-terus/keputusan-syarikat*') ? 'text-white' : '' }}">Keputusan
                                        Syarikat</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </aside>
@else
    <!-- VENDOR SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="/" class="sidebar-brand">
                <div class="sidebar-logo-container">
                    <img src="{{ asset('images/Jata_Negeri_Selangor_2025.png') }}" alt="Logo"
                        class="sidebar-logo">
                </div>
                <div class="sidebar-brand-text">
                    <span class="brand-title">
                        <span class="brand-title-top">Perolehan</span>
                        <span class="brand-title-bottom">Selangor</span>
                    </span>
                    <span class="brand-subtitle">Sistem Perolehan Negeri</span>
                </div>
            </a>
        </div>
        <!-- Scrollable Area -->
        <div class="sidebar-scroll-area">
            <ul class="sidebar-nav">

                <!-- ============================================= -->
                <!-- NEW SECTION: MODUL 3.0 (VENDOR) -->
                <!-- ============================================= -->

                <!-- Menu: Pelantikan Jawatankuasa -->
                <li class="nav-item">
                    <a class="sidebar-link {{ request()->is('pelantikan-jawatankuasa*') ? 'active' : '' }}"
                        href="{{ route('pelantikanJawatankuasa') }}" style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span class="nav-text">Pelantikan Jawatankuasa</span>
                    </a>
                </li>

                <!-- Menu : Jawatankuasa Spesifikasi /  Pengurusan-->
                <li class="nav-item">
                    <a class="sidebar-link collapsed" data-bs-toggle="collapse"
                        data-bs-target="#menuJawatankusaSpesifikasi" aria-expanded="false" style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span class="nav-text">Jawatankuasa Spesifikasi / Pengurusan </span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-arrow" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                    <div class="collapse" id="menuJawatankusaSpesifikasi">
                        <ul class="sidebar-submenu">
                            <li>
                                <a class="submenu-item" href="{{ route('senaraiSemak') }}">
                                    <div class="submenu-icon"></div><span>Senarai Semak</span>
                                </a>
                            </li>
                            <!-- <li><a class="submenu-item" href="{{ route('senaraiTeknikal') }}">
                                    <div class="submenu-icon"></div><span>Senarai Semak Teknikal</span>
                                </a></li> -->
                            <!-- <li><a class="submenu-item" href="{{ route('senaraiKewangan') }}">
                                    <div class="submenu-icon"></div><span>Senarai Semak Kewangan</span>
                                </a></li> -->
                        </ul>
                    </div>
                </li>

                <!-- Menu : Syarikat / Pembekal -->
                <li class="nav-item">
                    <a class="sidebar-link collapsed" data-bs-toggle="collapse"
                        data-bs-target="#menuSyarikatPembekal" aria-expanded="false" style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span class="nav-text">Syarikat / Pembekal</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-arrow" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                    <div class="collapse" id="menuSyarikatPembekal">
                        <ul class="sidebar-submenu">
                            <li><a class="submenu-item" href="{{ route('syaratTender') }}">
                                    <div class="submenu-icon"></div><span>Syarat Tender</span>
                                </a></li>
                            <li><a class="submenu-item" href="{{ route('lawatanTapak') }}">
                                    <div class="submenu-icon"></div><span>Lawatan Tapak</span>
                                </a></li>
                            <li><a class="submenu-item" href="{{ route('kodBidang') }}">
                                    <div class="submenu-icon"></div><span>Kod - Kod Bidang</span>
                                </a></li>
                            <li><a class="submenu-item" href="">
                                    <div class="submenu-icon"></div><span>Coming soon</span>
                                </a></li>
                        </ul>
                    </div>
                </li>

                <!-- Menu : Jawatankuasa Pembuka -->
                <li class="nav-item">
                    <a class="sidebar-link {{ request()->is('jawatankuasa-pembuka*') ? 'active' : '' }}"
                        href="{{ route('jawatankuasaPembuka') }}" style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span class="nav-text">Jawatankuasa Pembuka</span>
                    </a>
                </li>

                <!-- Menu : Penilaian Teknikal & Kewangan -->
                <li class="nav-item">
                    <a class="sidebar-link collapsed" data-bs-toggle="collapse"
                        data-bs-target="#menuPenilaianTeknikalKewangan" aria-expanded="false"
                        style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span class="nav-text">Penilaian Teknikal & Kewangan</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-arrow" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                    <div class="collapse" id="menuPenilaianTeknikalKewangan">
                        <ul class="sidebar-submenu">
                            <li>
                                <a class="submenu-item" href="{{ route('penilaianTeknikalKerja') }}">
                                    <div class="submenu-icon"></div><span>Penilaian Teknikal</span>
                                </a>
                            </li>
                            <li>
                                <a class="submenu-item" href="{{ route('penilaianKewangan') }}">
                                    <div class="submenu-icon"></div><span>Penilaian Kewangan</span>
                                </a>
                            </li>
                            <li>
                                {{-- MAIN SUBMENU --}}
                                <a class="submenu-item d-flex justify-content-between align-items-center"
                                    data-bs-toggle="collapse" href="#menuKewanganKerja" role="button"
                                    aria-expanded="false" aria-controls="menuKewanganKerja">
                                    <span>
                                        <div class="submenu-icon"></div>
                                        Penilaian Kewangan Kerja
                                    </span>
                                    <span>▾</span>
                                </a>

                                <div class="collapse" id="menuKewanganKerja">
                                    <ul class="sidebar-submenu sub-submenu">

                                        <li><a class="submenu-item" href="{{ route('borang1') }}">Borang 1</a></li>
                                        <li><a class="submenu-item" href="{{ route('borang2') }}">Borang 2</a></li>

                                        {{-- BORANG 3 WITH CHILD --}}
                                        <li>
                                            <a class="submenu-item d-flex justify-content-between align-items-center"
                                                data-bs-toggle="collapse" href="#menuBorang3" role="button">
                                                <span>Borang 3</span>
                                                <span>▸</span>
                                            </a>

                                            <div class="collapse" id="menuBorang3">
                                                <ul class="sidebar-submenu sub-submenu-level-2">
                                                    <li><a class="submenu-item" href="{{ route('borang3') }}">Borang
                                                            3</a></li>
                                                    <li><a class="submenu-item"
                                                            href="{{ route('lembaran') }}">Lembaran Imbangan</a></li>
                                                    <li><a class="submenu-item"
                                                            href="{{ route('akaunBank') }}">Akaun Bank</a></li>
                                                    <li><a class="submenu-item" href="{{ route('bonSaham') }}">Bon
                                                            atau Saham</a></li>
                                                </ul>
                                            </div>
                                        </li>

                                        <li><a class="submenu-item" href="{{ route('borang4') }}">Borang 4</a></li>
                                        <li><a class="submenu-item" href="{{ route('borang5') }}">Borang 5</a></li>
                                        <li><a class="submenu-item" href="{{ route('borang6') }}">Borang 6</a></li>

                                        <li>
                                            <a class="submenu-item d-flex justify-content-between align-items-center"
                                                data-bs-toggle="collapse" href="#menuBorang7" role="button">
                                                <span>Borang 7</span>
                                                <span>▸</span>
                                            </a>
                                            <div class="collapse" id="menuBorang7">
                                                <ul class="sidebar-submenu sub-submenu-level-2">
                                                    <li><a class="submenu-item"
                                                            href="{{ route('serupa') }}">Serupa</a></li>
                                                    <li><a class="submenu-item"
                                                            href="{{ route('sebanding') }}">Sebanding</a></li>
                                                </ul>
                                            </div>
                                        </li>

                                        <li><a class="submenu-item" href="{{ route('borang8') }}">Borang 8</a></li>

                                        <li>
                                            <a class="submenu-item d-flex justify-content-between align-items-center"
                                                data-bs-toggle="collapse" href="#menuBorang9" role="button">
                                                <span>Borang 9</span>
                                                <span>▸</span>
                                            </a>
                                            <div class="collapse" id="menuBorang9">
                                                <ul class="sidebar-submenu sub-submenu-level-2">
                                                    <li><a class="submenu-item" href="{{ route('borang9') }}">Borang
                                                            9</a></li>
                                                    <li><a class="submenu-item"
                                                            href="{{ route('kerjaSerupa') }}">Kerja Serupa</a></li>
                                                    <li><a class="submenu-item"
                                                            href="{{ route('kerjaSebanding') }}">Kerja Sebanding</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>

                                        <li><a class="submenu-item" href="{{ route('borang10') }}">Borang 10</a>
                                        </li>
                                        <li><a class="submenu-item" href="{{ route('borang11') }}">Borang 11</a>
                                        </li>
                                        <li><a class="submenu-item" href="{{ route('borang12') }}">Borang 12</a>
                                        </li>
                                        <li><a class="submenu-item" href="{{ route('borang13') }}">Borang 13</a>
                                        </li>
                                        <li><a class="submenu-item" href="{{ route('borang14') }}">Borang 14</a>
                                        </li>
                                        <li><a class="submenu-item" href="{{ route('borang15') }}">Borang 15</a>
                                        </li>

                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Menu: Cut Off -->
                <li class="nav-item">
                    <a class="sidebar-link {{ request()->is('cut-off*') ? 'active' : '' }}"
                        href="{{ route('cutOff.index') }}" style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span class="nav-text">Cut Off</span>
                    </a>
                </li>

                <!-- Menu : E-Bidding -->
                <li class="nav-item">
                    <a class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#menuEBidding"
                        aria-expanded="false" style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span class="nav-text">E-Bidding</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-arrow" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                    <div class="collapse" id="menuEBidding">
                        <ul class="sidebar-submenu">
                            <li><a class="submenu-item" href="{{ route('keputusanMesyuarat') }}">
                                    <div class="submenu-icon"></div><span>Keputusan Mesyuarat</span>
                                </a></li>
                            <li><a class="submenu-item" href="#">
                                    <div class="submenu-icon"></div><span>Comming Soon</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Menu: Pembelian Terus -->
                <li class="nav-item">
                    <a class="sidebar-link {{ request()->is('pembelian-terus*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" data-bs-target="#menuPembelianTerus"
                        aria-expanded="{{ request()->is('pembelian-terus*') ? 'true' : 'false' }}"
                        style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span class="nav-text">Pembelian Terus</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-arrow" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                    <div class="collapse {{ request()->is('pembelian-terus*') ? 'show' : '' }}"
                        id="menuPembelianTerus">
                        <ul class="sidebar-submenu">
                            <li>
                                <a class="submenu-item {{ request()->routeIs('pembelianTerus.createProject') || request()->is('pembelian-terus/cipta-projek*') ? 'active' : '' }}"
                                    href="{{ route('pembelianTerus.createProject') }}" style="cursor: pointer;">
                                    <div class="submenu-icon"
                                        style="{{ request()->routeIs('pembelianTerus.createProject') || request()->is('pembelian-terus/cipta-projek*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                    </div><span
                                        class="{{ request()->routeIs('pembelianTerus.createProject') || request()->is('pembelian-terus/cipta-projek*') ? 'text-white' : '' }}">Cipta
                                        Projek</span>
                                </a>
                            </li>
                            <li>
                                <a class="submenu-item {{ request()->routeIs('pembelianTerus.quoteProject') || request()->is('pembelian-terus/sebut-harga*') ? 'active' : '' }}"
                                    href="{{ route('pembelianTerus.quoteProject') }}" style="cursor: pointer;">
                                    <div class="submenu-icon"
                                        style="{{ request()->routeIs('pembelianTerus.quoteProject') || request()->is('pembelian-terus/sebut-harga*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                    </div><span
                                        class="{{ request()->routeIs('pembelianTerus.quoteProject') || request()->is('pembelian-terus/sebut-harga*') ? 'text-white' : '' }}">Sebut
                                        Harga</span>
                                </a>
                            </li>
                            <li>
                                <a class="submenu-item {{ request()->routeIs('pembelianTerus.cutOffProject') || request()->routeIs('pembelianTerus.cutOffDetails') || request()->is('pembelian-terus/cut-off-projek*') || request()->is('pembelian-terus/cut-off-details*') ? 'active' : '' }}"
                                    href="{{ route('pembelianTerus.cutOffProject') }}" style="cursor: pointer;">
                                    <div class="submenu-icon"
                                        style="{{ request()->routeIs('pembelianTerus.cutOffProject') || request()->routeIs('pembelianTerus.cutOffDetails') || request()->is('pembelian-terus/cut-off-projek*') || request()->is('pembelian-terus/cut-off-details*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                    </div><span
                                        class="{{ request()->routeIs('pembelianTerus.cutOffProject') || request()->routeIs('pembelianTerus.cutOffDetails') || request()->is('pembelian-terus/cut-off-projek*') || request()->is('pembelian-terus/cut-off-details*') ? 'text-white' : '' }}">
                                        Cut Off Projek
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="submenu-item {{ request()->routeIs('pembelianTerus.pemilihanSyarikat') || request()->routeIs('pembelianTerus.pemilihanSyarikatDetails') || request()->is('pembelian-terus/pemilihan-syarikat*') ? 'active' : '' }}"
                                    href="{{ route('pembelianTerus.pemilihanSyarikat') }}" style="cursor: pointer;">
                                    <div class="submenu-icon"
                                        style="{{ request()->routeIs('pembelianTerus.pemilihanSyarikat') || request()->routeIs('pembelianTerus.pemilihanSyarikatDetails') || request()->is('pembelian-terus/pemilihan-syarikat*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                    </div><span
                                        class="{{ request()->routeIs('pembelianTerus.pemilihanSyarikat') || request()->routeIs('pembelianTerus.pemilihanSyarikatDetails') || request()->is('pembelian-terus/pemilihan-syarikat*') ? 'text-white' : '' }}">Pemilihan
                                        Syarikat</span>
                                </a>
                            </li>
                            <li>
                                <a class="submenu-item {{ request()->routeIs('pembelianTerus.keputusanSyarikat') || request()->routeIs('pembelianTerus.keputusanSyarikatDetails') || request()->is('pembelian-terus/keputusan-syarikat*') ? 'active' : '' }}"
                                    href="{{ route('pembelianTerus.keputusanSyarikat') }}" style="cursor: pointer;">
                                    <div class="submenu-icon"
                                        style="{{ request()->routeIs('pembelianTerus.keputusanSyarikat') || request()->routeIs('pembelianTerus.keputusanSyarikatDetails') || request()->is('pembelian-terus/keputusan-syarikat*') ? 'background-color: var(--sg-yellow); transform: scale(1.2); box-shadow: 0 0 5px var(--sg-yellow);' : '' }}">
                                    </div><span
                                        class="{{ request()->routeIs('pembelianTerus.keputusanSyarikat') || request()->routeIs('pembelianTerus.keputusanSyarikatDetails') || request()->is('pembelian-terus/keputusan-syarikat*') ? 'text-white' : '' }}">Keputusan
                                        Syarikat</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </aside>
@endif

<!-- Preserve sidebar scroll position across page loads -->
<script>
    (function() {
        var scrollArea = document.querySelector('.sidebar-scroll-area');
        if (!scrollArea) return;

        // Restore scroll position instantly on page load
        var savedPosition = sessionStorage.getItem('sidebarScrollPos');
        if (savedPosition) {
            scrollArea.scrollTop = parseInt(savedPosition);
        }

        // Save scroll position before page unloads
        window.addEventListener('beforeunload', function() {
            sessionStorage.setItem('sidebarScrollPos', scrollArea.scrollTop);
        });
    })();
</script>
