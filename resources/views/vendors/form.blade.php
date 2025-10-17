
<script>
    var isAdmin = {{(Auth::user() && !Auth::user()->hasRole('Vendor') ? 'true' : 'false')}};
    @if(Request::old('shareholder'))var inputOldShareholdes = {{json_encode(Request::old('shareholder'))}}; @endif
    
</script>
<div class="row stacked-form" ng-controller="VendorController" {{strstr(Route::currentRouteName(), 'show') ? 'ng-init="show=true"' : 'ng-init="show=false"'}}>
    	<div class="col-lg-2">
			<ul class="nav nav-pills nav-stacked">
				<li class="active"><a href="#vf-main" data-toggle="pill">Maklumat Syarikat</a></li>
				<li><a href="#vf-officer" data-toggle="pill">Maklumat Pegawai</a></li>
				<?php if(!isset($vendor->approval_1_id) || Auth::user()->can('Vendor:override')) : ?>
				<li><a href="#vf-mof" data-toggle="pill">MOF</a></li>
				<li><a href="#vf-cidb" data-toggle="pill">CIDB</a></li>
				<?php endif; ?>
				<li><a href="#vf-shareholders" data-toggle="pill">Pemegang Saham</a></li>
				<li><a href="#vf-directors" data-toggle="pill">Pengarah</a></li>
				@if(isset($vendor) && $vendor->approval_1_id > 0)
				<li><a href="#vf-contacts" data-toggle="pill">Kakitangan</a></li>
				<li><a href="#vf-awards" data-toggle="pill">Anugerah</a></li>
				<li><a href="#vf-assets" data-toggle="pill">Aset</a></li>
				<li><a href="#vf-projects" data-toggle="pill">Projek</a></li>
				<li><a href="#vf-products" data-toggle="pill">Produk</a></li>
				@endif
				<li><a href="#vf-files" data-toggle="pill">Fail</a></li>
			</ul>
   	</div>
    	<div class="tab-content col-lg-10">
        	<div class="tab-pane active" id="vf-main">
            <div class="row">
             	<div class="col-md-6">
                 	@if(!isset($vendor))
                     {!! Former::text('email')
								->label('Alamat Emel')
								->addClass('x-uppercase')
								->required() !!}	
                     {!! Former::text('registration')
								->required()
								->data_ng_blur('uppercaseInputValue($event)')
								->placeholder("Aksara, Nombor dan tanda '-' Sahaja")
								->label('No. Pendaftaran') !!}
                 	@else
                     <?php $email = Former::text('email')
								->label('Alamat Emel')
								->addClass('x-uppercase')
								->forceValue($vendor->user->email);
								if(!Auth::user()->hasRole('Admin')) $email = $email->disabled(); ?>
								{!! $email !!}
                     <?php $registration = Former::text('registration')
								->label('No Pendaftaran');
								if(!Auth::user()->hasRole('Admin')) $registration = $registration->disabled(); ?>
								{!! $registration !!}
                 	@endif
                 	{!! Former::text('name')
							->label('Nama Syarikat / Perniagaan')
							->required() !!}
					
					
 					@if (Auth::user() && !Auth::user()->hasRole('Vendor') && $disable_create_flaq == 0 )
					{!! Former::textarea('address')
                     ->label('Alamat')
                     ->rows(4)
                     ->required() !!}
					@elseif ($disable_create_flaq == 3 ) {{-- Enable only for first time vendor registrations --}}
					{!! Former::textarea('address')
                     ->label('Alamat')
                     ->rows(4)
                     ->required() !!}
					@else( isset($disable_create_flaq) && $disable_create_flaq == 1 )
                 	{!! Former::textarea('address')
                     ->label('Alamat')
                     ->rows(4)
                     ->readonly() !!}
					@endif
                 	<?php $districts = []; foreach(App\Vendor::$districts as $key => $val) $districts[$key] = strtoupper($val); ?>

                 	<?php
                     if (isset($vendor)) {
                         	$district = $vendor['district_id'] ?: '0';
                     } else {
                         	$district = null;
                     }
                 	?>

					@if (Auth::user() && !Auth::user()->hasRole('Vendor') && $disable_create_flaq == 0 )
					{!! Former::select('district_id')
                     ->label('Daerah')
                     ->options($districts)
                     ->placeholder('Pilihan daerah...')
                     ->disabled(Auth::user()->hasRole('Vendor') && !is_null($vendor->approval_1_id))
                     ->value($district)
                     ->required() !!}
					@elseif ($disable_create_flaq == 3 ) {{-- Enable only for first time vendor registrations --}}
					{!! Former::select('district_id')
                     ->label('Daerah')
                     ->options($districts)
                     ->placeholder('Pilihan daerah...')
                     ->disabled(Auth::user()->hasRole('Vendor') && !is_null($vendor->approval_1_id))
                     ->value($district)
                     ->required() !!}
					@else
					{!! Former::select('district_id')
                     ->label('Daerah')
                     ->options($districts)
                     ->placeholder('Pilihan daerah...')
                     ->disabled(Auth::user()->hasRole('Vendor') && !is_null($vendor->approval_1_id))
                     ->value($district)
                     ->disabled() !!}
					@endif

					@if (Auth::user() && !Auth::user()->hasRole('Vendor') && $disable_create_flaq == 0 )
						<div id="state_id_div" class="form-group" style="{{ ($district == 0) ? '' : 'display:none' }}">
							<label for="state_id" class="control-label col-lg-3 col-sm-3">Negeri<sup>*</sup></label>
							<div class="col-lg-9 col-sm-9">
								<select class="form-control" name="state_id" id="state_id" style="{{ ($district == 0) ? '' : 'display:none' }}" >
									<option value="0" disabled selected>Pilihan Negeri...</option>
									@foreach ($country_states as $state)
										<option value="{{ $state->id }}" {{ $vendor->state_id == $state->id ? "selected" : "" }}>{{ $state->description }}</option>
									@endforeach
								</select>
							</div>
						</div>
					@elseif ($disable_create_flaq == 3 ) {{-- Enable only for first time vendor registrations --}}
						<div id="state_id_div" class="form-group" style="{{ ($district == 0) ? '' : 'display:none' }}">
							<label for="state_id" class="control-label col-lg-3 col-sm-3">Negeri<sup>*</sup></label>
							<div class="col-lg-9 col-sm-9">
								<select class="form-control" name="state_id" id="state_id" style="{{ ($district == 0) ? '' : 'display:none' }}" >
									<option value="0" disabled selected>Pilihan Negeri...</option>
									@foreach ($country_states as $state)
										<option value="{{ $state->id }}" {{ $vendor->state_id == $state->id ? "selected" : "" }}>{{ $state->description }}</option>
									@endforeach
								</select>
							</div>
						</div>
					@else
						<div id="state_id_div" class="form-group" style="{{ ($district == 0) ? '' : 'display:none' }}">
							<label for="state_id" class="control-label col-lg-3 col-sm-3">Negeri<sup>*</sup></label>
							<div class="col-lg-9 col-sm-9">
								<select disabled class="form-control" name="state_id" id="state_id" style="{{ ($district == 0) ? '' : 'display:none' }}" >
									<option value="0" disabled selected>Pilihan Negeri...</option>
									@foreach ($country_states as $state)
										<option value="{{ $state->id }}" {{ $vendor->state_id == $state->id ? "selected" : "" }}>{{ $state->description }}</option>
									@endforeach
								</select>
							</div>
						</div>
					@endif

					

                 	{!! Former::text('tel')
                     ->pattern('^[+0-9]{9,}$')
                     ->label('No. Telefon')
                     ->placeholder("Tanda '+' dan nombor sahaja")
                     ->required() !!}
                 	{!! Former::text('fax')
                     ->pattern('^[+0-9]{9,}$')
                     ->placeholder("Tanda '+' dan nombor sahaja")
                     ->label('No. Faks') !!}
             	</div>
             	<div class="col-md-6">
                 	{!! Former::select('organization_type')
                     ->label('Jenis Perniagaan')
                     ->placeholder('Pilih dari senarai atau masukkan nilai baru')
                     ->options(App\Vendor::$organizationTypes)
                     ->required() !!}
                 	{!! Former::text('incorporation_date')
                     ->label('Tarikh Penubuhan')
                     ->required()
                     ->data_date_end_date(date('d/m/Y')) !!}

					<div class="form-group">
						<label for="ssm_expiry" class="control-label col-lg-3 col-sm-3">Tarikh Tamat Sijil SSM <sup>*</sup></label>
						<div class="col-lg-9 col-sm-9">
							<input class="form-control valid" date-date-start-date="{{ date('d/m/Y') }}" required aria-required="true" aria-invalid="false" id="ssm_expiry" type="text" name="ssm_expiry" value="{{ (isset($vendor->ssm_expiry) && $vendor->ssm_expiry != "") ? $vendor->ssm_expiry->format('d/m/Y') : date('d/m/Y') }}" aria-invalid="false">
						</div>
					</div>

                 	<div class="form-group">
                     <label for="authorized_capital" class="control-label col-lg-3 col-sm-3">Modal Dibenarkan</label>
                     <div class="col-lg-3 col-sm-3">
                        {!! Form::select('authorized_capital_currency', App\Vendor::$currencies, @$vendor->authorized_capital_currency, ["class" => "form-control"]) !!}
                     </div>
                     <div class="col-lg-6 col-sm-6">
                        <input class="form-control" id="authorized_capital" type="text" name="authorized_capital" value="{{ isset($vendor) ? $vendor->authorized_capital : '0.00'}}">
                     </div>
                 	</div>
                 	<div class="form-group">
                     <label for="paidup_capital" class="control-label col-lg-3 col-sm-3">Modal Berbayar</label>
                     <div class="col-lg-3 col-sm-3">
                        {!! Form::select('paidup_capital_currency', App\Vendor::$currencies, @$vendor->paidup_capital_currency, ["class" => "form-control"]) !!}
                     </div>
                     <div class="col-lg-6 col-sm-6">
                        <input class="form-control" id="paidup_capital" type="text" name="paidup_capital" value="{{ isset($vendor) ? $vendor->paidup_capital : '0.00'}}">
                     </div>
                 	</div>
                 	{!! Former::text('tax_no')
                     ->label('No. Rujukan Cukai') !!}
                 	{!! Former::text('gst_no')
                     ->label('No Pendaftaran GST') !!}
                 	{!! Former::text('website')
                     ->label('Laman Web')
                     ->addClass('x-uppercase') !!}
             	</div>
            </div>
        	</div>

        	<div class="tab-pane" id="vf-officer">
            {!! Former::text('officer_name')
						->label('Nama Pegawai')
						->required() !!}
            {!! Former::text('officer_designation')
						->label('Jawatan Pegawai') 
						->required() !!}
            {!! Former::text('officer_tel')
						->label('No. Telefon') 
						->required() !!}
            @if(!isset($vendor))
            {!! Former::password('password')
						->label('Kata Laluan')
						->required() !!}
            {!! Former::password('password_confirmation')
						->label('Sahkan Kata Laluan')
						->required() !!}
            @endif
        	</div>
        	<?php if(!isset($vendor->approval_1_id) || Auth::user()->can('Vendor:override')) : ?>
        	<div class="tab-pane" id="vf-mof">
            {!! Former::text('mof_ref_no')
						->label('No Rujukan Pendaftaran MOF') !!}
            <div class="form-group">
                	<label for="mof_start_date" class="control-label col-lg-3 col-sm-3">Tarikh Aktif</label>
                	<div class="col-lg-9 col-sm-9">
                    	<div class="input-group">
                        <input class="form-control x-uppercase" id="mof_start_date" type="text" name="mof_start_date" value="{{ isset($vendor) && !empty($vendor->mof_start_date) ? Carbon\Carbon::parse($vendor->mof_start_date)->format('j M Y') : '' }}">
                        <div class="input-group-addon">hingga</div>
                        <input class="form-control x-uppercase" id="mof_end_date" type="text" name="mof_end_date" value="{{ isset($vendor) && !empty($vendor->mof_end_date) ? Carbon\Carbon::parse($vendor->mof_end_date)->format('j M Y') : '' }}">
                    	</div>
                	</div>
            </div>
            <input type="hidden" name="mof_bumi" value="0">
            {!! Former::checkbox('mof_bumi')
						->inline()
						->label('Syarikat Bumiputera')
						->checked(isset($vendor) && !empty($vendor->mof_bumi))
						->forceValue(1) !!}
            {!! Former::select('mof_codes')
						->id('mof_codes')
						->name('mof_codes[]')
						->label('Kod Bidang')
						->multiple(true)
						->placeholder('Pilih kod bidang MOF')
						->class('selectize')
						->options(App\Code::where('type', 'mof')->orderBy('code')->get()->pluck('label', 'id'), isset($vendor) ? $vendor->vendorCodes()->where('code_type', 'mof')->pluck('code_id') : '') !!}
        	</div>

        	<div class="tab-pane" id="vf-cidb">
            {!! Former::text('cidb_ref_no')
						->label('No Sijil CIDB') !!}
            <div class="form-group">
                	<label for="cidb_start_date" class="control-label col-lg-3 col-sm-3">Tarikh Aktif</label>
                	<div class="col-lg-9 col-sm-9">
                   	<div class="input-group">
                        <input class="form-control x-uppercase" id="cidb_start_date" type="text" name="cidb_start_date" value="{{ isset($vendor) && !empty($vendor->cidb_start_date) ? Carbon\Carbon::parse($vendor->cidb_start_date)->format('j M Y') : '' }}">
                        <div class="input-group-addon">hingga</div>
                        <input class="form-control x-uppercase" id="cidb_end_date" type="text" name="cidb_end_date" value="{{ isset($vendor) && !empty($vendor->cidb_end_date) ? Carbon\Carbon::parse($vendor->cidb_end_date)->format('j M Y') : '' }}">
                    	</div>
                	</div>
            </div>
            <input type="hidden" name="cidb_bumi" value="0">
            {!! Former::checkbox('cidb_bumi')
						->inline()
						->label('Syarikat Bumiputera')
						->checked(isset($vendor) && !empty($vendor->cidb_bumi))
						->forceValue(1) !!}
            <div class="form-group">
                	<label for="cidb_group" class="control-label col-lg-3 col-sm-3">Gred &amp; Bidang Pengkhususan</label>
                	<div class="col-lg-9 col-sm-9">
                    	<div id="cidb_group">
                        <div id="cidb_group_template" class="cidb-group-template">
                         	<input type="hidden" id="cidb_group_#index#_id" class="cidb-group-id" name="cidb_group[#index#][id]">
                         	<select id="cidb_group_#index#_code_id" class="cidb_group-code_id form-control selectize" name="cidb_group[#index#][code_id]" data-tracker="cidb_group_tracker" onchange="updateOption(this)">
                             	<option disabled="disabled" selected="selected" value="">Sila pilih Gred CIDB</option>
                             	@foreach(App\Code::where('type', 'cidb-g')->orderBy('code', 'asc')->get() as $code)
                             		<option value="{{$code->id}}">{{ $code->label }}</option>
                             	@endforeach
                         	</select>
                         	<select id="cidb_group_#index#_codes" class="cidb_group-codes form-control selectize" name="cidb_group[#index#][codes][]" multiple="multiple">
                             	<option disabled="disabled" value="">
                             		Sila pilih Bidang Pengkhususan CIDB
                             	</option>
                       			@foreach(App\Code::where('type', 'cidb-c')->orderBy('code', 'asc')->get() as $code)
                       				<option value="{{ $code->id }}">{{ $code->label }}</option>
                       			@endforeach
                         	</select>
                         	<a class="btn btn-danger btn-xs btn-delete-cidb_group" id="cidb_group_remove_current">Padam</a>
                        </div>
                        <div id="cidb_group_noforms_template">Tiada maklumat Gred &amp; Bidang Pengkhususan CIDB</div>
                        <div id="cidb_group_controls">
                           	<div id="cidb_group_add"><a class="btn btn-primary btn-sm"><span>Tambah</span></a></div>
                        </div>
                    </div>
                    <input type="hidden" name="deleted_cidb_group[]">
                	</div>
            </div>
        	</div>
        	<?php endif; ?>

        	<div class="tab-pane" id="vf-shareholders" ng-controller="ItemController" 
        		<?php if(isset($vendor)) { ?> 
        			data-remote="{{ asset('vendor/'.$vendor->id.'/shareholders') }}"
        		<?php } ?> >
            <input type="hidden" name="deleted[shareholder][]" ng-repeat="item in deletedItems" ng-value="item.id">
            <table class="table table-striped table-bordered table-hover">
             	<thead class="bg-blue-selangor">
                 	<tr>
                     <th>Nama</th>
                     <th>IC / Pasport</th>
                     <th>Kewarganegaraan</th>
                     <th>Taraf</th>
                     <th ng-if="!show" width="120" class="text-center">-</th>
                 	</tr>
             	</thead>
             	<tbody>
                 	<tr ng-repeat="item in items" ng-class="item == editingItem && 'editing'">
                     <td ng-bind="item.name"></td>
                     <td ng-bind="item.identity"></td>
                     <td ng-bind="item.nationality"></td>
                     <td ng-bind="item.bumiputera_status"></td>
                     <td class="text-right" ng-if="!show">
								<input type="hidden" name="shareholder[id][]" ng-value="item.id">
								<input type="hidden" name="shareholder[name][]" ng-value="item.name">
								<input type="hidden" name="shareholder[identity][]" ng-value="item.identity">
								<input type="hidden" name="shareholder[nationality][]" ng-value="item.nationality">
								<input type="hidden" name="shareholder[bumiputera_status][]" ng-value="item.bumiputera_status">
								<div ng-show="item != editingItem">
									<button type="button" class="btn btn-info btn-xs" ng-click="edit(item)">Kemaskini</button>
									<button type="button" class="btn btn-danger btn-xs" ng-click="remove($index)">Padam</button>
								</div>
								<div ng-show="item == editingItem">
									<div class="label label-warning">Sedang Dikemaskini</div>
								</div>
                     </td>
                 	</tr>
             	</tbody>
               <tfoot ng-if="!show">
                 	<tr ng-keyup="setHasNewItem()">
                     <td><input id="focusshareholder" class="form-control input-xs" ng-keypress="handleKeypress($event)" ng-model="newItem.name" ng-keyup="newItem.name=newItem.name.toUpperCase()" type="text" placeholder="Nama Penuh"></td>
                     <td><input class="form-control input-xs" ng-keypress="handleKeypress($event)" ng-model="newItem.identity" type="text" placeholder="IC / Passport"></td>
                     <td>
                      	{!! Form::select('nat', App\Vendor::$nationalities, 'MALAYSIAN', [
                             'data_ng_model' => "newItem.nationality",
                             'class' => "form-control input-xs"
                        ]) !!}
                     </td>
                     <td>
                      	<select class="form-control input-xs" ng-model="newItem.bumiputera_status" ng-options="type for type in shareHolderTypes"></select>
                     </td>
                     <td class="text-right">
								<button type ="button" class="btn btn-info btn-xs" ng-click="save()">Simpan</button>
								<button ng-show ="hasEditingItem" type="button" class="btn btn-default btn-xs" ng-click="clear()">Batal</button>
								<button ng-show ="!hasEditingItem" type="button" class="btn btn-default btn-xs" ng-click="clear()">Kosongkan</button>
                     </td>
                 	</tr>
               </tfoot>
            </table>
            <h4>Ringkasan <sup>*</sup></h4>
            <table class="table table-bordered">
					<thead class="bg-blue-selangor">
						<tr>
							<th>Bumiputera</th>
							<th>Bukan Bumiputera</th>
							<th>Warga Asing</th>
							<th>Jumlah</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="text-right">
								<div class="input-group">
									<input ng-init="percentages.bumi=({{ Request::old('bumi_percentage', isset($vendor) ? $vendor->bumi_percentage : 0) }} || 0)"  name="bumi_percentage" ng-model="percentages.bumi" min="0" type="number" class="form-control">
									<div class="input-group-addon">%</div>
								</div>
							</td>
							<td class="text-right">
								<div class="input-group">
									<input ng-init="percentages.nonbumi=({{ Request::old('nonbumi_percentage', isset($vendor) ? $vendor->nonbumi_percentage : 0) }} || 0)"  name="nonbumi_percentage" ng-model="percentages.nonbumi" min="0" type="number" class="form-control">
									<div class="input-group-addon">%</div>
								</div>
							</td>
							<td class="text-right">
								<div class="input-group">
									<input ng-init="percentages.foreigner=({{ Request::old('foreigner_percentage', isset($vendor) ? $vendor->foreigner_percentage : 0) }} || 0)"  name="foreigner_percentage" ng-model="percentages.foreigner" min="0" type="number" class="form-control">
									<div class="input-group-addon">%</div>
								</div>
							</td>
							<td class="text-right">
								<div class="input-group">
									<input ng-init="(percentages.bumi + percentages.nonbumi + percentages.foreigner)" ng-value="(percentages.bumi + percentages.nonbumi + percentages.foreigner)" class="form-control" disabled="disabled">
									<div class="input-group-addon">%</div>
								</div>
							</td>
						</tr>
					</tbody>
            </table>
        </div>

        <div class="tab-pane" id="vf-directors" ng-controller="ItemController" 
        		<?php if(isset($vendor)) { ?> 
        			data-remote="{{ asset('vendor/'.$vendor->id.'/directors') }}"
        		<?php } ?> >
            <input type="hidden" name="deleted[directors][]" ng-repeat="item in deletedItems" ng-value="item.id">
            <table class="table table-striped table-bordered table-hover">
             	<thead class="bg-blue-selangor">
                 	<tr>
                     <th class="col-xs-4">Nama</th>
                     <th class="col-xs-2">IC / Pasport</th>
                     <th>Kewarganegaraan</th>
                     <th>Jawatan</th>
                     <th ng-if="!show" width="120" class="text-center">-</th>
                 	</tr>
             	</thead>
             	<tbody>
                 	<tr ng-repeat="item in items" ng-class="item == editingItem && 'editing'">
                     <td ng-bind="item.name"></td>
                     <td ng-bind="item.identity"></td>
                     <td ng-bind="item.nationality"></td>
                     <td ng-bind="item.designation"></td>
                     <td class="text-right" ng-if="!show">
								<input type="hidden" name="director[id][]" ng-value="item.id">
								<input type="hidden" name="director[name][]" ng-value="item.name">
								<input type="hidden" name="director[identity][]" ng-value="item.identity">
								<input type="hidden" name="director[nationality][]" ng-value="item.nationality">
								<input type="hidden" name="director[designation][]" ng-value="item.designation">
								<div ng-show="item != editingItem">
									<button type="button" class="btn btn-info btn-xs" ng-click="edit(item)">Kemaskini</button>
									<button type="button" class="btn btn-danger btn-xs" ng-click="remove($index)">Padam</button>
								</div>
								<div ng-show="item == editingItem">
									<div class="label label-warning">Sedang Dikemaskini</div>
								</div>
                     </td>
                 	</tr>
             	</tbody>
               <tfoot ng-if="!show">
                 	<tr ng-keyup="setHasNewItem()">
                     <td><input id="focusdirector" class="form-control input-xs" ng-keypress="handleKeypress($event)" ng-model="newItem.name" ng-keyup="newItem.name=newItem.name.toUpperCase()" type="text" placeholder="Nama Penuh"></td>
                     <td><input class="form-control input-xs" ng-keypress="handleKeypress($event)" ng-model="newItem.identity" type="text" placeholder="IC / Passport"></td>
                     <td>
								{!! Form::select('nat', App\Vendor::$nationalities, 'MALAYSIAN', [
									'data_ng_model' => "newItem.nationality",
									'class' => "form-control input-xs"]) !!}
                     </td>
                     <td>
								{!! Form::select('nat', App\Vendor::$directorDesignations, 'Pengarah', [
									'data_ng_model' => "newItem.designation",
									'class' => "form-control input-xs"]) !!}
                     </td>
                     <td class="text-right">
								<button type    ="button" class="btn btn-info btn-xs" ng-click="save()">Simpan</button>
								<button ng-show ="hasEditingItem" type="button" class="btn btn-default btn-xs" ng-click="clear()">Batal</button>
								<button ng-show ="!hasEditingItem" type="button" class="btn btn-default btn-xs" ng-click="clear()">Kosongkan</button>
                     </td>
                 	</tr>
               </tfoot>
            </table>
        	</div>

        	@if(isset($vendor) && $vendor->approval_1_id > 0)
				<div class="tab-pane" id="vf-contacts" ng-controller="ItemController" 
					<?php if(isset($vendor)) { ?> 
        				data-remote="{{ asset('vendor/'.$vendor->id.'/contacts') }}"
        			<?php } ?> >
					<input type="hidden" name="deleted[contact][]" ng-repeat="item in deletedItems" ng-value="item.id">
					<table class="table table-striped table-bordered table-hover">
						<thead class="bg-blue-selangor">
						<tr>
						<th>Nama</th>
						<th>Jawatan</th>
						<th>Warga Negara</th>
						<th>Taraf</th>
						<th ng-if="!show" width="120" class="text-center">-</th>
						</tr>
						</thead>
						<tbody>
							<tr ng-repeat="item in items" ng-class="item == editingItem && 'editing'">
								<td ng-bind="item.name"></td>
								<td ng-bind="item.designation"></td>
								<td ng-bind="item.nationality"></td>
								<td ng-bind="item.status"></td>
								<td class="text-right" ng-if="!show">
								<input type="hidden" name="contact[id][]" ng-value="item.id">
								<input type="hidden" name="contact[name][]" ng-value="item.name">
								<input type="hidden" name="contact[designation][]" ng-value="item.designation">
								<input type="hidden" name="contact[nationality][]" ng-value="item.nationality">
								<input type="hidden" name="contact[status][]" ng-value="item.status">
								<div ng-show="item != editingItem">
									<button type="button" class="btn btn-info btn-xs" ng-click="edit(item)">Kemaskini</button>
									<button type="button" class="btn btn-danger btn-xs" ng-click="remove($index)">Padam</button>
								</div>
								<div ng-show="item == editingItem">
									<div class="label label-warning">Sedang Dikemaskini</div>
								</div>
								</td>
							</tr>
						</tbody>
						<tfoot ng-if="!show">
							<tr ng-keyup="setHasNewItem()">
								<td>
									<input id="focuscontact" class="form-control input-xs" ng-keypress="handleKeypress($event)" ng-model="newItem.name" ng-keyup="newItem.name=newItem.name.toUpperCase()" type="text" placeholder="Nama Penuh">
								</td>
								<td>
									<input class="form-control input-xs" ng-keypress="handleKeypress($event)" ng-model="newItem.designation" ng-keyup="newItem.designation=newItem.designation.toUpperCase()" type="text" placeholder="Jawatan">
								</td>
								<td>
									{!! Form::select('nat', App\Vendor::$nationalities, 'MALAYSIAN', [
										'data_ng_model' => "newItem.nationality",
										'class' => "form-control input-xs"]) !!}
								</td>
								<td>
									<select class="form-control input-xs" ng-model="newItem.status" ng-options="type for type in shareHolderTypes"></select>
								</td>
								<td class="text-right">
									<button type="button" class="btn btn-info btn-xs" ng-click="save()">Simpan</button>
									<button ng-show="hasEditingItem" type="button" class="btn btn-default btn-xs" ng-click="clear()">Batal</button>
									<button ng-show="!hasEditingItem" type="button" class="btn btn-default btn-xs" ng-click="clear()">Kosongkan</button>
								</td>
							</tr>
						</tfoot>
					</table>
        		</div>

	        	<div class="tab-pane" id="vf-awards" ng-controller="ItemController" 
	        		<?php if(isset($vendor)) { ?> 
        				data-remote="{{ asset('vendor/'.$vendor->id.'/awards') }}"
        			<?php } ?> >
	            <input type="hidden" name="deleted[award][]" ng-repeat="item in deletedItems" ng-value="item.id">
	            <table class="table table-striped table-bordered table-hover">
						<thead class="bg-blue-selangor">
							<tr>
								<th>Nama</th>
								<th>Keterangan</th>
								<th>Pemberi Anugerah</th>
								<th ng-if="!show" width="120" class="text-center">-</th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="item in items" ng-class="item == editingItem && 'editing'">
								<td ng-bind="item.name"></td>
								<td ng-bind="item.description"></td>
								<td ng-bind="item.by"></td>
								<td class="text-right" ng-if="!show">
									<input type="hidden" name="award[id][]" ng-value="item.id">
									<input type="hidden" name="award[name][]" ng-value="item.name">
									<input type="hidden" name="award[description][]" ng-value="item.description">
									<input type="hidden" name="award[by][]" ng-value="item.by">
									<div ng-show="item != editingItem">
										<button type="button" class="btn btn-info btn-xs" ng-click="edit(item)">Kemaskini</button>
										<button type="button" class="btn btn-danger btn-xs" ng-click="remove($index)">Padam</button>
									</div>
									<div ng-show="item == editingItem">
										<div class="label label-warning">Sedang Dikemaskini</div>
									</div>
								</td>
							</tr>
						</tbody>
						<tfoot ng-if="!show">
							<tr ng-keyup="setHasNewItem()">
								<td>
									<input id="focuscontact" class="form-control input-xs" ng-keypress="handleKeypress($event)" ng-model="newItem.name" ng-keyup="newItem.name=newItem.name.toUpperCase()" type="text" placeholder="Nama">
								</td>
								<td>
									<input class="form-control input-xs" ng-keypress="handleKeypress($event)" ng-model="newItem.description" ng-keyup="newItem.description=newItem.description.toUpperCase()" type="text" placeholder="Keterangan">
								</td>
								<td>
									<input class="form-control input-xs" ng-keypress="handleKeypress($event)" ng-model="newItem.by" ng-keyup="newItem.by=newItem.by.toUpperCase()" type="text" placeholder="Pemberi Anugerah">
								</td>                        
								<td class="text-right">
									<button type="button" class="btn btn-info btn-xs" ng-click="save()">Simpan</button>
									<button ng-show="hasEditingItem" type="button" class="btn btn-default btn-xs" ng-click="clear()">Batal</button>
									<button ng-show="!hasEditingItem" type="button" class="btn btn-default btn-xs" ng-click="clear()">Kosongkan</button>
								</td>
							</tr>
						</tfoot>
	            </table>
	        	</div>

	        	<div class="tab-pane" id="vf-assets" ng-controller="ItemController" 
	        		<?php if(isset($vendor)) { ?> 
        				data-remote="{{ asset('vendor/'.$vendor->id.'/assets') }}"
        			<?php } ?> >
	            <input type="hidden" name="deleted[asset][]" ng-repeat="item in deletedItems" ng-value="item.id">
	            <table class="table table-striped table-bordered table-hover">
						<thead class="bg-blue-selangor">
							<tr>
								<th>Nama</th>
								<th class="col-lg-2">Nilai (RM)</th>
								<th ng-if="!show" width="120" class="text-center">-</th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="item in items" ng-class="item == editingItem && 'editing'">
								<td ng-bind="item.name"></td>
								<td ng-bind="item.value"></td>
								<td class="text-right" ng-if="!show">
									<input type="hidden" name="asset[id][]" ng-value="item.id">
									<input type="hidden" name="asset[name][]" ng-value="item.name">
									<input type="hidden" name="asset[value][]" ng-value="item.value">
									<div ng-show="item != editingItem">
										<button type="button" class="btn btn-info btn-xs" ng-click="edit(item)">Kemaskini</button>
										<button type="button" class="btn btn-danger btn-xs" ng-click="remove($index)">Padam</button>
									</div>
									<div ng-show="item == editingItem">
										<div class="label label-warning">Sedang Dikemaskini</div>
									</div>
								</td>
							</tr>
						</tbody>
						<tfoot ng-if="!show">
							<tr ng-keyup="setHasNewItem()">
								<td>
									<input id="focuscontact" class="form-control input-xs" ng-keypress="handleKeypress($event)" ng-model="newItem.name" ng-keyup="newItem.name=newItem.name.toUpperCase()" type="text" placeholder="Nama">
								</td>
								<td>
									<div class="input-group">
									<div class="input-group-addon">RM</div>
									<input class="form-control input-xs" ng-keypress="handleKeypress($event)" ng-model="newItem.value" type="text" placeholder="Nilai">
									</div>
								</td>
								<td class="text-right">
									<button type="button" class="btn btn-info btn-xs" ng-click="save()">Simpan</button>
									<button ng-show="hasEditingItem" type="button" class="btn btn-default btn-xs" ng-click="clear()">Batal</button>
									<button ng-show="!hasEditingItem" type="button" class="btn btn-default btn-xs" ng-click="clear()">Kosongkan</button>
								</td>
							</tr>
						</tfoot>
	            </table>
	       	</div>

				<div class="tab-pane" id="vf-projects" ng-controller="ItemController" 
					<?php if(isset($vendor)) { ?> 
        				data-remote="{{ asset('vendor/'.$vendor->id.'/projects') }}"
        			<?php } ?> >
					<input type="hidden" name="deleted[project][]" ng-repeat="item in deletedItems" ng-value="item.id">
					<table class="table table-striped table-bordered table-hover">
						<thead class="bg-blue-selangor">
							<tr>
							<th>Nama</th>
							<th>Pelanggan</th>
							<th>Tempoh Projek</th>
							<th class="col-lg-2">Nilai (RM)</th>
							<th>Projek Siap</th>
							<th ng-if="!show" width="120" class="text-center">-</th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="item in items" ng-class="item == editingItem && 'editing'">
								<td ng-bind="item.name"></td>
								<td ng-bind="item.customer"></td>
								<td ng-bind="item.period"></td>
								<td ng-bind="item.value"></td>
								<td>
									<input type="checkbox" ng-checked="item.done" disabled="disabled">
								</td>
								<td class="text-right" ng-if="!show">
									<input type="hidden" name="project[id][]" ng-value="item.id">
									<input type="hidden" name="project[name][]" ng-value="item.name">
									<input type="hidden" name="project[value][]" ng-value="item.value">
									<input type="hidden" name="project[customer][]" ng-value="item.customer">
									<input type="hidden" name="project[period][]" ng-value="item.period">
									<input type="hidden" name="project[done][]" ng-value="item.done ? 'true' : ''">
									<div ng-show="item != editingItem">
										<button type="button" class="btn btn-info btn-xs" ng-click="edit(item)">Kemaskini</button>
										<button type="button" class="btn btn-danger btn-xs" ng-click="remove($index)">Padam</button>
									</div>
									<div ng-show="item == editingItem">
										<div class="label label-warning">Sedang Dikemaskini</div>
									</div>
								</td>
							</tr>
						</tbody>
						<tfoot ng-if="!show">
							<tr ng-keyup="setHasNewItem()">
								<td>
									<input id="focuscontact" class="form-control input-xs" ng-keypress="handleKeypress($event)" ng-model="newItem.name" ng-keyup="newItem.name=newItem.name.toUpperCase()" type="text" placeholder="Nama">
								</td>
								<td>
									<input id="focuscontact" class="form-control input-xs" ng-keypress="handleKeypress($event)" ng-model="newItem.customer" ng-keyup="newItem.customer=newItem.customer.toUpperCase()" type="text" placeholder="Pelanggan">
								</td>
								<td>
									<input id="focuscontact" class="form-control input-xs" ng-keypress="handleKeypress($event)" ng-model="newItem.period" ng-keyup="newItem.period=newItem.period.toUpperCase()" type="text" placeholder="Tempoh Projek">
								</td>
								<td>
									<div class="input-group">
										<div class="input-group-addon">RM</div>
										<input class="form-control input-xs" ng-keypress="handleKeypress($event)" ng-model="newItem.value" type="text" placeholder="Nilai">
									</div>
								</td>
								<td>
									<input id="focuscontact" class="form-control input-xs" ng-keypress="handleKeypress($event)" ng-model="newItem.done" type="checkbox">
								</td>
								<td class="text-right">
									<button type="button" class="btn btn-info btn-xs" ng-click="save()">Simpan</button>
									<button ng-show="hasEditingItem" type="button" class="btn btn-default btn-xs" ng-click="clear()">Batal</button>
									<button ng-show="!hasEditingItem" type="button" class="btn btn-default btn-xs" ng-click="clear()">Kosongkan</button>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
				
				<div class="tab-pane" id="vf-products" ng-controller="ItemController" 
					<?php if(isset($vendor)) { ?> 
        				data-remote="{{ asset('vendor/'.$vendor->id.'/products') }}"
        			<?php } ?> >
					<input type="hidden" name="deleted[product][]" ng-repeat="item in deletedItems" ng-value="item.id">
					<table class="table table-striped table-bordered table-hover">
						<thead class="bg-blue-selangor">
						<tr>
						<th>Nama</th>
						<th>Keterangan</th>
						<th>Pengguna</th>
						<th ng-if="!show" width="120" class="text-center">-</th>
						</tr>
						</thead>
						<tbody>
							<tr ng-repeat="item in items" ng-class="item == editingItem && 'editing'">
								<td ng-bind="item.name"></td>
								<td ng-bind="item.description"></td>
								<td ng-bind="item.implementations"></td>
								<td class="text-right" ng-if="!show">
									<input type="hidden" name="product[id][]" ng-value="item.id">
									<input type="hidden" name="product[name][]" ng-value="item.description">
									<input type="hidden" name="product[description][]" ng-value="item.name">
									<input type="hidden" name="product[implementations][]" ng-value="item.implementations">
									<div ng-show="item != editingItem">
										<button type="button" class="btn btn-info btn-xs" ng-click="edit(item)">Kemaskini</button>
										<button type="button" class="btn btn-danger btn-xs" ng-click="remove($index)">Padam</button>
									</div>
									<div ng-show="item == editingItem">
										<div class="label label-warning">Sedang Dikemaskini</div>
									</div>
								</td>
							</tr>
						</tbody>
						<tfoot ng-if="!show">
							<tr ng-keyup="setHasNewItem()">
								<td>
									<input id="focuscontact" class="form-control input-xs" ng-keypress="handleKeypress($event)" ng-model="newItem.name" ng-keyup="newItem.name=newItem.name.toUpperCase()" type="text" placeholder="Nama">
								</td>
								<td>
									<input id="focuscontact" class="form-control input-xs" ng-keypress="handleKeypress($event)" ng-model="newItem.description" ng-keyup="newItem.description=newItem.description.toUpperCase()" type="text" placeholder="Keterangan">
								</td>
								<td>
									<input id="focuscontact" class="form-control input-xs" ng-keypress="handleKeypress($event)" ng-model="newItem.implementations" ng-keyup="newItem.implementations=newItem.implementations.toUpperCase()" type="text" placeholder="Pengguna">
								</td>
								<td class="text-right">
									<button type="button" class="btn btn-info btn-xs" ng-click="save()">Simpan</button>
									<button ng-show="hasEditingItem" type="button" class="btn btn-default btn-xs" ng-click="clear()">Batal</button>
									<button ng-show="!hasEditingItem" type="button" class="btn btn-default btn-xs" ng-click="clear()">Kosongkan</button>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
        	@endif

        	<div class="tab-pane" id="vf-files">
            <div class="alert alert-warning">Hanya fail beformat PDF dan bersaiz maksimum 5MB boleh dimuat naik.</div>
            <?php $ssm = Former::file('ssm')->label('Sijil SSM')->accept('application/pdf')->addClass('file_input'); ?>
            <?php if(!isset($vendor) || !$vendor->completed || !$vendor->hasFile('ssm')) $ssm = $ssm->required(); ?>
            {!! $ssm !!}

            @if( !isset($vendor) || !$vendor->completed || Auth::user()->hasRole('Admin') )

            {!! Former::file('mof')
					->label('Sijil MOF')
					->accept('application/pdf')
					->addClass('file_input') !!}

            {!! Former::file('mof_bumiputera')
					->label('Sijil Bumiputera MOF')
					->accept('application/pdf')
					->addClass('file_input') !!}

            {!! Former::file('cidb')
					->label('Sijil CIDB & SPKK')
					->accept('application/pdf')
					->addClass('file_input')
					->help('Muat naik fail sijil SPKK & CIDB sebagai satu fail sahaja.') !!}

            {!! Former::file('cidb_bumiputera')
					->label('Sijil Bumiputera PKK')
					->accept('application/pdf')
					->addClass('file_input') !!}
            @endif

            @if(isset($vendor) && count($vendor->uploads) > 0)
	            <h3>Fail Dimuat Naik</h3>
	            <br>
	            	{!! $vendor->uploadsTable() !!}
            @endif
        	</div>
    	</div>
