{{-- Langkah 1 (Pematuhan Dokumentasi) only — no Pematuhan column, since this step only
     checks documents were submitted, not whether they comply. --}}
@php
    $rows = $content['rows'] ?? [];
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

<div class="table-responsive">
    <table id="tbl-specifikasi-dokumentasi" class="table table-bordered table-slate align-middle mb-0 w-100 spec-pricing-table" style="font-size:0.82rem;">
        <thead class="text-center">
            <tr>
                <th style="width:26%;">Item / Spesifikasi</th>
                <th style="width:12%;">Kekerapan / Kuantiti</th>
                <th style="width:10%;">Unit</th>
                <th style="width:37%;">Cadangan Petender</th>
                <th style="width:15%;">Tawaran Harga (RM)</th>
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
                    <td rowspan="{{ $priceRowspan }}" class="spec-price-cell align-middle text-center">
                        @if ($savedPrice !== '')
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
                        $savedCadangan = is_array($savedDetail) ? ($savedDetail['cadangan'] ?? '') : (string) $savedDetail;
                    @endphp
                    <tr class="spec-detail-row">
                        <td>
                            <div class="spec-text-box spec-text-box-sub">{{ $detail['title'] ?? '-' }}</div>
                        </td>
                        <td class="text-center text-muted">—</td>
                        <td class="text-center text-muted">—</td>
                        <td class="align-middle" style="white-space:normal; word-break:break-word;">
                            @if ($savedCadangan !== '')
                                <span>{{ $savedCadangan }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Tiada data spesifikasi.</td>
                </tr>
            @endforelse
            @if (count($groups) > 0)
                <tr class="spec-total-row">
                    <td colspan="4" class="text-end fw-bold py-3">Jumlah Tawaran Harga</td>
                    <td class="text-center fw-bold py-3">
                        <span class="spec-price-total-value">{{ number_format($initialTotal, 2) }}</span>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
