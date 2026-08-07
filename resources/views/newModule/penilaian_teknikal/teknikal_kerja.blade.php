@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/tender-show.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/modal-confirm.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/file-upload.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">

    <style>
        /* Lampiran: staged file (selected, awaiting confirmation/rename before adding) */
        .lampiran-staged-card {
            padding: 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }

        .lampiran-staged-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .lampiran-staged-size {
            display: block;
            font-size: 0.72rem;
            margin-top: 6px;
            /* Lines up under the input, past the ext badge + row gap. */
            margin-left: calc(30px + 12px);
        }

        .lampiran-staged-actions {
            display: flex;
            gap: 8px;
            flex-shrink: 0;
        }

        /* Neutral (not destructive) colour, sized to match .file-chip-remove. */
        .file-chip-rename {
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: none;
            background: #eff6ff;
            color: #1d4ed8;
            cursor: pointer;
            padding: 0;
            transition: background 0.15s, color 0.15s;
        }

        .file-chip-rename:hover {
            background: #1d4ed8;
            color: #fff;
        }

        .file-chip-name-input {
            font-size: 0.78rem;
            font-weight: 600;
            padding: 1px 4px;
            max-width: 160px;
        }
    </style>
@endsection

@section('content')

@php
    $tarikhSerahan = !empty($tender->submission_datetime) ? \Carbon\Carbon::parse($tender->submission_datetime) : null;
    $sahLakuTamat = $tarikhSerahan ? $tarikhSerahan->copy()->addDays(90)->format('d/m/Y') : '-';
    $ptj = optional($tender->tenderer)->name ?: '-';
@endphp

