<div class="row g-3">
    <!-- NAMA -->
    <div class="col-md-6">
        <label for="name" class="form-label fw-medium small">Nama <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="name" name="name"
            value="{{ old('name', $currentUser->name ?? '') }}" required>
        {!! $errors->first('name', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <!-- EMAIL -->
    <div class="col-md-6">
        <label for="email" class="form-label fw-medium small">Alamat Emel <span class="text-danger">*</span></label>
        <input type="email" class="form-control" id="email" name="email"
            value="{{ old('email', $currentUser->email ?? '') }}" required>
        {!! $errors->first('email', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <!-- IC NUMBER -->
    <div class="col-md-6">
        <label for="ic_number" class="form-label fw-medium small">Nombor Kad Pengenalan</label>
        <input type="text" class="form-control" id="ic_number" name="ic_number"
            value="{{ old('ic_number', $currentUser->ic_number ?? '') }}">
        {!! $errors->first('ic_number', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <div class="col-md-6">
        <label for="jawatan" class="form-label fw-medium small">Jawatan</label>
        <input type="text" class="form-control" id="jawatan" name="jawatan"
            value="{{ old('jawatan', $currentUser->jawatan ?? '') }}">
        {!! $errors->first('jawatan', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <!-- GRED -->
    <div class="col-md-6">
        <label for="gred" class="form-label fw-medium small">Gred</label>
        <input type="text" class="form-control" id="gred" name="gred"
            value="{{ old('gred', $currentUser->gred ?? '') }}">
        {!! $errors->first('gred', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <!-- ROLES -->
    <div class="col-12">
        <label for="roles" class="form-label fw-medium small">Peranan <span class="text-danger">*</span></label>
        <select id="roles" name="roles[]" multiple required placeholder="Pilih Peranan">
            @php
                $availableRoles = App\Role::where('name', '!=', 'Vendor')->availableRoles()->pluck('name', 'id');
                $userRoles = isset($currentUser) ? $currentUser->roles->pluck('id')->toArray() : [];
            @endphp
            @foreach ($availableRoles as $id => $name)
                <option value="{{ $id }}" {{ in_array($id, old('roles', $userRoles)) ? 'selected' : '' }}>
                    {{ $name }}</option>
            @endforeach
        </select>
        {!! $errors->first('roles', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <!-- AGENSI (Admin Only) -->
    @if (Auth::user()->hasRole('Admin'))
        <div class="col-12">
            <label for="organization_unit_id" class="form-label fw-medium small">Agensi <span
                    class="text-danger">*</span></label>
            <select id="organization_unit_id" name="organization_unit_id"
                placeholder="Pilih Agensi bagi pengguna dengan peranan Agency Admin atau Agency User">
                @foreach (App\OrganizationUnit::all()->pluck('name', 'id') as $id => $name)
                    <option value="{{ $id }}"
                        {{ old('organization_unit_id', $currentUser->organization_unit_id ?? '') == $id ? 'selected' : '' }}>
                        {{ $name }}</option>
                @endforeach
            </select>
            {!! $errors->first('organization_unit_id', '<div class="text-danger small mt-1">:message</div>') !!}
        </div>
    @endif
</div>

@section('scripts')
    @parent
    <script type="text/javascript">
        $(document).ready(function() {
            $('#roles').selectize({
                plugins: ['remove_button'],
            });

            if ($('#organization_unit_id').length) {
                $('#organization_unit_id').selectize();
            }
        });
    </script>
@endsection
