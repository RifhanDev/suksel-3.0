@extends('layouts.v3.master')

@section('content')
<style>
    :root{
        --brand-red:#B11217;
        --line:#d9dde5;
        --soft:#f5f6f8;
        --bar:#d9d9d9;

        --btn-green:#18a34a;      /* Papar */
        --btn-teal:#19c1a7;       /* Simpan */
        --btn-blue:#2f3f91;       /* Tutup/primary modal */
    }

    /* ====== SECTION BAR ====== */
    .section-bar{
        background:var(--bar);
        font-weight:800;
        text-transform:uppercase;
        font-size:12px;
        padding:8px 12px;
        border-radius:2px;
        margin:12px 0 10px;
    }

    /* ====== TOP INFO STRIP ====== */
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

    /* ====== TABLE (HEADER BLUE) ====== */
    .table-blue thead th{
        background:var(--brand-red) !important;
        color:#fff !important;
        font-size:12px;
        font-weight:800;
        text-align:center;
        vertical-align:middle;
        border-color:rgba(255,255,255,.15) !important;
        padding:9px 10px;
        white-space:nowrap;
    }
    .table-blue td{
        font-size:12px;
        vertical-align:middle;
        padding:9px 10px;
        border-color:var(--line);
    }

    /* ====== BUTTONS ====== */
    .btn-papar{
        background:var(--btn-green);
        color:#fff;
        border:0;
        padding:6px 14px;
        border-radius:4px;
        font-weight:800;
        font-size:12px;
        min-width:74px;
    }
    .btn-simpan{
        background:var(--btn-teal);
        color:#fff;
        border:0;
        padding:9px 18px;
        border-radius:4px;
        font-weight:800;
        font-size:12px;
        min-width:92px;
    }
    .btn-kembali{
        background:var(--btn-teal);
        color:#fff;
        border:0;
        padding:9px 18px;
        border-radius:4px;
        font-weight:800;
        font-size:12px;
        min-width:92px;
    }

    /* ====== MODAL LOOK ====== */
    .doc-modal .modal-content{
        border-radius:4px;
        border:0;
        overflow:hidden;
        box-shadow:0 10px 30px rgba(0,0,0,.18);
    }
    .doc-modal .modal-header{
        background:var(--bar);
        border:0;
        padding:10px 14px;
    }
    .doc-modal .modal-title{
        font-size:12px;
        font-weight:900;
        text-transform:uppercase;
    }
    .doc-note{
        font-size:11px;
        color:#2563eb;
        margin:6px 0 6px;
        font-style:italic;
    }

    /* ====== SUCCESS MODAL (like image) ====== */
    .modal-card{
        border-radius:8px;
        border:0;
        box-shadow:0 10px 30px rgba(0,0,0,.15);
        padding:18px 18px 14px;
        text-align:center;
    }
    .confetti{ width:44px;height:44px;margin:6px auto 8px; }
    .btn-modal{
        background:var(--btn-blue);
        color:#fff;
        border:0;
        padding:9px 18px;
        border-radius:4px;
        font-weight:900;
        min-width:110px;
        font-size:12px;
    }

    /* Small inputs in modal */
    .modal .form-control,
    .modal .form-select{
        font-size:12px;
        padding:8px 10px;
        border-color:var(--line);
    }

    /* Icon cell */
    .doc-ic{
        display:flex; align-items:center; gap:8px;
    }
    .doc-icon{
        width:18px;height:18px; display:inline-flex; align-items:center; justify-content:center;
        color:#2563eb;
    }
</style>

