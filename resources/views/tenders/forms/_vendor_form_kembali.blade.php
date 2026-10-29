@php
    $kembaliUrl = $kembaliUrl ?? ($returnUrl ?? '#');
    $btnClass = $btnClass ?? 'btn-form btn-form-secondary';
    // When true, closing the iframe reloads the parent checklist so badges
    // (Belum Selesai → Selesai) refresh after AJAX saves inside the form.
    $reloadOnClose = (bool) ($reloadOnClose ?? false);
@endphp

@if ($modalEmbed ?? false)
    <button type="button" class="{{ $btnClass }}" onclick="vendorFormClose({{ $reloadOnClose ? 'true' : 'false' }})">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
        Tutup
    </button>
@else
    <a href="{{ $kembaliUrl }}" class="{{ $btnClass }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        Kembali
    </a>
@endif
