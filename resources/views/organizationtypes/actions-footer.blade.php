<div class="well">
	@if(isset($has_submit))
		<button class="btn btn-primary confirm">Simpan</button>
		{!! Former::close() !!}
	@endif
	@if(!isset($is_list) && App\CertificationType::canList())
		<a href="{{ asset('organizationtypes') }}" class="btn btn-default">Senarai</a>  
	@endif
	@if(App\CertificationType::canCreate())
		<a href="{{ asset('organizationtypes/create') }}" class="btn btn-default">Kategori Baru</a>
	@endif
	@if(isset($type))
		@if($type->canUpdate())
			<a href="{{ asset('organizationtypes/'.$type->id.'/edit')  }}" class="btn btn-default">Kemaskini</a>
		@endif
		@if($type->canDelete())
			{!! Former::open(url('organizationtypes/'.$type->id))->class('form-inline') !!}
				{!! Former::hidden('_method', 'DELETE') !!}
				<button type="button" class="btn btn-default confirm-delete">Padam</button>
			{!! Former::close()  !!}
		@endif
	@endif
</div>