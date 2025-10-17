@extends('layouts.default')
@section('content')
	<h4 class="tender-title">Laporan Sistem Tender Online: Hasil Transaksi Tahunan</h4>

	{!! Former::open(url('reports/revenue'))->target('_blank') !!}
		{!! Former::select('year_start')
			->label('Mulai Tahun')
			->options($select_year)
			->placeholder('Pilih tahun laporan yang ingin dihasilkan...')
			->required() !!}
		{!! Former::select('year_end')
			->label('hingga')
			->options($select_year)
			->placeholder('Pilih tahun laporan yang ingin dihasilkan...')
			->required() !!}
		<div class="form-group">
			<label class="control-label col-lg-3">Maklumat Dikehendaki</label>
			<div class="col-lg-9">
				<div class="checkbox"><label><input type="checkbox" name="tender" value="1"> Transaksi Tender</label></div>
				<div class="checkbox"><label><input type="checkbox" name="quotation" value="1"> Transaksi Sebut Harga</label></div>
				<div class="checkbox"><label><input type="checkbox" name="transaction" value="1"> Kesemua Transaksi</label></div>
				<div class="checkbox"><label><input type="checkbox" name="registration" value="1"> Langganan Kontraktor</label></div>
				<div class="checkbox"><label><input type="checkbox" name="renewal" value="1"> Pembaharuan Langganan Kontraktor</label></div>
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-lg-9 col-lg-offset-3">
				{!! Former::submit('Hantar')->class('btn bg-blue-selangor') !!}
			</div>
		</div>
	{!! Former::close() !!}

@endsection

@section('scripts')

	<script type="text/javascript">
	$("input[name=tender]").change(function(){
		if(this.checked) {
			if( $("input[name=quotation]").is(':checked') ) {
				$("input[name=transaction]").prop('checked', true);
			}
		}
	});

	$("input[name=quotation]").change(function(){
		if(this.checked) {
			if( $("input[name=tender]").is(':checked') ) {
				$("input[name=transaction]").prop('checked', true);
			}
		}
	});

	$("input[name=transaction]").change(function(){
		if(this.checked) {
			$("input[name=tender]").prop('checked', true);
			$("input[name=quotation]").prop('checked', true);
		}
	});

	</script>
	
@endsection