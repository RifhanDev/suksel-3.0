{!! Former::populate($blacklist) !!}
{!! Former::textarea('cancel_reason')
		->label('Sebab')
		->rows(5)
		->required() !!}

@section('scripts')
	<script type="text/javascript">
		$('input[name="start"], input[name="end"]').datepicker({
	    	format: 'd M yyyy'
		});
	</script>
@endsection