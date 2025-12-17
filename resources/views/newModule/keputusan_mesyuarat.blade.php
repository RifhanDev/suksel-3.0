@extends('layouts.v3.master')

@section('title', 'Keputusan Mesyuarat')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        .breadcrumb-path {
            background-color: #e6e6e6;
            padding: 6px 12px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .header-info {
            background-color: #e6e6e6;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .header-info b {
            font-weight: 700;
        }

        .header-info span.red-text {
            color: #c40000;
        }

        .table thead th {
            background-color: #3e5a9a;
            color: white;
            font-weight: 600;
        }

        .section-header {
            background-color: #d6d6d6;
            font-weight: bold;
            padding: 10px;
        }
    </style>
@endsection

@section('content')
<div class="tab-content">

    <!-- Breadcrumb -->
    <div class="breadcrumb-path">STOS > <strong>Penyediaan Kertas Taklimat dan Pengesyoran Pembekal</strong></div>

    <!-- Card -->
    <div class="card">
        <div class="card-body">

            <h6 class="fw-bold mb-3">SENARAI TENDER</h6>

            <!-- Filters Row -->
            <div class="row g-2 align-items-end mb-4">
                <div class="col-md-2">
                    <select class="form-select">
                        <option selected>No. Tender</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" placeholder="Tajuk Perolehan">
                </div>
                <div class="col-md-2">
                    <select class="form-select">
                        <option selected>Status</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select">
                        <option selected>Tarikh</option>
                        <option value="1">Hari Ini</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Tapis</button>
                </div>
            </div>

            <!-- Tender Table -->
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>No. Tender/Sebut Harga</th>
                            <th>Tajuk Perolehan</th>
                            <th>Tarikh Serahan</th>
                            <th>Tempoh Sah Laku</th>
                            <th>Status</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>QT210000000023741</td>
                            <td class="text-start">TENDER PERKHIDMATAN DIGITAL FORENSIK KE ATAS ALIRAN PROSES SISTEM XXXX</td>
                            <td>3/3/2024</td>
                            <td></td>
                            <td>Bidaan</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <input type="text" class="form-control" disabled>
                                    {{-- <a href="{{ route('pengesyoran-pembekal') }}" class="btn btn-success">Kuiri</a> --}}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>QT210000000023740</td>
                            <td class="text-start">TAJUK PEROLEHAN 1</td>
                            <td>2/2/2024</td>
                            <td></td>
                            <td></td>
                            <td>
                                <div class="input-group">
                                    <input type="text" class="form-control">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
