@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/content-card.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/modal-kuiri.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/modal-confirm.css') }}" rel="stylesheet">
    <style>

        /* ── Section header (red gradient) ──────────────────────────────────── */
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
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .review-section-header h6 {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .review-section-header small {
            display: block;
            font-size: 0.7rem;
            font-weight: 400;
            opacity: 0.85;
            margin-top: 2px;
        }

        /* ── Tajuk row (top of card) ─────────────────────────────────────────── */
        .review-tajuk {
            padding: 18px 20px 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        .review-tajuk .tajuk-label {
            font-size: 0.67rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            display: block;
            margin-bottom: 5px;
        }

        .review-tajuk .tajuk-value {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.5;
        }

        .review-tajuk .tajuk-type {
            font-size: 0.8rem;
            font-weight: 400;
            color: #64748b;
            font-style: italic;
            margin-left: 6px;
        }

        /* ── Horizontal key-value rows ────────────────────────────────────────── */
        .kv-row {
            display: flex;
            align-items: flex-start;
            padding: 9px 20px;
            border-bottom: 1px solid #f1f5f9;
        }

        .kv-row:last-child {
            border-bottom: none;
        }

        .kv-row:hover {
            background: #fafbfc;
        }

        .kv-label {
            flex: 0 0 44%;
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
            padding-right: 14px;
            line-height: 1.6;
            padding-top: 1px;
        }

        .kv-value {
            flex: 1;
            font-size: 0.875rem;
            color: #1e293b;
            font-weight: 600;
            line-height: 1.5;
        }

        .kv-value.muted {
            color: #94a3b8;
            font-weight: 400;
        }

        .kv-value .rm-prefix {
            font-size: 0.72rem;
            font-weight: 600;
            color: #94a3b8;
            margin-right: 2px;
        }

        /* Amount row — slightly larger value */
        .kv-row.kv-amount .kv-value {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
        }

        /* Highlight the primary tender number */
        .kv-value.accent {
            color: var(--sg-red, #c41e3a);
        }

        /* Status values */
        .kv-yes {
            color: #16a34a;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .kv-no {
            color: #94a3b8;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        /* ── Sub-section head inside a card ──────────────────────────────────── */
        .kv-section-head {
            padding: 7px 20px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
        }

        /* Vertical divider between two kv columns on desktop */
        @media (min-width: 992px) {
            .kv-col-left {
                border-right: 1px solid #f1f5f9;
            }
        }

        /* ── Verify table (for Kod Bidang) ────────────────────────────────────── */
        .verify-table {
            font-size: 0.82rem;
            margin-bottom: 0;
            width: 100%;
            border-collapse: collapse;
        }

        .verify-table thead th {
            font-size: 0.67rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            font-weight: 700;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            border-top: none;
            padding: 9px 16px;
        }

        .verify-table tbody td {
            padding: 10px 16px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            color: #1e293b;
        }

        .verify-table tbody tr:last-child td {
            border-bottom: none;
        }

        .verify-table tbody tr:hover td {
            background: #fafbfc;
        }

        /* Connector separator row between groups */
        .verify-table .tr-connector td {
            background: #f8fafc;
            text-align: center;
            padding: 7px 16px;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }

        /* ── Code tag chip ────────────────────────────────────────────────────── */
        .code-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #e2e8f0;
            margin: 2px;
        }

        /* ── Logic connector pill ─────────────────────────────────────────────── */
        .logic-connector {
            display: inline-flex;
            align-items: center;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            background: #fef9c3;
            color: #854d0e;
            border: 1px solid #fde68a;
        }

        /* ── Kod Bidang sub-header strip ──────────────────────────────────────── */
        .kodbidang-subheader {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: #dde3ec;
            border-top: 1px solid #c8d0dc;
            border-bottom: 1px solid #c8d0dc;
        }

        .kodbidang-subheader .subheader-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #334155;
        }

        .kodbidang-subheader .subheader-note {
            font-size: 0.7rem;
            color: #64748b;
        }

        /* ── Code group card (one logical group of codes) ─────────────────────── */
        .code-group {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }

        .code-group-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 6px 14px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .code-group-header .group-label {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #64748b;
        }

        .code-group-header .group-logic-hint {
            font-size: 0.68rem;
            color: #94a3b8;
        }

        /* ── Individual code row inside a group ───────────────────────────────── */
        .code-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            border-bottom: 1px solid #f8fafc;
            font-size: 0.82rem;
            color: #1e293b;
        }

        .code-item:last-child {
            border-bottom: none;
        }

        .code-item:hover {
            background: #fafcff;
        }

        /* Row number circle */
        .code-item .item-num {
            width: 22px;
            height: 22px;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.68rem;
            font-weight: 700;
            color: #64748b;
            flex-shrink: 0;
        }

        /* Label inside a code-item (e.g. "Gred", "Pengkhususan") */
        .code-item-type {
            flex: 0 0 108px;
            font-size: 0.7rem;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* ── Horizontal inline codes row (codes side by side with connector) ────── */
        .code-group-items {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            padding: 12px 14px;
        }

        .code-inline-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            color: #475569;
        }

        /* ── Between-group connector (DAN / ATAU between group cards) ─────────── */
        .group-connector {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 4px;
        }

        .group-connector::before,
        .group-connector::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .group-connector-text {
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            font-size: 0.68rem;
            color: #94a3b8;
        }

        /* ── MOF ↔ CIDB separator banner ─────────────────────────────────────── */
        .mof-cidb-separator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 13px 16px;
            background: #1e293b;
            color: #cbd5e1;
            font-size: 0.75rem;
            flex-wrap: wrap;
            text-align: center;
        }

        .mof-cidb-separator .sep-label {
            font-weight: 600;
            color: #fff;
        }

        .mof-cidb-separator .sep-connector {
            background: var(--sg-red, #c41e3a);
            color: #fff;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            flex-shrink: 0;
        }

        .mof-cidb-separator .sep-note {
            font-size: 0.7rem;
            color: #94a3b8;
            flex-basis: 100%;
            text-align: center;
        }

    </style>
@endsection

@section('content')

    <!-- ── HEADER ─────────────────────────────────────────────────────────────── -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Semak & Sahkan Tender</h3>
            <p class="text-muted small m-0">Semak maklumat tender sebelum meluluskan atau membuat pertanyaan.</p>
        </div>

        <!-- Meta strip -->
        <div class="d-flex flex-wrap align-items-center gap-3 bg-white px-3 py-2 rounded-2 shadow-sm border">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-dark border">NO. TENDER</span>
                <span class="small fw-bold text-muted">SUKSEL/PERT/2026/001</span>
            </div>
            <div class="vr d-none d-lg-block text-muted opacity-25" style="height: 20px;"></div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-dark border">PTJ</span>
                <span class="small fw-bold text-muted">100-007</span>
            </div>
            <div class="vr d-none d-lg-block text-muted opacity-25" style="height: 20px;"></div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-dark border">STATUS</span>
                <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-2 fw-semibold"
                    style="background:#fef9c3;color:#854d0e;font-size:0.72rem;border:1px solid #fde68a;">
                    <span class="rounded-circle" style="width:6px;height:6px;background:#ca8a04;flex-shrink:0;display:inline-block;"></span>
                    Menunggu Kelulusan
                </span>
            </div>
        </div>
    </div>


    <!-- ═══════════════════════════════════════════════════════════════════════════
         SECTION 1: MAKLUMAT UMUM
    ═══════════════════════════════════════════════════════════════════════════ -->
    <div class="content-card mb-4 p-0">

        <!-- Section header -->
        <div class="review-section-header">
            <div class="section-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

        <!-- ── MAKLUMAT ASAS: two-column kv layout ───────────────────────────── -->
        <div class="row g-0">

            <!-- Left column -->
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

            <!-- Right column -->
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
                    <span class="kv-value muted">—</span>
                </div>
            </div>

        </div>

        <!-- ── NILAI & TEMPOH ────────────────────────────────────────────────── -->
        <div class="kv-section-head">Nilai &amp; Tempoh</div>

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

        <!-- ── MAKLUMAT TAMBAHAN ──────────────────────────────────────────────── -->
        <div class="kv-section-head">Maklumat Tambahan</div>

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
                    <span class="kv-value kv-yes">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Ya
                    </span>
                </div>
                <div class="kv-row" style="border-bottom:none;">
                    <span class="kv-label">Penghantaran Fizikal</span>
                    <span class="kv-value kv-no">
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
                    <span class="kv-value kv-yes">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Ya
                    </span>
                </div>
                <div class="kv-row" style="border-bottom:none;">
                    <span class="kv-label">Taklimat Tender / Lawatan Tapak</span>
                    <span class="kv-value kv-no">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        Tiada
                    </span>
                </div>
            </div>
        </div>

    </div>
    <!-- End Section 1 -->


    <!-- ═══════════════════════════════════════════════════════════════════════════
         SECTION 2: KOD BIDANG & SYARAT
    ═══════════════════════════════════════════════════════════════════════════ -->
    <div class="content-card mb-4 p-0">

        <!-- Section header -->
        <div class="review-section-header">
            <div class="section-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>
            <div>
                <h6>Kod Bidang &amp; Syarat</h6>
                <small>Kelayakan MOF dan CIDB yang ditetapkan</small>
            </div>
        </div>

        <!-- ── MOF sub-header ────────────────────────────────────────────────── -->
        <div class="kodbidang-subheader">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
            </svg>
            <span class="subheader-title">Kod Bidang MOF</span>
            <span class="subheader-note">— Kementerian Kewangan Malaysia</span>
        </div>

        <!-- MOF groups -->
        <div class="p-3 d-flex flex-column gap-0">

            <!-- Kumpulan 1 — codes displayed inline, separated by ATAU -->
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

            <!-- Connector: DAN between Group 1 and Group 2 -->
            <div class="group-connector">
                <div class="group-connector-text">
                    <span class="logic-connector">DAN</span>
                    <span>Kumpulan 1 dan Kumpulan 2 mesti dipenuhi serentak</span>
                </div>
            </div>

            <!-- Kumpulan 2 — single code inline -->
            <div class="code-group">
                <div class="code-group-header">
                    <span class="group-label">Kumpulan 2</span>
                </div>
                <div class="code-group-items">
                    <span class="code-tag">310401 — Perkhidmatan Pengedaran</span>
                </div>
            </div>

        </div>

        <!-- ── MOF ↔ CIDB separator — dark banner ───────────────────────────── -->
        <div class="mof-cidb-separator">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            <span class="sep-label">Kelayakan MOF</span>
            <span class="sep-connector">DAN</span>
            <span class="sep-label">Kelayakan CIDB</span>
            <span class="sep-note ms-1">— Syarikat mesti memenuhi kedua-dua kelayakan ini secara serentak untuk layak menyertai tender.</span>
        </div>

        <!-- ── CIDB sub-header ───────────────────────────────────────────────── -->
        <div class="kodbidang-subheader">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="6" width="20" height="12" rx="2"></rect>
                <path d="M12 12h.01"></path>
                <path d="M17 12h.01"></path>
                <path d="M7 12h.01"></path>
            </svg>
            <span class="subheader-title">Kod Bidang CIDB</span>
            <span class="subheader-note">— Lembaga Pembangunan Industri Pembinaan</span>
        </div>

        <!-- CIDB groups -->
        <div class="p-3">

            <!-- Kumpulan 1 -->
            <div class="code-group">
                <div class="code-group-header">
                    <span class="group-label">Kumpulan 1</span>
                </div>
                <div>
                    <!-- Gred row -->
                    <div class="code-item">
                        <span class="code-item-type">Gred</span>
                        <div class="d-flex align-items-center flex-wrap gap-1">
                            <span class="code-tag">G5</span>
                            <span class="code-tag">G6</span>
                            <span class="code-tag">G7</span>
                            <span style="font-size:0.68rem; color:#94a3b8; margin-left:4px;">(mana-mana satu)</span>
                        </div>
                    </div>
                    <!-- Pengkhususan row -->
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


    <!-- ── ACTION BUTTONS ─────────────────────────────────────────────────────── -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

        <!-- Back -->
        <a href="{{ route('ciptaTender') }}" class="btn-form btn-form-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali
        </a>

        <!-- Kuiri + Lulus -->
        <div class="d-flex gap-2">

            <!-- Kuiri -->
            <button type="button" class="btn-form btn-form-danger" data-bs-toggle="modal" data-bs-target="#modalKuiri">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                Kuiri
            </button>

            <!-- Lulus -->
            <button type="button" class="btn-form btn-form-success" id="btn-lulus">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                Lulus
            </button>

        </div>
    </div>
@endsection

@push('modals')
    <!-- ═══════════════════════════════════════════════════════════════════════════
         MODAL: KUIRI
    ═══════════════════════════════════════════════════════════════════════════ -->
    <div class="modal fade" id="modalKuiri" tabindex="-1" aria-labelledby="modalKuiriLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-kuiri">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-kuiri-header">
                    <div class="modal-kuiri-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8"  x2="12"   y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0 fw-bold" id="modalKuiriLabel">Hantar Kuiri</h5>
                        <p class="modal-kuiri-subtitle mb-0">Nyatakan sebab atau catatan bagi pertanyaan ini.</p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <!-- Body -->
                <div class="modal-body p-4">

                    <!-- Alert hint -->
                    <div class="modal-kuiri-alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                            <line x1="12" y1="9"  x2="12"   y2="13"></line>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                        <span>Borang akan dikembalikan kepada penghantar untuk semakan semula. Sila nyatakan dengan jelas bahagian yang perlu diperbetulkan.</span>
                    </div>

                    <!-- Catatan textarea -->
                    <div>
                        <label for="catatan_kuiri" class="form-label fw-semibold modal-kuiri-label">
                            Catatan / Sebab Kuiri <span class="text-danger">*</span>
                        </label>
                        <textarea id="catatan_kuiri" name="catatan_kuiri" class="form-control" rows="5"
                            placeholder="Contoh: Sila kemaskini No. Tender yang tepat dan semak semula kategori perolehan yang dipilih..."></textarea>
                        <div class="d-flex justify-content-between mt-1">
                            <span class="modal-kuiri-hint">Minimum 10 aksara diperlukan.</span>
                            <span id="catatan_kuiri-char-count" class="modal-kuiri-hint">0 aksara</span>
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="modal-footer border-top px-4 py-3 d-flex justify-content-between gap-2">
                    <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6"  y2="18"></line>
                            <line x1="6"  y1="6" x2="18" y2="18"></line>
                        </svg>
                        Batal
                    </button>
                    <button type="button" class="btn-form btn-form-danger" id="btn-confirm-kuiri">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2"  x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                        Hantar Kuiri
                    </button>
                </div>

            </div>
        </div>
    </div>


    <!-- ═══════════════════════════════════════════════════════════════════════════
         MODAL: LULUS CONFIRMATION
    ═══════════════════════════════════════════════════════════════════════════ -->
    <div class="modal fade" id="modalLulus" tabindex="-1" aria-labelledby="modalLulusLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-confirm">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header border-bottom-0 pt-4 pb-2 px-4">
                    <div class="modal-confirm-center">
                        <div class="modal-confirm-icon modal-confirm-icon--success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                        <h5 class="modal-confirm-title" id="modalLulusLabel">Sahkan Kelulusan</h5>
                        <p class="modal-confirm-desc">
                            Adakah anda pasti ingin meluluskan tender ini? Tindakan ini tidak boleh dibatalkan.
                        </p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer border-top-0 px-4 pb-4 pt-2 d-flex justify-content-center gap-2">
                    <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="button" class="btn-form btn-form-success" id="btn-confirm-lulus">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        Ya, Luluskan
                    </button>
                </div>

            </div>
        </div>
    </div>
@endpush

@section('scripts')
<script>
$(document).ready(function () {

    // ── Character counter for Kuiri textarea ────────────────────────────────
    $('#catatan_kuiri').on('input', function () {
        var len = $(this).val().length;
        $('#catatan_kuiri-char-count').text(len + ' aksara');
        if (len >= 10) {
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else {
            $(this).removeClass('is-valid');
        }
    });

    // ── Validate & submit Kuiri ──────────────────────────────────────────────
    $('#btn-confirm-kuiri').on('click', function () {
        var catatan = $('#catatan_kuiri').val().trim();
        if (catatan.length < 10) {
            $('#catatan_kuiri').addClass('is-invalid').focus();
            if ($('#catatan_kuiri-error').length === 0) {
                $('#catatan_kuiri').after(
                    '<div id="catatan_kuiri-error" class="invalid-feedback">Sila masukkan catatan sekurang-kurangnya 10 aksara.</div>'
                );
            }
            return;
        }

        // TODO: replace with actual form submit / AJAX call
        // e.g. $.post('{ route("submitKuiriTender") }', { catatan: catatan, _token: '{{ csrf_token() }}' })

        $('#modalKuiri').modal('hide');
        showToast('Kuiri berjaya dihantar. Borang akan dikembalikan kepada penghantar.', 'warning');
    });

    // ── Lulus button triggers confirmation modal ─────────────────────────────
    $('#btn-lulus').on('click', function () {
        $('#modalLulus').modal('show');
    });

    // ── Confirm Lulus ────────────────────────────────────────────────────────
    $('#btn-confirm-lulus').on('click', function () {
        // TODO: replace with actual form submit / AJAX call
        // e.g. $.post('{ route("lulusTender") }', { _token: '{{ csrf_token() }}' })

        $('#modalLulus').modal('hide');
        showToast('Tender berjaya diluluskan!', 'success');
    });

    // ── Simple toast helper (nanti akan dibawa ke master.blade untuk global usage, for now sini dulu sebelum demo) ──────────────────────────────────────────────────
    function showToast(message, type) {
        var bgMap = { success: '#16a34a', warning: '#d97706', danger: '#dc2626' };
        var bg    = bgMap[type] || '#334155';

        var $toast = $(
            '<div style="position:fixed;top:104px;right:24px;z-index:9999;' +
            'background:' + bg + ';color:#fff;padding:12px 20px;border-radius:10px;' +
            'font-size:0.82rem;font-weight:600;box-shadow:0 4px 16px rgba(0,0,0,0.18);' +
            'display:flex;align-items:center;gap:10px;max-width:360px;">' +
                '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" ' +
                'stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">' +
                    '<polyline points="20 6 9 17 4 12"></polyline>' +
                '</svg>' +
                '<span>' + message + '</span>' +
            '</div>'
        );

        $('body').append($toast);
        $toast.hide().fadeIn(200);

        setTimeout(function () {
            $toast.fadeOut(300, function () { $(this).remove(); });
        }, 3500);
    }

    // ── Reset modal state on close ───────────────────────────────────────────
    $('#modalKuiri').on('hidden.bs.modal', function () {
        $('#catatan_kuiri').val('').removeClass('is-invalid is-valid');
        $('#catatan_kuiri-error').remove();
        $('#catatan_kuiri-char-count').text('0 aksara');
    });

});
</script>
@endsection
