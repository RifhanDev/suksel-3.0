@extends('layouts.default')
@section('content')
	<h2 class="tender-title">Masukkan Permintaan Kemaskini Alamat Emel</h2>
	
	{!! Former::open_for_files(route('vendor.requests.store', [$vendor->id, 'type' => $type])) !!}
		{!! Former::populate($vendor) !!}
		{!! Former::text('email')
			->label('Alamat Emel Baru')
			->required() !!}
		{!! Former::file('sijil_auth')
			->label('Surat Kebenaran')
			->accept('application/pdf')
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
	</script>
@endsection