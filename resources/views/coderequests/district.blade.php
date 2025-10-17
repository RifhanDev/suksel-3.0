@extends('layouts.default')
@section('content')
	<h2 class="tender-title">Masukkan Permintaan Kemaskini Daerah</h2>
	
	{!! Former::open_for_files(route('vendor.requests.store', [$vendor->id, 'type' => $type])) !!}
		{!! Former::populate($vendor) !!}

		{!! Former::textarea('address')
                     ->label('Alamat')
                     ->rows(4)
                     ->required() !!}

		@php
			$district_list = array("" => "Pilihan Daerah...");
			foreach(App\Vendor::$districts as $key => $district_desc)
			{
				$district_list[$key] = $district_desc;
			}
		@endphp

		{!! Former::select('district_id')
			->id('district_id')
			->label('Daerah')
			->options($district_list)
			->select('')
			->required() !!}

		<div id="state_id_div" class="form-group" style="{{ ( ($vendor->district_id ?? "0") == 0 && ($vendor->state_id ?? "0") != "0" ) ? '' : 'display:none' }}">
			<label for="state_id" class="control-label col-lg-3 col-sm-3">Negeri<sup>*</sup></label>
			<div class="col-lg-9 col-sm-9">
				<select class="form-control" name="state_id" id="state_id" style="{{ (($vendor->district_id ?? "0") == 0) ? '' : 'display:none' }}" {{ (($vendor->district_id ?? "0") == 0) ? 'required' : '' }} >
					<option value="" selected>Pilihan Negeri...</option>
					@foreach ($country_states as $state)
						<option value="{{ $state->id }}" {{ $vendor->state_id == $state->id ? "selected" : "" }}>{{ $state->description }}</option>
					@endforeach
				</select>
			</div>
		</div>

		{!! Former::file('sijil_daerah')
		->name('sijil_daerah')
		->label('Dokumen Sokongan')
		->accept('application/pdf')
		->help('Muat naik dokumen sokongan seperti sijil SPKK &amp; CIDB untuk rujukan.')
		->required() !!}
	
	<div class="well">
		{!! Former::submit('Hantar')
			->class('btn btn-primary') !!}
			<a href="{{ route('vendor.requests.index', $vendor->id) }}" class="btn btn-default">Senarai Permintaan Kemaskini</a>
			<a href="{{ route(Auth::user()->hasRole('Vendor') ? 'vendor' : 'vendors.show', $vendor->id) }}" class="btn btn-default pull-right">Maklumat Syarikat</a>
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

		$('#district_id').on('change', function(){
			let selected = this.value.toString();
			
			if (selected != 0 || (selected === "") )
			{
				$("#state_id_div").hide();
				$("#state_id").hide();
				$("#state_id").prop("disabled", true);
			}
			else
			{
				$("#state_id_div").show();
				$("#state_id").show();
				$("#state_id").prop("disabled", false);
			}
		});
	</script>
@endsection