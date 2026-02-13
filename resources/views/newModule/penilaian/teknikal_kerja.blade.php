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

/* ====== PAGE VISIBILITY ====== */
#pageDetail{ display:none; }

/* ====== CARD LOOK (like screenshot) ====== */
.tender-card{
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:8px;
    box-shadow:0 6px 18px rgba(0,0,0,.06);
    padding:18px;
}

/* ====== TITLE ====== */
.page-title{
    font-weight:900;
    letter-spacing:.2px;
    margin:2px 0 14px;
}

/* ====== FILTER BAR (like screenshot) ====== */
.filter-grid{
    display:grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    gap:12px;
    margin-bottom:12px;
}
.filter-grid input,
.filter-grid select{
    width:100%;
    padding:10px 12px;
    border:1px solid #dbe3f0;
    border-radius:10px;
    background:#fff;
    font-size:13px;
    outline:none;
}
.filter-grid input:focus,
.filter-grid select:focus{
    border-color:#b7c5e9;
    box-shadow:0 0 0 .15rem rgba(34,58,143,.08);
}

/* ====== BIG TAPIS BUTTON BAR (like screenshot) ====== */
.btn-tapis-bar{
    width:100%;
    background:var(--btn-primary);
    color:#fff;
    border:0;
    padding:10px 14px;
    border-radius:6px;
    font-weight:800;
    margin-bottom:14px;
}
.btn-tapis-bar:hover{ background:var(--btn-primary-hover); }

/* ====== TABLE (like screenshot) ====== */
.table-clean{
    width:100%;
    border-collapse:collapse;
}
.table-clean th,
.table-clean td{
    border:1px solid #dbe3f0;
    padding:10px 12px;
    font-size:13px;
    vertical-align:middle;
}
.table-clean thead th{
    background:#ffffff;
    font-weight:900;
    text-align:center;
}
.table-clean td:first-child{
    text-align:center;
    width:220px;
}
.table-clean td:nth-child(3),
.table-clean td:nth-child(4){
    text-align:center;
    width:140px;
}

/* clickable title */
.tender-link{
    color:#1d4ed8;
    font-weight:800;
    text-decoration:underline;
    cursor:pointer;
}

