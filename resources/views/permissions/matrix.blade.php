<div class="col-12">
    <label class="form-label fw-medium small">Kebenaran</label>
    <div class="row g-2 permission-matrix">
        @php $last = null; @endphp
        @foreach (App\Permission::orderBy('group_name')->get() as $permission)
            @if ($permission->group_name !== $last)
                @if ($last !== null)
                    </div>{{-- close row --}}
                    </div>{{-- close group --}}
                @endif
                <div class="col-12 mt-3">
                    <div class="fw-semibold small text-uppercase mb-1" style="color: var(--sg-red); letter-spacing: 0.04em;">
                        {{ $permission->group_name }}
                    </div>
                    <hr class="mt-1 mb-2">
                    <div class="row g-2">
                @php $last = $permission->group_name; @endphp
            @endif

            <div class="col-lg-3 col-md-4 col-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="perms[]" value="{{ $permission->id }}"
                        id="matrix_perm_{{ $permission->id }}"
                        @if (isset($role) && in_array($permission->id, $role->perms->pluck('id')->toArray())) checked @endif>
                    <label class="form-check-label small" for="matrix_perm_{{ $permission->id }}">
                        {{ $permission->display_name }}
                    </label>
                </div>
            </div>
        @endforeach

        @if ($last !== null)
            </div>{{-- close last row --}}
            </div>{{-- close last group --}}
        @endif
    </div>
</div>