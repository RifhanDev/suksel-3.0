@extends('layouts.v3.master')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Penyediaan Iklan</h3>
            <p class="text-muted small m-0">Senarai tender dengan status proses 4 (selesai spesifikasi kewangan), sedia untuk penyediaan iklan.</p>
        </div>
    </div>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="get" action="{{ route('penyediaanIklan.index') }}" class="card border shadow-sm mb-3 rounded-3">
        <div class="card-body p-3">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-lg-2">
                    <label for="filter_no_tender" class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Tender / Sebut Harga</label>
                    <input type="text" name="no_tender" id="filter_no_tender" class="form-control form-control-sm" placeholder="Cth: JPS/01" value="{{ request('no_tender') }}">
                </div>
                <div class="col-12 col-lg-4">
                    <label for="filter_tajuk" class="form-label small fw-bold text-secondary text-uppercase mb-1">Tajuk Perolehan</label>
                    <input type="text" name="tajuk" id="filter_tajuk" class="form-control form-control-sm" placeholder="Cari tajuk projek..." value="{{ request('tajuk') }}">
                </div>
                <div class="col-6 col-lg-2">
                    <label for="filter_status" class="form-label small fw-bold text-secondary text-uppercase mb-1">Status</label>
                    <select name="status" id="filter_status" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="penyediaan_iklan" @selected(request('status') === 'penyediaan_iklan')>Penyediaan Iklan</option>
                    </select>
                </div>
                <div class="col-6 col-lg-2">
                    <label for="filter_tarikh" class="form-label small fw-bold text-secondary text-uppercase mb-1">Tarikh</label>
                    <input type="text" name="tarikh" id="filter_tarikh" class="form-control form-control-sm datepicker" placeholder="dd/mm/yyyy" value="{{ request('tarikh') }}">
                </div>
                <div class="col-12 col-lg-2">
                    <div class="d-flex gap-2">
                        <a href="{{ route('penyediaanIklan.index') }}" class="btn btn-sm btn-light border w-100">Reset</a>
                        <button type="submit" class="btn btn-sm btn-selangor w-100">Tapis</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="bg-white rounded overflow-hidden mb-4 shadow-sm border">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:0.85rem;">
                <thead class="table-light">
                    <tr>
                        <th>No. Tender</th>
                        <th>Tajuk Perolehan</th>
                        <th>PTJ</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tenders as $row)
                        <tr>
                            <td class="fw-semibold text-danger">{{ $row['no_tender'] }}</td>
                            <td>{{ $row['tajuk'] }}</td>
                            <td class="small text-muted">{{ $row['ptj'] }}</td>
                            <td><span class="badge bg-warning text-dark">{{ $row['status_label'] }}</span></td>
                            <td class="text-end">
                                <a href="{{ $row['show_url'] }}" class="btn btn-sm btn-selangor">Sedia Iklan</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                Tiada tender dalam peringkat Penyediaan Iklan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                clearBtn: true
            });
        });
    </script>
@endsection
