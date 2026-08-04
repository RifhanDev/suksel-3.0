@php
    $kelulusanRows = \App\Models\PenyediaanIklan::normalizeKelulusanRows(
        $meta['kelulusan'] ?? \App\Models\PenyediaanIklan::defaultKelulusan()
    );
@endphp

@push('styles')
<style>
    #tblKelulusan { margin: 0; font-size: 0.82rem; }
    #tblKelulusan thead th { background: #f8fafc; color: #64748b; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; border-bottom: 2px solid #e2e8f0; padding: 10px 12px; white-space: nowrap; vertical-align: middle; }
    #tblKelulusan tbody td { padding: 10px 12px; vertical-align: middle; border-color: #f1f5f9; }
    #tblKelulusan tbody tr:hover { background: #fafbfc; }
    #tblKelulusan tbody tr.kelulusan-fixed { background: #fafafa; }
    #tblKelulusan .fixed-label { font-size: 0.82rem; font-weight: 600; color: #1e293b; }
    .upload-mini { display: inline-flex; align-items: center; gap: 6px; padding: 5px 10px; border: 1px dashed #cbd5e1; border-radius: 6px; cursor: pointer; font-size: 0.75rem; color: #64748b; background: #f8fafc; transition: border-color 0.15s, background 0.15s; white-space: nowrap; }
    .upload-mini:hover { border-color: #c41e3a; color: #c41e3a; background: #fef2f2; }
    .upload-mini input[type=file] { display: none; }
    .file-info-display { display: inline-flex; align-items: center; gap: 6px; font-size: 0.75rem; color: #334155; max-width: 160px; }
    .file-info-display .file-name-text { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 100px; }
    .btn-remove-file { display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; border-radius: 50%; border: none; background: #fee2e2; color: #b91c1c; cursor: pointer; flex-shrink: 0; padding: 0; line-height: 1; }
    .chk-row, #chkSelectAll { width: 15px; height: 15px; cursor: pointer; accent-color: #c41e3a; }
    .lock-icon { color: #cbd5e1; }
</style>
@endpush

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

    <div class="d-flex justify-content-end align-items-center gap-2 px-3 py-2 border-bottom bg-light">
        <button type="button" class="btn btn-sm btn-success" id="btnTambahKelulusan">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah
        </button>
        <button type="button" class="btn btn-sm btn-danger" id="btnHapusKelulusan" style="display:none;">Hapus</button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered" id="tblKelulusan">
            <thead>
                <tr>
                    <th style="width:44px;" class="text-center"><input type="checkbox" id="chkSelectAll" title="Pilih semua"></th>
                    <th style="min-width:200px;">Jenis Kelulusan</th>
                    <th style="width:180px;">Dokumen</th>
                    <th style="min-width:160px;">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kelulusanRows as $idx => $row)
                    @php
                        $isFixed = !empty($row['is_fixed']);
                        $dokumen = $row['dokumen'] ?? null;
                    @endphp
                    <tr class="{{ $isFixed ? 'kelulusan-fixed' : 'kelulusan-dynamic' }}">
                        <td class="text-center {{ $isFixed ? 'lock-icon' : '' }}">
                            @if ($isFixed)
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            @else
                                <input type="checkbox" class="chk-row">
                            @endif
                        </td>
                        <td>
                            @if ($isFixed)
                                <span class="fixed-label">{{ $row['jenis'] }}</span>
                            @else
                                <textarea class="form-control form-control-sm" name="kelulusan_jenis[]" rows="2">{{ $row['jenis'] ?? '' }}</textarea>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($dokumen)
                                <input type="hidden" name="kelulusan_existing_dokumen[{{ $idx }}]" value="{{ $dokumen['path'] ?? '' }}">
                            @endif
                            <label class="upload-mini" @if($dokumen) style="display:none;" @endif>
                                <input type="file" name="kelulusan_dokumen[]" accept=".pdf,.doc,.docx,.jpg,.png">
                                Pilih Fail
                            </label>
                            <div class="file-info-display" @if(!$dokumen) style="display:none;" @endif>
                                <a class="file-name-text" href="{{ $dokumen['url'] ?? '#' }}" target="_blank">{{ $dokumen['original_name'] ?? '' }}</a>
                                <button type="button" class="btn-remove-file" title="Buang fail">&times;</button>
                            </div>
                        </td>
                        <td>
                            <textarea class="form-control form-control-sm" name="kelulusan_catatan[]" rows="2" placeholder="Catatan...">{{ $row['catatan'] ?? '' }}</textarea>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function () {
    function buildKelulusanRow() {
        return `<tr class="kelulusan-dynamic">
            <td class="text-center"><input type="checkbox" class="chk-row"></td>
            <td><textarea class="form-control form-control-sm" name="kelulusan_jenis[]" rows="2" placeholder="Masukkan jenis kelulusan..."></textarea></td>
            <td class="text-center">
                <label class="upload-mini"><input type="file" name="kelulusan_dokumen[]" accept=".pdf,.doc,.docx,.jpg,.png">Pilih Fail</label>
                <div class="file-info-display" style="display:none;"><span class="file-name-text"></span><button type="button" class="btn-remove-file">&times;</button></div>
            </td>
            <td><textarea class="form-control form-control-sm" name="kelulusan_catatan[]" rows="2"></textarea></td>
        </tr>`;
    }

    $('#btnTambahKelulusan').on('click', function () {
        $('#tblKelulusan tbody').append(buildKelulusanRow());
        $('#btnHapusKelulusan').show();
    });

    $('#btnHapusKelulusan').on('click', function () {
        $('#tblKelulusan tbody tr.kelulusan-dynamic').each(function () {
            if ($(this).find('.chk-row').is(':checked')) $(this).remove();
        });
        $('#chkSelectAll').prop('checked', false);
        if ($('#tblKelulusan tbody tr.kelulusan-dynamic').length === 0) $('#btnHapusKelulusan').hide();
    });

    $(document).on('change', '#tblKelulusan .upload-mini input[type=file]', function () {
        if (!this.files.length) return;
        var $td = $(this).closest('td');
        $td.find('input[name^="kelulusan_existing_dokumen"]').remove();
        $td.find('.file-name-text').text(this.files[0].name);
        $td.find('.upload-mini').hide();
        $td.find('.file-info-display').show();
    });

    $(document).on('click', '#tblKelulusan .btn-remove-file', function () {
        var $td = $(this).closest('td');
        $td.find('input[name^="kelulusan_existing_dokumen"]').remove();
        $td.find('.upload-mini input[type=file]').val('');
        $td.find('.upload-mini').show();
        $td.find('.file-info-display').hide();
    });

    if ($('#tblKelulusan tbody tr.kelulusan-dynamic').length > 0) $('#btnHapusKelulusan').show();
});
</script>
@endpush
