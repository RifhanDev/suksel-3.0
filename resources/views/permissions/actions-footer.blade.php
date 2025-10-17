<div class="well">
	@if(isset($has_submit))
		<button class="btn btn-primary">Simpan</button>
	@endif
	@if(!isset($is_list) && App\Permission::canList())
		<a href="{{route('permissions.index')}}" class="btn btn-default">Senarai</a>
	@endif
	@if(App\Permission::canCreate())
		<a href="{{route('permissions.create')}}" class="btn btn-default">Tambah Kebenaran Baru</a>
	@endif
	{!! Former::close()  !!}
	@if(isset($permission))
		@if($permission->canDelete())
			{!! Former::open(url('permissions/'.$permission->id))->class('form-inline') !!}
			{!! Former::hidden('_method', 'DELETE')  !!}
			<button type="button" class="btn btn-default confirm-delete">Padam</button>
			{!! Former::close()  !!}
		@endif --}}
	@endif
</div>
