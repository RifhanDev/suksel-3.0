@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <style>
        #tbl-spesifikasi { border: 1px solid #e2e8f0; }
        #tbl-spesifikasi th, #tbl-spesifikasi td { border-right: 1px solid #e2e8f0 !important; }
        #tbl-spesifikasi th:last-child, #tbl-spesifikasi td:last-child { border-right: none !important; }
        .dokumen-chip-cell { min-width: 160px; max-width: 220px; }
        .file-chip {
            display: inline-flex; align-items: center; gap: 4px;
            background: #f1f5f9; border: 1px solid #e2e8f0;
            border-radius: 6px; padding: 2px 6px 2px 4px; font-size: 0.7rem;
            margin: 2px;
        }
        .file-chip .ext-badge {
            background: #64748b; color: #fff; border-radius: 3px;
            padding: 1px 4px; font-size: 0.6rem; font-weight: 700;
            text-transform: uppercase; flex-shrink: 0;
        }
        .file-chip a { color: #334155; font-weight: 600; max-width: 90px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block; }
        .file-chip .chip-delete {
            background: none; border: none; padding: 0;
            color: #94a3b8; cursor: pointer; line-height: 1;
        }
        .file-chip .chip-delete:hover { color: #ef4444; }
        .upload-btn-row { cursor: pointer; }
        .upload-btn-row.disabled-upload { opacity: 0.45; cursor: not-allowed; pointer-events: none; }
        #toast-container { position: fixed; top: 1.25rem; right: 1.25rem; z-index: 9999; min-width: 280px; }
    </style>
@endsection

@section('content')

    {{-- Toast container --}}
    <div id="toast-container"></div>

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Penyediaan Spesifikasi Tender</h3>
            <p class="text-muted small m-0">Isi maklumat spesifikasi bagi tender / sebutharga ini.</p>
        </div>
    </div>

    {{-- TENDER INFO CARD --}}
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

    {{-- SPECIFICATION TABLE CARD --}}
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
                    <p class="text-muted mb-0" style="font-size:0.78rem;">Tambah dan urus item spesifikasi tender ini</p>
                </div>
            </div>
        </div>
        <div class="content-card-body p-4">

            {{-- Toolbar --}}
            <div class="d-flex align-items-center justify-content-between mb-3">
                {{-- BQ upload — disabled per spec --}}
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1"
                        disabled title="Fungsi ini tidak tersedia buat masa ini.">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        Muat Naik Dokumen BQ / Spesifikasi
                    </button>
                </div>
                {{-- Add row button --}}
                <button type="button" id="btn-tambah-spesifikasi"
                    class="btn btn-sm btn-success d-inline-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Tambah Spesifikasi
                </button>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table id="tbl-spesifikasi" class="table table-modern align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th class="text-center py-3" style="width:50px;">Bil</th>
                            <th class="py-3" style="min-width:280px;">Spesifikasi</th>
                            <th class="text-center py-3" style="width:120px;">Ya / Tidak</th>
                            <th class="py-3" style="min-width:200px;">Catatan</th>
                            <th class="py-3 dokumen-chip-cell">Dokumen</th>
                            <th class="text-center py-3" style="width:60px;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody id="tbl-spesifikasi-body">
                        <tr id="tbl-spesifikasi-empty">
                            <td colspan="6" class="text-center text-muted py-4 small">
                                Tiada spesifikasi. Klik <strong>Tambah Spesifikasi</strong> untuk menambah baris.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- ACTION BUTTONS --}}
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
            <button type="button" id="btn-simpan" class="btn-form btn-form-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Simpan
            </button>
            <button type="button" id="btn-hantar" class="btn-form btn-form-success">
                Hantar
            </button>
        </div>
    </div>

@endsection

