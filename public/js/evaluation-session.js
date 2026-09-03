/**
 * Live committee evaluation session — shared by every jawatankuasa flow.
 *
 * Owns the session state, the Akuan Pengakuan gate, activity logging, and the
 * row-reservation calls. Rendering stays with each page: the row markup differs
 * per flow, so this layer deals only in state and HTTP.
 *
 * Usage:
 *   EvaluationSession.configure({ jenis: 'tech', tender: uuid, csrfToken: token,
 *                                 exitUrl: url, urls: { ... } });
 *   EvaluationSession.start(function () { ...page-specific setup... });
 */
window.EvaluationSession = (function () {
    'use strict';

    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    let cfg = {
        jenis: null,
        tender: null,
        csrfToken: null,
        exitUrl: null,
        committeeLabel: 'Ahli Jawatankuasa',
        urls: {},
    };

    let state = {
        user_id: null,
        peranan: null,
        peranan_label: null,
        is_committee_member: false,
        is_admin: false,
        can_submit: false,
        has_declared: false,
        locks: [],
    };

    function configure(options) {
        cfg = Object.assign(cfg, options || {});
        return api;
    }

    function load() {
        return $.get(cfg.urls.session, { tender: cfg.tender }).done(function (res) {
            state = Object.assign(state, res.data || {});
        });
    }

    /**
     * Enables the agree button once the text has been read to the end.
     * Uses IntersectionObserver against a sentinel at the bottom of the text, so it
     * stays correct at any zoom level or viewport height where a scrollTop maths
     * check would drift. Falls back to a scroll listener where unsupported.
     */
    function wireScrollGate() {
        const box = document.getElementById('akuanScroll');
        const end = document.getElementById('akuanEnd');
        const $btn = $('#btnAkuanSetuju');
        const $hint = $('#akuanHint');

        if (!box || !end) return;

        function unlock() {
            $btn.prop('disabled', false);
            $hint.addClass('is-complete')
                .find('span').text('Terima kasih. Anda kini boleh menerima akuan ini.');
        }

        if (box.scrollHeight <= box.clientHeight + 2) {
            unlock();
            return;
        }

        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function (entries) {
                if (entries.some(function (e) { return e.isIntersecting; })) {
                    unlock();
                    observer.disconnect();
                }
            }, { root: box, threshold: 1.0 });
            observer.observe(end);
            return;
        }

        box.addEventListener('scroll', function onScroll() {
            if (box.scrollTop + box.clientHeight >= box.scrollHeight - 8) {
                unlock();
                box.removeEventListener('scroll', onScroll);
            }
        });
    }

    function showDeclaration() {
        $('#akuanPeranan').text(state.peranan_label || cfg.committeeLabel);

        const el = document.getElementById('modalAkuan');
        if (!el) return;

        const modal = new bootstrap.Modal(el, { backdrop: 'static', keyboard: false });

        // Gate is wired after the modal is visible so the scroll box has real dimensions.
        el.addEventListener('shown.bs.modal', function () {
            wireScrollGate();
            document.getElementById('akuanScroll').focus();
        }, { once: true });

        modal.show();
    }

    function wireDeclarationButtons() {
        $('#btnAkuanSetuju').on('click', function () {
            const $btn = $(this);
            $btn.prop('disabled', true).text('Merekod...');

            $.post(cfg.urls.declaration, { _token: cfg.csrfToken, tender: cfg.tender })
                .done(function (res) {
                    state.has_declared = true;
                    bootstrap.Modal.getInstance(document.getElementById('modalAkuan')).hide();
                    showToast('success', res.message || 'Akuan telah direkodkan.');
                })
                .fail(function (xhr) {
                    $btn.prop('disabled', false).text('Saya Faham dan Bersetuju');
                    showToast('error', xhr.responseJSON?.message || 'Gagal merekod akuan.');
                });
        });

        // Declining means leaving — evaluation cannot proceed without the akuan.
        $('#btnAkuanTolak').on('click', function () {
            window.location.href = cfg.exitUrl;
        });
    }

    /**
     * Loads the session, hands control back for page-specific setup, then shows the
     * akuan if this member still owes one. Admins overseeing the process are not
     * committee members and have none to give.
     */
    function start(onReady) {
        wireDeclarationButtons();

        return load()
            .done(function () {
                if (typeof onReady === 'function') onReady(state);

                if (!state.is_committee_member || state.has_declared) return;
                showDeclaration();
            })
            .fail(function () {
                showToast('error', 'Gagal memuatkan sesi penilaian. Sila muat semula halaman.');
            });
    }

    function log(action, payload) {
        return $.post(cfg.urls.log, Object.assign({
            _token: cfg.csrfToken,
            tender: cfg.tender,
            action: action,
        }, payload || {}));
    }

    function acquireLock(itemUuid, vendorId, itemTitle) {
        return $.post(cfg.urls.lock, {
            _token: cfg.csrfToken,
            tender: cfg.tender,
            checklist_item_uuid: itemUuid,
            vendor_id: vendorId,
            item_title: itemTitle || '',
        });
    }

    function releaseLock(itemUuid, vendorId, itemTitle) {
        return $.post(cfg.urls.lockRelease, {
            _token: cfg.csrfToken,
            tender: cfg.tender,
            checklist_item_uuid: itemUuid,
            vendor_id: vendorId,
            item_title: itemTitle || '',
        });
    }

    function completeRows(itemUuid, rows, itemTitle) {
        return $.post(cfg.urls.rowsComplete, {
            _token: cfg.csrfToken,
            tender: cfg.tender,
            checklist_item_uuid: itemUuid,
            item_title: itemTitle || '',
            rows: rows,
        });
    }

    function fetchLocks(itemUuid) {
        return $.get(cfg.urls.locks, { tender: cfg.tender, checklist_item_uuid: itemUuid });
    }

    function lockingActive() {
        return state.is_committee_member === true;
    }

    function findLock(itemUuid, vendorId) {
        return (state.locks || []).find(function (l) {
            return l.checklist_item_uuid === itemUuid && Number(l.vendor_id) === Number(vendorId);
        }) || null;
    }

    /** 'free' | 'mine' | 'other' */
    function lockState(itemUuid, vendorId) {
        if (!lockingActive()) return 'mine';
        const lock = findLock(itemUuid, vendorId);
        if (!lock) return 'free';
        return Number(lock.user_id) === Number(state.user_id) ? 'mine' : 'other';
    }

    function setItemLocks(itemUuid, locks) {
        state.locks = (state.locks || [])
            .filter(function (l) { return l.checklist_item_uuid !== itemUuid; })
            .concat(locks || []);
    }

    function addLocalLock(itemUuid, vendorId) {
        setItemLocks(itemUuid, (state.locks || []).filter(function (l) {
            return !(l.checklist_item_uuid === itemUuid && Number(l.vendor_id) === Number(vendorId));
        }));
        state.locks.push({
            checklist_item_uuid: itemUuid,
            vendor_id: Number(vendorId),
            user_id: Number(state.user_id),
            user_name: 'Anda',
        });
    }

    const pollers = {};

    /**
     * Runs tickFn on an interval under a name, skipping ticks while the previous one
     * is still open so a slow backend cannot stack requests. tickFn returns a promise.
     * Named so independent polls (row locks, outer statuses) can run side by side.
     */
    function startPolling(name, tickFn, intervalMs) {
        stopPolling(name);

        const poller = { timer: null, inFlight: false };
        pollers[name] = poller;

        function tick() {
            if (poller.inFlight || typeof tickFn !== 'function') return;

            const pending = tickFn();
            if (!pending || typeof pending.always !== 'function') return;

            poller.inFlight = true;
            pending.always(function () { poller.inFlight = false; });
        }

        tick();
        poller.timer = setInterval(tick, intervalMs || 5000);
    }

    function stopPolling(name) {
        const poller = pollers[name];
        if (!poller) return;
        if (poller.timer) clearInterval(poller.timer);
        delete pollers[name];
    }

    /**
     * One reusable Ya/Batal prompt. Resolves true on confirm, false on cancel or dismiss.
     * options: { title, html, icon: 'warning'|'info'|'danger'|'success', confirmText, cancelText, showCancel }
     */
    function confirmDialog(options) {
        const opts = Object.assign({
            title: 'Sahkan',
            html: '',
            icon: 'warning',
            confirmText: 'Ya, Teruskan',
            cancelText: 'Batal',
            showCancel: true,
        }, options || {});

        return new Promise(function (resolve) {
            const el = document.getElementById('modalConfirmDialog');
            if (!el) { resolve(false); return; }

            document.getElementById('confirmDialogTitle').textContent = opts.title;
            document.getElementById('confirmDialogBody').innerHTML = opts.html;

            ['warning', 'info', 'danger', 'success'].forEach(function (name) {
                const icon = document.getElementById('confirmDialogIcon' + name.charAt(0).toUpperCase() + name.slice(1));
                if (icon) icon.classList.toggle('d-none', name !== opts.icon);
            });

            const $confirmBtn = $('#confirmDialogConfirm');
            const $cancelBtn = $('#confirmDialogCancel');
            $confirmBtn.text(opts.confirmText);
            $cancelBtn.text(opts.cancelText).toggleClass('d-none', !opts.showCancel);

            let settled = false;
            function settle(value) {
                if (settled) return;
                settled = true;
                resolve(value);
            }

            $confirmBtn.off('click.confirmDialog').on('click.confirmDialog', function () {
                settle(true);
                bootstrap.Modal.getInstance(el)?.hide();
            });
            $cancelBtn.off('click.confirmDialog').on('click.confirmDialog', function () {
                bootstrap.Modal.getInstance(el)?.hide();
            });
            el.addEventListener('hidden.bs.modal', function onHidden() {
                el.removeEventListener('hidden.bs.modal', onHidden);
                settle(false);
            }, { once: true });

            bootstrap.Modal.getOrCreateInstance(el).show();
        });
    }

    const api = {
        configure: configure,
        load: load,
        start: start,
        showDeclaration: showDeclaration,
        log: log,
        acquireLock: acquireLock,
        releaseLock: releaseLock,
        completeRows: completeRows,
        fetchLocks: fetchLocks,
        lockingActive: lockingActive,
        findLock: findLock,
        lockState: lockState,
        setItemLocks: setItemLocks,
        addLocalLock: addLocalLock,
        startPolling: startPolling,
        stopPolling: stopPolling,
        confirmDialog: confirmDialog,
        escapeHtml: escapeHtml,
        state: function () { return state; },
    };

    return api;
})();
