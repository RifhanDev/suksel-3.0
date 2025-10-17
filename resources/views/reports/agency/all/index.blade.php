@extends('layouts.default')
@section('content')
	
<h4 class="tender-title">Laporan Sistem Tender Online: Transaksi Semua Agensi</h4>

{!! Former::open(action('ReportAgencyAllController@view'))->target('_blank') !!}
	
{!! Former::select('type')
			->label('Jenis Laporan')
			->options($select_type)
			->placeholder('Pilih jenis laporan yang ingin dihasilkan...')
			->required() !!}

	{!! Former::select('year_start')
		->label('Tahun')
		->options($select_year)
		->placeholder('Pilih tahun laporan yang ingin dihasilkan...')
		->required() !!}

	{!! Former::select('year_end')
	->label('hingga')
		->options($select_year)
		->placeholder('Pilih tahun laporan yang ingin dihasilkan...') !!}

	<div class="form-group">
		<div class="col-lg-9 col-lg-offset-3">
				{!! Former::submit('Hantar')->class('btn bg-blue-selangor') !!}
		</div>
	</div>
{!! Former::close() !!}

@endsection

@section('scripts')

	<script type="text/javascript">
	    	$("form").find("select[name=year_end]").each(function(){
	        	$(this).parents('.form-group').hide();
	    	});

	    	$("form select[name=type]").on('change', function(){
	        	selected = $(this).find('option:selected')

	        	if(selected && selected.val() == 'yearly')  {
	            year_end   = $("select[name=year_end]");
	            form_group = year_end.parents('.form-group')
	            form_group.fadeIn();
	            form_group.find('label').text('hingga');
	            $("select[name=year_start]").parents('.form-group').find('label').text('Mulai Tahun');
	            year_end.attr('required', true);
	        	} else {
	            year_end   = $("select[name=year_end]:visible");
	            form_group = year_end.parents('.form-group')
	            form_group.fadeOut();
	            $("select[name=year_start]").parents('.form-group').find('label').text('Tahun');
	            year_end.attr('required', false);
	        	}
	    	});
	</script>

@endsection