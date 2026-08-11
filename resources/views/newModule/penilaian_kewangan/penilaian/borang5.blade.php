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

    /* Papar button */
    .btn-papar{
        background:var(--btn-teal);
        color:#fff;
        border:0;
        padding:6px 14px;
        border-radius:6px;
        font-weight:800;
        font-size:12px;
        min-width:84px;
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

    /* Rumusan mini input */
    .mini-input{
        width:60px;
        padding:6px 8px;
        border:1px solid var(--line);
        border-radius:4px;
        font-size:12px;
        text-align:center;
    }

    /* ===== Modal look  ===== */
    .modal-wide{
        max-width:1100px;
    }
    .modal-shell{
        border-radius:6px;
        border:0;
        overflow:hidden;
    }
    .modal-strip{
        background:var(--soft-grey);
        font-weight:800;
        font-size:11px;
        padding:8px 12px;
        text-transform:uppercase;
    }
    .modal-body-wrap{
        padding:14px 14px 10px;
    }
    .hint-link{
        font-size:11px;
        color:#2563eb;
        font-style:italic;
        margin:6px 0 8px;
    }
    .doc-icon{
        width:22px;height:22px;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        border-radius:4px;
        background:#eef2ff;
        color:#1d4ed8;
        font-weight:900;
        font-size:12px;
        margin-right:8px;
    }
    .status-select{
        max-width:160px;
        margin:auto;
        font-size:12px;
    }
    .catatan-box{
        width:100%;
        min-height:36px;
        border:1px solid var(--line);
        border-radius:4px;
        font-size:12px;
        padding:8px;
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
        background:var(--btn-teal);
        color:#fff;
        border:0;
        padding:10px 20px;
        border-radius:6px;
        font-weight:800;
        min-width:110px;
    }
</style>

<div class="container-fluid mt-3">

    <div class="section-bar">BORANG 5 - JADUAL KEPUTUSAN PENILAIAN PERINGKAT PERTAMA</div>

    {{-- TABLE 1: KRITERIA --}}
    <div class="table-responsive mb-4">
        <table class="table-blue">
            <thead>
            <tr>
                <th>Kriteria - Kriteria</th>
                <th style="width:160px;">Tindakan</th>
            </tr>
            </thead>
            <tbody>
            @php
                $kriteria = [
                    'Kesempurnaan Tender (Borang 1)',
                    'Kecukupan Dokumen (Borang 2)',
                    'Kecukupan Modal (Borang 3)',
                    'Prestasi Kerja Semasa (Borang 4)',
                ];
            @endphp

            @foreach($kriteria as $idx => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td class="text-center">
                        <button type="button"
                                class="btn-papar"
                                onclick="openPaparModal('{{ e($label) }}')">
                            Papar
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- RUMUSAN --}}
    <div class="section-bar">RUMUSAN</div>

    <div class="section-bar" style="background:#f3f4f6; font-weight:800; margin-top:-4px;">
        KEPUTUSAN PENILAIAN PERINGKAT PERTAMA
    </div>

    <div class="table-responsive">
        <table class="table-blue">
            <thead>
            <tr>
                <th style="width:160px;">Bil</th>
                <th style="width:200px;">Keputusan</th>
                <th>Ulasan</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-center">1/2</td>
                <td class="text-center">Sempurna</td>
                <td class="text-center">xxx</td>
            </tr>
            <tr>
                <td class="text-center">2/2</td>
                <td class="text-center">Sempurna</td>
                <td class="text-center">xxx</td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="d-flex align-items-center gap-3 mt-2">
        <div style="font-size:12px;">Bilangan Syarikat yang Berjaya</div>
        <input class="mini-input" value="2" />
    </div>

    <div class="form-check mt-2">
        <input class="form-check-input" type="checkbox" id="chkSah">
        <label class="form-check-label" for="chkSah" style="font-size:12px;">
            Saya mengesahkan petender diatas layak untuk penilaian peringkat seterusnya.
        </label>
    </div>

    <div class="d-flex justify-content-end mt-3">
        <button type="button" class="btn-simpan" onclick="openSuccessModal()">Simpan</button>
    </div>

</div>

{{-- =========================
    MODAL PAPAR (image 2)
========================== --}}
<div class="modal fade" id="paparModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-wide">
        <div class="modal-content modal-shell">

            <div class="modal-strip">JENIS KRITERIA</div>
            <div class="modal-body-wrap">
                <div id="paparJenisKriteria" style="font-size:12px; font-weight:700;">
                    Borang Tender Ditandatangani
                </div>
            </div>

            <div class="modal-strip">SENARAI PEMBEKAL</div>
            <div class="modal-body-wrap">
                <div class="hint-link">Klik butang Semak untuk meneruskan penilaian pematuhan</div>

                <div class="table-responsive">
                    <table class="table-blue">
                        <thead>
                        <tr>
                            <th style="width:90px;">Bil</th>
                            <th>Dokumen</th>
                            <th style="width:220px;">Status Kesempurnaan</th>
                            <th style="width:240px;">Catatan</th>
                        </tr>
                        </thead>
                        <tbody>
                        @for($i=1;$i<=2;$i++)
                            <tr>
                                <td class="text-center">{{ $i }}/2</td>
                                <td>
                                    <span class="doc-icon">📄</span>
                                    <span id="docLabel{{ $i }}">Kesempurnaan Tender </span>
                                </td>
                                <td class="text-center">
                                    <select class="form-select form-select-sm status-select">
                                        <option selected>Sempurna</option>
                                        <option>Tidak Sempurna</option>
                                        <option>Tiada Borang GA</option>
                                        <option>Tiada Kerja Semasa</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea class="catatan-box" placeholder=""></textarea>
                                </td>
                            </tr>
                        @endfor
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    <button type="button" class="btn-simpan" onclick="onModalSimpan()">Simpan</button>
                </div>
            </div>

            <div class="p-3 d-flex justify-content-end" style="background:#fff;">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>

{{-- =========================
    MODAL SUCCESS 
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
    function openPaparModal(kriteriaLabel){
        document.getElementById('paparJenisKriteria').textContent = kriteriaLabel;

        for(let i=1;i<=2;i++){
            const el = document.getElementById('docLabel'+i);
            if(el) el.textContent = kriteriaLabel;
        }

        const modal = new bootstrap.Modal(document.getElementById('paparModal'));
        modal.show();
    }

    function openSuccessModal(){
        const modal = new bootstrap.Modal(document.getElementById('successModal'));
        modal.show();
    }

    function onModalSimpan(){
        const paparEl = document.getElementById('paparModal');
        const paparModal = bootstrap.Modal.getInstance(paparEl) || new bootstrap.Modal(paparEl);
        paparModal.hide();

        setTimeout(() => openSuccessModal(), 250);
    }
</script>
@endsection
