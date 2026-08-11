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

    /* Sub title */
    .sub-title{
        font-weight:800;
        font-size:12px;
        color:#111827;
        margin:6px 0 10px;
        text-transform:uppercase;
    }

    /* Small info box (right) */
    .info-box{
        background:#fff;
        border:1px solid var(--line);
        border-radius:4px;
        padding:10px 12px;
        width:210px;
        font-size:11px;
        line-height:1.35;
    }
    .info-box .rowx{
        display:flex;
        justify-content:space-between;
        gap:10px;
        margin-bottom:6px;
    }
    .info-box .rowx:last-child{ margin-bottom:0; }
    .info-box .k{ color:#6b7280; font-weight:700; }
    .info-box .v{ color:#111827; font-weight:800; }

    /* Table */
    .table-prestasi{
        width:100%;
        table-layout:fixed;
        border-collapse:collapse;
        border:1px solid var(--line);
        background:#fff;
    }
    .table-prestasi th,
    .table-prestasi td{
        border:1px solid var(--line) !important;
        padding:10px 10px;
        font-size:11px;
        vertical-align:middle;
    }
    .table-prestasi thead th{
        background:var(--brand-red);
        color:#fff;
        text-align:center;
        font-weight:800;
        white-space:nowrap;
    }

    .col-perkara{ width:260px; }
    .cell-perkara{
        font-weight:700;
        color:#111827;
    }

    /* Bottom controls */
    .btn-action{
        background:var(--btn-teal);
        color:#fff;
        border:0;
        padding:10px 18px;
        border-radius:6px;
        font-weight:800;
        min-width:110px;
    }
    .btn-action:hover{ background:var(--btn-teal-hover); }

    .btn-secondary-soft{
        background:var(--btn-grey);
        color:#111827;
        border:1px solid var(--line);
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
        background:var(--brand-blue);
        color:#fff;
        border:0;
        padding:10px 20px;
        border-radius:6px;
        font-weight:800;
        min-width:110px;
    }

    /* Inputs in table */
    .table-prestasi select,
    .table-prestasi input{
        font-size:11px;
    }
</style>

<div class="container-fluid mt-3">

    <div class="section-bar">BORANG 4 - ANALISA DATA-DATA PENILAIAN PRESTASI PETENDER</div>

    <div class="d-flex justify-content-between align-items-start gap-3">
        <div class="sub-title">PRESTASI KERJA SEMASA PETENDER</div>

        <div class="info-box">
            <div class="rowx">
                <div class="k">No. Ruj Petender</div>
                <div class="v">45/53</div>
            </div>
            <div class="rowx">
                <div class="k">Gred Pendaftaran</div>
                <div class="v">#N/A</div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table-prestasi">
            <thead>
                <tr>
                    <th class="col-perkara">Perkara</th>
                    <th>Kerja 1</th>
                    <th>Kerja 2</th>
                    <th>Kerja 3</th>
                    <th>Kerja 4</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="cell-perkara">Nama Ringkas Kerja Semasa</td>
                    <td>Kerja Penyelenggaraan Dan Lain-lain Kerja Berkaitan Di PGC, Pj, Putrajaya Untuk Tempoh 2 Tahun</td>
                    <td></td><td></td><td></td>
                </tr>

                <tr>
                    <td class="cell-perkara">No. Kontrak Kerja Semasa</td>
                    <td>PPJ/KJ/32(TB)/5/2023(KW)</td>
                    <td></td><td></td><td></td>
                </tr>

                <tr>
                    <td class="cell-perkara">Harga Kontrak (RM)</td>
                    <td>8,985,719.20</td>
                    <td></td><td></td><td></td>
                </tr>

                <tr>
                    <td class="cell-perkara">Tarikh Pemilikan Tapak</td>
                    <td>15 JUN 2023</td>
                    <td></td><td></td><td></td>
                </tr>

                <tr>
                    <td class="cell-perkara">Tempoh Kontrak (Hari) (P)</td>
                    <td>730</td>
                    <td></td><td></td><td></td>
                </tr>

                <tr>
                    <td class="cell-perkara">Tarikh Siap Kontrak Termasuk (termasuk EOT diluluskan)</td>
                    <td>14 JUN 2025</td>
                    <td></td><td></td><td></td>
                </tr>

                <tr>
                    <td class="cell-perkara">Tarikh Penilaian Kemajuan</td>
                    <td>19 NOV 2024</td>
                    <td></td><td></td><td></td>
                </tr>

                <tr>
                    <td class="cell-perkara">Lepas Tarikh Siap Kontrak (Hari) (D)</td>
                    <td>0</td>
                    <td></td><td></td><td></td>
                </tr>

                <tr>
                    <td class="cell-perkara">Peratus Kemajuan Sebenar Dicapai (A) (%)</td>
                    <td>48.15</td>
                    <td></td><td></td><td></td>
                </tr>

                <tr>
                    <td class="cell-perkara">Peratus Kemajuan Mengikut Jadual (S) (%)</td>
                    <td>48.15</td>
                    <td></td><td></td><td></td>
                </tr>

                <tr>
                    <td class="cell-perkara">Prestasi Kerja Semasa (A-(S*P-D)/P)</td>
                    <td>0</td>
                    <td></td><td></td><td></td>
                </tr>

                <tr>
                    <td class="cell-perkara">Status Prestasi</td>
                    <td>MEMUASKAN</td>
                    <td></td><td></td><td></td>
                </tr>

                <tr>
                    <td class="cell-perkara">Prestasi Kerja Semasa Terdahulu (%)</td>
                    <td>0.00</td>
                    <td></td><td></td><td></td>
                </tr>

                <tr>
                    <td class="cell-perkara">Semakan Projek Sakit Oleh Pegawai Penilai</td>
                    <td>
                        <select class="form-select form-select-sm" style="max-width:160px;">
                            <option selected>TIADA</option>
                            <option>ADA</option>
                        </select>
                    </td>
                    <td></td><td></td><td></td>
                </tr>

                <tr>
                    <td class="cell-perkara">STATUS PRESTASI:</td>
                    <td colspan="4" style="font-weight:800;">MEMUASKAN</td>
                </tr>

                <tr>
                    <td class="cell-perkara">Formula Pengiraan Nilai Baki Kerja Semasa Dalam Tangan :</td>
                    <td colspan="4" style="font-size:10px; color:#374151;">
                        (100% - %Kerja Sebenar) x Harga Kontrak Kerja
                    </td>
                </tr>
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

        <div class="d-flex gap-2">
            <button type="button" class="btn-action" onclick="onTambah()">Tambah</button>
            <button type="button" class="btn-action" onclick="showSuccessModal()">Simpan</button>
        </div>
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

    // Placeholder action (UI dulu). Nanti boleh sambung logic tambah kerja baru.
    function onTambah(){
        // contoh: boleh trigger toast / modal lain kalau perlu
        // buat masa ini, kita reuse success modal untuk demo
        showSuccessModal();
    }
</script>
@endsection
