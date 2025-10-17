{!! Former::text('vendor')
	->label('Nama Syarikat')
	->forceValue($transaction->vendor->name)
	->disabled() !!}

{!! Former::text('organization_unit_id')
	->label('Agensi Pembayaran')
	->disabled() !!}

{!! Former::text('number')
	->label('No. Transaksi')
	->disabled() !!}

@if($transaction->gateway_reference)
	{!! Former::text('receipt_number')
		->label('No. Resit')
		->disabled() !!}
@endif    

{!! Former::text('type')
	->label('Jenis Pembayaran')
	->forceValue(App\Transaction::$types[$transaction->type])
	->disabled() !!}

{!! Former::text('amount')
	->label('Amount')
	->disabled() !!}

@if(in_array($transaction->status, ['pending', 'pending-authorization']))
	{!! Former::select('status')
		->label('Status')
		->options(App\Transaction::$statuses) !!}
@else
	{!! Former::text('status')
		->label('Status')
		->disabled()
		->forceValue(App\Transaction::$statuses[$transaction->status]) !!}
@endif

@if(in_array($transaction->status, ['pending', 'pending-authorization']))
	{!! Former::text('gateway_reference')
	   ->label('No Rujukan Pembayaran') !!}

	{!! Former::text('gateway_auth')
	   ->label('No Kebenaran Pembayaran') !!}
@endif