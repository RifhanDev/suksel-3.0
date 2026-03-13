@extends('layouts.modernLanding')

@section('styles')
    <link href="{{ asset('css/components/tabs.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/form-components.css') }}" rel="stylesheet">

    <style>
        /* ===== PAGE-SPECIFIC STYLES ===== */

        /* Currency Select */
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

        /* ===== MODERN BUTTONS ===== */
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
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Pendaftaran Syarikat</h3>
            <p class="text-muted small m-0">Sila lengkapkan maklumat syarikat anda.</p>
        </div>

        @if (isset($vendor) && $vendor->registration)
            <div class="d-flex flex-wrap align-items-center gap-3 bg-white px-3 py-2 rounded-2 shadow-sm border">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light text-dark border" style="font-size: 0.65rem;">NO. PENDAFTARAN</span>
                    <span class="small fw-bold text-dark">{{ $vendor->registration }}</span>
                </div>
                @if ($vendor->approval_1_id)
                    <div class="vr d-none d-lg-block text-muted opacity-25" style="height: 20px;"></div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-light text-dark border" style="font-size: 0.65rem;">STATUS</span>
                        <span class="small fw-bold" style="color: #059669;">Disahkan</span>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- FORM CARD -->
    <div class="modern-form-card">
        {!! Former::open_for_files(action('RegistrationController@storeCompany'))->addClass(
            'form-uppercase jq-validate',
        ) !!}
        {!! Former::populate($vendor) !!}
        {!! Former::hidden('_method', 'PUT') !!}

        @include('vendors.form')

        <div class="modern-form-actions">
            <a href="{{ asset('dashboard') }}" class="modern-btn modern-btn-back">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali ke Laman Utama
            </a>
            <div style="display: flex; gap: 12px;">
                <button type="button" id="next" class="modern-btn modern-btn-next">
                    Seterusnya
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>
                <button type="submit" id="submit" class="modern-btn modern-btn-submit" style="display: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Hantar
                </button>
            </div>
        </div>
        {!! Former::close() !!}
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        (function() {
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
                inputs.forEach(function(input) {
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

            document.addEventListener('DOMContentLoaded', function() {
                console.log('Company registration: Next button handler initializing...');
                var nextButton = document.getElementById('next');
                if (!nextButton) {
                    console.error('Next button not found!');
                    return;
                }

                console.log('Next button found:', nextButton);
                updateActionButtons();

                nextButton.addEventListener('click', function(e) {
                    console.log('Next button clicked');

                    if (!validateActiveTab()) {
                        return;
                    }

                    // MOF/CIDB Validation Logic when leaving CIDB tab
                    var activePane = getActivePane();
                    if (activePane && activePane.id === 'vf-cidb') {
                        var mofStart = document.getElementById('mof_start_date');
                        var mofEnd = document.getElementById('mof_end_date');
                        var mofRef = document.getElementById('mof_ref_no');
                        var mofCodes = document.getElementById('mof_codes');

                        var cidbStart = document.getElementById('cidb_start_date');
                        var cidbEnd = document.getElementById('cidb_end_date');
                        var cidbRef = document.getElementById('cidb_ref_no');

                        var mofFilled = true;
                        if (!mofStart || !mofStart.value) mofFilled = false;
                        if (!mofEnd || !mofEnd.value) mofFilled = false;
                        if (!mofRef || !mofRef.value) mofFilled = false;
                        if (mofCodes && mofCodes.selectize) {
                            if (mofCodes.selectize.getValue().length === 0) mofFilled = false;
                        } else if (mofCodes && mofCodes.selectedOptions.length === 0) {
                            mofFilled = false;
                        }

                        var cidbFilled = true;
                        if (!cidbStart || !cidbStart.value) cidbFilled = false;
                        if (!cidbEnd || !cidbEnd.value) cidbFilled = false;
                        if (!cidbRef || !cidbRef.value) cidbFilled = false;

                        if (!mofFilled && !cidbFilled) {
                            if (window.VendorForm && window.VendorForm.alert) {
                                window.VendorForm.alert(
                                    'Sila pastikan salah satu maklumat MOF atau CIDB di isi dengan lengkap.'
                                );
                            } else {
                                alert(
                                    'Sila pastikan salah satu maklumat MOF atau CIDB di isi dengan lengkap.'
                                );
                            }
                            return;
                        }

                        // Toggle required attribute on file inputs based on data filled
                        var cidbFile = document.querySelector('input[name="cidb"][type="file"]');
                        if (cidbFile) {
                            if (cidbFilled) cidbFile.setAttribute('required', 'required');
                            // else cidbFile.removeAttribute('required'); // Don't remove if it was originally required by backend
                        }

                        var mofFile = document.querySelector('input[name="mof"][type="file"]');
                        if (mofFile) {
                            if (mofFilled) mofFile.setAttribute('required', 'required');
                            // else mofFile.removeAttribute('required');
                        }
                    }

                    var nextIndex = getActiveTabIndex() + 1;
                    goToTab(nextIndex);
                    updateActionButtons();
                });

                // Submit Button Handler
                var submitButton = document.getElementById('submit');
                if (submitButton) {
                    submitButton.addEventListener('click', function(e) {
                        e.preventDefault();

                        // Validate current tab first
                        if (!validateActiveTab()) return;

                        // Check required file uploads
                        var fileErrors = [];
                        var fileInputs = document.querySelectorAll('input[type="file"][required]');

                        fileInputs.forEach(function(input) {
                            if (!input.value) {
                                // Check if there is a "file uploaded" indicator if needed, 
                                // but standard backend logic removes 'required' if file exists.
                                // So here, if required is present, value MUST be present.
                                var label = input.closest('.form-group') ? input.closest(
                                        '.form-group').querySelector('label').innerText : input
                                    .name;
                                fileErrors.push(label.replace('*', '').trim());
                            }
                        });

                        if (fileErrors.length > 0) {
                            var msg = 'Sila muat naik fail yang diperlukan: ' + fileErrors[0];
                            if (window.VendorForm && window.VendorForm.alert) {
                                window.VendorForm.alert(msg);
                            } else {
                                alert(msg);
                            }
                            return;
                        }

                        // Final Confirmation
                        var disclaimer =
                            "Saya mengaku bahawa maklumat yang diberikan adalah benar.<br><br>Pihak SUK Selangor berhak menolak / tidak meluluskan permohonan ini pada bila-bila masa sekiranya maklumat / keterangan yang saya kemukakan adalah tidak benar.<br><br>Saya juga memberi kuasa kepada pihak SUK Selangor untuk mendapatkan pengesahan daripada mana-mana sumber yang difikirkan benar.<br><br>Pihak SUK Selangor tidak akan dipertanggungjawabkan sekiranya berlaku kesilapan dan kesalahan semasa mengisi borang ini.";

                        if (window.VendorForm && window.VendorForm.confirm) {
                            window.VendorForm.confirm(disclaimer, function(result) {
                                if (result) {
                                    var form = document.querySelector('form.jq-validate');
                                    if (form) {
                                        HTMLFormElement.prototype.submit.call(form);
                                    }
                                }
                            });
                        } else {
                            if (confirm(disclaimer.replace(/<[^>]*>/g, ''))) {
                                var form = document.querySelector('form.jq-validate');
                                if (form) {
                                    HTMLFormElement.prototype.submit.call(form);
                                }
                            }
                        }
                    });
                }

                document.addEventListener('shown.bs.tab', function(event) {
                    if (event.target && event.target.closest('.modern-nav-tabs')) {
                        updateActionButtons();
                        // Scroll to page title to ensure full context
                        setTimeout(function() {
                            var pageTitle = document.querySelector('h3.fw-bold');
                            if (pageTitle) {
                                pageTitle.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'start'
                                });
                            } else {
                                // Fallback to tabs container
                                var tabsContainer = document.querySelector('.modern-nav-tabs');
                                if (tabsContainer) {
                                    tabsContainer.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'start'
                                    });
                                }
                            }
                        }, 100);
                    }
                });
            });
        })();
    </script>
@endsection
