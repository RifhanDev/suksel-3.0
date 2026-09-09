/**
 * Malaysian IC inputs: strip non-digits and cap at 12.
 * Mark fields with data-ic-input (or class js-ic-input).
 */
(function (window, document) {
    'use strict';

    function normalizeIc(value) {
        return String(value || '').replace(/\D+/g, '').slice(0, 12);
    }

    function applyToInput(el) {
        if (!el || el.dataset.icBound === '1') return;
        el.dataset.icBound = '1';
        el.setAttribute('maxlength', '12');
        el.setAttribute('inputmode', 'numeric');
        el.setAttribute('autocomplete', 'off');

        function sanitize() {
            var cleaned = normalizeIc(el.value);
            if (el.value !== cleaned) {
                el.value = cleaned;
            }
        }

        el.addEventListener('input', sanitize);
        el.addEventListener('blur', sanitize);
        el.addEventListener('paste', function () {
            setTimeout(sanitize, 0);
        });

        sanitize();
    }

    function bindAll(root) {
        var scope = root || document;
        scope.querySelectorAll('[data-ic-input], .js-ic-input, .rep-ic, #ic_number, #ic').forEach(applyToInput);
    }

    window.normalizeIcNumber = normalizeIc;
    window.bindIcInputs = bindAll;

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            bindAll(document);
        });
    } else {
        bindAll(document);
    }

    document.addEventListener('shown.bs.modal', function (e) {
        bindAll(e.target || document);
    });
})(window, document);
