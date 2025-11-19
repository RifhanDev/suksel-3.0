@extends('layouts.modern')
@section('content')
	<div class="col-12">
		<div class="card">
			{!! Former::open_for_files(route('vendor.requests.store', [$vendor->id, 'type' => $type])) !!}
			{!! Former::populate($vendor) !!}
			<div class="card-body">
				<div class="row">
					<div class="col-12">
						<div class="d-flex align-items-center justify-content-between mb-3">
							<div>Masukkan Permintaan Kemaskini Daerah</div>
						</div>
					</div>
					<hr>
				</div>

				<div class="row mt-4 justify-content-center">
					<div class="col-12">
						<h4 class="card-title card-title-grey">MAKLUMAT PERMINTAAN KEMASKINI</h4>
						<p class="card-title-desc text-primary fst-italic">
							Sila isi maklumat di bawah untuk membuat permintaan kemaskini alamat SSM dan daerah
						</p>
					</div>
				</div>

				<div class="row d-flex justify-content-center">
					<div class="col-11">
						<div class="row">
							<!-- Alamat -->
							<div class="col-md-12 my-2">
								<div class="row">
									<div class="col-md-2 d-flex justify-content-end">
										<label for="address" class="">Alamat <span class="text-danger">*</span></label>
									</div>
									<div class="col-md-10">
										<textarea class="form-control" name="address" id="address" rows="4" required>{{ old('address', isset($vendor) ? $vendor->address : '') }}</textarea>
									</div>
								</div>
							</div>

							<!-- Daerah -->
							<div class="col-md-12 my-2">
								<div class="row">
									<div class="col-md-2 d-flex justify-content-end">
										<label for="district_id" class="">Daerah <span class="text-danger">*</span></label>
									</div>
									<div class="col-md-10">
										@php
											$district_list = ['' => 'Pilihan Daerah...'];
											foreach (App\Vendor::$districts as $key => $district_desc) {
											    $district_list[$key] = $district_desc;
											}
										@endphp
										<select class="form-control" name="district_id" id="district_id" required>
											@foreach ($district_list as $key => $value)
												<option value="{{ $key }}"
													{{ old('district_id', isset($vendor) ? $vendor->district_id : '') == $key ? 'selected' : '' }}>
													{{ $value }}
												</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>

							<!-- Negeri (shown when district is 0 - Luar Selangor) -->
							<div class="col-md-12 my-2" id="state_id_div"
								style="{{ ($vendor->district_id ?? '0') == 0 && ($vendor->state_id ?? '0') != '0' ? '' : 'display:none' }}">
								<div class="row">
									<div class="col-md-2 d-flex justify-content-end">
										<label for="state_id" class="">Negeri <span class="text-danger">*</span></label>
									</div>
									<div class="col-md-10">
										<select class="form-control" name="state_id" id="state_id"
											style="{{ ($vendor->district_id ?? '0') == 0 ? '' : 'display:none' }}"
											{{ ($vendor->district_id ?? '0') == 0 ? 'required' : '' }}>
											<option value="" selected>Pilihan Negeri...</option>
											@foreach ($country_states as $state)
												<option value="{{ $state->id }}" {{ $vendor->state_id == $state->id ? 'selected' : '' }}>
													{{ $state->description }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>

							<!-- Tarikh SSM -->
							<div class="col-md-12 my-2">
								<div class="row">
									<div class="col-md-2 d-flex justify-content-end">
										<label for="ssm_expiry" class="">Tarikh SSM <span class="text-danger">*</span></label>
									</div>
									<div class="col-md-10">
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
										<input class="form-control" type="date" name="ssm_expiry" id="ssm_expiry"
											value="{{ $ssmExpiryValue ?? '' }}" required>
									</div>
								</div>
							</div>

							<!-- Dokumen Sokongan -->
							<div class="col-md-12 my-2">
								<div class="row">
									<div class="col-md-2 d-flex justify-content-end">
										<label for="sijil_daerah" class="">Dokumen Sokongan <span class="text-danger">*</span></label>
									</div>
									<div class="col-md-10">
										<input class="form-control" type="file" name="sijil_daerah" id="sijil_daerah" accept="application/pdf"
											required>
										<small class="form-text text-muted">Muat naik dokumen sokongan seperti sijil SPKK &amp; CIDB untuk
											rujukan.</small>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row mt-4">
					<div class="col-12">
						<div class="d-flex justify-content-end gap-2">
							<a href="{{ route('vendor.requests.index', $vendor->id) }}" class="btn btn-default">Senarai Permintaan
								Kemaskini</a>
							<a href="{{ route(Auth::user()->hasRole('Vendor') ? 'vendor' : 'vendors.show', $vendor->id) }}"
								class="btn btn-default">Maklumat Syarikat</a>
							{!! Former::submit('Hantar')->class('btn btn-primary') !!}
						</div>
					</div>
				</div>
			</div>
			{!! Former::close() !!}
		</div>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		function selectize_select(id) {
			$(id).find('select.selectize').each(function() {
				if (!this.selectize) $(this).selectize();
			});
		}

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
