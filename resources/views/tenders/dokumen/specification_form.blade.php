@extends($layout ?? 'layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/content-card.css') }}" rel="stylesheet">
    <style>
        .borang-title-bar {
            background: #e2e8f0;
            color: #1e293b;
            font-weight: 700;
            font-size: 0.82rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 10px 16px;
            border-radius: 6px 6px 0 0;
        }
        .borang-instruction {
            font-size: 0.78rem;
            color: #475569;
            line-height: 1.55;
        }
        #tbl-specifikasi {
            border: 1px solid #e2e8f0;
        }
        #tbl-specifikasi thead th {
            background: #1e3a5f;
            color: #fff;
            font-size: 0.78rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            border-color: #1e3a5f !important;
        }
        #tbl-specifikasi th,
        #tbl-specifikasi td {
            border-right: 1px solid #e2e8f0 !important;
            vertical-align: middle;
        }
        #tbl-specifikasi th:last-child,
        #tbl-specifikasi td:last-child {
            border-right: none !important;
        }
        .spec-item-row td {
            background: #f8fafc;
        }
        .spec-detail-row td {
            background: #fff;
        }
        .spec-text-box {
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 8px 10px;
            background: #fff;
            font-size: 0.8rem;
            line-height: 1.45;
            min-height: 2.5rem;
        }
        .spec-text-box-sub {
            margin-left: 0.5rem;
            font-size: 0.78rem;
        }
        .spec-price-cell {
            background: #f8fafc !important;
            vertical-align: middle !important;
        }
        .spec-total-row td {
            background: #e8eef5 !important;
            border-top: 2px solid #94a3b8 !important;
            font-size: 0.84rem;
        }
        .spec-pematuhan-readonly {
            display: inline-block;
            padding: 4px 8px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            font-size: 0.78rem;
        }
        .spec-price-total-value {
            display: inline-block;
            min-width: 90px;
            padding: 4px 8px;
            background: #dbeafe;
            border: 1px solid #93c5fd;
            border-radius: 4px;
        }
        #loading-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(2px);
            align-items: center;
            justify-content: center;
        }
        #loading-overlay.active { display: flex; }
        .loading-box {
            background: #fff;
            border-radius: 12px;
            padding: 28px 36px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        }
        .loading-spinner {
            width: 28px;
            height: 28px;
            border: 3px solid #e2e8f0;
            border-top-color: #3b82f6;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        #loading-overlay.success .loading-spinner { display: none; }
        #loading-overlay.success .loading-check { display: block; }
        #loading-overlay.success .loading-text { color: #16a34a; }
        .loading-check { display: none; width: 28px; height: 28px; color: #22c55e; }
    </style>
@endsection

@section('content')
    @include('tenders.forms._view_only_lock')

    @php
        $content = $item['admin_content'] ?? [];
        $rows = $content['rows'] ?? [];
        $savedResponses = $item['vendor_content']['specification'] ?? [];
        $itemUuid = $item['uuid'] ?? '';
        $section = $item['section'] ?? '';
        $kembaliUrl = $returnUrl ?? route('tenders.show', $tender->id) . '#vt-dokumen-tawaran';
        $hasDetailRows = collect($rows)->contains(fn ($row) => ($row['kind'] ?? '') === 'detail');
    @endphp

    @unless ($modalEmbed ?? false)
    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Maklum Balas Spesifikasi</h3>
            <p class="text-muted small m-0">{{ $content['document_title'] ?? $item['title'] ?? '' }}</p>
        </div>
    </div>
    @endunless

    <div class="content-card mb-4 p-0">
        <div class="content-card-body p-4">
            <div class="mb-3 pb-3 border-bottom">
                <span class="text-muted fw-semibold text-uppercase d-block mb-1" style="font-size:0.67rem;letter-spacing:0.5px;">Tajuk Tender</span>
                <h5 class="fw-bold text-dark mb-0" style="font-size:1rem;line-height:1.45;">{{ $tender->name ?? 'Tiada Tajuk' }}</h5>
            </div>
            <div class="row g-3">
                <div class="col-6 col-md-4">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1" style="font-size:0.67rem;">No. Tender</span>
                    <span class="fw-semibold text-dark" style="font-size:0.875rem;">{{ $tender->no_tender ?? $tender->ref_number ?? '-' }}</span>
                </div>
                <div class="col-6 col-md-4">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1" style="font-size:0.67rem;">Dokumen</span>
                    <span class="fw-semibold text-dark" style="font-size:0.875rem;">{{ $content['document_title'] ?? $item['title'] ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="content-card mb-4 p-0">
        <div class="borang-title-bar">Item Spesifikasi & Maklum Balas</div>
        <div class="content-card-body p-4 pt-3">
            @unless ($viewOnly ?? false)
            <p class="borang-instruction mb-3">
                Isi <strong>Cadangan Petender</strong> bagi setiap sub-spesifikasi dan <strong>Tawaran Harga</strong> bagi setiap item utama.
                Ruangan <strong>Pematuhan</strong> ditetapkan oleh PTJ sahaja.
            </p>
            @else
            <p class="borang-instruction mb-3">Paparan maklum balas spesifikasi (mod baca sahaja).</p>
            @endunless

            @include('tenders.dokumen.partials.specification_table', [
                'content' => $content,
                'dok' => $item,
                'tender' => $tender,
                'mode' => ($viewOnly ?? false) ? 'admin' : 'vendor',
                'vendorCanEdit' => ! ($viewOnly ?? false),
                'standalone' => true,
            ])
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        @include('tenders.forms._vendor_form_kembali', ['kembaliUrl' => $kembaliUrl])
        @unless ($viewOnly ?? false)
        <button type="button" id="btn-simpan-spec" class="btn-form btn-form-success" @disabled(! $hasDetailRows)>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                <polyline points="7 3 7 8 15 8"></polyline>
            </svg>
            Simpan
        </button>
        @endunless
    </div>

    <div id="loading-overlay">
        <div class="loading-box">
            <div class="loading-spinner"></div>
            <svg class="loading-check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <span class="loading-text" id="loading-text">Menyimpan...</span>
        </div>
    </div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    var SAVE_URL = @json(route('tenderDokumen.saveSpecification', ['tender' => $tender->id, 'itemUuid' => $itemUuid]));
    var CSRF_TOKEN = @json(csrf_token());
    var SECTION = @json($section);
    var VIEW_ONLY = @json($viewOnly ?? false);
    var IS_ADMIN_MODE = @json($viewOnly ?? false);

    function formatMoney(value) {
        return (parseFloat(value) || 0).toLocaleString('ms-MY', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function updateSpecTotal() {
        var total = 0;
        $('.dokumen-spec-price').each(function () {
            total += parseFloat($(this).val()) || 0;
        });
        $('#spec-price-total').text(formatMoney(total));
    }

    function collectResponses() {
        var data = { item_prices: {}, details: {} };

        $('.dokumen-spec-price').each(function () {
            var uuid = $(this).data('item-uuid');
            if (uuid) {
                data.item_prices[uuid] = $(this).val() || '';
            }
        });

        $('.dokumen-spec-pematuhan').each(function () {
            if (!IS_ADMIN_MODE) return;
            var uuid = $(this).data('detail-uuid');
            if (!uuid) return;
            if (!data.details[uuid]) data.details[uuid] = {};
            data.details[uuid].pematuhan = $(this).val() || '';
        });

        $('.dokumen-spec-cadangan').each(function () {
            var uuid = $(this).data('detail-uuid');
            if (!uuid) return;
            if (!data.details[uuid]) data.details[uuid] = {};
            data.details[uuid].cadangan = $(this).val() || '';
        });

        return data;
    }

    $(document).on('input change', '.dokumen-spec-price', updateSpecTotal);
    updateSpecTotal();

    $('#btn-simpan-spec').on('click', function () {
        if (VIEW_ONLY) return;

        $('#loading-text').text('Menyimpan...');
        $('#loading-overlay').removeClass('success').addClass('active');

        $.ajax({
            url: SAVE_URL,
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(Object.assign({ section: SECTION }, collectResponses())),
            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
        })
        .done(function (res) {
            if (res && res.success) {
                $('#loading-text').text('Berjaya disimpan!');
                $('#loading-overlay').addClass('success');
                setTimeout(function () {
                    $('#loading-overlay').removeClass('active success');
                    if (typeof vendorFormComplete === 'function' && vendorFormComplete('Maklum balas berjaya disimpan.')) {
                        return;
                    }
                    if (typeof vendorFormNavigate === 'function') {
                        vendorFormNavigate(@json($kembaliUrl));
                    }
                }, 800);
            } else {
                $('#loading-overlay').removeClass('active success');
                alert((res && res.message) || 'Ralat semasa menyimpan.');
            }
        })
        .fail(function (xhr) {
            $('#loading-overlay').removeClass('active success');
            var msg = (xhr.responseJSON && xhr.responseJSON.message) || 'Ralat semasa menyimpan.';
            alert(msg);
        });
    });
});
</script>
@endsection
