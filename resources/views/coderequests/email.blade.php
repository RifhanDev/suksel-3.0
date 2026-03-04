@extends(Auth::user()->hasRole('Vendor') ? 'layouts.modernLanding' : 'layouts.v3.master')

@section('styles')
<style>
    /* Branding */
    .btn-selangor {
        background-color: var(--sg-red);
        border-color: var(--sg-red);
        color: white;
    }
    .btn-selangor:hover {
        background-color: var(--sg-red-dark);
        border-color: var(--sg-red-dark);
        color: white;
    }

    /* COMPACT SIDEBAR (Left Panel) */
    .card-sidebar-compact {
        background: linear-gradient(160deg, var(--sg-red) 0%, var(--sg-red-dark) 100%);
        color: white;
        border-radius: 12px;
        padding: 1.5rem;
        position: sticky;
        top: 120px;
        box-shadow: 0 4px 10px rgba(196, 30, 58, 0.15);
        overflow: hidden;
    }
    .card-sidebar-compact::after {
        content: ''; position: absolute; bottom: -20px; right: -20px; width: 100px; height: 100px;
        background: var(--sg-yellow); opacity: 0.15; border-radius: 50%; pointer-events: none;
    }

    /* Form Card */
    .card-form-compact {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        overflow: hidden;
    }
    .card-form-header {
        padding: 15px 20px;
        border-bottom: 1px solid #f1f5f9;
        background: #fff;
    }
    .card-form-body {
        padding: 20px;
    }
</style>
@endsection

@section('content')

<div class="mb-4">
    <h4 class="fw-bold text-dark m-0">Permintaan Kemaskini Alamat Emel</h4>
</div>

<form action="{{ route('vendor.requests.store', [$vendor->id, 'type' => $type]) }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        
        <!-- LEFT: SIDEBAR -->
        <div class="col-lg-3">
            <div class="card-sidebar-compact">
                <div class="mb-3 d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </div>
                    <div class="fw-bold text-white small text-uppercase" style="letter-spacing: 1px;">Maklumat Semasa</div>
                </div>

                <div class="mb-3">
                    <label class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem;">Nama Syarikat</label>
                    <div class="fw-bold text-white fs-6 lh-sm">{{ $vendor->name }}</div>
                </div>

                <div class="mb-3">
                    <label class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem;">Emel Semasa</label>
                    <div class="fw-bold fs-6 text-white">{{ $vendor->user->email }}</div>
                </div>
                
                <hr class="border-white opacity-25 my-3">
                <p class="text-white small mb-0 opacity-75" style="font-size: 0.75rem; line-height: 1.4;">
                    <i class="ti ti-info-circle me-1"></i> Surat kebenaran diperlukan untuk menukar alamat emel rasmi.
                </p>
            </div>
        </div>

        <!-- RIGHT: FORM CARD -->
        <div class="col-lg-9">
            <div class="card-form-compact">
                
                <div class="card-form-header">
                    <h6 class="fw-bold text-dark m-0 d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        Butiran Permohonan
                    </h6>
                </div>

                <div class="card-form-body">
                    <div class="row g-3">
                        
                        <!-- Email Input -->
                        <div class="col-12">
                            <label for="email" class="form-label">Alamat Emel Baru <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="col-12"><hr class="text-muted opacity-25 my-1"></div>

                        <!-- File Upload -->
                        <div class="col-12">
                            <label for="sijil_auth" class="form-label">Surat Kebenaran <span class="text-danger">*</span></label>
                            <input class="form-control" type="file" id="sijil_auth" name="sijil_auth" accept="application/pdf" required>
                            <div class="form-text" style="font-size: 0.7rem;">
                                <i class="ti ti-file me-1"></i> Muat naik surat kebenaran rasmi syarikat (PDF).
                            </div>
                        </div>

                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3 border-top bg-light rounded-bottom">
                    
                    <!-- LEFT: NAV LINKS -->
                    <div class="d-flex gap-3">
                        <a href="{{ route('vendor.requests.index', $vendor->id) }}" class="btn btn-danger border-danger text-white fw-medium d-flex align-items-center gap-2">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            <span class="fw-bold">Senarai Permintaan</span>
                        </a>

                        <a href="{{ route(Auth::user()->hasRole('Vendor') ? 'vendor' : 'vendors.show', $vendor->id) }}" class="btn btn-danger border-danger text-white fw-medium d-flex align-items-center gap-2">
                            <span class="fw-bold">Maklumat Syarikat</span>
                        </a>
                    </div>

                    <!-- RIGHT: SUBMIT -->
                    <button type="submit" class="btn btn-success d-flex align-items-center gap-2 fw-bold px-4 py-2 btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        Hantar
                    </button>
                </div>

            </div>
        </div>
    </div>

</form>

@endsection

@section('scripts')
	<script type="text/javascript">
		function selectize_select(id) {
			$(id).find('select.selectize').each(function() {
				if (!this.selectize) $(this).selectize();
			});
		}
	</script>
@endsection