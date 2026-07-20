@php
    $content = $dok['admin_content'] ?? [];
    $type = $content['type'] ?? null;
    $action = $dok['action'] ?? null;
@endphp

<div class="dokumen-content-box">
    @if ($mode === 'admin')
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-light text-dark border" style="font-size:0.68rem;">Pratonton Admin</span>
            @if (! empty($content['note']))
                <span class="text-muted" style="font-size:0.75rem;">{{ $content['note'] }}</span>
            @endif
        </div>
    @elseif (! $vendorCanEdit)
        <div class="alert alert-warning py-2 px-3 mb-2" style="font-size:0.78rem;">
            Sila beli dokumen tender untuk mengisi atau memuat naik.
        </div>
    @endif

    @if ($type === 'specification_table')
        @include('tenders.dokumen.partials.specification_card', [
            'content' => $content,
            'dok' => $dok,
            'tender' => $tender,
            'mode' => $mode,
            'vendorCanEdit' => $vendorCanEdit,
        ])
    @elseif ($type === 'files')
        @include('tenders.dokumen.partials.files', [
            'content' => $content,
            'action' => $action,
            'mode' => $mode,
            'vendorCanEdit' => $vendorCanEdit,
            'dok' => $dok,
            'tender' => $tender,
        ])
    @elseif ($type === 'online_form')
        @include('tenders.dokumen.partials.online_form', [
            'content' => $content,
            'mode' => $mode,
            'vendorCanEdit' => $vendorCanEdit,
        ])
    @elseif ($type === 'key_in')
        @include('tenders.dokumen.partials.key_in', [
            'dok' => $dok,
            'content' => $content,
            'mode' => $mode,
            'vendorCanEdit' => $vendorCanEdit,
            'tender' => $tender,
        ])
    @else
        <div class="text-muted small">Tiada butiran tambahan untuk item ini.</div>
    @endif
</div>
