{{-- {{ App\Libraries\Asset::push('css', 'form') }} --}}

@push('styles')
    <style>
        .nav-pills-custom .nav-link.active {
            background-color: #fff1f2 !important;
            color: #c41e3a !important;
            border: 1px solid #fecaca;
            font-weight: 700;
        }

        .nav-pills-custom .nav-link {
            color: #64748b;
            font-weight: 500;
        }

        .nav-pills-custom .nav-link:hover {
            background-color: #f8fafc;
            color: #1e293b;
        }

        /* Icons */
        .nav-link svg {
            opacity: 0.5;
            transition: 0.2s;
        }

        .nav-link.active svg {
            opacity: 1;
            stroke: #c41e3a;
        }

        /* Scrollable Code List */
        .code-list-scroll {
            max-height: 300px;
            overflow-y: auto;
        }

        /* CIDB Gred & Bidang Pengkhususan */
        .cidb-grade {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #fff;
            overflow: hidden;
        }

        .cidb-grade + .cidb-grade {
            margin-top: 0.75rem;
        }

        .cidb-grade-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.9rem;
            background: #fff1f2;
            border-bottom: 1px solid #fecaca;
        }

        .cidb-grade-badge {
            flex-shrink: 0;
            min-width: 2.6rem;
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
            background: #c41e3a;
            color: #fff;
            font-weight: 700;
            font-size: 0.85rem;
            text-align: center;
        }

        .cidb-grade-title {
            flex-grow: 1;
            font-weight: 600;
            font-size: 0.85rem;
            color: #9f1239;
        }

        .cidb-grade-count {
            flex-shrink: 0;
            font-size: 0.75rem;
            color: #64748b;
            white-space: nowrap;
        }

        .cidb-grade-body {
            padding: 0.75rem 0.9rem;
            max-height: 460px;
            overflow-y: auto;
        }

        .cidb-category + .cidb-category {
            margin-top: 0.9rem;
        }

        .cidb-category-title {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 0.4rem;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            color: #475569;
        }

        .cidb-category-title .cidb-category-count {
            padding: 0 0.45rem;
            border-radius: 999px;
            background: #f1f5f9;
            color: #64748b;
            font-weight: 600;
        }

        .cidb-spec-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 0.35rem;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .cidb-spec-item {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            padding: 0.35rem 0.5rem;
            border: 1px solid #f1f5f9;
            border-radius: 6px;
            background: #f8fafc;
            font-size: 0.8rem;
            line-height: 1.35;
            color: #1e293b;
        }

        .cidb-spec-code {
            flex-shrink: 0;
            min-width: 2.9rem;
            padding: 0.05rem 0.35rem;
            border-radius: 4px;
            background: #e2e8f0;
            color: #334155;
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            font-size: 0.72rem;
            font-weight: 700;
            text-align: center;
        }
    </style>
@endpush

