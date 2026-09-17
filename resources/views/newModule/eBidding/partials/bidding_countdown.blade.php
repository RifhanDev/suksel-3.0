@php
	$endsAtIso = $endsAtIso ?? ($window['ends_at'] ?? null);
	$hasEndedWindow = $hasEndedWindow ?? !empty($window['has_ended']);
	$countdownId = $countdownId ?? 'eb-bid-countdown';
	$wrapExtraClass = $wrapExtraClass ?? 'mb-3';
@endphp
@if ($endsAtIso)
	<div id="{{ $countdownId }}-wrap"
		class="eb-bid-countdown-wrap d-flex flex-wrap align-items-center justify-content-between gap-3 px-3 py-2 border rounded {{ $wrapExtraClass }} {{ $hasEndedWindow ? 'border-danger' : 'border-primary' }}"
		style="{{ $hasEndedWindow ? 'background:#fff5f5;' : 'background:#f0f4ff;' }}">
		<div>
			<div class="small text-muted mb-0">Baki masa sehingga bidaan tamat</div>
			<div id="{{ $countdownId }}-label" class="fw-semibold eb-bid-countdown-label {{ $hasEndedWindow ? 'text-danger' : '' }}">
				{{ $hasEndedWindow ? 'Bidaan telah tamat' : '—' }}
			</div>
		</div>
		<div id="{{ $countdownId }}"
			class="eb-bid-countdown d-flex gap-2 text-center"
			data-ends-at="{{ $endsAtIso }}"
			aria-live="polite">
			<div class="px-2 py-1 rounded bg-white border" style="min-width:3.25rem;">
				<div class="fs-5 fw-bold lh-1" data-unit="days">0</div>
				<div class="small text-muted">hari</div>
			</div>
			<div class="px-2 py-1 rounded bg-white border" style="min-width:3.25rem;">
				<div class="fs-5 fw-bold lh-1" data-unit="hours">00</div>
				<div class="small text-muted">jam</div>
			</div>
			<div class="px-2 py-1 rounded bg-white border" style="min-width:3.25rem;">
				<div class="fs-5 fw-bold lh-1" data-unit="minutes">00</div>
				<div class="small text-muted">min</div>
			</div>
			<div class="px-2 py-1 rounded bg-white border" style="min-width:3.25rem;">
				<div class="fs-5 fw-bold lh-1" data-unit="seconds">00</div>
				<div class="small text-muted">saat</div>
			</div>
		</div>
	</div>
@endif
