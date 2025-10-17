	{!! Former::populate($blacklist) !!}
	{!! Former::select('organization_unit_id')
		->label('Agensi')
		->options(App\OrganizationUnit::pluck('name', 'id'))
		->placeholder('Pilihan agensi...')
		->help('Pilih agensi yang ingin disenarai hitam atau kosongkan jika mahu syarikat di senarai hitam untuk kesemua tender / sebut harga.') !!}
	{!! Former::textarea('reason')
		->label('Sebab')
		->rows(5)
		->required() !!}
	{!! Former::text('start')
		->label('Tarikh Mula')
		->required()
		->forceValue(Request::old('start', $blacklist->start_date)) !!}
	{!! Former::text('end')
		->label('Tarikh Tamat')
		->required()
		->forceValue(Request::old('end', $blacklist->end_date)) !!}
	<?php $file = Former::file('file')
		->label('Lampiran')
		->accept('pdf'); if(!$blacklist->exists()) $file = $file->required(); ?>
		{!! $file !!}
	
	@section('scripts')
		<script type="text/javascript">
			$("#organization_unit_id").selectize();
			$('input[name="start"], input[name="end"]').datepicker({
			format: 'd M yyyy'
			});
	
		</script>
	@endsection