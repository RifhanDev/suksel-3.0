@php
	$availableRoles = App\Role::where('name', '!=', 'Vendor')->availableRoles()->pluck('name', 'id');
	$userRoles = $selectedRoles ?? (isset($currentUser) ? $currentUser->roles->pluck('id')->toArray() : []);
@endphp

<div class="row g-3">
	<div class="col-md-6">
		<label for="name" class="form-label fw-medium small">Nama <span class="text-danger">*</span></label>
		<input type="text" class="form-control" id="name" name="name"
			value="{{ old('name', $currentUser->name ?? '') }}" required>
		{!! $errors->first('name', '<div class="text-danger small mt-1">:message</div>') !!}
	</div>

	<div class="col-md-6">
		<label for="email" class="form-label fw-medium small">Alamat Emel <span class="text-danger">*</span></label>
		<input type="email" class="form-control" id="email" name="email"
			value="{{ old('email', $currentUser->email ?? '') }}" required>
		{!! $errors->first('email', '<div class="text-danger small mt-1">:message</div>') !!}
	</div>

	<div class="col-12">
		<label for="roles" class="form-label fw-medium small">Peranan <span class="text-danger">*</span></label>
		<select id="roles" name="roles[]" multiple required placeholder="Pilih Peranan">
			@foreach ($availableRoles as $id => $name)
				<option value="{{ $id }}" {{ in_array($id, old('roles', $userRoles)) ? 'selected' : '' }}>
					{{ $name }}
				</option>
			@endforeach
		</select>
		{!! $errors->first('roles', '<div class="text-danger small mt-1">:message</div>') !!}
	</div>

	@if (Auth::user()->hasRole('Admin'))
		<div class="col-12">
			<label for="organization_unit_id" class="form-label fw-medium small">Agensi <span class="text-danger">*</span></label>
			<select id="organization_unit_id" name="organization_unit_id" required
				placeholder="Pilih Agensi bagi pengguna dengan peranan Agency Admin atau Agency User">
				<option value=""></option>
				@foreach (App\OrganizationUnit::all()->pluck('name', 'id') as $id => $name)
					<option value="{{ $id }}"
						{{ (string) old('organization_unit_id', $currentUser->organization_unit_id ?? '') === (string) $id ? 'selected' : '' }}>
						{{ $name }}
					</option>
				@endforeach
			</select>
			{!! $errors->first('organization_unit_id', '<div class="text-danger small mt-1">:message</div>') !!}
		</div>
	@endif
</div>
