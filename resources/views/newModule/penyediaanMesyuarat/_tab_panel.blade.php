@php
    $uiTab = $tab['ui'];
    $jenis = $tab['jenis'];
    $label = $tab['label'];
    $status = $meetingStatusByJenis[$jenis] ?? 'Belum Disimpan';
    $statusClass = $status === 'Dihantar' ? 'bg-success-subtle text-success border-success-subtle' : 'bg-warning-subtle text-warning border-warning-subtle';
@endphp

<div class="tab-content {{ empty($isFirst) ? 'd-none' : '' }}" data-tab="{{ $uiTab }}" data-jenis="{{ $jenis }}">
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
                    <h3 class="content-card-title mb-0" style="font-size: 1rem;">Perincian Mesyuarat</h3>
                    <p class="text-muted mb-0" style="font-size: 0.78rem;">Diisi oleh Urusetia</p>
                </div>
            </div>
        </div>

        <div class="content-card-body p-4">
            <div class="d-flex justify-content-end mb-3">
                <button type="button" id="btn-tambah-row-mesyuarat-{{ $uiTab }}"
                    class="btn btn-sm btn-success d-inline-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Tambah
                </button>
            </div>

            <div class="table-responsive">
                <table id="tbl-mesyuarat-{{ $uiTab }}" class="table table-modern align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th class="text-center py-3" style="width:50px;">Bil</th>
                            <th class="text-center py-3">Tarikh Mesyuarat</th>
                            <th class="text-center py-3">Masa</th>
                            <th class="text-center py-3">Tempat</th>
                            <th class="text-center py-3" style="width:60px;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody id="tbl-mesyuarat-{{ $uiTab }}-body"></tbody>
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
                    <h3 class="content-card-title mb-0" style="font-size: 1rem;">Senarai Ahli {{ $label }}</h3>
                    <p class="text-muted mb-0" style="font-size: 0.78rem;">Maklumat dari pelantikan jawatankuasa</p>
                </div>
            </div>
        </div>

        <div class="content-card-body p-4">
            <div class="row mb-3">
                <div class="col-sm-6">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Status Mesyuarat</label>
                    <div>
                        <span class="badge rounded-pill {{ $statusClass }} border px-3 py-2 fw-bold text-uppercase" style="font-size: 0.8rem;">
                            {{ $status }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0 w-100">
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
                    <tbody>
                        @include('newModule.penyediaanMesyuarat._senarai_ahli', ['jenis' => $jenis])
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end align-items-center mb-4 flex-wrap gap-2">
        <div class="d-flex gap-2">
            <button type="button" class="btn-form btn-form-success btn-simpan-mesyuarat" data-ui-tab="{{ $uiTab }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Simpan
            </button>
            <button type="button" class="btn-form btn-form-primary btn-hantar-mesyuarat" data-ui-tab="{{ $uiTab }}">
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
