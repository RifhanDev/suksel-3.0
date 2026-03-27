@extends('layouts.v3.master')

@section('content')

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Laporan Pendaftaran Syarikat</h3>
            <p class="text-muted small m-0">Jana laporan pendaftaran syarikat mengikut daerah sistem tender online.</p>
        </div>
    </div>

    <div class="content-card p-4">
        <form action="" method="POST" target="_blank">
            @csrf

            <div class="mb-4">
                <label for="type" class="form-label fw-semibold">Carian Mengikut</label>
                <select name="type" id="type" class="form-select">
                    <option value="active" selected>Syarikat Aktif</option>
                    <option value="register">Pendaftaran</option>
                    <option value="update">Kemaskini</option>
                </select>
            </div>

            <div>
                <button type="submit" class="btn-form btn-form-primary">Jana Laporan</button>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/report-date.js') }}"></script>
@endsection
