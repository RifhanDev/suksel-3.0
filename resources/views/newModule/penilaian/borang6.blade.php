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

    /* Section bar (grey) */
    .section-bar{
        background:var(--soft-grey);
        font-weight:800;
        text-transform:uppercase;
        font-size:12px;
        padding:8px 12px;
        border-radius:2px;
        margin-bottom:10px;
    }

    .sub-note{
        font-size:12px;
        color:#111827;
        margin:6px 0 10px;
        font-weight:600;
    }

    /* Table header blue */
    .table-blue{
        width:100%;
        border-collapse:collapse;
        background:#fff;
    }
    .table-blue th, .table-blue td{
        border:1px solid var(--line) !important;
        padding:10px 10px;
        font-size:12px;
        vertical-align:middle;
    }
    .table-blue thead th{
        background:var(--brand-red) !important;
        color:#fff !important;
        font-weight:800;
        text-align:center;
        white-space:nowrap;
    }
    .table-blue tbody td{
        text-align:center;
    }

    /* Simpan button */
    .btn-simpan{
        background:var(--btn-teal);
        color:#fff;
        border:0;
        padding:10px 18px;
        border-radius:6px;
        font-weight:800;
        min-width:110px;
    }

    /* ===== Success modal ===== */
    .modal-card{
        border-radius:10px;
        border:0;
        box-shadow:0 10px 30px rgba(0,0,0,.15);
        padding:18px 18px 14px;
        text-align:center;
    }
    .confetti{
        width:44px;height:44px;margin:6px auto 8px;
    }
    .btn-modal{
        background:var(--btn-primary);
        color:#fff;
        border:0;
        padding:10px 20px;
        border-radius:6px;
        font-weight:800;
        min-width:110px;
    }
</style>

<div class="container-fluid mt-3">

    <div class="section-bar">BORANG 6</div>

    <div class="text-center" style="font-weight:800; font-size:12px; margin:6px 0 10px; text-transform:uppercase;">
        SENARAI PETENDER YANG LULUS PENILAIAN PERINGKAT PERTAMA MENGIKUT TURUTAN HARGA TENDER
    </div>

    <div class="sub-note">Kriteria Kesempurnaan Tender:</div>

    <div class="table-responsive">
        <table class="table-blue">
            <thead>
            <tr>
                <th style="width:140px;">Bilangan</th>
                <th style="width:200px;">Rujukan Petender</th>
                <th>Harga Tender Asal (RM)</th>
            </tr>
            </thead>
            <tbody>
            @php
                // Dummy data ikut screenshot
                $rows = [
                    ['bil'=>1,'ruj'=>'45/53','harga'=>'4,438,243.50'],
                    ['bil'=>2,'ruj'=>'27/53','harga'=>'4,799,852.00'],
                    ['bil'=>3,'ruj'=>'34/53','harga'=>'4,830,689.40'],
                    ['bil'=>4,'ruj'=>'24/53','harga'=>'4,864,594.40'],
                    ['bil'=>5,'ruj'=>'37/53','harga'=>'4,966,328.00'],
                    ['bil'=>6,'ruj'=>'30/53','harga'=>'4,980,824.00'],
                    ['bil'=>7,'ruj'=>'32/53','harga'=>'5,010,773.00'],
                    ['bil'=>8,'ruj'=>'2/53','harga'=>'5,018,400.10'],
                    ['bil'=>9,'ruj'=>'7/53','harga'=>'5,050,444.00'],
                ];
            @endphp

            @foreach($rows as $r)
                <tr>
                    <td>{{ $r['bil'] }}</td>
                    <td>{{ $r['ruj'] }}</td>
                    <td style="text-align:right; font-variant-numeric: tabular-nums; padding-right:14px;">
                        {{ $r['harga'] }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-4">
        <button type="button" class="btn-simpan" onclick="openSuccessModal()">Simpan</button>
    </div>

</div>

{{-- =========================
    MODAL: SIMPAN SUCCESS
========================== --}}
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
    function openSuccessModal(){
        const modal = new bootstrap.Modal(document.getElementById('successModal'));
        modal.show();
    }
</script>
@endsection
