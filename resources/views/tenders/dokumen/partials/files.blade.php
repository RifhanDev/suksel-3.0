@php
    $files = $content['files'] ?? [];
    $vendorFiles = $dok['vendor_content']['files'] ?? [];
    $itemUuid = $dok['uuid'] ?? '';
    $section = $dok['section'] ?? '';
    $tenderId = $tender->id ?? null;
    $showUpload = $mode === 'vendor' && $vendorCanEdit && in_array($action, ['vendor_upload', 'download_upload'], true);
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
        <div class="d-flex align-items-center justify-content-between mb-1">
            <div class="text-muted" style="font-size:0.72rem;font-weight:600;">
                {{ $mode === 'admin' ? 'Penyerahan Petender' : 'Dokumen Anda' }}
            </div>
            @if ($mode === 'vendor' && ($dok['vendor_status'] ?? '') === 'submitted')
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
            <div class="dokumen-upload-zone mt-2"
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
                    <div class="mt-1">PDF, Word, Excel atau imej (maks. 10MB)</div>
                </label>
                <div class="text-danger small mt-1 d-none dokumen-upload-error"></div>
            </div>
        @endif
    </div>
@elseif ($mode === 'vendor' && $vendorCanEdit && $action === 'download_only' && count($files) > 0)
    <p class="text-muted small mt-2 mb-0">Sila muat turun dokumen PTJ di atas.</p>
@elseif ($mode === 'admin')
    <p class="text-muted small mt-2 mb-0">Penyerahan petender akan dipaparkan selepas mereka memuat naik.</p>
@endif
