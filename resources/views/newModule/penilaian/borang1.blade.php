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
        background:var(--bar-grey);
        font-weight:800;
        text-transform:uppercase;
        font-size:12px;
        padding:8px 12px;
        border-radius:2px;
        margin-bottom:10px;
    }

    /* table biru */
    .table-blue thead th{
        background:var(--brand-red) !important;
        color:#fff !important;
        font-size:12px;
        font-weight:800;
        text-align:center;
        vertical-align:middle;
        border-color:rgba(255,255,255,.15) !important;
        padding:10px 12px;
        white-space:nowrap;
    }
    .table-blue td{
        font-size:12px;
        vertical-align:middle;
        padding:10px 12px;
        border-color:var(--line);
    }

    /* button style */
    .btn-teal{
        background:var(--btn-teal);
        color:#fff;
        border:0;
        padding:8px 18px;
        border-radius:6px;
        font-weight:800;
        font-size:12px;
        min-width:90px;
    }
    .btn-teal:hover{ opacity:.92; color:#fff; }

    .btn-navy{
        background:var(--btn-navy);
        color:#fff;
        border:0;
        padding:10px 22px;
        border-radius:6px;
        font-weight:800;
        min-width:110px;
    }
    .btn-navy:hover{ opacity:.92; color:#fff; }

    /* modal look (macam gambar 2 & 3) */
    .modal-card{
        border-radius:8px;
        border:0;
        box-shadow:0 10px 30px rgba(0,0,0,.18);
    }
    .modal-head-grey{
        background:var(--bar-grey);
        font-weight:800;
        text-transform:uppercase;
        font-size:12px;
        padding:10px 12px;
        border-bottom:0;
    }

    /* icon confetti simple */
    .confetti{
        width:46px;height:46px;margin:6px auto 10px;display:block;
    }
</style>

<div class="container-fluid mt-3">

    {{-- =========================
        PAGE UTAMA (GAMBAR 1)
    ========================== --}}
    <div class="section-bar">BORANG 1 - ANALISA KESEMPURNAAN TENDER</div>

    <div class="fw-bold mb-2" style="font-size:12px;">Kriteria Kesempurnaan Tender:</div>

    <div class="table-responsive">
        <table class="table table-bordered table-blue mb-4">
            <thead>
            <tr>
                <th>Kriteria - Kriteria</th>
                <th style="width:160px;">Tindakan</th>
            </tr>
            </thead>
            <tbody>
            @php
                $rows = [
                    'Borang Tender Ditandatangani',
                    'Penandatangan Diberi kuasa?',
                    'Harga Tender / Tempoh Tercatat di Borang Tender',
                    'Pendaftaran Masih Sah Semasa Tutup Tender',
                    'Mengembalikan Kesemua Dokumen Asas Tender',
                    'Tempoh Tidak Melebihi Tempoh Siap Maksimum',
                    'Surat Akuan Pembida Ditandatangani (Integrity Pact)',
                ];
            @endphp

            @foreach($rows as $i => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td class="text-center">
                        <button
                            type="button"
                            class="btn-teal btn-papar"
                            data-title="{{ $label }}"
                            data-bs-toggle="modal"
                            data-bs-target="#paparModal"
                        >
                            Papar
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="section-bar">RUMUSAN</div>
    <div class="fw-bold mb-2" style="font-size:12px;">KEPUTUSAN PENILAIAN ANALISA KESEMPURNAAN TENDER</div>

    <div class="table-responsive">
        <table class="table table-bordered table-blue mb-3">
            <thead>
            <tr>
                <th style="width:80px;">Bil</th>
                <th style="width:160px;">Keputusan</th>
                <th>Catatan <span class="text-danger">*</span></th>
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

    <div class="d-flex align-items-center gap-3 mb-3" style="font-size:12px;">
        <div style="min-width:140px;">Bilangan Pembekal</div>
        <input type="text" class="form-control form-control-sm" value="2" style="max-width:80px;">
    </div>

    <div class="form-check mb-3" style="font-size:12px;">
        <input class="form-check-input" type="checkbox" id="chkSah">
        <label class="form-check-label" for="chkSah">
            Saya mengesahkan petender diatas layak untuk penilaian peringkat seterusnya.
        </label>
    </div>

    <div class="d-flex justify-content-end">
        <button type="button" class="btn-teal" onclick="openSavedModal()">Simpan</button>
    </div>

</div>

{{-- =========================
    MODAL PAPAR 
========================== --}}
<div class="modal fade" id="paparModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content modal-card">

            <div class="modal-head-grey">
                <div class="d-flex justify-content-between align-items-center">
                    <div id="paparTitle">JENIS KRITERIA</div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <div class="modal-body">

                <div class="section-bar mb-2">SENARAI SYARIKAT</div>
                <div class="text-primary mb-2" style="font-size:12px;">
                    Klik butang Semak untuk meneruskan penilaian pematuhan
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-blue">
                        <thead>
                        <tr>
                            <th style="width:90px;">Bil</th>
                            <th>Dokumen</th>
                            <th style="width:220px;">Status Kesempurnaan</th>
                            <th style="width:260px;">Catatan</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="text-center">1/2</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span style="font-size:14px;">📄</span>
                                    <span>Dokumen</span>
                                </div>
                            </td>
                            <td>
                                <select class="form-select form-select-sm" style="max-width:180px;margin:auto;">
                                    <option selected>Sempurna</option>
                                    <option>Tidak</option>
                                </select>
                            </td>
                            <td><input type="text" class="form-control form-control-sm" /></td>
                        </tr>
                        <tr>
                            <td class="text-center">2/2</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span style="font-size:14px;">📄</span>
                                    <span>Dokumen</span>
                                </div>
                            </td>
                            <td>
                                <select class="form-select form-select-sm" style="max-width:180px;margin:auto;">
                                    <option selected>Sempurna</option>
                                    <option>Tidak</option>
                                </select>
                            </td>
                            <td><input type="text" class="form-control form-control-sm" /></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center my-3">
                    <button type="button" class="btn-teal" id="btnSimpanDalamModal">Simpan</button>
                </div>


            </div>
        </div>
    </div>
</div>

{{-- =========================
    MODAL SIMPAN SUCCESS 
========================== --}}
<div class="modal fade" id="savedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
        <div class="modal-content modal-card p-4 text-center">

            <svg class="confetti" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16 42 L34 28 L30 50 Z" stroke="#19c1a7" stroke-width="3" fill="none"/>
                <path d="M38 16 C44 20, 48 24, 52 30" stroke="#19c1a7" stroke-width="3" fill="none"/>
                <path d="M40 40 L52 46" stroke="#19c1a7" stroke-width="3"/>
                <path d="M22 18 L18 12" stroke="#19c1a7" stroke-width="3"/>
            </svg>

            <div class="fw-bold" style="font-size:16px;margin:6px 0 16px;">
                Maklumat telah berjaya disimpan
            </div>

            <button type="button" class="btn-navy" data-bs-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.btn-papar').forEach(btn => {
        btn.addEventListener('click', function(){
            const title = this.dataset.title || 'JENIS KRITERIA';
            document.getElementById('paparTitle').textContent = title;
        });
    });

    // Simpan dalam modal papar -> tutup modal papar -> buka modal success
    document.getElementById('btnSimpanDalamModal').addEventListener('click', function(){
        const paparEl = document.getElementById('paparModal');
        const savedEl = document.getElementById('savedModal');

        const paparModal = bootstrap.Modal.getInstance(paparEl) || new bootstrap.Modal(paparEl);
        paparModal.hide();

        // tunggu modal papar tutup dulu (supaya backdrop tak bertindih)
        paparEl.addEventListener('hidden.bs.modal', function handler(){
            paparEl.removeEventListener('hidden.bs.modal', handler);
            const savedModal = new bootstrap.Modal(savedEl);
            savedModal.show();
        });
    });

    // Simpan dari page utama (tanpa papar) -> terus buka modal success
    function openSavedModal(){
        const savedModal = new bootstrap.Modal(document.getElementById('savedModal'));
        savedModal.show();
    }
    window.openSavedModal = openSavedModal;
</script>
@endsection
