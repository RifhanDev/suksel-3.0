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

    .top-strip{
        background:#f5f0f0;
        border-bottom:1px solid #efe7e7;
        padding:10px 14px;
        font-size:11px;
        color:#374151;
    }
    .top-strip .label{ font-weight:700; color:#111827; }
    .top-strip .muted{ color:#6b7280; }

    .section-bar{
        background:var(--soft-grey);
        font-weight:800;
        text-transform:uppercase;
        font-size:12px;
        padding:8px 12px;
        border-radius:2px;
        margin:10px 0 10px;
    }

    .title-center{
        text-align:center;
        font-weight:800;
        color:var(--text);
        margin:8px 0 10px;
        text-transform:uppercase;
        font-size:13px;
        line-height:1.35;
    }

    .blue-band{
        background:var(--brand-red);
        color:#fff;
        padding:10px 12px;
        font-weight:800;
        font-size:12px;
        text-transform:uppercase;
        display:flex;
        gap:8px;
        align-items:center;
    }

    .table-7a{
        width:100%;
        border-collapse:collapse;
        table-layout:fixed;
        background:#fff;
        border:1px solid var(--line);
    }
    .table-7a th,.table-7a td{
        border:1px solid var(--line) !important;
        padding:10px 8px;
        font-size:11px;
        vertical-align:middle;
    }
    .table-7a thead th{
        background:var(--blue);
        color:#fff;
        text-align:center;
        font-weight:800;
        font-size:11px;
    }
    .table-7a tbody td{
        height:34px;
        background:#fff;
    }

    .sum-row td{
        background:#fff;
        font-weight:700;
        font-size:11px;
    }
    .sum-label{
        padding-left:12px !important;
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

    {{-- Top strip (optional) --}}
    <div class="top-strip">
        <div class="d-flex flex-wrap gap-4 justify-content-between">
            <div>
                <span class="label">No. Sebut Harga / Tender</span>
                <span class="muted ms-2">QT210000000023741</span>
            </div>
            <div>
                <span class="label">PTJ</span>
                <span class="muted ms-2">BAHAGIAN PENTADBIRAN - CAWANGAN KEWANGAN - KEMENTERIAN KEWANGAN</span>
            </div>
            <div>
                <span class="label">Status</span>
                <span class="muted ms-2">Menunggu Pengesahan Jawatan Pembuka</span>
            </div>
            <div>
                <span class="label">Sah Laku Tawaran Tamat</span>
                <span class="muted ms-2">17/01/2022</span>
            </div>
        </div>
    </div>

    <div class="section-bar">BORANG 7a - ANALISA NILAI BAKI KERJA DALAM TANGAN</div>

    <div class="title-center">
        ANALISA DATA-DATA PENILAIAN KEUPAYAAN PETENDER<br>
        NILAI BAKI KERJA DALAM TANGAN (SEBANDING)
    </div>

    <div class="blue-band">
        <span>NO. RUJUKAN PETENDER :</span>
        <span>45/53</span>
    </div>

    <div class="table-responsive">
        <table class="table-7a">
            <thead>
            <tr>
                <th style="width:50px;">BIL</th>
                <th style="width:140px;">NAMA KONTRAK SEMASA</th>
                <th style="width:105px;">NILAI KONTRAK (RM)</th>
                <th style="width:165px;">NILAI WANG KOS PRIMA &amp; PERUNTUKAN SEMENTARA (RM)</th>
                <th style="width:95px;">NILAI KERJA PEMBINA (RM)</th>
                <th style="width:65px;">PERATUS SIAP (%)</th>
                <th style="width:80px;">PERATUS BELUM SIAP (%)</th>
                <th style="width:80px;">Tarikh Jangka Siap Sebenar</th>
                <th style="width:85px;">BAKI TEMPOH PENYIAPAN (Bulan)</th>
                <th style="width:75px;">NILAI KERJA YANG TELAH DISIAPKAN</th>
                <th style="width:120px;">NILAI TAHUNAN BAKI KERJA DALAM TANGAN (NBK) (RM)</th>
                <th style="width:105px;">NILAI BAKI KERJA DALAM TANGAN (NBK) (RM)</th>
            </tr>
            </thead>

            <tbody>
            @for($i=1;$i<=5;$i++)
                <tr>
                    <td class="text-center"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endfor

            <tr class="sum-row">
                <td class="text-center"></td>
                <td class="sum-label" colspan="11">JUMLAH NILAI KERJA YANG TELAH DISIAPKAN bawa ke Butiran 3 Borang 9</td>
            </tr>
            <tr class="sum-row">
                <td class="text-center"></td>
                <td class="sum-label" colspan="11">JUMLAH NILAI TAHUNAN BAKI KERJA (NTBK) bawa ke Butiran 12 Borang 8</td>
            </tr>
            <tr class="sum-row">
                <td class="text-center"></td>
                <td class="sum-label" colspan="11">JUMLAH NILAI BAKI KERJA (NBK) bawa ke Borang 14</td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-4">
        <button type="button" class="btn-simpan" onclick="showB7aSebandingSuccess()">Simpan</button>
    </div>

</div>

{{-- MODAL SUCCESS --}}
<div class="modal fade" id="b7aSebandingSuccessModal" tabindex="-1" aria-hidden="true">
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
    function showB7aSebandingSuccess(){
        const modal = new bootstrap.Modal(document.getElementById('b7aSebandingSuccessModal'));
        modal.show();
    }
</script>
@endsection
