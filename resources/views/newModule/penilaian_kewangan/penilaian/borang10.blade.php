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
    .table-form .subhead{
        font-weight:800;
        background:var(--brand-red);
        color:#fff;
        text-align:left;
    }
    .table-form .label{
        font-weight:700;
        color:#111827;
    }
    .col-butiran{ width:62%; }
    .col-kat{ width:12.66%; text-align:center; }

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

    <div class="section-bar">BORANG 10 - ANALISA DATA-DATA PENILAIAN KEUPAYAAN TEKNIKAL PETENDER</div>

    <div class="table-responsive">
        <table class="table-form">
            <thead>
                <tr>
                    <th colspan="5" style="text-align:left;">
                        B. KEUPAYAAN TEKNIKAL<br>
                        <span style="font-weight:700;">B2. KAKITANGAN TEKNIKAL</span>
                    </th>
                </tr>
                <tr>
                    <th class="col-butiran">Butiran</th>
                    <th class="col-kat">Kat. A</th>
                    <th class="col-kat">Kat. B</th>
                    <th class="col-kat">Kat. C</th>
                    <th style="width:70px;text-align:center;">&nbsp;</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="label">
                        B2.1 Bilangan<br><br>
                        1. Faktor Penyama<br>
                        2. Bilangan AKM<br>
                        3. Bilangan dalam penggajian petender<br>
                        4. Peratus (%) dpd. AKM [(c) x (a)] x 100/Sum.(b) x (a)]
                    </td>
                    <td></td><td></td><td></td>
                    <td style="text-align:center;">
                        (a)<br><br>(b)<br>(c)<br>(d)
                    </td>
                </tr>

                <tr>
                    <td class="label">
                        5. (%) keseluruhan dpd. AKM<br>
                        [Sum. e)] atau [(Ae) + 100] jika [(Be) + (Ce)] &gt; 100%
                    </td>
                    <td colspan="3"></td>
                    <td style="text-align:center;">(e)</td>
                </tr>

                <tr>
                    <td class="label">
                        B2.2 Pengalaman<br><br>
                        1. Jumlah sebenar (tahun)<br>
                        2. Jumlah sama nilai (tahun) [(g) x (a) / Sum (b) x (a)]
                    </td>
                    <td></td><td></td><td></td>
                    <td style="text-align:center;">
                        <br><br>(g)<br>(h)
                    </td>
                </tr>

                <tr>
                    <td class="label">
                        3. Jumlah sama nilai. Keseluruhan.<br>
                        [Sum.(h)] atau [(Ah)+10.00] jika [(Bh)+(Ch)] &gt; 10.00 tahun.
                    </td>
                    <td colspan="3"></td>
                    <td style="text-align:center;">(i)</td>
                </tr>
            </tbody>
        </table>
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