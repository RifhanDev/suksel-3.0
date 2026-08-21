<!-- CATATAN & DOKUMEN CARD is unchanged; this section is spec-tab only. -->
<div class="content-card mb-4 p-0">
    <div class="content-card-header p-4 pb-3 border-bottom">
        <div class="d-flex align-items-center gap-3">
            <div class="content-card-icon" style="width: 38px; height: 38px;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>
            <div>
                <h3 class="content-card-title mb-0" style="font-size: 1rem;">Perincian Mesyuarat</h3>
                <p class="text-muted mb-0" style="font-size: 0.78rem;">Tarikh, masa dan lokasi mesyuarat Jawatankuasa Spesifikasi</p>
            </div>
        </div>
    </div>
    <div class="content-card-body p-4">
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <label class="form-label small fw-semibold text-uppercase text-muted mb-1" style="font-size: 0.7rem; letter-spacing: 0.6px;">Tarikh</label>
                <input type="date" class="form-control committee-tarikh-mesyuarat"
                    min="{{ now()->format('Y-m-d') }}"
                    value="{{ $committeeDrafts['spec']['tarikh_mesyuarat'] ?? '' }}">
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label small fw-semibold text-uppercase text-muted mb-1" style="font-size: 0.7rem; letter-spacing: 0.6px;">Masa</label>
                <input type="time" class="form-control committee-masa-mesyuarat"
                    value="{{ $committeeDrafts['spec']['masa_mesyuarat'] ?? '' }}">
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label small fw-semibold text-uppercase text-muted mb-1" style="font-size: 0.7rem; letter-spacing: 0.6px;">Lokasi</label>
                <input type="text" class="form-control committee-lokasi-mesyuarat"
                    placeholder="Cth: Bilik Mesyuarat Melur, Tingkat 2"
                    value="{{ $committeeDrafts['spec']['lokasi_mesyuarat'] ?? '' }}">
            </div>
        </div>
    </div>
</div>
