{{-- Nama Peranan --}}
<div class="content-card mb-3">
    <div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
            stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
        </svg>
        <span class="fw-bold text-dark text-uppercase small">Maklumat Peranan</span>
    </div>

    <div class="p-4">
        <div class="row g-3">
            <div class="col-12">
                <label for="name" class="form-label fw-medium small">Nama Peranan <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ isset($role) ? $role->name : old('name') }}"
                    placeholder="Masukkan nama peranan" required
                    @if (isset($role)) disabled @endif>
                {!! $errors->first('name', '<div class="text-danger small mt-1">:message</div>') !!}
            </div>
        </div>
    </div>
</div>

{{-- Kebenaran --}}
<div class="content-card">
    <div class="bg-light px-4 py-3 border-bottom d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            <span class="fw-bold text-dark text-uppercase small">Kebenaran</span>
        </div>
        @if (!request()->routeIs('roles.show'))
            <div class="d-flex align-items-center gap-2">
                <input type="checkbox" id="selectAllPerms" class="form-check-input mb-0"
                    style="width:1em;height:1em;cursor:pointer;">
                <label for="selectAllPerms" class="small fw-medium mb-0" style="cursor:pointer;">Pilih Semua</label>
            </div>
        @endif
    </div>

    <div class="p-4">
        @php
            $last = null;
            $permissions = App\Permission::orderBy('group_name')->orderBy('display_name')->get();
            $rolePermIds = isset($role) ? $role->perms->pluck('id')->toArray() : [];
        @endphp

        @foreach ($permissions as $permission)
            @if ($permission->group_name !== $last)
                @if ($last !== null)
                    </div>{{-- close row --}}
                    </div>{{-- close perm-group --}}
                @endif

                <div class="perm-group mb-4" data-group="{{ $permission->group_name }}">
                    <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width:8px;height:8px;border-radius:50%;background:var(--sg-red);display:inline-block;flex-shrink:0;"></span>
                            <span class="fw-semibold small text-dark text-uppercase" style="letter-spacing:0.04em;">{{ $permission->group_name }}</span>
                        </div>
                        @if (!request()->routeIs('roles.show'))
                            <div class="d-flex align-items-center gap-2">
                                <input type="checkbox" class="form-check-input select-group-cb mb-0"
                                    data-group="{{ $permission->group_name }}"
                                    style="width:1em;height:1em;cursor:pointer;">
                                <label class="small mb-0" style="cursor:pointer;">Pilih Semua</label>
                            </div>
                        @endif
                    </div>
                    <div class="row g-2">
                @php $last = $permission->group_name; @endphp
            @endif

            <div class="col-lg-4 col-md-6 col-12">
                <div class="perm-check-item d-flex align-items-start gap-2 p-2 rounded-2"
                    style="background:#f8f9fa; border: 1px solid #eee; transition: background 0.15s;">
                    <input class="form-check-input flex-shrink-0 perm-checkbox mt-0" type="checkbox"
                        name="perms[]" value="{{ $permission->id }}"
                        id="perm_{{ $permission->id }}"
                        data-group="{{ $permission->group_name }}"
                        @if (in_array($permission->id, $rolePermIds)) checked @endif
                        @if (request()->routeIs('roles.show')) disabled @endif
                        style="width:1em; height:1em; margin-top:2px;">
                    <label class="form-check-label small mb-0" for="perm_{{ $permission->id }}"
                        style="cursor: {{ request()->routeIs('roles.show') ? 'default' : 'pointer' }}; line-height: 1.4;">
                        {{ $permission->display_name }}
                    </label>
                </div>
            </div>
        @endforeach

        @if ($last !== null)
            </div>{{-- close last row --}}
            </div>{{-- close last perm-group --}}
        @endif

        @if ($permissions->isEmpty())
            <p class="text-muted small m-0">Tiada kebenaran tersedia.</p>
        @endif
    </div>
</div>

@if (!request()->routeIs('roles.show'))
    @push('scripts')
        <script>
            var masterCb = document.getElementById('selectAllPerms');

            // Master checkbox
            masterCb.addEventListener('change', function() {
                document.querySelectorAll('.perm-checkbox:not([disabled])').forEach(cb => cb.checked = this.checked);
                updateGroupStates();
            });

            // Group checkboxes
            document.querySelectorAll('.select-group-cb').forEach(function(gcb) {
                gcb.addEventListener('change', function() {
                    var group = this.dataset.group;
                    document.querySelectorAll('.perm-checkbox[data-group="' + group + '"]:not([disabled])')
                        .forEach(cb => cb.checked = this.checked);
                    updateMasterState();
                });
            });

            // Permission checkboxes
            document.querySelectorAll('.perm-checkbox:not([disabled])').forEach(function(cb) {
                cb.addEventListener('change', function() {
                    updateGroupStates();
                    updateMasterState();
                });
            });

            // Hover effect
            document.querySelectorAll('.perm-check-item').forEach(function(el) {
                var cb = el.querySelector('.perm-checkbox');
                if (cb && !cb.disabled) {
                    el.addEventListener('mouseenter', function() { this.style.background = '#eef0f3'; });
                    el.addEventListener('mouseleave', function() { this.style.background = '#f8f9fa'; });
                }
            });

            function updateGroupStates() {
                document.querySelectorAll('.select-group-cb').forEach(function(gcb) {
                    var group = gcb.dataset.group;
                    var boxes = Array.from(document.querySelectorAll('.perm-checkbox[data-group="' + group + '"]:not([disabled])'));
                    var checkedCount = boxes.filter(cb => cb.checked).length;
                    gcb.checked = checkedCount === boxes.length;
                    gcb.indeterminate = checkedCount > 0 && checkedCount < boxes.length;
                });
            }

            function updateMasterState() {
                var all = Array.from(document.querySelectorAll('.perm-checkbox:not([disabled])'));
                var checkedCount = all.filter(cb => cb.checked).length;
                masterCb.checked = checkedCount === all.length;
                masterCb.indeterminate = checkedCount > 0 && checkedCount < all.length;
            }

            // Init states on page load
            updateGroupStates();
            updateMasterState();
        </script>
    @endpush
@endif