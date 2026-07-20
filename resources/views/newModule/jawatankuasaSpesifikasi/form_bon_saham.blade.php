@extends($layout ?? 'layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
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
        .borang-subtitle {
            font-size: 0.78rem;
            color: #1e40af;
            font-weight: 600;
        }
        .bon-akaun-block {
            border-bottom: 2px dotted #cbd5e1;
            padding-bottom: 1.25rem;
            margin-bottom: 1.25rem;
        }
        .bon-akaun-block:last-of-type {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .btn-hapus-akaun {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            background: #fee2e2;
            color: #ef4444;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }
    </style>
@endsection

@section('content')
    @include('tenders.forms._view_only_lock')

    @php
        $kembaliUrl = $returnUrl ?? route('senaraiKewanganKerja', $tender->uuid);
        $viewOnly = $viewOnly ?? false;
        $initialAccounts = isset($bonSaham) && $bonSaham->accounts->isNotEmpty()
            ? $bonSaham->accounts->map(fn ($a) => [
                'bank_institusi' => $a->bank_institusi,
                'jumlah_deposit' => (float) $a->jumlah_deposit,
            ])->values()->toArray()
            : [];
    @endphp

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Saham atau Bon</h3>
            <p class="text-muted small m-0">Isi maklumat saham atau bon syarikat petender.</p>
        </div>
    </div>

    <!-- TENDER INFO -->
    <div class="content-card mb-4 p-0">
        <div class="content-card-body p-4">
            <div class="mb-3 pb-3 border-bottom">
                <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                    style="font-size: 0.67rem; letter-spacing: 0.5px;">Tajuk Tender</span>
                <h5 class="fw-bold text-dark mb-0" style="line-height: 1.45; font-size: 1rem;">
                    {{ $tender->name ?? '-' }}
                    @if($tender?->kategori_perolehan_name)
                    <span class="fw-normal text-muted fst-italic" style="font-size: 0.85rem;">({{ $tender->kategori_perolehan_name }})</span>
                    @endif
                </h5>
            </div>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size: 0.67rem; letter-spacing: 0.5px;">No. Tender</span>
                    <span class="fw-semibold text-dark" style="font-size: 0.875rem;">
                        {{ $tender->no_tender ?: ($tender->ref_number ?? '-') }}
                    </span>
                </div>
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size: 0.67rem; letter-spacing: 0.5px;">PTJ</span>
                    <span class="fw-semibold text-dark" style="font-size: 0.875rem;">
                        {{ $tender->tenderer->name ?? '-' }}
                    </span>
                </div>
                <div class="col-12 col-md-6 d-md-flex justify-content-md-end align-items-md-center">
                    @if(isset($bonSaham) && $bonSaham->status === 'submitted')
                        <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2 fw-semibold"
                            style="background: #dcfce7; color: #166534; font-size: 0.8rem; border: 1px solid #bbf7d0;">
                            <span class="d-inline-block rounded-circle"
                                style="width:7px;height:7px;background:#16a34a;flex-shrink:0;"></span>
                            Telah Dihantar
                        </span>
                    @else
                        <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2 fw-semibold"
                            style="background: #fef9c3; color: #854d0e; font-size: 0.8rem; border: 1px solid #fde68a;">
                            <span class="d-inline-block rounded-circle"
                                style="width:7px;height:7px;background:#ca8a04;flex-shrink:0;"></span>
                            Dalam Proses
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <form id="form-bon-saham" action="{{ route('bonAtauSaham.store', $tender->uuid) }}" method="POST">
    @csrf
    @if (! empty($returnUrl))
        <input type="hidden" name="return" value="{{ $returnUrl }}">
    @endif
    @if ($modalEmbed ?? false)
        <input type="hidden" name="modal" value="1">
    @endif

        <div class="content-card mb-4 p-0">
            <div class="borang-title-bar">Saham atau Bon</div>
            <div class="content-card-body p-4 pt-3">
                <div id="bon-akaun-container"></div>

                @unless($viewOnly)
                <div class="d-flex justify-content-end mt-3">
                    <button type="button" id="btn-tambah-akaun" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah Akaun
                    </button>
                </div>
                @endunless

                <div class="row justify-content-end mt-4 pt-3">
                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-center gap-3">
                            <label class="fw-semibold text-muted mb-0 flex-shrink-0" style="font-size:0.82rem;">
                                Jumlah Keseluruhan Deposit Tetap/Saham/Bon (RM)
                            </label>
                            <input type="text" class="form-control form-control-sm bg-light text-end fw-semibold"
                                id="jumlah-keseluruhan-display" readonly tabindex="-1"
                                value="{{ isset($bonSaham) ? number_format($bonSaham->jumlah_keseluruhan, 2) : '0.00' }}">
                            <input type="hidden" name="jumlah_keseluruhan" id="jumlah-keseluruhan-input"
                                value="{{ isset($bonSaham) ? $bonSaham->jumlah_keseluruhan : '0.00' }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            @include('tenders.forms._vendor_form_kembali', ['kembaliUrl' => $kembaliUrl])
            <div class="d-flex gap-2">
                <button type="button" class="btn-form btn-form-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    Laporan
                </button>
                @unless($viewOnly)
                <button type="submit" class="btn-form btn-form-success">
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
        </div>

    </form>

@endsection

@section('scripts')
<script>
$(document).ready(function () {

    var VIEW_ONLY = @json($viewOnly);
    var accounts = @json($initialAccounts);
    var akaunCounter = 0;

    function parseAmount(val) {
        return parseFloat(String(val).replace(/,/g, '')) || 0;
    }

    function formatAmount(n) {
        return n.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function esc(v) { return $('<div/>').text(v || '').html(); }
    function ro() { return VIEW_ONLY ? ' readonly' : ''; }

    function buildAkaunBlock(index, data) {
        data = data || {};
        var canDelete = index > 0;
        var jumlahFmt = data.jumlah_deposit ? formatAmount(data.jumlah_deposit) : '';

        var $block = $(
            '<div class="bon-akaun-block" data-akaun-index="' + index + '">' +
                '<div class="d-flex justify-content-between align-items-center mb-2">' +
                    '<span class="fw-bold text-dark" style="font-size:0.9rem;">Akaun ' + (index + 1) + '</span>' +
                    (canDelete && !VIEW_ONLY
                        ? '<button type="button" class="btn-hapus-akaun" title="Buang akaun">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">' +
                            '<polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>' +
                            '<path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path></svg>' +
                          '</button>'
                        : '') +
                '</div>' +
                '<p class="borang-subtitle mb-3">Perlu diisi oleh Petender</p>' +
                '<div class="row g-3 align-items-end">' +
                    '<div class="col-12 col-md-8">' +
                        '<label class="form-label fw-semibold small">Bank / Institusi</label>' +
                        '<input type="text" name="bank_institusi[]" class="form-control form-control-sm field-bank" placeholder="Bank / institusi..." value="' + esc(data.bank_institusi) + '"' + ro() + '>' +
                    '</div>' +
                    '<div class="col-12 col-md-4">' +
                        '<label class="form-label fw-semibold small">Jumlah Deposit Tetap/Saham/Bon (RM)</label>' +
                        '<input type="text" name="jumlah_deposit[]" class="form-control form-control-sm text-end amount-input jumlah-deposit" placeholder="0.00" value="' + jumlahFmt + '"' + ro() + '>' +
                    '</div>' +
                '</div>' +
            '</div>'
        );

        $('#bon-akaun-container').append($block);
        return $block;
    }

    function renumberAkaun() {
        $('#bon-akaun-container .bon-akaun-block').each(function (i) {
            $(this).attr('data-akaun-index', i);
            $(this).find('.fw-bold').first().text('Akaun ' + (i + 1));
            $(this).find('.btn-hapus-akaun').toggle(i > 0 && !VIEW_ONLY);
        });
        akaunCounter = $('#bon-akaun-container .bon-akaun-block').length;
    }

    function updateJumlah() {
        var total = 0;
        $('.jumlah-deposit').each(function () {
            total += parseAmount($(this).val());
        });
        $('#jumlah-keseluruhan-display').val(formatAmount(total));
        $('#jumlah-keseluruhan-input').val(total.toFixed(2));
    }

    function initAccounts() {
        if (!accounts.length) {
            accounts = [{}, {}];
        }
        accounts.forEach(function (acc, i) { buildAkaunBlock(i, acc); });
        akaunCounter = accounts.length;
        updateJumlah();
    }

    initAccounts();

    $('#btn-tambah-akaun').on('click', function () {
        buildAkaunBlock(akaunCounter, {});
        akaunCounter++;
        renumberAkaun();
    });

    $('#bon-akaun-container').on('click', '.btn-hapus-akaun', function () {
        if ($('#bon-akaun-container .bon-akaun-block').length <= 1) return;
        $(this).closest('.bon-akaun-block').remove();
        renumberAkaun();
        updateJumlah();
    });

    $('#bon-akaun-container').on('input change', '.jumlah-deposit', updateJumlah);

    $(document).on('focus', '.amount-input', function () {
        var raw = $(this).val().replace(/,/g, '');
        $(this).val(parseFloat(raw) === 0 ? '' : raw);
    });
    $(document).on('blur', '.amount-input', function () {
        var val = $(this).val();
        if (val !== '') $(this).val(formatAmount(parseAmount(val)));
        updateJumlah();
    });
    $(document).on('input', '.amount-input', function () {
        $(this).val($(this).val().replace(/[^\d.]/g, ''));
    });

    $('#form-bon-saham').on('submit', function () {
        $(this).find('.amount-input').each(function () {
            $(this).val($(this).val().replace(/,/g, ''));
        });
    });

});
</script>
@endsection
