/**
 * Global double-submit guard.
 *
 * - Native <form> submit: locks submit controls (hard) until navigation
 * - Action buttons (Simpan / Hantar / data-submit-once / common ids): lock on click
 * - If validation fails and no AJAX starts, auto-unlock shortly after
 * - jQuery AJAX complete/error: unlock soft-locked buttons
 * - Opt out: data-allow-double-submit on the form or control
 *
 *   window.SukselSubmitGuard.lock(btn, 'Menghantar...')
 *   window.SukselSubmitGuard.unlock(btn)
 */
(function () {
    'use strict';

    var SAFETY_UNLOCK_MS = 45000;
    var NO_AJAX_UNLOCK_MS = 400;
    var pendingAjax = 0;
    var ACTION_LABEL_RE = /^(simpan|hantar|kemaskini|sahkan|submit|teruskan|bayar|mula|ya,?\s*hantar|hantar bidaan|simpan draf)(\.\.\.)?$/i;
    var BUSY_BY_LABEL = {
        hantar: 'Menghantar...',
        simpan: 'Menyimpan...',
        kemaskini: 'Mengemaskini...',
        sahkan: 'Mengesahkan...',
        submit: 'Menghantar...',
        teruskan: 'Memproses...',
        bayar: 'Memproses...',
        mula: 'Memulakan...',
    };

    function isExcluded(el) {
        if (!el || el.nodeType !== 1) return true;
        if (el.closest('[data-allow-double-submit]')) return true;
        if (el.matches('[data-bs-dismiss], [data-dismiss], .dropdown-toggle')) return true;
        if (el.classList.contains('btn-form-secondary') || el.classList.contains('btn-close')) return true;
        return false;
    }

    function buttonLabel(btn) {
        if (!btn) return '';
        if (btn.tagName === 'INPUT') return (btn.value || '').trim();
        return (btn.textContent || '').replace(/\s+/g, ' ').trim();
    }

    function busyLabelFor(btn) {
        if (btn && btn.dataset && btn.dataset.busyLabel) {
            return btn.dataset.busyLabel;
        }
        var label = buttonLabel(btn).toLowerCase();
        for (var key in BUSY_BY_LABEL) {
            if (Object.prototype.hasOwnProperty.call(BUSY_BY_LABEL, key) && label.indexOf(key) === 0) {
                return BUSY_BY_LABEL[key];
            }
        }
        return 'Memproses...';
    }

    function shouldGuardClick(btn) {
        if (!btn || isExcluded(btn)) return false;
        if (btn.getAttribute('aria-disabled') === 'true') return false;

        if (btn.matches('[data-submit-once]')) return true;
        if (btn.matches('button[type="submit"], input[type="submit"]')) return true;

        var id = (btn.id || '').toLowerCase();
        if (/(^|[-_])(simpan|hantar|submit)([-_]|$)/.test(id)) return true;

        return ACTION_LABEL_RE.test(buttonLabel(btn));
    }

    function lock(btn, busyText, permanent) {
        if (!btn || isExcluded(btn)) return false;
        if (btn.dataset.submitLocked === '1') return false;

        btn.dataset.submitLocked = '1';
        btn.dataset.submitLockMode = permanent ? 'hard' : 'soft';
        btn.dataset.submitOriginalDisabled = btn.disabled ? '1' : '0';

        if (btn.tagName === 'INPUT') {
            btn.dataset.submitOriginalHtml = btn.value || '';
            if (busyText) btn.value = busyText;
        } else {
            btn.dataset.submitOriginalHtml = btn.innerHTML;
            if (busyText) btn.textContent = busyText;
        }

        btn.disabled = true;
        btn.classList.add('is-submit-locked');
        btn.setAttribute('aria-busy', 'true');

        window.clearTimeout(btn._sukselSubmitUnlockTimer);
        if (!permanent) {
            btn._sukselSubmitUnlockTimer = window.setTimeout(function () {
                unlock(btn);
            }, SAFETY_UNLOCK_MS);
        }

        return true;
    }

    function unlock(btn) {
        if (!btn || btn.dataset.submitLocked !== '1') return;
        if (btn.dataset.submitLockMode === 'hard') return;

        window.clearTimeout(btn._sukselSubmitUnlockTimer);
        window.clearTimeout(btn._sukselNoAjaxUnlockTimer);

        if (btn.tagName === 'INPUT') {
            if (btn.dataset.submitOriginalHtml != null) {
                btn.value = btn.dataset.submitOriginalHtml;
            }
        } else if (btn.dataset.submitOriginalHtml != null) {
            btn.innerHTML = btn.dataset.submitOriginalHtml;
        }

        btn.disabled = btn.dataset.submitOriginalDisabled === '1';
        btn.dataset.submitLocked = '0';
        delete btn.dataset.submitLockMode;
        btn.classList.remove('is-submit-locked');
        btn.removeAttribute('aria-busy');
    }

    function unlockAllSoft() {
        document.querySelectorAll('[data-submit-locked="1"]').forEach(function (btn) {
            if (btn.dataset.submitLockMode !== 'hard') {
                unlock(btn);
            }
        });
    }

    function scheduleNoAjaxUnlock(btn) {
        window.clearTimeout(btn._sukselNoAjaxUnlockTimer);
        btn._sukselNoAjaxUnlockTimer = window.setTimeout(function () {
            if (pendingAjax === 0 && btn.dataset.submitLockMode === 'soft') {
                unlock(btn);
            }
        }, NO_AJAX_UNLOCK_MS);
    }

    function lockFormSubmitters(form) {
        if (!form || isExcluded(form)) return;
        form.querySelectorAll('button[type="submit"], input[type="submit"], button:not([type])').forEach(function (btn) {
            if (isExcluded(btn)) return;
            // Promote soft click-lock to hard once the form actually submits.
            if (btn.dataset.submitLocked === '1') {
                btn.dataset.submitLockMode = 'hard';
                window.clearTimeout(btn._sukselSubmitUnlockTimer);
                window.clearTimeout(btn._sukselNoAjaxUnlockTimer);
                return;
            }
            lock(btn, busyLabelFor(btn), true);
        });
    }

    document.addEventListener(
        'submit',
        function (e) {
            var form = e.target;
            if (!(form instanceof HTMLFormElement)) return;
            if (isExcluded(form)) return;
            lockFormSubmitters(form);
        },
        true
    );

    document.addEventListener(
        'click',
        function (e) {
            var btn = e.target.closest('button, input[type="submit"], input[type="button"]');
            if (!btn) return;

            if (btn.dataset.submitLocked === '1') {
                e.preventDefault();
                e.stopPropagation();
                if (typeof e.stopImmediatePropagation === 'function') {
                    e.stopImmediatePropagation();
                }
                return;
            }

            // Native submit controls: do NOT disable on click. Disabling the clicked
            // submit button before the browser's default action cancels form submit
            // (seen on /two-factor "Simpan Tetapan …"). Lock on the submit event instead.
            if (
                btn.matches('button[type="submit"], input[type="submit"]')
                || (btn.tagName === 'BUTTON' && !btn.getAttribute('type'))
            ) {
                return;
            }

            if (!shouldGuardClick(btn) || btn.disabled) return;

            var permanent = btn.matches('[data-submit-once="hard"]');
            lock(btn, busyLabelFor(btn), permanent);
            if (!permanent) {
                // Defer until after sync click handlers (incl. window.confirm) finish.
                window.setTimeout(function () {
                    scheduleNoAjaxUnlock(btn);
                }, 0);
            }
        },
        true
    );

    if (typeof window.jQuery !== 'undefined') {
        window.jQuery(document).on('ajaxSend', function () {
            pendingAjax += 1;
        });
        // ajaxComplete runs for success and error (do not also bind ajaxError — double count).
        window.jQuery(document).on('ajaxComplete', function () {
            pendingAjax = Math.max(0, pendingAjax - 1);
            if (pendingAjax === 0) {
                unlockAllSoft();
            }
        });
    }

    window.SukselSubmitGuard = {
        lock: lock,
        unlock: unlock,
        unlockAllSoft: unlockAllSoft,
    };
})();
