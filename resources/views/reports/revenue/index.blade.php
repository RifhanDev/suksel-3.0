@extends('layouts.v3.master')

@section('content')

	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div>
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Laporan Hasil Transaksi Tahunan</h3>
			<p class="text-muted small m-0">Jana laporan transaksi sistem tender online mengikut tahun.</p>
		</div>
	</div>

	<div class="content-card p-4">
		<form action="{{ url('reports/revenue') }}" method="POST" target="_blank">
			@csrf

			<div class="mb-3">
				<label for="year_start" class="form-label fw-semibold">Mulai Tahun</label>
				<select name="year_start" id="year_start" class="form-select" required>
					<option value="">Pilih tahun laporan yang ingin dihasilkan...</option>
					@foreach ($select_year as $value => $label)
						<option value="{{ $value }}">{{ $label }}</option>
					@endforeach
				</select>
			</div>

			<div class="mb-3">
				<label for="year_end" class="form-label fw-semibold">Hingga Tahun</label>
				<select name="year_end" id="year_end" class="form-select" required>
					<option value="">Pilih tahun laporan yang ingin dihasilkan...</option>
					@foreach ($select_year as $value => $label)
						<option value="{{ $value }}">{{ $label }}</option>
					@endforeach
				</select>
			</div>

			<div class="mb-4">
				<label class="form-label fw-semibold">Maklumat Dikehendaki</label>
				<div class="d-flex flex-column gap-2 mt-1">
					<div class="form-check">
						<input class="form-check-input" type="checkbox" name="tender" value="1" id="chk_tender">
						<label class="form-check-label" for="chk_tender">Transaksi Tender</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" name="quotation" value="1" id="chk_quotation">
						<label class="form-check-label" for="chk_quotation">Transaksi Sebut Harga</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" name="transaction" value="1" id="chk_transaction">
						<label class="form-check-label" for="chk_transaction">Kesemua Transaksi</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" name="registration" value="1" id="chk_registration">
						<label class="form-check-label" for="chk_registration">Langganan Kontraktor</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" name="renewal" value="1" id="chk_renewal">
						<label class="form-check-label" for="chk_renewal">Pembaharuan Langganan Kontraktor</label>
					</div>
				</div>
			</div>

			<div>
				<button type="submit" class="btn-form btn-form-primary">Jana Laporan</button>
			</div>
		</form>
	</div>

@endsection

@section('scripts')
	<script type="text/javascript">
		$("input[name=tender]").change(function() {
			if (this.checked) {
				if ($("input[name=quotation]").is(':checked')) {
					$("input[name=transaction]").prop('checked', true);
				}
			}
		});

		$("input[name=quotation]").change(function() {
			if (this.checked) {
				if ($("input[name=tender]").is(':checked')) {
					$("input[name=transaction]").prop('checked', true);
				}
			}
		});

		$("input[name=transaction]").change(function() {
			if (this.checked) {
				$("input[name=tender]").prop('checked', true);
				$("input[name=quotation]").prop('checked', true);
			}
		});
	</script>
@endsection
