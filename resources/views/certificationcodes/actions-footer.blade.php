<div class="well">
	@if(isset($has_submit))
		<button class="btn btn-primary confirm">Simpan</button>
	@endif
	@if(!isset($is_list) && App\Code::canList())
		<a href="{{ asset('codes') }}" class="btn btn-default">Senarai Kod</a>  
	@endif
	@if(App\Code::canCreate())
		<a href="{{ asset('codes/create')}}" class="btn btn-default">Kod Baru</a>
	@endif

	{!! Former::close() !!}

	@if(isset($certificationcode))
		@if($certificationcode->canShow())
			<a href="{{ asset('codes/'.$certificationcode->id) }}" class="btn btn-default">Maklumat</a>
		@endif
		@if($certificationcode->canUpdate())
			<a href="{{ asset('codes/'.$certificationcode->id.'/edit') }}" class="btn btn-default">Kemaskini</a>
		@endif
		@if($certificationcode->canDelete())
			{!! Former::open(url('codes/'.$certificationcode->id))->class('form-inline') !!}
				{!! Former::hidden('_method', 'DELETE') !!}
				<button type="button" class="btn btn-default confirm-delete">Padam</button>
			{!! Former::close() !!}
		@endif
	@endif
</div>