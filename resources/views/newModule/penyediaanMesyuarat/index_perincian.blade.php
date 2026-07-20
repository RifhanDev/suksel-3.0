@extends('layouts.v3.master')

@section('styles')
<style>
    .stats-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }
    .stats-card-header {
        padding: 20px 24px;
        background: #fff;
        border-bottom: 1px solid #f1f5f9;
    }
    .stats-card-title {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
    }
    .table-modern thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        padding: 14px 20px;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }
    .table-modern tbody td {
        padding: 16px 20px;
        vertical-align: middle;
        color: #334155;
        font-size: 0.9rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .table-modern tbody tr:hover { background-color: #fff9f9; }
</style>
@endsection

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Perincian Mesyuarat</h3>
            <p class="text-muted small m-0">Senarai tender / sebut harga untuk penyediaan perincian mesyuarat.</p>
        </div>
    </div>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="get" action="{{ route('perincianMesyuarat') }}" class="card border shadow-sm mb-2 rounded-3">
        <div class="card-body p-3">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-lg-2">
                    <label for="filter_no_tender" class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Tender</label>
                    <input type="text" name="no_tender" id="filter_no_tender" class="form-control form-control-sm"
                        placeholder="Cth: JPS/01" value="{{ request('no_tender') }}">
                </div>
                <div class="col-12 col-lg-4">
                    <label for="filter_tajuk" class="form-label small fw-bold text-secondary text-uppercase mb-1">Tajuk Perolehan</label>
                    <input type="text" name="tajuk" id="filter_tajuk" class="form-control form-control-sm"
                        placeholder="Cari tajuk projek..." value="{{ request('tajuk') }}">
                </div>
                <div class="col-6 col-lg-2">
                    <label for="filter_status" class="form-label small fw-bold text-secondary text-uppercase mb-1">Status</label>
                    <input type="text" name="status" id="filter_status" class="form-control form-control-sm"
                        placeholder="Cth: Dalam Proses" value="{{ request('status') }}">
                </div>
                <div class="col-6 col-lg-2">
                    <label for="filter_tarikh" class="form-label small fw-bold text-secondary text-uppercase mb-1">Tarikh Jual</label>
                    <input type="text" name="tarikh" id="filter_tarikh" class="form-control form-control-sm"
                        placeholder="dd/mm/yyyy" value="{{ request('tarikh') }}">
                </div>
                <div class="col-12 col-lg-2">
                    <div class="d-flex gap-2">
                        <a href="{{ route('perincianMesyuarat') }}" class="btn btn-md btn-light border w-100">Reset</a>
                        <button type="submit" class="btn btn-md btn-selangor fw-medium w-100">Tapis</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="stats-card mb-4">
        <div class="stats-card-header">
            <h3 class="stats-card-title">Senarai Tender / Sebut Harga</h3>
        </div>
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-modern w-100 mb-0">
                    <thead>
                        <tr>
                            <th>No. Tender / Sebut Harga</th>
                            <th>Tajuk Perolehan</th>
                            <th class="text-center" width="120px">Tarikh Jual</th>
                            <th class="text-center" width="120px">Tarikh Tutup</th>
                            <th class="text-center" width="120px">Status</th>
                            <th class="text-center" width="120px">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tenders as $item)
                            <tr>
                                <td class="fw-semibold">{{ $item['no_tender'] }}</td>
                                <td>{{ $item['name'] }}</td>
                                <td class="text-center">{{ $item['tarikh_jual'] }}</td>
                                <td class="text-center">{{ $item['tarikh_tutup'] }}</td>
                                <td class="text-center">{{ $item['status'] }}</td>
                                <td class="text-center">
                                    <a href="{{ route('perincianPage', ['tender' => $item['uuid']]) }}" class="btn btn-sm btn-info text-white">
                                        Kemaskini
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Tiada tender / sebut harga dalam peringkat penyediaan mesyuarat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
