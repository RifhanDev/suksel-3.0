/**
 * Client-side guard for attachment uploads.
 * Allowed: pdf, png, jpg, jpeg, xls, xlsx, doc, docx
 */
(function () {
    'use strict';

    var ALLOWED = {
        pdf: true,
        png: true,
        jpg: true,
        jpeg: true,
        xls: true,
        xlsx: true,
        doc: true,
        docx: true
    };
    var ACCEPT = '.pdf,.png,.jpg,.jpeg,.xls,.xlsx,.doc,.docx';
    var MESSAGE = 'Format fail tidak dibenarkan. Hanya PDF, PNG, JPG, JPEG, Excel (xls, xlsx) dan Word (doc, docx).';

    function extensionOf(name) {
        var parts = String(name || '').toLowerCase().split('.');
        return parts.length > 1 ? parts.pop() : '';
    }

    function isAllowedFile(file) {
        if (!file || !file.name) return false;
        return !!ALLOWED[extensionOf(file.name)];
    }

    function notify(message) {
        if (window.SukselToast && typeof window.SukselToast.error === 'function') {
            window.SukselToast.error(message);
            return;
        }
        if (typeof window.showToast === 'function') {
            window.showToast(message, 'error');
            return;
        }
        window.alert(message);
    }

    function applyAccept(input) {
        if (!input || input.dataset.uploadRestrictBound === '1') return;
        input.dataset.uploadRestrictBound = '1';

        var current = (input.getAttribute('accept') || '').trim();
        // Keep stricter PDF-only / image-only inputs as-is; only widen or replace open ones.
        if (!current || /zip|rar|image\/\*|\/\*/i.test(current)) {
            input.setAttribute('accept', ACCEPT);
        }
    }

    function bindInput(input) {
        if (!input || input.type !== 'file') return;
        applyAccept(input);

        if (input.dataset.uploadRestrictListener === '1') return;
        input.dataset.uploadRestrictListener = '1';

        input.addEventListener('change', function () {
            var files = input.files ? Array.prototype.slice.call(input.files) : [];
            if (!files.length) return;

            var bad = files.filter(function (file) {
                return !isAllowedFile(file);
            });
            if (!bad.length) return;

            input.value = '';
            notify(MESSAGE);
        });
    }

    function scan(root) {
        (root || document).querySelectorAll('input[type="file"]').forEach(bindInput);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            scan(document);
        });
    } else {
        scan(document);
    }

    document.addEventListener('shown.bs.modal', function (e) {
        scan(e.target || document);
    });

    if (typeof MutationObserver !== 'undefined') {
        var observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (m) {
                m.addedNodes.forEach(function (node) {
                    if (!(node instanceof HTMLElement)) return;
                    if (node.matches && node.matches('input[type="file"]')) {
                        bindInput(node);
                    }
                    scan(node);
                });
            });
        });
        observer.observe(document.documentElement, { childList: true, subtree: true });
    }

    window.SukselAllowedAttachment = {
        accept: ACCEPT,
        isAllowed: isAllowedFile,
        message: MESSAGE,
        scan: scan
    };
})();
