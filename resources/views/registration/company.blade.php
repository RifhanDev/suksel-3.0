@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/tabs.css') }}" rel="stylesheet">

    <style>
        /* ===== INPUT GROUPS ===== */
        .modern-input-group {
            display: flex;
            align-items: center;
            width: 100%;
        }

        .modern-input-group > :first-child {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
            border-right: 0;
            z-index: 2;
        }

        .modern-input-group > :last-child {
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            border-left: 1px solid #cbd5e1;
        }

        .modern-input-group .addon {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-left: 0;
            border-right: 0;
            padding: 0 15px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 600;
            color: #64748b;
            white-space: nowrap;
            border-radius: 0 !important;
        }

        .modern-input-group > *:focus {
            z-index: 10;
            border-color: #c41e3a;
            position: relative;
        }

        .currency-select {
            flex: 0 0 100px !important;
            width: 100px !important;
            background-color: #f8fafc;
        }

        /* ===== ALIGNMENT HELPERS ===== */
        .checkbox-align-wrapper {
            height: 42px;
            display: flex;
            align-items: center;
            padding-bottom: 40px;
        }

        /* ===== TABLES ===== */
        .clean-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .clean-table thead th {
            background: #f1f5f9;
            color: #475569;
            font-weight: 700;
            padding: 12px 16px;
            text-transform: uppercase;
            font-size: 0.8rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .clean-table tbody td,
        .clean-table tfoot td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .clean-table tbody tr:last-child td {
            border-bottom: none;
        }

        .clean-table tbody tr:hover {
            background: #f8fafc;
        }

        /* ===== REPEATER ===== */
        .repeater-item {
            display: flex;
            gap: 15px;
            align-items: flex-end;
            background: white;
            padding: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .repeater-item .fields-wrapper {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .btn-add-repeater {
            width: 100%;
            border: 2px dashed #cbd5e1;
            background: white;
            color: #64748b;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.2s;
            text-align: center;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-add-repeater:hover {
            border-color: #c41e3a;
            color: #c41e3a;
            background: #fff1f2;
        }

        /* ===== ICONS ===== */
        .icon-svg {
            width: 18px;
            height: 18px;
            stroke-width: 2;
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
            border-radius: 8px;
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

        /* Back Button */
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

        /* Next Button */
        .modern-btn-next {
            background: linear-gradient(135deg, #c41e3a 0%, #a01830 100%);
            color: white;
            border: none;
        }

        .modern-btn-next:hover {
            background: linear-gradient(135deg, #a01830 0%, #8b1428 100%);
            box-shadow: 0 6px 20px rgba(196, 30, 58, 0.4);
        }

        /* Submit Button */
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

        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .form-grid-2 {
                grid-template-columns: 1fr;
            }

            .repeater-item {
                flex-direction: column;
                align-items: stretch;
            }

            .repeater-item .fields-wrapper {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
	<div class="modern-form-wrapper">
		<div class="modern-form-card">
			<div class="modern-tabs-header">
                <div style="padding: 1.5rem 0;">
				    <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #1e293b; font-family: 'Poppins', sans-serif;">Pendaftaran Syarikat</h2>
                </div>
			</div>

			{!! Former::open_for_files(action('RegistrationController@storeCompany'))->addClass('form-uppercase jq-validate') !!}
				{!! Former::populate($vendor) !!}
				{!! Former::hidden('_method', 'PUT') !!}
				
                @include('vendors.form')

				<div class="modern-form-actions">
					<a href="{{ asset('dashboard')}}" class="modern-btn modern-btn-back">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        Kembali
					</a>
					<div style="display: flex; gap: 12px;">
						<button type="button" id="next" class="modern-btn modern-btn-next">
							Seterusnya
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
						</button>
						<button type="button" id="submit" class="modern-btn modern-btn-submit" style="display: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
							Hantar
						</button>
					</div>
				</div>
			{!! Former::close() !!}
		</div>
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
                console.log('Company registration: Next button handler initializing...');
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
            });
        })();
    </script>
@endsection

