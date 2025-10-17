	{!! Former::populate($banner) !!}
	{!! Former::text('title')
			->label('Tajuk')
			->required() !!}
			<?php $file = Former::file('file')
			->label('Fail')
			->accept('image/png,image/jpg,image/jpeg'); if(!$banner->exists()) $file = $file->required(); ?>
	{!! $file !!}
	{!! Former::text('link')
	    	->label('Pautan') !!}
	{!! Former::checkbox('published')
	    	->label('Siar') !!}

@section('scripts')

	<script type="text/javascript">
		$("#organization_unit_id").selectize();
		$('input[name="start"], input[name="end"]').datepicker({
		    	format: 'd M yyyy'
		});
	</script>

@endsection