{{--
    Reusable Vendor CIDB meta modal (accordion + comparison + optional integrate).

    @include('components.vendor-cidb-meta', [
        'vendor' => $vendor,
        'trigger' => 'button', // button | icon | link | none
        'showIntegrate' => false,
        'openOnLoad' => false,
        'scrollToComparison' => false,
        'showComparison' => false,
        'buttonLabel' => 'Maklumat CIDB Terperinci',
    ])

    Table icon example:
    <td class="text-center">
        @include('components.vendor-cidb-meta', ['vendor' => $rowVendor, 'trigger' => 'icon'])
    </td>
--}}
@php
    use App\Support\VendorCidbMeta;

    $vendor = $vendor ?? null;
    $trigger = $trigger ?? 'button';
    $showIntegrate = (bool) ($showIntegrate ?? false);
    $openOnLoad = (bool) ($openOnLoad ?? false);
    $scrollToComparison = (bool) ($scrollToComparison ?? false);
    $showComparison = (bool) ($showComparison ?? false);
    $buttonLabel = $buttonLabel ?? 'Maklumat CIDB Terperinci';
    $modalSuffix = $vendor?->id ?? ($modalSuffix ?? uniqid('cidb'));
    $modalId = 'cidbMetaModal-' . $modalSuffix;
    $accordionId = 'cidbMetaAccordion-' . $modalSuffix;
    $cidbMeta = $vendor
        ? VendorCidbMeta::normalizeMeta(is_array($vendor->meta ?? null) ? $vendor->meta : null)
        : null;
    $cidbSections = VendorCidbMeta::resolveSections($cidbMeta);
    $cidbSyncedAt = VendorCidbMeta::currentSyncedAt($cidbMeta);
    $hasCidbMeta = is_array($cidbMeta);
    $vendorName = $vendor?->name;
@endphp

@if ($trigger !== 'none')
    @if ($trigger === 'icon')
        <button type="button"
            class="btn btn-sm btn-outline-primary py-0 px-2 d-inline-flex align-items-center justify-content-center"
            data-bs-toggle="modal" data-bs-target="#{{ $modalId }}" @disabled(! $hasCidbMeta)
            title="{{ $buttonLabel }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
        </button>
    @elseif ($trigger === 'link')
        <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none" data-bs-toggle="modal"
            data-bs-target="#{{ $modalId }}" @disabled(! $hasCidbMeta)>
            {{ $buttonLabel }}
        </button>
    @else
        <button type="button" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2"
            data-bs-toggle="modal" data-bs-target="#{{ $modalId }}" @disabled(! $hasCidbMeta)>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
            </svg>
            {{ $buttonLabel }}
        </button>
    @endif

    @if ($showIntegrate && $vendor)
        <form action="{{ route('vendors.cidb.integrate', $vendor->id) }}" method="POST" class="m-0 d-inline"
            onsubmit="return confirm('Integrasi data CIDB terkini untuk syarikat ini?');">
            @csrf
            <button type="submit" class="btn btn-sm btn-danger d-inline-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12a9 9 0 1 1-3-6.7"></path>
                    <polyline points="21 3 21 9 15 9"></polyline>
                </svg>
                Integrasi CIDB
            </button>
        </form>
    @endif
@endif

