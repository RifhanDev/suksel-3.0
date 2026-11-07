@php
    $mode = $mode ?? 'admin';
    $tender = $tender ?? null;
    $vendorCanEdit = $vendorCanEdit ?? false;
    $vendorDokumenLockReason = $vendorDokumenLockReason ?? null;
@endphp

@include('tenders.dokumen.checklist', [
    'mode' => $mode,
    'tender' => $tender,
    'vendorCanEdit' => $vendorCanEdit,
    'vendorDokumenLockReason' => $vendorDokumenLockReason,
    'dokumenList' => $dokumenList ?? null,
    'tenderDokumen' => $tenderDokumen ?? null,
])
