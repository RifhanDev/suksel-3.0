<!DOCTYPE html><html lang="ms"><head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <title>Laporan Penilaian Harga - {{ $tender->no_tender ?? $tender->ref_number ?? '-' }}</title>
    {{-- Screen-only chrome (grey backdrop, Cetak/Tutup buttons) — excluded from paged.js's
         print-content processing via media="screen", so it never ends up inside a page. --}}
    <style media="screen">
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #e5e7eb;
        }
        .no-print {
            text-align: center;
            padding: 16px 0;
        }
        .no-print button {
            padding: 8px 24px;
            font-size: 13px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: #fff;
        }
        .no-print .btn-cetak {
            background: #2C3E9E;
        }
        .no-print .btn-tutup {
            background: #888;
            margin-left: 8px;
        }
        /* paged.js only sets page width/height itself — the "floating white sheet on a
           grey backdrop" look is ours to style, targeting the classes it actually generates. */
        .pagedjs_pages {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            padding: 24px 0;
        }
        .pagedjs_page {
            background: #fff;
            box-shadow: 0 0 6px rgba(0, 0, 0, 0.15);
        }
    </style>
    {{-- Report content — this is what paged.js reads (via the <template> below) and lays out
         into real, height-bounded A4 pages. @page here defines the page format once; every
         generated page obeys it automatically, including the repeating header/footer. --}}
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        strong {
            font-weight: 700;
        }
        @page {
            size: A4;
            /* Order: top right bottom left — extra left margin is the binding gutter. */
            margin: 26mm 18mm 20mm 32mm;
            @top-left {
                content: "Sulit";
                font-weight: 700;
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: 0.3px;
                border-bottom: 2.5px solid #000;
                padding-bottom: 6px;
                vertical-align: bottom;
                white-space: nowrap;
            }
            @top-center {
                content: "";
                border-bottom: 2.5px solid #000;
            }
            @top-right {
                content: "Laporan Jawatankuasa Penilaian Harga - {{ $tender->no_tender ?: ($tender->ref_number ?: '-') }}";
                font-weight: 700;
                font-size: 10px;
                text-transform: uppercase;
                letter-spacing: 0.3px;
                border-bottom: 2.5px solid #000;
                padding-bottom: 6px;
                vertical-align: bottom;
                white-space: nowrap;
            }
            @bottom-center {
                content: counter(page);
                font-size: 11px;
            }
            @bottom-right {
                content: "Sulit";
                font-weight: 700;
                font-size: 11px;
                text-transform: uppercase;
            }
        }
        /* paged.js splits the top margin box into 3 equal thirds — @top-right needs more
           than that to keep the report title on one line. */
        .pagedjs_margin-top {
            grid-template-columns: 15% 15% 70% !important;
        }
        /* Content otherwise starts flush against the header rule on every page. */
        .pagedjs_page_content {
            padding-top: 14px;
        }
        /* A heading landing first on a page would otherwise add its 30px on top of that. */
        .pagedjs_page_content > div > *:first-child {
            margin-top: 0;
        }
        /* Stops a sentence introducing a table from being stranded on the previous page. */
        .keep-with-next {
            break-after: avoid;
        }
        /* A section that must never be split across two pages (a table, a signature block).
           Does not guarantee it fits — only that it won't be cut mid-way. */
        .avoid-break {
            break-inside: avoid;
        }
        /* Forces whatever comes after it onto a fresh page, regardless of space remaining. */
        .force-new-page {
            break-before: page;
        }
        .doc-title-block {
            text-align: center;
            padding-bottom: 14px;
            border-bottom: 2.5px solid #000;
            margin-bottom: 22px;
        }
        .doc-title-block .doc-title {
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            line-height: 1.5;
        }
        .doc-title-block .doc-no-tender {
            font-weight: 700;
            font-size: 13px;
            margin-top: 16px;
            text-transform: uppercase;
        }
        .laporan-heading {
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            margin: 30px 0 12px;
            break-after: avoid;
        }
        /* Keeps a heading glued to its opening clause(s) — if they can't fit together at the
           bottom of a page, the whole group moves to the next one. */
        .laporan-heading-group {
            break-inside: avoid;
            margin-top: 30px;
        }
        .laporan-heading-group .laporan-heading {
            margin-top: 0;
        }
        /* A sub-heading like "5.2 ..." or "5.3 ..." — a sibling of X.1 under topic X, not a new
           top-level topic, so it matches .laporan-subitem's plain weight and indent exactly. */
        .laporan-subheading {
            margin: 18px 0 10px;
            padding-left: 20px;
            break-after: avoid;
        }
        .laporan-subitem {
            display: flex;
            gap: 8px;
            margin-bottom: 10px;
            padding-left: 20px;
            text-align: justify;
            line-height: 1.6;
        }
        .laporan-subitem-no {
            flex-shrink: 0;
        }
        .laporan-subsubitem {
            display: flex;
            gap: 8px;
            margin-bottom: 10px;
            padding-left: 48px;
            text-align: justify;
            line-height: 1.6;
        }
        .laporan-subsubitem-no {
            flex-shrink: 0;
        }
        /* Per-cell borders, not border-collapse — collapsed borders drop out when printing. */
        .laporan-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 8px;
            margin-bottom: 16px;
            font-size: 11.5px;
        }
        .laporan-table-caption {
            text-align: center;
            font-weight: 700;
            font-size: 11.5px;
            margin-bottom: 16px;
        }
        .laporan-table th,
        .laporan-table td {
            border-top: 1px solid #333;
            border-left: 1px solid #333;
            padding: 6px 8px;
            vertical-align: top;
            line-height: 1.5;
        }
        .laporan-table th:last-child,
        .laporan-table td:last-child {
            border-right: 1px solid #333;
        }
        .laporan-table tr:last-child > th,
        .laporan-table tr:last-child > td {
            border-bottom: 1px solid #333;
        }
        .laporan-table th {
            background: #d9d9d9;
            font-weight: 700;
            text-transform: uppercase;
            text-align: center;
            font-size: 11px;
        }
        .laporan-table td.col-bil {
            text-align: center;
        }
        .laporan-table td.col-harga {
            text-align: right;
        }
        .laporan-table td.col-kod {
            text-align: center;
            font-weight: 700;
        }
        /* The "Atau"/"Dan" operator that joins one kod bidang row to the next — it sits inside
           the row it follows, not in a column of its own. */
        .kod-bidang-operator {
            font-style: italic;
            font-weight: 700;
            text-align: center;
            margin-top: 8px;
        }
        .kod-bidang-operator--section {
            margin: 14px 0 18px;
            font-size: 12.5px;
        }
        .laporan-table td.col-perkara {
            font-weight: 700;
            background: #f2f2f2;
        }
        .jpt-members {
            padding-left: 48px;
            margin: 4px 0 14px;
        }
        .jpt-member {
            display: flex;
            margin-bottom: 14px;
            break-inside: avoid;
        }
        .jpt-member:last-child {
            margin-bottom: 0;
        }
        .jpt-member-letter {
            flex: 0 0 20px;
        }
        .jpt-member-role {
            flex: 0 0 74px;
        }
        .jpt-members--lantikan .jpt-member-role {
            font-weight: 700;
        }
        .jpt-member-colon {
            flex: 0 0 14px;
        }
        .jpt-member-details {
            flex: 1;
            line-height: 1.6;
        }
        .jpt-member-name {
            font-weight: 700;
        }
        .pengesahan-block {
            margin-top: 40px;
        }
        .pengesahan-block .jpt-member {
            margin-bottom: 34px;
        }
        .signature-line {
            width: 240px;
            height: 26px;
            border-bottom: 1px dotted #333;
            margin-bottom: 6px;
        }
        .pengesahan-name {
            font-weight: 700;
            text-transform: uppercase;
        }
        .laporan-roman-list {
            padding-left: 48px;
            margin-bottom: 10px;
        }
        .laporan-roman-item {
            display: flex;
            gap: 8px;
            margin-bottom: 8px;
            text-align: justify;
            line-height: 1.6;
        }
        .laporan-roman-item .roman {
            flex: 0 0 22px;
        }
    </style>
    {{-- data-pagedjs-ignore: paged.js unwraps @media print and applies it on screen too. --}}
    <style data-pagedjs-ignore>
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
    <script src="{{ asset('js/report-format-pdf/paged.polyfill.js') }}"></script></head><body>
    <div class="no-print">
        <button class="btn-cetak" onclick="window.print()">Cetak / Simpan PDF</button>
        <button class="btn-tutup" onclick="window.close()">Tutup</button>
    </div>
    {{-- SCAFFOLD: every value below is hardcoded from the sample report. Real data gets wired
         in section by section — nothing here reads from the tender yet. --}}
    {{-- paged.js reads this exact tag (data-ref="pagedjs-content") instead of sweeping the
         whole <body> in — keeps the Cetak/Tutup buttons above out of the paginated output. --}}
    <template data-ref="pagedjs-content">
        <div class="doc-title-block">
            <div class="doc-title">
                Laporan Jawatankuasa Penilaian Harga<br>
                {{ $tender->name ?? '-' }}
            </div>
            <div class="doc-no-tender">No. Tender: {{ $noTender }}</div>
        </div>
        <div class="laporan-heading">1. Tujuan</div>
        <div class="laporan-subitem">
            <span class="laporan-subitem-no">1.1</span>
            <span>Tujuan kertas ini adalah bagi melaporkan hasil penilaian harga bagi <strong>{{ $tender->name ?? '-' }}</strong> - <strong>No. Tender: {{ $noTender }}</strong>; dan</span>
        </div>
        <div class="laporan-subitem">
            <span class="laporan-subitem-no">1.2</span>
            <span>Laporan ini seterusnya akan mengesyorkan cadangan tawaran terbaik yang mematuhi semua syarat-syarat dalam dokumentasi harga untuk pertimbangan dan kelulusan Lembaga Perolehan Tender Negeri Selangor.</span>
        </div>
        <div class="laporan-heading">2. Latar Belakang/ Maklumat Petender</div>
        <table class="laporan-table avoid-break">
            <thead>
                <tr>
                    <th style="width:6%;">Bil.</th>
                    <th style="width:30%;">Perkara</th>
                    <th style="width:64%;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="col-bil">i.</td>
                    <td>Agensi Pelaksana</td>
                    <td>{{ $latarBelakang['agensi_pelaksana'] }}</td>
                </tr>
                <tr>
                    <td class="col-bil">ii.</td>
                    <td>Kaedah Perolehan</td>
                    <td>{{ $latarBelakang['kaedah_perolehan'] }}</td>
                </tr>
                <tr>
                    <td class="col-bil">iii.</td>
                    <td>Anggaran Harga Jabatan</td>
                    <td>{{ $latarBelakang['anggaran_jabatan'] }}</td>
                </tr>
                <tr>
                    <td class="col-bil">iv.</td>
                    <td>No. Tender</td>
                    <td>{{ $noTender }}</td>
                </tr>
                <tr>
                    <td class="col-bil">v.</td>
                    <td>Tarikh Iklan</td>
                    <td>{{ $latarBelakang['tarikh_iklan'] }}</td>
                </tr>
                <tr>
                    <td class="col-bil">vi.</td>
                    <td>Tarikh Jual</td>
                    <td>{{ $latarBelakang['tarikh_jual'] }}</td>
                </tr>
                <tr>
                    <td class="col-bil">vii.</td>
                    <td>Tarikh Tutup</td>
                    <td>{{ $latarBelakang['tarikh_tutup'] }}</td>
                </tr>
                <tr>
                    <td class="col-bil">viii.</td>
                    <td>Masa Tutup</td>
                    <td>{{ $latarBelakang['masa_tutup'] }}</td>
                </tr>
                <tr>
                    <td class="col-bil">ix.</td>
                    <td>Tempoh Sah Laku Tender</td>
                    <td>{{ $latarBelakang['tempoh_sah_laku'] }}</td>
                </tr>
                <tr>
                    <td class="col-bil">x.</td>
                    <td>Bilangan Dokumen Tender Yang Diterima Untuk Dinilai</td>
                    <td>{{ $latarBelakang['bilangan_dokumen'] }} Dokumen</td>
                </tr>
            </tbody>
        </table>
        <div class="laporan-heading force-new-page">3. Pelantikan Jawatankuasa</div>
        <div class="laporan-subitem">
            <span class="laporan-subitem-no">3.1</span>
            <span>Memo Jemputan Mesyuarat Jawatankuasa Penilaian Harga (JPH) telah diedarkan oleh {{ $latarBelakang['agensi_pelaksana'] }}@if ($tarikhPemakluman) bertarikh <strong>{{ $tarikhPemakluman }}</strong>@endif dan senarai jawatankuasa yang dilantik oleh YB Setiausaha Kerajaan Negeri Selangor seperti berikut:</span>
        </div>
        <div class="jpt-members jpt-members--lantikan">
            @forelse ($jphMembers as $member)
                <div class="jpt-member">
                    <div class="jpt-member-letter">{{ $member['letter'] }}</div>
                    <div class="jpt-member-role">{{ $member['peranan_label'] }}</div>
                    <div class="jpt-member-colon">:</div>
                    <div class="jpt-member-details">
                        <div class="jpt-member-name">{{ $member['name'] }}</div>
                        @if ($member['jawatan'])
                            <div>{{ $member['jawatan'] }}</div>
                        @endif
                        @if ($member['department'])
                            <div>{{ $member['department'] }}</div>
                        @endif
                        @if ($member['agensi'])
                            <div>{{ $member['agensi'] }}</div>
                        @endif
                    </div>
                </div>
            @empty
                <div>Tiada ahli Jawatankuasa Penilaian Harga direkodkan.</div>
            @endforelse
        </div>
        <div class="laporan-subitem">
            <span class="laporan-subitem-no">3.2</span>
            <span>Terma dan tanggungjawab JPH yang dipersetujui dan ditetapkan seperti berikut:</span>
        </div>
        <div class="laporan-roman-list">
            <div class="laporan-roman-item">
                <span class="roman">i.</span>
                <span>Menentukan metodologi penilaian harga;</span>
            </div>
            <div class="laporan-roman-item">
                <span class="roman">ii.</span>
                <span>Menjalankan penilaian harga tawaran bagi tender yang diterima;</span>
            </div>
            <div class="laporan-roman-item">
                <span class="roman">iii.</span>
                <span>Mengemukakan syor kepada Lembaga Perolehan Tender Selangor; dan</span>
            </div>
            <div class="laporan-roman-item">
                <span class="roman">iv.</span>
                <span>Mengemukakan laporan bertulis yang lengkap kepada Urus Setia Perolehan.</span>
            </div>
        </div>
        <div class="laporan-subitem">
            <span class="laporan-subitem-no">3.3</span>
            <span>Mesyuarat JPH telah diadakan sebanyak satu (1) kali pada {{ $tarikhMesyuarat ?? '-' }}.</span>
        </div>
        <div class="laporan-heading-group">
            <div class="laporan-heading">4. Syarat-Syarat Penyertaan Tender</div>
            <div class="laporan-subitem">
                <span class="laporan-subitem-no">4.1</span>
                <span>Berdaftar dengan Kementerian Kewangan dan Kerajaan Negeri Selangor melalui Unit Perancang Ekonomi Negeri.</span>
            </div>
        </div>
        <div class="laporan-subitem keep-with-next">
            <span class="laporan-subitem-no">4.2</span>
            <span>Petender wajib mempunyai Sijil Akaun Pendaftaran Syarikat dengan Kementerian Kewangan dan masih dalam tempoh sah laku di bawah kod bidang seperti berikut:</span>
        </div>
        <table class="laporan-table">
            <thead>
                <tr>
                    <th style="width:8%;">Bil.</th>
                    <th style="width:22%;">Kod Bidang MOF</th>
                    <th style="width:70%;">Keterangan Kod Bidang</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kodBidangMof as $row)
                    <tr>
                        <td class="col-bil">{{ $row['bil'] }}.</td>
                        <td class="col-kod">{{ $row['kod'] }}</td>
                        <td>
                            {{ $row['keterangan'] }}
                            @if ($row['operator'])
                                <div class="kod-bidang-operator">{{ $row['operator'] }}</div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="col-bil">Tiada kod bidang MOF direkodkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($gredCidb->isNotEmpty() || $kodBidangCidb->isNotEmpty())
            {{-- Connector and 4.3 break as one block; paged.js only walks back one level. --}}
            <div class="avoid-break keep-with-next">
                @if ($kodBidangMof->isNotEmpty())
                    <div class="kod-bidang-operator kod-bidang-operator--section">{{ $mofCidbRule }}</div>
                @endif
                <div class="laporan-subitem">
                    <span class="laporan-subitem-no">4.3</span>
                    <span>Petender juga wajib berdaftar dengan Lembaga Pembangunan Industri Pembinaan Malaysia (CIDB) dan masih dalam tempoh sah laku seperti berikut:</span>
                </div>
            </div>
            <table class="laporan-table">
                <thead>
                    <tr>
                        <th style="width:30%;">Perkara</th>
                        <th style="width:70%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($gredCidb->isNotEmpty())
                        <tr>
                            <td class="col-perkara">Gred CIDB</td>
                            <td>
                                @foreach ($gredCidb as $row)
                                    <div><strong>{{ $row['kod'] }}</strong> {{ $row['keterangan'] }}</div>
                                @endforeach
                            </td>
                        </tr>
                    @endif
                    @if ($kodBidangCidb->isNotEmpty())
                        <tr>
                            <td class="col-perkara">Bidang Pengkhususan CIDB</td>
                            <td>
                                @foreach ($kodBidangCidb as $row)
                                    <div><strong>{{ $row['kod'] }}</strong> {{ $row['keterangan'] }}</div>
                                    @if ($row['operator'])
                                        <div class="kod-bidang-operator">{{ $row['operator'] }}</div>
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endif
        <div class="laporan-heading-group">
            <div class="laporan-heading">5. Dokumen-Dokumen Untuk Cadangan Kewangan</div>
            <div class="laporan-subitem keep-with-next">
                <span class="laporan-subitem-no">5.1</span>
                <span>Petender dikehendaki mengemukakan dokumen cadangan kewangan seperti berikut:</span>
            </div>
        </div>
        <table class="laporan-table">
            <thead>
                <tr>
                    <th style="width:8%;">Bil.</th>
                    <th style="width:92%;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dokumenKewangan as $doc)
                    <tr>
                        <td class="col-bil">{{ $doc['bil'] }}.</td>
                        <td>{{ $doc['keterangan'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="col-bil">Tiada dokumen kewangan direkodkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="laporan-subitem keep-with-next">
            <span class="laporan-subitem-no">5.2</span>
            <span>Petender dikehendaki mengemukakan dokumen teknikal seperti berikut:</span>
        </div>
        <table class="laporan-table">
            <thead>
                <tr>
                    <th style="width:8%;">Bil.</th>
                    <th style="width:92%;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dokumenTeknikal as $doc)
                    <tr>
                        <td class="col-bil">{{ $doc['bil'] }}.</td>
                        <td>{{ $doc['keterangan'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="col-bil">Tiada dokumen teknikal direkodkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="laporan-subitem">
            <span class="laporan-subitem-no">5.3</span>
            <span>Dokumen-dokumen yang dihantar hendaklah lengkap merangkumi maklumat yang dinyatakan, memenuhi syarat dan mematuhi arahan yang dinyatakan seperti di dalam dokumen tender.</span>
        </div>
        <div class="laporan-heading-group">
            <div class="laporan-heading">6. Perihal Tender</div>
            <div class="laporan-subitem">
                <span class="laporan-subitem-no">6.1</span>
                <span>Sebanyak {{ $bilanganPetender }} petender telah mengambil bahagian dalam tender ini;</span>
            </div>
        </div>
        @if ($petenderTerendah && $petenderTertinggi && $petenderTerendah['vendor_id'] !== $petenderTertinggi['vendor_id'])
            <div class="laporan-subitem">
                <span class="laporan-subitem-no">6.2</span>
                <span>Berdasarkan kepada Jadual Tender, Petender <strong>Bil. {{ $petenderTerendah['no_petender'] }}</strong> menawarkan harga terendah iaitu, <strong>RM{{ $petenderTerendah['harga_display'] }}</strong> manakala petender <strong>Bil. {{ $petenderTertinggi['no_petender'] }}</strong> menawarkan harga tertinggi iaitu <strong>RM{{ $petenderTertinggi['harga_display'] }}</strong>; dan</span>
            </div>
        @elseif ($petenderTerendah)
            <div class="laporan-subitem">
                <span class="laporan-subitem-no">6.2</span>
                <span>Berdasarkan kepada Jadual Tender, Petender <strong>Bil. {{ $petenderTerendah['no_petender'] }}</strong> menawarkan harga iaitu, <strong>RM{{ $petenderTerendah['harga_display'] }}</strong>; dan</span>
            </div>
        @endif
        <div class="laporan-subitem keep-with-next">
            <span class="laporan-subitem-no">6.3</span>
            <span>Berikut dikemukakan jadual harga tawaran petender seperti di <strong>Jadual 1</strong>:</span>
        </div>
        <table class="laporan-table avoid-break">
            <thead>
                <tr>
                    <th style="width:15%;">Bil.</th>
                    <th style="width:40%;">No. Petender</th>
                    <th style="width:45%;">Harga Tawaran (RM)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($petenderHarga as $row)
                    <tr>
                        <td class="col-bil">{{ $row['bil'] }}.</td>
                        <td class="col-bil">{{ $row['no_petender'] }}</td>
                        <td class="col-harga">{{ $row['harga_display'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="col-bil">Tiada petender direkodkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="laporan-table-caption">Jadual 1: Jadual Harga Tawaran Tender</div>
        <div class="laporan-heading-group">
            <div class="laporan-heading">7. Terma Dan Rujukan Bagi Penilaian Harga</div>
            <div class="laporan-subitem">
                <span class="laporan-subitem-no">7.1</span>
                <span>JPH telah membuat keputusan membahagikan penilaian kepada tiga (3) peringkat iaitu:</span>
            </div>
        </div>
        <div class="laporan-subsubitem">
            <span class="laporan-subsubitem-no">7.1.1</span>
            <span><strong>Penilaian Peringkat Pertama:</strong> Penilaian dibuat berdasarkan <strong>Analisa Kesempurnaan Dan Kecukupan Dokumen</strong> dengan menyemak pematuhan kepada syarat-syarat mandatori yang telah ditetapkan seperti di dalam dokumen tender. Kegagalan untuk menepati syarat-syarat yang telah ditetapkan boleh menyebabkan petender tersebut tidak layak untuk dipertimbangkan ke peringkat seterusnya; dan</span>
        </div>
        <div class="laporan-subsubitem">
            <span class="laporan-subsubitem-no">7.1.2</span>
            <span><strong>Penilaian Peringkat Kedua:</strong> Penilaian dibuat untuk menilai <strong>Kemampuan Kewangan</strong> petender berdasarkan modal minimum sekurang-kurangnya 3% daripada Anggaran Harga Jabatan dengan mengambil kira nilai positif purata baki akhir bulan dalam penyata bulanan bank bagi tiga (3) bulan terakhir; dan</span>
        </div>
        <div class="laporan-subsubitem">
            <span class="laporan-subsubitem-no">7.1.3</span>
            <span><strong>Penilaian Peringkat Ketiga:</strong> Penilaian dibuat berdasarkan <strong>Semakan Harga</strong> dengan memastikan semua harga tawaran adalah betul. Jika terdapat kesilapan pengiraan boleh menyebabkan petender tersebut tidak dapat dipertimbangkan.</span>
        </div>
        <div class="laporan-subheading">7.2 <strong>Penilaian Peringkat Pertama</strong></div>
        <div class="laporan-subsubitem keep-with-next">
            <span class="laporan-subsubitem-no">7.2.1</span>
            <span>JPH telah membuat penilaian harga kepada <strong>{{ $bilPeringkat1 }} petender</strong> yang telah <strong>melepasi peringkat penilaian teknikal</strong>. Penilaian peringkat pertama dibuat ke atas <strong>Analisa Kesempurnaan Dan Kecukupan Dokumen</strong> dan hasil penilaian seperti di <strong>Jadual 2</strong> dan maklumat terperinci seperti pada <strong>Borang 1</strong>:</span>
        </div>
        <table class="laporan-table">
            <thead>
                <tr>
                    <th style="width:8%;">Bil.</th>
                    <th style="width:14%;">No. Petender</th>
                    <th style="width:20%;">Keputusan (Lulus/ Gagal)</th>
                    <th style="width:58%;">Ulasan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadual2 as $row)
                    <tr>
                        <td class="col-bil">{{ $row['bil'] }}.</td>
                        <td class="col-bil">{{ $row['no_petender'] }}</td>
                        <td class="col-bil">{{ $row['keputusan'] }}</td>
                        <td class="col-bil">{{ $row['ulasan'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="col-bil">Tiada petender dinilai.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="laporan-table-caption">Jadual 2: Keputusan Penilaian Peringkat Pertama</div>
        <div class="laporan-subsubitem">
            <span class="laporan-subsubitem-no">7.2.2</span>
            <span>{{ $catatanPeringkat1 ?: 'Sehubungan itu, JPH bersetuju untuk mengambil sebanyak ' . $bilPeringkat2 . ' petender sahaja untuk Penilaian Peringkat Kedua.' }}</span>
        </div>
        <div class="laporan-subheading">7.3 <strong>Penilaian Peringkat Kedua</strong></div>
        <div class="laporan-subsubitem keep-with-next">
            <span class="laporan-subsubitem-no">7.3.1</span>
            <span>JPH telah membuat Penilaian <strong>Kemampuan Kewangan</strong> dengan mengambil kira modal minimum 3% daripada anggaran harga jabatan iaitu sebanyak <strong>{{ $modalMinimum }}</strong> kepada <strong>{{ $bilPeringkat2 }} petender</strong> seperti di <strong>Jadual 3</strong> dan maklumat terperinci seperti pada <strong>Borang 2</strong>:</span>
        </div>
        <table class="laporan-table avoid-break">
            <thead>
                <tr>
                    <th style="width:8%;">Bil.</th>
                    <th style="width:16%;">No. Petender</th>
                    <th style="width:24%;">Keputusan (Lulus/ Gagal)</th>
                    <th style="width:52%;">Ulasan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadual3 as $row)
                    <tr>
                        <td class="col-bil">{{ $row['bil'] }}.</td>
                        <td class="col-bil">{{ $row['no_petender'] }}</td>
                        <td class="col-bil">{{ $row['keputusan'] }}</td>
                        <td class="col-bil">{{ $row['ulasan'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="col-bil">Tiada petender layak ke peringkat ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="laporan-table-caption">Jadual 3: Keputusan Penilaian Peringkat Kedua</div>
        <div class="laporan-subsubitem">
            <span class="laporan-subsubitem-no">7.3.2</span>
            <span>{{ $catatanPeringkat2 ?: 'Sehubungan itu, JPH bersetuju untuk mengambil sebanyak ' . $bilPeringkat3 . ' petender sahaja untuk Penilaian Peringkat Ketiga.' }}</span>
        </div>
        <div class="laporan-subheading">7.4 <strong>Penilaian Peringkat Ketiga</strong></div>
        <div class="laporan-subsubitem keep-with-next">
            <span class="laporan-subsubitem-no">7.4.1</span>
            <span>JPH telah membuat Penilaian berdasarkan <strong>Semakan Harga</strong> kepada <strong>{{ $bilPeringkat3 }}</strong> petender dan hasil Penilaian Peringkat ketiga seperti di <strong>Jadual 4</strong> dan maklumat terperinci seperti di <strong>Borang 3</strong>:</span>
        </div>
        <table class="laporan-table">
            <thead>
                <tr>
                    <th style="width:8%;">Bil.</th>
                    <th style="width:14%;">No. Petender</th>
                    <th style="width:18%;">Jumlah Tawaran Harga (RM)</th>
                    <th style="width:18%;">Keputusan (Lulus/ Gagal)</th>
                    <th style="width:42%;">Ulasan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadual4 as $row)
                    <tr>
                        <td class="col-bil">{{ $row['bil'] }}.</td>
                        <td class="col-bil">{{ $row['no_petender'] }}</td>
                        <td class="col-harga">{{ $row['harga_display'] }}</td>
                        <td class="col-bil">{{ $row['keputusan'] }}</td>
                        <td class="col-bil">{{ $row['ulasan'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="col-bil">Tiada petender layak ke peringkat ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="laporan-table-caption">Jadual 4 : Keputusan Penilaian Peringkat Ketiga</div>
        <div class="laporan-subsubitem">
            <span class="laporan-subsubitem-no">7.4.2</span>
            <span>{{ $catatanPeringkat3 ?: 'Sehubungan itu, JPH bersetuju untuk mengambil ' . $bilPeringkat3 . ' petender sahaja untuk ke Peringkat Pengesyoran.' }}</span>
        </div>
        <div class="laporan-heading-group">
            <div class="laporan-heading">8. Pengesyoran</div>
            <div class="laporan-subitem">
                <span class="laporan-subitem-no">8.1</span>
                <span>Dengan ini, JPH mengesyorkan <strong>petender bil. {{ $petenderDisyorkan['no_petender'] ?? '-' }} LAYAK DIPERTIMBANGKAN</strong> untuk melaksanakan <strong>{{ $tender->name ?? '-' }} - No. Tender: {{ $noTender }};</strong> untuk pertimbangan dan kelulusan Lembaga Perolehan Tender Negeri Selangor berdasarkan kepada justifikasi seperti berikut:</span>
            </div>
        </div>
        @forelse ($pengesyoranJustifikasi as $index => $justifikasi)
            <div class="laporan-subsubitem">
                <span class="laporan-subsubitem-no">8.1.{{ $index + 1 }}</span>
                <span>{{ $justifikasi }}</span>
            </div>
        @empty
            <div class="laporan-subsubitem">
                <span class="laporan-subsubitem-no">8.1.1</span>
                <span>Petender telah lulus dan melepasi semua peringkat penilaian harga.</span>
            </div>
        @endforelse
        @if ($petenderLayakLain->isNotEmpty())
            <div class="laporan-subitem keep-with-next">
                <span class="laporan-subitem-no">8.2</span>
                <span>JPH juga bersetuju mengesyorkan petender {{ $petenderLayakLain->pluck('no_petender')->implode(', ') }} kerana telah melepasi semua peringkat penilaian harga:-</span>
            </div>
            <table class="laporan-table avoid-break">
                <thead>
                    <tr>
                        <th style="width:8%;">Bil.</th>
                        <th style="width:16%;">No. Petender</th>
                        <th style="width:22%;">Jumlah Tawaran Harga (RM)</th>
                        <th style="width:14%;">BWAJ %</th>
                        <th style="width:40%;">Ulasan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($petenderLayakLain as $row)
                        <tr>
                            <td class="col-bil">{{ $row['bil'] }}.</td>
                            <td class="col-bil">{{ $row['no_petender'] }}</td>
                            <td class="col-harga">{{ $row['harga_display'] }}</td>
                            <td class="col-bil">{{ $row['bwaj_display'] }}</td>
                            <td class="col-bil">Layak Dipertimbangkan</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <div class="doc-title-block force-new-page">
            <div class="doc-title">
                Laporan Jawatankuasa Penilaian Harga<br>
                {{ $tender->name ?? '-' }}
            </div>
            <div class="doc-no-tender">No. Tender: {{ $noTender }}</div>
        </div>
        <div class="laporan-heading">9. Pengesahan</div>
        <div class="jpt-members pengesahan-block">
            @forelse ($jphMembers as $member)
                <div class="jpt-member">
                    <div class="jpt-member-role">{{ $member['peranan_label'] }}</div>
                    <div class="jpt-member-colon">:</div>
                    <div class="jpt-member-details">
                        <div class="signature-line"></div>
                        <div class="pengesahan-name">({{ $member['name'] }})</div>
                        @if ($member['jawatan'])
                            <div>{{ $member['jawatan'] }}</div>
                        @endif
                        @if ($member['department'])
                            <div>{{ $member['department'] }}</div>
                        @endif
                        @if ($member['agensi'])
                            <div>{{ $member['agensi'] }}</div>
                        @endif
                    </div>
                </div>
            @empty
                <div>Tiada ahli Jawatankuasa Penilaian Harga direkodkan.</div>
            @endforelse
            <div class="jpt-member">
                <div class="jpt-member-role">Tarikh</div>
                <div class="jpt-member-colon">:</div>
                <div class="jpt-member-details">{{ $tarikhMesyuarat ?? '-' }}</div>
            </div>
        </div>
    </template></body></html>