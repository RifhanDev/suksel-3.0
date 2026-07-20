<div class="d-flex gap-2">
    @if (isset($has_submit))
        <button class="btn-form btn-form-primary confirm">Simpan</button>
    @endif
    @if (!isset($is_list) && App\Code::canList())
        <a href="{{ asset('codes') }}" class="btn-form btn-form-secondary">Senarai Kod</a>
    @endif
    @if (App\Code::canCreate())
        <a href="{{ asset('codes/create') }}" class="btn-form btn-form-create">Kod Baru</a>
    @endif

    {!! Former::close() !!}

    @if (isset($certificationcode))
        @if ($certificationcode->canShow())
            <a href="{{ asset('codes/' . $certificationcode->id) }}" class="btn-form btn-form-secondary">Maklumat</a>
        @endif
        @if ($certificationcode->canUpdate())
            <a href="{{ asset('codes/' . $certificationcode->id . '/edit') }}"
                class="btn-form btn-form-primary">Kemaskini</a>
        @endif
        @if ($certificationcode->canDelete())
            {!! Former::open(url('codes/' . $certificationcode->id))->class('m-0') !!}
            {!! Former::hidden('_method', 'DELETE') !!}
            <button type="button" class="btn-form btn-form-danger confirm-delete">Padam</button>
            {!! Former::close() !!}
        @endif
    @endif
</div>
