@extends('layouts.v3.master')

@section('content')

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Laporan Aktiviti Staf</h3>
            <p class="text-muted small m-0">Jana laporan aktiviti staf sistem tender online.</p>
        </div>
    </div>

    <div class="content-card p-4" style="overflow: visible;">
        <form action="{{ action('ReportUserActivityController@view') }}" method="POST" target="_blank">
            @csrf

            <div class="mb-3">
                <label for="users" class="form-label fw-semibold">Pengguna <span class="text-danger">*</span></label>
                <select class="selectize" multiple="multiple" required id="users" name="users[]">
                    <option value=""></option>
                    @foreach ($select_users as $s_user)
                        <option value="{{ $s_user->id }}">{{ $s_user->name }} &lt;{{ $s_user->email }}&gt;</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="date_start" class="form-label fw-semibold">Tarikh Mula <span class="text-danger">*</span></label>
                <input type="text" name="date_start" id="date_start" class="form-control datepicker" required>
            </div>

            <div class="mb-4">
                <label for="date_end" class="form-label fw-semibold">Tarikh Akhir <span class="text-danger">*</span></label>
                <input type="text" name="date_end" id="date_end" class="form-control datepicker" required>
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
        $('.datepicker').datepicker({
            format: 'd/m/yyyy'
        });
    </script>
@endsection
