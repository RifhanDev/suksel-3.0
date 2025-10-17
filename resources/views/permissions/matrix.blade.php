<div class="form-group">
	<label for="name" class="control-label col-lg-3 col-sm-3">Kebenaran</label>
	<div class="col-lg-9 col-sm-9">
		<div class="row permission-matrix">
			@if ($last = null) @endif
			@foreach(App\Permission::orderBy('group_name')->get() as $permission)
				@if($permission->group_name !== $last)
					<div class="clearfix"></div>
					<p><b>{{ $permission->group_name }}</b></p>
					<hr>
				@endif
				<div class="col-sm-3">
					<input type="checkbox" name="perms[]" value="{{ $permission->id }}"
					@if(isset($role) && in_array($permission->id, $role->perms->pluck('id')->toArray() )) checked="checked" @endif>
					{{ $permission->display_name }}
				</div>
				@if($permission->group_name !== $last)
					@if ($last = $permission->group_name) @endif
				@endif
			@endforeach
			<div class="clearfix"></div>
		</div>
	</div>
</div>
