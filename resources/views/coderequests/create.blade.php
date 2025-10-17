@extends('layouts.default')
@section('content')
	<h2 class="tender-title">Masukkan Permintaan Kemaskini MOF / CIDB</h2>
	
	{!! Former::open_for_files(route('vendor.requests.store', $vendor->id)) !!}
		{!! Former::populate($vendor) !!}
		{!! Former::select('type')
			->label('Maklumat Kemaskini')
			->options(App\CodeRequest::availableTypes($vendor->id))
			->placeholder('Sila pilihan perubahan maklumat yang ingin dimasukkan...') !!}
	
		<div class="form mof-form" style="display:none;">
			{!! Former::text('mof_ref_no')
				->label('No Rujukan Pendaftaran MOF') }}
			<div class="form-group">
				<label for="mof_start_date" class="control-label col-lg-3 col-sm-3">Tarikh Aktif</label>
				<div class="col-lg-9 col-sm-9">
					<div class="input-group">
						<input class="form-control" id="mof_start_date" type="text" name="mof_start_date" value="{{ Carbon\Carbon::parse($vendor->mof_start_date)->format('j M Y') }}">
						<div class="input-group-addon">hingga</div>
						<input class="form-control" id="mof_end_date" type="text" name="mof_end_date" value="{{ Carbon\Carbon::parse($vendor->mof_end_date)->format('j M Y') }}">
					</div>
				</div>
			</div>
			{!! Former::checkbox('mof_bumi')
				->inline()
				->label('Syarikat Bumiputera') !!}
			{!! Former::select('mof_codes')
				->id('mof_codes')
				->name('mof_codes[]')
				->label('Kod Bidang')
				->multiple(true)
				->placeholder('Pilih kod bidang MOF')
				->options(Code::where('type', 'mof')->get()->lists('label', 'id'), $vendor->mof_codes->lists('code_id')) !!}
			{!! Former::file('sijil_mof')
				->accept('application/pdf')
				->label('Sijil MOF') !!}
			{!! Former::file('sijil_mof_bumiputera')
				->label('Sijil Bumiputera MOF')
				->accept('application/pdf')
				->help('Muat naik fail berkaitan untuk tujuan pengesahan. Hanya fail beformat PDF sahaja.') !!}
		</div>
	
	<div class="form cidb-form" style="display:none;">
		{!! Former::text('cidb_ref_no')
			->label('No Sijil CIDB') !!}
		<div class="form-group">
			<label for="cidb_start_date" class="control-label col-lg-3 col-sm-3">Tarikh Aktif</label>
			<div class="col-lg-9 col-sm-9">
				<div class="input-group">
					<input class="form-control" id="cidb_start_date" type="text" name="cidb_start_date" value="{{ Carbon\Carbon::parse($vendor->cidb_start_date)->format('j M Y') }}">
					<div class="input-group-addon">hingga</div>
					<input class="form-control" id="cidb_end_date" type="text" name="cidb_end_date" value="{{ Carbon\Carbon::parse($vendor->cidb_end_date)->format('j M Y') }}">
				</div>
			</div>
		</div>
		{!! Former::checkbox('cidb_bumi')
			->inline()
			->label('Syarikat Bumiputera') }}
		<div class="form-group">
			<label for="cidb_group" class="control-label col-lg-3 col-sm-3">Gred &amp; Bidang Pengkhususan</label>
			<div class="col-lg-9 col-sm-9">
				<div id="cidb_group">
					<div id="cidb_group_template" class="cidb-group-template">
						<input type="hidden" id="cidb_group_#index#_id" class="cidb-group-id" name="cidb_group[#index#][id]">
						<select id="cidb_group_#index#_code_id" class="cidb_group-code_id form-control selectize" name="cidb_group[#index#][code_id]">
							<option disabled="disabled" selected="selected" value="">Sila pilih Gred CIDB</option>
								@foreach(App\Code::where('type', 'cidb-g')->orderBy('code', 'asc')->get() as $code)
									<option value="{{$code->id}}">{{$code->label}}</option>
								@endforeach
						</select>
						<select id="cidb_group_#index#_codes" class="cidb_group-codes form-control selectize" name="cidb_group[#index#][codes][]" multiple="multiple">
							<option disabled="disabled" value="">Sila pilih Bidang Pengkhususan CIDB</option>
							@foreach(App\Code::where('type', 'cidb-c')->orderBy('code', 'asc')->get() as $code)
								<option value="{{$code->id}}">{{$code->label}}</option>
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
		{!! Former::file('sijil_cidb')
			->label('Sijil SPKK & CIDB')
			->accept('application/pdf') !!}
		{!! Former::file('sijil_pkk_bumiputera')
			->label('Sijil Bumiputera PKK')
			->accept('application/pdf')
			->help('Muat naik fail berkaitan untuk tujuan pengesahan. Hanya fail beformat PDF sahaja.') !!}
	</div>
	
	<div class="well">
		{!! Former::submit('Hantar')
			->class('btn btn-primary')
			->style('display:none;') !!}
			<a href="{{ route('vendor.requests.index', $vendor->id) }}" class="btn btn-default">Senarai Permintaan Kemaskini</a>	
			<a href="{{ route(Auth::user()->hasRole('Vendor') ? 'vendor' : 'vendors.show', $vendor->id) }}" class="btn btn-default pull-right">Maklumat Syarikat</a>
	</div>
	
	{!! Former::close() !!}
@endsection

@section('scripts')
	<script>
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
					@foreach($vendor->cidbGrades()->orderBy('id', 'asc')->get() as $grade) {
					'cidb_group_#index#_id': "{{ $grade->id }}",
					'cidb_group_#index#_code_id': "{{ $grade->code_id }}",
					'cidb_group_#index#_codes': {{ json_encode($grade->children()->lists('code_id')) }}
					},
					@endforeach
				]
			@endif
		});
		selectize_select("#cidb_group");
		$("#cidb_group_add").click(function(){
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
	</script>
@endsection