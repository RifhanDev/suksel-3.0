@push('styles')
<style>
    /* ── Section header (red gradient) ── */
    .review-section-header {
        background: linear-gradient(135deg, var(--sg-red, #c41e3a) 0%, #a01830 100%);
        color: #fff;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-radius: 12px 12px 0 0;
    }
    .review-section-header .section-icon {
        width: 32px; height: 32px;
        background: rgba(255,255,255,0.2);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .review-section-header h6 { margin: 0; font-size: 0.9rem; font-weight: 700; }
    .review-section-header small { display: block; font-size: 0.7rem; opacity: 0.85; margin-top: 2px; }

    /* ── Tajuk row ── */
    .review-tajuk { padding: 18px 20px 16px; border-bottom: 1px solid #f1f5f9; }
    .review-tajuk .tajuk-label { font-size: 0.67rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; display: block; margin-bottom: 5px; }
    .review-tajuk .tajuk-value { font-size: 1rem; font-weight: 700; color: #1e293b; line-height: 1.5; }
    .review-tajuk .tajuk-type  { font-size: 0.8rem; font-weight: 400; color: #64748b; font-style: italic; margin-left: 6px; }

    /* ── Key-value rows ── */
    .kv-row { display: flex; align-items: flex-start; padding: 9px 20px; border-bottom: 1px solid #f1f5f9; }
    .kv-row:last-child { border-bottom: none; }
    .kv-row:hover { background: #fafbfc; }
    .kv-label { flex: 0 0 44%; font-size: 0.75rem; color: #64748b; font-weight: 500; padding-right: 14px; line-height: 1.6; padding-top: 1px; }
    .kv-value { flex: 1; font-size: 0.875rem; color: #1e293b; font-weight: 600; line-height: 1.5; }
    .kv-row.kv-amount .kv-value { font-size: 1rem; font-weight: 700; }
    .kv-value.accent { color: var(--sg-red, #c41e3a); }
    .kv-value .rm-prefix { font-size: 0.72rem; font-weight: 600; color: #94a3b8; margin-right: 2px; }
    @media (min-width: 992px) { .kv-col-left { border-right: 1px solid #f1f5f9; } }

    /* ── Code tags & logic connector ── */
    .code-tag { display: inline-flex; align-items: center; padding: 3px 9px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; margin: 2px; }
    .logic-connector { display: inline-flex; align-items: center; padding: 2px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.5px; background: #fef9c3; color: #854d0e; border: 1px solid #fde68a; }

    /* ── Kod Bidang subheader ── */
    .kodbidang-subheader { display: flex; align-items: center; gap: 8px; padding: 10px 16px; background: #dde3ec; border-top: 1px solid #c8d0dc; border-bottom: 1px solid #c8d0dc; }
    .kodbidang-subheader .subheader-title { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; color: #334155; }
    .kodbidang-subheader .subheader-note  { font-size: 0.7rem; color: #64748b; }

    /* ── Code groups ── */
    .code-group { border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; }
    .code-group-header { display: flex; align-items: center; justify-content: space-between; padding: 6px 14px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
    .code-group-header .group-label { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; color: #64748b; }
    .code-group-items { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; padding: 12px 14px; }
    .code-item { display: flex; align-items: center; gap: 10px; padding: 9px 14px; border-bottom: 1px solid #f8fafc; font-size: 0.82rem; color: #1e293b; }
    .code-item:last-child { border-bottom: none; }
    .code-item:hover { background: #fafcff; }
    .code-item-type { flex: 0 0 108px; font-size: 0.7rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.3px; }

    /* ── Between-group connector ── */
    .group-connector { display: flex; align-items: center; gap: 10px; padding: 10px 4px; }
    .group-connector::before, .group-connector::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }
    .group-connector-text { display: flex; align-items: center; gap: 8px; white-space: nowrap; font-size: 0.68rem; color: #94a3b8; }

    /* ── MOF ↔ CIDB separator ── */
    .mof-cidb-separator { display: flex; align-items: center; justify-content: center; gap: 10px; padding: 13px 16px; background: #1e293b; color: #cbd5e1; font-size: 0.75rem; flex-wrap: wrap; text-align: center; }
    .mof-cidb-separator .sep-label { font-weight: 600; color: #fff; }
    .mof-cidb-separator .sep-connector { background: var(--sg-red, #c41e3a); color: #fff; padding: 3px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.5px; flex-shrink: 0; }
    .mof-cidb-separator .sep-note { font-size: 0.7rem; color: #94a3b8; flex-basis: 100%; text-align: center; }
</style>
@endpush

<!-- SECTION 1: MAKLUMAT UMUM -->
<div class="content-card mb-4 p-0">

    <div class="review-section-header">
        <div class="section-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
            </svg>
        </div>
        <div>
            <h6>Maklumat Umum</h6>
            <small>Butiran asas tender / sebut harga</small>
        </div>
    </div>

    <!-- Tajuk Perolehan -->
    <div class="review-tajuk">
        <span class="tajuk-label">Tajuk Perolehan</span>
        <span class="tajuk-value">
            MEMBEKAL RANGSUM PUKAL (AIR MINERAL) UNTUK BANGUNAN KERAJAAN
            <span class="tajuk-type">(Bekalan Perkhidmatan)</span>
        </span>
    </div>

    <!-- Maklumat Asas -->
    <div class="row g-0">
        <div class="col-12 col-lg-6 kv-col-left">
            <div class="kv-row">
                <span class="kv-label">Kaedah Perolehan</span>
                <span class="kv-value">Tender Terbuka</span>
            </div>
            <div class="kv-row">
                <span class="kv-label">Kategori Jenis Perolehan</span>
                <span class="kv-value">Bekalan</span>
            </div>
            <div class="kv-row">
                <span class="kv-label">Jenis Kontrak</span>
                <span class="kv-value">Jabatan</span>
            </div>
            <div class="kv-row" style="border-bottom:none;">
                <span class="kv-label">Disediakan Untuk PTJ</span>
                <span class="kv-value">JABATAN KERJA RAYA SELANGOR (100-007)</span>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="kv-row">
                <span class="kv-label">No. Rujukan Fail</span>
                <span class="kv-value">JKR.SEL.100-007/2026</span>
            </div>
            <div class="kv-row">
                <span class="kv-label">No. Tender / Sebut Harga</span>
                <span class="kv-value accent">SUKSEL/PERT/2026/001</span>
            </div>
            <div class="kv-row">
                <span class="kv-label">Tarikh Dicipta</span>
                <span class="kv-value">14/04/2026</span>
            </div>
            <div class="kv-row" style="border-bottom:none;">
                <span class="kv-label">No. Kontrak Sedia Ada</span>
                <span class="kv-value text-secondary fw-normal">—</span>
            </div>
        </div>
    </div>

    <!-- Nilai & Tempoh -->
    <div class="px-3 py-2 bg-light border-top border-bottom small fw-bold text-uppercase text-secondary" style="letter-spacing:0.5px; font-size:0.68rem;">Nilai &amp; Tempoh</div>
    <div class="row g-0">
        <div class="col-12 col-lg-6 kv-col-left">
            <div class="kv-row kv-amount">
                <span class="kv-label">Harga Indikatif Jabatan</span>
                <span class="kv-value"><span class="rm-prefix">RM</span>125,000.00</span>
            </div>
            <div class="kv-row kv-amount" style="border-bottom:none;">
                <span class="kv-label">Anggaran Jabatan</span>
                <span class="kv-value"><span class="rm-prefix">RM</span>118,500.00</span>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="kv-row">
                <span class="kv-label">Kategori Perolehan</span>
                <span class="kv-value">Perkhidmatan Am</span>
            </div>
            <div class="kv-row" style="border-bottom:none;">
                <span class="kv-label">Tempoh Kontrak / Penyiapan</span>
                <span class="kv-value">12 Bulan</span>
            </div>
        </div>
    </div>

    <!-- Maklumat Tambahan -->
    <div class="px-3 py-2 bg-light border-top border-bottom small fw-bold text-uppercase text-secondary" style="letter-spacing:0.5px; font-size:0.68rem;">Maklumat Tambahan</div>
    <div class="row g-0">
        <div class="col-12 col-lg-6 kv-col-left">
            <div class="kv-row">
                <span class="kv-label">Sumber Peruntukan</span>
                <span class="kv-value">Pembangunan</span>
            </div>
            <div class="kv-row">
                <span class="kv-label">Lokaliti Liputan</span>
                <span class="kv-value">Petaling Jaya</span>
            </div>
            <div class="kv-row">
                <span class="kv-label">Zon / Lokasi</span>
                <span class="kv-value text-success fw-semibold d-inline-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    Ya
                </span>
            </div>
            <div class="kv-row" style="border-bottom:none;">
                <span class="kv-label">Penghantaran Fizikal</span>
                <span class="kv-value text-secondary fw-medium d-inline-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    Tidak
                </span>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="kv-row">
                <span class="kv-label">Terbuka Kepada</span>
                <span class="kv-value">Semua</span>
            </div>
            <div class="kv-row">
                <span class="kv-label">Jawatankuasa Spesifikasi</span>
                <span class="kv-value text-success fw-semibold d-inline-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    Ya
                </span>
            </div>
            <div class="kv-row" style="border-bottom:none;">
                <span class="kv-label">Taklimat Tender / Lawatan Tapak</span>
                <span class="kv-value text-secondary fw-medium d-inline-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    Tiada
                </span>
            </div>
        </div>
    </div>

</div>
<!-- End Section 1 -->

<!-- SECTION 2: KOD BIDANG & SYARAT -->
<div class="content-card mb-4 p-0">

    <div class="review-section-header">
        <div class="section-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
        </div>
        <div>
            <h6>Kod Bidang &amp; Syarat</h6>
            <small>Kelayakan MOF dan CIDB yang ditetapkan</small>
        </div>
    </div>

    <!-- MOF sub-header -->
    <div class="kodbidang-subheader">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
        </svg>
        <span class="subheader-title">Kod Bidang MOF</span>
        <span class="subheader-note">— Kementerian Kewangan Malaysia</span>
    </div>

    <!-- MOF groups -->
    <div class="p-3 d-flex flex-column gap-0">
        <div class="code-group">
            <div class="code-group-header">
                <span class="group-label">Kumpulan 1</span>
            </div>
            <div class="code-group-items">
                <span class="code-tag">210101 — Makanan &amp; Minuman Industri</span>
                <span class="logic-connector">ATAU</span>
                <span class="code-tag">210102 — Bekalan Air Minuman</span>
            </div>
        </div>
        <div class="group-connector">
            <div class="group-connector-text">
                <span class="logic-connector">DAN</span>
                <span>Kumpulan 1 dan Kumpulan 2 mesti dipenuhi serentak</span>
            </div>
        </div>
        <div class="code-group">
            <div class="code-group-header">
                <span class="group-label">Kumpulan 2</span>
            </div>
            <div class="code-group-items">
                <span class="code-tag">310401 — Perkhidmatan Pengedaran</span>
            </div>
        </div>
    </div>

    <!-- MOF ↔ CIDB separator -->
    <div class="mof-cidb-separator">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="16" x2="12" y2="12"></line>
            <line x1="12" y1="8" x2="12.01" y2="8"></line>
        </svg>
        <span class="sep-label">Kelayakan MOF</span>
        <span class="sep-connector">DAN</span>
        <span class="sep-label">Kelayakan CIDB</span>
        <span class="sep-note ms-1">— Syarikat mesti memenuhi kedua-dua kelayakan ini secara serentak untuk layak menyertai tender.</span>
    </div>

    <!-- CIDB sub-header -->
    <div class="kodbidang-subheader">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="6" width="20" height="12" rx="2"></rect>
            <path d="M12 12h.01"></path><path d="M17 12h.01"></path><path d="M7 12h.01"></path>
        </svg>
        <span class="subheader-title">Kod Bidang CIDB</span>
        <span class="subheader-note">— Lembaga Pembangunan Industri Pembinaan</span>
    </div>

    <!-- CIDB groups -->
    <div class="p-3">
        <div class="code-group">
            <div class="code-group-header">
                <span class="group-label">Kumpulan 1</span>
            </div>
            <div>
                <div class="code-item">
                    <span class="code-item-type">Gred</span>
                    <div class="d-flex align-items-center flex-wrap gap-1">
                        <span class="code-tag">G5</span>
                        <span class="code-tag">G6</span>
                        <span class="code-tag">G7</span>
                        <span class="text-secondary" style="font-size:0.68rem; margin-left:4px;">(mana-mana satu)</span>
                    </div>
                </div>
                <div class="code-item">
                    <span class="code-item-type">Pengkhususan</span>
                    <div class="d-flex align-items-center flex-wrap gap-1">
                        <span class="code-tag">CE21 — Jalan Raya</span>
                        <span class="logic-connector">ATAU</span>
                        <span class="code-tag">CE22 — Jambatan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- End Section 2 -->
