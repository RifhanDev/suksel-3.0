<script type="text/javascript">
	(function() {
		if (window.__ebBidCountdownInit) {
			return;
		}
		window.__ebBidCountdownInit = true;

		function pad2(n) {
			return String(Math.max(0, n)).padStart(2, '0');
		}

		function startOneCountdown($countdown, options) {
			const endsAtMs = Date.parse($countdown.data('ends-at'));
			if (Number.isNaN(endsAtMs)) {
				return;
			}

			const wrapId = $countdown.attr('id') + '-wrap';
			const labelId = $countdown.attr('id') + '-label';
			const $wrap = $('#' + wrapId);
			const $label = $('#' + labelId);
			let endedFired = false;

			function onEnded() {
				if (endedFired) {
					return;
				}
				endedFired = true;
				$countdown.find('[data-unit="days"]').text('0');
				$countdown.find('[data-unit="hours"]').text('00');
				$countdown.find('[data-unit="minutes"]').text('00');
				$countdown.find('[data-unit="seconds"]').text('00');
				$label.addClass('text-danger').text('Bidaan telah tamat');
				$wrap.removeClass('border-primary').addClass('border-danger').css('background', '#fff5f5');
				if (typeof options.onEnded === 'function') {
					options.onEnded();
				}
			}

			function tick() {
				const remaining = endsAtMs - Date.now();
				if (remaining <= 0) {
					onEnded();
					return;
				}

				const totalSec = Math.floor(remaining / 1000);
				const days = Math.floor(totalSec / 86400);
				const hours = Math.floor((totalSec % 86400) / 3600);
				const minutes = Math.floor((totalSec % 3600) / 60);
				const seconds = totalSec % 60;

				$countdown.find('[data-unit="days"]').text(String(days));
				$countdown.find('[data-unit="hours"]').text(pad2(hours));
				$countdown.find('[data-unit="minutes"]').text(pad2(minutes));
				$countdown.find('[data-unit="seconds"]').text(pad2(seconds));

				const label = days > 0
					? (days + ' hari ' + pad2(hours) + ':' + pad2(minutes) + ':' + pad2(seconds))
					: (pad2(hours) + ':' + pad2(minutes) + ':' + pad2(seconds));
				$label.text(label);

				if (remaining <= 15 * 60 * 1000) {
					$wrap.removeClass('border-primary').addClass('border-danger').css('background', '#fff5f5');
					$label.addClass('text-danger');
				}

				setTimeout(tick, 1000);
			}

			tick();
		}

		window.initEbBidCountdowns = function(options) {
			options = options || {};
			$('.eb-bid-countdown').each(function() {
				startOneCountdown($(this), options);
			});
		};
	})();
</script>
