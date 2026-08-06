@extends('layouts.v3.master')

@section('content')
    <style>
        .stats-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            position: relative;
        }
        .stats-card::before {
            content: ''; position: absolute; top: -25px; right: -25px; width: 80px; height: 80px;
            background: var(--sg-red); opacity: 0.03; border-radius: 20px; transform: rotate(45deg); pointer-events: none;
        }
        .table-modern thead th, .table-modern tfoot th {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.5px;
            padding: 14px 20px;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
            vertical-align: middle;
        }
        .table-modern tbody td {
            padding: 16px 20px;
            vertical-align: middle;
            color: #334155;
            font-size: 0.9rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .table-modern tbody tr:hover {
            background-color: #fff9f9;
        }
        .heartbeat {
            display: inline-block;
            animation: heartbeat 1.2s infinite;
        }
        @keyframes heartbeat {
            0% { transform: scale(1); }
            25% { transform: scale(1.05); }
            40% { transform: scale(1); }
            60% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .lawatan-tapak-modal-card {
            border-radius: 10px;
            border: 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 18px 18px 14px;
            text-align: center;
            min-height: 200px;
        }
        .badge-status-draf {
            background: #fff4e5;
            color: #b45309;
            border: 1px solid #fcd9a4;
        }
        .badge-status-dihantar {
            background: #e6f7f3;
            color: #0f766e;
            border: 1px solid #99e2d3;
        }
        .field-group label.form-label {
            font-size: 0.72rem;
        }
        .tujuan-toggle {
            display: flex;
            gap: 10px;
        }
        .tujuan-toggle .btn-check {
            position: absolute;
            clip: rect(0, 0, 0, 0);
        }
        .tujuan-toggle label {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.88rem;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            transition: all .15s ease;
            margin-bottom: 0;
        }
        .tujuan-toggle label:hover {
            border-color: var(--sg-red);
            color: var(--sg-red);
        }
        .tujuan-toggle .btn-check:checked + label {
            border-color: var(--sg-red);
            background: var(--sg-red);
            color: #fff;
            box-shadow: 0 4px 10px rgba(196, 30, 58, 0.25);
        }
        .tujuan-toggle label .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: currentColor;
            opacity: .55;
        }
        .faktor-check-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 0;
            height: 100%;
            cursor: pointer;
            transition: all .15s ease;
        }
        .faktor-check-item:hover {
            border-color: var(--sg-red);
            background: #fff9f9;
        }
        .faktor-check-item:has(input:checked) {
            border-color: var(--sg-red);
            background: var(--sg-red-soft, rgba(196, 30, 58, 0.05));
        }
        .faktor-check-item input {
            margin-top: 2px;
            flex-shrink: 0;
        }
        .input-group-text-hari {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-left: 0;
            color: #64748b;
            font-weight: 600;
            font-size: 0.85rem;
        }
    </style>

    <div class="card border shadow-sm mb-2 rounded-3">
        <div class="card-body p-3">
            <div class="row g-2 align-items-end">
                <div class="col-4 col-lg-4">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Tender</label>
                    <h6 class="text-primary">{{ $tenderModel->no_tender ?? $tenderModel->ref_number ?? 'SUKSEL/PERT/2026/001' }}</h6>
                </div>
                <div class="col-4 col-lg-4">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">PTJ</label>
                    <h6 class="text-primary">{{ optional($tenderModel?->tenderer)->name ?? '100-007' }}</h6>
                </div>
                <div class="col-4 col-lg-4">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Status</label>
                    <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 fw-bold text-uppercase heartbeat" style="font-size: 0.8rem;">
                        Dalam Proses
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="content-card mb-4 p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width: 38px; height: 38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                        <line x1="7" y1="8" x2="17" y2="8"></line>
                        <line x1="7" y1="12" x2="17" y2="12"></line>
                        <line x1="7" y1="16" x2="17" y2="16"></line>
                    </svg>
                </div>
                <div>
                    <h3 class="content-card-title mb-0" style="font-size: 1rem;">Senarai Cadangan Pembekal</h3>
                    <p class="text-muted mb-0" style="font-size: 0.78rem;">Tetapkan sama ada Surat Niat diperlukan bagi setiap pembekal</p>
                </div>
            </div>
        </div>

        <div class="content-card-body p-4">
            <div class="table-responsive">
                <table id="dt_pembekal" class="table table-modern w-100 mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">Pembekal</th>
                            <th class="text-center" width="160px">Surat Niat Diperlukan</th>
                            <th class="text-center" width="320px">Catatan</th>
                        </tr>
                    </thead>
                    <tbody id="pembekal-tbody">
                        @forelse ($pembekals as $pembekal)
                            <tr class="pembekal-row"
                                data-pembekal-id="{{ $pembekal['id'] ?? '' }}"
                                data-vendor-id="{{ $pembekal['vendor_id'] }}"
                                data-vendor-name="{{ $pembekal['vendor_name'] }}"
                                data-vendor-address="{{ $pembekal['vendor_address'] }}">
                                <td class="text-center fw-medium">{{ $pembekal['vendor_name'] ?? '-' }}</td>
                                <td class="text-center">
                                    <select class="form-select form-select-sm shadow-none w-auto mx-auto pembekal-diperlukan">
                                        <option value="1" {{ $pembekal['diperlukan'] ? 'selected' : '' }}>Ya</option>
                                        <option value="0" {{ !$pembekal['diperlukan'] ? 'selected' : '' }}>Tidak</option>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <input type="text" class="form-control form-control-sm pembekal-catatan"
                                        placeholder="Sebab surat niat tidak diperlukan"
                                        value="{{ $pembekal['catatan'] }}"
                                        style="{{ $pembekal['diperlukan'] ? 'display:none;' : '' }}">
                                </td>
                            </tr>
                        @empty
                            <tr id="pembekal-no-data">
                                <td colspan="3" class="text-center text-muted py-4 small">Tiada pembekal dijumpai untuk tender ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="content-card mb-4 p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width: 38px; height: 38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="content-card-title mb-0" style="font-size: 1rem;">Perbincangan / Rundingan</h3>
                    <p class="text-muted mb-0" style="font-size: 0.78rem;">Sedia maklumat perbincangan/rundingan sebelum menjana surat niat</p>
                </div>
            </div>
        </div>

        <div class="content-card-body p-4">
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Pilih Pembekal <span class="text-danger">*</span></label>
                    <select id="rundingan_pembekal" class="form-select">
                        <option value="">Sila Pilih</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tujuan <span class="text-danger">*</span></label>
                    <div class="tujuan-toggle">
                        <input type="radio" class="btn-check" name="rundingan_tujuan" id="tujuan_perbincangan" value="perbincangan" checked>
                        <label for="tujuan_perbincangan"><span class="dot"></span> Perbincangan</label>

                        <input type="radio" class="btn-check" name="rundingan_tujuan" id="tujuan_rundingan" value="rundingan">
                        <label for="tujuan_rundingan"><span class="dot"></span> Rundingan</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tempoh Maklumbalas Surat (Hari) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" min="1" id="rundingan_tempoh" class="form-control" placeholder="Cth: 7">
                        <span class="input-group-text input-group-text-hari">Hari</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Jenis Surat</label>
                    <input type="text" class="form-control" value="Surat Niat" readonly>
                </div>

                <div class="col-12">
                    <label class="form-label small fw-bold text-secondary text-uppercase mb-2 d-block">Faktor Perbincangan / Rundingan <span class="text-danger">*</span></label>
                    <div class="row g-3" id="faktor-options">
                        @foreach ($faktorOptions as $key => $label)
                            <div class="col-md-6">
                                <label class="faktor-check-item" for="faktor_{{ $key }}">
                                    <input class="form-check-input faktor-checkbox" type="checkbox" value="{{ $key }}" id="faktor_{{ $key }}">
                                    <span class="small">{{ $label }}</span>
                                </label>
                            </div>
                        @endforeach
                        <div class="col-md-6">
                            <label class="faktor-check-item" for="faktor_lain_lain">
                                <input class="form-check-input" type="checkbox" id="faktor_lain_lain">
                                <span class="small">Lain-lain</span>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="faktor_lain_text" class="form-control h-100" placeholder="Nyatakan faktor lain-lain" style="display:none;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="button" id="btn-jana-surat" class="btn btn-md btn-selangor fw-medium">
                    Jana Surat
                </button>
            </div>
        </div>
    </div>

    <div class="content-card mb-4 p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div>
                    <h3 class="content-card-title mb-0" style="font-size: 1rem;">Senarai Surat</h3>
                    <p class="text-muted mb-0" style="font-size: 0.78rem;">Surat Niat yang telah dijana bagi tender ini</p>
                </div>
            </div>
        </div>

        <div class="content-card-body p-4">
            <div class="table-responsive">
                <table id="dt_surat" class="table table-modern w-100 mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">No. LOA</th>
                            <th class="text-center">Jenis</th>
                            <th class="text-center">Pembekal</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody id="surat-tbody">
                        <tr id="surat-no-data">
                            <td colspan="6" class="text-center text-muted py-4 small">Tiada Data</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="d-flex justify-content-end align-items-center mb-4 flex-wrap gap-2">
        <div class="d-flex gap-2">
            <button type="button" class="btn-form btn-form-success" id="btn-simpan">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Simpan
            </button>
            <button type="button" class="btn-form btn-form-primary" id="btn-hantar">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13"/>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                </svg>
                Hantar
            </button>
        </div>
    </div>
