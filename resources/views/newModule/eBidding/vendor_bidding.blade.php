@extends('layouts.v3.master')

@section('content')
	<div class="content-card p-4">
		@if (session('success'))
			<div class="alert alert-success py-2 px-3 mb-3">{{ session('success') }}</div>
		@endif
		<div id="vendor-bid-alert" class="alert d-none py-2 px-3 mb-3"></div>
		<div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
			<p class="text-muted small m-0">
				Isi <strong>Harga Bidaan</strong> (harga baharu) pada setiap <strong>item anak</strong> sahaja.
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
				<input type="text" class="form-control form-control-sm" value="{{ $jadualBidaan->masa_bidaan_mula }}" readonly>
			</div>
			<div class="col-md-3">
				<label class="form-label small">Tarikh Tamat Bidaan</label>
				<input type="text" class="form-control form-control-sm"
					value="{{ optional($jadualBidaan->tarikh_bidaan_tamat)->format('d/m/Y') }}" readonly>
			</div>
			<div class="col-md-3">
				<label class="form-label small">Masa Tamat Bidaan</label>
				<input type="text" class="form-control form-control-sm" value="{{ $jadualBidaan->masa_bidaan_tamat }}" readonly>
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
									<input type="number" class="form-control form-control-sm vendor-bid-price"
										data-item-id="{{ $row['pemilihan_item_id'] }}" data-group="{{ $groupKey }}" min="0.01" step="0.01"
										required placeholder="0.00" value="{{ $row['bid_price'] }}" {{ $canVendorEditBid ? '' : 'readonly' }}>
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
									<span class="overall-price-group" data-group="{{ $groupKey }}">0.00</span>
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

			// Recalculate on every keypress / input in child Harga Bidaan fields.
			$(document).on('input keyup change', '.vendor-bid-price', recalcOverallPrices);
			recalcOverallPrices();

			$('#vendor-bid-submit').on('click', function() {
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

				$('.vendor-bid-price').each(function() {
					const itemId = parseInt($(this).data('item-id'), 10);
					const raw = ($(this).val() || '').toString().trim();
					const price = raw === '' ? null : parseFloat(raw);
					if (raw === '' || price === null || Number.isNaN(price) || price <= 0) {
						missing = true;
						$(this).addClass('is-invalid');
					} else {
						$(this).removeClass('is-invalid');
					}
					if (raw !== '' && (Number.isNaN(price) || price <= 0)) hasInvalid = true;
					items.push({
						pemilihan_item_id: itemId,
						bid_price: price
					});
				});

				if (missing || hasInvalid) {
					showAlert(
						'Sila isi Harga Bidaan (harga baharu) bagi setiap item anak. Nilai mesti melebihi 0.',
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
					}
				});
			});
		});
	</script>
@endsection
