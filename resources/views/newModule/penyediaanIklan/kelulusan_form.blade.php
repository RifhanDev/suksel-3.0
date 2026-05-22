@push('styles')
<style>
    /* ── Kelulusan table ── */
    #tblKelulusan { margin: 0; font-size: 0.82rem; }
    #tblKelulusan thead th { background: #f8fafc; color: #64748b; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; border-bottom: 2px solid #e2e8f0; padding: 10px 12px; white-space: nowrap; vertical-align: middle; }
    #tblKelulusan tbody td { padding: 10px 12px; vertical-align: middle; border-color: #f1f5f9; }
    #tblKelulusan tbody tr:hover { background: #fafbfc; }
    #tblKelulusan tbody tr.kelulusan-fixed { background: #fafafa; }
    #tblKelulusan .fixed-label { font-size: 0.82rem; font-weight: 600; color: #1e293b; }

    /* ── Status select ── */
    .status-select { font-size: 0.8rem; border-radius: 6px; }
    .status-select.status-lulus { border-color: #22c55e; color: #15803d; background-color: #f0fdf4; }
    .status-select.status-tidak { border-color: #ef4444; color: #b91c1c; background-color: #fef2f2; }

    /* ── File upload mini ── */
    .upload-mini { display: inline-flex; align-items: center; gap: 6px; padding: 5px 10px; border: 1px dashed #cbd5e1; border-radius: 6px; cursor: pointer; font-size: 0.75rem; color: #64748b; background: #f8fafc; transition: border-color 0.15s, background 0.15s; white-space: nowrap; }
    .upload-mini:hover { border-color: #c41e3a; color: #c41e3a; background: #fef2f2; }
    .upload-mini input[type=file] { display: none; }
    .file-info-display { display: inline-flex; align-items: center; gap: 6px; font-size: 0.75rem; color: #334155; max-width: 160px; }
    .file-info-display .file-name-text { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 100px; }
    .btn-remove-file { display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; border-radius: 50%; border: none; background: #fee2e2; color: #b91c1c; cursor: pointer; flex-shrink: 0; padding: 0; line-height: 1; }
    .btn-remove-file:hover { background: #fecaca; }

    /* ── Row checkbox ── */
    .chk-row, #chkSelectAll { width: 15px; height: 15px; cursor: pointer; accent-color: #c41e3a; }

    /* ── Lock icon for fixed rows ── */
    .lock-icon { color: #cbd5e1; }
</style>
@endpush

<!-- SECTION: KELULUSAN -->
<div class="content-card mb-4 p-0">

    <div class="review-section-header">
        <div class="section-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        <div>
            <h6>Kelulusan</h6>
            <small>Senarai kelulusan yang diperlukan sebelum iklan diterbitkan</small>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="d-flex justify-content-end align-items-center gap-2 px-3 py-2 border-bottom bg-light">
        <button type="button" class="btn btn-sm btn-success" id="btnTambahKelulusan">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah
        </button>
        <button type="button" class="btn btn-sm btn-danger" id="btnHapusKelulusan" style="display:none;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                <path d="M10 11v6"></path>
                <path d="M14 11v6"></path>
                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
            </svg>
            Hapus
        </button>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered" id="tblKelulusan">
            <thead>
                <tr>
                    <th style="width:44px;" class="text-center">
                        <input type="checkbox" id="chkSelectAll" title="Pilih semua">
                    </th>
                    <th style="min-width:200px;">Jenis Kelulusan</th>
                    <th style="width:160px;">Status</th>
                    <th style="width:180px;">Dokumen</th>
                    <th style="min-width:160px;">Catatan</th>
                </tr>
            </thead>
            <tbody>

                {{-- row 1 --}}
                <tr class="kelulusan-fixed">
                    <td class="text-center lock-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </td>
                    <td>
                        <span class="fixed-label">Kelulusan Berbelanja</span>
                    </td>
                    <td>
                        <select class="form-select form-select-sm status-select" name="kelulusan_status[]">
                            <option value="">— Pilih —</option>
                            <option value="Diluluskan">Diluluskan</option>
                            <option value="Tidak Diluluskan">Tidak Diluluskan</option>
                        </select>
                    </td>
                    <td class="text-center">
                        <label class="upload-mini">
                            <input type="file" name="kelulusan_dokumen[]" accept=".pdf,.doc,.docx,.jpg,.png">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            Pilih Fail
                        </label>
                        <div class="file-info-display" style="display:none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                <polyline points="13 2 13 9 20 9"></polyline>
                            </svg>
                            <a class="file-name-text" href="#" target="_blank" style="color:#334155; text-decoration:underline dotted; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:100px;"></a>
                            <button type="button" class="btn-remove-file" title="Buang fail">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>
                    </td>
                    <td>
                        <textarea class="form-control form-control-sm" name="kelulusan_catatan[]" rows="2" placeholder="Catatan..."></textarea>
                    </td>
                </tr>

                {{-- row 2 --}}
                <tr class="kelulusan-fixed">
                    <td class="text-center lock-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </td>
                    <td>
                        <span class="fixed-label">Kelulusan Projek ICT</span>
                    </td>
                    <td>
                        <select class="form-select form-select-sm status-select" name="kelulusan_status[]">
                            <option value="">— Pilih —</option>
                            <option value="Diluluskan">Diluluskan</option>
                            <option value="Tidak Diluluskan">Tidak Diluluskan</option>
                        </select>
                    </td>
                    <td class="text-center">
                        <label class="upload-mini">
                            <input type="file" name="kelulusan_dokumen[]" accept=".pdf,.doc,.docx,.jpg,.png">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            Pilih Fail
                        </label>
                        <div class="file-info-display" style="display:none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                <polyline points="13 2 13 9 20 9"></polyline>
                            </svg>
                            <a class="file-name-text" href="#" target="_blank" style="color:#334155; text-decoration:underline dotted; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:100px;"></a>
                            <button type="button" class="btn-remove-file" title="Buang fail">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>
                    </td>
                    <td>
                        <textarea class="form-control form-control-sm" name="kelulusan_catatan[]" rows="2" placeholder="Catatan..."></textarea>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

</div>
<!-- End Kelulusan -->

@push('scripts')
<script>
$(document).ready(function () {

    /* ── Build a dynamic row ── */
    function buildKelulusanRow() {
        return `
            <tr class="kelulusan-dynamic">
                <td class="text-center">
                    <input type="checkbox" class="chk-row">
                </td>
                <td>
                    <textarea class="form-control form-control-sm" name="kelulusan_jenis[]" rows="2" placeholder="Masukkan jenis kelulusan..."></textarea>
                </td>
                <td>
                    <select class="form-select form-select-sm status-select" name="kelulusan_status[]">
                        <option value="">— Pilih —</option>
                        <option value="Diluluskan">Diluluskan</option>
                        <option value="Tidak Diluluskan">Tidak Diluluskan</option>
                    </select>
                </td>
                <td class="text-center">
                    <label class="upload-mini">
                        <input type="file" name="kelulusan_dokumen[]" accept=".pdf,.doc,.docx,.jpg,.png">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        Pilih Fail
                    </label>
                    <div class="file-info-display" style="display:none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                            <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                            <polyline points="13 2 13 9 20 9"></polyline>
                        </svg>
                        <span class="file-name-text"></span>
                        <button type="button" class="btn-remove-file" title="Buang fail">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                </td>
                <td>
                    <textarea class="form-control form-control-sm" name="kelulusan_catatan[]" rows="2" placeholder="Catatan..."></textarea>
                </td>
            </tr>`;
    }

    /* ── Tambah row ── */
    $('#btnTambahKelulusan').on('click', function () {
        $('#tblKelulusan tbody').append(buildKelulusanRow());
        syncHapusButton();
    });

    /* ── Hapus terpilih ── */
    $('#btnHapusKelulusan').on('click', function () {
        $('#tblKelulusan tbody tr.kelulusan-dynamic').each(function () {
            if ($(this).find('.chk-row').is(':checked')) {
                $(this).remove();
            }
        });
        $('#chkSelectAll').prop('checked', false);
        syncHapusButton();
    });

    /* ── Select all (only dynamic rows) ── */
    $('#chkSelectAll').on('change', function () {
        var checked = $(this).is(':checked');
        $('#tblKelulusan tbody tr.kelulusan-dynamic .chk-row').prop('checked', checked);
    });

    /* ── Individual checkbox → sync select-all state ── */
    $(document).on('change', '#tblKelulusan .chk-row', function () {
        var total   = $('#tblKelulusan tbody tr.kelulusan-dynamic .chk-row').length;
        var checked = $('#tblKelulusan tbody tr.kelulusan-dynamic .chk-row:checked').length;
        $('#chkSelectAll').prop('checked', total > 0 && total === checked);
    });

    /* ── Status colour ── */
    $(document).on('change', '#tblKelulusan .status-select', function () {
        var val = $(this).val();
        $(this).removeClass('status-lulus status-tidak');
        if (val === 'Diluluskan')       $(this).addClass('status-lulus');
        if (val === 'Tidak Diluluskan') $(this).addClass('status-tidak');
    });

    /* ── File selected: create blob URL, show as clickable link ── */
    $(document).on('change', '#tblKelulusan .upload-mini input[type=file]', function () {
        if (!this.files.length) return;
        var file    = this.files[0];
        var blobUrl = URL.createObjectURL(file);
        var $td     = $(this).closest('td');
        $td.find('.file-name-text').text(file.name).attr('href', blobUrl);
        $td.find('.upload-mini').hide();
        $td.find('.file-info-display').show();
    });

    /* ── Remove file: revoke blob URL, show upload button again ── */
    $(document).on('click', '#tblKelulusan .btn-remove-file', function () {
        var $td  = $(this).closest('td');
        var $link = $td.find('.file-name-text');
        var old  = $link.attr('href');
        if (old && old !== '#') URL.revokeObjectURL(old);
        $td.find('.upload-mini input[type=file]').val('');
        $link.text('').attr('href', '#');
        $td.find('.file-info-display').hide();
        $td.find('.upload-mini').show();
    });

    /* ── Show/hide Hapus button based on dynamic rows ── */
    function syncHapusButton() {
        var hasDynamic = $('#tblKelulusan tbody tr.kelulusan-dynamic').length > 0;
        hasDynamic ? $('#btnHapusKelulusan').show() : $('#btnHapusKelulusan').hide();
    }

});
</script>
@endpush
