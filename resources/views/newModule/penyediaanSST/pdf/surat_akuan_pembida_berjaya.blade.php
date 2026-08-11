@extends('newModule.penyediaanSST.pdf.layout')

@section('title', 'Surat Akuan Pembida Berjaya - ' . $noTender)

@push('styles')
<style>
    @page {
        /* Order: top right bottom left — wide bottom holds the signature block. */
        margin: 30mm 20mm 36mm 26mm;

        /* Two-line running header; the tender number is filled in later, above the dots. */
        @top-left {
            content: "Pekeliling Perbendaharaan Malaysia\A\ANo. Tender/Kontrak : ...............................";
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        @top-right {
            content: "PK 4.2";
            font-family: Arial, sans-serif;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }

        @bottom-left {
            content: "Tandatangan & Cap (Kerajaan) : ..................\ATandatangan & Cap (Syarikat) : ..................";
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        @bottom-right {
            content: counter(page) " daripada " counter(pages);
            font-family: Arial, sans-serif;
            font-size: 10px;
            white-space: nowrap;
        }
    }

    /* paged.js splits both margin boxes into 3 equal thirds — the header and the
       signature block need far more than a third to stay on one line. These boxes
       already span exactly the body text width (the corner holders either side are
       the page margins), so only vertical padding is needed to lift them off the edge. */
    .pagedjs_margin-top {
        grid-template-columns: 70% 5% 25% !important;
        padding-top: 10mm;
    }

    .pagedjs_margin-bottom {
        grid-template-columns: 70% 5% 25% !important;
        padding-top: 6mm;
        padding-bottom: 10mm;
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

    /* Renders the \A escapes above as real line breaks so the header and the two
       signature lines stack instead of running together. */
    .pagedjs_margin-top-left .pagedjs_margin-content,
    .pagedjs_margin-bottom-left .pagedjs_margin-content {
        white-space: pre;
        line-height: 1.6;
    }

    .lampiran-label {
        text-align: right;
        font-weight: 700;
        font-style: italic;
        margin-bottom: 14px;
    }

    .doc-subtitle {
        text-align: center;
        font-weight: 700;
        text-decoration: underline;
        margin-bottom: 26px;
    }

    /* Sub-numbered clause (2.1, 3.1) — wider marker than the layout's default. */
    .sub-list {
        padding-left: 34px;
        margin-bottom: 14px;
    }

    .sub-item {
        display: flex;
        gap: 12px;
        margin-bottom: 12px;
        text-align: justify;
    }

    .sub-marker {
        flex: 0 0 30px;
    }

    /* Declaration fields use a solid rule, not the dotted fill-in style. */
    .decl-row {
        display: flex;
        align-items: baseline;
        gap: 8px;
        margin-bottom: 10px;
    }

    .decl-label {
        flex: 0 0 110px;
    }

    .decl-colon {
        flex: 0 0 8px;
    }

    .decl-line {
        flex: 1;
        border-bottom: 1px solid #000;
    }

    .notes {
        margin-top: 26px;
        font-size: 10.5px;
    }

    .notes-title {
        margin-bottom: 8px;
    }

    .notes-item {
        display: flex;
        gap: 10px;
        margin-bottom: 6px;
        text-align: justify;
        line-height: 1.5;
    }

    .notes-marker {
        flex: 0 0 30px;
    }
</style>
@endpush

@section('content')

    {{-- ================= PAGE 1 ================= --}}

    <div class="lampiran-label">Lampiran B</div>

    <div class="doc-title" style="margin-bottom: 8px;">SURAT AKUAN PEMBIDA BERJAYA</div>
    <div class="doc-subtitle"><span class="blank blank-lg"></span></div>

    <div class="para-plain">
        Saya, <span class="blank blank-md"></span> No. Kad Pengenalan <span class="blank blank-md"></span> yang
        mewakili <span class="blank blank-md"></span> nombor Pendaftaran <span class="blank blank-md"></span>
        dengan ini mengisytiharkan bahawa saya atau mana-mana orang yang mewakili syarikat ini:
    </div>

    <div class="sub-list">
        <div class="sub-item">
            <span class="sub-marker">i.</span>
            <span>tidak akan menawarkan, menjanjikan atau memberikan apa-apa suapan kepada mana-mana orang dalam
            mana-mana Kementerian/Agensi atau mana-mana orang lain, sebagai suapan untuk dipilih dalam mana-mana
            perolehan; dan</span>
        </div>
        <div class="sub-item">
            <span class="sub-marker">ii.</span>
            <span>tidak akan melakukan atau terlibat dengan tipuan bida dalam mana-mana perolehan.</span>
        </div>
    </div>

    <div class="para-plain">
        Bersama ini dilampirkan Surat Perwakilan Kuasa bagi saya mewakili syarikat seperti tercatat di atas untuk
        membuat pengisytiharan ini.
    </div>

    <div class="para">
        <span class="para-no">2.</span>
        <span>Sekiranya saya, atau mana-mana individu yang mewakili syarikat ini didapati terlibat dalam membuat
        pakatan harga dengan syarikat lain atau apa-apa pakatan sepanjang proses perolehan ini atau menawarkan,
        menjanjikan atau memberikan apa-apa suapan kepada mana-mana orang dalam <span class="blank blank-md"></span>
        atau mana-mana orang lain sebagai dorongan untuk dipilih dalam perolehan seperti di atas, maka saya sebagai
        wakil syarikat bersetuju tindakan-tindakan berikut boleh diambil:</span>
    </div>

    <div class="sub-list">
        <div class="sub-item">
            <span class="sub-marker">2.1</span>
            <span>Penarikan balik tawaran kontrak bagi perolehan di atas; atau</span>
        </div>
        <div class="sub-item">
            <span class="sub-marker">2.2</span>
            <span>Penamatan kontrak bagi perolehan di atas; dan</span>
        </div>
        <div class="sub-item">
            <span class="sub-marker">2.3</span>
            <span>Lain-lain tindakan undang-undang/tatatertib mengikut undang-undang/peraturan perolehan Kerajaan
            yang berkuat-kuasa.</span>
        </div>
    </div>

    <div class="para keep-with-next">
        <span class="para-no">3.</span>
        <span>Saya sesungguhnya faham bahawa:</span>
    </div>

    <div class="sub-list">
        <div class="sub-item">
            <span class="sub-marker">3.1</span>
            <span>saya atau mana-mana orang yang berkaitan dengan syarikat boleh didakwa bagi kesalahan** di bawah
            Akta Suruhanjaya Pencegahan Rasuah Malaysia 2009 <em>[Akta 694]</em> dan Kanun Keseksaan
            <em>[Akta 574]</em> serta boleh dihukum di bawah undang-undang masing-masing atas kegagalan saya atau
            mana-mana orang yang mewakili syarikat ini untuk mematuhi perkara (i) dalam surat akuan ini; atau</span>
        </div>
        <div class="sub-item">
            <span class="sub-marker">3.2</span>
            <span>tindakan boleh dikenakan ke atas syarikat di bawah Akta Persaingan 2010 <em>[Akta 712]</em> atas
            kegagalan saya atau mana-mana orang yang mewakili syarikat ini untuk mematuhi perkara (ii). Sekiranya
            syarikat didapati melanggar peruntukan seksyen 4(2)(d) Akta 712, syarikat boleh didenda tidak melebihi
            sepuluh peratus (10%) daripada pusing ganti (<em>turn over</em>) seluruh dunia sepanjang tempoh
            pelanggaran itu berlaku.</span>
        </div>
    </div>

    {{-- ================= PAGE 2 ================= --}}
    <div class="page-break"></div>

    <div class="para">
        <span class="para-no">4.</span>
        <span>Sekiranya terdapat mana-mana orang cuba memperolehi atau meminta apa-apa suapan daripada saya atau
        mana-mana orang yang berkaitan dengan syarikat ini sebagai dorongan untuk dipilih dalam perolehan seperti di
        atas, maka saya berjanji akan dengan segera melaporkan perbuatan tersebut kepada pejabat Suruhanjaya
        Pencegahan Rasuah Malaysia (SPRM) atau balai polis yang berhampiran. Saya sedar bahawa kegagalan saya
        berbuat demikian adalah merupakan suatu kesalahan di bawah seksyen 25 (1) Akta Suruhanjaya Pencegahan Rasuah
        Malaysia 2009 <em>[Akta 694]</em> dan boleh dihukum di bawah seksyen 25 (2) akta yang sama, apabila
        disabitkan boleh didenda tidak melebihi RM100,000 atau penjara selama tempoh tidak melebihi sepuluh tahun
        atau kedua-duanya.</span>
    </div>

    <div class="para">
        <span class="para-no">5.</span>
        <span>Saya sesungguhnya faham bahawa syarikat melakukan kesalahan jika seseorang yang bersekutu dengan
        syarikat*** memberikan, menjanjikan atau menawarkan suapan untuk memperoleh atau mengekalkan perniagaan atau
        faedah dalam menjalankan perniagaan di bawah seksyen 17A Akta Suruhanjaya Pencegahan Rasuah Malaysia 2009
        <em>[Akta 694]</em>, apabila disabitkan kesalahan boleh didenda tidak kurang daripada sepuluh kali ganda
        jumlah atau nilai suapan, atau RM1 juta, atau dipenjarakan selama tempoh tidak melebihi dua puluh tahun atau
        kedua-duanya.</span>
    </div>

    <div style="margin-top: 30px;">Yang benar,</div>

    <div style="margin-top: 26px;">
        <div class="decl-row">
            <span class="decl-label">Tandatangan</span>
            <span class="decl-colon">:</span>
            <span class="decl-line"></span>
        </div>
        <div class="decl-row">
            <span class="decl-label">Nama</span>
            <span class="decl-colon">:</span>
            <span class="decl-line"></span>
        </div>
        <div class="decl-row">
            <span class="decl-label">No.KP</span>
            <span class="decl-colon">:</span>
            <span class="decl-line"></span>
        </div>
        <div class="decl-row">
            <span class="decl-label">Tarikh</span>
            <span class="decl-colon">:</span>
            <span class="decl-line"></span>
        </div>
        <div class="decl-row">
            <span class="decl-label">Cap Syarikat</span>
            <span class="decl-colon">:</span>
            <span class="decl-line"></span>
        </div>
    </div>

    <div class="notes">
        <div class="notes-title">Catatan:</div>
        <div class="notes-item">
            <span class="notes-marker">(i)</span>
            <span>**termasuk kesalahan ditetapkan dalam Jadual (Perenggan 3 (a), takrif &ldquo;kesalahan
            ditetapkan&rdquo;) Akta Suruhanjaya Pencegahan Rasuah Malaysia 2009 <em>[Akta 694]</em> yang boleh
            dihukum di bawah Kanun Keseksaan <em>[Akta 574]</em>.</span>
        </div>
        <div class="notes-item">
            <span class="notes-marker">(ii)</span>
            <span>***seseorang yang bersekutu dengan syarikat merujuk kepada seksyen 17A (6) Akta Suruhanjaya
            Pencegahan Rasuah Malaysia 2009 <em>[Akta 694]</em>, iaitu seseorang itu bersekutu dengan organisasi
            komersial jika dia seorang pengarah, pekongsi atau pekerja organisasi komersial itu atau dia ialah orang
            yang melaksanakan perkhidmatan untuk atau bagi pihak organisasi komersial itu.</span>
        </div>
        <div class="notes-item">
            <span class="notes-marker">(iii)</span>
            <span>Surat Akuan ini hendaklah dikemukakan bersama surat perwakilan kuasa.</span>
        </div>
        <div class="notes-item">
            <span class="notes-marker">(iv)</span>
            <span>Takrifan perusahaan di bawah Akta 712 merangkumi syarikat yang terlibat dengan perolehan
            Kerajaan.</span>
        </div>
    </div>

@endsection