<div class="container-fluid mt-3">

    {{-- ====== TOP STRIP ====== --}}
    <div class="info-top">
        <div class="item">
            <small>No. Sebut Harga / Tender</small>
            <span class="val">QT210000000023741</span>

            <small class="mt-1">Tempoh Sah Laku Tawaran (Hari)</small>
            <span class="val">90</span>
        </div>

        <div class="item">
            <small>PTJ</small>
            <span class="val">JABATAN PENGAIRAN DAN SALIRAN</span>
        </div>

        <div class="item">
            <small>Status</small>
            <span class="val">Menunggu Pengesahan Jawatan Kewangan</span>
        </div>

        <div class="item">
            <small>Sah Laku Tawaran Tamat</small>
            <span class="val">17/01/2022</span>
        </div>
    </div>

    {{-- ====== BORANG 2 ====== --}}
    <div class="section-bar">BORANG 2 - ANALISA KECUKUPAN DOKUMAN</div>

    <div class="table-responsive">
        <table class="table table-bordered table-blue">
            <thead>
                <tr>
                    <th>Dokumen</th>
                    <th style="width:140px;">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Lembaran Imbangan</td>
                    <td class="text-center">
                        <button class="btn-papar" type="button" onclick="openDocModal('imbangan','Lembaran Imbangan')">Papar</button>
                    </td>
                </tr>
                <tr>
                    <td>Penyata Bulanan / Akaun Bank</td>
                    <td class="text-center">
                        <button class="btn-papar" type="button" onclick="openDocModal('penyata_bank','Penyata Bank')">Papar</button>
                    </td>
                </tr>
                <tr>
                    <td>Bon atau Saham</td>
                    <td class="text-center">
                        <button class="btn-papar" type="button" onclick="openDocModal('bon_saham','Bon atau Saham')">Papar</button>
                    </td>
                </tr>
                <tr>
                    <td>Prestasi Kerja Semasa Petender</td>
                    <td class="text-center">
                        <button class="btn-papar" type="button" onclick="openDocModal('prestasi','Prestasi Kerja Semasa Petender')">Papar</button>
                    </td>
                </tr>
                <tr>
                    <td>Laporan Bank atau Borang CA</td>
                    <td class="text-center">
                        <button class="btn-papar" type="button" onclick="openDocModal('laporan_ca','Laporan Bank atau Borang CA')">Papar</button>
                    </td>
                </tr>
                <tr>
                    <td>Laporan Penyelia Projek Bagi Kerja Semasa</td>
                    <td class="text-center">
                        <button class="btn-papar" type="button" onclick="openDocModal('laporan_penyelia','Laporan Penyelia Projek Bagi Kerja Semasa (Borang GA)')">Papar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- ====== RUMUSAN ====== --}}
    <div class="section-bar">RUMUSAN</div>

    <div class="text-uppercase fw-bold mb-2" style="font-size:11px;">
        Keputusan Penilaian Analisa Kecukupan Dokuman
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-blue">
            <thead>
                <tr>
                    <th style="width:120px;">Bil</th>
                    <th>Keputusan <span style="font-weight:600;font-size:11px;">(*Cukup = Cukup walaupun tidak kemukakan Borang GA)</span></th>
                    <th>Ulasan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1/2</td>
                    <td class="text-center">Cukup</td>
                    <td class="text-center">XXX</td>
                </tr>
                <tr>
                    <td class="text-center">2/2</td>
                    <td class="text-center">*Cukup</td>
                    <td class="text-center">XXX</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="d-flex align-items-center gap-2" style="margin:10px 0 12px;">
        <div style="font-size:11px;">Bilangan Pembekal</div>
        <input type="text" class="form-control" value="2" style="max-width:60px;font-size:12px;">
    </div>

    <div class="form-check" style="font-size:12px;">
        <input class="form-check-input" type="checkbox" id="chkSah">
        <label class="form-check-label" for="chkSah">
            Saya mengesahkan petender diatas layak untuk penilaian peringkat seterusnya.
        </label>
    </div>

    <div class="d-flex justify-content-end mt-3">
        <button class="btn-simpan" type="button" onclick="openSavedModal()">Simpan</button>
    </div>

</div>

