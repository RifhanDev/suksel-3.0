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
        text-align:left;
    }
    .thead-blue th.center{ text-align:center; }
    .subhead{
        background:var(--brand-red);
        color:#fff;
        font-weight:800;
        font-size:11px;
    }
    .muted{ color:#6b7280; }
    .btn-simpan{
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

    <div class="section-bar">BORANG 9 - ANALISA DATA-DATA PENILAIAN KEUPAYAAN TEKNIKAL PETENDER</div>

    <div class="table-responsive">
        <table class="table-form">
            <colgroup>
                <col style="width:68%">
                <col style="width:32%">
            </colgroup>

            <thead class="thead-blue">
            <tr>
                <th>
                    B. KEUPAYAAN TEKNIKAL<br>
                    <span style="font-weight:700;">B1. PENGALAMAN KERJA DALAM LIMA (5) TAHUN LEPAS (termasuk kerja semasa yang telah siap)</span>
                </th>
                <th class="center">
                    Anggaran Jabatan (AJ):<br>
                    <span style="font-weight:900;">0.00</span>
                </th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td class="subhead">Senarai Pengalaman Kerja</td>
                <td class="subhead center">Pelarasan Nilai Kerja (RM)</td>
            </tr>

            <tr>
                <td style="height:140px;">
                    <div style="font-weight:700; line-height:1.7;">
                        1. Kerja-kerja serupa yang disiapkan (Lampiran 9a)<br>
                        2. Kerja-kerja sebanding yang disiapkan (Lampiran 9b)<br>
                        3. Bahagian kerja semasa (serupa) yang telah siap (Borang 7a)<br>
                        4. Bahagian kerja semasa (sebanding) yang telah siap (Borang 7b)
                    </div>
                </td>
                <td></td>
            </tr>

            <tr>
                <td class="center" style="font-weight:800;">Jumlah Keseluruhan Kerja</td>
                <td class="center">0.00</td>
            </tr>

            <tr>
                <td style="height:220px;">
                    <div style="font-weight:800; margin-bottom:6px;">B1.1 Keseluruhan Kerja</div>
                    <div style="margin-left:18px;">
                        (i) &nbsp; Jumlah Keseluruhan Kerja &nbsp;&nbsp; =<br>
                        (ii) % berbanding dengan AJ &nbsp;&nbsp; =
                    </div>

                    <div style="font-weight:800; margin:16px 0 6px;">B1.2 Kerja Terbesar <span class="muted">*</span></div>
                    <div style="margin-left:18px;">
                        (i) &nbsp; Nilai Kerja Terbesar &nbsp;&nbsp; =<br>
                        (ii) % berbanding dengan AJ &nbsp;&nbsp; =
                    </div>

                    <div style="margin-top:14px; font-weight:800;">Nota:</div>
                    <div style="font-size:10px; line-height:1.6;">
                        1. Pelarasan Nilai Kerja - (i) Kerja Serupa - tidak perlu pelarasan. <br>
                        &nbsp;&nbsp;&nbsp;&nbsp;- (ii) Kerja Sebanding - Nilai Kerja sebanding darab 0.5 <br>
                        2. <span class="muted">*</span> Kerja Terbesar - Nilai kerja terbesar selepas mengambil kira pelarasan nilai kerja.
                    </div>
                </td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-end">
        <button type="button" class="btn-simpan" onclick="showSuccessModalB9()">Simpan</button>
    </div>
</div>

{{-- SUCCESS MODAL --}}
<div class="modal fade" id="successModalB9" tabindex="-1" aria-hidden="true">
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
    function showSuccessModalB9(){
        const modal = new bootstrap.Modal(document.getElementById('successModalB9'));
        modal.show();
    }
</script>
@endsection
