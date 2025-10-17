@extends('layouts.default')
@section('content')
	<h4 class="tender-title">Laporan Sistem Tender Online: Laporan Aktiviti Staf</h4>

	{!! Former::open(action('ReportUserActivityController@view'))->target('_blank') !!}
	 	<div class="form-group required">
	     	<label for="users[]" class="control-label col-lg-3 col-sm-3">Pengguna <sup>*</sup></label>
	     	<div class="col-lg-9 col-sm-9">
	         <select class="form-control selectize" multiple="multiple" required="true" id="users" name="users[]" style="display: none;">
	          	<option value="" selected="selected"></option>
	          	@foreach($select_users as $s_user)<option value="{{ $s_user->id }}">{{ $s_user->name }} &lt;{{ $s_user->email }}&gt;</option>@endforeach
	         </select>
	     	</div>
	 	</div>
	 	{!! Former::text('date_start')->addClass('datepicker')->label('Tarikh Mula')->required() !!}
	 	{!! Former::text('date_end')->addClass('datepicker')->label('Tarikh Akhir')->required() !!}

	 	<div class="form-group">
	      <div class="col-lg-9 col-lg-offset-3">
	        {!! Former::submit('Hantar')->class('btn bg-blue-selangor') !!}
	      </div>
	 	</div>
	{!! Former::close() !!}

@endsection

@section('scripts')
	<script type="text/javascript">
		$('.selectize').each(function() {
		    	$(this).selectize();
		});
		$('.datepicker').datepicker({
		    	format: 'd/m/yyyy'
		});
	</script>
@endsection