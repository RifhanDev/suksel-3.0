@extends('layouts.default')
@section('content')
	<h2 class="tender-title">Masukkan Permintaan Kemaskini MOF</h2>
	
	{!! Former::open_for_files(route('vendor.requests.store', [$vendor->id, 'type' => $type])) !!}
		{!! Former::populate($vendor) !!}
		{!! Former::text('mof_ref_no')
			->label('No Rujukan Pendaftaran MOF') !!}
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
			->options(App\Code::where('type', 'mof')->get()->pluck('label', 'id'), $vendor->mof_codes->pluck('code_id')) !!}
		{!! Former::file('sijil_mof')
			->accept('application/pdf')
			->label('Sijil MOF')
			->required() !!}
		{!! Former::file('sijil_mof_bumiputera')
			->label('Sijil Bumiputera MOF')
			->accept('application/pdf')
			->help('Muat naik fail berkaitan untuk tujuan pengesahan. Hanya fail beformat PDF sahaja.') !!}
		
		<div class="well">
			{!! Former::submit('Hantar')
				->class('btn btn-primary') !!}
			<a href="{{ route('vendor.requests.index', $vendor->id) }}" class="btn btn-default">Senarai Permintaan Kemaskini</a>
			
			<a href="{{ route(Auth::user()->hasRole('Vendor') ? 'vendor' : 'vendors.show', $vendor->id) }}" class="btn btn-default pull-right">Maklumat Syarikat</a>
		</div>
	
	{!! Former::close() !!}
@endsection

@section('scripts')

	<script src="{{ asset('js/request.js') }}"></script>
	<script type="text/javascript">
		function selectize_select(id) {
		   $(id).find('select.selectize').each(function(){
		      if(!this.selectize) $(this).selectize();
		   });
		}
</script>
@stop