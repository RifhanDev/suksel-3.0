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
        font-weight:800;
        text-align:center;
        font-size:12px;
        margin:8px 0 10px;
        text-transform:uppercase;
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

    .muted{
        color:#111827;
        font-size:10px;
        margin-top:10px;
        line-height:1.35;
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
    <div class="section-bar">BORANG 11b - PENILAIAN KEUPAYAAN PETENDER</div>

    <div class="page-title">
        PENGIRAAN MARKAH PENILAIAN BAGI TENDER KERJA PENYELENGGARAAN MEKANIKAL / ELEKTRIKAL
    </div>

    <div class="table-responsive">
        <table class="table-form">
            <thead>
            <tr>
                <th colspan="2" style="text-align:left;">No. Rujukan Petender:</th>
                <th style="width:90px;">NILAI</th>
                <th style="width:90px;">MARKAH</th>
                <th style="width:90px;">WAJARAN</th>
                <th style="width:100px;">JUMLAH MARKAH</th>
                <th colspan="2">MARKAH KELAYAKAN MINIMUM</th>
            </tr>
            <tr>
                <th colspan="2"></th>
                <th></th><th></th><th></th><th></th>
                <th style="width:90px;">P.B.*</th>
                <th style="width:90px;">P.B.P #</th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td style="width:240px;">
                    <div style="font-weight:800;">A - KEUPAYAAN KEWANGAN</div>
                    <div style="margin-top:8px;">A1 - Keupayaan Biayawan</div>

                    <div style="font-weight:800; margin-top:14px;">B - KEUPAYAAN TEKNIKAL</div>
                    <div style="margin-top:8px;">B1 - Pengalaman Kerja</div>
                    <div style="margin-left:12px; margin-top:6px;">B1.1 Jumlah Keseluruhan Kerja</div>
                    <div style="margin-left:12px; margin-top:6px;">B1.2 Nilai Kerja Terbesar</div>
                    <div style="margin-left:12px; margin-top:6px;">Markah Purata</div>

                    <div style="margin-top:10px;">B2 - Kakitangan Teknikal</div>
                    <div style="margin-left:12px; margin-top:6px;">B2.1 Bilangan Kakitangan Teknikal</div>
                    <div style="margin-left:12px; margin-top:6px;">B2.2 Pengalaman Kakitangan Teknikal</div>
                    <div style="margin-left:12px; margin-top:6px;">Markah Purata</div>
                </td>

                <td style="width:260px;"></td>
                <td></td><td></td><td></td><td></td>
                <td></td><td></td>
            </tr>

            <tr>
                <td colspan="6" style="font-weight:800;">
                    MARKAH KESELURUHAN PENILAIAN KEUPAYAAN PETENDER
                </td>
                <td colspan="2"></td>
            </tr>

            <tr>
                <td colspan="6" style="font-weight:800;">
                    KEPUTUSAN PENILAIAN PERINGKAT KEDUA (LULUS/GAGAL)
                </td>
                <td colspan="2"></td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="muted mt-2">
        * P.B. &nbsp;&nbsp;- Petender Berpengalaman<br>
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