@endsection

@push('modals')
    <!-- Edit Surat Modal -->
    <div class="modal fade" id="modalEditSurat" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header px-4 pt-4 border-0">
                    <h5 class="modal-title fw-bold">Kemaskini Surat Niat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <input type="hidden" id="edit_surat_id">
                    <div class="row g-3 mb-2">
                        <div class="col-lg-6">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Pembekal</label>
                            <input type="text" id="edit_pembekal_nama" class="form-control bg-light" readonly>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tujuan</label>
                            <div class="d-flex gap-3 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="edit_tujuan" id="edit_tujuan_perbincangan" value="perbincangan">
                                    <label class="form-check-label" for="edit_tujuan_perbincangan">Perbincangan</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="edit_tujuan" id="edit_tujuan_rundingan" value="rundingan">
                                    <label class="form-check-label" for="edit_tujuan_rundingan">Rundingan</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tempoh Maklumbalas (Hari)</label>
                            <input type="number" min="1" id="edit_tempoh" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-2 d-block">Faktor Perbincangan / Rundingan</label>
                            <div class="d-flex flex-wrap gap-3" id="edit-faktor-options">
                                @foreach ($faktorOptions as $key => $label)
                                    <div class="form-check">
                                        <input class="form-check-input edit-faktor-checkbox" type="checkbox" value="{{ $key }}" id="edit_faktor_{{ $key }}">
                                        <label class="form-check-label small" for="edit_faktor_{{ $key }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="edit_faktor_lain_lain">
                                    <label class="form-check-label small" for="edit_faktor_lain_lain">Lain-lain</label>
                                </div>
                            </div>
                            <input type="text" id="edit_faktor_lain_text" class="form-control form-control-sm mt-2 w-50" placeholder="Nyatakan faktor lain-lain" style="display:none;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-form btn-form-primary" id="btn-simpan-edit-surat">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content lawatan-tapak-modal-card">
                <h5 class="fw-bold mb-2" id="successModalTitle">Berjaya</h5>
                <p class="text-muted mb-4" id="successModalMessage">Maklumat telah berjaya disimpan.</p>
                <button type="button" class="btn-form btn-form-primary mx-auto" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
