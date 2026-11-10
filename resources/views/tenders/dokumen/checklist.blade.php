@php
    $mode = $mode ?? 'admin';
    $tender = $tender ?? null;
    $vendorCanEdit = $vendorCanEdit ?? false;
    $vendorDokumenLockReason = $vendorDokumenLockReason ?? null;
    $vendorId = ($vendorCanEdit && auth()->check() && auth()->user()->vendor_id)
        ? (int) auth()->user()->vendor_id
        : null;
    $dokumenList = $dokumenList
        ?? ($tenderDokumen ? $tenderDokumen->items($mode === 'vendor' ? 'vendor' : 'admin', $vendorId) : []);
@endphp

<style>
    .dokumen-checklist-wrap .table-bordered > :not(caption) > * > * {
        border-color: #e2e8f0;
    }
    .dokumen-checklist-row {
        cursor: pointer;
        transition: background 0.15s ease, box-shadow 0.15s ease;
        background: #fff;
    }
    .dokumen-checklist-row:hover { background: #f8fafc; }
    .dokumen-checklist-row.is-open td {
        background: #dce5f0 !important;
        border-color: #b8c8da !important;
    }
    .dokumen-checklist-row.is-open td:first-child {
        box-shadow: inset 3px 0 0 #1e3a5f;
    }
    .dokumen-checklist-detail td {
        background: #eef2f7 !important;
        border-top: none !important;
        border-bottom: 2px solid #c5d0de !important;
        padding-top: 0.5rem !important;
        padding-bottom: 0.85rem !important;
    }
    .dokumen-checklist-chevron { transition: transform 0.2s; color: #64748b; }
    .dokumen-checklist-row.is-open .dokumen-checklist-chevron {
        transform: rotate(90deg);
        color: #1e3a5f;
    }
    .serahan-tangan-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        background: #b91c1c;
        color: #fde047;
        padding: 0.2rem 0.5rem;
        font-size: 0.62rem;
        font-weight: 700;
        line-height: 1.25;
        white-space: nowrap;
    }
    .dokumen-content-box {
        border: 1px solid #d4dde8;
        border-radius: 8px;
        background: #fff;
        padding: 0.85rem 1rem;
        box-shadow: 0 1px 4px rgba(15, 23, 42, 0.07);
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
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <span class="fw-semibold">{{ $dok['nama'] ?? $dok['title'] ?? '-' }}</span>
                            @if (!empty($dok['penghantaran_fizikal']))
                                <span class="serahan-tangan-badge">Serahan tangan diperlukan.</span>
                            @endif
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge-status {{ $dok['badge_class'] ?? 'badge-status-neutral' }}">
                            {{ $dok['tindakan'] ?? '-' }}
                        </span>
                        @if ($mode === 'vendor' && $vendorCanEdit && !empty($dok['admin_content']['bq_template_mode']))
                            <div class="mt-1 bq-vendor-status {{ ($dok['vendor_status'] ?? '') === 'submitted' ? '' : 'd-none' }}">
                                <span class="badge-status badge-status-success" style="font-size:0.65rem;">Selesai</span>
                            </div>
                        @elseif ($mode === 'vendor' && $vendorCanEdit && ($dok['action'] ?? '') === 'download_upload')
                            <div class="mt-1 file-vendor-status {{ ($dok['vendor_status'] ?? '') === 'submitted' ? '' : 'd-none' }}">
                                <span class="badge-status badge-status-success" style="font-size:0.65rem;">Selesai</span>
                            </div>
                        @elseif ($mode === 'vendor' && $vendorCanEdit && ($dok['vendor_status'] ?? '') === 'submitted')
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
                                'vendorDokumenLockReason' => $vendorDokumenLockReason,
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

        function syncBqCompletionBadge(detailRow) {
            if (!detailRow) return;

            var offerPanel = detailRow.querySelector('.bq-offer-form');
            var fileList = detailRow.querySelector('.dokumen-vendor-file-list');
            var total = offerPanel && offerPanel.querySelector('.bq-total-input');
            var tempoh = offerPanel && offerPanel.querySelector('.bq-tempoh-input');
            var hasBqFile = !!(fileList && fileList.querySelector('.dokumen-vendor-file-row'));
            var hasValidOffer = !!(total && tempoh
                && total.checkValidity() && Number(total.value) > 0
                && tempoh.checkValidity() && Number(tempoh.value) > 0);
            var isComplete = hasBqFile && hasValidOffer;
            var itemRow = detailRow.previousElementSibling;
            var rowBadge = itemRow && itemRow.querySelector('.bq-vendor-status');
            var detailBadge = detailRow.querySelector('.bq-vendor-detail-status');

            if (rowBadge) rowBadge.classList.toggle('d-none', !isComplete);
            if (detailBadge) detailBadge.classList.toggle('d-none', !isComplete);
        }

        function syncFileCompletionBadge(detailRow) {
            if (!detailRow) return;

            var fileList = detailRow.querySelector('.dokumen-vendor-file-list');
            var uploadZone = detailRow.querySelector('.dokumen-upload-zone[data-single-file="1"]');
            var hasFile = !!(fileList && fileList.querySelector('.dokumen-vendor-file-row'));
            var itemRow = detailRow.previousElementSibling;
            var rowBadge = itemRow && itemRow.querySelector('.file-vendor-status');
            var detailBadge = detailRow.querySelector('.file-vendor-detail-status');

            if (rowBadge) rowBadge.classList.toggle('d-none', !hasFile);
            if (detailBadge) detailBadge.classList.toggle('d-none', !hasFile);
            if (uploadZone) {
                uploadZone.classList.toggle('d-none', hasFile);
                uploadZone.removeAttribute('data-uploading');
                var fileInput = uploadZone.querySelector('.dokumen-file-input');
                if (fileInput) fileInput.disabled = hasFile;
            }
        }

        document.addEventListener('click', function (e) {
            if (e.target.closest('.dokumen-upload-zone, .dokumen-key-in-form, .dokumen-delete-file, .dokumen-spec-form, a, button, [data-online-form-modal], [data-vendor-form-modal]')) {
                return;
            }
            var row = e.target.closest('[data-dokumen-toggle]');
            if (!row) return;
            var targetId = row.getAttribute('data-dokumen-toggle');
            var detail = document.getElementById(targetId);
            if (!detail) return;

            var wrap = row.closest('.dokumen-checklist-wrap');
            var isOpen = !detail.classList.contains('d-none');

            if (wrap) {
                wrap.querySelectorAll('.dokumen-checklist-detail:not(.d-none)').forEach(function (openDetail) {
                    if (openDetail.id !== targetId) {
                        openDetail.classList.add('d-none');
                    }
                });
                wrap.querySelectorAll('.dokumen-checklist-row.is-open').forEach(function (openRow) {
                    if (openRow !== row) {
                        openRow.classList.remove('is-open');
                    }
                });
            }

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

            if (zone.getAttribute('data-single-file') === '1') {
                if (zone.getAttribute('data-uploading') === '1') return;
                zone.setAttribute('data-uploading', '1');
                input.disabled = true;
            }

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
                var icon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                icon.setAttribute('width', '13');
                icon.setAttribute('height', '13');
                icon.setAttribute('viewBox', '0 0 24 24');
                icon.setAttribute('fill', 'none');
                icon.setAttribute('stroke', '#16a34a');
                icon.setAttribute('stroke-width', '2');
                icon.innerHTML = '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline>';
                var link = document.createElement('a');
                link.href = f.url;
                link.target = '_blank';
                link.rel = 'noopener';
                link.className = 'small fw-semibold text-decoration-none flex-grow-1';
                link.textContent = f.name;
                var remove = document.createElement('button');
                remove.type = 'button';
                remove.className = 'btn btn-sm btn-link text-danger p-0 dokumen-delete-file';
                remove.setAttribute('data-file-uuid', f.uuid);
                remove.textContent = 'Buang';
                li.append(icon, link, remove);
                list.appendChild(li);
                syncBqCompletionBadge(zone.closest('.dokumen-checklist-detail'));
                syncFileCompletionBadge(zone.closest('.dokumen-checklist-detail'));
            })
            .catch(function (err) {
                if (zone.getAttribute('data-single-file') === '1') {
                    zone.removeAttribute('data-uploading');
                    input.disabled = false;
                }
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
            var detailRow = row && row.closest('.dokumen-checklist-detail');
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
                syncBqCompletionBadge(detailRow);
                syncFileCompletionBadge(detailRow);
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
                syncBqCompletionBadge(offerPanel.closest('.dokumen-checklist-detail'));
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

        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.bq-offer-save');
            if (!btn) return;
            e.preventDefault();
            e.stopPropagation();

            var offerPanel = btn.closest('.bq-offer-form');
            var total = offerPanel.querySelector('.bq-total-input');
            var tempoh = offerPanel.querySelector('.bq-tempoh-input');
            var unit = offerPanel.querySelector('.bq-tempoh-unit');
            var okEl = offerPanel.querySelector('.bq-offer-success');
            var errEl = offerPanel.querySelector('.bq-offer-error');

            if (!total.checkValidity()) {
                total.reportValidity();
                return;
            }
            if (!tempoh.checkValidity()) {
                tempoh.reportValidity();
                return;
            }
            if (okEl) okEl.classList.add('d-none');
            if (errEl) { errEl.classList.add('d-none'); errEl.textContent = ''; }
            btn.disabled = true;

            fetch(offerPanel.getAttribute('data-save-url'), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': offerPanel.getAttribute('data-csrf'),
                },
                body: JSON.stringify({
                    section: offerPanel.getAttribute('data-section'),
                    bq_total_tawaran: total.value,
                    vendor_tempoh_siap_val: tempoh.value,
                    vendor_tempoh_siap_unit: unit.value,
                }),
            })
            .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, json: j }; }); })
            .then(function (res) {
                if (!res.ok || !res.json.success) {
                    throw new Error((res.json && res.json.message) || 'Gagal menyimpan maklumat tawaran.');
                }
                if (okEl) okEl.classList.remove('d-none');
            })
            .catch(function (err) {
                if (errEl) {
                    errEl.textContent = err.message || 'Ralat menyimpan.';
                    errEl.classList.remove('d-none');
                }
            })
            .finally(function () { btn.disabled = false; });
        });

        document.querySelectorAll('.dokumen-checklist-detail').forEach(syncFileCompletionBadge);
    })();
</script>
