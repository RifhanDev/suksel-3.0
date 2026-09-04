@extends($layout ?? 'layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/file-upload.css') }}" rel="stylesheet">
    <style>
        /* ── Table grid borders ─────────────────────────────────────── */
        #tbl-kakitangan {
            border: 1px solid #e2e8f0;
        }
        #tbl-kakitangan thead th {
            background: #1e3a5f;
            color: #fff;
            font-size: 0.78rem;
            letter-spacing: 0.03em;
            border-color: #1e3a5f !important;
        }
        #tbl-kakitangan th,
        #tbl-kakitangan td {
            border-right: 1px solid #e2e8f0 !important;
        }
        #tbl-kakitangan th:last-child,
        #tbl-kakitangan td:last-child {
            border-right: none !important;
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
        .row-action-btn.btn-edit-row {
            background: #e0f2fe;
            color: #0284c7;
        }
        .row-action-btn.btn-edit-row:hover {
            background: #bae6fd;
        }
        .row-action-btn.btn-hapus-row {
            background: #fee2e2;
            color: #ef4444;
        }
        .row-action-btn.btn-hapus-row:hover {
            background: #fecaca;
        }

        .existing-file-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 5px 10px;
            border-radius: 6px;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            font-size: 0.78rem;
            color: #334155;
            margin-right: 6px;
            margin-bottom: 6px;
        }
        .existing-file-chip .chip-del {
            cursor: pointer;
            color: #ef4444;
            display: inline-flex;
            align-items: center;
        }
        .existing-file-chip .chip-del:hover {
            color: #b91c1c;
        }

        /* ── Toast ── */
        #kt-toast-container {
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
        .kt-toast {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 1rem 1.25rem;
            border-radius: 10px;
            box-shadow: 0 8px 30px rgba(0,0,0,.13);
            font-size: 0.85rem;
            font-weight: 500;
            animation: ktSlideIn .28s cubic-bezier(.16,1,.3,1);
        }
        .kt-toast.kt-success { background:#f0fdf4; border:1.5px solid #86efac; color:#15803d; }
        .kt-toast.kt-error   { background:#fef2f2; border:1.5px solid #fca5a5; color:#b91c1c; }
        .kt-toast.kt-warning { background:#fffbeb; border:1.5px solid #fde68a; color:#92400e; }
        @keyframes ktSlideIn {
            from { opacity:0; transform:translateX(40px); }
            to   { opacity:1; transform:translateX(0); }
        }
    </style>
@endsection

@section('content')
    @include('tenders.forms._view_only_lock')

    @php
        $kembaliUrl = $returnUrl ?? ($tender ? route('senaraiTeknikal', $tender->uuid) : url()->previous());
    @endphp

    <div id="kt-toast-container"></div>

    @unless ($modalEmbed ?? false)
    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Kakitangan Teknikal</h3>
            <p class="text-muted small m-0">Borang atas talian untuk petender mengisi senarai kakitangan teknikal.</p>
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
    @endunless

    <!-- SECTION: SENARAI KAKITANGAN TEKNIKAL -->
    <div class="content-card mb-4 p-0">
        <div class="borang-title-bar">Senarai Kakitangan Teknikal</div>
        <div class="content-card-body p-4 pt-3">

            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <p class="borang-subtitle m-0">Senarai Kakitangan Teknikal Syarikat / Projek.</p>
                @if (!($viewOnly ?? false))
                <button type="button" id="btn-tambah-kakitangan" class="btn btn-sm btn-success rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1 shadow-sm"
                    style="font-size: 0.78rem; font-weight: 600;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Tambah Kakitangan</span>
                </button>
                @endif
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table id="tbl-kakitangan" class="table table-modern align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th class="text-center py-3" style="width:50px;">Bil.</th>
                            <th class="py-3" style="min-width:200px;">Nama Pegawai</th>
                            <th class="py-3" style="min-width:140px;">Kategori</th>
                            <th class="py-3" style="min-width:200px;">Tahap Pendidikan Tertinggi</th>
                            <th class="py-3" style="width:160px;">Jumlah Pengalaman</th>
                            @if (!($viewOnly ?? false))
                            <th class="text-center py-3" style="width:80px;">Tindakan</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="tbl-kakitangan-body">
                        @forelse($kakitanganList ?? [] as $index => $item)
                            <tr class="kakitangan-row" data-uuid="{{ $item->uuid }}" data-item="{{ json_encode($item) }}">
                                <td class="text-center row-bil fw-semibold text-muted" style="font-size:0.8rem;">{{ $index + 1 }}</td>
                                <td class="fw-medium text-dark cell-nama">{{ $item->nama_pegawai }}</td>
                                <td class="cell-kategori">
                                    <span class="badge rounded-pill {{ $item->kategori === 'Kategori A' ? 'bg-success' : ($item->kategori === 'Kategori B' ? 'bg-primary' : 'bg-warning text-dark') }} px-2.5 py-1">
                                        {{ $item->kategori }}
                                    </span>
                                </td>
                                <td class="text-muted cell-pendidikan">{{ $item->tahap_pendidikan }}</td>
                                <td class="text-muted cell-pengalaman">{{ $item->jumlah_pengalaman }} Tahun</td>
                                @if (!($viewOnly ?? false))
                                <td class="text-center">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <button type="button" class="row-action-btn btn-edit-row" title="Kemaskini">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </button>
                                        <button type="button" class="row-action-btn btn-hapus-row" title="Buang">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path></svg>
                                        </button>
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr id="tr-empty-state">
                                <td colspan="{{ ($viewOnly ?? false) ? 5 : 6 }}" class="text-center text-muted py-4 small">Tiada rekod kakitangan teknikal dimasukkan lagi. Klik "Tambah Kakitangan" di atas untuk menambah.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- SECTION: DOKUMEN SOKONGAN KESELURUHAN (SENARAI KAKITANGAN TEKNIKAL) -->
    <div class="content-card mb-4 p-0">
        <div class="borang-title-bar">Dokumen Sokongan Keseluruhan</div>
        <div class="content-card-body p-4 pt-3">
            @if ($viewOnly ?? false)
                @if(!empty($generalDokumens) && count($generalDokumens) > 0)
                    <p class="borang-subtitle mb-2">Dokumen dimuat naik:</p>
                @else
                    <p class="borang-subtitle mb-0">Tiada dokumen dimuat naik.</p>
                @endif
            @else
                <p class="borang-subtitle mb-3">Muat naik dokumen sokongan tambahan berkaitan senarai kakitangan teknikal secara keseluruhan (cth: Carta Organisasi, Ringkasan Kakitangan, atau dokumen sokongan lain).</p>
            @endif

            <!-- Container for existing overall uploaded document chips -->
            <div id="general-existing-files" class="mb-3">
                @if(!empty($generalDokumens) && count($generalDokumens) > 0)
                    @unless ($viewOnly ?? false)
                    <label class="form-label text-muted small d-block mb-1" style="font-size:0.75rem;">Dokumen Keseluruhan Sedia Ada:</label>
                    @endunless
                    @foreach($generalDokumens as $doc)
                        <span class="existing-file-chip" data-doc-uuid="{{ $doc->uuid }}">
                            <a href="{{ $doc->url }}" target="_blank" class="text-decoration-none text-dark fw-medium me-1"><i class="bi bi-paperclip me-1"></i>{{ $doc->original_name }}</a>
                            @if (!($viewOnly ?? false))
                            <span class="chip-del btn-del-general-file" title="Padam Dokumen">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </span>
                            @endif
                        </span>
                    @endforeach
                @endif
            </div>

            @if (!($viewOnly ?? false))
            <form id="form-general-dokumen" onsubmit="return false;" enctype="multipart/form-data">
                @csrf
                <label class="upload-zone w-100 p-3 text-center rounded-3 cursor-pointer" id="general-upload-zone" for="general-input-dokumen" style="border: 2px dashed #cbd5e1; background: #f8fafc; display: block;">
                    <div class="upload-zone-icon mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                            stroke="#64748b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="16 16 12 12 8 16"></polyline>
                            <line x1="12" y1="12" x2="12" y2="21"></line>
                            <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path>
                        </svg>
                    </div>
                    <span class="upload-zone-label d-block fw-semibold text-dark small mb-1">Klik di sini untuk memuat naik dokumen sokongan keseluruhan</span>
                    <span class="upload-zone-sub d-block text-muted" style="font-size:0.75rem;">PDF, Word, Excel, Imej — saiz maksimum 10 MB per fail (Boleh muat naik pelbagai fail)</span>
                    <input type="file" id="general-input-dokumen" name="dokumen_umum[]" multiple hidden accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.zip,.rar">
                </label>

                <!-- File list container for newly selected uploads -->
                <div class="file-chip-list mt-2" id="general-file-chip-list"></div>

                <div class="mt-3 text-end">
                    <button type="button" id="btn-upload-general-dokumen" class="btn btn-sm btn-primary rounded-8 px-3 py-1.5 d-inline-flex align-items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        <span>Muat Naik Dokumen Keseluruhan</span>
                    </button>
                </div>
            </form>
            @endif
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        @include('tenders.forms._vendor_form_kembali', ['kembaliUrl' => $kembaliUrl])
        <!-- <div class="d-flex gap-2">
            @if (!($viewOnly ?? false))
            <a href="{{ $kembaliUrl }}" class="btn-form btn-form-success text-decoration-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Selesai
            </a>
            @endif
        </div> -->
    </div>

    <!-- MODAL: TAMBAH / KEMASKINI KAKITANGAN TEKNIKAL -->
    <div class="modal fade" id="modal-kakitangan" tabindex="-1" aria-labelledby="modalKakitanganLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom bg-light py-3">
                    <h5 class="modal-title fw-bold text-dark fs-6 m-0" id="modalKakitanganLabel">Tambah Kakitangan Teknikal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="form-modal-kakitangan" onsubmit="return false;" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="kakitangan_uuid" id="input-kakitangan-uuid" value="">

                        <div class="row g-3 mb-3">
                            <!-- 1. Nama Pegawai -->
                            <div class="col-md-12">
                                <label class="form-label fw-semibold text-dark small mb-1">Nama Pegawai <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="nama_pegawai" id="input-nama-pegawai" placeholder="Masukkan nama penuh pegawai..." autocomplete="off" required>
                            </div>

                            <!-- 2. Tahap Pendidikan Tertinggi -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark small mb-1">Tahap Pendidikan Tertinggi <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm" name="tahap_pendidikan" id="select-tahap-pendidikan" required>
                                    <option value="">-- Sila Pilih --</option>
                                    <option value="Pascasiswazah">Pascasiswazah</option>
                                    <option value="Diploma dan Ijazah">Diploma dan Ijazah</option>
                                    <option value="SPM dan Sijil">SPM dan Sijil</option>
                                </select>
                            </div>

                            <!-- 3. Jumlah Pengalaman (Tahun) -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark small mb-1">Jumlah Pengalaman (Tahun) <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <input type="number" min="0" step="1" class="form-control form-control-sm" name="jumlah_pengalaman" id="input-jumlah-pengalaman" placeholder="0" inputmode="numeric" required>
                                    <span class="input-group-text">Tahun</span>
                                </div>
                            </div>

                            <!-- 4. Sijil Professional Teknikal -->
                            <div class="col-md-12">
                                <label class="form-label fw-semibold text-dark small mb-1">Sijil Professional Teknikal</label>
                                <textarea class="form-control form-control-sm" name="sijil_professional" id="input-sijil-professional" rows="3" placeholder="Nyatakan sijil profesional teknikal (cth: Ir., P.Eng, PMP, CIDB, etc.)..."></textarea>
                                <div id="sijil-professional-note" class="d-none align-items-center mt-1 text-warning-dark" style="font-size: 0.75rem; color: #b45309; font-weight: 500;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1 flex-shrink-0">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg>
                                    <span>Sekiranya medan ini diisi, sila muat naik sijil sebagai bukti.</span>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Dokumen Sokongan (Muat Naik Pelbagai Fail) -->
                        <div class="border-top pt-3 mt-3">
                            <label class="form-label fw-semibold text-dark small d-block mb-1">Dokumen Sokongan Kakitangan <span class="text-danger">*</span></label>
                            <p class="text-muted small mb-2" style="font-size: 0.75rem;">Muat naik salinan sijil kelayakan, sijil profesional, atau CV pegawai (Boleh pilih dan muat naik pelbagai fail).</p>
                            
                            <!-- Container for existing uploaded document chips -->
                            <div id="modal-existing-files" class="mb-2"></div>

                            <label class="upload-zone w-100 p-3 text-center rounded-3 cursor-pointer" id="modal-upload-zone" for="modal-input-dokumen" style="border: 2px dashed #cbd5e1; background: #f8fafc; display: block;">
                                <div class="upload-zone-icon mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                                        stroke="#64748b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="16 16 12 12 8 16"></polyline>
                                        <line x1="12" y1="12" x2="12" y2="21"></line>
                                        <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path>
                                    </svg>
                                </div>
                                <span class="upload-zone-label d-block fw-semibold text-dark small mb-1">Klik di sini untuk memuat naik dokumen</span>
                                <span class="upload-zone-sub d-block text-muted" style="font-size:0.75rem;">PDF, Word, Excel, Imej — saiz maksimum 10 MB per fail (Pilih pelbagai fail)</span>
                                <input type="file" id="modal-input-dokumen" name="kakitangan_dokumen[]" multiple hidden accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.zip,.rar">
                            </label>

                            <!-- File list container for new uploads -->
                            <div class="file-chip-list mt-2" id="modal-file-chip-list"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-top bg-light py-2">
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btn-modal-simpan-kakitangan" class="btn btn-sm btn-primary px-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        <span id="btn-simpan-text">Simpan</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script src="{{ asset('js/components/file-upload.js') }}"></script>
<script>
$(document).ready(function () {

    const STORE_URL = "{{ route('senaraiTeknikal.kakitanganTeknikal.store', $tender->uuid) }}";
    const CSRF_TOKEN = "{{ csrf_token() }}";

    // Toast Notification helper
    function showToast(message, type) {
        type = type || 'success';
        var icon = type === 'success' ? '✓' : (type === 'error' ? '✕' : 'ℹ');
        var $toast = $('<div class="kt-toast kt-' + type + '">' +
            '<div>' + icon + '</div>' +
            '<div>' + message + '</div>' +
        '</div>');
        $('#kt-toast-container').append($toast);
        setTimeout(function() {
            $toast.fadeOut(300, function() { $(this).remove(); });
        }, 4000);
    }

    const GENERAL_UPLOAD_URL = "{{ route('senaraiTeknikal.kakitanganTeknikal.uploadGeneralDokumen', $tender->uuid) }}";

    // Initialize multi-file upload for modal and general section
    if (typeof FileUpload !== 'undefined' && typeof FileUpload.init === 'function') {
        FileUpload.init({
            zoneId     : 'modal-upload-zone',
            inputId    : 'modal-input-dokumen',
            chipListId : 'modal-file-chip-list'
        });
        if ($('#general-upload-zone').length > 0) {
            FileUpload.init({
                zoneId     : 'general-upload-zone',
                inputId    : 'general-input-dokumen',
                chipListId : 'general-file-chip-list'
            });
        }
    }

    // Upload General Documents via AJAX
    $('#btn-upload-general-dokumen').on('click', function () {
        var inputFiles = $('#general-input-dokumen')[0] ? $('#general-input-dokumen')[0].files : null;
        if (!inputFiles || inputFiles.length === 0) {
            showToast('Sila pilih sekurang-kurangnya satu dokumen untuk dimuat naik.', 'warning');
            return;
        }

        var formData = new FormData($('#form-general-dokumen')[0]);
        var $btn = $(this);
        $btn.prop('disabled', true).addClass('opacity-75');

        $.ajax({
            url: GENERAL_UPLOAD_URL,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            success: function (res) {
                $btn.prop('disabled', false).removeClass('opacity-75');
                if (res.success && res.files) {
                    showToast(res.message, 'success');
                    $('#general-input-dokumen').val('');
                    $('#general-file-chip-list').empty();

                    var $container = $('#general-existing-files');
                    if ($container.find('label').length === 0) {
                        $container.html('<label class="form-label text-muted small d-block mb-1" style="font-size:0.75rem;">Dokumen Keseluruhan Sedia Ada:</label>');
                    }
                    res.files.forEach(function(doc) {
                        var chipHtml = '<span class="existing-file-chip" data-doc-uuid="' + doc.uuid + '">' +
                            '<a href="' + doc.url + '" target="_blank" class="text-decoration-none text-dark fw-medium me-1"><i class="bi bi-paperclip me-1"></i>' + $('<div/>').text(doc.original_name).html() + '</a>' +
                            '<span class="chip-del btn-del-general-file" title="Padam Dokumen">' +
                                '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>' +
                            '</span>' +
                        '</span>';
                        $container.append(chipHtml);
                    });
                } else {
                    showToast(res.message || 'Gagal memuat naik dokumen.', 'error');
                }
            },
            error: function (xhr) {
                $btn.prop('disabled', false).removeClass('opacity-75');
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal memuat naik dokumen.';
                showToast(msg, 'error');
            }
        });
    });

    // Delete General Document
    $('#general-existing-files').on('click', '.btn-del-general-file', function () {
        var $chip = $(this).closest('.existing-file-chip');
        var docUuid = $chip.data('doc-uuid');
        if (!docUuid) return;

        if (!confirm('Adakah anda pasti untuk memadam dokumen ini?')) return;

        $.ajax({
            url: '/kakitangan-teknikal-dokumen/' + docUuid,
            type: 'POST',
            data: {
                _token: CSRF_TOKEN,
                _method: 'DELETE'
            },
            success: function (res) {
                if (res.success) {
                    $chip.remove();
                    if ($('#general-existing-files .existing-file-chip').length === 0) {
                        $('#general-existing-files').empty();
                    }
                    showToast(res.message, 'success');
                } else {
                    showToast(res.message || 'Gagal memadam dokumen.', 'error');
                }
            },
            error: function (xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal memadam dokumen.';
                showToast(msg, 'error');
            }
        });
    });

    // Toggle note when typing in Sijil Professional Teknikal textarea
    $(document).on('input', 'textarea[name="sijil_professional"]', function () {
        var val = $(this).val().trim();
        if (val.length > 0) {
            $('#sijil-professional-note').removeClass('d-none').addClass('d-flex');
        } else {
            $('#sijil-professional-note').addClass('d-none').removeClass('d-flex');
        }
    });

    // Open Modal for Create
    $('#btn-tambah-kakitangan').on('click', function () {
        $('#modalKakitanganLabel').text('Tambah Kakitangan Teknikal');
        $('#btn-simpan-text').text('Simpan');
        $('#input-kakitangan-uuid').val('');
        $('#form-modal-kakitangan')[0].reset();
        if (typeof FileUpload !== 'undefined' && typeof FileUpload.reset === 'function') {
            FileUpload.reset('modal-input-dokumen');
        } else {
            $('#modal-input-dokumen').val('');
            $('#modal-file-chip-list').empty();
        }
        $('#sijil-professional-note').addClass('d-none').removeClass('d-flex');
        $('#modal-existing-files').empty();
        $('#modal-file-chip-list').empty();
        $('#modal-kakitangan').modal('show');
    });

    // Open Modal for Edit
    $('#tbl-kakitangan-body').on('click', '.btn-edit-row', function () {
        var $tr = $(this).closest('tr');
        var itemData = $tr.data('item');
        if (!itemData) return;

        $('#modalKakitanganLabel').text('Kemaskini Kakitangan Teknikal');
        $('#btn-simpan-text').text('Kemaskini');
        $('#input-kakitangan-uuid').val(itemData.uuid);
        $('#input-nama-pegawai').val(itemData.nama_pegawai);
        $('#select-tahap-pendidikan').val(itemData.tahap_pendidikan);
        $('#input-jumlah-pengalaman').val(itemData.jumlah_pengalaman);
        $('#input-sijil-professional').val(itemData.sijil_professional || '').trigger('input');
        if (typeof FileUpload !== 'undefined' && typeof FileUpload.reset === 'function') {
            FileUpload.reset('modal-input-dokumen');
        } else {
            $('#modal-input-dokumen').val('');
            $('#modal-file-chip-list').empty();
        }

        // Render existing files
        $('#modal-existing-files').empty();
        if (itemData.dokumens && itemData.dokumens.length > 0) {
            var html = '<label class="form-label text-muted small d-block mb-1" style="font-size:0.75rem;">Dokumen Sedia Ada:</label>';
            itemData.dokumens.forEach(function(doc) {
                html += '<span class="existing-file-chip" data-doc-uuid="' + doc.uuid + '">' +
                    '<a href="/kakitangan-teknikal-dokumen/' + doc.uuid + '/download" target="_blank" class="text-decoration-none text-dark fw-medium me-1"><i class="bi bi-paperclip me-1"></i>' + doc.original_name + '</a>' +
                    '<span class="chip-del btn-del-existing-file" title="Padam Dokumen">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>' +
                    '</span>' +
                '</span>';
            });
            $('#modal-existing-files').html(html);
        }

        $('#modal-file-chip-list').empty();
        $('#modal-kakitangan').modal('show');
    });

    // Delete Existing Document
    $('#modal-existing-files').on('click', '.btn-del-existing-file', function () {
        var $chip = $(this).closest('.existing-file-chip');
        var docUuid = $chip.data('doc-uuid');
        if (!docUuid) return;

        if (!confirm('Adakah anda pasti untuk memadam dokumen ini?')) return;

        $.ajax({
            url: '/kakitangan-teknikal-dokumen/' + docUuid,
            type: 'POST',
            data: {
                _token: CSRF_TOKEN,
                _method: 'DELETE'
            },
            success: function (res) {
                if (res.success) {
                    $chip.remove();
                    showToast(res.message, 'success');
                } else {
                    showToast(res.message || 'Gagal memadam dokumen.', 'error');
                }
            },
            error: function (xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal memadam dokumen.';
                showToast(msg, 'error');
            }
        });
    });

    // Save/Update Kakitangan Teknikal via AJAX
    $('#btn-modal-simpan-kakitangan').on('click', function () {
        var staffUuid = $('#input-kakitangan-uuid').val();
        var isEdit = staffUuid && staffUuid.trim() !== '';

        var nama = $('#input-nama-pegawai').val().trim();
        var pendidikan = $('#select-tahap-pendidikan').val();
        var pengalaman = $('#input-jumlah-pengalaman').val();
        var newFilesCount = $('#modal-input-dokumen')[0].files ? $('#modal-input-dokumen')[0].files.length : 0;
        var existingFilesCount = $('#modal-existing-files .existing-file-chip').length;

        // Validation
        if (!nama) {
            showToast('Sila isi nama pegawai.', 'warning');
            $('#input-nama-pegawai').focus();
            return;
        }
        if (!pendidikan) {
            showToast('Sila pilih tahap pendidikan tertinggi.', 'warning');
            $('#select-tahap-pendidikan').focus();
            return;
        }
        if (pengalaman === '' || parseInt(pengalaman) < 0) {
            showToast('Sila isi jumlah pengalaman.', 'warning');
            $('#input-jumlah-pengalaman').focus();
            return;
        }
        if ((newFilesCount + existingFilesCount) < 1) {
            showToast('Sila muat naik sekurang-kurangnya satu dokumen sokongan.', 'warning');
            return;
        }

        var formElement = $('#form-modal-kakitangan')[0];
        var formData = new FormData(formElement);

        var targetUrl = STORE_URL;
        if (isEdit) {
            targetUrl = '/kakitangan-teknikal/' + staffUuid;
            formData.append('_method', 'PUT');
        }

        var $btn = $(this);
        $btn.prop('disabled', true).addClass('opacity-75');

        $.ajax({
            url: targetUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            success: function (res) {
                $btn.prop('disabled', false).removeClass('opacity-75');
                if (res.success && res.data) {
                    $('#modal-kakitangan').modal('hide');
                    showToast(res.message, 'success');
                    renderOrUpdateRow(res.data);
                } else {
                    showToast(res.message || 'Gagal menyimpan data.', 'error');
                }
            },
            error: function (xhr) {
                $btn.prop('disabled', false).removeClass('opacity-75');
                var msg = 'Gagal menyimpan rekod.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var firstErrKey = Object.keys(xhr.responseJSON.errors)[0];
                    msg = xhr.responseJSON.errors[firstErrKey][0];
                }
                showToast(msg, 'error');
            }
        });
    });

    // Delete Staff Row
    $('#tbl-kakitangan-body').on('click', '.btn-hapus-row', function () {
        var $tr = $(this).closest('tr');
        var staffUuid = $tr.data('uuid');
        if (!staffUuid) return;

        if (!confirm('Adakah anda pasti untuk memadam rekod kakitangan ini?')) return;

        $.ajax({
            url: '/kakitangan-teknikal/' + staffUuid,
            type: 'POST',
            data: {
                _token: CSRF_TOKEN,
                _method: 'DELETE'
            },
            success: function (res) {
                if (res.success) {
                    $tr.fadeOut(300, function() {
                        $(this).remove();
                        reNumber();
                        checkEmptyState();
                    });
                    showToast(res.message, 'success');
                } else {
                    showToast(res.message || 'Gagal memadam rekod.', 'error');
                }
            },
            error: function (xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal memadam rekod.';
                showToast(msg, 'error');
            }
        });
    });

    // Helpers to re-number and render rows
    function reNumber() {
        $('#tbl-kakitangan-body .kakitangan-row').each(function (i) {
            $(this).find('.row-bil').text(i + 1);
        });
    }

    function checkEmptyState() {
        if ($('#tbl-kakitangan-body .kakitangan-row').length === 0) {
            if ($('#tr-empty-state').length === 0) {
                $('#tbl-kakitangan-body').append('<tr id="tr-empty-state"><td colspan="6" class="text-center text-muted py-4 small">Tiada rekod kakitangan teknikal dimasukkan lagi. Klik "Tambah Kakitangan" di atas untuk menambah.</td></tr>');
            }
        }
    }

    function renderOrUpdateRow(item) {
        $('#tr-empty-state').remove();
        var $existingTr = $('#tbl-kakitangan-body tr[data-uuid="' + item.uuid + '"]');

        var badgeClass = item.kategori === 'Kategori A' ? 'bg-success' : (item.kategori === 'Kategori B' ? 'bg-primary' : 'bg-warning text-dark');
        var badgeHtml = '<span class="badge rounded-pill ' + badgeClass + ' px-2.5 py-1">' + item.kategori + '</span>';

        var EDIT_BTN =
            '<button type="button" class="row-action-btn btn-edit-row" title="Kemaskini">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>' +
            '</button>';
        var DELETE_BTN =
            '<button type="button" class="row-action-btn btn-hapus-row" title="Buang">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path></svg>' +
            '</button>';

        if ($existingTr.length > 0) {
            $existingTr.data('item', item);
            $existingTr.find('.cell-nama').text(item.nama_pegawai);
            $existingTr.find('.cell-kategori').html(badgeHtml);
            $existingTr.find('.cell-pendidikan').text(item.tahap_pendidikan);
            $existingTr.find('.cell-pengalaman').text(item.jumlah_pengalaman + ' Tahun');
        } else {
            var bil = $('#tbl-kakitangan-body .kakitangan-row').length + 1;
            var $newTr = $('<tr class="kakitangan-row" data-uuid="' + item.uuid + '">' +
                '<td class="text-center row-bil fw-semibold text-muted" style="font-size:0.8rem;">' + bil + '</td>' +
                '<td class="fw-medium text-dark cell-nama">' + $('<div/>').text(item.nama_pegawai).html() + '</td>' +
                '<td class="cell-kategori">' + badgeHtml + '</td>' +
                '<td class="text-muted cell-pendidikan">' + $('<div/>').text(item.tahap_pendidikan).html() + '</td>' +
                '<td class="text-muted cell-pengalaman">' + item.jumlah_pengalaman + ' Tahun</td>' +
                '<td class="text-center">' +
                    '<div class="d-inline-flex align-items-center gap-1">' + EDIT_BTN + DELETE_BTN + '</div>' +
                '</td>' +
            '</tr>');
            $newTr.data('item', item);
            $('#tbl-kakitangan-body').append($newTr);
            reNumber();
        }
    }

});
</script>
@endsection
