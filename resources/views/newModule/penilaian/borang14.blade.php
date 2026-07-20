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
    .top-white th{
        background:#fff !important;
        color:#111 !important;
        font-weight:800;
        text-align:center;
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

    .note{
        font-size:11px;
        margin-top:10px;
        line-height:1.5;
    }
</style>

<div class="container-fluid mt-3">

    <div class="section-bar">BORANG 14 - JADUAL KEPUTUSAN PENILAIAN PERINGKAT KETIGA</div>

    <div class="table-responsive">
        <table class="table-form">
            <thead class="thead-top">
                <tr>
                    {{-- kiri besar "TENDER UNTUK :" rowspan 3 ikut gambar --}}
                    <th class="left-title" colspan="3" rowspan="3" style="width:420px;">
                        TENDER UNTUK :
                    </th>

                    <th colspan="2">KEDUDUKAN TENDER</th>
                    <th colspan="2">Markah Keseluruhan Penilaian Keupayaan Minimum</th>
                </tr>

                <tr>
                    <th colspan="2">Petender Dibawah Pelaras CutOff</th>
                    <th style="width:90px;">PB*</th>
                    <th style="width:90px;">PBP**</th>
                </tr>

                <tr>
                    <th colspan="2">Petender Diatas Pelaras CutOff</th>
                    <th>50.00</th>
                    <th>50.00</th>
                </tr>
            </thead>

            {{-- header biru --}}
            <thead class="thead-blue">
                <tr>
                    <th style="width:170px;">No. Rujukan. Tender</th>
                    <th style="width:120px;">Harga Tender Asal<br>(RM)</th>
                    <th style="width:120px;">Status Petender</th>
                    <th style="width:260px;">Markah Keseluruhan Penilaian Keupayaan Terlaras (Dari Borang 13)</th>
                    <th style="width:180px;">Kedudukan Petender</th>
                    <th style="width:120px;">Score CIDB</th>
                </tr>
            </thead>

            <tbody>
                @for($i=1;$i<=9;$i++)
                    <tr>
                        <td></td>
                        <td class="text-end"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>

    <div class="note">
        * PB &nbsp;&nbsp; - Petender Berpengalaman<br>
        ** PBP - Petender Belum Berpengalaman
    </div>

    <div class="d-flex justify-content-end mt-3">
        <button type="button" class="btn-simpan" onclick="showSuccessModalB14()">Simpan</button>
    </div>

</div>

{{-- MODAL: SIMPAN SUCCESS --}}
<div class="modal fade" id="successModalB14" tabindex="-1" aria-hidden="true">
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
    function showSuccessModalB14(){
        const modal = new bootstrap.Modal(document.getElementById('successModalB14'));
        modal.show();
    }
</script>
@endsection