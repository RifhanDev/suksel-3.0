<div class="modal fade" id="onlineFormModal" tabindex="-1" aria-labelledby="onlineFormModalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="true">
    <div class="modal-dialog modal-fullscreen-lg-down modal-xl modal-dialog-scrollable">
        <div class="modal-content" style="min-height:80vh;">
            <div class="modal-header py-2 px-3 border-bottom">
                <h5 class="modal-title fw-semibold" id="onlineFormModalLabel" style="font-size:0.95rem;">
                    Borang Atas Talian
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-0 position-relative">
                <div id="onlineFormLoading"
                    class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-white"
                    style="z-index:2;">
                    <div class="text-center text-muted small">
                        <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                        <div>Memuatkan borang...</div>
                    </div>
                </div>
                <iframe id="onlineFormFrame" title="Borang atas talian" src="about:blank"
                    style="width:100%;min-height:75vh;border:0;display:block;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var modalEl = document.getElementById('onlineFormModal');
    if (!modalEl || typeof bootstrap === 'undefined') {
        return;
    }

    var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    var frame = document.getElementById('onlineFormFrame');
    var loading = document.getElementById('onlineFormLoading');
    var titleEl = document.getElementById('onlineFormModalLabel');
    var activeTrigger = null;

    function showLoading() {
        if (loading) loading.classList.remove('d-none');
    }

    function hideLoading() {
        if (loading) loading.classList.add('d-none');
    }

    function shouldReloadOnComplete(trigger) {
        if (!trigger) return false;
        return trigger.getAttribute('data-reload-on-complete') !== '0';
    }

    function reloadParentPage(trigger) {
        if (!trigger) {
            window.location.reload();
            return;
        }

        var hash = trigger.getAttribute('data-reload-hash');
        var tab = trigger.getAttribute('data-reload-tab');

        if (hash) {
            var hashUrl = new URL(window.location.href);
            hashUrl.hash = hash;
            window.location.assign(hashUrl.toString());
            return;
        }

        if (tab) {
            var tabUrl = new URL(window.location.href);
            tabUrl.searchParams.set('tab', tab);
            window.location.assign(tabUrl.toString());
            return;
        }

        window.location.reload();
    }

    function openOnlineFormModal(trigger) {
        var url = trigger.getAttribute('data-form-url');
        if (!url) return;

        activeTrigger = trigger;
        titleEl.textContent = trigger.getAttribute('data-form-title') || 'Borang Atas Talian';
        showLoading();
        frame.src = url;
        modal.show();
    }

    function closeOnlineFormModal(reload) {
        modal.hide();
        frame.src = 'about:blank';
        hideLoading();
        if (reload) {
            reloadParentPage(activeTrigger);
        }
        activeTrigger = null;
    }

    document.addEventListener('click', function (e) {
        var trigger = e.target.closest('[data-online-form-modal], [data-vendor-form-modal]');
        if (!trigger) return;
        e.preventDefault();
        openOnlineFormModal(trigger);
    });

    frame.addEventListener('load', function () {
        hideLoading();
    });

    modalEl.addEventListener('hidden.bs.modal', function () {
        frame.src = 'about:blank';
        hideLoading();
        activeTrigger = null;
    });

    window.addEventListener('message', function (e) {
        if (e.origin !== window.location.origin || !e.data || typeof e.data !== 'object') {
            return;
        }
        if (e.data.type === 'vendor-form-complete') {
            closeOnlineFormModal(shouldReloadOnComplete(activeTrigger));
        } else if (e.data.type === 'vendor-form-close') {
            closeOnlineFormModal(!!e.data.reload);
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        var hash = (window.location.hash || '').replace('#', '');
        if (hash) {
            var tabTrigger = document.querySelector(
                '[href="#' + hash + '"][data-bs-toggle="pill"], [href="#' + hash + '"][data-bs-toggle="tab"]'
            );
            if (tabTrigger) {
                bootstrap.Tab.getOrCreateInstance(tabTrigger).show();
            }
        }

        var tabParam = new URLSearchParams(window.location.search).get('tab');
        if (tabParam) {
            var tabBtn = document.querySelector('[href="#tab-' + tabParam + '"], [aria-controls="tab-' + tabParam + '"]');
            if (tabBtn) {
                bootstrap.Tab.getOrCreateInstance(tabBtn).show();
            }
        }
    });
})();
</script>
