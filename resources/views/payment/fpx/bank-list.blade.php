@extends('layouts.modernLanding')

@section('styles')
	<style>
		.bank-wrap {
			max-width: 560px;
			margin: 0 auto;
			padding: 32px 16px 60px;
		}

		.bank-header {
			margin-bottom: 24px;
		}

		.bank-header h2 {
			font-size: 1.5rem;
			font-weight: 700;
			color: #1e293b;
			margin: 0 0 4px;
		}

		.bank-header p {
			color: #6b7280;
			font-size: 0.9rem;
			margin: 0;
		}

		.bank-card {
			background: #fff;
			border-radius: 12px;
			border: 1px solid #e5e7eb;
			box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
			padding: 24px;
		}

		.bank-label {
			font-size: 0.78rem;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 0.4px;
			color: #6b7280;
			margin-bottom: 8px;
			display: block;
		}

		.bank-select {
			width: 100%;
			padding: 12px 14px;
			border: 1px solid #d1d5db;
			border-radius: 8px;
			font-size: 0.92rem;
			color: #1e293b;
			background: #fff;
			appearance: none;
			background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
			background-repeat: no-repeat;
			background-position: right 14px center;
			padding-right: 38px;
			transition: border-color 0.15s;
		}

		.bank-select:focus {
			outline: none;
			border-color: #c41e3a;
			box-shadow: 0 0 0 3px rgba(196, 30, 58, 0.1);
		}

		.bank-submit {
			display: block;
			width: 100%;
			margin-top: 18px;
			padding: 13px 18px;
			background: #c41e3a;
			color: #fff;
			border: none;
			border-radius: 8px;
			font-weight: 700;
			font-size: 0.9rem;
			cursor: pointer;
			transition: background 0.15s, box-shadow 0.15s;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
		}

		.bank-submit:hover {
			background: #a91830;
			box-shadow: 0 2px 6px rgba(196, 30, 58, 0.25);
		}

		.bank-submit:disabled {
			background: #d1d5db;
			cursor: not-allowed;
			box-shadow: none;
		}
	</style>
@endsection

@section('content')
	<div class="bank-wrap">
		<div class="bank-header">
			<h2>Pilih Bank Anda</h2>
			<p>Pilih bank untuk teruskan pembayaran melalui Internet Banking (FPX).</p>
		</div>

		<div class="bank-card">
			<form id="fpx_connect" action="{{ route('fpx.connect') }}">
				<label class="bank-label" for="bank_code">Bank</label>
				<select required class="bank-select" name="bank_code" id="bank_code">
					<option value="">Sila Pilih Bank</option>
					@foreach ($banks as $code => $name)
						<?php $disabled = stristr($name, '(Offline)') != false ? 'disabled' : null; ?>
						<option value="{{ $code }}" {{ $disabled }}>
							{{ $name }}
						</option>
					@endforeach
				</select>
				<button type="submit" class="bank-submit">Teruskan ke Pembayaran Online Banking (FPX)</button>
			</form>
		</div>
	</div>
@endsection
