@extends('layouts.v3.master')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
@endpush

@section('content')
{{-- Breadcrumb: back to SENARAI TENDER (first page) --}}
<nav aria-label="breadcrumb" class="py-2 mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="#" class="text-secondary text-decoration-none">STOS</a></li>
        <li class="breadcrumb-item"><a href="{{ route('penilaianTeknikal') }}" class="text-decoration-none">Peringkat Penilaian Teknikal</a></li>
        <li class="breadcrumb-item active" aria-current="page">Penilaian Teknikal</li>
    </ol>
</nav>

<style>
    /* ========================
   GLOBAL
======================== */
    body {
        background: #F3F4F6;
    }

    .card {
        border-radius: 12px;
        border: 1px solid #E5E7EB;
    }

    .card-body {
        padding: 24px;
    }

    hr {
        border: 1px solid #E5E7EB;
    }

    .card-body b {
        font-size: 13px;
        color: #374151;
    }

    /* =========================
   SECTION TITLES
========================= */
    .card-title-grey {
        background: #F9FAFB;
        padding: 12px 16px;
        border-left: 5px solid #C0392B;
        font-weight: 700;
        font-size: 15px;
        border-radius: 6px;
    }

    /* ==========================
   TABLE (shared: main + modal)
========================== */
    .teknikal-kerja-table-wrap > .table {
        border: none;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
        border-radius: 0;
        overflow: visible;
        --teknikal-table-border: var(--bs-border-color, #dee2e6);
    }

    .teknikal-kerja-table-wrap > .table thead th,
    .teknikal-kerja-table-wrap > .table tbody td {
        border-style: solid;
        border-color: var(--teknikal-table-border);
        border-width: 0 1px 1px 0;
    }

    .teknikal-kerja-table-wrap > .table thead th:first-child,
    .teknikal-kerja-table-wrap > .table tbody td:first-child {
        border-left-width: 1px;
    }

    .teknikal-kerja-table-wrap > .table thead tr:first-child th {
        border-top-width: 1px;
    }

    .teknikal-kerja-table-wrap > .table thead tr:first-child th:first-child {
        border-top-left-radius: 10px;
    }

    .teknikal-kerja-table-wrap > .table thead tr:first-child th:last-child {
        border-top-right-radius: 10px;
    }

    .teknikal-kerja-table-wrap > .table tbody tr:last-child td:first-child {
        border-bottom-left-radius: 10px;
    }

    .teknikal-kerja-table-wrap > .table tbody tr:last-child td:last-child {
        border-bottom-right-radius: 10px;
    }

    .teknikal-kerja-table-wrap .table td {
        font-size: 13px;
        padding: 12px;
        vertical-align: middle;
    }

    /* ==========================
   BUTTONS
========================== */
    .btn {
        border-radius: 8px;
        font-weight: 600;
        padding: 8px 16px;
    }

    .btn-success {
        background: #16A34A;
        border: none;
    }

    .btn-success:hover {
        background: #15803D;
    }

    .btn-primary {
        background: #1E3A8A;
        border: none;
    }

    .btn-primary:hover {
        background: #1E40AF;
    }

    .btn-outline-secondary {
        border-radius: 8px;
    }

    /* ==========================
   LINKS
========================== */
    .text-primary,
    .text-primary:hover {
        color: #2563EB !important;
    }

    /* ==========================
   FORM
========================== */
    .form-control,
    .form-select {
        border-radius: 8px;
        font-size: 13px;
    }

    .form-check-label {
        font-size: 13px;
    }

    .lampiran-teknikal-label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }

    .btn-tutup-simpan {
        background: #45558a;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        padding: 8px 28px;
    }

    .btn-tutup-simpan:hover {
        background: #3a4a75;
        color: #fff;
    }

    .modal-simpan-berjaya .modal-content {
        border: 1px solid #000;
        border-radius: 4px;
        background: #fff;
    }

    .modal-simpan-berjaya .modal-body {
        padding: 2rem 1.5rem 2.25rem;
    }

    .modal-simpan-berjaya-img {
        margin: 0 auto 1rem;
        display: block;
    }

    .modal-simpan-berjaya-msg {
        font-weight: 700;
        color: #000;
        font-size: 1rem;
        margin-bottom: 1.75rem;
    }

    /* ==========================
   MODAL
========================== */
    .modal-content {
        border-radius: 14px;
    }

    .modal-header {
        background: #1E3A8A;
        color: white;
    }

    .modal-title {
        color: white;
        font-weight: 700;
    }

    .modal-footer {
        border-top: 1px solid #E5E7EB;
    }

    /* Status Pematuhan dropdown: enough width, no overlap with chevron */
    #modalSemakanKetepatanDokumenTeknikal select.form-select {
        min-width: 100%;
        width: 100%;
        padding-right: 2.25rem;
        box-sizing: border-box;
    }

    #modalSemakanKetepatanDokumenTeknikal td:nth-child(3) {
        min-width: 200px;
    }

    #modalViewDokumenTeknikal .modal-body iframe {
        background: #fff;
    }

    /* ==========================
   TABLE – RED THEME OVERRIDE
========================== */

    /* Table header */
    .table thead th {
        background-color: #C0392B !important;
        color: #FFFFFF !important;
        text-align: center;
        font-size: 13px;
        padding: 12px;
        border-color: #A93226 !important;
    }

    /* Table header row */
    .table thead tr {
        background-color: #C0392B !important;
    }

    /* Table borders */
    .table-bordered> :not(caption)>* {
        border-color: #E5B4AF;
    }

    /* Table hover */
    .table tbody tr:hover {
        background: #FDEDEC;
    }
