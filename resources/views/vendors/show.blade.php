@extends('layouts.v3.master')

@section('styles')
<style>
    .vr-custom {
        width: 1px;
        height: 24px;
        background-color: #e2e8f0;
        display: inline-block;
        vertical-align: middle;
    }

     /* === NEW: Grey Dropdown Style === */
    .dropdown-menu-gray {
        background-color: #f8fafc; /* Subtle Grey-Blue tint */
        border: 1px solid #e2e8f0;
    }
    .dropdown-menu-gray .dropdown-item {
        color: #475569;
        font-weight: 500;
        font-size: 0.9rem;
        padding: 8px 16px;
    }
    .dropdown-menu-gray .dropdown-item:hover {
        background-color: #e2e8f0; /* Darker grey on hover */
        color: #1e293b;
    }
    .dropdown-menu-gray .dropdown-item.text-danger:hover {
        background-color: #fee2e2; /* Light red hover for dangerous items */
        color: #dc2626;
    }
</style>
@endsection

@section('content')

<div class="d-flex gap-4 align-items-start position-relative">
    
    <!-- LEFT PANE  -->
    <div class="flex-grow-1" style="min-width: 0; padding-bottom: 100px;">
        
        <!-- HEADER CARD -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-3 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase text-muted fw-bold small mb-1" style="letter-spacing: 0.5px;">Maklumat Syarikat</h6>
                    <h1 class="h3 fw-bolder text-dark mb-0">{{ $vendor->name }}</h1>
                </div>
                <div>
                    @php
                        $statusLabel = $vendor->status;
                        $badgeClass = 'bg-secondary-subtle text-secondary border-secondary'; // Default

                        switch ($statusLabel) {
                            case 'Aktif':
                                $badgeClass = 'bg-success-subtle text-success border border-success-subtle';
                                break;
                            case 'Disenarai Hitam':
                                $badgeClass = 'bg-danger-subtle text-danger border border-danger-subtle';
                                break;
                            case 'Tiada Langganan Aktif':
                            case 'Belum Selesai':
                                $badgeClass = 'bg-warning-subtle text-warning border border-warning-subtle';
                                break;
                            case 'Belum Diluluskan':
                                $badgeClass = 'bg-info-subtle text-primary border border-info-subtle';
                                break;
                        }
                    @endphp
                    <span class="badge rounded-pill {{ $badgeClass }} px-3 py-2 fw-bold text-uppercase" style="font-size: 0.8rem;">
                        {{ $statusLabel ?? 'N/A' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- CONTENT INCLUDES -->
        <div class="mb-4">
            @include('vendors.vendor')
        </div>

        <div class="w-100 bg-white border border-top-0 shadow-lg rounded-3 p-3 z-3">
            <div class="d-flex justify-content-between align-items-center">
                
                <!-- LEFT SIDE: Navigation -->
                <div>
                    @if (App\Vendor::canList())
                        <a href="{{ route('vendors.index') }}" class="btn btn-link text-secondary text-decoration-none d-flex align-items-center gap-2 ps-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            <span class="fw-bold">Kembali</span>
                        </a>
                    @endif
                </div>

                <!-- RIGHT SIDE: Actions -->
                <div class="d-flex align-items-center gap-2">

                    <!-- 1. "TINDAKAN LANJUT" DROPDOWN (Added .dropdown-menu-gray) -->
                    <div class="dropdown">
                        <button class="btn btn-light border dropdown-toggle d-flex align-items-center gap-2 fw-medium" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                            Lain-lain
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm dropdown-menu-gray mt-2">
                            
                            @if ($vendor->canConfirm())
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ action('UsersController@resendConfirmation', $vendor->user->id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                        Hantar Emel Pengesahan
                                    </a>
                                </li>
                            @endif

                            @if ($vendor->canUpdate())
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ action('UsersController@getSetPassword', $vendor->user->id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                        Tukar Kata Laluan
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('CodeRequest:list'))
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ asset('vendor/' . $vendor->id . '/requests') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                        Permintaan Kemaskini
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('Vendor:histories'))
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ action('VendorsController@histories', $vendor->id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                        Sejarah Kemaskini
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ action('UsersController@histories', $vendor->user->id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                        Aktiviti Pengguna
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('User:login') || App\VendorBlacklist::canList())
                                <li><hr class="dropdown-divider"></li>
                                @if (Auth::user()->can('User:login'))
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2 text-danger" href="{{ asset('users/' . $vendor->user->id . '/login') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                                            Login Sebagai
                                        </a>
                                    </li>
                                @endif
                                @if (App\VendorBlacklist::canList())
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ action('VendorsController@blacklist', $vendor->id) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                                            Senarai Hitam
                                        </a>
                                    </li>
                                @endif
                            @endif
                        </ul>
                    </div>

                    <!-- 2. EDIT GROUP (Added .dropdown-menu-gray) -->
                    @if ($vendor->canUpdate())
                        <div class="btn-group">
                            <a href="{{ action('VendorsController@edit', $vendor->id) }}" class="btn btn-light border d-flex align-items-center gap-2 fw-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                Kemaskini
                            </a>
                            @if ($vendor->canUpdate2())
                                <button type="button" class="btn btn-light border dropdown-toggle dropdown-toggle-split px-2" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-gray">
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ action('VendorsController@editEmail', $vendor->id) }}">
                                            Emel / No. Pendaftaran
                                        </a>
                                    </li>
                                </ul>
                            @endif
                        </div>
                    @endif

                    <!-- 3. DECISION ACTIONS -->
                    @if ($vendor->canApprove())
                        <div class="vr-custom mx-1"></div>
                        
                        <button type="button" id="btnTriggerReject" class="btn btn-danger d-flex align-items-center gap-2 fw-medium px-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                            Tolak
                        </button>

                        <a href="{{ action('VendorsController@approve', [$vendor->id]) }}" class="btn btn-selangor d-flex align-items-center gap-2 fw-medium px-3 link-confirm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Lulus
                        </a>
                    @endif

                </div>
            </div>
        </div>

    </div>

    <!-- Legacy Right Pane (Hidden) -->
    <div class="d-none" id="right-pane"></div>

</div>

<!-- 1. THE BOOTSTRAP 5 MODAL SKELETON -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <!-- Header -->
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    Tolak Permohonan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- NEW: Error Message Container -->
            <div id="rejectErrorContainer"></div>
            
            <!-- Body: Content will be injected here via JS -->
            <div class="modal-body p-4 bg-light" id="modalBodyContainer">
                <!-- Javascript will put the form here -->
            </div>

            <!-- Footer -->
            <div class="modal-footer bg-white border-top-0">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger d-flex align-items-center gap-2" id="confirmRejectBtn">
                    Tolak
                </button>
            </div>
        </div>
    </div>
</div>

@if ($vendor->canApprove())
    @include('vendors.reject-modal')
@endif

@endsection

@section('scripts')
	{{-- <script src="{{ asset('js/pdfobject.min.js') }}"></script> --}}
	{{-- <script src="{{ asset('js/show.js') }}"></script> --}}
	{{-- <script src="{{ asset('js/vendor.js') }}"></script> --}}
	<script type="text/javascript">
		$('input:not([type=hidden]),select,textarea', 'form').attr({
			disabled: false,
			readonly: false
		});
		
        // Initialize Bootstrap 5 Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
	</script>
	<script>
        document.addEventListener("DOMContentLoaded", function() {
            
            // --- 1. OPEN MODAL ---
            var rejectBtn = document.getElementById('btnTriggerReject');
            
            if (rejectBtn) {
                rejectBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    $("#rejectErrorContainer").empty();
                    
                    // Grab the HTML
                    var sourceHtml = $("#rejectForm").html();
                    
                    // Inject it into the Bootstrap Modal Body
                    $("#modalBodyContainer").html(sourceHtml);
                    
                    // Show the Bootstrap Modal
                    var myModal = new bootstrap.Modal(document.getElementById('rejectModal'));
                    myModal.show();
                    
                    // D. Re-initialize tooltips inside the modal (since content is dynamic)
                    var modalTooltips = [].slice.call(document.querySelectorAll('#rejectModal [data-bs-toggle="tooltip"]'));
                    modalTooltips.map(function (el) { return new bootstrap.Tooltip(el) });
                });
            }

            // --- 2. SUBMIT LOGIC ---
            // Replaces: callback: function(result) { ... }
            $('#confirmRejectBtn').click(function() {
                
                // IMPORTANT: Select inputs INSIDE the modal body (#modalBodyContainer)
                var container = $("#modalBodyContainer");
                var reason = container.find("[name=reason]").val();
                var template = [];
                
                container.find("input[name='template']:checked").each(function() {
                    template.push($(this).val());
                });

                // Validation
                if (reason != '' || template.length != 0) {
                    
                    // Loading State
                    var $btn = $(this);
                    var originalText = $btn.html();
                    $btn.prop('disabled', true).html('Menolak...');

                    // AJAX Post
                    $.post('/vendor/{{ $vendor->id }}/reject', {
                            reason: reason,
                            template: template
                        })
                        .done(function() {
                            window.location.href = '{{ route('vendors.show', $vendor->id) }}';
                        })
                        .fail(function() {
                            $("#rejectErrorContainer").html(`
                                <div class="alert alert-danger small mb-3">
                                    Ralat berlaku semasa memproses. Sila cuba lagi.
                                </div>
                            `);
                            $btn.prop('disabled', false).html(originalText);
                        });
                } else {
                    $("#rejectErrorContainer").html(`
                        <div class="alert alert-danger d-flex align-items-center p-2 m-2 rounded-2" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            <div class="small fw-bold">
                                Sila nyatakan sebab penolakan atau pilih templat.
                            </div>
                        </div>
                    `);
                }
            });
        });
	</script>
@endsection