{{-- =========================
    MODAL: PAPAR DOKUMEN 
========================== --}}
<div class="modal fade doc-modal" id="docModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <div class="w-100">
                    <div class="modal-title" id="docModalTitle">Nama Dokumen</div>
                    <div style="font-size:12px;font-weight:700;margin-top:2px;" id="docModalName">-</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body" style="padding:14px 14px 10px;">

                {{-- ===== VIEW A: LIST PEMBEKAL ===== --}}
                <div id="docViewList">

                    <div class="section-bar" style="margin-top:0;">Senarai Pembekal</div>
                    <div class="doc-note">Klik butang Semak untuk meneruskan penilaian pematuhan</div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-blue">
                            <thead>
                                <tr>
                                    <th style="width:90px;">Bil</th>
                                    <th>Dokumen</th>
                                    <th style="width:170px;">Dikemukakan</th>
                                    <th id="thDiaudit" style="width:170px;">Diaudit</th>
                                    <th style="width:220px;">Catatan</th>
                                </tr>
                            </thead>
                            <tbody id="docRows">
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center" style="padding:6px 0 2px;">
                        <button class="btn-simpan" type="button" onclick="saveFromDocModal()">Simpan</button>
                    </div>

                
                </div>

                {{-- ===== VIEW B: PENYATA BANK DETAIL ===== --}}
                <div id="docViewPenyataBank" style="display:none;">
                    <div class="section-bar" style="margin-top:0;">Penyata Bank</div>
                    <div class="doc-note">Sila pilih bulan pertama penyata bank yang perlu dikemukakan oleh petender</div>

                    <div class="row g-3 align-items-end mb-3">
                        <div class="col-md-3">
                            <label class="form-label" style="font-size:12px;font-weight:700;">Dari (bulan)</label>
                            <select class="form-select">
                                <option>Jun</option><option>Julai</option><option>Ogos</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" style="font-size:12px;font-weight:700;">Tahun</label>
                            <select class="form-select"><option>2024</option><option>2025</option></select>
                        </div>

                        <div class="col-md-2 text-center" style="font-weight:800;font-size:12px;">
                            hingga (bulan)
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" style="font-size:12px;font-weight:700;">Hingga (bulan)</label>
                            <select class="form-select">
                                <option>Ogos</option><option>Sep</option><option>Okt</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" style="font-size:12px;font-weight:700;">Tahun</label>
                            <select class="form-select"><option>2024</option><option>2025</option></select>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:12px;">Penyata Bank Bulan Jun (RM)</label>
                            <input class="form-control" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:12px;">Penyata Bank Bulan Julai (RM)</label>
                            <input class="form-control" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:12px;">Penyata Bank Bulan Ogos (RM)</label>
                            <input class="form-control" value="0">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" style="font-size:12px;">Jumlah Keseluruhan Penyata Bank (RM)</label>
                            <input class="form-control" value="">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:12px;">Purata Penyata Bank (RM)</label>
                            <input class="form-control" value="">
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        <button class="btn-kembali" type="button" onclick="backToDocList()">Kembali</button>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

{{-- =========================
    MODAL: SIMPAN SUCCESS 
========================== --}}
<div class="modal fade" id="savedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
        <div class="modal-content modal-card">
            <svg class="confetti" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16 42 L34 28 L30 50 Z" stroke="#19c1a7" stroke-width="3" fill="none"/>
                <path d="M38 16 C44 20, 48 24, 52 30" stroke="#19c1a7" stroke-width="3" fill="none"/>
                <path d="M40 40 L52 46" stroke="#19c1a7" stroke-width="3"/>
                <path d="M22 18 L18 12" stroke="#19c1a7" stroke-width="3"/>
            </svg>

            <div class="fw-bold" style="font-size:13px;margin-bottom:12px;">
                Maklumat telah berjaya disimpan
            </div>

            <button type="button" class="btn-modal" data-bs-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>

