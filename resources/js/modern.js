/**
 * Modern Layout JS
 * Uses Bootstrap 5 and jQuery
 */

// Load jQuery
window.$ = window.jQuery = require('jquery');

// Load Bootstrap 5
import * as bootstrap from 'bootstrap5';
window.bootstrap = bootstrap;

// Initialize Bootstrap 5 components
document.addEventListener('DOMContentLoaded', function() {
    console.log('Modern layout loaded with Bootstrap 5');

    // Initialize all tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize all popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Initialize all modals
    const modalList = [].slice.call(document.querySelectorAll('.modal'));
    modalList.map(function (modalEl) {
        return new bootstrap.Modal(modalEl);
    });
});

// CSRF Token for AJAX
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
});
