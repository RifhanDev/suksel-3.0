@extends('layouts.v3.master')

@section('content')

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Syarikat Mengikut Daerah</h3>
            <p class="text-muted small m-0">Jana laporan senarai syarikat mengikut daerah sistem tender online.</p>
        </div>
    </div>

    <div class="content-card p-4" style="overflow: visible;">
        <form action="{{ action('ReportVendorDistrictController@view') }}" method="GET" target="_blank">

            <div class="mb-4">
                <label for="district" class="form-label fw-semibold">Daerah <span class="text-danger">*</span></label>
                <select name="district" id="district" class="selectize" required>
                    <option value="">Pilih daerah...</option>
                    <option value="all">Semua</option>
                    @foreach (App\Vendor::$districts as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="btn-form btn-form-primary">Jana Laporan</button>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        $('.selectize').each(function() {
            $(this).selectize();
        });
    </script>
@endsection
