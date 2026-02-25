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
    <div id="laporanArea">
        <!-- ===================== PAGE DETAIL ====================== -->
        <div id="pageDetail">

            <div class="card p-4">

                <!-- Header -->
                <div class="row mb-3">
                    <div class="col-md-8">
                        <strong>No Tender:</strong> QT21000000023741 <br>
                        <strong>PTJ:</strong> BAHAGIAN PENTADBIRAN – CAWANGAN KEWANGAN – KEMENTERIAN KEWANGAN
                    </div>

                    <div class="col-md-4 text-end">
                        <strong>Status:</strong> Menunggu Penyerahan Tender
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

                    <button class="committee-tab" data-tab="harga" onclick="switchCommittee('harga')">
                        Jawatankuasa Penilaian Sebut Harga / Tender
                    </button>
                </div>

                <div id="committeeContent">

                    @foreach (['spec', 'open', 'tech', 'fin', 'harga'] as $tab)
                        <div id="tab-{{ $tab }}" @if ($tab != 'spec') style="display:none" @endif>

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
                                <button class="btn btn-success btn-tambah">Tambah</button>
                                <button class="btn btn-danger btn-hapus">Hapus</button>
                            </div>


                            <!-- CATATAN -->
                            <div class="catatan-box">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Catatan</label>
                                        <textarea class="form-control" rows="3"></textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Dokumen Sokongan</label><br>

                                        <!-- Hidden file input -->
                                        <input type="file" id="dokumenSokongan" class="d-none">

                                        <!-- Trigger button -->
                                        <button type="button" class="btn btn-success mt-2"
                                            onclick="document.getElementById('dokumenSokongan').click()">
                                            Muat Naik
                                        </button>
                                    </div>

                                    <div class="mt-2 text-muted small" id="fileName"></div>

                                </div>
                            </div>


                            <!-- MAIN ACTION -->
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-primary btn-simpan">Simpan</button>
                                <button class="btn btn-info text-white" onclick="printLaporan()">Laporan</button>
                                <button class="btn btn-success btn-hantar">Hantar Pemakluman</button>
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
@endsection

@section('scripts')
    <script>
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
            $(el).selectize({
                valueField: 'id',
                labelField: 'name',
                searchField: 'name',
                maxItems: 1,
                create: false,
                placeholder: 'Taip Nama...',
                render: {
                    option: function(item, escape) {
                        return '<div>' +
                            '<strong>' + escape(item.name) + '</strong>' +
                            '<br><small class="text-muted">' + escape(item.email) + '</small>' +
                            '</div>';
                    }
                },
                load: function(query, callback) {
                    if (!query.length) return callback();
                    $.ajax({
                        url: '{{ url('api/search-users') }}?q=' + encodeURIComponent(query),
                        type: 'GET',
                        success: function(res) {
                            callback(res);
                        },
                        error: function() {
                            callback();
                        }
                    });
                },
                onChange: function(value) {
                    if (!value) return;
                    let item = this.options[value];
                    if (item) {
                        let tr = $(this.$wrapper).closest('tr');
                        tr.find('td:eq(2) input').val(item.name || '-');
                        tr.find('td:eq(3) input').val(item.roles_column || 'Pegawai');
                        tr.find('td:eq(4) input').val(item.email || '-');
                        tr.find('td:eq(5) input').val('G41');
                    }
                }
            });
        }

        /* Build and append a fresh row to a tbody, then initialize Selectize on its IC input */
        function createRow(tbody) {
            let tr = document.createElement('tr');
            tr.innerHTML = `
                <td><input type="checkbox" class="row-check"></td>
                <td><input type="text" class="ic-search-select form-control" placeholder="Taip Nama..."></td>
                <td><input type="text" class="form-control bg-light" placeholder="-" readonly></td>
                <td><input type="text" class="form-control bg-light" placeholder="-" readonly></td>
                <td><input type="email" class="form-control bg-light" placeholder="-" readonly></td>
                <td><input type="text" class="form-control bg-light" placeholder="-" readonly></td>
                <td>
                    <select class="form-select">
                        <option>Ya</option>
                        <option>Tidak</option>
                    </select>
                </td>
                <td>
                    <select class="form-select">
                        <option>Pengerusi</option>
                        <option>Setiausaha</option>
                        <option>Ahli</option>
                    </select>
                </td>
            `;
            tbody.appendChild(tr);
            initSelectize(tr.querySelector('.ic-search-select'));
        }

        // On page load: seed one empty row in every committee tbody
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.committee-tbody').forEach(function(tbody) {
                createRow(tbody);
            });
        });

        // ADD ROW
        document.querySelectorAll('.btn-tambah').forEach(btn => {
            btn.addEventListener('click', function() {
                let tabPane = this.closest('[id^="tab-"]');
                let tbody = tabPane.querySelector('.committee-tbody');
                createRow(tbody);
            });
        });

        // DELETE SELECTED ROWS
        document.querySelectorAll('.btn-hapus').forEach(btn => {
            btn.addEventListener('click', function() {
                let tabPane = this.closest('[id^="tab-"]');
                let table = tabPane.querySelector('table');
                table.querySelectorAll('.row-check:checked').forEach(cb => {
                    cb.closest('tr').remove();
                });
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

            // SIMPAN
            document.querySelectorAll('.btn-simpan').forEach(btn => {
                btn.addEventListener('click', function() {
                    successModal.show();
                });
            });

            // HANTAR
            document.querySelectorAll('.btn-hantar').forEach(btn => {
                btn.addEventListener('click', function() {
                    successModal.show();
                });
            });

        });

        // DISPLAY UPLOADED FILE NAME
        document.getElementById('dokumenSokongan').addEventListener('change', function() {
            if (this.files.length > 0) {
                document.getElementById('fileName').innerText = this.files[0].name;
            }
        });

        // PRINT REPORT
        function printLaporan() {
            const content = document.getElementById('laporanArea').innerHTML;
            const original = document.body.innerHTML;

            document.body.innerHTML = content;
            window.print();
            document.body.innerHTML = original;
            location.reload(); // restore JS & styles
        }
    </script>
@endsection
