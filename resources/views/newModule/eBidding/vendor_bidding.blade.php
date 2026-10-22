@extends('layouts.v3.master')

@section('content')
	<div class="content-card p-4">
		@if (session('success'))
			<div class="alert alert-success py-2 px-3 mb-3">{{ session('success') }}</div>
		@endif
		<div id="vendor-bid-alert" class="alert d-none py-2 px-3 mb-3"></div>
		<div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
			<p class="text-muted small m-0">
				Isi <strong>Harga Bidaan</strong> (harga baharu) pada setiap item.
				Harga baharu mesti lebih daripada 0 dan <strong>tidak boleh melebihi Harga Sebelum Bidaan</strong>.
				<strong>Jumlah Keseluruhan</strong> dikira automatik semasa menaip dan tidak boleh diedit.
			</p>
			<span class="badge {{ !empty($hasVendorSubmitted) ? 'bg-success' : 'bg-warning text-dark' }}">
				{{ !empty($hasVendorSubmitted) ? 'Submitted' : 'Pending Submission' }}
			</span>
		</div>

		@include('newModule.eBidding.partials.bidding_countdown', [
			'window' => $window ?? [],
			'countdownId' => 'vendor-bid-countdown',
		])

		<div class="row g-3 mb-4">
			<div class="col-md-3">
				<label class="form-label small">Tarikh Mula Bidaan</label>
				<input type="text" class="form-control form-control-sm"
					value="{{ optional($jadualBidaan->tarikh_bidaan_mula)->format('d/m/Y') }}" readonly>
			</div>
			<div class="col-md-3">
				<label class="form-label small">Masa Mula Bidaan</label>
				<input type="text" class="form-control form-control-sm" value="{{ $jadualBidaan->timeForInput('masa_bidaan_mula') }}" readonly>
			</div>
			<div class="col-md-3">
				<label class="form-label small">Tarikh Tamat Bidaan</label>
				<input type="text" class="form-control form-control-sm"
					value="{{ optional($jadualBidaan->tarikh_bidaan_tamat)->format('d/m/Y') }}" readonly>
			</div>
			<div class="col-md-3">
				<label class="form-label small">Masa Tamat Bidaan</label>
				<input type="text" class="form-control form-control-sm" value="{{ $jadualBidaan->timeForInput('masa_bidaan_tamat') }}" readonly>
			</div>
		</div>

		<div class="table-responsive">
			<table class="table table-bordered align-middle" id="vendor-bid-table">
				<thead class="text-white text-center" style="background-color:#2d3e84;">
					<tr>
						<th>Item Spesifikasi</th>
						<th width="110">Kuantiti</th>
						<th width="110">Unit Ukuran</th>
						<th width="100">Pematuhan</th>
						<th width="140">Harga Sebelum Bidaan</th>
						<th width="160">Harga Bidaan (Baharu)</th>
						<th width="150">Jumlah Keseluruhan (RM)</th>
					</tr>
				</thead>
				<tbody>
					@forelse ($vendorItems as $row)
						@php
							$isParent = ($row['row_type'] ?? '') === 'parent';
							$isBidable = !empty($row['is_bidable']);
							$pad = ((int) ($row['indent'] ?? 0)) > 0 ? 'ps-4' : '';
							$groupKey = $row['group_key'] ?? '';
						@endphp
						<tr class="{{ $isParent ? 'table-light' : '' }}" data-row-type="{{ $row['row_type'] ?? 'leaf' }}"
							data-group="{{ $groupKey }}">
							<td class="{{ $pad }} {{ $isParent ? 'fw-semibold' : '' }}">
								@if (!$isParent && ((int) ($row['indent'] ?? 0)) > 0)
									<span class="text-muted me-1">↳</span>
								@endif
								{{ $row['spesifikasi'] }}
							</td>
							<td class="text-center">{{ $row['kuantiti'] !== '' ? $row['kuantiti'] : '—' }}</td>
							<td class="text-center">{{ $row['unit_ukuran'] }}</td>
							<td class="text-center">{{ $row['pematuhan'] }}</td>
							<td class="text-center">
								@if ($isBidable && $row['previous_price'] !== '')
									{{ number_format((float) $row['previous_price'], 2) }}
								@else
									—
								@endif
							</td>
							<td>
								@if ($isBidable)
									@php
										$prevMax = $row['previous_price'] !== '' ? (float) $row['previous_price'] : null;
									@endphp
									<input type="text" inputmode="decimal" autocomplete="off"
										class="form-control form-control-sm vendor-bid-price text-end"
										data-item-id="{{ $row['pemilihan_item_id'] }}" data-group="{{ $groupKey }}"
										data-max-price="{{ $prevMax !== null && $prevMax > 0 ? number_format($prevMax, 2, '.', '') : '' }}"
										placeholder="0.00" value="{{ $row['bid_price'] }}"
										{{ $canVendorEditBid ? '' : 'readonly' }}>
								@else
									<div class="text-center text-muted small">—</div>
								@endif
							</td>
							<td class="text-end fw-semibold">
								@if ($isParent)
									<span class="overall-price-group" data-group="{{ $groupKey }}">0.00</span>
								@elseif ($isBidable)
									<span class="text-muted small">—</span>
								@else
									—
								@endif
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="7" class="text-center text-muted">Tiada item bidaan.</td>
						</tr>
					@endforelse
				</tbody>
				<tfoot>
					<tr class="table-light">
						<td colspan="6" class="text-end fw-bold">Jumlah Keseluruhan (RM)</td>
						<td class="text-end fw-bold">
							<span id="vendor-bid-total">0.00</span>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>

		<div class="d-flex justify-content-end mt-3">
			<button type="button" class="btn btn-selangor" id="vendor-bid-submit" {{ $canVendorEditBid ? '' : 'disabled' }}>
				Hantar
			</button>
		</div>
	</div>
