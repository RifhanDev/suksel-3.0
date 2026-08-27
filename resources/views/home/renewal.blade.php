@extends('layouts.modernLanding')

@section('styles')
	<style>
		.renewal-wrap {
			max-width: 640px;
			margin: 0 auto;
			padding: 32px 16px 60px;
		}

		.renewal-header {
			margin-bottom: 24px;
		}

		.renewal-header h2 {
			font-size: 1.5rem;
			font-weight: 700;
			color: #1e293b;
			margin: 0 0 4px;
		}

		.renewal-header p {
			color: #6b7280;
			font-size: 0.9rem;
			margin: 0;
		}

		.renewal-card {
			background: #fff;
			border-radius: 12px;
			border: 1px solid #e5e7eb;
			box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
			overflow: hidden;
			margin-bottom: 20px;
		}

		.renewal-card-header {
			padding: 16px 24px;
			border-bottom: 1px solid #e5e7eb;
			font-size: 0.78rem;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 0.4px;
			color: #6b7280;
			display: flex;
			align-items: center;
			gap: 8px;
		}

		.renewal-price-body {
			padding: 32px 24px;
			text-align: center;
		}

		.renewal-price {
			font-size: 2.75rem;
			font-weight: 800;
			color: #c41e3a;
			line-height: 1;
			margin-bottom: 12px;
		}

		.renewal-price sup {
			font-size: 1.1rem;
			font-weight: 700;
			top: -1.4rem;
			margin-right: 2px;
		}

		.renewal-period {
			display: inline-flex;
			align-items: center;
			gap: 8px;
			font-size: 0.85rem;
			color: #4b5563;
			background: #f9fafb;
			border: 1px solid #e5e7eb;
			border-radius: 999px;
			padding: 6px 16px;
		}

		.renewal-period strong {
			color: #1e293b;
		}

		.renewal-methods-body {
			padding: 24px;
		}

		.renewal-method-list {
			display: flex;
			flex-direction: column;
			gap: 10px;
		}

		.renewal-method-btn {
			display: flex;
			align-items: center;
			gap: 14px;
			width: 100%;
			padding: 14px 18px;
			background: #fff;
			border: 1px solid #e5e7eb;
			border-radius: 10px;
			cursor: pointer;
			text-align: left;
			transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
			font-size: 0.92rem;
			font-weight: 600;
			color: #1e293b;
		}

		.renewal-method-btn:hover {
			border-color: #c41e3a;
			background: #fff7f7;
			box-shadow: 0 1px 4px rgba(196, 30, 58, 0.12);
		}

		.renewal-method-icon {
			flex-shrink: 0;
			width: 38px;
			height: 38px;
			border-radius: 8px;
			background: #f3f4f6;
			display: flex;
			align-items: center;
			justify-content: center;
			color: #c41e3a;
		}

		.renewal-method-text small {
			display: block;
			font-weight: 400;
			font-size: 0.75rem;
			color: #9ca3af;
			margin-top: 1px;
		}

		.renewal-method-arrow {
			margin-left: auto;
			color: #d1d5db;
		}

		.renewal-error-body {
			padding: 28px 24px;
			text-align: center;
			color: #6b7280;
			font-size: 0.9rem;
		}
	</style>
@endsection

@section('content')
	<div class="renewal-wrap">
		<div class="renewal-header">
			<h2>Pembaharuan Langganan</h2>
			<p>Perbaharui langganan syarikat anda untuk terus mengakses tender/sebut harga.</p>
		</div>

		<div class="renewal-card">
			<div class="renewal-card-header">
				<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
					stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M4 4h16v16H4z" opacity="0" />
					<rect x="3" y="5" width="18" height="14" rx="2" />
					<path d="M3 10h18" />
				</svg>
				Ringkasan Langganan
			</div>
			<div class="renewal-price-body">
				<div class="renewal-price"><sup>RM</sup>100</div>
				<div class="renewal-period">
					<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<rect x="4" y="5" width="16" height="16" rx="2" />
						<path d="M8 3v4M16 3v4M4 11h16" />
					</svg>
					Akses 1 tahun &middot; <strong>{{ $start_date->format('d/m/Y') }}</strong> hingga
					<strong>{{ $end_date->format('d/m/Y') }}</strong>
				</div>
			</div>
		</div>

		<div class="renewal-card">
			<div class="renewal-card-header">
				<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
					stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<rect x="2" y="5" width="20" height="14" rx="2" />
					<path d="M2 10h20" />
				</svg>
				Pilih Kaedah Pembayaran
			</div>

			@if ($fpx || $ebpg)
				{!! Former::open(action('HomeController@storeRenewal'))->class('disabled-submit') !!}
				<div class="renewal-methods-body">
					<input type="hidden" name="method">
					<div class="renewal-method-list">
						@if ($ebpg)
							<button type="button" name="method" id="method-cc" value="ebpg" class="renewal-method-btn">
								<span class="renewal-method-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none"
										stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<rect x="2" y="5" width="20" height="14" rx="2" />
										<path d="M2 10h20" />
									</svg>
								</span>
								<span class="renewal-method-text">
									Kad Kredit
									<small>Bayar terus dengan kad kredit/debit</small>
								</span>
								<span class="renewal-method-arrow">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
										stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M9 6l6 6-6 6" />
									</svg>
								</span>
							</button>
						@endif
						@if ($fpx)
							<button type="button" class="renewal-method-btn method-ob" data-value="fpx-1">
								<span class="renewal-method-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none"
										stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<rect x="3" y="4" width="18" height="16" rx="2" />
										<path d="M3 9h18M8 4v16" />
									</svg>
								</span>
								<span class="renewal-method-text">
									Internet Banking (FPX) &mdash; Perbankan Peribadi
									<small>Bayar terus melalui akaun bank peribadi</small>
								</span>
								<span class="renewal-method-arrow">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
										stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M9 6l6 6-6 6" />
									</svg>
								</span>
							</button>
							@unless ($fpx->private_key == 'b2c')
								<button type="button" class="renewal-method-btn method-ob" data-value="fpx-2">
									<span class="renewal-method-icon">
										<svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none"
											stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path d="M3 21V9l9-6 9 6v12" />
											<path d="M9 21v-6h6v6" />
										</svg>
									</span>
									<span class="renewal-method-text">
										Internet Banking (FPX) &mdash; Perbankan Korporat
										<small>Bayar terus melalui akaun bank korporat/syarikat</small>
									</span>
									<span class="renewal-method-arrow">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
											stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path d="M9 6l6 6-6 6" />
										</svg>
									</span>
								</button>
							@endunless
						@endif
					</div>
				</div>
				{!! Former::close() !!}
			@else
				<div class="renewal-error-body">
					Pembayaran tidak dapat dilakukan pada masa ini kerana masalah teknikal. Sila cuba sebentar lagi
					atau hubungi kami.
				</div>
			@endif
		</div>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		$('.method-ob').click(function() {
			method = $(this).data('value');
			$('input[name=method]').val(method);
			$(this).parents('form').submit();
		});
		$("#method-cc").click(function() {
			$('input[name="method"]').val('ebpg');
			$(this).parents('form').submit();
		})
		$(".selectize").selectize();
	</script>
@endsection
