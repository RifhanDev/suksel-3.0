@php
    use App\Support\VendorCidbMeta;

    $cidbMeta = $cidbMeta ?? null;
    $comparisonId = $comparisonId ?? 'cidb-comparison';
    $diffRows = VendorCidbMeta::diffRows($cidbMeta);
    $groupedDiff = VendorCidbMeta::groupDiffRowsBySection($diffRows);
    $source1 = is_array($cidbMeta) ? ($cidbMeta['source1'] ?? null) : null;
    $source2 = is_array($cidbMeta) ? ($cidbMeta['source2'] ?? null) : null;
    $showComparison = is_array($cidbMeta) && VendorCidbMeta::hasDiff($cidbMeta);
    $comparisonAccordionId = $comparisonId . '-accordion';
@endphp

@if ($showComparison)
    <div class="cidb-comparison-panel" id="{{ $comparisonId }}" data-cidb-comparison>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 mb-3">
            <div>
                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Perbandingan Kemaskini Terakhir</label>
                <div class="small text-muted">
                    @if (! empty($source1['synced_at']))
                        Sebelum: {{ \Carbon\Carbon::parse($source1['synced_at'])->format('d M Y, H:i') }}
                    @else
                        Sebelum: Data asal
                    @endif
                    <span class="mx-1">→</span>
                    @if (! empty($source2['synced_at']))
                        Selepas: {{ \Carbon\Carbon::parse($source2['synced_at'])->format('d M Y, H:i') }}
                    @endif
                </div>
            </div>
            <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-3 py-2">
                {{ count($diffRows) }} perubahan
            </span>
        </div>

        <div class="accordion cidb-comparison-accordion" id="{{ $comparisonAccordionId }}">
            @foreach ($groupedDiff as $index => $group)
                @php
                    $collapseId = $comparisonId . '-collapse-' . $index;
                    $headingId = $comparisonId . '-heading-' . $index;
                @endphp
                <div class="accordion-item border rounded-3 mb-2 overflow-hidden">
                    <h2 class="accordion-header" id="{{ $headingId }}">
                        <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }} py-3" type="button"
                            data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}"
                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="{{ $collapseId }}">
                            <span class="fw-semibold">{{ $group['section_label'] }}</span>
                            <span class="badge rounded-pill bg-light text-secondary border ms-2">
                                {{ count($group['rows']) }}
                            </span>
                        </button>
                    </h2>
                    <div id="{{ $collapseId }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                        data-bs-parent="#{{ $comparisonAccordionId }}">
                        <div class="accordion-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm align-middle mb-0 cidb-comparison-table">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4 py-3 small text-uppercase text-muted">Medan</th>
                                            <th class="py-3 small text-uppercase text-muted">Sebelum</th>
                                            <th class="pe-4 py-3 small text-uppercase text-muted">Selepas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($group['rows'] as $row)
                                            <tr>
                                                <td class="ps-4 py-3">
                                                    <div class="fw-semibold text-dark">{{ $row['field_label'] }}</div>
                                                    @if (! empty($row['record_label']))
                                                        <div class="small text-muted">{{ $row['record_label'] }}</div>
                                                    @endif
                                                    <span
                                                        class="badge rounded-pill {{ VendorCidbMeta::changeTypeBadgeClass($row['type']) }} mt-1">
                                                        {{ VendorCidbMeta::changeTypeLabel($row['type']) }}
                                                    </span>
                                                </td>
                                                <td class="py-3 text-muted">
                                                    {{ VendorCidbMeta::formatDisplayValue($row['old']) }}
                                                </td>
                                                <td class="pe-4 py-3 bg-blue-selangor fw-semibold text-dark">
                                                    {{ VendorCidbMeta::formatDisplayValue($row['new']) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
