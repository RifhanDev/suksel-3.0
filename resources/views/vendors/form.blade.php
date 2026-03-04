<script>
    // Global variables for the form
    var isAdmin = {{ Auth::user() && !Auth::user()->hasRole('Vendor') ? 'true' : 'false' }};
    var show = {{ strstr(Route::currentRouteName(), 'show') ? 'true' : 'false' }};
    var shareHolderTypes = ['Bumiputera', 'Bukan Bumiputera', 'Warga Asing'];
    var nationalities = @json(App\Vendor::$nationalities);
    @if (Request::old('shareholder'))
        var inputOldShareholdes = {{ json_encode(Request::old('shareholder')) }};
    @endif
</script>

<div data-show-mode="{{ strstr(Route::currentRouteName(), 'show') ? 'true' : 'false' }}">

    <!-- TABS NAV -->
    <div class="tabs-nav-wrapper">
        <ul class="modern-nav-tabs nav nav-tabs" role="tablist">
            <li class="nav-item active" role="presentation">
                <a class="nav-link active" href="#vf-main" data-bs-toggle="tab" role="tab" aria-controls="vf-main"
                    aria-selected="true">Syarikat</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#vf-officer" data-bs-toggle="tab" role="tab" aria-controls="vf-officer"
                    aria-selected="false">Pegawai</a>
            </li>

            <?php if(!isset($vendor->approval_1_id) || Auth::user()->can('Vendor:override')) : ?>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#vf-mof" data-bs-toggle="tab" role="tab" aria-controls="vf-mof"
                    aria-selected="false">MOF</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#vf-cidb" data-bs-toggle="tab" role="tab" aria-controls="vf-cidb"
                    aria-selected="false">CIDB</a>
            </li>
            <?php endif; ?>

            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#vf-shareholders" data-bs-toggle="tab" role="tab"
                    aria-controls="vf-shareholders" aria-selected="false">Pemegang Saham</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#vf-directors" data-bs-toggle="tab" role="tab"
                    aria-controls="vf-directors" aria-selected="false">Pengarah</a>
            </li>

            @if (isset($vendor) && $vendor->approval_1_id > 0)
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#vf-contacts" data-bs-toggle="tab" role="tab"
                        aria-controls="vf-contacts" aria-selected="false">Kakitangan</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#vf-awards" data-bs-toggle="tab" role="tab" aria-controls="vf-awards"
                        aria-selected="false">Anugerah</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#vf-assets" data-bs-toggle="tab" role="tab" aria-controls="vf-assets"
                        aria-selected="false">Aset</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#vf-projects" data-bs-toggle="tab" role="tab"
                        aria-controls="vf-projects" aria-selected="false">Projek</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#vf-products" data-bs-toggle="tab" role="tab"
                        aria-controls="vf-products" aria-selected="false">Produk</a>
                </li>
            @endif

            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#vf-files" data-bs-toggle="tab" role="tab" aria-controls="vf-files"
                    aria-selected="false">Fail</a>
            </li>
        </ul>
    </div>

    <!-- TAB CONTENT -->
    <div class="modern-tab-content tab-content">

        <!-- 1. MAKLUMAT SYARIKAT -->
        <div class="tab-pane active" id="vf-main">
            <!-- Tab Section Header -->
            <div class="tab-section-header">
                <div class="tab-section-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </div>
                <div class="tab-section-header-text">
                    <h3>Maklumat Syarikat</h3>
                    <p>Maklumat asas pendaftaran syarikat anda</p>
                </div>
            </div>

            <div class="form-grid-2">
                <!-- Left Column -->
                <div>
                    @if (!isset($vendor))
                        {!! Former::text('email')->label('Alamat Emel')->addClass('x-uppercase')->required() !!}
                        {!! Former::text('registration')->required()->placeholder("Aksara, Nombor dan tanda '-' Sahaja")->label('No. Pendaftaran') !!}
                    @else
                        <?php
                        $email = Former::text('email')->label('Alamat Emel')->addClass('x-uppercase')->forceValue($vendor->user->email);
                        if (!Auth::user()->hasRole('Admin')) {
                            $email = $email->disabled();
                        }
                        ?>
                        {!! $email !!}
                        <?php
                        $registration = Former::text('registration')->label('No Pendaftaran');
                        if (!Auth::user()->hasRole('Admin')) {
                            $registration = $registration->disabled();
                        }
                        ?>
                        {!! $registration !!}
                    @endif

                    {!! Former::text('name')->label('Nama Syarikat / Perniagaan')->required() !!}

                    @if (Auth::user() && !Auth::user()->hasRole('Vendor') && $disable_create_flaq == 0)
                        {!! Former::textarea('address')->label('Alamat')->rows(4)->required() !!}
                    @elseif ($disable_create_flaq == 3)
                        {!! Former::textarea('address')->label('Alamat')->rows(4)->required() !!}
                    @else
                        {!! Former::textarea('address')->label('Alamat')->rows(4)->readonly()->style('background-color: #e9ecef; pointer-events: none;') !!}
                    @endif

                    <?php $districts = [];
                    foreach (App\Vendor::$districts as $key => $val) {
                        $districts[$key] = strtoupper($val);
                    } ?>
                    <?php if (isset($vendor)) {
                        $district = $vendor['district_id'] ?: '0';
                    } else {
                        $district = null;
                    } ?>

                    @if (Auth::user() && !Auth::user()->hasRole('Vendor') && $disable_create_flaq == 0)
                        {!! Former::select('district_id')->label('Daerah')->options($districts)->placeholder('Pilihan daerah...')->disabled(Auth::user()->hasRole('Vendor') && !is_null($vendor->approval_1_id))->value($district)->required() !!}
                    @elseif ($disable_create_flaq == 3)
                        {!! Former::select('district_id')->label('Daerah')->options($districts)->placeholder('Pilihan daerah...')->disabled(Auth::user()->hasRole('Vendor') && !is_null($vendor->approval_1_id))->value($district)->required() !!}
                    @else
                        {!! Former::select('district_id')->label('Daerah')->options($districts)->placeholder('Pilihan daerah...')->disabled(Auth::user()->hasRole('Vendor') && !is_null($vendor->approval_1_id))->value($district)->disabled() !!}
                    @endif

                    <div id="state_id_div" class="mb-3" style="{{ $district == 0 ? '' : 'display:none' }}">
                        <label for="state_id" class="form-label">Negeri<sup>*</sup></label>
                        <select class="form-control" name="state_id" id="state_id"
                            {{ (Auth::user() && !Auth::user()->hasRole('Vendor')) || $disable_create_flaq == 3 ? '' : 'disabled' }}>
                            <option value="0" disabled selected>Pilihan Negeri...</option>
                            @foreach ($country_states as $state)
                                <option value="{{ $state->id }}"
                                    {{ $vendor->state_id == $state->id ? 'selected' : '' }}>{{ $state->description }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {!! Former::text('tel')->pattern('^[+0-9]{9,}$')->label('No. Telefon')->placeholder("Tanda '+' dan nombor sahaja")->required() !!}
                    {!! Former::text('fax')->pattern('^[+0-9]{9,}$')->placeholder("Tanda '+' dan nombor sahaja")->label('No. Faks') !!}
                </div>

                <!-- Right Column -->
                <div>
                    {!! Former::select('organization_type')->label('Jenis Perniagaan')->placeholder('Pilih dari senarai')->options(App\Vendor::$organizationTypes)->required() !!}
                    {!! Former::text('incorporation_date')->label('Tarikh Penubuhan')->required()->data_date_end_date(date('d/m/Y')) !!}

                    <div class="mb-3">
                        <label for="ssm_expiry" class="form-label">Tarikh Tamat Sijil SSM <sup>*</sup></label>
                        <input class="form-control valid" date-date-start-date="{{ date('d/m/Y') }}" required
                            aria-required="true" id="ssm_expiry" type="text" name="ssm_expiry"
                            value="{{ isset($vendor->ssm_expiry) && $vendor->ssm_expiry != '' ? $vendor->ssm_expiry->format('d/m/Y') : date('d/m/Y') }}">
                    </div>

                    <div class="mb-3">
                        <label for="authorized_capital" class="form-label">Modal Dibenarkan</label>
                        <div class="modern-input-group">
                            <select class="form-control currency-select" name="authorized_capital_currency"
                                id="authorized_capital_currency">
                                @foreach (App\Vendor::$currencies as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ (isset($vendor) && $vendor->authorized_capital_currency == $key) || (!isset($vendor) && $key == 'MYR') ? 'selected' : '' }}>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                            <input class="form-control" id="authorized_capital" type="text"
                                name="authorized_capital"
                                value="{{ isset($vendor) ? $vendor->authorized_capital : '0.00' }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="paidup_capital" class="form-label">Modal Berbayar</label>
                        <div class="modern-input-group">
                            <select class="form-control currency-select" name="paidup_capital_currency"
                                id="paidup_capital_currency">
                                @foreach (App\Vendor::$currencies as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ (isset($vendor) && $vendor->paidup_capital_currency == $key) || (!isset($vendor) && $key == 'MYR') ? 'selected' : '' }}>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                            <input class="form-control" id="paidup_capital" type="text" name="paidup_capital"
                                value="{{ isset($vendor) ? $vendor->paidup_capital : '0.00' }}">
                        </div>
                    </div>

                    {!! Former::text('tax_no')->label('No. Rujukan Cukai') !!}
                    {!! Former::text('gst_no')->label('No Pendaftaran GST') !!}
                    {!! Former::text('website')->label('Laman Web')->addClass('x-uppercase') !!}
                </div>
            </div>
        </div>

        <!-- 2. MAKLUMAT PEGAWAI -->
        <div class="tab-pane" id="vf-officer">
            <!-- Tab Section Header -->
            <div class="tab-section-header">
                <div class="tab-section-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <div class="tab-section-header-text">
                    <h3>Maklumat Pegawai</h3>
                    <p>Pegawai yang bertanggungjawab untuk urusan syarikat</p>
                </div>
            </div>

            <div class="form-grid-2">
                <div>
                    {!! Former::text('officer_name')->label('Nama Pegawai')->required() !!}
                    {!! Former::text('officer_designation')->label('Jawatan Pegawai')->required() !!}
                </div>
                <div>
                    {!! Former::text('officer_tel')->label('No. Telefon')->required() !!}
                    @if (!isset($vendor))
                        {!! Former::password('password')->label('Kata Laluan')->required() !!}
                        {!! Former::password('password_confirmation')->label('Sahkan Kata Laluan')->required() !!}
                    @endif
                </div>
            </div>
        </div>

        <?php if(!isset($vendor->approval_1_id) || Auth::user()->can('Vendor:override')) : ?>
        <!-- 3. MOF -->
        <div class="tab-pane" id="vf-mof">
            <!-- Tab Section Header -->
            <div class="tab-section-header">
                <div class="tab-section-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                </div>
                <div class="tab-section-header-text">
                    <h3>Pendaftaran MOF</h3>
                    <p>Maklumat pendaftaran Kementerian Kewangan Malaysia</p>
                </div>
            </div>

            <div class="form-grid-2">
                <div>
                    {!! Former::text('mof_ref_no')->label('No Rujukan Pendaftaran MOF') !!}
                    <div class="mb-3">
                        <label for="mof_start_date" class="form-label">Tarikh Aktif</label>
                        <div class="modern-input-group">
                            <input class="form-control x-uppercase" id="mof_start_date" type="text"
                                name="mof_start_date"
                                value="{{ isset($vendor) && !empty($vendor->mof_start_date) ? Carbon\Carbon::parse($vendor->mof_start_date)->format('j M Y') : '' }}">
                            <div class="addon">hingga</div>
                            <input class="form-control x-uppercase" id="mof_end_date" type="text"
                                name="mof_end_date"
                                value="{{ isset($vendor) && !empty($vendor->mof_end_date) ? Carbon\Carbon::parse($vendor->mof_end_date)->format('j M Y') : '' }}">
                        </div>
                    </div>
                </div>
                <div>
                    <div class="mb-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="checkbox-align-wrapper">
                            <input type="hidden" name="mof_bumi" value="0">
                            {!! Former::checkbox('mof_bumi')->inline()->label('Syarikat Bumiputera')->checked(isset($vendor) && !empty($vendor->mof_bumi))->forceValue(1) !!}
                        </div>
                    </div>
                    {!! Former::select('mof_codes')->id('mof_codes')->name('mof_codes[]')->label('Kod Bidang')->multiple(true)->placeholder('Pilih kod bidang MOF')->class('selectize')->options(
                            App\Code::where('type', 'mof')->orderBy('code')->get()->pluck('label', 'id'),
                            isset($vendor) ? $vendor->vendorCodes()->where('code_type', 'mof')->pluck('code_id') : '',
                        ) !!}
                </div>
            </div>
        </div>

        <!-- 4. CIDB -->
        <div class="tab-pane" id="vf-cidb">
            <!-- Tab Section Header -->
            <div class="tab-section-header">
                <div class="tab-section-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 20h.01"></path>
                        <path d="M7 20v-4"></path>
                        <path d="M12 20v-8"></path>
                        <path d="M17 20V8"></path>
                        <path d="M22 4v16"></path>
                    </svg>
                </div>
                <div class="tab-section-header-text">
                    <h3>Pendaftaran CIDB</h3>
                    <p>Maklumat pendaftaran Lembaga Pembangunan Industri Pembinaan</p>
                </div>
            </div>

            <div class="form-grid-2">
                <!-- Top Section -->
                <div>
                    {!! Former::text('cidb_ref_no')->label('No Sijil CIDB') !!}
                    <div class="mb-3">
                        <label for="cidb_start_date" class="form-label">Tarikh Aktif</label>
                        <div class="modern-input-group">
                            <input class="form-control x-uppercase" id="cidb_start_date" type="text"
                                name="cidb_start_date"
                                value="{{ isset($vendor) && !empty($vendor->cidb_start_date) ? Carbon\Carbon::parse($vendor->cidb_start_date)->format('j M Y') : '' }}">
                            <div class="addon">hingga</div>
                            <input class="form-control x-uppercase" id="cidb_end_date" type="text"
                                name="cidb_end_date"
                                value="{{ isset($vendor) && !empty($vendor->cidb_end_date) ? Carbon\Carbon::parse($vendor->cidb_end_date)->format('j M Y') : '' }}">
                        </div>
                    </div>
                </div>

                <div>
                    <div class="mb-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="checkbox-align-wrapper">
                            <input type="hidden" name="cidb_bumi" value="0">
                            {!! Former::checkbox('cidb_bumi')->inline()->label('Syarikat Bumiputera')->checked(isset($vendor) && !empty($vendor->cidb_bumi))->forceValue(1) !!}
                        </div>
                    </div>
                </div>

                <!-- Bottom Section: REPEATER -->
                <div style="grid-column: 1 / -1;">
                    <label class="form-label">Gred &amp; Bidang Pengkhususan</label>
                    <div id="cidb_group">
                        <!-- Hidden template using <template> -->
                        <template id="cidb_group_template">
                            <div class="repeater-item">
                                <input type="hidden" class="cidb-group-id" name="cidb_group[#index#][id]">
                                <div class="fields-wrapper">
                                    <div>
                                        <label
                                            style="font-size: 0.7rem; color:#94a3b8; text-transform:uppercase; font-weight:700;">Gred</label>
                                        <select class="cidb_group-code_id form-control selectize"
                                            name="cidb_group[#index#][code_id]" data-tracker="cidb_group_tracker"
                                            onchange="updateOption(this)">
                                            <option disabled selected value="">Sila pilih Gred</option>
                                            @foreach (App\Code::where('type', 'cidb-g')->orderBy('code', 'asc')->get() as $code)
                                                <option value="{{ $code->id }}">{{ $code->label }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label
                                            style="font-size: 0.7rem; color:#94a3b8; text-transform:uppercase; font-weight:700;">Bidang
                                            Pengkhususan</label>
                                        <select class="cidb_group-codes form-control selectize"
                                            name="cidb_group[#index#][codes][]" multiple>
                                            <option disabled value="">Pilih Bidang</option>
                                            @foreach (App\Code::where('type', 'cidb-c')->orderBy('code', 'asc')->get() as $code)
                                                <option value="{{ $code->id }}">{{ $code->label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="action-wrapper">
                                    <button type="button" class="table-btn table-btn-delete btn-delete-cidb_group"
                                        title="Padam">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path
                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                            </path>
                                            <line x1="10" y1="11" x2="10" y2="17">
                                            </line>
                                            <line x1="14" y1="11" x2="14" y2="17">
                                            </line>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <!-- Shown when no forms exist -->
                        <div id="cidb_group_noforms_template" class="empty-state">
                            <svg class="empty-state-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="12" y1="18" x2="12" y2="12"></line>
                                <line x1="9" y1="15" x2="15" y2="15"></line>
                            </svg>
                            <p class="empty-state-text">Tiada maklumat ditambah. Sila tekan butang tambah di bawah.</p>
                        </div>

                        <!-- Controls -->
                        <div id="cidb_group_controls">
                            <div id="cidb_group_add">
                                <a class="btn-add-repeater">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    Tambah Gred CIDB
                                </a>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="deleted_cidb_group[]">
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- 5. PEMEGANG SAHAM -->
        <div class="tab-pane" id="vf-shareholders" data-entity-name="shareholder" <?php if(isset($vendor)) { ?>
            data-remote="{{ asset('vendor/' . $vendor->id . '/shareholders') }}" <?php } ?>>
            <!-- Tab Section Header -->
            <div class="tab-section-header">
                <div class="tab-section-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div class="tab-section-header-text">
                    <h3>Pemegang Saham</h3>
                    <p>Senarai pemegang saham syarikat</p>
                </div>
            </div>

            <table class="clean-table">
                <thead>
                    <tr>
                        <th data-field="name">Nama</th>
                        <th data-field="identity">IC / Pasport</th>
                        <th data-field="nationality">Kewarganegaraan</th>
                        <th data-field="bumiputera_status">Taraf</th>
                        <?php if(!strstr(Route::currentRouteName(), 'show')) { ?>
                        <th data-field="actions" width="100" class="text-center">Tindakan</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows generated by ItemController -->
                </tbody>
                <tfoot <?php if(strstr(Route::currentRouteName(), 'show')) { ?>style="display:none;"<?php } ?>>
                    <tr style="background: #f8fafc;">
                        <td><input class="form-control input-sm" data-field="name" type="text"
                                placeholder="Nama Penuh"></td>
                        <td><input class="form-control input-sm" data-field="identity" type="text"
                                placeholder="IC / Passport"></td>
                        <td>
                            <select class="form-control input-sm" name="nat" data-field="nationality">
                                @foreach (App\Vendor::$nationalities as $key => $value)
                                    <option value="{{ $key }}" {{ $key == 'MALAYSIAN' ? 'selected' : '' }}>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><select class="form-control input-sm" data-field="bumiputera_status"></select></td>
                        <td class="text-center">
                            <div class="table-btn-group">
                                <button type="button" class="table-btn table-btn-save" data-action="save"
                                    title="Simpan">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </button>
                                <button type="button" class="table-btn table-btn-clear" data-action="clear"
                                    title="Kosongkan">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <h4 class="section-header">Ringkasan Pegangan Saham <sup>*</sup></h4>
            <table class="clean-table">
                <thead>
                    <tr>
                        <th>Bumiputera</th>
                        <th>Bukan Bumiputera</th>
                        <th>Warga Asing</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="modern-input-group">
                                <input name="bumi_percentage" min="0" type="number" class="form-control"
                                    value="{{ Request::old('bumi_percentage', isset($vendor) ? $vendor->bumi_percentage : 0) }}">
                                <div class="addon">%</div>
                            </div>
                        </td>
                        <td>
                            <div class="modern-input-group">
                                <input name="nonbumi_percentage" min="0" type="number" class="form-control"
                                    value="{{ Request::old('nonbumi_percentage', isset($vendor) ? $vendor->nonbumi_percentage : 0) }}">
                                <div class="addon">%</div>
                            </div>
                        </td>
                        <td>
                            <div class="modern-input-group">
                                <input name="foreigner_percentage" min="0" type="number"
                                    class="form-control"
                                    value="{{ Request::old('foreigner_percentage', isset($vendor) ? $vendor->foreigner_percentage : 0) }}">
                                <div class="addon">%</div>
                            </div>
                        </td>
                        <td>
                            <div class="modern-input-group">
                                <input class="form-control" disabled="disabled">
                                <div class="addon">%</div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- 6. PENGARAH -->
        <div class="tab-pane" id="vf-directors" data-entity-name="director" <?php if(isset($vendor)) { ?>
            data-remote="{{ asset('vendor/' . $vendor->id . '/directors') }}" <?php } ?>>
            <!-- Tab Section Header -->
            <div class="tab-section-header">
                <div class="tab-section-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                </div>
                <div class="tab-section-header-text">
                    <h3>Pengarah Syarikat</h3>
                    <p>Senarai pengarah yang dilantik</p>
                </div>
            </div>

            <table class="clean-table">
                <thead>
                    <tr>
                        <th data-field="name" class="col-4">Nama</th>
                        <th data-field="identity" class="col-2">IC / Pasport</th>
                        <th data-field="nationality">Kewarganegaraan</th>
                        <th data-field="designation">Jawatan</th>
                        <?php if(!strstr(Route::currentRouteName(), 'show')) { ?>
                        <th data-field="actions" width="100" class="text-center">Tindakan</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows generated by ItemController -->
                </tbody>
                <tfoot <?php if(strstr(Route::currentRouteName(), 'show')) { ?>style="display:none;"<?php } ?>>
                    <tr style="background: #f8fafc;">
                        <td><input class="form-control input-sm" data-field="name" type="text"
                                placeholder="Nama Penuh"></td>
                        <td><input class="form-control input-sm" data-field="identity" type="text"
                                placeholder="IC / Passport"></td>
                        <td>
                            <select class="form-control input-sm" name="nat" data-field="nationality">
                                @foreach (App\Vendor::$nationalities as $key => $value)
                                    <option value="{{ $key }}" {{ $key == 'MALAYSIAN' ? 'selected' : '' }}>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="form-control input-sm" name="nat" data-field="designation">
                                @foreach (App\Vendor::$directorDesignations as $key => $value)
                                    <option value="{{ $key }}" {{ $key == 'PENGARAH' ? 'selected' : '' }}>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="text-center">
                            <div class="table-btn-group">
                                <button type="button" class="table-btn table-btn-save" data-action="save"
                                    title="Simpan">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </button>
                                <button type="button" class="table-btn table-btn-clear" data-action="clear"
                                    title="Kosongkan">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if (isset($vendor) && $vendor->approval_1_id > 0)
            <!-- 7. KAKITANGAN -->
            <div class="tab-pane" id="vf-contacts" data-entity-name="contact" <?php if(isset($vendor)) { ?>
                data-remote="{{ asset('vendor/' . $vendor->id . '/contacts') }}" <?php } ?>>
                <!-- Tab Section Header -->
                <div class="tab-section-header">
                    <div class="tab-section-header-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <div class="tab-section-header-text">
                        <h3>Kakitangan Syarikat</h3>
                        <p>Senarai kakitangan yang bekerja dalam syarikat</p>
                    </div>
                </div>

                <table class="clean-table">
                    <thead>
                        <tr>
                            <th data-field="name">Nama</th>
                            <th data-field="designation">Jawatan</th>
                            <th data-field="nationality">Warga Negara</th>
                            <th data-field="status">Taraf</th>
                            <?php if(!strstr(Route::currentRouteName(), 'show')) { ?>
                            <th data-field="actions" width="100" class="text-center">Tindakan</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows generated by ItemController -->
                    </tbody>
                    <tfoot <?php if(strstr(Route::currentRouteName(), 'show')) { ?>style="display:none;"<?php } ?>>
                        <tr style="background: #f8fafc;">
                            <td><input class="form-control input-sm" data-field="name" type="text"
                                    placeholder="Nama Penuh"></td>
                            <td><input class="form-control input-sm" data-field="designation" type="text"
                                    placeholder="Jawatan"></td>
                            <td>
                                <select class="form-control input-sm" name="nat" data-field="nationality">
                                    @foreach (App\Vendor::$nationalities as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ $key == 'MALAYSIAN' ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><select class="form-control input-sm" data-field="status"></select></td>
                            <td class="text-center">
                                <div class="table-btn-group">
                                    <button type="button" class="table-btn table-btn-save" data-action="save"
                                        title="Simpan"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg></button>
                                    <button type="button" class="table-btn table-btn-clear" data-action="clear"
                                        title="Kosongkan"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18">
                                            </line>
                                            <line x1="6" y1="6" x2="18" y2="18">
                                            </line>
                                        </svg></button>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- 8. ANUGERAH -->
            <div class="tab-pane" id="vf-awards" data-entity-name="award" <?php if(isset($vendor)) { ?>
                data-remote="{{ asset('vendor/' . $vendor->id . '/awards') }}" <?php } ?>>
                <!-- Tab Section Header -->
                <div class="tab-section-header">
                    <div class="tab-section-header-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="7"></circle>
                            <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                        </svg>
                    </div>
                    <div class="tab-section-header-text">
                        <h3>Anugerah & Pengiktirafan</h3>
                        <p>Senarai anugerah yang diterima oleh syarikat</p>
                    </div>
                </div>

                <table class="clean-table">
                    <thead>
                        <tr>
                            <th data-field="name">Nama</th>
                            <th data-field="description">Keterangan</th>
                            <th data-field="by">Pemberi</th><?php if(!strstr(Route::currentRouteName(), 'show')) { ?><th data-field="actions"
                                width="100" class="text-center">Tindakan</th><?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows generated by ItemController -->
                    </tbody>
                    <tfoot <?php if(strstr(Route::currentRouteName(), 'show')) { ?>style="display:none;"<?php } ?>>
                        <tr>
                            <td><input class="form-control input-sm" data-field="name" type="text"></td>
                            <td><input class="form-control input-sm" data-field="description" type="text"></td>
                            <td><input class="form-control input-sm" data-field="by" type="text"></td>
                            <td class="text-center">
                                <div class="table-btn-group"><button type="button" class="table-btn table-btn-save"
                                        data-action="save" title="Simpan"><svg xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg></button><button type="button" class="table-btn table-btn-clear"
                                        data-action="clear" title="Kosongkan"><svg xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18">
                                            </line>
                                            <line x1="6" y1="6" x2="18" y2="18">
                                            </line>
                                        </svg></button></div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- 9. ASET -->
            <div class="tab-pane" id="vf-assets" data-entity-name="asset" <?php if(isset($vendor)) { ?>
                data-remote="{{ asset('vendor/' . $vendor->id . '/assets') }}" <?php } ?>>
                <!-- Tab Section Header -->
                <div class="tab-section-header">
                    <div class="tab-section-header-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                    </div>
                    <div class="tab-section-header-text">
                        <h3>Aset Syarikat</h3>
                        <p>Senarai aset yang dimiliki oleh syarikat</p>
                    </div>
                </div>

                <table class="clean-table">
                    <thead>
                        <tr>
                            <th data-field="name">Nama</th>
                            <th data-field="value" width="200">Nilai (RM)</th><?php if(!strstr(Route::currentRouteName(), 'show')) { ?><th
                                data-field="actions" width="100" class="text-center">Tindakan</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows generated by ItemController -->
                    </tbody>
                    <tfoot <?php if(strstr(Route::currentRouteName(), 'show')) { ?>style="display:none;"<?php } ?>>
                        <tr>
                            <td><input class="form-control input-sm" data-field="name" type="text"></td>
                            <td>
                                <div class="modern-input-group">
                                    <div class="addon">RM</div><input class="form-control input-sm"
                                        data-field="value" type="text">
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="table-btn-group"><button type="button" class="table-btn table-btn-save"
                                        data-action="save" title="Simpan"><svg xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg></button><button type="button" class="table-btn table-btn-clear"
                                        data-action="clear" title="Kosongkan"><svg xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18">
                                            </line>
                                            <line x1="6" y1="6" x2="18" y2="18">
                                            </line>
                                        </svg></button></div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- 10. PROJEK -->
            <div class="tab-pane" id="vf-projects" data-entity-name="project" <?php if(isset($vendor)) { ?>
                data-remote="{{ asset('vendor/' . $vendor->id . '/projects') }}" <?php } ?>>
                <!-- Tab Section Header -->
                <div class="tab-section-header">
                    <div class="tab-section-header-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="tab-section-header-text">
                        <h3>Projek Lepas</h3>
                        <p>Senarai projek yang telah dilaksanakan</p>
                    </div>
                </div>

                <table class="clean-table">
                    <thead>
                        <tr>
                            <th data-field="name">Nama</th>
                            <th data-field="customer">Pelanggan</th>
                            <th data-field="period">Tempoh</th>
                            <th data-field="value">Nilai (RM)</th>
                            <th data-field="done">Siap</th><?php if(!strstr(Route::currentRouteName(), 'show')) { ?><th data-field="actions"
                                width="100" class="text-center">Tindakan</th><?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows generated by ItemController -->
                    </tbody>
                    <tfoot <?php if(strstr(Route::currentRouteName(), 'show')) { ?>style="display:none;"<?php } ?>>
                        <tr>
                            <td><input class="form-control input-sm" data-field="name" type="text"></td>
                            <td><input class="form-control input-sm" data-field="customer" type="text"></td>
                            <td><input class="form-control input-sm" data-field="period" type="text"></td>
                            <td>
                                <div class="modern-input-group">
                                    <div class="addon">RM</div><input class="form-control input-sm"
                                        data-field="value" type="text">
                                </div>
                            </td>
                            <td><input data-field="done" type="checkbox"></td>
                            <td class="text-center">
                                <div class="table-btn-group"><button type="button" class="table-btn table-btn-save"
                                        data-action="save" title="Simpan"><svg xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg></button><button type="button" class="table-btn table-btn-clear"
                                        data-action="clear" title="Kosongkan"><svg xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18">
                                            </line>
                                            <line x1="6" y1="6" x2="18" y2="18">
                                            </line>
                                        </svg></button></div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- 11. PRODUK -->
            <div class="tab-pane" id="vf-products" data-entity-name="product" <?php if(isset($vendor)) { ?>
                data-remote="{{ asset('vendor/' . $vendor->id . '/products') }}" <?php } ?>>
                <!-- Tab Section Header -->
                <div class="tab-section-header">
                    <div class="tab-section-header-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                            </path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                    </div>
                    <div class="tab-section-header-text">
                        <h3>Produk & Perkhidmatan</h3>
                        <p>Senarai produk atau perkhidmatan yang ditawarkan</p>
                    </div>
                </div>

                <table class="clean-table">
                    <thead>
                        <tr>
                            <th data-field="name">Nama</th>
                            <th data-field="description">Keterangan</th>
                            <th data-field="implementations">Pengguna</th><?php if(!strstr(Route::currentRouteName(), 'show')) { ?><th data-field="actions"
                                width="100" class="text-center">Tindakan</th><?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows generated by ItemController -->
                    </tbody>
                    <tfoot <?php if(strstr(Route::currentRouteName(), 'show')) { ?>style="display:none;"<?php } ?>>
                        <tr>
                            <td><input class="form-control input-sm" data-field="name" type="text"></td>
                            <td><input class="form-control input-sm" data-field="description" type="text"></td>
                            <td><input class="form-control input-sm" data-field="implementations" type="text">
                            </td>
                            <td class="text-center">
                                <div class="table-btn-group"><button type="button" class="table-btn table-btn-save"
                                        data-action="save" title="Simpan"><svg xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg></button><button type="button" class="table-btn table-btn-clear"
                                        data-action="clear" title="Kosongkan"><svg xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18">
                                            </line>
                                            <line x1="6" y1="6" x2="18" y2="18">
                                            </line>
                                        </svg></button></div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

        <!-- 12. FAIL -->
        <div class="tab-pane" id="vf-files">
            <!-- Tab Section Header -->
            <div class="tab-section-header">
                <div class="tab-section-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <div class="tab-section-header-text">
                    <h3>Muat Naik Fail</h3>
                    <p>Dokumen sijil dan fail sokongan</p>
                </div>
            </div>

            <div class="info-alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                Hanya fail beformat PDF dan bersaiz maksimum 5MB boleh dimuat naik.
            </div>

            <div class="form-grid-1">
                <?php $ssm = Former::file('ssm')->label('Sijil SSM')->accept('application/pdf')->addClass('file_input'); ?>
                <?php if (!isset($vendor) || !$vendor->completed || !$vendor->hasFile('ssm')) {
                    $ssm = $ssm->required();
                } ?>
                {!! $ssm !!}

                @if (!isset($vendor) || !$vendor->completed || Auth::user()->hasRole('Admin'))
                    {!! Former::file('mof')->label('Sijil MOF')->accept('application/pdf')->addClass('file_input') !!}
                    {!! Former::file('mof_bumiputera')->label('Sijil Bumiputera MOF')->accept('application/pdf')->addClass('file_input') !!}
                    {!! Former::file('cidb')->label('Sijil CIDB & SPKK')->accept('application/pdf')->addClass('file_input')->help('Muat naik fail sijil SPKK & CIDB sebagai satu fail sahaja.') !!}
                    {!! Former::file('cidb_bumiputera')->label('Sijil Bumiputera PKK')->accept('application/pdf')->addClass('file_input') !!}
                @endif
            </div>

            @if (isset($vendor) && count($vendor->uploads) > 0)
                <h4 class="section-header">Fail Dimuat Naik</h4>
                <div class="table-responsive">
                    {!! $vendor->uploadsTable() !!}
                </div>
            @endif
        </div>
    </div>
</div>

@push('modals')
    <!-- Generic Alert Modal -->
    <div class="modal fade" id="vendorAlertModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-3 overflow-hidden">
                <div class="modal-header text-white border-0"
                    style="background: linear-gradient(135deg, #c41e3a 0%, #a01830 100%);">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        Perhatian
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="mb-3 text-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                            </path>
                            <line x1="12" y1="9" x2="12" y2="13"></line>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                    </div>
                    <p class="mb-4 fs-6 text-dark" id="vendorAlertMessage" style="white-space: pre-line;"></p>
                    <button type="button" class="btn btn-dark px-4 rounded-3 fw-semibold" data-bs-dismiss="modal"
                        style="min-width: 100px;">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Generic Confirm Modal -->
    <div class="modal fade" id="vendorConfirmModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow rounded-3 overflow-hidden">
                <div class="modal-header bg-white border-bottom">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                        <div
                            class="bg-success bg-opacity-10 text-success rounded p-1 d-flex align-items-center justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                        Pengesahan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-light border mb-0 rounded-3">
                        <div class="d-flex gap-3">
                            <div class="text-secondary flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="16" x2="12" y2="12"></line>
                                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                </svg>
                            </div>
                            <div class="text-muted small text-break" id="vendorConfirmMessage"
                                style="max-height: 400px; overflow-y: auto; line-height: 1.6;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top px-4 py-3">
                    <button type="button" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold"
                        data-bs-dismiss="modal" id="vendorConfirmCancelBtn">Batal</button>
                    <button type="button" class="btn btn-success px-4 rounded-3 fw-semibold" id="vendorConfirmOkBtn"
                        style="background: linear-gradient(135deg, #059669 0%, #047857 100%); border: none;">Pasti</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor Delete Confirm Modal -->
    <div class="modal fade" id="vendorDeleteConfirmModal" tabindex="-1" aria-hidden="true"
        style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-3 overflow-hidden">
                <div class="modal-header border-0 text-white"
                    style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M3 6h18"></path>
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                        </svg>
                        Pengesahan Padam
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="mb-3 text-danger opacity-75">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                    </div>
                    <p id="vendorDeleteConfirmMessage" class="fs-5 mb-0 text-dark fw-medium">
                        Adakah anda pasti mahu memadam data ini?
                    </p>
                    <p class="text-muted small mt-2 mb-0">Tindakan ini tidak boleh dibatalkan.</p>
                </div>
                <div class="modal-footer border-0 bg-light justify-content-center p-3">
                    <button type="button" class="btn btn-light px-4 py-2 rounded-3 fw-bold text-muted border"
                        data-bs-dismiss="modal" id="vendorDeleteCancelBtn">
                        Batal
                    </button>
                    <button type="button" class="btn btn-danger px-4 py-2 rounded-3 fw-bold text-white shadow-sm"
                        id="vendorDeleteOkBtn"
                        style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); border: none;">
                        Ya, Padam
                    </button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script src="{{ asset('js/ajax-loader.js') }}"></script>
    <script src="{{ asset('js/item-controller.js') }}"></script>
    <script src="{{ asset('js/two-way-binding.js') }}"></script>
    <script src="{{ asset('js/form-validator.js') }}"></script>
    <script src="{{ asset('js/percentage-calculator.js') }}"></script>
    <script src="{{ asset('js/vendor-form-init.js') }}"></script>
    <script>
        (function() {
            // Function to prevent duplicate CIDB grade selections
            window.updateOption = function(selectElement) {
                const allGradeSelects = document.querySelectorAll(
                    '.cidb_group-code_id[data-tracker="cidb_group_tracker"]');
                const selectedValues = [];

                // Collect all selected values
                allGradeSelects.forEach(function(select) {
                    if (select.value) {
                        selectedValues.push(select.value);
                    }
                });

                // Update each selectize dropdown to remove already selected options
                allGradeSelects.forEach(function(select) {
                    if (select.selectize) {
                        const currentValue = select.value;

                        // Remove options that are selected in other dropdowns
                        selectedValues.forEach(function(value) {
                            if (value !== currentValue && value !== '') {
                                select.selectize.removeOption(value);
                            }
                        });
                    }
                });
            };

            function setTabState(tab, disabled) {
                var link = tab.querySelector('a');
                if (!link) {
                    return;
                }

                if (disabled) {
                    tab.classList.add('tab-disabled');
                    tab.classList.add('disabled');
                    link.classList.add('disabled');
                    link.setAttribute('tabindex', '-1');
                    link.setAttribute('aria-disabled', 'true');
                } else {
                    tab.classList.remove('tab-disabled');
                    tab.classList.remove('disabled');
                    link.classList.remove('disabled');
                    link.removeAttribute('tabindex');
                    link.removeAttribute('aria-disabled');
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                var tabs = document.querySelectorAll('.modern-nav-tabs .nav-item');

                tabs.forEach(function(tab, index) {
                    var link = tab.querySelector('a');
                    if (!link) {
                        return;
                    }

                    if (!link.dataset.tabGuardInitialized) {
                        link.addEventListener('click', function(event) {
                            if (tab.classList.contains('tab-disabled')) {
                                event.preventDefault();
                                event.stopImmediatePropagation();
                            }
                        });
                        link.dataset.tabGuardInitialized = 'true';
                    }

                    setTabState(tab, index !== 0);
                });

                window.VendorTabManager = {
                    enableTabByIndex: function(index) {
                        var tabs = document.querySelectorAll('.modern-nav-tabs .nav-item');
                        if (index < 0 || index >= tabs.length) {
                            return;
                        }
                        setTabState(tabs[index], false);
                    }
                };

                const addBtn = document.querySelector(".btn-add-repeater");
                const container = document.querySelector("#cidb_group");
                const template = document.querySelector("#cidb_group_template");
                const emptyState = document.querySelector("#cidb_group_noforms_template");

                // Load existing CIDB data from backend
                const loadExistingData = () => {
                    @if (isset($vendor) && $vendor->cidbGrades)
                        const cidbData = [
                            @foreach ($vendor->cidbGrades()->orderBy('id', 'asc')->get() as $grade)
                                {
                                    id: "{{ $grade->id }}",
                                    code_id: "{{ $grade->code_id }}",
                                    codes: @json($grade->children()->pluck('code_id'))
                                },
                            @endforeach
                        ];

                        cidbData.forEach((data, idx) => {
                            const clone = template.content.cloneNode(true);
                            const item = clone.querySelector(".repeater-item");

                            // Replace #index# placeholders
                            item.innerHTML = item.innerHTML.replace(/#index#/g, idx);

                            // Insert the item
                            const insertedItem = container.insertBefore(item, document
                                .getElementById("cidb_group_controls"));

                            // Set values
                            const idInput = insertedItem.querySelector('.cidb-group-id');
                            const codeSelect = insertedItem.querySelector('.cidb_group-code_id');
                            const codesSelect = insertedItem.querySelector('.cidb_group-codes');

                            if (idInput) idInput.value = data.id;
                            if (codeSelect) codeSelect.value = data.code_id;

                            // Initialize selectize
                            if (typeof $ !== 'undefined' && typeof $.fn.selectize !== 'undefined') {
                                if (codeSelect && !codeSelect.selectize) {
                                    $(codeSelect).selectize();
                                    if (codeSelect.selectize) {
                                        codeSelect.selectize.setValue(data.code_id);
                                    }
                                }

                                if (codesSelect && !codesSelect.selectize) {
                                    $(codesSelect).selectize();
                                    if (codesSelect.selectize && data.codes) {
                                        codesSelect.selectize.setValue(data.codes);
                                    }
                                }
                            }
                        });
                    @endif
                };

                // Initialize selectize on any pre-existing items
                const initializeExistingItems = () => {
                    const existingItems = container.querySelectorAll('.repeater-item');

                    if (existingItems.length > 0) {
                        emptyState.style.display = "none";

                        // Initialize selectize on existing select elements
                        if (typeof $ !== 'undefined' && typeof $.fn.selectize !== 'undefined') {
                            existingItems.forEach(function(item) {
                                $(item).find('select.selectize').each(function() {
                                    if (!this.selectize) {
                                        $(this).selectize();
                                    }
                                });
                            });
                        }
                    } else {
                        emptyState.style.display = "block";
                    }

                    // Set index to number of existing items
                    return existingItems.length;
                };

                // Load data from backend first
                loadExistingData();

                // Then initialize any existing items
                let index = initializeExistingItems();

                const addItem = () => {
                    const clone = template.content.cloneNode(true);
                    const item = clone.querySelector(".repeater-item");

                    // Replace #index# placeholders
                    item.innerHTML = item.innerHTML.replace(/#index#/g, index);

                    // Insert the item
                    const insertedItem = container.insertBefore(item, document.getElementById(
                        "cidb_group_controls"));

                    // Initialize selectize on newly added select elements
                    if (typeof $ !== 'undefined' && typeof $.fn.selectize !== 'undefined') {
                        $(insertedItem).find('select.selectize').each(function() {
                            if (!this.selectize) {
                                $(this).selectize();
                            }
                        });
                    }

                    // Update options to prevent duplicates
                    if (typeof window.updateOption === 'function') {
                        const gradeSelect = insertedItem.querySelector('.cidb_group-code_id');
                        if (gradeSelect) {
                            window.updateOption(gradeSelect);
                        }
                    }

                    index++;
                    emptyState.style.display = "none";
                };

                addBtn.addEventListener("click", addItem);

                // Delegate remove events
                container.addEventListener("click", function(e) {
                    if (e.target.closest(".btn-delete-cidb_group")) {
                        const row = e.target.closest(".repeater-item");

                        // Track deleted item ID for backend
                        const idInput = row.querySelector('.cidb-group-id');
                        if (idInput && idInput.value) {
                            const deletedInput = document.querySelector(
                                'input[name="deleted_cidb_group[]"]');
                            if (deletedInput) {
                                // Create new hidden input for each deleted ID
                                const newDeletedInput = document.createElement('input');
                                newDeletedInput.type = 'hidden';
                                newDeletedInput.name = 'deleted_cidb_group[]';
                                newDeletedInput.value = idInput.value;
                                deletedInput.parentNode.appendChild(newDeletedInput);
                            }
                        }

                        // Destroy selectize instances before removing
                        const selectElements = row.querySelectorAll('select.selectize');
                        selectElements.forEach(function(select) {
                            if (select.selectize) {
                                select.selectize.destroy();
                            }
                        });

                        row.remove();

                        if (!container.querySelector(".repeater-item")) {
                            emptyState.style.display = "block";
                        }
                    }
                });
            });
        })();
    </script>
@endpush

@push('scripts')
    <script>
        (function() {

            // Expose VendorForm helpers
            window.VendorForm = window.VendorForm || {};

            // Alert Helper
            window.VendorForm.alert = function(message, callback) {
                const modalEl = document.getElementById('vendorAlertModal');
                const msgEl = document.getElementById('vendorAlertMessage');

                if (!modalEl || !msgEl) {
                    console.error('VendorForm.alert: Modal elements not found');
                    alert(message);
                    if (callback) callback();
                    return;
                }

                // Try Bootstrap 5 native
                if (typeof bootstrap !== 'undefined' && typeof bootstrap.Modal !== 'undefined') {
                    const bsModal = new bootstrap.Modal(modalEl, {
                        backdrop: 'static'
                    });
                    msgEl.textContent = message;
                    if (callback) {
                        modalEl.addEventListener('hidden.bs.modal', function onHidden() {
                            callback();
                            modalEl.removeEventListener('hidden.bs.modal', onHidden);
                        });
                    }
                    bsModal.show();
                    return;
                }

                // Try jQuery (Bootstrap 4 or 5 with jQuery)
                if (typeof jQuery !== 'undefined' && typeof jQuery.fn.modal !== 'undefined') {
                    $(msgEl).text(message);
                    if (callback) {
                        $(modalEl).one('hidden.bs.modal', function() {
                            callback();
                        });
                    }
                    $(modalEl).modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $(modalEl).modal('show');
                    return;
                }

                // Fallback
                console.warn(
                    'Bootstrap Modal not recognized (neither BS5 global nor jQuery plugin). using native alert.'
                );
                alert(message);
                if (callback) callback();
            };

            // Confirm Helper
            window.VendorForm.confirm = function(message, callback) {
                const modalEl = document.getElementById('vendorConfirmModal');
                const msgEl = document.getElementById('vendorConfirmMessage');
                const okBtn = document.getElementById('vendorConfirmOkBtn');
                const cancelBtn = document.getElementById('vendorConfirmCancelBtn');

                // Fallback wrapper
                const doFallback = () => {
                    if (confirm(message.replace(/<[^>]*>/g, ''))) {
                        if (callback) callback(true);
                    } else {
                        if (callback) callback(false);
                    }
                };

                if (!modalEl || !msgEl || !okBtn) {
                    console.error('VendorForm.confirm: Modal elements not found');
                    doFallback();
                    return;
                }

                // Prepare Content
                if (message.includes('<br>') || message.includes('</')) {
                    msgEl.innerHTML = message;
                } else {
                    msgEl.textContent = message;
                }

                // Handler Logic
                let result = false;
                const handleOk = () => {
                    result = true;
                    hideModal();
                };

                // Helper to close modal based on available lib
                const hideModal = () => {
                    if (typeof bootstrap !== 'undefined' && typeof bootstrap.Modal !== 'undefined') {
                        const bsModal = bootstrap.Modal.getInstance(modalEl);
                        if (bsModal) bsModal.hide();
                        else new bootstrap.Modal(modalEl).hide();
                    } else if (typeof jQuery !== 'undefined') {
                        $(modalEl).modal('hide');
                    }
                };

                // Setup Cleanup and Callback
                const onHidden = () => {
                    // Clean up events
                    okBtn.removeEventListener('click', handleOk);
                    // Callback
                    if (callback) callback(result);
                };

                // Cloning is safer for vanilla JS to remove all previous listeners
                const newOkBtn = okBtn.cloneNode(true);
                okBtn.parentNode.replaceChild(newOkBtn, okBtn);
                newOkBtn.addEventListener('click', handleOk);

                // Show Modal
                if (typeof bootstrap !== 'undefined' && typeof bootstrap.Modal !== 'undefined') {
                    const bsModal = new bootstrap.Modal(modalEl, {
                        backdrop: 'static'
                    });
                    modalEl.addEventListener('hidden.bs.modal', function hiddenHandler() {
                        onHidden();
                        modalEl.removeEventListener('hidden.bs.modal', hiddenHandler);
                    });
                    bsModal.show();
                } else if (typeof jQuery !== 'undefined' && typeof jQuery.fn.modal !== 'undefined') {
                    $(modalEl).off('hidden.bs.modal').on('hidden.bs.modal', onHidden);
                    $(modalEl).modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $(modalEl).modal('show');
                } else {
                    console.warn('Bootstrap Modal not recognized. using native confirm.');
                    doFallback();
                }
            };

            // Delete Confirm Helper
            window.VendorForm.confirmDelete = function(message, callback) {
                // If message is function, treat as callback (use default message)
                if (typeof message === 'function') {
                    callback = message;
                    message = 'Adakah anda pasti mahu memadam item ini?';
                }

                const modalEl = document.getElementById('vendorDeleteConfirmModal');
                const msgEl = document.getElementById('vendorDeleteConfirmMessage');
                const okBtn = document.getElementById('vendorDeleteOkBtn');
                const cancelBtn = document.getElementById('vendorDeleteCancelBtn');

                // Fallback wrapper
                const doFallback = () => {
                    if (confirm(message ? message.replace(/<[^>]*>/g, '') :
                            'Adakah anda pasti mahu memadam item ini?')) {
                        if (callback) callback(true);
                    } else {
                        if (callback) callback(false);
                    }
                };

                if (!modalEl || !msgEl || !okBtn) {
                    console.error('VendorForm.confirmDelete: Modal elements not found');
                    doFallback();
                    return;
                }

                // Update message if provided
                if (message) {
                    msgEl.textContent = message;
                }

                // Handler Logic
                let result = false;
                const handleOk = () => {
                    result = true;
                    hideModal();
                };

                // Helper to close modal
                const hideModal = () => {
                    if (typeof bootstrap !== 'undefined' && typeof bootstrap.Modal !== 'undefined') {
                        const bsModal = bootstrap.Modal.getInstance(modalEl);
                        if (bsModal) bsModal.hide();
                        else new bootstrap.Modal(modalEl).hide();
                    } else if (typeof jQuery !== 'undefined') {
                        $(modalEl).modal('hide');
                    }
                };

                // Setup Cleanup and Callback
                const onHidden = () => {
                    okBtn.removeEventListener('click', handleOk);
                    if (callback) callback(result);
                };

                // Attach OK listener
                const newOkBtn = okBtn.cloneNode(true);
                okBtn.parentNode.replaceChild(newOkBtn, okBtn);
                newOkBtn.addEventListener('click', handleOk);

                // Show Modal
                if (typeof bootstrap !== 'undefined' && typeof bootstrap.Modal !== 'undefined') {
                    const bsModal = new bootstrap.Modal(modalEl, {
                        backdrop: 'static'
                    });
                    modalEl.addEventListener('hidden.bs.modal', function hiddenHandler() {
                        onHidden();
                        modalEl.removeEventListener('hidden.bs.modal', hiddenHandler);
                    });
                    bsModal.show();
                } else if (typeof jQuery !== 'undefined' && typeof jQuery.fn.modal !== 'undefined') {
                    $(modalEl).off('hidden.bs.modal').on('hidden.bs.modal', onHidden);
                    $(modalEl).modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $(modalEl).modal('show');
                } else {
                    doFallback();
                }
            };
        })();

        // Address Field Logic (District -> State)
        document.addEventListener('DOMContentLoaded', function() {
            var districtSelect = document.getElementById('district_id');
            var stateDiv = document.getElementById('state_id_div');
            var stateSelect = document.getElementById('state_id');

            function toggleState() {
                if (!districtSelect || !stateDiv || !stateSelect) return;

                // '0' is the ID for "Luar Negeri Selangor"
                // Check against string '0' because values are usually strings in DOM
                if (String(districtSelect.value) === '0') {
                    stateDiv.style.display = 'block';
                    stateSelect.disabled = false;
                } else {
                    stateDiv.style.display = 'none';
                    stateSelect.disabled = true;
                    // Optional: Reset state selection when hidden
                    stateSelect.value = '0';
                }
            }

            if (districtSelect) {
                // Initial check
                toggleState();

                // Add event listener
                districtSelect.addEventListener('change', toggleState);

                // Fallback for selectize if used (jQuery)
                if (typeof $ !== 'undefined') {
                    $(districtSelect).on('change', toggleState);
                }
            }
        });
    </script>
@endpush
