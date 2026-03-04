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
    .table-borang3{
        border:1px solid var(--line);
        border-collapse:collapse;
        width:100%;
        table-layout:fixed;
    }
    .table-borang3 th,
    .table-borang3 td{
        border:1px solid var(--line) !important;
        padding:8px 8px;
        font-size:12px;
        vertical-align:middle;
    }

    .table-borang3 thead th{
        background:var(--brand-red);
        color:#fff;
        text-align:center;
        font-weight:700;
    }

    .table-borang3 thead .subhead{
        font-weight:600;
        font-size:11px;
        opacity:.95;
    }

    .table-borang3 thead .tiny{
        font-size:10px;
        opacity:.95;
        font-weight:600;
    }

    .table-borang3 tbody td{
        height:34px; /* kosong macam gambar */
        background:#fff;
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
</style>

<div class="container-fluid mt-3">

    <div class="section-bar">BORANG 3 - ANALISA KECUKUPAN MODAL</div>

    <div class="table-responsive">
        <table class="table-borang3">
            <thead>
                <tr>
                    <th rowspan="3" style="width:100px;">
                        Ruj. Petender<br><span class="tiny">(a)</span>
                    </th>

                    <th colspan="8">
                        ANALISA KECUKUPAN MODAL
                    </th>

                    <th colspan="5">
                        Modal Minimum diperlukan (3% dari nilai Kerja Pembina)
                    </th>
                </tr>

                <tr>
                    {{-- ANALISA KECUKUPAN MODAL --}}
                    <th colspan="3" class="subhead">Lembaran Imbangan</th>
                    <th colspan="2" class="subhead">Penyata Bulanan Bank</th>
                    <th rowspan="2" class="subhead">
                        Wang Dalam Tangan Semasa<br><span class="tiny">(Nilai positif)</span><br><span class="tiny">(g)</span>
                    </th>
                    <th rowspan="2" class="subhead">
                        Jumlah Modal<br><span class="tiny">(k)=(f)+(g)+(h)+(i)+(j)</span>
                    </th>
                    <th rowspan="2" class="subhead">
                        Mudah Cair yang boleh digunakan<br><span class="tiny">(m)=(j)-(k)</span>
                    </th>

                    {{-- Modal Minimum diperlukan --}}
                    <th rowspan="2" class="subhead">
                        Borang CA 2 / Deposit tetap / Saham-saham<br><span class="tiny">(h)</span>
                    </th>
                    <th rowspan="2" class="subhead">
                        Aset Cair<br><span class="tiny">(h) atau (g) + (h) yang mcm ni lebihi tinggi</span><br><span class="tiny">(i)</span>
                    </th>
                    <th rowspan="2" class="subhead">
                        Borang CA 1<br>Baki Dari Kemudahan Kredit<br><span class="tiny">(j)</span>
                    </th>
                    <th rowspan="2" class="subhead">
                        Jumlah Modal<br><span class="tiny">(k)=(f)+(g)+(h)+(i)+(j)</span>
                    </th>
                    <th rowspan="2" class="subhead">
                        Mudah Cair yang boleh digunakan<br><span class="tiny">(m)=(j)-(k)</span>
                    </th>
                </tr>

                <tr>
                    {{-- Lembaran Imbangan --}}
                    <th class="subhead">Aset Semasa<br><span class="tiny">(b)</span></th>
                    <th class="subhead">Liabiliti Semasa<br><span class="tiny">(c)</span></th>
                    <th class="subhead">Modal Pusingan<br><span class="tiny">(d)=(b)-(c)</span></th>

                    {{-- Penyata Bulanan Bank --}}
                    <th class="subhead">Baki bagi<br>3 Bulan lepas<br><span class="tiny">(e)</span></th>
                    <th class="subhead">Purata Baki<br>3 Bulan lepas<br><span class="tiny">(f)=(e)/3</span></th>
                </tr>
            </thead>

            <tbody>
                {{-- kosong macam template (boleh loop ikut data nanti) --}}
                @for($r=1; $r<=5; $r++)
                    <tr>
                        <td class="text-center"></td>

                        <td></td>
                        <td></td>
                        <td></td>

                        <td></td>
                        <td></td>

                        <td></td>
                        <td></td>
                        <td></td>

                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
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

        <button type="button" class="btn-simpan" onclick="showSuccessModal()">
            Simpan
        </button>
    </div>
</div>

{{-- =========================
    MODAL: SIMPAN SUCCESS
========================== --}}
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
        <div class="modal-content" style="border-radius:10px; padding:20px; text-align:center;">
            
            <svg width="48" height="48" viewBox="0 0 64 64" fill="none"
                 xmlns="http://www.w3.org/2000/svg" style="margin-bottom:12px;">
                <path d="M16 42 L34 28 L30 50 Z" stroke="#19c1a7" stroke-width="3" fill="none"/>
                <path d="M38 16 C44 20, 48 24, 52 30" stroke="#19c1a7" stroke-width="3" fill="none"/>
                <path d="M40 40 L52 46" stroke="#19c1a7" stroke-width="3"/>
                <path d="M22 18 L18 12" stroke="#19c1a7" stroke-width="3"/>
            </svg>

            <div class="fw-bold mb-3" style="font-size:15px;">
                Maklumat telah berjaya disimpan
            </div>

            <button type="button"
                    class="btn"
                    style="background:#3f5496;color:#fff;font-weight:700;"
                    data-bs-dismiss="modal">
                Tutup
            </button>
        </div>
    </div>
</div>


<script>
    function showSuccessModal(){
        // OPTIONAL: do validation here before showing modal

        const modal = new bootstrap.Modal(
            document.getElementById('successModal')
        );
        modal.show();
    }
</script>

@endsection
