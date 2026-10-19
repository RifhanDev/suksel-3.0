/**
 * 2FA code prompt for the final Hantar of each penilaian stage.
 *
 * The server (RequireTwoFactorPenilaian middleware) answers 428 + {two_factor} when a code is needed.
 * This script wraps fetch and $.ajax for POSTs to window.TWO_FACTOR_PENILAIAN_PATHS only, shows
 * #modalTwoFactorPenilaian, and re-sends the same request with an X-Two-Factor-Code header.
 * The pages' own success/error handling is left untouched.
 */
(function (window, document) {
    'use strict';

    var patterns = (window.TWO_FACTOR_PENILAIAN_PATHS || []).map(function (p) { return new RegExp(p); });
    var CANCEL_MESSAGE = 'Penghantaran dibatalkan. Kod pengesahan dua faktor tidak dimasukkan.';

    if (!patterns.length) {
        return;
    }

    function isProtected(url, method) {
        if (String(method || 'GET').toUpperCase() !== 'POST' || !url) {
            return false;
        }
        var path;
        try {
            path = new URL(url, window.location.href).pathname;
        } catch (e) {
            return false;
        }
        return patterns.some(function (re) { return re.test(path); });
    }

    // ── Modal ──────────────────────────────────────────────────────────
    var el, input, errorEl, descEl, codeArea, setupArea, setupLink, confirmBtn, cancelBtn, toggleLink, labelEl;
    var pending = null; // resolve fn of the current ask()
    var recoveryMode = false;

    function bind() {
        if (el) return true;
        el = document.getElementById('modalTwoFactorPenilaian');
        if (!el || typeof bootstrap === 'undefined' || !bootstrap.Modal) return false;

        input = document.getElementById('tfpCodeInput');
        errorEl = document.getElementById('tfpError');
        descEl = document.getElementById('tfpModalDesc');
        codeArea = document.getElementById('tfpCodeArea');
        setupArea = document.getElementById('tfpSetupArea');
        setupLink = document.getElementById('tfpSetupLink');
        confirmBtn = document.getElementById('tfpConfirm');
        cancelBtn = document.getElementById('tfpCancel');
        toggleLink = document.getElementById('tfpToggleRecovery');
        labelEl = document.getElementById('tfpCodeLabel');

        confirmBtn.addEventListener('click', submitCode);
        cancelBtn.addEventListener('click', function () { settle(null); hide(); });
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); submitCode(); }
        });
        input.addEventListener('input', function () {
            if (!recoveryMode) {
                input.value = input.value.replace(/\D/g, '').slice(0, 6);
            }
        });
        toggleLink.addEventListener('click', function (e) {
            e.preventDefault();
            setRecoveryMode(!recoveryMode);
            input.focus();
        });
        el.addEventListener('shown.bs.modal', function () {
            if (!codeArea.classList.contains('d-none')) input.focus();
        });
        return true;
    }

    function setRecoveryMode(on) {
        recoveryMode = on;
        input.value = '';
        input.maxLength = on ? 20 : 6;
        input.inputMode = on ? 'text' : 'numeric';
        input.placeholder = on ? 'XXXX-XXXX' : '000000';
        labelEl.textContent = on ? 'Kod pemulihan' : 'Kod 6 digit daripada aplikasi pengesah';
        document.getElementById('tfpToggleRecoveryText').textContent = on ? 'Guna aplikasi pengesah' : 'Guna kod pemulihan';
    }

    function setBusy(busy) {
        confirmBtn.disabled = busy;
        input.disabled = busy;
        confirmBtn.textContent = busy ? 'Mengesahkan...' : 'Sahkan';
    }

    function showError(message) {
        errorEl.textContent = message || '';
        errorEl.classList.toggle('d-none', !message);
    }

    function submitCode() {
        var code = input.value.trim();
        if (!code) {
            showError('Sila masukkan kod.');
            return;
        }
        setBusy(true);
        showError('');
        settle(code);
    }

    function settle(value) {
        if (pending) {
            var resolve = pending;
            pending = null;
            resolve(value);
        }
    }

    function hide() {
        if (el && el.classList.contains('show')) {
            bootstrap.Modal.getOrCreateInstance(el).hide();
        }
    }

    // Some pages keep their own confirm dialog open while sending; close it first so the two don't overlap.
    function closeOtherModals() {
        var open = Array.prototype.filter.call(document.querySelectorAll('.modal.show'), function (m) { return m !== el; });

        return Promise.all(open.map(function (m) {
            return new Promise(function (resolve) {
                m.addEventListener('hidden.bs.modal', resolve, { once: true });
                setTimeout(resolve, 600); // in case it never reports hidden
                bootstrap.Modal.getOrCreateInstance(m).hide();
            });
        }));
    }

    /**
     * Shows the modal for a 428/422 two_factor body. Resolves with the typed code, or null when cancelled
     * (or when the user still has to enrol, since there is nothing to type yet).
     */
    function ask(body) {
        if (!bind()) {
            var typed = window.prompt((body && body.message) || 'Masukkan kod pengesahan dua faktor:');
            return Promise.resolve(typed ? typed.trim() : null);
        }

        var setup = body && body.two_factor === 'setup_required';
        var retry = body && (body.two_factor === 'invalid' || body.two_factor === 'locked');

        codeArea.classList.toggle('d-none', setup);
        setupArea.classList.toggle('d-none', !setup);
        confirmBtn.classList.toggle('d-none', setup);
        cancelBtn.textContent = setup ? 'Tutup' : 'Batal';
        if (setup && body.setup_url) setupLink.href = body.setup_url;

        descEl.textContent = setup || !retry
            ? ((body && body.message) || '')
            : 'Masukkan kod pengesahan untuk meneruskan penghantaran.';

        setBusy(false);
        showError(retry ? body.message : '');
        if (retry) {
            input.value = '';
        } else if (!setup) {
            setRecoveryMode(false);
        }

        closeOtherModals().then(function () {
            bootstrap.Modal.getOrCreateInstance(el).show();
            if (retry) input.focus();
        });

        return new Promise(function (resolve) { pending = resolve; });
    }

    function needsCode(status, body, sentCode) {
        if (!body || !body.two_factor) return false;
        return status === 428 || (status === 422 && !!sentCode);
    }

    // ── fetch ──────────────────────────────────────────────────────────
    if (typeof window.fetch === 'function') {
        var originalFetch = window.fetch;

        window.fetch = function (resource, init) {
            var url = typeof resource === 'string' ? resource : (resource && resource.url);
            var method = (init && init.method) || (resource && resource.method) || 'GET';

            if (typeof resource !== 'string' || !isProtected(url, method)) {
                return originalFetch.apply(this, arguments);
            }

            var self = this;

            function attempt(code) {
                var options = Object.assign({}, init || {});
                if (code) {
                    var headers = new Headers(options.headers || {});
                    headers.set('X-Two-Factor-Code', code);
                    options.headers = headers;
                }

                return originalFetch.call(self, resource, options).then(function (res) {
                    if (res.status !== 428 && res.status !== 422) {
                        hide();
                        return res;
                    }

                    return res.clone().json().then(function (body) {
                        if (!needsCode(res.status, body, code)) {
                            hide();
                            return res;
                        }

                        return ask(body).then(function (typed) {
                            if (typed === null) {
                                hide();
                                return new Response(JSON.stringify({ message: body.two_factor === 'setup_required' ? body.message : CANCEL_MESSAGE }), {
                                    status: 422,
                                    headers: { 'Content-Type': 'application/json' },
                                });
                            }
                            return attempt(typed);
                        });
                    }, function () {
                        hide();
                        return res;
                    });
                }, function (err) {
                    hide();
                    throw err;
                });
            }

            return attempt(null);
        };
    }

    // ── jQuery $.ajax ─────────────────────────────────────────────────
    var $ = window.jQuery;

    if ($ && typeof $.ajax === 'function') {
        var originalAjax = $.ajax;

        $.ajax = function (url, settings) {
            var opts = typeof url === 'object' ? $.extend({}, url) : $.extend({}, settings, { url: url });

            if (!isProtected(opts.url, opts.method || opts.type)) {
                return originalAjax.apply(this, arguments);
            }

            var dfd = $.Deferred();
            var context = opts.context || opts;
            var success = opts.success, error = opts.error, complete = opts.complete;
            delete opts.success;
            delete opts.error;
            delete opts.complete;

            function finishOk(data, textStatus, xhr) {
                hide();
                if (success) $.each([].concat(success), function (_, fn) { fn.call(context, data, textStatus, xhr); });
                dfd.resolveWith(context, [data, textStatus, xhr]);
                if (complete) $.each([].concat(complete), function (_, fn) { fn.call(context, xhr, textStatus); });
            }

            function finishFail(xhr, textStatus, errorThrown) {
                hide();
                if (error) $.each([].concat(error), function (_, fn) { fn.call(context, xhr, textStatus, errorThrown); });
                dfd.rejectWith(context, [xhr, textStatus, errorThrown]);
                if (complete) $.each([].concat(complete), function (_, fn) { fn.call(context, xhr, textStatus); });
            }

            function attempt(code) {
                var o = $.extend({}, opts);
                o.headers = $.extend({}, opts.headers, code ? { 'X-Two-Factor-Code': code } : {});

                originalAjax.call($, o).done(finishOk).fail(function (xhr, textStatus, errorThrown) {
                    var body = xhr.responseJSON;

                    if (!needsCode(xhr.status, body, code)) {
                        finishFail(xhr, textStatus, errorThrown);
                        return;
                    }

                    ask(body).then(function (typed) {
                        if (typed === null) {
                            // Pages read responseJSON.message; give them a clear reason instead of the 428 text.
                            var message = body.two_factor === 'setup_required' ? body.message : CANCEL_MESSAGE;
                            xhr.responseJSON = { message: message };
                            xhr.responseText = JSON.stringify(xhr.responseJSON);
                            finishFail(xhr, textStatus, errorThrown);
                            return;
                        }
                        attempt(typed);
                    });
                });
            }

            attempt(null);

            return dfd.promise();
        };
    }
})(window, document);
