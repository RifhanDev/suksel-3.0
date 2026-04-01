@extends('layouts.v3.master')

@section('content')

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Laporan Syarikat Mengikut Status</h3>
            <p class="text-muted small m-0">Semak bilangan syarikat mengikut status pendaftaran.</p>
        </div>
    </div>

    <div class="content-card p-0">
        <div class="content-card-header p-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width: 38px; height: 38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <h3 class="content-card-title" style="font-size: 1rem;">Senarai Status Syarikat</h3>
            </div>
        </div>

        <div class="content-card-body p-2">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 w-100">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-muted small fw-bold py-3 ps-4">Status</th>
                            <th class="text-uppercase text-muted small fw-bold py-3" width="200px">Bilangan Syarikat</th>
                            <th class="text-uppercase text-center text-muted small fw-bold py-3 pe-4" width="160px">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="ps-4">
                                <span class="d-inline-flex align-items-center gap-2">
                                    <span style="width:10px;height:10px;border-radius:50%;background:#f59e0b;flex-shrink:0;"></span>
                                    <span class="fw-semibold text-dark">Daftar Belum Lulus</span>
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold" style="font-size:1.1rem;color:#1e293b;">{{ $daftar_belum_lulus }}</span>
                                <span class="text-muted small ms-1">syarikat</span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ action('ReportVendorStatusController@view', ['view' => 'daftar_belum_lulus']) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-8 px-3 d-inline-flex align-items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                                        Senarai
                                    </a>
                                    <a href="{{ action('ReportVendorStatusController@csv', ['view' => 'daftar_belum_lulus']) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-8 px-3 d-inline-flex align-items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                        CSV
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-4">
                                <span class="d-inline-flex align-items-center gap-2">
                                    <span style="width:10px;height:10px;border-radius:50%;background:#3b82f6;flex-shrink:0;"></span>
                                    <span class="fw-semibold text-dark">Lulus Belum Bayar</span>
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold" style="font-size:1.1rem;color:#1e293b;">{{ $lulus_belum_bayar }}</span>
                                <span class="text-muted small ms-1">syarikat</span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ action('ReportVendorStatusController@view', ['view' => 'lulus_belum_bayar']) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-8 px-3 d-inline-flex align-items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                                        Senarai
                                    </a>
                                    <a href="{{ action('ReportVendorStatusController@csv', ['view' => 'lulus_belum_bayar']) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-8 px-3 d-inline-flex align-items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                        CSV
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-4">
                                <span class="d-inline-flex align-items-center gap-2">
                                    <span style="width:10px;height:10px;border-radius:50%;background:#22c55e;flex-shrink:0;"></span>
                                    <span class="fw-semibold text-dark">Aktif</span>
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold" style="font-size:1.1rem;color:#1e293b;">{{ $aktif }}</span>
                                <span class="text-muted small ms-1">syarikat</span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ action('ReportVendorStatusController@view', ['view' => 'aktif']) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-8 px-3 d-inline-flex align-items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                                        Senarai
                                    </a>
                                    <a href="{{ action('ReportVendorStatusController@csv', ['view' => 'aktif']) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-8 px-3 d-inline-flex align-items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                        CSV
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-4">
                                <span class="d-inline-flex align-items-center gap-2">
                                    <span style="width:10px;height:10px;border-radius:50%;background:#ef4444;flex-shrink:0;"></span>
                                    <span class="fw-semibold text-dark">Tamat Pendaftaran</span>
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold" style="font-size:1.1rem;color:#1e293b;">{{ $tidak_aktif }}</span>
                                <span class="text-muted small ms-1">syarikat</span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ action('ReportVendorStatusController@view', ['view' => 'tidak_aktif']) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-8 px-3 d-inline-flex align-items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                                        Senarai
                                    </a>
                                    <a href="{{ action('ReportVendorStatusController@csv', ['view' => 'tidak_aktif']) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-8 px-3 d-inline-flex align-items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                        CSV
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-4">
                                <span class="d-inline-flex align-items-center gap-2">
                                    <span style="width:10px;height:10px;border-radius:50%;background:#f97316;flex-shrink:0;"></span>
                                    <span class="fw-semibold text-dark">Tamat Tempoh MOF</span>
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold" style="font-size:1.1rem;color:#1e293b;">{{ $mof_expired }}</span>
                                <span class="text-muted small ms-1">syarikat</span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ action('ReportVendorStatusController@view', ['view' => 'mof_expired']) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-8 px-3 d-inline-flex align-items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                                        Senarai
                                    </a>
                                    <a href="{{ action('ReportVendorStatusController@csv', ['view' => 'mof_expired']) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-8 px-3 d-inline-flex align-items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                        CSV
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-4">
                                <span class="d-inline-flex align-items-center gap-2">
                                    <span style="width:10px;height:10px;border-radius:50%;background:#f97316;flex-shrink:0;"></span>
                                    <span class="fw-semibold text-dark">Tamat Tempoh CIDB</span>
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold" style="font-size:1.1rem;color:#1e293b;">{{ $cidb_expired }}</span>
                                <span class="text-muted small ms-1">syarikat</span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ action('ReportVendorStatusController@view', ['view' => 'cidb_expired']) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-8 px-3 d-inline-flex align-items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                                        Senarai
                                    </a>
                                    <a href="{{ action('ReportVendorStatusController@csv', ['view' => 'cidb_expired']) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-8 px-3 d-inline-flex align-items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                        CSV
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
