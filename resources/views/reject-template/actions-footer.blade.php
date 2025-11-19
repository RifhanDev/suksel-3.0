<div class="well">
	@if(isset($has_submit))
		<button class="btn btn-primary confirm">Simpan</button>
	@endif
	@if(!isset($is_list) && App\Models\rejectTemplate::canList())
		<a href="{{ asset('reject-template') }}" class="btn btn-default">Senarai Templat Penolakan</a>  
	@endif
	@if(App\Models\rejectTemplate::canCreate())
		<a href="{{ asset('reject-template/create')}}" class="btn btn-default">Templat Penolakan Baru</a>
	@endif

	{!! Former::close() !!}

	@if(isset($template))
		@if($template->canShow())
			<a href="{{ asset('reject-template/'.$template->id) }}" class="btn btn-default">Maklumat</a>
		@endif
		@if($template->canUpdate())
			<a href="{{ asset('reject-template/'.$template->id.'/edit') }}" class="btn btn-default">Kemaskini</a>
		@endif
		@if($template->canDelete())
			{!! Former::open(url('reject-template/'.$template->id))->class('form-inline') !!}
				{!! Former::hidden('_method', 'DELETE') !!}
				<button type="button" class="btn btn-default confirm-delete">Padam</button>
			{!! Former::close() !!}
		@endif
	@endif
</div>