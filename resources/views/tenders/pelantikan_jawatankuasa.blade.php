@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/tender-show.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <style>
        /* Page overlay */
        #pageOverlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 9999;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 14px;
        }

        #pageOverlay.active { display: flex; }

        #pageOverlay .overlay-spinner {
            width: 48px;
            height: 48px;
            border: 5px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        #pageOverlay .overlay-text {
            color: #fff;
            font-size: 15px;
            font-weight: 600;
        }

        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
@endsection

@section('content')
    @php
        $availableTabs = ['spec', 'open', 'tech', 'fin'];
        $draftTenderUuid = optional($tender)->uuid ?? request('tender');
        $supportedDraftJenisData = $supportedDraftJenis ?? ['spec', 'open', 'tech', 'fin'];
        $localIcUsersData = $icUsers ?? [];
        $tabLabels = [
            'spec'  => 'Jawatankuasa Spesifikasi',
            'open'  => 'Jawatankuasa Pembuka',
            'tech'  => 'Jawatankuasa Penilaian Teknikal',
            'fin'   => 'Jawatankuasa Penilaian Kewangan',
        ];
    @endphp

    <div id="laporanArea">
        <div id="pageDetail">

            {{-- HEADER CARD --}}
            <div class="tender-header-card mb-4">
                <div class="tender-page-header">

                    <div class="row g-3 pb-3">

                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="d-flex flex-column gap-1">
                                <span class="text-muted fw-semibold text-uppercase"
                                    style="font-size:0.67rem; letter-spacing:0.5px;">No. Tender</span>
                                <span class="fw-semibold text-dark" style="font-size:0.88rem;">
                                    {{ $tender->ref_number ?? request('tender_id', '-') }}
                                </span>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="d-flex flex-column gap-1">
                                <span class="text-muted fw-semibold text-uppercase"
                                    style="font-size:0.67rem; letter-spacing:0.5px;">PTJ</span>
                                <span class="fw-semibold text-dark" style="font-size:0.88rem;">
                                    {{ optional(optional($tender)->tenderer)->name ?? '-' }}
                                </span>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="d-flex flex-column gap-1">
                                <span class="text-muted fw-semibold text-uppercase"
                                    style="font-size:0.67rem; letter-spacing:0.5px;">Status</span>
                                <div>
                                    <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill fw-semibold"
                                        style="background:#fef3c7; color:#b45309; font-size:0.78rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                        {{ $tender->status ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Tab Navigation --}}
                <div class="tender-top-tabs">
                    <ul class="nav nav-tabs" id="committeeMainTabs" role="tablist">
                        @foreach ($availableTabs as $index => $tab)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                    id="tab-{{ $tab }}-btn"
                                    data-bs-toggle="tab"
                                    data-bs-target="#tab-{{ $tab }}"
                                    data-tab="{{ $tab }}"
                                    type="button" role="tab">
                                    {{ $tabLabels[$tab] ?? $tab }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>

            {{-- TAB CONTENT --}}
            <div class="tab-content" id="committeeContent">

                @foreach ($availableTabs as $index => $tab)
                    <div id="tab-{{ $tab }}"
                        class="tab-pane fade committee-pane {{ $index === 0 ? 'show active' : '' }}"
                        data-jenis="{{ $tab }}"
                        role="tabpanel">

                        <!-- AHLI JAWATANKUASA CARD -->
                        <div class="content-card mb-4 p-0">
                            <div class="content-card-header p-4 pb-3 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="content-card-icon" style="width: 38px; height: 38px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="content-card-title mb-0" style="font-size: 1rem;">Ahli Jawatankuasa</h3>
                                        <p class="text-muted mb-0" style="font-size: 0.78rem;">{{ $tabLabels[$tab] ?? $tab }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="content-card-body p-4">

                                <!-- Table toolbar -->
                                <div class="d-flex justify-content-end gap-2 mb-3">
                                    <button type="button" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 btn-tambah">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        Tambah
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1 btn-hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6l-1 14H6L5 6"></path>
                                            <path d="M10 11v6"></path>
                                            <path d="M14 11v6"></path>
                                            <path d="M9 6V4h6v2"></path>
                                        </svg>
                                        Hapus
                                    </button>
                                </div>

                                <!-- Table -->
                                <div class="table-responsive">
                                    <table class="table table-modern align-middle mb-0 js-table" style="min-width: 1100px;">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 44px;">
                                                    <input type="checkbox" class="check-all form-check-input">
                                                </th>
                                                <th style="width: 200px; white-space: nowrap;">No IC</th>
                                                <th style="width: 180px; white-space: nowrap;">Nama</th>
                                                <th style="width: 180px; white-space: nowrap;">Jawatan</th>
                                                <th style="width: 200px; white-space: nowrap;">Email</th>
                                                <th style="width: 100px; white-space: nowrap;">Gred</th>
                                                <th style="width: 100px; white-space: nowrap;">P&amp;P</th>
                                                <th style="width: 160px; white-space: nowrap;">Peranan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="committee-tbody">
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                        <!-- CATATAN & DOKUMEN CARD -->
                        <div class="content-card mb-4 p-0">
                            <div class="content-card-header p-4 pb-3 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="content-card-icon" style="width: 38px; height: 38px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="content-card-title mb-0" style="font-size: 1rem;">Catatan &amp; Dokumen Sokongan</h3>
                                        <p class="text-muted mb-0" style="font-size: 0.78rem;">Rekod catatan mesyuarat dan muat naik dokumen berkaitan</p>
                                    </div>
                                </div>
                            </div>
                            <div class="content-card-body p-0">
                                <div class="row g-0">

                                    <!-- Catatan -->
                                    <div class="col-12 col-md-6 p-4" style="border-right: 1px solid #f1f5f9;">
                                        <p class="fw-semibold text-uppercase mb-3"
                                            style="font-size: 0.7rem; letter-spacing: 0.6px; color: #64748b;">Catatan</p>
                                        <textarea class="form-control committee-catatan" rows="5"
                                            placeholder="Masukkan catatan mesyuarat...">{{ $committeeDrafts[$tab]['catatan'] ?? '' }}</textarea>
                                    </div>

                                    <!-- Dokumen Sokongan -->
                                    <div class="col-12 col-md-6 p-4">
                                        <p class="fw-semibold text-uppercase mb-3"
                                            style="font-size: 0.7rem; letter-spacing: 0.6px; color: #64748b;">Dokumen Sokongan</p>

                                        <input type="file" class="d-none committee-dokumen-input"
                                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">

                                        <div class="rounded-2 p-4 text-center btn-upload-dokumen"
                                            style="border: 2px dashed #cbd5e1; cursor: pointer; transition: border-color 0.2s, background 0.2s;"
                                            onmouseover="this.style.borderColor='#94a3b8'; this.style.background='#f8fafc';"
                                            onmouseout="this.style.borderColor='#cbd5e1'; this.style.background='';">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" class="mb-2 d-block mx-auto">
                                                <polyline points="16 16 12 12 8 16"></polyline>
                                                <line x1="12" y1="12" x2="12" y2="21"></line>
                                                <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path>
                                            </svg>
                                            <p class="mb-1 fw-semibold text-dark" style="font-size: 0.82rem;">Klik untuk muat naik dokumen</p>
                                            <p class="mb-0 text-muted" style="font-size: 0.72rem;">PDF, Word, Imej — saiz maksimum 10MB</p>
                                        </div>

                                        <div class="mt-2 d-flex align-items-center gap-2 px-3 py-2 rounded-2 committee-file-name-wrapper"
                                            style="background:#f0fdf4; border:1px solid #bbf7d0; {{ ($committeeDrafts[$tab]['dokumen_sokongan_nama'] ?? '') ? '' : 'display:none!important' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                                                fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round"
                                                stroke-linejoin="round" style="flex-shrink:0;">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>
                                            <span class="small fw-semibold committee-file-name" style="color:#15803d;">{{ $committeeDrafts[$tab]['dokumen_sokongan_nama'] ?? '' }}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- MAIN ACTION BUTTONS -->
                        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                            <div></div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn-form btn-form-secondary btn-simpan">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                        <polyline points="7 3 7 8 15 8"></polyline>
                                    </svg>
                                    Simpan [Draf]
                                </button>
                                <button type="button" class="btn-form btn-form-primary" onclick="printLaporan()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                        <rect x="6" y="14" width="12" height="8"></rect>
                                    </svg>
                                    Laporan
                                </button>
                                <button type="button" class="btn-form btn-form-success btn-hantar">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <line x1="22" y1="2" x2="11" y2="13"></line>
                                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                    </svg>
                                    Hantar Pemakluman
                                </button>
                            </div>
                        </div>

                    </div>
                @endforeach

            </div>

            <!-- ===================== SUCCESS POPUP ====================== -->
            <div id="successPopup" class="modal fade" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content text-center p-4">
                        <div class="mb-3">
                            <svg width="60" height="60" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" fill="#E6F7F3" />
                                <path d="M10 14.2l-2.2-2.2-1.4 1.4L10 17 18 9l-1.4-1.4z" fill="#19c1a7" />
                            </svg>
                        </div>
                        <h6 class="fw-bold mb-3">Maklumat telah berjaya disimpan</h6>
                        <button type="button" class="btn btn-primary px-4" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- PAGE BLOCK OVERLAY -->
    <div id="pageOverlay">
        <div class="overlay-spinner"></div>
        <div class="overlay-text" id="overlayText">Menghantar pemakluman...</div>
    </div>
@endsection

@section('scripts')
    <script>
        const initialCommitteeDrafts = @json($committeeDrafts ?? []);
        const tenderUuid = @json($draftTenderUuid);
        const saveDraftUrl = @json(route('jawatankuasa.store'));
        const hantarPemaklumanUrl = @json(route('jawatankuasa.hantarPemakluman'));
        const supportedDraftJenis = @json($supportedDraftJenisData);
        const localIcUsers = @json($localIcUsersData);

        /* =============================
           LOCAL TABLE FUNCTIONS
        ============================= */

        function initSelectize(el) {
            const selectizeInstance = $(el).selectize({
                valueField: 'id',
                labelField: 'ic_number',
                searchField: ['ic_number', 'name', 'email'],
                options: localIcUsers,
                maxItems: 1,
                create: false,
                placeholder: 'Taip No IC...',
                dropdownParent: 'body',
                render: {
                    option: function(item, escape) {
                        return '<div>' +
                            '<strong>' + escape(item.ic_number || '-') + '</strong>' +
                            '<br><small class="text-muted">' + escape(item.name || '-') + ' | ' + escape(item.email || '-') + ' | ' + escape(item.jawatan || '-') + '</small>' +
                            '</div>';
                    },
                    item: function(item, escape) {
                        return '<div>' + escape(item.ic_number || '-') + '</div>';
                    }
                },
                onChange: function(value) {
                    let tr = $(this.$wrapper).closest('tr');
                    if (!value) {
                        tr.find('td:eq(2) input').val('-');
                        tr.find('td:eq(3) input').val('-');
                        tr.find('td:eq(4) input').val('-');
                        tr.find('td:eq(5) input').val('-');
                        return;
                    }
                    let item = this.options[value];
                    if (item) {
                        tr.find('td:eq(2) input').val(item.name || '-');
                        tr.find('td:eq(3) input').val(item.jawatan || '-');
                        tr.find('td:eq(4) input').val(item.email || '-');
                        tr.find('td:eq(5) input').val(item.gred || 'G41');
                    } else {
                        tr.find('td:eq(2) input').val('-');
                        tr.find('td:eq(3) input').val('-');
                        tr.find('td:eq(4) input').val('-');
                        tr.find('td:eq(5) input').val('-');
                    }
                }
            });

            return selectizeInstance[0].selectize;
        }

        function createRow(tbody, rowData = null) {
            let tr = document.createElement('tr');
            const pAndP = (rowData && rowData.p_p) ? String(rowData.p_p) : '1';
            const peranan = (rowData && rowData.peranan) ? String(rowData.peranan) : '1';
            tr.innerHTML = `
                <td class="text-center"><input type="checkbox" class="row-check form-check-input"></td>
                <td><input type="text" class="ic-search-select" placeholder="Taip No IC..."></td>
                <td><input type="text" class="form-control form-control-sm bg-light member-name" placeholder="-" readonly></td>
                <td><input type="text" class="form-control form-control-sm bg-light member-jawatan" placeholder="-" readonly></td>
                <td><input type="email" class="form-control form-control-sm bg-light member-email" placeholder="-" readonly></td>
                <td><input type="text" class="form-control form-control-sm bg-light member-gred" placeholder="-" readonly></td>
                <td>
                    <select class="form-select form-select-sm pp-select">
                        <option value="1" ${pAndP === '1' ? 'selected' : ''}>Ya</option>
                        <option value="0" ${pAndP === '0' ? 'selected' : ''}>Tidak</option>
                    </select>
                </td>
                <td>
                    <select class="form-select form-select-sm peranan-select">
                        <option value="1" ${peranan === '1' ? 'selected' : ''}>Pengerusi</option>
                        <option value="2" ${peranan === '2' ? 'selected' : ''}>Setiausaha</option>
                        <option value="3" ${peranan === '3' ? 'selected' : ''}>Ahli</option>
                    </select>
                </td>
            `;
            tbody.appendChild(tr);
            const input = tr.querySelector('.ic-search-select');
            const selectize = initSelectize(input);

            if (rowData && rowData.user_id) {
                if (!selectize.options[String(rowData.user_id)]) {
                    selectize.addOption({
                        id: String(rowData.user_id),
                        ic_number: rowData.ic_number || '-',
                        name: rowData.name || '-',
                        email: rowData.email || '-',
                        jawatan: rowData.jawatan || '-',
                        gred: rowData.gred || '-',
                    });
                }
                selectize.setValue(String(rowData.user_id), true);
                tr.querySelector('.member-name').value = rowData.name || '-';
                tr.querySelector('.member-jawatan').value = rowData.jawatan || '-';
                tr.querySelector('.member-email').value = rowData.email || '-';
                tr.querySelector('.member-gred').value = rowData.gred || '-';
            }
        }

        function seedCommitteeRows() {
            document.querySelectorAll('.committee-pane').forEach(function(tabPane) {
                const jenis = tabPane.dataset.jenis;
                const draftData = initialCommitteeDrafts[jenis] || {};
                const tbody = tabPane.querySelector('.committee-tbody');
                tbody.innerHTML = '';

                if (Array.isArray(draftData.rows) && draftData.rows.length > 0) {
                    draftData.rows.forEach(function(row) {
                        createRow(tbody, row);
                    });
                } else {
                    createRow(tbody);
                }

                const fileNameEl = tabPane.querySelector('.committee-file-name');
                if (fileNameEl && draftData.dokumen_sokongan_nama) {
                    fileNameEl.textContent = draftData.dokumen_sokongan_nama;
                }

                if (!supportedDraftJenis.includes(jenis)) {
                    const saveButton = tabPane.querySelector('.btn-simpan');
                    if (saveButton) {
                        saveButton.disabled = true;
                        saveButton.title = 'Tab ini belum disokong untuk simpan draf.';
                    }
                }
            });
        }

        function collectRows(tabPane) {
            const rows = [];
            tabPane.querySelectorAll('.committee-tbody tr').forEach(function(tr) {
                const selectInput = tr.querySelector('.ic-search-select');
                const selectize = selectInput ? selectInput.selectize : null;
                const userId = selectize ? selectize.getValue() : '';

                if (!userId) {
                    return;
                }

                rows.push({
                    user_id: userId,
                    p_p: (tr.querySelector('.pp-select') && tr.querySelector('.pp-select').value) || '1',
                    peranan: (tr.querySelector('.peranan-select') && tr.querySelector('.peranan-select').value) || '3',
                });
            });

            return rows;
        }

        function buildDraftFormDataAll() {
            const formData = new FormData();

            formData.append('_token', $('meta[name=_token]').attr('content'));
            formData.append('tender_uuid', tenderUuid || '');

            document.querySelectorAll('.committee-pane').forEach(function(tabPane) {
                const jenis = tabPane.dataset.jenis;
                if (!supportedDraftJenis.includes(jenis)) {
                    return;
                }

                const rows = collectRows(tabPane);
                const fileInput = tabPane.querySelector('.committee-dokumen-input');
                const catatanValue = (tabPane.querySelector('.committee-catatan') && tabPane.querySelector('.committee-catatan').value) || '';

                formData.append(`tabs[${jenis}][catatan]`, catatanValue);

                rows.forEach((row, index) => {
                    formData.append(`tabs[${jenis}][rows][${index}][user_id]`, row.user_id);
                    formData.append(`tabs[${jenis}][rows][${index}][p_p]`, row.p_p);
                    formData.append(`tabs[${jenis}][rows][${index}][peranan]`, row.peranan);
                });

                if (fileInput && fileInput.files && fileInput.files.length > 0) {
                    formData.append(`tabs[${jenis}][dokumen_sokongan]`, fileInput.files[0]);
                }
            });

            return formData;
        }

        function saveAllDrafts(triggerButton, successModal) {
            if (!tenderUuid) {
                alert('Tender tidak ditemui. Sila buka semula halaman menggunakan pautan tender.');
                return;
            }

            const formData = buildDraftFormDataAll();
            const saveButtons = Array.from(document.querySelectorAll('.btn-simpan'));
            const buttonStates = saveButtons.map(function(btn) {
                return {
                    button: btn,
                    text: btn.textContent,
                    disabled: btn.disabled,
                };
            });

            saveButtons.forEach(function(btn) {
                btn.disabled = true;
                btn.textContent = 'Menyimpan...';
            });

            $.ajax({
                url: saveDraftUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function() {
                    successModal.show();
                },
                error: function(xhr) {
                    const message = (xhr && xhr.responseJSON && xhr.responseJSON.message)
                        ? xhr.responseJSON.message
                        : 'Simpan draf gagal. Sila cuba semula.';
                    alert(message);
                },
                complete: function() {
                    buttonStates.forEach(function(state) {
                        state.button.disabled = state.disabled;
                        state.button.textContent = state.text;
                    });
                }
            });
        }

        // ADD ROW
        document.querySelectorAll('.btn-tambah').forEach(btn => {
            btn.addEventListener('click', function() {
                let tabPane = this.closest('.committee-pane');
                let tbody = tabPane.querySelector('.committee-tbody');
                createRow(tbody);
            });
        });

        // DELETE SELECTED ROWS
        document.querySelectorAll('.btn-hapus').forEach(btn => {
            btn.addEventListener('click', function() {
                let tabPane = this.closest('.committee-pane');
                let table = tabPane.querySelector('table');
                table.querySelectorAll('.row-check:checked').forEach(cb => {
                    cb.closest('tr').remove();
                });

                if (tabPane.querySelectorAll('.committee-tbody tr').length === 0) {
                    createRow(tabPane.querySelector('.committee-tbody'));
                }
            });
        });

        // CHECK/UNCHECK ALL ROWS
        document.querySelectorAll('.check-all').forEach(checkAll => {
            checkAll.addEventListener('change', function() {
                let table = this.closest('table');
                let rows = table.querySelectorAll('.row-check');
                rows.forEach(cb => {
                    cb.checked = this.checked;
                });
            });
        });

        // SAVE POPUP
        document.addEventListener('DOMContentLoaded', function() {

            const successModal = new bootstrap.Modal(
                document.getElementById('successPopup')
            );
            seedCommitteeRows();

            // SIMPAN
            document.querySelectorAll('.btn-simpan').forEach(btn => {
                btn.addEventListener('click', function() {
                    saveAllDrafts(this, successModal);
                });
            });

            // HANTAR PEMAKLUMAN
            document.querySelectorAll('.btn-hantar').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (!tenderUuid) {
                        alert('Tender tidak ditemui. Sila buka semula halaman menggunakan pautan tender.');
                        return;
                    }

                    // Validate all 4 tabs have Pengerusi(1), Setiausaha(2), Ahli(3)
                    const requiredTabs = ['spec', 'open', 'tech', 'fin'];
                    const requiredPeranan = ['1', '2', '3'];

                    for (let i = 0; i < requiredTabs.length; i++) {
                        const tab = requiredTabs[i];
                        const tabPane = document.getElementById('tab-' + tab);
                        if (!tabPane) {
                            alert('Jawatan Kuasa tidak Mencukupi');
                            return;
                        }

                        const rows = tabPane.querySelectorAll('.committee-tbody tr');
                        const existingPeranan = [];

                        rows.forEach(function(tr) {
                            const selectInput = tr.querySelector('.ic-search-select');
                            const selectize = selectInput ? selectInput.selectize : null;
                            const userId = selectize ? selectize.getValue() : '';
                            if (!userId) return;

                            const perananSelect = tr.querySelector('.peranan-select');
                            if (perananSelect) {
                                existingPeranan.push(perananSelect.value);
                            }
                        });

                        for (let j = 0; j < requiredPeranan.length; j++) {
                            if (existingPeranan.indexOf(requiredPeranan[j]) === -1) {
                                alert('Jawatan Kuasa tidak Mencukupi');
                                return;
                            }
                        }
                    }

                    if (!confirm('Adakah anda pasti untuk menghantar pemakluman kepada semua ahli jawatankuasa?')) {
                        return;
                    }

                    const overlay = document.getElementById('pageOverlay');
                    const overlayText = document.getElementById('overlayText');

                    overlay.classList.add('active');
                    overlayText.textContent = 'Menyimpan draf...';

                    const formData = buildDraftFormDataAll();

                    $.ajax({
                        url: saveDraftUrl,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function() {
                            overlayText.textContent = 'Menghantar pemakluman...';

                            $.ajax({
                                url: hantarPemaklumanUrl,
                                type: 'POST',
                                timeout: 300000,
                                data: {
                                    _token: $('meta[name=_token]').attr('content'),
                                    tender_uuid: tenderUuid,
                                },
                                dataType: 'json',
                                success: function(res) {
                                    overlay.classList.remove('active');
                                    alert(res.message || 'Pemakluman berjaya dihantar.');
                                    window.location.href = '/tender';
                                },
                                error: function(xhr) {
                                    overlay.classList.remove('active');
                                    const message = (xhr && xhr.responseJSON && xhr.responseJSON.message)
                                        ? xhr.responseJSON.message
                                        : 'Hantar pemakluman gagal. Sila cuba semula.';
                                    alert(message);
                                }
                            });
                        },
                        error: function(xhr) {
                            overlay.classList.remove('active');
                            const message = (xhr && xhr.responseJSON && xhr.responseJSON.message)
                                ? xhr.responseJSON.message
                                : 'Simpan draf gagal. Sila cuba semula.';
                            alert(message);
                        }
                    });
                });
            });

            // FILE UPLOAD BUTTON + FILE NAME
            document.querySelectorAll('.btn-upload-dokumen').forEach(btn => {
                btn.addEventListener('click', function() {
                    const tabPane = this.closest('.committee-pane');
                    const fileInput = tabPane.querySelector('.committee-dokumen-input');
                    if (fileInput) {
                        fileInput.click();
                    }
                });
            });

            document.querySelectorAll('.committee-dokumen-input').forEach(input => {
                input.addEventListener('change', function() {
                    const tabPane = this.closest('.committee-pane');
                    const fileNameEl = tabPane.querySelector('.committee-file-name');
                    const wrapperEl = tabPane.querySelector('.committee-file-name-wrapper');

                    if (!fileNameEl) return;

                    if (this.files.length > 0) {
                        fileNameEl.textContent = this.files[0].name;
                        if (wrapperEl) wrapperEl.style.removeProperty('display');
                    } else {
                        fileNameEl.textContent = '';
                        if (wrapperEl) wrapperEl.style.display = 'none';
                    }
                });
            });

        });

        // GENERATE REPORT PDF
        function printLaporan() {
            if (!tenderUuid) {
                alert('Tender tidak ditemui. Sila buka semula halaman menggunakan pautan tender.');
                return;
            }
            const url = @json(route('jawatankuasa.laporan')) + '?tender=' + encodeURIComponent(tenderUuid);
            window.open(url, '_blank');
        }
    </script>
@endsection
