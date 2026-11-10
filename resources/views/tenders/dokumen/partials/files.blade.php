@php
    $files = $content['files'] ?? [];
    $vendorFiles = $dok['vendor_content']['files'] ?? [];
    $itemUuid = $dok['uuid'] ?? '';
    $section = $dok['section'] ?? '';
    $tenderId = $tender->id ?? null;
    $showUpload = $mode === 'vendor' && $vendorCanEdit && in_array($action, ['vendor_upload', 'download_upload'], true);
    $bqTemplateMode = ! empty($content['bq_template_mode']);
    $bqOffer = $dok['vendor_content']['specification'] ?? [];
    $bqTempohUnit = match (strtolower(trim((string) ($tender->tempoh_siap_unit ?? 'bulan')))) {
        'hari', '3' => 'hari',
        'minggu', '1' => 'minggu',
        'bulan', '2' => 'bulan',
        default => 'bulan',
    };
    $showKeyInArea = false;
@endphp

@if (! empty($content['note']) && $mode === 'admin')
    <p class="text-muted mb-2" style="font-size:0.78rem;">{{ $content['note'] }}</p>
@endif

@if (count($files) > 0)
    <div class="mb-2">
        <div class="text-muted mb-1" style="font-size:0.72rem;font-weight:600;">Dokumen PTJ</div>
        <ul class="list-unstyled mb-0">
            @foreach ($files as $file)
                <li class="d-flex align-items-center gap-2 py-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                        stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                    <a href="{{ $file['url'] ?? '#' }}" target="_blank" class="small fw-semibold text-decoration-none">
                        {{ $file['name'] ?? 'Dokumen' }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@elseif ($mode === 'admin' && in_array($action, ['download_only', 'download_upload'], true))
    <p class="text-muted small mb-2">Tiada dokumen PTJ dimuat naik lagi.</p>
@endif

@if (count($vendorFiles) > 0 || $showUpload)
    <div class="mt-2 pt-2 border-top">
        <div class="d-flex align-items-center justify-content-between mb-1 dokumen-vendor-status-wrap">
            <div class="text-muted" style="font-size:0.72rem;font-weight:600;">
                {{ $mode === 'admin' ? 'Penyerahan Petender' : 'Dokumen Anda' }}
            </div>
            @if ($mode === 'vendor' && $vendorCanEdit && $bqTemplateMode)
                <span class="badge-status badge-status-success bq-vendor-detail-status {{ ($dok['vendor_status'] ?? '') === 'submitted' ? '' : 'd-none' }}" style="font-size:0.68rem;">Selesai</span>
            @elseif ($mode === 'vendor' && $vendorCanEdit && $action === 'download_upload')
                <span class="badge-status badge-status-success file-vendor-detail-status {{ ($dok['vendor_status'] ?? '') === 'submitted' ? '' : 'd-none' }}" style="font-size:0.68rem;">Selesai</span>
            @elseif ($mode === 'vendor' && ($dok['vendor_status'] ?? '') === 'submitted')
                <span class="badge-status badge-status-success" style="font-size:0.68rem;">Selesai</span>
            @endif
        </div>

        <ul class="list-unstyled mb-0 dokumen-vendor-file-list" data-item-uuid="{{ $itemUuid }}">
            @foreach ($vendorFiles as $file)
                <li class="d-flex align-items-center gap-2 py-1 dokumen-vendor-file-row" data-file-uuid="{{ $file['uuid'] }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                        stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                    <a href="{{ $file['url'] ?? '#' }}" target="_blank" class="small fw-semibold text-decoration-none flex-grow-1">
                        {{ $file['name'] ?? 'Dokumen' }}
                    </a>
                    @if ($showUpload)
                        <button type="button" class="btn btn-sm btn-link text-danger p-0 dokumen-delete-file"
                            data-file-uuid="{{ $file['uuid'] }}" title="Buang">Buang</button>
                    @endif
                </li>
            @endforeach
        </ul>

        @if ($showUpload)
            <div class="dokumen-upload-zone mt-2 {{ $action === 'download_upload' && count($vendorFiles) > 0 ? 'd-none' : '' }}" data-single-file="{{ $action === 'download_upload' ? '1' : '0' }}"
                data-upload-url="{{ route('tenderDokumen.upload', ['tender' => $tenderId, 'itemUuid' => $itemUuid]) }}"
                data-section="{{ $section }}"
                data-csrf="{{ csrf_token() }}">
                <label class="dokumen-upload-placeholder mb-0 d-block" style="cursor:pointer;">
                    <input type="file" class="d-none dokumen-file-input" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                        stroke="#94a3b8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="mb-2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" y1="3" x2="12" y2="15"></line>
                    </svg>
                    <div class="fw-semibold">Muat Naik Dokumen</div>
                    <div class="mt-1">PDF, Word, Excel atau imej (maks. 10MB{{ $action === 'download_upload' ? ', satu fail sahaja' : '' }})</div>
                </label>
                <div class="text-danger small mt-1 d-none dokumen-upload-error"></div>
            </div>
        @endif

        @if ($mode === 'vendor' && $vendorCanEdit && $bqTemplateMode)
            <div class="mt-3 pt-3 border-top bq-offer-form"
                data-save-url="{{ route('tenderDokumen.saveSpecification', ['tender' => $tenderId, 'itemUuid' => $itemUuid]) }}"
                data-section="{{ $section }}"
                data-csrf="{{ csrf_token() }}">
                <div class="fw-semibold small mb-2">Maklumat Tawaran</div>
                <div class="row g-2">
                    <div class="col-12 col-md-6">
                        <label class="form-label small mb-1" for="bq-total-{{ $itemUuid }}">Jumlah Harga Tawaran (RM)</label>
                        <input id="bq-total-{{ $itemUuid }}" type="number" min="0.01" step="0.01" required
                            class="form-control form-control-sm bq-total-input"
                            value="{{ $bqOffer['bq_total_tawaran'] ?? '' }}" placeholder="Contoh: 125000.00">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label small mb-1" for="bq-tempoh-{{ $itemUuid }}">Tempoh Siap Yang Ditawarkan</label>
                        <div class="input-group input-group-sm">
                            <input id="bq-tempoh-{{ $itemUuid }}" type="number" min="1" step="1" required
                                class="form-control bq-tempoh-input" value="{{ $bqOffer['vendor_tempoh_siap_val'] ?? '' }}" placeholder="Contoh: 90">
                            <span class="input-group-text">{{ ucfirst($bqTempohUnit) }}</span>
                            <input type="hidden" class="bq-tempoh-unit" value="{{ $bqTempohUnit }}">
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary mt-2 bq-offer-save">Simpan Maklumat Tawaran</button>
                <span class="small text-success ms-2 d-none bq-offer-success">Maklumat berjaya disimpan.</span>
                <div class="small text-danger mt-1 d-none bq-offer-error"></div>
                <div class="small text-muted mt-2">Maklumat ini digunakan untuk penilaian kewangan. Nilai dalam dokumen BQ tidak dibaca secara automatik.</div>
            </div>
        @endif
    </div>
@elseif ($mode === 'vendor' && $vendorCanEdit && $action === 'download_only' && count($files) > 0)
    <p class="text-muted small mt-2 mb-0">Sila muat turun dokumen PTJ di atas.</p>
@elseif ($action === 'serahan_tangan')
    <p class="text-muted small mt-2 mb-0">Dokumen ini perlu diserahkan secara serahan tangan.</p>
@elseif ($mode === 'admin')
    <p class="text-muted small mt-2 mb-0">Penyerahan petender akan dipaparkan selepas mereka memuat naik.</p>
@endif
