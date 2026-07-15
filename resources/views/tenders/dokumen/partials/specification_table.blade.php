@php
    $rows = $content['rows'] ?? [];
    $hasKerjaCols = collect($rows)->contains(fn ($row) => isset($row['ya_tidak']) || isset($row['catatan']));
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

    $initialTotal = collect($itemPrices)->sum(fn ($value) => is_numeric($value) ? (float) $value : 0);
@endphp

@if (! $standalone && ! empty($content['document_title']))
    <div class="fw-semibold mb-2" style="font-size:0.84rem;">{{ $content['document_title'] }}</div>
@endif

<div class="table-responsive">
    <table id="{{ $tableId }}" class="table table-modern align-middle mb-0 w-100 spec-pricing-table" style="font-size:0.82rem;">
        @if ($hasKerjaCols)
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
