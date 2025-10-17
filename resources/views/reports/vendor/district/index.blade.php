@extends('layouts.default')
@section('styles')

	<link href="{{ asset('css/form.css') }}" rel="stylesheet">

@endsection
@section('content')
<h4 class="tender-title">Laporan Sistem Tender Online: Senarai Syarikat Mengikut Daerah</h4>

{!! Former::open(action('ReportVendorDistrictController@view'))->target('_blank')->method('GET') !!}
    {!! Former::select('district')
        ->label('Daerah')
        ->options(['all' => 'Semua'] + App\Vendor::$districts)
        ->placeholder('Pilih daerah...')
        ->required()
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