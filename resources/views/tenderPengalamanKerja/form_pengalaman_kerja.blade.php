@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/file-upload.css') }}" rel="stylesheet">
    <style>
        /* ── Table grid borders ─────────────────────────────────────── */
        #tbl-pengalaman {
            border: 1px solid #e2e8f0;
        }
        #tbl-pengalaman thead th {
            background: #1e3a5f;
            color: #fff;
            font-size: 0.78rem;
            letter-spacing: 0.03em;
            border-color: #1e3a5f !important;
        }
        #tbl-pengalaman th,
        #tbl-pengalaman td {
            border-right: 1px solid #e2e8f0 !important;
        }
        #tbl-pengalaman th:last-child,
        #tbl-pengalaman td:last-child {
            border-right: none !important;
        }

        /* ── Saved file chips ───────────────────────────────────────── */
        .saved-file-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 8px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            font-size: 0.8rem;
            color: #334155;
            margin: 4px;
        }
        .saved-file-chip .chip-del {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #fee2e2;
            color: #ef4444;
            border: none;
            cursor: pointer;
            padding: 0;
            flex-shrink: 0;
        }
        .saved-file-chip .chip-del:hover { background: #fecaca; }

        /* ── Toast ──────────────────────────────────────────────────── */
        #pk-toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            min-width: 360px;
            max-width: 520px;
        }
        .pk-toast {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 1.1rem 1.4rem;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,.13);
            font-size: 0.9rem;
            font-weight: 500;
            animation: pkSlideIn .28s cubic-bezier(.16,1,.3,1);
        }
        .pk-toast.pk-success { background:#f0fdf4; border:1.5px solid #86efac; color:#15803d; }
        .pk-toast.pk-error   { background:#fef2f2; border:1.5px solid #fca5a5; color:#b91c1c; }
        .pk-toast.pk-warning { background:#fffbeb; border:1.5px solid #fde68a; color:#92400e; }
        @keyframes pkSlideIn {
            from { opacity:0; transform:translateX(40px); }
            to   { opacity:1; transform:translateX(0); }
        }

        .borang-title-bar {
            background: #e2e8f0;
            color: #1e293b;
            font-weight: 700;
            font-size: 0.82rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 10px 16px;
            border-radius: 6px 6px 0 0;
        }
        .borang-subtitle {
            font-size: 0.78rem;
            color: #475569;
            font-weight: 600;
            letter-spacing: 0.02em;
        }
        .row-action-btn {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }
        .row-action-btn.btn-tambah-inline {
            background: #dbeafe;
            color: #2563eb;
        }
        .row-action-btn.btn-tambah-inline:hover {
            background: #bfdbfe;
        }
        .row-action-btn.btn-hapus-row {
            background: #fee2e2;
            color: #ef4444;
        }
        .row-action-btn.btn-hapus-row:hover {
            background: #fecaca;
        }
    </style>
@endsection

@section('content')
    @include('tenders.forms._view_only_lock')

    @php
        $kembaliUrl = $returnUrl ?? ($tender ? route('senaraiTeknikal', $tender->uuid) : url()->previous());
        $storeUrl = $tender ? route('senaraiTeknikal.pengalamanKerja.store', $tender->uuid) : '#';
    @endphp

    <!-- Toast container -->
    <div id="pk-toast-container"></div>

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            showPkToast('success', @json(session('success')));
        });
    </script>
    @endif
    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            showPkToast('error', @json(session('error')));
        });
    </script>
    @endif
    @if(session('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            showPkToast('warning', @json(session('warning')));
        });
    </script>
    @endif

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Pengalaman Kerja</h3>
            <p class="text-muted small m-0">Borang atas talian untuk petender mengisi pengalaman kerja dalam lima (5) tahun lepas.</p>
        </div>
    </div>

    <!-- TENDER INFO -->
    <div class="content-card mb-4 p-0">
        <div class="content-card-body p-4">

            <!-- Tajuk Tender -->
            <div class="mb-3 pb-3 border-bottom">
                <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                    style="font-size: 0.67rem; letter-spacing: 0.5px;">Tajuk Tender</span>
                <h5 class="fw-bold text-dark mb-0" style="line-height: 1.45; font-size: 1rem;">
                    {{ $tender->name ?? '-' }}
                    @if($tender?->kategori_perolehan_name)
                    <span class="fw-normal text-muted fst-italic" style="font-size: 0.85rem;">({{ $tender->kategori_perolehan_name }})</span>
                    @endif
                </h5>
            </div>

            <!-- Metadata: No. Tender · Status -->
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1"
                        style="font-size: 0.67rem; letter-spacing: 0.5px;">No. Tender</span>
                    <span class="fw-semibold text-dark" style="font-size: 0.875rem;">
                        {{ $tender?->no_tender ?? $tender?->ref_number ?? '-' }}
                    </span>
                </div>
                <div class="col-12 col-md-9 d-md-flex justify-content-md-end align-items-md-center">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2 fw-semibold"
                        style="background: #fef9c3; color: #854d0e; font-size: 0.8rem; border: 1px solid #fde68a;">
                        <span class="d-inline-block rounded-circle"
                            style="width:7px;height:7px;background:#ca8a04;flex-shrink:0;"></span>
                        Dalam Proses
                    </span>
                </div>
            </div>

        </div>
    </div>

    <form id="form-pengalaman-kerja" action="{{ $storeUrl }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if (! empty($returnUrl))
        <input type="hidden" name="return" value="{{ $returnUrl }}">
    @endif

        <!-- ===================== SECTION: PENGALAMAN KERJA ===================== -->
        <div class="content-card mb-4 p-0">
            <div class="borang-title-bar">Pengalaman Kerja</div>
            <div class="content-card-body p-4 pt-3">

                <p class="borang-subtitle mb-3">Pengalaman Kerja Dalam Lima (5) Tahun Lepas.</p>

                <!-- Table -->
                <div class="table-responsive">
                    <table id="tbl-pengalaman" class="table table-modern align-middle mb-0 w-100">
                        <thead>
                            <tr>
                                <th class="text-center py-3" style="width:50px;">Bil.</th>
                                <th class="py-3" style="min-width:220px;">Senarai Kerja Yang Disiapkan</th>
                                <th class="py-3" style="min-width:140px;">PIC</th>
                                <th class="py-3" style="width:160px;">Nombor Telefon PIC</th>
                                <th class="text-end py-3" style="width:150px;">Nilai Kerja (RM)</th>
                                <th class="text-center py-3" style="width:80px;"></th>
                            </tr>
                        </thead>
                        <tbody id="tbl-pengalaman-body">
                            <!-- rows rendered by JS below -->
                        </tbody>
                        <tfoot>
                            <tr style="border-top: 2px solid #e2e8f0;">
                                <th colspan="4" class="text-end text-uppercase text-muted" style="font-size:0.75rem;">Jumlah</th>
                                <th class="text-end fw-bold" id="total-nilai" style="font-size:0.875rem; color:#1e293b;">0.00</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>

        <!-- ===================== SECTION: DOKUMEN SOKONGAN ===================== -->
        <div class="content-card mb-4 p-0">
            <div class="borang-title-bar">Dokumen Sokongan</div>
            <div class="content-card-body p-4 pt-3">

                <p class="borang-subtitle mb-3">Memuat Naik Dokumen Sokongan di bawah.</p>

                <!-- Saved files (from API) -->
                @php $savedDokumens = $existingData['dokumens'] ?? []; @endphp
                @if(!empty($savedDokumens))
                <div class="mb-3">
                    <p class="text-muted mb-2" style="font-size:0.78rem;">Fail yang telah disimpan:</p>
                    <div id="saved-file-chips">
                        @foreach($savedDokumens as $doc)
                        <span class="saved-file-chip" data-uuid="{{ $doc['uuid'] }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            <a href="{{ $doc['url'] }}" target="_blank" class="text-decoration-none text-inherit">{{ $doc['original_name'] }}</a>
                            <button type="button" class="chip-del btn-del-saved-file" data-uuid="{{ $doc['uuid'] }}" title="Padam fail">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Upload Zone (for new files) -->
                <label class="upload-zone w-100" id="upload-zone" for="input-dokumen">
                    <div class="upload-zone-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="16 16 12 12 8 16"></polyline>
                            <line x1="12" y1="12" x2="12" y2="21"></line>
                            <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path>
                        </svg>
                    </div>
                    <span class="upload-zone-label">Klik disini untuk memuat naik</span>
                    <span class="upload-zone-sub">PDF, Word, Excel, Imej, ZIP — saiz maksimum 10 MB setiap fail</span>
                    <input type="file" id="input-dokumen" name="dokumen_pengalaman[]" multiple hidden accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.zip,.rar">
                </label>

                <!-- New file chips (from FileUpload.init) -->
                <div class="file-chip-list" id="file-chip-list"></div>

            </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <a href="{{ $kembaliUrl }}" class="btn-form btn-form-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali
            </a>
            <div class="d-flex gap-2">
                @if (!($viewOnly ?? false))
                <button type="button" class="btn-form btn-form-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    Laporan
                </button>
                <button type="submit" id="btn-simpan" class="btn-form btn-form-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Simpan
                </button>
                @endif
            </div>
        </div>

    </form>

