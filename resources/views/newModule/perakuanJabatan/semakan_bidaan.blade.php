@php
	$semakanUrl = route('perakuanjabatan.semakanBidaan.hantar', ['tender' => $tender->id]);
	$laporanItem = collect($kertasItems ?? [])->firstWhere('slot_key', 'laporan_bidaan');
@endphp
<div class="tab-pane fade show active" id="tab-semakan-bidaan" role="tabpanel">
	<div class="content-card p-4" id="pj-semakan-root">
		<div class="alert d-none py-2 px-3 mb-3" id="pjSemakanAlert" role="alert"></div>

		<div class="alert alert-warning py-2 px-3 mb-3">
			Tempoh bidaan telah tamat. Sila muat naik <strong>Laporan Bidaan</strong> dan tandakan pengesahan sebelum menghantar.
			Tab Kertas Taklimat dan Pengesyoran Pembekal adalah untuk rujukan (read-only).
		</div>

		<h6 class="fw-bold mb-3">Laporan Bidaan</h6>
		<div class="mb-3" style="max-width:480px;">
			<label class="form-label">Muat Naik Laporan Bidaan <span class="text-danger">*</span></label>
			<input type="file" class="form-control" id="pjLaporanBidaanFile" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
			@if ($laporanItem && $laporanItem->files->isNotEmpty())
				<div class="mt-2 small">
					@foreach ($laporanItem->files as $f)
						<div class="mb-1">
							<a href="{{ route('perakuanjabatan.kertasTaklimat.download', $f) }}" class="text-primary">
								{{ $f->file_original_name }}
							</a>
						</div>
					@endforeach
				</div>
			@endif
		</div>

		<div class="form-check mb-4">
			<input class="form-check-input" type="checkbox" id="pjPengesahanBidaan" value="1"
				{{ !empty($pengesyoranPembekal->pengesahan_bidaan) ? 'checked' : '' }}>
			<label class="form-check-label" for="pjPengesahanBidaan">
				Saya mengesahkan Bidaan telah dijalankan dan laporan yang dimuat naik adalah tepat. <span class="text-danger">*</span>
			</label>
		</div>

		<div class="d-flex justify-content-end">
			<button type="button" class="btn btn-selangor" id="pjSemakanHantar">Hantar</button>
		</div>
	</div>
</div>

@push('scripts')
	<script>
		(function() {
			const root = document.getElementById('pj-semakan-root');
			if (!root) return;

			const url = @json($semakanUrl);
			const token = document.querySelector('meta[name="_token"]')?.getAttribute('content');
			const alertEl = document.getElementById('pjSemakanAlert');
			const hasExisting = @json($laporanItem && $laporanItem->files->isNotEmpty());

			function showAlert(msg, ok) {
				if (!alertEl) return;
				alertEl.textContent = msg;
				alertEl.classList.remove('d-none', 'alert-success', 'alert-danger');
				alertEl.classList.add(ok ? 'alert-success' : 'alert-danger');
			}

			document.getElementById('pjSemakanHantar')?.addEventListener('click', function() {
				const fileInput = document.getElementById('pjLaporanBidaanFile');
				const checked = document.getElementById('pjPengesahanBidaan')?.checked;
				if (!checked) {
					showAlert('Sila tandakan pengesahan bidaan.', false);
					return;
				}
				if (!hasExisting && (!fileInput || !fileInput.files || !fileInput.files.length)) {
					showAlert('Sila muat naik Laporan Bidaan.', false);
					return;
				}

				const fd = new FormData();
				fd.append('_token', token);
				fd.append('pengesahan_bidaan', '1');
				if (fileInput && fileInput.files && fileInput.files[0]) {
					fd.append('laporan_bidaan', fileInput.files[0]);
				}

				fetch(url, {
						method: 'POST',
						body: fd,
						headers: {
							'X-Requested-With': 'XMLHttpRequest',
							'Accept': 'application/json'
						}
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
							setTimeout(() => window.location.href = @json(route('perakuanjabatan.index')), 900);
						} else {
							let msg = res.body.message || 'Ralat.';
							if (res.body.errors) {
								msg = Object.values(res.body.errors).flat().join(' ');
							}
							showAlert(msg, false);
						}
					})
					.catch(() => showAlert('Ralat rangkaian.', false));
			});
		})();
	</script>
@endpush
