@extends('layouts.default')
@section('content')
	<h4 class="tender-title">Laporan Sistem Tender Online: 10 Agensi Aktif</h4>

	{!! Former::open( url('reports/agency/active'))->target('_blank') !!}
    	{!! Former::select('type')
        ->label('Jenis Laporan')
        ->options($select_type)
        ->placeholder('Pilih jenis laporan yang ingin dihasilkan...') !!}
    	{!! Former::select('year')
        ->label('Tahun')
        ->options($select_year)
        ->placeholder('Pilih tahun laporan yang ingin dihasilkan...') !!}
    	<div class="form-group">
      	<div class="col-lg-9 col-lg-offset-3">
        		{!! Former::submit('Hantar')->class('btn bg-blue-selangor') !!}
      	</div>
    	</div>
	{!! Former::close() !!}

@endsection
