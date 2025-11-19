{!! Former::populate($gateway) !!}
{!! Former::select('organization_unit_id')
		->label('Agensi')
		->placeholder('Pilihan Agensi Penerima Bayaran...')
		->options(App\OrganizationUnit::pluck('name', 'id'))
		->required() !!}
{!! Former::select('type')
		->label('Saluran')
		->placeholder('')
		->options(array_slice(App\Gateway::$methods, 1))
		->required() !!}

<div id="fpxVersion" style="display:none">
	{!! Former::text('version')
			->label('Versi FPX') !!}
</div>

{!! Former::text('merchant_code')
    	->label('ID Merchant') !!}
{!! Former::text('private_key')
    	->label('Kekunci Rahsia') !!}
{!! Former::text('endpoint_url')
    	->label('URL Transaksi') !!}
{!! Former::text('daemon_url')
    	->label('URL Daemon') !!}
{!! Former::text('transaction_prefix')
    	->label('Label Transaksi') !!}
{!! Former::checkbox('active')
		->label('Aktif')
		->checked(isset($gateway) && $gateway->active)
		->forceValue(1) !!}
{!! Former::checkbox('default')
		->label('Akaun Utama')
		->checked(isset($gateway) && $gateway->default)
		->forceValue(1) !!}

@section('scripts')
	<script type="text/javascript">
		$("#organization_unit_id").selectize();
		$("#type").selectize();
		
		if (($('#type').val()) === 'fpx') {
			$('#fpxVersion').show();
		} else {
			$('#fpxVersion').hide();
		}
	</script>
@endsection