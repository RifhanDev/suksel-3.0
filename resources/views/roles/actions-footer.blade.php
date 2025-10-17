<div class="well">
	@if(isset($has_submit))
		<button class="btn btn-primary">Simpan</button>
	@endif
		@if(!isset($is_list) && App\Role::canList())
			<a href="{{ asset('roles')}}" class="btn btn-default">Senarai</a>
		@endif
		@if(App\Role::canCreate())
			<a href="{{ asset('roles/create') }}" class="btn btn-default">Tambah Peranan Baru</a>
		@endif
	{!! Former::close() !!}
	@if(isset($role))
		@if($role->canDelete())
			{!! Former::open(url('roles/'.$role->id))->class('form-inline') !!}
			{!! Former::hidden('_method', 'DELETE') !!}
			<button type="button" class="btn btn-default confirm-delete">Padam</button>
			{!! Former::close() !!}
		@endif
	@endif
</div>
