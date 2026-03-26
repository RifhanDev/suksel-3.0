<div class="d-flex justify-content-between align-items-center p-4 bg-light">
    <div class="d-flex gap-2">
        @if (!isset($is_list) && App\Role::canList())
            <a href="{{ asset('roles') }}" class="btn-form btn-form-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Senarai Peranan
            </a>
        @endif
        @if (App\Role::canCreate())
            <a href="{{ asset('roles/create') }}" class="btn-form btn-form-create">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Tambah Peranan Baru
            </a>
        @endif
        @if (isset($role))
            @if ($role->canDelete())
                <form action="{{ url('roles/' . $role->id) }}" method="POST" class="d-inline m-0">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn-form btn-form-danger confirm-delete">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6l-1 14H6L5 6"></path>
                            <path d="M10 11v6"></path>
                            <path d="M14 11v6"></path>
                            <path d="M9 6V4h6v2"></path>
                        </svg>
                        Padam
                    </button>
                </form>
            @endif
        @endif
    </div>
    @if (isset($has_submit))
        <button type="submit" class="btn-form btn-form-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                <polyline points="7 3 7 8 15 8"></polyline>
            </svg>
            Simpan
        </button>
    @endif
</div>