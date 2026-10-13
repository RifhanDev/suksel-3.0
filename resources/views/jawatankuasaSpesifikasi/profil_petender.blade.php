@extends($layout ?? 'layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/guideline-card.css') }}" rel="stylesheet">
    <style>
        /* ── Table grid borders for profil table ──────────────────────── */
        #tbl-profil {
            border: 1px solid #e2e8f0;
        }
        #tbl-profil thead th {
            background: #1e3a5f;
            color: #fff;
            font-size: 0.78rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            border-color: #1e3a5f !important;
        }
        #tbl-profil th,
        #tbl-profil td {
            border-right: 1px solid #e2e8f0 !important;
        }
        #tbl-profil th:last-child,
        #tbl-profil td:last-child {
            border-right: none !important;
        }

        /* Sub-field row styling inside Butiran cells */
        .sub-field-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sub-field-row + .sub-field-row {
            margin-top: 8px;
        }
        .sub-field-label {
            flex-shrink: 0;
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
            min-width: 110px;
        }
        .sub-field-input {
            flex: 1;
            min-width: 0;
        }

        /* Numbered list items inside Butiran (aset / peralatan) */
        .numbered-input-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .numbered-input-row + .numbered-input-row {
            margin-top: 6px;
        }
        .numbered-input-num {
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #f1f5f9;
            font-size: 0.65rem;
            font-weight: 700;
            color: #64748b;
        }
        .numbered-input-row .form-control {
            flex: 1;
            min-width: 0;
        }

        /* ── Projek table borders ──────────────────────────────────── */
        #tbl-projek {
            border: 1px solid #e2e8f0;
        }
        #tbl-projek th,
        #tbl-projek td {
            border-right: 1px solid #e2e8f0 !important;
        }
        #tbl-projek th:last-child,
        #tbl-projek td:last-child {
            border-right: none !important;
        }

        /* ── Analisa tables borders ────────────────────────────────── */
        #tbl-modal-berbayar,
        #tbl-modal-dibenarkan {
            border: 1px solid #e2e8f0;
        }
        #tbl-modal-berbayar th, #tbl-modal-berbayar td,
        #tbl-modal-dibenarkan th, #tbl-modal-dibenarkan td {
            border-right: 1px solid #e2e8f0 !important;
        }
        #tbl-modal-berbayar th:last-child, #tbl-modal-berbayar td:last-child,
        #tbl-modal-dibenarkan th:last-child, #tbl-modal-dibenarkan td:last-child {
            border-right: none !important;
        }

        /* Loading overlay */
        #loading-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(2px);
            align-items: center;
            justify-content: center;
        }
        #loading-overlay.active { display: flex; }
        .loading-box {
            background: #fff;
            border-radius: 12px;
            padding: 28px 36px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        }
        .loading-spinner {
            width: 28px;
            height: 28px;
            border: 3px solid #e2e8f0;
            border-top-color: #3b82f6;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            flex-shrink: 0;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .loading-text {
            font-size: 0.9rem;
            font-weight: 600;
            color: #1e293b;
        }
        .loading-check {
            display: none;
            width: 28px;
            height: 28px;
            flex-shrink: 0;
            color: #22c55e;
        }
        #loading-overlay.success .loading-spinner { display: none; }
        #loading-overlay.success .loading-check { display: block; }
        #loading-overlay.success .loading-text { color: #16a34a; }

        .borang-title-bar {
            background: #e2e8f0;
            color: #1e293b;
            font-weight: 700;
            font-size: 0.82rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 10px 16px;
            border-radius: 6px 6px 0 0;
        }
        .borang-instruction {
            font-size: 0.78rem;
            color: #475569;
            line-height: 1.55;
        }
        .vendor-profile-locked {
            background-color: #f8fafc !important;
            cursor: not-allowed;
        }
    </style>
@endsection