@endpush

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            var tenderId = {{ (int) $tenderId }};
            var urls = {
                pembekals: '{{ route('suratNiat.pembekals', $tenderId) }}',
                savePembekals: '{{ route('suratNiat.savePembekals', $tenderId) }}',
                suratList: '{{ route('suratNiat.suratList', $tenderId) }}',
                generateSurat: '{{ route('suratNiat.generateSurat', $tenderId) }}',
                hantar: '{{ route('suratNiat.hantar', $tenderId) }}',
                updateSurat: '{{ url('surat-niat/surat') }}',
                deleteSurat: '{{ url('surat-niat/surat') }}',
                download: '{{ url('surat-niat/surat') }}'
            };

            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            var editSuratModal = new bootstrap.Modal(document.getElementById('modalEditSurat'));

            function showSuccess(message) {
                $('#successModalMessage').text(message);
                successModal.show();
            }

            function showError(message) {
                alert(message || 'Ralat tidak diketahui. Sila cuba semula.');
            }

            function pembekalList() {
                return $('.pembekal-row').map(function () {
                    var $row = $(this);
                    return {
                        vendor_id: $row.data('vendor-id'),
                        vendor_name: $row.data('vendor-name'),
                        vendor_address: $row.data('vendor-address'),
                        diperlukan: $row.find('.pembekal-diperlukan').val(),
                        catatan: $row.find('.pembekal-catatan').val()
                    };
                }).get();
            }

            function refreshPembekalDropdown() {
                var $select = $('#rundingan_pembekal');
                var current = $select.val();
                $select.find('option:not(:first)').remove();

                $('.pembekal-row').each(function () {
                    var $row = $(this);
                    if ($row.find('.pembekal-diperlukan').val() === '1') {
                        $select.append($('<option>', {
                            value: $row.data('pembekal-id'),
                            text: $row.data('vendor-name')
                        }));
                    }
                });

                if (current) {
                    $select.val(current);
                }
            }

            $(document).on('change', '.pembekal-diperlukan', function () {
                var $row = $(this).closest('.pembekal-row');
                var isTidak = $(this).val() === '0';
                $row.find('.pembekal-catatan').toggle(isTidak);
                if (! isTidak) {
                    $row.find('.pembekal-catatan').val('');
                }
                refreshPembekalDropdown();
            });

            $('#faktor_lain_lain').on('change', function () {
                $('#faktor_lain_text').toggle($(this).is(':checked'));
            });

            $('#edit_faktor_lain_lain').on('change', function () {
                $('#edit_faktor_lain_text').toggle($(this).is(':checked'));
            });

            function statusBadge(status) {
                if (status === 'dihantar') {
                    return '<span class="badge rounded-pill badge-status-dihantar px-3 py-2 fw-bold text-uppercase">Dihantar</span>';
                }
                return '<span class="badge rounded-pill badge-status-draf px-3 py-2 fw-bold text-uppercase">Draf</span>';
            }

            function renderSuratTable(rows) {
                var $tbody = $('#surat-tbody');
                $tbody.empty();

                if (! rows || rows.length === 0) {
                    $tbody.append('<tr id="surat-no-data"><td colspan="6" class="text-center text-muted py-4 small">Tiada Data</td></tr>');
                    return;
                }

                rows.forEach(function (surat, index) {
                    var editDisabled = surat.status !== 'draf' ? 'disabled' : '';
                    var $tr = $('<tr>').attr('data-surat-id', surat.id).html(
                        '<td class="text-center">' + (index + 1) + '</td>' +
                        '<td class="text-center fw-medium">' + surat.no_loa + '</td>' +
                        '<td class="text-center">' + surat.jenis + '</td>' +
                        '<td class="text-center">' + (surat.pembekal ? surat.pembekal.vendor_name : '-') + '</td>' +
                        '<td class="text-center">' + statusBadge(surat.status) + '</td>' +
                        '<td class="text-center">' +
                            '<button type="button" class="btn btn-sm btn-light border me-1 btn-edit-surat" ' + editDisabled + ' title="Kemaskini">' +
                                '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>' +
                            '</button>' +
                            '<a href="' + urls.download + '/' + surat.id + '/download" class="btn btn-sm btn-light border me-1" title="Muat Turun">' +
                                '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>' +
                            '</a>' +
                            '<button type="button" class="btn btn-sm btn-light border text-danger btn-delete-surat" title="Padam">' +
                                '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path></svg>' +
                            '</button>' +
                        '</td>'
                    );
                    $tr.data('surat', surat);
                    $tbody.append($tr);
                });
            }

            function loadSuratList() {
                $.getJSON(urls.suratList).done(function (response) {
                    renderSuratTable(response.data || []);
                }).fail(function () {
                    renderSuratTable([]);
                });
            }

            $('#btn-simpan').on('click', function () {
                var pembekal = pembekalList();
                var invalid = pembekal.some(function (row) {
                    return row.diperlukan === '0' && ! (row.catatan || '').trim();
                });

                if (invalid) {
                    showError('Sila lengkapkan Catatan untuk pembekal yang tidak memerlukan Surat Niat.');
                    return;
                }

                $.ajax({
                    url: urls.savePembekals,
                    method: 'POST',
                    data: { pembekal: pembekal }
                }).done(function (response) {
                    showSuccess(response.message || 'Senarai pembekal berjaya disimpan sebagai draf.');
                }).fail(function (xhr) {
                    showError(xhr.responseJSON ? xhr.responseJSON.message : null);
                });
            });

            $('#btn-hantar').on('click', function () {
                if (! confirm('Hantar semua Surat Niat berstatus draf bagi tender ini?')) {
                    return;
                }

                $.post(urls.hantar).done(function (response) {
                    showSuccess(response.message || 'Surat Niat berjaya dihantar.');
                    loadSuratList();
                }).fail(function (xhr) {
                    showError(xhr.responseJSON ? xhr.responseJSON.message : null);
                });
            });

            $('#btn-jana-surat').on('click', function () {
                var pembekalId = $('#rundingan_pembekal').val();
                var tujuan = $('input[name="rundingan_tujuan"]:checked').val();
                var tempoh = $('#rundingan_tempoh').val();
                var faktor = $('.faktor-checkbox:checked').map(function () { return $(this).val(); }).get();
                var faktorLain = $('#faktor_lain_lain').is(':checked') ? $('#faktor_lain_text').val().trim() : '';

                if (! pembekalId) {
                    showError('Sila pilih pembekal.');
                    return;
                }
                if (! tempoh || tempoh <= 0) {
                    showError('Sila masukkan Tempoh Maklumbalas Surat.');
                    return;
                }
                if (faktor.length === 0 && ! faktorLain) {
                    showError('Sila pilih sekurang-kurangnya satu Faktor Perbincangan/Rundingan.');
                    return;
                }

                $.ajax({
                    url: urls.generateSurat,
                    method: 'POST',
                    data: {
                        pembekal_id: pembekalId,
                        tujuan: tujuan,
                        tempoh_maklumbalas_hari: tempoh,
                        faktor: faktor,
                        faktor_lain: faktorLain
                    }
                }).done(function (response) {
                    showSuccess(response.message || 'Surat Niat berjaya dijana.');
                    loadSuratList();

                    $('#rundingan_pembekal').val('');
                    $('#rundingan_tempoh').val('');
                    $('.faktor-checkbox').prop('checked', false);
                    $('#faktor_lain_lain').prop('checked', false);
                    $('#faktor_lain_text').val('').hide();
                }).fail(function (xhr) {
                    showError(xhr.responseJSON ? xhr.responseJSON.message : null);
                });
            });

            $(document).on('click', '.btn-edit-surat', function () {
                var surat = $(this).closest('tr').data('surat');

                $('#edit_surat_id').val(surat.id);
                $('#edit_pembekal_nama').val(surat.pembekal ? surat.pembekal.vendor_name : '-');
                $('#edit_tempoh').val(surat.tempoh_maklumbalas_hari);
                $('input[name="edit_tujuan"][value="' + surat.tujuan + '"]').prop('checked', true);

                $('.edit-faktor-checkbox').prop('checked', false);
                (surat.faktor || []).forEach(function (key) {
                    $('#edit_faktor_' + key).prop('checked', true);
                });

                var hasLain = !! surat.faktor_lain;
                $('#edit_faktor_lain_lain').prop('checked', hasLain);
                $('#edit_faktor_lain_text').val(surat.faktor_lain || '').toggle(hasLain);

                editSuratModal.show();
            });

            $('#btn-simpan-edit-surat').on('click', function () {
                var id = $('#edit_surat_id').val();
                var faktor = $('.edit-faktor-checkbox:checked').map(function () { return $(this).val(); }).get();
                var faktorLain = $('#edit_faktor_lain_lain').is(':checked') ? $('#edit_faktor_lain_text').val().trim() : '';

                $.ajax({
                    url: urls.updateSurat + '/' + id,
                    method: 'PUT',
                    data: {
                        tujuan: $('input[name="edit_tujuan"]:checked').val(),
                        tempoh_maklumbalas_hari: $('#edit_tempoh').val(),
                        faktor: faktor,
                        faktor_lain: faktorLain
                    }
                }).done(function (response) {
                    editSuratModal.hide();
                    showSuccess(response.message || 'Surat Niat berjaya dikemaskini.');
                    loadSuratList();
                }).fail(function (xhr) {
                    showError(xhr.responseJSON ? xhr.responseJSON.message : null);
                });
            });

            $(document).on('click', '.btn-delete-surat', function () {
                var surat = $(this).closest('tr').data('surat');

                if (! confirm('Padam Surat Niat ' + surat.no_loa + '?')) {
                    return;
                }

                $.ajax({
                    url: urls.deleteSurat + '/' + surat.id,
                    method: 'DELETE'
                }).done(function (response) {
                    showSuccess(response.message || 'Surat Niat berjaya dipadam.');
                    loadSuratList();
                }).fail(function (xhr) {
                    showError(xhr.responseJSON ? xhr.responseJSON.message : null);
                });
            });

            refreshPembekalDropdown();
            loadSuratList();
        });
    </script>
@endsection
