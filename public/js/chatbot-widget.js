/**
 * Chatbot Lela — Hide/Show Toggle
 *
 * Adds a × button above the bubble to slide the widget off-screen,
 * and a ribbon tab on the right edge to bring it back.
 *
 * Depends on:
 *   - #botmanWidgetRoot in the DOM (injected by widget.js)
 *   - localStorage key 'chatbotHidden' for persistence
 *   - CSS class 'chatbot-pre-hidden' on <html> (set by inline script in master.blade.php)
 */
(function () {

    var HIDDEN_KEY = 'chatbotHidden';
    var SLIDE_OUT  = 'translateX(calc(100% + 24px))';
    var SLIDE_IN   = 'translateX(0)';
    var TRANSITION = 'transform 0.3s cubic-bezier(.4,0,.2,1)';

    /* ── DOM helper ──────────────────────────────────────────────────────── */
    function make(tag, props) {
        var node = document.createElement(tag);
        Object.keys(props).forEach(function (k) { node[k] = props[k]; });
        return node;
    }

    /* ── Hide button (× badge above the bubble) ──────────────────────────── */
    function buildHideBtn() {
        var btn = make('div', {
            id       : 'chatbot-hide-btn',
            title    : 'Sembunyikan chatbot',
            innerHTML:
                '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24"' +
                ' fill="none" stroke="currentColor" stroke-width="3"' +
                ' stroke-linecap="round" stroke-linejoin="round">' +
                '<line x1="18" y1="6" x2="6" y2="18"></line>' +
                '<line x1="6" y1="6" x2="18" y2="18"></line></svg>',
            style    :
                'position:fixed;right:14px;bottom:72px;z-index:2147483647;' +
                'width:20px;height:20px;border-radius:50%;' +
                'background:rgba(30,41,59,0.55);color:#fff;' +
                'display:flex;align-items:center;justify-content:center;' +
                'cursor:pointer;transition:background .15s;user-select:none;'
        });
        btn.onmouseenter = function () { this.style.background = 'rgba(30,41,59,0.85)'; };
        btn.onmouseleave = function () { this.style.background = 'rgba(30,41,59,0.55)'; };
        return btn;
    }

    /* ── Ribbon tab (right edge — visible when widget is hidden) ─────────── */
    function buildRibbon() {
        var rib = make('div', {
            id       : 'chatbot-ribbon',
            title    : 'Buka chatbot Lela',
            innerHTML:
                '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"' +
                ' fill="none" stroke="currentColor" stroke-width="2"' +
                ' stroke-linecap="round" stroke-linejoin="round">' +
                '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>' +
                '<span style="writing-mode:vertical-rl;font-size:0.6rem;font-weight:700;' +
                'letter-spacing:1.5px;text-transform:uppercase;margin-top:6px;">Lela</span>',
            style    :
                'position:fixed;right:0;bottom:100px;z-index:2147483646;' +
                'background:#c41e3a;color:#fff;border-radius:8px 0 0 8px;' +
                'padding:12px 7px;display:none;flex-direction:column;' +
                'align-items:center;cursor:pointer;' +
                'box-shadow:-3px 2px 12px rgba(0,0,0,.22);user-select:none;' +
                'transition:background .15s;'
        });
        rib.onmouseenter = function () { this.style.background = '#a01830'; };
        rib.onmouseleave = function () { this.style.background = '#c41e3a'; };
        return rib;
    }

    /* ── Main init ───────────────────────────────────────────────────────── */
    function init() {
        var root = document.getElementById('botmanWidgetRoot');
        if (!root || !root.firstElementChild) { setTimeout(init, 300); return; }

        var widget   = root.firstElementChild;
        var hideBtn  = buildHideBtn();
        var ribbon   = buildRibbon();
        var isHidden = false; // true = widget slid off screen globally

        document.body.appendChild(hideBtn);
        document.body.appendChild(ribbon);

        widget.style.transition = TRANSITION;
        widget.addEventListener('dragstart', function (e) { e.preventDefault(); });

        /* ── Sync hide-button visibility ──────────────────────────────────── */
        // × button must be hidden when: widget is globally hidden OR chat window is open.
        // Chat open = widget taller than the bubble alone (~80px); open chat is ~500px.
        function syncHideBtn() {
            if (isHidden) { hideBtn.style.display = 'none'; return; }
            hideBtn.style.display = widget.offsetHeight > 150 ? 'none' : 'flex';
        }

        if (window.ResizeObserver) {
            new ResizeObserver(syncHideBtn).observe(widget);
        }

        /* ── Hide / show ─────────────────────────────────────────────────── */
        function hideWidget() {
            isHidden               = true;
            widget.style.transform = SLIDE_OUT;
            ribbon.style.display   = 'flex';
            syncHideBtn();
            try { localStorage.setItem(HIDDEN_KEY, '1'); } catch (e) {}
        }

        function showWidget() {
            isHidden               = false;
            widget.style.transform = SLIDE_IN;
            ribbon.style.display   = 'none';
            syncHideBtn();
            try { localStorage.removeItem(HIDDEN_KEY); } catch (e) {}
        }

        hideBtn.addEventListener('click', hideWidget);
        ribbon.addEventListener('click', showWidget);

        /* ── Hand off from CSS pre-hide to JS ────────────────────────────── */
        document.documentElement.classList.remove('chatbot-pre-hidden');

        try {
            if (localStorage.getItem(HIDDEN_KEY) === '1') {
                isHidden = true;
                widget.style.transition = 'none';
                widget.style.transform  = SLIDE_OUT;
                hideBtn.style.display   = 'none';
                ribbon.style.display    = 'flex';
                setTimeout(function () { widget.style.transition = TRANSITION; }, 50);
            }
        } catch (e) {}
    }

    window.addEventListener('load', function () { setTimeout(init, 400); });

})();
