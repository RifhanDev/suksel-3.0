@extends('layouts.v3.master')

@section('content')

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Status Pengguna Mengikut Agensi</h3>
            <p class="text-muted small m-0">Jana laporan senarai status pengguna mengikut agensi sistem tender online.</p>
        </div>
    </div>

    <div class="content-card p-4" style="overflow: visible;">
        <form action="{{ action('ReportUserActiveController@view') }}" method="POST" target="_blank">
            @csrf

            @if (Auth::user()->can('Report:view:user_agency'))
                <div class="mb-4">
                    <label for="agency" class="form-label fw-semibold">Agensi <span class="text-danger">*</span></label>
                    <select name="agency" id="agency" class="selectize" required>
                        <option value="">Pilih agensi...</option>
                        @foreach ($select_agencies as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

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