@section('scripts')
<script>
$(document).ready(function () {

    // ── CSRF Setup ─────────────────────────────────────────────────────────────
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // ── Constants (injected from PHP) ──────────────────────────────────────────
    const TENDER_UUID    = @json($tender->uuid);
    const SAVE_URL       = @json(route('penyediaanSpekTender.store', $tender->uuid));
    const SUBMIT_URL     = @json(route('penyediaanSpekTender.submit', $tender->uuid));
    const UPLOAD_URL     = @json(route('penyediaanSpekTender.uploadFile', $tender->uuid));
    const DELETE_BASE    = @json(url('/penyediaan-spesifikasi-tender/fail'));
    const USER_ID        = @json(auth()->id());

    // Server-provided existing data (null if first visit)
    var serverData = @json($checklistData ?? null);

    var rowSeq = 0; // counter for temp IDs on new rows

    // ── Init ───────────────────────────────────────────────────────────────────
    if (serverData && serverData.items && serverData.items.length > 0) {
        renderItems(serverData.items);
    }

    // ── Render existing items ──────────────────────────────────────────────────
    function renderItems(items) {
        $('#tbl-spesifikasi-empty').remove();
        items.forEach(function (item, idx) {
            var $tr = buildRow(idx + 1, item);
            $('#tbl-spesifikasi-body').append($tr);
        });
    }

    // ── Build a table row ──────────────────────────────────────────────────────
    function buildRow(bil, item) {
        item = item || {};
        var uuid        = item.uuid || '';
        var spesifikasi = item.spesifikasi || '';
        var yaTidak     = item.ya_tidak || '';
        var catatan     = item.catatan || '';
        var files       = item.files || [];

        var tempId   = 'row-' + (++rowSeq);
        var uploadId = 'upload-' + tempId;
        var chipId   = 'chips-' + tempId;

        // Upload button: disabled for unsaved rows (no uuid yet)
        var uploadDisabledClass = uuid ? '' : 'disabled-upload';
        var uploadTitle = uuid ? 'Muat naik dokumen sokongan' : 'Sila simpan terlebih dahulu sebelum memuat naik dokumen';

        var $tr = $(
            '<tr class="spesifikasi-row" data-uuid="' + uuid + '">' +
                '<td class="text-center row-bil fw-semibold text-muted" style="font-size:0.8rem;">' + bil + '</td>' +
                '<td style="vertical-align:top;padding-top:10px;">' +
                    '<textarea name="spesifikasi" class="form-control form-control-sm" rows="2" ' +
                    'placeholder="Masukkan spesifikasi..." style="resize:vertical;min-height:60px;">' + htmlEscape(spesifikasi) + '</textarea>' +
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
                    'placeholder="Catatan tambahan..." style="resize:vertical;min-height:60px;">' + htmlEscape(catatan) + '</textarea>' +
                '</td>' +
                '<td class="dokumen-chip-cell" style="vertical-align:top;padding-top:10px;">' +
                    '<div class="d-flex flex-column align-items-start gap-1">' +
                        '<label class="upload-btn-row ' + uploadDisabledClass + ' d-inline-flex align-items-center gap-1 btn btn-sm btn-outline-primary px-2 py-1 mb-1" ' +
                        'style="font-size:0.75rem;" title="' + uploadTitle + '" for="' + uploadId + '">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">' +
                                '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line>' +
                            '</svg>Muat Naik' +
                            '<input type="file" id="' + uploadId + '" multiple hidden ' +
                            'accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">' +
                        '</label>' +
                        '<div class="chips-container" id="' + chipId + '" style="display:flex;flex-wrap:wrap;gap:4px;"></div>' +
                    '</div>' +
                '</td>' +
                '<td class="text-center" style="vertical-align:middle;">' + deleteBtn() + '</td>' +
            '</tr>'
        );

        // Render existing files for this row
        files.forEach(function (f) {
            renderFileChip(f, $tr.find('#' + chipId));
        });

        // File input change → upload immediately
        $tr.find('#' + uploadId).on('change', function () {
            if (!uuid) { showToast('Sila simpan terlebih dahulu sebelum memuat naik dokumen.', 'warning'); return; }
            var files = this.files;
            if (!files || !files.length) return;
            var $chips = $tr.find('#' + chipId);
            $.each(files, function (i, file) {
                uploadFile(uuid, file, $chips, $tr);
            });
            $(this).val('');
        });

        return $tr;
    }

    // ── Upload file for a row ─────────────────────────────────────────────────
    function uploadFile(itemUuid, file, $chips, $tr) {
        var formData = new FormData();
        formData.append('file', file);
        formData.append('item_uuid', itemUuid);
        formData.append('user_id', USER_ID);

        var $btn = $tr.find('.upload-btn-row');
        $btn.prop('disabled', true);

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
                $btn.prop('disabled', false);
            }
        });
    }

    // ── Render a file chip ────────────────────────────────────────────────────
    function renderFileChip(fileData, $container) {
        var ext = fileData.original_name.split('.').pop().toLowerCase();
        var $chip = $('<div class="file-chip" data-file-uuid="' + fileData.uuid + '">' +
            '<span class="ext-badge">' + htmlEscape(ext) + '</span>' +
            '<a href="' + fileData.url + '" target="_blank" title="' + htmlEscape(fileData.original_name) + '">' +
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

    // ── Delete a file ─────────────────────────────────────────────────────────
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

    // ── Collect payload from table ────────────────────────────────────────────
    function collectPayload() {
        var items = [];
        $('#tbl-spesifikasi-body .spesifikasi-row').each(function (idx) {
            var $row = $(this);
            items.push({
                uuid:        $row.data('uuid') || null,
                spesifikasi: $row.find('[name="spesifikasi"]').val().trim(),
                ya_tidak:    $row.find('[name="ya_tidak"]').val() || null,
                catatan:     $row.find('[name="catatan"]').val().trim() || null,
                sort_order:  idx,
            });
        });
        return items;
    }

    // ── Validate before submit ────────────────────────────────────────────────
    function validateBeforeSubmit() {
        var rows = $('#tbl-spesifikasi-body .spesifikasi-row');
        if (rows.length === 0) {
            showToast('Sila tambah sekurang-kurangnya satu spesifikasi sebelum menghantar.', 'danger');
            return false;
        }
        var valid = true;
        rows.each(function () {
            var $ta = $(this).find('[name="spesifikasi"]');
            if (!$ta.val().trim()) {
                $ta.addClass('is-invalid');
                valid = false;
            } else {
                $ta.removeClass('is-invalid');
            }
        });
        if (!valid) {
            showToast('Sila lengkapkan semua medan Spesifikasi sebelum menghantar.', 'danger');
        }
        return valid;
    }

    // ── Save draft ────────────────────────────────────────────────────────────
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
                    // Re-render table with server UUIDs so file upload becomes available
                    $('#tbl-spesifikasi-body').empty();
                    renderItems(response.data.items);
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

    // ── Submit ────────────────────────────────────────────────────────────────
    function submitSpesifikasi() {
        if (!validateBeforeSubmit()) return;

        if (!confirm('Hantar spesifikasi ini?')) return;

        // Save first, then submit
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
                        updateStatusBadge('submitted');
                        setBusy('#btn-hantar', false, 'Hantar');
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

    // ── UI Helpers ────────────────────────────────────────────────────────────
    function updateStatusBadge(status) {
        var $badge = $('#status-badge');
        if (status === 'submitted') {
            $badge.attr('style', 'background:#dcfce7;color:#166534;font-size:0.8rem;border:1px solid #bbf7d0;')
                  .html('<span class="d-inline-block rounded-circle" style="width:7px;height:7px;background:#16a34a;flex-shrink:0;"></span> Telah Dihantar');
            $badge.addClass('d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2 fw-semibold');
        }
    }

    function reNumber() {
        $('#tbl-spesifikasi-body .spesifikasi-row').each(function (i) {
            $(this).find('.row-bil').text(i + 1);
        });
    }

    function syncEmpty() {
        var rowCount = $('#tbl-spesifikasi-body .spesifikasi-row').length;
        if (rowCount > 0) {
            $('#tbl-spesifikasi-empty').remove();
        } else if ($('#tbl-spesifikasi-empty').length === 0) {
            $('#tbl-spesifikasi-body').append(
                '<tr id="tbl-spesifikasi-empty"><td colspan="6" class="text-center text-muted py-4 small">' +
                'Tiada spesifikasi. Klik <strong>Tambah Spesifikasi</strong> untuk menambah baris.</td></tr>'
            );
        }
    }

    function setBusy(selector, busy, label) {
        var $btn = $(selector);
        $btn.prop('disabled', busy).text(busy ? label : label);
    }

    function showToast(message, type) {
        var colors = {
            success: '#dcfce7', danger: '#fee2e2', warning: '#fef9c3', info: '#e0f2fe'
        };
        var textColors = {
            success: '#166534', danger: '#991b1b', warning: '#854d0e', info: '#0c4a6e'
        };
        var bg   = colors[type]   || colors.info;
        var text = textColors[type] || textColors.info;

        var id = 'toast-' + Date.now();
        var $toast = $('<div id="' + id + '" style="background:' + bg + ';color:' + text + ';border:1px solid;border-radius:8px;padding:0.75rem 1rem;margin-bottom:0.5rem;font-size:0.85rem;font-weight:500;box-shadow:0 4px 12px rgba(0,0,0,0.1);">' +
            message + '</div>');
        $('#toast-container').append($toast);
        setTimeout(function () { $toast.fadeOut(400, function () { $(this).remove(); }); }, 4000);
    }

    function deleteBtn() {
        return '<button type="button" class="btn-hapus-spesifikasi d-inline-flex align-items-center justify-content-center p-0" ' +
            'style="width:28px;height:28px;border-radius:6px;background:#fee2e2;color:#ef4444;border:none;" title="Buang baris">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">' +
            '<polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>' +
            '<path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>' +
            '</svg></button>';
    }

    function htmlEscape(str) {
        return String(str)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    // ── Event Handlers ────────────────────────────────────────────────────────

    // Add row
    $('#btn-tambah-spesifikasi').on('click', function () {
        $('#tbl-spesifikasi-empty').remove();
        var bil = $('#tbl-spesifikasi-body .spesifikasi-row').length + 1;
        $('#tbl-spesifikasi-body').append(buildRow(bil, {}));
    });

    // Delete row
    $('#tbl-spesifikasi-body').on('click', '.btn-hapus-spesifikasi', function () {
        $(this).closest('tr').remove();
        reNumber();
        syncEmpty();
    });

    // Simpan button
    $('#btn-simpan').on('click', function () {
        saveDraft();
    });

    // Hantar button
    $('#btn-hantar').on('click', function () {
        submitSpesifikasi();
    });

    // Clear invalid state when user types
    $('#tbl-spesifikasi-body').on('input', '[name="spesifikasi"]', function () {
        $(this).removeClass('is-invalid');
    });

});
</script>
@endsection
