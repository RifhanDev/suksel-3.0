@php
    $mode = $mode ?? 'admin';
    $tender = $tender ?? null;
    $vendorCanEdit = $vendorCanEdit ?? false;
    $vendorId = ($vendorCanEdit && auth()->check() && auth()->user()->vendor_id)
        ? (int) auth()->user()->vendor_id
        : null;
    $dokumenList = $dokumenList
        ?? ($tenderDokumen ? $tenderDokumen->items($mode === 'vendor' ? 'vendor' : 'admin', $vendorId) : []);
@endphp

<style>
    .dokumen-checklist-row { cursor: pointer; transition: background 0.15s; }
    .dokumen-checklist-row:hover { background: #f8fafc; }
    .dokumen-checklist-row.is-open { background: #f1f5f9; }
    .dokumen-checklist-detail td { background: #fafbfc; border-top: none !important; }
    .dokumen-checklist-chevron { transition: transform 0.2s; color: #94a3b8; }
    .dokumen-checklist-row.is-open .dokumen-checklist-chevron { transform: rotate(90deg); }
    .dokumen-content-box {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        padding: 0.85rem 1rem;
    }
    .dokumen-upload-placeholder {
        border: 1.5px dashed #cbd5e1;
        border-radius: 8px;
        padding: 1.25rem;
        text-align: center;
        color: #64748b;
        font-size: 0.8rem;
        background: #f8fafc;
    }
</style>

<div class="table-responsive dokumen-checklist-wrap">
    <table class="table table-bordered mb-0" style="font-size:0.82rem;">
        <thead>
            <tr>
                <th style="width:52px;" class="text-center">No.</th>
                <th>Tender / Sebut Harga</th>
                <th style="width:200px;" class="text-center">Tindakan Oleh Petender</th>
                <th style="width:42px;"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dokumenList as $i => $dok)
                @php
                    $rowId = 'dokumen-row-' . ($dok['uuid'] ?? $i);
                    $hasDetail = ! empty($dok['admin_content']['type']);
                @endphp
                <tr class="dokumen-checklist-row {{ $hasDetail ? '' : 'pe-none' }}"
                    @if ($hasDetail) data-dokumen-toggle="{{ $rowId }}" @endif>
                    <td class="text-center text-muted">{{ $i + 1 }}</td>
                    <td>
                        <div class="fw-semibold">{{ $dok['nama'] ?? $dok['title'] ?? '-' }}</div>
                        @if (! empty($dok['section']))
                            <div class="text-muted" style="font-size:0.72rem;">
                                {{ ucfirst(str_replace('_', ' ', $dok['section'])) }}
                            </div>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge-status {{ $dok['badge_class'] ?? 'badge-status-neutral' }}">
                            {{ $dok['tindakan'] ?? '-' }}
                        </span>
                        @if ($mode === 'vendor' && $vendorCanEdit && ($dok['vendor_status'] ?? '') === 'submitted')
                            <div class="mt-1">
                                <span class="badge-status badge-status-success" style="font-size:0.65rem;">Selesai</span>
                            </div>
                        @endif
                    </td>
                    <td class="text-center align-middle">
                        @if ($hasDetail)
                            <svg class="dokumen-checklist-chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        @endif
                    </td>
                </tr>
                @if ($hasDetail)
                    <tr class="dokumen-checklist-detail d-none" id="{{ $rowId }}">
                        <td colspan="4" class="p-3">
                            @include('tenders.dokumen.partials.content', [
                                'dok' => $dok,
                                'mode' => $mode,
                                'vendorCanEdit' => $vendorCanEdit,
                                'tender' => $tender,
                            ])
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                        Tiada dokumen tender direkodkan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    (function () {
        if (window.__dokumenChecklistInit) return;
        window.__dokumenChecklistInit = true;

        document.addEventListener('click', function (e) {
            if (e.target.closest('.dokumen-upload-zone, .dokumen-key-in-form, .dokumen-delete-file, a')) {
                return;
            }
            var row = e.target.closest('[data-dokumen-toggle]');
            if (!row) return;
            var targetId = row.getAttribute('data-dokumen-toggle');
            var detail = document.getElementById(targetId);
            if (!detail) return;
            var isOpen = !detail.classList.contains('d-none');
            detail.classList.toggle('d-none', isOpen);
            row.classList.toggle('is-open', !isOpen);
        });

        document.addEventListener('change', function (e) {
            var input = e.target.closest('.dokumen-file-input');
            if (!input || !input.files || !input.files[0]) return;
            e.stopPropagation();

            var zone = input.closest('.dokumen-upload-zone');
            var list = zone.closest('.border-top, .mt-2')?.querySelector('.dokumen-vendor-file-list')
                || zone.parentElement.querySelector('.dokumen-vendor-file-list');
            var errEl = zone.querySelector('.dokumen-upload-error');
            var file = input.files[0];
            input.value = '';

            var fd = new FormData();
            fd.append('file', file);
            fd.append('section', zone.getAttribute('data-section'));
            fd.append('_token', zone.getAttribute('data-csrf'));

            if (errEl) { errEl.classList.add('d-none'); errEl.textContent = ''; }

            fetch(zone.getAttribute('data-upload-url'), {
                method: 'POST',
                body: fd,
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            })
            .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, json: j }; }); })
            .then(function (res) {
                if (!res.ok || !res.json.success) {
                    throw new Error((res.json && res.json.message) || 'Gagal memuat naik fail.');
                }
                var f = res.json.data;
                if (!list) return;
                var li = document.createElement('li');
                li.className = 'd-flex align-items-center gap-2 py-1 dokumen-vendor-file-row';
                li.setAttribute('data-file-uuid', f.uuid);
                li.innerHTML =
                    '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>' +
                    '<a href="' + f.url + '" target="_blank" class="small fw-semibold text-decoration-none flex-grow-1">' + f.name + '</a>' +
                    '<button type="button" class="btn btn-sm btn-link text-danger p-0 dokumen-delete-file" data-file-uuid="' + f.uuid + '">Buang</button>';
                list.appendChild(li);
            })
            .catch(function (err) {
                if (errEl) {
                    errEl.textContent = err.message || 'Ralat memuat naik.';
                    errEl.classList.remove('d-none');
                } else {
                    alert(err.message || 'Ralat memuat naik.');
                }
            });
        });

        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.dokumen-delete-file');
            if (!btn) return;
            e.preventDefault();
            e.stopPropagation();
            if (!confirm('Buang fail ini?')) return;

            var uuid = btn.getAttribute('data-file-uuid');
            var row = btn.closest('.dokumen-vendor-file-row');
            var deleteUrl = @json(url('tenders/dokumen-files')) + '/' + uuid;

            fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': @json(csrf_token()),
                },
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (!res.success) throw new Error(res.message || 'Gagal membuang fail.');
                if (row) row.remove();
            })
            .catch(function (err) { alert(err.message || 'Ralat.'); });
        });

        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.dokumen-key-in-save');
            if (!btn) return;
            e.preventDefault();
            e.stopPropagation();

            var form = btn.closest('.dokumen-key-in-form');
            var input = form.querySelector('.dokumen-key-in-input');
            var okEl = form.querySelector('.dokumen-key-in-success');
            var errEl = form.querySelector('.dokumen-key-in-error');

            if (okEl) okEl.classList.add('d-none');
            if (errEl) { errEl.classList.add('d-none'); errEl.textContent = ''; }

            fetch(form.getAttribute('data-save-url'), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': form.getAttribute('data-csrf'),
                },
                body: JSON.stringify({
                    value: input.value,
                    section: form.getAttribute('data-section'),
                }),
            })
            .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, json: j }; }); })
            .then(function (res) {
                if (!res.ok || !res.json.success) {
                    throw new Error((res.json && res.json.message) || 'Gagal menyimpan.');
                }
                if (okEl) okEl.classList.remove('d-none');
            })
            .catch(function (err) {
                if (errEl) {
                    errEl.textContent = err.message || 'Ralat menyimpan.';
                    errEl.classList.remove('d-none');
                } else {
                    alert(err.message || 'Ralat menyimpan.');
                }
            });
        });
    })();
</script>
