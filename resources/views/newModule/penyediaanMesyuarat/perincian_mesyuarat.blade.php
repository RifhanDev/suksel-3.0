@extends('layouts.v3.master')


@section('content')
    <style>
        .stats-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            position: relative;
        }
        .stats-card::before {
            content: ''; position: absolute; top: -25px; right: -25px; width: 80px; height: 80px;
            background: var(--sg-red); opacity: 0.03; border-radius: 20px; transform: rotate(45deg); pointer-events: none;
        }
        .stats-card-header {
            padding: 20px 16px;
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; justify-content: space-between;
        }
        .stats-card-title {
            margin: 0; font-size: 1.1rem; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 10px;
        }
       .table-modern thead th, .table-modern tfoot th {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.5px;
            padding: 14px 20px;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
            vertical-align: middle;
        }

        .table-modern tbody td {
            padding: 16px 20px;
            vertical-align: middle;
            color: #334155;
            font-size: 0.9rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-modern tbody tr:hover {
            background-color: #fff9f9;
        }
        .btn-primary {
            background: #405189;
        }
        .card-title-grey {
            background: #D9D9D9;
            padding: 5px 15px;
        }
        hr {
            border:1px solid #E9EBEC;
        }
        .btn-sm-cust {
            font-size: 10px !important;
            padding: 3px 3px 3px 3px;
            height: max-content;
        }
        .heartbeat {
            display: inline-block;
            animation: heartbeat 1.2s infinite;
        }

        @keyframes heartbeat {
            0% {
                transform: scale(1);
            }
            25% {
                transform: scale(1.05);
            }
            40% {
                transform: scale(1);
            }
            60% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }
        .btn-circle {
            width: 25px;
            height: 25px;
            padding: 0;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes btnPop {
            0% {
                transform: scale(1);
            }
            40% {
                transform: scale(1.25);
            }
            100% {
                transform: scale(1.1);
            }
        }

        .btn-circle:hover {
            animation: btnPop 0.25s ease forwards;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        
        .nested-tabs {
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
        }

        .nested-tab-btn {
            border: none;
            background: transparent;
            padding: 6px 20px;
            font-weight: 600;
            cursor: pointer;
            margin-right: 3px;
        }

        /* Active nested tab */
        .nested-tab-btn.active {
            background: #c0392b;
            color: #fff;
            border-radius: 4px 4px 0 0;
        }
    </style>

    @php
        $firstUiTab = ($uiTabs[0]['ui'] ?? 'pembuka');
        $visibleUiTabs = collect($uiTabs ?? [])->pluck('ui')->all();
    @endphp

    <div class="card border shadow-sm mb-2 rounded-3">
        <div class="card-body p-3">
            <div class="row g-2 align-items-end">
                <div class="col-12">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">
                        {{ ($tender->type ?? '') === 'quotation' ? 'Nama Sebut Harga' : 'Nama Tender' }}
                    </label>
                    <h6 class="text-primary mb-2">{{ $tender->name ?? '-' }}</h6>
                </div>
                <div class="col-4 col-lg-4">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Tender</label>
                    <h6 class="text-primary">{{ $tender->no_tender ?: $tender->ref_number ?: '-' }}</h6>
                </div>
                <div class="col-4 col-lg-4">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">PTJ</label>
                    <h6 class="text-primary">{{ optional($tender->tenderer)->name ?? '-' }}</h6>
                </div>
                <div class="col-4 col-lg-4">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Status</label>
                    <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 fw-bold text-uppercase heartbeat" style="font-size: 0.8rem;">
                        {{ $tender->status ?? '-' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="nested-tabs">
        @foreach($uiTabs ?? [] as $tab)
        <button type="button" class="nested-tab-btn {{ $loop->first ? 'active' : '' }}" data-tab="{{ $tab['ui'] }}" data-jenis="{{ $tab['jenis'] }}">
            {{ $tab['label'] }}
        </button>
        @endforeach
    </div>

    <div class="nested-content">
        @if(in_array('pembuka', $visibleUiTabs))
        <div class="tab-content {{ $firstUiTab !== 'pembuka' ? 'd-none' : '' }}" data-tab="pembuka" data-jenis="open">
            <div class="content-card mb-4 p-0">
                <div class="content-card-header p-4 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="content-card-icon" style="width: 38px; height: 38px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                        </div>
                        <div>
                            <h3 class="content-card-title mb-0" style="font-size: 1rem;">Perincian Mesuarat</h3>
                            <p class="text-muted mb-0" style="font-size: 0.78rem;">Diisi oleh Petender</p>
                        </div>
                    </div>
                </div>

                <div class="content-card-body p-4">

                    <!-- Table toolbar -->
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" id="btn-tambah-row-mesyuarat-pembuka"
                            class="btn btn-sm btn-success d-inline-flex align-items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Tambah
                        </button>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table id="tbl-mesyuarat-pembuka" class="table table-modern align-middle mb-0 w-100">
                            <thead>
                                <tr>
                                    <th class="text-center py-3" style="width:50px;">Bil</th>
                                    <th class="text-center py-3">Tarikh Mesyuarat</th>
                                    <th class="text-center py-3">Masa</th>
                                    <th class="text-center py-3">Tempat</th>
                                    <th class="text-center py-3" style="width:60px;">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody id="tbl-mesyuarat-pembuka-body">
                                <!-- initial row rendered by JS below -->
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="content-card mb-4 p-0">
                <div class="content-card-header p-4 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="content-card-icon" style="width: 38px; height: 38px;">
                            <svg viewBox="0 0 25 25" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.6 5c.6 0 1.2.2 1.6.7.4.4.7 1 .7 1.6 0 1.3-1 2.3-2.3 2.3s-2.3-1-2.3-2.3S11.3 5 12.6 5z"/>
                                <path d="M10.3 12.9h4.7c1.6.1 2.9 1.4 2.9 3s-1.3 2.9-2.9 3h-4.7c-1.6-.1-2.9-1.4-2.9-3s1.3-2.9 2.9-3z"/>
                                <path d="M19 7.3c.5 0 .9.2 1.2.5.3.3.5.7.5 1.2 0 1-0.8 1.8-1.7 1.8-1 0-1.8-.8-1.8-1.8 0-1 .8-1.7 1.8-1.7z"/>
                                <path d="M6.1 7.3c1 0 1.8.8 1.8 1.7 0 1-.8 1.8-1.8 1.8S4.3 10 4.3 9s.8-1.7 1.8-1.7z"/>
                                <path d="M19.4 12.8h1.3c1.7 0 3 1.4 3 3.1s-1.3 3-3 3h-1.3M5.6 12.8H4.3c-1.7 0-3 1.4-3 3.1s1.3 3 3 3h1.3"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="content-card-title mb-0" style="font-size: 1rem;">Senarai Ahli Jawatankuasa Pembuka</h3>
                            <p class="text-muted mb-0" style="font-size: 0.78rem;">Diisi oleh Petender</p>
                        </div>
                    </div>
                </div>

                <div class="content-card-body p-4">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <label for="status_dummy" class="form-label small fw-bold text-secondary text-uppercase mb-1">Status</label>
                            <select id="status_dummy" name="status_dummy" class="form-select form-select-sm">
                                <option value="">Sila Pilih</option>
                                <option value="">Menunggu Penyerahan Pembentukan Jawatankuasa</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="untuk_kelulusan_pembuka" name="untuk_kelulusan_pembuka">
                                <label class="form-check-label small fw-bold text-secondary" for="untuk_kelulusan_pembuka">
                                    Untuk Kelulusan
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive">
                        <table id="tbl-jkpembuka" class="table table-modern align-middle mb-0 w-100">
                            <thead>
                                <tr>
                                    <th class="text-center py-3">No. Kad Pengenalan</th>
                                    <th class="text-center py-3">Nama</th>
                                    <th class="text-center py-3">Jawatan</th>
                                    <th class="text-center py-3">E-mel</th>
                                    <th class="text-center py-3">Gred</th>
                                    <th class="text-center py-3">P&P</th>
                                    <th class="text-center py-3">Peranan</th>
                                </tr>
                            </thead>
                            <tbody id="tbl-jkpembuka-body">
                                @include('newModule.penyediaanMesyuarat._senarai_ahli', ['jenis' => 'open'])
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <!-- ACTION BUTTONS -->
            <div class="d-flex justify-content-end align-items-center mb-4 flex-wrap gap-2">
        
                <div class="d-flex gap-2">
                    <button type="button" class="btn-form btn-form-success btn-simpan-mesyuarat" data-ui-tab="pembuka">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Simpan
                    </button>
                    <button type="button" class="btn-form btn-form-primary btn-hantar-mesyuarat" data-ui-tab="pembuka">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                        Hantar
                    </button>
                </div>
            </div>
        </div>
        @endif
        @if(in_array('teknikal', $visibleUiTabs))
        <div class="tab-content {{ $firstUiTab !== 'teknikal' ? 'd-none' : '' }}" data-tab="teknikal" data-jenis="tech">
            <div class="content-card mb-4 p-0">
                <div class="content-card-header p-4 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="content-card-icon" style="width: 38px; height: 38px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                        </div>
                        <div>
                            <h3 class="content-card-title mb-0" style="font-size: 1rem;">Perincian Mesuarat</h3>
                            <p class="text-muted mb-0" style="font-size: 0.78rem;">Diisi oleh Petender</p>
                        </div>
                    </div>
                </div>

                <div class="content-card-body p-4">

                    <!-- Table toolbar -->
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" id="btn-tambah-row-mesyuarat-teknikal"
                            class="btn btn-sm btn-success d-inline-flex align-items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Tambah
                        </button>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table id="tbl-mesyuarat-teknikal" class="table table-modern align-middle mb-0 w-100">
                            <thead>
                                <tr>
                                    <th class="text-center py-3" style="width:50px;">Bil</th>
                                    <th class="text-center py-3">Tarikh Mesyuarat</th>
                                    <th class="text-center py-3">Masa</th>
                                    <th class="text-center py-3">Tempat</th>
                                    <th class="text-center py-3" style="width:60px;">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody id="tbl-mesyuarat-teknikal-body">
                                <!-- initial row rendered by JS below -->
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="content-card mb-4 p-0">
                <div class="content-card-header p-4 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="content-card-icon" style="width: 38px; height: 38px;">
                            <svg viewBox="0 0 25 25" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.6 5c.6 0 1.2.2 1.6.7.4.4.7 1 .7 1.6 0 1.3-1 2.3-2.3 2.3s-2.3-1-2.3-2.3S11.3 5 12.6 5z"/>
                                <path d="M10.3 12.9h4.7c1.6.1 2.9 1.4 2.9 3s-1.3 2.9-2.9 3h-4.7c-1.6-.1-2.9-1.4-2.9-3s1.3-2.9 2.9-3z"/>
                                <path d="M19 7.3c.5 0 .9.2 1.2.5.3.3.5.7.5 1.2 0 1-0.8 1.8-1.7 1.8-1 0-1.8-.8-1.8-1.8 0-1 .8-1.7 1.8-1.7z"/>
                                <path d="M6.1 7.3c1 0 1.8.8 1.8 1.7 0 1-.8 1.8-1.8 1.8S4.3 10 4.3 9s.8-1.7 1.8-1.7z"/>
                                <path d="M19.4 12.8h1.3c1.7 0 3 1.4 3 3.1s-1.3 3-3 3h-1.3M5.6 12.8H4.3c-1.7 0-3 1.4-3 3.1s1.3 3 3 3h1.3"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="content-card-title mb-0" style="font-size: 1rem;">Senarai Ahli Jawatankuasa Teknikal</h3>
                            <p class="text-muted mb-0" style="font-size: 0.78rem;">Diisi oleh Petender</p>
                        </div>
                    </div>
                </div>

                <div class="content-card-body p-4">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <label for="status_dummy" class="form-label small fw-bold text-secondary text-uppercase mb-1">Status</label>
                            <select id="status_dummy" name="status_dummy" class="form-select form-select-sm">
                                <option value="">Sila Pilih</option>
                                <option value="">Menunggu Penyerahan Pembentukan Jawatankuasa</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="untuk_kelulusan_teknikal" name="untuk_kelulusan_teknikal">
                                <label class="form-check-label small fw-bold text-secondary" for="untuk_kelulusan_teknikal">
                                    Untuk Kelulusan
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive">
                        <table id="tbl-jkteknikal" class="table table-modern align-middle mb-0 w-100">
                            <thead>
                                <tr>
                                    <th class="text-center py-3">No. Kad Pengenalan</th>
                                    <th class="text-center py-3">Nama</th>
                                    <th class="text-center py-3">Jawatan</th>
                                    <th class="text-center py-3">E-mel</th>
                                    <th class="text-center py-3">Gred</th>
                                    <th class="text-center py-3">P&P</th>
                                    <th class="text-center py-3">Peranan</th>
                                </tr>
                            </thead>
                            <tbody id="tbl-jkteknikal-body">
                                @include('newModule.penyediaanMesyuarat._senarai_ahli', ['jenis' => 'tech'])
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <!-- ACTION BUTTONS -->
            <div class="d-flex justify-content-end align-items-center mb-4 flex-wrap gap-2">
        
                <div class="d-flex gap-2">
                    <button type="button" class="btn-form btn-form-success btn-simpan-mesyuarat" data-ui-tab="teknikal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Simpan
                    </button>
                    <button type="button" class="btn-form btn-form-primary btn-hantar-mesyuarat" data-ui-tab="teknikal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                        Hantar
                    </button>
                </div>
            </div>
        </div>
        @endif
        @if(in_array('kewangan', $visibleUiTabs))
        <div class="tab-content {{ $firstUiTab !== 'kewangan' ? 'd-none' : '' }}" data-tab="kewangan" data-jenis="fin">
            <div class="content-card mb-4 p-0">
                <div class="content-card-header p-4 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="content-card-icon" style="width: 38px; height: 38px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                        </div>
                        <div>
                            <h3 class="content-card-title mb-0" style="font-size: 1rem;">Perincian Mesuarat</h3>
                            <p class="text-muted mb-0" style="font-size: 0.78rem;">Diisi oleh Petender</p>
                        </div>
                    </div>
                </div>

                <div class="content-card-body p-4">

                    <!-- Table toolbar -->
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" id="btn-tambah-row-mesyuarat-kewangan"
                            class="btn btn-sm btn-success d-inline-flex align-items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Tambah
                        </button>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table id="tbl-mesyuarat-kewangan" class="table table-modern align-middle mb-0 w-100">
                            <thead>
                                <tr>
                                    <th class="text-center py-3" style="width:50px;">Bil</th>
                                    <th class="text-center py-3">Tarikh Mesyuarat</th>
                                    <th class="text-center py-3">Masa</th>
                                    <th class="text-center py-3">Tempat</th>
                                    <th class="text-center py-3" style="width:60px;">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody id="tbl-mesyuarat-kewangan-body">
                                <!-- initial row rendered by JS below -->
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="content-card mb-4 p-0">
                <div class="content-card-header p-4 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="content-card-icon" style="width: 38px; height: 38px;">
                            <svg viewBox="0 0 25 25" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.6 5c.6 0 1.2.2 1.6.7.4.4.7 1 .7 1.6 0 1.3-1 2.3-2.3 2.3s-2.3-1-2.3-2.3S11.3 5 12.6 5z"/>
                                <path d="M10.3 12.9h4.7c1.6.1 2.9 1.4 2.9 3s-1.3 2.9-2.9 3h-4.7c-1.6-.1-2.9-1.4-2.9-3s1.3-2.9 2.9-3z"/>
                                <path d="M19 7.3c.5 0 .9.2 1.2.5.3.3.5.7.5 1.2 0 1-0.8 1.8-1.7 1.8-1 0-1.8-.8-1.8-1.8 0-1 .8-1.7 1.8-1.7z"/>
                                <path d="M6.1 7.3c1 0 1.8.8 1.8 1.7 0 1-.8 1.8-1.8 1.8S4.3 10 4.3 9s.8-1.7 1.8-1.7z"/>
                                <path d="M19.4 12.8h1.3c1.7 0 3 1.4 3 3.1s-1.3 3-3 3h-1.3M5.6 12.8H4.3c-1.7 0-3 1.4-3 3.1s1.3 3 3 3h1.3"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="content-card-title mb-0" style="font-size: 1rem;">Senarai Ahli Jawatankuasa kewangan</h3>
                            <p class="text-muted mb-0" style="font-size: 0.78rem;">Diisi oleh Petender</p>
                        </div>
                    </div>
                </div>

                <div class="content-card-body p-4">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <label for="status_dummy" class="form-label small fw-bold text-secondary text-uppercase mb-1">Status</label>
                            <select id="status_dummy" name="status_dummy" class="form-select form-select-sm">
                                <option value="">Sila Pilih</option>
                                <option value="">Menunggu Penyerahan Pembentukan Jawatankuasa</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="untuk_kelulusan_kewangan" name="untuk_kelulusan_kewangan">
                                <label class="form-check-label small fw-bold text-secondary" for="untuk_kelulusan_kewangan">
                                    Untuk Kelulusan
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive">
                        <table id="tbl-jkkewangan" class="table table-modern align-middle mb-0 w-100">
                            <thead>
                                <tr>
                                    <th class="text-center py-3">No. Kad Pengenalan</th>
                                    <th class="text-center py-3">Nama</th>
                                    <th class="text-center py-3">Jawatan</th>
                                    <th class="text-center py-3">E-mel</th>
                                    <th class="text-center py-3">Gred</th>
                                    <th class="text-center py-3">P&P</th>
                                    <th class="text-center py-3">Peranan</th>
                                </tr>
                            </thead>
                            <tbody id="tbl-jkkewangan-body">
                                @include('newModule.penyediaanMesyuarat._senarai_ahli', ['jenis' => 'fin'])
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <!-- ACTION BUTTONS -->
            <div class="d-flex justify-content-end align-items-center mb-4 flex-wrap gap-2">
        
                <div class="d-flex gap-2">
                    <button type="button" class="btn-form btn-form-success btn-simpan-mesyuarat" data-ui-tab="kewangan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Simpan
                    </button>
                    <button type="button" class="btn-form btn-form-primary btn-hantar-mesyuarat" data-ui-tab="kewangan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                        Hantar
                    </button>
                </div>
            </div>
        </div>
        @endif
        @if(in_array('sebutharga', $visibleUiTabs))
        <div class="tab-content {{ $firstUiTab !== 'sebutharga' ? 'd-none' : '' }}" data-tab="sebutharga" data-jenis="harga">
            <div class="content-card mb-4 p-0">
                <div class="content-card-header p-4 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="content-card-icon" style="width: 38px; height: 38px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                        </div>
                        <div>
                            <h3 class="content-card-title mb-0" style="font-size: 1rem;">Perincian Mesuarat</h3>
                            <p class="text-muted mb-0" style="font-size: 0.78rem;">Diisi oleh Petender</p>
                        </div>
                    </div>
                </div>

                <div class="content-card-body p-4">

                    <!-- Table toolbar -->
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" id="btn-tambah-row-mesyuarat-sebutharga"
                            class="btn btn-sm btn-success d-inline-flex align-items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Tambah
                        </button>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table id="tbl-mesyuarat-sebutharga" class="table table-modern align-middle mb-0 w-100">
                            <thead>
                                <tr>
                                    <th class="text-center py-3" style="width:50px;">Bil</th>
                                    <th class="text-center py-3">Tarikh Mesyuarat</th>
                                    <th class="text-center py-3">Masa</th>
                                    <th class="text-center py-3">Tempat</th>
                                    <th class="text-center py-3" style="width:60px;">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody id="tbl-mesyuarat-sebutharga-body">
                                <!-- initial row rendered by JS below -->
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="content-card mb-4 p-0">
                <div class="content-card-header p-4 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="content-card-icon" style="width: 38px; height: 38px;">
                            <svg viewBox="0 0 25 25" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.6 5c.6 0 1.2.2 1.6.7.4.4.7 1 .7 1.6 0 1.3-1 2.3-2.3 2.3s-2.3-1-2.3-2.3S11.3 5 12.6 5z"/>
                                <path d="M10.3 12.9h4.7c1.6.1 2.9 1.4 2.9 3s-1.3 2.9-2.9 3h-4.7c-1.6-.1-2.9-1.4-2.9-3s1.3-2.9 2.9-3z"/>
                                <path d="M19 7.3c.5 0 .9.2 1.2.5.3.3.5.7.5 1.2 0 1-0.8 1.8-1.7 1.8-1 0-1.8-.8-1.8-1.8 0-1 .8-1.7 1.8-1.7z"/>
                                <path d="M6.1 7.3c1 0 1.8.8 1.8 1.7 0 1-.8 1.8-1.8 1.8S4.3 10 4.3 9s.8-1.7 1.8-1.7z"/>
                                <path d="M19.4 12.8h1.3c1.7 0 3 1.4 3 3.1s-1.3 3-3 3h-1.3M5.6 12.8H4.3c-1.7 0-3 1.4-3 3.1s1.3 3 3 3h1.3"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="content-card-title mb-0" style="font-size: 1rem;">Senarai Ahli Jawatankuasa Penilaian Sebut Harga/Tender</h3>
                            <p class="text-muted mb-0" style="font-size: 0.78rem;">Diisi oleh Petender</p>
                        </div>
                    </div>
                </div>

                <div class="content-card-body p-4">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <label for="status_dummy" class="form-label small fw-bold text-secondary text-uppercase mb-1">Status</label>
                            <select id="status_dummy" name="status_dummy" class="form-select form-select-sm">
                                <option value="">Sila Pilih</option>
                                <option value="">Menunggu Penyerahan Pembentukan Jawatankuasa</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="untuk_kelulusan_sebutharga" name="untuk_kelulusan_sebutharga">
                                <label class="form-check-label small fw-bold text-secondary" for="untuk_kelulusan_sebutharga">
                                    Untuk Kelulusan
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive">
                        <table id="tbl-jksebutharga" class="table table-modern align-middle mb-0 w-100">
                            <thead>
                                <tr>
                                    <th class="text-center py-3">No. Kad Pengenalan</th>
                                    <th class="text-center py-3">Nama</th>
                                    <th class="text-center py-3">Jawatan</th>
                                    <th class="text-center py-3">E-mel</th>
                                    <th class="text-center py-3">Gred</th>
                                    <th class="text-center py-3">P&P</th>
                                    <th class="text-center py-3">Peranan</th>
                                </tr>
                            </thead>
                            <tbody id="tbl-jksebutharga-body">
                                @include('newModule.penyediaanMesyuarat._senarai_ahli', ['jenis' => 'harga'])
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <!-- ACTION BUTTONS -->
            <div class="d-flex justify-content-end align-items-center mb-4 flex-wrap gap-2">
        
                <div class="d-flex gap-2">
                    <button type="button" class="btn-form btn-form-success btn-simpan-mesyuarat" data-ui-tab="sebutharga">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Simpan
                    </button>
                    <button type="button" class="btn-form btn-form-primary btn-hantar-mesyuarat" data-ui-tab="sebutharga">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                        Hantar
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>

 


<script type="application/json" id="mesyuarat-page-config">{!! json_encode([
    'tenderUuid' => $tender->uuid ?? '',
    'jenisByUiTab' => $jenisByUiTab ?? [],
    'savedMeetings' => $meetingsForJs ?? [],
    'saveUrl' => route('penyediaanMesyuarat.simpan'),
    'hantarUrl' => route('penyediaanMesyuarat.hantar'),
    'csrfToken' => csrf_token(),
]) !!}</script>

<script type="text/javascript">

    document.addEventListener('DOMContentLoaded', function () {

        const {
            tenderUuid,
            jenisByUiTab,
            savedMeetings,
            saveUrl,
            hantarUrl,
            csrfToken,
        } = JSON.parse(document.getElementById('mesyuarat-page-config').textContent);

        function seedMeetingRows(uiTab, $body, buildRowFn) {
            const saved = savedMeetings[uiTab] || [];
            $body.empty();
            if (!saved.length) {
                $body.append(buildRowFn(1));
                return;
            }
            saved.forEach(function (row, index) {
                const $row = $(buildRowFn(index + 1));
                $row.find('input[type="date"]').val(row.tarikh_mesyuarat || '');
                $row.find('input[type="time"]').val(row.masa || '');
                $row.find('input[type="text"]').val(row.tempat || '');
                $body.append($row);
            });
        }

        function collectRows(uiTab) {
            const tableMap = {
                pembuka: '#tbl-mesyuarat-pembuka-body',
                teknikal: '#tbl-mesyuarat-teknikal-body',
                kewangan: '#tbl-mesyuarat-kewangan-body',
                sebutharga: '#tbl-mesyuarat-sebutharga-body',
            };
            const rows = [];
            $(tableMap[uiTab] + ' tr').each(function () {
                const tarikh = $(this).find('input[type="date"]').val();
                const masa = $(this).find('input[type="time"]').val();
                const tempat = $(this).find('input[type="text"]').val();
                if (tarikh && masa && tempat) {
                    rows.push({ tarikh_mesyuarat: tarikh, masa: masa, tempat: tempat });
                }
            });
            return rows;
        }

        function postMeeting(action, uiTab) {
            const rows = collectRows(uiTab);
            if (!rows.length) {
                alert('Sila lengkapkan sekurang-kurangnya satu perincian mesyuarat.');
                return;
            }
            if (action === 'hantar' && !confirm('Hantar jemputan mesyuarat kepada ahli jawatankuasa tab ini?')) {
                return;
            }
            $.ajax({
                url: action === 'hantar' ? hantarUrl : saveUrl,
                method: 'POST',
                data: {
                    _token: csrfToken,
                    tender: tenderUuid,
                    jenis_jawatankuasa: jenisByUiTab[uiTab],
                    rows: rows,
                },
                success: function (res) {
                    alert(res.message || 'Berjaya.');
                },
                error: function (xhr) {
                    alert((xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal memproses permintaan.');
                },
            });
        }

        $(document).on('click', '.btn-simpan-mesyuarat', function () {
            postMeeting('simpan', $(this).data('ui-tab'));
        });

        $(document).on('click', '.btn-hantar-mesyuarat', function () {
            postMeeting('hantar', $(this).data('ui-tab'));
        });

        function buildRow(bil) {
            return $('<tr class="mesyuarat-pembuka">' +
                '<td class="text-center row-bil fw-semibold text-muted" style="font-size:0.8rem;">' + bil + '</td>' +
                '<td><input type="date" name="pengalaman_tarikh_mesyuarat[]" class="form-control form-control-sm"></td>' +
                '<td><input type="time" name="pengalaman_masa[]" class="form-control form-control-sm"></td>' +
                '<td><input type="text" name="pengalaman_tempat[]" class="form-control form-control-sm" placeholder="Tempat mesyuarat..."></td>' +
                '<td class="text-center">' +
                    '<button type="button" class="btn btn-sm btn-hapus-row d-inline-flex align-items-center justify-content-center p-0" ' +
                        'style="width:28px;height:28px;border-radius:6px;background:#fee2e2;color:#ef4444;border:none;" ' +
                        'title="Buang baris">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path></svg>' +
                    '</button>' +
                '</td>' +
            '</tr>');
        }

        const $mesyuaratPembukaBody = $('#tbl-mesyuarat-pembuka-body');

        function renumberRows() {
            $mesyuaratPembukaBody.find('.row-bil').each(function (index) {
                $(this).text(index + 1);
            });
        }

        seedMeetingRows('pembuka', $mesyuaratPembukaBody, buildRow);

        // Add one row per click
        $('#btn-tambah-row-mesyuarat-pembuka').on('click', function () {
            const nextBil = $mesyuaratPembukaBody.find('.mesyuarat-pembuka').length + 1;
            $mesyuaratPembukaBody.append(buildRow(nextBil));
        });

        // Remove row and keep numbering consistent
        $mesyuaratPembukaBody.on('click', '.btn-hapus-row', function () {
            $(this).closest('.mesyuarat-pembuka').remove();
            renumberRows();
        });

        function buildRowTeknikal(bil) {
            return $('<tr class="mesyuarat-teknikal">' +
                '<td class="text-center row-bil fw-semibold text-muted" style="font-size:0.8rem;">' + bil + '</td>' +
                '<td><input type="date" name="pengalaman_tarikh_mesyuarat_teknikal[]" class="form-control form-control-sm"></td>' +
                '<td><input type="time" name="pengalaman_masa_teknikal[]" class="form-control form-control-sm"></td>' +
                '<td><input type="text" name="pengalaman_tempat_teknikal[]" class="form-control form-control-sm" placeholder="Tempat mesyuarat..."></td>' +
                '<td class="text-center">' +
                    '<button type="button" class="btn btn-sm btn-hapus-row-teknikal d-inline-flex align-items-center justify-content-center p-0" ' +
                        'style="width:28px;height:28px;border-radius:6px;background:#fee2e2;color:#ef4444;border:none;" ' +
                        'title="Buang baris">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path></svg>' +
                    '</button>' +
                '</td>' +
            '</tr>');
        }

        const $mesyuaratTeknikalBody = $('#tbl-mesyuarat-teknikal-body');

        function renumberRowsTeknikal() {
            $mesyuaratTeknikalBody.find('.row-bil').each(function (index) {
                $(this).text(index + 1);
            });
        }

        seedMeetingRows('teknikal', $mesyuaratTeknikalBody, buildRowTeknikal);

        // Add one row per click
        $('#btn-tambah-row-mesyuarat-teknikal').on('click', function () {
            const nextBil = $mesyuaratTeknikalBody.find('.mesyuarat-teknikal').length + 1;
            $mesyuaratTeknikalBody.append(buildRowTeknikal(nextBil));
        });

        // Remove row and keep numbering consistent
        $mesyuaratTeknikalBody.on('click', '.btn-hapus-row-teknikal', function () {
            $(this).closest('.mesyuarat-teknikal').remove();
            renumberRowsTeknikal();
        });

        function buildRowKewangan(bil) {
            return $('<tr class="mesyuarat-kewangan">' +
                '<td class="text-center row-bil fw-semibold text-muted" style="font-size:0.8rem;">' + bil + '</td>' +
                '<td><input type="date" name="pengalaman_tarikh_mesyuarat_kewangan[]" class="form-control form-control-sm"></td>' +
                '<td><input type="time" name="pengalaman_masa_kewangan[]" class="form-control form-control-sm"></td>' +
                '<td><input type="text" name="pengalaman_tempat_kewangan[]" class="form-control form-control-sm" placeholder="Tempat mesyuarat..."></td>' +
                '<td class="text-center">' +
                    '<button type="button" class="btn btn-sm btn-hapus-row-kewangan d-inline-flex align-items-center justify-content-center p-0" ' +
                        'style="width:28px;height:28px;border-radius:6px;background:#fee2e2;color:#ef4444;border:none;" ' +
                        'title="Buang baris">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path></svg>' +
                    '</button>' +
                '</td>' +
            '</tr>');
        }

        const $mesyuaratKewanganBody = $('#tbl-mesyuarat-kewangan-body');

        function renumberRowsKewangan() {
            $mesyuaratKewanganBody.find('.row-bil').each(function (index) {
                $(this).text(index + 1);
            });
        }

        seedMeetingRows('kewangan', $mesyuaratKewanganBody, buildRowKewangan);

        // Add one row per click
        $('#btn-tambah-row-mesyuarat-kewangan').on('click', function () {
            const nextBil = $mesyuaratKewanganBody.find('.mesyuarat-kewangan').length + 1;
            $mesyuaratKewanganBody.append(buildRowKewangan(nextBil));
        });

        // Remove row and keep numbering consistent
        $mesyuaratKewanganBody.on('click', '.btn-hapus-row-kewangan', function () {
            $(this).closest('.mesyuarat-kewangan').remove();
            renumberRowsKewangan();
        });

        function buildRowSebutharga(bil) {
            return $('<tr class="mesyuarat-sebutharga">' +
                '<td class="text-center row-bil fw-semibold text-muted" style="font-size:0.8rem;">' + bil + '</td>' +
                '<td><input type="date" name="pengalaman_tarikh_mesyuarat_sebutharga[]" class="form-control form-control-sm"></td>' +
                '<td><input type="time" name="pengalaman_masa_sebutharga[]" class="form-control form-control-sm"></td>' +
                '<td><input type="text" name="pengalaman_tempat_sebutharga[]" class="form-control form-control-sm" placeholder="Tempat mesyuarat..."></td>' +
                '<td class="text-center">' +
                    '<button type="button" class="btn btn-sm btn-hapus-row-sebutharga d-inline-flex align-items-center justify-content-center p-0" ' +
                        'style="width:28px;height:28px;border-radius:6px;background:#fee2e2;color:#ef4444;border:none;" ' +
                        'title="Buang baris">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path></svg>' +
                    '</button>' +
                '</td>' +
            '</tr>');
        }

        const $mesyuaratSebuthargaBody = $('#tbl-mesyuarat-sebutharga-body');

        function renumberRowsSebutharga() {
            $mesyuaratSebuthargaBody.find('.row-bil').each(function (index) {
                $(this).text(index + 1);
            });
        }

        seedMeetingRows('sebutharga', $mesyuaratSebuthargaBody, buildRowSebutharga);

        // Add one row per click
        $('#btn-tambah-row-mesyuarat-sebutharga').on('click', function () {
            const nextBil = $mesyuaratSebuthargaBody.find('.mesyuarat-sebutharga').length + 1;
            $mesyuaratSebuthargaBody.append(buildRowSebutharga(nextBil));
        });

        // Remove row and keep numbering consistent
        $mesyuaratSebuthargaBody.on('click', '.btn-hapus-row-sebutharga', function () {
            $(this).closest('.mesyuarat-sebutharga').remove();
            renumberRowsSebutharga();
        });

        document.querySelectorAll('.nested-tabs').forEach(wrapper => {

            wrapper.addEventListener('click', function (e) {

                const btn = e.target.closest('.nested-tab-btn');
                if (!btn) return;

                const tab = btn.dataset.tab;
                const contentWrapper = wrapper.nextElementSibling;

                // remove active
                wrapper.querySelectorAll('.nested-tab-btn')
                    .forEach(b => b.classList.remove('active'));

                btn.classList.add('active');

                // toggle content
                contentWrapper.querySelectorAll('.tab-content')
                    .forEach(div => {
                        div.classList.toggle('d-none', div.dataset.tab !== tab);
                    });
            });
        });

    });

    

</script>

  
@endsection

