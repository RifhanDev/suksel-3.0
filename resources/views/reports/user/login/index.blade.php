@extends('layouts.default')
@section('content')
	<h4 class="tender-title">Laporan Sistem Tender Online: Laporan Sebagai</h4>

	{!! Former::open(action('ReportUserLoginController@view'))->target('_blank') !!}
		<div class="form-group required">
			<label for="users[]" class="control-label col-lg-3 col-sm-3">Pengguna <sup>*</sup></label>
			<div class="col-lg-9 col-sm-9">
				<select class="form-control selectize" required="true" id="users" name="user_id" style="display: none;">
					<option value="" selected="selected"></option>
					@foreach($select_users as $s_user)<option value="{{ $s_user->id }}">{{ $s_user->name }} &lt;{{ $s_user->email }}&gt;</option>@endforeach
				</select>
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
		$('.selectize').each(function() {
		    $(this).selectize();
		});
	</script>
@endsection