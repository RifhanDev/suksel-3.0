@php
    $mode = $mode ?? 'admin';
    $tender = $tender ?? null;
    $vendorCanEdit = $vendorCanEdit ?? false;
@endphp

@include('tenders.dokumen.checklist', [
    'mode' => $mode,
    'tender' => $tender,
    'vendorCanEdit' => $vendorCanEdit,
    'dokumenList' => $dokumenList ?? null,
    'tenderDokumen' => $tenderDokumen ?? null,
])
