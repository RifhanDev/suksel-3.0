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
        margin:12px 0 10px;
    }

    .title-center{
        text-align:center;
        font-weight:800;
        font-size:12px;
        margin:0 0 8px;
        text-transform:uppercase;
    }

    .table-form{
        width:100%;
        border-collapse:collapse;
        table-layout:fixed;
        background:#fff;
        border:1px solid var(--line);
    }
    .table-form th, .table-form td{
        border:1px solid var(--line);
        padding:10px 8px;
        font-size:11px;
        vertical-align:middle;
    }
    .table-form thead th{
        background:var(--brand-red);
        color:#fff;
        font-weight:800;
        text-align:center;
    }
    .subnote{
        font-size:11px;
        margin-top:10px;
        line-height:1.5;
    }

    .btn-simpan{
        background:var(--btn-teal);
        color:#fff;
        border:0;
        padding:9px 18px;
        border-radius:4px;
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
        background:#2f3f73;
        color:#fff;
        border:0;
        padding:10px 20px;
        border-radius:6px;
        font-weight:800;
        min-width:110px;
    }
</style>

<div class="container-fluid mt-3">

    <div class="section-bar">BORANG 13 -</div>

    <div class="title-center">PENILAIAN PERINGKAT KETIGA</div>
    <div class="title-center" style="font-weight:700;">
        PENILAIAN KEUPAYAAN PETENDER BERDASARKAN FAKTOR PELARASAN BAKI KERJA (FRPK)
    </div>

    <div class="table-responsive">
        <table class="table-form">
            <thead>
                <tr>
                    <th style="width:160px;">No. Rujukan. Tender<br>(a)</th>
                    <th style="width:110px;">Harga Tender Asal<br>(b)</th>
                    <th style="width:130px;">Nilai Baki Kerja Dalam Tangan<br>(Dari Borang 7)<br>(c)</th>
                    <th style="width:150px;">Markah Keseluruhan Penilaian Keupayaan<br>(Dari Borang 12)<br>(d)</th>
                    <th style="width:130px;">Faktor Pelarasan Baki Kerja (FRPK)<br>(e)=(b)/(0.5x(c))</th>
                    <th style="width:160px;">Markah Keseluruhan Penilaian Keupayaan Terlaras<br>(f)=(d)x(e)</th>
                </tr>
            </thead>
            <tbody>
                @for($i=1;$i<=10;$i++)
                    <tr>
                        <td></td>
                        <td class="text-end"></td>
                        <td class="text-end"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>

    <div class="subnote">
        <strong>Nota # :</strong><br>
        a) Nilai Maksima = 1<br>
        b) Jika nilai baki kerja (Di ruang (c)) = 0 atau petender tidak mempunyai kerja semasa, nilai FRBK (Di ruang (e)) = 1
    </div>

    <div class="d-flex justify-content-end mt-3">
        <button type="button" class="btn-simpan" onclick="showSuccessModalB13()">Simpan</button>
    </div>

</div>

{{-- MODAL: SIMPAN SUCCESS --}}
<div class="modal fade" id="successModalB13" tabindex="-1" aria-hidden="true">
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
    function showSuccessModalB13(){
        const modal = new bootstrap.Modal(document.getElementById('successModalB13'));
        modal.show();
    }
</script>
@endsection