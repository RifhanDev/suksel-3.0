<div class="d-flex gap-2">
    @if (isset($has_submit))
        <button class="btn-form btn-form-primary confirm">Simpan</button>
        {!! Former::close() !!}
    @endif
    @if (!isset($is_list) && App\CertificationType::canList())
        <a href="{{ asset('organizationtypes') }}" class="btn-form btn-form-secondary">Senarai</a>
    @endif
    @if (App\CertificationType::canCreate())
        <a href="{{ asset('organizationtypes/create') }}" class="btn-form btn-form-create">Kategori Baru</a>
    @endif
    @if (isset($type))
        @if ($type->canUpdate())
            <a href="{{ asset('organizationtypes/' . $type->id . '/edit') }}" class="btn-form btn-form-primary">Kemaskini</a>
        @endif
        @if ($type->canDelete())
            {!! Former::open(url('organizationtypes/' . $type->id))->class('form-inline m-0') !!}
            {!! Former::hidden('_method', 'DELETE') !!}
            <button type="button" class="btn-form btn-form-danger confirm-delete">Padam</button>
            {!! Former::close() !!}
        @endif
    @endif
</div>
