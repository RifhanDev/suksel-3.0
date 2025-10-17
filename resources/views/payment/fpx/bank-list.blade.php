@extends('layouts.default')
@section('content')

	<form id="fpx_connect" action="{{ route('fpx.connect') }}">
		<div class="form-group">
			<label>Pilih Bank Anda: </label>
			<select required class="form-control" name="bank_code">
				<option value="">Sila Pilih Bank</option> 
				@foreach($banks as $code => $name)
					<?php $disabled = stristr($name, '(Offline)') != false ? 'disabled' : null ?>
						<option value="{{ $code }}" {{ $disabled }}>
						{{ $name }}
					</option>
				@endforeach
			</select>
		</div>
	<input type="submit" value="Teruskan ke Pembayaran Online Banking (FPX)" class="btn bg-blue-selangor">
	</form>
@endsection
