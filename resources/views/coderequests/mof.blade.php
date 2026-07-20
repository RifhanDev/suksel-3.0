@extends(Auth::user()->hasRole('Vendor') ? 'layouts.modernLanding' : 'layouts.v3.master')

@section('styles')
<style>
    .card-sidebar-compact {
        background: linear-gradient(160deg, var(--sg-red) 0%, var(--sg-red-dark) 100%);
        color: white;
        border-radius: 12px;
        padding: 1.5rem;
        position: sticky;
        top: 120px; /* Stays visible while scrolling */
        box-shadow: 0 4px 10px rgba(196, 30, 58, 0.15);
        overflow: hidden;
    }
    
    /* Geometric Accent */
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
    <h4 class="fw-bold text-dark m-0">Permintaan Kemaskini MOF</h4>
</div>

<form action="{{ route('vendor.requests.store', [$vendor->id, 'type' => $type]) }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        
        <!-- LEFT: COMPACT SIDEBAR -->
        <div class="col-lg-3">
            <div class="card-sidebar-compact">
                <div class="mb-3 d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    </div>
                    <div class="fw-bold text-white small text-uppercase" style="letter-spacing: 1px;">Maklumat Semasa</div>
                </div>

                <div class="mb-3">
                    <label class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem;">Nama Syarikat</label>
                    <div class="fw-bold text-white fs-6 lh-sm">{{ $vendor->name }}</div>
                </div>

                <div class="mb-3">
                    <label class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem;">No. Pendaftaran</label>
                    <div class="fw-bold fs-6 text-white font-monospace">{{ $vendor->registration }}</div>
                </div>
                
                <hr class="border-white opacity-25 my-3">
                <p class="text-white small mb-0 opacity-75" style="font-size: 0.75rem; line-height: 1.4;">
                    <i class="ti ti-info-circle me-1"></i> Sila pastikan maklumat sijil MOF yang dimasukkan adalah tepat.
                </p>
            </div>
        </div>

        <!-- RIGHT: FORM CARD -->
        <div class="col-lg-9">
            <div class="card-form-compact">
                
                <div class="card-form-header">
                    <h6 class="fw-bold text-dark m-0 d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        Butiran & Dokumen
                    </h6>
                </div>

                <div class="card-form-body">
                    <div class="row g-3">
                        
                        <!-- Row 1 -->
                        <div class="col-md-6">
                            <label for="mof_ref_no" class="form-label">No Rujukan Pendaftaran MOF</label>
                            <input type="text" class="form-control" id="mof_ref_no" name="mof_ref_no" value="{{ $vendor->mof_ref_no }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tarikh Aktif</label>
                            <div class="input-group input-group-md">
                                <input type="text" class="form-control" id="mof_start_date" name="mof_start_date" 
                                       value="{{ Carbon\Carbon::parse($vendor->mof_start_date)->format('j M Y') }}" placeholder="Mula">
                                <span class="input-group-text px-2 small">hingga</span>
                                <input type="text" class="form-control" id="mof_end_date" name="mof_end_date" 
                                       value="{{ Carbon\Carbon::parse($vendor->mof_end_date)->format('j M Y') }}" placeholder="Tamat">
                            </div>
                        </div>

                        <!-- Checkbox -->
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="mof_bumi" name="mof_bumi" value="1" {{ $vendor->mof_bumi ? 'checked' : '' }}>
                                <label class="form-check-label text-dark fw-bold small" for="mof_bumi">
                                    Status Syarikat Bumiputera
                                </label>
                            </div>
                        </div>

                        <!-- MOF Codes -->
                        <div class="col-12">
                            <label for="mof_codes" class="form-label">Kod Bidang</label>
                            <select id="mof_codes" name="mof_codes[]" multiple placeholder="Cari kod bidang...">
                                @foreach(App\Code::where('type', 'mof')->get() as $code)
                                    <option value="{{ $code->id }}" {{ $vendor->mof_codes->contains('code_id', $code->id) ? 'selected' : '' }}>
                                        {{ $code->label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12"><hr class="text-muted opacity-25 my-1"></div>

                        <!-- Files -->
                        <div class="col-md-6">
                            <label for="sijil_mof" class="form-label">Sijil MOF <span class="text-danger">*</span></label>
                            <input class="form-control form-control-sm" type="file" id="sijil_mof" name="sijil_mof" accept="application/pdf" required>
                            <div class="form-text" style="font-size: 0.7rem;">Satu fail PDF sahaja.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="sijil_mof_bumiputera" class="form-label">Sijil Bumiputera</label>
                            <input class="form-control form-control-sm" type="file" id="sijil_mof_bumiputera" name="sijil_mof_bumiputera" accept="application/pdf">
                            <div class="form-text" style="font-size: 0.7rem;">PDF sahaja (Jika ada).</div>
                        </div>

                    </div>
                </div>

                <!-- FOOTER ACTIONS -->
                <div class="d-flex justify-content-between align-items-center p-3 border-top bg-light rounded-bottom">
                    
                    <div class="d-flex gap-2">
                            
                        <!-- Back to List -->
                        <a href="{{ route('vendor.requests.index', $vendor->id) }}" class="btn btn-danger border-danger text-white fw-medium d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            Senarai Permintaan
                        </a>

                        <!-- View Profile -->
                        <a href="{{ route(Auth::user()->hasRole('Vendor') ? 'vendor' : 'vendors.show', $vendor->id) }}" class="btn btn-danger border-danger text-white fw-medium d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l8-4 8 4v14"/><path d="M17 21v-8H7v8"/></svg>
                            Maklumat Syarikat
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

	{{-- <script src="{{ asset('js/request.js') }}"></script> --}}
	<script type="text/javascript">
		// function selectize_select(id) {
		// 	$(id).find('select.selectize').each(function() {
		// 		if (!this.selectize) $(this).selectize();
		// 	});
		// }
		$(document).ready(function() {
			// Initialize selectize for MOF codes
			$('#mof_codes').selectize({
				plugins: ['remove_button'],
				delimiter: ',',
				persist: false
			});

			// Initialize datepicker for date fields
			$("#mof_start_date, #mof_end_date").datepicker({
				format: 'd M yyyy',
				autoclose: true
			});
		});
	</script>
@endsection
