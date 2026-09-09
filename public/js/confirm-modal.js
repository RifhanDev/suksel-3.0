/**
 * Shared Ya/Batal confirm dialog (vanilla JS — no jQuery required at load time).
 * Requires #modalConfirmDialog from components.confirm-dialog-modal (layouts.v3.master).
 *
 * showConfirmModal('Hantar spesifikasi ini?')
 * showConfirmModal({ title, message, html, icon, confirmText, cancelText, showCancel })
 *
 * Returns a Promise that resolves true on confirm, false on cancel/dismiss.
 */
(function (window) {
    'use strict';

    function normalizeOptions(options) {
        if (typeof options === 'string') {
            options = { message: options };
        }
        options = options || {};
        return {
            title: options.title || 'Sahkan',
            message: options.message || '',
            html: options.html || '',
            icon: options.icon || 'warning',
            confirmText: options.confirmText || 'Ya, Teruskan',
            cancelText: options.cancelText || 'Batal',
            showCancel: options.showCancel !== false,
        };
    }

    function showConfirmModal(options) {
        var opts = normalizeOptions(options);

        return new Promise(function (resolve) {
            var el = document.getElementById('modalConfirmDialog');
            if (!el || typeof bootstrap === 'undefined' || !bootstrap.Modal) {
                resolve(window.confirm(opts.message || opts.title));
                return;
            }

            document.getElementById('confirmDialogTitle').textContent = opts.title;
            var body = document.getElementById('confirmDialogBody');
            if (opts.html) {
                body.innerHTML = opts.html;
            } else {
                body.textContent = opts.message;
            }

            ['warning', 'info', 'danger', 'success'].forEach(function (name) {
                var icon = document.getElementById(
                    'confirmDialogIcon' + name.charAt(0).toUpperCase() + name.slice(1)
                );
                if (icon) {
                    icon.classList.toggle('d-none', name !== opts.icon);
                }
            });

            var confirmBtn = document.getElementById('confirmDialogConfirm');
            var cancelBtn = document.getElementById('confirmDialogCancel');
            confirmBtn.textContent = opts.confirmText;
            cancelBtn.textContent = opts.cancelText;
            cancelBtn.classList.toggle('d-none', !opts.showCancel);

            var settled = false;
            function settle(value) {
                if (settled) return;
                settled = true;
                confirmBtn.removeEventListener('click', onConfirm);
                cancelBtn.removeEventListener('click', onCancel);
                resolve(value);
            }

            function onConfirm() {
                settle(true);
                bootstrap.Modal.getInstance(el).hide();
            }

            function onCancel() {
                bootstrap.Modal.getInstance(el).hide();
            }

            function onHidden() {
                el.removeEventListener('hidden.bs.modal', onHidden);
                settle(false);
            }

            confirmBtn.addEventListener('click', onConfirm);
            cancelBtn.addEventListener('click', onCancel);
            el.addEventListener('hidden.bs.modal', onHidden, { once: true });

            bootstrap.Modal.getOrCreateInstance(el).show();
        });
    }

    window.showConfirmModal = showConfirmModal;
})(window);