<script>
    // ====== DATA ======
    const docTemplates = {
        imbangan: {
            showDiaudit: true,
            rows: [
                { bil:'1/2', label:'Lembaran Imbangan', link:true, showAudit:true },
                { bil:'2/2', label:'Lembaran Imbangan', link:true, showAudit:true },
            ],
        },
        bon_saham: {
            showDiaudit: false,
            rows: [
                { bil:'1/2', label:'Bon atau Saham', link:true, showAudit:false },
                { bil:'2/2', label:'Bon atau Saham', link:true, showAudit:false },
            ],
        },
        prestasi: {
            showDiaudit: false,
            rows: [
                { bil:'1/2', label:'Prestasi Kerja', link:true, showAudit:false },
                { bil:'2/2', label:'Prestasi Kerja', link:true, showAudit:false },
            ],
        },
        laporan_ca: {
            showDiaudit: false,
            rows: [
                { bil:'1/2', label:'Laporan Bank / Borang CA', link:true, showAudit:false },
                { bil:'2/2', label:'Laporan Bank / Borang CA', link:true, showAudit:false },
            ],
        },
        laporan_penyelia: {
            showDiaudit: false,
            rows: [
                { bil:'1/2', label:'Dokumen', icon:true, link:false, showAudit:false, ddText:'Ya / Tidak / T.K.S' },
                { bil:'2/2', label:'Dokumen', icon:true, link:false, showAudit:false, ddText:'Ya / Tidak / T.K.S' },
            ],
        },
        penyata_bank: {
            // first view is list with link; clicking link goes to detail view
            showDiaudit: false,
            rows: [
                { bil:'1/2', label:'Penyata Bank', link:true, goDetail:true, showAudit:false },
                { bil:'2/2', label:'Penyata Bank', link:true, goDetail:true, showAudit:false },
            ],
        }
    };

    // ====== Modal refs ======
    const docModalEl = document.getElementById('docModal');
    const docModal = () => new bootstrap.Modal(docModalEl);

    const docModalName = document.getElementById('docModalName');
    const docRows = document.getElementById('docRows');
    const thDiaudit = document.getElementById('thDiaudit');

    const docViewList = document.getElementById('docViewList');
    const docViewPenyataBank = document.getElementById('docViewPenyataBank');

    let currentDocType = null;

    function openDocModal(type, name){
        currentDocType = type;

        docModalName.textContent = name || '-';

        // reset views
        docViewList.style.display = 'block';
        docViewPenyataBank.style.display = 'none';

        // inject rows
        const conf = docTemplates[type] || { showDiaudit:false, rows:[] };

        // show/hide Diaudit column like screenshot (some have Diaudit, some don't)
        thDiaudit.style.display = conf.showDiaudit ? '' : 'none';

        docRows.innerHTML = conf.rows.map(r => {
            const docCell = r.icon
                ? `<div class="doc-ic">
                        <span class="doc-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                <path d="M7 3h7l3 3v15H7V3z" stroke="#2563eb" stroke-width="1.8"/>
                                <path d="M14 3v4h4" stroke="#2563eb" stroke-width="1.8"/>
                            </svg>
                        </span>
                        <span>${escapeHtml(r.label)}</span>
                   </div>`
                : (r.link
                    ? `<a href="javascript:void(0)" style="color:#2563eb;text-decoration:underline;font-weight:700;"
                         onclick="${r.goDetail ? `openPenyataBankDetail()` : ``}">
                         ${escapeHtml(r.label)}
                       </a>`
                    : escapeHtml(r.label)
                );

            const dikemukakanDd = r.ddText
                ? `<select class="form-select form-select-sm" style="max-width:160px;margin:auto;">
                        <option selected>${escapeHtml(r.ddText)}</option>
                        <option>Ya</option>
                        <option>Tidak</option>
                   </select>`
                : `<select class="form-select form-select-sm" style="max-width:160px;margin:auto;">
                        <option selected>Ya / Tidak</option>
                        <option>Ya</option>
                        <option>Tidak</option>
                   </select>`;

            const diauditDd = conf.showDiaudit
                ? `<td class="text-center" style="${conf.showDiaudit ? '' : 'display:none;'}">
                        <select class="form-select form-select-sm" style="max-width:160px;margin:auto;">
                            <option selected>Ya / Tidak</option>
                            <option>Ya</option>
                            <option>Tidak</option>
                        </select>
                   </td>`
                : ``;

            return `
                <tr>
                    <td class="text-center">${escapeHtml(r.bil)}</td>
                    <td>${docCell}</td>
                    <td class="text-center">${dikemukakanDd}</td>
                    ${diauditDd}
                    <td><input class="form-control" placeholder=""></td>
                </tr>
            `;
        }).join('');

        // if Diaudit hidden, remove column space from each row (handled via template)
        docModal().show();
    }

    function openPenyataBankDetail(){
        // switch inside the same modal (image like "PENYATA BANK" page)
        docViewList.style.display = 'none';
        docViewPenyataBank.style.display = 'block';
    }

    function backToDocList(){
        docViewPenyataBank.style.display = 'none';
        docViewList.style.display = 'block';
    }

    function saveFromDocModal(){
        // close doc modal then show success
        const m = bootstrap.Modal.getInstance(docModalEl);
        if(m) m.hide();
        openSavedModal();
    }

    function openSavedModal(){
        new bootstrap.Modal(document.getElementById('savedModal')).show();
    }

    // helpers
    function escapeHtml(str){
        return String(str ?? '')
            .replaceAll('&','&amp;')
            .replaceAll('<','&lt;')
            .replaceAll('>','&gt;')
            .replaceAll('"','&quot;')
            .replaceAll("'","&#039;");
    }

    // expose
    window.openDocModal = openDocModal;
    window.openSavedModal = openSavedModal;
    window.saveFromDocModal = saveFromDocModal;
    window.openPenyataBankDetail = openPenyataBankDetail;
    window.backToDocList = backToDocList;
</script>
@endsection
