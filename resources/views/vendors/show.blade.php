@extends('layouts.v3.master')

@section('styles')
<style>
    .btn-selangor {
        background-color: var(--sg-red);
        border-color: var(--sg-red);
        color: white;
    }
    .btn-selangor:hover {
        background-color: var(--sg-red);
        border-color: var(--sg-red);
        color: white;
    }

    .vr-custom {
        width: 1px;
        height: 24px;
        background-color: #e2e8f0;
        display: inline-block;
        vertical-align: middle;
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

        <!-- ACTION BAR -->
        <div class="w-100 bg-white border border-top-0 shadow-lg rounded-3 p-3 z-3">
            <div class="d-flex flex-wrap align-items-center gap-2">
                
                <!-- 1. Edit & Email Actions -->
                @if ($vendor->canUpdate())
                    <div class="btn-group">
                        <a href="{{ action('VendorsController@edit', $vendor->id) }}" class="btn btn-light border d-flex align-items-center gap-2 fw-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            Kemaskini
                        </a>

                        @if ($vendor->canUpdate2())
                            <button type="button" class="btn btn-light border dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ action('VendorsController@editEmail', $vendor->id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                        Emel / No. Pendaftaran
                                    </a>
                                </li>
                            </ul>
                        @endif
                    </div>
                @endif

                <!-- 2. Confirmation Email -->
                @if ($vendor->canConfirm())
                    <a href="{{ action('UsersController@resendConfirmation', $vendor->user->id) }}" class="btn btn-light border d-flex align-items-center gap-2 fw-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        Hantar Emel Pengesahan
                    </a>
                @endif

                <div class="vr-custom mx-2"></div>

                <!-- 3. Approve / Reject -->
                @if ($vendor->canApprove())
                    <a href="{{ action('VendorsController@approve', [$vendor->id]) }}" class="btn btn-selangor d-flex align-items-center gap-2 fw-medium link-confirm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Lulus
                    </a>
                    <button type="button" id="reject" class="btn btn-danger d-flex align-items-center gap-2 fw-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                        Tolak
                    </button>
                @endif

                <!-- 4. Login As -->
                @if (Auth::user()->can('User:login'))
                    <a href="{{ asset('users/' . $vendor->user->id . '/login') }}" class="btn btn-outline-danger d-flex align-items-center gap-2 fw-medium link-confirm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                        Login Sebagai
                    </a>
                @endif

                <!-- 5. Change Password -->
                @if ($vendor->canUpdate())
                    <a href="{{ action('UsersController@getSetPassword', $vendor->user->id) }}" class="btn btn-warning text-white d-flex align-items-center gap-2 fw-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        Tukar Kata Laluan
                    </a>
                @endif

                <div class="vr-custom mx-2"></div>

                <!-- 6. Request Update -->
                @if (Auth::user()->can('CodeRequest:list'))
                    <a href="{{ asset('vendor/' . $vendor->id . '/requests') }}" class="btn btn-light border d-flex align-items-center gap-2 fw-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        Permintaan Kemaskini
                    </a>
                @endif

                <!-- 7. Blacklist -->
                @if (App\VendorBlacklist::canList())
                    <a href="{{ asset('vendor/' . $vendor->id . '/blacklists') }}" class="btn btn-dark d-flex align-items-center gap-2 fw-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                        Senarai Hitam
                    </a>
                @endif

                <!-- 8. Histories -->
                @if (Auth::user()->can('Vendor:histories'))
                    <div class="btn-group">
                        <a href="{{ action('VendorsController@histories', $vendor->id) }}" class="btn btn-light border d-flex align-items-center gap-2 fw-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            Sejarah Kemaskini
                        </a>
                        <button type="button" class="btn btn-light border dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ action('UsersController@histories', $vendor->user->id) }}">
                                    Aktiviti Pengguna
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif

                <!-- 9. Right Aligned: List -->
                @if (App\Vendor::canList())
                    <div class="ms-auto">
                        <a href="{{ route('vendors.index') }}" class="btn btn-link text-secondary text-decoration-none d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                            Senarai Syarikat
                        </a>
                    </div>
                @endif

            </div>
        </div>

    </div>

    <!-- Legacy Right Pane (Hidden) -->
    <div class="d-none" id="right-pane"></div>

</div>

@if ($vendor->canApprove())
    @include('vendors.reject-modal')
@endif

@endsection

@section('scripts')
	<script src="{{ asset('js/pdfobject.min.js') }}"></script>
	<script src="{{ asset('js/show.js') }}"></script>
	{{-- <script src="{{ asset('js/displayfile.js') }}"></script> --}} <!-- Commented out as per your previous instruction -->
	<script src="{{ asset('js/vendor.js') }}"></script>
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
		var form = $("#rejectForm").html();

		$('#reject').click(function(e) {
			dialog = bootbox.confirm({
				message: form,
				buttons: {
					cancel: {
						label: 'Batal',
						className: 'btn-light border'
					},
					confirm: {
						label: 'Tolak',
						className: 'btn-danger'
					}
				},
				callback: function(result) {
					var reason = dialog[0].querySelector("[name=reason]").value;
					var template = Array.from(dialog[0].querySelectorAll(
						"input[type=checkbox][name=template]:checked"), e => e.value);

					if (result && (reason != '' || template.length != 0)) {
						$.post('/vendor/{{ $vendor->id }}/reject', {
								reason: reason,
								template: template
							})
							.success(function() {
								window.location.href = '{{ route('vendors.show', $vendor->id) }}';
							})
					}
				}
			});
		});
	</script>
@endsection