</div>

@section('scripts')

	<script>

		var validateMofCidb = @if(!isset($vendor) || !$vendor->completed) true @else false @endif;
		var nationalities   = @json(App\Vendor::$nationalities);

		function updateOption(value)
		{
			// let existing_cidb_group = $('.cidb_group-code_id[data-tracker="cidb_group_tracker"]');			
			// let selected = [];

			// $.each(existing_cidb_group, function( index, element ) {
			// 	selected.push(element.value);
			// 	$(this).remove(element.value);
			// });

			$(this).remove(value);

		}

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

			$.each(existing_cidb_group, function( index, element ) {
				selected.push(element.value);
			});

			$.each(existing_cidb_group, function( index, element ) {
				if(index != 0)
				{
					var select = $('#' + element.id).selectize();
					selected.forEach(row => {
						if (row != '')
						{
							if (select.val() != row)
							{
								// select[0].selectize.updateOption(row, {value: row, disable: true});
								select[0].selectize.removeOption(row);
							}
						}
					});
				}
			});
			
			selectize_select('#cidb_group');
		});
		
		$(".btn-delete-cidb_group").click(function(){
		    	id = $(this).siblings('.cidb-group-id').val();

		    	if(id) {
		        	deleted = $('input[name="deleted_cidb_group[]"]:first');

		        	if(deleted.val() == "") {
		            deleted.val(id);
		        	} else {
		            new_deleted = deleted.clone();
		            new_deleted.val(id);
		            new_deleted.insertAfter(deleted);
		        	}
		    	}
		});

		$('#district_id').on('change', function(){
			let selected = this.value;

			if (selected == 0)
			{
				$("#state_id_div").show();
				$("#state_id").show();
				$("#state_id").prop("disabled", false);
			}
			else{
				$("#state_id_div").hide();
				$("#state_id").hide();
				$("#state_id").prop("disabled", true);
			}
			// console.log(this.value);
		});
	</script>
	<script src="{{ asset('js/definitions.js') }}"></script>
	<script src="{{ asset('js/vendor.js') }}"></script>
@endsection
