@php
    $viewOnly = $viewOnly ?? false;
@endphp

@if ($viewOnly)
    <style>
        .btn-tambah, .btn-tambah-row, .btn-tambah-inline, .btn-tambah-projek, .btn-tambah-akaun, .btn-tambah-prestasi, .btn-tambah-purata, .btn-tambah-berbayar, .btn-tambah-dibenarkan,
        .btn-hapus, .btn-hapus-row, .btn-del-row, .btn-del-saved-file, .chip-del, .row-action-btn,
        .upload-zone, .btn-simpan, .btn-hantar, #btn-simpan, #btn-hantar, #btn-tambah-row, #btn-tambah-projek, #btn-tambah-akaun, #btn-tambah-prestasi, [type="file"] {
            display: none !important;
        }
        tr > th:last-child:has(.btn-tambah-row), tr > td:last-child:has(.btn-tambah-row), tr > td:last-child:has(.btn-hapus-row), tr > td:last-child:has(.row-action-btn) {
            display: none !important;
        }
    </style>
    <div class="alert alert-info d-flex align-items-center gap-2 mb-4 py-2 px-3" style="font-size:0.82rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
            <circle cx="12" cy="12" r="3"></circle>
        </svg>
        <span><strong>Mod Paparan Sahaja</strong> — borang atas talian ini dalam mod baca sahaja untuk semakan Jawatankuasa.</span>
    </div>
@endif

@if ($viewOnly)
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const hideSelectors = [
                    '.btn-form', '.btn-simpan', '.btn-hantar', '#btn-simpan', '#btn-hantar',
                    '.btn-tambah', '.btn-hapus', '.btn-tambah-row', '.btn-hapus-row', '.btn-tambah-inline',
                    '.btn-tambah-projek', '.btn-tambah-akaun', '.btn-tambah-prestasi', '.btn-tambah-purata',
                    '.btn-tambah-berbayar', '.btn-tambah-dibenarkan', '.btn-del-row',
                    '.row-action-btn', '.chip-del', '.btn-del-saved-file', '.upload-zone', '[type="file"]',
                    '#btn-tambah-row', '#btn-tambah-projek', '#btn-tambah-akaun', '#btn-tambah-prestasi'
                ];

                document.querySelectorAll(
                    'form input:not([type="hidden"]), form select, form textarea, form button:not(.btn-preview-file):not(.btn-download-file), form [type="submit"]'
                ).forEach(function (el) {
                    el.disabled = true;
                });

                document.querySelectorAll(hideSelectors.join(',')).forEach(function (el) {
                    if (!el.classList.contains('btn-preview-file') && !el.classList.contains('btn-download-file')) {
                        el.style.display = 'none';
                        el.disabled = true;
                    }
                });

                document.querySelectorAll('form').forEach(function (form) {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                    });
                });

                setInterval(function () {
                    document.querySelectorAll(hideSelectors.join(',')).forEach(function (el) {
                        if (!el.classList.contains('btn-preview-file') && !el.classList.contains('btn-download-file')) {
                            el.style.display = 'none';
                            el.disabled = true;
                        }
                    });
                }, 300);
            });
        </script>
    @endpush
@endif
