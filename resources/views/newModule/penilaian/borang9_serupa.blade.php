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
        margin-bottom:10px;
    }
    .title-center{
        text-align:center;
        font-weight:900;
        font-size:12px;
        margin:10px 0 12px;
        text-transform:uppercase;
    }
    .table-form{
        width:100%;
        border-collapse:collapse;
        border:1px solid var(--line);
        background:#fff;
        table-layout:fixed;
    }
    .table-form th,.table-form td{
        border:1px solid var(--line) !important;
        padding:10px 10px;
        font-size:11px;
        vertical-align:top;
    }
    .thead-blue th{
        background:var(--brand-red);
        color:#fff;
        font-weight:800;
        text-align:center;
    }
    .rowpad{ height:210px; } /* big blank area like screenshot */
    .btn-teal{
        background:var(--btn-teal);
        color:#fff;
        border:0;
        padding:10px 18px;
        border-radius:6px;
        font-weight:800;
        min-width:110px;
    }

    /* success modal */
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

<div class="container-fluid mt-3">

    <div class="section-bar">BORANG 9a - PENGALAMAN KERJA DALAM LIMA (5) TAHUN LEPAS.</div>

    <div class="title-center">
        PENGALAMAN KERJA DALAM LIMA (5) TAHUN LEPAS.<br>(KERJA SERUPA)
    </div>

    <div class="table-responsive">
        <table class="table-form" id="tblB9a">
            <colgroup>
                <col style="width:55px;">
                <col>
                <col style="width:240px;">
            </colgroup>
            <thead class="thead-blue">
            <tr>
                <th colspan="3" style="text-align:left;">No. Ruj. Petender</th>
            </tr>
            <tr>
                <th>Bil.</th>
                <th>Senarai Kerja Yang Disiapkan</th>
                <th>Nilai Kerja (RM)</th>
            </tr>
            </thead>

            <tbody>
            <tr class="rowpad">
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <tr>
                <td colspan="2" style="text-align:right; font-weight:900;">JUMLAH</td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-end gap-2">
        <button type="button" class="btn-teal" onclick="addRowB9a()">Tambah</button>
        <button type="button" class="btn-teal" onclick="showSuccessModalB9a()">Simpan</button>
    </div>
</div>

{{-- SUCCESS MODAL --}}
<div class="modal fade" id="successModalB9a" tabindex="-1" aria-hidden="true">
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
    function showSuccessModalB9a(){
        const modal = new bootstrap.Modal(document.getElementById('successModalB9a'));
        modal.show();
    }

    // UI-only: add one editable row above JUMLAH
    function addRowB9a(){
        const tbl = document.getElementById('tblB9a').querySelector('tbody');
        const rows = tbl.querySelectorAll('tr');
        const jumlahRow = rows[rows.length - 1];

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td style="text-align:center;"></td>
            <td contenteditable="true" style="min-height:34px;"></td>
            <td contenteditable="true" style="text-align:right; white-space:nowrap;"></td>
        `;

        tbl.insertBefore(tr, jumlahRow);
    }
</script>
@endsection
