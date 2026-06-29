@php
    $rows = $content['rows'] ?? [];
    $hasKerjaCols = collect($rows)->contains(fn ($row) => isset($row['ya_tidak']) || isset($row['catatan']));
    $hasDetailRows = collect($rows)->contains(fn ($row) => ($row['kind'] ?? '') === 'detail');
    $savedResponses = $dok['vendor_content']['specification'] ?? [];
    $standalone = $standalone ?? false;
    $canEdit = ($mode ?? '') === 'vendor' && ($vendorCanEdit ?? false);
    $tableId = $standalone ? 'tbl-specifikasi' : 'tbl-specifikasi-inline';
@endphp

@if (! $standalone && ! empty($content['document_title']))
    <div class="fw-semibold mb-2" style="font-size:0.84rem;">{{ $content['document_title'] }}</div>
@endif

<div class="table-responsive">
    <table id="{{ $tableId }}" class="table table-modern align-middle mb-0 w-100" style="font-size:0.82rem;">
        <thead>
            <tr>
                <th class="text-center py-3" style="width:55px;">Bil</th>
                <th class="py-3">Spesifikasi / Perkara</th>
                @unless ($hasKerjaCols)
                    <th class="text-center py-3" style="width:100px;">Kuantiti</th>
                    <th class="text-center py-3" style="width:90px;">Unit</th>
                @endunless
                @if ($hasKerjaCols)
                    <th class="text-center py-3" style="width:90px;">Ya/Tidak</th>
                    <th class="py-3">Catatan</th>
                @endif
                @if ($hasDetailRows)
                    <th class="py-3" style="min-width:{{ $standalone ? '260px' : '220px' }};">Maklum Balas Petender</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                @php
                    $isDetail = ($row['kind'] ?? '') === 'detail';
                    $responseType = $row['response_type'] ?? null;
                    $detailUuid = $row['detail_uuid'] ?? '';
                    $savedValue = $detailUuid !== '' ? ($savedResponses[$detailUuid] ?? '') : '';
                    $typeLabel = match ($responseType) {
                        'text' => 'Text',
                        'number' => 'Nombor',
                        'yes_no' => 'Ya/Tidak',
                        default => $responseType ? ucfirst(str_replace('_', ' ', $responseType)) : '',
                    };
                @endphp
                <tr class="{{ $isDetail ? 'spec-detail-row' : 'spec-item-row' }}">
                    <td class="text-center fw-semibold text-muted" style="font-size:0.8rem;">{{ $row['bil'] ?? '-' }}</td>
                    <td style="font-size:{{ $isDetail ? '0.82rem' : '0.85rem' }};">
                        @if ($isDetail)
                            <span class="text-muted me-1">↳</span>
                        @endif
                        {{ $row['title'] ?? '-' }}
                        @if ($isDetail && $typeLabel)
                            <span class="badge bg-light text-dark border spec-type-badge ms-1">{{ $typeLabel }}</span>
                        @endif
                    </td>
                    @unless ($hasKerjaCols)
                        <td class="text-center">{{ $isDetail ? '—' : ($row['quantity'] ?? '—') }}</td>
                        <td class="text-center text-uppercase">{{ $isDetail ? '—' : ($row['unit'] ?? '—') }}</td>
                    @endunless
                    @if ($hasKerjaCols)
                        <td class="text-center">{{ $row['ya_tidak'] ?? '—' }}</td>
                        <td>{{ $row['catatan'] ?? '—' }}</td>
                    @endif
                    @if ($hasDetailRows)
                        <td>
                            @if ($isDetail && $canEdit)
                                @if ($responseType === 'text')
                                    <textarea class="form-control form-control-sm dokumen-spec-input"
                                        data-detail-uuid="{{ $detailUuid }}"
                                        rows="{{ $standalone ? 2 : 2 }}"
                                        placeholder="Sila isi...">{{ $savedValue }}</textarea>
                                @elseif ($responseType === 'number')
                                    <input type="number" step="any"
                                        class="form-control form-control-sm dokumen-spec-input text-end"
                                        data-detail-uuid="{{ $detailUuid }}"
                                        value="{{ $savedValue }}"
                                        placeholder="0">
                                @elseif ($responseType === 'yes_no')
                                    <select class="form-select form-select-sm dokumen-spec-input"
                                        data-detail-uuid="{{ $detailUuid }}">
                                        <option value="">— Sila pilih —</option>
                                        <option value="yes" @selected($savedValue === 'yes')>Ya</option>
                                        <option value="no" @selected($savedValue === 'no')>Tidak</option>
                                    </select>
                                @else
                                    <input type="text"
                                        class="form-control form-control-sm dokumen-spec-input"
                                        data-detail-uuid="{{ $detailUuid }}"
                                        value="{{ $savedValue }}"
                                        placeholder="Sila isi...">
                                @endif
                            @elseif ($isDetail && ($mode ?? '') === 'admin')
                                @if ($savedValue !== '')
                                    <span class="fw-semibold">
                                        @if ($responseType === 'yes_no')
                                            {{ $savedValue === 'yes' ? 'Ya' : ($savedValue === 'no' ? 'Tidak' : $savedValue) }}
                                        @else
                                            {{ $savedValue }}
                                        @endif
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            @elseif ($isDetail)
                                <span class="text-muted">—</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ ($hasKerjaCols ? 4 : 4) + ($hasDetailRows ? 1 : 0) }}" class="text-center text-muted py-4">
                        Tiada data spesifikasi.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
