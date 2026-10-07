<!-- SECTION: DOKUMEN IKLAN (kaedah yang skip ke penyediaan iklan) -->
<div class="content-card mb-4 p-0">

    <div class="review-section-header">
        <div class="section-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                <polyline points="13 2 13 9 20 9"></polyline>
            </svg>
        </div>
        <div>
            <h6>{{ $tender->dokumenSenaraiTabLabel() }}</h6>
            <small>Nama dokumen dan lampiran untuk dimuat turun oleh syarikat</small>
        </div>
    </div>

    <div class="d-flex justify-content-end align-items-center gap-2 px-3 py-2 border-bottom bg-light">
        <button type="button" class="btn btn-sm btn-success" id="btnTambahIklanDokumen">
            Tambah Dokumen
        </button>
    </div>

    <input type="hidden" name="iklan_dokumen_present" value="1">

    <div class="table-responsive">
        <table class="table table-bordered mb-0" id="tblIklanDokumen" style="font-size:0.82rem;">
            <thead>
                <tr>
                    <th style="min-width:220px;">Nama Dokumen</th>
                    <th style="min-width:220px;">Lampiran</th>
                    <th style="width:90px;" class="text-center">Tindakan</th>
                </tr>
            </thead>
            <tbody id="iklanDokumenRows" data-next-index="{{ $tender->iklanDokumens->count() }}">
                @foreach ($tender->iklanDokumens as $index => $document)
                    <tr>
                        <td>
                            <input type="hidden" name="iklan_dokumen[{{ $index }}][id]" value="{{ $document->id }}">
                            <input type="text" class="form-control form-control-sm" name="iklan_dokumen[{{ $index }}][name]"
                                value="{{ $document->name }}" maxlength="255">
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <a href="{{ route('tenderIklanDokumen.download', [$tender->id, $document->id]) }}"
                                    class="small iklan-dokumen-current">{{ $document->original_name }}</a>
                                <input type="file" class="form-control form-control-sm" name="iklan_dokumen[{{ $index }}][file]"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">
                            </div>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger btn-buang-iklan-dokumen">Buang</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <p class="text-muted small px-3 py-2 mb-0">PDF, Word, Excel atau imej. Saiz maksimum 20MB.</p>
</div>

<script>
    (function () {
        var rows = document.getElementById('iklanDokumenRows');
        var addButton = document.getElementById('btnTambahIklanDokumen');
        if (!rows || !addButton) return;

        function nextIndex() {
            var index = parseInt(rows.getAttribute('data-next-index') || '0', 10);
            rows.setAttribute('data-next-index', String(index + 1));
            return index;
        }

        function bindRemove(button) {
            button.addEventListener('click', function () {
                var row = button.closest('tr');
                if (row) row.remove();
            });
        }

        rows.querySelectorAll('.btn-buang-iklan-dokumen').forEach(bindRemove);

        function rowHtml(index, data) {
            data = data || {};
            var idField = data.id
                ? '<input type="hidden" name="iklan_dokumen[' + index + '][id]" value="' + data.id + '">'
                : '';
            var current = data.download_url
                ? '<a href="' + data.download_url + '" class="small iklan-dokumen-current">' + (data.original_name || 'Dokumen') + '</a>'
                : '';
            return '<tr>' +
                '<td>' + idField +
                    '<input type="text" class="form-control form-control-sm" name="iklan_dokumen[' + index + '][name]" value="' + (data.name || '') + '" maxlength="255">' +
                '</td>' +
                '<td><div class="d-flex flex-column gap-1">' + current +
                    '<input type="file" class="form-control form-control-sm" name="iklan_dokumen[' + index + '][file]" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">' +
                '</div></td>' +
                '<td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger btn-buang-iklan-dokumen">Buang</button></td>' +
                '</tr>';
        }

        addButton.addEventListener('click', function () {
            rows.insertAdjacentHTML('beforeend', rowHtml(nextIndex()));
            var button = rows.querySelector('tr:last-child .btn-buang-iklan-dokumen');
            if (button) bindRemove(button);
        });

        window.refreshIklanDokumenRows = function (documents) {
            rows.innerHTML = '';
            rows.setAttribute('data-next-index', '0');
            (documents || []).forEach(function (document) {
                rows.insertAdjacentHTML('beforeend', rowHtml(nextIndex(), {
                    id: document.id,
                    name: escapeHtml(document.name || ''),
                    original_name: escapeHtml(document.original_name || ''),
                    download_url: document.download_url || ''
                }));
            });
            rows.querySelectorAll('.btn-buang-iklan-dokumen').forEach(bindRemove);
        };

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }
    })();
</script>