/* badges like screenshot */
.badge-pill{
    display:inline-block;
    padding:4px 10px;
    border-radius:999px;
    font-size:11px;
    font-weight:900;
}
.badge-yellow{ background:#facc15; color:#111827; }
.badge-green{ background:#16a34a; color:#fff; }

/* ====== INFO STRIP (page 2 keep yours) ====== */
.section-bar{
    background:#d9d9d9;
    font-weight:800;
    text-transform:uppercase;
    font-size:12px;
    padding:8px 12px;
    border-radius:2px;
    margin-bottom:10px;
}

.info-top{
    display:flex;
    gap:18px;
    flex-wrap:wrap;
    padding:10px 12px;
    border-top:1px solid #eee;
    border-bottom:1px solid #eee;
    margin-bottom:12px;
    background:#fafafa;
}
.info-top .item{ min-width:190px; }
.info-top small{
    display:block;
    font-size:11px;
    color:#6b7280;
    margin-bottom:2px;
}
.info-top .val{
    display:block;
    font-size:12px;
    font-weight:800;
    color:#111827;
}

.table-blue thead th{
    background:var(--brand-red) !important;
    color:#fff !important;
    font-size:12px;
    font-weight:800;
    text-align:center;
    vertical-align:middle;
    border-color:rgba(255,255,255,.15) !important;
    padding:10px 10px;
    white-space:nowrap;
}
.table-blue td{
    font-size:12px;
    vertical-align:middle;
    padding:10px 10px;
    border-color:var(--line);
}

/* Buttons */
.btn-simpan{
    background:var(--btn-teal);
    color:#fff;
    border:0;
    padding:10px 18px;
    border-radius:6px;
    font-weight:800;
    min-width:110px;
}
.btn-back{
    background:#ef4444;
    color:#fff;
    border:0;
    padding:9px 14px;
    border-radius:8px;
    font-weight:800;
}

/* Lampiran */
.lampiran-row{
    display:flex;
    gap:14px;
    flex-wrap:wrap;
    align-items:center;
    margin-bottom:10px;
}
.lampiran-row input{
    min-width:280px;
    flex:1;
    padding:10px 12px;
    border:1px solid var(--line);
    border-radius:6px;
    font-size:13px;
}
.btn-file{
    background:var(--btn-teal);
    color:#fff;
    border:0;
    padding:10px 16px;
    border-radius:6px;
    font-weight:800;
    min-width:220px;
    text-align:center;
}
.btn-add-doc{
    background:var(--btn-primary);
    color:#fff;
    border:0;
    padding:10px 14px;
    border-radius:6px;
    font-weight:800;
    font-size:12px;
}
.btn-add-doc:hover{ background:var(--btn-primary-hover); }

/* MODAL */
.modal-card{
    border-radius:10px;
    border:0;
    box-shadow:0 10px 30px rgba(0,0,0,.15);
    padding:18px 18px 14px;
    text-align:center;
}
.confetti{ width:44px;height:44px;margin:6px auto 8px; }
.btn-modal{
    background:var(--btn-primary);
    color:#fff;
    border:0;
    padding:10px 20px;
    border-radius:6px;
    font-weight:800;
    min-width:110px;
}
.btn-modal:hover{ background:var(--btn-primary-hover); }

/* responsive like screenshot */
@media (max-width: 992px){
    .filter-grid{ grid-template-columns: 1fr 1fr; }
}
@media (max-width: 576px){
    .filter-grid{ grid-template-columns: 1fr; }
}
</style>

<div class="container-fluid mt-3">

    {{-- =========================
        PAGE 1: SENARAI TENDER (match screenshot)
    ========================== --}}
    <div id="pageList">
        <div class="tender-card">
            <h4 class="page-title">SENARAI TENDER</h4>

            <div class="filter-grid">
                <input type="text" placeholder="No Tender">
                <input type="text" placeholder="Tajuk Perolehan">
                <select>
                    <option>Status</option>
                    <option>Aktif</option>
                    <option>Dalam Proses</option>
                    <option>Selesai</option>
                </select>
                <input type="date" placeholder="dd/mm/yyyy">
            </div>

            <button class="btn-tapis-bar" type="button">Tapis</button>

            <div class="table-responsive">
                <table class="table-clean">
                    <thead>
                    <tr>
                        <th>No Tender</th>
                        <th>Tajuk Perolehan</th>
                        <th>Tarikh</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>QT21000000023741</td>
                        <td>
                            <span class="tender-link"
                                  data-no="QT210000000023741"
                                  data-ptj="JABATAN PENGAIRAN DAN SALIRAN"
                                  data-status="Menunggu Pengesahan Jawatan Kewangan"
                                  data-tajuk="TENDER PERKHIDMATAN DIGITAL FORENSIK KE ATAS ALIRAN PROSES SISTEM XXXX"
                                  data-tamat="17/01/2022"
                                  onclick="openTender(this)">
                                TENDER PERKHIDMATAN DIGITAL FORENSIK KE ATAS ALIRAN PROSES SISTEM XXXX
                            </span>
                        </td>
                        <td>03/03/2024</td>
                        <td><span class="badge-pill badge-yellow">Dalam Proses</span></td>
                    </tr>

                    <tr>
                        <td>QT21000000023799</td>
                        <td>
                            <span class="tender-link"
                                  data-no="QT210000000023799"
                                  data-ptj="JABATAN PENGAIRAN DAN SALIRAN"
                                  data-status="Menunggu Pengesahan Jawatan Kewangan"
                                  data-tajuk="TENDER KERJA-KERJA NAIK TARAF INFRASTRUKTUR RANGKAIAN ICT"
                                  data-tamat="17/01/2022"
                                  onclick="openTender(this)">
                                TENDER KERJA-KERJA NAIK TARAF INFRASTRUKTUR RANGKAIAN ICT
                            </span>
                        </td>
                        <td>05/03/2024</td>
                        <td><span class="badge-pill badge-green">Aktif</span></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- =========================
        PAGE 2: BORANG TEKNIKAL (keep your flow + modal)
    ========================== --}}
    <div id="pageDetail">

        <div class="info-top">
            <div class="item">
                <small>No. Sebut Harga / Tender</small>
                <span class="val" id="dNo">-</span>
                <small class="mt-1">Tempoh Sah Laku Tawaran (Hari)</small>
                <span class="val">90</span>
            </div>

            <div class="item">
                <small>PTJ</small>
                <span class="val" id="dPtj">-</span>
            </div>

            <div class="item">
                <small>Status</small>
                <span class="val" id="dStatus">-</span>
            </div>

            <div class="item">
                <small>Sah Laku Tawaran Tamat</small>
                <span class="val" id="dTamat">-</span>
            </div>

            <div class="item" style="margin-left:auto;">
                <button class="btn-back" type="button" onclick="backToList()">Kembali</button>
            </div>
        </div>

        <div class="section-bar" style="margin-top:18px;">Borang Teknikal</div>

        <div class="table-responsive">
            <table class="table table-bordered table-blue">
                <thead>
                <tr>
                    <th style="width:110px;">Bilangan</th>
                    <th>Rujukan Petender</th>
                    <th style="width:220px;">Harga Tender Asal (RM)</th>
                    <th style="width:160px;">Status</th>
                </tr>
                </thead>
                <tbody>
                @for($i=1;$i<=9;$i++)
                    <tr>
                        <td class="text-center">{{ $i }}</td>
                        <td class="text-center">{{ rand(1,50) }}/53</td>
                        <td class="text-end">{{ number_format(rand(4300000,5200000) + (rand(0,99)/100), 2) }}</td>
                        <td class="text-center">
                            <select class="form-select form-select-sm" style="max-width:120px;margin:auto;">
                                <option selected>Lulus</option>
                                <option>Tidak Lulus</option>
                            </select>
                        </td>
                    </tr>
                @endfor
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end">
            <button class="btn-simpan" type="button" onclick="openSavedModal()">Simpan</button>
        </div>

        <div class="section-bar" style="margin-top:16px;">Lampiran Penilaian Teknikal (Jika Perlu)</div>

        <div class="lampiran-row">
            <input type="text" placeholder="Nama Dokumen">
            <input type="file" id="lampiranFile" class="d-none">
            <button class="btn-file" type="button" onclick="document.getElementById('lampiranFile').click()">Pilih Fail</button>
        </div>

        <button class="btn-add-doc" type="button">Tambah Dokumen</button>
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="savedModal" tabindex="-1" aria-hidden="true">
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
const pageList   = document.getElementById('pageList');
const pageDetail = document.getElementById('pageDetail');

const dNo     = document.getElementById('dNo');
const dPtj    = document.getElementById('dPtj');
const dStatus = document.getElementById('dStatus');
const dTamat  = document.getElementById('dTamat');

function openTender(el){
    dNo.textContent     = el.dataset.no || '-';
    dPtj.textContent    = el.dataset.ptj || '-';
    dStatus.textContent = el.dataset.status || '-';
    dTamat.textContent  = el.dataset.tamat || '-';

    pageList.style.display = 'none';
    pageDetail.style.display = 'block';
}
function backToList(){
    pageDetail.style.display = 'none';
    pageList.style.display = 'block';
}
function openSavedModal(){
    const modal = new bootstrap.Modal(document.getElementById('savedModal'));
    modal.show();
}

window.openTender = openTender;
window.backToList = backToList;
window.openSavedModal = openSavedModal;
</script>
@endsection