<div class="row g-4">

    <!-- LEFT NAVIGATION -->
    <div class="col-lg-3 col-xl-3">
        <div class="sticky-top" style="top: 130px; z-index: 10;">
            <div class="card border shadow-sm rounded-3 p-2">
                <ul class="nav nav-pills nav-pills-custom flex-column gap-1">
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center @if (!isset($active_prestasi_tab)) active @endif"
                            href="#vf-main" data-bs-toggle="pill">
                            Maklumat Syarikat
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center" href="#vf-officer"
                            data-bs-toggle="pill">
                            Maklumat Pegawai
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center" href="#vf-mof"
                            data-bs-toggle="pill">
                            MOF
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center" href="#vf-cidb"
                            data-bs-toggle="pill">
                            CIDB
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center" href="#vf-shareholders"
                            data-bs-toggle="pill">
                            Pemegang Saham
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center" href="#vf-directors"
                            data-bs-toggle="pill">
                            Pengarah
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                    </li>
                    @if (isset($vendor) && $vendor->approval_1_id > 0)
                        <li class="nav-item"><a class="nav-link d-flex justify-content-between align-items-center"
                                href="#vf-contacts" data-bs-toggle="pill">Kakitangan <svg
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg></a></li>
                        <li class="nav-item"><a class="nav-link d-flex justify-content-between align-items-center"
                                href="#vf-awards" data-bs-toggle="pill">Anugerah <svg
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg></a></li>
                        <li class="nav-item"><a class="nav-link d-flex justify-content-between align-items-center"
                                href="#vf-assets" data-bs-toggle="pill">Aset <svg xmlns="http://www.w3.org/2000/svg"
                                    width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg></a></li>
                        <li class="nav-item"><a class="nav-link d-flex justify-content-between align-items-center"
                                href="#vf-projects" data-bs-toggle="pill">Projek <svg
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg></a></li>
                        <li class="nav-item"><a class="nav-link d-flex justify-content-between align-items-center"
                                href="#vf-products" data-bs-toggle="pill">Produk <svg
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg></a></li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center" href="#vf-files"
                            data-bs-toggle="pill">
                            Fail
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center" href="#vf-subscriptions"
                            data-bs-toggle="pill">
                            Bayaran Pendaftaran
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center @if (isset($active_prestasi_tab)) active @endif"
                            href="#vf-prestasi-syarikat" data-bs-toggle="pill">
                            Rekod Prestasi
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- RIGHT CONTENT -->
    <div class="col-lg-9 col-xl-9">
        <div class="tab-content">

            <!-- 1. MAKLUMAT SYARIKAT -->
            <div class="tab-pane fade @if (!isset($active_prestasi_tab)) show active @endif" id="vf-main">
                <div class="card border shadow-sm rounded-3 overflow-hidden mb-4">
                    <div
                        class="card-header bg-light py-3 border-bottom d-flex align-items-center gap-2 fw-bold text-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                        Butiran Syarikat
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Alamat Emel</label>
                                <div class="fw-medium text-dark">{{ $vendor->user->email }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">No
                                    Pendaftaran</label>
                                <div class="fw-medium text-dark">{{ $vendor->registration }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Nama
                                    Syarikat</label>
                                <div class="fw-medium text-dark">{{ $vendor->name }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Alamat</label>
                                <div class="fw-medium text-dark">{!! nl2br($vendor->address) !!}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Daerah</label>
                                <div class="fw-medium text-dark">
                                    @if ($vendor->district_id)
                                        {{ App\Vendor::$districts[$vendor->district_id] }}
                                    @elseif(($vendor->state_id ?? 0) == 0 && ($vendor->district_id ?? 0) == 0)
                                        <span class="text-danger">Sila Kemaskini</span>
                                    @else
                                        Luar Negeri Selangor
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Negeri</label>
                                <div class="fw-medium text-dark">
                                    @if ($vendor->state_id && ($vendor->district_id ?? 0) == 0)
                                        {{ App\Vendor::$states[$vendor->state_id] }}
                                    @elseif(($vendor->state_id ?? 0) == 0 && ($vendor->district_id ?? 0) == 0)
                                        <span class="text-danger">Sila Kemaskini</span>
                                    @else
                                        Selangor
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">No. Telefon</label>
                                <div class="fw-medium text-dark">{{ $vendor->tel ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">No. Faks</label>
                                <div class="fw-medium text-dark">{{ $vendor->fax ?? '-' }}</div>
                            </div>
                        </div>

                        @if ($vendor->canCertificate() && Auth::user()->can('Vendor:certificate'))
                            <hr class="my-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <label class="small text-muted fw-bold text-uppercase d-block mb-1">Kod Pengesahan
                                        Sijil</label>
                                    <span
                                        class="badge bg-light text-dark border font-monospace">{{ $vendor->token }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ action('VendorsController@certificate', $vendor->id) }}"
                                        data-download-url="{{ route('vendors.certificate.download', $vendor->id) }}"
                                        class="btn btn-sm btn-outline-danger d-flex align-items-center gap-2 view-pdf-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                        Papar Sijil
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card border shadow-sm rounded-3 overflow-hidden mb-4">
                    <div
                        class="card-header bg-light py-3 border-bottom d-flex align-items-center gap-2 fw-bold text-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a4.5 4.5 0 0 0-4.5 4.5v9a4.5 4.5 0 0 0 4.5 4.5H17"></path>
                        </svg>
                        Maklumat Kewangan & Korporat
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Jenis
                                    Perniagaan</label>
                                <div class="fw-medium text-dark">{{ $vendor->organization_type }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Tarikh
                                    Penubuhan</label>
                                <div class="fw-medium text-dark">{{ $vendor->incorporation_date }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Modal
                                    Dibenarkan</label>
                                <div class="fw-medium text-dark">{{ $vendor->authorized_capital_currency }}
                                    {{ $vendor->authorized_capital }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Modal
                                    Berbayar</label>
                                <div class="fw-medium text-dark">{{ $vendor->paidup_capital_currency }}
                                    {{ $vendor->paidup_capital }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">No. Rujukan
                                    Cukai</label>
                                <div class="fw-medium text-dark">{{ $vendor->tax_no ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">No. Pendaftaran
                                    GST</label>
                                <div class="fw-medium text-dark">{{ $vendor->gst_no ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Laman Web</label>
                                <div class="fw-medium text-dark">
                                    @if ($vendor->website)
                                        <a href="{{ $vendor->website }}" target="_blank"
                                            class="text-danger text-decoration-none">{{ $vendor->website }}</a>
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. MAKLUMAT PEGAWAI -->
            <div class="tab-pane fade" id="vf-officer">
                <div class="card border shadow-sm rounded-3 overflow-hidden mb-4">
                    <div class="card-header bg-light py-3 border-bottom fw-bold text-secondary">Maklumat Pegawai Utama
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Nama
                                    Pegawai</label>
                                <div class="fw-medium text-dark">{{ $vendor->user->name }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Jawatan</label>
                                <div class="fw-medium text-dark">{{ $vendor->officer_designation }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">No. Telefon</label>
                                <div class="fw-medium text-dark">{{ $vendor->officer_tel }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. MOF -->
            <div class="tab-pane fade" id="vf-mof">
                <div class="card border shadow-sm rounded-3 overflow-hidden mb-4">
                    <div class="card-header bg-light py-3 border-bottom fw-bold text-secondary">Sijil Kementerian
                        Kewangan (MOF)</div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">No Rujukan</label>
                                <div class="fw-medium text-dark">{{ $vendor->mof_ref_no ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Tarikh
                                    Aktif</label>
                                <div class="fw-medium text-dark">
                                    @if ($vendor->mof_start_date && $vendor->mof_end_date)
                                        {{ Carbon\Carbon::parse($vendor->mof_start_date)->format('d M Y') }} -
                                        {{ Carbon\Carbon::parse($vendor->mof_end_date)->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Status
                                    Bumiputera</label>
                                <div>
                                    @if ($vendor->mof_bumi)
                                        <span
                                            class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                                                class="me-1">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg> Ya</span>
                                    @else
                                        <span
                                            class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger">Tidak</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <label class="small text-muted fw-bold text-uppercase d-block mb-2">Kod Bidang MOF
                            ({{ count($vendor->mofCodes) }})</label>
                        <div class="bg-light p-3 rounded border code-list-scroll">
                            @if (count($vendor->mofCodes) > 0)
                                <ul class="mb-0 ps-3">
                                    @foreach ($vendor->mofCodes->sortBy('code.code') as $code)
                                        <li class="mb-1">{!! $code->code->label2 !!}</li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-muted text-center fst-italic">Tiada Kod Bidang.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. CIDB -->
            <div class="tab-pane fade" id="vf-cidb">
                <div class="card border shadow-sm rounded-3 overflow-hidden mb-4">
                    <div class="card-header bg-light py-3 border-bottom d-flex justify-content-between align-items-center gap-3">
                        <div class="fw-bold text-secondary">Lembaga Pembangunan Industri Pembinaan (CIDB)</div>
                        {{-- Hidden until CIDB integration is ready
                        <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                            @include('components.vendor-cidb-meta', [
                                'vendor' => $vendor,
                                'trigger' => 'button',
                                'showIntegrate' => Auth::user() && Auth::user()->hasRole('Admin'),
                                'showComparison' => true,
                                'openOnLoad' => session('cidb_integrated') && (int) session('cidb_integrated_vendor_id') === (int) $vendor->id,
                                'scrollToComparison' => session('cidb_integrated') && (int) session('cidb_integrated_vendor_id') === (int) $vendor->id,
                            ])
                        </div>
                        --}}
                    </div>
                    <div class="card-body p-4">
                        @if (session('success') && session('cidb_integrated'))
                            <div class="alert alert-success border-0 shadow-sm mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">No Sijil
                                    CIDB</label>
                                <div class="fw-medium text-dark">{!! $vendor->cidb_ref_no ?? '-' !!}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Tarikh
                                    Aktif</label>
                                <div class="fw-medium text-dark">
                                    @if ($vendor->cidb_start_date && $vendor->cidb_end_date)
                                        {{ Carbon\Carbon::parse($vendor->cidb_start_date)->format('d M Y') }} -
                                        {{ Carbon\Carbon::parse($vendor->cidb_end_date)->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Status
                                    Bumiputera</label>
                                <div>
                                    @if ($vendor->cidb_bumi)
                                        <span
                                            class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                                                class="me-1">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg> Ya</span>
                                    @else
                                        <span
                                            class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger">Tidak</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        @php
                            // One query for grades + their specialisations (was 1 + 5 queries per grade).
                            $cidbGradeRows = $vendor->cidbGrades()->with(['code', 'children.code'])->orderBy('id')->get();
                            $cidbCategoryNames = [
                                'B'  => 'B · Bangunan',
                                'CE' => 'CE · Kejuruteraan Awam',
                                'ME' => 'ME · Mekanikal & Elektrikal',
                                'A'  => 'A',
                                'X'  => 'Lain-lain',
                            ];
                        @endphp

                        <label class="small text-muted fw-bold text-uppercase d-block mb-2">Gred & Bidang
                            Pengkhususan</label>

                        @forelse ($cidbGradeRows as $grade)
                            @php
                                $specs = $grade->children->pluck('code')->filter()->unique('id')
                                    ->sortBy('code', SORT_NATURAL);

                                // Group by the start of the code; anything unexpected goes to
                                // "Lain-lain" so it is still shown instead of silently dropped.
                                $specGroups = $specs->groupBy(function ($code) {
                                    $c = strtoupper((string) $code->code);
                                    if (str_starts_with($c, 'CE')) return 'CE';
                                    if (str_starts_with($c, 'B')) return 'B';
                                    if (str_starts_with($c, 'M') || str_starts_with($c, 'E')) return 'ME';
                                    if (str_starts_with($c, 'A')) return 'A';
                                    return 'X';
                                });
                            @endphp
                            <div class="cidb-grade">
                                <div class="cidb-grade-header">
                                    <span class="cidb-grade-badge">{{ optional($grade->code)->code ?? '-' }}</span>
                                    <span class="cidb-grade-title">{{ optional($grade->code)->name }}</span>
                                    <span class="cidb-grade-count">{{ $specs->count() }} bidang</span>
                                </div>
                                <div class="cidb-grade-body">
                                    @foreach (array_keys($cidbCategoryNames) as $category)
                                        @continue(!isset($specGroups[$category]))
                                        <div class="cidb-category">
                                            <div class="cidb-category-title">
                                                <span>{{ $cidbCategoryNames[$category] }}</span>
                                                <span class="cidb-category-count">{{ $specGroups[$category]->count() }}</span>
                                            </div>
                                            <ul class="cidb-spec-list">
                                                @foreach ($specGroups[$category] as $spec)
                                                    <li class="cidb-spec-item">
                                                        <span class="cidb-spec-code">{{ $spec->code }}</span>
                                                        <span>{{ $spec->description ?: $spec->name }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                    @if ($specs->isEmpty())
                                        <div class="text-muted small fst-italic">Tiada bidang pengkhususan.</div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="bg-light p-3 rounded border text-muted text-center fst-italic">Tiada maklumat
                                Gred.</div>
                        @endforelse

                    </div>
                </div>
            </div>

            @php $canViewSensitiveIdentity = Auth::check() && (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Vendor')); @endphp

            <!-- 5. SHAREHOLDERS -->
            <div class="tab-pane fade" id="vf-shareholders">
                <div class="card border shadow-sm rounded-3 overflow-hidden mb-4">
                    <div class="card-header bg-white py-3 border-bottom fw-bold">Senarai Pemegang Saham</div>
                    <div class="card-body p-0">
                        @if (count($vendor->shareholders) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th
                                                class="px-4 py-3 border-bottom-0 text-uppercase small text-muted font-weight-bold">
                                                Nama</th>
                                            <th
                                                class="px-4 py-3 border-bottom-0 text-uppercase small text-muted font-weight-bold">
                                                IC / Pasport</th>
                                            <th
                                                class="px-4 py-3 border-bottom-0 text-uppercase small text-muted font-weight-bold">
                                                Warganegara</th>
                                            <th
                                                class="px-4 py-3 border-bottom-0 text-uppercase small text-muted font-weight-bold">
                                                Taraf</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($vendor->shareholders as $sd)
                                            <tr>
                                                <td class="px-4">{{ $sd->name }}</td>
                                                <td class="px-4">
                                                    @if (!empty($sd->identity))
                                                        @if ($canViewSensitiveIdentity)
                                                            <span class="identity-mask font-monospace"
                                                                data-identity="{{ $sd->identity }}">**********</span>
                                                            <button type="button"
                                                                class="btn btn-sm p-0 mb-1 ms-2 toggle-identity text-decoration-none"
                                                                style="color: var(--bs-primary);" title="Tunjuk">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="16" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                    <path
                                                                        d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z">
                                                                    </path>
                                                                    <circle cx="12" cy="12" r="3">
                                                                    </circle>
                                                                </svg>
                                                            </button>
                                                        @else
                                                            <span class="font-monospace">**********</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td class="px-4">{{ $sd->nationality }}</td>
                                                <td class="px-4">{{ $sd->bumiputera_status }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-4 text-center text-muted fst-italic">Tiada maklumat pemegang saham.</div>
                        @endif
                    </div>
                </div>

                <div class="card border shadow-sm rounded-3 overflow-hidden mb-4">
                    <div class="card-header bg-white py-3 border-bottom fw-bold">Ringkasan Pegangan Saham</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center py-3">Bumiputera</th>
                                        <th class="text-center py-3">Bukan Bumiputera</th>
                                        <th class="text-center py-3">Warga Asing</th>
                                        <th class="text-center py-3 bg-white">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-center">
                                        <td class="py-3">{{ $vendor->bumi_percentage }} %</td>
                                        <td class="py-3">{{ $vendor->nonbumi_percentage }} %</td>
                                        <td class="py-3">{{ $vendor->foreigner_percentage }} %</td>
                                        <td class="py-3 fw-bold bg-white">
                                            {{ sprintf('%.2f', $vendor->bumi_percentage + $vendor->nonbumi_percentage + $vendor->foreigner_percentage) }}
                                            %</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 6. DIRECTORS -->
            <div class="tab-pane fade" id="vf-directors">
                <div class="card border shadow-sm rounded-3 overflow-hidden mb-4">
                    <div class="card-header bg-white py-3 border-bottom fw-bold">Senarai Pengarah</div>
                    <div class="card-body p-0">
                        @if (count($vendor->directors) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Nama
                                            </th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">IC /
                                                Pasport</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">
                                                Warganegara</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">
                                                Jawatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($vendor->directors as $sd)
                                            <tr>
                                                <td class="px-4">{{ $sd->name }}</td>
                                                <td class="px-4">
                                                    @if (!empty($sd->identity))
                                                        @if ($canViewSensitiveIdentity)
                                                            <span class="identity-mask font-monospace"
                                                                data-identity="{{ $sd->identity }}">**********</span>
                                                            <button type="button"
                                                                class="btn btn-sm p-0 mb-1 ms-2 toggle-identity text-decoration-none"
                                                                style="color: var(--bs-primary);" title="Tunjuk">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="16" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                    <path
                                                                        d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z">
                                                                    </path>
                                                                    <circle cx="12" cy="12" r="3">
                                                                    </circle>
                                                                </svg>
                                                            </button>
                                                        @else
                                                            <span class="font-monospace">**********</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td class="px-4">{{ $sd->nationality }}</td>
                                                <td class="px-4">{{ $sd->designation }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-4 text-center text-muted fst-italic">Tiada maklumat pengarah.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 7. CONTACTS -->
            <div class="tab-pane fade" id="vf-contacts">
                <div class="card border shadow-sm rounded-3 overflow-hidden mb-4">
                    <div class="card-header bg-white py-3 border-bottom fw-bold">Kakitangan</div>
                    <div class="card-body p-0">
                        @if (count($vendor->contacts) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Nama
                                            </th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">
                                                Jawatan</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">
                                                Warganegara</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">
                                                Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($vendor->contacts()->orderBy('name', 'asc')->get() as $contact)
                                            <tr>
                                                <td class="px-4">{{ $contact->name }}</td>
                                                <td class="px-4">{{ $contact->designation }}</td>
                                                <td class="px-4">{{ $contact->nationality }}</td>
                                                <td class="px-4">{{ $contact->status }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-4 text-center text-muted fst-italic">Tiada maklumat kakitangan.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 8. AWARDS -->
            <div class="tab-pane fade" id="vf-awards">
                <div class="card border shadow-sm rounded-3 overflow-hidden mb-4">
                    <div class="card-header bg-white py-3 border-bottom fw-bold">Anugerah</div>
                    <div class="card-body p-0">
                        @if (count($vendor->awards) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Nama
                                            </th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">
                                                Keterangan</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">
                                                Pemberi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($vendor->awards()->orderBy('name', 'asc')->get() as $award)
                                            <tr>
                                                <td class="px-4">{{ $award->name }}</td>
                                                <td class="px-4">{{ $award->description }}</td>
                                                <td class="px-4">{{ $award->by }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-4 text-center text-muted fst-italic">Tiada maklumat anugerah.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 9. ASSETS -->
            <div class="tab-pane fade" id="vf-assets">
                <div class="card border shadow-sm rounded-3 overflow-hidden mb-4">
                    <div class="card-header bg-white py-3 border-bottom fw-bold">Aset</div>
                    <div class="card-body p-0">
                        @if (count($vendor->assets) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Nama
                                            </th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Nilai
                                                (RM)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($vendor->assets()->orderBy('name', 'asc')->get() as $asset)
                                            <tr>
                                                <td class="px-4">{{ $asset->name }}</td>
                                                <td class="px-4">{{ $asset->value }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-4 text-center text-muted fst-italic">Tiada maklumat aset.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 10. PROJECTS -->
            <div class="tab-pane fade" id="vf-projects">
                <div class="card border shadow-sm rounded-3 overflow-hidden mb-4">
                    <div class="card-header bg-white py-3 border-bottom fw-bold">Projek</div>
                    <div class="card-body p-0">
                        @if (count($vendor->projects) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Nama
                                            </th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">
                                                Pelanggan</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">
                                                Tempoh</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Nilai
                                                (RM)</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Siap
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($vendor->projects()->orderBy('name', 'asc')->get() as $project)
                                            <tr>
                                                <td class="px-4">{{ $project->name }}</td>
                                                <td class="px-4">{{ $project->customer }}</td>
                                                <td class="px-4">{{ $project->period }}</td>
                                                <td class="px-4">{{ $project->value }}</td>
                                                <td class="px-4">{!! boolean_icon($project->done) !!}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-4 text-center text-muted fst-italic">Tiada maklumat projek.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 11. PRODUCTS -->
            <div class="tab-pane fade" id="vf-products">
                <div class="card border shadow-sm rounded-3 overflow-hidden mb-4">
                    <div class="card-header bg-white py-3 border-bottom fw-bold">Produk</div>
                    <div class="card-body p-0">
                        @if (count($vendor->products) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Nama
                                            </th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">
                                                Keterangan</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">
                                                Pengguna</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($vendor->products()->orderBy('name', 'asc')->get() as $product)
                                            <tr>
                                                <td class="px-4">{{ $product->name }}</td>
                                                <td class="px-4">{{ $product->description }}</td>
                                                <td class="px-4">{{ $product->implementations }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-4 text-center text-muted fst-italic">Tiada maklumat produk.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 12. FILES -->
            <div class="tab-pane fade" id="vf-files">
                <div class="card border shadow-sm rounded-3 overflow-hidden mb-4">
                    <div class="card-header bg-white py-3 border-bottom fw-bold">Dokumen Dimuat Naik</div>
                    <div class="card-body">
                        {!! $vendor->uploadsTable() !!}
                    </div>
                </div>
            </div>

            <!-- 13. SUBSCRIPTIONS -->
            <div class="tab-pane fade" id="vf-subscriptions">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c41e3a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        <span class="fw-bold" style="font-size:0.88rem;">Bayaran Pendaftaran</span>
                    </div>
                    <div class="card-body p-0">
                        @if ($vendor->subscriptions()->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead style="background:#f8fafc;">
                                        <tr>
                                            <th style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-color:#e5e7eb;padding:10px 16px;">Bil.</th>
                                            <th style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-color:#e5e7eb;padding:10px 16px;">No Transaksi</th>
                                            <th style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-color:#e5e7eb;padding:10px 16px;">No Resit</th>
                                            <th style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-color:#e5e7eb;padding:10px 16px;">Tempoh Langganan</th>
                                            <th style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-color:#e5e7eb;padding:10px 16px;text-align:center;">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $transaction)
                                            <tr style="border-color:#f1f5f9;">
                                                <td style="font-size:0.78rem;color:#9ca3af;padding:12px 16px;border-color:#f1f5f9;">{{ $loop->index + 1 }}.</td>
                                                <td style="font-size:0.82rem;color:#374151;padding:12px 16px;border-color:#f1f5f9;">{{ $transaction->number }}</td>
                                                <td style="font-size:0.82rem;color:#374151;padding:12px 16px;border-color:#f1f5f9;">
                                                    {{ $transaction->receipt != 'old' ? $transaction->receipt : $transaction->receipt_number }}
                                                </td>
                                                <td style="font-size:0.82rem;color:#374151;padding:12px 16px;border-color:#f1f5f9;white-space:nowrap;">
                                                    {{ $transaction->start_date }} — {{ $transaction->end_date }}
                                                </td>
                                                <td style="padding:12px 16px;border-color:#f1f5f9;text-align:center;">
                                                    @if ($transaction->subscription_id !== 'TIADA')
                                                        <a href="{{ route('vendors.subscriptions.receipt', [$vendor->id, $transaction->subscription_id]) }}" target="_blank"
                                                            style="display:inline-flex;align-items:center;gap:4px;font-size:0.72rem;font-weight:600;color:#0369a1;padding:4px 12px;background:#f0f9ff;border-radius:5px;border:1px solid #bae6fd;text-decoration:none;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                                            Resit
                                                        </a>
                                                    @else
                                                        <span style="font-size:0.72rem;color:#9ca3af;">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-4 text-center text-muted" style="font-size:0.82rem;">Tiada maklumat langganan.</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Rekod Penilaian Prestasi Syarikat --}}
            @include('vendors.tab-contents.prestasi-syarikat')

        </div>
    </div>
</div>

@push('scripts')
    {{-- PDF viewer: partials.global_pdf_viewer --}}
    @if (session('cidb_integrated'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const cidbTab = document.querySelector('a[href="#vf-cidb"]');
                if (cidbTab && window.bootstrap) {
                    window.bootstrap.Tab.getOrCreateInstance(cidbTab).show();
                }
            });
        </script>
    @endif

    <!-- Show/Hide Sensitive Info (IC. NO.) -->
    @if ($canViewSensitiveIdentity)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (window.__identityToggleInitialized) return;
                window.__identityToggleInitialized = true;

                const iconShow =
                    '<svg style="color: var(--bs-primary);" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
                const iconHide =
                    '<svg style="color: var(--bs-primary);" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';

                var toggleButtons = document.querySelectorAll('.toggle-identity');
                toggleButtons.forEach(function(button) {
                    button.addEventListener('click', function(event) {
                        event.preventDefault();
                        var mask = button.previousElementSibling;
                        if (!mask || !mask.dataset || !mask.dataset.identity) return;
                        var revealed = mask.classList.toggle('identity-revealed');
                        // Toggle Text Content
                        mask.textContent = revealed ? mask.dataset.identity : '**********';
                        // Toggle Icon
                        button.innerHTML = revealed ? iconHide : iconShow;
                        // Tooltip Title
                        button.title = revealed ? 'Sembunyi' : 'Tunjuk';
                    });
                });
            });
        </script>
    @endif
@endpush