@push('modals')
    <div class="modal fade cidb-meta-modal" id="{{ $modalId }}" tabindex="-1"
        aria-labelledby="{{ $modalId }}Label" aria-hidden="true" data-cidb-meta-modal
        @if ($openOnLoad) data-cidb-open-on-load="1" @endif
        @if ($scrollToComparison) data-cidb-scroll-comparison="1" @endif>
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header border-bottom bg-light">
                    <div class="pe-3">
                        <h5 class="modal-title fw-bold text-secondary mb-1" id="{{ $modalId }}Label">
                            Maklumat CIDB Terperinci
                        </h5>
                        @if ($vendorName)
                            <div class="small fw-semibold text-dark">{{ $vendorName }}</div>
                        @endif
                        @if ($cidbSyncedAt)
                            <div class="small text-muted">
                                Dikemas kini: {{ \Carbon\Carbon::parse($cidbSyncedAt)->format('d M Y, H:i') }}
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-4">
                    @if (! $hasCidbMeta)
                        <div class="bg-light p-4 rounded border text-muted text-center fst-italic">
                            Tiada maklumat CIDB diintegrasi.
                        </div>
                    @else
                        <div class="cidb-meta-search-wrap mb-3">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                </span>
                                <input type="search" class="form-control border-start-0" data-cidb-search
                                    placeholder="Cari maklumat CIDB..." autocomplete="off">
                            </div>
                        </div>

                        <div class="alert alert-light border text-center text-muted small mb-3 d-none" data-cidb-no-results>
                            Tiada padanan carian ditemui.
                        </div>

                        <div class="accordion cidb-meta-accordion" id="{{ $accordionId }}" data-cidb-accordion>
                            @foreach ($cidbSections as $index => $section)
                                @php
                                    $collapseId = 'cidbMetaCollapse-' . $modalSuffix . '-' . $index;
                                    $headingId = 'cidbMetaHeading-' . $modalSuffix . '-' . $index;
                                    $hasData = VendorCidbMeta::sectionHasData($section['data']);
                                    $searchText = VendorCidbMeta::sectionSearchText($section['label'], $section['data']);
                                @endphp
                                <div class="accordion-item cidb-meta-accordion-item border rounded-3 mb-2 overflow-hidden"
                                    data-cidb-accordion-item data-cidb-search-text="{{ $searchText }}">
                                    <h2 class="accordion-header" id="{{ $headingId }}">
                                        <button class="accordion-button collapsed cidb-meta-accordion-btn py-3"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#{{ $collapseId }}" aria-expanded="false"
                                            aria-controls="{{ $collapseId }}">
                                            <span class="cidb-meta-index me-3">{{ $loop->iteration }}</span>
                                            <span class="cidb-meta-title flex-grow-1 text-start">{{ $section['label'] }}</span>
                                            @if ($hasData)
                                                <span
                                                    class="badge rounded-pill bg-success-subtle text-success border border-success-subtle ms-2">
                                                    Ada Data
                                                </span>
                                            @else
                                                <span class="badge rounded-pill bg-light text-muted border ms-2">Kosong</span>
                                            @endif
                                        </button>
                                    </h2>
                                    <div id="{{ $collapseId }}" class="accordion-collapse collapse"
                                        data-bs-parent="#{{ $accordionId }}">
                                        <div class="accordion-body pt-0 pb-3">
                                            @if (! $hasData)
                                                <div class="text-muted fst-italic small">Tiada maklumat.</div>
                                            @elseif (VendorCidbMeta::isListSection($section['key']))
                                                <div class="d-flex flex-column gap-3">
                                                    @foreach ($section['data'] as $itemIndex => $item)
                                                        @if (is_array($item))
                                                            <div class="cidb-meta-record border rounded-3 p-3 bg-light">
                                                                <div class="small fw-bold text-secondary text-uppercase mb-2">
                                                                    Rekod {{ $itemIndex + 1 }}
                                                                </div>
                                                                <div class="row g-2">
                                                                    @foreach ($item as $field => $value)
                                                                        <div class="col-md-6">
                                                                            <div class="small text-muted cidb-meta-field-label">
                                                                                {{ VendorCidbMeta::humanizeKey($field) }}
                                                                            </div>
                                                                            <div class="fw-medium text-dark cidb-meta-field-value">
                                                                                {{ VendorCidbMeta::formatDisplayValue($value) }}
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="row g-3">
                                                    @foreach ($section['data'] as $field => $value)
                                                        <div class="col-md-6">
                                                            <div
                                                                class="small text-muted text-uppercase fw-semibold cidb-meta-field-label">
                                                                {{ VendorCidbMeta::humanizeKey($field) }}
                                                            </div>
                                                            <div class="fw-medium text-dark cidb-meta-field-value">
                                                                {{ VendorCidbMeta::formatDisplayValue($value) }}
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($showComparison)
                            @include('components.partials.vendor-cidb-meta-comparison', [
                                'cidbMeta' => $cidbMeta,
                                'comparisonId' => 'cidb-comparison-' . $modalSuffix,
                            ])
                        @endif
                    @endif
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@once
    @push('styles')
        <style>
            .cidb-meta-modal .modal-body {
                max-height: calc(100vh - 220px);
                overflow-y: auto;
            }

            .cidb-meta-search-wrap {
                min-width: 260px;
            }

            .cidb-meta-accordion .accordion-button {
                background: #fff;
                box-shadow: none;
                font-size: 0.9rem;
                font-weight: 600;
                color: #1e293b;
            }

            .cidb-meta-accordion .accordion-button:not(.collapsed) {
                background: #fff1f2;
                color: #c41e3a;
            }

            .cidb-meta-accordion .accordion-button:focus {
                box-shadow: none;
                border-color: #fecaca;
            }

            .cidb-meta-index {
                flex-shrink: 0;
                width: 28px;
                height: 28px;
                background: rgba(196, 30, 58, 0.08);
                color: #c41e3a;
                border-radius: 50%;
                font-size: 0.72rem;
                font-weight: 800;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .cidb-meta-accordion-item.cidb-meta-hidden {
                display: none;
            }

            .cidb-meta-accordion-item.cidb-meta-match .accordion-button {
                border-left: 3px solid #c41e3a;
            }

            .cidb-meta-highlight {
                background: #fef08a;
                color: #854d0e;
                padding: 0 2px;
                border-radius: 2px;
                font-weight: 700;
            }

            .cidb-comparison-table th,
            .cidb-comparison-table td {
                border-color: #e2e8f0;
                vertical-align: top;
            }

            .bg-blue-selangor {
                background-color: #dbeafe !important;
            }

            .cidb-comparison-accordion .accordion-button:not(.collapsed) {
                background: #fff1f2;
                color: #c41e3a;
                box-shadow: none;
            }

            .cidb-comparison-accordion .accordion-button:focus {
                box-shadow: none;
            }

            .cidb-comparison-panel {
                margin-top: 2rem;
                padding-top: 1.5rem;
                border-top: 1px solid #e2e8f0;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const highlightTargets = '.cidb-meta-title, .cidb-meta-field-label, .cidb-meta-field-value';

                const escapeRegExp = function(value) {
                    return value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                };

                document.querySelectorAll('[data-cidb-meta-modal]').forEach(function(modalEl) {
                    const searchInput = modalEl.querySelector('[data-cidb-search]');
                    const noResults = modalEl.querySelector('[data-cidb-no-results]');
                    const items = Array.from(modalEl.querySelectorAll('[data-cidb-accordion-item]'));

                    if (!searchInput) {
                        return;
                    }

                    modalEl.querySelectorAll(highlightTargets).forEach(function(el) {
                        if (!el.hasAttribute('data-cidb-original')) {
                            el.setAttribute('data-cidb-original', el.textContent);
                        }
                    });

                    const resetAccordion = function() {
                        searchInput.value = '';
                        items.forEach(function(item) {
                            item.classList.remove('cidb-meta-hidden', 'cidb-meta-match');
                            const collapse = item.querySelector('.accordion-collapse');
                            const button = item.querySelector('.accordion-button');
                            if (collapse && button) {
                                button.classList.add('collapsed');
                                collapse.classList.remove('show');
                                button.setAttribute('aria-expanded', 'false');
                            }
                        });
                        modalEl.querySelectorAll(highlightTargets).forEach(function(el) {
                            const original = el.getAttribute('data-cidb-original');
                            if (original !== null) {
                                el.textContent = original;
                            }
                        });
                        if (noResults) {
                            noResults.classList.add('d-none');
                        }
                    };

                    const clearHighlights = function() {
                        modalEl.querySelectorAll(highlightTargets).forEach(function(el) {
                            const original = el.getAttribute('data-cidb-original');
                            if (original !== null) {
                                el.textContent = original;
                            }
                        });
                    };

                    const highlightElement = function(el, query) {
                        const original = el.getAttribute('data-cidb-original') || el.textContent;
                        const regex = new RegExp('(' + escapeRegExp(query) + ')', 'gi');

                        el.innerHTML = original.replace(regex, '<mark class="cidb-meta-highlight">$1</mark>');
                    };

                    const filterAccordion = function() {
                        const query = searchInput.value.trim();
                        const normalizedQuery = query.toLowerCase();
                        let visibleCount = 0;

                        clearHighlights();

                        items.forEach(function(item) {
                            const collapse = item.querySelector('.accordion-collapse');
                            const button = item.querySelector('.accordion-button');
                            const searchable = (item.getAttribute('data-cidb-search-text') || '').toLowerCase();
                            const matches = normalizedQuery === '' || searchable.includes(normalizedQuery);

                            item.classList.toggle('cidb-meta-hidden', !matches);
                            item.classList.toggle('cidb-meta-match', normalizedQuery !== '' && matches);

                            if (!matches) {
                                if (collapse && collapse.classList.contains('show') && window.bootstrap) {
                                    window.bootstrap.Collapse.getOrCreateInstance(collapse, {
                                        toggle: false
                                    }).hide();
                                }
                                return;
                            }

                            visibleCount++;

                            if (normalizedQuery !== '') {
                                item.querySelectorAll(highlightTargets).forEach(function(el) {
                                    highlightElement(el, query);
                                });
                            }

                            if (normalizedQuery !== '' && collapse && window.bootstrap) {
                                window.bootstrap.Collapse.getOrCreateInstance(collapse, {
                                    toggle: false
                                }).show();
                            } else if (normalizedQuery === '' && collapse && button) {
                                button.classList.add('collapsed');
                                collapse.classList.remove('show');
                                button.setAttribute('aria-expanded', 'false');
                            }
                        });

                        if (noResults) {
                            noResults.classList.toggle('d-none', visibleCount > 0 || normalizedQuery === '');
                        }
                    };

                    searchInput.addEventListener('input', filterAccordion);
                    modalEl.addEventListener('hidden.bs.modal', resetAccordion);

                    if (modalEl.dataset.cidbOpenOnLoad === '1' && window.bootstrap) {
                        const modal = window.bootstrap.Modal.getOrCreateInstance(modalEl);
                        modalEl.addEventListener('shown.bs.modal', function() {
                            if (modalEl.dataset.cidbScrollComparison === '1') {
                                const comparison = modalEl.querySelector('[data-cidb-comparison]');
                                if (comparison) {
                                    comparison.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                }
                            }
                        }, { once: true });
                        modal.show();
                    }
                });
            });
        </script>
    @endpush
@endonce
