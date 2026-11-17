@extends('layouts.tender_pdf')

@section('styles')
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        html,
        body {
            background: #fff;
            color: #111;
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 17mm 15mm;
        }

        .tender-print-page {
            margin: 0;
            color: #111;
            font-family: Arial, Helvetica, sans-serif;
            font-size: .9rem;
            width: 100%;
        }

        .tender-print-running-header,
        .tender-print-running-footer {
            color: #000;
            display: table;
            font-size: 7.5pt;
            left: 15mm;
            position: fixed;
            right: 15mm;
            width: auto;
        }

        .tender-print-running-header span,
        .tender-print-running-footer span {
            display: table-cell;
            width: 50%;
        }

        .tender-print-running-header span:first-child,
        .tender-print-running-footer span:first-child {
            padding-right: 5mm;
        }

        .tender-print-running-header span:last-child,
        .tender-print-running-footer span:last-child {
            text-align: right;
        }

        .tender-print-running-header {
            border-bottom: 1px solid #000;
            font-weight: 700;
            padding-bottom: 2mm;
            text-transform: uppercase;
            top: 5mm;
        }

        .tender-print-running-footer {
            border-top: 1px solid #000;
            bottom: 11mm;
            line-height: 1.3;
            padding-top: 1.5mm;
        }

        .tender-print-header {
            border-bottom: 1px solid #111;
            margin-bottom: 1.25rem;
            padding: .5rem 0 1rem;
            text-align: center;
        }

        .tender-print-brand-row {
            border-bottom: 1px solid #111;
            display: table;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .07em;
            margin-bottom: 1.05rem;
            padding-bottom: .45rem;
            text-transform: uppercase;
            width: 100%;
        }

        .tender-print-brand-row span {
            display: table-cell;
            vertical-align: middle;
            width: 50%;
        }

        .tender-print-document-type {
            text-align: right;
        }

        .tender-print-title-block {
            margin: .75rem auto .9rem;
            max-width: 700px;
        }

        .tender-print-eyebrow {
            font-size: .74rem;
            font-weight: 700;
            letter-spacing: .08em;
            margin-bottom: .45rem;
            text-transform: uppercase;
        }

        .tender-print-title-block h1 {
            color: #000;
            font-size: 1.35rem;
            font-weight: 700;
            line-height: 1.3;
            margin: 0;
            overflow-wrap: anywhere;
            text-transform: uppercase;
        }

        .tender-print-tender-name {
            font-size: .95rem;
            font-weight: 700;
            line-height: 1.35;
            margin-top: .45rem;
            overflow-wrap: anywhere;
        }

        .tender-print-meta {
            border-collapse: collapse;
            display: table;
            margin: .8rem auto 0;
            max-width: 700px;
            text-align: left;
            width: 100%;
        }

        .tender-print-meta-item {
            border: 1px solid #111;
            display: table-cell;
            padding: .42rem .55rem;
            width: 33.333%;
            vertical-align: top;
        }

        .tender-print-meta-label {
            color: #333;
            display: block;
            font-size: .64rem;
            font-weight: 700;
            margin-bottom: .18rem;
            text-transform: uppercase;
        }

        .tender-print-meta-value {
            color: #111;
            display: block;
            font-size: .82rem;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .tender-print-caption {
            color: #333;
            font-size: .68rem;
            margin: .7rem 0 0;
        }

        .tender-print-section {
            margin: 1.15rem 0 1.35rem;
            break-inside: auto;
        }

        .tender-print-keep-together {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .tender-print-section-heading {
            border-bottom: 1px solid #111;
            margin: 0 0 .55rem;
            padding: 0 0 .32rem;
            break-after: avoid;
            page-break-after: avoid;
            page-break-inside: avoid;
        }

        .tender-print-section-number {
            display: inline-block;
            font-size: .9rem;
            font-weight: 700;
            margin-right: .35rem;
        }

        .tender-print-section-heading h2 {
            color: #000;
            display: inline;
            font-size: .92rem;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
        }

        .tender-print-table {
            border-collapse: collapse;
            font-size: .82rem;
            width: 100%;
        }

        .tender-print-table th,
        .tender-print-table td {
            border: 1px solid #222;
            padding: .38rem .48rem;
            text-align: left;
            vertical-align: top;
        }

        .tender-print-table th {
            background: #f2f2f2;
            color: #111;
            font-size: .8rem;
            font-weight: 700;
            width: 30%;
        }

        .tender-print-table thead th {
            background: #e6e6e6;
            color: #111;
            width: auto;
        }

        .tender-print-detail-table .bil-column {
            text-align: center;
            width: 7%;
        }

        .tender-print-detail-table .item-column {
            width: 29%;
        }

        .tender-print-table tbody tr:nth-child(even) td {
            background: #fff;
        }

        .tender-print-note {
            color: #333;
            font-size: .72rem;
        }

        .tender-print-terms {
            border: 1px solid #222;
            padding: .85rem 1rem;
            overflow-wrap: anywhere;
        }

        .tender-print-terms img {
            max-width: 100%;
            height: auto;
        }

        .tender-print-terms p,
        .tender-print-terms li,
        .tender-print-terms blockquote {
            orphans: 3;
            page-break-inside: avoid;
            widows: 3;
        }

        .tender-print-section-heading,
        .tender-print-table tr {
            page-break-inside: avoid;
        }

        .tender-print-table thead {
            display: table-header-group;
        }

        .tender-print-table tfoot {
            display: table-footer-group;
        }

        a {
            color: inherit;
            text-decoration: none;
        }
    </style>
@endsection

@section('content')
    @php
        $typeLabel = \App\Tender::$types[$tender->type] ?? 'Tender / Sebut Harga';
        $visitRows = $tender->siteVisits->sortBy('id');
        $hasCodes = count($tender->mof_codes) > 0 || count($tender->cidb_grades) > 0 || count($tender->cidb_codes) > 0;
        $hasTerms = trim(strip_tags((string) $tender->tender_rules)) !== '';
        $controlledDocs = $mejaTerkawal->documents();
        $tenderFiles = $showTenderFiles ? $tender->tenderFiles : collect();
        $sectionNo = 1;
    @endphp

    <div class="tender-print-running-header" aria-hidden="true">
        <span>Sistem Perolehan Negeri Selangor</span>
        <span>{{ $tender->displayNoTender() }}</span>
    </div>
    <div class="tender-print-running-footer" aria-hidden="true">
        <span>Maklumat Tender / Sebut Harga</span>
        <span>{{ now()->format('d/m/Y') }}</span>
    </div>

    <main class="tender-print-page">
        <header class="tender-print-header">
            <div class="tender-print-brand-row">
                <span>Kerajaan Negeri Selangor</span>
                <span class="tender-print-document-type">Dokumen Maklumat {{ $typeLabel }}</span>
            </div>

            <div class="tender-print-title-block">
                <div class="tender-print-eyebrow">Sistem Perolehan Negeri Selangor</div>
                <h1>Maklumat {{ $typeLabel }}</h1>
                <div class="tender-print-tender-name">{{ $tender->name }}</div>
            </div>

            <div class="tender-print-meta">
                <div class="tender-print-meta-item">
                    <span class="tender-print-meta-label">No. {{ $typeLabel }}</span>
                    <span class="tender-print-meta-value">{{ $tender->displayNoTender() }}</span>
                </div>
                <div class="tender-print-meta-item">
                    <span class="tender-print-meta-label">Agensi</span>
                    <span class="tender-print-meta-value">{{ $tender->tenderer->name ?? '-' }}</span>
                </div>
                <div class="tender-print-meta-item status">
                    <span class="tender-print-meta-label">Status Semasa</span>
                    <span class="tender-print-meta-value">{{ \App\Support\TenderProcessStatus::label((int) $tender->status_process_id) }}</span>
                </div>
            </div>
            <p class="tender-print-caption">Dijana pada {{ now()->format('d/m/Y H:i') }}. Dokumen ini memaparkan maklumat yang telah direkodkan setakat tarikh cetakan.</p>
        </header>

        <section class="tender-print-section tender-print-keep-together">
            <div class="tender-print-section-heading">
                <span class="tender-print-section-number">{{ sprintf('%d.0', $sectionNo++) }}</span>
                <h2>Maklumat {{ $typeLabel }}</h2>
            </div>
            @php $detailNo = 1; @endphp
            <table class="tender-print-table tender-print-detail-table">
                <thead>
                    <tr><th class="bil-column">BIL.</th><th class="item-column">PERKARA</th><th>KETERANGAN</th></tr>
                </thead>
                <tbody>
                    <tr><td class="bil-column">{{ $detailNo++ }}.</td><th class="item-column">Petender</th><td>{{ $tender->tenderer->name ?? '-' }}</td></tr>
                    <tr><td class="bil-column">{{ $detailNo++ }}.</td><th class="item-column">No. {{ $typeLabel }}</th><td>{{ $tender->displayNoTender() }}</td></tr>
                    <tr><td class="bil-column">{{ $detailNo++ }}.</td><th class="item-column">Tarikh Iklan</th><td>{{ $tender->tarikh_iklan_display ?: '-' }}</td></tr>
                    <tr><td class="bil-column">{{ $detailNo++ }}.</td><th class="item-column">Tarikh Jual</th><td>{{ $tender->tarikh_jual_display ?: '-' }}</td></tr>
                    <tr><td class="bil-column">{{ $detailNo++ }}.</td><th class="item-column">Tarikh Tutup</th><td>{{ $tender->tarikh_tutup_display ?: '-' }}</td></tr>
                    <tr><td class="bil-column">{{ $detailNo++ }}.</td><th class="item-column">Masa Tutup</th><td>{{ $tender->masa_tutup_display ?: '-' }}</td></tr>
                    <tr><td class="bil-column">{{ $detailNo++ }}.</td><th class="item-column">Tempat Hantar</th><td>{!! nl2br(e((string) $tender->submission_location_address)) ?: '-' !!}</td></tr>
                    @if ($tender->hasBriefing())
                        <tr>
                            <td class="bil-column">{{ $detailNo++ }}.</td><th class="item-column">Tarikh &amp; Masa Taklimat</th>
                            <td>{{ \Carbon\Carbon::parse($tender->briefing_datetime)->format('j M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="bil-column">{{ $detailNo++ }}.</td><th class="item-column">Alamat Taklimat</th>
                            <td>{!! nl2br(e((string) $tender->briefing_address)) !!} @if ($tender->briefing_required)<br><strong>Kehadiran taklimat diwajibkan.</strong>@endif</td>
                        </tr>
                    @endif
                    <tr><td class="bil-column">{{ $detailNo++ }}.</td><th class="item-column">Kebenaran Khas</th><td>{{ $tender->allow_exception ? 'Ya' : 'Tidak' }}</td></tr>
                    @if ($tender->only_bumiputera)
                        <tr><td class="bil-column">{{ $detailNo++ }}.</td><th class="item-column">Syarikat Bumiputera Sahaja</th><td>Ya</td></tr>
                    @endif
                    @if ($tender->only_selangor == 2)
                        <tr><td class="bil-column">{{ $detailNo++ }}.</td><th class="item-column">Syarikat Negeri</th><td>{{ strtoupper($tender->getNegeriList()) }} SAHAJA</td></tr>
                    @elseif ($tender->only_selangor == 3)
                        <tr><td class="bil-column">{{ $detailNo++ }}.</td><th class="item-column">Syarikat Negeri</th><td>SELURUH MALAYSIA</td></tr>
                    @endif
                    @if ($tender->district_id != null && $tender->district_id > 0)
                        <tr><td class="bil-column">{{ $detailNo++ }}.</td><th class="item-column">Syarikat Daerah</th><td>{{ strtoupper(\App\Vendor::$districts[$tender->district_id] ?? '-') }} SAHAJA</td></tr>
                    @elseif ($tender->district_id == null && $tender->getDaerahListExist() && $tender->only_selangor != 3)
                        <tr><td class="bil-column">{{ $detailNo++ }}.</td><th class="item-column">Syarikat Daerah</th><td>{{ strtoupper($tender->getDaerahList()) }} SAHAJA</td></tr>
                    @elseif ($tender->district_id == null && $tender->district_list_rule === '[]' && $tender->only_selangor == 1)
                        <tr><td class="bil-column">{{ $detailNo++ }}.</td><th class="item-column">Syarikat Daerah</th><td>SELURUH SELANGOR</td></tr>
                    @endif
                    @if ($tender->hasDaerahLiputanProjek())
                        <tr><td class="bil-column">{{ $detailNo++ }}.</td><th class="item-column">Daerah Liputan Projek</th><td>{{ $tender->getDaerahLiputanProjek() }}</td></tr>
                    @endif
                    <tr><td class="bil-column">{{ $detailNo++ }}.</td><th class="item-column">Harga Dokumen</th><td>RM {{ number_format((float) $tender->price, 2) }}</td></tr>
                </tbody>
            </table>
        </section>

        @if ($hasTerms)
            <section class="tender-print-section">
                <div class="tender-print-section-heading">
                    <span class="tender-print-section-number">{{ sprintf('%d.0', $sectionNo++) }}</span>
                    <h2>Syarat {{ $typeLabel }}</h2>
                </div>
                <div class="tender-print-terms">{!! $tender->tender_rules !!}</div>
            </section>
        @endif

        @if ($visitRows->isNotEmpty())
            <section class="tender-print-section tender-print-keep-together">
                <div class="tender-print-section-heading">
                    <span class="tender-print-section-number">{{ sprintf('%d.0', $sectionNo++) }}</span>
                    <h2>Lawatan Tapak</h2>
                </div>
                <table class="tender-print-table">
                    <thead><tr><th>Bil.</th><th>Tempat Berkumpul</th><th>Alamat</th><th>Tarikh &amp; Waktu</th><th>Wajib Hadir</th></tr></thead>
                    <tbody>
                        @foreach ($visitRows as $index => $visit)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{!! nl2br(e((string) $visit->meetpoint)) !!}</td>
                                <td>{!! nl2br(e((string) $visit->address)) !!}</td>
                                <td>{{ $visit->datetime ? \Carbon\Carbon::parse($visit->datetime)->format('j M Y H:i') : '-' }}</td>
                                <td>{{ $visit->required ? 'Ya' : 'Tidak' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        @endif

        @if ($hasCodes)
            <section class="tender-print-section tender-print-keep-together">
                <div class="tender-print-section-heading">
                    <span class="tender-print-section-number">{{ sprintf('%d.0', $sectionNo++) }}</span>
                    <h2>Kod-kod Bidang</h2>
                </div>
                <table class="tender-print-table">
                    <tbody>
                        @if (count($tender->mof_codes) > 0)
                            <tr>
                                <th>Kod Bidang MOF</th>
                                <td>
                                    @foreach (tender_code_groups_display($tender->mof_code_groups) as $data)
                                        {!! implode('<br>' . tender_code_rule_html($data['inner_rule'], 'or') . '<br>', tender_vendor_codes($data['codes'], null)) !!}
                                        @if ($data['join_after'] !== null)<br><br>{!! tender_code_rule_html($data['join_after']) !!}<br><br>@endif
                                    @endforeach
                                </td>
                            </tr>
                        @endif
                        @if (count($tender->cidb_grades) > 0)
                            <tr><th>Gred CIDB</th><td><ul class="mb-0">@foreach ($tender->cidb_grades as $code)<li>@if ($code->code){!! tender_cidb_grade($code->code, null) !!}@else{{ $code->code_id }}@endif</li>@endforeach</ul></td></tr>
                        @endif
                        @if (count($tender->cidb_codes) > 0)
                            <tr>
                                <th>Bidang Pengkhususan CIDB</th>
                                <td>
                                    @foreach (tender_code_groups_display($tender->cidb_code_groups) as $data)
                                        {!! implode('<br>' . tender_code_rule_html($data['inner_rule']) . '<br>', tender_vendor_codes($data['codes'], null)) !!}
                                        @if ($data['join_after'] !== null)<br><br>{!! tender_code_rule_html($data['join_after'], 'or') !!}<br><br>@endif
                                    @endforeach
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                @if (count($tender->mof_codes) > 0 && (count($tender->cidb_grades) > 0 || count($tender->cidb_codes) > 0))
                    <p class="tender-print-note mt-2">Hubungan MOF/CIDB: {{ strtoupper((string) ($tender->mof_cidb_rule ?: 'or')) === 'AND' ? 'DAN' : 'ATAU' }}</p>
                @endif
            </section>
        @endif

        @if ($controlledDocs !== [])
            <section class="tender-print-section">
                <div class="tender-print-section-heading">
                    <span class="tender-print-section-number">{{ sprintf('%d.0', $sectionNo++) }}</span>
                    <h2>Dokumen Meja Terkawal</h2>
                </div>
                <table class="tender-print-table">
                    <thead><tr><th class="bil-column">BIL.</th><th>PERKARA</th><th>SAIZ</th><th>JENIS</th></tr></thead>
                    <tbody>
                        @foreach ($controlledDocs as $index => $document)
                            <tr><td class="bil-column">{{ $index + 1 }}.</td><td>{{ $document['label'] }}</td><td>{{ $document['size'] ?: '-' }}</td><td>{{ $document['type'] ?: '-' }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="tender-print-note mt-2">Senarai ini memaparkan dokumen yang tersedia; kandungan fail tidak digabungkan dalam PDF ini.</p>
            </section>
        @endif

        @if ($tenderFiles->isNotEmpty())
            <section class="tender-print-section">
                <div class="tender-print-section-heading">
                    <span class="tender-print-section-number">{{ sprintf('%d.0', $sectionNo++) }}</span>
                    <h2>Dokumen {{ $typeLabel }}</h2>
                </div>
                <table class="tender-print-table">
                    <thead><tr><th class="bil-column">BIL.</th><th>PERKARA</th><th>SAIZ</th><th>JENIS</th></tr></thead>
                    <tbody>
                        @foreach ($tenderFiles as $index => $file)
                            <tr>
                                <td class="bil-column">{{ $index + 1 }}.</td>
                                <td>{{ method_exists($file, 'displayName') ? $file->displayName() : ($file->label ?: $file->name) }}</td>
                                <td>{{ $file->size ?: '-' }}</td>
                                <td>{{ $file->type ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        @endif

        <section class="tender-print-section tender-print-keep-together">
            <div class="tender-print-section-heading">
                <span class="tender-print-section-number">{{ sprintf('%d.0', $sectionNo++) }}</span>
                <h2>Pegawai Bertanggungjawab</h2>
            </div>
            @include('tenders._pegawai_bertanggungjawab_table', [
                'pegawaiDisplay' => $pegawaiDisplay,
                'tableClass' => 'tender-print-table',
                'headerBg' => 'background:#f2f2f2;',
            ])
        </section>

    </main>
@endsection
