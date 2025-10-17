@extends('layouts.default')
@section('styles')

	<link href="{{ asset('css/form.css') }}" rel="stylesheet">

@endsection
@section('content')
<h4 class="tender-title">Laporan Sistem Tender Online: Syarikat Mengikut Kod Bidang</h4>

	{!! Former::open(action('ReportVendorCodeController@view'))->target('_blank') !!}
    	{!! Former::select('district_id')
			->id('district_id')
			->label('Daerah')
			->options(['-1' => ''] + App\Vendor::$districts)
			->placeholder('') !!}

    	<div class="form-group">
        	<label for="mof_codes" class="control-label col-lg-3 col-sm-3">Kod Bidang MOF</label>
        	<div class="col-lg-9 col-sm-9">
            <div id="mof_form">
               <div id="mof_form_template">
                    	<div class="row">
                        <div class="col-lg-3">
                           <select id="mof_form_#index#_inner_rule" class="mof-code-inner-rule form-control" name="mof_codes[#index#][inner_rule]">
                             	<option value="and">Kesemua</option>
                             	<option value="or">Salah Satu</option>
                         	</select>
                        </div>

                        <div class="col-lg-7">
                         	<select id="mof_form_#index#_codes" class="mof-code-codes form-control selectize" name="mof_codes[#index#][codes][]" multiple="multiple">
                             	@foreach(App\Code::where('type', 'mof')->get() as $code)
                             		<option value="{{ $code->id }}">{{ $code->label }}</option>
                             	@endforeach
                         	</select>
                        </div>

                        <div class="col-lg-2">
                           <a class="btn btn-warning btn-xs" id="mof_form_remove_current">&times;</a>
                        </div>
                    	</div>
                    
                    	<div class="row join-rule">
                        <div class="col-lg-3 col-lg-offset-3">
                         	<select id="mof_form_#index#_join_rule" class="mof-code-join-rule form-control" name="mof_codes[#index#][join_rule]">
                             	<option value="and">Dan</option>
                             	<option value="or">Atau</option>
                         	</select>
                        </div>
                    	</div>
               </div>
               <div id="mof_form_noforms_template"></div>
               <div id="mof_form_controls">
                 	<div id="mof_form_add"><a class="btn btn-primary btn-sm"><span>Tambah</span></a></div>
               </div>
            </div>
        </div>
    	</div>

    	{!! Former::select('cidb_grades[]')
			->id('cidb_grades')
			->label('Gred CIDB')
			->options(App\Code::where('type', 'cidb-g')->get()->pluck('label', 'id'))
			->multiple(true)
			->placeholder('') !!}

    	<div class="form-group required">
        	<label for="cidb_codes" class="control-label col-lg-3 col-sm-3">Kod Bidang CIDB</label>
        	<div class="col-lg-9 col-sm-9">
            <div id="cidb_form">
             	<div id="cidb_form_template">
                    	<div class="row">
                        <div class="col-lg-3">
                            	<select id="cidb_form_#index#_inner_rule" class="cidb-codes-inner-rule form-control" name="cidb_codes[#index#][inner_rule]">
                                	<option value="and">Kesemua</option>
                                	<option value="or">Salah Satu</option>
                            	</select>
                        </div>

                        <div class="col-lg-7">
                            	<select id="cidb_form_#index#_codes" class="mof-codes-code form-control selectize" name="cidb_codes[#index#][codes][]" multiple="multiple">
                                	@foreach(App\Code::where('type', 'cidb-c')->get() as $code)
                                		<option value="{{ $code->id }}">{{ $code->label }}</option>
                                	@endforeach
                            	</select>
                        </div>

                        <div class="col-lg-2">
                           <a class="btn btn-warning btn-raised" id="cidb_form_remove_current">&times;</a>
                        </div>
                    	</div>
                    
                    	<div class="row join-rule">
                        <div class="col-lg-3 col-lg-offset-3">
                            	<select id="cidb_form_#index#_join_rule" class="cidb-codes-join-rule form-control" name="cidb_codes[#index#][join_rule]">
                                	<option value="and">Dan</option>
                                	<option value="or">Atau</option>
                            	</select>
                        </div>
                    	</div>
               </div>
             	<div id="cidb_form_noforms_template"></div>
             	<div id="cidb_form_controls">
                  <div id="cidb_form_add"><a class="btn btn-primary btn-sm"><span>Tambah</span></a></div>
             	</div>
            </div>
        	</div>
    	</div>

    	<div class="form-group">
      	<div class="col-lg-9 col-lg-offset-3">
        		{!! Former::submit('Hantar')->class('btn bg-blue-selangor') !!}
     		</div>
    	</div>
	{!! Former::close() !!}

@endsection

@section('scripts')
	<script type="text/javascript">
		function selectize_select(id) {
	    	$(id).find('select.selectize').each(function(){
		      if(!this.selectize) $(this).selectize();
		   });
		}

	$("#mof_form").sheepIt({
	    separator: '',
	    iniFormsCount: 1,
	    allowAdd: true,
	@if(isset($tender) && $tender->mof_codes)
	    data: [
	    @foreach($tender->mof_code_groups as $order => $group)
	    {
	        'mof_form_#index#_codes': {{ json_encode($group['codes']) !!},
	        'mof_form_#index#_join_rule': "{{ $group['join_rule'] !!}",
	        'mof_form_#index#_inner_rule': "{{ $group['inner_rule'] !!}"
	    },
	    @endforeach
	    ],
	@endif
	    beforeAdd: function(source, form) {
	        $('[idtemplate="mof_form_template"]:last').find('.join-rule').fadeIn();
	    },
	    afterAdd: function(source, form) {
	        $('[idtemplate="mof_form_template"]:last').find('.join-rule').hide();
	    },
	    afterRemoveCurrent: function(source) {
	       $('[idtemplate="mof_form_template"]:last').find('.join-rule').hide(); 
	    }
	});
	$("#mof_form_add").click(function(){
	    selectize_select('#mof_form');
	});

	$("#cidb_form").sheepIt({
	    separator: '',
	    iniFormsCount: 1,
	    allowAdd: true,
	@if(isset($tender) && $tender->cidb_codes)
	    data: [
	    @foreach($tender->cidb_code_groups as $order => $group)
	    {
	        'cidb_form_#index#_codes': {{ json_encode($group['codes']) !!},
	        'cidb_form_#index#_join_rule': "{{ $group['join_rule'] !!}",
	        'cidb_form_#index#_inner_rule': "{{ $group['inner_rule'] !!}"
	    },
	    @endforeach
	    ],
	@endif
	    beforeAdd: function(source, form) {
	        $('[idtemplate="cidb_form_template"]:last').find('.join-rule').fadeIn();
	    },
	    afterAdd: function(source, form) {
	        $('[idtemplate="cidb_form_template"]:last').find('.join-rule').hide();
	    },
	    afterRemoveCurrent: function(source) {
	       $('[idtemplate="cidb_form_template"]:last').find('.join-rule').hide(); 
	    }
	});
	$("#cidb_form_add").click(function(){
	    selectize_select('#cidb_form');
	});

	selectize_select('#mof_form');
	selectize_select('#cidb_form');
	$("#cidb_grades").selectize();
	</script>
@endsection