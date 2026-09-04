@php
	$jadualSimpanUrl = route('perakuanjabatan.jadualBidaan.simpan', ['tender' => $tender->id]);
	$jadualMulaUrl = route('perakuanjabatan.jadualBidaan.mula', ['tender' => $tender->id]);
	$jadualReadOnly = $jadualReadOnly ?? false;
	$pjMode = $pjMode ?? 'jadual';
@endphp
<div class="tab-pane fade {{ ($pjMode ?? '') === 'jadual' ? 'show active' : '' }} jadual-bidaan-tab" id="tab-jadual-bidaan"
	role="tabpanel">
	<div class="content-card p-4" id="pj-jadual-root">
		<div class="alert d-none py-2 px-3 mb-3" id="pjJadualAlert" role="alert"></div>

		@if ($jadualReadOnly)
			<div class="alert alert-info py-2 px-3 mb-3">
				Jadual bidaan telah dimulakan / tamat. Maklumat di bawah adalah untuk rujukan sahaja.
			</div>
		@endif

		<h6 class="fw-bold mb-3">Penyediaan Jadual Bidaan</h6>

		<div class="row mb-3 g-3">
			<div class="col-md-3">
				<label class="form-label">Tarikh Bidaan Mula<span class="text-danger">*</span></label>
				<input type="date" class="form-control" id="pj_jadual_tarikh_mula"
					value="{{ optional($jadualBidaan)->tarikh_bidaan_mula ? optional($jadualBidaan->tarikh_bidaan_mula)->format('Y-m-d') : '' }}"
					{{ $jadualReadOnly ? 'disabled' : '' }}>
			</div>
			<div class="col-md-3">
				<label class="form-label">Masa Bidaan Mula<span class="text-danger">*</span></label>
				<input type="time" class="form-control" id="pj_jadual_masa_mula"
					value="{{ optional($jadualBidaan)->masa_bidaan_mula }}"
					{{ $jadualReadOnly ? 'disabled' : '' }}>
			</div>
			<div class="col-md-3">
				<label class="form-label">Tarikh Bidaan Tamat<span class="text-danger">*</span></label>
				<input type="date" class="form-control" id="pj_jadual_tarikh_tamat"
					value="{{ optional($jadualBidaan)->tarikh_bidaan_tamat ? optional($jadualBidaan->tarikh_bidaan_tamat)->format('Y-m-d') : '' }}"
					{{ $jadualReadOnly ? 'disabled' : '' }}>
			</div>
			<div class="col-md-3">
				<label class="form-label">Masa Bidaan Tamat<span class="text-danger">*</span></label>
				<input type="time" class="form-control" id="pj_jadual_masa_tamat"
					value="{{ optional($jadualBidaan)->masa_bidaan_tamat }}"
					{{ $jadualReadOnly ? 'disabled' : '' }}>
			</div>
		</div>

		@unless ($jadualReadOnly)
			<div class="d-flex justify-content-end gap-2">
				<button class="btn btn-outline-secondary" type="button" id="pjJadualSimpan">Simpan</button>
				<button class="btn btn-selangor" type="button" id="pjJadualMula">Mula Bidaan</button>
			</div>
		@endunless
	</div>
</div>

@push('scripts')
	<script>
		(function() {
			const root = document.getElementById('pj-jadual-root');
			if (!root) return;

			const readOnly = @json((bool) $jadualReadOnly);
			if (readOnly) return;

			const simpanUrl = @json($jadualSimpanUrl);
			const mulaUrl = @json($jadualMulaUrl);
			const token = document.querySelector('meta[name="_token"]')?.getAttribute('content');
			const alertEl = document.getElementById('pjJadualAlert');

			function showAlert(msg, ok) {
				if (!alertEl) return;
				alertEl.textContent = msg;
				alertEl.classList.remove('d-none', 'alert-success', 'alert-danger');
				alertEl.classList.add(ok ? 'alert-success' : 'alert-danger');
			}

			function payload() {
				return {
					_token: token,
					tarikh_bidaan_mula: document.getElementById('pj_jadual_tarikh_mula')?.value || '',
					masa_bidaan_mula: document.getElementById('pj_jadual_masa_mula')?.value || '',
					tarikh_bidaan_tamat: document.getElementById('pj_jadual_tarikh_tamat')?.value || '',
					masa_bidaan_tamat: document.getElementById('pj_jadual_masa_tamat')?.value || '',
				};
			}

			function post(url) {
				fetch(url, {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
							'X-Requested-With': 'XMLHttpRequest',
							'Accept': 'application/json',
							'X-CSRF-TOKEN': token
						},
						body: JSON.stringify(payload())
					})
					.then(r => r.json().then(j => ({
						ok: r.ok,
						body: j
					})).catch(() => ({
						ok: r.ok,
						body: {}
					})))
					.then(res => {
						if (res.ok) {
							showAlert(res.body.message || 'Berjaya.', true);
							if (url === mulaUrl) {
								setTimeout(() => window.location.reload(), 900);
							}
						} else {
							let msg = res.body.message || 'Ralat.';
							if (res.body.errors) {
								msg = Object.values(res.body.errors).flat().join(' ');
							}
							showAlert(msg, false);
						}
					})
					.catch(() => showAlert('Ralat rangkaian.', false));
			}

			document.getElementById('pjJadualSimpan')?.addEventListener('click', () => post(simpanUrl));
			document.getElementById('pjJadualMula')?.addEventListener('click', () => post(mulaUrl));
		})();
	</script>
@endpush