<!-- Tender header -->
<div class="tender-header-card mb-4">
    <div class="tender-page-header">
        <div class="tender-ref-label">
            <span class="tender-type-label">{{ App\Tender::$types[$tender->type] ?? 'Sebut Harga / Tender' }}</span>
            <span class="tender-ref-sep">&middot;</span>
            <span class="tender-ref-no">{{ $tender->no_tender ?: $tender->ref_number ?: 'Belum Dijana' }}</span>
        </div>
        <h2 class="tender-title-main mb-3">{{ $tender->name ?: '-' }}</h2>

        <div class="row g-3 pb-3">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="d-flex flex-column gap-1">
                    <span class="text-muted fw-semibold text-uppercase" style="font-size:0.67rem; letter-spacing:0.5px;">PTJ</span>
                    <span class="fw-semibold text-dark" style="font-size:0.88rem;">{{ $ptj }}</span>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="d-flex flex-column gap-1">
                    <span class="text-muted fw-semibold text-uppercase" style="font-size:0.67rem; letter-spacing:0.5px;">Tempoh Sah Laku Tawaran (Hari)</span>
                    <span class="fw-semibold text-dark" style="font-size:0.88rem;">90</span>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="d-flex flex-column gap-1">
                    <span class="text-muted fw-semibold text-uppercase" style="font-size:0.67rem; letter-spacing:0.5px;">Sah Laku Tawaran Tamat</span>
                    <span class="fw-semibold text-dark" style="font-size:0.88rem;">{{ $sahLakuTamat }}</span>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="d-flex flex-column gap-1">
                    <span class="text-muted fw-semibold text-uppercase" style="font-size:0.67rem; letter-spacing:0.5px;">Status</span>
                    <div>
                        <span class="badge-status badge-status-warning">Menunggu Penilaian Cadangan Teknikal</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-12 px-0">

    <!-- Borang Teknikal -->
    <div class="content-card p-0 mb-4">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width: 42px; height: 42px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="9" y="2" width="6" height="4" rx="1"></rect>
                        <path d="M9 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-3"></path>
                        <path d="m9 14 2 2 4-4"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1" style="font-size: 1.05rem; letter-spacing: -0.2px;">Borang Teknikal</h4>
                    <p class="text-muted mb-0" style="font-size: 0.78rem;">Nilai status Lulus/Tidak Lulus bagi setiap pembekal disenarai pendek berdasarkan cadangan teknikal.</p>
                </div>
            </div>
        </div>
        <div class="content-card-body p-4">
            <div class="table-responsive">
                <table class="table table-bordered table-slate align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th style="width: 10%;">Bil.</th>
                            <th style="width: 40%;">Rujukan Petender</th>
                            <th style="width: 20%;">Harga Asal Tender (RM)</th>
                            <th style="width: 20%;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($borangTeknikalRows as $index => $row)
                            <tr data-vendor-id="{{ $row['vendor_id'] }}">
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $row['rujukan'] ?? '-' }}</td>
                                <td class="text-end">{{ $row['harga'] !== null ? number_format($row['harga'], 2) : '-' }}</td>
                                <td class="text-center">
                                    <select class="form-select status-petender" name="status_petender_{{ $row['vendor_id'] }}" data-vendor-id="{{ $row['vendor_id'] }}" aria-label="Status" @disabled($readOnly ?? false)>
                                        <option value="" @selected(!$row['status'])>Sila Pilih</option>
                                        <option value="lulus" @selected($row['status'] === 'lulus')>Lulus</option>
                                        <option value="tidak_lulus" @selected($row['status'] === 'tidak_lulus')>Tidak Lulus</option>
                                    </select>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Tiada pembekal disenarai pendek untuk tender ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- LAMPIRAN PENILAIAN TEKNIKAL -->
    <div class="content-card p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width: 42px; height: 42px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1" style="font-size: 1.05rem; letter-spacing: -0.2px;">Lampiran Penilaian Teknikal (Jika Perlu)</h4>
                    <p class="text-muted mb-0" style="font-size: 0.78rem;">Muat naik dokumen sokongan tambahan bagi penilaian ini, jika ada.</p>
                </div>
            </div>
        </div>
        <div class="content-card-body p-4">
            {{-- Upload zone hidden after submission — existing files stay downloadable. --}}
            <label class="upload-zone w-100 @if ($readOnly ?? false) d-none @endif" id="lampiranUploadZone" for="lampiranFileInput">
                <div class="upload-zone-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="16 16 12 12 8 16"></polyline>
                        <line x1="12" y1="12" x2="12" y2="21"></line>
                        <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path>
                    </svg>
                </div>
                <span class="upload-zone-label">Klik atau seret fail ke sini untuk muat naik</span>
                <span class="upload-zone-sub">Sahkan / kemaskini nama dokumen sebelum menambah</span>
                <input type="file" class="d-none" id="lampiranFileInput" name="lampiran_fail" autocomplete="off">
            </label>

            <!-- Staged file: confirm/rename before adding to the list below. -->
            <div class="lampiran-staged d-none mt-3" id="lampiranStaged">
                <div class="lampiran-staged-card">
                    <label class="form-label small mb-2" for="lampiranNamaFail">Nama Dokumen</label>
                    <div class="lampiran-staged-row">
                        <span class="file-chip-ext" id="lampiranStagedExt"></span>
                        <input type="text" class="form-control form-control-sm flex-grow-1" id="lampiranNamaFail" name="lampiran_nama_paparan" placeholder="Nama dokumen" autocomplete="off">
                        <div class="lampiran-staged-actions">
                            <button type="button" class="btn-form btn-form-primary" id="btnTambahDokumen">Simpan</button>
                            <button type="button" class="btn-form btn-form-secondary" id="btnBatalLampiran">Batal</button>
                        </div>
                    </div>
                    <span class="lampiran-staged-size text-muted" id="lampiranStagedSize"></span>
                </div>
            </div>

            <div class="mt-3">
                <p class="small fw-semibold text-uppercase text-muted mb-2 d-none" id="lampiranListLabel" style="letter-spacing:0.4px; font-size:0.7rem;">Dokumen Ditambah</p>
                <div class="file-chip-list" id="lampiranList"></div>
                <p class="small text-muted mb-0" id="lampiranEmptyState">Tiada dokumen ditambah lagi.</p>
            </div>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="{{ route('penilaianTeknikal') }}" class="btn-form btn-form-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali
        </a>
        @if ($readOnly ?? false)
            <a href="{{ route('penilaianTeknikal') }}" class="btn-form btn-form-primary">Kembali ke Senarai</a>
        @else
            <button type="button" class="btn-form btn-form-primary" id="btnHantarTeknikalKerja">Hantar</button>
        @endif
    </div>

