@extends('layouts.tender_pdf')

@section('styles')
    <style>
        @page {
            size: A4 portrait;
            margin: 16mm 16mm 20mm 16mm;
        }

        body {
            margin: 0;
            padding: 0;
            background: #fff;
            color: #1f2937;
            font-family: Helvetica, Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.45;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* Running footer: fixed elements sit relative to the page margins, so a negative bottom lands in the margin */
        .tp-footer {
            position: fixed;
            bottom: -12mm;
            left: 0;
            width: 178mm;
            border-top: 0.5pt solid #cbd5e1;
            padding-top: 2mm;
            font-size: 7pt;
            color: #6b7280;
        }

        .tp-footer table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .tp-footer td {
            padding: 0;
            vertical-align: top;
            overflow-wrap: anywhere;
        }

        .tp-footer .tp-footer-page {
            width: 24mm;
            text-align: right;
            white-space: nowrap;
            color: #1e293b;
        }

        .tp-page-number:after {
            content: counter(page);
        }

        /* Letterhead */
        .tp-letterhead {
            width: 100%;
            border-collapse: collapse;
        }

        .tp-letterhead td {
            padding: 0;
            vertical-align: middle;
        }

        .tp-letterhead .tp-crest {
            width: 13mm;
        }

        .tp-crest img {
            height: 15mm;
        }

        .tp-org {
            padding-left: 3mm !important;
        }

        .tp-org-state {
            font-size: 6.8pt;
            font-weight: bold;
            letter-spacing: 1.2pt;
            text-transform: uppercase;
            color: #6b7280;
        }

        .tp-org-system {
            margin-top: 0.8mm;
            font-size: 12pt;
            font-weight: bold;
            color: #1e293b;
        }

        .tp-doc {
            text-align: right;
        }

        .tp-doc-name {
            font-size: 8.5pt;
            font-weight: bold;
            letter-spacing: 1pt;
            text-transform: uppercase;
            color: #1e293b;
        }

        .tp-doc-meta {
            margin-top: 1mm;
            font-size: 7pt;
            color: #6b7280;
        }

        .tp-rule {
            margin: 4mm 0 7mm;
            border-top: 1.4pt solid #1e293b;
        }

        /* Title block: the tender name leads, agency and reference number support it */
        .tp-title {
            margin: 0 0 1.6mm;
            font-size: 15pt;
            font-weight: bold;
            line-height: 1.3;
            color: #0f172a;
        }

        .tp-agency {
            font-size: 10pt;
            color: #475569;
        }

        .tp-ref {
            margin-top: 3mm;
            font-size: 9.5pt;
            color: #0f172a;
            overflow-wrap: anywhere;
        }

        .tp-ref-label {
            padding-right: 2mm;
            font-size: 7pt;
            font-weight: bold;
            letter-spacing: 0.8pt;
            text-transform: uppercase;
            color: #64748b;
        }

        .tp-ref-value {
            font-weight: bold;
        }

        /* Sections: more space above a heading than below it */
        .tp-section {
            margin-top: 9mm;
        }

        .tp-keep {
            page-break-inside: avoid;
        }

        .tp-heading {
            margin: 0 0 3mm;
            padding-bottom: 1.6mm;
            border-bottom: 0.8pt solid #1e293b;
            font-size: 9.5pt;
            font-weight: bold;
            letter-spacing: 0.8pt;
            text-transform: uppercase;
            color: #1e293b;
            page-break-after: avoid;
        }

        .tp-subheading {
            margin: 6mm 0 2.4mm;
            font-size: 7.5pt;
            font-weight: bold;
            letter-spacing: 0.8pt;
            text-transform: uppercase;
            color: #1e293b;
            page-break-after: avoid;
        }

        /* Ledger: the one label / value pattern used across the document, divided by hairlines only */
        .tp-ledger {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .tp-ledger th,
        .tp-ledger td {
            padding: 2.4mm 0;
            border-bottom: 0.5pt solid #e2e8f0;
            text-align: left;
            vertical-align: top;
        }

        .tp-ledger th {
            width: 34%;
            padding-right: 4mm;
            font-size: 7.5pt;
            font-weight: bold;
            letter-spacing: 0.6pt;
            text-transform: uppercase;
            color: #64748b;
        }

        .tp-ledger td {
            color: #0f172a;
            overflow-wrap: anywhere;
        }

        .tp-ledger tr {
            page-break-inside: avoid;
        }

        .tp-ledger .tp-strong {
            font-size: 10pt;
            font-weight: bold;
        }

        /* Wajib hadir: a double-ruled stamp that stays clear on a black-and-white printer */
        .tp-stamp {
            display: inline;
            padding: 0.4mm 2mm;
            border: 2pt double #1e293b;
            color: #1e293b;
            font-size: 7pt;
            font-weight: bold;
            letter-spacing: 1pt;
            text-transform: uppercase;
        }

        .tp-ledger .tp-stamp {
            margin-left: 2.5mm;
        }

        /* Top block: title on the left, the closing date as a calendar page on the right */
        .tp-top {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .tp-top td {
            padding: 0;
            vertical-align: top;
        }

        .tp-top td.tp-top-main {
            width: 72%;
        }

        .tp-top td.tp-top-cal {
            width: 28%;
            padding-left: 8mm;
        }

        .tp-cal {
            border: 0.6pt solid #cbd5e1;
            text-align: center;
        }

        .tp-cal-head {
            padding: 1.6mm 0;
            background: #1e293b;
            color: #fff;
            font-size: 7pt;
            font-weight: bold;
            letter-spacing: 1pt;
            text-transform: uppercase;
        }

        .tp-cal-day {
            margin-top: 2.4mm;
            font-size: 28pt;
            font-weight: bold;
            line-height: 1.05;
            color: #0f172a;
        }

        .tp-cal-month {
            margin-top: 0.8mm;
            font-size: 7.5pt;
            font-weight: bold;
            letter-spacing: 0.8pt;
            text-transform: uppercase;
            color: #1e293b;
        }

        .tp-cal-time {
            margin-top: 2.4mm;
            padding: 1.8mm 0;
            border-top: 0.5pt solid #e2e8f0;
            font-size: 8.5pt;
            color: #475569;
        }

        /* Kelayakan: one full-width plain line under the top block, label in grey, value in bold black */
        .tp-reqs {
            margin-top: 4mm;
            padding-top: 2.4mm;
            border-top: 0.5pt solid #e2e8f0;
            font-size: 9pt;
            line-height: 1.6;
            color: #475569;
        }

        .tp-reqs b {
            color: #0f172a;
        }

        .tp-req-sep {
            padding: 0 2mm;
            color: #94a3b8;
        }

        /* Kod bidang: one table; ATAU / DAN connectors and the MOF / CIDB relation sit inside it as rows */
        .tp-codes {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            border: 0.6pt solid #cbd5e1;
        }

        /* Header shows once only; DomPDF repeats a table-header-group on every page */
        .tp-codes thead {
            display: table-row-group;
        }

        .tp-codes thead th {
            padding: 2mm 3mm;
            background: #1e293b;
            color: #fff;
            font-size: 7pt;
            font-weight: bold;
            letter-spacing: 0.6pt;
            text-align: left;
            text-transform: uppercase;
        }

        /* Rows inside one body (MOF, Gred CIDB, CIDB) have no lines; a line closes each body */
        .tp-codes td {
            padding: 2mm 3mm;
            vertical-align: top;
            color: #0f172a;
            overflow-wrap: anywhere;
        }

        .tp-codes tr.tp-codes-end td {
            border-bottom: 0.5pt solid #cbd5e1;
        }

        .tp-codes tr {
            page-break-inside: avoid;
        }

        .tp-codes .tp-codes-body {
            width: 16%;
            font-size: 7pt;
            font-weight: bold;
            letter-spacing: 0.6pt;
            text-transform: uppercase;
            color: #64748b;
        }

        .tp-codes th.tp-codes-body {
            color: #fff;
        }

        .tp-codes .tp-codes-code {
            width: 13%;
            font-weight: bold;
            color: #1e293b;
        }

        .tp-codes th.tp-codes-code {
            color: #fff;
        }

        .tp-codes .tp-codes-gap {
            width: 3%;
        }

        /* Group panel: when a body has two or more groups, each group's rows form one light box over Kod and Bidang */
        .tp-codes tr.tp-grp td.tp-codes-code,
        .tp-codes tr.tp-grp td.tp-codes-name,
        .tp-codes tr.tp-grp td.tp-codes-opcell {
            background: #f8fafc;
        }

        .tp-codes tr.tp-grp td.tp-codes-code,
        .tp-codes tr.tp-grp td.tp-codes-opcell {
            border-left: 0.6pt solid #e2e8f0;
        }

        .tp-codes tr.tp-grp td.tp-codes-name,
        .tp-codes tr.tp-grp td.tp-codes-opcell {
            border-right: 0.6pt solid #e2e8f0;
        }

        .tp-codes tr.tp-grp-first td.tp-codes-code,
        .tp-codes tr.tp-grp-first td.tp-codes-name {
            border-top: 0.6pt solid #e2e8f0;
        }

        .tp-codes tr.tp-grp-last td.tp-codes-code,
        .tp-codes tr.tp-grp-last td.tp-codes-name {
            border-bottom: 0.6pt solid #e2e8f0;
        }

        .tp-codes tr.tp-codes-space td {
            height: 1.8mm;
            padding: 0;
            font-size: 0;
            line-height: 0;
        }

        .tp-codes i,
        .tp-pill i {
            font-style: normal;
        }

        /* ATAU / DAN between codes of one group: bold dark slate, centred over Kod and Bidang */
        .tp-codes tr.tp-codes-op td {
            padding: 0.4mm 3mm;
            text-align: center;
            font-size: 7.5pt;
            font-weight: bold;
            letter-spacing: 1.2pt;
            color: #1e293b;
        }

        /* DAN / ATAU between groups: an outlined pill, the stronger break */
        .tp-codes tr.tp-codes-join td {
            padding: 1.6mm 3mm;
            text-align: center;
        }

        .tp-pill.tp-pill-outline {
            border: 0.8pt solid #1e293b;
            background: #fff;
            color: #1e293b;
        }

        .tp-codes tr.tp-codes-relation td {
            padding: 2.4mm 3mm;
            background: #f1f5f9;
            text-align: center;
        }

        .tp-codes-relation-label {
            margin-bottom: 1.2mm;
            font-size: 7pt;
            font-weight: bold;
            letter-spacing: 0.8pt;
            text-transform: uppercase;
            color: #64748b;
        }

        .tp-pill {
            display: inline-block;
            padding: 0.9mm 3.4mm;
            border-radius: 2pt;
            background: #1e293b;
            color: #fff;
            font-size: 7pt;
            font-weight: bold;
            letter-spacing: 1pt;
        }


        /* Data tables: dark header row, hairline rows, no vertical rules */
        .tp-table {
            width: 100%;
            border-collapse: collapse;
        }

        .tp-table thead {
            display: table-row-group;
        }

        .tp-table thead th {
            padding: 2mm 3mm;
            background: #1e293b;
            color: #fff;
            font-size: 7pt;
            font-weight: bold;
            letter-spacing: 0.6pt;
            text-align: left;
            text-transform: uppercase;
        }

        .tp-table tbody td {
            padding: 2.4mm 3mm;
            border-bottom: 0.5pt solid #e2e8f0;
            text-align: left;
            vertical-align: top;
            color: #0f172a;
            overflow-wrap: anywhere;
        }

        .tp-table tr {
            page-break-inside: avoid;
        }

        .tp-table .tp-col-no {
            width: 9mm;
            text-align: center;
        }

        .tp-table .tp-col-center {
            text-align: center;
        }

        .tp-table .tp-time {
            color: #475569;
        }

        .tp-note {
            margin-top: 2mm;
            font-size: 7.5pt;
            color: #64748b;
        }

        /* Pegawai: two equal contacts side by side */
        .tp-contacts {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .tp-contacts td.tp-contact-cell {
            width: 50%;
            padding: 0 2mm 0 0;
            vertical-align: top;
        }

        .tp-contacts td.tp-contact-cell + td.tp-contact-cell {
            padding: 0 0 0 2mm;
        }

        /* Only one pegawai: the card takes the full width */
        .tp-contacts td.tp-contact-cell.tp-contact-solo {
            width: 100%;
            padding: 0;
        }

        .tp-contact {
            padding: 3.2mm 4mm;
            border: 0.6pt solid #cbd5e1;
        }

        /* Card top: user icon beside the name and jabatan; contact lines with icons below a hairline */
        .tp-contact-head {
            width: 100%;
            border-collapse: collapse;
        }

        .tp-contact-head td {
            padding: 0;
            vertical-align: middle;
        }

        .tp-contact-head td.tp-contact-avatar {
            width: 9mm;
        }

        .tp-contact-avatar img {
            width: 6.5mm;
            height: 6.5mm;
        }

        .tp-contact-name {
            font-size: 10.5pt;
            font-weight: bold;
            color: #0f172a;
            overflow-wrap: anywhere;
        }

        .tp-contact-dept {
            margin-top: 0.4mm;
            font-size: 8.5pt;
            color: #475569;
            overflow-wrap: anywhere;
        }

        .tp-contact-lines {
            width: 100%;
            margin-top: 2.6mm;
            border-collapse: collapse;
            border-top: 0.5pt solid #e2e8f0;
        }

        .tp-contact-lines td {
            padding: 1.2mm 0 0;
            vertical-align: middle;
            overflow-wrap: anywhere;
        }

        .tp-contact-lines td.tp-contact-key {
            width: 6.5mm;
        }

        .tp-contact-key img {
            width: 3.6mm;
            height: 3.6mm;
        }

        /* Syarat: agencies paste Word content, so fonts, colours, highlights and offsets are evened out */
        .tp-terms p,
        .tp-terms span,
        .tp-terms td,
        .tp-terms li,
        .tp-terms div,
        .tp-terms strong,
        .tp-terms b,
        .tp-terms em,
        .tp-terms u {
            font-family: Helvetica, Arial, sans-serif !important;
            font-size: 9pt !important;
            line-height: 1.5 !important;
            color: #1f2937 !important;
            background-color: transparent !important;
        }

        .tp-terms p {
            margin: 0 0 2mm 0 !important;
            text-indent: 0 !important;
        }

        .tp-terms table {
            max-width: 100%;
            margin: 1mm 0 3mm !important;
            border-collapse: collapse !important;
        }

        .tp-terms td {
            height: auto !important;
            padding: 1.2mm 2mm !important;
            vertical-align: top !important;
        }

        .tp-terms td p {
            margin: 0 !important;
        }

        .tp-terms img {
            max-width: 100%;
            height: auto;
        }
    </style>
@endsection

@section('content')
    @php
        $typeLabel = \App\Tender::$types[$tender->type] ?? 'Tender / Sebut Harga';
        $visitRows = $tender->siteVisits->sortBy('id')->values();
        $hasCodes = count($tender->mof_codes) > 0 || count($tender->cidb_grades) > 0 || count($tender->cidb_codes) > 0;
        $hasTerms = trim(strip_tags((string) $tender->tender_rules)) !== '';
        $controlledDocs = $mejaTerkawal->documents();
        $tenderFiles = $showTenderFiles ? $tender->tenderFiles : collect();
        $ralatNews = $tender->news()->wherePublish(1)->orderBy('published_at', 'asc')->get();
        $hasBriefing = $tender->hasBriefing();
        $pegawai1 = $pegawaiDisplay->pegawai1();
        $pegawai2 = $pegawaiDisplay->pegawai2();
        $closing = $tender->submission_datetime ? \Carbon\Carbon::parse($tender->submission_datetime) : null;

        // Company requirements; same conditions as the tender page
        $kelayakan = [
            ['label' => 'Syarikat Bumiputera Sahaja', 'value' => $tender->only_bumiputera ? 'Ya' : 'Tidak'],
            ['label' => 'Kebenaran Khas', 'value' => $tender->allow_exception ? 'Ya' : 'Tidak'],
        ];
        if ($tender->only_selangor == 2) {
            $kelayakan[] = ['label' => 'Syarikat Negeri', 'value' => strtoupper($tender->getNegeriList()) . ' SAHAJA'];
        } elseif ($tender->only_selangor == 3) {
            $kelayakan[] = ['label' => 'Syarikat Negeri', 'value' => 'SELURUH MALAYSIA'];
        }
        if ($tender->district_id != null && $tender->district_id > 0) {
            $kelayakan[] = ['label' => 'Syarikat Daerah', 'value' => strtoupper(\App\Vendor::$districts[$tender->district_id] ?? '-') . ' SAHAJA'];
        } elseif ($tender->district_id == null && $tender->getDaerahListExist() && $tender->only_selangor != 3) {
            $kelayakan[] = ['label' => 'Syarikat Daerah', 'value' => strtoupper($tender->getDaerahList()) . ' SAHAJA'];
        } elseif ($tender->district_id == null && $tender->district_list_rule === '[]' && $tender->only_selangor == 1) {
            $kelayakan[] = ['label' => 'Syarikat Daerah', 'value' => 'SELURUH SELANGOR'];
        }
        if ($tender->hasDaerahLiputanProjek()) {
            $kelayakan[] = ['label' => 'Daerah Liputan Projek', 'value' => $tender->getDaerahLiputanProjek()];
        }

        // Code names keyed by code id, shown beside each code
        $mofNames = $tender->mof_codes->pluck('code.name', 'code_id');
        $cidbNames = $tender->cidb_codes->pluck('code.name', 'code_id');
    @endphp

    <div class="tp-footer">
        <table>
            <tr>
                <td>Maklumat {{ $typeLabel }} &middot; {{ $tender->displayNoTender() }} &middot; Sistem Perolehan Negeri Selangor</td>
                <td class="tp-footer-page">Muka surat <span class="tp-page-number"></span></td>
            </tr>
        </table>
    </div>

    {{-- Letterhead --}}
    <table class="tp-letterhead">
        <tr>
            <td class="tp-crest"><img src="{{ public_path('images/jata_selangor.png') }}" alt="Jata Negeri Selangor"></td>
            <td class="tp-org">
                <div class="tp-org-state">Kerajaan Negeri Selangor</div>
                <div class="tp-org-system">Sistem Perolehan Negeri Selangor</div>
            </td>
            <td class="tp-doc">
                <div class="tp-doc-name">Maklumat {{ $typeLabel }}</div>
                <div class="tp-doc-meta">Dijana pada {{ now()->format('d/m/Y, h:i A') }}</div>
            </td>
        </tr>
    </table>
    <div class="tp-rule"></div>

    {{-- Title, with the closing date as a calendar page on the right --}}
    <table class="tp-top">
        <tr>
            <td class="tp-top-main">
                <div class="tp-title">{{ $tender->name }}</div>
                <div class="tp-agency">{{ $tender->tenderer->name ?? '-' }}</div>
                <div class="tp-ref"><span class="tp-ref-label">No. {{ $typeLabel }}</span><span class="tp-ref-value">{{ $tender->displayNoTender() }}</span></div>
            </td>
            <td class="tp-top-cal">
                <div class="tp-cal">
                    <div class="tp-cal-head">Tarikh Tutup</div>
                    <div class="tp-cal-day">{{ $closing ? $closing->format('j') : '-' }}</div>
                    <div class="tp-cal-month">{{ $closing ? $closing->format('F Y') : '' }}</div>
                    <div class="tp-cal-time">Jam {{ $tender->masa_tutup_display ?: '-' }}</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- Kelayakan on its own full-width line below the title and the calendar --}}
    <div class="tp-reqs">
        @foreach ($kelayakan as $item)
            <span>{{ $item['label'] }}: <b>{{ $item['value'] }}</b></span>@if (! $loop->last)<span class="tp-req-sep">&middot;</span>@endif
        @endforeach
    </div>

    {{-- Advertising period, document price and where to submit --}}
    <div class="tp-section tp-keep">
        <div class="tp-heading">Maklumat Iklan dan Penyerahan</div>
        <table class="tp-ledger">
            <tr><th>Tempoh Iklan</th><td>{{ $tender->tarikh_iklan_display ?: '-' }}</td></tr>
            <tr><th>Tempoh Jualan Dokumen</th><td>{{ $tender->tarikh_jual_display ?: '-' }}</td></tr>
            <tr><th>Harga Dokumen</th><td class="tp-strong">RM {{ number_format((float) $tender->price, 2) }}</td></tr>
            <tr><th>Tempat Hantar</th><td>{!! nl2br(e((string) $tender->submission_location_address)) ?: '-' !!}</td></tr>
        </table>
    </div>

    {{-- Kod bidang: one table that flows on from the section above, splitting between rows when needed --}}
    @if ($hasCodes)
        <div class="tp-section">
            <div class="tp-heading">Kod Bidang</div>
            <table class="tp-codes">
                <thead><tr><th class="tp-codes-body">Badan</th><th class="tp-codes-code">Kod</th><th>Bidang</th><th class="tp-codes-gap"></th></tr></thead>
                <tbody>
                    @if (count($tender->mof_codes) > 0)
                        @include('tenders._print_code_rows', ['label' => 'MOF', 'groups' => tender_code_groups_display($tender->mof_code_groups_by_code), 'names' => $mofNames, 'innerDefault' => 'or', 'joinDefault' => 'and'])
                    @endif

                    @if (count($tender->mof_codes) > 0 && (count($tender->cidb_grades) > 0 || count($tender->cidb_codes) > 0))
                        <tr class="tp-codes-relation"><td colspan="4"><div class="tp-codes-relation-label">Hubungan MOF dan CIDB</div><span class="tp-pill">{{ strtoupper((string) ($tender->mof_cidb_rule ?: 'or')) === 'AND' ? 'DAN' : 'ATAU' }}</span></td></tr>
                    @endif

                    @foreach ($tender->cidb_grades as $grade)
                        <tr class="{{ $loop->last ? 'tp-codes-end' : '' }}">
                            <td class="tp-codes-body">{{ $loop->first ? 'Gred CIDB' : '' }}</td>
                            <td class="tp-codes-code">{{ $grade->code->code ?? $grade->code_id }}</td>
                            <td class="tp-codes-name">{{ $grade->code->name ?? '' }}</td>
                            <td></td>
                        </tr>
                    @endforeach

                    @if (count($tender->cidb_codes) > 0)
                        @include('tenders._print_code_rows', ['label' => 'CIDB', 'groups' => tender_code_groups_display($tender->cidb_code_groups_by_code), 'names' => $cidbNames, 'innerDefault' => 'and', 'joinDefault' => 'or'])
                    @endif
                </tbody>
            </table>
        </div>
    @endif

    {{-- Taklimat and lawatan tapak --}}
    @if ($hasBriefing || $visitRows->isNotEmpty())
        <div class="tp-section">
            <div class="tp-heading">{{ $hasBriefing && $visitRows->isNotEmpty() ? 'Taklimat & Lawatan Tapak' : ($hasBriefing ? 'Taklimat' : 'Lawatan Tapak') }}</div>

            @if ($hasBriefing)
                <table class="tp-ledger tp-keep">
                    <tr>
                        <th>Tarikh &amp; Masa Taklimat</th>
                        <td>
                            {{ \Carbon\Carbon::parse($tender->briefing_datetime)->format('j M Y, h:i A') }}
                            @if ($tender->briefing_required)<span class="tp-stamp">Wajib Hadir</span>@endif
                        </td>
                    </tr>
                    <tr><th>Alamat Taklimat</th><td>{!! nl2br(e((string) $tender->briefing_address)) !!}</td></tr>
                </table>
            @endif

            @if ($visitRows->isNotEmpty())
                @if ($hasBriefing)<div class="tp-subheading">Lawatan Tapak</div>@endif
                <table class="tp-table">
                    <thead><tr><th class="tp-col-no">Bil.</th><th>Tempat Berkumpul</th><th>Alamat</th><th class="tp-col-center">Tarikh &amp; Waktu</th><th class="tp-col-center">Wajib Hadir</th></tr></thead>
                    <tbody>
                        @foreach ($visitRows as $index => $visit)
                            <tr>
                                <td class="tp-col-no">{{ $index + 1 }}</td>
                                <td>{!! nl2br(e((string) $visit->meetpoint)) ?: '-' !!}</td>
                                <td>{!! nl2br(e((string) $visit->address)) !!}</td>
                                <td class="tp-col-center">
                                    @if ($visit->datetime)
                                        <div>{{ \Carbon\Carbon::parse($visit->datetime)->format('j M Y') }}</div>
                                        <div class="tp-time">{{ \Carbon\Carbon::parse($visit->datetime)->format('h:i A') }}</div>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="tp-col-center">
                                    @if ($visit->required)
                                        <span class="tp-stamp">Ya</span>
                                    @else
                                        Tidak
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endif

    @if ($hasTerms)
        <div class="tp-section">
            <div class="tp-heading">Syarat {{ $typeLabel }}</div>
            <div class="tp-terms">{!! syarat_html($tender->tender_rules) !!}</div>
        </div>
    @endif

    @if ($controlledDocs !== [])
        <div class="tp-section">
            <div class="tp-heading">Dokumen Meja Terkawal</div>
            <table class="tp-table">
                <thead><tr><th class="tp-col-no">Bil.</th><th>Perkara</th><th>Saiz</th><th>Jenis</th></tr></thead>
                <tbody>
                    @foreach ($controlledDocs as $index => $document)
                        <tr><td class="tp-col-no">{{ $index + 1 }}</td><td>{{ $document['label'] }}</td><td>{{ $document['size'] ?: '-' }}</td><td>{{ $document['type'] ?: '-' }}</td></tr>
                    @endforeach
                </tbody>
            </table>
            <p class="tp-note">Senarai ini memaparkan dokumen yang tersedia; kandungan fail tidak digabungkan dalam PDF ini.</p>
        </div>
    @endif

    @if ($tenderFiles->isNotEmpty())
        <div class="tp-section">
            <div class="tp-heading">Dokumen {{ $typeLabel }}</div>
            <table class="tp-table">
                <thead><tr><th class="tp-col-no">Bil.</th><th>Perkara</th><th>Saiz</th><th>Jenis</th></tr></thead>
                <tbody>
                    @foreach ($tenderFiles as $index => $file)
                        <tr>
                            <td class="tp-col-no">{{ $index + 1 }}</td>
                            <td>{{ method_exists($file, 'displayName') ? $file->displayName() : ($file->label ?: $file->name) }}</td>
                            <td>{{ $file->size ?: '-' }}</td>
                            <td>{{ $file->type ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if ($ralatNews->isNotEmpty())
        <div class="tp-section">
            <div class="tp-heading">Makluman / Ralat</div>
            <table class="tp-table">
                <thead><tr><th class="tp-col-no">Bil.</th><th style="width:28mm;">Tarikh</th><th>Tajuk</th></tr></thead>
                <tbody>
                    @foreach ($ralatNews as $index => $news)
                        <tr>
                            <td class="tp-col-no">{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($news->published_at)->format('j M Y') }}</td>
                            <td>{{ $news->title }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Pegawai bertanggungjawab --}}
    <div class="tp-section tp-keep">
        <div class="tp-heading">Pegawai Bertanggungjawab</div>
        <table class="tp-contacts">
            <tr>
                @foreach (array_filter([$pegawai1, $pegawai2]) as $index => $pegawai)
                    <td class="tp-contact-cell {{ $pegawai2 ? '' : 'tp-contact-solo' }}">
                        <div class="tp-contact">
                            <table class="tp-contact-head">
                                <tr>
                                    <td class="tp-contact-avatar"><img src="{{ public_path('images/print-icons/user.svg') }}" alt=""></td>
                                    <td>
                                        <div class="tp-contact-name">{{ $pegawai['nama'] }}</div>
                                        @if ($pegawai['jabatan'] !== '-')
                                            <div class="tp-contact-dept">{{ $pegawai['jabatan'] }}</div>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            <table class="tp-contact-lines">
                                <tr><td class="tp-contact-key"><img src="{{ public_path('images/print-icons/mail.svg') }}" alt="E-mel"></td><td>{{ $pegawai['emel'] }}</td></tr>
                                <tr><td class="tp-contact-key"><img src="{{ public_path('images/print-icons/phone.svg') }}" alt="No. Tel"></td><td>{{ $pegawai['tel'] }}</td></tr>
                            </table>
                        </div>
                    </td>
                @endforeach
            </tr>
        </table>
    </div>
@endsection
