@php
    $rows = $content['rows'] ?? [];
    $isKerjaSpec = collect($rows)->contains(fn ($row) => ($row['kind'] ?? '') === 'spec')
        || (($dok['section'] ?? $dok['source'] ?? '') === 'spesifikasi_kerja');
    $hasKerjaCols = $isKerjaSpec || collect($rows)->contains(fn ($row) => array_key_exists('ya_tidak', $row) || array_key_exists('catatan', $row));
    $specData = $dok['vendor_content']['specification'] ?? [];
    if (is_array($specData) && (isset($specData['item_prices']) || isset($specData['details']))) {
        $itemPrices = $specData['item_prices'] ?? [];
        $detailResponses = $specData['details'] ?? [];
    } else {
        $itemPrices = [];
        $detailResponses = [];
        foreach ((array) $specData as $uuid => $value) {
            if (! is_string($uuid) || $value === null || $value === '') {
                continue;
            }
            $value = is_string($value) ? trim($value) : (string) $value;
            $detailResponses[$uuid] = in_array($value, ['yes', 'no'], true)
                ? ['pematuhan' => $value, 'cadangan' => '']
                : ['pematuhan' => '', 'cadangan' => $value];
        }
    }
    $standalone = $standalone ?? false;
    $canEdit = ($mode ?? '') === 'vendor' && ($vendorCanEdit ?? false);
    $canEditPematuhan = ($mode ?? '') === 'admin' && ! ($vendorCanEdit ?? false);
    $tableId = $standalone ? 'tbl-specifikasi' : 'tbl-specifikasi-inline';

    $groups = [];
    $current = null;
    foreach ($rows as $row) {
        $kind = $row['kind'] ?? 'item';
        if ($kind === 'item') {
            if ($current) {
                $groups[] = $current;
            }
            $current = ['item' => $row, 'details' => []];
        } elseif ($current) {
            $current['details'][] = $row;
        } else {
            $groups[] = ['item' => $row, 'details' => []];
        }
    }
    if ($current) {
        $groups[] = $current;
    }

    $initialTotal = 0.0;
    if ($isKerjaSpec) {
        foreach ($groups as $group) {
            foreach ($group['details'] as $detail) {
                $uuid = $detail['item_uuid'] ?? '';
                $qty = (float) ($detail['kuantiti'] ?? $detail['quantity'] ?? 0);
                $vendorKadar = $uuid !== '' && isset($itemPrices[$uuid]) && $itemPrices[$uuid] !== ''
                    ? (float) str_replace(',', '', (string) $itemPrices[$uuid])
                    : null;
                $ptjKadar = isset($detail['kadar']) && $detail['kadar'] !== null && $detail['kadar'] !== ''
                    ? (float) $detail['kadar']
                    : null;
                $kadar = $canEdit
                    ? ($vendorKadar ?? 0.0)
                    : ($vendorKadar ?? $ptjKadar ?? 0.0);
                $initialTotal += round($kadar * $qty, 2);
            }
        }
    } else {
        $initialTotal = collect($itemPrices)->sum(fn ($value) => is_numeric($value) ? (float) $value : 0);
    }
@endphp

@if (! $standalone && ! empty($content['document_title']))
    <div class="fw-semibold mb-2" style="font-size:0.84rem;">{{ $content['document_title'] }}</div>
@endif

