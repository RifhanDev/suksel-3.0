@extends('layouts.v3.master')

@section('content')

<style>
/* ===============================
   THEME COLORS
================================ */
:root{
    --theme-red: #B11217;        /* main red */
    --theme-red-dark: #8E0F13;
    --theme-grey: #f5f6fa;
}

/* ===============================
   CARD
================================ */
.card{
    border-radius:12px;
}

/* ===============================
   TABLE HEADER (RED)
================================ */
.table thead th{
    background: var(--theme-red) !important;
    color: #fff !important;
    text-align: center;
    font-size: 13px;
    vertical-align: middle;
}

/* ===============================
   TABLE BODY
================================ */
.table tbody td{
    font-size: 13px;
    vertical-align: middle;
}

/* ===============================
   BADGE - WAJIB
================================ */
.badge-wajib{
    background: var(--theme-red);
    color:#fff;
    font-size:11px;
    padding:4px 8px;
    border-radius:6px;
}

/* ===============================
   ACTION ICONS
================================ */
.action-icon{
    color: var(--theme-red);
    font-size:16px;
    cursor:pointer;
    margin:0 5px;
}

.action-icon:hover{
    color: var(--theme-red-dark);
}

/* ===============================
   MODAL HEADER
================================ */
.modal-header{
    background: var(--theme-red);
    color:#fff;
}
</style>

<div class="card">
    <div class="card-body">

        <h5 class="fw-bold mb-3">SENARAI MESYUARAT / TAKLIMAT</h5>

        <!-- ================= TABLE ================= -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="5%">Bil.</th>
                        <th>No / Tajuk</th>
                        <th>Lokasi / Alamat</th>
                        <th>Tarikh & Waktu</th>
                        <th>Wajib Hadir</th>
                        <th width="10%">Wakil Syarikat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td>
                            TENDER PERKHIDMATAN DIGITAL FORENSIK KE ATAS ALIRAN PROSES SISTEM XXXX
                        </td>
                        <td>
                            Bilik Gerakan MTES, Tingkat Bawah, Pejabat SUK Selangor
                        </td>
                        <td class="text-center">
                            20 Jun 2024<br>
                            <small>15:00</small>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-danger">Wajib</span>
                        </td>
                        <td class="text-center">
                            <!-- VIEW (OPTIONAL) -->
                            <button class="action-btn me-2">
                                <i class="bi bi-eye"></i>
                            </button>

                            <!-- EDIT -->
                            <button class="action-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#wakilModal">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- ================= MODAL ================= -->
<div class="modal fade" id="wakilModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title">Wakil Syarikat</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th width="25%">No IC</th>
                                <th>Nama Individu</th>
                                <th width="15%" class="text-center">Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text"
                                           class="form-control"
                                           value="984356089032">
                                </td>
                                <td>
                                    <input type="text"
                                           class="form-control"
                                           value="ALI BIN ABU">
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input" checked>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="text" class="form-control">
                                </td>
                                <td>
                                    <input type="text" class="form-control">
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input">
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="text" class="form-control">
                                </td>
                                <td>
                                    <input type="text" class="form-control">
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <small class="text-muted">
                    Nota: Nama agen dan penama hendaklah dinyatakan di sijil CIDB dan MoF.
                </small>

            </div>

            <div class="modal-footer justify-content-center">
                <button type="button"
                        class="btn btn-success px-4"
                        data-bs-dismiss="modal">
                    Keluar
                </button>
            </div>

        </div>
    </div>
</div>

@endsection