@endsection

@section('scripts')
<script src="{{ asset('js/components/file-upload.js') }}"></script>
<script>
// ── Toast helper ──────────────────────────────────────────────────────────────
function showPkToast(type, message) {
    var icons = {
        success: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>',
        error:   '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>',
        warning: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>'
    };
    var toast = $('<div class="pk-toast pk-' + type + '">' + (icons[type] || '') + '<span>' + message + '</span></div>');
    $('#pk-toast-container').prepend(toast);
    setTimeout(function () { toast.fadeOut(400, function () { $(this).remove(); }); }, 5000);
}

$(document).ready(function () {

    // ── Existing data from PHP ────────────────────────────────────────
    var existingData = @json($existingData);

    // ── Row template ─────────────────────────────────────────────────
    var ADD_BTN =
        '<button type="button" class="row-action-btn btn-tambah-inline btn-tambah-row" title="Tambah baris">' +
        '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>' +
        '</button>';
    var DELETE_BTN =
        '<button type="button" class="row-action-btn btn-hapus-row" title="Buang baris">' +
        '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path></svg>' +
        '</button>';

    function buildRow(bil, data) {
        data = data || {};
        var nilaiFormatted = data.nilai_kerja
            ? parseFloat(data.nilai_kerja).toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
            : '';
        return $('<tr class="pengalaman-row">' +
            '<td class="text-center row-bil fw-semibold text-muted" style="font-size:0.8rem;">' + bil + '</td>' +
            '<td><input type="text" name="pengalaman_tajuk[]" class="form-control form-control-sm" placeholder="Senarai kerja yang disiapkan..." value="' + $('<div/>').text(data.tajuk || '').html() + '"></td>' +
            '<td><input type="text" name="pengalaman_pic[]" class="form-control form-control-sm" placeholder="PIC..." value="' + $('<div/>').text(data.pic || '').html() + '"></td>' +
            '<td><input type="text" name="pengalaman_telefon[]" class="form-control form-control-sm" placeholder="Nombor telefon PIC" value="' + $('<div/>').text(data.telefon_pic || '').html() + '"></td>' +
            '<td><input type="text" name="pengalaman_nilai[]" class="form-control form-control-sm text-end nilai-kerja" placeholder="0.00" value="' + nilaiFormatted + '"></td>' +
            '<td class="text-center">' +
                '<div class="d-inline-flex align-items-center gap-1">' + ADD_BTN + DELETE_BTN + '</div>' +
            '</td>' +
        '</tr>');
    }

    // ── Populate from existing data or seed two blank rows ────────────
    if (existingData && existingData.items && existingData.items.length > 0) {
        $.each(existingData.items, function (i, item) {
            $('#tbl-pengalaman-body').append(buildRow(i + 1, item));
        });
    } else {
        $('#tbl-pengalaman-body').append(buildRow(1));
        $('#tbl-pengalaman-body').append(buildRow(2));
    }

    updateTotal();

    // ── Re-number rows ───────────────────────────────────────────────
    function reNumber() {
        $('#tbl-pengalaman-body .pengalaman-row').each(function (i) {
            $(this).find('.row-bil').text(i + 1);
        });
    }

    // ── Update total ─────────────────────────────────────────────────
    function updateTotal() {
        var total = 0;
        $('#tbl-pengalaman-body .nilai-kerja').each(function () {
            total += parseFloat($(this).val().replace(/,/g, '')) || 0;
        });
        $('#total-nilai').text(total.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    }

    // ── Add row (inline + on each row) ───────────────────────────────
    $('#tbl-pengalaman-body').on('click', '.btn-tambah-row', function () {
        var bil = $('#tbl-pengalaman-body .pengalaman-row').length + 1;
        var $row = buildRow(bil);
        $(this).closest('tr').after($row);
        reNumber();
    });

    // ── Delete row ───────────────────────────────────────────────────
    $('#tbl-pengalaman-body').on('click', '.btn-hapus-row', function () {
        if ($('#tbl-pengalaman-body .pengalaman-row').length <= 1) return;
        $(this).closest('tr').remove();
        reNumber();
        updateTotal();
    });

    // ── Nilai kerja: numeric only + format on blur ───────────────────
    $('#tbl-pengalaman-body').on('input', '.nilai-kerja', function () {
        $(this).val($(this).val().replace(/[^\d.]/g, ''));
        updateTotal();
    });
    $('#tbl-pengalaman-body').on('blur', '.nilai-kerja', function () {
        var v = parseFloat($(this).val().replace(/,/g, '')) || 0;
        $(this).val(v.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        updateTotal();
    });

    // ── Delete saved file (AJAX) ─────────────────────────────────────
    $(document).on('click', '.btn-del-saved-file', function () {
        var btn  = $(this);
        var uuid = btn.data('uuid');
        var chip = btn.closest('.saved-file-chip');

        if (!confirm('Padam fail ini?')) return;

        btn.prop('disabled', true);

        $.ajax({
            url: '{{ route("pengalamanKerja.deleteFile", ":uuid") }}'.replace(':uuid', uuid),
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function (res) {
                chip.fadeOut(200, function () { $(this).remove(); });
                showPkToast('success', 'Fail berjaya dipadam.');
            },
            error: function (xhr) {
                btn.prop('disabled', false);
                var msg = (xhr.responseJSON && xhr.responseJSON.message) || 'Gagal memadam fail.';
                showPkToast('error', msg);
            }
        });
    });

    // ── Disable Simpan if no rows with content ───────────────────────
    $('#form-pengalaman-kerja').on('submit', function (e) {
        var hasContent = false;
        $('#tbl-pengalaman-body .pengalaman-row').each(function () {
            if ($(this).find('[name="pengalaman_tajuk[]"]').val().trim()) {
                hasContent = true;
                return false;
            }
        });
        if (!hasContent) {
            e.preventDefault();
            showPkToast('error', 'Sila isi sekurang-kurangnya satu baris pengalaman kerja.');
        }
    });

    // ── File Upload Zone ─────────────────────────────────────────────
    FileUpload.init({
        zoneId     : 'upload-zone',
        inputId    : 'input-dokumen',
        chipListId : 'file-chip-list'
    });

});
</script>
@endsection
