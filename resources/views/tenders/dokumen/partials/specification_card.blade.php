@php
    $content = $content ?? ($dok['admin_content'] ?? []);
    $rows = $content['rows'] ?? [];
    $section = $dok['section'] ?? '';
    $isKerjaSpec = $section === 'spesifikasi_kerja'
        || collect($rows)->contains(fn ($row) => ($row['kind'] ?? '') === 'spec');
    $detailCount = $isKerjaSpec
        ? collect($rows)->where('kind', 'spec')->count()
        : collect($rows)->where('kind', 'detail')->count();
    $itemCount = collect($rows)->where('kind', 'item')->count();
    $itemUuid = $dok['uuid'] ?? '';
    $tenderId = $tender->id ?? null;
    $title = $content['document_title'] ?? ($dok['title'] ?? 'Spesifikasi');
    $status = $dok['vendor_status'] ?? 'draft';
    $isSubmitted = $status === 'submitted';

    $returnHash = ($mode ?? '') === 'admin' ? 'tf-dokumen-tawaran' : 'vt-dokumen-tawaran';
    $returnUrl = route('tenders.show', $tenderId) . '#' . $returnHash;

    $formUrl = route('tenderDokumen.specificationForm', ['tender' => $tenderId, 'itemUuid' => $itemUuid]);
    $formUrl .= '?modal=1&return=' . urlencode($returnUrl);
    if (($mode ?? '') === 'admin') {
        $formUrl .= '&mode=view';
    }
@endphp

<div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
    <div class="flex-grow-1">
        <div class="fw-semibold" style="font-size:0.84rem;">{{ $title }}</div>
        <div class="mt-2 d-flex flex-wrap align-items-center gap-2">
            <span class="badge-status {{ $isSubmitted ? 'badge-status-success' : 'badge-status-warning' }}">
                {{ $isSubmitted ? 'Selesai' : 'Belum Selesai' }}
            </span>
            @if ($detailCount > 0)
                <span class="text-muted" style="font-size:0.72rem;">
                    @if ($isKerjaSpec)
                        {{ $itemCount }} item · {{ $detailCount }} spesifikasi
                    @else
                        {{ $itemCount }} item · {{ $detailCount }} sub-spesifikasi
                    @endif
                </span>
            @endif
        </div>
        <div class="text-muted mt-2" style="font-size:0.78rem;">
            @if ($mode === 'vendor' && $vendorCanEdit)
                @if ($isKerjaSpec)
                    Sila isi Kadar (RM) bagi setiap spesifikasi. Jumlah dikira automatik (Kadar × Kuantiti).
                @else
                    Sila isi Pematuhan, Cadangan Petender (sub-item) dan Tawaran Harga (item utama).
                @endif
            @elseif ($mode === 'admin')
                Pratonton templat spesifikasi dan skema maklum balas petender.
            @else
                Sila beli dokumen tender untuk mengisi maklum balas spesifikasi.
            @endif
        </div>
    </div>
    <div class="d-flex flex-column align-items-stretch gap-1">
        @if ($mode === 'vendor' && $vendorCanEdit && $detailCount > 0)
            <button type="button" class="btn btn-sm btn-primary"
                data-online-form-modal
                data-form-url="{{ $formUrl }}"
                data-form-title="{{ $title }}"
                data-reload-on-complete="1"
                data-reload-hash="{{ $returnHash }}">
                {{ $isSubmitted ? 'Kemaskini Maklum Balas' : 'Isi Maklum Balas' }}
            </button>
        @elseif ($mode === 'admin' && $detailCount > 0)
            <button type="button" class="btn btn-sm btn-outline-secondary"
                data-online-form-modal
                data-form-url="{{ $formUrl }}"
                data-form-title="{{ $title }}"
                data-reload-on-complete="0"
                data-reload-hash="{{ $returnHash }}">
                Lihat Spesifikasi
            </button>
        @else
            <span class="text-muted small text-center">
                {{ $isKerjaSpec ? 'Tiada spesifikasi' : 'Tiada sub-spesifikasi' }}
            </span>
        @endif
        @if ($mode === 'vendor' && $vendorCanEdit && $isSubmitted)
            <span class="text-success text-center" style="font-size:0.72rem;">Maklum balas telah disimpan</span>
        @endif
    </div>
</div>
