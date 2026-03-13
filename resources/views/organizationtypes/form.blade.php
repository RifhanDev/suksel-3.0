<div class="mb-4">
    <label class="form-label fw-bold">Nama Kategori <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $type->name ?? '') }}" required>
    @if ($errors->has('name'))
        <div class="text-danger small mt-1">{{ $errors->first('name') }}</div>
    @endif
</div>

@if (isset($process) && $process == 'update')
    <div class="mb-4">
        <label class="form-label fw-bold">Susunan <span class="text-danger">*</span></label>
        <div class="p-3 bg-light rounded border">
            <ul id="simpleList" class="list-group mb-3 shadow-sm border-0">
                @foreach ($list_organization_type as $row_org_type)
                    <li style="{{ $row_org_type->id == ($type->id ?? 0) ? 'background-color: #f0fdf4; border-left: 4px solid var(--sg-green); cursor: grab;' : 'cursor: grab;' }}"
                        data-id="{{ $row_org_type->id }}"
                        class="list-group-item d-flex align-items-center gap-3 border-0 border-bottom {{ $row_org_type->id == ($type->id ?? 0) ? 'fw-bold text-dark' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="{{ $row_org_type->id == ($type->id ?? 0) ? 'text-success' : 'text-muted' }}">
                            <line x1="8" y1="6" x2="21" y2="6"></line>
                            <line x1="8" y1="12" x2="21" y2="12"></line>
                            <line x1="8" y1="18" x2="21" y2="18"></line>
                            <line x1="3" y1="6" x2="3.01" y2="6"></line>
                            <line x1="3" y1="12" x2="3.01" y2="12"></line>
                            <line x1="3" y1="18" x2="3.01" y2="18"></line>
                        </svg>
                        {{ $row_org_type->name }}
                    </li>
                @endforeach
            </ul>

            <input type="hidden" name="org_type_id" id="org_type_id" value="{{ $type->id ?? '' }}">
            <button type="button" class="btn-form btn-form-danger" id="resetOrder">
                Set Semula Susunan
            </button>
        </div>
    </div>
@endif

@section('scripts')
    <script src="{{ asset('js/sortable.js') }}"></script>
    <script type="text/javascript">
        var simpleList = document.getElementById('simpleList');
        var sortable = null;
        var initialOrder = [];

        if (simpleList) {
            // create sortable and save instance
            sortable = Sortable.create(simpleList, {
                animation: 150
            });

            // save initial order
            initialOrder = sortable.toArray();

            document.getElementById('resetOrder').addEventListener('click', function(e) {
                if (sortable) sortable.sort(initialOrder);
            });
        }

        var btnSimpan = document.getElementById('btn-simpan');
        if (btnSimpan) {
            btnSimpan.addEventListener('click', function(e) {
                var order = sortable ? sortable.toArray() : [];

                var data = {
                    "org_type_id": $("#org_type_id").val(),
                    "order": order,
                    "name": $("input[name='name']").val(),
                    "_token": "{{ csrf_token() }}"
                };

                $.ajax({
                    type: "post",
                    url: "{{ route('org_type_custom_save') }}",
                    data: data,
                    success: function(response) {
                        if (response.status == "success") {
                            alert(response.message);
                            window.location.href = response.redirect;
                        }
                    },
                    error: function(err) {
                        alert("Ralat semasa menyimpan: " + (err.responseJSON?.message ||
                            'Sila cuba lagi.'));
                    }
                });
            });
        }
    </script>
@endsection
