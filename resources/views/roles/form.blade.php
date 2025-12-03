<div class="card modern-form-card">
	<div class="card-header">
		<h3 class="card-title">
			<i class="ti ti-user"></i>
			Maklumat Peranan
		</h3>
	</div>
	<div class="card-body">
		<div class="row">
			<!-- Name Field -->
			<div class="col-md-12 mb-3">
				<label class="form-label required">
					<i class="ti ti-user"></i>
					Nama Peranan
				</label>
				<input class="form-control" required="true" <?php if (isset($role)): ?> disabled="disabled" <?php endif; ?>
					id="name" type="text" name="name" value="<?php echo isset($role) ? $role->name : ''; ?>" placeholder="Masukkan nama peranan">
			</div>
		</div>
	</div>
</div>

<div class="card modern-form-card">
	<div class="card-header">
		<h3 class="card-title">
			<i class="ti ti-key"></i>
			Kebenaran
		</h3>
	</div>
	<div class="card-body">
		<div class="row permission-matrix">
			@if ($last = null)
			@endif
			@foreach (App\Permission::orderBy('group_name')->get() as $permission)
				@if ($permission->group_name !== $last)
					<div class="col-12 mb-3">
						<hr>
						<h5 class="mb-3"><strong>{{ $permission->group_name }}</strong></h5>
					</div>
				@endif
				<div class="col-md-4 col-sm-6 mb-2">
					<div class="form-check">
						<input class="form-check-input" type="checkbox" name="perms[]" value="{{ $permission->id }}"
							id="perm_{{ $permission->id }}" @if (isset($role) && in_array($permission->id, $role->perms->pluck('id')->toArray())) checked="checked" @endif
							@if (request()->routeIs('roles.show')) disabled @endif>
						<label class="form-check-label" for="perm_{{ $permission->id }}">
							{{ $permission->display_name }}
						</label>
					</div>
				</div>
				@if ($permission->group_name !== $last)
					@if ($last = $permission->group_name)
					@endif
				@endif
			@endforeach
		</div>
	</div>
</div>
