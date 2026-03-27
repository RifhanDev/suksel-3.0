@extends('layouts.v3.master')

@section('content')

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Laporan Transaksi Harian Gateway</h3>
            <p class="text-muted small m-0">Jana laporan transaksi harian gateway sistem tender online.</p>
        </div>
    </div>

    <div class="content-card p-4">
        <form action="{{ action('ReportGatewayDailyController@view') }}" method="POST" target="_blank">
            @csrf

            <div class="mb-3">
                <label for="gateway_id" class="form-label fw-semibold">Gateway <span class="text-danger">*</span></label>
                <select name="gateway_id" id="gateway_id" class="form-select" required>
                    <option value="">Pilihan gateway...</option>
                    @foreach ($select_gateways as $group => $options)
                        <optgroup label="{{ $group }}">
                            @foreach ($options as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="date" class="form-label fw-semibold">Tarikh <span class="text-danger">*</span></label>
                <input type="text" name="date" id="date" class="form-control" required>
            </div>

            <div class="mb-4">
                <label for="time" class="form-label fw-semibold">Waktu Akhir</label>
                <input type="text" name="time" id="time" class="form-control">
            </div>

            <div>
                <button type="submit" class="btn-form btn-form-primary">Jana Laporan</button>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('input[name=date]').datepicker({
                format: 'yyyy-mm-dd',
                maxDate: new Date()
            });
        });
    </script>
@endsection
