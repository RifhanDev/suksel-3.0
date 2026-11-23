@extends('layouts.v3.master')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Penyediaan Iklan</h3>
            <p class="text-muted small m-0">Senarai tender yang sedang disediakan atau menunggu Ketua Jabatan siarkan iklan.</p>
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
                        <option value="penyediaan_iklan" @selected(request('status') === 'penyediaan_iklan')>Penyediaan Iklan / Menunggu Siar</option>
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

    <div class="content-card p-0 mb-4">
        <div class="content-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon" style="width: 38px; height: 38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                </div>
                <h3 class="content-card-title" style="font-size: 1rem;">Senarai Tender</h3>
            </div>
        </div>

        <div class="content-card-body p-2">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 w-100">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-muted small fw-bold py-3 ps-4" width="200px">No. Tender</th>
                            <th class="text-uppercase text-muted small fw-bold py-3">Tajuk Perolehan</th>
                            <th class="text-uppercase text-muted small fw-bold py-3">PTJ</th>
                            <th class="text-uppercase text-center text-muted small fw-bold py-3" width="160px">Status</th>
                            <th class="py-3 pe-4" width="140px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tenders as $row)
                            <tr>
                                <td class="ps-4 fw-semibold text-danger">{{ $row['no_tender'] }}</td>
                                <td>{{ $row['tajuk'] }}</td>
                                <td class="text-muted small">{{ $row['ptj'] }}</td>
                                <td class="text-center">
                                    <span class="badge-status {{ $row['status_label'] === 'Menunggu Siar' ? 'badge-status-info' : 'badge-status-warning' }}">{{ $row['status_label'] }}</span>
                                </td>
                                <td class="text-center pe-4">
                                    <a href="{{ $row['show_url'] }}" class="btn btn-sm btn-info text-white d-inline-flex align-items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 20h9"></path>
                                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                        </svg>
                                        Sedia Iklan
                                    </a>
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