</style>

@php
    $borangTeknikalRows = [
        ['rujukan' => '45/53', 'harga' => '4,438,243.50'],
        ['rujukan' => '46/2024', 'harga' => '3,125,000.00'],
        ['rujukan' => 'KPKR/88', 'harga' => '5,890,100.25'],
        ['rujukan' => 'PTJ/12/2023', 'harga' => '2,450,999.00'],
        ['rujukan' => 'SW/001', 'harga' => '6,200,000.00'],
        ['rujukan' => 'JKR/PHT/44', 'harga' => '1,980,500.75'],
        ['rujukan' => 'TENDER/2024/07', 'harga' => '4,100,250.00'],
        ['rujukan' => 'PER/P/J11', 'harga' => '3,333,333.33'],
    ];
@endphp

<div class="col-12">
    <div class="card">
        <div class="card-body">
            {{-- Tender info strip --}}
            <div class="row mb-2">
                <div class="col-md-4 border-end">
                    <b>No. Sebut Harga / Tender</b>
                    <div class="text-success">{{ $tender_no ?? 'Belum Dijana' }}</div>
                </div>
                <div class="col-md-4 border-end">
                    <b>Tempoh Sah Laku Tawaran (Hari)</b>
                    <div>90</div>
                </div>
                <div class="col-md-4">
                    <b>PTJ</b>
                    <div class="small">BAHAGIAN PENTADBIRAN - CAWANGAN KEWANGAN - KEMENTERIAN KEWANGAN</div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 border-end">
                    <b>Tajuk Perolehan</b>
                    <div class="small">PROJEK MENAIKTARAF JALAN PELABUHAN UTARA DARI KLANG CONTAINER TERMINAL (Kerja)</div>
                </div>
                <div class="col-md-4 border-end">
                    <b>STATUS</b>
                    <div>Menunggu Penilaian Cadangan Teknikal</div>
                </div>
                <div class="col-md-4">
                    <b>Sah Laku Tawaran Tamat</b>
                    <div>17/01/2022</div>
                </div>
            </div>
            <hr class="my-3 border-secondary">

            <h4 class="card-title card-title-grey">BORANG TEKNIKAL</h4>
            <div class="teknikal-kerja-table-wrap">
                <table class="table table-bordered w-100 mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 10%;">Bilangan</th>
                            <th class="text-center" style="width: 40%;">Rujukan Petender</th>
                            <th class="text-center" style="width: 20%;">Harga Asal Tender (RM)</th>
                            <th class="text-center" style="width: 20%;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($borangTeknikalRows as $index => $row)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $row['rujukan'] }}</td>
                                <td class="text-end">{{ $row['harga'] }}</td>
                                <td class="text-center">
                                    <select class="form-select" name="status_petender_{{ $index + 1 }}" aria-label="Status">
                                        <option value="" selected disabled>Sila Pilih</option>
                                        <option value="lulus">Lulus</option>
                                        <option value="tidak_lulus">Tidak Lulus</option>
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row mb-3 px-3 mt-4">
                <div class="col-md-12 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="btnSimpanTeknikalKerja" data-bs-toggle="modal" data-bs-target="#modalSimpanBerjaya">Simpan</button>
                </div>
            </div>

            <hr class="my-4 border-secondary">

            <h4 class="card-title card-title-grey mb-3">LAMPIRAN PENILAIAN TEKNIKAL (JIKA PERLU)</h4>
            <div class="row g-3 align-items-end mb-3 px-1">
                <div class="col-md-6">
                    <div class="lampiran-teknikal-label">Nama Dokumen</div>
                    <input type="text" class="form-control" id="lampiranNamaFail" name="lampiran_nama_paparan" readonly placeholder="Tiada fail dipilih" autocomplete="off">
                </div>
                <div class="col-md-6">
                    <div class="lampiran-teknikal-label">Pilih Fail</div>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <input type="file" class="d-none" id="lampiranFileInput" name="lampiran_fail">
                        <button type="button" class="btn btn-outline-secondary" id="btnPilihFail">Pilih Fail</button>
                    </div>
                </div>
            </div>
            <div class="row mb-2 px-1">
                <div class="col-12">
                    <button type="button" class="btn btn-primary" id="btnTambahDokumen">Tambah Dokumen</button>
                </div>
            </div>
            <ul class="list-unstyled small text-muted mb-0 px-1" id="lampiranList"></ul>

        </div>
    </div>

    {{-- Modal: maklumat berjaya disimpan (selepas Simpan) --}}
    <div class="modal fade modal-simpan-berjaya" id="modalSimpanBerjaya" tabindex="-1" aria-labelledby="modalSimpanBerjayaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <svg class="modal-simpan-berjaya-img" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M40.5514 48.8009C40.2542 48.8009 39.9588 48.687 39.733 48.4611C39.3122 48.0403 39.2832 47.3744 39.648 46.9188C39.6712 46.5405 39.2967 45.1197 37.2622 42.205C35.4149 39.5585 32.7317 36.4352 29.7088 33.4143C26.686 30.3914 23.5646 27.7102 20.9182 25.861C18.0034 23.8264 16.5846 23.4519 16.2043 23.4751C15.7507 23.8399 15.0828 23.811 14.662 23.3902C14.2103 22.9385 14.2103 22.2049 14.662 21.7513C15.8299 20.5835 18.0246 21.1394 21.5706 23.5021C24.4738 25.4363 27.9465 28.3742 31.3477 31.7755C34.7489 35.1767 37.6868 38.6493 39.621 41.5525C41.9837 45.0985 42.5397 47.2933 41.3718 48.4611C41.144 48.6889 40.8468 48.8009 40.5514 48.8009Z" fill="#0AB39C"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M52.6042 50.0906C51.9653 50.0906 51.446 49.5714 51.446 48.9324C51.446 43.4445 48.2108 40.8328 45.4968 39.6071C40.0861 37.1652 33.0694 38.3234 30.1546 40.8927C29.674 41.3154 28.9424 41.2691 28.5197 40.7904C28.0969 40.3097 28.1432 39.5781 28.622 39.1554C32.5695 35.675 40.7328 34.9145 46.4485 37.4953C51.1642 39.6244 53.7624 43.6858 53.7624 48.9324C53.7624 49.5714 53.2432 50.0906 52.6042 50.0906Z" fill="#405189"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M23.1397 36.1476C22.9448 36.1476 22.746 36.0974 22.5645 35.9931C22.0105 35.6746 21.8194 34.9662 22.1379 34.4122C24.8828 29.6347 24.5257 19.5758 19.6208 12.7791C17.1326 9.33158 12.5577 5.55008 4.88666 7.11171C4.25931 7.23911 3.6474 6.83568 3.52 6.20832C3.3926 5.58097 3.79796 4.96906 4.42339 4.84166C11.3532 3.4306 17.4183 5.76821 21.499 11.4221C26.485 18.3307 27.6972 29.3876 24.1454 35.5665C23.9312 35.9391 23.5413 36.1476 23.1397 36.1476Z" fill="#405189"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M40.5515 48.8012C40.2542 48.8012 39.9589 48.6892 39.733 48.4615C39.2814 48.0098 39.2794 47.2763 39.733 46.8246L39.735 46.8227C40.1866 46.371 40.9201 46.371 41.3737 46.8227C41.8254 47.2744 41.8254 48.0079 41.3737 48.4615C41.146 48.6873 40.8487 48.8012 40.5515 48.8012Z" fill="#0AB39C"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.4782 23.7259C15.181 23.7259 14.8856 23.614 14.6598 23.3862C14.2081 22.9345 14.2062 22.201 14.6598 21.7494L14.6617 21.7474C15.1134 21.2958 15.8469 21.2958 16.3005 21.7474C16.7522 22.1991 16.7522 22.9326 16.3005 23.3862C16.0708 23.614 15.7735 23.7259 15.4782 23.7259Z" fill="#0AB39C"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M41.3703 46.8245C40.9495 46.4037 40.2873 46.3748 39.8337 46.7357C39.8202 46.7357 39.8067 46.7377 39.7932 46.7396C39.3743 46.7415 37.9516 46.3362 35.114 44.3537C32.4675 42.5064 29.3442 39.8232 26.3233 36.8003C23.3023 33.7774 20.6191 30.656 18.7698 28.0095C16.7874 25.168 16.382 23.7492 16.3839 23.3284C16.3859 23.3149 16.3859 23.3033 16.3878 23.2898C16.7488 22.8362 16.7198 22.1722 16.299 21.7533C15.8473 21.3016 15.1138 21.3016 14.6601 21.7533C14.3378 22.0756 14.1525 22.4791 14.0946 22.9655L0.254014 59.6323C-0.0992388 60.5666 0.120821 61.5878 0.827325 62.2943C1.31184 62.7788 1.94113 63.0336 2.59166 63.0336C2.89086 63.0336 3.19392 62.9795 3.48926 62.8676L40.1464 49.0348C40.6387 48.9788 41.044 48.7896 41.3703 48.4634C41.822 48.0098 41.822 47.2762 41.3703 46.8245ZM2.6707 60.699C2.64174 60.7106 2.55295 60.7434 2.46608 60.6546C2.37729 60.5658 2.41011 60.479 2.42169 60.45L15.1809 26.6391C15.5148 27.2587 15.9221 27.9304 16.4086 28.662C18.3427 31.5652 21.2806 35.0378 24.6818 38.4389C28.083 41.8401 31.5555 44.778 34.4587 46.7121C35.1883 47.1986 35.8601 47.6078 36.4778 47.9417L2.6707 60.699Z" fill="#0AB39C"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M38.4861 29.1749C38.212 29.1749 37.936 29.0784 37.714 28.8796C37.2373 28.453 37.1967 27.7214 37.6233 27.2446C39.0034 25.7005 40.9452 26.0826 42.362 26.3606C43.7653 26.6366 44.4776 26.7119 44.9292 26.2062C45.3809 25.7005 45.2265 25.0036 44.796 23.639C44.3617 22.2608 43.7653 20.373 45.1454 18.8308C46.5255 17.2885 48.4673 17.6688 49.8841 17.9486C51.2874 18.2247 51.9997 18.3 52.4494 17.7942C52.876 17.3175 53.6076 17.2769 54.0843 17.7035C54.5611 18.1301 54.6016 18.8616 54.175 19.3384C52.7949 20.8807 50.8531 20.5004 49.4363 20.2205C48.0331 19.9445 47.3208 19.8692 46.871 20.3749C46.4194 20.8807 46.5738 21.5775 47.0042 22.9422C47.4385 24.3203 48.035 26.2081 46.6549 27.7504C45.2747 29.2926 43.3329 28.9124 41.9161 28.6344C40.5129 28.3584 39.8006 28.2831 39.3509 28.7888C39.1212 29.0436 38.8046 29.1749 38.4861 29.1749Z" fill="#405189"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M33.4113 21.3249C32.7897 21.3249 32.2743 20.8307 32.255 20.2033C32.1874 18.134 33.8572 17.0704 35.0752 16.2925C36.2817 15.5223 36.8473 15.086 36.8261 14.4085C36.8048 13.7309 36.2103 13.3333 34.9575 12.6422C33.6912 11.9453 31.9577 10.9917 31.8902 8.92242C31.8226 6.8531 33.4924 5.78948 34.7104 5.01155C35.9169 4.24135 36.4825 3.80509 36.4612 3.12947C36.44 2.49053 36.9419 1.95583 37.5808 1.93459C38.2178 1.91529 38.7545 2.41525 38.7757 3.05419C38.8433 5.12351 37.1735 6.18713 35.9555 6.96506C34.749 7.73526 34.1834 8.17152 34.2047 8.84714C34.2259 9.52469 34.8204 9.92427 36.0732 10.6134C37.3395 11.3103 39.073 12.2638 39.1405 14.3332C39.2081 16.4025 37.5383 17.4661 36.3203 18.244C35.1138 19.0142 34.5483 19.4505 34.5695 20.128C34.5907 20.767 34.0888 21.3017 33.4499 21.3229C33.4364 21.3229 33.4229 21.3249 33.4113 21.3249Z" fill="#405189"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M24.572 4.67748C24.3713 4.67748 24.1667 4.62536 23.9814 4.51534C23.4313 4.18913 23.2517 3.47688 23.578 2.92869L24.9832 0.566085C25.3094 0.0159694 26.0216 -0.163542 26.5698 0.162667C27.1199 0.488876 27.2994 1.20113 26.9732 1.74932L25.568 4.11192C25.3518 4.4748 24.9677 4.67748 24.572 4.67748Z" fill="#0AB39C"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M49.1879 61.19C48.7961 61.19 48.4139 60.9912 48.1958 60.6322C47.8638 60.0859 48.0375 59.3737 48.5857 59.0417L51.5389 57.2504C52.0852 56.9184 52.7974 57.0921 53.1294 57.6403C53.4614 58.1866 53.2877 58.8988 52.7395 59.2308L49.7863 61.0221C49.5991 61.136 49.3925 61.19 49.1879 61.19Z" fill="#0AB39C"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M60.7969 47.6796C60.5961 47.6796 60.3915 47.6274 60.2062 47.5174C59.6561 47.1912 59.4766 46.4789 59.8028 45.9308L61.208 43.5682C61.5342 43.018 62.2445 42.8385 62.7947 43.1647C63.3448 43.491 63.5243 44.2032 63.1981 44.7514L61.7929 47.114C61.5767 47.4769 61.1906 47.6796 60.7969 47.6796Z" fill="#405189"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M1.15718 35.7829C0.859919 35.7829 0.564594 35.669 0.338756 35.4431C-0.112918 34.9915 -0.112918 34.258 0.338756 33.8044L2.2825 31.8606C2.73417 31.409 3.46766 31.409 3.92127 31.8606C4.37294 32.3123 4.37294 33.0458 3.92127 33.4994L1.97752 35.4431C1.74976 35.669 1.45443 35.7829 1.15718 35.7829Z" fill="#405189"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M55.4442 7.28524C55.1469 7.28524 54.8516 7.17136 54.6258 6.94552L52.682 5.00371C52.2303 4.55203 52.2303 3.81854 52.682 3.36494C53.1337 2.91327 53.8672 2.91327 54.3208 3.36494L56.2645 5.30868C56.7162 5.76036 56.7162 6.49385 56.2645 6.94745C56.0368 7.17329 55.7414 7.28524 55.4442 7.28524Z" fill="#0AB39C"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M31.473 61.1957C31.0079 61.1957 30.5697 60.9139 30.3921 60.4545L29.4 57.8912C29.1683 57.2947 29.4656 56.623 30.062 56.3933C30.6585 56.1617 31.3302 56.459 31.5599 57.0554L32.552 59.6187C32.7837 60.2152 32.4864 60.8869 31.89 61.1166C31.7529 61.1706 31.612 61.1957 31.473 61.1957Z" fill="#405189"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8.60843 19.0564C8.34013 19.0564 8.07183 18.9638 7.85371 18.7765L5.77099 16.9833C5.2865 16.5664 5.23052 15.8349 5.64939 15.3504C6.06632 14.8659 6.79787 14.8099 7.28236 15.2288L9.36508 17.0219C9.84957 17.4389 9.90555 18.1704 9.48669 18.6549C9.25892 18.9213 8.93464 19.0564 8.60843 19.0564Z" fill="#0AB39C"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M57.689 32.1306C57.1832 32.1306 56.718 31.7967 56.5752 31.2852L55.8301 28.6388C55.6564 28.0231 56.0154 27.3842 56.6312 27.2105C57.2469 27.0367 57.8858 27.3958 58.0596 28.0115L58.8046 30.6578C58.9783 31.2736 58.6193 31.9125 58.0036 32.0862C57.8993 32.1171 57.7932 32.1306 57.689 32.1306Z" fill="#0AB39C"/>
                    </svg>
                    <p class="modal-simpan-berjaya-msg mb-0" id="modalSimpanBerjayaLabel">Maklumat telah berjaya disimpan</p>
                    <button type="button" class="btn btn-tutup-simpan mt-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: SEMAKAN PEMATUHAN DOKUMEN TEKNIKAL --}}
    <div class="modal fade" id="modalSemakanKetepatanDokumenTeknikal" tabindex="-1" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">SEMAKAN PEMATUHAN DOKUMEN TEKNIKAL</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p class="text-muted small mb-2">Semak status pematuhan bagi setiap pembekal.</p>
                    <p><strong>Tajuk / Dokumen:</strong> Salinan Sijil Pendaftaran dengan Kementerian Teknikal</p>
                    <div class="mb-3">
                        <h4 class="card-title card-title-grey">SENARAI PEMBEKAL</h4>
                    </div>

                    <div class="table-responsive">
                        <div class="teknikal-kerja-table-wrap">
                            <table class="table table-bordered align-middle w-100 mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 12%;">Kod Pembekal</th>
                                        <th class="text-center" style="width: 38%;">Dokumen</th>
                                        <th class="text-center" style="width: 28%;">Status Pematuhan</th>
                                        <th class="text-center" style="width: 22%;">Catatan</th>
                                    </tr>
                                </thead>
                            <tbody>
                                {{-- data-doc-url: ganti dengan URL storage sebenar selepas backend menyediakan fail --}}
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>
                                        <div class="d-flex align-items-start gap-2">
                                            <a href="https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf" target="_blank">
                                                <i class="fa-solid fa-file-pdf fa-lg" aria-hidden="true"></i>
                                            </a>
                                            <span class="small text-break">Salinan Sijil Pendaftaran dengan Kementerian Kewangan.pdf</span>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <select class="form-select" name="status_pematuhan_1" aria-label="Status Pematuhan">
                                            <option value="" selected disabled>Sila Pilih</option>
                                            <option value="mematuhi">Mematuhi</option>
                                            <option value="tidak_mematuhi">Tidak Mematuhi</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="Catatan">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">2</td>
                                    <td>
                                        <div class="d-flex align-items-start gap-2">
                                            <a href="https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf" target="_blank">
                                                <i class="fa-solid fa-file-pdf fa-lg" aria-hidden="true"></i>
                                            </a>
                                            <span class="small text-break">Salinan Sijil Pendaftaran dengan Kementerian Kewangan.pdf</span>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <select class="form-select" name="status_pematuhan_2" aria-label="Status Pematuhan">
                                            <option value="" selected disabled>Sila Pilih</option>
                                            <option value="mematuhi">Mematuhi</option>
                                            <option value="tidak_mematuhi">Tidak Mematuhi</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="Catatan">
                                    </td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" id="btnStep1SimpanDokTeknikal" class="btn btn-success" data-bs-dismiss="modal">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal paparan dokumen (PDF / dipaparkan dalam iframe) --}}
    <div class="modal fade" id="modalViewDokumenTeknikal" tabindex="-1" aria-labelledby="modalViewDokumenTeknikalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-fullscreen-lg-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-truncate pe-3 mb-0" id="modalViewDokumenTeknikalLabel">Paparan dokumen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-0 bg-light">
                    <iframe id="iframeViewDokumenTeknikal" title="Paparan dokumen" class="w-100 border-0 d-block"
                        style="min-height: 70vh; height: min(78vh, 820px);"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modalViewDoc = document.getElementById('modalViewDokumenTeknikal');
        const iframeViewDoc = document.getElementById('iframeViewDokumenTeknikal');
        const titleViewDoc = document.getElementById('modalViewDokumenTeknikalLabel');
        if (modalViewDoc && iframeViewDoc && titleViewDoc) {
            modalViewDoc.addEventListener('show.bs.modal', function(event) {
                const trigger = event.relatedTarget;
                if (!trigger || !trigger.matches('.btn-view-doc-teknikal')) return;
                const url = trigger.getAttribute('data-doc-url');
                const docTitle = trigger.getAttribute('data-doc-title') || 'Dokumen';
                titleViewDoc.textContent = docTitle;
                iframeViewDoc.src = url ? url.trim() : 'about:blank';
            });
            modalViewDoc.addEventListener('hidden.bs.modal', function() {
                iframeViewDoc.src = 'about:blank';
            });
        }

        const lampiranInput = document.getElementById('lampiranFileInput');
        const lampiranNama = document.getElementById('lampiranNamaFail');
        const btnPilihFail = document.getElementById('btnPilihFail');
        const btnTambahDokumen = document.getElementById('btnTambahDokumen');
        const lampiranList = document.getElementById('lampiranList');

        if (btnPilihFail && lampiranInput) {
            btnPilihFail.addEventListener('click', () => lampiranInput.click());
        }

        if (lampiranInput && lampiranNama) {
            lampiranInput.addEventListener('change', () => {
                const f = lampiranInput.files && lampiranInput.files[0];
                lampiranNama.value = f ? f.name : '';
            });
        }

        if (btnTambahDokumen && lampiranInput && lampiranList && lampiranNama) {
            btnTambahDokumen.addEventListener('click', () => {
                const f = lampiranInput.files && lampiranInput.files[0];
                if (!f) {
                    alert('Sila pilih fail terlebih dahulu.');
                    return;
                }
                const li = document.createElement('li');
                li.className = 'mb-1';
                li.textContent = f.name;
                lampiranList.appendChild(li);
                lampiranInput.value = '';
                lampiranNama.value = '';
            });
        }
    });
</script>

@endsection