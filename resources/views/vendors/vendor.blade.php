{{-- {{ App\Libraries\Asset::push('css', 'form') }} --}}

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
    .nav-link svg { opacity: 0.5; transition: 0.2s; }
    .nav-link.active svg { opacity: 1; stroke: #c41e3a; }
    
    /* Scrollable Code List */
    .code-list-scroll {
        max-height: 300px;
        overflow-y: auto;
    }
</style>

<div class="row g-4">
    
    <!-- LEFT NAVIGATION -->
    <div class="col-lg-3 col-xl-3">
        <div class="sticky-top" style="top: 130px; z-index: 10;">
            <div class="card border shadow-sm rounded-3 p-2">
                <ul class="nav nav-pills nav-pills-custom flex-column gap-1">
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center @if (!isset($active_prestasi_tab)) active @endif" href="#vf-main" data-bs-toggle="pill">
                            Maklumat Syarikat
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center" href="#vf-officer" data-bs-toggle="pill">
                            Maklumat Pegawai
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center" href="#vf-mof" data-bs-toggle="pill">
                            MOF
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center" href="#vf-cidb" data-bs-toggle="pill">
                            CIDB
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center" href="#vf-shareholders" data-bs-toggle="pill">
                            Pemegang Saham
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center" href="#vf-directors" data-bs-toggle="pill">
                            Pengarah
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </a>
                    </li>
                    @if (isset($vendor) && $vendor->approval_1_id > 0)
                        <li class="nav-item"><a class="nav-link d-flex justify-content-between align-items-center" href="#vf-contacts" data-bs-toggle="pill">Kakitangan <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></a></li>
                        <li class="nav-item"><a class="nav-link d-flex justify-content-between align-items-center" href="#vf-awards" data-bs-toggle="pill">Anugerah <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></a></li>
                        <li class="nav-item"><a class="nav-link d-flex justify-content-between align-items-center" href="#vf-assets" data-bs-toggle="pill">Aset <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></a></li>
                        <li class="nav-item"><a class="nav-link d-flex justify-content-between align-items-center" href="#vf-projects" data-bs-toggle="pill">Projek <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></a></li>
                        <li class="nav-item"><a class="nav-link d-flex justify-content-between align-items-center" href="#vf-products" data-bs-toggle="pill">Produk <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></a></li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center" href="#vf-files" data-bs-toggle="pill">
                            Fail
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center" href="#vf-subscriptions" data-bs-toggle="pill">
                            Bayaran Pendaftaran
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center @if (isset($active_prestasi_tab)) active @endif" href="#vf-prestasi-syarikat" data-bs-toggle="pill">
                            Rekod Prestasi
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
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
                    <div class="card-header bg-light py-3 border-bottom d-flex align-items-center gap-2 fw-bold text-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                        Butiran Syarikat
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Alamat Emel</label>
                                <div class="fw-medium text-dark">{{ $vendor->user->email }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">No Pendaftaran</label>
                                <div class="fw-medium text-dark">{{ $vendor->registration }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Nama Syarikat</label>
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
                                    <label class="small text-muted fw-bold text-uppercase d-block mb-1">Kod Pengesahan Sijil</label>
                                    <span class="badge bg-light text-dark border font-monospace">{{ $vendor->token }}</span>
                                </div>
                                <a href="{{ action('VendorsController@certificate', $vendor->id) }}" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-2 view-pdf-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                    Papar Sijil
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card border shadow-sm rounded-3 overflow-hidden mb-4">
                    <div class="card-header bg-light py-3 border-bottom d-flex align-items-center gap-2 fw-bold text-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a4.5 4.5 0 0 0-4.5 4.5v9a4.5 4.5 0 0 0 4.5 4.5H17"></path></svg>
                        Maklumat Kewangan & Korporat
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Jenis Perniagaan</label>
                                <div class="fw-medium text-dark">{{ $vendor->organization_type }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Tarikh Penubuhan</label>
                                <div class="fw-medium text-dark">{{ $vendor->incorporation_date }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Modal Dibenarkan</label>
                                <div class="fw-medium text-dark">{{ $vendor->authorized_capital_currency }} {{ $vendor->authorized_capital }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Modal Berbayar</label>
                                <div class="fw-medium text-dark">{{ $vendor->paidup_capital_currency }} {{ $vendor->paidup_capital }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">No. Rujukan Cukai</label>
                                <div class="fw-medium text-dark">{{ $vendor->tax_no ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">No. Pendaftaran GST</label>
                                <div class="fw-medium text-dark">{{ $vendor->gst_no ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Laman Web</label>
                                <div class="fw-medium text-dark">
                                    @if ($vendor->website)
                                        <a href="{{ $vendor->website }}" target="_blank" class="text-danger text-decoration-none">{{ $vendor->website }}</a>
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
                    <div class="card-header bg-light py-3 border-bottom fw-bold text-secondary">Maklumat Pegawai Utama</div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Nama Pegawai</label>
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
                    <div class="card-header bg-light py-3 border-bottom fw-bold text-secondary">Sijil Kementerian Kewangan (MOF)</div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">No Rujukan</label>
                                <div class="fw-medium text-dark">{{ $vendor->mof_ref_no ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Tarikh Aktif</label>
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
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Status Bumiputera</label>
                                <div>
                                    @if ($vendor->mof_bumi)
                                        <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="me-1"><polyline points="20 6 9 17 4 12"></polyline></svg> Ya</span>
                                    @else
                                        <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger">Tidak</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <label class="small text-muted fw-bold text-uppercase d-block mb-2">Kod Bidang MOF ({{ count($vendor->mofCodes) }})</label>
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
                    <div class="card-header bg-light py-3 border-bottom fw-bold text-secondary">Lembaga Pembangunan Industri Pembinaan (CIDB)</div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">No Sijil CIDB</label>
                                <div class="fw-medium text-dark">{!! $vendor->cidb_ref_no ?? '-' !!}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Tarikh Aktif</label>
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
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Status Bumiputera</label>
                                <div>
                                    @if ($vendor->cidb_bumi)
                                        <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="me-1"><polyline points="20 6 9 17 4 12"></polyline></svg> Ya</span>
                                    @else
                                        <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger">Tidak</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <label class="small text-muted fw-bold text-uppercase d-block mb-2">Gred & Bidang Pengkhususan</label>
                        <div class="bg-light p-3 rounded border code-list-scroll">
                            @forelse($vendor->cidbGrades()->orderBy('id')->get() as $grade)
                                <div class="mb-3">
                                    <strong class="text-danger">{{ $grade->code->label }}</strong>
                                    <div class="ps-3 mt-1 small">
                                        {{-- Group A --}}
                                        @php $a_codes = App\Code::whereIn('id', $grade->children->pluck('code_id'))->where('code', 'LIKE', 'A%')->orderBy('code')->get(); @endphp
                                        @if(count($a_codes) > 0)
                                            <div><strong>A:</strong> {{ $a_codes->pluck('description')->implode(', ') }}</div>
                                        @endif
                                        {{-- Group B --}}
                                        @php $b_codes = App\Code::whereIn('id', $grade->children->pluck('code_id'))->where('code', 'LIKE', 'B%')->orderBy('code')->get(); @endphp
                                        @if(count($b_codes) > 0)
                                            <div><strong>B:</strong> {{ $b_codes->pluck('description')->implode(', ') }}</div>
                                        @endif
                                        {{-- Group CE --}}
                                        @php $ce_codes = App\Code::whereIn('id', $grade->children->pluck('code_id'))->where('code', 'LIKE', 'CE%')->orderBy('code')->get(); @endphp
                                        @if(count($ce_codes) > 0)
                                            <div><strong>CE:</strong> {{ $ce_codes->pluck('description')->implode(', ') }}</div>
                                        @endif
                                         {{-- Group ME --}}
                                        @php $me_codes = App\Code::whereIn('id', $grade->children->pluck('code_id'))->where('code', 'REGEXP', '^[ME]')->orderBy('code')->get(); @endphp
                                        @if(count($me_codes) > 0)
                                            <div><strong>ME:</strong> {{ $me_codes->pluck('description')->implode(', ') }}</div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted text-center fst-italic">Tiada maklumat Gred.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            @php $canViewSensitiveIdentity = Auth::check() && Auth::user()->hasRole('Admin'); @endphp

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
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted font-weight-bold">Nama</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted font-weight-bold">IC / Pasport</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted font-weight-bold">Warganegara</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted font-weight-bold">Taraf</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($vendor->shareholders as $sd)
                                            <tr>
                                                <td class="px-4">{{ $sd->name }}</td>
                                                <td class="px-4">
                                                    @if (!empty($sd->identity))
                                                        @if ($canViewSensitiveIdentity)
                                                            <span class="identity-mask font-monospace" data-identity="{{ $sd->identity }}">**********</span>
                                                            <button type="button" class="btn btn-link btn-sm p-0 ms-2 toggle-identity text-decoration-none small">Tunjuk</button>
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
                                        <td class="py-3 fw-bold bg-white">{{ sprintf('%.2f', $vendor->bumi_percentage + $vendor->nonbumi_percentage + $vendor->foreigner_percentage) }} %</td>
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
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Nama</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">IC / Pasport</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Warganegara</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Jawatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($vendor->directors as $sd)
                                            <tr>
                                                <td class="px-4">{{ $sd->name }}</td>
                                                <td class="px-4">
                                                    @if (!empty($sd->identity))
                                                        @if ($canViewSensitiveIdentity)
                                                            <span class="identity-mask font-monospace" data-identity="{{ $sd->identity }}">**********</span>
                                                            <button type="button" class="btn btn-link btn-sm p-0 ms-2 toggle-identity text-decoration-none small">Tunjuk</button>
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
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Nama</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Jawatan</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Warganegara</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Status</th>
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
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Nama</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Keterangan</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Pemberi</th>
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
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Nama</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Nilai (RM)</th>
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
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Nama</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Pelanggan</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Tempoh</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Nilai (RM)</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Siap</th>
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
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Nama</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Keterangan</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Pengguna</th>
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
                <div class="card border shadow-sm rounded-3 overflow-hidden mb-4">
                    <div class="card-header bg-white py-3 border-bottom fw-bold">Bayaran Pendaftaran</div>
                    <div class="card-body p-0">
                        @if ($vendor->subscriptions()->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">No Transaksi</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">No Resit</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Tempoh Langganan</th>
                                            <th class="px-4 py-3 border-bottom-0 text-uppercase small text-muted">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $transaction)
                                            <tr>
                                                <td class="px-4">{{ $transaction->number }} </td>
                                                <td class="px-4">{{ $transaction->receipt != 'old' ? $transaction->receipt : $transaction->receipt_number }}</td>
                                                <td class="px-4">{{ $transaction->start_date }} - {{ $transaction->end_date }}</td>
                                                <td class="px-4">
                                                    {{ link_to_route('vendors.subscriptions.receipt', 'Resit', [$vendor->id, $transaction->subscription_id], ['target' => 'new', 'class' => 'btn btn-xs btn-outline-danger']) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                             <div class="p-4 text-center text-muted fst-italic">Tiada maklumat langganan.</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- START: Tab Content - Rekod Penilaian Prestasi Syarikat --}}
            @include('vendors.tab-contents.prestasi-syarikat')
            {{-- END: Tab Content - Rekod Penilaian Prestasi Syarikat --}}

        </div>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="pdfViewerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content h-100 border-0 shadow-lg rounded-3">
            <div class="modal-body p-0 bg-light">
                <iframe id="pdfIframe" src="" width="100%" height="100%" style="border:none; min-height: 85vh;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() 
	{    
        document.body.addEventListener('click', function(e) 
		{    
            var target = e.target.closest('.view-pdf-btn, .btn-file-view');

            if (target) {
                e.preventDefault();

                var url = target.getAttribute('data-url') || target.getAttribute('href');

                if (url) {
                    // Open Modal
                    var iframe = document.getElementById('pdfIframe');
                    var modalEl = document.getElementById('pdfViewerModal');

                    if (iframe && modalEl) {
                        iframe.src = url;
                        var myModal = new bootstrap.Modal(modalEl);
                        myModal.show();
                    }
                }
            }
        });

        var pdfModal = document.getElementById('pdfViewerModal');
        if (pdfModal) {
            pdfModal.addEventListener('hidden.bs.modal', function () {
                document.getElementById('pdfIframe').src = '';
            });
        }
    });
</script>

@if ($canViewSensitiveIdentity)
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			if (window.__identityToggleInitialized) return;
			window.__identityToggleInitialized = true;
			var toggleButtons = document.querySelectorAll('.toggle-identity');
			toggleButtons.forEach(function(button) {
				button.addEventListener('click', function(event) {
					event.preventDefault();
					var mask = button.previousElementSibling;
					if (!mask || !mask.dataset || !mask.dataset.identity) return;
					var revealed = mask.classList.toggle('identity-revealed');
					mask.textContent = revealed ? mask.dataset.identity : '**********';
					button.textContent = revealed ? 'Sembunyi' : 'Tunjuk';
				});
			});
		});
	</script>
@endif