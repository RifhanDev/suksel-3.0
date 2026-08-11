@extends('newModule.penyediaanSST.pdf.layout')

@section('title', 'Surat Setuju Terima - ' . $noTender)

@push('styles')
<style>
    @page {
        /* Order: top right bottom left — wide bottom holds the signature block. */
        margin: 30mm 20mm 40mm 26mm;

        /* Running header — the SST document number, repeated on every page. */
        @top-left {
            content: "No. Surat Setuju Terima : {{ $documentNo }}";
            font-family: Arial, sans-serif;
            font-size: 11px;
            white-space: nowrap;
        }

        @top-right {
            content: "LAMPIRAN 1";
            font-family: Arial, sans-serif;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        @bottom-left {
            content: "Tandatangan & Cap (Kerajaan) : ..................................\A\ATandatangan & Cap (Syarikat) : ..................................";
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        @bottom-right {
            content: counter(page) " daripada " counter(pages);
            font-family: Arial, sans-serif;
            font-size: 11px;
            white-space: nowrap;
        }
    }

    /* paged.js splits both margin boxes into 3 equal thirds — the header and the
       signature block need far more than a third to stay on one line. These boxes
       already span exactly the body text width (the corner holders either side are
       the page margins), so only vertical padding is needed to lift them off the edge. */
    .pagedjs_margin-top {
        grid-template-columns: 62% 8% 30% !important;
        padding-top: 12mm;
    }

    .pagedjs_margin-bottom {
        grid-template-columns: 70% 5% 25% !important;
        padding-top: 8mm;
        padding-bottom: 12mm;
    }

    /* paged.js forces align-items:center on every margin box, so vertical-align in
       the @page rule is ignored — the alignment has to be set on the box itself. */
    .pagedjs_margin-top-left,
    .pagedjs_margin-top-right,
    .pagedjs_margin-bottom-right {
        align-items: flex-start !important;
    }

    .pagedjs_margin-bottom-left {
        align-items: flex-end !important;
    }

    /* Renders the \A escapes above as real line breaks so the two signature
       lines stack instead of running together. */
    .pagedjs_margin-bottom-left .pagedjs_margin-content {
        white-space: pre;
        line-height: 1.9;
    }

    /* PROTEGE-RTW formula, centred and underlined as in the sample. */
    .formula {
        text-align: center;
        margin: 16px 0 18px;
    }

    .formula-line {
        font-weight: 700;
        text-decoration: underline;
        margin-bottom: 10px;
    }

    .subject-label {
        flex: 0 0 200px;
        font-weight: 700;
    }

    /* Takes the remaining width so a long tender title wraps instead of pushing the
       colon out of line. */
    .subject-value {
        flex: 1;
    }

    /* Reference block, sitting well right of centre as in the sample. */
    .ref-block {
        width: 46%;
        margin-left: auto;
        margin-bottom: 26px;
    }

    /* Lampiran A — numbered section heading. */
    .section-heading {
        font-weight: 700;
        text-decoration: underline;
        margin: 20px 0 12px;
        break-after: avoid;
    }

    /* Lampiran A — "label : value" detail row. */
    .detail-row {
        display: flex;
        gap: 8px;
        margin-bottom: 10px;
        break-inside: avoid;
    }

    .detail-label {
        flex: 0 0 32%;
    }

    .detail-colon {
        flex: 0 0 8px;
    }

    .detail-value {
        flex: 1;
    }

    .note-small {
        font-size: 10px;
    }

    /* Acknowledgement page — company and witness sign side by side. */
    .witness-grid {
        display: flex;
        gap: 40px;
        margin-top: 34px;
    }

    .witness-col {
        flex: 1;
    }

    .witness-rule {
        border-bottom: 1px dotted #000;
        margin-bottom: 6px;
    }

    .witness-row {
        display: flex;
        margin-bottom: 2px;
    }

    .witness-label {
        flex: 1;
    }

    /* Copy list — each recipient block stays whole. */
    .copy-block {
        margin-bottom: 18px;
        line-height: 1.6;
        break-inside: avoid;
    }

    .copy-postcode {
        font-weight: 700;
    }
</style>
@endpush

@section('content')

    @php
        // The opening paragraph carries no number, so numbering starts at 2. Every numbered
        // paragraph advances this counter as it renders, so any paragraph dropped by a
        // condition closes its own gap and the rest shift up. $ref holds the numbers other
        // paragraphs point back to, so cross-references follow the same shift; a key that is
        // absent means that paragraph was not printed.
        $clauseNo = 1;
        $ref = [];
    @endphp

    {{-- ================= PAGE 1 ================= --}}

    <div class="doc-title">
        SURAT SETUJU TERIMA<br>
        (Bagi {{ $jenisPerolehanLabel }} {{ ucfirst($kategoriPerolehan) }})
    </div>

    <div class="ref-block">
        <div class="field-row">
            <span class="field-label">Rujukan Kami</span>
            <span class="field-colon">:</span>
            <span class="subject-value">{!! $rujukanKami !== '' ? '<strong>' . e($rujukanKami) . '</strong>' : '<span class="line"></span>' !!}</span>
        </div>
        <div class="field-row">
            <span class="field-label">Tarikh</span>
            <span class="field-colon">:</span>
            <span class="subject-value"><strong>{{ $tarikhSurat }}</strong></span>
        </div>
    </div>

    <div class="recipient">
        <div class="recipient-role">PENGARAH URUSAN/ PENGURUS / PENGERUSI</div>
        <div><span class="blank blank-lg"></span></div>
        <div><span class="blank blank-lg"></span></div>
        <div><span class="blank blank-lg"></span></div>
        <div><span class="blank blank-lg"></span></div>
    </div>

    <div class="salutation">Tuan,</div>

    <div style="margin-bottom: 20px;">
        <div class="field-row">
            <span class="subject-label">{{ $jenisPerolehanLabel }} Untuk</span>
            <span class="field-colon">:</span>
            <span class="subject-value"><strong>{{ $tender->name }}</strong></span>
        </div>
        <div class="field-row">
            <span class="subject-label">No. {{ $jenisPerolehanLabel }}/Kontrak</span>
            <span class="field-colon">:</span>
            <span class="subject-value"><strong>{{ $noTender }}</strong></span>
        </div>
    </div>

    <div class="para-plain">
        Dengan ini dimaklumkan bahawa Kerajaan telah bersetuju menerima tawaran {{ strtolower($jenisPerolehanLabel) }}
        syarikat tuan dengan harga sebanyak Ringgit Malaysia <strong>{{ $amounts['contract']['words'] }}</strong>
        (<strong>RM{{ $amounts['contract']['figure'] }}</strong>) yang merupakan harga kontrak bagi tempoh kontrak
        selama {!! $tempohKontrak !== '' ? '<strong>' . e($tempohKontrak) . '</strong>' : '<span class="blank blank-md"></span>' !!}
        tertakluk kepada dokumen {{ strtolower($jenisPerolehanLabel) }} yang
        menjadi sebahagian daripada perolehan ini dan Surat Setuju Terima ini berserta dengan
        <strong>Lampiran A</strong> kepada Surat Setuju Terima iaitu maklumat terperinci kontrak (selepas ini
        disebut sebagai &ldquo;Surat ini&rdquo;).
    </div>

    <div class="para">
        <span class="para-no">{{ ++$clauseNo }}.</span>
        <span>Dengan pengakuan penerimaan Surat ini, suatu kontrak yang mengikat terbentuk antara Kerajaan dengan
        syarikat tuan. Satu dokumen kontrak hendaklah ditandatangani dengan kadar segera dengan memasukkan semua
        terma sebagaimana dokumen {{ strtolower($jenisPerolehanLabel) }} serta semua terma dalam
        <strong>Lampiran A</strong>. Sehingga dokumen kontrak tersebut ditandatangani, Surat ini hendaklah terus
        mengikat kedua-dua pihak.</span>
    </div>

    {{-- Paragraph 3 — the tax wording is chosen in the controller from the tax rate,
         the vendor's JKDM (CBP) number and the procurement category. --}}
    <div class="para">
        <span class="para-no">{{ ++$clauseNo }}.</span>
        <span>
            @switch($taxClause)

                @case('exempt')
                    Harga kontrak adalah tidak termasuk cukai jualan selaras dengan pengecualian yang diberikan di
                    bawah Perintah Cukai Jualan (Orang Yang Dikecualikan Daripada Pembayaran Cukai) 2018 yang
                    dengannya Sijil Di Bawah Perintah Cukai Jualan (Orang Yang Dikecualikan Daripada Pembayaran
                    Cukai) 2018 akan dikeluarkan sebelum sebarang pembayaran dibuat.
                    @break

                @case('registered')
                    Harga kontrak adalah termasuk peruntukan Kerajaan sebanyak <strong>{{ $taxRateFormatted }}%</strong>
                    {{ $taxLabel }} memandangkan perkhidmatan ini dikenakan cukai dan syarikat tuan telah berdaftar
                    dengan Jabatan Kastam Diraja Malaysia (JKDM). Pembayaran {{ $taxLabel }} ini adalah dikira
                    berdasarkan tuntutan sebenar dan tarikh kuat kuasa pendaftaran syarikat tuan dengan JKDM.
                    @break

                @case('unregistered')
                    Harga kontrak adalah tidak termasuk peruntukan Kerajaan sebanyak <strong>{{ $taxRateFormatted }}%</strong>
                    {{ $taxLabel }} memandangkan syarikat tuan tidak berdaftar dengan Jabatan Kastam Diraja Malaysia
                    (JKDM). Sekiranya syarikat tuan telah berdaftar dengan JKDM, tuan adalah dikehendaki untuk
                    memaklumkan nombor pendaftaran dan tarikh kuat kuasanya kepada Kerajaan untuk pelarasan harga
                    kontrak dalam tempoh tujuh (7) hari dari tarikh surat kelulusan JKDM. Pembayaran
                    {{ $taxLabel }} ini adalah dikira berdasarkan tuntutan sebenar dan tarikh kuat kuasa pendaftaran
                    syarikat tuan dengan JKDM.
                    @break

            @endswitch
        </span>
    </div>

    {{-- ================= PAGE 2 ================= --}}
    <div class="page-break"></div>

    {{-- Paragraph 4 — works contracts get a price-adjustment clause instead of the
         document checklist. Which documents are listed is decided in the controller.
         One branch or the other always prints, so the number is taken up front. --}}
    @php $clause4No = ++$clauseNo; @endphp

    @if ($kategoriPerolehan === 'kerja')

        <div class="para">
            <span class="para-no">{{ $clause4No }}.</span>
            <span>Pelarasan harga dan kadar harga dalam Jadual Kadar Harga dan/atau Ringkasan Tender atau Senarai
            Kuantiti, mengikut yang mana berkenaan setelah diteliti dan diselaraskan oleh Kerajaan tentang
            kemunasabahannya, yang mana akan menjadi sebahagian daripada terma-terma kontrak. Walau bagaimanapun,
            Jumlah Harga Kontrak seperti di atas adalah kekal tidak berubah.</span>
        </div>

    @else

        <div class="para keep-with-next">
            <span class="para-no">{{ $clause4No }}.</span>
            <span>Adalah dimaklumkan bahawa tiada pembekalan barang boleh dibuat <strong>melainkan</strong> jika
            syarikat tuan telah mengemukakan kepada Kerajaan dokumen-dokumen berikut:</span>
        </div>

        <div class="list">
            @foreach ($clause4Documents as $document)
                <div class="list-item">
                    {{-- Markers run in sequence, so dropping (a) or (b) shifts the rest up. --}}
                    <span class="list-marker">({{ chr(97 + $loop->index) }})</span>
                    <span>
                        @switch($document)

                            @case('bond')
                                suatu bon pelaksanaan yang tidak boleh dibatalkan yang berjumlah Ringgit Malaysia
                                <strong>{{ $amounts['bond']['words'] }}</strong>
                                (<strong>RM{{ $amounts['bond']['figure'] }}</strong>);
                                @break

                            @case('insurance')
                                suatu polisi insurans yang tidak boleh dibatalkan yang berjumlah Ringgit Malaysia
                                <strong>{{ $amounts['insurance']['words'] }}</strong>
                                (<strong>RM{{ $amounts['insurance']['figure'] }}</strong>) dan resit premium;
                                @break

                            @case('perkeso')
                                Nombor Kod Majikan di bawah Skim PERKESO dan/atau Polisi Pampasan Pekerja;
                                @break

                            @case('kwsp')
                                Nombor Pendaftaran Kumpulan Wang Simpanan Pekerja (KWSP),
                                @break

                        @endswitch
                    </span>
                </div>
            @endforeach
        </div>

        <div class="para-continued">
            seperti yang ditetapkan dalam <strong>Lampiran A</strong> tidak melebihi 14/30 hari dari tarikh
            pengakuan penerimaan Surat ini oleh syarikat tuan.
            Apa-apa kegagalan dalam mematuhi kehendak di perenggan ini dalam tempoh masa yang ditetapkan, boleh
            mengakibatkan Surat ini terbatal dan Kerajaan tidaklah dengan apa-apa cara jua bertanggungan terhadap
            syarikat tuan <strong>melainkan jika</strong> penepian melebihi tempoh masa yang diberi kuasa, bagi
            bekalan barang yang perlu dibuat dengan segera atau serta-merta apabila kelewatan itu akan memudarat dan
            menjejaskan perkhidmatan dan kepentingan awam.
        </div>

    @endif

    {{-- Paragraph 5 — works contracts list the documents due before site possession;
         supply and services simply cover delivery. The documents checklist therefore sits
         at paragraph 5 for works and paragraph 4 for everything else, and the cancellation
         grounds point back at whichever it is. --}}
    @php
        $clause5No = ++$clauseNo;
        $ref['documents'] = $kategoriPerolehan === 'kerja' ? $clause5No : $clause4No;
    @endphp

    @if ($kategoriPerolehan === 'kerja')

        <div class="para keep-with-next">
            <span class="para-no">{{ $clause5No }}.</span>
            <span>Tarikh milik tapak seperti yang disebutkan dalam Syarat-syarat Kontrak ialah pada
            <span class="blank blank-md"></span>. Walau bagaimanapun, tuan adalah diingatkan bahawa tiada kerja
            boleh dibuat <strong>melainkan</strong> jika tuan telah mengemukakan kepada Kerajaan dokumen-dokumen
            berikut:</span>
        </div>

        <div class="list">
            @foreach ($clause5Documents as $document)
                <div class="list-item">
                    {{-- Markers run in sequence, so dropping (a) to (c) shifts the rest up. --}}
                    <span class="list-marker">({{ chr(97 + $loop->index) }})</span>
                    <span>
                        @switch($document)

                            @case('bond')
                                suatu bon pelaksanaan yang tidak boleh dibatalkan yang berjumlah Ringgit Malaysia
                                <strong>{{ $amounts['bond']['words'] }}</strong> (<strong>RM{{ $amounts['bond']['figure'] }}</strong>) dan jikalau Bon
                                Pelaksanaan gagal dikemukakan pada tarikh milik tapak, Kerajaan berhak untuk
                                melaksanakan kaedah Wang Jaminan Pelaksanaan;
                                @break

                            @case('public_liability_insurance')
                                suatu polisi Insurans Tanggungan Awam (iaitu insurans terhadap bencana kepada
                                orang-orang dan kerosakan kepada harta) nilai insurans tidak kurang daripada Ringgit
                                Malaysia <strong>{{ $amounts['insurance']['words'] }}</strong>
                                (<strong>RM{{ $amounts['insurance']['figure'] }}</strong>);
                                @break

                            @case('works_insurance')
                                suatu polisi Insurans Kerja yang berjumlah: Ringgit Malaysia
                                <strong>{{ $amounts['insurance']['words'] }}</strong> (<strong>RM{{ $amounts['insurance']['figure'] }}</strong>);
                                @break

                            @case('perkeso')
                                Nombor Kod Majikan di bawah Skim PERKESO dan/atau Polisi Pampasan Pekerja;
                                @break

                            @case('kwsp')
                                Nombor Pendaftaran Kumpulan Wang Simpanan Pekerja (KWSP),
                                @break

                        @endswitch
                    </span>
                </div>
            @endforeach
        </div>

        <div class="para-continued">
            mengikut ketetapan seperti di <strong>Lampiran A</strong>. Walau bagaimanapun, bagi memulakan
            kerja-kerja dan bukan maksud lain, tuan boleh menyerahkan Nota-nota Liputan bagi maksud polisi-polisi
            insurans tersebut dan resit-resit premium yang telah dibayar itu kepada Pegawai Penguasa. Tuan
            dikehendaki menyerahkan Polisi-polisi Insurans yang berkenaan (jika belum diserahkan) menurut perenggan
            ini, dalam tempoh tidak lewat 30 hari daripada tarikh penyerahan Nota-nota Liputan. Apa-apa kegagalan
            dalam mematuhi kehendak di perenggan ini dalam tempoh masa yang ditetapkan, boleh mengakibatkan Surat
            ini terbatal dan Kerajaan tidaklah dengan apa-apa cara jua bertanggungan terhadap tuan
            <strong>melainkan jika</strong> penepian bertulis diberikan oleh orang yang diberi kuasa, bagi kerja
            yang perlu dibuat dengan segera atau serta-merta apabila kelewatan itu akan memudarat dan menjejaskan
            perkhidmatan dan kepentingan awam.
        </div>

    @else

        <div class="para">
            <span class="para-no">{{ $clause5No }}.</span>
            <span>Setelah pesanan/arahan dikeluarkan oleh Kerajaan, syarikat tuan dikehendaki melaksanakan
            pembekalan barang dalam tempoh yang ditetapkan dan kualiti bekalan tersebut hendaklah memuaskan dan
            tepat serta memenuhi kehendak Kerajaan. Sekiranya syarikat tuan gagal melaksanakan pembekalan barang
            dalam tempoh dan/atau kualiti yang ditetapkan, Kerajaan berhak membatalkan pesanan/arahan yang
            dikeluarkan dan/atau mengenakan Denda / Tolakan / <em>Liquidated &amp; Ascertained Damages</em> (LAD)
            seperti yang ditetapkan dalam <strong>Lampiran A</strong>.</span>
        </div>

    @endif

    {{-- Works contracts state the delivery obligation here; supply and services covered it
         in paragraph 5. Lands at paragraph 6. --}}
    @if ($kategoriPerolehan === 'kerja')
        <div class="para">
            <span class="para-no">{{ ++$clauseNo }}.</span>
            <span>Setelah arahan dikeluarkan oleh Kerajaan, tuan dikehendaki melaksanakan kerja dalam tempoh
            yang ditetapkan dan kualiti kerja tersebut hendaklah memuaskan hati serta memenuhi kehendak
            Kerajaan. Sekiranya tuan gagal melaksanakan kerja dalam tempoh yang ditetapkan, Kerajaan berhak
            membatalkan arahan yang dikeluarkan dan/atau mengenakan <em>Liquidated &amp; Ascertained
            Damages</em> (LAD) seperti yang ditetapkan dalam <strong>Lampiran A</strong>.</span>
        </div>
    @endif

    {{-- PROTEGE-RTW statement — paragraph 6 for supply and services, 7 for works, and
         dropped entirely when the programme does not apply. --}}
    @switch($protege)

        @case('above_threshold')
            @php $ref['protege'] = ++$clauseNo; @endphp
            <div class="para keep-with-next">
                <span class="para-no">{{ $ref['protege'] }}.</span>
                <span>Syarikat tuan juga adalah dikehendaki melaksanakan program <em>Professional Training and
                Education for Growing Entrepreneurs &ndash; Ready To Work</em> (PROTEGE-RTW) seperti
                yang ditetapkan oleh Kerajaan berdasarkan harga kontrak dengan bilangan minimum peserta
                PROTEGE-RTW sebanyak <span class="blank blank-sm"></span> orang tanpa sebarang kos
                kepada Kerajaan. Bilangan minimum peserta yang diperlukan dikira berdasarkan formula di
                bawah:</span>
            </div>

            <div class="formula">
                <div class="formula-line">1% X Harga Kontrak*</div>
                <div class="formula-line">RM24,000**</div>
            </div>
            @break

        @case('below_threshold')
            @php $ref['protege'] = ++$clauseNo; @endphp
            <div class="para">
                <span class="para-no">{{ $ref['protege'] }}.</span>
                <span>Syarikat tuan juga adalah digalakkan melaksanakan program <em>Professional Training and
                Education for Growing Entrepreneurs &ndash; Ready To Work</em> (PROTEGE-RTW) seperti
                yang ditetapkan oleh Kerajaan.</span>
            </div>
            @break

    @endswitch

    {{-- ================= PAGE 3 ================= --}}
    <div class="page-break"></div>

    {{-- The obligations list follows the PROTEGE-RTW statement: paragraph 7 for supply and
         services, 8 for works. Dropped when the programme does not apply. --}}
    @if ($protege !== 'none')

    <div class="para keep-with-next">
        <span class="para-no">{{ $ref['protege_obligations'] = ++$clauseNo }}.</span>
        <span>Bagi tujuan program PROTEGE-RTW ini, syarikat tuan adalah dikehendaki untuk:</span>
    </div>

    <div class="list">
        <div class="list-item">
            <span class="list-marker">(a)</span>
            <span>mengemukakan Jadual Pelaksanaan Program PROTEGE-RTW berdasarkan tempoh kontrak
            kepada Sekretariat PROTEGE untuk kelulusan dalam tempoh dua (2) minggu selepas tarikh
            pengakuan penerimaan Surat ini oleh syarikat tuan;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(b)</span>
            <span>melaksanakan program ini mengikut Jadual Pelaksanaan Program PROTEGE-RTW yang
            diluluskan oleh Sekretariat PROTEGE;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(c)</span>
            <span>mengemaskini maklumat berkaitan pengalaman syarikat melaksanakan program
            PROTEGE-RTW dalam sistem ePerolehan di Kementerian Kewangan atau sistem di Lembaga
            Pembangunan Industri Pembinaan Malaysia (CIDB), mengikut mana yang berkaitan;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(d)</span>
            <span>mengemukakan sijil atau surat pengesahan oleh Sekretariat PROTEGE kepada Agensi
            sebaik sahaja pelaksanaan program PROTEGE-RTW selesai; dan</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(e)</span>
            <span>mengemukakan laporan berkaitan pelaksanaan program PROTEGE-RTW kepada Sekretariat
            PROTEGE.</span>
        </div>
    </div>

    @endif

    {{-- Closes the PROTEGE-RTW block with the consequence of breaching it. Paragraph 8 for
         supply and services, 9 for works. --}}
    @if ($protege !== 'none')
        @php
            // Points at whichever PROTEGE-RTW paragraphs were actually printed.
            $protegeRefs = array_filter([$ref['protege'] ?? null, $ref['protege_obligations'] ?? null]);
        @endphp

        <div class="para">
            <span class="para-no">{{ ++$clauseNo }}.</span>
            <span>Sekiranya syarikat tuan gagal mematuhi mana-mana terma di perenggan
            {{ implode(' dan ', $protegeRefs) }} atau arahan oleh Kerajaan, Kerajaan berhak untuk tidak
            mempertimbangkan sebarang tawaran kontrak baharu atau pelanjutan kontrak pada masa hadapan kepada
            syarikat tuan.</span>
        </div>
    @endif

    <div class="para">
        <span class="para-no">{{ $ref['icp'] = ++$clauseNo }}.</span>
        <span>Syarikat Tuan hendaklah melaksanakan Program Kolaborasi Industri (<em>Industrial Collaboration
        Program</em> (ICP)) yang mana pelaksanaan Program ICP tersebut adalah tertakluk kepada pematuhan kepada
        PP/PK1.7 Dasar dan Garis Panduan Program ICP Dalam Perolehan Kerajaan.</span>
    </div>

    <div class="para">
        <span class="para-no">{{ ++$clauseNo }}.</span>
        <span>Syarikat Tuan hendaklah merujuk dan mengadakan perbincangan lebih lanjut dengan pihak <em>Technology
        Depository Agency</em> (TDA) sebagai agensi pelaksana bagi menentu dan memuktamadkan butiran projek-projek
        ICP untuk dimasukkan di dalam Perjanjian Utama ICP yang akan ditandatangani antara pihak Kerajaan dengan
        Syarikat Tuan secara bersama dengan Kontrak Perolehan Utama.</span>
    </div>

    <div class="para">
        <span class="para-no">{{ ++$clauseNo }}.</span>
        <span>Syarikat Tuan hendaklah melaksanakan dan menyempurnakan projek ICP dengan jumlah Nilai Kredit ICP
        Mandatori untuk projek ICP hendaklah bersamaan dengan nilai seperti termaktub di bawah PP/PK 1.7.</span>
    </div>

    <div class="para">
        <span class="para-no">{{ ++$clauseNo }}.</span>
        <span>Syarikat Tuan hendaklah mengemukakan kepada Kerajaan, suatu Bon Pelaksanaan yang tidak boleh
        dibatalkan dalam bentuk Jaminan Bank bagi pelaksanaan ICP yang bersamaan dengan lima peratus (5%) daripada
        Nilai Kredit ICP Mandatori dalam tempoh empat belas (14) hari dari tarikh Perjanjian Utama ICP
        ditandatangani. Tempoh sah laku Bon Pelaksanaan tersebut hendaklah bermula dari tarikh ianya dikeluarkan
        sehingga dua belas (12) bulan selepas pelaksanaan penuh Program ICP dan penyempurnaan sepenuhnya jumlah
        Nilai Kredit ICP Mandatori.</span>
    </div>

    {{-- ================= PAGE 4 ================= --}}
    <div class="page-break"></div>

    <div class="para keep-with-next">
        <span class="para-no">{{ $ref['cancellation'] = ++$clauseNo }}.</span>
        <span>Syarikat tuan juga adalah diingatkan bahawa Kerajaan berhak untuk membatalkan Surat ini
        sekiranya:</span>
    </div>

    {{-- Works contracts carry their own grounds: an extra item for failing to start on site,
         another for defaulting under the Syarat-syarat Kontrak, and works-specific wording
         for the subcontracting and completion items. --}}
    @if ($kategoriPerolehan === 'kerja')

    <div class="list">
        <div class="list-item">
            <span class="list-marker">(a)</span>
            <span>syarikat tuan gagal mematuhi mana-mana terma di perenggan {{ $ref['documents'] }} dalam tempoh
            masa yang ditetapkan;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(b)</span>
            <span>syarikat tuan gagal mematuhi mana-mana terma yang dinyatakan dalam Surat Akuan Pembida
            Berjaya;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(c)</span>
            <span>syarikat tuan gagal memulakan kerja dalam tempoh dua (2) minggu dari tarikh milik tapak;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(d)</span>
            <span>syarikat tuan telah membuat salah nyataan (<em>misrepresentation</em>) atau mengemukakan maklumat
            palsu semasa berurusan dengan Kerajaan bagi perolehan ini atau melakukan apa-apa perbuatan lain, seperti
            memalsukan maklumat dalam Sijil Akuan Pendaftaran Syarikat, mengemukakan bon pelaksanaan atau dokumen
            lain yang palsu atau yang telah diubah suai;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(e)</span>
            <span>syarikat tuan membenarkan Sijil Akuan Pendaftaran Syarikat disalahgunakan oleh individu/syarikat
            lain;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(f)</span>
            <span>syarikat tuan terlibat dalam membuat pakatan harga dengan syarikat-syarikat lain atau apa-apa
            pakatan sepanjang proses {{ strtolower($jenisPerolehanLabel) }} sehingga dokumen kontrak
            ditandatangani;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(g)</span>
            <span>syarikat tuan telah memberikan subkontrak sama ada sepenuhnya atau sebahagiannya perkhidmatan
            tanpa kelulusan Kerajaan terlebih dahulu. Sekiranya Kerajaan meluluskan permohonan syarikat tuan untuk
            memberikan subkontrak sebahagian kerja atau keseluruhan kerja, kelulusan tersebut adalah tertakluk
            kepada syarikat tuan mengikat perjanjian hak (<em>Deed Of Assignment</em>) dengan Subkontraktor terlebih
            dahulu;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(h)</span>
            <span>syarikat gagal menyempurnakan kerja dalam tempoh yang ditetapkan seperti di
            <strong>Lampiran A</strong>;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(i)</span>
            <span>syarikat tuan gagal mematuhi mana-mana terma/arahan di dalam dokumen
            {{ strtolower($jenisPerolehanLabel) }};</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(j)</span>
            <span>syarikat tuan/ pemilik/ rakan kongsi/ pengarah telah disabitkan atas kesalahan jenayah di dalam
            atau luar Malaysia;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(k)</span>
            <span>syarikat tuan digulungkan;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(l)</span>
            <span>syarikat tuan membekal barang-barang yang tidak tulen, bukan baharu atau yang terpakai;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(m)</span>
            <span>kontraktor gagal/mungkir dalam melaksanakan tanggung jawabnya sepertimana ditetapkan dalam
            Syarat-syarat Kontrak;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(n)</span>
            <span>syarikat tuan tidak mendapat kelulusan daripada Kerajaan terlebih dahulu bagi apa-apa penjualan
            atau pemindahan ekuiti sepanjang tempoh kontrak ini berkuat kuasa; atau</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(o)</span>
            <span>terdapat perkara yang melibatkan kepentingan awam atau keselamatan dan kepentingan negara.</span>
        </div>
    </div>

    @else

    <div class="list">
        <div class="list-item">
            <span class="list-marker">(a)</span>
            <span>syarikat tuan gagal mematuhi mana-mana terma di perenggan {{ $ref['documents'] }} dalam tempoh
            masa yang ditetapkan;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(b)</span>
            <span>syarikat tuan gagal mematuhi mana-mana terma yang dinyatakan dalam Surat Akuan Pembida
            Berjaya;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(c)</span>
            <span>syarikat tuan telah memberikan salah nyataan (<em>misrepresentation</em>) atau mengemukakan
            maklumat palsu semasa berurusan dengan Kerajaan bagi perolehan ini atau melakukan apa-apa perbuatan
            lain, seperti memasukkan maklumat dalam Sijil Akuan Pendaftaran Syarikat, mengemukakan bon pelaksanaan
            atau dokumen lain yang palsu atau yang telah diubah suai;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(d)</span>
            <span>syarikat tuan membenarkan Sijil Akuan Pendaftaran Syarikat disalahgunakan oleh individu/syarikat
            lain;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(e)</span>
            <span>syarikat tuan terlibat dalam membuat pakatan harga dengan syarikat-syarikat lain atau apa-apa
            pakatan sepanjang proses {{ strtolower($jenisPerolehanLabel) }} sehingga dokumen kontrak
            ditandatangani;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(f)</span>
            <span>syarikat tuan telah melantik subkontrak sama ada sepenuhnya atau sebahagiannya pembekalan barang
            tanpa kelulusan Kerajaan terlebih dahulu;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(g)</span>
            <span>syarikat tuan gagal membekalkan barang/menyempurnakan perkhidmatan dalam tempoh yang ditetapkan
            seperti di <strong>Lampiran A</strong>;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(h)</span>
            <span>syarikat tuan gagal mematuhi mana-mana terma/arahan di dalam dokumen
            {{ strtolower($jenisPerolehanLabel) }};</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(i)</span>
            <span>syarikat tuan/ pemilik/ rakan kongsi/ pengarah telah disabitkan atas kesalahan jenayah di dalam
            atau luar Malaysia;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(j)</span>
            <span>syarikat tuan digulungkan;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(k)</span>
            <span>syarikat tuan membekal barang-barang yang tidak tulen, bukan baharu atau yang terpakai;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(l)</span>
            <span>syarikat tuan gagal mematuhi spesifikasi pembekalan yang ditetapkan;</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(m)</span>
            <span>syarikat tuan tidak mendapat kelulusan daripada Kerajaan terlebih dahulu bagi apa-apa penjualan
            atau pemindahan ekuiti sepanjang tempoh kontrak ini berkuat kuasa; atau</span>
        </div>
        <div class="list-item">
            <span class="list-marker">(n)</span>
            <span>terdapat perkara yang melibatkan kepentingan awam atau keselamatan dan kepentingan negara.</span>
        </div>
    </div>

    @endif

    <div class="para">
        <span class="para-no">{{ ++$clauseNo }}.</span>
        <span>Sekiranya Surat ini dibatalkan atas alasan seperti yang ditetapkan di perenggan
        {{ $ref['cancellation'] }}, Kerajaan tidak akan bertanggungan terhadap apa-apa kerugian syarikat tuan
        termasuk kerugian masa hadapan.</span>
    </div>

    <div class="para">
        <span class="para-no">{{ ++$clauseNo }}.</span>
        <span>Bersama-sama Surat ini disertakan Surat Akuan Pembida Berjaya dan Surat Akuan Sumpah Syarikat seperti
        di <strong>Lampiran B</strong> dan <strong>Lampiran C</strong> untuk ditandatangani oleh syarikat tuan dan
        dikembalikan bersama-sama dengan Surat ini.</span>
    </div>

    {{-- ================= PAGE 5 ================= --}}
    <div class="page-break"></div>

    {{-- Works contracts add two more obligations before the closing paragraph: the
         subcontractor and programme documents, and the completion date. --}}
    @if ($kategoriPerolehan === 'kerja')

        <div class="para keep-with-next">
            <span class="para-no">{{ ++$clauseNo }}.</span>
            <span>Syarikat tuan juga adalah dikehendaki untuk mengemukakan dokumen berikut bersama-sama dengan
            Surat ini yang telah ditandatangan balas oleh tuan, untuk kelulusan Pegawai Penguasa sebelum memulakan
            kerja di tapak bina:</span>
        </div>

        <div class="list">
            <div class="list-item">
                <span class="list-marker">(a)</span>
                <span>Senarai nama subkontraktor berserta pengalamannya dengan menyatakan bahagian kerja yang
                terlibat; dan</span>
            </div>
            <div class="list-item">
                <span class="list-marker">(b)</span>
                <span>Program Kerja bagi pelaksanaan projek ini.</span>
            </div>
        </div>

        <div class="para">
            <span class="para-no">{{ ++$clauseNo }}.</span>
            <span>Berdasarkan kepada Tempoh Siap Kerja yang ditenderkan selama
            <span class="blank blank-md"></span> hari/minggu/bulan, Tarikh Siap untuk seluruh kerja-kerja di bawah
            kontrak ini ialah pada <span class="blank blank-md"></span>.</span>
        </div>

    @endif

    <div class="para">
        <span class="para-no">{{ ++$clauseNo }}.</span>
        <span>Surat ini dihantar kepada syarikat tuan dalam tiga (3) salinan. Sila kembalikan ke pejabat ini salinan
        asal dan kedua beserta lampiran yang berkaitan yang telah ditandatangani dengan sempurna oleh syarikat tuan
        dan saksi syarikat tuan tidak melebihi 3/7/14 hari dari tarikh Surat ini
        diterima untuk tindakan kami selanjutnya. Apa-apa kegagalan dalam mematuhi kehendak di perenggan ini dalam
        tempoh masa yang ditetapkan boleh mengakibatkan Surat ini terbatal dan Kerajaan tidaklah dengan apa-apa jua
        bertanggungan terhadap syarikat tuan.</span>
    </div>

    <div class="para-plain" style="margin-top: 24px;">Sekian, terima kasih.</div>

    <div class="para-plain" style="font-weight: 700; margin-top: 20px;">&ldquo;BERKHIDMAT UNTUK NEGARA&rdquo;</div>

    <div class="para-plain" style="margin-top: 20px;">Saya yang menjalankan amanah,</div>

    <div class="signature" style="margin-top: 46px;">
        <div class="signature-line"></div>
        <div><span class="blank blank-lg"></span></div>
        <div><span class="blank blank-lg"></span></div>
    </div>

    {{-- ================= PAGE 6 ================= --}}
    <div class="page-break"></div>

    <div style="font-weight: 700; margin-bottom: 18px;">
        PENGAKUAN PENERIMAAN SURAT SETUJU TERIMA DAN LAMPIRAN YANG BERKAITAN OLEH SYARIKAT
    </div>

    <div class="para-plain">
        Dengan ini disahkan bahawa yang bertandatangan di bawah ini mengakui penerimaan Surat ini dan lampiran yang
        berkaitan yang rujukannya ialah <span class="blank blank-lg"></span> bertarikh
        <span class="blank blank-md"></span> dan bersetuju dengan terma dan syarat yang terkandung dalam Surat ini
        tanpa syarat yang mana salinan kepada Surat ini telah pun disimpan, dan selanjutnya disahkan bahawa tiada
        apa-apa terma, syarat atau stipulasi tambahan kepada yang terkandung dalam dokumen
        {{ strtolower($jenisPerolehanLabel) }} dan Surat ini telah dikenakan.
    </div>

    <div class="witness-grid">
        <div class="witness-col">
            <div class="witness-rule"></div>
            <div class="witness-row"><span class="witness-label">Nama Penuh</span><span>:</span></div>
            <div class="witness-row"><span class="witness-label">No. Kad Pengenalan</span><span>:</span></div>
            <div class="witness-row"><span class="witness-label">Alamat</span><span>:</span></div>
            <div class="witness-row"><span class="witness-label">Tarikh</span><span>:</span></div>
        </div>
        <div class="witness-col">
            <div class="witness-rule"></div>
            <div class="witness-row"><span class="witness-label">Nama Penuh Saksi</span><span>:</span></div>
            <div class="witness-row"><span class="witness-label">No. Kad Pengenalan</span><span>:</span></div>
            <div class="witness-row"><span class="witness-label">Alamat</span><span>:</span></div>
            <div class="witness-row"><span class="witness-label">Tarikh</span><span>:</span></div>
        </div>
    </div>

    <div style="margin-top: 30px;">Meterai atau Cap Syarikat</div>

    <div style="margin-top: 30px;"><em>*potong mana yang tidak berkenaan</em></div>

    {{-- ================= PAGE 7 ================= --}}
    <div class="page-break"></div>

    <div style="text-align: right; font-weight: 700; font-style: italic; margin-bottom: 14px;">Lampiran A</div>

    <div class="doc-title" style="margin-bottom: 10px;">BUTIRAN KONTRAK</div>

    <div class="doc-title" style="margin-bottom: 24px;">{{ $lampiranA['tajuk'] }}</div>

    <div class="section-heading">
        1. Pendaftaran Syarikat Dengan Suruhanjaya Syarikat Malaysia (SSM) Atau Pendaftaran Koperasi Dengan
        Suruhanjaya Koperasi Malaysia (SKM) (jika berkaitan)
    </div>

    @php
        // Prints a fetched value in bold, or a fill-in rule when there is no source for it.
        $val = fn ($v) => ($v ?? '') !== ''
            ? '<strong>' . e($v) . '</strong>'
            : '<span class="line"></span>';
    @endphp

    <div class="detail-row">
        <div class="detail-label">1.1 No. Pendaftaran</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">{!! $val($lampiranA['no_pendaftaran']) !!}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">1.2 Tempoh Sah Laku</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">{!! $val($lampiranA['tempoh_sah_laku_pendaftaran']) !!}</div>
    </div>

    <div class="section-heading">2. Pendaftaran dengan Kementerian Kewangan (jika berdaftar)</div>

    <div class="detail-row">
        <div class="detail-label">2.1 No. Pendaftaran</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">{!! $val($lampiranA['mof_no']) !!}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">2.2 Tempoh Sah Laku</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">{!! $val($lampiranA['mof_tempoh']) !!}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">2.3 Kod Bidang</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">{!! $val($lampiranA['mof_kod_bidang']) !!}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">2.4 Taraf Syarikat</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">{!! $val($lampiranA['mof_taraf']) !!}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">2.5 Tempoh Sah Laku Taraf Bumiputera</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">{!! $val($lampiranA['mof_tempoh_bumiputera']) !!}</div>
    </div>

    <div class="section-heading">
        3. Pendaftaran Cukai Jualan dengan Jabatan Kastam Diraja Malaysia (jika berdaftar), sekiranya berkaitan
    </div>

    <div class="detail-row">
        <div class="detail-label">3.1 No. Pendaftaran</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">{!! $val($lampiranA['cukai_no']) !!}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">3.2 Tarikh Kuat Kuasa</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">{!! $val($lampiranA['cukai_tarikh_kuat_kuasa']) !!}</div>
    </div>

    {{-- ================= PAGE 8 ================= --}}
    <div class="page-break"></div>

    <div class="section-heading" style="margin-top: 0;">4. Harga dan Tempoh Kontrak</div>

    <div class="detail-row">
        <div class="detail-label">4.1 Harga {{ $jenisPerolehanLabel }} (butiran harga seperti di
            <strong><em>Lampiran A1</em></strong>)</div>
        <div class="detail-colon">:</div>
        <div class="detail-value value-with-prefix"><span>RM</span>
            <span><strong>{{ $lampiranA['harga_tawaran'] }}</strong></span></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">4.2 Peruntukan Cukai Jualan (sekiranya berkaitan)</div>
        <div class="detail-colon">:</div>
        <div class="detail-value value-with-prefix"><span>RM</span>
            <span><strong>{{ $lampiranA['cukai_jualan'] }}</strong></span></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">4.3 Fi Perkhidmatan ePerolehan (sekiranya berkaitan)</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">{!! $val($lampiranA['fi_eperolehan']) !!}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">4.4 Harga Kontrak</div>
        <div class="detail-colon">:</div>
        <div class="detail-value value-with-prefix"><span>RM</span>
            <span><strong>{{ $lampiranA['harga_kontrak'] }}</strong></span></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">4.5 Tempoh Kontrak</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">{!! $val($lampiranA['tempoh_kontrak']) !!}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">4.6 Tarikh Mula Kontrak</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">{!! $val($lampiranA['tarikh_mula']) !!}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">4.7 Tarikh Tamat Kontrak</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">{!! $val($lampiranA['tarikh_tamat']) !!}</div>
    </div>

    <div class="section-heading">5. Tempoh dan Jadual Pembekalan Barang</div>

    <div class="para-plain">
        Senarai item, kuantiti dan/atau tempoh serta jadual pembekalan yang ditetapkan seperti di
        <strong><em>Lampiran A2</em></strong>
    </div>

    <div class="para-plain">
        <em><strong>Lampiran A2</strong> adalah untuk menyatakan tempoh atau jadual pembekalan mengikut tawaran
        syarikat dan disediakan oleh Agensi</em>
    </div>

    <div class="section-heading">6. Spesifikasi Pembekalan <em>(sekiranya berkaitan)</em></div>

    <div class="para-plain">
        Spesifikasi pembekalan yang ditetapkan seperti di <strong><em>Lampiran A3</em></strong>
    </div>

    {{-- ================= PAGE 9 ================= --}}
    <div class="page-break"></div>

    <div class="section-heading" style="margin-top: 0;">7. Bon Pelaksanaan</div>

    <div class="detail-row">
        <div class="detail-label">7.1 Kadar Bon Pelaksanaan</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">{!! $val($lampiranA['bon_kadar']) !!}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">7.2 Formula Bon Pelaksanaan</div>
        <div class="detail-colon">:</div>
        <div class="detail-value"><strong>{{ $lampiranA['bon_formula'] }}</strong></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">7.3 Nilai Bon Pelaksanaan</div>
        <div class="detail-colon">:</div>
        <div class="detail-value value-with-prefix"><span>RM</span>
            <span>{!! $val($lampiranA['bon_nilai']) !!}</span></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">7.4 Bentuk Bon Pelaksanaan</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">Jaminan Bank/ Bank Islam/ Bank Pembangunan Malaysia Berhad; atau Jaminan Syarikat
            Kewangan; atau Jaminan Insurans/ Takaful</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">7.5 Tempoh Sah Laku</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">Dari tarikh kuat kuasa kontrak sehingga 12 bulan selepas tarikh tamat kontrak atau
            tarikh obligasi terakhir mengikut mana yang terkemudian.</div>
    </div>

    <div class="para-plain" style="padding-left: 34px;">
        Mengikut format yang ditetapkan oleh Kerajaan seperti di <strong><em>Lampiran A4</em></strong>
    </div>

    <div class="section-heading">
        8. No. Kod Majikan PERKESO/No. Pendaftaran KWSP/Polisi Insurans (jika berkaitan)
    </div>

    <div class="detail-row">
        <div class="detail-label">8.1 No. Kod Majikan PERKESO</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">
            <span class="line"></span>
            <span class="note-small">(Diisi oleh syarikat)</span>
        </div>
    </div>
    <div class="detail-row">
        <div class="detail-label">8.2 No. Pendaftaran KWSP</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">
            <span class="line"></span>
            <span class="note-small">(Diisi oleh syarikat)</span>
        </div>
    </div>
    <div class="detail-row">
        <div class="detail-label">8.3 Nilai Polisi</div>
        <div class="detail-colon">:</div>
        <div class="detail-value value-with-prefix"><span>RM</span>
            <span><strong>{{ $lampiranA['nilai_polisi'] }}</strong></span></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">8.4</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">Meliputi tempoh kontrak</div>
    </div>

    <div class="section-heading">
        9. Kenaan Denda/ Tolakan/ <em>Liquidated &amp; Ascertained Damages</em> (LAD)
    </div>

    <div class="detail-row">
        <div class="detail-label">9.1 Formula</div>
        <div class="detail-colon">:</div>
        <div class="detail-value"><span class="line"></span></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">9.2 Kadar Sehari</div>
        <div class="detail-colon">:</div>
        <div class="detail-value value-with-prefix"><span>RM</span>
            <span>{!! $val($lampiranA['lad_kadar_sehari']) !!}</span></div>
    </div>

    <div class="section-heading">
        10. <em>Professional Training and Education for Growing Entrepreneurs - Ready To Work</em>
        (PROTEGE-RTW) (jika berkaitan)
    </div>

    {{-- ================= PAGE 10 ================= --}}
    <div class="page-break"></div>

    <div class="detail-row">
        <div class="detail-label">10.1 Tertakluk kepada pelaksanaan Program PROTEGE-RTW</div>
        <div class="detail-colon">:</div>
        <div class="detail-value"><strong>{{ $lampiranA['protege_tertakluk'] }}</strong></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">10.2 Bilangan minimum peserta Program PROTEGE-RTW</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">{!! $val($lampiranA['protege_peserta']) !!} peserta</div>
    </div>

    <div class="detail-row" style="margin-top: 20px;">
        <div class="detail-label">Formula</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">
            <div class="formula" style="margin: 0;">
                <div class="formula-line">1% X Harga Kontrak*</div>
                <div class="formula-line">RM24,000**</div>
            </div>
        </div>
    </div>

    <div class="section-heading">
        11. Bon Pelaksanaan Program Kolaborasi Industri (<em>Industrial Collaboration Program - ICP</em>)
    </div>

    <div class="detail-row">
        <div class="detail-label">11.1 Nilai Bon Pelaksanaan</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">{!! $val($lampiranA['icp_nilai_bon']) !!}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">11.2 Kadar Bon Pelaksanaan</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">{!! $val($lampiranA['icp_kadar_bon']) !!}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">11.3 Bentuk Bon Pelaksanaan</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">Jaminan Bank/ Bank Islam/ Bank Pembangunan Malaysia Berhad; atau Jaminan Syarikat
            Kewangan; atau Jaminan Insurans/ Takaful</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">11.4 Tempoh Sah Laku</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">Dari tarikh kuat kuasa kontrak sehingga 12 bulan selepas tarikh tamat kontrak atau
            tarikh obligasi terakhir mengikut mana yang terkemudian.</div>
    </div>

    <div style="margin: 16px 0;"><em>*potong mana yang tidak berkenaan</em></div>

    <div class="section-heading">12. Pegawai Yang Boleh Dihubungi (Pentadbir Kontrak)</div>

    <div class="detail-row">
        <div class="detail-label">12.1 Nama</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">{!! $val($lampiranA['pentadbir_nama']) !!}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">12.2 Nombor Telefon</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">{!! $val($lampiranA['pentadbir_telefon']) !!}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">12.3 Alamat E-mel</div>
        <div class="detail-colon">:</div>
        <div class="detail-value">{!! $val($lampiranA['pentadbir_emel']) !!}</div>
    </div>

    {{-- ================= PAGE 11 ================= --}}
    <div class="page-break"></div>

    <div style="font-weight: 700; text-decoration: underline; margin-bottom: 22px;">Salinan Kepada :</div>

    <div class="copy-block">
        <div>Ketua Pegawai Eksekutif</div>
        <div>Ibu Pejabat Lembaga Hasil Dalam Negeri Malaysia</div>
        <div>Menara Hasil</div>
        <div>Aras 18, Persiaran Rimba Permai, Cyber 8</div>
        <div class="copy-postcode">63000 CYBERJAYA</div>
        <div>(u.p.: Pengarah Jabatan Pematuhan Cukai)</div>
    </div>

    <div class="copy-block">
        <div>Ketua Pengarah Kastam</div>
        <div>Ibu Pejabat Kastam Diraja Malaysia</div>
        <div>Bahagian Cukai Dalam Negeri (SST)</div>
        <div>Aras 3-7, Blok A, Menara Tulus</div>
        <div>No. 22, Persiaran Perdana, Presint 3</div>
        <div class="copy-postcode">61200 PUTRAJAYA</div>
        <div>(u.p.: Pengarah Bahagian Cukai Dalam Negeri (SST))</div>
    </div>

    <div class="copy-block">
        <div>Sekretariat Professional Training and Education for Growing Entrepreneurs (PROTEGE)</div>
        <div>Aras 2, Blok E4/5, Parcel E</div>
        <div>Kementerian Pembangunan Usahawan</div>
        <div>Pusat Pentadbiran Kerajaan Persekutuan</div>
        <div class="copy-postcode">62668 PUTRAJAYA</div>
        <div>(u.p.: Ketua Sekretariat PROTEGE)</div>
    </div>

    <div class="copy-block">
        <div>Ketua Pegawai Eksekutif</div>
        <div>Kumpulan Wang Simpanan Pekerja</div>
        <div>Jabatan Penguatkuasaan</div>
        <div>Tingkat 13, Bangunan KWSP, Jalan Raja Laut</div>
        <div class="copy-postcode">50350 Kuala Lumpur</div>
        <div>(u.p.: Ketua Unit Forensik Majikan dan Hubungan Luar, Seksyen Operasi)</div>
    </div>

@endsection
