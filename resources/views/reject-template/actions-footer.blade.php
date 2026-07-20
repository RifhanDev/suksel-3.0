<div class="d-flex gap-2">
    @if (isset($has_submit))
        <button type="submit" class="btn-form btn-form-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                <polyline points="7 3 7 8 15 8"></polyline>
            </svg>
            Simpan
        </button>
    @endif
    @if (!isset($is_list) && App\Models\rejectTemplate::canList())
        <a href="{{ asset('reject-template') }}" class="btn-form btn-form-secondary">Senarai Templat Penolakan</a>
    @endif
    @if (App\Models\rejectTemplate::canCreate())
        <a href="{{ asset('reject-template/create') }}" class="btn-form btn-form-create">Templat Penolakan Baru</a>
    @endif
    @if (isset($template))
        @if ($template->canShow())
            <a href="{{ asset('reject-template/' . $template->id) }}" class="btn-form btn-form-secondary">Maklumat</a>
        @endif
        @if ($template->canUpdate())
            <a href="{{ asset('reject-template/' . $template->id . '/edit') }}" class="btn-form btn-form-primary">Kemaskini</a>
        @endif
        @if ($template->canDelete())
            <form action="{{ url('reject-template/' . $template->id) }}" method="POST" class="d-inline m-0">
                @csrf
                @method('DELETE')
                <button type="button" class="btn-form btn-form-danger confirm-delete">Padam</button>
            </form>
        @endif
    @endif
</div>
