
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
        border:1px solid var(--line);
        border-collapse:collapse;
        width:100%;
        table-layout:fixed;
        background:#fff;
    }
    .table-akaunbank th,
    .table-akaunbank td{
        border:1px solid var(--line) !important;
        padding:8px 8px;
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

    .table-akaunbank tbody td{
        background:#fff;
    }

    .col-ruj{ width:70px; }
    .col-bulan{ width:70px; }
    .col-akaun{ width:85px; }
    .col-bank{ width:95px; }
    .col-jumlah{ width:95px; }

    /* Bulan list style (Aug/Sep/Oct) */
    .bulan-stack{
        line-height:1.35;
        font-size:10px;
        color:#374151;
        text-align:left;
        white-space:normal;
    }

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

    /* ====== MODAL THEME (popup success) ====== */
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
        background:var(--brand-red);
        color:#fff;
        border:0;
        padding:10px 20px;
        border-radius:6px;
        font-weight:800;
        min-width:110px;
    }
</style>

<div class="container-fluid mt-3">

    <div class="section-bar">AKAUN BANK</div>

    <div class="table-responsive">
        <table class="table-akaunbank">
            <thead>
            <tr>
                <th colspan="11">MAKLUMAT BAKI AKAUN BANK BAGI 3 BULAN LEPAS</th>
            </tr>
            <tr>
                <th class="col-ruj">Ruj.<br>Petender</th>
                <th class="col-bulan">Bulan</th>

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
                // Dummy data ikut layout gambar (3 bulan dalam satu cell)
                $rows = [
                    [
                        'ruj' => '45/53',
                        'bulan' => ['Aug 2024','Sep 2024','Oct 2024'],
                        'akaun1' => ['157,894.95','181,807.61','252,434.78'],
                        'bank1' => 'RHB BANK',
                        'akaun2' => ['','',''],
                        'bank2' => '',
                        'akaun3' => ['','',''],
                        'bank3' => '',
                        'akaun4' => ['','',''],
                        'bank4' => '',
                        'jumlah' => '591,937.34',
                    ],
                    [
                        'ruj' => '27/53',
                        'bulan' => ['Aug 2024','Sep 2024','Oct 2024'],
                        'akaun1' => ['287,753.15','509,483.59','514,701.07'],
                        'bank1' => "HONG LEONG\nBANK",
                        'akaun2' => ['','',''],
                        'bank2' => '',
                        'akaun3' => ['','',''],
                        'bank3' => '',
                        'akaun4' => ['','',''],
                        'bank4' => '',
                        'jumlah' => '1,019,988.71',
                    ],
                    [
                        'ruj' => '34/53',
                        'bulan' => ['Aug 2024','Sep 2024','Oct 2024'],
                        'akaun1' => ['63,607.87','63,699.87','168,297.97'],
                        'bank1' => 'ALLIANCE BANK',
                        'akaun2' => ['','',''],
                        'bank2' => '',
                        'akaun3' => ['','',''],
                        'bank3' => '',
                        'akaun4' => ['','',''],
                        'bank4' => '',
                        'jumlah' => '295,605.71',
                    ],
                    [
                        'ruj' => '24/53',
                        'bulan' => ['Aug 2024','Sep 2024','Oct 2024'],
                        'akaun1' => ['305,994.23','411,462.72','94,361.87'],
                        'bank1' => "MAYBANK\nBERHAD",
                        'akaun2' => ['','',''],
                        'bank2' => '',
                        'akaun3' => ['','',''],
                        'bank3' => '',
                        'akaun4' => ['','',''],
                        'bank4' => '',
                        'jumlah' => '811,817.82',
                    ],
                    [
                        'ruj' => '37/53',
                        'bulan' => ['Aug 2024','Sep 2024','Oct 2024'],
                        'akaun1' => ['148,201.90','350,061.90','300,800.09'],
                        'bank1' => "CIMB ISLAMIC\nBANK",
                        'akaun2' => ['','',''],
                        'bank2' => '',
                        'akaun3' => ['','',''],
                        'bank3' => '',
                        'akaun4' => ['','',''],
                        'bank4' => '',
                        'jumlah' => '799,063.89',
                    ],
                    [
                        'ruj' => '30/53',
                        'bulan' => ['Aug 2024','Sep 2024','Oct 2024'],
                        'akaun1' => ['201,333.02','206,385.75','96,802.35'],
                        'bank1' => "RHB BANK",
                        'akaun2' => ['','',''],
                        'bank2' => '',
                        'akaun3' => ['','',''],
                        'bank3' => '',
                        'akaun4' => ['','',''],
                        'bank4' => '',
                        'jumlah' => '515,221.12',
                    ],
                    [
                        'ruj' => '32/53',
                        'bulan' => ['Aug 2024','Sep 2024','Oct 2024'],
                        'akaun1' => ['86,226.19','206,385.75','96,802.35'],
                        'bank1' => "RHB BANK",
                        'akaun2' => ['','',''],
                        'bank2' => '',
                        'akaun3' => ['','',''],
                        'bank3' => '',
                        'akaun4' => ['','',''],
                        'bank4' => '',
                        'jumlah' => '232,771.76',
                    ],
                    [
                        'ruj' => '2/53',
                        'bulan' => ['Aug 2024','Sep 2024','Oct 2024'],
                        'akaun1' => ['78,297.64','67,647.93','1,000,667.93'],
                        'bank1' => "CIMB\nPUTRAJAYA",
                        'akaun2' => ['','',''],
                        'bank2' => '',
                        'akaun3' => ['','',''],
                        'bank3' => '',
                        'akaun4' => ['','',''],
                        'bank4' => '',
                        'jumlah' => '2,860,443.50',
                    ],
                    [
                        'ruj' => '48/53',
                        'bulan' => ['Aug 2024','Sep 2024','Oct 2024'],
                        'akaun1' => ['700,667.80','795,239.82','1,063,535.73'],
                        'bank1' => "CIMB BANK",
                        'akaun2' => ['','',''],
                        'bank2' => '',
                        'akaun3' => ['','',''],
                        'bank3' => '',
                        'akaun4' => ['','',''],
                        'bank4' => '',
                        'jumlah' => '195,177.26',
                    ],
                    [
                        'ruj' => '7/53',
                        'bulan' => ['Aug 2024','Sep 2024','Oct 2024'],
                        'akaun1' => ['319,282.93','325,213.36','313,853.39'],
                        'bank1' => "UOB BANK",
                        'akaun2' => ['','',''],
                        'bank2' => '',
                        'akaun3' => ['','',''],
                        'bank3' => '',
                        'akaun4' => ['','',''],
                        'bank4' => '',
                        'jumlah' => '958,203.98',
                    ],
                ];
            @endphp

            @foreach($rows as $row)
                <tr>
                    <td class="text-center">{{ $row['ruj'] }}</td>

                    <td>
                        <div class="bulan-stack">
                            {{ $row['bulan'][0] }}<br>
                            {{ $row['bulan'][1] }}<br>
                            {{ $row['bulan'][2] }}
                        </div>
                    </td>

                    <td class="num">
                        {{ $row['akaun1'][0] }}<br>
                        {{ $row['akaun1'][1] }}<br>
                        {{ $row['akaun1'][2] }}
                    </td>
                    <td class="bank-name">{!! nl2br(e($row['bank1'])) !!}</td>

                    <td class="num">
                        {{ $row['akaun2'][0] }}<br>
                        {{ $row['akaun2'][1] }}<br>
                        {{ $row['akaun2'][2] }}
                    </td>
                    <td class="bank-name">{!! nl2br(e($row['bank2'])) !!}</td>

                    <td class="num">
                        {{ $row['akaun3'][0] }}<br>
                        {{ $row['akaun3'][1] }}<br>
                        {{ $row['akaun3'][2] }}
                    </td>
                    <td class="bank-name">{!! nl2br(e($row['bank3'])) !!}</td>

                    <td class="num">
                        {{ $row['akaun4'][0] }}<br>
                        {{ $row['akaun4'][1] }}<br>
                        {{ $row['akaun4'][2] }}
                    </td>
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
