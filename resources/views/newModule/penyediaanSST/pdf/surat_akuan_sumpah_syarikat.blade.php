@extends('newModule.penyediaanSST.pdf.layout')

@section('title', 'Surat Akuan Sumpah Syarikat - ' . $noTender)

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

    /* Lettered clause (a)-(e). */
    .clause-list {
        padding-left: 34px;
        margin-bottom: 14px;
    }

    .clause-item {
        display: flex;
        gap: 12px;
        margin-bottom: 12px;
        text-align: justify;
    }

    .clause-marker {
        flex: 0 0 26px;
    }

    /* Statutory declaration block. The sample braces these two columns with a
       column of ")" characters — a typewriter-era device, dropped here. */
    .oath {
        display: flex;
        gap: 30px;
        margin: 26px 0 10px;
        break-inside: avoid;
    }

    .oath-left {
        flex: 0 0 52%;
    }

    .oath-right {
        flex: 1;
        align-self: center;
    }

    .oath-field {
        display: flex;
        gap: 8px;
        margin-top: 10px;
    }

    .commissioner {
        text-align: center;
        margin-top: 34px;
        break-inside: avoid;
    }

    .commissioner-line {
        width: 260px;
        border-bottom: 1px dotted #000;
        margin: 40px auto 6px;
    }

    .notes {
        margin-top: 30px;
    }

    .notes-title {
        margin-bottom: 8px;
    }

    .notes-item {
        display: flex;
        gap: 10px;
        margin-bottom: 6px;
        text-align: justify;
    }

    .notes-marker {
        flex: 0 0 24px;
    }
</style>
@endpush

@section('content')

    <div class="lampiran-label">Lampiran C</div>

    <div class="doc-title" style="margin-bottom: 24px;">SURAT AKUAN SUMPAH SYARIKAT</div>

    <div class="para-plain">
        Saya <span class="blank blank-lg"></span> nombor kad pengenalan <span class="blank blank-md"></span> yang
        mewakili syarikat <span class="blank blank-lg"></span> nombor pendaftaran
        <span class="blank blank-md"></span> (*MOF/CIDB/SSM) dengan sesungguhnya dan sebenarnya mengaku bahawa:
    </div>

    <div class="clause-list">
        <div class="clause-item">
            <span class="clause-marker">(a)</span>
            <span>syarikat <strong>TIDAK</strong> membuat salah nyataan (<em>misrepresentation</em>) atau
            mengemukakan maklumat palsu semasa berurusan dengan Kerajaan bagi perolehan ini atau melakukan apa-apa
            perbuatan lain, seperti memasukkan maklumat dalam Sijil Akuan Pendaftaran Syarikat, mengemukakan bon
            pelaksanaan atau dokumen lain yang palsu atau yang telah diubah suai;</span>
        </div>
        <div class="clause-item">
            <span class="clause-marker">(b)</span>
            <span>syarikat <strong>TIDAK</strong> membenarkan Sijil Akuan Pendaftaran Syarikat disalahgunakan oleh
            individu/syarikat lain;</span>
        </div>
        <div class="clause-item">
            <span class="clause-marker">(c)</span>
            <span>syarikat <strong>TIDAK</strong> terlibat dalam membuat pakatan harga dengan syarikat-syarikat lain
            atau apa-apa pakatan sepanjang proses {{ strtolower($jenisPerolehanLabel) }} sehingga dokumen kontrak
            ditandatangani;</span>
        </div>
        <div class="clause-item">
            <span class="clause-marker">(d)</span>
            <span>syarikat/ pemilik/ rakan kongsi/ pengarah <strong>TIDAK</strong> disabitkan atas kesalahan jenayah
            di dalam atau luar Malaysia; dan</span>
        </div>
        <div class="clause-item">
            <span class="clause-marker">(e)</span>
            <span>syarikat <strong>TIDAK</strong> digulungkan.</span>
        </div>
    </div>

    <div class="para-plain">
        Sekiranya pada bila-bila masa, dibuktikan bahawa pengisytiharan perenggan di atas adalah tidak benar,
        Kerajaan berhak menarik balik tawaran kontrak atau menamatkan perkhidmatan syarikat bagi projek ini.
    </div>

    <div class="para-plain">
        Dan saya membuat Surat Akuan Bersumpah ini dengan kepercayaan bahawa apa-apa yang tersebut di dalamnya adalah
        benar serta menurut Akta Akuan Berkanun 1960.
    </div>

    <div class="oath">
        <div class="oath-left">
            <div>Diperbuat dan dengan</div>
            <div>sebenar-benarnya diakui oleh</div>
            <div class="line" style="margin-top: 18px;"></div>
            <div class="oath-field"><span>di</span><span class="line-fill"></span></div>
            <div class="oath-field"><span>pada</span><span class="line-fill"></span></div>
        </div>
        <div class="oath-right">
            <div class="oath-field"><span>Tandatangan</span><span class="line-fill"></span></div>
        </div>
    </div>

    <div class="commissioner">
        <div>Di hadapan saya,</div>
        <div class="commissioner-line"></div>
        <div>Pesuruhjaya Sumpah</div>
    </div>

    <div class="notes">
        <div class="notes-title">Catatan:</div>
        <div class="notes-item">
            <span class="notes-marker">i.</span>
            <span>*Potong mana yang tidak berkenaan.</span>
        </div>
        <div class="notes-item">
            <span class="notes-marker">ii.</span>
            <span>Surat akuan ini hendaklah ditandatangani oleh hanya penama di sijil pendaftaran MOF/CIDB.</span>
        </div>
    </div>

@endsection
