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

    .page-title{
        text-align:center;
        font-weight:800;
        font-size:12px;
        text-transform:uppercase;
        margin:10px 0 10px;
    }

    .table-form{
        width:100%;
        border-collapse:collapse;
        table-layout:fixed;
        background:#fff;
        border:1px solid var(--line);
    }
    .table-form th,.table-form td{
        border:1px solid var(--line);
        padding:10px 10px;
        font-size:11px;
        vertical-align:top;
    }
    .table-form thead th{
        background:var(--brand-red);
        color:#fff;
        text-align:center;
        font-weight:800;
    }

    .note{
        font-size:10px;
        margin-top:12px;
        line-height:1.35;
        color:#111827;
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
        background:#223a8f;
        color:#fff;
        border:0;
        padding:10px 20px;
        border-radius:6px;
        font-weight:800;
        min-width:110px;
    }
</style>

<div class="container-fluid mt-3">
    <div class="section-bar">BORANG 12 - JADUAL KEPUTUSAN PERINGKAT KEDUA</div>

    <div class="page-title">PENILAIAN KEUPAYAAN TENDER</div>

    <div class="table-responsive">
        <table class="table-form">
            <thead>
            <tr>
                <th rowspan="2" style="width:60px;">Bil.</th>
                <th rowspan="2" style="width:130px;">Rujukan Petender</th>
                <th rowspan="2" style="width:120px;">Status Petender<br>(PB/PBP)*</th>
                <th rowspan="2" style="width:150px;">Harga Asal Tender<br>(RM)</th>

                <th colspan="2" style="width:260px; background:#ffffff; color:#111827; border-color:var(--line); font-weight:800;">
                    Markah Keseluruhan Penilaian Keupayaan Minimum
                </th>

                <th rowspan="2" style="width:160px;">Keputusan<br>(Lulus/Gagal)</th>
            </tr>
            <tr>
                <th style="background:#ffffff; color:#111827; font-weight:800;">PB*</th>
                <th style="background:#ffffff; color:#111827; font-weight:800;">PBP**</th>
            </tr>
            <tr>
                <th colspan="4" style="background:var(--brand-blue);"></th>
                <th style="background:var(--brand-blue); color:#fff;">50.00</th>
                <th style="background:var(--brand-blue); color:#fff;">50.00</th>
                <th style="background:var(--brand-blue);"></th>
            </tr>
            <tr>
                <th colspan="4" style="background:var(--brand-blue);"></th>
                <th colspan="2" style="background:var(--brand-blue); color:#fff;">
                    Markah Keseluruhan Penilaian Keupayaan Petender (Dari Borang 12)
                </th>
                <th style="background:var(--brand-blue); color:#fff;">Keputusan (Lulus/Gagal)</th>
            </tr>
            </thead>

            <tbody>
            @for($i=0;$i<6;$i++)
                <tr>
                    <td style="height:42px;"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td colspan="2"></td>
                    <td></td>
                </tr>
            @endfor
            </tbody>
        </table>
    </div>

    <div class="note">
        * P.B &nbsp;&nbsp;- Petender Berpengalaman<br>
        # P.B.P - Petender Belum Berpengalaman
    </div>

    <div class="d-flex justify-content-end mt-3">
        <button type="button" class="btn-simpan" onclick="showSuccessModal()">Simpan</button>
    </div>
</div>

{{-- SUCCESS MODAL --}}
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
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
    function showSuccessModal(){
        const modal = new bootstrap.Modal(document.getElementById('successModal'));
        modal.show();
    }
</script>
@endsection