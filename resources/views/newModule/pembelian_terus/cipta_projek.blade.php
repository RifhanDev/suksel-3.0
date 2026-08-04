@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/file-upload.css') }}" rel="stylesheet">
    <style>
        /* --- TIMELINE (3 steps) --- */
        .stepper-wrapper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
            padding: 0 40px;
        }

        .stepper-track {
            position: absolute;
            top: 15px;
            left: 40px;
            right: 40px;
            height: 4px;
            background: #e2e8f0;
            border-radius: 4px;
            z-index: 0;
            overflow: hidden;
        }

        .stepper-progress {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 0%;
            background: var(--sg-red);
            border-radius: 4px;
            transition: width 0.4s ease;
        }

        .stepper-wrapper[data-step="1"] .stepper-progress { width: 0%; }
        .stepper-wrapper[data-step="2"] .stepper-progress { width: 50%; }
        .stepper-wrapper[data-step="3"] .stepper-progress { width: 100%; }

        .stepper-item {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }

        .step-counter {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #fff;
            border: 3px solid #cbd5e1;
            color: #94a3b8;
            font-weight: 700;
            font-size: 0.85rem;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 8px;
            transition: all 0.3s ease;
            position: relative;
        }

        .step-name {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            transition: color 0.3s ease;
            text-align: center;
        }

        .stepper-item.active .step-counter {
            border-color: var(--sg-red);
            background: var(--sg-red);
            color: #fff;
            box-shadow: 0 0 0 5px var(--sg-red-soft), 0 2px 8px rgba(196, 30, 58, 0.3);
            transform: scale(1.05);
        }

        .stepper-item.active .step-name { color: var(--sg-red); }

        .stepper-item.completed .step-counter {
            border-color: #10b981;
            background: #10b981;
            color: #fff;
            box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);
        }

        .stepper-item.completed .step-counter::after {
            content: '✓';
            font-size: 0.9rem;
        }

        .stepper-item.completed .step-counter span { display: none; }
        .stepper-item.completed .step-name { color: #10b981; }

        /* --- CONDITION BUILDER (Kod Bidang) --- */
        .condition-builder { background: #f8fafc; border-radius: 12px; }

        .condition-group {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        .condition-group-header {
            background: linear-gradient(135deg, var(--sg-red) 0%, #a01830 100%);
            color: #fff;
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 12px 12px 0 0;
        }

        .condition-group-header .group-title { display: flex; align-items: center; gap: 10px; }

        .condition-group-header .group-title .group-icon {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .condition-group-header .group-title h6 { margin: 0; font-size: 0.9rem; font-weight: 700; }

        .condition-group-header .group-title small {
            display: block;
            font-size: 0.7rem;
            font-weight: 400;
            opacity: 0.85;
            margin-top: 2px;
        }

        .condition-group-body { padding: 0; }

        .condition-row {
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
            position: relative;
            display: flex;
            align-items: flex-end;
            gap: 16px;
        }

        .condition-row:last-child { border-bottom: none; }
        .condition-row:hover { background: #fafbfc; }

        .condition-row .row-number {
            width: 28px;
            height: 28px;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            flex-shrink: 0;
        }

        .condition-row .row-fields {
            flex: 1;
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .condition-row .row-fields .form-label { height: 20px; margin-bottom: 6px; }

        .condition-row .row-actions { flex-shrink: 0; align-self: flex-end; padding-bottom: 1px; }

        .condition-row .btn-remove {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: none;
            background: var(--sg-red);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .condition-row .btn-remove:hover { background: #a01830; color: #fff; }

        .condition-connector {
            display: flex;
            align-items: center;
            padding: 0 20px;
            background: #f8fafc;
        }

        .condition-connector::before,
        .condition-connector::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .connector-pill {
            background: var(--sg-yellow);
            color: #1a1a1a;
            font-weight: 700;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: 90px;
            padding: 6px 12px;
            padding-right: 26px;
            border-radius: 16px;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            border: none;
            margin: 8px 12px;
            text-align: center;
            transition: all 0.2s ease;
        }

        .connector-pill:focus { outline: none; box-shadow: 0 0 0 3px var(--sg-red-soft); }

        .connector-pill-wrapper { position: relative; display: inline-flex; align-items: center; }

        .connector-pill-wrapper::after {
            content: '';
            position: absolute;
            right: 22px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 5px solid #1a1a1a;
            pointer-events: none;
        }

        .condition-row .field-logic { width: 140px; flex-shrink: 0; }
        .condition-row .field-main { flex: 1; min-width: 200px; }
        .condition-row .field-grade { width: 120px; flex-shrink: 0; }

        .condition-info {
            font-size: 0.75rem;
            color: #94a3b8;
            padding: 12px 20px;
            background: #fafbfc;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .condition-info svg { flex-shrink: 0; }

        .cidb-fields { flex-direction: column; gap: 12px; }
        .cidb-row-top { width: 100%; }
        .cidb-row-top .field-grade-full { width: 100%; }
        .cidb-row-bottom { display: flex; gap: 12px; width: 100%; align-items: flex-start; }
        .cidb-row-bottom .form-label { height: 20px; margin-bottom: 6px; }
        .cidb-row-bottom .field-logic { width: 160px; flex-shrink: 0; }
        .cidb-row-bottom .field-main { flex: 1; }

        /* --- MOBILE --- */
        @media (max-width: 991px) {
            .stepper-wrapper { padding: 0 20px; }
            .stepper-track { left: 40px; right: 40px; }
            .step-name { font-size: 0.65rem; }
            .step-counter { width: 30px; height: 30px; font-size: 0.75rem; }
            .condition-group-header { padding: 12px 16px; }
            .condition-row { padding: 14px 16px; flex-wrap: wrap; gap: 12px; }
            .condition-row .row-fields { flex-direction: column; width: 100%; gap: 10px; }
            .condition-row .field-logic,
            .condition-row .field-grade,
            .condition-row .field-main { width: 100%; flex: none; }
            .condition-row .row-actions { position: absolute; top: 10px; right: 10px; }
            .condition-row .btn-remove { width: 28px; height: 28px; }
            .condition-connector { padding: 0 16px; }
            .cidb-row-bottom { flex-direction: column; gap: 10px; }
            .cidb-row-bottom .field-logic { width: 100%; }
        }

        @media (max-width: 576px) {
            .stepper-wrapper { padding: 0 10px; }
            .stepper-track { left: 30px; right: 30px; }
            .step-name { font-size: 0.6rem; }
            .condition-group-header .group-title small { display: none; }
        }
    </style>
@endsection

@section('content')
    @php
        $p = $project ?? null;

        // Kod Bidang prefill (initial row only) — falls back to old() on validation error
        $mof0      = old('mof.0', optional($p)->mof[0] ?? []);
        $mof0Logic = $mof0['logic_mid'] ?? 'OR';
        $mof0Code  = $mof0['code'] ?? [];

        $cidb0      = old('cidb.0', optional($p)->cidb[0] ?? []);
        $cidb0Logic = $cidb0['logic_mid'] ?? 'AND';
        $cidb0Grade = $cidb0['grade'] ?? [];
        $cidb0Spec  = $cidb0['spec'] ?? [];
    @endphp
    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">{{ $p ? 'Kemaskini' : 'Cipta' }} Projek Untuk Pembelian Terus</h3>
            <p class="text-muted small m-0">Sila lengkapkan maklumat di bawah mengikut langkah.</p>
        </div>
    </div>

    <!-- TIMELINE -->
    <div class="stepper-wrapper" data-step="1" id="stepper-wrapper">
        <div class="stepper-track">
            <div class="stepper-progress" id="stepper-progress"></div>
        </div>

        <div class="stepper-item active" id="step1-indicator">
            <div class="step-counter"><span>1</span></div>
            <div class="step-name">Cipta Projek</div>
        </div>
        <div class="stepper-item" id="step2-indicator">
            <div class="step-counter"><span>2</span></div>
            <div class="step-name">Maklumat Spesifikasi</div>
        </div>
        <div class="stepper-item" id="step3-indicator">
            <div class="step-counter"><span>3</span></div>
            <div class="step-name">Kod Bidang</div>
        </div>
    </div>

    <form id="createProjekForm"
        action="{{ $p ? route('pembelianTerus.update', $p->id) : route('pembelianTerus.store') }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        @if ($p)
            @method('PUT')
        @endif
        <input type="hidden" name="action" id="form-action" value="draft">

        <div class="modern-card">

            <!-- ======================= STEP 1: CIPTA PROJEK ======================= -->
            <div id="step1-content">
                <div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                    <span class="fw-bold text-dark text-uppercase small">Cipta Projek Untuk Pembelian Terus</span>
                </div>

                <div class="p-4">

                    <!-- Tajuk Perolehan -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Tajuk Perolehan <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="name" rows="2" placeholder="Masukkan tajuk perolehan..."
                                required>{{ old('name', optional($p)->name) }}</textarea>
                        </div>
                    </div>

                    <!-- Disediakan Untuk PTJ -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Disediakan Untuk PTJ <span class="text-danger">*</span></label>
                            <select class="form-select" name="ptj_id" id="ptj-select" required>
                                <option value="" selected disabled>Pilih...</option>
                                @foreach (App\OrganizationUnit::all() as $org)
                                    <option value="{{ $org->id }}" {{ old('ptj_id', optional($p)->ptj_id) == $org->id ? 'selected' : '' }}>
                                        {{ strtoupper($org->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- No. Rujukan Fail / Harga Indikatif -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">No. Rujukan Fail <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ref_number" value="{{ old('ref_number', optional($p)->ref_number) }}"
                                placeholder="Cth: SH/DF/TRG" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga Indikatif Jabatan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">RM</span>
                                @php $hargaIndikatif = old('harga_indikatif', optional($p)->harga_indikatif); @endphp
                                <input type="text" class="form-control text-end amount-input" name="harga_indikatif"
                                    value="{{ $hargaIndikatif !== null && $hargaIndikatif !== '' ? number_format($hargaIndikatif, 2) : '' }}"
                                    placeholder="0.00" inputmode="decimal" autocomplete="off" required>
                            </div>
                        </div>
                    </div>

                    <!-- Tarikh Buka / Tarikh Tutup -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tarikh Buka</label>
                            <input type="text" class="form-control form-control-lg datepicker" name="tarikh_buka"
                                value="{{ old('tarikh_buka', optional($p)->tarikh_buka) }}" placeholder="dd/mm/yyyy">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tarikh Tutup</label>
                            <input type="text" class="form-control form-control-lg datepicker" name="tarikh_tutup"
                                value="{{ old('tarikh_tutup', optional($p)->tarikh_tutup) }}" placeholder="dd/mm/yyyy">
                        </div>
                    </div>

                    <hr class="border-light my-2">

                    <div class="row g-4">
                        <!-- Left column -->
                        <div class="col-lg-6">
                            <!-- Zon / Lokasi -->
                            <div class="mb-3">
                                <label class="form-label">Zon / Lokasi</label>
                                @php $zonLokasi = old('zon_lokasi', optional($p)->zon_lokasi ?? '0'); @endphp
                                <div class="segmented-control">
                                    <input type="radio" name="zon_lokasi" id="z_y" value="1"
                                        {{ $zonLokasi == '1' ? 'checked' : '' }}>
                                    <label for="z_y">Ya</label>
                                    <input type="radio" name="zon_lokasi" id="z_t" value="0"
                                        {{ $zonLokasi == '0' ? 'checked' : '' }}>
                                    <label for="z_t">Tidak</label>
                                </div>
                            </div>
                            <!-- Lokaliti Liputan -->
                            <div class="mb-3 {{ $zonLokasi == '0' ? 'd-none' : '' }}" id="lokaliti-group">
                                <label class="form-label">Lokaliti Liputan</label>
                                <select class="form-select" name="lokaliti_id">
                                    <option value="" selected disabled>Pilih...</option>
                                    @foreach (\App\Models\Ref\RefLokaliti::where('active', true)->get() as $lokaliti)
                                        <option value="{{ $lokaliti->id }}"
                                            {{ old('lokaliti_id', optional($p)->lokaliti_id) == $lokaliti->id ? 'selected' : '' }}>
                                            {{ $lokaliti->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Kategori Perolehan -->
                            <div class="mb-0">
                                @php $kategoriSelected = old('kategori_perolehan', optional($p)->kategori_perolehan); @endphp
                                <label class="form-label">Kategori Perolehan <span class="text-danger">*</span></label>
                                <select class="form-select" name="kategori_perolehan" required>
                                    <option value="" selected disabled>Pilih...</option>
                                    @foreach (($kategoriPerolehan ?? \App\Models\Ref\RefKategoriJenisPerolehan::where('active', true)->get()) as $kategori)
                                        <option value="{{ $kategori->id }}" {{ (string) $kategoriSelected === (string) $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Right column -->
                        <div class="col-lg-6">
                            <!-- Sumber Peruntukan -->
                            <div class="mb-3">
                                <label class="form-label">Sumber Peruntukan <span class="text-danger">*</span></label>
                                @php $sumberPeruntukan = old('sumber_peruntukan', optional($p)->sumber_peruntukan ?? 'pembangunan'); @endphp
                                <div class="segmented-control">
                                    <input type="radio" name="sumber_peruntukan" id="sp_pem" value="pembangunan"
                                        {{ $sumberPeruntukan == 'pembangunan' ? 'checked' : '' }}>
                                    <label for="sp_pem">Pembangunan</label>
                                    <input type="radio" name="sumber_peruntukan" id="sp_meng" value="mengurus"
                                        {{ $sumberPeruntukan == 'mengurus' ? 'checked' : '' }}>
                                    <label for="sp_meng">Mengurus</label>
                                    <input type="radio" name="sumber_peruntukan" id="sp_lain" value="lain"
                                        {{ $sumberPeruntukan == 'lain' ? 'checked' : '' }}>
                                    <label for="sp_lain">Lain-lain</label>
                                </div>
                                <div class="mt-2 {{ $sumberPeruntukan == 'lain' ? '' : 'd-none' }}" id="sumber-lain-group">
                                    <input type="text" class="form-control" name="sumber_lain_text"
                                        value="{{ old('sumber_lain_text', optional($p)->sumber_lain_text) }}"
                                        placeholder="Nyatakan sumber peruntukan...">
                                </div>
                            </div>
                            <!-- Terbuka Kepada -->
                            <div class="mb-0">
                                <label class="form-label">Terbuka Kepada <span class="text-danger">*</span></label>
                                @php $terbukaKepada = old('terbuka_kepada', optional($p)->terbuka_kepada ?? 'semua'); @endphp
                                <div class="segmented-control">
                                    <input type="radio" name="terbuka_kepada" id="tk_b" value="bumiputera"
                                        {{ $terbukaKepada == 'bumiputera' ? 'checked' : '' }}>
                                    <label for="tk_b">Bumiputera</label>
                                    <input type="radio" name="terbuka_kepada" id="tk_s" value="semua"
                                        {{ $terbukaKepada == 'semua' ? 'checked' : '' }}>
                                    <label for="tk_s">Semua</label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- END STEP 1 -->

            <!-- ======================= STEP 2: MAKLUMAT SPESIFIKASI ======================= -->
            <div id="step2-content" class="d-none">
                <div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                    <span class="fw-bold text-dark text-uppercase small">Maklumat Spesifikasi</span>
                </div>

                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0">Senarai Item</h6>
                        <button type="button" class="btn-form btn-form-primary" id="btn-add-spec-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Tambah Item
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0" id="spec-items-table">
                            <thead class="bg-light">
                                <tr>
                                    <th width="60" class="text-center">Bil.</th>
                                    <th>Nama Item</th>
                                    <th width="160">Kuantiti</th>
                                    <th width="120">SST</th>
                                    <th width="80" class="text-center">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody id="spec-items-body">
                                @php
                                    $specItems = old('items', isset($items) ? $items->toArray() : (optional($p)->items ?? []));
                                    if (empty($specItems)) {
                                        $specItems = [['nama_item' => '', 'kuantiti' => '', 'sst' => true]];
                                    }
                                @endphp
                                @foreach ($specItems as $idx => $specItem)
                                    @php $specItem = (array) $specItem; @endphp
                                    <tr class="spec-item-row">
                                        <td class="text-center bil-no">{{ $idx + 1 }}</td>
                                        <td>
                                            <input type="text" class="form-control" name="items[{{ $idx }}][nama_item]"
                                                value="{{ $specItem['nama_item'] ?? '' }}" placeholder="Nama item..." required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" name="items[{{ $idx }}][kuantiti]"
                                                value="{{ $specItem['kuantiti'] ?? '' }}" min="0" step="1" required>
                                        </td>
                                        <td>
                                            <select class="form-select" name="items[{{ $idx }}][sst]">
                                                <option value="1" {{ !empty($specItem['sst']) ? 'selected' : '' }}>Ya</option>
                                                <option value="0" {{ empty($specItem['sst']) && isset($specItem['sst']) ? 'selected' : '' }}>Tidak</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-spec" title="Buang">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- END STEP 2 -->

            <!-- ======================= STEP 3: KOD BIDANG ======================= -->
            <div id="step3-content" class="d-none">
                <div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <span class="fw-bold text-dark text-uppercase small">Kod Bidang</span>
                </div>

                <div class="p-4">

                    <div class="alert-selangor mb-4">
                        <div class="alert-selangor-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                        </div>
                        <div class="small mt-1">
                            Ruangan Kod Bidang ini <span class="fw-bold text-danger text-uppercase">WAJIB</span> diisi oleh Pemilik Projek.
                        </div>
                    </div>

                    <div class="condition-builder">
                        <!-- MOF SECTION -->
                        <div class="condition-group">
                            <div class="condition-group-header">
                                <div class="group-title">
                                    <div class="group-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h6>Kod Bidang MOF</h6>
                                        <small>Kementerian Kewangan Malaysia</small>
                                    </div>
                                </div>
                            </div>
                            <div class="condition-group-body">
                                <div id="mof-wrapper">
                                    <div class="condition-row mof-row" data-index="0">
                                        <div class="row-number">1</div>
                                        <div class="row-fields">
                                            <div class="field-logic">
                                                <label class="form-label">Hubungan</label>
                                                <div class="segmented-control">
                                                    <input type="radio" name="mof[0][logic_mid]" id="mof0_or" value="OR"
                                                        {{ $mof0Logic == 'OR' ? 'checked' : '' }}>
                                                    <label for="mof0_or">ATAU</label>
                                                    <input type="radio" name="mof[0][logic_mid]" id="mof0_and" value="AND"
                                                        {{ $mof0Logic == 'AND' ? 'checked' : '' }}>
                                                    <label for="mof0_and">DAN</label>
                                                </div>
                                            </div>
                                            <div class="field-main">
                                                <label class="form-label">Kod Bidang</label>
                                                <select class="selectize" name="mof[0][code][]" multiple>
                                                    @foreach (App\Code::where('type', 'mof')->orderBy('code')->get() as $code)
                                                        <option value="{{ $code->id }}"
                                                            {{ in_array($code->id, (array) $mof0Code) ? 'selected' : '' }}>
                                                            {{ $code->label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="condition-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                    </svg>
                                    Syarikat mesti mempunyai pendaftaran MOF yang sah dengan kod bidang yang dipilih
                                </div>
                            </div>
                            <button type="button" class="btn-add-condition" id="btn-add-mof">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Tambah Kod Bidang MOF
                            </button>
                        </div>

                        <!-- SECTION CONNECTOR -->
                        <div class="position-relative w-100 py-5 d-flex justify-content-center align-items-center">
                            <div class="position-absolute h-100 border-start border-2 border-secondary opacity-25"
                                style="left: 50%; transform: translateX(-50%); z-index: 0;"></div>
                            <div class="col-12 col-md-6 position-relative z-1">
                                <div class="bg-white rounded-3 shadow-sm border" style="border-color: #e2e8f0;">
                                    <div class="d-flex align-items-center justify-content-between p-3 gap-3">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle bg-light border text-secondary p-1"
                                                style="width: 32px; height: 32px; flex-shrink: 0;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                                </svg>
                                            </div>
                                            <span class="fw-bold text-uppercase text-muted small ms-2 text-nowrap">Hubungan</span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <select name="section_logic"
                                                class="form-select form-select-sm fw-bold text-dark border-secondary bg-light"
                                                style="cursor: pointer;">
                                                <option value="AND" {{ old('section_logic', 'AND') == 'AND' ? 'selected' : '' }}>DAN</option>
                                                <option value="OR" {{ old('section_logic') == 'OR' ? 'selected' : '' }}>ATAU</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="bg-light rounded-bottom-3 px-3 py-2 border-top d-flex align-items-start gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="mt-1 flex-shrink-0">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="16" x2="12" y2="12"></line>
                                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                        </svg>
                                        <p class="m-0 text-muted lh-sm" style="font-size: 0.75rem;">
                                            Pilih <strong>'DAN'</strong> jika syarikat wajib mematuhi kedua-dua kelayakan
                                            (MOF &amp; CIDB) secara serentak.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CIDB SECTION -->
                        <div class="condition-group">
                            <div class="condition-group-header">
                                <div class="group-title">
                                    <div class="group-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="2" y="6" width="20" height="12" rx="2"></rect>
                                            <path d="M12 12h.01"></path>
                                            <path d="M17 12h.01"></path>
                                            <path d="M7 12h.01"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h6>Kod Bidang CIDB</h6>
                                        <small>Lembaga Pembangunan Industri Pembinaan</small>
                                    </div>
                                </div>
                            </div>
                            <div class="condition-group-body">
                                <div id="cidb-wrapper">
                                    <div class="condition-row cidb-row" data-index="0">
                                        <div class="row-number">1</div>
                                        <div class="row-fields cidb-fields">
                                            <div class="cidb-row-top">
                                                <div class="field-grade-full">
                                                    <label class="form-label">Gred CIDB</label>
                                                    <select class="selectize" name="cidb[0][grade]" multiple>
                                                        <option value="" selected disabled>Pilih Gred...</option>
                                                        @foreach (App\Code::where('type', 'cidb-g')->orderBy('code')->get() as $code)
                                                            <option value="{{ $code->id }}"
                                                                {{ in_array($code->id, (array) $cidb0Grade) ? 'selected' : '' }}>
                                                                {{ $code->label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="cidb-row-bottom">
                                                <div class="field-logic">
                                                    <label class="form-label">Hubungan</label>
                                                    <div class="segmented-control">
                                                        <input type="radio" name="cidb[0][logic_mid]" id="cidb0_and" value="AND"
                                                            {{ $cidb0Logic == 'AND' ? 'checked' : '' }}>
                                                        <label for="cidb0_and">DAN</label>
                                                        <input type="radio" name="cidb[0][logic_mid]" id="cidb0_or" value="OR"
                                                            {{ $cidb0Logic == 'OR' ? 'checked' : '' }}>
                                                        <label for="cidb0_or">ATAU</label>
                                                    </div>
                                                </div>
                                                <div class="field-main">
                                                    <label class="form-label">Pengkhususan</label>
                                                    <select class="selectize" name="cidb[0][spec][]" multiple>
                                                        @foreach (App\Code::where('type', 'cidb-c')->orderBy('code')->get() as $code)
                                                            <option value="{{ $code->id }}"
                                                                {{ in_array($code->id, (array) $cidb0Spec) ? 'selected' : '' }}>
                                                                {{ $code->label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="condition-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                    </svg>
                                    Untuk kerja pembinaan, syarikat mesti berdaftar dengan CIDB mengikut gred dan pengkhususan
                                </div>
                            </div>
                            <button type="button" class="btn-add-condition" id="btn-add-cidb">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Tambah Pengkhususan
                            </button>
                        </div>
                    </div>

                </div>
            </div>
            <!-- END STEP 3 -->

            <!-- FOOTER -->
            <div class="bg-light p-4 border-top d-flex justify-content-between align-items-center">
                <button type="button" class="btn-form btn-form-secondary d-none" id="btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Sebelumnya
                </button>

                <div class="ms-auto d-flex gap-2">
                    <button type="button" class="btn-form btn-form-success" id="btn-save">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Simpan
                    </button>

                    <button type="button" class="btn-form btn-form-primary" id="btn-next">
                        Seterusnya
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>

                    <button type="button" class="btn-form btn-form-primary d-none" id="btn-submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 2L11 13"></path>
                            <path d="M22 2l-7 20-4-9-9-4 20-7z"></path>
                        </svg>
                        Terbitkan
                    </button>
                </div>
            </div>

        </div>
    </form>
@endsection

@section('scripts')
    <script>
        // --- DROPDOWN OPTIONS FOR DYNAMIC ROWS (Kod Bidang) ---
        var mofOptions =
            `@foreach (App\Code::where('type', 'mof')->orderBy('code')->get() as $code)<option value="{{ $code->id }}">{{ $code->label }}</option>@endforeach`;
        var cidbSpecOptions =
            `@foreach (App\Code::where('type', 'cidb-c')->orderBy('code')->get() as $code)<option value="{{ $code->id }}">{{ $code->label }}</option>@endforeach`;

        $(document).ready(function() {

            // --- SPEC ITEMS ---
            let specIndex = $('#spec-items-body .spec-item-row').length;

            function renumberSpecRows() {
                $('#spec-items-body .spec-item-row').each(function(i) {
                    $(this).find('.bil-no').text(i + 1);
                });
            }

            $('#btn-add-spec-item').on('click', function() {
                const idx = specIndex++;
                const row = `
                    <tr class="spec-item-row">
                        <td class="text-center bil-no"></td>
                        <td><input type="text" class="form-control" name="items[${idx}][nama_item]" placeholder="Nama item..." required></td>
                        <td><input type="number" class="form-control" name="items[${idx}][kuantiti]" min="0" step="1" required></td>
                        <td>
                            <select class="form-select" name="items[${idx}][sst]">
                                <option value="1" selected>Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-spec" title="Buang">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                        </td>
                    </tr>`;
                $('#spec-items-body').append(row);
                renumberSpecRows();
            });

            $(document).on('click', '.btn-remove-spec', function() {
                if ($('#spec-items-body .spec-item-row').length <= 1) {
                    alert('Sekurang-kurangnya satu item diperlukan.');
                    return;
                }
                $(this).closest('tr').remove();
                renumberSpecRows();
            });

            // --- SUMBER PERUNTUKAN LAIN ---
            $('input[name="sumber_peruntukan"]').on('change', function() {
                if ($(this).val() === 'lain') {
                    $('#sumber-lain-group').removeClass('d-none');
                } else {
                    $('#sumber-lain-group').addClass('d-none').find('input').val('');
                }
            });

            // --- DATEPICKER ---
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });

            // --- SELECTIZE (Kod Bidang multi-selects) ---
            $('#step3-content select.selectize').each(function() {
                if (!this.selectize) $(this).selectize();
            });

            // --- ZON/LOKASI TOGGLE ---
            $('input[name="zon_lokasi"]').change(function() {
                if ($(this).val() === '1') {
                    $('#lokaliti-group').removeClass('d-none');
                } else {
                    $('#lokaliti-group').addClass('d-none').find('select').val('');
                }
            });

            // --- SAVE DRAFT / PUBLISH ---
            $('#btn-save').on('click', function() {
                $('#form-action').val('draft');
                $('#createProjekForm').submit();
            });

            $('#btn-submit').on('click', function(e) {
                e.preventDefault();
                if (!validateCurrentStep()) return;
                $('#form-action').val('publish');
                $('#createProjekForm').submit();
            });

            // --- WIZARD NAVIGATION (3 steps) ---
            let currentStep = 1;
            const TOTAL_STEPS = 3;

            function updateWizardUI() {
                $('#stepper-wrapper').attr('data-step', currentStep);

                // Toggle step content panels
                for (let i = 1; i <= TOTAL_STEPS; i++) {
                    $('#step' + i + '-content').toggleClass('d-none', i !== currentStep);
                }

                // Stepper indicators
                $('#step1-indicator, #step2-indicator, #step3-indicator').removeClass('active completed');
                for (let i = 1; i < currentStep; i++) {
                    $('#step' + i + '-indicator').addClass('completed');
                }
                $('#step' + currentStep + '-indicator').addClass('active');

                // Footer buttons
                $('#btn-back').toggleClass('d-none', currentStep === 1);
                $('#btn-next').toggleClass('d-none', currentStep === TOTAL_STEPS);
                $('#btn-submit').toggleClass('d-none', currentStep !== TOTAL_STEPS);
            }

            function scrollToStepper() {
                $('html, body').animate({
                    scrollTop: $('#stepper-wrapper').offset().top - 20
                }, 400);
            }

            function validateCurrentStep() {
                var isValid = true;
                $('#step' + currentStep + '-content [required]').each(function() {
                    if (!this.checkValidity()) {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                if (!isValid) {
                    $('#step' + currentStep + '-content [required]:invalid').first().focus();
                }
                return isValid;
            }

            // expose for Terbitkan click
            window.validateCurrentStep = validateCurrentStep;

            $('#btn-next').click(function() {
                if (!validateCurrentStep()) return;
                if (currentStep < TOTAL_STEPS) {
                    currentStep++;
                    updateWizardUI();
                    scrollToStepper();
                }
            });

            $('#btn-back').click(function() {
                if (currentStep > 1) {
                    currentStep--;
                    updateWizardUI();
                    scrollToStepper();
                }
            });

            // --- DYNAMIC ROWS: MOF ---
            let mofCount = 1;

            function updateMofRowNumbers() {
                $('#mof-wrapper .condition-row').each(function(i) {
                    $(this).find('.row-number').text(i + 1);
                });
            }

            $('#btn-add-mof').click(function() {
                let index = mofCount++;
                let rowNum = $('#mof-wrapper .condition-row').length + 1;

                let connectorHtml = `
                    <div class="condition-connector" id="mof-logic-${index}">
                        <div class="connector-pill-wrapper">
                            <select class="connector-pill" name="mof_logic_${index}">
                                <option value="OR">ATAU</option>
                                <option value="AND">DAN</option>
                            </select>
                        </div>
                    </div>`;

                let rowHtml = `
                    <div class="condition-row mof-row" id="mof-row-${index}" data-index="${index}">
                        <div class="row-number">${rowNum}</div>
                        <div class="row-fields">
                            <div class="field-logic">
                                <label class="form-label">Hubungan</label>
                                <div class="segmented-control">
                                    <input type="radio" name="mof[${index}][logic_mid]" id="mof${index}_or" value="OR" checked>
                                    <label for="mof${index}_or">ATAU</label>
                                    <input type="radio" name="mof[${index}][logic_mid]" id="mof${index}_and" value="AND">
                                    <label for="mof${index}_and">DAN</label>
                                </div>
                            </div>
                            <div class="field-main">
                                <label class="form-label">Kod Bidang</label>
                                <select class="selectize" name="mof[${index}][code][]" multiple>
                                    ${mofOptions}
                                </select>
                            </div>
                        </div>
                        <div class="row-actions">
                            <button type="button" class="btn-remove" onclick="removeMofRow(${index})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                        </div>
                    </div>`;

                $('#mof-wrapper').append(connectorHtml + rowHtml);
                $('#mof-row-' + index + ' select.selectize').each(function() {
                    if (!this.selectize) $(this).selectize();
                });
            });

            window.removeMofRow = function(index) {
                $('#mof-row-' + index).remove();
                $('#mof-logic-' + index).remove();
                updateMofRowNumbers();
            };

            // --- DYNAMIC ROWS: CIDB ---
            let cidbCount = 1;

            function updateCidbRowNumbers() {
                $('#cidb-wrapper .condition-row').each(function(i) {
                    $(this).find('.row-number').text(i + 1);
                });
            }

            $('#btn-add-cidb').click(function() {
                let index = cidbCount++;
                let rowNum = $('#cidb-wrapper .condition-row').length + 1;

                let connectorHtml = `
                    <div class="condition-connector" id="cidb-logic-${index}">
                        <div class="connector-pill-wrapper">
                            <select class="connector-pill" name="cidb_logic_${index}">
                                <option value="OR">ATAU</option>
                                <option value="AND">DAN</option>
                            </select>
                        </div>
                    </div>`;

                let rowHtml = `
                    <div class="condition-row cidb-row" id="cidb-row-${index}" data-index="${index}">
                        <div class="row-number">${rowNum}</div>
                        <div class="row-fields cidb-fields">
                            <div class="cidb-row-bottom">
                                <div class="field-logic">
                                    <label class="form-label">Hubungan</label>
                                    <div class="segmented-control">
                                        <input type="radio" name="cidb[${index}][logic_mid]" id="cidb${index}_and" value="AND" checked>
                                        <label for="cidb${index}_and">DAN</label>
                                        <input type="radio" name="cidb[${index}][logic_mid]" id="cidb${index}_or" value="OR">
                                        <label for="cidb${index}_or">ATAU</label>
                                    </div>
                                </div>
                                <div class="field-main">
                                    <label class="form-label">Pengkhususan</label>
                                    <select class="selectize" name="cidb[${index}][spec][]" multiple>
                                        ${cidbSpecOptions}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row-actions">
                            <button type="button" class="btn-remove" onclick="removeCidbRow(${index})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                        </div>
                    </div>`;

                $('#cidb-wrapper').append(connectorHtml + rowHtml);
                $('#cidb-row-' + index + ' select.selectize').each(function() {
                    if (!this.selectize) $(this).selectize();
                });
            });

            window.removeCidbRow = function(index) {
                $('#cidb-row-' + index).remove();
                $('#cidb-logic-' + index).remove();
                updateCidbRowNumbers();
            };

        });

        // --- AMOUNT INPUT: comma formatting ---
        function parseAmount(val) {
            return parseFloat(String(val).replace(/,/g, '')) || 0;
        }

        function formatAmount(n) {
            return n.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        $(document).on('focus', '.amount-input', function () {
            var raw = $(this).val().replace(/,/g, '');
            $(this).val(raw === '0.00' || raw === '0' ? '' : raw);
        });

        $(document).on('blur', '.amount-input', function () {
            var val = $(this).val().trim();
            if (val === '') return;
            $(this).val(formatAmount(parseAmount(val)));
        });

        $(document).on('input', '.amount-input', function () {
            var val = $(this).val().replace(/[^\d.]/g, '');
            var parts = val.split('.');
            if (parts.length > 2) val = parts[0] + '.' + parts.slice(1).join('');
            $(this).val(val);
        });

        $('#createProjekForm').on('submit', function () {
            $(this).find('.amount-input').each(function () {
                $(this).val($(this).val().replace(/,/g, ''));
            });
        });
    </script>
@endsection
