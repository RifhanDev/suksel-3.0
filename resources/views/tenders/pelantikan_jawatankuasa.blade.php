@extends('layouts.v3.master')

@section('styles')
    <style>
        /* =====================
            GENERAL LINKS
        ===================== */
        .tender-link {
            color: #3751FF;
            font-weight: 600;
            text-decoration: underline;
            cursor: pointer;
        }

        /* =====================
            COMMITTEE TABS
        ===================== */
        .committee-tabs {
            display: flex;
            background: #f1f3ff;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .committee-tab {
            flex: 1;
            border: 0;
            background: transparent;
            padding: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            color: #333;
        }

        .committee-tab:hover {
            background: #f5c2c7;
            /* light red hover */
        }

        /* ACTIVE TAB = RED */
        .committee-tab.active {
            background: #A4161A !important;
            color: #fff !important;
        }

        /* =====================
            BUTTON COLORS
        ===================== */

        /* PRIMARY BUTTON (TAPIS, SIMPAN) */
        .btn-primary {
            background-color: #A4161A !important;
            border-color: #A4161A !important;
        }

        .btn-primary:hover {
            background-color: #8F1215 !important;
            border-color: #8F1215 !important;
        }

        /* SUCCESS BUTTON */
        .btn-success {
            background-color: #198754;
            border-color: #198754;
        }

        /* DANGER BUTTON */
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        /* INFO BUTTON (LAPORAN) */
        .btn-info {
            background-color: #0dcaf0;
            border-color: #0dcaf0;
        }

        /* =====================
            TABLE STYLE
        ===================== */
        .table thead th {
            text-align: center;
            vertical-align: middle;
        }

        /* RED TABLE HEADER */
        .thead-red {
            background: #A4161A !important;
            color: #fff !important;
        }

        .table tbody td {
            vertical-align: middle;
        }

        /* =====================
            FORM ELEMENTS
        ===================== */
        input[type="checkbox"] {
            width: 16px;
            height: 16px;
        }

        .form-select,
        .form-control {
            padding: 6px 10px;
        }

        /* =====================
            PAGE OVERLAY
        ===================== */
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

        #pageOverlay.active {
            display: flex;
        }

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

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* =====================
            CATATAN BOX
        ===================== */
        .catatan-box {
            background: #f3f3f3;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
    </style>
@endsection

@section('content')
    @php
        $availableTabs = ['spec', 'open', 'tech', 'fin', 'harga'];
        $draftTenderUuid = optional($tender)->uuid ?? request('tender');
        $supportedDraftJenisData = $supportedDraftJenis ?? ['spec', 'open', 'tech', 'fin'];
        $localIcUsersData = $icUsers ?? [];
    @endphp

    <div id="laporanArea">
        <!-- ===================== PAGE DETAIL ====================== -->
        <div id="pageDetail">

            <div class="card p-4">

                <!-- Header -->
                <div class="row mb-3">
                    <div class="col-md-8">
                        <strong>No Tender:</strong> {{ $tender->ref_number ?? request('tender_id', '-') }} <br>
                        <strong>PTJ:</strong> {{ optional(optional($tender)->tenderer)->name ?? '-' }}
                    </div>

                    <div class="col-md-4 text-end">
                        <strong>Status:</strong> {{ $tender->status ?? '-' }}
                    </div>
                </div>

                <hr>

                <h5 class="fw-bold">Maklumat Jawatankuasa</h5>

                <div class="committee-tabs">
                    <button class="committee-tab active" data-tab="spec" onclick="switchCommittee('spec')">
                        Jawatankuasa Spesifikasi
                    </button>

                    <button class="committee-tab" data-tab="open" onclick="switchCommittee('open')">
                        Jawatankuasa Pembuka
                    </button>

                    <button class="committee-tab" data-tab="tech" onclick="switchCommittee('tech')">
                        Jawatankuasa Penilaian Teknikal
                    </button>

                    <button class="committee-tab" data-tab="fin" onclick="switchCommittee('fin')">
                        Jawatankuasa Penilaian Kewangan
                    </button>
                </div>

                <div id="committeeContent">

                    @foreach ($availableTabs as $tab)
                        <div id="tab-{{ $tab }}" class="committee-pane" data-jenis="{{ $tab }}"
                            @if ($tab != 'spec') style="display:none" @endif>

                            <table class="table table-bordered js-table">

                                <thead class="text-white" style="background:#2C3E9E">
                                    <tr>
                                        <th width="40"><input type="checkbox" class="check-all"></th>
                                        <th style="min-width: 250px;">No IC</th>
                                        <th style="min-width: 200px; white-space: normal;">Nama</th>
                                        <th style="min-width: 200px; white-space: normal;">Jawatan</th>
                                        <th style="min-width: 200px;">Email</th>
                                        <th width="80">Gred</th>
                                        <th width="80">P&P</th>
                                        <th width="150">Peranan</th>
                                    </tr>
                                </thead>

                                <tbody class="committee-tbody">
                                </tbody>

                            </table>

                            <!-- ACTION BUTTONS -->
                            <div class="d-flex justify-content-end gap-2 mb-4">
                                <button type="button" class="btn btn-success btn-tambah">Tambah</button>
                                <button type="button" class="btn btn-danger btn-hapus">Hapus</button>
                            </div>


                            <!-- CATATAN -->
                            <div class="catatan-box">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Catatan</label>
                                        <textarea class="form-control committee-catatan" rows="3">{{ $committeeDrafts[$tab]['catatan'] ?? '' }}</textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Dokumen Sokongan</label><br>

                                        <input type="file" class="d-none committee-dokumen-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">

                                        <button type="button" class="btn btn-success mt-2 btn-upload-dokumen">
                                            Muat Naik
                                        </button>
                                    </div>

                                    <div class="mt-2 text-muted small committee-file-name">
                                        {{ $committeeDrafts[$tab]['dokumen_sokongan_nama'] ?? '' }}
                                    </div>

                                </div>
                            </div>


                            <!-- MAIN ACTION -->
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-primary btn-simpan">Simpan [Draf]</button>
                                <button type="button" class="btn btn-info text-white" onclick="printLaporan()">Laporan</button>
                                <button type="button" class="btn btn-success btn-hantar">Hantar Pemakluman</button>
                            </div>


                        </div>
                    @endforeach

                </div>

                <hr>

                <!-- ===================== SUCCESS POPUP ====================== -->
                <div id="successPopup" class="modal fade" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center p-4">

                            <div class="mb-3">
                                <svg width="60" height="60" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"
                                        fill="#E6F7F3" />
                                    <path d="M10 14.2l-2.2-2.2-1.4 1.4L10 17 18 9l-1.4-1.4z" fill="#19c1a7" />
                                </svg>
                            </div>

                            <h6 class="fw-bold mb-3">Maklumat telah berjaya disimpan</h6>

                            <button type="button" class="btn btn-primary px-4" data-bs-dismiss="modal">
                                Tutup
                            </button>

                        </div>
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

        // helper
        function showTabs(tabList, activeTab) {

            tabList.forEach(type => {
                const btn = document.querySelector(`[data-tab="${type}"]`);
                const pane = document.getElementById(`tab-${type}`);

                if (btn && pane) {
                    btn.style.display = 'block';
                    pane.style.display = 'none';
                }
            });

            // active
            document.querySelector(`[data-tab="${activeTab}"]`).classList.add('active');
            document.getElementById(`tab-${activeTab}`).style.display = 'block';
        }

        function switchCommittee(type) {

            document.querySelectorAll('.committee-tab')
                .forEach(tab => tab.classList.remove('active'));

            document.querySelector(`[onclick="switchCommittee('${type}')"]`)
                .classList.add('active');

            document.querySelectorAll('#committeeContent > div')
                .forEach(pane => pane.style.display = 'none');

            document.getElementById(`tab-${type}`).style.display = 'block';
        }


        /* =============================
           LOCAL TABLE FUNCTIONS
        ============================= */

        /* Shared Selectize initialization function */
        function initSelectize(el) {
            const selectizeInstance = $(el).selectize({
                valueField: 'id',
                labelField: 'ic_number',
                searchField: ['ic_number', 'name', 'email'],
                options: localIcUsers,
                maxItems: 1,
                create: false,
                placeholder: 'Taip No IC...',
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

        /* Build and append a fresh row to a tbody, then initialize Selectize on its IC input */
        function createRow(tbody, rowData = null) {
            let tr = document.createElement('tr');
            const pAndP = (rowData && rowData.p_p) ? String(rowData.p_p) : '1';
            const peranan = (rowData && rowData.peranan) ? String(rowData.peranan) : '1';
            tr.innerHTML = `
                <td><input type="checkbox" class="row-check"></td>
                <td><input type="text" class="ic-search-select form-control" placeholder="Taip No IC..."></td>
                <td><input type="text" class="form-control bg-light member-name" placeholder="-" readonly></td>
                <td><input type="text" class="form-control bg-light member-jawatan" placeholder="-" readonly></td>
                <td><input type="email" class="form-control bg-light member-email" placeholder="-" readonly></td>
                <td><input type="text" class="form-control bg-light member-gred" placeholder="-" readonly></td>
                <td>
                    <select class="form-select pp-select">
                        <option value="1" ${pAndP === '1' ? 'selected' : ''}>Ya</option>
                        <option value="0" ${pAndP === '0' ? 'selected' : ''}>Tidak</option>
                    </select>
                </td>
                <td>
                    <select class="form-select peranan-select">
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

                // find the table this checkbox belongs to
                let table = this.closest('table');

                // find all row checkboxes in that table
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

                    if (!fileNameEl) {
                        return;
                    }

                    fileNameEl.textContent = this.files.length > 0 ? this.files[0].name : '';
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
