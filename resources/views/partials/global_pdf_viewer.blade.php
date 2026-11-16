{{-- Shared draggable + resizable PDF viewer. Open via .btn-file-view / .view-pdf-btn with data-url or href. --}}
<div class="modal fade" id="pdfViewerModal" tabindex="-1" aria-hidden="true"
    data-bs-backdrop="false" data-bs-keyboard="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable pdf-viewer-dialog">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header pdf-viewer-drag-handle">
                <h5 class="modal-title fw-bold mb-0">
                    Paparan Dokumen
                    <small class="text-muted fw-normal ms-2 d-none d-sm-inline">(seret / saiz)</small>
                </h5>
                <div class="d-flex align-items-center gap-1 ms-auto me-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary pdf-viewer-btn-size"
                        data-pdf-size="min" title="Kecilkan" aria-label="Kecilkan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <polyline points="4 14 10 14 10 20"></polyline>
                            <polyline points="20 10 14 10 14 4"></polyline>
                            <line x1="14" y1="10" x2="21" y2="3"></line>
                            <line x1="3" y1="21" x2="10" y2="14"></line>
                        </svg>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary pdf-viewer-btn-size"
                        data-pdf-size="default" title="Saiz biasa" aria-label="Saiz biasa">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <rect x="5" y="5" width="14" height="14" rx="1"></rect>
                        </svg>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary pdf-viewer-btn-size"
                        data-pdf-size="max" title="Besarkan" aria-label="Besarkan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <polyline points="15 3 21 3 21 9"></polyline>
                            <polyline points="9 21 3 21 3 15"></polyline>
                            <line x1="21" y1="3" x2="14" y2="10"></line>
                            <line x1="3" y1="21" x2="10" y2="14"></line>
                        </svg>
                    </button>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 bg-light pdf-viewer-body">
                <iframe id="pdfIframe" src="" title="Paparan dokumen" width="100%" height="100%"
                    style="border:none;"></iframe>
            </div>
            <div id="pdfViewerFooter" class="modal-footer py-2 d-none">
                <a id="pdfViewerDownloadBtn" href="#" class="btn btn-danger d-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Muat Turun Sijil
                </a>
            </div>
            <div class="pdf-viewer-resize-handle" title="Seret untuk ubah saiz" aria-hidden="true"></div>
        </div>
    </div>
</div>
<style>
    #pdfViewerModal {
        pointer-events: none;
        z-index: 1080;
    }
    #pdfViewerModal .modal-dialog {
        pointer-events: auto;
        margin: 1.5rem auto 1.5rem 2rem;
        max-width: min(720px, 92vw);
        width: min(720px, 92vw);
    }
    #pdfViewerModal .modal-content {
        position: relative;
        height: 75vh;
        min-height: 280px;
        max-height: 92vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    #pdfViewerModal .pdf-viewer-body {
        flex: 1 1 auto;
        min-height: 0;
        overflow: hidden;
    }
    #pdfViewerModal #pdfIframe {
        display: block;
        width: 100%;
        height: 100%;
        min-height: 0;
    }
    #pdfViewerModal .pdf-viewer-drag-handle {
        cursor: move;
        user-select: none;
        background: #f8fafc;
        flex: 0 0 auto;
    }
    #pdfViewerModal .pdf-viewer-btn-size {
        padding: 0.15rem 0.4rem;
        line-height: 1;
    }
    #pdfViewerModal .pdf-viewer-resize-handle {
        position: absolute;
        right: 0;
        bottom: 0;
        width: 18px;
        height: 18px;
        cursor: nwse-resize;
        z-index: 2;
        background:
            linear-gradient(135deg, transparent 50%, #94a3b8 50%, #94a3b8 60%, transparent 60%),
            linear-gradient(135deg, transparent 70%, #94a3b8 70%, #94a3b8 80%, transparent 80%);
        border-bottom-right-radius: 0.5rem;
    }
    #pdfViewerModal.show {
        background: transparent !important;
    }
    body.modal-open:has(#pdfViewerModal.show) {
        overflow: auto !important;
        padding-right: 0 !important;
    }