@section('content')
    @include('tenders.forms._view_only_lock')

    @php
        $showVendorForm = $showVendorForm ?? ($vendorFormMode ?? false);
        $showScoringConfig = $showScoringConfig ?? ! $showVendorForm;
        $isKerja = (isset($tender->kategori_perolehan_name) && strtolower($tender->kategori_perolehan_name) === 'kerja');
        $kembaliUrl = $returnUrl ?? ($isKerja
            ? route('senaraiKewanganKerja', $tender->uuid)
            : route('senaraiKewanganBekalan', $tender->uuid));
    @endphp
    @unless ($modalEmbed ?? false)
    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Maklumat Profil Petender</h3>
            <p class="text-muted small m-0">
                @if ($showVendorForm)
                    Borang atas talian untuk petender mengisi maklumat profil syarikat.
                @else
                    Penetapan skema pemarkahan profil petender bagi tender ini.
                @endif
            </p>
        </div>
    </div>
    @endunless

    <form id="form-profil-petender" action="{{ route('profilPetender.store', $tender->uuid) }}" method="POST">
    @csrf
    @if ($modalEmbed ?? false)
        <input type="hidden" name="modal" value="1">
    @endif

        @unless ($modalEmbed ?? false)
        <!-- TENDER INFO CARD -->
        <div class="content-card mb-4 p-0">
            <div class="content-card-body p-4">

                <!-- Tajuk Tender -->
                <div class="mb-3 pb-3 border-bottom">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size: 0.67rem; letter-spacing: 0.5px;">Tajuk Tender</span>
                    <h5 class="fw-bold text-dark mb-0" style="line-height: 1.45; font-size: 1rem;">
                        {{ $tender->name ?? 'Tiada Tajuk' }}
                        @if($tender->kategori_perolehan_name)
                        <span class="fw-normal text-muted fst-italic" style="font-size: 0.85rem;">({{ $tender->kategori_perolehan_name }})</span>
                        @endif
                    </h5>
                </div>

                <!-- Metadata: No. Tender · PTJ · Status -->
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                            style="font-size: 0.67rem; letter-spacing: 0.5px;">No. Tender</span>
                        <span class="fw-semibold text-dark" style="font-size: 0.875rem;">{{ $tender->no_tender ?? $tender->ref_number ?? '-' }}</span>
                    </div>
                    <div class="col-6 col-md-3">
                        <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                            style="font-size: 0.67rem; letter-spacing: 0.5px;">PTJ</span>
                        <span class="fw-semibold text-dark" style="font-size: 0.875rem;">{{ optional($tender->tenderer)->name ?? '-' }}</span>
                    </div>
                    <div class="col-12 col-md-6 d-md-flex justify-content-md-end align-items-md-center">
                        <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2 fw-semibold"
                            style="background: #fef9c3; color: #854d0e; font-size: 0.8rem; border: 1px solid #fde68a;">
                            <span class="d-inline-block rounded-circle"
                                style="width:7px;height:7px;background:#ca8a04;flex-shrink:0;"></span>
                            Dalam Proses
                        </span>
                    </div>
                </div>

            </div>
        </div>
        @endunless

        <!-- ===================== SECTION 1: MAKLUMAT PROFIL PETENDER ===================== -->
        @if ($showVendorForm)
        @php
            // Lock identity fields whenever reviewing a specific vendor (vendor fill OR staff mode=view).
            $lockVendorProfile = ($vendorFormMode ?? false)
                || (($viewOnly ?? false) && ! empty($vendorProfilDefaults));
            $vp = $vendorProfilDefaults ?? [];
        @endphp
        <div class="content-card mb-4 p-0">
            <div class="borang-title-bar">Maklumat Profil Petender</div>
            <div class="content-card-body p-4 pt-3">

                <div class="mb-4">
                    <p class="borang-instruction mb-2">
                        <strong>1.</strong> Borang ini hendaklah diisi dengan <strong>lengkap secara bertaip</strong> bagi memudahkan Lembaga Perolehan menimbangkan tender ini. Kegagalan berbuat demikian boleh menjejaskan peluang petender.
                    </p>
                    <p class="borang-instruction mb-0">
                        <strong>2.</strong> Jangan gunakan atau merujuk kepada <strong>lampiran lain</strong>, kecuali diminta di ruang berkenaan. Jika ruang tidak mencukupi, borang boleh dicetak semula dalam format asal.
                    </p>
                    @if ($lockVendorProfile)
                        <p class="borang-instruction mb-0 mt-2 text-primary">
                            <strong>Nota:</strong> Maklumat syarikat (nama, jenis, alamat, pendaftaran, modal, dll.) diambil daripada profil syarikat anda dan tidak boleh diubah di sini.
                        </p>
                    @endif
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table id="tbl-profil" class="table table-modern align-middle mb-0 w-100">
                        <thead>
                            <tr>
                                <th class="text-center py-3" style="width:55px;">Bil</th>
                                <th class="py-3" style="width:280px;">Perkara</th>
                                <th class="py-3">Butiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- 1. Nama Syarikat -->
                            <tr>
                                <td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">1</td>
                                <td class="fw-semibold" style="font-size:0.85rem;">Nama Syarikat</td>
                                <td>
                                    <input type="text" name="nama_syarikat"
                                        class="form-control form-control-sm{{ $lockVendorProfile ? ' vendor-profile-locked' : '' }}"
                                        placeholder="Masukkan nama syarikat..."
                                        value="{{ $vp['nama_syarikat'] ?? '' }}"
                                        @if ($lockVendorProfile) readonly @endif>
                                </td>
                            </tr>
                            <!-- 2. Jenis Syarikat -->
                            <tr>
                                <td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">2</td>
                                <td class="fw-semibold" style="font-size:0.85rem;">Jenis Syarikat</td>
                                <td>
                                    @if ($lockVendorProfile)
                                        <input type="text" class="form-control form-control-sm vendor-profile-locked" readonly
                                            value="{{ $vp['jenis_syarikat_label'] ?? '' }}">
                                        <input type="hidden" name="jenis_syarikat" value="{{ $vp['jenis_syarikat'] ?? '' }}">
                                    @else
                                        <select name="jenis_syarikat" class="form-select form-select-sm">
                                            <option value="">— Sila pilih —</option>
                                            <option value="persendirian">Persendirian</option>
                                            <option value="perkongsian">Perkongsian</option>
                                            <option value="koperasi">Koperasi</option>
                                            <option value="sdn_bhd">Sdn Bhd</option>
                                        </select>
                                    @endif
                                </td>
                            </tr>
                            <!-- 3. Taraf Petender -->
                            <tr>
                                <td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">3</td>
                                <td class="fw-semibold" style="font-size:0.85rem;">Taraf Petender <span class="fw-normal text-muted" style="font-size:0.75rem;">(Sertakan salinan sijil)</span></td>
                                <td>
                                    @if ($lockVendorProfile)
                                        <input type="text" class="form-control form-control-sm vendor-profile-locked" readonly
                                            value="{{ $vp['taraf_petender_label'] ?? '' }}">
                                        <input type="hidden" name="taraf_petender" value="{{ $vp['taraf_petender'] ?? '' }}">
                                    @else
                                        <select name="taraf_petender" class="form-select form-select-sm">
                                            <option value="">— Sila pilih —</option>
                                            <option value="bumiputera">Bumiputera</option>
                                            <option value="bukan_bumiputera">Bukan Bumiputera</option>
                                        </select>
                                    @endif
                                </td>
                            </tr>
                            <!-- 4. Alamat -->
                            <tr>
                                <td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">4</td>
                                <td class="fw-semibold" style="font-size:0.85rem;">Alamat</td>
                                <td>
                                    <textarea name="alamat_syarikat"
                                        class="form-control form-control-sm{{ $lockVendorProfile ? ' vendor-profile-locked' : '' }}"
                                        rows="3" placeholder="Masukkan alamat penuh syarikat..."
                                        @if ($lockVendorProfile) readonly @endif>{{ $vp['alamat'] ?? '' }}</textarea>
                                </td>
                            </tr>
                            <!-- 5. Pegawai untuk dihubungi -->
                            <tr>
                                <td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">5</td>
                                <td class="fw-semibold" style="font-size:0.85rem;">Pegawai untuk dihubungi</td>
                                <td>
                                    <div class="sub-field-row">
                                        <span class="sub-field-label">Nama</span>
                                        <input type="text" name="pegawai_nama"
                                            class="form-control form-control-sm sub-field-input{{ $lockVendorProfile ? ' vendor-profile-locked' : '' }}"
                                            placeholder="Nama pegawai..."
                                            value="{{ $vp['pegawai_nama'] ?? '' }}"
                                            @if ($lockVendorProfile) readonly @endif>
                                    </div>
                                    <div class="sub-field-row">
                                        <span class="sub-field-label">No. Telefon</span>
                                        <input type="text" name="pegawai_telefon"
                                            class="form-control form-control-sm sub-field-input{{ $lockVendorProfile ? ' vendor-profile-locked' : '' }}"
                                            placeholder="Cth: 012-3456789"
                                            value="{{ $vp['pegawai_telefon'] ?? '' }}"
                                            @if ($lockVendorProfile) readonly @endif>
                                    </div>
                                    <div class="sub-field-row">
                                        <span class="sub-field-label">E-mel</span>
                                        <input type="email" name="pegawai_emel"
                                            class="form-control form-control-sm sub-field-input{{ $lockVendorProfile ? ' vendor-profile-locked' : '' }}"
                                            placeholder="contoh@email.com"
                                            value="{{ $vp['pegawai_emel'] ?? '' }}"
                                            @if ($lockVendorProfile) readonly @endif>
                                    </div>
                                </td>
                            </tr>
                            <!-- 6. Maklumat Pendaftaran -->
                            <tr>
                                <td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">6</td>
                                <td class="fw-semibold" style="font-size:0.85rem;">Maklumat Pendaftaran Petender</td>
                                <td>
                                    <div class="sub-field-row">
                                        <span class="sub-field-label">No. SSM</span>
                                        <input type="text" name="no_ssm"
                                            class="form-control form-control-sm sub-field-input{{ $lockVendorProfile ? ' vendor-profile-locked' : '' }}"
                                            placeholder="No. SSM..."
                                            value="{{ $vp['no_ssm'] ?? '' }}"
                                            @if ($lockVendorProfile) readonly @endif>
                                    </div>
                                    <div class="sub-field-row">
                                        <span class="sub-field-label">No. MOF</span>
                                        <input type="text" name="no_mof"
                                            class="form-control form-control-sm sub-field-input{{ $lockVendorProfile ? ' vendor-profile-locked' : '' }}"
                                            placeholder="No. MOF..."
                                            value="{{ $vp['no_mof'] ?? '' }}"
                                            @if ($lockVendorProfile) readonly @endif>
                                    </div>
                                    <div class="sub-field-row">
                                        <span class="sub-field-label">Tempoh Sah MOF</span>
                                        <input type="text" name="tempoh_sah_mof"
                                            class="form-control form-control-sm sub-field-input{{ $lockVendorProfile ? ' vendor-profile-locked' : '' }}"
                                            placeholder=""
                                            value="{{ $vp['tempoh_sah_mof'] ?? '' }}"
                                            @if ($lockVendorProfile) readonly @endif>
                                    </div>
                                </td>
                            </tr>
                            <!-- 7. Bilangan Pekerja Sekarang -->
                            <tr>
                                <td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">7</td>
                                <td class="fw-semibold" style="font-size:0.85rem;">Bilangan Pekerja Sekarang</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2" style="max-width:200px;">
                                        <input type="number" name="bil_pekerja_sekarang" class="form-control form-control-sm text-center" placeholder="0" min="0">
                                        <span class="text-muted small flex-shrink-0">orang</span>
                                    </div>
                                </td>
                            </tr>
                            <!-- 8. Bilangan Pekerja Teknikal -->
                            <tr>
                                <td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">8</td>
                                <td class="fw-semibold" style="font-size:0.85rem;">Bilangan Pekerja Teknikal <span class="fw-normal text-muted" style="font-size:0.75rem;">(Cadangan bagi melaksanakan Kontrak, jika berjaya kelak)</span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2" style="max-width:200px;">
                                        <input type="number" name="bil_pekerja_teknikal" class="form-control form-control-sm text-center" placeholder="0" min="0">
                                        <span class="text-muted small flex-shrink-0">orang</span>
                                    </div>
                                </td>
                            </tr>
                            <!-- 9. Modal Berbayar -->
                            <tr>
                                <td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">9</td>
                                <td class="fw-semibold" style="font-size:0.85rem;">Modal Berbayar</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2" style="max-width:220px;">
                                        <span class="text-muted small flex-shrink-0">RM</span>
                                        <input type="text" name="modal_berbayar"
                                            class="form-control form-control-sm text-end amount-input{{ $lockVendorProfile ? ' vendor-profile-locked' : '' }}"
                                            placeholder="0.00"
                                            value="{{ ! empty($vp['modal_berbayar']) ? number_format((float) $vp['modal_berbayar'], 2) : '' }}"
                                            @if ($lockVendorProfile) readonly @endif>
                                    </div>
                                </td>
                            </tr>
                            <!-- 10. Modal Dibenarkan -->
                            <tr>
                                <td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">10</td>
                                <td class="fw-semibold" style="font-size:0.85rem;">Modal Dibenarkan</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2" style="max-width:220px;">
                                        <span class="text-muted small flex-shrink-0">RM</span>
                                        <input type="text" name="modal_dibenarkan"
                                            class="form-control form-control-sm text-end amount-input{{ $lockVendorProfile ? ' vendor-profile-locked' : '' }}"
                                            placeholder="0.00"
                                            value="{{ ! empty($vp['modal_dibenarkan']) ? number_format((float) $vp['modal_dibenarkan'], 2) : '' }}"
                                            @if ($lockVendorProfile) readonly @endif>
                                    </div>
                                </td>
                            </tr>
                            <!-- 11. Kedudukan Kewangan Semasa -->
                            <tr style="background:#f1f5f9;">
                                <td class="text-center fw-semibold text-dark" style="font-size:0.85rem; background:#f1f5f9;" rowspan="4">11</td>
                                <td colspan="2" class="fw-semibold text-dark" style="font-size:0.9rem; background:#f1f5f9;">
                                    Kedudukan Kewangan Semasa
                                </td>
                            </tr>
                            <!-- 11.1 Harta (Aset) Syarikat -->
                            <tr>
                                <td class="fw-semibold" style="font-size:0.82rem;">
                                    11.1 Harta (Aset) Syarikat
                                    <span class="d-block fw-normal text-muted" style="font-size:0.72rem;">Senarai 5 aset yang terbesar</span>
                                </td>
                                <td>
                                    <p class="text-muted fst-italic mb-2" style="font-size:0.72rem;">Contoh: 1. Rumah Kedai - 1 unit</p>
                                    <div class="numbered-input-row">
                                        <span class="numbered-input-num">1</span>
                                        <input type="text" name="aset[]" class="form-control form-control-sm" placeholder="Aset 1...">
                                    </div>
                                    <div class="numbered-input-row">
                                        <span class="numbered-input-num">2</span>
                                        <input type="text" name="aset[]" class="form-control form-control-sm" placeholder="Aset 2...">
                                    </div>
                                    <div class="numbered-input-row">
                                        <span class="numbered-input-num">3</span>
                                        <input type="text" name="aset[]" class="form-control form-control-sm" placeholder="Aset 3...">
                                    </div>
                                    <div class="numbered-input-row">
                                        <span class="numbered-input-num">4</span>
                                        <input type="text" name="aset[]" class="form-control form-control-sm" placeholder="Aset 4...">
                                    </div>
                                    <div class="numbered-input-row">
                                        <span class="numbered-input-num">5</span>
                                        <input type="text" name="aset[]" class="form-control form-control-sm" placeholder="Aset 5...">
                                    </div>
                                </td>
                            </tr>
                            <!-- 11.2 Peralatan -->
                            <tr>
                                <td class="fw-semibold" style="font-size:0.82rem;">
                                    11.2 Peralatan
                                    <span class="d-block fw-normal text-muted" style="font-size:0.72rem;">Nyatakan nilai dan senaraikan 5 peralatan berkaitan tender ini</span>
                                </td>
                                <td>
                                    <p class="text-muted fst-italic mb-2" style="font-size:0.72rem;">Contoh: 1. Kereta Perdana V6 (2002) - RM40,000.00</p>
                                    <div class="numbered-input-row">
                                        <span class="numbered-input-num">1</span>
                                        <input type="text" name="peralatan[]" class="form-control form-control-sm" placeholder="Peralatan 1...">
                                    </div>
                                    <div class="numbered-input-row">
                                        <span class="numbered-input-num">2</span>
                                        <input type="text" name="peralatan[]" class="form-control form-control-sm" placeholder="Peralatan 2...">
                                    </div>
                                    <div class="numbered-input-row">
                                        <span class="numbered-input-num">3</span>
                                        <input type="text" name="peralatan[]" class="form-control form-control-sm" placeholder="Peralatan 3...">
                                    </div>
                                    <div class="numbered-input-row">
                                        <span class="numbered-input-num">4</span>
                                        <input type="text" name="peralatan[]" class="form-control form-control-sm" placeholder="Peralatan 4...">
                                    </div>
                                    <div class="numbered-input-row">
                                        <span class="numbered-input-num">5</span>
                                        <input type="text" name="peralatan[]" class="form-control form-control-sm" placeholder="Peralatan 5...">
                                    </div>
                                </td>
                            </tr>
                            <!-- 11.3 & 11.4 combined row area -->
                            <tr>
                                <td>
                                    <div class="fw-semibold mb-2" style="font-size:0.82rem;">11.3 Tanggungan / Liabilities (RM)</div>
                                    <div class="fw-semibold" style="font-size:0.82rem;">11.4 Baki Wang Dalam Bank (RM)</div>
                                </td>
                                <td>
                                    <div class="mb-2 d-flex align-items-center gap-2" style="max-width:220px;">
                                        <span class="text-muted small flex-shrink-0">RM</span>
                                        <input type="text" name="tanggungan" class="form-control form-control-sm text-end amount-input" placeholder="0.00">
                                    </div>
                                    <div class="d-flex align-items-center gap-2" style="max-width:220px;">
                                        <span class="text-muted small flex-shrink-0">RM</span>
                                        <input type="text" name="baki_wang_bank" class="form-control form-control-sm text-end amount-input" placeholder="0.00">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        @endif

        <!-- ===================== SECTION 2: ANALISA KECUKUPAN MODAL BERBAYAR ===================== -->
        @if ($showScoringConfig)
        <div class="content-card mb-4 p-0">
            <div class="content-card-header p-4 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="content-card-icon" style="width: 38px; height: 38px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2c5.523 0 10 4.477 10 10a10 10 0 0 1-19.995.324L2 12l.004-.28C2.152 6.327 6.57 2 12 2m0 9h-1l-.117.007a1 1 0 0 0 0 1.986L11 13v3l.007.117a1 1 0 0 0 .876.876L12 17h1l.117-.007a1 1 0 0 0 .876-.876L14 16l-.007-.117a1 1 0 0 0-.764-.857l-.112-.02L13 15v-3l-.007-.117a1 1 0 0 0-.876-.876zm.01-3l-.127.007a1 1 0 0 0 0 1.986L12 10l.127-.007a1 1 0 0 0 0-1.986z"/></svg>
                    </div>
                    <div>
                        <h3 class="content-card-title mb-0" style="font-size: 1rem;">Analisa Kecukupan Modal Berbayar</h3>
                        <p class="text-muted mb-0" style="font-size: 0.78rem;">Penetapan skema pemarkahan</p>
                    </div>
                </div>
            </div>
            <div class="content-card-body p-4">

                <!-- Jenis Skor -->
                <div class="row mb-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label fw-semibold small">Jenis Skor <span class="text-danger">*</span></label>
                        <select name="jenis_skor_berbayar" class="form-select form-select-sm jenis-skor-select-berbayar">
                            <option value="">— Sila pilih —</option>
                            <option value="manual">Manual</option>
                            <option value="automatik">Automatik</option>
                        </select>
                    </div>
                </div>

                <!-- Automatik panel — calculated read-only rows -->
                <div id="panel-berbayar-automatik" class="d-none">
                    <div class="table-responsive">
                        <table class="table table-modern align-middle mb-0 w-100" style="border:1px solid #e2e8f0;">
                            <thead>
                                <tr>
                                    <th class="text-center py-3" style="width:55px;">Bil</th>
                                    <th class="py-3">Dari (RM)</th>
                                    <th class="py-3">Hingga (RM)</th>
                                    <th class="py-3 text-center" style="width:120px;">Skema</th>
                                </tr>
                            </thead>
                            <tbody id="tbl-berbayar-auto-body">
                                <!-- rendered by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Manual panel (table) — user-editable rows -->
                <div id="panel-berbayar" class="d-none">
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 btn-tambah-berbayar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Tambah
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table id="tbl-modal-berbayar" class="table table-modern align-middle mb-0 w-100">
                            <thead>
                                <tr>
                                    <th class="text-center py-3" style="width:55px;">Bil</th>
                                    <th class="py-3">Dari</th>
                                    <th class="py-3">Hingga</th>
                                    <th class="py-3" style="width:140px;">Skema</th>
                                    <th class="text-center py-3" style="width:60px;">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody id="tbl-berbayar-body">
                                <!-- seeded by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===================== SECTION 3: ANALISA KECUKUPAN MODAL DIBENARKAN ===================== -->
        <div class="content-card mb-4 p-0">
            <div class="content-card-header p-4 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="content-card-icon" style="width: 38px; height: 38px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2c5.523 0 10 4.477 10 10a10 10 0 0 1-19.995.324L2 12l.004-.28C2.152 6.327 6.57 2 12 2m0 9h-1l-.117.007a1 1 0 0 0 0 1.986L11 13v3l.007.117a1 1 0 0 0 .876.876L12 17h1l.117-.007a1 1 0 0 0 .876-.876L14 16l-.007-.117a1 1 0 0 0-.764-.857l-.112-.02L13 15v-3l-.007-.117a1 1 0 0 0-.876-.876zm.01-3l-.127.007a1 1 0 0 0 0 1.986L12 10l.127-.007a1 1 0 0 0 0-1.986z"/></svg>
                    </div>
                    <div>
                        <h3 class="content-card-title mb-0" style="font-size: 1rem;">Analisa Kecukupan Modal Dibenarkan</h3>
                        <p class="text-muted mb-0" style="font-size: 0.78rem;">Penetapan skema pemarkahan</p>
                    </div>
                </div>
            </div>
            <div class="content-card-body p-4">

                <!-- Jenis Skor -->
                <div class="row mb-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label fw-semibold small">Jenis Skor <span class="text-danger">*</span></label>
                        <select name="jenis_skor_dibenarkan" class="form-select form-select-sm jenis-skor-select-dibenarkan">
                            <option value="">— Sila pilih —</option>
                            <option value="manual">Manual</option>
                            <option value="automatik">Automatik</option>
                        </select>
                    </div>
                </div>

                <!-- Automatik panel — calculated read-only rows -->
                <div id="panel-dibenarkan-automatik" class="d-none">
                    <div class="table-responsive">
                        <table class="table table-modern align-middle mb-0 w-100" style="border:1px solid #e2e8f0;">
                            <thead>
                                <tr>
                                    <th class="text-center py-3" style="width:55px;">Bil</th>
                                    <th class="py-3">Dari (RM)</th>
                                    <th class="py-3">Hingga (RM)</th>
                                    <th class="py-3 text-center" style="width:120px;">Skema</th>
                                </tr>
                            </thead>
                            <tbody id="tbl-dibenarkan-auto-body">
                                <!-- rendered by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Manual panel (table) — user-editable rows -->
                <div id="panel-dibenarkan" class="d-none">
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 btn-tambah-dibenarkan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Tambah
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table id="tbl-modal-dibenarkan" class="table table-modern align-middle mb-0 w-100">
                            <thead>
                                <tr>
                                    <th class="text-center py-3" style="width:55px;">Bil</th>
                                    <th class="py-3">Dari</th>
                                    <th class="py-3">Hingga</th>
                                    <th class="py-3" style="width:140px;">Skema</th>
                                    <th class="text-center py-3" style="width:60px;">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody id="tbl-dibenarkan-body">
                                <!-- seeded by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- ===================== ACTION BUTTONS ===================== -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            @include('tenders.forms._vendor_form_kembali', ['kembaliUrl' => $kembaliUrl])
            <div class="d-flex gap-2">
                @if (!($viewOnly ?? false))
                <button type="button" class="btn-form btn-form-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    Laporan
                </button>
                <button type="button" id="btn-simpan" class="btn-form btn-form-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Simpan
                </button>
                @endif
            </div>
        </div>

    </form>

    <!-- Loading overlay -->
    <div id="loading-overlay">
        <div class="loading-box">
            <div class="loading-spinner"></div>
            <svg class="loading-check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M8 12l3 3 5-5"></path>
            </svg>
            <span class="loading-text" id="loading-text">Menyimpan...</span>
        </div>
    </div>

