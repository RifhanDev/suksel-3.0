{{-- Maklumat Kebenaran --}}
<div class="content-card">
    <div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
            stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>
        </svg>
        <span class="fw-bold text-dark text-uppercase small">Maklumat Kebenaran</span>
    </div>

    <div class="p-4">
        <div class="row g-3">
            {{-- Nama Kumpulan --}}
            <div class="col-12">
                <label for="group_name" class="form-label fw-medium small">Nama Kumpulan <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="group_name" name="group_name"
                    list="group_name_list"
                    value="{{ isset($permission) ? $permission->group_name : old('group_name') }}"
                    placeholder="Masukkan nama kumpulan" required
                    @if (isset($permission)) disabled @endif>
                <datalist id="group_name_list">
                    @foreach (App\Permission::select('group_name')->groupBy('group_name')->orderBy('group_name')->get() as $group)
                        <option value="{{ $group->group_name }}">
                    @endforeach
                </datalist>
                {!! $errors->first('group_name', '<div class="text-danger small mt-1">:message</div>') !!}
            </div>

            {{-- Nama --}}
            <div class="col-12">
                <label for="name" class="form-label fw-medium small">Nama <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ isset($permission) ? $permission->name : old('name') }}"
                    placeholder="Masukkan nama kebenaran" required
                    @if (isset($permission)) disabled @endif>
                {!! $errors->first('name', '<div class="text-danger small mt-1">:message</div>') !!}
            </div>

            {{-- Keterangan --}}
            <div class="col-12">
                <label for="display_name" class="form-label fw-medium small">Keterangan <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="display_name" name="display_name"
                    value="{{ isset($permission) ? $permission->display_name : old('display_name') }}"
                    placeholder="Masukkan keterangan kebenaran" required>
                {!! $errors->first('display_name', '<div class="text-danger small mt-1">:message</div>') !!}
            </div>
        </div>
    </div>
</div>