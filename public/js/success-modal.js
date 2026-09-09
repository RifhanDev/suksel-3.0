/**
 * Shared Berjaya success modal helper (vanilla JS — no jQuery required at load time).
 * Requires #stosSuccessModal from components.success-modal (layouts.v3.master).
 *
 * showBerjayaModal()
 * showBerjayaModal('Custom message')
 * showBerjayaModal({ title, message, onClose })
 */
(function (window) {
    'use strict';

    var DEFAULT_TITLE = 'Berjaya';
    var DEFAULT_MESSAGE = 'Maklumat telah berjaya disimpan.';
    var modalInstance = null;
    var pendingOnClose = null;
    var hiddenBound = false;

    function getModalElement() {
        return document.getElementById('stosSuccessModal');
    }

    function getModal() {
        var el = getModalElement();
        if (!el || typeof bootstrap === 'undefined' || !bootstrap.Modal) {
            return null;
        }
        if (!modalInstance) {
            modalInstance = bootstrap.Modal.getOrCreateInstance(el);
        }
        if (!hiddenBound) {
            hiddenBound = true;
            el.addEventListener('hidden.bs.modal', function () {
                if (typeof pendingOnClose === 'function') {
                    var cb = pendingOnClose;
                    pendingOnClose = null;
                    cb();
                }
            });
        }
        return modalInstance;
    }

    function normalizeOptions(options) {
        if (typeof options === 'string') {
            return { title: DEFAULT_TITLE, message: options, onClose: null };
        }
        options = options || {};
        return {
            title: options.title || DEFAULT_TITLE,
            message: options.message || DEFAULT_MESSAGE,
            onClose: typeof options.onClose === 'function' ? options.onClose : null,
        };
    }

    function showBerjayaModal(options) {
        var opts = normalizeOptions(options);
        var el = getModalElement();
        var modal = getModal();

        if (!el || !modal) {
            if (typeof window.alert === 'function') {
                window.alert(opts.title + '\n' + opts.message);
            }
            if (opts.onClose) {
                opts.onClose();
            }
            return;
        }

        pendingOnClose = opts.onClose;
        document.getElementById('stosSuccessModalTitle').textContent = opts.title;
        document.getElementById('stosSuccessModalMessage').textContent = opts.message;
        modal.show();
    }

    window.showBerjayaModal = showBerjayaModal;
})(window);
