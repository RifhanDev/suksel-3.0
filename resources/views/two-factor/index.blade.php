@extends('layouts.v3.master')

@section('content')
    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Pengurusan Pengesahan Dua Faktor</h3>
            <p class="text-muted small m-0">Tetapkan peranan yang diwajibkan, tempoh tangguh dan status pendaftaran pengguna.</p>
        </div>
    </div>

    {{-- success/error flashes are already rendered globally by layouts._notification --}}

    <!-- 1. ROLE REQUIREMENT TOGGLES -->
    <div class="content-card mb-4">
        <div class="content-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    </svg>
                </div>
                <h3 class="content-card-title">Peranan Yang Diwajibkan 2FA</h3>
            </div>
        </div>

        <div class="content-card-body p-4">
            <p class="text-muted small mb-3">
                Pengguna dengan peranan yang ditanda perlu mendaftar pengesahan dua faktor.
                Mereka diberi tempoh tangguh sebelum disekat daripada menggunakan sistem.
            </p>

            <form method="POST" action="{{ route('two-factor.roles.update') }}">
                @csrf
                @method('PUT')

                <div class="row g-2 mb-4">
                    @foreach ($roles as $role)
                        <div class="col-md-4 col-sm-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="required_roles[]"
                                       value="{{ $role->id }}" id="role_{{ $role->id }}"
                                       {{ in_array($role->id, $requiredRoleIds) ? 'checked' : '' }}>
                                <label class="form-check-label" for="role_{{ $role->id }}">
                                    {{ $role->display_name ?: $role->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="btn-form btn-form-create">Simpan Tetapan Peranan</button>
            </form>
        </div>
    </div>

    <!-- 2. GLOBAL SETTINGS -->
    <div class="content-card mb-4">
        <div class="content-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.6a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                    </svg>
                </div>
                <h3 class="content-card-title">Tetapan Umum</h3>
            </div>
        </div>

        <div class="content-card-body p-4">
            <form method="POST" action="{{ route('two-factor.settings.update') }}">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-uppercase text-muted">Tempoh Tangguh Setup 2FA (hari)</label>
                        <input type="number" class="form-control" name="grace_period_days" min="0" max="365"
                               value="{{ old('grace_period_days', $settings->grace_period_days) }}" required>
                        <small class="text-muted">Tempoh untuk pengguna menetapkan 2FA sebaik sahaja akaun didaftarkan atau sebaik sahaja peranan diwajibkan 2FA.</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-uppercase text-muted">Bilangan Kod Pemulihan</label>
                        <input type="number" class="form-control" name="recovery_codes_count" min="1" max="20"
                               value="{{ old('recovery_codes_count', $settings->recovery_codes_count) }}" required>
                        <small class="text-muted">Kod pemulihan. Kod-kod ni untuk situasi pengguna hilang/tukar telefon dan tidak dapat mengakses authenticator app</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-uppercase text-muted">Ingat Peranti (hari)</label>
                        <input type="number" class="form-control" name="remember_device_days" min="1" max="365"
                               value="{{ old('remember_device_days', $settings->remember_device_days) }}" required>
                        <small class="text-muted">Tempoh peranti dipercayai sebelum kod diminta semula.</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-uppercase text-muted">Maksimum Percubaan Gagal</label>
                        <input type="number" class="form-control" name="max_failed_attempts" min="1" max="20"
                               value="{{ old('max_failed_attempts', $settings->max_failed_attempts) }}" required>
                        <small class="text-muted">Percubaan kod salah sebelum akaun dikunci sementara.</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-uppercase text-muted">Tempoh Kunci (minit)</label>
                        <input type="number" class="form-control" name="lockout_minutes" min="1" max="1440"
                               value="{{ old('lockout_minutes', $settings->lockout_minutes) }}" required>
                        <small class="text-muted">Tempoh menunggu selepas terlalu banyak percubaan gagal.</small>
                    </div>
                </div>

                <button type="submit" class="btn-form btn-form-create">Simpan Tetapan Umum</button>
            </form>
        </div>
    </div>

    <!-- 3. USER ENROLMENT STATUS -->
    <div class="content-card mb-4">
        <div class="content-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        <path d="M9 12l2 2 4-4"></path>
                    </svg>
                </div>
                <h3 class="content-card-title">Status Pendaftaran Pengguna</h3>
            </div>
        </div>

        <div class="content-card-body p-3">
            <div class="table-responsive">
                <table data-path="{{ route('two-factor.index') }}"
                       class="DT-two-factor-users table table-hover align-middle mb-0 w-100">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-muted small fw-bold py-3 ps-4">Nama</th>
                            <th class="text-uppercase text-muted small fw-bold py-3">Emel</th>
                            <th class="text-uppercase text-muted small fw-bold py-3">Peranan</th>
                            <th class="text-uppercase text-center text-muted small fw-bold py-3" style="width: 120px;">Status</th>
                            <th class="text-uppercase text-center text-muted small fw-bold py-3" style="width: 150px;">Tarikh Daftar</th>
                            <th class="text-uppercase text-center text-muted small fw-bold py-3 pe-4" style="width: 140px;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 4. AUDIT LOG -->
    <div class="content-card">
        <div class="content-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                </div>
                <h3 class="content-card-title">Log Audit 2FA</h3>
            </div>
        </div>

        <div class="content-card-body p-3">
            <div class="table-responsive">
                <table data-path="{{ route('two-factor.audit') }}"
                       class="DT-two-factor-audit table table-hover align-middle mb-0 w-100">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-muted small fw-bold py-3 ps-4" style="width: 150px;">Masa</th>
                            <th class="text-uppercase text-muted small fw-bold py-3">Pengguna</th>
                            <th class="text-uppercase text-muted small fw-bold py-3">Dilakukan Oleh</th>
                            <th class="text-uppercase text-muted small fw-bold py-3" style="width: 200px;">Peristiwa</th>
                            <th class="text-uppercase text-muted small fw-bold py-3 pe-4">Butiran</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        var dtLanguage = {
            sEmptyTable: "Tiada data",
            sInfo: "Paparan dari _START_ hingga _END_ dari _TOTAL_ rekod",
            sInfoEmpty: "Paparan 0 hingga 0 dari 0 rekod",
            sInfoFiltered: "(Ditapis dari jumlah _MAX_ rekod)",
            sLengthMenu: "Papar _MENU_ rekod",
            sLoadingRecords: "Diproses...",
            sProcessing: "Sedang diproses...",
            sSearch: "Carian:",
            sZeroRecords: "Tiada padanan rekod yang dijumpai.",
            oPaginate: {
                sFirst: "Pertama",
                sPrevious: "Sebelum",
                sNext: "Kemudian",
                sLast: "Akhir"
            }
        };

        $('.DT-two-factor-users').each(function() {
            var target = $(this);

            target.DataTable({
                ajax: target.data('path'),
                columns: [{
                        data: 'name',
                        name: 'users.name'
                    },
                    {
                        data: 'email',
                        name: 'users.email'
                    },
                    {
                        data: 'roles',
                        name: 'roles',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'confirmed_at',
                        name: 'two_factor_auths.confirmed_at',
                        className: 'text-center'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    }
                ],
                serverSide: true,
                stateSave: true,
                pageLength: 25,
                language: dtLanguage,
                aaSorting: []
            });
        });

        $('.DT-two-factor-audit').each(function() {
            var target = $(this);

            target.DataTable({
                ajax: target.data('path'),
                columns: [{
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'user_name',
                        name: 'user_name',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actor_name',
                        name: 'actor_name',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'event',
                        name: 'event'
                    },
                    {
                        data: 'meta_summary',
                        name: 'meta_summary',
                        orderable: false,
                        searchable: false
                    }
                ],
                serverSide: true,
                stateSave: true,
                pageLength: 25,
                language: dtLanguage,
                aaSorting: []
            });
        });
    </script>
@endsection
