@extends('layouts.v3.master')

@section('content')

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Laporan Permohonan Kemaskini Maklumat Syarikat</h3>
            <p class="text-muted small m-0">Jana laporan permohonan kemaskini maklumat syarikat sistem tender online.</p>
        </div>
    </div>

    <div class="content-card p-4">
        <form action="" method="POST" target="_blank">
            @csrf

            <div class="mb-3">
                <label for="type" class="form-label fw-semibold">Carian Mengikut</label>
                <select name="type" id="type" class="form-select" onchange="inputSelector(this.value)">
                    <option value="year" selected>Tahunan</option>
                    <option value="month">Bulan</option>
                    <option value="week">Mingguan</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Tarikh <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="year" id="year" value="">
                <input type="month" class="form-control" name="month" id="month" value="" onfocus="this.showPicker()" style="display: none;">
                <input type="week" class="form-control" name="week" id="week" value="" onfocus="this.showPicker()" style="display: none;">
            </div>

            <div>
                <button type="submit" class="btn-form btn-form-primary">Jana Laporan</button>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/report-date.js') }}"></script>
asset('js/report-date.js') }}"></script>
asset('js/report-date.js') }}"></script>
@endsection
