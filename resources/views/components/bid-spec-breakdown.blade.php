{{--
    Reusable info icon + modal: child spesifikasi / harga bidaan breakdown.

    @include('components.bid-spec-breakdown', [
        'items' => $row['spec_items'] ?? [],
        'vendorName' => $row['vendor_name'] ?? null,
        'vendorId' => $row['vendor_id'] ?? null,
        'showPriceDiff' => false, // green/red when bidding finished
        'title' => 'Item Spesifikasi',
    ])
--}}
@php
    $items = collect($items ?? [])->values();
    $vendorName = $vendorName ?? null;
    $vendorId = $vendorId ?? null;
    $showPriceDiff = (bool) ($showPriceDiff ?? false);
    $title = $title ?? 'Item Spesifikasi';
    $trigger = $trigger ?? 'icon'; // icon | none (modal only, for JS tables)
    $modalSuffix = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($modalSuffix ?? ($vendorId ?? uniqid('spec'))));
    $modalId = 'bidSpecBreakdownModal-' . $modalSuffix;
    $hasItems = $items->isNotEmpty();
    $totalPrevious = $items->sum(function ($item) {
        $prev = $item['previous_price'] ?? null;

        return ($prev !== null && $prev !== '') ? (float) $prev : 0.0;
    });
    $totalBid = $items->sum(function ($item) {
        $bid = $item['bid_price'] ?? null;

        return ($bid !== null && $bid !== '') ? (float) $bid : 0.0;
    });
@endphp

@once
    <style>
        .bid-spec-breakdown-btn {
            width: 1.7rem;
            height: 1.7rem;
            padding: 0;
            border-radius: 999px;
            line-height: 1;
            flex-shrink: 0;
        }

        /* Keep modal centered even when trigger lives inside a table / overflow container */
        .bid-spec-breakdown-modal.modal {
            position: fixed !important;
        }

        .bid-spec-breakdown-modal .modal-dialog {
            max-width: 980px;
            width: calc(100% - 2rem);
            margin: 1.5rem auto;
        }

        .bid-spec-breakdown-modal .modal-content {
            border: 0;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 18px 48px rgba(15, 23, 42, 0.22);
        }

        .bid-spec-breakdown-modal .modal-header {
            background: linear-gradient(135deg, #2d3e84 0%, #3d529e 100%);
            color: #fff;
            border-bottom: 0;
            padding: 1rem 1.25rem;
        }

        .bid-spec-breakdown-modal .modal-header .btn-close {
            filter: invert(1) grayscale(1);
            opacity: 0.85;
        }

        .bid-spec-breakdown-modal .modal-title {
            font-size: 1.1rem;
            font-weight: 700;
        }

        .bid-spec-breakdown-modal .modal-subtitle {
            color: rgba(255, 255, 255, 0.82);
            font-size: 0.85rem;
        }

        .bid-spec-breakdown-modal .modal-body {
            padding: 0;
            background: #f8fafc;
        }

        .bid-spec-breakdown-modal .table {
            margin-bottom: 0;
            background: #fff;
        }

        .bid-spec-breakdown-modal .table thead th {
            background: #eef2ff !important;
            color: #1e293b !important;
            font-size: 0.84rem;
            font-weight: 700;
            vertical-align: middle;
            padding: 0.85rem 0.9rem;
            border-color: #e2e8f0 !important;
            white-space: nowrap;
        }

        .bid-spec-breakdown-modal .table td {
            font-size: 0.92rem;
            vertical-align: middle;
            padding: 0.85rem 0.9rem;
            border-color: #eef2f7 !important;
        }

        .bid-spec-breakdown-modal .table tfoot td {
            font-size: 0.95rem;
            font-weight: 700;
            vertical-align: middle;
            padding: 0.95rem 0.9rem;
            background: #f1f5f9 !important;
            border-color: #e2e8f0 !important;
        }

        .bid-spec-breakdown-modal .modal-footer {
            background: #fff;
            border-top: 1px solid #eef2f7;
            padding: 0.85rem 1.25rem;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.bid-spec-breakdown-modal').forEach(function(modalEl) {
                if (modalEl.parentElement !== document.body) {
                    document.body.appendChild(modalEl);
                }
            });
        });
    </script>
@endonce

@if ($trigger !== 'none')
<button type="button"
    class="btn btn-sm btn-outline-secondary bid-spec-breakdown-btn d-inline-flex align-items-center justify-content-center"
    data-bs-toggle="modal" data-bs-target="#{{ $modalId }}" @disabled(! $hasItems)
    title="{{ $title }}" aria-label="{{ $title }}">
    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <circle cx="12" cy="12" r="10"></circle>
        <line x1="12" y1="16" x2="12" y2="12"></line>
        <line x1="12" y1="8" x2="12.01" y2="8"></line>
    </svg>
</button>
@endif

@if ($hasItems)
    <div class="modal fade bid-spec-breakdown-modal" id="{{ $modalId }}" tabindex="-1"
        aria-labelledby="{{ $modalId }}-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0" id="{{ $modalId }}-label">{{ $title }}</h5>
                        @if ($vendorName || $vendorId)
                            <div class="modal-subtitle mt-1">
                                {{ $vendorName ?: 'Vendor' }}
                                @if ($vendorId)
                                    <span>· ID: {{ $vendorId }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 text-center align-middle">
                            <thead>
                                <tr>
                                    <th class="text-start" style="min-width:260px;">Spesifikasi</th>
                                    <th style="width:100px;">Kuantiti</th>
                                    <th style="width:110px;">Unit</th>
                                    <th style="width:150px;">Harga Sebelum (RM)</th>
                                    <th style="width:160px;">Harga Bidaan (RM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    @php
                                        $prev = $item['previous_price'] ?? null;
                                        $bid = $item['bid_price'] ?? null;
                                        $isNew = $showPriceDiff && !empty($item['is_new_bid']);
                                        $isOld = $showPriceDiff && empty($item['is_new_bid']);
                                        $bidClass = $isNew ? 'text-success fw-semibold' : ($isOld ? 'text-danger fw-semibold' : 'fw-semibold');
                                        $bidBg = $isNew ? '#e8f7ef' : ($isOld ? '#fdebec' : '');
                                        $bidLabel = $isNew ? 'Harga baharu' : ($isOld ? 'Harga lama' : null);
                                    @endphp
                                    <tr>
                                        <td class="text-start">
                                            {{ $item['spesifikasi'] ?? '-' }}
                                        </td>
                                        <td>{{ ($item['kuantiti'] ?? '') !== '' ? $item['kuantiti'] : '—' }}</td>
                                        <td>{{ $item['unit_ukuran'] ?? '—' }}</td>
                                        <td>
                                            {{ $prev !== null && $prev !== '' ? number_format((float) $prev, 2) : '—' }}
                                        </td>
                                        <td class="{{ $bidClass }}" @if ($bidBg) style="background:{{ $bidBg }};" @endif>
                                            <div>
                                                {{ $bid !== null && $bid !== '' ? number_format((float) $bid, 2) : '—' }}
                                            </div>
                                            @if ($bidLabel)
                                                <div class="small fw-normal opacity-75">{{ $bidLabel }}</div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="text-end" colspan="3">Jumlah Keseluruhan (RM)</td>
                                    <td>{{ number_format((float) $totalPrevious, 2) }}</td>
                                    <td>{{ number_format((float) $totalBid, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endif
