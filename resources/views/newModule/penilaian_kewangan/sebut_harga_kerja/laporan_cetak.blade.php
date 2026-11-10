<!DOCTYPE html><html lang="ms"><head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <title>Laporan Penilaian Sebut Harga - {{ $noTender }}</title>
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
        .no-print .btn-word {
            background: #2B579A;
            margin-left: 8px;
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
                content: "Laporan Jawatankuasa Penilaian-{{ $noTender }}";
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
        /* Keeps a heading glued to its opening clause(s) and the block after them — if they can't fit
           together at the bottom of a page, the whole group moves to the next one. */
        .laporan-heading-group {
            break-inside: avoid;
            break-after: avoid;
            margin-top: 30px;
        }
        .laporan-heading-group .laporan-heading {
            margin-top: 0;
        }
        /* A sub-heading like "7.2 ..." — a sibling of X.1 under topic X, not a new top-level
           topic, so it matches .laporan-subitem's plain weight and indent exactly. */
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
            margin-bottom: 8px;
            font-size: 11.5px;
        }
        .laporan-table-caption {
            text-align: center;
            font-weight: 700;
            font-size: 11.5px;
            margin-bottom: 36px;
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
        /* The DAN/ATAU row joining the CIDB and MOF rows in the kod bidang table. */
        .laporan-table td.col-rule {
            text-align: center;
            font-weight: 700;
            font-style: italic;
            background: #f9fafb;
            padding: 3px 8px;
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
        <button class="btn-word" onclick="window.location.search = (window.location.search ? window.location.search + '&' : '?') + 'format=word'">Muat Turun Word</button>
        <button class="btn-tutup" onclick="window.close()">Tutup</button>
    </div>
    {{-- SCAFFOLD: values not wired yet are hardcoded from the template. Real data gets wired in section by section. --}}
    {{-- paged.js reads this exact tag (data-ref="pagedjs-content") instead of sweeping the
         whole <body> in — keeps the Cetak/Tutup buttons above out of the paginated output. --}}
    <template data-ref="pagedjs-content">
        <div class="doc-title-block">
            <div class="doc-title">
                Laporan Jawatankuasa Penilaian Sebut Harga<br>
                {{ $tender->name ?? '-' }}<br>
                (Sebut Harga Kerja)
            </div>
            <div class="doc-no-tender">No. Sebut Harga: {{ $noTender }}</div>
        </div>
        <div class="laporan-heading">1. Tujuan</div>
        <div class="laporan-subitem">
            <span class="laporan-subitem-no">1.1</span>
            <span>Tujuan kertas ini adalah bagi melaporkan hasil penilaian bagi Sebut Harga <strong>{{ $tender->name ?? '-' }} - {{ $noTender }}</strong>; dan</span>
        </div>
        <div class="laporan-subitem">
            <span class="laporan-subitem-no">1.2</span>
            <span>Laporan ini seterusnya akan mengesyorkan cadangan tawaran terbaik yang mematuhi syarat-syarat dokumentasi harga dan teknikal untuk pertimbangan dan kelulusan Jawatankuasa Sebut Harga {{ $latarBelakang['agensi_pelaksana'] }}.</span>
        </div>
        <div class="laporan-heading">2. Latarbelakang/ Maklumat Sebut Harga</div>
        <div class="avoid-break">
            <table class="laporan-table">
                <thead>
                    <tr>
                        <th style="width:6%;">Bil.</th>
                        <th style="width:36%;">Perkara</th>
                        <th style="width:58%;">Keterangan</th>
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
                        <td>Anggaran Jabatan</td>
                        <td>{{ $latarBelakang['anggaran_jabatan'] }}</td>
                    </tr>
                    <tr>
                        <td class="col-bil">iv.</td>
                        <td>No. Sebut Harga</td>
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
                        <td>Tempoh Sah Laku Sebut Harga</td>
                        <td>{{ $latarBelakang['tempoh_sah_laku'] }}</td>
                    </tr>
                    <tr>
                        <td class="col-bil">x.</td>
                        <td>Bilangan Dokumen Sebut Harga Yang Diterima Untuk Dinilai</td>
                        <td>{{ $latarBelakang['bilangan_dokumen'] }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="laporan-table-caption">Jadual 1: Maklumat Sebut Harga</div>
        </div>
        <div class="laporan-heading-group">
            <div class="laporan-heading">3. Pelantikan Jawatankuasa</div>
            <div class="laporan-subitem">
                <span class="laporan-subitem-no">3.1</span>
                <span>Memo Jemputan Mesyuarat Jawatankuasa Penilaian (JP) telah diedarkan oleh {{ $latarBelakang['agensi_pelaksana'] }}@if ($tarikhPemakluman) bertarikh {{ $tarikhPemakluman }}@endif seperti berikut:</span>
            </div>
        </div>
        <div class="jpt-members jpt-members--lantikan">
            @forelse ($jpMembers as $member)
                <div class="jpt-member">
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
                        <div>Tarikh Lantikan: {{ $member['tarikh_lantikan'] }}</div>
                    </div>
                </div>
            @empty
                <div>Tiada ahli Jawatankuasa Penilaian direkodkan.</div>
            @endforelse
        </div>
        <div class="laporan-subitem">
            <span class="laporan-subitem-no">3.2</span>
            <span>Terma dan tanggungjawab JP telah dipersetujui dan ditetapkan seperti berikut:</span>
        </div>
        <div class="laporan-roman-list">
            <div class="laporan-roman-item">
                <span class="roman">i.</span>
                <span>Menentukan metodologi penilaian harga dan teknikal;</span>
            </div>
            <div class="laporan-roman-item">
                <span class="roman">ii.</span>
                <span>Menjalankan penilaian harga tawaran dan penilaian teknikal bagi sebut harga yang diterima;</span>
            </div>
            <div class="laporan-roman-item">
                <span class="roman">iii.</span>
                <span>Mengemukakan syor kepada Mesyuarat Jawatankuasa Sebut Harga {{ $latarBelakang['agensi_pelaksana'] }}; dan</span>
            </div>
            <div class="laporan-roman-item">
                <span class="roman">iv.</span>
                <span>Mengemukakan laporan bertulis yang lengkap kepada Urus Setia Perolehan.</span>
            </div>
        </div>
        <div class="laporan-subitem">
            <span class="laporan-subitem-no">3.3</span>
            <span>Mesyuarat JP telah diadakan sebanyak {{ $bilanganMesyuarat }} kali pada {{ $tarikhMesyuarat ?? '-' }}.</span>
        </div>
        <div class="laporan-heading-group">
            <div class="laporan-heading">4. Syarat-Syarat Penyertaan Sebut Harga</div>
            <div class="laporan-subitem">
                <span class="laporan-subitem-no">4.1</span>
                <span>Berdaftar dengan Kementerian Kewangan Malaysia dan Kerajaan Negeri Selangor (melalui Unit Perancang Ekonomi Negeri); dan</span>
            </div>
            <div class="laporan-subitem">
                <span class="laporan-subitem-no">4.2</span>
                <span>Hanya penyebut harga yang masih sah berdaftar dengan Kementerian Kewangan Malaysia di bawah kod bidang seperti di <strong>Jadual 2</strong> di bawah:</span>
            </div>
        </div>
        <div class="avoid-break">
            <table class="laporan-table">
                <thead>
                    <tr>
                        <th style="width:22%;">Kod Bidang</th>
                        <th style="width:78%;">Perkara</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($gredCidb->isEmpty() && $kodBidangCidb->isEmpty() && $kodBidangMof->isEmpty())
                        <tr>
                            <td colspan="2" class="col-bil">Tiada kod bidang direkodkan.</td>
                        </tr>
                    @endif
                    @if ($gredCidb->isNotEmpty() || $kodBidangCidb->isNotEmpty())
                        <tr>
                            <td class="col-kod">CIDB @if ($gredCidb->isNotEmpty())<br>({{ $gredCidb->join(', ') }})@endif</td>
                            <td>
                                @forelse ($kodBidangCidb as $row)
                                    <div><strong>{{ $row['kod'] }} - {{ $row['keterangan'] }}</strong> {{ $row['operator'] }}</div>
                                @empty
                                    <div>-</div>
                                @endforelse
                            </td>
                        </tr>
                        @if ($kodBidangMof->isNotEmpty())
                            <tr>
                                <td colspan="2" class="col-rule">{{ $mofCidbRule }}</td>
                            </tr>
                        @endif
                    @endif
                    @if ($kodBidangMof->isNotEmpty())
                        <tr>
                            <td class="col-kod">MOF</td>
                            <td>
                                @foreach ($kodBidangMof as $row)
                                    <div><strong>{{ $row['kod'] }} - {{ $row['keterangan'] }}</strong> {{ $row['operator'] }}</div>
                                @endforeach
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="laporan-table-caption">Jadual 2: Kod Bidang</div>
        </div>
        <div class="laporan-heading-group">
            <div class="laporan-heading">5. Dokumen-Dokumen Untuk Cadangan Kewangan Dan Teknikal</div>
            <div class="laporan-subitem">
                <span class="laporan-subitem-no">5.1</span>
                <span>Penyebut harga dikehendaki mengemukakan dokumen cadangan kewangan dan teknikal seperti berikut:</span>
            </div>
            <div class="laporan-subitem">
                <span class="laporan-subitem-no">5.2</span>
                <span><strong>Jadual 3</strong> di bawah adalah senarai dokumen yang perlu dikemukakan:</span>
            </div>
        </div>
        <div class="avoid-break">
            <table class="laporan-table">
                <thead>
                    <tr>
                        <th style="width:8%;">Bil.</th>
                        <th style="width:72%;">Keterangan</th>
                        <th style="width:20%;">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($senaraiDokumen as $dokumen)
                        <tr>
                            <td class="col-bil">{{ $dokumen['bil'] }}.</td>
                            <td>{{ $dokumen['keterangan'] }}</td>
                            <td class="col-bil">{{ $dokumen['lampiran'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="laporan-table-caption">Jadual 3: Senarai Dokumen</div>
        </div>
        <div class="laporan-subitem">
            <span class="laporan-subitem-no">5.3</span>
            <span>Dokumen-dokumen yang dihantar hendaklah lengkap daripada segi maklumat yang dinyatakan, memenuhi semua syarat dan arahan yang dinyatakan seperti di dalam dokumen Sebut Harga.</span>
        </div>
        <div class="laporan-heading-group">
            <div class="laporan-heading">6. Perihal Sebut Harga</div>
            <div class="laporan-subitem">
                <span class="laporan-subitem-no">6.1</span>
                <span>Sebanyak {{ $bilanganPetender }} penyebut harga telah mengambil bahagian dalam sebut harga ini.</span>
            </div>
            <div class="laporan-subitem">
                <span class="laporan-subitem-no">6.2</span>
                @if ($petenderTerendah && $petenderTertinggi)
                    <span>Berdasarkan kepada Jadual Sebut Harga, Penyebut Harga <strong>Bil. {{ $petenderTerendah->kod_pembekal ?: '-' }} menawarkan harga terendah, iaitu RM{{ number_format($petenderTerendah->harga_tawaran_resolved, 2) }}</strong> manakala Penyebut Harga <strong>Bil. {{ $petenderTertinggi->kod_pembekal ?: '-' }} menawarkan harga tertinggi, iaitu RM{{ number_format($petenderTertinggi->harga_tawaran_resolved, 2) }}</strong> dan Jadual Sebut Harga seperti di <strong>Jadual 4</strong>:</span>
                @elseif ($petenderTerendah)
                    <span>Berdasarkan kepada Jadual Sebut Harga, Penyebut Harga <strong>Bil. {{ $petenderTerendah->kod_pembekal ?: '-' }} menawarkan harga, iaitu RM{{ number_format($petenderTerendah->harga_tawaran_resolved, 2) }}</strong> dan Jadual Sebut Harga seperti di <strong>Jadual 4</strong>:</span>
                @else
                    <span>Jadual Sebut Harga adalah seperti di <strong>Jadual 4</strong>:</span>
                @endif
            </div>
        </div>
        <table class="laporan-table">
            <thead>
                <tr>
                    <th style="width:15%;">Bil.</th>
                    <th style="width:40%;">No. Penyebut Harga</th>
                    <th style="width:45%;">Harga Tawaran (RM)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadualHarga as $row)
                    <tr>
                        {{-- Widths repeat here because a table split across pages loses its thead. --}}
                        <td class="col-bil" style="width:15%;">{{ $row['bil'] }}.</td>
                        <td class="col-bil" style="width:40%;">{{ $row['kod_pembekal'] }}</td>
                        <td class="col-harga" style="width:45%;">{{ $row['harga'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="col-bil">Tiada penyebut harga yang menghantar dokumen.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="laporan-table-caption">Jadual 4: Jadual Sebut Harga</div>
        <div class="laporan-heading-group">
            <div class="laporan-heading">7. Terma Rujukan Bagi Penilaian</div>
            <div class="laporan-subitem">
                <span class="laporan-subitem-no">7.1</span>
                <span>JP telah melaksanakan penilaian melalui Lima (5) peringkat seperti berikut:</span>
            </div>
        </div>
        <div class="laporan-subsubitem">
            <span class="laporan-subsubitem-no">7.1.1</span>
            <span><strong>Penilaian Peringkat Pertama:</strong> Penilaian dibuat menggunakan <strong>Sistem Cut-off</strong> dan pembida terbaik mestilah memiliki harga dengan % BWAM dalam julat antara 0% hingga -20% dan BWAJ yang bersesuaian;</span>
        </div>
        <div class="laporan-subsubitem">
            <span class="laporan-subsubitem-no">7.1.2</span>
            <span><strong>Penilaian Peringkat Kedua:</strong> Penilaian dibuat berdasarkan <strong>Analisa Kesempurnaan Dan Kecukupan Dokumen</strong> dengan menyemak pematuhan kepada syarat-syarat mandatori yang telah ditetapkan seperti di dalam dokumen sebut harga. Kegagalan untuk menepati syarat-syarat yang telah ditetapkan boleh menyebabkan penyebut harga tersebut tidak layak untuk dipertimbangkan ke peringkat seterusnya;</span>
        </div>
        <div class="laporan-subsubitem">
            <span class="laporan-subsubitem-no">7.1.3</span>
            <span><strong>Penilaian Peringkat Ketiga:</strong> Penilaian dibuat untuk menilai <strong>Kemampuan Kewangan</strong> penyebut harga berdasarkan modal minimum sekurang-kurangnya 1.5% daripada Anggaran Harga Jabatan dengan mengambil kira nilai positif purata baki akhir bulan dalam penyata bulanan bank bagi tiga (3) bulan terakhir; dan</span>
        </div>
        <div class="laporan-subsubitem">
            <span class="laporan-subsubitem-no">7.1.4</span>
            <span><strong>Penilaian Peringkat Keempat:</strong> Penilaian dibuat berdasarkan <strong>Analisa Pengalaman Syarikat dan Senarai Kerja Yang Sedang Dilaksanakan</strong> dengan menyemak pematuhan kepada syarat-syarat mandatori yang telah ditetapkan seperti di dalam dokumen sebut harga. Kegagalan untuk menepati syarat-syarat yang telah ditetapkan boleh menyebabkan penyebut harga tersebut tidak layak untuk dipertimbangkan ke peringkat seterusnya.</span>
        </div>
        {{-- Sub-heading and its first clause break as one block; paged.js only walks back one level. --}}
        <div class="avoid-break keep-with-next">
            <div class="laporan-subheading">7.2 <strong>Penilaian Peringkat Pertama</strong></div>
            <div class="laporan-subsubitem">
                <span class="laporan-subsubitem-no">7.2.1</span>
                <span>Penilaian dibuat menggunakan Sistem Cut-Off dan hasil penilaian seperti di <strong>Jadual 5</strong> (maklumat terperinci di <strong>Lampiran Cut-Off</strong>):</span>
            </div>
        </div>
        <table class="laporan-table">
            <thead>
                <tr>
                    <th style="width:10%;">Bil.</th>
                    <th style="width:30%;">No. Penyebut Harga</th>
                    <th style="width:35%;">Harga Tawaran (RM)</th>
                    <th style="width:25%;">BWAM%</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadualHarga as $row)
                    <tr>
                        <td class="col-bil" style="width:10%;">{{ $row['bil'] }}.</td>
                        <td class="col-bil" style="width:30%;">{{ $row['kod_pembekal'] }}</td>
                        <td class="col-harga" style="width:35%;">{{ $row['harga'] }}</td>
                        <td class="col-bil" style="width:25%;">{{ $row['bwam'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="col-bil">Tiada penyebut harga yang menghantar dokumen.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="laporan-table-caption">Jadual 5 Keputusan Penilaian Pertama</div>
        <div class="laporan-subsubitem">
            <span class="laporan-subsubitem-no">7.2.2</span>
            <span>Sebanyak <strong>{{ $bilanganLulusCutOff }} penyebut harga</strong> sahaja telah melepasi penentuan harga cut-off iaitu memiliki peratus (% BWAM) dalam julat antara 0% hingga -20%;</span>
        </div>
        <div class="laporan-subsubitem">
            <span class="laporan-subsubitem-no">7.2.3</span>
            <span>Sehubungan dengan itu, JP bersetuju untuk mengambil <strong>{{ ucwords($bilanganTerpilih) }} penyebut harga</strong> iaitu <strong>bil.{{ $kodTerpilih }}</strong> untuk Penilaian Peringkat Kedua.</span>
        </div>
        <div class="avoid-break keep-with-next">
            <div class="laporan-subheading">7.3 <strong>Penilaian Peringkat Kedua</strong></div>
            <div class="laporan-subsubitem">
                <span class="laporan-subsubitem-no">7.3.1</span>
                <span>JPH telah membuat penilaian ke atas <strong>Analisa Kesempurnaan Dan Kecukupan Dokumen Kewangan</strong> kepada <strong>{{ $bilanganTerpilih }}</strong> penyebut harga dan hasil penilaian peringkat ketiga seperti di <strong>Jadual 6</strong> dan maklumat terperinci seperti di <strong>Borang 1</strong>:</span>
            </div>
        </div>
        <table class="laporan-table avoid-break">
            <thead>
                <tr>
                    <th style="width:8%;">Bil.</th>
                    <th style="width:22%;">No. Penyebut Harga</th>
                    <th style="width:22%;">Keputusan (Lulus/ Gagal)</th>
                    <th style="width:48%;">Ulasan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peringkatKedua as $row)
                    <tr>
                        <td class="col-bil">{{ $row['bil'] }}.</td>
                        <td class="col-bil">{{ $row['kod_pembekal'] }}</td>
                        <td class="col-bil">{{ $row['lulus'] ? 'LULUS' : 'GAGAL' }}</td>
                        <td class="col-bil">{{ $row['lulus'] ? 'Layak Ke Peringkat Ketiga' : $row['ulasan'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="col-bil">Tiada penyebut harga dipilih di peringkat cut-off.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="laporan-table-caption">Jadual 6 Keputusan Penilaian Peringkat Kedua</div>
        <div class="laporan-subsubitem">
            <span class="laporan-subsubitem-no">7.3.2</span>
            <span>Sehubungan dengan itu, JPH bersetuju untuk mengambil <strong>{{ $bilanganLulusKedua }} penyebut harga iaitu bil.{{ $kodLulusKedua }}</strong> untuk Penilaian Peringkat Ketiga.</span>
        </div>
        <div class="avoid-break keep-with-next">
            <div class="laporan-subheading">7.4 <strong>Penilaian Peringkat Ketiga</strong></div>
            <div class="laporan-subsubitem">
                <span class="laporan-subsubitem-no">7.4.1</span>
                <span>JPH telah membuat penilaian <strong>Kecukupan Modal {{ $bilanganLulusKedua }} penyebut harga</strong> dengan mengambil kira <strong>modal minimum sekurang-kurangnya RM{{ $modalMinimum }}</strong> seperti di <strong>Jadual 7</strong> dan maklumat terperinci seperti di <strong>Borang 2</strong>:</span>
            </div>
        </div>
        <table class="laporan-table avoid-break">
            <thead>
                <tr>
                    <th style="width:8%;">Bil.</th>
                    <th style="width:22%;">No. Penyebut Harga</th>
                    <th style="width:22%;">Lulus/ Gagal</th>
                    <th style="width:48%;">Ulasan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peringkatKetiga as $row)
                    <tr>
                        <td class="col-bil">{{ $row['bil'] }}.</td>
                        <td class="col-bil">{{ $row['kod_pembekal'] }}</td>
                        <td class="col-bil">{{ $row['lulus'] ? 'LULUS' : 'GAGAL' }}</td>
                        <td class="col-bil">{{ $row['lulus'] ? 'Layak Ke Peringkat Keempat' : $row['ulasan'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="col-bil">Tiada penyebut harga yang layak ke peringkat ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="laporan-table-caption">Jadual 7 Keputusan Penilaian Peringkat Ketiga</div>
        <div class="laporan-subsubitem">
            <span class="laporan-subsubitem-no">7.4.2</span>
            <span>Sehubungan dengan itu, JPH bersetuju untuk <strong>{{ $bilanganLulusKetiga }} penyebut harga iaitu bil.{{ $kodLulusKetiga }}</strong> untuk Peringkat Keempat.</span>
        </div>
        <div class="avoid-break keep-with-next">
            <div class="laporan-subheading">7.5 <strong>Penilaian Peringkat Keempat</strong></div>
            <div class="laporan-subsubitem">
                <span class="laporan-subsubitem-no">7.5.1</span>
                <span>JPH telah membuat Penilaian berdasarkan <strong>Analisa Pengalaman Syarikat dan Senarai Kerja Yang Sedang Dilaksanakan</strong> kepada <strong>{{ $bilanganLulusKetiga }} penyebut harga</strong> seperti di <strong>Jadual 8</strong> dan maklumat terperinci seperti di <strong>Borang 3</strong>:</span>
            </div>
        </div>
        <table class="laporan-table avoid-break">
            <thead>
                <tr>
                    <th style="width:8%;">Bil.</th>
                    <th style="width:22%;">No. Penyebut Harga</th>
                    <th style="width:22%;">Lulus/ Gagal</th>
                    <th style="width:48%;">Ulasan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peringkatKeempat as $row)
                    <tr>
                        <td class="col-bil">{{ $row['bil'] }}.</td>
                        <td class="col-bil">{{ $row['kod_pembekal'] }}</td>
                        <td class="col-bil">{{ $row['lulus'] ? 'LULUS' : 'GAGAL' }}</td>
                        <td class="col-bil">{{ $row['lulus'] ? 'Layak Ke Peringkat Pengesyoran' : $row['ulasan'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="col-bil">Tiada penyebut harga yang layak ke peringkat ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="laporan-table-caption">Jadual 8 Keputusan Penilaian Peringkat Keempat</div>
        <div class="laporan-subsubitem">
            <span class="laporan-subsubitem-no">7.5.2</span>
            <span>Sehubungan dengan itu, JPH bersetuju mengambil <strong>{{ $bilanganLulusKeempat }} penyebut harga iaitu bil.{{ $kodLulusKeempat }}</strong> layak ke peringkat pengesyoran.</span>
        </div>
        <div class="laporan-heading-group">
            <div class="laporan-heading">8. Pengesyoran</div>
            <div class="laporan-subitem">
                <span class="laporan-subitem-no">8.1</span>
                @if ($disyorkan)
                    <span>Dengan ini, JP mengesyorkan penyebut harga bil. <strong>{{ $disyorkan['kod_pembekal'] }}</strong> <strong>LAYAK DIPERTIMBANGKAN dengan harga tawaran sebanyak RM{{ $disyorkan['harga'] }} (Ringgit Malaysia: {{ $disyorkan['harga_perkataan'] }})</strong> bagi melaksanakan Sebut Harga <strong>{{ $tender->name ?? '-' }} – {{ $noTender }}</strong>, berdasarkan kepada justifikasi seperti berikut untuk dibawa ke Mesyuarat Jawatankuasa Sebutharga {{ $latarBelakang['agensi_pelaksana'] }}.</span>
                @else
                    <span>Tiada penyebut harga yang layak dipertimbangkan bagi melaksanakan Sebut Harga <strong>{{ $tender->name ?? '-' }} – {{ $noTender }}</strong>.</span>
                @endif
            </div>
        </div>
        <div class="laporan-subsubitem">
            <span class="laporan-subsubitem-no">8.1.1</span>
            <span>{{ $justifikasiSyor ?: 'Dengan ini, JPT mengesyorkan penyebut harga yang melepasi untuk melaksanakan projek ini untuk dibawa ke mesyuarat Jawatankuasa Sebut Harga berdasarkan justifikasi yang ditetapkan.' }}</span>
        </div>
        @if ($syaratTambahan)
            <div class="laporan-subsubitem">
                <span class="laporan-subsubitem-no">8.1.2</span>
                <span>{{ $syaratTambahan }}</span>
            </div>
        @endif
        <div class="doc-title-block force-new-page">
            <div class="doc-title">
                Laporan Jawatankuasa Penilaian Sebut Harga<br>
                {{ $tender->name ?? '-' }}<br>
                (Sebut Harga Kerja)
            </div>
            <div class="doc-no-tender">No. Sebut Harga: {{ $noTender }}</div>
        </div>
        <div class="laporan-heading">9. Pengesahan</div>
        <div class="laporan-subitem">
            <span>Ditandatangani oleh Jawatankuasa Penilaian (JP):</span>
        </div>
        <div class="jpt-members pengesahan-block">
            @forelse ($jpMembers as $member)
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
                <div>Tiada ahli Jawatankuasa Penilaian direkodkan.</div>
            @endforelse
            <div class="jpt-member">
                <div class="jpt-member-role">Tarikh</div>
                <div class="jpt-member-colon">:</div>
                <div class="jpt-member-details">{{ $tarikhPenilaian ?? '-' }}</div>
            </div>
        </div>
    </template></body></html>
