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

    .info-strip{
        display:grid;
        grid-template-columns: repeat(4, 1fr);
        gap:12px;
        font-size:12px;
        margin-bottom:10px;
    }
    .info-strip b{
        display:block;
        font-weight:700;
    }

    .section-bar{
        background:var(--soft-grey);
        padding:8px 12px;
        font-size:12px;
        font-weight:800;
        text-transform:uppercase;
        margin-bottom:8px;
    }

    table{
        width:100%;
        border-collapse:collapse;
        font-size:12px;
    }
    th, td{
        border:1px solid var(--line);
        padding:8px;
        text-align:center;
        vertical-align:middle;
    }
    thead th{
        background:var(--brand-red);
        color:#fff;
        font-weight:700;
    }
    thead .subhead{
        font-size:11px;
        font-weight:600;
    }

    tbody td{
        height:34px;
        background:#fff;
    }

    .btn-simpan{
        background:var(--btn-red);
        color:#fff;
        border:0;
        padding:8px 18px;
        border-radius:4px;
        font-weight:700;
        font-size:12px;
    }
</style>

<div class="container-fluid mt-3">

    {{-- ================= INFO HEADER ================= --}}
    <div class="info-strip">
        <div>
            <small>No. Sebut Harga / Tender</small>
            <b>QT210000000023741</b>
        </div>
        <div>
            <small>PTJ</small>
            <b>BAHAGIAN PENTADBIRAN – CAWANGAN KEWANGAN – KEMENTERIAN KEWANGAN</b>
        </div>
        <div>
            <small>Status</small>
            <b>Menunggu Pengesahan Jawatan Pembuka</b>
        </div>
        <div>
            <small>Sah Laku Tawaran Tamat</small>
            <b>17/01/2022</b>
        </div>
    </div>

    {{-- ================= SECTION ================= --}}
    <div class="section-bar">LEMBARAN IMBANGAN</div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th colspan="9">ANALISA KECUKUPAN MODAL</th>
                </tr>
                <tr>
                    <th colspan="6" class="subhead">
                        MAKLUMAT DARI LEMBARAN IMBANGAN (BALANCE SHEET)
                    </th>
                    <th colspan="3" class="subhead">
                        BORANG CA / SURAT BANK
                    </th>
                </tr>
                <tr>
                    <th>Ruj. Petender</th>
                    <th>Aset Tetap</th>
                    <th>Aset Semasa</th>
                    <th>Liabiliti Semasa</th>
                    <th>Long Term / Liabiliti Tetap</th>
                    <th>Wang Tunai Dalam Tangan</th>
                    <th>Baki Kemudahan Kredit</th>
                    <th>Pinjaman Bank</th>
                    <th>Yang Akan Diluluskan</th>
                </tr>
            </thead>
            <tbody>
                @for($i=1;$i<=6;$i++)
                    <tr>
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

    {{-- ================= ACTION ================= --}}
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="sah">
            <label class="form-check-label" for="sah" style="font-size:12px;">
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
 