</div>

@endsection

@push('modals')

<!-- Confirms before submitting — eliminates failing vendors, irreversible. -->
<div class="modal fade" id="modalKonfirmasiHantarTeknikalKerja" tabindex="-1" aria-labelledby="modalKonfirmasiHantarTeknikalKerjaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-confirm">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 pt-4 pb-2 px-4">
                <div class="modal-confirm-center">
                    <div class="modal-confirm-icon modal-confirm-icon--warning">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                            <line x1="12" y1="9" x2="12" y2="13"></line>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                    </div>
                    <h5 class="modal-confirm-title" id="modalKonfirmasiHantarTeknikalKerjaLabel">Sahkan Penghantaran</h5>
                    <p class="modal-confirm-desc">
                        Pembekal berstatus Tidak Lulus akan disingkirkan, dan tender akan diteruskan ke Penilaian Kewangan. Tindakan ini tidak boleh dibuat asal.
                    </p>
                </div>
            </div>
            <div class="modal-footer border-top-0 px-4 pb-4 pt-2 d-flex justify-content-center gap-2">
                <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-form btn-form-primary" id="btnKonfirmasiHantarTeknikalKerja">Ya, Hantar</button>
            </div>
        </div>
    </div>
</div>

@endpush

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const TENDER_UUID = '{{ $tender->uuid }}';
        const UPLOAD_LAMPIRAN_URL = '{{ route('penilaianTeknikalKerja.lampiran.upload', $tender->id) }}';
        const RENAME_LAMPIRAN_URL_TEMPLATE = '{{ route('penilaianTeknikalKerja.lampiran.rename', ['lampiran' => '__UUID__']) }}';
        const DELETE_LAMPIRAN_URL_TEMPLATE = '{{ route('penilaianTeknikalKerja.lampiran.delete', ['lampiran' => '__UUID__']) }}';
        const HANTAR_TEKNIKAL_KERJA_URL = '{{ route('penilaianTeknikalKerja.hantar') }}';
        const PENILAIAN_TEKNIKAL_LIST_URL = '{{ route('penilaianTeknikal') }}';
        const EXISTING_LAMPIRAN = @json($lampiranList);
        // Server also rejects a second submit/upload — this just keeps the UI from offering
        // an action that would fail anyway.
        const READ_ONLY_KERJA = @json($readOnly ?? false);

        function setButtonBusy(button, busyLabel) {
            if (!button || button.disabled) return;
            button.dataset.originalText = button.textContent;
            button.disabled = true;
            button.textContent = busyLabel;
        }

        function clearButtonBusy(button) {
            if (!button) return;
            button.disabled = false;
            if (button.dataset.originalText) {
                button.textContent = button.dataset.originalText;
                delete button.dataset.originalText;
            }
        }

        const lampiranZone = document.getElementById('lampiranUploadZone');
        const lampiranInput = document.getElementById('lampiranFileInput');
        const lampiranStaged = document.getElementById('lampiranStaged');
        const lampiranStagedExt = document.getElementById('lampiranStagedExt');
        const lampiranStagedSize = document.getElementById('lampiranStagedSize');
        const lampiranNama = document.getElementById('lampiranNamaFail');
        const btnTambahDokumen = document.getElementById('btnTambahDokumen');
        const btnBatalLampiran = document.getElementById('btnBatalLampiran');
        const lampiranList = document.getElementById('lampiranList');
        const lampiranListLabel = document.getElementById('lampiranListLabel');
        const lampiranEmptyState = document.getElementById('lampiranEmptyState');

        let lampiranStagedFile = null;

        function lampiranFormatBytes(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(1) + ' MB';
        }

        function lampiranExt(filename) {
            const parts = filename.split('.');
            return parts.length > 1 ? parts.pop().toLowerCase() : '';
        }

        function showLampiranStaged(file) {
            lampiranStagedFile = file;
            const ext = lampiranExt(file.name);
            lampiranStagedExt.textContent = ext;
            lampiranStagedExt.className = 'file-chip-ext' + (ext ? ' ext-' + ext : '');
            lampiranStagedSize.textContent = lampiranFormatBytes(file.size);
            lampiranNama.value = file.name;
            lampiranStaged.classList.remove('d-none');
            // Hides the dropzone so a second drag/drop can't silently swap the staged file.
            lampiranZone.classList.add('d-none');
        }

        function clearLampiranStaged() {
            lampiranStagedFile = null;
            lampiranStaged.classList.add('d-none');
            lampiranNama.value = '';
            lampiranInput.value = '';
            lampiranZone.classList.remove('d-none');
        }

        function updateLampiranEmptyState() {
            const hasItems = lampiranList.children.length > 0;
            lampiranEmptyState.classList.toggle('d-none', hasItems);
            lampiranListLabel.classList.toggle('d-none', !hasItems);
        }

        if (lampiranInput) {
            lampiranInput.addEventListener('change', () => {
                const f = lampiranInput.files && lampiranInput.files[0];
                if (f) showLampiranStaged(f);
            });
        }

        if (lampiranZone) {
            lampiranZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                lampiranZone.classList.add('dragover');
            });
            lampiranZone.addEventListener('dragleave', () => {
                lampiranZone.classList.remove('dragover');
            });
            lampiranZone.addEventListener('drop', (e) => {
                e.preventDefault();
                lampiranZone.classList.remove('dragover');
                const f = e.dataTransfer.files && e.dataTransfer.files[0];
                if (f) showLampiranStaged(f);
            });
        }

        if (btnBatalLampiran) {
            btnBatalLampiran.addEventListener('click', clearLampiranStaged);
        }

        // Shared by newly-uploaded files and existing ones loaded at page load.
        function buildRealLampiranChip(file) {
            const chip = document.createElement('div');
            chip.className = 'file-chip';
            chip.setAttribute('data-uuid', file.uuid);

            const ext = lampiranExt(file.name);
            const extSpan = document.createElement('span');
            extSpan.className = 'file-chip-ext' + (ext ? ' ext-' + ext : '');
            extSpan.textContent = ext;

            const body = document.createElement('div');
            body.className = 'file-chip-body';

            const nameLink = document.createElement('a');
            nameLink.className = 'file-chip-name';
            nameLink.href = file.url;
            nameLink.target = '_blank';
            nameLink.rel = 'noopener';
            nameLink.title = file.name;
            nameLink.textContent = file.name;

            const sizeSpan = document.createElement('span');
            sizeSpan.className = 'file-chip-size';
            sizeSpan.textContent = typeof file.size === 'number' ? lampiranFormatBytes(file.size) : '';

            body.appendChild(nameLink);
            body.appendChild(sizeSpan);

            const renameBtn = document.createElement('button');
            renameBtn.type = 'button';
            renameBtn.className = 'file-chip-rename';
            renameBtn.title = 'Tukar nama';
            renameBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path></svg>';
            renameBtn.addEventListener('click', () => startRenameLampiranChip(nameLink, file));

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'file-chip-remove';
            removeBtn.title = 'Buang fail';
            removeBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
            removeBtn.addEventListener('click', () => {
                if (!window.confirm('Buang lampiran "' + file.name + '"?')) return;

                $.ajax({
                    url: DELETE_LAMPIRAN_URL_TEMPLATE.replace('__UUID__', file.uuid),
                    method: 'DELETE',
                    data: { _token: CSRF_TOKEN },
                }).done(() => {
                    chip.remove();
                    updateLampiranEmptyState();
                }).fail(() => {
                    showToast('error', 'Ralat semasa membuang lampiran.');
                });
            });

            chip.appendChild(extSpan);
            chip.appendChild(body);
            // Read-only: files stay downloadable but rename/remove buttons aren't attached.
            if (!READ_ONLY_KERJA) {
                chip.appendChild(renameBtn);
                chip.appendChild(removeBtn);
            }
            lampiranList.appendChild(chip);
            return chip;
        }

        // Swaps the name link for a text input — Enter/blur saves, Escape cancels.
        function startRenameLampiranChip(nameLink, file) {
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control form-control-sm file-chip-name-input';
            input.value = file.name;
            nameLink.replaceWith(input);
            input.focus();
            input.select();

            let settled = false;

            function commit() {
                if (settled) return;
                settled = true;

                const newName = (input.value || '').trim();
                if (!newName || newName === file.name) {
                    input.replaceWith(nameLink);
                    return;
                }

                $.ajax({
                    url: RENAME_LAMPIRAN_URL_TEMPLATE.replace('__UUID__', file.uuid),
                    method: 'POST',
                    data: { _token: CSRF_TOKEN, display_name: newName },
                }).done((res) => {
                    file.name = res.data.name;
                    nameLink.textContent = file.name;
                    nameLink.title = file.name;
                    input.replaceWith(nameLink);
                }).fail(() => {
                    showToast('error', 'Ralat semasa menukar nama lampiran.');
                    input.replaceWith(nameLink);
                });
            }

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') { e.preventDefault(); commit(); }
                if (e.key === 'Escape') { settled = true; input.replaceWith(nameLink); }
            });
            input.addEventListener('blur', commit);
        }

        if (btnTambahDokumen && lampiranList) {
            btnTambahDokumen.addEventListener('click', () => {
                if (!lampiranStagedFile) {
                    showToast('warning', 'Sila pilih fail terlebih dahulu.');
                    return;
                }

                const displayName = (lampiranNama.value || '').trim() || lampiranStagedFile.name;
                const formData = new FormData();
                formData.append('file', lampiranStagedFile);
                formData.append('display_name', displayName);
                formData.append('_token', CSRF_TOKEN);

                setButtonBusy(btnTambahDokumen, 'Memuat naik...');

                $.ajax({
                    url: UPLOAD_LAMPIRAN_URL,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                }).done((res) => {
                    buildRealLampiranChip(res.data);
                    updateLampiranEmptyState();
                    clearLampiranStaged();
                }).fail((xhr) => {
                    showToast('error', xhr.responseJSON?.message || 'Ralat semasa memuat naik lampiran.');
                }).always(() => {
                    clearButtonBusy(btnTambahDokumen);
                });
            });
        }

        EXISTING_LAMPIRAN.forEach(buildRealLampiranChip);
        updateLampiranEmptyState();

        const btnHantarTeknikalKerja = document.getElementById('btnHantarTeknikalKerja');
        const modalKonfirmasiHantar = document.getElementById('modalKonfirmasiHantarTeknikalKerja');
        const btnKonfirmasiHantar = document.getElementById('btnKonfirmasiHantarTeknikalKerja');

        if (btnHantarTeknikalKerja && modalKonfirmasiHantar) {
            btnHantarTeknikalKerja.addEventListener('click', () => {
                const selects = document.querySelectorAll('.status-petender');
                if (!selects.length) {
                    showToast('warning', 'Tiada pembekal untuk dinilai.');
                    return;
                }

                const belumLengkap = Array.from(selects).some((el) => !el.value);
                if (belumLengkap) {
                    showToast('warning', 'Sila pilih status (Lulus/Tidak Lulus) bagi setiap pembekal.');
                    return;
                }

                bootstrap.Modal.getOrCreateInstance(modalKonfirmasiHantar).show();
            });
        }

        if (btnKonfirmasiHantar) {
            btnKonfirmasiHantar.addEventListener('click', () => {
                const rows = Array.from(document.querySelectorAll('.status-petender')).map((el) => ({
                    vendor_id: el.getAttribute('data-vendor-id'),
                    status: el.value,
                }));

                setButtonBusy(btnKonfirmasiHantar, 'Menghantar...');

                $.ajax({
                    url: HANTAR_TEKNIKAL_KERJA_URL,
                    method: 'POST',
                    data: {
                        _token: CSRF_TOKEN,
                        tender: TENDER_UUID,
                        rows: rows,
                    },
                }).done((res) => {
                    window.location.href = PENILAIAN_TEKNIKAL_LIST_URL + '?toast=success&message=' + encodeURIComponent(res.message || 'Penilaian teknikal berjaya dihantar.');
                }).fail((xhr) => {
                    clearButtonBusy(btnKonfirmasiHantar);
                    bootstrap.Modal.getInstance(modalKonfirmasiHantar)?.hide();
                    showToast('error', xhr.responseJSON?.message || 'Ralat semasa menghantar penilaian teknikal.');
                });
            });
        }
    });
</script>
@endsection
