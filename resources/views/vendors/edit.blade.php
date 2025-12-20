@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/tabs.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/form-components.css') }}" rel="stylesheet">

    <style>
        .currency-select {
            flex: 0 0 100px !important;
            width: 100px !important;
            background-color: #f8fafc;
        }

        /* Checkbox Alignment */
        .checkbox-align-wrapper {
            height: 42px;
            display: flex;
            align-items: center;
            padding-bottom: 40px;
        }

        /* ===== ACTION FOOTER ===== */
        .modern-form-actions {
            padding: 1.5rem 2rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 0 0 12px 12px;
        }

        /* ===== BUTTONS ===== */
        .modern-btn {
            padding: 12px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .modern-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .modern-btn:active {
            transform: translateY(0);
        }

        .modern-btn-back {
            background: white;
            color: #64748b;
            border: 2px solid #e2e8f0;
        }

        .modern-btn-back:hover {
            background: #f8fafc;
            color: #334155;
            border-color: #cbd5e1;
        }

        .modern-btn-next {
            background: linear-gradient(135deg, #c41e3a 0%, #a01830 100%);
            color: white;
            border: none;
        }

        .modern-btn-next:hover {
            background: linear-gradient(135deg, #a01830 0%, #8b1428 100%);
            box-shadow: 0 6px 20px rgba(196, 30, 58, 0.4);
        }

        .modern-btn-submit {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            border: none;
        }

        .modern-btn-submit:hover {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
            box-shadow: 0 6px 20px rgba(5, 150, 105, 0.4);
        }

        .modern-btn svg {
            width: 20px;
            height: 20px;
            stroke-width: 2.5;
        }
    </style>
@endsection

@section('content')
	<!-- HEADER -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="d-flex flex-wrap align-items-center gap-3 mb-3 mb-lg-0">

			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">
				Kemaskini Syarikat
			</h3>

			<span class="text-muted opacity-25" style="font-size: 1.75rem; font-weight: 300; line-height: 1; margin-top: -4px;">|</span>

			<h3 class="fw-bold m-0 text-truncate" style="color: var(--sg-red); letter-spacing: -0.5px; max-width: 500px;">
				{{ $vendor->name }}
			</h3>

		</div>

		@if(isset($vendor) && $vendor->registration)
		<div class="d-flex align-items-center gap-2 bg-white px-3 py-2 rounded-2 shadow-sm border">
			<span class="badge bg-light text-dark border" style="font-size: 0.65rem;">NO. PENDAFTARAN</span>
			<span class="small fw-bold text-dark font-monospace">{{ $vendor->registration }}</span>
		</div>
		@endif

    </div>

    <!-- FORM CARD -->
    <div class="modern-form-card">
        {!! Former::open_for_files(url('vendors/' . $vendor->id))->addClass('form-uppercase jq-validate') !!}
        {!! Former::populate($vendor) !!}
        {!! Former::hidden('_method', 'PUT') !!}

        @include('vendors.form')

        <div class="modern-form-actions">
            @if (!Auth::user()->hasRole('Vendor') && App\Vendor::canList())
                <a href="{{ asset('vendors') }}" class="modern-btn modern-btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Senarai Syarikat
                </a>
            @elseif (Auth::user()->hasRole('Vendor'))
                <a href="{{ asset('vendor') }}" class="modern-btn modern-btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Maklumat Syarikat
                </a>
            @else
                <a href="{{ asset('dashboard') }}" class="modern-btn modern-btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Kembali
                </a>
            @endif

            <div style="display: flex; gap: 12px;">
                <button type="button" id="next" class="modern-btn modern-btn-next">
                    Seterusnya
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </button>
                <button type="submit" id="submit" class="modern-btn modern-btn-submit" style="display: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    Kemaskini
                </button>
            </div>
        </div>
        {!! Former::close() !!}
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        (function () {
            function getTabs() {
                return document.querySelectorAll('.modern-nav-tabs .nav-item');
            }

            function getActiveTabIndex() {
                var tabs = getTabs();
                var activeLink = document.querySelector('.modern-nav-tabs .nav-link.active');
                if (!activeLink) {
                    return 0;
                }
                var activeTab = activeLink.closest('.nav-item');
                return Array.prototype.indexOf.call(tabs, activeTab);
            }

            function getActivePane() {
                var activeLink = document.querySelector('.modern-nav-tabs .nav-link.active');
                if (!activeLink) {
                    return null;
                }
                var target = activeLink.getAttribute('href');
                if (!target) {
                    return null;
                }
                return document.querySelector(target);
            }

            function validateActiveTab() {
                var validator = window.VendorForm && window.VendorForm.validators && window.VendorForm.validators.main;
                var pane = getActivePane();
                if (!validator || !pane) {
                    return true;
                }

                var isValid = true;
                var inputs = pane.querySelectorAll('input, select, textarea');
                inputs.forEach(function (input) {
                    validator.touched.add(input);
                    if (!validator.validateInput(input)) {
                        isValid = false;
                    }
                });

                if (!isValid && typeof validator.focusFirstError === 'function') {
                    validator.focusFirstError();
                }

                return isValid;
            }

            function allowTabNavigation(tab) {
                if (!tab) {
                    return;
                }

                tab.classList.remove('tab-disabled');
                tab.classList.remove('disabled');
                var link = tab.querySelector('a');
                if (link) {
                    link.classList.remove('disabled');
                    link.removeAttribute('tabindex');
                    link.removeAttribute('aria-disabled');
                }
            }

            function goToTab(index) {
                var tabs = getTabs();
                if (index < 0 || index >= tabs.length) {
                    return;
                }

                if (window.VendorTabManager && typeof window.VendorTabManager.enableTabByIndex === 'function') {
                    window.VendorTabManager.enableTabByIndex(index);
                } else {
                    allowTabNavigation(tabs[index]);
                }

                var link = tabs[index].querySelector('a');
                if (link) {
                    link.click();
                }
            }

            function updateActionButtons() {
                var tabs = getTabs();
                var currentIndex = getActiveTabIndex();
                var nextButton = document.getElementById('next');
                var submitButton = document.getElementById('submit');

                if (!nextButton || !submitButton || tabs.length === 0) {
                    return;
                }

                if (currentIndex >= tabs.length - 1) {
                    nextButton.style.display = 'none';
                    submitButton.style.display = 'inline-flex';
                } else {
                    nextButton.style.display = 'inline-flex';
                    submitButton.style.display = 'none';
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                console.log('Vendor edit: Next button handler initializing...');
                var nextButton = document.getElementById('next');
                if (!nextButton) {
                    console.error('Next button not found!');
                    return;
                }

                console.log('Next button found:', nextButton);
                updateActionButtons();

                nextButton.addEventListener('click', function (e) {
                    console.log('Next button clicked!');
                    console.log('VendorForm:', window.VendorForm);
                    console.log('Active tab index:', getActiveTabIndex());

                    if (!validateActiveTab()) {
                        console.log('Validation failed, staying on current tab');
                        return;
                    }

                    console.log('Validation passed, moving to next tab');
                    var nextIndex = getActiveTabIndex() + 1;
                    console.log('Next tab index:', nextIndex);
                    goToTab(nextIndex);
                    updateActionButtons();
                });

                document.addEventListener('shown.bs.tab', function (event) {
                    if (event.target && event.target.closest('.modern-nav-tabs')) {
                        updateActionButtons();
                        setTimeout(function () {
                            var pane = getActivePane();
                            if (pane) {
                                pane.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            }
                        }, 0);
                    }
                });

                // PDF Viewer Modal Handler
                document.body.addEventListener('click', function(e) {
                    var target = e.target.closest('.view-pdf-btn, .btn-file-view');

                    if (target) {
                        e.preventDefault();

                        var url = target.getAttribute('data-url') || target.getAttribute('href');

                        if (url) {
                            // Open Modal
                            var iframe = document.getElementById('pdfIframe');
                            var modalEl = document.getElementById('pdfViewerModal');

                            if (iframe && modalEl) {
                                iframe.src = url;
                                var myModal = new bootstrap.Modal(modalEl);
                                myModal.show();
                            }
                        }
                    }
                });

                var pdfModal = document.getElementById('pdfViewerModal');
                if (pdfModal) {
                    pdfModal.addEventListener('hidden.bs.modal', function () {
                        document.getElementById('pdfIframe').src = '';
                    });
                }
            });
        })();
    </script>
@endsection

<!-- PDF MODAL -->
@push('modals')
<div class="modal fade" id="pdfViewerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content h-100 border-0 shadow-lg rounded-3">
            <div class="modal-body p-0 bg-light">
                <iframe id="pdfIframe" src="" width="100%" height="100%" style="border:none; min-height: 85vh;"></iframe>
            </div>
        </div>
    </div>
</div>
@endpush
