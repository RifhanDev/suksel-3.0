@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/content-card.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/tender-show.css') }}" rel="stylesheet">
    <style>
        /* ── Side tab navigation ── */
        .page-side-nav .nav-link { color: #475569; font-size: 0.85rem; font-weight: 500; border-radius: 8px; padding: 10px 14px; display: flex; align-items: center; gap: 10px; transition: background 0.15s, color 0.15s; margin-bottom: 2px; }
        .page-side-nav .nav-link:hover { background: #f1f5f9; color: #1e293b; }
        .page-side-nav .nav-link.active { background: linear-gradient(135deg, #c41e3a 0%, #a01830 100%); color: #fff; }
        .page-side-nav .nav-link svg { opacity: 0.65; flex-shrink: 0; }
        .page-side-nav .nav-link.active svg { opacity: 1; }

        /* ── Empty tab placeholder ── */
        .tab-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 64px 24px; color: #94a3b8; text-align: center; }
        .tab-empty svg { opacity: 0.35; margin-bottom: 14px; }
        .tab-empty p { font-size: 0.85rem; margin: 0; }
    </style>
@endsection

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- ── HEADER ── -->
    <div class="mb-4">
        <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Penyediaan Iklan</h3>
        <p class="text-muted small m-0">Semak dan sediakan maklumat iklan tender sebelum diterbitkan.</p>
    </div>

    <!-- ── TENDER INFO CARD ── -->
    <div class="tender-header-card mb-4">
        <div class="tender-page-header pb-3">
            <div class="row g-3">

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="d-flex flex-column gap-1">
                        <span class="text-muted fw-semibold text-uppercase" style="font-size:0.67rem; letter-spacing:0.5px;">No. Tender / Sebut Harga</span>
                        <span class="fw-semibold text-dark" style="font-size:0.88rem;">{{ $tender->no_tender ?: $tender->ref_number }}</span>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="d-flex flex-column gap-1">
                        <span class="text-muted fw-semibold text-uppercase" style="font-size:0.67rem; letter-spacing:0.5px;">PTJ</span>
                        <span class="fw-semibold text-dark" style="font-size:0.88rem;">{{ $tender->tenderer?->name ?? '-' }}</span>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-2">
                    <div class="d-flex flex-column gap-1">
                        <span class="text-muted fw-semibold text-uppercase" style="font-size:0.67rem; letter-spacing:0.5px;">Status</span>
                        <div>
                            <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill fw-semibold"
                                style="background:#fef3c7; color:#b45309; font-size:0.78rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                Penyediaan Iklan
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ── MAIN LAYOUT: side nav + tab content ── -->
    <div class="row g-4 align-items-start">

        <!-- LEFT: Side tab navigation -->
        <div class="col-lg-3">
            <div class="bg-white rounded-3 shadow-sm border p-2">
                <nav class="nav flex-column page-side-nav" id="pageSideTabs" role="tablist">

                    <a class="nav-link active" id="tab-maklumat-btn" data-bs-toggle="pill"
                        href="#tab-maklumat" role="tab" aria-controls="tab-maklumat" aria-selected="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                        Maklumat Tender
                    </a>

                    <a class="nav-link" id="tab-kelulusan-btn" data-bs-toggle="pill"
                        href="#tab-kelulusan" role="tab" aria-controls="tab-kelulusan" aria-selected="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        Kelulusan
                    </a>

                    <a class="nav-link" id="tab-iklan-btn" data-bs-toggle="pill"
                        href="#tab-iklan" role="tab" aria-controls="tab-iklan" aria-selected="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>
                        </svg>
                        Penyediaan Iklan
                    </a>

                    <a class="nav-link" id="tab-pegawai-btn" data-bs-toggle="pill"
                        href="#tab-pegawai" role="tab" aria-controls="tab-pegawai" aria-selected="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        Pegawai Bertanggungjawab
                    </a>

                    <a class="nav-link" id="tab-dokumen-btn" data-bs-toggle="pill"
                        href="#tab-dokumen" role="tab" aria-controls="tab-dokumen" aria-selected="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                            <polyline points="13 2 13 9 20 9"></polyline>
                        </svg>
                        Dokumen Tender/Tawaran
                    </a>

                </nav>
            </div>
        </div>

        <!-- RIGHT: Tab content -->
        <div class="col-lg-9">
            <form id="formPenyediaanIklan"
                action="{{ route('penyediaanIklan.simpan', $tender) }}"
                method="POST"
                enctype="multipart/form-data">
            @csrf
            <div class="tab-content" id="pageTabContent">

                {{-- ══ TAB 1: MAKLUMAT TENDER ══ --}}
                <div class="tab-pane fade show active" id="tab-maklumat" role="tabpanel" aria-labelledby="tab-maklumat-btn">
                    @include('newModule.penyediaanIklan.maklumat_tender_form')
                </div>

                {{-- ══ TAB 2: KELULUSAN ══ --}}
                <div class="tab-pane fade" id="tab-kelulusan" role="tabpanel" aria-labelledby="tab-kelulusan-btn">
                    @include('newModule.penyediaanIklan.kelulusan_form')
                </div>

                {{-- ══ TAB 3: PENYEDIAAN IKLAN ══ --}}
                <div class="tab-pane fade" id="tab-iklan" role="tabpanel" aria-labelledby="tab-iklan-btn">
                    @include('newModule.penyediaanIklan.penyediaan_iklan_form')
                </div>

                {{-- ══ TAB 4: PEGAWAI BERTANGGUNGJAWAB ══ --}}
                <div class="tab-pane fade" id="tab-pegawai" role="tabpanel" aria-labelledby="tab-pegawai-btn">
                    @include('newModule.penyediaanIklan.pegawai_bertanggungjawab_form')
                </div>

                {{-- ══ TAB 5: DOKUMEN TENDER/TAWARAN ══ --}}
                <div class="tab-pane fade" id="tab-dokumen" role="tabpanel" aria-labelledby="tab-dokumen-btn">
                    @include('newModule.penyediaanIklan.dokumen_tender_form')
                </div>

            </div>
            {{-- End tab-content --}}
            </form>

            <!-- ── TAB NAVIGATION BUTTONS ── -->
            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                <button type="button" class="btn-form btn-form-secondary" id="btnSebelum" style="display:none!important;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Sebelum
                </button>
                <div id="btnSebelumPlaceholder"></div>
                <button type="button" class="btn-form btn-form-primary" id="btnSeterusnya">
                    Seterusnya
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>
            </div>

        </div>
        {{-- End right col --}}

    </div>
    {{-- End main row --}}

    <!-- ── KEMBALI ── -->
    <div class="mt-3 mb-4">
        <a href="{{ route('penyediaanIklan.index') }}" class="btn-form btn-form-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali
        </a>
    </div>

@endsection

@section('scripts')
<script>
$(document).ready(function () {

    /* ── Tab IDs in order ── */
    var tabs = ['#tab-maklumat-btn', '#tab-kelulusan-btn', '#tab-iklan-btn', '#tab-pegawai-btn', '#tab-dokumen-btn'];
    var currentTab = 0;

    if (new URLSearchParams(window.location.search).get('tab') === 'iklan') {
        currentTab = 2;
        $('#tab-iklan-btn').tab('show');
    }

    function updateNavButtons() {
        /* Sebelum: hide on first tab */
        if (currentTab === 0) {
            $('#btnSebelum').hide();
            $('#btnSebelumPlaceholder').show();
        } else {
            $('#btnSebelum').show();
            $('#btnSebelumPlaceholder').hide();
        }

        /* Seterusnya → Terbitkan on last tab */
        if (currentTab === tabs.length - 1) {
            $('#btnSeterusnya').html(
                '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>' +
                ' Terbitkan'
            ).addClass('btn-form-success').removeClass('btn-form-primary');
        } else {
            $('#btnSeterusnya').html(
                'Seterusnya ' +
                '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
            ).addClass('btn-form-primary').removeClass('btn-form-success');
        }
    }

    function goToTab(index) {
        if (index < 0 || index >= tabs.length) return;
        currentTab = index;
        $(tabs[currentTab]).tab('show');
        updateNavButtons();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    /* Sync currentTab when user clicks side nav directly */
    $.each(tabs, function (i, selector) {
        $(selector).on('shown.bs.tab', function () {
            currentTab = i;
            updateNavButtons();
        });
    });

    $('#btnSebelum').on('click', function () { goToTab(currentTab - 1); });
    $('#btnSeterusnya').on('click', function () {
        if (currentTab === tabs.length - 1) {
            var form = document.getElementById('formPenyediaanIklan');
            if (form) {
                form.action = '{{ route("penyediaanIklan.hantar", $tender) }}';
                if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['syarat_tender']) {
                    CKEDITOR.instances['syarat_tender'].updateElement();
                }
                if (typeof taklimatRows !== 'undefined') {
                    $('#formPenyediaanIklan #taklimat_rows_hidden').remove();
                    $('<input>').attr({ type: 'hidden', id: 'taklimat_rows_hidden', name: 'taklimat_rows' })
                        .val(JSON.stringify(taklimatRows)).appendTo('#formPenyediaanIklan');
                }
                form.submit();
            }
        } else {
            goToTab(currentTab + 1);
        }
    });

    updateNavButtons();

});
</script>
@endsection
