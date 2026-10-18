@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/tabs.css') }}" rel="stylesheet">
    <style>
        .tfp-step { display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; background: #f1f5f9; color: #475569; font-size: .75rem; font-weight: 700; margin-right: .5rem; }
        .tfp-section + .tfp-section { border-top: 1px solid #eef2f6; padding-top: 1.5rem; margin-top: 1.5rem; }
        .tfp-kategori summary { cursor: pointer; }
        .tf-audit-label { min-width: 4.5rem; }
    </style>
@endsection

@section('content')
    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Pengurusan Pengesahan Dua Faktor</h3>
            <p class="text-muted small m-0">Tetapkan peranan yang diwajibkan, tempoh tangguh dan status 2FA pengguna.</p>
        </div>
    </div>

    {{-- success/error flashes are already rendered globally by layouts._notification --}}

    <ul class="nav segmented-tabs mb-4 flex-wrap" role="tablist" id="tfTabs">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" href="#tab-login" data-bs-toggle="tab" role="tab" aria-controls="tab-login" aria-selected="true">Log Masuk</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" href="#tab-penilaian" data-bs-toggle="tab" role="tab" aria-controls="tab-penilaian" aria-selected="false">Penilaian</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" href="#tab-pengguna" data-bs-toggle="tab" role="tab" aria-controls="tab-pengguna" aria-selected="false">Pengguna</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" href="#tab-audit" data-bs-toggle="tab" role="tab" aria-controls="tab-audit" aria-selected="false">Log Audit</a>
        </li>
    </ul>

    <div class="tab-content">
    <!-- TAB: LOG MASUK -->
    <div class="tab-pane fade show active" id="tab-login" role="tabpanel">

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
                Pengguna dengan peranan yang ditanda perlu mengaktifkan pengesahan dua faktor.
                Mereka diberi tempoh tangguh sebelum disekat daripada menggunakan sistem.
            </p>

            <form method="POST" action="{{ route('two-factor.roles.update') }}" id="tfRolesForm">
                @csrf
                @method('PUT')

                <div class="row g-2 mb-4">
                    @foreach ($roles as $role)
                        <div class="col-md-4 col-sm-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="required_roles[]"
                                       value="{{ $role->id }}" id="role_{{ $role->id }}"
                                       @if ($penilaianRoles->has($role->id)) data-penilaian-role="{{ $role->display_name ?: $role->name }}" @endif
                                       {{ in_array($role->id, $requiredRoleIds) ? 'checked' : '' }}>
                                <label class="form-check-label" for="role_{{ $role->id }}">
                                    {{ $role->display_name ?: $role->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-end border-top pt-4">
                    <button type="submit" class="btn-form btn-form-primary">Simpan Tetapan Peranan</button>
                </div>
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

                <div class="d-flex justify-content-end border-top pt-4">
                    <button type="submit" class="btn-form btn-form-primary">Simpan Tetapan Umum</button>
                </div>
            </form>
        </div>
    </div>

    </div>{{-- /tab-login --}}

    <!-- TAB: HANTAR PENILAIAN -->
    <div class="tab-pane fade" id="tab-penilaian" role="tabpanel">

    <div class="content-card mb-4">
        <div class="content-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <h3 class="content-card-title">Pengesahan 2FA semasa Hantar Penilaian</h3>
            </div>
        </div>

        <div class="content-card-body p-4">
            <p class="text-muted small mb-4">
                Kod daripada aplikasi pengesah diminta sebelum butang Hantar terakhir diproses, bagi peringkat dan peranan yang ditanda.
                Pengguna yang belum mengaktifkan 2FA akan diminta mengaktifkannya dahulu.
            </p>

            <form method="POST" action="{{ route('two-factor.penilaian.update') }}" id="tfpForm">
                @csrf
                @method('PUT')

                <!-- Step 1 -->
                <div class="tfp-section">
                    <div class="fw-semibold mb-1"><span class="tfp-step">1</span>Peringkat</div>
                    <p class="text-muted small mb-3 ms-4 ps-2">Peringkat yang butang Hantar terakhirnya meminta kod.</p>
                    <div class="row g-2 ms-4">
                        @foreach ($penilaianPeringkat as $key => $label)
                            <div class="col-md-3 col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="peringkat[]"
                                           value="{{ $key }}" id="tfp_peringkat_{{ $key }}"
                                           {{ in_array($key, $penilaianRequired, true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tfp_peringkat_{{ $key }}">{{ $label }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="tfp-section">
                    <div class="fw-semibold mb-1"><span class="tfp-step">2</span>Peranan</div>
                    <p class="text-muted small mb-3 ms-4 ps-2">
                        Peranan boleh ditanda jika diwajibkan 2FA di tab Log Masuk dan sekurang-kurangnya satu peringkatnya ditanda di langkah 1.
                    </p>
                    <div class="ms-4">
                        @forelse ($penilaianEligible as $role)
                            @php
                                $peringkatLabels = collect($role->peringkat)
                                    ->map(fn ($key) => $penilaianPeringkat[$key])
                                    ->implode(' · ');
                                $loginRequired = in_array($role->id, $requiredRoleIds);
                            @endphp
                            <div class="form-check mb-2">
                                <input class="form-check-input tfp-role-check" type="checkbox" name="roles[]"
                                       value="{{ $role->id }}" id="tfp_role_{{ $role->id }}"
                                       data-peringkat='@json($role->peringkat)'
                                       data-login-required="{{ $loginRequired ? 1 : 0 }}"
                                       {{ $loginRequired && $penilaianRoles->has($role->id) ? 'checked' : '' }}>
                                <label class="form-check-label" for="tfp_role_{{ $role->id }}">
                                    {{ $role->display_name ?: $role->name }}
                                    @if ($loginRequired)
                                        <span class="text-muted small ms-2">{{ $peringkatLabels }}</span>
                                    @endif
                                </label>
                                @unless ($loginRequired)
                                    <button type="button" class="btn-action btn-action-blue py-1 px-2 ms-2 js-tf-open-login" data-role="{{ $role->id }}">
                                        Tetapkan di Log Masuk
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                    </button>
                                @endunless
                            </div>
                        @empty
                            <p class="text-muted small fst-italic mb-0">Tiada peranan mempunyai kebenaran penilaian.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="tfp-section">
                    <div class="fw-semibold mb-1"><span class="tfp-step">3</span>Skop Agensi</div>
                    <p class="text-muted small mb-3 ms-4 ps-2">Untuk setiap peranan yang ditanda, pilih semua agensi atau hadkan kepada kategori / agensi tertentu.</p>

                    <div class="ms-4">
                        <p class="tfp-scope-empty text-muted small fst-italic mb-0" @if ($penilaianRoles->isNotEmpty()) hidden @endif>
                            Tiada peranan ditanda. Tandakan peranan di langkah 2 dahulu.
                        </p>

                        @foreach ($penilaianEligible as $role)
                            @php
                                $rule = $penilaianRoles->get($role->id);
                                $pilih = $rule && !$rule->semua_agensi;
                                $pickedKategori = $rule ? $rule->kategoriAgensi->pluck('organization_type_id')->map(fn ($id) => (int) $id)->all() : [];
                                $pickedAgensi = $rule ? $rule->agensi->pluck('organization_unit_id')->map(fn ($id) => (int) $id)->all() : [];
                            @endphp
                            <div class="tfp-scope border rounded-3 px-3 py-2 mb-2" data-role="{{ $role->id }}" @unless ($rule) hidden @endunless>
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                    <span class="fw-semibold">{{ $role->display_name ?: $role->name }}</span>
                                    <div class="d-flex flex-wrap gap-4">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input tfp-skop" type="radio" name="skop[{{ $role->id }}]"
                                                   value="semua" id="tfp_skop_semua_{{ $role->id }}" {{ $pilih ? '' : 'checked' }}>
                                            <label class="form-check-label small" for="tfp_skop_semua_{{ $role->id }}">Semua agensi</label>
                                        </div>
                                        <div class="form-check mb-0">
                                            <input class="form-check-input tfp-skop" type="radio" name="skop[{{ $role->id }}]"
                                                   value="pilih" id="tfp_skop_pilih_{{ $role->id }}" {{ $pilih ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="tfp_skop_pilih_{{ $role->id }}">Pilih kategori / agensi</label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Filled from #tfpPickerTemplate by JS only when "Pilih" is used, so the agensi list is printed once. --}}
                                <div class="tfp-pick mt-2" data-role="{{ $role->id }}"
                                     data-kategori='@json($pickedKategori)' data-agensi='@json($pickedAgensi)'
                                     @unless ($pilih) hidden @endunless></div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <template id="tfpPickerTemplate">
                    @foreach ($kategoriAgensi as $kategori)
                        @php $units = $agensiByKategori->get($kategori->id, collect()); @endphp
                        <details class="tfp-kategori border-start ps-3 py-1">
                            <summary class="small">
                                <span class="form-check d-inline-block mb-0">
                                    <input class="form-check-input tfp-kategori-check" type="checkbox"
                                           name="kategori[__ROLE__][]" value="{{ $kategori->id }}" id="tfp_kat___ROLE___{{ $kategori->id }}">
                                    <label class="form-check-label" for="tfp_kat___ROLE___{{ $kategori->id }}">{{ $kategori->name }}</label>
                                </span>
                                <span class="text-muted ms-1 tfp-kategori-note"></span>
                            </summary>
                            <div class="row g-1 ps-4 py-2">
                                @forelse ($units as $unit)
                                    <div class="col-lg-4 col-md-6"><div class="form-check">
                                        <input class="form-check-input tfp-agensi-check" type="checkbox"
                                               name="agensi[__ROLE__][]" value="{{ $unit->id }}" id="tfp_ag___ROLE___{{ $unit->id }}">
                                        <label class="form-check-label small" for="tfp_ag___ROLE___{{ $unit->id }}">{{ $unit->name }}</label>
                                    </div></div>
                                @empty
                                    <div class="col-12 text-muted small">Tiada agensi dalam kategori ini.</div>
                                @endforelse
                            </div>
                        </details>
                    @endforeach
                </template>

                <div class="d-flex justify-content-end border-top pt-4 mt-4">
                    <button type="submit" class="btn-form btn-form-primary">Simpan Tetapan Penilaian</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 5. PENILAIAN HANTAR LOG -->
    <div class="content-card">
        <div class="content-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        <path d="M9 12l2 2 4-4"></path>
                    </svg>
                </div>
                <h3 class="content-card-title">Log Pengesahan Penilaian</h3>
            </div>
        </div>

        <div class="content-card-body p-3">
            <div class="table-responsive">
                <table data-path="{{ route('two-factor.penilaian.logs') }}"
                       class="DT-two-factor-penilaian table table-hover align-middle mb-0 w-100">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-muted small fw-bold py-3 ps-4" style="width: 150px;">Masa</th>
                            <th class="text-uppercase text-muted small fw-bold py-3">Pengguna</th>
                            <th class="text-uppercase text-muted small fw-bold py-3">No. Perolehan</th>
                            <th class="text-uppercase text-muted small fw-bold py-3">Peringkat</th>
                            <th class="text-uppercase text-muted small fw-bold py-3 pe-4" style="width: 170px;">Kaedah</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    </div>{{-- /tab-penilaian --}}

    <!-- TAB: PENGGUNA -->
    <div class="tab-pane fade" id="tab-pengguna" role="tabpanel">

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
                <h3 class="content-card-title">Status 2FA Pengguna</h3>
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
                            <th class="text-uppercase text-center text-muted small fw-bold py-3" style="width: 150px;">Tarikh Diaktifkan</th>
                            <th class="text-uppercase text-center text-muted small fw-bold py-3 pe-4" style="width: 140px;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    </div>{{-- /tab-pengguna --}}

    <!-- TAB: LOG AUDIT -->
    <div class="tab-pane fade" id="tab-audit" role="tabpanel">

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
                            <th class="text-uppercase text-muted small fw-bold py-3 ps-4" style="width: 140px;">Masa</th>
                            <th class="text-uppercase text-muted small fw-bold py-3" style="width: 14%;">Pengguna</th>
                            <th class="text-uppercase text-muted small fw-bold py-3" style="width: 14%;">Dilakukan Oleh</th>
                            <th class="text-uppercase text-muted small fw-bold py-3" style="width: 210px;">Peristiwa</th>
                            <th class="text-uppercase text-muted small fw-bold py-3 pe-4">Butiran</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    </div>{{-- /tab-audit --}}
    </div>{{-- /tab-content --}}
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
                // The table starts in a hidden tab, so measured widths would be wrong; use the <th> widths instead.
                autoWidth: false,
                columns: [{
                        data: 'created_at',
                        name: 'created_at',
                        className: 'text-nowrap'
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
                        name: 'event',
                        className: 'text-nowrap'
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

        $('.DT-two-factor-penilaian').each(function() {
            var target = $(this);

            target.DataTable({
                ajax: target.data('path'),
                columns: [
                    { data: 'created_at', name: 'created_at' },
                    { data: 'user_name', name: 'user_name', orderable: false, searchable: false },
                    { data: 'tender_no', name: 'tender_no', orderable: false, searchable: false },
                    { data: 'peringkat', name: 'peringkat' },
                    { data: 'kaedah', name: 'kaedah' }
                ],
                serverSide: true,
                stateSave: true,
                pageLength: 25,
                language: dtLanguage,
                aaSorting: []
            });
        });

        // Penilaian Hantar card: show the scope only for ticked roles, the picker only for "Pilih".
        var tfpTemplate = document.getElementById('tfpPickerTemplate');

        // A ticked kategori covers all its agensi, so the single agensi boxes are locked.
        function tfpRefreshKategori($kategori) {
            var whole = $kategori.find('.tfp-kategori-check').prop('checked');
            var $units = $kategori.find('.tfp-agensi-check');

            $units.prop('disabled', whole);
            $kategori.find('.tfp-kategori-note').text(whole
                ? '(semua ' + $units.length + ' agensi)'
                : '(' + $units.filter(':checked').length + '/' + $units.length + ' agensi dipilih)');
        }

        // Copies the kategori/agensi list into a role the first time it is needed, ticking saved picks.
        function tfpFillPicker($pick) {
            if (!tfpTemplate || $pick.data('filled')) return;

            var roleId = String($pick.data('role'));
            $pick.html(tfpTemplate.innerHTML.split('__ROLE__').join(roleId)).data('filled', true);

            ($pick.data('kategori') || []).forEach(function(id) {
                $pick.find('.tfp-kategori-check[value="' + id + '"]').prop('checked', true);
            });
            ($pick.data('agensi') || []).forEach(function(id) {
                $pick.find('.tfp-agensi-check[value="' + id + '"]').prop('checked', true);
            });
            $pick.find('.tfp-kategori').each(function() { tfpRefreshKategori($(this)); });
        }

        $('#tfpForm .tfp-pick:not([hidden])').each(function() { tfpFillPicker($(this)); });

        // Step 2 tick shows that role's panel in step 3.
        $('#tfpForm').on('change', '.tfp-role-check', function() {
            $('#tfpForm .tfp-scope[data-role="' + this.value + '"]').prop('hidden', !this.checked);
            $('#tfpForm .tfp-scope-empty').prop('hidden', $('#tfpForm .tfp-role-check:checked').length > 0);
        });

        // Step 2 role is only tickable when it requires 2FA at login and one of its stages is ticked in step 1.
        function tfpSyncRolesWithPeringkat() {
            var tickedPeringkat = $('#tfpForm input[name="peringkat[]"]:checked').map(function() {
                return this.value;
            }).get();

            $('#tfpForm .tfp-role-check').each(function() {
                var rolePeringkat = $(this).data('peringkat') || [];
                var reachable = $(this).data('login-required') == 1
                    && rolePeringkat.some(function(key) { return tickedPeringkat.includes(key); });

                if (!reachable && this.checked) {
                    $(this).prop('checked', false).trigger('change');
                }

                $(this).prop('disabled', !reachable);
                // Grey the checkbox and name only, so a badge next to them stays readable.
                $(this).add($(this).siblings('label')).toggleClass('opacity-50', !reachable);
            });
        }

        $('#tfpForm').on('change', 'input[name="peringkat[]"]', tfpSyncRolesWithPeringkat);
        tfpSyncRolesWithPeringkat();

        // Takes the admin to that role's checkbox on the Log Masuk tab.
        $(document).on('click', '.js-tf-open-login', function() {
            var box = document.getElementById('role_' + $(this).data('role'));
            var $loginTab = $('#tfTabs a[href="#tab-login"]');

            $loginTab.one('shown.bs.tab', function() {
                box.scrollIntoView({ block: 'center' });
                box.focus();
            });
            $loginTab[0].click();
        });

        // Unticking a login role that Penilaian uses also removes its Penilaian setting, so ask first.
        $('#tfRolesForm').on('submit', function(e) {
            var form = this;
            var dropped = $(form).find('[data-penilaian-role]:not(:checked)').map(function() {
                return $(this).data('penilaian-role');
            }).get();

            if (!dropped.length) return;

            e.preventDefault();
            showConfirmModal({
                title: 'Peranan ini digunakan dalam Penilaian',
                message: dropped.join(', ') + ' juga ditetapkan untuk kod 2FA semasa Hantar Penilaian. Menyahtanda di sini akan membuang tetapan Penilaian bagi peranan ini.',
                icon: 'warning',
                confirmText: 'Ya, Teruskan'
            }).then(function(ok) {
                // form.submit() skips this handler, so it does not ask again.
                if (ok) form.submit();
            });
        });

        // Set Semula asks through the shared confirm modal instead of the browser confirm().
        $(document).on('click', '.js-tf-reset', function() {
            var form = this.closest('form');
            showConfirmModal({
                title: 'Set Semula 2FA?',
                message: $(this).data('name') + ' perlu mengaktifkan semula 2FA. Kod pemulihan sedia ada akan dibatalkan.',
                icon: 'danger',
                confirmText: 'Ya, Set Semula'
            }).then(function(ok) {
                if (ok) form.submit();
            });
        });

        // Keep the open tab in the URL hash so a save/refresh returns to it.
        var $tabLinks = $('#tfTabs a[data-bs-toggle="tab"]');
        $tabLinks.on('shown.bs.tab', function(e) {
            history.replaceState(null, '', $(e.target).attr('href'));
            $.fn.dataTable && $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
        if (location.hash) {
            var $initial = $tabLinks.filter('[href="' + location.hash + '"]');
            if ($initial.length) bootstrap.Tab.getOrCreateInstance($initial[0]).show();
        }

        $('#tfpForm').on('change', '.tfp-skop', function() {
            var $pick = $(this).closest('.tfp-scope').find('.tfp-pick');
            $pick.prop('hidden', this.value !== 'pilih');
            if (this.value === 'pilih') tfpFillPicker($pick);
        });

        $('#tfpForm').on('change', '.tfp-kategori-check, .tfp-agensi-check', function() {
            tfpRefreshKategori($(this).closest('.tfp-kategori'));
        });

        // Clicking the kategori checkbox should tick it, not open/close the <details>.
        $('#tfpForm').on('click', '.tfp-kategori summary .form-check', function(e) {
            if (!$(e.target).is('input')) {
                e.preventDefault();
                $(this).find('input').trigger('click');
            }
        });
    </script>
@endsection
