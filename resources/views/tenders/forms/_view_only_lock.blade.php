@php
    $viewOnly = $viewOnly ?? false;
@endphp

@if ($viewOnly)
    <div class="alert alert-info d-flex align-items-center gap-2 mb-4 py-2 px-3" style="font-size:0.82rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
            <circle cx="12" cy="12" r="3"></circle>
        </svg>
        <span><strong>Mod Paparan Sahaja</strong> — borang ini tidak boleh diedit dari penyediaan iklan.</span>
    </div>
@endif

@if ($viewOnly)
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll(
                    'form input:not([type="hidden"]), form select, form textarea, form button, form [type="submit"]'
                ).forEach(function (el) {
                    el.disabled = true;
                });
                document.querySelectorAll(
                    '.btn-form, .btn-simpan, .btn-hantar, #btn-simpan, #btn-hantar, .btn-tambah, .btn-hapus, .btn-tambah-row, .row-action-btn, .chip-del, .btn-del-saved-file, .upload-zone, [type="file"]'
                ).forEach(function (el) {
                    el.style.display = 'none';
                    el.disabled = true;
                });
                document.querySelectorAll('form').forEach(function (form) {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                    });
                });
            });
        </script>
    @endpush
@endif
