@extends($layout ?? 'layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/file-upload.css') }}" rel="stylesheet">
    <style>
        #tbl-prestasi { border: 1px solid #e2e8f0; }
        #tbl-prestasi thead th {
            background: #1e3a5f;
            color: #fff;
            font-size: 0.72rem;
            letter-spacing: 0.02em;
            border-color: #1e3a5f !important;
            white-space: nowrap;
        }
        #tbl-prestasi th, #tbl-prestasi td { border-right: 1px solid #e2e8f0 !important; }
        #tbl-prestasi th:last-child, #tbl-prestasi td:last-child { border-right: none !important; }
        #tbl-prestasi .form-control-sm { font-size: 0.78rem; min-width: 90px; }

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
            color: #1e40af;
            font-weight: 600;
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
        .row-action-btn.btn-tambah-inline { background: #dbeafe; color: #2563eb; }
        .row-action-btn.btn-hapus-row { background: #fee2e2; color: #ef4444; }
    </style>
@endsection

@section('content')
    @include('tenders.forms._view_only_lock')

    @php
        $kembaliUrl = $returnUrl ?? route('senaraiKewanganKerja', $tender->uuid);
        $viewOnly = $viewOnly ?? false;
    @endphp

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Prestasi Kerja Semasa Petender</h3>
            <p class="text-muted small m-0">Isi maklumat prestasi kerja semasa yang sedang dilaksanakan oleh petender.</p>
        </div>
    </div>

    <!-- TENDER INFO -->
    <div class="content-card mb-4 p-0">
        <div class="content-card-body p-4">
            <div class="mb-3 pb-3 border-bottom">
                <span class="text-muted fw-semibold text-uppercase d-block mb-1" style="font-size:0.67rem;letter-spacing:0.5px;">Tajuk Tender</span>
                <h5 class="fw-bold text-dark mb-0" style="line-height:1.45;font-size:1rem;">
                    {{ $tender->name ?? '-' }}
                    @if($tender?->kategori_perolehan_name)
                    <span class="fw-normal text-muted fst-italic" style="font-size:0.85rem;">({{ $tender->kategori_perolehan_name }})</span>
                    @endif
                </h5>
            </div>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1" style="font-size:0.67rem;letter-spacing:0.5px;">No. Tender</span>
                    <span class="fw-semibold text-dark" style="font-size:0.875rem;">
                        {{ $tender->no_tender ?: ($tender->ref_number ?? '-') }}
                    </span>
                </div>
                <div class="col-6 col-md-3">
                    <span class="text-muted fw-semibold text-uppercase d-block mb-1" style="font-size:0.67rem;letter-spacing:0.5px;">PTJ</span>
                    <span class="fw-semibold text-dark" style="font-size:0.875rem;">
                        {{ $tender->tenderer->name ?? '-' }}
                    </span>
                </div>
                <div class="col-12 col-md-6 d-md-flex justify-content-md-end align-items-md-center">
                    @if(isset($prestasi) && $prestasi->status === 'submitted')
                        <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2 fw-semibold"
                            style="background:#dcfce7;color:#166534;font-size:0.8rem;border:1px solid #bbf7d0;">
                            <span class="d-inline-block rounded-circle" style="width:7px;height:7px;background:#16a34a;flex-shrink:0;"></span>
                            Telah Dihantar
                        </span>
                    @else
                        <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2 fw-semibold"
                            style="background:#fef9c3;color:#854d0e;font-size:0.8rem;border:1px solid #fde68a;">
                            <span class="d-inline-block rounded-circle" style="width:7px;height:7px;background:#ca8a04;flex-shrink:0;"></span>
                            Dalam Proses
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <form id="form-prestasi" action="{{ route('prestasiKerjaSemasa.store', $tender->uuid) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if (session('error'))
        <div class="alert alert-danger py-2 px-3 mb-3" style="font-size:0.85rem;">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger py-2 px-3 mb-3" style="font-size:0.85rem;">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (! empty($returnUrl))
        <input type="hidden" name="return" value="{{ $returnUrl }}">
    @endif
    @if ($modalEmbed ?? false)
        <input type="hidden" name="modal" value="1">
    @endif

        <!-- PRESTASI KERJA SEMASA -->
        <div class="content-card mb-4 p-0">
            <div class="borang-title-bar">Prestasi Kerja Semasa Petender</div>
            <div class="content-card-body p-4 pt-3">
                <div class="table-responsive">
                    <table id="tbl-prestasi" class="table table-modern align-middle mb-0 w-100">
                        <thead>
                            <tr>
                                <th class="text-center py-3" style="width:44px;">Bil.</th>
                                <th class="py-3" style="min-width:160px;">Nama Ringkas Kerja Semasa</th>
                                <th class="py-3" style="min-width:130px;">No. Kontrak Kerja Semasa</th>
                                <th class="text-end py-3" style="min-width:120px;">Harga Kontrak (RM)</th>
                                <th class="text-end py-3" style="min-width:140px;">Wang Kos Prima (RM)</th>
                                <th class="text-end py-3" style="min-width:150px;">Wang Peruntukan Semasa (RM)</th>
                                <th class="py-3" style="min-width:120px;">Tarikh Pemilikan Tapak</th>
                                <th class="text-center py-3" style="min-width:100px;">Tempoh Kontrak (Hari) (P)</th>
                                <th class="py-3" style="min-width:140px;">Tarikh Siap Kontrak <span class="fw-normal">(termasuk EOT diluluskan)</span></th>
                                <th class="py-3" style="min-width:120px;">Tarikh Penilaian Kemajuan</th>
                                <th class="text-center py-3" style="min-width:110px;">Luputan Tarikh Siap Kontrak (Hari) (D)</th>
                                <th class="text-center py-3" style="min-width:100px;">Peratus Kemajuan Sebenar Dicapai (A) (%)</th>
                                <th class="text-center py-3" style="min-width:100px;">Peratus Kemajuan Mengikut Jadual (S) (%)</th>
                                @unless($viewOnly)
                                <th class="text-center py-3" style="width:70px;"></th>
                                @endunless
                            </tr>
                        </thead>
                        <tbody id="tbl-prestasi-body"></tbody>
                    </table>
                </div>

                @unless($viewOnly)
                <div class="d-flex justify-content-end mt-3">
                    <button type="button" id="btn-tambah-prestasi" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah
                    </button>
                </div>
                @endunless
            </div>
        </div>

        <!-- DOKUMEN SOKONGAN -->
        <div class="content-card mb-4 p-0">
            <div class="borang-title-bar">Dokumen Sokongan</div>
            <div class="content-card-body p-4 pt-3">
                <p class="borang-subtitle mb-3">Perlu diisi oleh Petender</p>

                <div class="row g-3 align-items-start">
                    <div class="col-12 col-md-3">
                        <label class="form-label fw-semibold small mb-0">Dokumen Sokongan</label>
                    </div>
                    <div class="col-12 col-md-9">
                        @unless($viewOnly)
                        <label class="upload-zone w-100" id="upload-zone-prestasi" for="input-dokumen-prestasi">
                            <div class="upload-zone-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="16 16 12 12 8 16"></polyline>
                                    <line x1="12" y1="12" x2="12" y2="21"></line>
                                    <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path>
                                </svg>
                            </div>
                            <span class="upload-zone-label">Sila Muat Naik fail di sini</span>
                            <input type="file" id="input-dokumen-prestasi" name="dokumen_prestasi[]" multiple hidden accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">
                        </label>
                        @endunless

                        <div class="file-chip-list" id="file-chip-list-prestasi">
                            @if(isset($prestasi) && $prestasi->dokumens->isNotEmpty())
                                @foreach($prestasi->dokumens as $doc)
                                    <div class="file-chip" data-file-uuid="{{ $doc->uuid }}">
                                        <span class="file-chip-ext ext-{{ strtolower(pathinfo($doc->original_name, PATHINFO_EXTENSION)) }}">
                                            {{ strtolower(pathinfo($doc->original_name, PATHINFO_EXTENSION)) }}
                                        </span>
                                        <div class="file-chip-body">
                                            <a href="{{ asset($doc->path) }}" target="_blank" class="file-chip-name" title="{{ $doc->original_name }}">
                                                {{ $doc->original_name }}
                                            </a>
                                            <span class="file-chip-size">
                                                @if($doc->size < 1024)
                                                    {{ $doc->size }} B
                                                @elseif($doc->size < 1048576)
                                                    {{ number_format($doc->size / 1024, 1) }} KB
                                                @else
                                                    {{ number_format($doc->size / 1048576, 1) }} MB
                                                @endif
                                            </span>
                                        </div>
                                        @unless($viewOnly)
                                        <button type="button" class="file-chip-remove btn-delete-file" data-file-uuid="{{ $doc->uuid }}" title="Buang fail">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                            </svg>
                                        </button>
                                        @endunless
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            @include('tenders.forms._vendor_form_kembali', ['kembaliUrl' => $kembaliUrl])
            <div class="d-flex gap-2">
                <button type="button" class="btn-form btn-form-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    Laporan
                </button>
                @unless($viewOnly)
                <button type="submit" class="btn-form btn-form-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Simpan
                </button>
                @endunless
            </div>
        </div>

    </form>

@endsection

@section('scripts')
<script src="{{ asset('js/components/file-upload.js') }}"></script>
<script>
$(document).ready(function () {

    var VIEW_ONLY = @json($viewOnly);
    @php
        if (old('nama')) {
            $prestasiItems = collect(old('nama', []))->map(function ($nama, $index) {
                return [
                    'nama'                   => $nama,
                    'no_kontrak'             => old('no_kontrak.' . $index),
                    'harga'                  => old('harga.' . $index),
                    'wang_kos_prima'         => old('wang_kos_prima.' . $index),
                    'wang_peruntukan_semasa' => old('wang_peruntukan_semasa.' . $index),
                    'tarikh_tapak'           => old('tarikh_tapak.' . $index),
                    'tempoh'                 => old('tempoh.' . $index),
                    'tarikh_siap'            => old('tarikh_siap.' . $index),
                    'tarikh_penilaian'       => old('tarikh_penilaian.' . $index),
                    'luputan'                => old('luputan.' . $index),
                    'kemajuan_sebenar'       => old('kemajuan_sebenar.' . $index),
                    'kemajuan_jadual'        => old('kemajuan_jadual.' . $index),
                ];
            })->values()->toArray();
        } else {
            $prestasiItems = isset($prestasi) ? $prestasi->items->map(function ($item) {
                return [
                    'nama'                   => $item->nama,
                    'no_kontrak'             => $item->no_kontrak,
                    'harga'                  => floatval($item->harga),
                    'wang_kos_prima'         => $item->wang_kos_prima !== null ? floatval($item->wang_kos_prima) : null,
                    'wang_peruntukan_semasa' => $item->wang_peruntukan_semasa !== null ? floatval($item->wang_peruntukan_semasa) : null,
                    'tarikh_tapak'           => $item->tarikh_tapak,
                    'tempoh'                 => $item->tempoh,
                    'tarikh_siap'            => $item->tarikh_siap,
                    'tarikh_penilaian'       => $item->tarikh_penilaian,
                    'luputan'                => $item->luputan,
                    'kemajuan_sebenar'       => $item->kemajuan_sebenar,
                    'kemajuan_jadual'        => $item->kemajuan_jadual,
                ];
            })->toArray() : [];
        }
    @endphp
    var entries = @json($prestasiItems);

    var DELETE_BTN = '<button type="button" class="row-action-btn btn-hapus-row" title="Buang baris">' +
        '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path></svg></button>';

    function esc(v) { return $('<div/>').text(v || '').html(); }
    function fmtAmt(n) { return (parseFloat(n) || 0).toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }
    function parseAmt(s) { return parseFloat(String(s).replace(/,/g, '')) || 0; }
    function ro() { return VIEW_ONLY ? ' readonly' : ''; }
    function dis() { return VIEW_ONLY ? ' disabled' : ''; }

    function buildRow(bil, data) {
        data = data || {};
        var hargaFmt = data.harga ? fmtAmt(data.harga) : '';
        var wkpFmt = (data.wang_kos_prima !== null && data.wang_kos_prima !== undefined && data.wang_kos_prima !== '') ? fmtAmt(data.wang_kos_prima) : '';
        var wpsFmt = (data.wang_peruntukan_semasa !== null && data.wang_peruntukan_semasa !== undefined && data.wang_peruntukan_semasa !== '') ? fmtAmt(data.wang_peruntukan_semasa) : '';
        var actionCol = VIEW_ONLY ? '' :
            '<td class="text-center"><div class="d-inline-flex align-items-center justify-content-center">' + DELETE_BTN + '</div></td>';

        return $('<tr class="prestasi-row">' +
            '<td class="text-center row-bil fw-semibold text-muted" style="font-size:0.8rem;">' + bil + '</td>' +
            '<td><input type="text" name="nama[]" class="form-control form-control-sm field-nama" placeholder="Nama ringkas..." value="' + esc(data.nama) + '"' + ro() + '></td>' +
            '<td><input type="text" name="no_kontrak[]" class="form-control form-control-sm" placeholder="No. kontrak..." value="' + esc(data.no_kontrak) + '"' + ro() + '></td>' +
            '<td><input type="text" name="harga[]" class="form-control form-control-sm text-end field-harga" placeholder="0.00" value="' + hargaFmt + '"' + ro() + '></td>' +
            '<td><input type="text" name="wang_kos_prima[]" class="form-control form-control-sm text-end field-harga" placeholder="0.00" value="' + wkpFmt + '"' + ro() + '></td>' +
            '<td><input type="text" name="wang_peruntukan_semasa[]" class="form-control form-control-sm text-end field-harga" placeholder="0.00" value="' + wpsFmt + '"' + ro() + '></td>' +
            '<td><input type="text" name="tarikh_tapak[]" class="form-control form-control-sm pk-date" placeholder="dd/mm/yyyy" value="' + esc(data.tarikh_tapak) + '" readonly' + dis() + '></td>' +
            '<td><input type="number" name="tempoh[]" class="form-control form-control-sm text-center" placeholder="0" min="0" value="' + esc(data.tempoh) + '"' + ro() + '></td>' +
            '<td><input type="text" name="tarikh_siap[]" class="form-control form-control-sm pk-date" placeholder="dd/mm/yyyy" value="' + esc(data.tarikh_siap) + '" readonly' + dis() + '></td>' +
            '<td><input type="text" name="tarikh_penilaian[]" class="form-control form-control-sm pk-date" placeholder="dd/mm/yyyy" value="' + esc(data.tarikh_penilaian) + '" readonly' + dis() + '></td>' +
            '<td><input type="number" name="luputan[]" class="form-control form-control-sm text-center" placeholder="0" min="0" value="' + esc(data.luputan) + '"' + ro() + '></td>' +
            '<td><input type="number" name="kemajuan_sebenar[]" class="form-control form-control-sm text-center" placeholder="0" min="0" max="100" step="0.01" value="' + esc(data.kemajuan_sebenar) + '"' + ro() + '></td>' +
            '<td><input type="number" name="kemajuan_jadual[]" class="form-control form-control-sm text-center" placeholder="0" min="0" max="100" step="0.01" value="' + esc(data.kemajuan_jadual) + '"' + ro() + '></td>' +
            actionCol +
        '</tr>');
    }

    function reNumber() {
        $('#tbl-prestasi-body .prestasi-row').each(function (i) {
            $(this).find('.row-bil').text(i + 1);
        });
    }

    function initDatepickers($scope) {
        if (VIEW_ONLY) return;
        $scope.find('.pk-date').datepicker({ format: 'd M yyyy', autoclose: true, todayHighlight: true, todayBtn: 'linked' });
    }

    function appendRow(data) {
        var bil = $('#tbl-prestasi-body .prestasi-row').length + 1;
        var $row = buildRow(bil, data);
        $('#tbl-prestasi-body').append($row);
        initDatepickers($row);
        reNumber();
        return $row;
    }

    if (entries.length > 0) {
        $.each(entries, function (i, e) { appendRow(e); });
    } else {
        appendRow({});
    }

    $('#btn-tambah-prestasi').on('click', function () { appendRow({}); });

    $('#tbl-prestasi-body').on('click', '.btn-hapus-row', function () {
        if ($('#tbl-prestasi-body .prestasi-row').length <= 1) return;
        $(this).closest('tr').remove();
        reNumber();
    });

    $('#tbl-prestasi-body').on('focus', '.field-harga', function () {
        $(this).val($(this).val().replace(/,/g, '') || '');
    }).on('blur', '.field-harga', function () {
        if ($(this).val()) $(this).val(fmtAmt($(this).val()));
    }).on('input', '.field-harga', function () {
        $(this).val($(this).val().replace(/[^\d.]/g, ''));
    });

    $('#form-prestasi').on('submit', function (e) {
        $('#tbl-prestasi-body .field-harga').each(function () {
            $(this).val(($(this).val() || '').replace(/,/g, ''));
        });

        $('#tbl-prestasi-body .prestasi-row').each(function () {
            if (!$(this).find('.field-nama').val().trim()) {
                $(this).find(':input').prop('disabled', true);
            }
        });

        var hasContent = false;
        $('#tbl-prestasi-body .field-nama').each(function () {
            if (!$(this).prop('disabled') && $(this).val().trim()) {
                hasContent = true;
                return false;
            }
        });

        if (!hasContent) {
            e.preventDefault();
            $('#tbl-prestasi-body .prestasi-row :input').prop('disabled', false);
            alert('Sila isi sekurang-kurangnya satu baris prestasi kerja semasa.');
        }
    });

    $('#file-chip-list-prestasi').on('click', '.btn-delete-file', function () {
        var $chip = $(this).closest('.file-chip');
        var fileUuid = $(this).data('file-uuid');
        if (!confirm('Adakah anda pasti mahu memadam fail ini?')) return;
        $.ajax({
            url: '{{ route("prestasiKerjaSemasa.deleteFile", "FILE_UUID") }}'.replace('FILE_UUID', fileUuid),
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function (res) {
                if (res.success) $chip.remove();
                else alert(res.message || 'Gagal memadam fail.');
            },
            error: function () { alert('Ralat semasa menghubungi pelayan.'); }
        });
    });

    if (!VIEW_ONLY) {
        FileUpload.init({
            zoneId     : 'upload-zone-prestasi',
            inputId    : 'input-dokumen-prestasi',
            chipListId : 'file-chip-list-prestasi'
        });
    }

});
</script>
@endsection
