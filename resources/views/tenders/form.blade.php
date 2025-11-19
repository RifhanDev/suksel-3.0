<?php // array_pop(App\Vendor::$districts);
?>
<div class="row stacked-form">
    <div class="col-lg-2">
        <ul class="nav nav-pills nav-stacked">
            <li role="presentation" class="active"><a href="#tf-main" aria-controls="home" role="tab"
                    data-toggle="tab">Maklumat Tender</a></li>
            <li role="presentation"><a href="#tf-pegawai" aria-controls="home" role="tab" data-toggle="tab">Pegawai
                    Bertanggungjawab</a></li>
            <li role="presentation"><a href="#tf-syarat" aria-controls="home" role="tab" data-toggle="tab">Syarat
                    Tender</a></li>
            <li role="presentation"><a href="#tf-khas" aria-controls="home" role="tab" data-toggle="tab">Syarat
                    Khas</a></li>
            <li role="presentation"><a href="#tf-lawatan" aria-controls="messages" role="tab"
                    data-toggle="tab">Lawatan Tapak</a></li>
            <li role="presentation"><a href="#tf-kod" aria-controls="settings" role="tab" data-toggle="tab">Kod-Kod
                    Bidang</a></li>
            <li role="presentation"><a href="#tf-doc1" aria-controls="settings" role="tab" data-toggle="tab">Dokumen
                    Tender</a></li>
        </ul>
    </div>

    <!-- Tab panes -->
    <div class="tab-content col-lg-10">
        <div role="tabpanel" class="tab-pane active" id="tf-main">
            @if (Auth::user()->hasRole('Admin'))
                {!! Former::select('organization_unit_id')->label('Agensi')->options(App\OrganizationUnit::all()->pluck('name', 'id'))->required() !!}
            @endif
            {!! Former::radios('type')->label('Jenis')->radios([
                    'Tender' => ['name' => 'type', 'value' => 'tender'],
                    'Sebut Harga' => ['name' => 'type', 'value' => 'quotation'],
                ])->required() !!}
            {!! Former::text('ref_number')->label('No Rujukan')->required() !!}
            {!! Former::text('name')->label('Tajuk')->required() !!}
            {!! Former::text('price')->label('Harga Dokumen')->required() !!}

            <div class="form-group">
                <label for="advertise_start_date" class="control-label col-lg-2 col-sm-2">Tarikh Iklan
                    <sup>*</sup></label>
                <div class="col-lg-10 col-sm-10">
                    <div class="input-group">
                        <input class="form-control x-uppercase" id="advertise_start_date" type="text"
                            name="advertise_start_date" required
                            value="{{ Request::old('advertise_start_date', isset($tender) && !empty($tender->advertise_start_date) ? Carbon\Carbon::parse($tender->advertise_start_date)->format('j M Y') : '') }}">
                        <div class="input-group-addon">hingga</div>
                        <input class="form-control x-uppercase" id="advertise_stop_date" type="text"
                            name="advertise_stop_date" required
                            value="{{ Request::old('advertise_stop_date', isset($tender) && !empty($tender->advertise_stop_date) ? Carbon\Carbon::parse($tender->advertise_stop_date)->format('j M Y') : '') }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="document_start_date" class="control-label col-lg-2 col-sm-2">Tarikh Jual
                    <sup>*</sup></label>
                <div class="col-lg-10 col-sm-10">
                    <div class="input-group">
                        <input class="form-control x-uppercase" id="document_start_date" type="text"
                            name="document_start_date" required
                            value="{{ Request::old('document_start_date', isset($tender) && !empty($tender->document_start_date) ? Carbon\Carbon::parse($tender->document_start_date)->format('j M Y') : '') }}">
                        <div class="input-group-addon">hingga</div>
                        <input class="form-control x-uppercase" id="document_stop_date" type="text"
                            name="document_stop_date" required
                            value="{{ Request::old('document_stop_date', isset($tender) && !empty($tender->document_stop_date) ? Carbon\Carbon::parse($tender->document_stop_date)->format('j M Y') : '') }}">
                    </div>
                </div>
            </div>

            <?php $submission_datetime = Former::text('submission_datetime')
                ->label('Tarikh Tutup')
                ->required(); ?>
            <?php if (isset($tender)) {
                $submission_datetime = $submission_datetime->forceValue(Request::old('submission_datetime', Carbon\Carbon::parse($tender->submission_datetime)->format('j M Y')));
            } ?>
            {!! $submission_datetime !!}

            {!! Former::textarea('submission_location_address')->label('Alamat Serahan')->rows(4)->required() !!}
            {!! Former::textarea('briefing_address')->rows(4)->label('Alamat Taklimat') !!}
            <?php $briefing_datetime = Former::text('briefing_datetime')
                ->data_date_format('D MMM YYYY H:mm')
                ->label('Tarikh & Masa Taklimat'); ?>
            <?php if (isset($tender) && !empty($tender->briefing_datetime)) {
                $briefing_datetime = $briefing_datetime->forceValue(Request::old('briefing_datetime', Carbon\Carbon::parse($tender->briefing_datetime)->format('d M Y H:i')));
            } ?>
            {!! $briefing_datetime !!}
            {!! Former::checkbox('briefing_required')->label('Wajib Hadir Taklimat') !!}
            {!! Former::checkbox('allow_exception')->label('Kebenaran Khas') !!}

        </div>

        <div role="tabpanel" class="tab-pane" id="tf-pegawai">
            <div class="row">
                <div class="col-md-6">
                    <div class="row text-center">
                        <label>
                            Pegawai Bertanggungjawab 1{{-- {{ dd(auth()->user()) }} --}}
                        </label>
                        <hr>
                    </div>
                    <div class="form-group required">
                        <label for="default_name" class="control-label col-lg-3 col-sm-3">
                            Nama
                        </label>
                        <div class="col-lg-9 col-sm-9">
                            <input type="text" class="form-control" id="default_name"
                                value="{{ auth()->user()->name }}" readonly>
                            @if (isset($tender->creator->id))
                                <input type="hidden" class="form-control" id="default_creator_id"
                                    name="default_creator_id" value="{{ $tender->creator->id }}" readonly>
                            @endif
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="default_email" class="control-label col-lg-3 col-sm-3">
                            E-mel
                        </label>
                        <div class="col-lg-9 col-sm-9">
                            <input type="email" class="form-control" id="default_email"
                                value="{{ auth()->user()->email }}" readonly>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="default_tel" class="control-label col-lg-3 col-sm-3">
                            No. Tel<sup>*</sup>
                        </label>
                        <div class="col-lg-9 col-sm-9">
                            @if (isset(auth()->user()->tel))
                                <input type="text" class="form-control" id="default_tel" name="default_tel"
                                    value="{{ auth()->user()->tel }}" required>
                            @else
                                <input type="text" class="form-control" id="default_tel" name="default_tel"
                                    required>
                            @endif
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="default_department" class="control-label col-lg-3 col-sm-3">
                            Jabatan<sup>*</sup>
                        </label>
                        <div class="col-lg-9 col-sm-9">
                            @if (isset(auth()->user()->department))
                                <input type="text" class="form-control" id="default_department"
                                    name="default_department" value="{{ auth()->user()->department }}" required>
                            @else
                                <input type="text" class="form-control" id="default_department"
                                    name="default_department" required>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row text-center">
                        <label>
                            Pegawai Bertanggungjawab 2 (Pilihan)
                        </label>
                        <hr>
                    </div>
                    <div class="form-group required">
                        <label for="name" class="control-label col-lg-3 col-sm-3">
                            Nama
                        </label>
                        <div class="col-lg-9 col-sm-9">
                            <select class="form-control" name="officer_id" id="officer_id" style="width: 100%;">
                                <option value="">-- Pilih Pegawai --</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="email" class="control-label col-lg-3 col-sm-3">
                            E-mel
                        </label>
                        <div class="col-lg-9 col-sm-9">
                            <input type="email" class="form-control" id="email" name="email" readonly>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="tel" class="control-label col-lg-3 col-sm-3">
                            No. Tel
                        </label>
                        <div class="col-lg-9 col-sm-9">
                            <input type="text" class="form-control" id="tel" name="tel">
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="department" class="control-label col-lg-3 col-sm-3">
                            Jabatan
                        </label>
                        <div class="col-lg-9 col-sm-9">
                            <input type="text" class="form-control" id="department" name="department">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div role="tabpanel" class="tab-pane" id="tf-syarat">
            <div id="tender-rules-editor" class="summernote">
                {!! isset($tender) ? strip_tags($tender->tender_rules, '<b><strong><i><em><u><p><ul><ol><li>') : '' !!}
            </div>
            <textarea name="tender_rules" id="tender-rules" required>
				{!! isset($tender) ? strip_tags($tender->tender_rules, '<b><strong><i><em><u><p><ul><ol><li>') : '' !!}
			</textarea>
            <span class="help-block">Syarat Tender wajib di isi.</span>
        </div>

        <div role="tabpanel" class="tab-pane" id="tf-khas">
            {{-- {!! Former::checkbox('only_selangor')->label('Syarikat Selangor Sahaja')->checked(isset($tender) && !empty($tender->only_selangor))->forceValue(1) !!} --}}
            {{-- {!! Former::select('district_id')->label('Syarikat Daerah')->options(App\Vendor::$districts)->placeholder('Pilihan daerah') !!} --}}

            <div class="form-group">
                <label for="only_selangor1" class="control-label col-lg-3 col-sm-3">Syarikat Selangor Sahaja</label>
                <div class="col-lg-9 col-sm-9">
                    <div class="checkbox">
                        <input id="only_selangor1" type="checkbox" name="only_selangor" value="1"
                            onclick="changeOnlySelangorCheckbox(this)"
                            {{ ($tender->only_selangor ?? 0) == 1 ? 'checked' : '' }} required>
                    </div>
                </div>
            </div>

            {{-- <div class="hr-with-label">
                <hr>
                <span class="label">Atau</span>
            </div> --}}

            <div class="form-group">
                <label for="only_selangor2" class="control-label col-lg-3 col-sm-3">Syarikat Selangor <br>Dan Lain-lain Negeri</label>
                <div class="col-lg-9 col-sm-9">
                    <div class="checkbox">
                        <input id="only_selangor2" type="checkbox" name="only_selangor" value="2"
                            onclick="changeOnlySelangorCheckbox(this)"
                            {{ ($tender->only_selangor ?? 0) == 2 ? 'checked' : '' }} required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="only_selangor3" class="control-label col-lg-3 col-sm-3">Seluruh Malaysia</label>
                <div class="col-lg-9 col-sm-9">
                    <div class="checkbox">
                        <input id="only_selangor3" type="checkbox" name="only_selangor" value="3"
                            onclick="changeOnlySelangorCheckbox(this)"
                            {{ ($tender->only_selangor ?? 0) == 3 ? 'checked' : '' }} required>
                    </div>
                </div>
            </div>

            <div id="main_district_div" style="{{ ($tender->only_selangor ?? 0) == 3 ? 'display:none' : '' }}">

                <div id="clone_district_div" name="clone_district_div" class="form-group">
                    <label for="district_id_new_0" class="control-label col-lg-3 col-sm-3">Syarikat Daerah/
                        Negeri</label>
                    <div class="col-lg-9 col-sm-9">
                        @php
                            $district_list_rule = json_decode($tender->district_list_rule ?? '[]');
                        @endphp
    
                        @if (count($district_list_rule) > 0)
                            @foreach ($district_list_rule as $row_id => $district_rule)
                                @php
                                    if ($row_id == 0) {
                                        $row_id = '';
                                    } else {
                                        $row_id = $row_id + 1;
                                    }
                                @endphp
    
                                <div id="tobe_cloned{{ $row_id }}" name="tobe_cloned"
                                    class="tobe_cloned pl-0 ml-0 pb-2 copy{{ $row_id }}">
    
                                    @if ($row_id == '')
                                        <div id="custom-label{{ $row_id }}" style="display: none">
                                            <div class="col-lg-11 col-sm-11 text-center">
                                                <span>------------------------ ATAU ------------------------</span>
                                            </div>
                                        </div>
                                    @else
                                        <div id="custom-label{{ $row_id }}" style="">
                                            <div class="col-lg-11 col-sm-11 text-center">
                                                <span>------------------------ ATAU ------------------------</span>
                                            </div>
                                        </div>
                                    @endif
    
                                    @if ($district_rule->district_id == 0)
                                        <div class="col-lg-6 col-sm-6" id="district_id_div{{ $row_id }}"
                                            name="district_id_div">
                                            <select class="form-control district_select"
                                                id="district_id_new{{ $row_id }}" name="district_id_new[]">
                                                <option value="0" selected>Pilihan Daerah/ Negeri...</option>
                                                @foreach (App\Vendor::$districts as $district_id => $district_name)
                                                    @if ($district_id == 0)
                                                        @if ($tender->only_selangor != 1)
                                                            <option value="{{ $district_id }}"
                                                                {{ $district_id == $district_rule->district_id ? 'selected' : '' }}>
                                                                {{ $district_name }}</option>
                                                        @endif
                                                    @else
                                                        <option value="{{ $district_id }}"
                                                            {{ $district_id == $district_rule->district_id ? 'selected' : '' }}>
                                                            {{ $district_name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div id="state_id_div{{ $row_id }}" name="state_id_div"
                                            class="col-lg-5 col-sm-5 " style="">
                                            <select class="form-control state_select"
                                                id="state_id_new{{ $row_id }}" name="state_id_new[]"
                                                style="" {{ ($tender->only_selangor ?? 0) == 1 ? 'disabled' : '' }}>
                                                <option value="0"
                                                    {{ $district_rule->state_id == 0 ? 'selected' : '' }}>Pilihan Negeri...
                                                </option>
                                                @foreach ($country_states as $state)
                                                    <option value="{{ $state->id }}"
                                                        {{ $state->id == $district_rule->state_id ? 'selected' : '' }}>
                                                        {{ $state->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($row_id != '')
                                            <div class="col-lg-1 col-sm-1">
                                                <a class="remove btn btn-danger" href="#"
                                                    onclick="$(this).parent().parent().remove(); return false">Padam</a>
                                            </div>
                                        @endif
                                    @else
                                        <div class="col-lg-11 col-sm-11" id="district_id_div{{ $row_id }}"
                                            name="district_id_div">
    
                                            <select class="form-control district_select"
                                                id="district_id_new{{ $row_id }}" name="district_id_new[]">
    
                                                <option value="0" selected>Pilihan Daerah/ Negeri...</option>
                                                @foreach (App\Vendor::$districts as $district_id => $district_name)
                                                    @if ($district_id == 0)
                                                        @if ($tender->only_selangor != 1)
                                                            <option value="{{ $district_id }}"
                                                                {{ $district_id == $district_rule->district_id ? 'selected' : '' }}>
                                                                {{ $district_name }}</option>
                                                        @endif
                                                    @else
                                                        <option value="{{ $district_id }}"
                                                            {{ $district_id == $district_rule->district_id ? 'selected' : '' }}>
                                                            {{ $district_name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div id="state_id_div{{ $row_id }}" name="state_id_div"
                                            class="col-lg-5 col-sm-5 " style="display:none">
                                            <select class="form-control state_select"
                                                id="state_id_new{{ $row_id }}" name="state_id_new[]"
                                                style="display:none">
                                                <option value="0"
                                                    {{ $district_rule->state_id == 0 ? 'selected' : '' }}>Pilihan Negeri...
                                                </option>
                                                @foreach ($country_states as $state)
                                                    <option value="{{ $state->id }}"
                                                        {{ $state->id == $district_rule->state_id ? 'selected' : '' }}>
                                                        {{ $state->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($row_id != '')
                                            <div class="col-lg-1 col-sm-1">
                                                <a class="remove btn btn-danger" href="#"
                                                    onclick="$(this).parent().parent().remove(); return false">Padam</a>
                                            </div>
                                        @endif
                                        {{-- <div class=""> <div class="col-lg-11 col-sm-11 text-center"> <span>------------------------ ATAU ------------------------</span> </div> </div> --}}
                                    @endif
    
                                </div>
                            @endforeach
                        @else
                            <div id="tobe_cloned" name="tobe_cloned" class="tobe_cloned pl-0 ml-0 pb-2 copy">
                                <div id="custom-label" style="display: none">
                                    <div class="col-lg-11 col-sm-11 text-center"> <span>------------------------ ATAU
                                            ------------------------</span> </div>
                                </div>
    
                                <div class="col-lg-11 col-sm-11" id="district_id_div" name="district_id_div">
                                    <select class="form-control district_select" id="district_id_new"
                                        name="district_id_new[]">
                                        <option value="0" disabled selected>Pilihan Daerah/ Negeri...</option>
                                        @foreach (App\Vendor::$districts as $district_id => $district_name)
                                            <option value="{{ $district_id }}">{{ $district_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="state_id_div" name="state_id_div" class="col-lg-5 col-sm-5 "
                                    style="display:none">
                                    <select class="form-control state_select" id="state_id_new" name="state_id_new[]"
                                        style="display:none">
                                        <option value="0">Pilihan Negeri...</option>
                                        @foreach ($country_states as $state)
                                            <option value="{{ $state->id }}">{{ $state->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
    
                                {{-- <div class="col-lg-11 col-sm-11" id="district_id_div" name="district_id_div">
                                    <select class="form-control district_select" id="district_id_new" name="district_id_new[]">
                                        <option value="0" disabled selected>Pilihan Daerah/ Negeri...</option>
                                        @foreach (App\Vendor::$districts as $district_id => $district_name)
                                            <option value="{{ $district_id }}" >{{ $district_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="state_id_div" name="state_id_div" class="col-lg-5 col-sm-5 " style="display:none">
                                    <select class="form-control state_select" id="state_id_new" name="state_id_new[]" style="display:none" >
                                        <option value="0" disabled selected>Pilihan Negeri...</option>
                                        @foreach ($country_states as $state)
                                            <option value="{{ $state->id }}" >{{ $state->description }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-3 col-sm-3">
                    </div>
                    <div class="col-lg-9 col-sm-9">
                        <div class="col-lg-11 col-sm-11"></div>
                        <div class="col-lg-1 col-sm-1">
                            <div class="">
                                <div class="pt-2">
                                    <p><a href="#" class="copy btn btn-primary" rel=".tobe_cloned">Tambah</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>



            {{-- Backup Working Code --}}
            {{-- <div id="clone_district_div" name="clone_district_div" class="form-group" >
                <label for="district_id_new_0" class="control-label col-lg-3 col-sm-3">Syarikat Daerah/ Negeri<sup>*</sup></label>
                <div class="col-lg-9 col-sm-9">
                    <div id="tobe_cloned" name="tobe_cloned" class="tobe_cloned pl-0 ml-0 pb-2">
                        <div class="col-lg-11 col-sm-11" id="district_id_div" name="district_id_div">
                            <select class="form-control district_select" id="district_id_new" name="district_id_new[]">
                                <option value="0" disabled selected>Pilihan Daerah/ Negeri...</option>
                                @foreach (App\Vendor::$districts as $district_id => $district_name)
                                    <option value="{{ $district_id }}" >{{ $district_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="state_id_div" name="state_id_div" class="col-lg-5 col-sm-5 " style="display:none">
                            <select class="form-control state_select" id="state_id_new" name="state_id_new[]" style="display:none" >
                                <option value="0" selected>Pilihan Negeri...</option>
                                @foreach ($country_states as $state)
                                    <option value="{{ $state->id }}" >{{ $state->description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div> --}}

            
            <hr>
            {!! Former::checkbox('only_bumiputera')->label('Bumiputera Sahaja')->checked(isset($tender) && !empty($tender->only_bumiputera))->forceValue(1) !!}

            {!! Former::checkbox('invitation')->label('Tender Terhad')->checked(isset($tender) && !empty($tender->invitation))->forceValue(1) !!}
            <div class="form-group">
                <label for="only_advertise" class="control-label col-lg-2 col-sm-2">Iklan Sahaja</label>
                <div class="col-lg-10 col-sm-10">
                    <div class="checkbox">
                        <input type="checkbox" name="only_advertise"
                            @if ((isset($tender) && !empty($tender->only_advertise)) || Auth::user()->agency->is_gateway_locked) checked="checked" @endif
                            @if (Auth::user()->agency->is_gateway_locked) disabled="disabled" @endif value="1">
                        <p class="help-block">Sila tandakan <span class="glyphicon glyphicon-ok"></span> sekiranya
                            penjualan dibuat secara manual.</p>
                    </div>
                </div>
            </div>
        </div>

        <div role="tabpanel" class="tab-pane" id="tf-lawatan">
            <div class="row r-h">
                <div class="col-md-3">
                    Tempat Berkumpul
                </div>
                <div class="col-md-3">
                    Alamat Tapak
                </div>
                <div class="col-md-3">
                    Tarikh &amp; Waktu
                </div>
                <div class="col-md-2">
                    Wajib Hadir
                </div>
            </div>

            <div id="site_visit_form">
                <div id="site_visit_form_template">
                    <div class="row r-b-10">
                        <div class="col-md-3">
                            <textarea class="form-control" rows="4" required="true" id="site_visit_form_#index#_meetpoint"
                                name="site_visits[#index#][meetpoint]"></textarea>
                        </div>
                        <div class="col-md-3">
                            <textarea class="form-control" rows="4" required="true" id="site_visit_form_#index#_address"
                                name="site_visits[#index#][address]"></textarea>
                        </div>
                        <div class="col-md-3">
                            <input id="site_visit_form_#index#_datetime" class="sitevisit-date form-control"
                                data-date-format="D MMM YYYY H:mm" name="site_visits[#index#][date]"
                                type="text" />
                        </div>
                        <div class="col-md-2">
                            <input id="site_visit_form_#index#_required" class="sitevisit-required form-control"
                                name="site_visits[#index#][required]" type="checkbox" />
                        </div>
                        <div class="col-md-1">
                            <a class="btn btn-warning btn-raised btn-remove-site-visit">&times;</a>
                            <input id="site_visit_form_#index#_id" type="hidden" name="site_visits[#index#][id]">
                        </div>
                    </div>
                </div>
                <div id="site_visit_form_noforms_template"></div>
                <div id="site_visit_form_controls">
                    <input type="hidden" name="deleted_site_visits[]">
                    <div id="site_visit_form_add"><a class="btn btn-primary btn-raised"><span>Tambah</span></a></div>
                </div>
            </div>
        </div>

        <div role="tabpanel" class="tab-pane" id="tf-kod">
            <div class="form-group">
                <label for="mof_codes" class="control-label col-lg-2 col-sm-2">Kod Bidang MOF</label>
                <div class="col-lg-10 col-sm-10">
                    <div id="mof_form">
                        <div v-for="(code, index) in codes" v-bind:class="'qc-mof-code-form index-' + index"
                            v-bind:data-index="index">
                            <div class="row">
                                <div class="col-lg-3">
                                    <select v-bind:id="'mof_form_' + index + '_inner_rule'"
                                        v-bind:name="'mof_codes[' + index + '][inner_rule]'"
                                        class="mof-code-inner-rule form-control" v-model="code.inner_rule">
                                        <option value="and">Dan</option>
                                        <option value="or">Atau</option>
                                    </select>
                                </div>

                                <div class="col-lg-7">
                                    <select v-bind:id="'mof_form_' + index + '_codes'"
                                        v-bind:name="'mof_codes[' + index + '][codes][]'"
                                        class="mof-code-codes form-control selectize" multiple="multiple"
                                        v-model="code.codes">
                                        @foreach (App\Code::where('type', 'mof')->orderBy('code', 'asc')->get() as $code)
                                            <option value="{{ $code->id }}">{{ $code->label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-2">
                                    <a class="btn btn-warning btn-raised" v-show="codes.length > 1"
                                        v-on:click="deleteForm(index)">&times;</a>
                                </div>
                            </div>

                            <div class="row join-rule" v-show="codes.length > 1 && index < (codes.length - 1)">
                                <div class="col-lg-3 col-lg-offset-3">
                                    <select v-bind:id="'mof_form_' + index + '_join_rule'"
                                        v-bind:name="'mof_codes[' + index + '][join_rule]'"
                                        class="mof-code-join-rule form-control" v-model="code.join_rule">
                                        <option value="and">Dan</option>
                                        <option value="or">Atau</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="mof_form_controls">
                            <a class="btn btn-primary btn-sm" v-on:click="addForm"><span>Tambah</span></a>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            {!! Former::select('mof_cidb_rule')->options(['and' => 'Dan', 'or' => 'Atau'])->label('&nbsp;') !!}
            <hr>

            {!! Former::select('cidb_grade[]')->id('cidb_grade')->label('Gred CIDB')->options(
                    App\Code::where('type', 'cidb-g')->get()->pluck('label', 'id'),
                    isset($tender) ? $tender->cidb_grades->pluck('code_id') : '',
                )->multiple(true)->placeholder('') !!}

            <div class="form-group required">
                <label for="cidb_codes" class="control-label col-lg-2 col-sm-2">Bidang Pengkhususan CIDB</label>
                <div class="col-lg-10 col-sm-10">
                    <div id="cidb_form">
                        <div v-for="(code, index) in codes" v-bind:class="'qc-cidb-code-form index-' + index"
                            v-bind:data-index="index">
                            <div class="row">
                                <div class="col-lg-3">
                                    <select v-bind:id="'cidb_form_' + index + '_inner_rule'"
                                        v-bind:name="'cidb_codes[' + index + '][inner_rule]'"
                                        v-model="code.inner_rule" class="cidb-codes-inner-rule form-control">
                                        <option value="and">Dan</option>
                                        <option value="or">Atau</option>
                                    </select>
                                </div>

                                <div class="col-lg-7">
                                    <select v-bind:id="'cidb_form_' + index + '_codes'"
                                        v-bind:name="'cidb_codes[' + index + '][codes][]'"
                                        class="cidb-codes-codes form-control selectize" multiple="multiple"
                                        v-model="code.codes">
                                        @foreach (App\Code::where('type', 'cidb-c')->orderBy('code', 'asc')->get() as $code)
                                            <option value="{{ $code->id }}">{{ $code->label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-2">
                                    <a class="btn btn-warning btn-raised" v-show="codes.length > 1"
                                        v-on:click="deleteForm(index)">&times;</a>
                                </div>
                            </div>

                            <div class="row join-rule">
                                <div class="col-lg-3 col-lg-offset-3"
                                    v-show="codes.length > 1 && index < (codes.length - 1)">
                                    <select v-bind:id="'cidb_form_' + index + '_join_rule'"
                                        v-bind:name="'cidb_codes[' + index + '][join_rule]'" v-model="code.join_rule"
                                        class="cidb-codes-join-rule form-control">
                                        <option value="and">Dan</option>
                                        <option value="or">Atau</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="cidb_form_noforms_template"></div>
                        <div id="cidb_form_controls">
                            <a class="btn btn-primary btn-sm" v-on:click="addForm"><span>Tambah</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div role="tabpanel" class="tab-pane" id="tf-doc1">
            @if (isset($tender) && count($tender->files) > 0)
                <h4>Senarai Fail</h4>
                <table class="table table-striped table-bordered table-condensed table-hover">
                    <thead class="bg-blue-selangor">
                        <tr>
                            <th>Nama</th>
                            <th>Saiz</th>
                            <th>Jenis</th>
                            <th>Dokumen Meja Terkawal</th>
                            <th>Padam</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tender->files as $upload)
                            <tr>
                                <td>{{ $upload->label }}</td>
                                <td>{{ $upload->size }}</td>
                                <td>{{ $upload->type }}</td>
                                <td>
                                    @if ($upload->public)
                                        <span class="glyphicon glyphicon-ok"></span>
                                    @endif
                                </td>
                                <td><input type="checkbox" name="deleted_files[]" value="{{ $upload->id }}"></td>
                                <td>
                                    <a href="{{ $upload->url }}" class="btn btn-primary btn-xs" download>Muat
                                        Turun</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table><br>
            @endif

            <div id="files">
                <div id="files_template">
                    <div class="row">
                        <div class="col-lg-4">
                            <input type="file" id="files_file_#index#" name="files[#index#][file]"
                                class="form-control">
                        </div>

                        <div class="col-lg-6">
                            <input type="text" id="files_name_#index#" name="files[#index#][name]"
                                placeholder="Nama Dokumen" class="form-control name">
                            <label>
                                <input type="checkbox" id="files_public_#index#" name="files[#index#][public]"
                                    class="doc-2"> Dokumen Meja Terkawal
                            </label>
                        </div>

                        <div class="col-lg-2">
                            <a class="btn btn-warning btn-raised" id="files_remove_current">&times;</a>
                        </div>
                    </div>
                </div>
                <div id="files_noforms_template"></div>
                <div id="files_controls">
                    <div id="files_add"><a class="btn btn-primary btn-raised"><span>Tambah</span></a></div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
    {{-- <script src="https://cdn.ckeditor.com/4.20.2/full/ckeditor.js"></script> --}}
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('custom_library/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/relCopy.js') }}"></script>

    <script src="{{ asset('js/tender-vue.js') }}"></script>
    <script>
        $(document).ready(function() {
            @if (isset($tender->officer->id))
                var $newOption = $("<option selected='selected'></option>").val("{{ $tender->officer->id }}").text(
                    "{{ $tender->officer->name }}")
                $("#officer_id").append($newOption).trigger('change');
            @endif

            $("#officer_id").select2({
                ajax: {
                    url: "{{ route('user.by.agency') }}",
                    type: "post",
                    dataType: 'json',
                    // delay: 1000,
                    data: function(params) {
                        var org_id = $("#organization_unit_id").val();
                        if ($("#organization_unit_id").val() === null || $("#organization_unit_id")
                            .val() === undefined) {
                            org_id = "{{ auth()->user()->organization_unit_id }}";
                        }
                        return {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: org_id,
                            search: params.term,
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    },
                    cache: true,
                    error: function(jqXHR, status, error) {
                        console.log(error + ": " + jqXHR.responseText);
                        return {
                            results: []
                        }; // Return dataset to load after error
                    }
                }

            });

            // var removeLink = '<div class="col-lg-1 col-sm-1"> <a class="remove btn btn-danger" href="#" onclick="$(this).parent().parent().remove(); return false">Padam</a> </div>' +
            // '<div class=""> <div class="col-lg-11 col-sm-11 text-center"> <span>------------------------ ATAU ------------------------</span> </div> </div>';

            var removeLink =
                '<div class="col-lg-1 col-sm-1"> <a class="remove btn btn-danger" href="#" onclick="$(this).parent().parent().remove(); return false">Padam</a> </div>';
            $('a.copy').relCopy({
                append: removeLink
            });
        });

        $("#officer_id").change(function() {
            var id = $(this).val();
            if (id != '') {
                $.ajax({
                    type: 'post',
                    url: "{{ route('user.by.id') }}",
                    data: {
                        "id": id,
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#email').val(response[0].email);
                        $('#department').val(response[0].department);
                        $('#tel').val(response[0].tel);
                        $('#department').prop("required", true);
                        $('#tel').prop("required", true);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            } else {
                $('#department').prop("required", false);
                $('#tel').prop("required", false);
                $('#email').val('');
                $('#department').val('');
                $('#tel').val('');
            }

        });

        $('input[name="advertise_start_date"], input[name="advertise_stop_date"]').datepicker({
            format: 'd M yyyy'
        });

        $('input[name="document_start_date"], input[name="document_stop_date"]').datepicker({
            format: 'd M yyyy'
        });

        $('input[name="submission_datetime"]').datepicker({
            format: 'd M yyyy'
        });

        CKEDITOR.replace('tender_rules', {
            toolbarGroups: [{
                    name: 'document',
                    groups: ['mode', 'document', 'doctools']
                },
                {
                    name: 'clipboard',
                    groups: ['clipboard', 'undo']
                },
                {
                    name: 'editing',
                    groups: ['find', 'selection', 'spellchecker', 'editing']
                },
                {
                    name: 'forms',
                    groups: ['forms']
                },
                {
                    name: 'insert',
                    groups: ['insert']
                },
                '/',
                {
                    name: 'basicstyles',
                    groups: ['basicstyles', 'cleanup']
                },
                {
                    name: 'paragraph',
                    groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']
                },
                {
                    name: 'links',
                    groups: ['links']
                },
                '/',
                {
                    name: 'styles',
                    groups: ['styles']
                },
                {
                    name: 'colors',
                    groups: ['colors']
                },
                {
                    name: 'tools',
                    groups: ['tools']
                },
                {
                    name: 'others',
                    groups: ['others']
                },
                {
                    name: 'about',
                    groups: ['about']
                }
            ],
            removeButtons: 'Flash,Iframe,Form,TextField,Checkbox,Radio,Textarea,Select,Button,ImageButton,HiddenField',
            removePlugins: 'easyimage, cloudservices, exportpdf'
        });

        CKEDITOR.on("instanceReady", function(event) {
            event.editor.on("beforeCommandExec", function(event) {
                // Show the paste dialog for the paste buttons and right-click paste
                if (event.data.name == "paste") {
                    event.editor._.forcePasteDialog = true;
                }
                // Don't show the paste dialog for Ctrl+Shift+V
                if (event.data.name == "pastetext" && event.data.commandData.from == "keystrokeHandler") {
                    event.cancel();
                }
            })
        });

        $('#certificates').on('update', function(e, data) {
            $('#certification-input').val(data.values)
        });

        function selectize_select(id) {
            $(id).find('select.selectize').each(function() {
                if (!this.selectize) $(this).selectize();
            });
        }

        @if (isset($tender) && count($tender->mof_codes) > 0)
            @foreach ($tender->mof_code_groups as $order => $group)
                mofForm.addCode({
                    'codes': {{ json_encode(array_keys($group['codes'])) }},
                    'join_rule': '{{ $group['join_rule'] }}',
                    'inner_rule': '{{ $group['inner_rule'] }}'
                });
            @endforeach
        @else
            mofForm.addForm();
        @endif

        @if (isset($tender) && count($tender->cidb_codes) > 0)
            @foreach ($tender->cidb_code_groups as $order => $group)
                cidbForm.addCode({
                    'codes': {{ json_encode(array_keys($group['codes'])) }},
                    'join_rule': '{{ $group['join_rule'] }}',
                    'inner_rule': '{{ $group['inner_rule'] }}'
                });
            @endforeach
        @else
            cidbForm.addForm();
        @endif

        $('#site_visit_form').sheepIt({
            allowRemoveLast: true,
            // allowRemoveCurrent: true,
            iniFormsCount: 0,
            allowAdd: true,
            minFormsCount: 0,
            separator: '',
            removeCurrentSelector: '.btn-remove-site-visit',
            @if (isset($tender) && count($tender->siteVisits) > 0)
                data: [
                    @foreach ($tender->siteVisits as $visit)
                        {
                            "site_visit_form_#index#_meetpoint": @json($visit->meetpoint),
                            "site_visit_form_#index#_address": @json($visit->address),
                            "site_visit_form_#index#_id": {{ $visit->id }},
                            @if ($visit->required)
                                "site_visit_form_#index#_required": 1,
                            @endif
                            "site_visit_form_#index#_datetime": "{{ \Carbon\Carbon::parse($visit->datetime)->format('j M Y H:i') }}"
                        },
                    @endforeach
                ],
            @endif
            afterAdd: function(source, newform) {
                $('.sitevisit-map', newform)
                    .locationpicker({
                        location: {
                            latitude: 3.073945,
                            longitude: 101.541286
                        },
                        radius: 0,
                        zoom: 13,
                        onchanged: function(pos) {
                            $('.sitevisit_latlng', newform).val(pos.latitude + ',' + pos.longitude)
                        }
                    });

                $('.sitevisit-date', newform).datetimepicker({});
            }
        });

        $(".btn-remove-site-visit").click(function() {
            value = $(this).siblings('input[type=hidden]')[0].value;

            if (value) {
                find = $('input[name="deleted_site_visits[]"]:last');

                if (find.val() == 0) {
                    find.attr('value', value);
                } else {
                    find.clone().attr('value', value).prependTo($("#site_visit_form_controls"));
                }
            }
        });

        $('#briefing_datetime').datetimepicker();

        $("#files").sheepIt({
            iniFormsCount: 1,
            allowAdd: true,
            minFormsCount: 1,
            separator: ''
        });

        $("#cidb_grade").selectize();
        $("#district_id").selectize();

        $("#files .doc-2").on('change', function() {
            if (this.checked) {
                $(this).parents('.row').find('input[type=file]').attr('accept', 'application/pdf');
            } else {
                $(this).parents('.row').find('input[type=file]').attr('accept', null);
            }
        });

        $('.district_select').on('change', function() {

            console.log(this);
            let select_id = this.id;
            let selected_district_value = this.value;

            let unique_idx = select_id.replace("district_id_new", "");

            console.log(unique_idx);
            console.log(selected_district_value);


            if (selected_district_value == 0) {
                // $("#state_id_div" + unique_idx).css("display", "block");
                // $("#state_id_new" + unique_idx).css("display", "block");
                $("#state_id_new" + unique_idx + "  option[value='0']").attr('selected', 'selected');
                // $("#state_id_new" + unique_idx).val("0");

                $("#district_id_div" + unique_idx).removeClass("col-lg-11 col-sm-11");
                $("#district_id_div" + unique_idx).addClass("col-lg-6 col-sm-6");

                $("#state_id_div" + unique_idx).show();
                $("#state_id_new" + unique_idx).show();
            } else {
                // $("#state_id_div" + unique_idx).css("display", "none");
                // $("#state_id_new" + unique_idx).css("display", "none");
                $("#state_id_new" + unique_idx + "  option[value='0']").attr('selected', 'selected');
                // $("#state_id_new" + unique_idx).val("0");

                $("#district_id_div" + unique_idx).removeClass("col-lg-6 col-sm-6");
                $("#district_id_div" + unique_idx).addClass("col-lg-11 col-sm-11");

                $("#state_id_div" + unique_idx).hide();
                $("#state_id_new" + unique_idx).hide();
            }

            if (unique_idx != "") {
                $("#tobe_cloned" + unique_idx).removeClass("copy");
            }

            $("#custom-label" + unique_idx).show();

            if (unique_idx == "") {
                $("#custom-label" + unique_idx).hide();
            }

        });
    </script>

    <script>
        function changeOnlySelangorCheckbox(that) {
            document.getElementById('only_selangor1').checked = false;
            document.getElementById('only_selangor2').checked = false;
            document.getElementById('only_selangor3').checked = false;

            that.checked = true;

            console.log(that.value);

            console.log(document.getElementById('only_selangor1').checked);
            console.log(document.getElementById('only_selangor2').checked);
            console.log(document.getElementById('only_selangor3').checked);


            // alert('lalalalalalalala');

            if (document.getElementById('only_selangor2').checked == true) {

                // alert('lalalalalalalala 1');
                $('#main_district_div').show();

                $('#only_selangor1').prop("required", false);
                $('#only_selangor2').prop("required", true);
                $('[name="district_id_new[]"]').find('[value="0"]').remove();
                $('[name="district_id_new[]"]').append('<option value="0"> Luar Negeri Selangor </option>');
                $('[name="district_id_new[]"]').prepend(
                    "<option value='0' selected='selected' disabled>Pilihan Daerah/ Negeri...</option>");
                $('[name="district_id_new[]"]').prop("required", true);
            }
            else if (document.getElementById('only_selangor1').checked == true) {

                // alert('lalalalalalalala 2');
                $('#main_district_div').show();

                $('#only_selangor1').prop("required", true);
                $('#only_selangor2').prop("required", false);
                $('[name="district_id_new[]"]').find('[value="0"]').remove().trigger('change');
                $('[name="district_id_new[]"]').prepend(
                    "<option value='0' selected='selected' disabled>Pilihan Daerah...</option>");
                $('[name="state_id_new[]"]').hide();

                $('[name="district_id_div"]').removeClass("col-lg-6 col-sm-6");
                $('[name="district_id_div"]').addClass("col-lg-11 col-sm-11");
                $('[name="district_id_new[]"]').prop("required", false);
            }
            else if (document.getElementById('only_selangor3').checked == true) {

                // alert('lalalalalalalala 3');
                $('#main_district_div').hide();
                $('#only_selangor1').prop("required", false);
                $('#only_selangor2').prop("required", false);
            }
            else {

                alert('return all to normal');
                $('#main_district_div').show();
                $('#only_selangor1').prop("required", true);
                $('#only_selangor2').prop("required", false);
            }

        }
    </script>
@endsection
