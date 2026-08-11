@extends('layouts.v3.master')

@section('content')
<style>
   :root{
        --brand-red:#B11217;
        --soft-grey:#e9ecef;
        --line:#d9dde5;

        --btn-primary: var(--brand-red);
        --btn-primary-hover:#991014;

        --btn-teal:#19c1a7;
        --btn-green:#17a34a;
    }


    .section-bar{
        background:var(--soft-grey);
        font-weight:800;
        text-transform:uppercase;
        font-size:12px;
        padding:8px 12px;
        border-radius:2px;
        margin:10px 0 10px;
    }

    .table-b8{
        width:100%;
        border-collapse:collapse;
        table-layout:fixed;
        background:#fff;
        border:1px solid var(--line);
    }
    .table-b8 th,.table-b8 td{
        border:1px solid var(--line) !important;
        padding:10px 10px;
        font-size:11px;
        vertical-align:top;
    }
    .table-b8 thead th{
        background:var(--brand-red);
        color:#fff;
        text-align:center;
        font-weight:800;
        font-size:11px;
    }
    .table-b8 .col-bil{ width:55px; text-align:center; }
    .table-b8 .col-butiran{ width:560px; }

    .butiran-list{
        margin:0;
        padding-left:0;
        list-style:none;
        line-height:1.55;
        white-space:normal;
    }
    .butiran-list li{
        display:flex;
        gap:12px;
        align-items:flex-start;
    }
    .butiran-list .no{
        width:18px;
        text-align:right;
        font-weight:700;
        color:#111827;
        flex:0 0 18px;
    }
    .butiran-list .txt{
        flex:1;
        color:#111827;
    }

    .notes{
        font-size:10px;
        color:#111827;
        line-height:1.55;
        padding:10px 12px;
    }
    .notes .title{
        font-weight:800;
        margin-bottom:6px;
    }
    .notes .bul{
        margin:0;
        padding-left:14px;
    }
    .notes .bul li{ margin-bottom:6px; }
    .notes .formula{
        margin-top:10px;
        white-space:pre-wrap;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-size:10px;
    }

    .rm-row{
        display:flex;
        justify-content:flex-end;
        align-items:center;
        gap:10px;
        margin-top:10px;
    }
    .rm-row .rm{
        font-weight:700;
        font-size:11px;
        color:#111827;
    }
    .rm-row input{
        width:140px;
        height:24px;
        border:1px solid #9ca3af;
        border-radius:3px;
        font-size:11px;
        padding:2px 8px;
    }

    .btn-simpan{
        background:var(--btn-teal);
        color:#fff;
        border:0;
        padding:10px 18px;
        border-radius:6px;
        font-weight:800;
        min-width:110px;
    }

    /* Success modal */
    .modal-card{
        border-radius:10px;
        border:0;
        box-shadow:0 10px 30px rgba(0,0,0,.15);
        padding:18px 18px 14px;
        text-align:center;
    }
    .confetti{ width:44px;height:44px;margin:6px auto 8px; }
    .btn-modal{
        background:var(--blue);
        color:#fff;
        border:0;
        padding:10px 20px;
        border-radius:6px;
        font-weight:800;
        min-width:110px;
    }
</style>

