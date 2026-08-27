@extends('layouts.modernLanding')

@section('styles')
	<style>
		.connect-wrap {
			max-width: 640px;
			margin: 0 auto;
			padding: 32px 16px 60px;
		}

		.connect-notice {
			display: flex;
			align-items: center;
			gap: 10px;
			background: #fff7f7;
			border: 1px solid #f3c6cc;
			color: #a91830;
			border-radius: 10px;
			padding: 14px 18px;
			font-size: 0.88rem;
			font-weight: 600;
			margin-bottom: 20px;
		}

		.connect-card {
			background: #fff;
			border-radius: 12px;
			border: 1px solid #e5e7eb;
			box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
			overflow: hidden;
			margin-bottom: 20px;
		}

		.connect-card-header {
			padding: 16px 24px;
			border-bottom: 1px solid #e5e7eb;
			font-size: 0.78rem;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 0.4px;
			color: #6b7280;
		}

		.connect-info {
			padding: 4px 24px;
		}

		.connect-info-row {
			display: flex;
			justify-content: space-between;
			gap: 16px;
			padding: 14px 0;
			border-bottom: 1px solid #f3f4f6;
			font-size: 0.9rem;
		}

		.connect-info-row:last-child {
			border-bottom: none;
		}

		.connect-info-row dt {
			color: #6b7280;
			font-weight: 500;
		}

		.connect-info-row dd {
			margin: 0;
			color: #1e293b;
			font-weight: 600;
			text-align: right;
		}

		.connect-tnc {
			padding: 20px 24px;
			display: flex;
			align-items: flex-start;
			gap: 10px;
			font-size: 0.86rem;
			color: #374151;
		}

		.connect-tnc input[type="checkbox"] {
			margin-top: 3px;
			width: 16px;
			height: 16px;
			accent-color: #c41e3a;
			cursor: pointer;
			flex-shrink: 0;
		}

		.connect-tnc a {
			color: #c41e3a;
			font-weight: 600;
		}

		.connect-submit {
			display: block;
			width: 100%;
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

		.connect-submit:hover:not(:disabled) {
			background: #a91830;
			box-shadow: 0 2px 6px rgba(196, 30, 58, 0.25);
		}

		.connect-submit:disabled {
			background: #d1d5db;
			cursor: not-allowed;
			box-shadow: none;
		}
	</style>
@endsection

@section('content')
	<div class="connect-wrap">
		<div class="connect-notice">
			<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
				stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<circle cx="12" cy="12" r="10" />
				<path d="M12 8v4M12 16h.01" />
			</svg>
			Transaksi Perbankan FPX sedang dalam proses. Harap bersabar.
		</div>

		<div class="connect-card">
			<div class="connect-card-header">Butiran Transaksi</div>
			<dl class="connect-info">
				<div class="connect-info-row">
					<dt>No Transaksi</dt>
					<dd>{{ $transaction->number }}</dd>
				</div>
				<div class="connect-info-row">
					<dt>Jumlah</dt>
					<dd>MYR {{ sprintf('%.2f', $transaction->amount) }}</dd>
				</div>
				<div class="connect-info-row">
					<dt>Kaedah Pembayaran</dt>
					<dd>Online Banking (FPX)</dd>
				</div>
				<div class="connect-info-row">
					<dt>Bank</dt>
					<dd>{{ App\FpxBank::active()->where('code', Request::get('bank_code'))->first()->display_name }}</dd>
				</div>
				<div class="connect-info-row">
					<dt>Status</dt>
					<dd>{{ App\Transaction::$statuses[$transaction->status] }}</dd>
				</div>
			</dl>
		</div>

		<div class="connect-card">
			<form method="post" id="fpx_connect" action="{{ $transaction->gateway->endpoint_url }}" target="_blank">
				@foreach ($fpx->request_keys as $key => $value)
					<input type="hidden" name="{{ $key }}" value="{{ $value }}">
				@endforeach

				<label class="connect-tnc">
					<input type="checkbox" id="acceptTnc" value="1" />
					<span>
						Saya terima
						<a href="https://www.mepsfpx.com.my/FPXMain/termsAndConditions.jsp" target="_blank">Terma
							& Syarat</a>
					</span>
				</label>

				<div style="padding: 0 24px 24px;">
					<button id="submitBtn" type="submit" class="connect-submit" disabled>
						Sila terima terma dan syarat terlebih dahulu.
					</button>
				</div>
			</form>
		</div>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
			$("#fpx_connect").submit(function() {
				setTimeout(function() {
					window.location = '{{ URL::route('txn_status', $transaction->id) }}';
				}, 1000);
			});

			$("#acceptTnc").change(function() {
				if (this.checked) {
					$('#submitBtn').prop('disabled', false);
					$('#submitBtn').text('Teruskan ke Pembayaran Online Banking (FPX)');
				} else {
					$('#submitBtn').prop('disabled', true);
					$('#submitBtn').text('Sila terima terma dan syarat terlebih dahulu.');
				}
			});
		});
	</script>
@endsection