@if ($isKerjaSpec)
<style>
    .spec-kerja-table { border: 1px solid #e2e8f0; }
    .spec-kerja-table th, .spec-kerja-table td { border-right: 1px solid #e2e8f0 !important; }
    .spec-kerja-table th:last-child, .spec-kerja-table td:last-child { border-right: none !important; }
    .spec-kerja-table tbody .group-alt td { background-color: #f8fafc; }
    .spec-kerja-table tbody .item-row td:first-child,
    .spec-kerja-table tbody .spec-row td:first-child { border-bottom: none !important; }
    .spec-kerja-table tbody .item-has-specs > td:first-child { position: relative; }
    .spec-kerja-table tbody .item-has-specs > td:first-child::before {
        content: ''; position: absolute; left: 10px; top: 50%;
        width: 7px; height: 1.5px; background: #cbd5e1; transform: translateY(-50%);
    }
    .spec-kerja-table tbody .item-has-specs > td:first-child::after {
        content: ''; position: absolute; left: 10px; top: 50%; bottom: 0;
        width: 1.5px; background: #cbd5e1;
    }
    .spec-kerja-table tbody .spec-row > td:first-child { position: relative; }
    .spec-kerja-table tbody .spec-row > td:first-child::before {
        content: ''; position: absolute; left: 10px; top: 0; bottom: 0;
        width: 1.5px; background: #cbd5e1;
    }
    .spec-kerja-table tbody .spec-row > td:first-child::after {
        content: ''; position: absolute; left: 10px; top: 50%; width: 18px;
        height: 1.5px; background: #cbd5e1; transform: translateY(-50%);
    }
    .spec-kerja-table tbody .spec-last > td:first-child::before { bottom: 50%; }
    .dokumen-chip-cell { min-width: 140px; max-width: 200px; }
    .file-chip {
        display: inline-flex; align-items: center; gap: 4px;
        background: #f1f5f9; border: 1px solid #e2e8f0;
        border-radius: 6px; padding: 2px 6px 2px 4px; font-size: 0.7rem; margin: 2px;
    }
    .file-chip .ext-badge {
        background: #64748b; color: #fff; border-radius: 3px;
        padding: 1px 4px; font-size: 0.6rem; font-weight: 700;
        text-transform: uppercase; flex-shrink: 0;
    }
    .file-chip a {
        color: #334155; font-weight: 600; max-width: 80px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;
    }
    .jumlah-total-bar {
        background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;
        padding: 0.75rem 1rem; max-width: 360px; margin-left: auto;
    }
    .spec-kerja-table .spec-ro,
    .spec-kerja-table .form-control[readonly],
    .spec-kerja-table .form-control:disabled {
        background-color: #f1f5f9 !important;
        color: #64748b;
        border-color: #e2e8f0;
        cursor: default;
        box-shadow: none;
    }
    .spec-kerja-table .dokumen-spec-kadar:not([readonly]) {
        background-color: #fff !important;
        color: #0f172a;
        border-color: #cbd5e1;
    }
</style>
@endif

<div class="table-responsive">
    <table id="{{ $tableId }}"
        class="table table-modern align-middle mb-0 w-100 spec-pricing-table {{ $isKerjaSpec ? 'spec-kerja-table' : '' }}"
        style="font-size:0.82rem;"
        data-spec-type="{{ $isKerjaSpec ? 'kerja' : 'bekalan' }}">
        @if ($isKerjaSpec)
            {{-- POV Syarikat: match Penyediaan Spesifikasi Tender table layout --}}
            <thead>
                <tr>
                    <th class="py-3" style="min-width:220px;">
                        Item
                        <div style="font-size:0.68rem;font-weight:600;text-transform:none;letter-spacing:0;color:#94a3b8;margin-top:2px;">
                            Spesifikasi
                        </div>
                    </th>
                    <th class="text-center py-3" style="width:100px;">Unit</th>
                    <th class="text-center py-3" style="width:100px;">Kuantiti</th>
                    <th class="py-3" style="min-width:160px;">Catatan</th>
                    <th class="py-3 dokumen-chip-cell">Dokumen</th>
                    <th class="text-center py-3" style="width:110px;">Kadar (RM)</th>
                    <th class="text-center py-3" style="width:110px;">Jumlah (RM)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($groups as $groupIndex => $group)
                    @php
                        $item = $group['item'];
                        $details = $group['details'];
                        $hasSpecs = count($details) > 0;
                        $groupAlt = $groupIndex % 2 === 1 ? 'group-alt' : '';
                    @endphp
                    <tr class="item-row {{ $hasSpecs ? 'item-has-specs' : '' }} {{ $groupAlt }}">
                        <td style="padding-left:28px;vertical-align:top;padding-top:10px;">
                            <textarea class="form-control form-control-sm spec-ro" rows="2" readonly tabindex="-1"
                                style="resize:none;min-height:52px;">{{ $item['title'] ?? '' }}</textarea>
                        </td>
                        <td class="text-center text-muted small">—</td>
                        <td class="text-center text-muted small">—</td>
                        <td class="text-center text-muted small">—</td>
                        <td class="text-center text-muted small">—</td>
                        <td class="text-center text-muted small">—</td>
                        <td class="text-center text-muted small">—</td>
                    </tr>
                    @foreach ($details as $detailIndex => $detail)
                        @php
                            $specUuid = $detail['item_uuid'] ?? '';
                            $qty = (float) ($detail['kuantiti'] ?? $detail['quantity'] ?? 0);
                            $qtyDisplay = $qty > 0 ? rtrim(rtrim(number_format($qty, 2, '.', ''), '0'), '.') : '';
                            $vendorKadar = ($specUuid !== '' && array_key_exists($specUuid, $itemPrices) && $itemPrices[$specUuid] !== '')
                                ? (string) $itemPrices[$specUuid]
                                : '';
                            // Vendor edit: only their offer. Read-only review: vendor offer, else PTJ/admin kadar.
                            $ptjKadar = isset($detail['kadar']) && $detail['kadar'] !== null && $detail['kadar'] !== ''
                                ? (string) $detail['kadar']
                                : '';
                            $savedKadar = $canEdit
                                ? $vendorKadar
                                : ($vendorKadar !== '' ? $vendorKadar : $ptjKadar);
                            $jumlah = $savedKadar !== ''
                                ? round(((float) str_replace(',', '', (string) $savedKadar)) * $qty, 2)
                                : 0;
                            $files = $detail['files'] ?? [];
                            $isLastSpec = $detailIndex === count($details) - 1;
                        @endphp
                        <tr class="spec-row {{ $isLastSpec ? 'spec-last' : '' }} {{ $groupAlt }}"
                            data-qty="{{ $qty }}">
                            <td style="padding-left:40px;vertical-align:top;padding-top:10px;">
                                <textarea class="form-control form-control-sm spec-ro" rows="2" readonly tabindex="-1"
                                    style="resize:none;min-height:52px;">{{ $detail['title'] ?? '' }}</textarea>
                            </td>
                            <td class="text-center" style="vertical-align:top;padding-top:10px;">
                                <input type="text" class="form-control form-control-sm text-center text-uppercase spec-ro"
                                    value="{{ $detail['unit'] ?: '' }}" readonly tabindex="-1"
                                    placeholder="—">
                            </td>
                            <td class="text-center" style="vertical-align:top;padding-top:10px;">
                                <input type="text" class="form-control form-control-sm text-center spec-ro"
                                    value="{{ $qtyDisplay }}" readonly tabindex="-1"
                                    placeholder="—">
                            </td>
                            <td style="vertical-align:top;padding-top:10px;">
                                <textarea class="form-control form-control-sm spec-ro" rows="2" readonly tabindex="-1"
                                    style="resize:none;min-height:52px;"
                                    placeholder="—">{{ $detail['catatan'] ?? '' }}</textarea>
                            </td>
                            <td class="dokumen-chip-cell" style="vertical-align:top;padding-top:10px;">
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse ($files as $file)
                                        @php
                                            $name = (string) ($file['name'] ?? 'Dokumen');
                                            $ext = strtoupper(pathinfo($name, PATHINFO_EXTENSION) ?: 'FILE');
                                        @endphp
                                        <span class="file-chip">
                                            <span class="ext-badge">{{ $ext }}</span>
                                            <a href="{{ $file['url'] ?? '#' }}" target="_blank" rel="noopener"
                                                title="{{ $name }}">{{ $name }}</a>
                                        </span>
                                    @empty
                                        <span class="text-muted small">—</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="text-center" style="vertical-align:top;padding-top:10px;">
                                @if ($canEdit && $specUuid !== '')
                                    <input type="text"
                                        class="form-control form-control-sm text-end dokumen-spec-kadar"
                                        data-item-uuid="{{ $specUuid }}"
                                        data-qty="{{ $qty }}"
                                        value="{{ $savedKadar !== '' ? number_format((float) str_replace(',', '', (string) $savedKadar), 2, '.', '') : '' }}"
                                        placeholder="0.00"
                                        inputmode="decimal">
                                @else
                                    <input type="text" class="form-control form-control-sm text-end spec-ro"
                                        value="{{ $savedKadar !== '' ? number_format((float) str_replace(',', '', (string) $savedKadar), 2) : '' }}"
                                        readonly tabindex="-1" placeholder="—">
                                @endif
                            </td>
                            <td class="text-center" style="vertical-align:top;padding-top:10px;">
                                <input type="text"
                                    class="form-control form-control-sm text-end dokumen-spec-jumlah spec-ro"
                                    data-item-uuid="{{ $specUuid }}"
                                    value="{{ number_format($jumlah, 2) }}"
                                    readonly tabindex="-1">
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4 small">Tiada data spesifikasi.</td>
                    </tr>
                @endforelse
            </tbody>
        @elseif ($hasKerjaCols)
            <thead>
                <tr>
                    <th class="text-center py-3" style="width:55px;">Bil</th>
                    <th class="py-3">Spesifikasi / Perkara</th>
                    <th class="text-center py-3" style="width:90px;">Ya/Tidak</th>
                    <th class="py-3">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $row)
                    <tr>
                        <td class="text-center fw-semibold text-muted">{{ $row['bil'] ?? '-' }}</td>
                        <td>{{ $row['title'] ?? '-' }}</td>
                        <td class="text-center">{{ $row['ya_tidak'] ?? '—' }}</td>
                        <td>{{ $row['catatan'] ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Tiada data spesifikasi.</td>
                    </tr>
                @endforelse
            </tbody>
        @else
            <thead>
                <tr>
                    <th class="py-3" style="min-width:220px;">
                        <span class="d-block">Item</span>
                        <span class="d-block fw-normal text-white-50" style="font-size:0.68rem;letter-spacing:0.03em;">Spesifikasi</span>
                    </th>
                    <th class="text-center py-3" style="width:95px;">
                        <span class="d-block">Kekerapan /</span>
                        <span class="d-block fw-normal text-white-50" style="font-size:0.68rem;">Kuantiti</span>
                    </th>
                    <th class="text-center py-3" style="width:90px;">
                        <span class="d-block">Unit</span>
                        <span class="d-block fw-normal text-white-50" style="font-size:0.68rem;">Ukuran</span>
                    </th>
                    <th class="text-center py-3" style="width:110px;">Pematuhan</th>
                    <th class="py-3" style="min-width:180px;">Cadangan Petender</th>
                    <th class="text-center py-3" style="width:130px;">
                        <span class="d-block">Tawaran</span>
                        <span class="d-block fw-normal text-white-50" style="font-size:0.68rem;">Harga</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($groups as $group)
                    @php
                        $item = $group['item'];
                        $details = $group['details'];
                        $itemUuid = $item['item_uuid'] ?? '';
                        $priceRowspan = max(1, count($details) + 1);
                        $savedPrice = $itemUuid !== '' ? ($itemPrices[$itemUuid] ?? '') : '';
                    @endphp
                    <tr class="spec-item-row">
                        <td>
                            <div class="spec-text-box">{{ $item['title'] ?? '-' }}</div>
                        </td>
                        <td class="text-center align-middle">{{ $item['quantity'] ?? '—' }}</td>
                        <td class="text-center align-middle text-uppercase">{{ $item['unit'] ?? '—' }}</td>
                        <td class="text-center text-muted">—</td>
                        <td class="text-center text-muted">—</td>
                        <td rowspan="{{ $priceRowspan }}" class="spec-price-cell align-middle text-end">
                            @if ($canEdit && $itemUuid !== '')
                                <input type="number" step="0.01" min="0"
                                    class="form-control form-control-sm dokumen-spec-price text-end"
                                    data-item-uuid="{{ $itemUuid }}"
                                    value="{{ $savedPrice }}"
                                    placeholder="0.00">
                            @elseif ($savedPrice !== '')
                                <span class="fw-semibold">{{ number_format((float) $savedPrice, 2) }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    @foreach ($details as $detail)
                        @php
                            $detailUuid = $detail['detail_uuid'] ?? '';
                            $savedDetail = $detailUuid !== '' ? ($detailResponses[$detailUuid] ?? []) : [];
                            $savedPematuhan = is_array($savedDetail) ? ($savedDetail['pematuhan'] ?? '') : '';
                            $savedCadangan = is_array($savedDetail) ? ($savedDetail['cadangan'] ?? '') : (string) $savedDetail;
                        @endphp
                        <tr class="spec-detail-row">
                            <td>
                                <div class="spec-text-box spec-text-box-sub">{{ $detail['title'] ?? '-' }}</div>
                            </td>
                            <td class="text-center text-muted">—</td>
                            <td class="text-center text-muted">—</td>
                            <td class="align-middle">
                                @if ($canEditPematuhan && $detailUuid !== '')
                                    <select class="form-select form-select-sm dokumen-spec-pematuhan"
                                        data-detail-uuid="{{ $detailUuid }}">
                                        <option value="">— Sila pilih —</option>
                                        <option value="yes" @selected($savedPematuhan === 'yes')>Ya</option>
                                        <option value="no" @selected($savedPematuhan === 'no')>Tidak</option>
                                    </select>
                                @elseif ($savedPematuhan !== '')
                                    <span class="fw-semibold spec-pematuhan-readonly">{{ $savedPematuhan === 'yes' ? 'Ya' : 'Tidak' }}</span>
                                @else
                                    <span class="text-muted small spec-pematuhan-readonly">Ya / Tidak</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                @if ($canEdit && $detailUuid !== '')
                                    <textarea class="form-control form-control-sm dokumen-spec-cadangan"
                                        data-detail-uuid="{{ $detailUuid }}"
                                        rows="2"
                                        placeholder="Sila isi cadangan...">{{ $savedCadangan }}</textarea>
                                @elseif ($savedCadangan !== '')
                                    <span>{{ $savedCadangan }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Tiada data spesifikasi.</td>
                    </tr>
                @endforelse
                @if (count($groups) > 0)
                    <tr class="spec-total-row">
                        <td colspan="5" class="text-end fw-bold py-3">Jumlah Tawaran Harga</td>
                        <td class="text-end fw-bold py-3">
                            <span id="spec-price-total" class="spec-price-total-value">{{ number_format($initialTotal, 2) }}</span>
                        </td>
                    </tr>
                @endif
            </tbody>
        @endif
    </table>
</div>

@if ($isKerjaSpec && count($groups) > 0)
    <div class="jumlah-total-bar mt-3 d-flex align-items-center justify-content-between">
        <span class="fw-semibold text-dark" style="font-size:0.85rem;">Jumlah Keseluruhan (RM)</span>
        <span class="fw-bold text-dark" id="spec-price-total" style="font-size:1rem;">{{ number_format($initialTotal, 2) }}</span>
    </div>
@endif
