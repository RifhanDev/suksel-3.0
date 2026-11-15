@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/bq-upload-tokens.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/bq-upload.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <style>
        #tbl-spesifikasi { border: 1px solid #e2e8f0; }
        #tbl-spesifikasi th, #tbl-spesifikasi td { border-right: 1px solid #e2e8f0 !important; }
        #tbl-spesifikasi th:last-child, #tbl-spesifikasi td:last-child { border-right: none !important; }
        #tbl-spesifikasi tbody .group-alt td { background-color: #f8fafc; }
        #tbl-spesifikasi tbody .item-row td:first-child,
        #tbl-spesifikasi tbody .spec-row td:first-child { border-bottom: none !important; }
        #tbl-spesifikasi tbody .item-has-specs > td:first-child { position: relative; }
        #tbl-spesifikasi tbody .item-has-specs > td:first-child::before {
            content: ''; position: absolute; left: 10px; top: 50%;
            width: 7px; height: 1.5px; background: #cbd5e1; transform: translateY(-50%);
        }
        #tbl-spesifikasi tbody .item-has-specs > td:first-child::after {
            content: ''; position: absolute; left: 10px; top: 50%; bottom: 0;
            width: 1.5px; background: #cbd5e1;
        }
        #tbl-spesifikasi tbody .spec-row > td:first-child { position: relative; }
        #tbl-spesifikasi tbody .spec-row > td:first-child::before {
            content: ''; position: absolute; left: 10px; top: 0; bottom: 0;
            width: 1.5px; background: #cbd5e1;
        }
        #tbl-spesifikasi tbody .spec-row > td:first-child::after {
            content: ''; position: absolute; left: 10px; top: 50%; width: 18px;
            height: 1.5px; background: #cbd5e1; transform: translateY(-50%);
        }
        #tbl-spesifikasi tbody .spec-last > td:first-child::before { bottom: 50%; }
        .dokumen-chip-cell { min-width: 140px; max-width: 200px; }
        .file-chip {
            display: inline-flex; align-items: center; gap: 4px;
            background: #f1f5f9; border: 1px solid #e2e8f0;
            border-radius: 6px; padding: 2px 6px 2px 4px; font-size: 0.7rem; margin: 2px;
        }
        .file-chip .ext-badge {
            background: #64748b; color: #fff; border-radius: 3px;
            padding: 1px 4px; font-size: 0.6rem; font-weight: 700;
            text-transform: uppercase; flex-shrink: 0;
        }
        .file-chip a {
            color: #334155; font-weight: 600; max-width: 80px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;
        }
        .file-chip .chip-delete {
            background: none; border: none; padding: 0; color: #94a3b8; cursor: pointer; line-height: 1;
        }
        .file-chip .chip-delete:hover { color: #ef4444; }
        .upload-btn-row { cursor: pointer; }
        .upload-btn-row.disabled-upload { opacity: 0.45; cursor: not-allowed; pointer-events: none; }
        #toast-container { position: fixed; top: 1.25rem; right: 1.25rem; z-index: 9999; min-width: 280px; }
        .jumlah-total-bar {
            background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;
            padding: 0.75rem 1rem; max-width: 360px; margin-left: auto;
        }
        .item-pager {
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 0.75rem;
            background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;
            padding: 0.65rem 0.9rem;
        }
        .item-pager-meta {
            display: flex; flex-direction: column; gap: 2px; min-width: 0; flex: 1;
        }
        .item-pager-label {
            font-size: 0.8rem; font-weight: 700; color: #0f172a;
        }
        .item-pager-preview {
            font-size: 0.72rem; color: #64748b;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 420px;
        }
        .item-pager-actions { display: flex; align-items: center; gap: 0.5rem; flex-shrink: 0; }
        #tbl-spesifikasi-body tr.item-page-hidden { display: none !important; }
    </style>
@endsection

@section('content')

    <div id="toast-container"></div>

    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Penyediaan Jadual Harga Dan Kuantiti</h3>
            <p class="text-muted small m-0">Isi jadual harga dan kuantiti bagi tender / sebutharga ini.</p>
        </div>
    </div>

    <div class="content-card mb-4 p-0">
        <div class="content-card-body p-4">
            <div class="mb-3 pb-3 border-bottom">
                <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                    style="font-size:0.67rem;letter-spacing:0.5px;">Tajuk Tender</span>
                <h5 class="fw-bold text-dark mb-0" style="line-height:1.45;font-size:1rem;">
                    {{ $tender->name ?? '-' }}
                    <span class="fw-normal text-muted fst-italic" style="font-size:0.85rem;">(Kerja)</span>
                </h5>
            </div>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size:0.67rem;letter-spacing:0.5px;">No. Tender</span>
                    <span class="fw-semibold text-dark" style="font-size:0.875rem;">
                        {{ $tender->no_tender ?: ($tender->ref_number ?? '-') }}
                    </span>
                </div>
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size:0.67rem;letter-spacing:0.5px;">PTJ</span>
                    <span class="fw-semibold text-dark" style="font-size:0.875rem;">
                        {{ $tender->tenderer->name ?? '-' }}
                    </span>
                </div>
                <div class="col-12 col-md-6 d-md-flex justify-content-md-end align-items-md-center">
                    @if(($checklistData['status'] ?? null) === 'submitted')
                        <span id="status-badge" class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2 fw-semibold"
                            style="background:#dcfce7;color:#166534;font-size:0.8rem;border:1px solid #bbf7d0;">
                            <span class="d-inline-block rounded-circle" style="width:7px;height:7px;background:#16a34a;flex-shrink:0;"></span>
                            Telah Dihantar
                        </span>
                    @else
                        <span id="status-badge" class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2 fw-semibold"
                            style="background:#fef9c3;color:#854d0e;font-size:0.8rem;border:1px solid #fde68a;">
                            <span class="d-inline-block rounded-circle" style="width:7px;height:7px;background:#ca8a04;flex-shrink:0;"></span>
                            Dalam Proses
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="content-card mb-4 p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width:38px;height:38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        <line x1="8" y1="6" x2="16" y2="6"></line>
                        <line x1="8" y1="10" x2="16" y2="10"></line>
                    </svg>
                </div>
                <div>
            <h3 class="content-card-title mb-0" style="font-size:1rem;">Muat Naik Jadual Harga & Kuantiti (BQ)</h3>
                    <p class="text-muted mb-0" style="font-size:0.78rem;">Muat naik templat BQ untuk dimuat turun dan dilengkapkan oleh petender.</p>
                </div>
            </div>
        </div>
        {{--
        Legacy item-based specification editor retained for reference.
        This section is replaced by the tender-level BQ template workflow below.
        <div class="content-card-body p-4">

            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <label class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 mb-0"
                        style="cursor:pointer;" title="Muat naik dokumen BQ / Spesifikasi">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        Muat Naik Dokumen BQ / Spesifikasi
                        <input type="file" id="input-bq-upload" hidden
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.csv">
                    </label>
                    <div id="bq-chips" class="d-flex flex-wrap gap-1"></div>
                </div>
                <button type="button" id="btn-tambah-item"
                    class="btn btn-sm btn-success d-inline-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Tambah Item
                </button>
            </div>

            <div id="item-pager" class="item-pager mb-3" style="display:none;">
                <div class="item-pager-meta">
                    <span class="item-pager-label" id="item-pager-label">Item 0 / 0</span>
                    <span class="item-pager-preview" id="item-pager-preview">—</span>
                </div>
                <div class="item-pager-actions">
                    <button type="button" id="btn-item-sebelum" class="btn btn-sm btn-outline-secondary">Sebelum</button>
                    <button type="button" id="btn-item-seterusnya" class="btn btn-sm btn-outline-secondary">Seterusnya</button>
                </div>
            </div>

            <div class="table-responsive">
                <table id="tbl-spesifikasi" class="table table-modern align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th class="py-3" style="min-width:220px;">
                                Item
                                <div style="font-size:0.68rem;font-weight:600;text-transform:none;letter-spacing:0;color:#94a3b8;margin-top:2px;">
                                    Spesifikasi
                                </div>
                            </th>
                            <th class="text-center py-3" style="width:100px;">Unit</th>
                            <th class="text-center py-3" style="width:100px;">Kuantiti</th>
                            <th class="text-center py-3" style="width:110px;">Pematuhan</th>
                            <th class="py-3" style="min-width:160px;">Catatan</th>
                            <th class="py-3 dokumen-chip-cell">Dokumen</th>
                            <th class="text-center py-3" style="width:110px;">Kadar (RM)</th>
                            <th class="text-center py-3" style="width:110px;">Jumlah (RM)</th>
                            <th class="text-center py-3" style="width:120px;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody id="tbl-spesifikasi-body">
                        <tr id="tbl-spesifikasi-empty">
                            <td colspan="9" class="text-center text-muted py-4 small">
                                Tiada item. Klik <strong>Tambah Item</strong> untuk mula.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="item-pager-bottom" class="item-pager mt-3" style="display:none;">
                <div class="item-pager-meta">
                    <span class="item-pager-label" id="item-pager-label-bottom">Item 0 / 0</span>
                    <span class="item-pager-preview" id="item-pager-preview-bottom">—</span>
                </div>
                <div class="item-pager-actions">
                    <button type="button" id="btn-item-sebelum-bottom" class="btn btn-sm btn-outline-secondary">Sebelum</button>
                    <button type="button" id="btn-item-seterusnya-bottom" class="btn btn-sm btn-outline-secondary">Seterusnya</button>
                </div>
            </div>

            <div class="jumlah-total-bar mt-3 d-flex align-items-center justify-content-between">
                <span class="fw-semibold text-dark" style="font-size:0.85rem;">Jumlah Keseluruhan (RM)</span>
                <span class="fw-bold text-dark" id="jumlah-keseluruhan" style="font-size:1rem;">0.00</span>
            </div>

        </div>
        --}}
        <div class="content-card-body p-4 bq-upload">
            <div class="bq-upload-panel" id="bq-upload-panel" aria-busy="false">
                <div class="bq-upload-heading">
                    <div class="bq-upload-symbol" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M12 18v-6m-3 3 3-3 3 3"/></svg>
                    </div>
                    <div>
                        <h4 class="bq-upload-title">Seret dan letakkan templat BQ di sini</h4>
                        <p class="bq-upload-help">atau pilih dokumen daripada komputer anda.</p>
                    </div>
                    <label for="input-bq-template" class="mb-0 bq-upload-action">
                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4m-4 4 4-4 4 4M4 16v4h16v-4"/></svg><span id="bq-template-upload-label">Pilih Templat BQ</span>
                        <input type="file" id="input-bq-template" aria-label="Pilih atau ganti templat BQ" aria-describedby="bq-template-rules bq-template-feedback" accept=".pdf,.doc,.docx,.xls,.xlsx">
                    </label>
                </div>
            </div>
            <div id="bq-template-rules" class="bq-upload-rules">
                <span>Format: PDF, Word atau Excel</span>
                <span>1 dokumen sahaja, maksimum 25 MB</span>
            </div>
            <div id="bq-template-feedback" class="small mt-3" role="status" aria-live="polite" aria-atomic="true"></div>
            <div class="bq-document-heading">
                <h4>Dokumen templat BQ</h4>
                <span id="bq-document-count" class="bq-document-count">0 / 1 dokumen</span>
            </div>
            <div id="bq-template-file"></div>
            <p class="bq-replacement-note">Petender akan memuat turun templat ini untuk dilengkapkan. Memuat naik dokumen baharu akan menggantikan templat sedia ada.</p>
        </div>
    </div>

    <div id="form-actions" class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <a href="{{ route('pengurusanSpesifikasi') }}" class="btn-form btn-form-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali
        </a>
        <div id="action-btn-group" class="d-flex gap-2">
            <button type="button" id="btn-simpan" disabled class="btn-form btn-form-primary">Simpan</button>
            <button type="button" id="btn-hantar" disabled class="btn-form btn-form-success">Hantar</button>
        </div>
    </div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{--
Legacy specification editor JavaScript retained for reference. It manages item
rows, pagination, quantities, rates, totals, and the old submit validation.
<script>
$(document).ready(function () {

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || $('meta[name="_token"]').attr('content') }
    });

    const LIST_URL       = @json(route('pengurusanSpesifikasi'));
    const SAVE_URL       = @json(route('penyediaanSpekTender.store', $tender->uuid));
    const SUBMIT_URL     = @json(route('penyediaanSpekTender.submit', $tender->uuid));
    const UPLOAD_URL     = @json(route('penyediaanSpekTender.uploadFile', $tender->uuid));
    const DELETE_BASE    = @json(url('/penyediaan-spesifikasi-tender/fail'));
    const FILE_BASE      = @json(url('/penyediaan-spesifikasi-tender/' . $tender->uuid . '/fail'));
    const USER_ID        = @json(auth()->id());
    const IS_SUBMITTED   = @json(($checklistData['status'] ?? null) === 'submitted');

    var serverData = @json($checklistData ?? null);
    var rowSeq = 0;
    var currentItemIndex = 0;
    var isPagerSaving = false;

    if (serverData) {
        renderFromServer(serverData);
    } else {
        updateItemPager();
    }

    function renderFromServer(data, preferredIndex) {
        var items = normalizeItems(data.items || []);
        $('#tbl-spesifikasi-body').empty();

        if (items.length === 0) {
            syncEmpty();
            updateJumlahKeseluruhan();
            updateItemPager();
        } else {
            items.forEach(function (item) {
                var $itemRow = buildItemRow(item);
                $('#tbl-spesifikasi-body').append($itemRow);
                (item.specs || []).forEach(function (spec) {
                    var $specs = getItemSpecs($itemRow);
                    var $after = $specs.length ? $specs.last() : $itemRow;
                    $after.after(buildSpecRow(spec));
                });
            });

            reindexGroups();
            updateJumlahKeseluruhan();
            showItemPage(typeof preferredIndex === 'number' ? preferredIndex : 0);
        }

        $('#bq-chips').empty();
        (data.files || []).forEach(function (f) {
            if ((f.file_type || '') === 'bq' || !f.item_uuid) {
                renderFileChip(f, $('#bq-chips'));
            }
        });
    }

    // Support both nested {specs:[]} and flat parent_id payloads from API.
    function normalizeItems(rawItems) {
        if (!rawItems.length) return [];

        if (rawItems[0].specs || rawItems[0].nama_item !== undefined || rawItems[0].item !== undefined) {
            return rawItems.map(function (item) {
                return {
                    uuid: item.uuid || '',
                    nama_item: item.nama_item || item.item || item.title || '',
                    specs: (item.specs || item.children || []).map(function (s) {
                        return {
                            uuid: s.uuid || '',
                            spesifikasi: s.spesifikasi || s.title || '',
                            unit: s.unit || item.unit || '',
                            kuantiti: s.kuantiti != null ? s.kuantiti : (s.kekerapan != null ? s.kekerapan : (item.kuantiti != null ? item.kuantiti : '')),
                            kadar: s.kadar != null ? s.kadar : (item.kadar != null ? item.kadar : ''),
                            ya_tidak: s.ya_tidak || s.pematuhan || '',
                            catatan: s.catatan || '',
                            files: s.files || [],
                        };
                    }),
                };
            });
        }

        // Flat list with parent_id
        var parents = rawItems.filter(function (i) { return !i.parent_id && !i.parent_uuid; });
        if (parents.length === 0 && rawItems.every(function (i) { return i.spesifikasi; })) {
            // Legacy flat checklist → wrap each as item with one empty-named group? Keep as item-only rows.
            return rawItems.map(function (i) {
                return {
                    uuid: '',
                    nama_item: i.spesifikasi || '',
                    specs: [{
                        uuid: i.uuid || '',
                        spesifikasi: i.spesifikasi || '',
                        unit: i.unit || '',
                        kuantiti: i.kuantiti != null ? i.kuantiti : '',
                        kadar: i.kadar != null ? i.kadar : '',
                        ya_tidak: i.ya_tidak || '',
                        catatan: i.catatan || '',
                        files: i.files || [],
                    }],
                };
            });
        }

        return parents.map(function (p) {
            var children = rawItems.filter(function (c) {
                return c.parent_uuid === p.uuid || c.parent_id === p.id;
            });
            return {
                uuid: p.uuid || '',
                nama_item: p.nama_item || p.spesifikasi || '',
                specs: children.map(function (s) {
                    return {
                        uuid: s.uuid || '',
                        spesifikasi: s.spesifikasi || '',
                        unit: s.unit || '',
                        kuantiti: s.kuantiti != null ? s.kuantiti : '',
                        kadar: s.kadar != null ? s.kadar : '',
                        ya_tidak: s.ya_tidak || '',
                        catatan: s.catatan || '',
                        files: s.files || [],
                    };
                }),
            };
        });
    }

    function buildItemRow(item) {
        item = item || {};
        var uuid = item.uuid || '';

        var $tr = $(
            '<tr class="item-row" data-uuid="' + htmlEscape(uuid) + '">' +
                '<td style="padding-left:28px;vertical-align:top;padding-top:10px;">' +
                    '<textarea name="nama_item" class="form-control form-control-sm" rows="2" ' +
                    'placeholder="Tajuk item..." style="resize:vertical;min-height:52px;">' +
                    htmlEscape(item.nama_item || '') + '</textarea>' +
                '</td>' +
                '<td class="text-center text-muted small">—</td>' +
                '<td class="text-center text-muted small">—</td>' +
                '<td class="text-center text-muted small">—</td>' +
                '<td class="text-center text-muted small">—</td>' +
                '<td class="text-center text-muted small">—</td>' +
                '<td class="text-center text-muted small">—</td>' +
                '<td class="text-center text-muted small">—</td>' +
                '<td class="text-center" style="vertical-align:middle;">' +
                    '<div class="d-flex flex-column gap-1 align-items-stretch">' +
                        '<button type="button" class="btn btn-sm btn-primary btn-tambah-spec" style="font-size:0.72rem;">+ Spesifikasi</button>' +
                        '<button type="button" class="btn btn-sm btn-outline-danger btn-hapus-item" style="font-size:0.72rem;">Hapus Item</button>' +
                    '</div>' +
                '</td>' +
            '</tr>'
        );

        return $tr;
    }

    function buildSpecRow(spec) {
        spec = spec || {};
        var uuid = spec.uuid || '';
        var yaTidak = spec.ya_tidak || '';
        var unit = spec.unit || '';
        var kuantiti = spec.kuantiti !== '' && spec.kuantiti != null ? spec.kuantiti : '';
        var kadar = spec.kadar !== '' && spec.kadar != null ? spec.kadar : '';
        var locked = (unit === 'HB' || unit === 'L/S');
        if (locked) kuantiti = 1;
        var jumlah = formatAmount((parseFloat(kuantiti) || 0) * (parseFloat(kadar) || 0));
        var files = spec.files || [];
        var tempId = 'row-' + (++rowSeq);
        var uploadId = 'upload-' + tempId;
        var chipId = 'chips-' + tempId;
        var uploadDisabledClass = uuid ? '' : 'disabled-upload';
        var uploadTitle = uuid
            ? 'Muat naik dokumen sokongan'
            : 'Sila simpan terlebih dahulu sebelum memuat naik dokumen';

        var $tr = $(
            '<tr class="spec-row" data-uuid="' + htmlEscape(uuid) + '">' +
                '<td style="padding-left:40px;vertical-align:top;padding-top:10px;">' +
                    '<textarea name="spesifikasi" class="form-control form-control-sm" rows="2" ' +
                    'placeholder="Penerangan spesifikasi..." style="resize:vertical;min-height:52px;">' +
                    htmlEscape(spec.spesifikasi || '') + '</textarea>' +
                '</td>' +
                '<td class="text-center" style="vertical-align:top;padding-top:10px;">' +
                    '<select name="unit" class="form-select form-select-sm unit-select">' +
                        '<option value="">—</option>' +
                        '<option value="HB"' + (unit === 'HB' ? ' selected' : '') + '>HB</option>' +
                        '<option value="L/S"' + (unit === 'L/S' ? ' selected' : '') + '>L/S</option>' +
                        '<option value="Unit"' + (unit === 'Unit' ? ' selected' : '') + '>Unit</option>' +
                    '</select>' +
                '</td>' +
                '<td class="text-center" style="vertical-align:top;padding-top:10px;">' +
                    '<input type="number" name="kuantiti" class="form-control form-control-sm text-center qty-input" ' +
                    'min="0" step="1" value="' + htmlEscape(kuantiti) + '"' + (locked ? ' readonly' : '') + '>' +
                '</td>' +
                '<td class="text-center" style="vertical-align:top;padding-top:10px;">' +
                    '<select name="ya_tidak" class="form-select form-select-sm">' +
                        '<option value=""' + (yaTidak === '' ? ' selected' : '') + '>—</option>' +
                        '<option value="ya"' + (yaTidak === 'ya' ? ' selected' : '') + '>Ya</option>' +
                        '<option value="tidak"' + (yaTidak === 'tidak' ? ' selected' : '') + '>Tidak</option>' +
                    '</select>' +
                '</td>' +
                '<td style="vertical-align:top;padding-top:10px;">' +
                    '<textarea name="catatan" class="form-control form-control-sm" rows="2" ' +
                    'placeholder="Catatan..." style="resize:vertical;min-height:52px;">' +
                    htmlEscape(spec.catatan || '') + '</textarea>' +
                '</td>' +
                '<td class="dokumen-chip-cell" style="vertical-align:top;padding-top:10px;">' +
                    '<div class="d-flex flex-column align-items-start gap-1">' +
                        '<label class="upload-btn-row ' + uploadDisabledClass + ' d-inline-flex align-items-center gap-1 btn btn-sm btn-outline-primary px-2 py-1 mb-1" ' +
                        'style="font-size:0.75rem;" title="' + uploadTitle + '" for="' + uploadId + '">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">' +
                            '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line>' +
                            '</svg>Muat Naik' +
                            '<input type="file" id="' + uploadId + '" multiple hidden ' +
                            'accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.csv">' +
                        '</label>' +
                        '<div class="chips-container" id="' + chipId + '" style="display:flex;flex-wrap:wrap;gap:4px;"></div>' +
                    '</div>' +
                '</td>' +
                '<td class="text-center" style="vertical-align:top;padding-top:10px;">' +
                    '<input type="text" name="kadar" class="form-control form-control-sm text-end kadar-input" ' +
                    'placeholder="0.00" value="' + (kadar !== '' ? formatAmount(parseFloat(kadar) || 0) : '') + '">' +
                '</td>' +
                '<td class="text-center" style="vertical-align:top;padding-top:10px;">' +
                    '<input type="text" name="jumlah" class="form-control form-control-sm text-end jumlah-input" ' +
                    'value="' + jumlah + '" readonly tabindex="-1">' +
                '</td>' +
                '<td class="text-center" style="vertical-align:middle;">' +
                    '<button type="button" class="btn btn-sm btn-outline-danger btn-hapus-spec" style="font-size:0.72rem;width:100%;">Hapus</button>' +
                '</td>' +
            '</tr>'
        );

        files.forEach(function (f) {
            renderFileChip(f, $tr.find('#' + chipId));
        });

        $tr.find('#' + uploadId).on('change', function () {
            if (!uuid) {
                showToast('Sila simpan terlebih dahulu sebelum memuat naik dokumen.', 'warning');
                return;
            }
            var selected = this.files;
            if (!selected || !selected.length) return;
            var $chips = $tr.find('#' + chipId);
            $.each(selected, function (i, file) {
                uploadFile(uuid, file, $chips, $tr, 'support');
            });
            $(this).val('');
        });

        return $tr;
    }

    function getItemSpecs($itemRow) {
        var $specs = $();
        var $next = $itemRow.next('.spec-row');
        while ($next.length) {
            $specs = $specs.add($next);
            $next = $next.next('.spec-row');
        }
        return $specs;
    }

    function getItemRows() {
        return $('#tbl-spesifikasi-body tr.item-row');
    }

    function showItemPage(index) {
        var $items = getItemRows();
        var total = $items.length;

        if (total === 0) {
            currentItemIndex = 0;
            updateItemPager();
            return;
        }

        if (index < 0) index = 0;
        if (index >= total) index = total - 1;
        currentItemIndex = index;

        $items.each(function (i) {
            var $item = $(this);
            var $group = $item.add(getItemSpecs($item));
            if (i === currentItemIndex) {
                $group.removeClass('item-page-hidden');
            } else {
                $group.addClass('item-page-hidden');
            }
        });

        updateItemPager();
    }

    function updateItemPager() {
        var $items = getItemRows();
        var total = $items.length;
        var hasItems = total > 0;
        var label = hasItems
            ? ('Item ' + (currentItemIndex + 1) + ' / ' + total)
            : 'Item 0 / 0';
        var preview = '—';

        if (isPagerSaving) {
            label = 'Menyimpan draf...';
        } else if (hasItems) {
            var $current = $items.eq(currentItemIndex);
            var nama = ($current.find('[name="nama_item"]').val() || '').trim();
            preview = nama || '(Tiada tajuk item)';
        }

        $('#item-pager, #item-pager-bottom').toggle(hasItems);
        $('#item-pager-label, #item-pager-label-bottom').text(label);
        $('#item-pager-preview, #item-pager-preview-bottom').text(preview).attr('title', preview);

        var atFirst = !hasItems || currentItemIndex <= 0;
        var atLast = !hasItems || currentItemIndex >= total - 1;
        $('#btn-item-sebelum, #btn-item-sebelum-bottom').prop('disabled', isPagerSaving || atFirst);
        $('#btn-item-seterusnya, #btn-item-seterusnya-bottom').prop('disabled', isPagerSaving || atLast);
    }

    function navigateItemPage(targetIndex) {
        var total = getItemRows().length;
        if (!total || isPagerSaving) return;
        if (targetIndex < 0 || targetIndex >= total || targetIndex === currentItemIndex) return;

        if (IS_SUBMITTED) {
            showItemPage(targetIndex);
            return;
        }

        isPagerSaving = true;
        updateItemPager();
        setBusy('#btn-simpan', true, 'Menyimpan...');

        $.ajax({
            url: SAVE_URL,
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                items: collectPayload(),
                status: 'draft',
                user_id: USER_ID
            }),
            success: function (response) {
                if (response && response.success && response.data) {
                    renderFromServer(response.data, targetIndex);
                    showToast('Draf auto-disimpan.', 'success');
                } else {
                    showToast('Gagal menyimpan draf. Sila cuba lagi.', 'danger');
                    showItemPage(currentItemIndex);
                }
            },
            error: function (xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message)
                    ? xhr.responseJSON.message
                    : 'Gagal menyimpan draf.';
                showToast(msg, 'danger');
                showItemPage(currentItemIndex);
            },
            complete: function () {
                isPagerSaving = false;
                setBusy('#btn-simpan', false, 'Simpan');
                updateItemPager();
            }
        });
    }

    function reindexGroups() {
        $('#tbl-spesifikasi-body tr').removeClass('group-alt item-has-specs spec-last');
        var idx = 0;
        $('#tbl-spesifikasi-body tr.item-row').each(function () {
            idx++;
            var $item = $(this);
            var $specs = getItemSpecs($item);
            var $group = $item.add($specs);
            if (idx % 2 === 0) $group.addClass('group-alt');
            if ($specs.length > 0) {
                $item.addClass('item-has-specs');
                $specs.last().addClass('spec-last');
            }
        });
    }

    function syncEmpty() {
        var hasRows = $('#tbl-spesifikasi-body tr.item-row').length > 0;
        if (hasRows) {
            $('#tbl-spesifikasi-empty').remove();
        } else if ($('#tbl-spesifikasi-empty').length === 0) {
            $('#tbl-spesifikasi-body').append(
                '<tr id="tbl-spesifikasi-empty"><td colspan="9" class="text-center text-muted py-4 small">' +
                'Tiada item. Klik <strong>Tambah Item</strong> untuk mula.</td></tr>'
            );
        }
        updateItemPager();
    }

    function parseAmount(val) {
        return parseFloat(String(val).replace(/,/g, '')) || 0;
    }

    function formatAmount(n) {
        return Number(n).toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function recalcItemJumlah($row) {
        var qty = parseAmount($row.find('[name="kuantiti"]').val());
        var kadar = parseAmount($row.find('[name="kadar"]').val());
        $row.find('[name="jumlah"]').val(formatAmount(qty * kadar));
        updateJumlahKeseluruhan();
    }

    function updateJumlahKeseluruhan() {
        var total = 0;
        $('#tbl-spesifikasi-body tr.spec-row').each(function () {
            total += parseAmount($(this).find('[name="jumlah"]').val());
        });
        $('#jumlah-keseluruhan').text(formatAmount(total));
    }

    function applyUnitRule($row) {
        var unit = $row.find('[name="unit"]').val();
        var $qty = $row.find('[name="kuantiti"]');
        if (unit === 'HB' || unit === 'L/S') {
            $qty.val(1).prop('readonly', true);
        } else {
            $qty.prop('readonly', false);
        }
        recalcItemJumlah($row);
    }

    function uploadFile(itemUuid, file, $chips, $tr, fileType) {
        var formData = new FormData();
        formData.append('file', file);
        formData.append('user_id', USER_ID);
        formData.append('file_type', fileType || 'support');
        if (itemUuid) formData.append('item_uuid', itemUuid);

        var $btn = $tr ? $tr.find('.upload-btn-row') : null;
        if ($btn) $btn.prop('disabled', true);

        $.ajax({
            url: UPLOAD_URL,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response && response.success && response.data) {
                    renderFileChip(response.data, $chips);
                    showToast('Fail berjaya dimuat naik.', 'success');
                } else {
                    showToast('Gagal memuat naik fail.', 'danger');
                }
            },
            error: function () {
                showToast('Gagal memuat naik fail. Sila cuba lagi.', 'danger');
            },
            complete: function () {
                if ($btn) $btn.prop('disabled', false);
            }
        });
    }

    function renderFileChip(fileData, $container) {
        var ext = (fileData.original_name || '').split('.').pop().toLowerCase();
        var fileHref = fileData.uuid
            ? (FILE_BASE + '/' + encodeURIComponent(fileData.uuid))
            : '#';
        var $chip = $('<div class="file-chip" data-file-uuid="' + fileData.uuid + '">' +
            '<span class="ext-badge">' + htmlEscape(ext) + '</span>' +
            '<a href="' + fileHref + '" target="_blank" title="' + htmlEscape(fileData.original_name) + '">' +
                htmlEscape(fileData.original_name) + '</a>' +
            '<button type="button" class="chip-delete" title="Padam fail">' +
                '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>' +
            '</button>' +
        '</div>');

        $chip.find('.chip-delete').on('click', function () {
            var ask = (typeof showConfirmModal === 'function')
                ? showConfirmModal({
                    title: 'Padam Fail',
                    message: 'Padam fail ini?',
                    confirmText: 'Ya, Padam',
                    icon: 'danger'
                })
                : Promise.resolve(window.confirm('Padam fail ini?'));

            ask.then(function (ok) {
                if (!ok) return;
                deleteFile(fileData.uuid, $chip);
            });
        });

        $container.append($chip);
    }

    function deleteFile(fileUuid, $chip) {
        $.ajax({
            url: DELETE_BASE + '/' + fileUuid,
            method: 'DELETE',
            success: function (response) {
                if (response && response.success) {
                    $chip.remove();
                    showToast('Fail berjaya dipadam.', 'success');
                } else {
                    showToast('Gagal memadam fail.', 'danger');
                }
            },
            error: function () {
                showToast('Gagal memadam fail. Sila cuba lagi.', 'danger');
            }
        });
    }

    function itemIsBlank($item) {
        if (($item.find('[name="nama_item"]').val() || '').trim()) return false;

        var blank = true;
        getItemSpecs($item).each(function () {
            var $spec = $(this);
            if (($spec.find('[name="spesifikasi"]').val() || '').trim()) blank = false;
            if ($spec.find('[name="unit"]').val()) blank = false;
            if ($spec.find('[name="ya_tidak"]').val()) blank = false;
            if (($spec.find('[name="catatan"]').val() || '').trim()) blank = false;
            if (parseAmount($spec.find('[name="kuantiti"]').val()) || parseAmount($spec.find('[name="kadar"]').val())) blank = false;
        });
        return blank;
    }

    function collectPayload() {
        var items = [];
        $('#tbl-spesifikasi-body tr.item-row').each(function () {
            var $item = $(this);
            if (itemIsBlank($item)) return;

            var specs = [];
            getItemSpecs($item).each(function (sIdx) {
                var $spec = $(this);
                specs.push({
                    uuid: $spec.data('uuid') || null,
                    spesifikasi: ($spec.find('[name="spesifikasi"]').val() || '').trim(),
                    unit: $spec.find('[name="unit"]').val() || null,
                    kuantiti: parseAmount($spec.find('[name="kuantiti"]').val()),
                    kadar: parseAmount($spec.find('[name="kadar"]').val()),
                    jumlah: parseAmount($spec.find('[name="jumlah"]').val()),
                    ya_tidak: $spec.find('[name="ya_tidak"]').val() || null,
                    catatan: ($spec.find('[name="catatan"]').val() || '').trim() || null,
                    sort_order: sIdx,
                });
            });
            items.push({
                uuid: $item.data('uuid') || null,
                nama_item: ($item.find('[name="nama_item"]').val() || '').trim(),
                unit: null,
                kuantiti: null,
                kadar: null,
                jumlah: null,
                sort_order: items.length,
                specs: specs,
            });
        });
        return items;
    }

    function validateBeforeSubmit() {
        var $items = $('#tbl-spesifikasi-body tr.item-row').filter(function () {
            return !itemIsBlank($(this));
        });
        if ($items.length === 0) {
            showToast('Sila tambah sekurang-kurangnya satu item sebelum menghantar.', 'danger');
            return false;
        }

        var valid = true;
        var messages = [];
        $items.each(function (idx) {
            var $item = $(this);
            var label = 'Item ' + (idx + 1);
            var $nama = $item.find('[name="nama_item"]');
            if (!$nama.val().trim()) {
                $nama.addClass('is-invalid');
                valid = false;
                messages.push(label + ': nama item diperlukan.');
            } else {
                $nama.removeClass('is-invalid');
            }

            var $specs = getItemSpecs($item);
            if ($specs.length === 0) {
                valid = false;
                messages.push(label + ': sekurang-kurangnya satu spesifikasi diperlukan.');
                return;
            }

            $specs.each(function (sIdx) {
                var $spec = $(this);
                var $ta = $spec.find('[name="spesifikasi"]');
                if (!$ta.val().trim()) {
                    $ta.addClass('is-invalid');
                    valid = false;
                    messages.push(label + ', spesifikasi ' + (sIdx + 1) + ': penerangan diperlukan.');
                } else {
                    $ta.removeClass('is-invalid');
                }

                var $unit = $spec.find('[name="unit"]');
                if (!$unit.val()) {
                    $unit.addClass('is-invalid');
                    valid = false;
                    messages.push(label + ', spesifikasi ' + (sIdx + 1) + ': unit diperlukan.');
                } else {
                    $unit.removeClass('is-invalid');
                }
            });
        });

        if (!valid) {
            showToast(messages[0] || 'Sila lengkapkan semua medan wajib sebelum menghantar.', 'danger');
        }
        return valid;
    }

    function saveDraft(callback) {
        var items = collectPayload();
        setBusy('#btn-simpan', true, 'Menyimpan...');

        $.ajax({
            url: SAVE_URL,
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ items: items, status: 'draft', user_id: USER_ID }),
            success: function (response) {
                if (response && response.success && response.data) {
                    renderFromServer(response.data, currentItemIndex);

                    if (typeof callback === 'function') {
                        callback(response);
                        return;
                    }

                    // Simpan draf: kekal di halaman yang sama (jangan redirect ke senarai).
                    showBerjayaModal({
                        message: 'Spesifikasi berjaya disimpan.',
                    });
                    setBusy('#btn-simpan', false, 'Simpan');
                } else {
                    showToast('Gagal menyimpan. Sila cuba lagi.', 'danger');
                    setBusy('#btn-simpan', false, 'Simpan');
                }
            },
            error: function (xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal menyimpan.';
                showToast(msg, 'danger');
                setBusy('#btn-simpan', false, 'Simpan');
            },
            complete: function () {
                if (typeof callback === 'function') {
                    setBusy('#btn-simpan', false, 'Simpan');
                }
            }
        });
    }

    function submitSpesifikasi() {
        if (!validateBeforeSubmit()) return;

        var ask = (typeof showConfirmModal === 'function')
            ? showConfirmModal({
                title: 'Sahkan Penghantaran',
                message: 'Hantar spesifikasi ini?',
                confirmText: 'Ya, Hantar',
                cancelText: 'Batal',
                icon: 'warning'
            })
            : Promise.resolve(window.confirm('Hantar spesifikasi ini?'));

        ask.then(function (ok) {
            if (!ok) return;

            saveDraft(function () {
                setBusy('#btn-hantar', true, 'Menghantar...');

                $.ajax({
                    url: SUBMIT_URL,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ user_id: USER_ID }),
                    success: function (response) {
                        if (response && response.success) {
                            showBerjayaModal({
                                message: 'Spesifikasi berjaya dihantar!',
                                onClose: function () {
                                    window.location.href = LIST_URL;
                                }
                            });
                        } else {
                            var msg = (response && response.message) ? response.message : 'Gagal menghantar.';
                            showToast(msg, 'danger');
                            setBusy('#btn-hantar', false, 'Hantar');
                        }
                    },
                    error: function (xhr) {
                        var errors = xhr.responseJSON && xhr.responseJSON.errors;
                        var msg = errors && errors.items ? errors.items[0] : 'Gagal menghantar. Sila cuba lagi.';
                        showToast(msg, 'danger');
                        setBusy('#btn-hantar', false, 'Hantar');
                    }
                });
            });
        });
    }

    function setBusy(selector, busy, label) {
        $(selector).prop('disabled', busy).text(label);
    }

    function showToast(message, type) {
        var colors = { success: '#dcfce7', danger: '#fee2e2', warning: '#fef9c3', info: '#e0f2fe' };
        var textColors = { success: '#166534', danger: '#991b1b', warning: '#854d0e', info: '#0c4a6e' };
        var $toast = $('<div style="background:' + (colors[type] || colors.info) +
            ';color:' + (textColors[type] || textColors.info) +
            ';border:1px solid;border-radius:8px;padding:0.75rem 1rem;margin-bottom:0.5rem;font-size:0.85rem;font-weight:500;box-shadow:0 4px 12px rgba(0,0,0,0.1);">' +
            message + '</div>');
        $('#toast-container').append($toast);
        setTimeout(function () { $toast.fadeOut(400, function () { $(this).remove(); }); }, 4000);
    }

    function htmlEscape(str) {
        return String(str)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    // ── Events ────────────────────────────────────────────────────────────────

    $('#btn-tambah-item').on('click', function () {
        $('#tbl-spesifikasi-empty').remove();
        $('#tbl-spesifikasi-body').append(buildItemRow({}));
        reindexGroups();
        updateJumlahKeseluruhan();
        showItemPage(getItemRows().length - 1);
    });

    $('#tbl-spesifikasi-body').on('click', '.btn-tambah-spec', function () {
        var $itemRow = $(this).closest('tr');
        var $specs = getItemSpecs($itemRow);
        var $after = $specs.length ? $specs.last() : $itemRow;
        $after.after(buildSpecRow({}));
        reindexGroups();
        showItemPage(getItemRows().index($itemRow));
    });

    $('#tbl-spesifikasi-body').on('click', '.btn-hapus-item', function () {
        var $item = $(this).closest('tr');
        var deletedIndex = getItemRows().index($item);
        var count = getItemSpecs($item).length;
        var msg = count > 0
            ? 'Hapus item ini beserta ' + count + ' spesifikasi di bawahnya?'
            : 'Hapus item ini?';

        var ask = (typeof showConfirmModal === 'function')
            ? showConfirmModal({
                title: 'Hapus Item',
                message: msg,
                confirmText: 'Ya, Hapus',
                icon: 'danger'
            })
            : Promise.resolve(window.confirm(msg));

        ask.then(function (ok) {
            if (!ok) return;
            getItemSpecs($item).remove();
            $item.remove();
            reindexGroups();
            syncEmpty();
            updateJumlahKeseluruhan();
            var remaining = getItemRows().length;
            if (remaining === 0) {
                currentItemIndex = 0;
                updateItemPager();
            } else {
                showItemPage(Math.min(deletedIndex, remaining - 1));
            }
        });
    });

    $('#tbl-spesifikasi-body').on('click', '.btn-hapus-spec', function () {
        var $row = $(this).closest('tr');
        var ask = (typeof showConfirmModal === 'function')
            ? showConfirmModal({
                title: 'Hapus Spesifikasi',
                message: 'Hapus spesifikasi ini?',
                confirmText: 'Ya, Hapus',
                icon: 'danger'
            })
            : Promise.resolve(window.confirm('Hapus spesifikasi ini?'));

        ask.then(function (ok) {
            if (!ok) return;
            $row.remove();
            reindexGroups();
            updateJumlahKeseluruhan();
            showItemPage(currentItemIndex);
        });
    });

    $('#btn-item-sebelum, #btn-item-sebelum-bottom').on('click', function () {
        navigateItemPage(currentItemIndex - 1);
    });

    $('#btn-item-seterusnya, #btn-item-seterusnya-bottom').on('click', function () {
        navigateItemPage(currentItemIndex + 1);
    });

    $('#tbl-spesifikasi-body').on('input', '[name="nama_item"], [name="spesifikasi"]', function () {
        $(this).removeClass('is-invalid');
        if ($(this).attr('name') === 'nama_item') {
            updateItemPager();
        }
    });

    $('#tbl-spesifikasi-body').on('change', '[name="unit"]', function () {
        $(this).removeClass('is-invalid');
    });

    $('#tbl-spesifikasi-body').on('change', '.unit-select', function () {
        applyUnitRule($(this).closest('tr'));
    });

    $('#tbl-spesifikasi-body').on('input', '.qty-input, .kadar-input', function () {
        recalcItemJumlah($(this).closest('tr'));
    });

    $('#tbl-spesifikasi-body').on('focus', '.kadar-input', function () {
        var raw = $(this).val().replace(/,/g, '');
        if (parseFloat(raw) === 0) raw = '';
        $(this).val(raw);
    });

    $('#tbl-spesifikasi-body').on('blur', '.kadar-input', function () {
        var val = $(this).val();
        if (val === '') return;
        $(this).val(formatAmount(parseAmount(val)));
        recalcItemJumlah($(this).closest('tr'));
    });

    $('#tbl-spesifikasi-body').on('input', '.kadar-input', function () {
        $(this).val($(this).val().replace(/[^\d.]/g, ''));
    });

    $('#input-bq-upload').on('change', function () {
        var files = this.files;
        if (!files || !files.length) return;
        $.each(files, function (i, file) {
            uploadFile(null, file, $('#bq-chips'), null, 'bq');
        });
        $(this).val('');
    });

    $('#btn-simpan').on('click', function () { saveDraft(); });
    $('#btn-hantar').on('click', function () { submitSpesifikasi(); });

    if (IS_SUBMITTED) {
        $('#btn-simpan, #btn-hantar, #btn-tambah-item').prop('disabled', true);
    }

});
</script>
--}}
<script>
$(function () {
    const uploadUrl = @json(route('penyediaanSpekTender.uploadFile', $tender->uuid));
    const deleteBase = @json(url('/penyediaan-spesifikasi-tender/fail'));
    const fileBase = @json(url('/penyediaan-spesifikasi-tender/' . $tender->uuid . '/fail'));
    const saveUrl = @json(route('penyediaanSpekTender.store', $tender->uuid));
    const submitUrl = @json(route('penyediaanSpekTender.submit', $tender->uuid));
    const listUrl = @json(route('pengurusanSpesifikasi'));
    const userId = @json(auth()->id());
    const isSubmitted = @json(($checklistData['status'] ?? null) === 'submitted');
    let templateFiles = (@json($checklistData['files'] ?? []))
        .filter(file => ['bq_template', 'bq'].includes(file.file_type || ''));
    templateFiles = templateFiles.filter(file => file.file_type === 'bq_template').slice(-1).concat(
        templateFiles.some(file => file.file_type === 'bq_template') ? [] : templateFiles.slice(-1)
    );
    let templateBusy = false;
    let actionBusy = false;

    function syncTemplateActions() {
        const locked = isSubmitted || templateBusy || actionBusy;
        $('#btn-simpan, #btn-hantar').prop('disabled', locked || !templateFiles.length);
        $('#input-bq-template, #btn-delete-bq-template').prop('disabled', locked);
    }

    function canProceedWithTemplate() {
        if (isSubmitted || templateBusy || actionBusy) return false;
        if (!templateFiles.length) {
            $('#bq-template-feedback').removeClass('text-success').addClass('text-danger')
                .text('Muat naik templat BQ sebelum menyimpan atau menghantar.');
            syncTemplateActions();
            return false;
        }
        return true;
    }
    const templateDialog = Swal.mixin({
        buttonsStyling: false,
        heightAuto: false,
        scrollbarPadding: false,
        showClass: {popup: 'bq-dialog-fade-in', backdrop: 'bq-backdrop-fade-in'},
        hideClass: {popup: 'bq-dialog-fade-out', backdrop: 'bq-backdrop-fade-out'},
        customClass: {
            container: 'bq-dialog-container',
            popup: 'bq-dialog',
            confirmButton: 'bq-dialog-button',
            cancelButton: 'bq-dialog-button bq-dialog-cancel'
        }
    });

    function escapeHtml(value) {
        return $('<div>').text(value || '').html();
    }

    function renderTemplate() {
        syncTemplateActions();
        const $container = $('#bq-template-file').empty();
        $('#bq-document-count').text(templateFiles.length ? '1 / 1 dokumen' : '0 / 1 dokumen');
        $('#bq-template-upload-label').text(templateFiles.length ? 'Ganti Templat BQ' : 'Pilih Templat BQ');
        if (!templateFiles.length) {
            $container.html('<p class="bq-upload-empty">Tiada dokumen dipilih. Pilih satu templat BQ untuk bermula.</p>');
            return;
        }
        const file = templateFiles[templateFiles.length - 1];
        const size = file.size ? (file.size / 1024 / 1024).toFixed(2) + ' MB' : '';
        const uploaded = file.created_at ? new Date(file.created_at).toLocaleString('ms-MY') : '';
        const href = fileBase + '/' + encodeURIComponent(file.uuid);
        $container.html(
            '<div class="bq-upload-file">' +
                '<span class="bq-file-symbol" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M8 13h8M8 17h5"/></svg></span>' +
                '<div class="bq-file-info"><a class="bq-file-name" target="_blank" rel="noopener" href="' + href + '">' + escapeHtml(file.original_name) + '</a>' +
                '<div class="bq-file-meta"><span>' + escapeHtml(size) + '</span><span>' + escapeHtml(uploaded) + '</span></div></div>' +
                '<div class="bq-file-actions"><a class="btn btn-sm btn-outline-secondary" download href="' + href + '"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12m-4-4 4 4 4-4M4 16v5h16v-5"/></svg>Muat Turun</a>' +
                '<button type="button" class="btn btn-sm btn-outline-danger" id="btn-delete-bq-template"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M9 6V3h6v3M5 6l1 15h12l1-15M10 10v7m4-7v7"/></svg>Padam</button></div>' +
            '</div>'
        );
    }

    renderTemplate();
    if (isSubmitted) {
        $('#input-bq-template, #btn-simpan, #btn-hantar').prop('disabled', true);
        $('#bq-template-upload-label').text('Templat telah dihantar');
        $('#bq-template-file').find('button').prop('disabled', true);
    }

    function uploadTemplate(files) {
        if (files && files.length > 1) {
            $('#bq-template-feedback').removeClass('text-success').addClass('text-danger').text('Hanya satu dokumen dibenarkan. Sila pilih satu templat BQ.');
            return;
        }
        const file = files && files[0];
        if (!file || isSubmitted || templateBusy || actionBusy) return;
        $('#bq-template-feedback').removeClass('text-danger text-success');
        if (!/\.(pdf|docx?|xlsx?)$/i.test(file.name)) {
            $('#bq-template-feedback').addClass('text-danger').text('Format fail tidak dibenarkan. Pilih PDF, Word atau Excel.');
            return;
        }
        if (file.size > 25 * 1024 * 1024) {
            $('#bq-template-feedback').addClass('text-danger').text('Saiz fail melebihi had 25 MB.');
            return;
        }
        const formData = new FormData();
        formData.append('file', file);
        formData.append('file_type', 'bq_template');
        formData.append('user_id', userId || '');
        templateBusy = true;
        $('#bq-upload-panel').attr('aria-busy', 'true');
        $('#btn-delete-bq-template, #btn-simpan, #btn-hantar').prop('disabled', true);
        const $input = $('#input-bq-template').prop('disabled', true);
        $('#bq-template-upload-label').text('Memuat naik...');
        $('#bq-template-feedback').removeClass('text-danger text-success').text('Sedang memuat naik...');
        fetch(uploadUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || $('meta[name="_token"]').attr('content') || ''
            }
        }).then(async response => {
            const payload = await response.json().catch(() => null);
            if (!response.ok || !payload?.success) {
                const statusMessages = {
                    401: 'Sesi anda telah tamat. Sila log masuk semula sebelum memuat naik.',
                    403: 'Anda tidak mempunyai kebenaran untuk memuat naik dokumen ini.',
                    413: 'Saiz muat naik melebihi had pelayan. Sila kecilkan fail atau hubungi pentadbir.',
                    419: 'Sesi anda telah tamat. Sila muat semula halaman dan cuba lagi.',
                    429: 'Terlalu banyak percubaan muat naik. Sila tunggu sebentar dan cuba lagi.'
                };
                const validationErrors = Object.values(payload?.errors || {}).flat().filter(message => typeof message === 'string');
                throw new Error(validationErrors.length
                    ? [...new Set(validationErrors)].join(' ')
                    : statusMessages[response.status] || (response.status >= 500
                        ? 'Pelayan mengalami ralat semasa memuat naik dokumen (HTTP ' + response.status + '). Sila cuba lagi atau hubungi pentadbir.'
                        : payload?.message || 'Gagal memuat naik templat (HTTP ' + response.status + '). Sila cuba lagi.'));
            }
            templateFiles = [payload.data];
            renderTemplate();
            $('#bq-template-feedback').addClass('text-success').text('Templat BQ berjaya dimuat naik.');
        }).catch(error => {
            const message = error instanceof TypeError
                ? 'Tidak dapat menghubungi pelayan. Sila semak sambungan internet dan cuba lagi.'
                : error.message || 'Gagal memuat naik templat. Sila cuba lagi.';
            $('#bq-template-feedback').removeClass('text-success').addClass('text-danger').text(message);
        }).finally(() => {
            templateBusy = false;
            $input.prop('disabled', isSubmitted);
            $('#bq-upload-panel').attr('aria-busy', 'false');
            syncTemplateActions();
            $('#bq-template-upload-label').text(templateFiles.length ? 'Ganti Templat BQ' : 'Pilih Templat BQ');
        });
    }

    $('#input-bq-template').on('change', function () {
        uploadTemplate(this.files);
        this.value = '';
    });

    let dragDepth = 0;
    $('#bq-upload-panel').on('dragenter dragover', function (event) {
        event.preventDefault();
        event.stopPropagation();
        if (isSubmitted || templateBusy || actionBusy) return;
        if (event.type === 'dragenter') dragDepth++;
        $(this).addClass('is-dragging');
    }).on('dragleave', function (event) {
        event.preventDefault();
        dragDepth = Math.max(0, dragDepth - 1);
        if (!dragDepth) $(this).removeClass('is-dragging');
    }).on('drop', function (event) {
        event.preventDefault();
        event.stopPropagation();
        dragDepth = 0;
        $(this).removeClass('is-dragging');
        if (!isSubmitted && !templateBusy && !actionBusy) uploadTemplate(event.originalEvent.dataTransfer.files);
    });
    $('#bq-template-file').on('click', '#btn-delete-bq-template', async function () {
        const file = templateFiles[templateFiles.length - 1];
        if (!file || isSubmitted || templateBusy || actionBusy) return;
        templateBusy = true;
        $('#input-bq-template, #btn-delete-bq-template, #btn-simpan, #btn-hantar').prop('disabled', true);
        try {
            const confirmation = await templateDialog.fire({
                title: 'Padam Templat BQ?',
                text: 'Dokumen "' + file.original_name + '" akan dipadam. Tindakan ini tidak boleh dibatalkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Padam',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true,
                heightAuto: false,
                scrollbarPadding: false
            });
            if (!confirmation.isConfirmed) return;
            $('#bq-template-feedback').removeClass('text-danger text-success').text('Sedang memadam templat...');
            const response = await fetch(deleteBase + '/' + encodeURIComponent(file.uuid), {
                method: 'DELETE',
                headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });
            const result = await response.json();
            if (!response.ok || !result.success) throw new Error(result.message || 'Gagal memadam templat.');
            templateFiles = templateFiles.filter(item => item.uuid !== file.uuid);
            renderTemplate();
            $('#bq-template-feedback').removeClass('text-danger').addClass('text-success').text('Templat BQ berjaya dipadam.');
            await templateDialog.fire({
                title: 'Berjaya!',
                text: 'Templat BQ berjaya dipadam.',
                icon: 'success',
                confirmButtonText: 'OK',
                heightAuto: false,
                scrollbarPadding: false
            });
        } catch (error) {
            const message = error.message || 'Gagal memadam templat.';
            $('#bq-template-feedback').removeClass('text-success').addClass('text-danger').text(message);
            await templateDialog.fire({
                title: 'Ralat',
                text: message,
                icon: 'error',
                confirmButtonText: 'Tutup',
                heightAuto: false,
                scrollbarPadding: false
            });
        } finally {
            templateBusy = false;
            syncTemplateActions();
        }
    });

    $('#btn-simpan').on('click', function () {
        if (!canProceedWithTemplate()) return;
        actionBusy = true;
        syncTemplateActions();
        const button = $(this).text('Menyimpan...');
        $.ajax({
            url: saveUrl, method: 'POST', contentType: 'application/json',
            data: JSON.stringify({status: 'draft', user_id: userId}),
            success: response => {
                if (!response || !response.success) {
                    $('#bq-template-feedback').addClass('text-danger').text(response.message || 'Gagal menyimpan.');
                    return;
                }
                $('#bq-template-feedback').removeClass('text-danger').addClass('text-success').text('Draf berjaya disimpan.');
            },
            error: xhr => $('#bq-template-feedback').addClass('text-danger').text(xhr.responseJSON?.message || 'Gagal menyimpan.'),
            complete: () => { actionBusy = false; button.text('Simpan'); syncTemplateActions(); }
        });
    });

    $('#btn-hantar').on('click', function () {
        if (!canProceedWithTemplate()) return;
        if (!window.confirm('Hantar templat BQ ini?')) return;
        actionBusy = true;
        syncTemplateActions();
        const button = $(this).text('Menghantar...');
        $.ajax({
            url: submitUrl, method: 'POST', contentType: 'application/json',
            data: JSON.stringify({user_id: userId}),
            success: response => {
                if (!response || !response.success) {
                    $('#bq-template-feedback').addClass('text-danger').text(response.message || 'Gagal menghantar.');
                    actionBusy = false;
                    button.text('Hantar');
                    syncTemplateActions();
                    return;
                }
                window.location.href = listUrl;
            },
            error: xhr => {
                $('#bq-template-feedback').addClass('text-danger').text(xhr.responseJSON?.message || 'Gagal menghantar.');
                actionBusy = false;
                    button.text('Hantar');
                    syncTemplateActions();
            }
        });
    });
});
</script>
@endsection