@endsection

@section('scripts')
<script>
$(document).ready(function () {

    var DELETE_BTN =
        '<button type="button" class="btn btn-sm btn-hapus-row d-inline-flex align-items-center justify-content-center p-0" ' +
        'style="width:28px;height:28px;border-radius:6px;background:#fee2e2;color:#ef4444;border:none;" title="Buang baris">' +
        '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path></svg>' +
        '</button>';

    var profilData = @json($profilData ?? null);
    var vendorProfilDefaults = @json($vendorProfilDefaults ?? null);
    var LOCK_VENDOR_PROFILE = @json($lockVendorProfile ?? false);
    var STORE_URL  = '{{ route("profilPetender.store", $tender->uuid) }}';
    var CSRF_TOKEN = '{{ csrf_token() }}';
    var KEMBALI_URL = @json($kembaliUrl);
    var SHOW_VENDOR_FORM = @json($showVendorForm);
    var SHOW_SCORING_CONFIG = @json($showScoringConfig);

    function parseAmt(s) { return parseFloat(String(s).replace(/,/g, '')) || 0; }
    function fmtAmt(n) { return n.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }

    function prefillVendorFields() {
        if (LOCK_VENDOR_PROFILE && vendorProfilDefaults) {
            $('#form-profil-petender [name="nama_syarikat"]').val(vendorProfilDefaults.nama_syarikat || '');
            $('#form-profil-petender [name="jenis_syarikat"]').val(vendorProfilDefaults.jenis_syarikat || '');
            $('#form-profil-petender [name="taraf_petender"]').val(vendorProfilDefaults.taraf_petender || '');
            $('#form-profil-petender [name="alamat_syarikat"]').val(vendorProfilDefaults.alamat || '');
            $('#form-profil-petender [name="pegawai_nama"]').val(vendorProfilDefaults.pegawai_nama || '');
            $('#form-profil-petender [name="pegawai_telefon"]').val(vendorProfilDefaults.pegawai_telefon || '');
            $('#form-profil-petender [name="pegawai_emel"]').val(vendorProfilDefaults.pegawai_emel || '');
            $('#form-profil-petender [name="no_ssm"]').val(vendorProfilDefaults.no_ssm || '');
            $('#form-profil-petender [name="no_mof"]').val(vendorProfilDefaults.no_mof || '');
            $('#form-profil-petender [name="tempoh_sah_mof"]').val(vendorProfilDefaults.tempoh_sah_mof || '');
            $('#form-profil-petender [name="modal_berbayar"]').val(vendorProfilDefaults.modal_berbayar > 0 ? fmtAmt(vendorProfilDefaults.modal_berbayar) : '');
            $('#form-profil-petender [name="modal_dibenarkan"]').val(vendorProfilDefaults.modal_dibenarkan > 0 ? fmtAmt(vendorProfilDefaults.modal_dibenarkan) : '');
        } else if (profilData) {
            $('#form-profil-petender [name="nama_syarikat"]').val(profilData.nama_syarikat || '');
            $('#form-profil-petender [name="jenis_syarikat"]').val(profilData.jenis_syarikat || '');
            $('#form-profil-petender [name="taraf_petender"]').val(profilData.taraf_petender || '');
            $('#form-profil-petender [name="alamat_syarikat"]').val(profilData.alamat || '');
            $('#form-profil-petender [name="pegawai_nama"]').val(profilData.pegawai_nama || '');
            $('#form-profil-petender [name="pegawai_telefon"]').val(profilData.pegawai_telefon || '');
            $('#form-profil-petender [name="pegawai_emel"]').val(profilData.pegawai_emel || '');
            $('#form-profil-petender [name="no_ssm"]').val(profilData.no_ssm || '');
            $('#form-profil-petender [name="no_mof"]').val(profilData.no_mof || '');
            $('#form-profil-petender [name="tempoh_sah_mof"]').val(profilData.tempoh_sah_mof || '');
            $('#form-profil-petender [name="modal_berbayar"]').val(profilData.modal_berbayar > 0 ? fmtAmt(profilData.modal_berbayar) : '');
            $('#form-profil-petender [name="modal_dibenarkan"]').val(profilData.modal_dibenarkan > 0 ? fmtAmt(profilData.modal_dibenarkan) : '');
        }

        if (!profilData) return;

        $('#form-profil-petender [name="bil_pekerja_sekarang"]').val(profilData.bil_pekerja || 0);
        $('#form-profil-petender [name="bil_pekerja_teknikal"]').val(profilData.bil_pekerja_teknikal || 0);
        if (profilData.aset && profilData.aset.length) {
            profilData.aset.forEach(function (v, i) { $('#form-profil-petender [name="aset[]"]').eq(i).val(v || ''); });
        }
        if (profilData.peralatan && profilData.peralatan.length) {
            profilData.peralatan.forEach(function (v, i) { $('#form-profil-petender [name="peralatan[]"]').eq(i).val(v || ''); });
        }
        $('#form-profil-petender [name="tanggungan"]').val(profilData.tanggungan > 0 ? fmtAmt(profilData.tanggungan) : '');
        $('#form-profil-petender [name="baki_wang_bank"]').val(profilData.baki_wang_bank > 0 ? fmtAmt(profilData.baki_wang_bank) : '');
    }

    if (SHOW_VENDOR_FORM) {
        prefillVendorFields();
    }

    if (SHOW_SCORING_CONFIG) {
    function initAnalisa(bodySelector, btnSelector, namePrefix) {
        var $body = $(bodySelector);

        function buildRow(bil) {
            return $('<tr class="analisa-row">' +
                '<td class="text-center row-bil fw-semibold text-muted" style="font-size:0.8rem;">' + bil + '</td>' +
                '<td><input type="text" name="' + namePrefix + '_dari[]" class="form-control form-control-sm amount-input" placeholder="0.00"></td>' +
                '<td><input type="text" name="' + namePrefix + '_hingga[]" class="form-control form-control-sm amount-input" placeholder="0.00"></td>' +
                '<td><input type="text" name="' + namePrefix + '_skema[]" class="form-control form-control-sm" placeholder="0"></td>' +
                '<td class="text-center">' + DELETE_BTN + '</td>' +
            '</tr>');
        }

        function reNumber() {
            $body.find('.analisa-row').each(function (i) {
                $(this).find('.row-bil').text(i + 1);
            });
        }

        // Seed first row
        $body.append(buildRow(1));

        $(btnSelector).on('click', function () {
            var bil = $body.find('.analisa-row').length + 1;
            $body.append(buildRow(bil));
        });

        $body.on('click', '.btn-hapus-row', function () {
            if ($body.find('.analisa-row').length <= 1) return;
            $(this).closest('tr').remove();
            reNumber();
        });

        // Range validation: Dari < Hingga, each row Dari > previous row Hingga
        $body.on('blur', '.amount-input', function () {
            var $input  = $(this);
            var $td     = $input.closest('td');
            var $row    = $td.closest('tr');
            var colIdx  = $td.index(); // 1=Dari, 2=Hingga

            if (colIdx === 1) {
                var $prevRow = $row.prev('tr.analisa-row');
                if ($prevRow.length) {
                    var prevHingga = parseAmt($prevRow.find('td:eq(2) input').val());
                    var dari = parseAmt($input.val());
                    $input.toggleClass('is-invalid', prevHingga > 0 && dari > 0 && dari <= prevHingga);
                }
            } else if (colIdx === 2) {
                var $nextRow = $row.next('tr.analisa-row');
                if ($nextRow.length) {
                    var hingga = parseAmt($input.val());
                    var $nextDari = $nextRow.find('td:eq(1) input');
                    var nextDari = parseAmt($nextDari.val());
                    $nextDari.toggleClass('is-invalid', hingga > 0 && nextDari > 0 && nextDari <= hingga);
                }
                // Also: Hingga must be >= Dari on same row
                var dariSame = parseAmt($row.find('td:eq(1) input').val());
                var hinggaSame = parseAmt($input.val());
                $input.toggleClass('is-invalid', dariSame > 0 && hinggaSame > 0 && hinggaSame < dariSame);
            }
        });

        // Auto-fill next row Dari on Tambah
        $(btnSelector).off('click');
        $(btnSelector).on('click', function () {
            var bil = $body.find('.analisa-row').length + 1;
            var $newRow = buildRow(bil);
            var $lastRow = $body.find('tr.analisa-row').last();
            if ($lastRow.length) {
                var lastHingga = parseAmt($lastRow.find('td:eq(2) input').val());
                if (lastHingga > 0) {
                    $newRow.find('td:eq(1) input').val(fmtAmt(lastHingga + 1));
                }
            }
            $body.append($newRow);
        });
    }

    initAnalisa('#tbl-berbayar-body', '.btn-tambah-berbayar', 'berbayar');
    initAnalisa('#tbl-dibenarkan-body', '.btn-tambah-dibenarkan', 'dibenarkan');

    // ══════════════════════════════════════════════════════════════════════════
    // AUTOMATIK ROWS — calculated in PHP from tender.anggaran_jabatan
    // ══════════════════════════════════════════════════════════════════════════
    var AUTOMATIK_ROWS_BERBAYAR   = @json($automatikRowsBerbayar);
    var AUTOMATIK_ROWS_DIBENARKAN = @json($automatikRowsDibenarkan);

    var keAtasBadge =
        '<span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-2 fw-semibold" ' +
        'style="background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;font-size:0.78rem;">' +
            '<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'11\' height=\'11\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2.5\' stroke-linecap=\'round\' stroke-linejoin=\'round\'>' +
                '<line x1=\'12\' y1=\'19\' x2=\'12\' y2=\'5\'></line>' +
                '<polyline points=\'5 12 12 5 19 12\'></polyline>' +
            '</svg>' +
            'Ke Atas' +
        '</span>';

    function renderAutomatikRows(tbodyId, namePrefix, rows) {
        var $tbody = $('#' + tbodyId);
        var html   = '';
        $.each(rows, function (i, row) {
            var hinggaDisplay = row.hingga !== null ? '<span class="fw-semibold" style="font-size:0.85rem;">' + fmtAmt(row.hingga) + '</span>' : keAtasBadge;
            var hinggaValue   = row.hingga !== null ? parseFloat(row.hingga).toFixed(2) : '';
            html +=
                '<tr>' +
                '<td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">' + (i + 1) + '</td>' +
                '<td class="fw-semibold" style="font-size:0.85rem;">' + fmtAmt(row.dari) + '<input type="hidden" name="' + namePrefix + '_dari[]" value="' + parseFloat(row.dari).toFixed(2) + '"></td>' +
                '<td>' + hinggaDisplay + '<input type="hidden" name="' + namePrefix + '_hingga[]" value="' + hinggaValue + '"></td>' +
                '<td class="text-center fw-semibold" style="font-size:0.85rem;">' + row.skema + '<input type="hidden" name="' + namePrefix + '_skema[]" value="' + row.skema + '"></td>' +
                '</tr>';
        });
        $tbody.html(html);
    }

    // ══════════════════════════════════════════════════════════════════════════
    // JENIS SKOR — toggle panels, disable hidden panel inputs to prevent
    //              null submissions from the panel that's not active
    // ══════════════════════════════════════════════════════════════════════════
    function handleJenisSkor(val, manualPanelId, autoPanelId, autoTbodyId, namePrefix, automatikRows) {
        var $manual = $('#' + manualPanelId);
        var $auto   = $('#' + autoPanelId);

        $manual.addClass('d-none');
        $auto.addClass('d-none');
        $manual.find('input, select').prop('disabled', true);
        $auto.find('input').prop('disabled', true);

        if (val === 'manual') {
            $manual.removeClass('d-none');
            $manual.find('input, select').prop('disabled', false);
        } else if (val === 'automatik') {
            renderAutomatikRows(autoTbodyId, namePrefix, automatikRows);
            $auto.removeClass('d-none');
            $auto.find('input').prop('disabled', false);
        }
    }

    $('.jenis-skor-select-berbayar').on('change', function () {
        handleJenisSkor($(this).val(), 'panel-berbayar', 'panel-berbayar-automatik', 'tbl-berbayar-auto-body', 'berbayar', AUTOMATIK_ROWS_BERBAYAR);
    });

    $('.jenis-skor-select-dibenarkan').on('change', function () {
        handleJenisSkor($(this).val(), 'panel-dibenarkan', 'panel-dibenarkan-automatik', 'tbl-dibenarkan-auto-body', 'dibenarkan', AUTOMATIK_ROWS_DIBENARKAN);
    });

    // ══════════════════════════════════════════════════════════════════════════
    // PRE-FILL SCORING ITEMS
    // ══════════════════════════════════════════════════════════════════════════
    if (profilData) {
        var berbayarItems    = (profilData.scoring_items || []).filter(function (s) { return s.jenis_skor === 'modal_berbayar'; });
        var dibenarkanItems  = (profilData.scoring_items || []).filter(function (s) { return s.jenis_skor === 'modal_dibenarkan'; });

        if (profilData.jenis_skor_modal_berbayar) {
            $('[name="jenis_skor_berbayar"]').val(profilData.jenis_skor_modal_berbayar);
            handleJenisSkor(profilData.jenis_skor_modal_berbayar, 'panel-berbayar', 'panel-berbayar-automatik', 'tbl-berbayar-auto-body', 'berbayar', AUTOMATIK_ROWS_BERBAYAR);
            if (profilData.jenis_skor_modal_berbayar === 'manual' && berbayarItems.length) {
                var $bb = $('#tbl-berbayar-body');
                $bb.empty();
                $.each(berbayarItems, function (i, s) {
                    $bb.append(
                        '<tr class="analisa-row">' +
                        '<td class="text-center row-bil fw-semibold text-muted" style="font-size:0.8rem;">' + (i + 1) + '</td>' +
                        '<td><input type="text" name="berbayar_dari[]" class="form-control form-control-sm amount-input" value="' + fmtAmt(s.dari || 0) + '"></td>' +
                        '<td><input type="text" name="berbayar_hingga[]" class="form-control form-control-sm amount-input" value="' + (s.hingga !== null && s.hingga !== undefined ? fmtAmt(s.hingga) : '') + '"></td>' +
                        '<td><input type="text" name="berbayar_skema[]" class="form-control form-control-sm" value="' + (s.skema || '') + '"></td>' +
                        '<td class="text-center">' + DELETE_BTN + '</td>' +
                        '</tr>'
                    );
                });
            }
        }

        if (profilData.jenis_skor_modal_dibenarkan) {
            $('[name="jenis_skor_dibenarkan"]').val(profilData.jenis_skor_modal_dibenarkan);
            handleJenisSkor(profilData.jenis_skor_modal_dibenarkan, 'panel-dibenarkan', 'panel-dibenarkan-automatik', 'tbl-dibenarkan-auto-body', 'dibenarkan', AUTOMATIK_ROWS_DIBENARKAN);
            if (profilData.jenis_skor_modal_dibenarkan === 'manual' && dibenarkanItems.length) {
                var $db = $('#tbl-dibenarkan-body');
                $db.empty();
                $.each(dibenarkanItems, function (i, s) {
                    $db.append(
                        '<tr class="analisa-row">' +
                        '<td class="text-center row-bil fw-semibold text-muted" style="font-size:0.8rem;">' + (i + 1) + '</td>' +
                        '<td><input type="text" name="dibenarkan_dari[]" class="form-control form-control-sm amount-input" value="' + fmtAmt(s.dari || 0) + '"></td>' +
                        '<td><input type="text" name="dibenarkan_hingga[]" class="form-control form-control-sm amount-input" value="' + (s.hingga !== null && s.hingga !== undefined ? fmtAmt(s.hingga) : '') + '"></td>' +
                        '<td><input type="text" name="dibenarkan_skema[]" class="form-control form-control-sm" value="' + (s.skema || '') + '"></td>' +
                        '<td class="text-center">' + DELETE_BTN + '</td>' +
                        '</tr>'
                    );
                });
            }
        }
    }
    }

    // ══════════════════════════════════════════════════════════════════════════
    // AMOUNT INPUT — auto comma formatting (delegated for dynamic rows too)
    // ══════════════════════════════════════════════════════════════════════════
    function formatAmount(value) {
        var num = parseFloat(value.replace(/,/g, '')) || 0;
        return num.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    $(document).on('focus', '.amount-input:not(.vendor-profile-locked)', function () {
        var raw = $(this).val().replace(/,/g, '');
        if (parseFloat(raw) === 0) raw = '';
        $(this).val(raw);
    });

    $(document).on('blur', '.amount-input:not(.vendor-profile-locked)', function () {
        var val = $(this).val();
        if (val === '') return;
        $(this).val(formatAmount(val));
    });

    $(document).on('input', '.amount-input:not(.vendor-profile-locked)', function () {
        $(this).val($(this).val().replace(/[^\d.]/g, ''));
    });

    // ══════════════════════════════════════════════════════════════════════════
    // AJAX SAVE
    // ══════════════════════════════════════════════════════════════════════════
    function buildPayload() {
        var $form = $('#form-profil-petender');
        var payload = {};

        if (SHOW_VENDOR_FORM) {
            payload = {
                nama_syarikat:        $form.find('[name="nama_syarikat"]').val() || '',
                jenis_syarikat:       $form.find('[name="jenis_syarikat"]').val() || '',
                taraf_petender:       $form.find('[name="taraf_petender"]').val() || '',
                alamat:               $form.find('[name="alamat_syarikat"]').val() || '',
                pegawai_nama:         $form.find('[name="pegawai_nama"]').val() || '',
                pegawai_telefon:      $form.find('[name="pegawai_telefon"]').val() || '',
                pegawai_emel:         $form.find('[name="pegawai_emel"]').val() || '',
                no_ssm:               $form.find('[name="no_ssm"]').val() || '',
                no_mof:               $form.find('[name="no_mof"]').val() || '',
                tempoh_sah_mof:       $form.find('[name="tempoh_sah_mof"]').val() || '',
                bil_pekerja:          parseInt($form.find('[name="bil_pekerja_sekarang"]').val(), 10) || 0,
                bil_pekerja_teknikal: parseInt($form.find('[name="bil_pekerja_teknikal"]').val(), 10) || 0,
                modal_berbayar:       parseAmt($form.find('[name="modal_berbayar"]').val()),
                modal_dibenarkan:     parseAmt($form.find('[name="modal_dibenarkan"]').val()),
                tanggungan:           parseAmt($form.find('[name="tanggungan"]').val()),
                baki_wang_bank:       parseAmt($form.find('[name="baki_wang_bank"]').val()),
            };
            payload.aset = [];
            $form.find('[name="aset[]"]').each(function () { payload.aset.push($(this).val() || ''); });
            payload.peralatan = [];
            $form.find('[name="peralatan[]"]').each(function () { payload.peralatan.push($(this).val() || ''); });
            return payload;
        }

        payload = {
            jenis_skor_modal_berbayar:   $form.find('[name="jenis_skor_berbayar"]').val() || '',
            jenis_skor_modal_dibenarkan: $form.find('[name="jenis_skor_dibenarkan"]').val() || '',
            scoring_items: [],
        };

        var jenisBerbayar = $form.find('[name="jenis_skor_berbayar"]').val();
        if (jenisBerbayar === 'manual') {
            $('#tbl-berbayar-body tr.analisa-row').each(function () {
                var h = $(this).find('[name="berbayar_hingga[]"]').val().replace(/,/g, '');
                payload.scoring_items.push({
                    jenis_skor: 'modal_berbayar',
                    dari:   parseFloat($(this).find('[name="berbayar_dari[]"]').val().replace(/,/g, '')) || 0,
                    hingga: h !== '' ? parseFloat(h) : null,
                    skema:  $(this).find('[name="berbayar_skema[]"]').val() || '',
                });
            });
        } else if (jenisBerbayar === 'automatik') {
            $('#tbl-berbayar-auto-body tr').each(function () {
                var h = $(this).find('[name="berbayar_hingga[]"]').val();
                payload.scoring_items.push({
                    jenis_skor: 'modal_berbayar',
                    dari:   parseFloat($(this).find('[name="berbayar_dari[]"]').val()) || 0,
                    hingga: h !== '' ? parseFloat(h) : null,
                    skema:  $(this).find('[name="berbayar_skema[]"]').val() || '',
                });
            });
        }

        var jenisDibenarkan = $form.find('[name="jenis_skor_dibenarkan"]').val();
        if (jenisDibenarkan === 'manual') {
            $('#tbl-dibenarkan-body tr.analisa-row').each(function () {
                var h = $(this).find('[name="dibenarkan_hingga[]"]').val().replace(/,/g, '');
                payload.scoring_items.push({
                    jenis_skor: 'modal_dibenarkan',
                    dari:   parseFloat($(this).find('[name="dibenarkan_dari[]"]').val().replace(/,/g, '')) || 0,
                    hingga: h !== '' ? parseFloat(h) : null,
                    skema:  $(this).find('[name="dibenarkan_skema[]"]').val() || '',
                });
            });
        } else if (jenisDibenarkan === 'automatik') {
            $('#tbl-dibenarkan-auto-body tr').each(function () {
                var h = $(this).find('[name="dibenarkan_hingga[]"]').val();
                payload.scoring_items.push({
                    jenis_skor: 'modal_dibenarkan',
                    dari:   parseFloat($(this).find('[name="dibenarkan_dari[]"]').val()) || 0,
                    hingga: h !== '' ? parseFloat(h) : null,
                    skema:  $(this).find('[name="dibenarkan_skema[]"]').val() || '',
                });
            });
        }

        return payload;
    }

    function blockUI(message) {
        $('#loading-overlay').removeClass('success');
        $('#loading-text').text(message || 'Menyimpan...');
        $('#loading-overlay').addClass('active');
    }

    function unblockUI() {
        $('#loading-overlay').removeClass('active success');
    }

    function showSuccess(message, callback) {
        $('#loading-text').text(message || 'Berjaya!');
        $('#loading-overlay').addClass('success');
        setTimeout(function () {
            unblockUI();
            if (callback) callback();
        }, 1200);
    }

    $('#btn-simpan').on('click', function () {
        blockUI('Menyimpan...');
        $.ajax({
            url:         STORE_URL,
            method:      'POST',
            contentType: 'application/json',
            data:        JSON.stringify(buildPayload()),
            headers:     { 'X-CSRF-TOKEN': CSRF_TOKEN },
        })
        .done(function (res) {
            if (res && res.success) {
                $('#loading-text').text('Berjaya disimpan! Mengalih...');
                $('#loading-overlay').addClass('success');
                if (typeof vendorFormNavigate === 'function') {
                    vendorFormNavigate(KEMBALI_URL);
                } else {
                    window.location.href = KEMBALI_URL;
                }
            } else {
                unblockUI();
                alert(res.message || 'Ralat semasa menyimpan.');
            }
        })
        .fail(function () {
            unblockUI();
            alert('Ralat semasa menyimpan. Sila cuba lagi.');
        });
    });

});
</script>
@endsection
