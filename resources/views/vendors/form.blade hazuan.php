<script>
    // Global variables for the form
    var isAdmin = {{(Auth::user() && !Auth::user()->hasRole('Vendor') ? 'true' : 'false')}};
    var show = {{strstr(Route::currentRouteName(), 'show') ? 'true' : 'false'}};
    var shareHolderTypes = ['Bumiputera', 'Bukan Bumiputera', 'Warga Asing'];
    @if(Request::old('shareholder'))var inputOldShareholdes = {{json_encode(Request::old('shareholder'))}}; @endif
</script>

<div ng-app="vendor" data-show-mode="{{strstr(Route::currentRouteName(), 'show') ? 'true' : 'false'}}">
    
    <!-- TABS HEADER -->
    <div class="modern-tabs-header" style="padding-top:0; padding-bottom:0;">
        <ul class="modern-nav-tabs nav nav-tabs" role="tablist">
            <li class="nav-item active" role="presentation">
                <a class="nav-link active" href="#vf-main" data-bs-toggle="tab" role="tab" aria-controls="vf-main" aria-selected="true">Syarikat</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#vf-officer" data-bs-toggle="tab" role="tab" aria-controls="vf-officer" aria-selected="false">Pegawai</a>
            </li>

            <?php if(!isset($vendor->approval_1_id) || Auth::user()->can('Vendor:override')) : ?>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#vf-mof" data-bs-toggle="tab" role="tab" aria-controls="vf-mof" aria-selected="false">MOF</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#vf-cidb" data-bs-toggle="tab" role="tab" aria-controls="vf-cidb" aria-selected="false">CIDB</a>
                </li>
            <?php endif; ?>

            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#vf-shareholders" data-bs-toggle="tab" role="tab" aria-controls="vf-shareholders" aria-selected="false">Pemegang Saham</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#vf-directors" data-bs-toggle="tab" role="tab" aria-controls="vf-directors" aria-selected="false">Pengarah</a>
            </li>

            @if(isset($vendor) && $vendor->approval_1_id > 0)
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#vf-contacts" data-bs-toggle="tab" role="tab" aria-controls="vf-contacts" aria-selected="false">Kakitangan</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#vf-awards" data-bs-toggle="tab" role="tab" aria-controls="vf-awards" aria-selected="false">Anugerah</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#vf-assets" data-bs-toggle="tab" role="tab" aria-controls="vf-assets" aria-selected="false">Aset</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#vf-projects" data-bs-toggle="tab" role="tab" aria-controls="vf-projects" aria-selected="false">Projek</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#vf-products" data-bs-toggle="tab" role="tab" aria-controls="vf-products" aria-selected="false">Produk</a>
                </li>
            @endif

            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#vf-files" data-bs-toggle="tab" role="tab" aria-controls="vf-files" aria-selected="false">Fail</a>
            </li>
        </ul>
    </div>

    <!-- TAB CONTENT -->
    <div class="modern-tab-content tab-content">
        
        <!-- 1. MAKLUMAT SYARIKAT -->
        <div class="tab-pane active" id="vf-main">
            <div class="form-grid-2">
                <!-- Left Column -->
                <div>
                    @if(!isset($vendor))
                        {!! Former::text('email')->label('Alamat Emel')->addClass('x-uppercase')->required() !!}	
                        {!! Former::text('registration')->required()->placeholder("Aksara, Nombor dan tanda '-' Sahaja")->label('No. Pendaftaran') !!}
                    @else
                        <?php 
                            $email = Former::text('email')->label('Alamat Emel')->addClass('x-uppercase')->forceValue($vendor->user->email);
                            if(!Auth::user()->hasRole('Admin')) $email = $email->disabled(); 
                        ?>
                        {!! $email !!}
                        <?php 
                            $registration = Former::text('registration')->label('No Pendaftaran');
                            if(!Auth::user()->hasRole('Admin')) $registration = $registration->disabled(); 
                        ?>
                        {!! $registration !!}
                    @endif

                    {!! Former::text('name')->label('Nama Syarikat / Perniagaan')->required() !!}
                    
                    @if (Auth::user() && !Auth::user()->hasRole('Vendor') && $disable_create_flaq == 0 )
                        {!! Former::textarea('address')->label('Alamat')->rows(4)->required() !!}
                    @elseif ($disable_create_flaq == 3 )
                        {!! Former::textarea('address')->label('Alamat')->rows(4)->required() !!}
                    @else( isset($disable_create_flaq) && $disable_create_flaq == 1 )
                        {!! Former::textarea('address')->label('Alamat')->rows(4)->readonly() !!}
                    @endif

                    <?php $districts = []; foreach(App\Vendor::$districts as $key => $val) $districts[$key] = strtoupper($val); ?>
                    <?php if (isset($vendor)) { $district = $vendor['district_id'] ?: '0'; } else { $district = null; } ?>

                    @if (Auth::user() && !Auth::user()->hasRole('Vendor') && $disable_create_flaq == 0 )
                        {!! Former::select('district_id')->label('Daerah')->options($districts)->placeholder('Pilihan daerah...')->disabled(Auth::user()->hasRole('Vendor') && !is_null($vendor->approval_1_id))->value($district)->required() !!}
                    @elseif ($disable_create_flaq == 3 )
                        {!! Former::select('district_id')->label('Daerah')->options($districts)->placeholder('Pilihan daerah...')->disabled(Auth::user()->hasRole('Vendor') && !is_null($vendor->approval_1_id))->value($district)->required() !!}
                    @else
                        {!! Former::select('district_id')->label('Daerah')->options($districts)->placeholder('Pilihan daerah...')->disabled(Auth::user()->hasRole('Vendor') && !is_null($vendor->approval_1_id))->value($district)->disabled() !!}
                    @endif

                    <div id="state_id_div" class="mb-3" style="{{ ($district == 0) ? '' : 'display:none' }}">
                        <label for="state_id" class="form-label">Negeri<sup>*</sup></label>
                        <select class="form-control" name="state_id" id="state_id" {{ (Auth::user() && !Auth::user()->hasRole('Vendor')) || $disable_create_flaq == 3 ? '' : 'disabled' }}>
                            <option value="0" disabled selected>Pilihan Negeri...</option>
                            @foreach ($country_states as $state)
                                <option value="{{ $state->id }}" {{ $vendor->state_id == $state->id ? "selected" : "" }}>{{ $state->description }}</option>
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
                        <input class="form-control valid" date-date-start-date="{{ date('d/m/Y') }}" required aria-required="true" id="ssm_expiry" type="text" name="ssm_expiry" value="{{ (isset($vendor->ssm_expiry) && $vendor->ssm_expiry != "") ? $vendor->ssm_expiry->format('d/m/Y') : date('d/m/Y') }}">
                    </div>

                    <div class="mb-3">
                        <label for="authorized_capital" class="form-label">Modal Dibenarkan</label>
                        <div class="modern-input-group">
                            <select class="form-control currency-select" name="authorized_capital_currency" id="authorized_capital_currency">
                                @foreach(App\Vendor::$currencies as $key => $value)
                                    <option value="{{ $key }}" {{ (isset($vendor) && $vendor->authorized_capital_currency == $key) || (!isset($vendor) && $key == 'MYR') ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            <input class="form-control" id="authorized_capital" type="text" name="authorized_capital" value="{{ isset($vendor) ? $vendor->authorized_capital : '0.00'}}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="paidup_capital" class="form-label">Modal Berbayar</label>
                        <div class="modern-input-group">
                            <select class="form-control currency-select" name="paidup_capital_currency" id="paidup_capital_currency">
                                @foreach(App\Vendor::$currencies as $key => $value)
                                    <option value="{{ $key }}" {{ (isset($vendor) && $vendor->paidup_capital_currency == $key) || (!isset($vendor) && $key == 'MYR') ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            <input class="form-control" id="paidup_capital" type="text" name="paidup_capital" value="{{ isset($vendor) ? $vendor->paidup_capital : '0.00'}}">
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
            <div class="form-grid-2">
                <div>
                    {!! Former::text('officer_name')->label('Nama Pegawai')->required() !!}
                    {!! Former::text('officer_designation')->label('Jawatan Pegawai')->required() !!}
                </div>
                <div>
                    {!! Former::text('officer_tel')->label('No. Telefon')->required() !!}
                    @if(!isset($vendor))
                        {!! Former::password('password')->label('Kata Laluan')->required() !!}
                        {!! Former::password('password_confirmation')->label('Sahkan Kata Laluan')->required() !!}
                    @endif
                </div>
            </div>
        </div>

        <?php if(!isset($vendor->approval_1_id) || Auth::user()->can('Vendor:override')) : ?>
        <!-- 3. MOF -->
        <div class="tab-pane" id="vf-mof">
            <div class="form-grid-2">
                <div>
                    {!! Former::text('mof_ref_no')->label('No Rujukan Pendaftaran MOF') !!}
                    <div class="mb-3">
                        <label for="mof_start_date" class="form-label">Tarikh Aktif</label>
                        <div class="modern-input-group">
                            <input class="form-control x-uppercase" id="mof_start_date" type="text" name="mof_start_date" value="{{ isset($vendor) && !empty($vendor->mof_start_date) ? Carbon\Carbon::parse($vendor->mof_start_date)->format('j M Y') : '' }}">
                            <div class="addon">hingga</div>
                            <input class="form-control x-uppercase" id="mof_end_date" type="text" name="mof_end_date" value="{{ isset($vendor) && !empty($vendor->mof_end_date) ? Carbon\Carbon::parse($vendor->mof_end_date)->format('j M Y') : '' }}">
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
                    {!! Former::select('mof_codes')->id('mof_codes')->name('mof_codes[]')->label('Kod Bidang')->multiple(true)->placeholder('Pilih kod bidang MOF')->class('selectize')->options(App\Code::where('type', 'mof')->orderBy('code')->get()->pluck('label', 'id'), isset($vendor) ? $vendor->vendorCodes()->where('code_type', 'mof')->pluck('code_id') : '') !!}
                </div>
            </div>
        </div>

        <!-- 4. CIDB -->
        <div class="tab-pane" id="vf-cidb">
            <div class="form-grid-2">
                <!-- Top Section -->
                <div>
                    {!! Former::text('cidb_ref_no')->label('No Sijil CIDB') !!}
                    <div class="mb-3">
                        <label for="cidb_start_date" class="form-label">Tarikh Aktif</label>
                        <div class="modern-input-group">
                            <input class="form-control x-uppercase" id="cidb_start_date" type="text" name="cidb_start_date" value="{{ isset($vendor) && !empty($vendor->cidb_start_date) ? Carbon\Carbon::parse($vendor->cidb_start_date)->format('j M Y') : '' }}">
                            <div class="addon">hingga</div>
                            <input class="form-control x-uppercase" id="cidb_end_date" type="text" name="cidb_end_date" value="{{ isset($vendor) && !empty($vendor->cidb_end_date) ? Carbon\Carbon::parse($vendor->cidb_end_date)->format('j M Y') : '' }}">
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
                        <!-- Template Item -->
                        <div id="cidb_group_template" class="repeater-item">
                            <input type="hidden" id="cidb_group_#index#_id" class="cidb-group-id" name="cidb_group[#index#][id]">
                            
                            <div class="fields-wrapper">
                                <div>
                                    <label style="font-size: 0.7rem; color:#94a3b8; text-transform:uppercase; font-weight:700;">Gred</label>
                                    <select id="cidb_group_#index#_code_id" class="cidb_group-code_id form-control selectize" name="cidb_group[#index#][code_id]" data-tracker="cidb_group_tracker" onchange="updateOption(this)">
                                        <option disabled="disabled" selected="selected" value="">Sila pilih Gred</option>
                                        @foreach(App\Code::where('type', 'cidb-g')->orderBy('code', 'asc')->get() as $code)
                                            <option value="{{$code->id}}">{{ $code->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label style="font-size: 0.7rem; color:#94a3b8; text-transform:uppercase; font-weight:700;">Bidang Pengkhususan</label>
                                    <select id="cidb_group_#index#_codes" class="cidb_group-codes form-control selectize" name="cidb_group[#index#][codes][]" multiple="multiple">
                                        <option disabled="disabled" value="">Pilih Bidang</option>
                                        @foreach(App\Code::where('type', 'cidb-c')->orderBy('code', 'asc')->get() as $code)
                                            <option value="{{ $code->id }}">{{ $code->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="action-wrapper">
                                <a class="btn btn-danger btn-sm btn-delete-cidb_group" id="cidb_group_remove_current" style="height: 42px; display: inline-flex; align-items: center; justify-content: center; width: 42px; border-radius: 8px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                </a>
                            </div>
                        </div>

                        <div id="cidb_group_noforms_template" class="text-center p-3 text-muted">
                            Tiada maklumat ditambah. Sila tekan butang tambah di bawah.
                        </div>

                        <div id="cidb_group_controls">
                            <div id="cidb_group_add">
                                <a class="btn-add-repeater">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> 
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
        <div class="tab-pane" id="vf-shareholders" ng-controller="ItemController" <?php if(isset($vendor)) { ?> data-remote="{{ asset('vendor/'.$vendor->id.'/shareholders') }}" <?php } ?> >
            <input type="hidden" name="deleted[shareholder][]" ng-repeat="item in deletedItems" ng-value="item.id">
            
            <table class="clean-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>IC / Pasport</th>
                        <th>Kewarganegaraan</th>
                        <th>Taraf</th>
                        <th ng-if="!show" width="100" class="text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="item in items" ng-class="item == editingItem && 'editing'">
                        <td ng-bind="item.name"></td>
                        <td ng-bind="item.identity"></td>
                        <td ng-bind="item.nationality"></td>
                        <td ng-bind="item.bumiputera_status"></td>
                        <td class="text-center" ng-if="!show">
                            <input type="hidden" name="shareholder[id][]" ng-value="item.id">
                            <input type="hidden" name="shareholder[name][]" ng-value="item.name">
                            <input type="hidden" name="shareholder[identity][]" ng-value="item.identity">
                            <input type="hidden" name="shareholder[nationality][]" ng-value="item.nationality">
                            <input type="hidden" name="shareholder[bumiputera_status][]" ng-value="item.bumiputera_status">
                            <div ng-show="item != editingItem">
                                <button type="button" class="btn btn-info btn-sm" ng-click="edit(item)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" ng-click="remove($index)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </div>
                            <div ng-show="item == editingItem">
                                <span class="badge text-bg-warning">Editing</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tfoot ng-if="!show">
                    <tr ng-keyup="setHasNewItem()" style="background: #f8fafc;">
                        <td><input class="form-control input-sm" ng-keypress="handleKeypress($event)" ng-model="newItem.name" ng-keyup="newItem.name=newItem.name.toUpperCase()" type="text" placeholder="Nama Penuh"></td>
                        <td><input class="form-control input-sm" ng-keypress="handleKeypress($event)" ng-model="newItem.identity" type="text" placeholder="IC / Passport"></td>
                        <td>
                            <select class="form-control input-sm" name="nat" ng-model="newItem.nationality">
                                @foreach(App\Vendor::$nationalities as $key => $value)
                                    <option value="{{ $key }}" {{ $key == 'MALAYSIAN' ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><select class="form-control input-sm" ng-model="newItem.bumiputera_status" ng-options="type for type in shareHolderTypes"></select></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-primary btn-sm" ng-click="save()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" ng-click="clear()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <h4 style="margin-top: 2rem; font-size: 1rem; font-weight: 700;">Ringkasan Pegangan Saham <sup>*</sup></h4>
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
                                <input ng-init="percentages.bumi=({{ Request::old('bumi_percentage', isset($vendor) ? $vendor->bumi_percentage : 0) }} || 0)"  name="bumi_percentage" ng-model="percentages.bumi" min="0" type="number" class="form-control">
                                <div class="addon">%</div>
                            </div>
                        </td>
                        <td>
                            <div class="modern-input-group">
                                <input ng-init="percentages.nonbumi=({{ Request::old('nonbumi_percentage', isset($vendor) ? $vendor->nonbumi_percentage : 0) }} || 0)"  name="nonbumi_percentage" ng-model="percentages.nonbumi" min="0" type="number" class="form-control">
                                <div class="addon">%</div>
                            </div>
                        </td>
                        <td>
                            <div class="modern-input-group">
                                <input ng-init="percentages.foreigner=({{ Request::old('foreigner_percentage', isset($vendor) ? $vendor->foreigner_percentage : 0) }} || 0)"  name="foreigner_percentage" ng-model="percentages.foreigner" min="0" type="number" class="form-control">
                                <div class="addon">%</div>
                            </div>
                        </td>
                        <td>
                            <div class="modern-input-group">
                                <input ng-init="(percentages.bumi + percentages.nonbumi + percentages.foreigner)" ng-value="(percentages.bumi + percentages.nonbumi + percentages.foreigner)" class="form-control" disabled="disabled">
                                <div class="addon">%</div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- 6. PENGARAH -->
        <div class="tab-pane" id="vf-directors" ng-controller="ItemController" <?php if(isset($vendor)) { ?> data-remote="{{ asset('vendor/'.$vendor->id.'/directors') }}" <?php } ?> >
            <input type="hidden" name="deleted[directors][]" ng-repeat="item in deletedItems" ng-value="item.id">
            <table class="clean-table">
                <thead>
                    <tr>
                        <th class="col-4">Nama</th>
                        <th class="col-2">IC / Pasport</th>
                        <th>Kewarganegaraan</th>
                        <th>Jawatan</th>
                        <th ng-if="!show" width="100" class="text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="item in items" ng-class="item == editingItem && 'editing'">
                        <td ng-bind="item.name"></td>
                        <td ng-bind="item.identity"></td>
                        <td ng-bind="item.nationality"></td>
                        <td ng-bind="item.designation"></td>
                        <td class="text-center" ng-if="!show">
                            <input type="hidden" name="director[id][]" ng-value="item.id">
                            <input type="hidden" name="director[name][]" ng-value="item.name">
                            <input type="hidden" name="director[identity][]" ng-value="item.identity">
                            <input type="hidden" name="director[nationality][]" ng-value="item.nationality">
                            <input type="hidden" name="director[designation][]" ng-value="item.designation">
                            <div ng-show="item != editingItem">
                                <button type="button" class="btn btn-info btn-sm" ng-click="edit(item)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" ng-click="remove($index)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </div>
                            <div ng-show="item == editingItem">
                                <span class="badge text-bg-warning">Editing</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tfoot ng-if="!show">
                    <tr ng-keyup="setHasNewItem()" style="background: #f8fafc;">
                        <td><input class="form-control input-sm" ng-keypress="handleKeypress($event)" ng-model="newItem.name" ng-keyup="newItem.name=newItem.name.toUpperCase()" type="text" placeholder="Nama Penuh"></td>
                        <td><input class="form-control input-sm" ng-keypress="handleKeypress($event)" ng-model="newItem.identity" type="text" placeholder="IC / Passport"></td>
                        <td>
                            <select class="form-control input-sm" name="nat" ng-model="newItem.nationality">
                                @foreach(App\Vendor::$nationalities as $key => $value)
                                    <option value="{{ $key }}" {{ $key == 'MALAYSIAN' ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="form-control input-sm" name="nat" ng-model="newItem.designation">
                                @foreach(App\Vendor::$directorDesignations as $key => $value)
                                    <option value="{{ $key }}" {{ $key == 'PENGARAH' ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-primary btn-sm" ng-click="save()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" ng-click="clear()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if(isset($vendor) && $vendor->approval_1_id > 0)
            <!-- 7. KAKITANGAN -->
            <div class="tab-pane" id="vf-contacts" ng-controller="ItemController" <?php if(isset($vendor)) { ?> data-remote="{{ asset('vendor/'.$vendor->id.'/contacts') }}" <?php } ?> >
                <input type="hidden" name="deleted[contact][]" ng-repeat="item in deletedItems" ng-value="item.id">
                <table class="clean-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jawatan</th>
                            <th>Warga Negara</th>
                            <th>Taraf</th>
                            <th ng-if="!show" width="100" class="text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="item in items" ng-class="item == editingItem && 'editing'">
                            <td ng-bind="item.name"></td>
                            <td ng-bind="item.designation"></td>
                            <td ng-bind="item.nationality"></td>
                            <td ng-bind="item.status"></td>
                            <td class="text-center" ng-if="!show">
                                <input type="hidden" name="contact[id][]" ng-value="item.id">
                                <input type="hidden" name="contact[name][]" ng-value="item.name">
                                <input type="hidden" name="contact[designation][]" ng-value="item.designation">
                                <input type="hidden" name="contact[nationality][]" ng-value="item.nationality">
                                <input type="hidden" name="contact[status][]" ng-value="item.status">
                                <div ng-show="item != editingItem">
                                    <button type="button" class="btn btn-info btn-sm" ng-click="edit(item)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button>
                                    <button type="button" class="btn btn-danger btn-sm" ng-click="remove($index)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></button>
                                </div>
                                <div ng-show="item == editingItem">
                                    <span class="badge text-bg-warning">Editing</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot ng-if="!show">
                        <tr ng-keyup="setHasNewItem()" style="background: #f8fafc;">
                            <td><input class="form-control input-sm" ng-keypress="handleKeypress($event)" ng-model="newItem.name" ng-keyup="newItem.name=newItem.name.toUpperCase()" type="text" placeholder="Nama Penuh"></td>
                            <td><input class="form-control input-sm" ng-keypress="handleKeypress($event)" ng-model="newItem.designation" ng-keyup="newItem.designation=newItem.designation.toUpperCase()" type="text" placeholder="Jawatan"></td>
                            <td>
                                <select class="form-control input-sm" name="nat" ng-model="newItem.nationality">
                                    @foreach(App\Vendor::$nationalities as $key => $value)
                                        <option value="{{ $key }}" {{ $key == 'MALAYSIAN' ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><select class="form-control input-sm" ng-model="newItem.status" ng-options="type for type in shareHolderTypes"></select></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-primary btn-sm" ng-click="save()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></button>
                                <button type="button" class="btn btn-secondary btn-sm" ng-click="clear()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- 8. ANUGERAH -->
            <div class="tab-pane" id="vf-awards" ng-controller="ItemController" <?php if(isset($vendor)) { ?> data-remote="{{ asset('vendor/'.$vendor->id.'/awards') }}" <?php } ?> >
                <input type="hidden" name="deleted[award][]" ng-repeat="item in deletedItems" ng-value="item.id">
                <table class="clean-table">
                    <thead><tr><th>Nama</th><th>Keterangan</th><th>Pemberi</th><th ng-if="!show" width="100" class="text-center">Tindakan</th></tr></thead>
                    <tbody>
                        <tr ng-repeat="item in items" ng-class="item == editingItem && 'editing'">
                            <td ng-bind="item.name"></td><td ng-bind="item.description"></td><td ng-bind="item.by"></td>
                            <td class="text-center" ng-if="!show">
                                <input type="hidden" name="award[id][]" ng-value="item.id">
                                <input type="hidden" name="award[name][]" ng-value="item.name">
                                <input type="hidden" name="award[description][]" ng-value="item.description">
                                <input type="hidden" name="award[by][]" ng-value="item.by">
                                <div ng-show="item != editingItem"><button type="button" class="btn btn-info btn-sm" ng-click="edit(item)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button><button type="button" class="btn btn-danger btn-sm" ng-click="remove($index)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></button></div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot ng-if="!show"><tr ng-keyup="setHasNewItem()" style="background:#f8fafc;"><td><input class="form-control input-sm" ng-model="newItem.name" type="text"></td><td><input class="form-control input-sm" ng-model="newItem.description" type="text"></td><td><input class="form-control input-sm" ng-model="newItem.by" type="text"></td><td class="text-center"><button type="button" class="btn btn-primary btn-sm" ng-click="save()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></button> <button type="button" class="btn btn-secondary btn-sm" ng-click="clear()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button></td></tr></tfoot>
                </table>
            </div>

            <!-- 9. ASET -->
            <div class="tab-pane" id="vf-assets" ng-controller="ItemController" <?php if(isset($vendor)) { ?> data-remote="{{ asset('vendor/'.$vendor->id.'/assets') }}" <?php } ?> >
                <input type="hidden" name="deleted[asset][]" ng-repeat="item in deletedItems" ng-value="item.id">
                <table class="clean-table">
                    <thead><tr><th>Nama</th><th width="200">Nilai (RM)</th><th ng-if="!show" width="100" class="text-center">Tindakan</th></tr></thead>
                    <tbody>
                        <tr ng-repeat="item in items" ng-class="item == editingItem && 'editing'">
                            <td ng-bind="item.name"></td><td ng-bind="item.value"></td>
                            <td class="text-center" ng-if="!show">
                                <input type="hidden" name="asset[id][]" ng-value="item.id"><input type="hidden" name="asset[name][]" ng-value="item.name"><input type="hidden" name="asset[value][]" ng-value="item.value">
                                <div ng-show="item != editingItem"><button type="button" class="btn btn-info btn-sm" ng-click="edit(item)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button><button type="button" class="btn btn-danger btn-sm" ng-click="remove($index)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></button></div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot ng-if="!show"><tr ng-keyup="setHasNewItem()" style="background:#f8fafc;"><td><input class="form-control input-sm" ng-model="newItem.name" type="text"></td><td><div class="modern-input-group"><div class="addon">RM</div><input class="form-control input-sm" ng-model="newItem.value" type="text"></div></td><td class="text-center"><button type="button" class="btn btn-primary btn-sm" ng-click="save()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></button> <button type="button" class="btn btn-secondary btn-sm" ng-click="clear()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button></td></tr></tfoot>
                </table>
            </div>
            
            <!-- 10. PROJEK -->
            <div class="tab-pane" id="vf-projects" ng-controller="ItemController" <?php if(isset($vendor)) { ?> data-remote="{{ asset('vendor/'.$vendor->id.'/projects') }}" <?php } ?> >
                <input type="hidden" name="deleted[project][]" ng-repeat="item in deletedItems" ng-value="item.id">
                <table class="clean-table">
                    <thead><tr><th>Nama</th><th>Pelanggan</th><th>Tempoh</th><th>Nilai (RM)</th><th>Siap</th><th ng-if="!show" width="100" class="text-center">Tindakan</th></tr></thead>
                    <tbody>
                        <tr ng-repeat="item in items" ng-class="item == editingItem && 'editing'">
                            <td ng-bind="item.name"></td><td ng-bind="item.customer"></td><td ng-bind="item.period"></td><td ng-bind="item.value"></td><td><input type="checkbox" ng-checked="item.done" disabled></td>
                            <td class="text-center" ng-if="!show">
                                <input type="hidden" name="project[id][]" ng-value="item.id"><input type="hidden" name="project[name][]" ng-value="item.name"><input type="hidden" name="project[value][]" ng-value="item.value"><input type="hidden" name="project[customer][]" ng-value="item.customer"><input type="hidden" name="project[period][]" ng-value="item.period"><input type="hidden" name="project[done][]" ng-value="item.done ? 'true' : ''">
                                <div ng-show="item != editingItem"><button type="button" class="btn btn-info btn-sm" ng-click="edit(item)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button><button type="button" class="btn btn-danger btn-sm" ng-click="remove($index)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></button></div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot ng-if="!show"><tr ng-keyup="setHasNewItem()" style="background:#f8fafc;"><td><input class="form-control input-sm" ng-model="newItem.name" type="text"></td><td><input class="form-control input-sm" ng-model="newItem.customer" type="text"></td><td><input class="form-control input-sm" ng-model="newItem.period" type="text"></td><td><div class="modern-input-group"><div class="addon">RM</div><input class="form-control input-sm" ng-model="newItem.value" type="text"></div></td><td><input ng-model="newItem.done" type="checkbox"></td><td class="text-center"><button type="button" class="btn btn-primary btn-sm" ng-click="save()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></button> <button type="button" class="btn btn-secondary btn-sm" ng-click="clear()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button></td></tr></tfoot>
                </table>
            </div>

            <!-- 11. PRODUK -->
            <div class="tab-pane" id="vf-products" ng-controller="ItemController" <?php if(isset($vendor)) { ?> data-remote="{{ asset('vendor/'.$vendor->id.'/products') }}" <?php } ?> >
                 <input type="hidden" name="deleted[product][]" ng-repeat="item in deletedItems" ng-value="item.id">
                 <table class="clean-table">
                     <thead><tr><th>Nama</th><th>Keterangan</th><th>Pengguna</th><th ng-if="!show" width="100" class="text-center">Tindakan</th></tr></thead>
                     <tbody>
                         <tr ng-repeat="item in items" ng-class="item == editingItem && 'editing'">
                             <td ng-bind="item.name"></td><td ng-bind="item.description"></td><td ng-bind="item.implementations"></td>
                             <td class="text-center" ng-if="!show">
                                 <input type="hidden" name="product[id][]" ng-value="item.id"><input type="hidden" name="product[name][]" ng-value="item.description"><input type="hidden" name="product[description][]" ng-value="item.name"><input type="hidden" name="product[implementations][]" ng-value="item.implementations">
                                 <div ng-show="item != editingItem"><button type="button" class="btn btn-info btn-sm" ng-click="edit(item)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button><button type="button" class="btn btn-danger btn-sm" ng-click="remove($index)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></button></div>
                             </td>
                         </tr>
                     </tbody>
                     <tfoot ng-if="!show"><tr ng-keyup="setHasNewItem()" style="background:#f8fafc;"><td><input class="form-control input-sm" ng-model="newItem.name" type="text"></td><td><input class="form-control input-sm" ng-model="newItem.description" type="text"></td><td><input class="form-control input-sm" ng-model="newItem.implementations" type="text"></td><td class="text-center"><button type="button" class="btn btn-primary btn-sm" ng-click="save()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></button> <button type="button" class="btn btn-secondary btn-sm" ng-click="clear()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button></td></tr></tfoot>
                 </table>
            </div>
        @endif

        <!-- 12. FAIL -->
        <div class="tab-pane" id="vf-files">
            <div class="alert alert-warning" style="border-radius: 8px; display:flex; align-items:center; gap:10px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                Hanya fail beformat PDF dan bersaiz maksimum 5MB boleh dimuat naik.
            </div>
            
            <div class="form-grid-1">
                <?php $ssm = Former::file('ssm')->label('Sijil SSM')->accept('application/pdf')->addClass('file_input'); ?>
                <?php if(!isset($vendor) || !$vendor->completed || !$vendor->hasFile('ssm')) $ssm = $ssm->required(); ?>
                {!! $ssm !!}

                @if( !isset($vendor) || !$vendor->completed || Auth::user()->hasRole('Admin') )
                    {!! Former::file('mof')->label('Sijil MOF')->accept('application/pdf')->addClass('file_input') !!}
                    {!! Former::file('mof_bumiputera')->label('Sijil Bumiputera MOF')->accept('application/pdf')->addClass('file_input') !!}
                    {!! Former::file('cidb')->label('Sijil CIDB & SPKK')->accept('application/pdf')->addClass('file_input')->help('Muat naik fail sijil SPKK & CIDB sebagai satu fail sahaja.') !!}
                    {!! Former::file('cidb_bumiputera')->label('Sijil Bumiputera PKK')->accept('application/pdf')->addClass('file_input') !!}
                @endif
            </div>

            @if(isset($vendor) && count($vendor->uploads) > 0)
                <h3 style="margin-top: 2rem; color: #374151; font-weight: 600;">Fail Dimuat Naik</h3>
                <br>
                <div class="table-responsive">
                    {!! $vendor->uploadsTable() !!}
                </div>
            @endif
        </div>
    </div>
</div>
@section('script')
    <script src="{{ asset('js/ajax-loader.js') }}"></script>
    <script src="{{ asset('js/item-controller.js') }}"></script>
    <script src="{{ asset('js/two-way-binding.js') }}"></script>
    <script src="{{ asset('js/form-validator.js') }}"></script>
    <script src="{{ asset('js/percentage-calculator.js') }}"></script>
    <script src="{{ asset('js/vendor-form-init.js') }}"></script>
@endsection
<!-- script below is not use used anymore the scrript-closeTemporary section is not exist -->
@section('scripts-closeTemporary')
	<script>
		var validateMofCidb = @if(!isset($vendor) || !$vendor->completed) true @else false @endif;
		var nationalities   = @json(App\Vendor::$nationalities);

		function updateOption(value) { $(this).remove(value); }

		function selectize_select(id) {
		    $(id).find('select.selectize').each(function(){
		        if(!this.selectize) $(this).selectize();
		    });
		}

		$("#cidb_group").sheepIt({
			separator: '',
			minFormsCount: 0,
			iniFormsCount: 1,
			allowAdd: true,
			@if(isset($vendor) && $vendor->cidbGrades)
				data: [
						@foreach($vendor->cidbGrades()->orderBy('id', 'asc')->get() as $grade)
							{
							'cidb_group_#index#_id': "{{ $grade->id }}",
							'cidb_group_#index#_code_id': "{{ $grade->code_id }}",
							'cidb_group_#index#_codes': @json($grade->children()->pluck('code_id'))
							},
						@endforeach
					]
			@endif
		});
		selectize_select("#cidb_group");

		$("#cidb_group_add").click(function(){
			let existing_cidb_group = $('.cidb_group-code_id[data-tracker="cidb_group_tracker"]');			
			let selected = [];
			$.each(existing_cidb_group, function( index, element ) { selected.push(element.value); });
			$.each(existing_cidb_group, function( index, element ) {
				if(index != 0) {
					var select = $('#' + element.id).selectize();
					selected.forEach(row => {
						if (row != '') { if (select.val() != row) { select[0].selectize.removeOption(row); } }
					});
				}
			});
			selectize_select('#cidb_group');
		});
		
		$(".btn-delete-cidb_group").click(function(){
		    	id = $(this).siblings('.cidb-group-id').val();
		    	if(id) {
		        	deleted = $('input[name="deleted_cidb_group[]"]:first');
		        	if(deleted.val() == "") { deleted.val(id); } else {
		            new_deleted = deleted.clone();
		            new_deleted.val(id);
		            new_deleted.insertAfter(deleted);
		        	}
		    	}
		});

		$('#district_id').on('change', function(){
			let selected = this.value;
			if (selected == 0) {
				$("#state_id_div").show(); $("#state_id").prop("disabled", false);
			} else {
				$("#state_id_div").hide(); $("#state_id").prop("disabled", true);
			}
		});
	</script>
	<script src="{{ asset('js/definitions.js') }}"></script>
	<script src="{{ asset('js/vendor.js') }}"></script>
@endsection