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

    /* Top grey bar title */
    .section-bar{
        background:var(--soft-grey);
        font-weight:800;
        text-transform:uppercase;
        font-size:12px;
        padding:8px 12px;
        border-radius:2px;
        margin-bottom:10px;
    }

    /* Table */
    .table-akaunbank{
        width:100%;
        table-layout:fixed;
        border-collapse:collapse;
        border:1px solid var(--line);
        background:#fff;
    }
    .table-akaunbank th,
    .table-akaunbank td{
        border:1px solid var(--line) !important;
        padding:10px 10px;
        font-size:11px;
        vertical-align:middle;
    }

    .table-akaunbank thead th{
        background:var(--brand-red);
        color:#fff;
        text-align:center;
        font-weight:800;
        white-space:nowrap;
    }

    .col-ruj{ width:95px; }
    .col-akaun{ width:95px; }
    .col-bank{ width:110px; }
    .col-jumlah{ width:110px; }

    .num{
        text-align:right;
        font-variant-numeric: tabular-nums;
        white-space:nowrap;
    }
    .bank-name{
        font-size:10px;
        color:#111827;
        text-align:center;
        white-space:normal;
        line-height:1.2;
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

    /* ====== MODAL SUCCESS (macam popup sebelum ini) ====== */
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
        background:#3a4f8a;
        color:#fff;
        border:0;
        padding:10px 20px;
        border-radius:6px;
        font-weight:800;
        min-width:110px;
    }
</style>

<div class="container-fluid mt-3">

    <div class="section-bar">BORANG 3 - AKAUN BANK</div>

    <div class="table-responsive">
        <table class="table-akaunbank">
            <thead>
                <tr>
                    <th colspan="10">MAKLUMAT BAKI AKAUN BANK BAGI 3 BULAN LEPAS</th>
                </tr>
                <tr>
                    <th class="col-ruj">Ruj. Petender</th>

                    <th class="col-akaun">Akaun 1</th>
                    <th class="col-bank">Bank</th>

                    <th class="col-akaun">Akaun 2</th>
                    <th class="col-bank">Bank</th>

                    <th class="col-akaun">Akaun 3</th>
                    <th class="col-bank">Bank</th>

                    <th class="col-akaun">Akaun 4</th>
                    <th class="col-bank">Bank</th>

                    <th class="col-jumlah">Jumlah<br>Besar</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $rows = [
                        ['ruj'=>'45/53','akaun1'=>'157,694.95','bank1'=>'RHB BANK','akaun2'=>'','bank2'=>'','akaun3'=>'','bank3'=>'','akaun4'=>'','bank4'=>'','jumlah'=>'157,694.95'],
                        ['ruj'=>'27/53','akaun1'=>'287,753.15','bank1'=>"HONG LEONG\nBANK",'akaun2'=>'','bank2'=>'','akaun3'=>'','bank3'=>'','akaun4'=>'','bank4'=>'','jumlah'=>'287,753.15'],
                        ['ruj'=>'34/53','akaun1'=>'63,607.87','bank1'=>'ALLIANCE BANK','akaun2'=>'','bank2'=>'','akaun3'=>'','bank3'=>'','akaun4'=>'','bank4'=>'','jumlah'=>'63,607.87'],
                        ['ruj'=>'24/53','akaun1'=>'305,994.23','bank1'=>"MAYBANK\nBERHAD",'akaun2'=>'','bank2'=>'','akaun3'=>'','bank3'=>'','akaun4'=>'','bank4'=>'','jumlah'=>'305,994.23'],
                        ['ruj'=>'37/53','akaun1'=>'118,201.90','bank1'=>"CIMB ISLAMIC\nBANK",'akaun2'=>'','bank2'=>'','akaun3'=>'','bank3'=>'','akaun4'=>'','bank4'=>'','jumlah'=>'118,201.90'],
                        ['ruj'=>'30/53','akaun1'=>'211,333.02','bank1'=>'RHB BANK','akaun2'=>'','bank2'=>'','akaun3'=>'','bank3'=>'','akaun4'=>'','bank4'=>'','jumlah'=>'211,333.02'],
                        ['ruj'=>'32/53','akaun1'=>'86,226.19','bank1'=>'RHB BANK','akaun2'=>'','bank2'=>'','akaun3'=>'','bank3'=>'','akaun4'=>'','bank4'=>'','jumlah'=>'86,226.19'],
                        ['ruj'=>'2/53','akaun1'=>'86,226.19','bank1'=>"CIMB\nPUTRAJAYA",'akaun2'=>'','bank2'=>'','akaun3'=>'','bank3'=>'','akaun4'=>'','bank4'=>'','jumlah'=>'86,226.19'],
                        ['ruj'=>'48/53','akaun1'=>'1,001,667.90','bank1'=>'CIMB BANK','akaun2'=>'','bank2'=>'','akaun3'=>'','bank3'=>'','akaun4'=>'','bank4'=>'','jumlah'=>'1,001,667.90'],
                        ['ruj'=>'7/53','akaun1'=>'319,229.23','bank1'=>'UOB BANK','akaun2'=>'','bank2'=>'','akaun3'=>'','bank3'=>'','akaun4'=>'','bank4'=>'','jumlah'=>'319,229.23'],
                    ];
                @endphp

                @foreach($rows as $row)
                    <tr>
                        <td class="text-center">{{ $row['ruj'] }}</td>

                        <td class="num">{{ $row['akaun1'] }}</td>
                        <td class="bank-name">{!! nl2br(e($row['bank1'])) !!}</td>

                        <td class="num">{{ $row['akaun2'] }}</td>
                        <td class="bank-name">{!! nl2br(e($row['bank2'])) !!}</td>

                        <td class="num">{{ $row['akaun3'] }}</td>
                        <td class="bank-name">{!! nl2br(e($row['bank3'])) !!}</td>

                        <td class="num">{{ $row['akaun4'] }}</td>
                        <td class="bank-name">{!! nl2br(e($row['bank4'])) !!}</td>

                        <td class="num">{{ $row['jumlah'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex align-items-center justify-content-between">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="sahPenilaian">
            <label class="form-check-label" for="sahPenilaian" style="font-size:12px;">
                Saya mengesahkan petender diatas layak untuk penilaian peringkat seterusnya.
            </label>
        </div>

        <button type="button" class="btn-simpan" onclick="showSuccessModal()">Simpan</button>
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
    function showSuccessModal(){
        const modal = new bootstrap.Modal(document.getElementById('successModal'));
        modal.show();
    }
</script>
@endsection
