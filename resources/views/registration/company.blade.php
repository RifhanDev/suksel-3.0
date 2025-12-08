@extends('layouts.v3.master')

@section('styles')
<style>
    /* ===== LAYOUT & CONTAINER ===== */
    .modern-form-wrapper {
        padding: 1.5rem;
        display: block; 
    }

    .modern-form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: 1px solid #e2e8f0;
        /* CRITICAL FIX: Overflow visible allows dropdowns to show */
        overflow: visible; 
    }

    /* ===== TABS NAVIGATION ===== */
    .modern-tabs-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 0 1.5rem;
        border-radius: 12px 12px 0 0;
    }

    .modern-nav-tabs {
        display: flex;
        flex-wrap: wrap;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 2px;
    }

    .modern-nav-tabs li a {
        display: block;
        padding: 1rem 1.5rem;
        color: #64748b;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }

    .modern-nav-tabs li a:hover {
        color: #1e293b;
        background: rgba(196, 30, 58, 0.05);
        border-bottom-color: rgba(196, 30, 58, 0.3);
    }

    .modern-nav-tabs li.active a {
        color: #fff;
        background: linear-gradient(135deg, #c41e3a 0%, #a01830 100%);
        border-bottom-color: #8b1428;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(196, 30, 58, 0.2);
    }

    /* Disabled/Locked tabs */
    .modern-nav-tabs li.disabled a {
        color: #cbd5e1;
        cursor: not-allowed;
        opacity: 0.5;
    }

    .modern-nav-tabs li.disabled a:hover {
        background: transparent;
        border-bottom-color: transparent;
    }

    /* ===== CONTENT AREA ===== */
    .modern-tab-content {
        padding: 2rem;
        background: white;
        /* CRITICAL FIX: Overflow visible allows dropdowns to show */
        overflow: visible;
        border-radius: 0 0 12px 12px;
        min-height: 500px;
    }

    .modern-tab-content .tab-pane {
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ===== FORM GRID SYSTEM ===== */
    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        row-gap: 20px;
        align-items: start;
    }

    .form-grid-1 {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
    }

    /* General Form */
    .modern-tab-content .form-group {
        margin-bottom: 20px !important;  /* Vertical spacing between fields */
        display: flex;
        flex-direction: column;
        position: relative; /* Context for z-index */
    }

    .modern-tab-content .form-group:last-child {
        margin-bottom: 0 !important;
    }

    /* ===== SELECTIZE FIXES - Hide Original Select & Fix Dropdown ===== */

    /* CRITICAL: Hide the original select elements that selectize replaces */
    .modern-tab-content .form-group select.selectize,
    .modern-tab-content .form-group select.selectized,
    .modern-tab-content select[style*="display: none"],
    .modern-tab-content .selectize-control > select {
        display: none !important;
        height: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
        position: absolute !important;
        opacity: 0 !important;
        pointer-events: none !important;
        z-index: -1 !important;
    }

    /* Selectize Dropdown - FIX TRANSPARENCY ISSUE */
    .modern-tab-content .selectize-dropdown {
        background: white !important;  /* Solid white background - fixes transparency */
        z-index: 9999 !important;      /* High z-index to appear above everything */
    }

    .modern-tab-content .control-label {
        text-align: left !important;
        width: 100% !important;
        margin-bottom: 8px !important;
        padding-top: 0 !important;
        font-weight: 600;
        color: #334155;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modern-tab-content .control-label sup {
        color: #ef4444;
        top: -2px;
    }

    /* Reset Bootstrap Columns */
    .modern-tab-content .col-lg-12, .modern-tab-content .col-lg-9, 
    .modern-tab-content .col-sm-9, .modern-tab-content .col-lg-3,
    .modern-tab-content .col-sm-3, .modern-tab-content .col-md-6 {
        width: 100% !important;
        padding: 0 !important;
        float: none !important;
    }

    /* IMPORTANT: Exclude selectize-control from form-control styles to prevent overlap */
    .modern-tab-content .form-control:not(.selectize-control) {
        border: 1px solid #cbd5e1;
        box-shadow: none;
        padding: 8px 12px;
        font-size: 0.95rem;
        width: 100% !important;
    }

    /* Reset styles for selectize-control wrapper (remove form-control inheritance) */
    .modern-tab-content .selectize-control.form-control {
        height: auto !important;
        border: none !important;
        padding: 0 !important;
        box-shadow: none !important;
        background: transparent !important;
    }

    .modern-tab-content .form-control:not(.selectize-control):focus {
        border-color: #c41e3a;
        box-shadow: 0 0 0 3px rgba(196, 30, 58, 0.1);
        z-index: 5; /* Bring to front on focus */
    }

    .modern-input-group {
        display: flex;
        align-items: center;
        width: 100%;
    }

    /* LEFT Element (e.g., Currency Select) */
    .modern-input-group > :first-child {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        border-right: 0; 
        z-index: 2;
    }

    /* RIGHT Element (e.g., Input Value) */
    .modern-input-group > :last-child {
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        border-left: 1px solid #cbd5e1; /* Re-add border to separate */
    }

    /* MIDDLE Element (e.g., "Hingga" text) */
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
        border-radius: 0 !important; /* Sharp corners */
    }

    /* Focus state fix for groups */
    .modern-input-group > *:focus {
        z-index: 10;
        border-color: #c41e3a;
        position: relative;
    }

    /* Specific Width for Currency Select */
    .currency-select {
        flex: 0 0 100px !important; /* Fixed width for currency */
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

    .clean-table tbody td, .clean-table tfoot td {
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .clean-table tbody tr:last-child td { border-bottom: none; }
    .clean-table tbody tr:hover { background: #f8fafc; }

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

    @media (max-width: 992px) {
        .form-grid-2 { grid-template-columns: 1fr; }
        .repeater-item { flex-direction: column; align-items: stretch; }
        .repeater-item .fields-wrapper { grid-template-columns: 1fr; }
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
	<script>
		console.log('SCRIPT SECTION IS LOADING');
		alert('SCRIPT SECTION IS LOADING');
	</script>
	<script src="{{ asset('js/definitions.js') }}"></script>
	<script src="{{ asset('js/vendor.js') }}"></script>
	<script>
		console.log('Before document ready');

		// Tab Navigation System - SIMPLIFIED VERSION
		$(document).ready(function() {
			console.log('Tab navigation script loaded');

			let unlockedTabs = ['#vf-main']; // First tab is always unlocked
			const allTabs = ['#vf-main', '#vf-officer', '#vf-mof', '#vf-cidb', '#vf-district'];
			let currentTabIndex = 0;

			// Mark all tabs except first as disabled initially
			$('.modern-nav-tabs li').each(function(index) {
				if (index > 0) {
					$(this).addClass('disabled');
				}
			});

			// INTERCEPT Bootstrap tab click BEFORE it shows
			$('.modern-nav-tabs li a[data-toggle="tab"]').on('show.bs.tab', function(e) {
				const targetTab = $(e.target).attr('href');
				console.log('Attempting to show tab:', targetTab);
				console.log('Unlocked tabs:', unlockedTabs);

				// If tab is NOT unlocked, prevent showing it
				if (!unlockedTabs.includes(targetTab)) {
					console.log('Tab is locked, preventing navigation');
					e.preventDefault();
					return false;
				}

				console.log('Tab is unlocked, allowing navigation');
				// Update current index
				currentTabIndex = allTabs.indexOf(targetTab);
			});

			// Update UI after tab is shown
			$('.modern-nav-tabs li a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
				const targetTab = $(e.target).attr('href');
				currentTabIndex = allTabs.indexOf(targetTab);
				updateButtonVisibility();
				console.log('Switched to tab:', targetTab, 'Index:', currentTabIndex);
			});

			// "Seterusnya" (Next) button
			$('#next').on('click', function() {
				console.log('Next button clicked');
				const currentTab = $('.tab-pane.active');
				let isValid = true;

				// Validate required fields
				currentTab.find('[required]:visible').each(function() {
					if (!this.checkValidity()) {
						isValid = false;
						$(this).closest('.form-group').addClass('has-error');
					} else {
						$(this).closest('.form-group').removeClass('has-error');
					}
				});

				if (!isValid) {
					alert('Sila lengkapkan semua medan yang diwajibkan.');
					return;
				}

				// Move to next tab
				if (currentTabIndex < allTabs.length - 1) {
					currentTabIndex++;
					const nextTab = allTabs[currentTabIndex];
					console.log('Moving to next tab:', nextTab);

					// Unlock the next tab
					if (!unlockedTabs.includes(nextTab)) {
						unlockedTabs.push(nextTab);
						$('.modern-nav-tabs li a[href="' + nextTab + '"]').parent().removeClass('disabled');
						console.log('Unlocked tab:', nextTab);
					}

					// Activate next tab using Bootstrap
					$('.modern-nav-tabs li a[href="' + nextTab + '"]').tab('show');
				}
			});

			// Update button visibility
			function updateButtonVisibility() {
				if (currentTabIndex === allTabs.length - 1) {
					$('#next').hide();
					$('#submit').show();
				} else {
					$('#next').show();
					$('#submit').hide();
				}
			}

			// "Hantar" (Submit) button
			$('#submit').on('click', function() {
				const form = $(this).closest('form');
				let isValid = true;

				form.find('[required]:visible').each(function() {
					if (!this.checkValidity()) {
						isValid = false;
						$(this).closest('.form-group').addClass('has-error');
					}
				});

				if (!isValid) {
					alert('Sila lengkapkan semua medan yang diwajibkan sebelum menghantar borang.');
					return;
				}

				form.submit();
			});

			// Initialize
			updateButtonVisibility();
			console.log('Initial unlocked tabs:', unlockedTabs);
		});
	</script>
@endsection