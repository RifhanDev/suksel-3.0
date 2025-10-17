@extends('layouts.default')
@section('content')
<h4 class="tender-title">Laporan Sistem Tender Online: Senarai Pengguna Agensi</h4>

{!! Former::open(action('ReportUserAgencyController@view'))->target('_blank') !!}
    @if(Auth::user()->can('Report:view:user_agency'))
    {!! Former::select('agency')
        ->label('Agensi')
        ->options($select_agencies)
        ->placeholder('Pilih agensi...')
        ->required()
        ->addClass('selectize') !!}
    @endif
    {!! Former::select('roles[]')
        ->label('Peranan')
        ->multiple(true)
        ->options($select_roles)
        ->placeholder('Pilih peranan...')
        ->addClass('selectize') !!}

    <div class="form-group">
      <div class="col-lg-9 col-lg-offset-3">
        {!! Former::submit('Hantar')->class('btn bg-blue-selangor') !!}
      </div>
    </div>
{!! Former::close() !!}

@endsection

@section('scripts')
<script type="text/javascript">
$('.selectize').each(function() {
    $(this).selectize();
});
</script>
@endsection