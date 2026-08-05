<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <title>Laporan Penilaian Teknikal - {{ $tender->no_tender ?? $tender->ref_number ?? '-' }}</title>

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
                content: "Laporan Jawatankuasa Penilaian Teknikal - {{ $tender->no_tender ?? $tender->ref_number ?? '-' }}";
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
            /* Content starts flush against the @page top margin (no gap of its own), so this
               margin-top balances against padding-bottom below for equal spacing either side
               of the title text, relative to the two divider lines. */
            margin-top: 14px;
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

        .jpt-members {
            padding-left: 48px;
            margin: 4px 0 14px;
            text-transform: capitalize;
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
            flex: 0 0 88px;
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
            text-transform: capitalize;
        }

        .jpt-jawatan {
            text-transform: capitalize;
        }

        .pengesahan-intro {
            padding-left: 20px;
            margin-bottom: 14px;
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

    <script src="{{ asset('js/report-format-pdf/paged.polyfill.js') }}"></script>
</head>
<body>

    <div class="no-print">
        <button class="btn-cetak" onclick="window.print()">Cetak / Simpan PDF</button>
        <button class="btn-tutup" onclick="window.close()">Tutup</button>
    </div>

    @php
        $noTender = $tender->no_tender ?? $tender->ref_number ?? '-';
    @endphp

    {{-- paged.js reads this exact tag (data-ref="pagedjs-content") instead of sweeping the
         whole <body> in — keeps the Cetak/Tutup buttons above out of the paginated output. --}}
    <template data-ref="pagedjs-content">

        <div class="doc-title-block">
            <div class="doc-title">
                Laporan Jawatankuasa Penilaian Teknikal<br>
                {{ strtoupper($tender->name ?? '-') }}
            </div>
            <div class="doc-no-tender">No. Tender: {{ $noTender }}</div>
        </div>

        <div class="laporan-heading">1. Tujuan</div>
        <div class="laporan-subitem">
            <span class="laporan-subitem-no">1.1</span>
            <span>Tujuan kertas ini adalah bagi melaporkan hasil penilaian teknikal bagi <strong>{{ $tender->name ?? '-' }}</strong> - <strong>No. Tender: {{ $noTender }}</strong>; dan</span>
        </div>
        <div class="laporan-subitem">
            <span class="laporan-subitem-no">1.2</span>
            <span>Laporan ini seterusnya akan mengesyorkan cadangan tawaran terbaik yang mematuhi syarat-syarat dokumentasi teknikal untuk pertimbangan dan kelulusan Jawatankuasa Tender Pejabat Setiausaha Kerajaan Negeri Selangor.</span>
        </div>

        <div class="laporan-heading">2. Latar Belakang/ Maklumat Tender</div>
        <table class="laporan-table avoid-break">
            <thead>
                <tr>
                    <th style="width:6%;">Bil.</th>
                    <th style="width:28%;">Perkara</th>
                    <th style="width:66%;">Keterangan</th>
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
                    <td>No. Tender</td>
                    <td>{{ $noTender }}</td>
                </tr>
                <tr>
                    <td class="col-bil">iv.</td>
                    <td>Tarikh Iklan</td>
                    <td>{{ $latarBelakang['tarikh_iklan'] }}</td>
                </tr>
                <tr>
                    <td class="col-bil">v.</td>
                    <td>Tarikh Jual</td>
                    <td>{{ $latarBelakang['tarikh_jual'] }}</td>
                </tr>
                <tr>
                    <td class="col-bil">vi.</td>
                    <td>Tarikh Tutup</td>
                    <td>{{ $latarBelakang['tarikh_tutup'] }}</td>
                </tr>
                <tr>
                    <td class="col-bil">vii.</td>
                    <td>Masa Tutup</td>
                    <td>{{ $latarBelakang['masa_tutup'] }}</td>
                </tr>
                <tr>
                    <td class="col-bil">viii.</td>
                    <td>Tempoh Sah Laku Tender</td>
                    <td>{{ $latarBelakang['tempoh_sah_laku'] }} selepas tarikh tutup tender</td>
                </tr>
                <tr>
                    <td class="col-bil">ix.</td>
                    <td>Bilangan Dokumen Tender Yang Diterima Untuk Dinilai</td>
                    <td>{{ $latarBelakang['bilangan_dokumen'] }} Dokumen</td>
                </tr>
            </tbody>
        </table>

        <div class="laporan-heading force-new-page">3. Pelantikan Jawatankuasa</div>
        <div class="laporan-subitem">
            <span class="laporan-subitem-no">3.1</span>
            <span>Memo Jemputan Mesyuarat Jawatankuasa Penilaian Teknikal (JPT) telah diedarkan oleh Bahagian Khidmat Pengurusan, Pejabat Setiausaha Kerajaan Negeri Selangor bertarikh <strong>{{ $tarikhMesyuaratTeknikal ? \Carbon\Carbon::parse($tarikhMesyuaratTeknikal)->format('d.m.Y') : 'Value to be confirm where to fetch it' }}</strong> dan senarai jawatankuasa yang dilantik oleh Y.B. Setiausaha Kerajaan Negeri Selangor seperti berikut:</span>
        </div>

        <div class="jpt-members">
            @forelse ($jptMembers as $member)
                <div class="jpt-member">
                    <div class="jpt-member-role">{{ $member['peranan_label'] }}</div>
                    <div class="jpt-member-colon">:</div>
                    <div class="jpt-member-details">
                        <div class="jpt-member-name">{{ $member['name'] }}</div>
                        <div class="jpt-jawatan">{{ $member['jawatan'] }}</div>
                        <div>Bahagian Khidmat Pengurusan</div>
                        <div>Pejabat Setiausaha Kerajaan Negeri Selangor</div>
                        {{-- <div>Surat Lantikan: Value to be confirm where to fetch it</div> --}}
                        <div>Tarikh Lantikan: {{ $member['tarikh_lantikan'] ?? '-' }}</div>
                    </div>
                </div>
            @empty
                <div>Tiada ahli Jawatankuasa Penilaian Teknikal direkodkan.</div>
            @endforelse
        </div>

        <div class="laporan-subitem">
            <span class="laporan-subitem-no">3.2</span>
            <span>Terma dan tanggungjawab JPT telah dipersetujui dan ditetapkan seperti berikut:</span>
        </div>
        <div class="laporan-roman-list">
            <div class="laporan-roman-item">
                <span class="roman">i.</span>
                <span>Menyemak dan menentukan kepatuhan terhadap keperluan mandatori yang mesti ditepati;</span>
            </div>
            <div class="laporan-roman-item">
                <span class="roman">ii.</span>
                <span>Menetapkan kaedah penilaian, nilai wajaran dan skema pemarkahan;</span>
            </div>
            <div class="laporan-roman-item">
                <span class="roman">iii.</span>
                <span>Mengemukakan syor kepada Mesyuarat Jawatankuasa Tender Pejabat Setiausaha Kerajaan Negeri Selangor; dan</span>
            </div>
            <div class="laporan-roman-item">
                <span class="roman">iv.</span>
                <span>Mengemukakan laporan bertulis yang lengkap kepada Urus Setia Perolehan.</span>
            </div>
        </div>

        <div class="laporan-subitem">
            <span class="laporan-subitem-no">3.3</span>
            <span>Mesyuarat JPT telah diadakan sebanyak satu (1) kali pada {{ $tarikhMesyuaratTeknikal ? \Carbon\Carbon::parse($tarikhMesyuaratTeknikal)->format('d.m.Y') : 'Value to be confirm where to fetch it' }}.</span>
        </div>

        <div class="laporan-heading-group">
            <div class="laporan-heading">4. Dokumen-Dokumen Untuk Cadangan Penilaian Teknikal</div>
            <div class="laporan-subitem">
                <span class="laporan-subitem-no">4.1</span>
                <span>Petender dikehendaki mengemukakan dokumen cadangan teknikal seperti berikut:</span>
            </div>
        </div>

        <table class="laporan-table">
            <thead>
                <tr>
                    <th style="width:8%;">Bil.</th>
                    <th style="width:92%;">Keterangan</th>
                    {{-- Lampiran column — disabled until we have a real source for it.
                    <th style="width:30%;">Lampiran</th>
                    --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($dokumenTeknikal as $doc)
                    <tr>
                        <td class="col-bil">{{ $doc['bil'] }}.</td>
                        <td>{{ $doc['keterangan'] }}</td>
                        {{-- <td>Value to be confirm where to fetch it</td> --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="col-bil">Tiada dokumen teknikal direkodkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="laporan-subitem">
            <span class="laporan-subitem-no">4.2</span>
            <span>Dokumen-dokumen yang dihantar hendaklah lengkap daripada segi maklumat yang dinyatakan, memenuhi syarat dan mematuhi arahan yang dinyatakan seperti di dalam dokumen Tender.</span>
        </div>

        <div class="laporan-heading-group">
            <div class="laporan-heading">5. Metodologi Penilaian Teknikal</div>
            <div class="laporan-subitem">
                <span class="laporan-subitem-no">5.1</span>
                <span>JPT telah membuat penilaian kepada dua (2) peringkat iaitu:</span>
            </div>

            <div class="laporan-subsubitem">
                <span class="laporan-subsubitem-no">5.1.1.</span>
                <span><strong>Penilaian Peringkat Pertama:</strong> Penilaian <strong>Analisa Kesempurnaan Dokumen Teknikal</strong> dengan menyemak pematuhan kepada syarat-syarat mandatori yang telah ditetapkan seperti di dalam dokumen tender. Kegagalan untuk menepati syarat-syarat yang telah ditetapkan boleh menyebabkan petender tersebut tidak layak untuk dipertimbangkan ke peringkat seterusnya;</span>
            </div>
        </div>
        <div class="laporan-subsubitem">
            <span class="laporan-subsubitem-no">5.1.2.</span>
            <span><strong>Penilaian Peringkat Kedua:</strong> Penilaian dibuat berdasarkan <strong>Pematuhan Spesifikasi, Pengalaman Kerja Dan Profil Syarikat.</strong> Kegagalan untuk menepati syarat-syarat yang telah ditetapkan boleh menyebabkan petender tersebut tidak layak untuk dipertimbangkan ke peringkat seterusnya.</span>
        </div>

        <div class="laporan-subheading">5.2 <strong>Penilaian Peringkat Pertama</strong></div>
        <div class="laporan-subsubitem">
            <span class="laporan-subsubitem-no">5.2.1.</span>
            <span>Penilaian dibuat berdasarkan <strong>Analisa Kesempurnaan Dokumen Teknikal</strong>. Hasil penilaian seperti di <strong>Jadual 1</strong> dan maklumat terperinci di <strong>Lampiran 1</strong>:</span>
        </div>

        <table class="laporan-table">
            <thead>
                <tr>
                    <th style="width:8%;">Bil.</th>
                    <th style="width:16%;">No. Petender</th>
                    <th style="width:18%;">Keputusan (Lulus/Gagal)</th>
                    <th style="width:58%;">Ulasan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($penilaianPeringkatPertama as $row)
                    <tr>
                        <td class="col-bil">{{ $row['bil'] }}.</td>
                        <td class="col-bil">{{ $row['no_petender'] }}</td>
                        <td class="col-bil">{{ $row['keputusan'] }}</td>
                        <td>{{ $row['ulasan'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="col-bil">Tiada petender direkodkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="laporan-table-caption">Jadual 1: Keputusan Penilaian Peringkat Pertama</div>

        <div class="laporan-subsubitem">
            <span class="laporan-subsubitem-no">5.2.2.</span>
            <span>{!! $catatanPematuhan ?: 'Value to be confirm where to fetch it' !!}</span>
        </div>

        <div class="laporan-subheading">5.3 <strong>Penilaian Peringkat Kedua</strong></div>
        <div class="laporan-subsubitem">
            <span class="laporan-subsubitem-no">5.3.1.</span>
            <span>Penilaian dibuat berdasarkan <strong>Pematuhan Spesifikasi, Pengalaman Kerja Dan Profil Syarikat</strong> dengan penetapan markah lulus ialah {{ $passingPercentageFormatted }}% keatas. Hasil Penilaian seperti di <strong>Jadual 2</strong> (maklumat terperinci di <strong>Lampiran 2</strong>).</span>
        </div>

        <table class="laporan-table">
            <thead>
                <tr>
                    <th style="width:8%;">Bil.</th>
                    <th style="width:14%;">No. Petender</th>
                    <th style="width:16%;">Markah Teknikal %</th>
                    <th style="width:18%;">Keputusan (Lulus/Gagal)</th>
                    <th style="width:44%;">Ulasan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($penilaianPeringkatKedua as $row)
                    <tr>
                        <td class="col-bil">{{ $row['bil'] }}.</td>
                        <td class="col-bil">{{ $row['no_petender'] }}</td>
                        <td class="col-bil">{{ $row['markah_teknikal'] }}</td>
                        <td class="col-bil">{{ $row['keputusan'] }}</td>
                        <td>{{ $row['ulasan'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="col-bil">Tiada petender direkodkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="laporan-table-caption">Jadual 2: Keputusan Penilaian Peringkat Kedua</div>

        <div class="laporan-subsubitem">
            <span class="laporan-subsubitem-no">5.3.2.</span>
            <span>{!! $catatanSpesifikasi ?: 'Value to be confirm where to fetch it' !!}</span>
        </div>

        <div class="laporan-heading-group">
            <div class="laporan-heading">6. Pengesyoran</div>
            <div class="laporan-subitem">
                <span class="laporan-subitem-no">6.1</span>
                <span>{!! $pengesyoranIntro ?: 'Value to be confirm where to fetch it' !!}</span>
            </div>
        </div>

        @foreach ($pengesyoranJustifikasi as $index => $justifikasi)
            <div class="laporan-subsubitem">
                <span class="laporan-subsubitem-no">6.1.{{ $index + 1 }}.</span>
                <span>{{ $justifikasi }}</span>
            </div>
        @endforeach

        @if ($petenderLainLulusText)
            <div class="laporan-subitem">
                <span class="laporan-subitem-no">6.2</span>
                <span>Walau bagaimanapun, petender bil. {{ $petenderLainLulusText }} juga <strong>LAYAK DIPERTIMBANGKAN</strong> kerana melepasi markah lulus {{ $passingPercentageFormatted }}% dan telah lulus dalam semua peringkat penilaian teknikal dan kesempurnaan dokumen tender. Semua petender ini turut berpengalaman dalam bidang yang bersesuaian untuk melaksanakan tender ini.</span>
            </div>
        @endif

        <div class="doc-title-block force-new-page">
            <div class="doc-title">
                Laporan Jawatankuasa Penilaian Teknikal<br>
                {{ strtoupper($tender->name ?? '-') }}
            </div>
            <div class="doc-no-tender">No. Tender: {{ $noTender }}</div>
        </div>

        <div class="laporan-heading">7. Pengesahan</div>
        <div class="pengesahan-intro">Ditandatangani oleh Jawatankuasa Penilaian Teknikal (JPT):</div>

        <div class="jpt-members">
            @foreach ($jptMembers as $member)
                <div class="jpt-member">
                    <div class="jpt-member-role">{{ $member['peranan_label'] }}</div>
                    <div class="jpt-member-colon">:</div>
                    <div class="jpt-member-details">
                        <div class="signature-line"></div>
                        <div class="pengesahan-name">({{ strtoupper($member['name']) }})</div>
                        <div>{{ $member['jawatan'] }}</div>
                        <div>Bahagian Khidmat Pengurusan</div>
                        <div>Pejabat Setiausaha Kerajaan Negeri Selangor</div>
                    </div>
                </div>
            @endforeach

            <div class="jpt-member">
                <div class="jpt-member-role">Tarikh</div>
                <div class="jpt-member-colon">:</div>
                <div class="jpt-member-details">{{ $tarikhLaporanDicetak }}</div>
            </div>
        </div>

    </template>

</body>
</html>
