@extends('layouts.v3.master')

@section('styles')
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
    </style>
@endsection

@section('content')

    <div id="toast-container"></div>

    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Penyediaan Spesifikasi Tender</h3>
            <p class="text-muted small m-0">Isi maklumat spesifikasi bagi tender / sebutharga ini.</p>
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
                    <h3 class="content-card-title mb-0" style="font-size:1rem;">Senarai Spesifikasi</h3>
                    <p class="text-muted mb-0" style="font-size:0.78rem;">Tambah item dan spesifikasi bagi tender ini</p>
                </div>
            </div>
        </div>
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

            <div class="jumlah-total-bar mt-3 d-flex align-items-center justify-content-between">
                <span class="fw-semibold text-dark" style="font-size:0.85rem;">Jumlah Keseluruhan (RM)</span>
                <span class="fw-bold text-dark" id="jumlah-keseluruhan" style="font-size:1rem;">0.00</span>
            </div>

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
            <button type="button" id="btn-simpan" class="btn-form btn-form-primary">Simpan</button>
            <button type="button" id="btn-hantar" class="btn-form btn-form-success">Hantar</button>
        </div>
    </div>

@endsection

@section('scripts')
<script>
$(document).ready(function () {

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    const LIST_URL       = @json(route('pengurusanSpesifikasi'));
    const SAVE_URL       = @json(route('penyediaanSpekTender.store', $tender->uuid));
    const SUBMIT_URL     = @json(route('penyediaanSpekTender.submit', $tender->uuid));
    const UPLOAD_URL     = @json(route('penyediaanSpekTender.uploadFile', $tender->uuid));
    const DELETE_BASE    = @json(url('/penyediaan-spesifikasi-tender/fail'));
    const USER_ID        = @json(auth()->id());
    const IS_SUBMITTED   = @json(($checklistData['status'] ?? null) === 'submitted');

    var serverData = @json($checklistData ?? null);
    var rowSeq = 0;

    if (serverData) {
        renderFromServer(serverData);
    }

    function renderFromServer(data) {
        var items = normalizeItems(data.items || []);
        $('#tbl-spesifikasi-body').empty();

        if (items.length === 0) {
            syncEmpty();
            updateJumlahKeseluruhan();
            return;
        }

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
                    unit: item.unit || '',
                    kuantiti: item.kuantiti != null ? item.kuantiti : (item.kekerapan != null ? item.kekerapan : ''),
                    kadar: item.kadar != null ? item.kadar : '',
                    specs: (item.specs || item.children || []).map(function (s) {
                        return {
                            uuid: s.uuid || '',
                            spesifikasi: s.spesifikasi || s.title || '',
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
                    unit: '',
                    kuantiti: '',
                    kadar: '',
                    specs: [{
                        uuid: i.uuid || '',
                        spesifikasi: i.spesifikasi || '',
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
                unit: p.unit || '',
                kuantiti: p.kuantiti != null ? p.kuantiti : '',
                kadar: p.kadar != null ? p.kadar : '',
                specs: children.map(function (s) {
                    return {
                        uuid: s.uuid || '',
                        spesifikasi: s.spesifikasi || '',
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
        var unit = item.unit || '';
        var kuantiti = item.kuantiti !== '' && item.kuantiti != null ? item.kuantiti : '';
        var kadar = item.kadar !== '' && item.kadar != null ? item.kadar : '';
        var locked = (unit === 'HB' || unit === 'L/S');
        if (locked) kuantiti = 1;

        var jumlah = formatAmount((parseFloat(kuantiti) || 0) * (parseFloat(kadar) || 0));

        var $tr = $(
            '<tr class="item-row" data-uuid="' + htmlEscape(uuid) + '">' +
                '<td style="padding-left:28px;vertical-align:top;padding-top:10px;">' +
                    '<textarea name="nama_item" class="form-control form-control-sm" rows="2" ' +
                    'placeholder="Tajuk item..." style="resize:vertical;min-height:52px;">' +
                    htmlEscape(item.nama_item || '') + '</textarea>' +
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
                '<td class="text-center text-muted small">—</td>' +
                '<td class="text-center text-muted small">—</td>' +
                '<td class="text-center text-muted small">—</td>' +
                '<td class="text-center" style="vertical-align:top;padding-top:10px;">' +
                    '<input type="text" name="kadar" class="form-control form-control-sm text-end kadar-input" ' +
                    'placeholder="0.00" value="' + (kadar !== '' ? formatAmount(parseFloat(kadar) || 0) : '') + '">' +
                '</td>' +
                '<td class="text-center" style="vertical-align:top;padding-top:10px;">' +
                    '<input type="text" name="jumlah" class="form-control form-control-sm text-end jumlah-input" ' +
                    'value="' + jumlah + '" readonly tabindex="-1">' +
                '</td>' +
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
                '<td class="text-center text-muted small">—</td>' +
                '<td class="text-center text-muted small">—</td>' +
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
                '<td class="text-center text-muted small">—</td>' +
                '<td class="text-center text-muted small">—</td>' +
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
    }

    function parseAmount(val) {
        return parseFloat(String(val).replace(/,/g, '')) || 0;
    }

    function formatAmount(n) {
        return Number(n).toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function recalcItemJumlah($itemRow) {
        var qty = parseAmount($itemRow.find('[name="kuantiti"]').val());
        var kadar = parseAmount($itemRow.find('[name="kadar"]').val());
        $itemRow.find('[name="jumlah"]').val(formatAmount(qty * kadar));
        updateJumlahKeseluruhan();
    }

    function updateJumlahKeseluruhan() {
        var total = 0;
        $('#tbl-spesifikasi-body tr.item-row').each(function () {
            total += parseAmount($(this).find('[name="jumlah"]').val());
        });
        $('#jumlah-keseluruhan').text(formatAmount(total));
    }

    function applyUnitRule($itemRow) {
        var unit = $itemRow.find('[name="unit"]').val();
        var $qty = $itemRow.find('[name="kuantiti"]');
        if (unit === 'HB' || unit === 'L/S') {
            $qty.val(1).prop('readonly', true);
        } else {
            $qty.prop('readonly', false);
        }
        recalcItemJumlah($itemRow);
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
        var $chip = $('<div class="file-chip" data-file-uuid="' + fileData.uuid + '">' +
            '<span class="ext-badge">' + htmlEscape(ext) + '</span>' +
            '<a href="' + (fileData.url || '#') + '" target="_blank" title="' + htmlEscape(fileData.original_name) + '">' +
                htmlEscape(fileData.original_name) + '</a>' +
            '<button type="button" class="chip-delete" title="Padam fail">' +
                '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>' +
            '</button>' +
        '</div>');

        $chip.find('.chip-delete').on('click', function () {
            if (!confirm('Padam fail ini?')) return;
            deleteFile(fileData.uuid, $chip);
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

    function collectPayload() {
        var items = [];
        $('#tbl-spesifikasi-body tr.item-row').each(function (idx) {
            var $item = $(this);
            var specs = [];
            getItemSpecs($item).each(function (sIdx) {
                var $spec = $(this);
                specs.push({
                    uuid: $spec.data('uuid') || null,
                    spesifikasi: ($spec.find('[name="spesifikasi"]').val() || '').trim(),
                    ya_tidak: $spec.find('[name="ya_tidak"]').val() || null,
                    catatan: ($spec.find('[name="catatan"]').val() || '').trim() || null,
                    sort_order: sIdx,
                });
            });
            items.push({
                uuid: $item.data('uuid') || null,
                nama_item: ($item.find('[name="nama_item"]').val() || '').trim(),
                unit: $item.find('[name="unit"]').val() || null,
                kuantiti: parseAmount($item.find('[name="kuantiti"]').val()),
                kadar: parseAmount($item.find('[name="kadar"]').val()),
                jumlah: parseAmount($item.find('[name="jumlah"]').val()),
                sort_order: idx,
                specs: specs,
            });
        });
        return items;
    }

    function validateBeforeSubmit() {
        var $items = $('#tbl-spesifikasi-body tr.item-row');
        if ($items.length === 0) {
            showToast('Sila tambah sekurang-kurangnya satu item sebelum menghantar.', 'danger');
            return false;
        }

        var valid = true;
        $items.each(function () {
            var $item = $(this);
            var $nama = $item.find('[name="nama_item"]');
            if (!$nama.val().trim()) {
                $nama.addClass('is-invalid');
                valid = false;
            } else {
                $nama.removeClass('is-invalid');
            }

            var $unit = $item.find('[name="unit"]');
            if (!$unit.val()) {
                $unit.addClass('is-invalid');
                valid = false;
            } else {
                $unit.removeClass('is-invalid');
            }

            var $specs = getItemSpecs($item);
            if ($specs.length === 0) {
                valid = false;
                showToast('Setiap item mesti mempunyai sekurang-kurangnya satu spesifikasi.', 'danger');
                return false;
            }

            $specs.each(function () {
                var $ta = $(this).find('[name="spesifikasi"]');
                if (!$ta.val().trim()) {
                    $ta.addClass('is-invalid');
                    valid = false;
                } else {
                    $ta.removeClass('is-invalid');
                }
            });
        });

        if (!valid) {
            showToast('Sila lengkapkan semua medan wajib sebelum menghantar.', 'danger');
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
                    renderFromServer(response.data);
                    showToast('Spesifikasi berjaya disimpan.', 'success');
                    if (typeof callback === 'function') callback(response);
                } else {
                    showToast('Gagal menyimpan. Sila cuba lagi.', 'danger');
                }
            },
            error: function (xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal menyimpan.';
                showToast(msg, 'danger');
            },
            complete: function () {
                setBusy('#btn-simpan', false, 'Simpan');
            }
        });
    }

    function submitSpesifikasi() {
        if (!validateBeforeSubmit()) return;
        if (!confirm('Hantar spesifikasi ini?')) return;

        saveDraft(function () {
            setBusy('#btn-hantar', true, 'Menghantar...');

            $.ajax({
                url: SUBMIT_URL,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ user_id: USER_ID }),
                success: function (response) {
                    if (response && response.success) {
                        showToast('Spesifikasi berjaya dihantar!', 'success');
                        setTimeout(function () {
                            window.location.href = LIST_URL;
                        }, 800);
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
    });

    $('#tbl-spesifikasi-body').on('click', '.btn-tambah-spec', function () {
        var $itemRow = $(this).closest('tr');
        var $specs = getItemSpecs($itemRow);
        var $after = $specs.length ? $specs.last() : $itemRow;
        $after.after(buildSpecRow({}));
        reindexGroups();
    });

    $('#tbl-spesifikasi-body').on('click', '.btn-hapus-item', function () {
        var $item = $(this).closest('tr');
        var count = getItemSpecs($item).length;
        var msg = count > 0
            ? 'Hapus item ini beserta ' + count + ' spesifikasi di bawahnya?'
            : 'Hapus item ini?';
        if (!confirm(msg)) return;
        getItemSpecs($item).remove();
        $item.remove();
        reindexGroups();
        syncEmpty();
        updateJumlahKeseluruhan();
    });

    $('#tbl-spesifikasi-body').on('click', '.btn-hapus-spec', function () {
        if (!confirm('Hapus spesifikasi ini?')) return;
        $(this).closest('tr').remove();
        reindexGroups();
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

    $('#tbl-spesifikasi-body').on('input', '[name="nama_item"], [name="spesifikasi"]', function () {
        $(this).removeClass('is-invalid');
    });
    $('#tbl-spesifikasi-body').on('change', '[name="unit"]', function () {
        $(this).removeClass('is-invalid');
    });

    if (IS_SUBMITTED) {
        $('#btn-simpan, #btn-hantar, #btn-tambah-item').prop('disabled', true);
    }

});
</script>
@endsection