@endsection

@section('scripts')
	@include('newModule.eBidding.partials.bidding_countdown_script')
	<script type="text/javascript">
		$(document).ready(function() {
			let canEdit = @json($canVendorEditBid);
			const hasVendorSubmitted = @json(!empty($hasVendorSubmitted));
			if (canEdit && !hasVendorSubmitted) {
				$('.vendor-bid-price').prop('readonly', false);
			}
			const submitUrl = @json(route('eBidding.vendorBidaan.hantar', ['id' => $tender->id]));
			const csrfToken = $('meta[name="csrf-token"]').attr('content') || $('meta[name="_token"]').attr('content');
			const $alert = $('#vendor-bid-alert');

			function formatMoney(value) {
				return (Number(value) || 0).toLocaleString('en-MY', {
					minimumFractionDigits: 2,
					maximumFractionDigits: 2
				});
			}

			function showAlert(message, type) {
				$alert.removeClass('d-none alert-success alert-danger')
					.addClass(type === 'success' ? 'alert-success' : 'alert-danger')
					.text(message || '');
			}

			function lockBidFormEnded() {
				canEdit = false;
				$('#vendor-bid-submit').prop('disabled', true);
				$('.vendor-bid-price').prop('readonly', true);
				showAlert('Tempoh bidaan telah tamat. Harga baharu tidak lagi boleh dihantar.', 'error');
			}

			if (typeof window.initEbBidCountdowns === 'function') {
				window.initEbBidCountdowns({
					onEnded: function() {
						lockBidFormEnded();
						setTimeout(function() {
							window.location.reload();
						}, 1200);
					}
				});
			}

			function recalcOverallPrices() {
				const groupSums = {};
				let grandTotal = 0;

				$('.vendor-bid-price').each(function() {
					const group = ($(this).data('group') || '').toString();
					const raw = ($(this).val() || '').toString().trim();
					const price = raw === '' ? 0 : parseFloat(raw);
					const amount = Number.isNaN(price) ? 0 : price;
					grandTotal += amount;
					if (group) {
						groupSums[group] = (groupSums[group] || 0) + amount;
					}
				});

				$('.overall-price-group').each(function() {
					const group = ($(this).data('group') || '').toString();
					$(this).text(formatMoney(groupSums[group] || 0));
				});

				$('#vendor-bid-total').text(formatMoney(grandTotal));
			}

			function getMaxPrice($input) {
				const raw = ($input.attr('data-max-price') || $input.attr('max') || '').toString().trim();
				if (raw === '') {
					return null;
				}
				const max = parseFloat(raw);
				return Number.isNaN(max) || max <= 0 ? null : max;
			}

			function parseBidPrice(raw) {
				const cleaned = raw.toString().replace(/,/g, '').trim();
				if (cleaned === '' || !/^\d+(\.\d{0,2})?$/.test(cleaned)) {
					return null;
				}
				const price = parseFloat(cleaned);
				return Number.isNaN(price) ? null : price;
			}

			// Do not rewrite the value while typing. A number input with max was
			// snapping the field on each key and looked like it would not accept input.
			$(document).on('input', '.vendor-bid-price', function() {
				const $input = $(this);
				const raw = ($input.val() || '').toString();
				const cleaned = raw.replace(/[^\d.]/g, '');
				if (cleaned !== raw) {
					$input.val(cleaned);
				}
				const max = getMaxPrice($input);
				const price = parseBidPrice($input.val() || '');
				if (price !== null && max !== null && price > max) {
					$input.addClass('is-invalid');
				} else {
					$input.removeClass('is-invalid');
				}
				recalcOverallPrices();
			});
			$(document).on('blur', '.vendor-bid-price', function() {
				const $input = $(this);
				const max = getMaxPrice($input);
				const price = parseBidPrice($input.val() || '');
				if (price === null) {
					recalcOverallPrices();
					return;
				}
				if (max !== null && price > max) {
					$input.val(max.toFixed(2));
					$input.addClass('is-invalid');
					showAlert(
						'Harga Bidaan tidak boleh melebihi Harga Sebelum Bidaan (RM ' + formatMoney(max) + ').',
						'error'
					);
				} else {
					$input.val(price.toFixed(2));
					$input.removeClass('is-invalid');
				}
				recalcOverallPrices();
			});
			recalcOverallPrices();

			let isSubmitting = false;

			function setSubmitBusy(busy) {
				const $btn = $('#vendor-bid-submit');
				isSubmitting = busy;
				$btn.prop('disabled', busy || !canEdit || hasVendorSubmitted);
				$btn.text(busy ? 'Menghantar...' : 'Hantar');
			}

			$('#vendor-bid-submit').on('click', function() {
				if (isSubmitting) {
					return;
				}
				if (!canEdit) {
					showAlert('Tempoh bidaan belum dibuka atau sudah tamat.', 'error');
					return;
				}
				if (hasVendorSubmitted) {
					showAlert('Harga bidaan telah dihantar. Sila tunggu semakan Agency Admin.', 'error');
					return;
				}

				const items = [];
				let hasInvalid = false;
				let missing = false;
				let overMax = false;

				$('.vendor-bid-price').each(function() {
					const $input = $(this);
					const itemId = parseInt($input.attr('data-item-id'), 10);
					const raw = ($input.val() || '').toString().trim();
					const price = parseBidPrice(raw);
					const max = getMaxPrice($input);

					if (raw === '' || price === null || price <= 0) {
						missing = true;
						$input.addClass('is-invalid');
					} else if (max !== null && price > max) {
						overMax = true;
						$input.addClass('is-invalid');
					} else {
						$input.removeClass('is-invalid');
					}
					if (raw !== '' && (price === null || price <= 0)) hasInvalid = true;
					items.push({
						pemilihan_item_id: itemId,
						bid_price: price
					});
				});

				if (overMax) {
					showAlert(
						'Harga Bidaan tidak boleh melebihi Harga Sebelum Bidaan bagi setiap item.',
						'error');
					return;
				}

				if (missing || hasInvalid) {
					showAlert(
						'Sila isi Harga Bidaan (harga baharu) bagi setiap item anak. Nilai mesti melebihi 0 dan tidak melebihi harga sebelum bidaan.',
						'error');
					return;
				}

				if (!items.length) {
					showAlert('Tiada item anak untuk dihantar.', 'error');
					return;
				}

				if (!window.confirm('Anda pasti setuju untuk hantar harga bidaan bagi semua item anak ini?')) {
					return;
				}

				setSubmitBusy(true);

				$.ajax({
					url: submitUrl,
					method: 'POST',
					headers: {
						'X-CSRF-TOKEN': csrfToken || '',
						'Accept': 'application/json'
					},
					data: {
						items: items
					},
					success: function(resp) {
						showAlert(resp.message || 'Harga bidaan berjaya dihantar.', 'success');
						const nextUrl = resp?.redirect_url || @json(route('eBidding.show', ['id' => $tender->id]));
						setTimeout(function() {
							window.location.href = nextUrl;
						}, 900);
					},
					error: function(xhr) {
						let message = xhr?.responseJSON?.message ||
							'Operasi gagal. Sila cuba semula.';
						if (xhr?.responseJSON?.errors) {
							message = Object.values(xhr.responseJSON.errors).flat().join(' ');
						}
						showAlert(message, 'error');
						setSubmitBusy(false);
					}
				});
			});
		});
	</script>
@endsection