<div class="container-fluid mt-2">

    <div class="section-bar">BORANG 8 - ANALISA DATA-DATA PENILAIAN KEUPAYAAN PETENDER</div>

    <div class="table-responsive">
        <table class="table-b8">
            <thead>
            <tr>
                <th colspan="3">KEDUDUKAN KEWANGAN PETENDER</th>
            </tr>
            <tr>
                <th colspan="3" style="text-align:left;">No. Ruj. Petender</th>
            </tr>
            <tr>
                <th class="col-bil">Bil.</th>
                <th class="col-butiran">Butiran</th>
                <th style="width:240px;"></th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td class="col-bil"></td>
                <td class="col-butiran">
                    <ul class="butiran-list">
                        <li><span class="no">1</span><span class="txt">Modal Pusingan (MP) *</span></li>
                        <li><span class="no">2</span><span class="txt">Jumlah Asset (JA) **</span></li>
                        <li><span class="no">3</span><span class="txt">Jumlah Liabiliti (JL) **</span></li>
                        <li><span class="no">4</span><span class="txt">Nett Worth (NW) = (JA - JL)</span></li>
                        <li><span class="no">5</span><span class="txt">Jumlah Baki Nilai Kemudahan Kredit yang telah diperolehi (KK)**</span></li>
                        <li><span class="no">6</span><span class="txt">Wang Dalam Tangan Semasa (WDTS)</span></li>
                        <li><span class="no">7</span><span class="txt">Harga Tender (T)</span></li>
                        <li><span class="no">8</span><span class="txt">Harga Tender mengikut Anggaran Jabatan (AJ)</span></li>
                        <li><span class="no">9</span><span class="txt">Nilai Wang Kos Prima (WKP) dan</span></li>
                        <li><span class="no">10</span><span class="txt">Wang Peruntukan Sementara (WPS)</span></li>
                        <li><span class="no">11</span><span class="txt">Tempoh Siap Penegang (TSP) :</span></li>
                        <li><span class="no">12</span><span class="txt">Tempoh Penyiapan yang di Tender (TS)</span></li>
                        <li><span class="no">13</span><span class="txt">Jumlah Nilai Tahunan Baki Kerja Dalam Tangan (NTBK di bawa dari Borang 7)</span></li>
                        <li><span class="no">14</span><span class="txt">Nilai Keupayaan Biayawan (KB) +</span></li>
                        <li><span class="no">15</span><span class="txt">Nilai Tahunan Projek (NTP) = [ AJ-(WKP+WPS)/TSP ]</span></li>
                        <li><span class="no">16</span><span class="txt">Peratus Nilai Keupayaan Biayawan berbanding dengan Nilai Tahunan Projek, [ (KB) x 100/(NTP) ]</span></li>
                    </ul>
                </td>
                <td></td>
            </tr>

            <tr>
                <td colspan="3" class="notes">
                    <div class="title">Catatan : (Semua tempoh hendaklah dalam dua titik perpuluhan)</div>

                    <ul class="bul">
                        <li><b>*</b> Modal Pusingan (MP) adalah perbezaan antara Asset Semasa dan Liabiliti Semasa petender seperti yang dinyatakan dalam Lembaran Imbangan dan dicontohi nilai positif perbezaan antara WDTS (Penyata Akaun) dengan WDT (Lembaran Imbangan) [MP= (Aset Semasa - Liabiliti Semasa) + Nilai positif (WDTS - WDT)].</li>

                        <li><b>**</b> Nilai ini hendaklah seperti yang dinyatakan dalam Lembaran Imbangan seperti yang terdapat dalam Akaun Syarikat yang diaudit oleh Juru Audit bertauliah bagi tahun kewangan terakhir atau sekiranya tiada, bagi tahun kewangan setahun sebelumnya.</li>

                        <li><b>***</b> Nilai-nilai ini hendaklah seperti yang dinyatakan dalam Laporan Bank mengenai kedudukan kewangan petender.</li>
                    </ul>

                    <div class="formula">
1) (KB) = [ (10 x MP) + (5 x (NW - MP)) ] - [0.5 x NTBK]
2) (KB) = [ (10 x MP) + (9 x KK) ] - [0.5 x NTBK]
3) (KB) = [ (10 x WDTS) + (9 x KK) ] - (0.5 x NTBK)

'Yang mana lebih tinggi.
                    </div>

                    <div class="rm-row">
                        <span class="rm">RM</span>
                        <input type="text" placeholder="">
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-4">
        <button type="button" class="btn-simpan" onclick="showB8Success()">Simpan</button>
    </div>

</div>

{{-- MODAL SUCCESS --}}
<div class="modal fade" id="b8SuccessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
        <div class="modal-content modal-card">
            <svg class="confetti" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16 42 L34 28 L30 50 Z" stroke="#19c1a7" stroke-width="3" fill="none"/>
                <path d="M38 16 C44 20, 48 24, 52 30" stroke="#19c1a7" stroke-width="3" fill="none"/>
                <path d="M40 40 L52 46" stroke="#19c1a7" stroke-width="3"/>
                <path d="M22 18 L18 12" stroke="#19c1a7" stroke-width="3"/>
            </svg>

            <div class="fw-bold" style="font-size:16px;margin-bottom:14px;">
                Maklumat telah berjaya disimpan
            </div>

            <button type="button" class="btn-modal" data-bs-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>

<script>
    function showB8Success(){
        const modal = new bootstrap.Modal(document.getElementById('b8SuccessModal'));
        modal.show();
    }
</script>
@endsection
