@extends('layouts.v3.master')

@section('styles')
    <style>
        .card-form-compact {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
            /* overflow: hidden; Removed to allow dropdowns to overflow */
        }

        .card-form-header {
            padding: 15px 20px;
            border-bottom: 1px solid #f1f5f9;
            background: #fff;
        }

        .card-form-body {
            padding: 20px;
        }
    </style>
@endsection

@section('content')
    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <!-- Title -->
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Pendaftaran Pengguna Baru</h3>
            <p class="text-muted small m-0">Sila lengkapkan maklumat di bawah.</p>
        </div>
    </div>

    <form action="{{ url('users') }}" method="POST">
        @csrf

        <div class="modern-card">

            <div id="step1-content">
                <!-- Header -->
                <div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17">
                            </polyline>
                            <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span class="fw-bold text-dark text-uppercase small">Maklumat Pengguna</span>
                </div>

                <div class="p-4">
                    <!-- Alert -->
                    <div class="alert-selangor mb-4">
                        <div class="alert-selangor-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                                </path>
                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                        </div>
                        <div class="small lh-sm">
                            <strong>Perhatian</strong>
                            Sila isikan maklumat pengguna sistem yang baru dengan tepat. Kata laluan mestilah
                            sekurang-kurangnya 8 aksara dan mengandungi satu simbol, nombor, huruf besar dan kecil.
                        </div>
                    </div>

                    @include('users.form')

                    <div class="row g-3">
                        <div class="col-12">
                            <hr class="text-muted opacity-25 my-2">
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label fw-medium small">Kata Laluan <span
                                    class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            {!! $errors->first('password', '<div class="text-danger small mt-1">:message</div>') !!}
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label fw-medium small">Sahkan Kata Laluan
                                <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" required>
                        </div>
                    </div>
                </div>

                <!-- FOOTER ACTIONS -->
                <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light rounded-bottom">
                    <a href="{{ asset('users') }}" class="btn-form btn-form-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        Batal
                    </a>
                    <button type="submit" class="btn-form btn-form-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Simpan
                    </button>
                </div>

            </div>
        </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#roles').selectize({
                plugins: ['remove_button'],
            });

            if ($('#organization_unit_id').length) {
                $('#organization_unit_id').selectize();
            }
        });
    </script>
@endsection
