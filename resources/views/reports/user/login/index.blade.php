@extends('layouts.v3.master')

@section('content')

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Laporan Sebagai</h3>
            <p class="text-muted small m-0">Jana laporan log masuk pengguna sistem tender online.</p>
        </div>
    </div>

    <div class="content-card p-4" style="overflow: visible;">
        <form action="{{ action('ReportUserLoginController@view') }}" method="POST" target="_blank">
            @csrf

            <div class="mb-4">
                <label for="users" class="form-label fw-semibold">Pengguna <span class="text-danger">*</span></label>
                <select class="selectize" required id="users" name="user_id">
                    <option value=""></option>
                    @foreach ($select_users as $s_user)
                        <option value="{{ $s_user->id }}">{{ $s_user->name }} &lt;{{ $s_user->email }}&gt;</option>
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
