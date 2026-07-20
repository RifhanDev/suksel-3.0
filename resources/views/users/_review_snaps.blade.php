@if ($user->ability(['Admin', 'Registration Assessor'], []))
    <div class="row g-4 mb-4">
        <div class="col-xl-6 col-lg-6 col-md-12">
            <div class="stats-card h-100 d-flex flex-column justify-content-center">
                <div class="d-flex align-items-center">
                    <div class="stats-card-icon stats-icon-warning me-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <div>
                        <h6 class="stats-card-title mb-1 text-nowrap">Semak Akaun Belum Selesai</h6>
                        <h2 class="stats-card-value text-warning" style="font-size: 1.5rem;">
                            {{ number_format(App\User::pendingReviewCount(), 0) }}</h2>
                        <small class="text-warning fw-bold text-nowrap">Menunggu Semakan</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-12">
            <div class="stats-card h-100 d-flex flex-column justify-content-center">
                <div class="d-flex align-items-center">
                    <div class="stats-card-icon stats-icon-success me-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <div>
                        <h6 class="stats-card-title mb-1 text-nowrap">Semak Akaun Selesai</h6>
                        <h2 class="stats-card-value text-success" style="font-size: 1.5rem;">
                            {{ number_format(App\User::reviewedCount(), 0) }}</h2>
                        <small class="text-success fw-bold text-nowrap">Selesai Disemak</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
