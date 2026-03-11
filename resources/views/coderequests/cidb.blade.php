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
    
    .repeater-item {
        display: block;
        background: #fdfdfd;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        position: relative;
    }
    .btn-delete-cidb_group {
        position: absolute; top: 5px; right: 10px; padding: 2px 6px;
    }
</style>
@endsection

@section('content')

<div class="mb-4">
    <h4 class="fw-bold text-dark m-0">Permintaan Kemaskini CIDB</h4>
</div>

<form action="{{ route('vendor.requests.store', [$vendor->id, 'type' => $type]) }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        
        <!-- LEFT -->
        <div class="col-lg-3">
            <div class="card-sidebar-compact">
                <div class="mb-3 d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="M3 21h18"/><path d="M5 21V7l8-4 8 4v14"/><path d="M17 21v-8H7v8"/></svg>
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
                    <i class="ti ti-info-circle me-1"></i> Pastikan maklumat Sijil & Gred dimasukkan dengan tepat.
                </p>
            </div>
        </div>

        <!-- RIGHT -->
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
                            <label for="cidb_ref_no" class="form-label">No Sijil CIDB</label>
                            <input type="text" class="form-control" id="cidb_ref_no" name="cidb_ref_no" value="{{ $vendor->cidb_ref_no }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tarikh Aktif</label>
                            <div class="input-group input-group-md">
                                <input type="text" class="form-control" id="cidb_start_date" name="cidb_start_date" 
                                       value="{{ Carbon\Carbon::parse($vendor->cidb_start_date)->format('j M Y') }}" placeholder="Mula">
                                <span class="input-group-text px-2 small">hingga</span>
                                <input type="text" class="form-control" id="cidb_end_date" name="cidb_end_date" 
                                       value="{{ Carbon\Carbon::parse($vendor->cidb_end_date)->format('j M Y') }}" placeholder="Tamat">
                            </div>
                        </div>

                        <!-- Checkbox -->
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cidb_bumi" name="cidb_bumi" value="1" {{ $vendor->cidb_bumi ? 'checked' : '' }}>
                                <label class="form-check-label text-dark fw-bold small" for="cidb_bumi">
                                    Status Syarikat Bumiputera
                                </label>
                            </div>
                        </div>

                        <div class="col-12"><hr class="text-muted opacity-25 my-1"></div>

                        <!-- REPEATER -->
                        <div class="col-12">
                            <label class="form-label d-block mb-2">Gred & Bidang Pengkhususan</label>
                            
                            <div id="cidb_group">
                                <!-- Template -->
                                <div id="cidb_group_template" class="repeater-item">
                                    <input type="hidden" id="cidb_group_#index#_id" class="cidb-group-id" name="cidb_group[#index#][id]">
                                    
                                    <div class="row g-2">
                                        <div class="col-md-4 pe-4"> 
                                            <label class="small text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Gred</label>
                                            
                                            <select id="cidb_group_#index#_code_id" class="cidb_group-code_id selectize" name="cidb_group[#index#][code_id]">
                                                <option disabled="disabled" selected="selected" value="">Pilih...</option>
                                                @foreach (App\Code::where('type', 'cidb-g')->orderBy('code', 'asc')->get() as $code)
                                                    <option value="{{ $code->id }}">{{ $code->label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-8">
                                            <label class="small text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Bidang</label>
                                            
                                            <select id="cidb_group_#index#_codes" class="cidb_group-codes selectize" name="cidb_group[#index#][codes][]" multiple="multiple">
                                                <option disabled="disabled" value="">Pilih...</option>
                                                @foreach (App\Code::where('type', 'cidb-c')->orderBy('code', 'asc')->get() as $code)
                                                    <option value="{{ $code->id }}">{{ $code->label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <button type="button" class="btn btn-sm text-danger btn-delete-cidb_group" id="cidb_group_remove_current">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    </button>
                                </div>

                                <div id="cidb_group_noforms_template" class="alert alert-light text-center border p-2 mb-2 small">
                                    Tiada maklumat ditambah.
                                </div>

                                <div id="cidb_group_controls" class="mb-2">
                                    <div id="cidb_group_add">
                                        <button type="button" class="btn btn-sm btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2 fw-semibold">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                            Tambah Gred
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="deleted_cidb_group[]">
                        </div>

                        <div class="col-12"><hr class="text-muted opacity-25 my-1"></div>

                        <!-- Files -->
                        <div class="col-md-6">
                            <label for="sijil_cidb" class="form-label">Sijil SPKK & CIDB <span class="text-danger">*</span></label>
                            <input class="form-control form-control-sm" type="file" id="sijil_cidb" name="sijil_cidb" accept="application/pdf" required>
                            <div class="form-text" style="font-size: 0.7rem;">Satu fail PDF sahaja.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="sijil_cidb_bumiputera" class="form-label">Sijil Bumiputera PKK</label>
                            <input class="form-control form-control-sm" type="file" id="sijil_cidb_bumiputera" name="sijil_cidb_bumiputera" accept="application/pdf">
                            <div class="form-text" style="font-size: 0.7rem;">PDF sahaja (Jika ada).</div>
                        </div>

                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3 border-top bg-light rounded-bottom">
                    
                    <!-- LEFT: NAV LINKS -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('vendor.requests.index', $vendor->id) }}" class="btn btn-danger text-white fw-medium d-flex align-items-center gap-2">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            <span class="fw-bold">Senarai Permintaan Kemaskini</span>
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
	{{-- <script src="{{ asset('js/request.js') }}"></script> --}}
	<script type="text/javascript">
		function selectize_select(id) {
			$(id).find('select.selectize').each(function() {
				if (!this.selectize) $(this).selectize();
			});
		}

		$("#cidb_group").sheepIt({
			separator: '',
			minFormsCount: 0,
			iniFormsCount: 1,
			allowAdd: true,
			@if (isset($vendor) && $vendor->cidbGrades)
				data: [
					@foreach ($vendor->cidbGrades()->orderBy('id', 'asc')->get() as $grade)
						{
							'cidb_group_#index#_id': "{{ $grade->id }}",
							'cidb_group_#index#_code_id': "{{ $grade->code_id }}",
							'cidb_group_#index#_codes': {{ json_encode($grade->children()->pluck('code_id')) }}
						},
					@endforeach
				]
			@endif
		});
		selectize_select("#cidb_group");
		$("#cidb_group_add").click(function() {
			selectize_select('#cidb_group');
		});
		$(".btn-delete-cidb_group").click(function() {
			id = $(this).siblings('.cidb-group-id').val();

			if (id) {
				deleted = $('input[name="deleted_cidb_group[]"]:first');

				if (deleted.val() == "") {
					deleted.val(id);
				} else {
					new_deleted = deleted.clone();
					new_deleted.val(id);
					new_deleted.insertAfter(deleted);
				}
			}
		});

        // Initialize datepicker
        $(document).ready(function() {
            $("#cidb_start_date, #cidb_end_date").datepicker({
                format: 'd M yyyy',
                autoclose: true
            });
        });
	</script>
@endsection