</style>
<script>
(function () {
    if (window.__globalPdfViewerReady) {
        return;
    }
    window.__globalPdfViewerReady = true;

    var SIZE_PRESETS = {
        min: { width: 420, heightRatio: 0.42 },
        default: { width: 720, heightRatio: 0.75 },
        max: { widthRatio: 0.94, heightRatio: 0.92 }
    };

    function getModalInstance(modalEl) {
        if (typeof bootstrap === 'undefined' || !bootstrap.Modal) {
            return null;
        }
        return bootstrap.Modal.getOrCreateInstance(modalEl);
    }

    function clamp(value, min, max) {
        return Math.min(Math.max(value, min), max);
    }

    function resetPdfDialogPosition(pdfDialog) {
        if (!pdfDialog) return;
        pdfDialog.style.transform = '';
        pdfDialog.style.left = '';
        pdfDialog.style.top = '';
        pdfDialog.style.margin = '';
        pdfDialog.style.position = '';
        pdfDialog.style.width = '';
        pdfDialog.style.maxWidth = '';
        var content = pdfDialog.querySelector('.modal-content');
        if (content) {
            content.style.height = '';
            content.style.width = '';
        }
    }

    function applyPdfSize(pdfDialog, mode) {
        if (!pdfDialog) return;
        var preset = SIZE_PRESETS[mode] || SIZE_PRESETS.default;
        var vw = window.innerWidth;
        var vh = window.innerHeight;
        var width = preset.width != null
            ? Math.min(preset.width, vw * 0.96)
            : vw * (preset.widthRatio || 0.7);
        var height = vh * (preset.heightRatio || 0.75);

        width = clamp(width, 320, vw - 16);
        height = clamp(height, 240, vh - 16);

        var rect = pdfDialog.getBoundingClientRect();
        pdfDialog.style.position = 'fixed';
        pdfDialog.style.margin = '0';
        pdfDialog.style.transform = 'none';
        pdfDialog.style.width = width + 'px';
        pdfDialog.style.maxWidth = 'none';

        var left = rect.left || 16;
        var top = rect.top || 16;
        // Keep window on screen after resize
        left = clamp(left, 8, Math.max(8, vw - width - 8));
        top = clamp(top, 8, Math.max(8, vh - 48));
        pdfDialog.style.left = left + 'px';
        pdfDialog.style.top = top + 'px';

        var content = pdfDialog.querySelector('.modal-content');
        if (content) {
            content.style.height = height + 'px';
        }
    }

    function openPdfViewer(url, downloadUrl) {
        var iframe = document.getElementById('pdfIframe');
        var modalEl = document.getElementById('pdfViewerModal');
        if (!iframe || !modalEl || !url) return;

        iframe.src = url;
        var downloadBtn = document.getElementById('pdfViewerDownloadBtn');
        var downloadFooter = document.getElementById('pdfViewerFooter');
        if (downloadBtn) {
            if (downloadUrl) {
                downloadBtn.href = downloadUrl;
                downloadBtn.classList.remove('d-none');
                if (downloadFooter) downloadFooter.classList.remove('d-none');
            } else {
                downloadBtn.removeAttribute('href');
                downloadBtn.classList.add('d-none');
                if (downloadFooter) downloadFooter.classList.add('d-none');
            }
        }
        var instance = getModalInstance(modalEl);
        if (instance) {
            instance.show();
        }
    }

    window.openGlobalPdfViewer = openPdfViewer;

    document.addEventListener('DOMContentLoaded', function () {
        var pdfModal = document.getElementById('pdfViewerModal');
        if (!pdfModal) return;

        var pdfDialog = pdfModal.querySelector('.modal-dialog');
        var pdfContent = pdfModal.querySelector('.modal-content');
        var pdfDragHandle = pdfModal.querySelector('.pdf-viewer-drag-handle');
        var pdfResizeHandle = pdfModal.querySelector('.pdf-viewer-resize-handle');
        var pdfDragging = false;
        var pdfResizing = false;
        var pdfDragOrigin = { x: 0, y: 0, left: 0, top: 0 };
        var pdfResizeOrigin = { x: 0, y: 0, width: 0, height: 0, left: 0, top: 0 };

        document.body.addEventListener('click', function (e) {
            var sizeBtn = e.target.closest('.pdf-viewer-btn-size');
            if (sizeBtn && pdfDialog) {
                e.preventDefault();
                e.stopPropagation();
                applyPdfSize(pdfDialog, sizeBtn.getAttribute('data-pdf-size') || 'default');
                return;
            }

            var target = e.target.closest('.btn-file-view, .view-pdf-btn');
            if (!target) return;

            var url = target.getAttribute('data-url') || target.getAttribute('href');
            if (!url || url === '#' || url.indexOf('javascript:') === 0) return;

            e.preventDefault();
            openPdfViewer(url, target.getAttribute('data-download-url'));
        });

        if (pdfDragHandle && pdfDialog) {
            pdfDragHandle.addEventListener('mousedown', function (e) {
                if (e.target.closest('.btn-close, .pdf-viewer-btn-size')) return;
                pdfDragging = true;
                var rect = pdfDialog.getBoundingClientRect();
                pdfDialog.style.position = 'fixed';
                pdfDialog.style.margin = '0';
                pdfDialog.style.left = rect.left + 'px';
                pdfDialog.style.top = rect.top + 'px';
                pdfDialog.style.transform = 'none';
                pdfDragOrigin = {
                    x: e.clientX,
                    y: e.clientY,
                    left: rect.left,
                    top: rect.top
                };
                e.preventDefault();
            });
        }

        if (pdfResizeHandle && pdfDialog && pdfContent) {
            pdfResizeHandle.addEventListener('mousedown', function (e) {
                pdfResizing = true;
                var rect = pdfDialog.getBoundingClientRect();
                pdfDialog.style.position = 'fixed';
                pdfDialog.style.margin = '0';
                pdfDialog.style.left = rect.left + 'px';
                pdfDialog.style.top = rect.top + 'px';
                pdfDialog.style.transform = 'none';
                pdfDialog.style.maxWidth = 'none';
                pdfResizeOrigin = {
                    x: e.clientX,
                    y: e.clientY,
                    width: rect.width,
                    height: pdfContent.getBoundingClientRect().height,
                    left: rect.left,
                    top: rect.top
                };
                e.preventDefault();
                e.stopPropagation();
            });
        }

        document.addEventListener('mousemove', function (e) {
            if (pdfDragging && pdfDialog) {
                var nextLeft = pdfDragOrigin.left + (e.clientX - pdfDragOrigin.x);
                var nextTop = pdfDragOrigin.top + (e.clientY - pdfDragOrigin.y);
                var maxLeft = window.innerWidth - pdfDialog.offsetWidth - 8;
                var maxTop = window.innerHeight - 48;
                pdfDialog.style.left = clamp(nextLeft, 8, Math.max(8, maxLeft)) + 'px';
                pdfDialog.style.top = clamp(nextTop, 8, Math.max(8, maxTop)) + 'px';
                return;
            }

            if (pdfResizing && pdfDialog && pdfContent) {
                var nextWidth = pdfResizeOrigin.width + (e.clientX - pdfResizeOrigin.x);
                var nextHeight = pdfResizeOrigin.height + (e.clientY - pdfResizeOrigin.y);
                nextWidth = clamp(nextWidth, 320, window.innerWidth - pdfResizeOrigin.left - 8);
                nextHeight = clamp(nextHeight, 240, window.innerHeight - pdfResizeOrigin.top - 8);
                pdfDialog.style.width = nextWidth + 'px';
                pdfDialog.style.maxWidth = 'none';
                pdfContent.style.height = nextHeight + 'px';
            }
        });

        document.addEventListener('mouseup', function () {
            pdfDragging = false;
            pdfResizing = false;
        });

        pdfModal.addEventListener('shown.bs.modal', function () {
            applyPdfSize(pdfDialog, 'default');
        });

        pdfModal.addEventListener('hidden.bs.modal', function () {
            var iframe = document.getElementById('pdfIframe');
            if (iframe) iframe.src = '';
            resetPdfDialogPosition(pdfDialog);
        });
    });
})();
</script>
