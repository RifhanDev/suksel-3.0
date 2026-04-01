@extends(Auth::user()->hasRole('Vendor') ? 'layouts.modernLanding' : 'layouts.v3.master')

@section('styles')
<style>
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
    <h4 class="fw-bold text-dark m-0">Permintaan Kemaskini Alamat</h4>
</div>

<form action="{{ route('vendor.requests.store', [$vendor->id, 'type' => $type]) }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        
        <!-- LEFT: SIDEBAR -->
        <div class="col-lg-3">
            <div class="card-sidebar-compact">
                <div class="mb-3 d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
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
                    <i class="ti ti-info-circle me-1"></i> Sila pastikan alamat SSM dan Daerah dikemaskini dengan tepat.
                </p>
            </div>
        </div>

        <!-- RIGHT: FORM CARD -->
        <div class="col-lg-9">
            <div class="card-form-compact">
                
                <div class="card-form-header">
                    <h6 class="fw-bold text-dark m-0 d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        Butiran Alamat & Dokumen
                    </h6>
                </div>

                <div class="card-form-body">
                    <div class="row g-3">
                        
                        <!-- Alamat -->
                        <div class="col-12">
                            <label for="address" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="address" id="address" rows="4" required>{{ old('address', isset($vendor) ? $vendor->address : '') }}</textarea>
                        </div>

                        <!-- Daerah -->
                        <div class="col-md-6">
                            <label for="district_id" class="form-label">Daerah <span class="text-danger">*</span></label>
                            <select class="form-select" name="district_id" id="district_id" required>
                                <option value="" disabled selected>Pilihan Daerah...</option>
                                @foreach (App\Vendor::$districts as $key => $district_desc)
                                    <option value="{{ $key }}" {{ old('district_id', isset($vendor) ? $vendor->district_id : '') == $key ? 'selected' : '' }}>
                                        {{ $district_desc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Negeri -->
                        <div class="col-md-6" id="state_id_div" style="{{ ($vendor->district_id ?? '0') == 0 && ($vendor->state_id ?? '0') != '0' ? '' : 'display:none' }}">
                            <label for="state_id" class="form-label">Negeri <span class="text-danger">*</span></label>
                            <select class="form-select" name="state_id" id="state_id" 
                                    style="{{ ($vendor->district_id ?? '0') == 0 ? '' : 'display:none' }}"
                                    {{ ($vendor->district_id ?? '0') == 0 ? 'required' : '' }}>
                                <option value="" selected>Pilihan Negeri...</option>
                                @foreach ($country_states as $state)
                                    <option value="{{ $state->id }}" {{ $vendor->state_id == $state->id ? 'selected' : '' }}>
                                        {{ $state->description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tarikh SSM -->
                        <div class="col-md-6">
                            <label for="ssm_expiry" class="form-label">Tarikh SSM <span class="text-danger">*</span></label>
                            
                            @php
                                $ssmExpiryValue = old('ssm_expiry');
                                if (empty($ssmExpiryValue) && isset($vendor) && $vendor->ssm_expiry) {
                                    $rawSsmExpiry = $vendor->getOriginal('ssm_expiry');
                                    if (!empty($rawSsmExpiry)) {
                                        try {
                                            if (\Carbon\Carbon::hasFormat($rawSsmExpiry, 'Y-m-d')) {
                                                $ssmExpiryValue = \Carbon\Carbon::parse($rawSsmExpiry)->format('Y-m-d');
                                            } elseif (\Carbon\Carbon::hasFormat($rawSsmExpiry, 'd/m/Y')) {
                                                $ssmExpiryValue = \Carbon\Carbon::createFromFormat('d/m/Y', $rawSsmExpiry)->format('Y-m-d');
                                            } else {
                                                $ssmExpiryValue = \Carbon\Carbon::parse($rawSsmExpiry)->format('Y-m-d');
                                            }
                                        } catch (\Exception $e) {
                                            $ssmExpiryValue = $rawSsmExpiry;
                                        }
                                    }
                                }
                            @endphp

                            <input class="form-control" type="date" name="ssm_expiry" id="ssm_expiry" value="{{ $ssmExpiryValue ?? '' }}" required>
                        </div>

                        <div class="col-12"><hr class="text-muted opacity-25 my-1"></div>

                        <!-- Dokumen Sokongan -->
                        <div class="col-12">
                            <label for="sijil_daerah" class="form-label">Dokumen Sokongan <span class="text-danger">*</span></label>
                            <input class="form-control" type="file" name="sijil_daerah" id="sijil_daerah" accept="application/pdf" required>
                            <div class="form-text" style="font-size: 0.7rem;">
                                <i class="ti ti-file me-1"></i> Muat naik dokumen sokongan seperti sijil SPKK &amp; CIDB untuk rujukan (PDF).
                            </div>
                        </div>

                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3 border-top bg-light rounded-bottom">
                    
                    <!-- LEFT: NAV LINKS -->
                    <div class="d-flex gap-3">
                        <a href="{{ route('vendor.requests.index', $vendor->id) }}" class="btn btn-danger text-white fw-medium d-flex align-items-center gap-2">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            <span class="fw-bold">Senarai Permintaan</span>
                        </a>

                        <a href="{{ route(Auth::user()->hasRole('Vendor') ? 'vendor' : 'vendors.show', $vendor->id) }}" class="btn btn-danger text-white fw-medium d-flex align-items-center gap-2">
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
		$('#district_id').on('change', function() {
			let selected = this.value.toString();

			if (selected != 0 && selected !== "") {
				$("#state_id_div").hide();
				$("#state_id").hide();
				$("#state_id").prop("disabled", true);
				$("#state_id").removeAttr("required");
			} else {
				$("#state_id_div").show();
				$("#state_id").show();
				$("#state_id").prop("disabled", false);
				$("#state_id").attr("required", "required");
			}
		});
	</script>
@endsection