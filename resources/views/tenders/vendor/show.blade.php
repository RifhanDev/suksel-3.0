@extends('layouts.modernLanding')

@section('styles')
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <style>
        .vendor-tender-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            overflow: hidden;
            margin-bottom: 1.25rem;
        }
        .vendor-tender-card-header {
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .vendor-tender-card-header h6 {
            margin: 0;
            font-size: 0.82rem;
            font-weight: 700;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .vendor-tender-card-header .header-icon {
            width: 28px; height: 28px;
            background: rgba(196,30,58,0.08);
            color: #c41e3a;
            border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .info-table { width: 100%; }
        .info-table tr { border-bottom: 1px solid #f1f5f9; }
        .info-table tr:last-child { border-bottom: none; }
        .info-table th {
            padding: 10px 20px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            width: 35%;
            vertical-align: top;
        }
        .info-table td {
            padding: 10px 20px;
            font-size: 0.82rem;
            color: #1f2937;
            font-weight: 500;
        }
        /* Side nav */
        .vendor-side-nav .nav-link {
            color: #475569;
            font-size: 0.82rem;
            font-weight: 500;
            border-radius: 8px;
            padding: 9px 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.15s, color 0.15s;
            margin-bottom: 2px;
        }
        .vendor-side-nav .nav-link:hover { background: #f1f5f9; color: #1e293b; }
        .vendor-side-nav .nav-link.active { background: linear-gradient(135deg, #c41e3a 0%, #a01830 100%); color: #fff; }
        .vendor-side-nav .nav-link svg { flex-shrink: 0; opacity: 0.65; }
        .vendor-side-nav .nav-link.active svg { opacity: 1; }
    </style>
@endsection

@section('content')

    {{-- Breadcrumb + Header --}}
    <div class="mb-4">
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ url('dashboard') }}" class="text-muted small text-decoration-none">Dashboard</a>
            <span class="text-muted small">/</span>
            <span class="text-muted small">{{ App\Tender::$types[$tender->type] ?? 'Tender' }}</span>
        </div>
        <h3 class="fw-bold text-dark m-0" style="letter-spacing:-0.5px;">{{ $tender->name }}</h3>
        <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
            <span class="text-muted small">{{ optional($tender->tenderer)->name }}</span>
            @if($tender->ref_number)
                <span class="text-muted small">·</span>
                <span class="fw-semibold small text-dark">{{ $tender->ref_number }}</span>
            @endif
        </div>
    </div>

    @include('tenders._notification')

    <div class="row g-4 align-items-start">

        {{-- LEFT: Side nav --}}
        <div class="col-lg-3">
            <div class="bg-white rounded-3 border shadow-sm p-2">
                <nav class="nav flex-column vendor-side-nav" id="vendorTabs" role="tablist">

                    <a class="nav-link active" href="#vt-main" data-bs-toggle="pill" role="tab">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Maklumat {{ App\Tender::$types[$tender->type] ?? 'Tender' }}
                    </a>

                    <a class="nav-link" href="#vt-syarat" data-bs-toggle="pill" role="tab">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        Syarat {{ App\Tender::$types[$tender->type] ?? 'Tender' }}
                    </a>

                    @if (count($tender->siteVisits) > 0)
                        <a class="nav-link" href="#vt-lawatan" data-bs-toggle="pill" role="tab">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            Lawatan Tapak
                        </a>
                    @endif

                    @if (count($tender->mof_codes) > 0 || count($tender->cidb_grades) > 0 || count($tender->cidb_codes) > 0)
                        <a class="nav-link {{ Auth::user()->vendor && $tender->codeErrors(Auth::user()->vendor_id) ? 'text-danger' : '' }}" href="#vt-kod" data-bs-toggle="pill" role="tab">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                            Kod Bidang
                        </a>
                    @endif

                    @if (count($tender->table_files) > 0)
                        <a class="nav-link" href="#vt-doc1" data-bs-toggle="pill" role="tab">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                            Dokumen Meja Terkawal
                            <span class="badge bg-primary ms-auto" style="font-size:0.6rem;">{{ $tender->files()->where('public', 1)->count() }}</span>
                        </a>
                    @endif

                    @if ($tender->canShowFiles(Auth::user()->vendor_id))
                        <a class="nav-link" href="#vt-doc2" data-bs-toggle="pill" role="tab">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                            Dokumen {{ App\Tender::$types[$tender->type] ?? 'Tender' }}
                            <span class="badge bg-primary ms-auto" style="font-size:0.6rem;">{{ $tender->files()->where('public', 0)->count() }}</span>
                        </a>
                    @endif

                    <a class="nav-link" href="#vt-news" data-bs-toggle="pill" role="tab">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3H4a4 4 0 0 0 2-3v-3a7 7 0 0 1 4-6M9 17v1a3 3 0 0 0 6 0v-1"/></svg>
                        Makluman / Ralat
                        @if($tender->news()->count() > 0)
                            <span class="badge bg-warning ms-auto" style="font-size:0.6rem;">{{ $tender->news()->count() }}</span>
                        @endif
                    </a>

                    {{-- Kebenaran Khas — same condition as auth/show --}}
                    @if (
                        !$tender->matchCidbCodesInverse(Auth::user()->vendor_id) &&
                        $tender->matchCidbGrade(Auth::user()->vendor_id) &&
                        $tender->attendVisits(Auth::user()->vendor_id) &&
                        $tender->attendBriefing(Auth::user()->vendor_id)
                    )
                        <a class="nav-link" href="#vt-exception" data-bs-toggle="pill" role="tab">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m16.555 3.843l3.602 3.602a2.877 2.877 0 0 1 0 4.069l-2.643 2.643a2.877 2.877 0 0 1-4.069 0l-.301-.301l-6.558 6.558a2 2 0 0 1-1.239.578L5.172 21H4a1 1 0 0 1-.993-.883L3 20v-1.172a2 2 0 0 1 .467-1.284l.119-.13L4 17h2v-2h2v-2l2.144-2.144l-.301-.301a2.877 2.877 0 0 1 0-4.069l2.643-2.643a2.877 2.877 0 0 1 4.069 0M15 9h.01"/></svg>
                            Kebenaran Khas
                        </a>
                    @endif

                    <a class="nav-link" href="#vt-officer" data-bs-toggle="pill" role="tab">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Pegawai Bertanggungjawab
                    </a>

                    {{-- Penilaian Prestasi — only if tender has a winner --}}
                    @if ($tender_winner)
                        <a class="nav-link" href="#vt-penilaian" data-bs-toggle="pill" role="tab">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 13a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1zm12-4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1zM9 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1zM4 20h14"/></svg>
                            Penilaian Prestasi Syarikat
                        </a>
                    @endif

                </nav>
            </div>
        </div>

        {{-- RIGHT: Tab content --}}
        <div class="col-lg-9">
            <div class="tab-content">

                {{-- TAB: Maklumat --}}
                <div class="tab-pane fade show active" id="vt-main" role="tabpanel">
                    <div class="vendor-tender-card">
                        <div class="vendor-tender-card-header">
                            <div class="header-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div>
                            <h6>Maklumat {{ App\Tender::$types[$tender->type] ?? 'Tender' }}</h6>
                        </div>
                        <table class="info-table">
                            <tr><th>Petender</th><td>{{ optional($tender->tenderer)->name ?? '-' }}</td></tr>
                            <tr><th>No. {{ App\Tender::$types[$tender->type] ?? 'Tender' }}</th><td>{{ $tender->ref_number ?? '-' }}</td></tr>
                            <tr>
                                <th>Tarikh Iklan</th>
                                <td>{{ \Carbon\Carbon::parse($tender->advertise_start_date)->format('j M Y') }} – {{ \Carbon\Carbon::parse($tender->advertise_stop_date)->format('j M Y') }}</td>
                            </tr>
                            <tr>
                                <th>Tarikh Jual</th>
                                <td>{{ \Carbon\Carbon::parse($tender->document_start_date)->format('j M Y') }} – {{ \Carbon\Carbon::parse($tender->document_stop_date)->format('j M Y') }}</td>
                            </tr>
                            <tr><th>Tarikh Tutup</th><td>{{ \Carbon\Carbon::parse($tender->submission_datetime)->format('j M Y') }}</td></tr>
                            <tr><th>Masa Tutup</th><td>12:00 PM</td></tr>
                            @if($tender->submission_location_address)
                                <tr><th>Tempat Hantar</th><td>{!! nl2br(e($tender->submission_location_address)) !!}</td></tr>
                            @endif
                            @if ($tender->hasBriefing())
                                <tr><th>Tarikh &amp; Masa Taklimat</th><td>{{ \Carbon\Carbon::parse($tender->briefing_datetime)->format('j M Y H:i') }}</td></tr>
                                <tr>
                                    <th>Alamat Taklimat</th>
                                    <td>
                                        {!! nl2br(e($tender->briefing_address)) !!}
                                        @if ($tender->briefing_required)
                                            <div class="mt-1 text-danger small fw-semibold">&#10003; Kehadiran taklimat adalah diwajibkan</div>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <th>Kebenaran Khas</th>
                                <td>
                                    @if ($tender->allow_exception)
                                        <span class="badge bg-success d-inline-flex align-items-center">Ya</span>
                                    @else
                                        <span class="badge bg-danger d-inline-flex align-items-center">Tidak</span>
                                    @endif
                                </td>
                            </tr>
                            @if ($tender->only_bumiputera)
                                <tr><th>Syarikat Bumiputera Sahaja</th><td><span class="badge bg-success">Ya</span></td></tr>
                            @endif
                            @if ($tender->only_selangor == 2)
                                <tr><th>Syarikat Negeri</th><td><span class="badge bg-info">{{ strtoupper($tender->getNegeriList()) }} SAHAJA</span></td></tr>
                            @elseif ($tender->only_selangor == 3)
                                <tr><th>Syarikat Negeri</th><td><span class="badge bg-info">SELURUH MALAYSIA</span></td></tr>
                            @endif
                            @if ($tender->district_id != null && $tender->district_id > 0)
                                <tr><th>Syarikat Dibawah Daerah Sahaja</th><td><span class="badge bg-info">{{ strtoupper(App\Vendor::$districts[$tender->district_id]) }} SAHAJA</span></td></tr>
                            @elseif($tender->district_id == null && $tender->getDaerahListExist() === true && $tender->only_selangor != 3)
                                <tr><th>Syarikat Dibawah Daerah Sahaja</th><td><span class="badge bg-info">{{ strtoupper($tender->getDaerahList()) }} SAHAJA</span></td></tr>
                            @elseif($tender->district_id == null && $tender->district_list_rule === '[]' && $tender->only_selangor == 1)
                                <tr><th>Syarikat Dibawah Daerah Sahaja</th><td><span class="badge bg-info">SELURUH SELANGOR</span></td></tr>
                            @endif
                            <tr><th>Harga Dokumen</th><td><strong>RM {{ number_format($tender->price, 2) }}</strong></td></tr>
                        </table>
                    </div>
                    @if ($tender->canPurchase())
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('tenders.buy', $tender->id) }}" class="btn-form btn-form-primary">Tambah Kepada Senarai Tempahan</a>
                        </div>
                    @endif
                </div>

                {{-- TAB: Syarat --}}
                <div class="tab-pane fade" id="vt-syarat" role="tabpanel">
                    <div class="vendor-tender-card">
                        <div class="vendor-tender-card-header">
                            <div class="header-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
                            <h6>Syarat {{ App\Tender::$types[$tender->type] ?? 'Tender' }}</h6>
                        </div>
                        <div class="p-4" style="font-size:0.85rem; line-height:1.7;">
                            @if($tender->tender_rules)
                                {!! $tender->tender_rules !!}
                            @else
                                <p class="text-muted mb-0">Tiada syarat ditetapkan.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- TAB: Lawatan Tapak --}}
                @if (count($tender->siteVisits) > 0)
                    <div class="tab-pane fade" id="vt-lawatan" role="tabpanel">
                        <div class="vendor-tender-card">
                            <div class="vendor-tender-card-header">
                                <div class="header-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
                                <h6>Lawatan Tapak</h6>
                            </div>
                            <div class="px-4 pt-3 pb-0">
                                <p class="text-muted small mb-0">
                                    Klik <strong>Wakil Syarikat</strong> pada setiap lawatan untuk masukkan No. IC, nama wakil dan tandakan kehadiran selepas menghadiri lawatan tapak.
                                </p>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" style="font-size:0.82rem;">
                                    <thead style="background:#f8fafc;">
                                        <tr>
                                            <th class="py-3 ps-4" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase;">Bil.</th>
                                            <th class="py-3" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase;">Tempat Berkumpul</th>
                                            <th class="py-3" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase;">Alamat</th>
                                            <th class="py-3" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase;">Tarikh &amp; Waktu</th>
                                            <th class="py-3" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase;">Wajib Hadir</th>
                                            <th class="py-3 pe-4 text-center" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase;">Wakil Syarikat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tender->siteVisits->sortBy('id') as $i => $visit)
                                            @php
                                                $visitAttended = Auth::check() && Auth::user()->vendor_id
                                                    ? App\TenderVisitor::hasVisit($visit->id, Auth::user()->vendor_id)
                                                    : false;
                                            @endphp
                                            <tr style="border-color:#e5e7eb;">
                                                <td class="ps-4">{{ $i + 1 }}</td>
                                                <td>{!! nl2br(e($visit->meetpoint)) !!}</td>
                                                <td>{!! nl2br(e($visit->address)) !!}</td>
                                                <td>{{ \Carbon\Carbon::parse($visit->datetime)->format('j M Y H:i') }}</td>
                                                <td>
                                                    @if ($visit->required)
                                                        <span class="badge bg-success">Ya</span>
                                                    @else
                                                        <span class="badge bg-danger">Tidak</span>
                                                    @endif
                                                </td>
                                                <td class="pe-4 text-center">
                                                    @if ($visitAttended)
                                                        <span class="badge bg-success mb-1 d-block">Hadir</span>
                                                    @endif
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger btn-wakil-lawatan"
                                                        data-visit-id="{{ $visit->id }}"
                                                        data-visit-label="Lawatan {{ $i + 1 }} — {{ \Carbon\Carbon::parse($visit->datetime)->format('j M Y H:i') }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#wakilLawatanModal">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                        Wakil
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Modal: Wakil Syarikat --}}
                    <div class="modal fade" id="wakilLawatanModal" tabindex="-1" aria-labelledby="wakilLawatanModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header" style="background:#c41e3a; color:#fff;">
                                    <h6 class="modal-title" id="wakilLawatanModalLabel">Wakil Syarikat</h6>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="text-muted small mb-3" id="wakilLawatanVisitInfo"></p>
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle mb-0" id="wakilLawatanTable">
                                            <thead>
                                                <tr>
                                                    <th width="28%">No. IC</th>
                                                    <th>Nama Individu</th>
                                                    <th width="12%" class="text-center">Hadir</th>
                                                    <th width="8%"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="wakilLawatanRows"></tbody>
                                        </table>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="wakilLawatanAddRow">+ Tambah wakil</button>
                                    <p class="text-muted small mt-3 mb-0">
                                        Nota: Tandakan <strong>Hadir</strong> untuk sekurang-kurangnya seorang wakil yang hadir ke lawatan tapak. Nama hendaklah selari dengan sijil CIDB / MOF jika berkenaan.
                                    </p>
                                    <div class="alert alert-danger d-none mt-3 mb-0" id="wakilLawatanError"></div>
                                    <div class="alert alert-success d-none mt-3 mb-0" id="wakilLawatanSuccess"></div>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="button" class="btn btn-selangor px-4" id="wakilLawatanSave">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- TAB: Kod Bidang --}}
                @if (count($tender->mof_codes) > 0 || count($tender->cidb_grades) > 0 || count($tender->cidb_codes) > 0)
                    <div class="tab-pane fade" id="vt-kod" role="tabpanel">
                        @if (count($tender->mof_codes) > 0)
                            <div class="vendor-tender-card">
                                <div class="vendor-tender-card-header">
                                    <div class="header-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg></div>
                                    <h6>Kod Bidang MOF</h6>
                                </div>
                                <table class="info-table">
                                    @php $max_count = count($tender->mof_code_groups); @endphp
                                    <tr>
                                        <th>Kod Bidang MOF</th>
                                        <td>
                                            @foreach ($tender->mof_code_groups as $order => $data)
                                                {!! implode('<br>' . App\VendorCode::$rule[$data['inner_rule']] . '<br>', tender_vendor_codes($data['codes'], Auth::user())) !!}
                                                @if ($order != $max_count)
                                                    <br><br>{!! App\VendorCode::$rule[$data['join_rule']] !!}<br><br>
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            @if (count($tender->cidb_grades) > 0)
                                <div class="text-center my-2">
                                    <span class="badge bg-success">{{ $tender->mof_cidb_rule == 'or' ? 'ATAU' : 'DAN' }}</span>
                                </div>
                            @endif
                        @endif
                        @if (count($tender->cidb_grades) > 0)
                            <div class="vendor-tender-card">
                                <div class="vendor-tender-card-header">
                                    <div class="header-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg></div>
                                    <h6>Kod Bidang CIDB</h6>
                                </div>
                                <table class="info-table">
                                    <tr>
                                        <th>Gred CIDB</th>
                                        <td>
                                            <ul class="mb-0 ps-3">
                                                @foreach ($tender->cidb_grades as $code)
                                                    <li>{!! tender_cidb_grade($code->code, Auth::user()) !!}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                    @if (count($tender->cidb_codes) > 0)
                                        @php $max_count = count($tender->cidb_code_groups); @endphp
                                        <tr>
                                            <th>Pengkhususan CIDB</th>
                                            <td>
                                                @foreach ($tender->cidb_code_groups as $order => $data)
                                                    {!! implode('<br>' . App\VendorCode::$rule[$data['inner_rule']] . '<br>', tender_vendor_codes($data['codes'], Auth::user())) !!}
                                                    @if ($order != $max_count)
                                                        <br><br>{!! App\VendorCode::$rule[$data['join_rule']] !!}<br><br>
                                                    @endif
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- TAB: Dokumen Meja Terkawal --}}
                <div class="tab-pane fade" id="vt-doc1" role="tabpanel">
                    <div class="vendor-tender-card">
                        <div class="vendor-tender-card-header">
                            <div class="header-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg></div>
                            <h6>Dokumen Meja Terkawal</h6>
                        </div>
                        @if (count($tender->table_files) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" style="font-size:0.82rem;">
                                    <thead style="background:#f8fafc;">
                                        <tr>
                                            <th class="py-3 ps-4" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase;">Nama Fail</th>
                                            <th class="py-3" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase; width:100px;">Saiz</th>
                                            <th class="py-3" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase; width:80px;">Jenis</th>
                                            <th class="py-3 pe-4 text-center" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase; width:120px;">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tender->tableFiles as $upload)
                                            <tr style="border-color:#e5e7eb;">
                                                <td class="ps-4">{{ $upload->label }}</td>
                                                <td>{{ $upload->size }}</td>
                                                <td>{{ $upload->type }}</td>
                                                <td class="pe-4 text-center"><a href="{{ $upload->url }}" class="btn btn-sm btn-primary rounded-8 px-3" download>Muat Turun</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-4 text-muted small">Tiada dokumen meja terkawal.</div>
                        @endif
                    </div>
                </div>

                {{-- TAB: Dokumen Tender --}}
                @if ($tender->canShowFiles(Auth::user()->vendor_id))
                    <div class="tab-pane fade" id="vt-doc2" role="tabpanel">
                        <div class="vendor-tender-card">
                            <div class="vendor-tender-card-header">
                                <div class="header-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg></div>
                                <h6>Dokumen {{ App\Tender::$types[$tender->type] ?? 'Tender' }}</h6>
                            </div>
                            @if (count($tender->tender_files) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0" style="font-size:0.82rem;">
                                        <thead style="background:#f8fafc;">
                                            <tr>
                                                <th class="py-3 ps-4" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase;">Nama Fail</th>
                                                <th class="py-3" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase; width:100px;">Saiz</th>
                                                <th class="py-3" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase; width:80px;">Jenis</th>
                                                <th class="py-3 pe-4 text-center" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase; width:120px;">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tender->tenderFiles as $upload)
                                                <tr style="border-color:#e5e7eb;">
                                                    <td class="ps-4">{{ $upload->label }}</td>
                                                    <td>{{ $upload->size }}</td>
                                                    <td>{{ $upload->type }}</td>
                                                    <td class="pe-4 text-center"><a href="{{ $upload->url }}" class="btn btn-sm btn-primary rounded-8 px-3" download>Muat Turun</a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="p-4"><div class="alert alert-info mb-0">Tiada fail untuk dimuat turun, sila rujuk syarat tender atau berhubung dengan agensi yang berkenaan.</div></div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- TAB: Makluman / Ralat --}}
                <div class="tab-pane fade" id="vt-news" role="tabpanel">
                    <div class="vendor-tender-card">
                        <div class="vendor-tender-card-header">
                            <div class="header-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3H4a4 4 0 0 0 2-3v-3a7 7 0 0 1 4-6M9 17v1a3 3 0 0 0 6 0v-1"/></svg></div>
                            <h6>Makluman / Ralat</h6>
                        </div>
                        @php $list_ralat_news = $tender->news()->wherePublish(1)->orderBy('published_at', 'asc')->get(); @endphp
                        @if ($list_ralat_news->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" style="font-size:0.82rem;">
                                    <thead style="background:#f8fafc;">
                                        <tr>
                                            <th class="py-3 ps-4" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase; width:130px;">Tarikh</th>
                                            <th class="py-3" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase;">Tajuk</th>
                                            <th class="py-3 pe-4 text-center" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase; width:120px;">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($list_ralat_news as $news)
                                            <tr style="border-color:#e5e7eb;">
                                                <td class="ps-4">{{ \Carbon\Carbon::parse($news->published_at)->format('j M Y') }}</td>
                                                <td>{{ $news->title }}</td>
                                                <td class="pe-4 text-center"><a href="{{ route('news.show', $news->id) }}" class="btn btn-sm btn-primary rounded-8 px-3">Selanjutnya</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-4 text-muted small">Tiada makluman / ralat.</div>
                        @endif
                    </div>
                </div>

                {{-- TAB: Kebenaran Khas — same condition as auth/show --}}
                @if (
                    !$tender->matchCidbCodesInverse(Auth::user()->vendor_id) &&
                    $tender->matchCidbGrade(Auth::user()->vendor_id) &&
                    $tender->attendVisits(Auth::user()->vendor_id) &&
                    $tender->attendBriefing(Auth::user()->vendor_id)
                )
                    <div class="tab-pane fade" id="vt-exception" role="tabpanel">
                        @if ($tender->canException())
                            <div class="vendor-tender-card mb-3">
                                <div class="vendor-tender-card-header">
                                    <div class="header-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m16.555 3.843l3.602 3.602a2.877 2.877 0 0 1 0 4.069l-2.643 2.643a2.877 2.877 0 0 1-4.069 0l-.301-.301l-6.558 6.558a2 2 0 0 1-1.239.578L5.172 21H4a1 1 0 0 1-.993-.883L3 20v-1.172a2 2 0 0 1 .467-1.284l.119-.13L4 17h2v-2h2v-2l2.144-2.144l-.301-.301a2.877 2.877 0 0 1 0-4.069l2.643-2.643a2.877 2.877 0 0 1 4.069 0M15 9h.01"/></svg></div>
                                    <h6>Status Kebenaran Khas</h6>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0" style="font-size:0.82rem;">
                                        <thead style="background:#f8fafc;">
                                            <tr>
                                                <th class="py-3 ps-4" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase;">Tarikh Permohonan</th>
                                                <th class="py-3" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase;">Tajuk</th>
                                                <th class="py-3 pe-4" style="border-color:#e5e7eb; font-size:0.68rem; color:#6b7280; text-transform:uppercase;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($exception)
                                                <tr style="border-color:#e5e7eb;">
                                                    <td class="ps-4">{{ $exception->updated_at ? \Carbon\Carbon::parse($exception->updated_at)->format('d/m/Y') : \Carbon\Carbon::parse($exception->created_at)->format('d/m/Y') }}</td>
                                                    <td>{{ $exception->files[0]->label ?? '' }}</td>
                                                    <td class="pe-4">
                                                        @if ($exception->status == 2)
                                                            <b>{{ $exception->getStatus() }}</b><br>
                                                            @if ($exception->rejection_reason) Catatan: {{ $exception->rejection_reason }} @endif
                                                            @if ($exception->rejection_template_id)
                                                                <ol>
                                                                    @foreach (json_decode($exception->rejection_template_id, true) as $reject_id)
                                                                        @foreach ($templates as $template)
                                                                            @if ($template['id'] == $reject_id)
                                                                                <li style="text-decoration:underline;">{{ $template['title'] }}</li>
                                                                                {!! $template['content'] !!}
                                                                            @endif
                                                                        @endforeach
                                                                    @endforeach
                                                                </ol>
                                                            @endif
                                                        @else
                                                            <b>{{ $exception->getStatus() }}</b>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @else
                                                <tr><td colspan="3" class="text-center text-muted py-3 ps-4">Tiada Surat Kebenaran Khas</td></tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @if (!$exception || $exception->status == 2)
                                <div class="vendor-tender-card">
                                    <div class="vendor-tender-card-header">
                                        <div class="header-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m16.555 3.843l3.602 3.602a2.877 2.877 0 0 1 0 4.069l-2.643 2.643a2.877 2.877 0 0 1-4.069 0l-.301-.301l-6.558 6.558a2 2 0 0 1-1.239.578L5.172 21H4a1 1 0 0 1-.993-.883L3 20v-1.172a2 2 0 0 1 .467-1.284l.119-.13L4 17h2v-2h2v-2l2.144-2.144l-.301-.301a2.877 2.877 0 0 1 0-4.069l2.643-2.643a2.877 2.877 0 0 1 4.069 0M15 9h.01"/></svg></div>
                                        <h6>Hantar Permohonan Kebenaran Khas</h6>
                                    </div>
                                    <div class="p-4">
                                        <form action="{{ route('tender.store.exception') }}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Surat Kebenaran Khas <sup class="text-danger">*</sup></label>
                                                <input type="file" name="exception_letter" class="form-control" required>
                                            </div>
                                            <input type="hidden" name="tender_id" value="{{ $tender->id }}">
                                            <div class="text-end">
                                                <button type="submit" class="btn-form btn-form-primary confirm">Hantar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info">Kebenaran Khas tidak dibenarkan bagi tender/sebut harga ini.</div>
                        @endif
                    </div>
                @endif

                {{-- TAB: Pegawai Bertanggungjawab --}}
                <div class="tab-pane fade" id="vt-officer" role="tabpanel">
                    <div class="vendor-tender-card">
                        <div class="vendor-tender-card-header">
                            <div class="header-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
                            <h6>Pegawai Bertanggungjawab</h6>
                        </div>
                        @if ($tender->hasOfficer())
                            <table class="info-table">
                                <tr><th colspan="2" class="text-center" style="background:#f8fafc;">Pegawai Bertanggungjawab 1</th><th colspan="2" class="text-center" style="background:#f8fafc;">Pegawai Bertanggungjawab 2</th></tr>
                                <tr><th>Nama</th><td>{{ data_get($tender, 'creator,name') }}</td><th>Nama</th><td>{{ data_get($tender, 'officer,name') }}</td></tr>
                                <tr><th>E-mel</th><td>{{ data_get($tender, 'creator,email') }}</td><th>E-mel</th><td>{{ data_get($tender, 'officer,email') }}</td></tr>
                                <tr><th>No. Tel</th><td>{{ data_get($tender, 'creator,tel') }}</td><th>No. Tel</th><td>{{ data_get($tender, 'officer,tel') }}</td></tr>
                                <tr><th>Jabatan</th><td>{{ data_get($tender, 'creator,department') }}</td><th>Jabatan</th><td>{{ data_get($tender, 'officer,department') }}</td></tr>
                            </table>
                        @else
                            <table class="info-table">
                                <tr><th>Nama</th><td>{{ optional($tender->creator)->name ?? '-' }}</td></tr>
                                <tr><th>E-mel</th><td>{{ optional($tender->creator)->email ?? '-' }}</td></tr>
                                <tr><th>No. Tel</th><td>{{ optional($tender->creator)->tel ?? '-' }}</td></tr>
                                <tr><th>Jabatan</th><td>{{ optional($tender->creator)->department ?? '-' }}</td></tr>
                            </table>
                        @endif
                    </div>
                </div>

                {{-- TAB: Penilaian Prestasi — only if tender_winner exists --}}
                @if (isset($tender_winner->vendor))
                    <div class="tab-pane fade" id="vt-penilaian" role="tabpanel">
                        <div class="vendor-tender-card">
                            <div class="vendor-tender-card-header">
                                <div class="header-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 13a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1zm12-4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1zM9 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1zM4 20h14"/></svg></div>
                                <h6>Penilaian Prestasi Syarikat</h6>
                            </div>
                            <div class="p-4">
                                @if (count($errors) > 0)
                                    <div class="alert alert-danger mb-3">
                                        <strong>Amaran!</strong>
                                        <ul class="mb-0 mt-2">
                                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                                        </ul>
                                    </div>
                                @endif
                                @include('tenders.petender-performance.form')
                                @include('tenders.petender-performance.table')
                            </div>
                        </div>
                    </div>
                @endif

            </div>{{-- /.tab-content --}}

            <div class="mt-3">
                <a href="{{ url('dashboard') }}" class="btn-form btn-form-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Kembali ke Dashboard
                </a>
            </div>

        </div>{{-- /.col-lg-9 --}}

    </div>{{-- /.row --}}

@endsection

@section('scripts')
@if (count($tender->siteVisits) > 0 && Auth::check() && Auth::user()->vendor_id)
<script>
(function () {
    var currentVisitId = null;
    var csrfToken = document.querySelector('meta[name="_token"]')?.getAttribute('content') || '';

    function emptyRow() {
        return '<tr class="wakil-row">' +
            '<td><input type="text" class="form-control form-control-sm rep-ic" maxlength="32" placeholder="No. IC"></td>' +
            '<td><input type="text" class="form-control form-control-sm rep-name" maxlength="255" placeholder="Nama"></td>' +
            '<td class="text-center"><input type="checkbox" class="form-check-input rep-attended"></td>' +
            '<td class="text-center"><button type="button" class="btn btn-sm btn-link text-danger p-0 btn-remove-rep" title="Buang">&times;</button></td>' +
            '</tr>';
    }

    function fillRows(reps) {
        var $tbody = $('#wakilLawatanRows');
        $tbody.empty();
        if (!reps || !reps.length) {
            $tbody.append(emptyRow());
            $tbody.append(emptyRow());
            return;
        }
        reps.forEach(function (rep) {
            var $row = $(emptyRow());
            $row.find('.rep-ic').val(rep.ic_no || '');
            $row.find('.rep-name').val(rep.name || '');
            $row.find('.rep-attended').prop('checked', !!rep.attended);
            $tbody.append($row);
        });
        if (reps.length < 2) {
            $tbody.append(emptyRow());
        }
    }

    $('.btn-wakil-lawatan').on('click', function () {
        currentVisitId = $(this).data('visit-id');
        $('#wakilLawatanVisitInfo').text($(this).data('visit-label'));
        $('#wakilLawatanError, #wakilLawatanSuccess').addClass('d-none').text('');

        $.get('{{ url('/visits') }}/' + currentVisitId + '/representatives', function (reps) {
            fillRows(reps);
        }).fail(function () {
            fillRows([]);
        });
    });

    $('#wakilLawatanAddRow').on('click', function () {
        $('#wakilLawatanRows').append(emptyRow());
    });

    $(document).on('click', '.btn-remove-rep', function () {
        if ($('#wakilLawatanRows .wakil-row').length > 1) {
            $(this).closest('tr').remove();
        }
    });

    $('#wakilLawatanSave').on('click', function () {
        if (!currentVisitId) return;

        var reps = [];
        $('#wakilLawatanRows .wakil-row').each(function () {
            var ic = $(this).find('.rep-ic').val().trim();
            var name = $(this).find('.rep-name').val().trim();
            var attended = $(this).find('.rep-attended').is(':checked');
            if (ic || name) {
                reps.push({ ic_no: ic, name: name, attended: attended ? 1 : 0 });
            }
        });

        var $btn = $(this).prop('disabled', true);
        $('#wakilLawatanError, #wakilLawatanSuccess').addClass('d-none');

        $.ajax({
            url: '{{ url('/visits') }}/' + currentVisitId + '/representatives',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: { reps: reps, _token: csrfToken },
            success: function (res) {
                $('#wakilLawatanSuccess').removeClass('d-none').text(res.message || 'Berjaya disimpan.');
                setTimeout(function () { window.location.reload(); }, 800);
            },
            error: function (xhr) {
                var msg = xhr.responseJSON?.message || 'Gagal menyimpan. Sila cuba lagi.';
                $('#wakilLawatanError').removeClass('d-none').text(msg);
                $btn.prop('disabled', false);
            }
        });
    });
})();
</script>
@endif
@endsection
