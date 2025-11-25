<style>
	/* ===== Top info strip ===== */
	.header-band {
		background: #f6eaea;
		border: 1px solid #e7d1d1;
		border-radius: .5rem;
		padding: .75rem 1rem;
	}

	.header-band .item small {
		display: block;
		font-size: .75rem;
		text-transform: uppercase;
		letter-spacing: .03em;
		color: #666;
		font-weight: 700;
		line-height: 1.1;
	}

	.header-band .item .val {
		font-weight: 700;
		margin-top: .15rem;
		white-space: nowrap;
	}

	.header-band .ok {
		color: #19c1a7;
	}

	/* ===== Wizard progress pills ===== */
	.progress-nav {
		border-bottom: 4px solid #d9d9d9;
	}

	.progress-nav .progress {
		height: 4px;
		background: #e8e8e8;
		margin: 0;
		border-radius: 2px;
	}

	.progress-nav .progress-bar {
		background: #a84545;
		transition: width .3s ease;
	}

	.progress-nav .custom-nav {
		gap: 1rem;
		padding-top: .5rem;
	}

	.progress-nav .nav-link {
		border: 0 !important;
		background: transparent !important;
		color: #2b2b2b;
		font-weight: 700;
		padding: .45rem 1rem;
		border-radius: .75rem;
	}

	.progress-nav .nav-link.active {
		background: #a84545 !important;
		color: #fff !important;
		box-shadow: 0 2px 6px rgba(168, 69, 69, .25);
	}

	/* Force tab content to show */
	.tab-content {
		display: block !important;
	}

	.tab-pane {
		display: none;
	}

	.tab-pane.active {
		display: block !important;
	}

	.tab-pane.show {
		opacity: 1 !important;
		visibility: visible !important;
	}
</style>

<div class="header-band d-flex flex-wrap align-items-center gap-4 mb-3">
	<div class="item">
		<small>Vendor Status</small>
		<span class="val ok">{{ isset($vendor) ? ($vendor->approved ? 'Approved' : 'Pending') : 'New' }}</span>
	</div>

	<div class="item flex-grow-1">
		<small>Company</small>
		<span class="val">{{ isset($vendor) ? $vendor->name : 'New Vendor Registration' }}</span>
	</div>
</div>

<div class="col-12">
	<div class="card">
		<form action="{{ isset($vendor) ? route('vendors.update', $vendor->id) : route('vendors.store') }}" method="POST"
			enctype="multipart/form-data">
			@if (isset($vendor))
				@method('PUT')
			@endif
			@csrf
			<div class="card-body">
				<div class="row">
					<div class="col-12">
						<div class="d-flex align-items-center justify-content-between mb-3">
							<div>Vendor Management</div>
							<div class="item ms-auto text-end">
								<small>Status</small>
								<span
									class="val">{{ isset($vendor) ? ($vendor->approved ? 'Active' : 'Pending Approval') : 'New Registration' }}</span>
							</div>
						</div>
					</div>
					<hr>
					<div id="custom-progress-bar" class="progress-nav mb-4 p-2">
						<div class="progress" style="height: 1px;">
							<div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
								aria-valuemax="100"></div>
						</div>
						<ul class="nav nav-pills progress-bar-tab custom-nav" role="tablist">
							<li class="nav-item" role="presentation">
								<button type="button" id="maklumat-syarikat-tab" class="nav-link rounded-pill active"
									data-progressbar="custom-progress-bar" data-bs-toggle="pill" data-bs-target="#maklumat-syarikat" role="tab"
									aria-controls="maklumat-syarikat" aria-selected="true">1</button>
							</li>
							<li class="nav-item" role="presentation">
								<button type="button" id="maklumat-pegawai-tab" class="nav-link rounded-pill" data-bs-toggle="pill"
									data-bs-target="#maklumat-pegawai" role="tab" aria-controls="maklumat-pegawai"
									aria-selected="false">2</button>
							</li>
							<li class="nav-item" role="presentation">
								<button type="button" id="maklumat-sub-pegawai-tab" class="nav-link rounded-pill" data-bs-toggle="pill"
									data-bs-target="#maklumat-sub-pegawai" role="tab" aria-controls="maklumat-sub-pegawai"
									aria-selected="false">3</button>
							</li>
							<li class="nav-item" role="presentation">
								<button type="button" id="mof-cidb-tab" class="nav-link rounded-pill" data-bs-toggle="pill"
									data-bs-target="#mof-cidb" role="tab" aria-controls="mof-cidb" aria-selected="false">4</button>
							</li>
							<li class="nav-item" role="presentation">
								<button type="button" id="pemegang-saham-tab" class="nav-link rounded-pill" data-bs-toggle="pill"
									data-bs-target="#pemegang-saham" role="tab" aria-controls="pemegang-saham" aria-selected="false">5</button>
							</li>
						</ul>
					</div>
				</div>

				<div class="tab-content" id="vendor-content">
					<!-- Tab 1: Maklumat Syarikat -->
					<div class="tab-pane fade show active" id="maklumat-syarikat" role="tabpanel"
						aria-labelledby="maklumat-syarikat-tab">
						<div class="row mt-4 justify-content-center">
							<div class="col-12">
								<h4 class="card-title card-title-grey">MAKLUMAT SYARIKAT</h4>
								<p class="card-title-desc text-primary fst-italic">
									Sila isi maklumat syarikat dengan lengkap dan tepat
								</p>
							</div>
						</div>
						<div class="row d-flex justify-content-center">
							<div class="col-11">
								<div class="row">
									<!-- Email -->
									<div class="col-md-12 my-2">
										<div class="row">
											<div class="col-md-2 d-flex justify-content-end">
												<label for="email" class="">Alamat Emel <span class="text-danger">*</span></label>
											</div>
											<div class="col-md-10">
												<input class="form-control" type="email" name="email" id="email"
													value="{{ old('email', isset($vendor) ? $vendor->user->email : '') }}"
													{{ isset($vendor) && !Auth::user()->hasRole('Admin') ? 'disabled' : '' }} required>
											</div>
										</div>
									</div>

									<!-- No. Pendaftaran -->
									<div class="col-md-12 my-2">
										<div class="row">
											<div class="col-md-2 d-flex justify-content-end">
												<label for="registration" class="">No. Pendaftaran <span class="text-danger">*</span></label>
											</div>
											<div class="col-md-10">
												<input class="form-control" type="text" name="registration" id="registration"
													value="{{ old('registration', isset($vendor) ? $vendor->registration : '') }}"
													placeholder="Aksara, Nombor dan tanda '-' Sahaja"
													{{ isset($vendor) && !Auth::user()->hasRole('Admin') ? 'disabled' : '' }} required>
											</div>
										</div>
									</div>

									<!-- Nama Syarikat -->
									<div class="col-md-12 my-2">
										<div class="row">
											<div class="col-md-2 d-flex justify-content-end">
												<label for="name" class="">Nama Syarikat / Perniagaan <span
														class="text-danger">*</span></label>
											</div>
											<div class="col-md-10">
												<input class="form-control" type="text" name="name" id="name"
													value="{{ old('name', isset($vendor) ? $vendor->name : '') }}" required>
											</div>
										</div>
									</div>

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
												<select class="form-control" name="district_id" id="district_id" required>
													<option value="">- Pilihan daerah -</option>
													@foreach (App\Vendor::$districts as $key => $value)
														<option value="{{ $key }}"
															{{ old('district_id', isset($vendor) ? $vendor->district_id : '') == $key ? 'selected' : '' }}>
															{{ strtoupper($value) }}
														</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>

									<!-- Negeri (shown when district is 0 - Luar Selangor) -->
									<div class="col-md-12 my-2" id="state_id_div"
										style="{{ old('district_id', isset($vendor) ? $vendor->district_id : '') == '0' ? '' : 'display:none' }}">
										<div class="row">
											<div class="col-md-2 d-flex justify-content-end">
												<label for="state_id" class="">Negeri <span class="text-danger">*</span></label>
											</div>
											<div class="col-md-10">
												<select class="form-control" name="state_id" id="state_id">
													<option value="">- Pilihan Negeri -</option>
													@if (isset($country_states))
														@foreach ($country_states as $state)
															<option value="{{ $state->id }}"
																{{ old('state_id', isset($vendor) ? $vendor->state_id : '') == $state->id ? 'selected' : '' }}>
																{{ $state->description }}
															</option>
														@endforeach
													@endif
												</select>
											</div>
										</div>
									</div>

									<!-- No. Telefon -->
									<div class="col-md-12 my-2">
										<div class="row">
											<div class="col-md-2 d-flex justify-content-end">
												<label for="tel" class="">No. Telefon <span class="text-danger">*</span></label>
											</div>
											<div class="col-md-10">
												<input class="form-control" type="text" name="tel" id="tel"
													value="{{ old('tel', isset($vendor) ? $vendor->tel : '') }}" pattern="^[+0-9]{9,}$"
													placeholder="Tanda '+' dan nombor sahaja" required>
											</div>
										</div>
									</div>

									<!-- No. Faks -->
									<div class="col-md-12 my-2">
										<div class="row">
											<div class="col-md-2 d-flex justify-content-end">
												<label for="fax" class="">No. Faks</label>
											</div>
											<div class="col-md-10">
												<input class="form-control" type="text" name="fax" id="fax"
													value="{{ old('fax', isset($vendor) ? $vendor->fax : '') }}" pattern="^[+0-9]{9,}$"
													placeholder="Tanda '+' dan nombor sahaja">
											</div>
										</div>
									</div>

									<!-- Jenis Perniagaan -->
									<div class="col-md-12 my-2">
										<div class="row">
											<div class="col-md-2 d-flex justify-content-end">
												<label for="organization_type" class="">Jenis Perniagaan <span class="text-danger">*</span></label>
											</div>
											<div class="col-md-10">
												<select class="form-control" name="organization_type" id="organization_type" required>
													<option value="">- Pilih dari senarai -</option>
													@foreach ($RefOrganizationType as $value)
														<option value="{{ $value->id }}" data-is-ssm="{{ $value->is_ssm ?? 0 }}"
															{{ old('organization_type', isset($vendor) ? $vendor->organization_type : '') == $value->id ? 'selected' : '' }}>
															{{ $value->name }}
														</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>

									<!-- Tarikh Penubuhan -->
									<div class="col-md-12 my-2">
										<div class="row">
											<div class="col-md-2 d-flex justify-content-end">
												<label for="incorporation_date" class="">Tarikh Penubuhan <span
														class="text-danger">*</span></label>
											</div>
											<div class="col-md-10">
												<input class="form-control" type="date" name="incorporation_date" id="incorporation_date"
													value="{{ old('incorporation_date', isset($vendor) ? $vendor->incorporation_date : '') }}"
													max="{{ date('Y-m-d') }}" required>
											</div>
										</div>
									</div>

									<!-- Tarikh Tamat Sijil SSM -->
									<div class="col-md-12 my-2" id="ssm_expiry_div" style="display: none;">
										<div class="row">
											<div class="col-md-2 d-flex justify-content-end">
												<label for="ssm_expiry" class="">Tarikh Tamat Sijil SSM <span class="text-danger">*</span></label>
											</div>
											<div class="col-md-10">
												<input class="form-control" type="date" name="ssm_expiry" id="ssm_expiry"
													value="{{ old('ssm_expiry', isset($vendor) && $vendor->ssm_expiry ? $vendor->ssm_expiry->format('Y-m-d') : date('Y-m-d')) }}"
													min="{{ date('Y-m-d') }}">
											</div>
										</div>
									</div>

									<!-- Modal Dibenarkan -->
									<div class="col-md-12 my-2">
										<div class="row">
											<div class="col-md-2 d-flex justify-content-end">
												<label for="authorized_capital" class="">Modal Dibenarkan</label>
											</div>
											<div class="col-md-3">
												<select class="form-control" name="authorized_capital_currency">
													@foreach (App\Vendor::$currencies as $key => $value)
														<option value="{{ $key }}"
															{{ old('authorized_capital_currency', isset($vendor) ? $vendor->authorized_capital_currency : 'MYR') == $key ? 'selected' : '' }}>
															{{ $value }}
														</option>
													@endforeach
												</select>
											</div>
											<div class="col-md-7">
												<input class="form-control" type="number" step="0.01" name="authorized_capital"
													id="authorized_capital"
													value="{{ old('authorized_capital', isset($vendor) ? $vendor->authorized_capital : '0.00') }}">
											</div>
										</div>
									</div>

									<!-- Modal Berbayar -->
									<div class="col-md-12 my-2">
										<div class="row">
											<div class="col-md-2 d-flex justify-content-end">
												<label for="paidup_capital" class="">Modal Berbayar</label>
											</div>
											<div class="col-md-3">
												<select class="form-control" name="paidup_capital_currency">
													@foreach (App\Vendor::$currencies as $key => $value)
														<option value="{{ $key }}"
															{{ old('paidup_capital_currency', isset($vendor) ? $vendor->paidup_capital_currency : 'MYR') == $key ? 'selected' : '' }}>
															{{ $value }}
														</option>
													@endforeach
												</select>
											</div>
											<div class="col-md-7">
												<input class="form-control" type="number" step="0.01" name="paidup_capital" id="paidup_capital"
													value="{{ old('paidup_capital', isset($vendor) ? $vendor->paidup_capital : '0.00') }}">
											</div>
										</div>
									</div>

									<!-- No. Rujukan Cukai -->
									<div class="col-md-12 my-2">
										<div class="row">
											<div class="col-md-2 d-flex justify-content-end">
												<label for="tax_no" class="">No. Rujukan Cukai</label>
											</div>
											<div class="col-md-10">
												<input class="form-control" type="text" name="tax_no" id="tax_no"
													value="{{ old('tax_no', isset($vendor) ? $vendor->tax_no : '') }}">
											</div>
										</div>
									</div>

									<!-- No Pendaftaran GST -->
									<div class="col-md-12 my-2">
										<div class="row">
											<div class="col-md-2 d-flex justify-content-end">
												<label for="gst_no" class="">No Pendaftaran GST</label>
											</div>
											<div class="col-md-10">
												<input class="form-control" type="text" name="gst_no" id="gst_no"
													value="{{ old('gst_no', isset($vendor) ? $vendor->gst_no : '') }}">
											</div>
										</div>
									</div>

									<!-- Laman Web -->
									<div class="col-md-12 my-2">
										<div class="row">
											<div class="col-md-2 d-flex justify-content-end">
												<label for="website" class="">Laman Web</label>
											</div>
											<div class="col-md-10">
												<input class="form-control" type="url" name="website" id="website"
													value="{{ old('website', isset($vendor) ? $vendor->website : '') }}">
											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-12 d-flex justify-content-between">
										<div class="left"></div>
										<div class="right">
											<button type="submit" class="btn-md-sm btn btn-success">Simpan</button>
											<button type="button" class="btn btn-primary ms-auto" data-nexttab="maklumat-pegawai-tab">
												Seterusnya
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Tab 2: Maklumat Pegawai -->
					<div class="tab-pane fade" id="maklumat-pegawai" role="tabpanel" aria-labelledby="maklumat-pegawai-tab">
						<div class="row mt-4 justify-content-center">
							<div class="col-12">
								<h4 class="card-title card-title-grey">MAKLUMAT PEGAWAI</h4>
							</div>
						</div>
						<div class="row d-flex justify-content-center">
							<div class="col-11">
								<!-- Nama Pegawai -->
								<div class="col-md-12 my-2">
									<div class="row">
										<div class="col-md-2 d-flex justify-content-end">
											<label for="officer_name" class="">Nama Pegawai <span class="text-danger">*</span></label>
										</div>
										<div class="col-md-10">
											<input class="form-control" type="text" name="officer_name" id="officer_name"
												value="{{ old('officer_name', isset($vendor) ? $vendor->officer_name : '') }}" required>
										</div>
									</div>
								</div>

								<!-- Jawatan Pegawai -->
								<div class="col-md-12 my-2">
									<div class="row">
										<div class="col-md-2 d-flex justify-content-end">
											<label for="officer_designation" class="">Jawatan Pegawai <span class="text-danger">*</span></label>
										</div>
										<div class="col-md-10">
											<input class="form-control" type="text" name="officer_designation" id="officer_designation"
												value="{{ old('officer_designation', isset($vendor) ? $vendor->officer_designation : '') }}" required>
										</div>
									</div>
								</div>

								<!-- No. Telefon Pegawai -->
								<div class="col-md-12 my-2">
									<div class="row">
										<div class="col-md-2 d-flex justify-content-end">
											<label for="officer_tel" class="">No. Telefon <span class="text-danger">*</span></label>
										</div>
										<div class="col-md-10">
											<input class="form-control" type="text" name="officer_tel" id="officer_tel"
												value="{{ old('officer_tel', isset($vendor) ? $vendor->officer_tel : '') }}" required>
										</div>
									</div>
								</div>

								@if (!isset($vendor))
									<!-- Password -->
									<div class="col-md-12 my-2">
										<div class="row">
											<div class="col-md-2 d-flex justify-content-end">
												<label for="password" class="">Kata Laluan <span class="text-danger">*</span></label>
											</div>
											<div class="col-md-10">
												<input class="form-control" type="password" name="password" id="password" required>
											</div>
										</div>
									</div>

									<!-- Password Confirmation -->
									<div class="col-md-12 my-2">
										<div class="row">
											<div class="col-md-2 d-flex justify-content-end">
												<label for="password_confirmation" class="">Sahkan Kata Laluan <span
														class="text-danger">*</span></label>
											</div>
											<div class="col-md-10">
												<input class="form-control" type="password" name="password_confirmation" id="password_confirmation"
													required>
											</div>
										</div>
									</div>
								@endif

								<div class="row">
									<div class="col-12 d-flex justify-content-between">
										<div class="left">
											<button type="button" class="btn-md-sm btn btn-info"
												data-prevtab="maklumat-syarikat-tab">Sebelumnya</button>
										</div>
										<div class="right">
											<button type="submit" class="btn-md-sm btn btn-success">Simpan</button>
											<button type="button" class="btn btn-primary ms-auto" data-nexttab="maklumat-sub-pegawai-tab">
												Seterusnya
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Tab 3: Maklumat Sub Pegawai -->
					<div class="tab-pane fade" id="maklumat-sub-pegawai" role="tabpanel"
						aria-labelledby="maklumat-sub-pegawai-tab">
						<div class="row mt-4 justify-content-center">
							<div class="col-12">
								<h4 class="card-title card-title-grey">MAKLUMAT SUB PEGAWAI</h4>
							</div>
						</div>
						<div class="row d-flex justify-content-center">
							<div class="col-11">
								<!-- Sub Officers Container -->
								<div id="sub-officers-container">
									@php
										// Check if we have existing sub-officers data (for edit mode)
										$isEdit = isset($vendor) && $vendor->id;

										// Get existing officers from relationship or old input
										if (old('sub_officers')) {
										    // From validation errors - use old input
										    $existingOfficers = old('sub_officers');
										} elseif ($isEdit && $vendor->subOfficers) {
										    // From database - load existing sub-officers
										    $existingOfficers = $vendor->subOfficers
										        ->map(function ($officer) {
										            return [
										                'id' => $officer->id,
										                'name' => $officer->name,
										                'email' => $officer->email,
										                'phone' => $officer->phone,
										                'start_date' => $officer->start_date ? $officer->start_date->format('Y-m-d') : '',
										                'end_date' => $officer->end_date ? $officer->end_date->format('Y-m-d') : '',
										            ];
										        })
										        ->toArray();
										} else {
										    // New form - create one empty entry
										    $existingOfficers = [['name' => '', 'email' => '', 'phone' => '', 'start_date' => '', 'end_date' => '']];
										}
									@endphp

									@foreach ($existingOfficers as $index => $officer)
										<!-- Sub Officer Card {{ $index + 1 }} -->
										<div class="card shadow-sm mb-3 officer-card" data-index="{{ $index }}">
											<div class="card-header bg-light d-flex justify-content-between align-items-center">
												<h5 class="mb-0">
													<i class="fas fa-user-tie me-2"></i>Sub Pegawai <span class="officer-number">{{ $index + 1 }}</span>
												</h5>
												<button type="button" class="btn btn-sm btn-danger remove-officer"
													style="display: {{ count($existingOfficers) > 1 ? 'inline-block' : 'none' }};" title="Buang">
													<i class="fas fa-trash"></i> Buang
												</button>
											</div>
											<div class="card-body">
												<div class="row">
													<!-- Hidden ID for existing records -->
													@if (isset($officer['id']))
														<input type="hidden" name="sub_officers[{{ $index }}][id]" value="{{ $officer['id'] }}">
													@endif

													<!-- Nama -->
													<div class="col-md-6 mb-3">
														<label class="form-label">Nama <span class="text-danger">*</span></label>
														<input class="form-control" type="text" name="sub_officers[{{ $index }}][name]"
															value="{{ old('sub_officers.' . $index . '.name', $officer['name'] ?? '') }}"
															placeholder="Masukkan nama pegawai" required>
													</div>

													<!-- Email -->
													<div class="col-md-6 mb-3">
														<label class="form-label">Email <span class="text-danger">*</span></label>
														<input class="form-control" type="email" name="sub_officers[{{ $index }}][email]"
															value="{{ old('sub_officers.' . $index . '.email', $officer['email'] ?? '') }}"
															placeholder="Masukkan email pegawai" required>
													</div>

													<!-- No. Telefon -->
													<div class="col-md-6 mb-3">
														<label class="form-label">No. Telefon <span class="text-danger">*</span></label>
														<input class="form-control" type="text" name="sub_officers[{{ $index }}][phone]"
															value="{{ old('sub_officers.' . $index . '.phone', $officer['phone'] ?? '') }}"
															placeholder="Contoh: 0123456789" required>
													</div>

													<!-- Spacer for alignment -->
													<div class="col-md-6 mb-3"></div>

													<!-- Kata Laluan -->
													<div class="col-md-6 mb-3">
														<label class="form-label">Kata Laluan @if (!$isEdit)
																<span class="text-danger">*</span>
															@endif
														</label>
														<input class="form-control password-field" type="password"
															name="sub_officers[{{ $index }}][password]" placeholder="Minimum 8 aksara"
															{{ $isEdit ? '' : 'required' }} minlength="8">
														<small
															class="text-muted">{{ $isEdit ? 'Biarkan kosong jika tidak ingin mengubah' : 'Minimum 8 aksara' }}</small>
													</div>

													<!-- Sahkan Kata Laluan -->
													<div class="col-md-6 mb-3">
														<label class="form-label">Sahkan Kata Laluan @if (!$isEdit)
																<span class="text-danger">*</span>
															@endif
														</label>
														<input class="form-control confirm-password-field" type="password"
															name="sub_officers[{{ $index }}][password_confirmation]"
															placeholder="Masukkan semula kata laluan" {{ $isEdit ? '' : 'required' }} minlength="8">
														<small class="text-muted">Mesti sama dengan kata laluan</small>
													</div>

													<!-- Tempoh Penggunaan -->
													<div class="col-12">
														<label class="form-label fw-bold">Tempoh Penggunaan <span class="text-danger">*</span></label>
													</div>

													<!-- Tarikh Mula -->
													<div class="col-md-6 mb-3">
														<label class="form-label">Tarikh Mula</label>
														<input class="form-control" type="date" name="sub_officers[{{ $index }}][start_date]"
															value="{{ old('sub_officers.' . $index . '.start_date', $officer['start_date'] ?? '') }}" required>
													</div>

													<!-- Tarikh Tamat -->
													<div class="col-md-6 mb-3">
														<label class="form-label">Tarikh Tamat</label>
														<input class="form-control" type="date" name="sub_officers[{{ $index }}][end_date]"
															value="{{ old('sub_officers.' . $index . '.end_date', $officer['end_date'] ?? '') }}" required>
													</div>
												</div>
											</div>
										</div>
									@endforeach
								</div>

								<!-- Add More Button -->
								<div class="row mb-4">
									<div class="col-12">
										<button type="button" class="btn btn-success" id="add-officer">
											<i class="fas fa-plus-circle me-2"></i> Tambah Sub Pegawai
										</button>
									</div>
								</div>

								<!-- Navigation Buttons -->
								<div class="row">
									<div class="col-12 d-flex justify-content-between">
										<div class="left">
											<button type="button" class="btn-md-sm btn btn-info"
												data-prevtab="maklumat-syarikat-tab">Sebelumnya</button>
										</div>
										<div class="right">
											<button type="submit" class="btn-md-sm btn btn-success">Simpan</button>
											<button type="button" class="btn btn-primary ms-auto" data-nexttab="mof-cidb-tab">
												Seterusnya
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- JavaScript for Dynamic Sub Officers Cards -->
					<script>
						document.addEventListener('DOMContentLoaded', function() {
							// Initialize officer index based on existing cards
							let officerIndex = document.querySelectorAll('.officer-card').length;
							const isEditMode = {{ isset($vendor) && $vendor->id ? 'true' : 'false' }};

							// Add new officer card
							document.getElementById('add-officer').addEventListener('click', function() {
								const container = document.getElementById('sub-officers-container');
								const newCard = createOfficerCard(officerIndex);
								container.insertAdjacentHTML('beforeend', newCard);
								officerIndex++;
								updateRemoveButtons();
								updateCardNumbers();
							});

							// Remove officer card (delegated event)
							document.getElementById('sub-officers-container').addEventListener('click', function(e) {
								if (e.target.classList.contains('remove-officer') || e.target.closest('.remove-officer')) {
									const card = e.target.closest('.officer-card');
									card.remove();
									updateRemoveButtons();
									updateCardNumbers();
								}
							});

							// Create new officer card HTML
							function createOfficerCard(index) {
								const passwordRequired = isEditMode ? '' : 'required';
								const passwordLabel = isEditMode ? 'Kata Laluan' : 'Kata Laluan <span class="text-danger">*</span>';
								const passwordHint = isEditMode ? 'Biarkan kosong jika tidak ingin mengubah' : 'Minimum 8 aksara';

								return `
						<div class="card shadow-sm mb-3 officer-card" data-index="${index}">
							<div class="card-header bg-light d-flex justify-content-between align-items-center">
								<h5 class="mb-0">
									<i class="fas fa-user-tie me-2"></i>Sub Pegawai <span class="officer-number">${index + 1}</span>
								</h5>
								<button type="button" class="btn btn-sm btn-danger remove-officer" title="Buang">
									<i class="fas fa-trash"></i> Buang
								</button>
							</div>
							<div class="card-body">
								<div class="row">
									<!-- Nama -->
									<div class="col-md-6 mb-3">
										<label class="form-label">Nama <span class="text-danger">*</span></label>
										<input class="form-control" type="text" name="sub_officers[${index}][name]" 
											placeholder="Masukkan nama pegawai" required>
									</div>

									<!-- Email -->
									<div class="col-md-6 mb-3">
										<label class="form-label">Email <span class="text-danger">*</span></label>
										<input class="form-control" type="email" name="sub_officers[${index}][email]" 
											placeholder="Masukkan email pegawai" required>
									</div>

									<!-- No. Telefon -->
									<div class="col-md-6 mb-3">
										<label class="form-label">No. Telefon <span class="text-danger">*</span></label>
										<input class="form-control" type="text" name="sub_officers[${index}][phone]" 
											placeholder="Contoh: 0123456789" required>
									</div>

									<!-- Spacer for alignment -->
									<div class="col-md-6 mb-3"></div>

									<!-- Kata Laluan -->
									<div class="col-md-6 mb-3">
										<label class="form-label">${passwordLabel}</label>
										<input class="form-control password-field" type="password" name="sub_officers[${index}][password]" 
											placeholder="Minimum 8 aksara" ${passwordRequired} minlength="8">
										<small class="text-muted">${passwordHint}</small>
									</div>

									<!-- Sahkan Kata Laluan -->
									<div class="col-md-6 mb-3">
										<label class="form-label">${passwordLabel}</label>
										<input class="form-control confirm-password-field" type="password" name="sub_officers[${index}][password_confirmation]" 
											placeholder="Masukkan semula kata laluan" ${passwordRequired} minlength="8">
										<small class="text-muted">Mesti sama dengan kata laluan</small>
									</div>

									<!-- Tempoh Penggunaan -->
									<div class="col-12">
										<label class="form-label fw-bold">Tempoh Penggunaan <span class="text-danger">*</span></label>
									</div>

									<!-- Tarikh Mula -->
									<div class="col-md-6 mb-3">
										<label class="form-label">Tarikh Mula</label>
										<input class="form-control" type="date" name="sub_officers[${index}][start_date]" required>
									</div>

									<!-- Tarikh Tamat -->
									<div class="col-md-6 mb-3">
										<label class="form-label">Tarikh Tamat</label>
										<input class="form-control" type="date" name="sub_officers[${index}][end_date]" required>
									</div>
								</div>
							</div>
						</div>
					`;
							}

							// Update remove buttons visibility
							function updateRemoveButtons() {
								const cards = document.querySelectorAll('.officer-card');
								cards.forEach((card, index) => {
									const removeBtn = card.querySelector('.remove-officer');
									if (cards.length > 1) {
										removeBtn.style.display = 'inline-block';
									} else {
										removeBtn.style.display = 'none';
									}
								});
							}

							// Update card numbers
							function updateCardNumbers() {
								const cards = document.querySelectorAll('.officer-card');
								cards.forEach((card, index) => {
									card.querySelector('.officer-number').textContent = index + 1;
								});
							}

							// Password matching validation
							document.getElementById('sub-officers-container').addEventListener('input', function(e) {
								if (e.target.classList.contains('confirm-password-field')) {
									const card = e.target.closest('.officer-card');
									const passwordField = card.querySelector('.password-field');
									const confirmField = e.target;

									// In edit mode, if both fields are empty, it's valid (not changing password)
									if (isEditMode && !passwordField.value && !confirmField.value) {
										confirmField.setCustomValidity('');
									} else if (confirmField.value !== passwordField.value) {
										confirmField.setCustomValidity('Kata laluan tidak sepadan');
									} else {
										confirmField.setCustomValidity('');
									}
								}

								if (e.target.classList.contains('password-field')) {
									const card = e.target.closest('.officer-card');
									const confirmField = card.querySelector('.confirm-password-field');

									// In edit mode, if both fields are empty, it's valid (not changing password)
									if (isEditMode && !e.target.value && !confirmField.value) {
										confirmField.setCustomValidity('');
									} else if (confirmField.value && confirmField.value !== e.target.value) {
										confirmField.setCustomValidity('Kata laluan tidak sepadan');
									} else {
										confirmField.setCustomValidity('');
									}
								}
							});
						});
					</script>

					<!-- Tab 4: MOF & CIDB -->
					<div class="tab-pane fade" id="mof-cidb" role="tabpanel" aria-labelledby="mof-cidb-tab">
						<div class="row mt-4 justify-content-center">
							<div class="col-12">
								<h4 class="card-title card-title-grey">MAKLUMAT MOF & CIDB</h4>
							</div>
						</div>
						<div class="row d-flex justify-content-center">
							<div class="col-11">
								<!-- MOF Section -->
								<div class="col-md-12 my-2">
									<h5>Maklumat MOF</h5>
								</div>

								<!-- No Rujukan MOF -->
								<div class="col-md-12 my-2">
									<div class="row">
										<div class="col-md-2 d-flex justify-content-end">
											<label for="mof_ref_no" class="">No Rujukan Pendaftaran MOF</label>
										</div>
										<div class="col-md-10">
											<input class="form-control" type="text" name="mof_ref_no" id="mof_ref_no"
												value="{{ old('mof_ref_no', isset($vendor) ? $vendor->mof_ref_no : '') }}">
										</div>
									</div>
								</div>

								<!-- Tarikh Aktif MOF -->
								<div class="col-md-12 my-2">
									<div class="row">
										<div class="col-md-2 d-flex justify-content-end">
											<label for="mof_start_date" class="">Tarikh Aktif MOF</label>
										</div>
										<div class="col-md-4">
											<input class="form-control" type="date" name="mof_start_date" id="mof_start_date"
												value="{{ old('mof_start_date', isset($vendor) && $vendor->mof_start_date ? Carbon\Carbon::parse($vendor->mof_start_date)->format('Y-m-d') : '') }}">
										</div>
										<div class="col-md-1 d-flex align-items-center justify-content-center">
											<span>hingga</span>
										</div>
										<div class="col-md-4">
											<input class="form-control" type="date" name="mof_end_date" id="mof_end_date"
												value="{{ old('mof_end_date', isset($vendor) && $vendor->mof_end_date ? Carbon\Carbon::parse($vendor->mof_end_date)->format('Y-m-d') : '') }}">
										</div>
									</div>
								</div>

								<!-- CIDB Section -->
								<div class="col-md-12 my-2 mt-4">
									<h5>Maklumat CIDB</h5>
								</div>

								<!-- No Sijil CIDB -->
								<div class="col-md-12 my-2">
									<div class="row">
										<div class="col-md-2 d-flex justify-content-end">
											<label for="cidb_ref_no" class="">No Sijil CIDB</label>
										</div>
										<div class="col-md-10">
											<input class="form-control" type="text" name="cidb_ref_no" id="cidb_ref_no"
												value="{{ old('cidb_ref_no', isset($vendor) ? $vendor->cidb_ref_no : '') }}">
										</div>
									</div>
								</div>

								<!-- Tarikh Aktif CIDB -->
								<div class="col-md-12 my-2">
									<div class="row">
										<div class="col-md-2 d-flex justify-content-end">
											<label for="cidb_start_date" class="">Tarikh Aktif CIDB</label>
										</div>
										<div class="col-md-4">
											<input class="form-control" type="date" name="cidb_start_date" id="cidb_start_date"
												value="{{ old('cidb_start_date', isset($vendor) && $vendor->cidb_start_date ? Carbon\Carbon::parse($vendor->cidb_start_date)->format('Y-m-d') : '') }}">
										</div>
										<div class="col-md-1 d-flex align-items-center justify-content-center">
											<span>hingga</span>
										</div>
										<div class="col-md-4">
											<input class="form-control" type="date" name="cidb_end_date" id="cidb_end_date"
												value="{{ old('cidb_end_date', isset($vendor) && $vendor->cidb_end_date ? Carbon\Carbon::parse($vendor->cidb_end_date)->format('Y-m-d') : '') }}">
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-12 d-flex justify-content-between">
										<div class="left">
											<button type="button" class="btn-md-sm btn btn-info"
												data-prevtab="maklumat-pegawai-tab">Sebelumnya</button>
										</div>
										<div class="right">
											<button type="submit" class="btn-md-sm btn btn-success">Simpan</button>
											<button type="button" class="btn btn-primary ms-auto" data-nexttab="pemegang-saham-tab">
												Seterusnya
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Tab 4: Pemegang Saham -->
					<div class="tab-pane fade" id="pemegang-saham" role="tabpanel" aria-labelledby="pemegang-saham-tab">
						<div class="row mt-4 justify-content-center">
							<div class="col-12">
								<h4 class="card-title card-title-grey">PEMEGANG SAHAM</h4>
							</div>
						</div>
						<div class="row d-flex justify-content-center">
							<div class="col-11">
								<p class="text-muted">Pemegang saham will be managed here</p>

								<div class="row">
									<div class="col-12 d-flex justify-content-between">
										<div class="left">
											<button type="button" class="btn-md-sm btn btn-info" data-prevtab="mof-cidb-tab">Sebelumnya</button>
										</div>
										<div class="right">
											<button type="submit" class="btn-md-sm btn btn-success">Simpan</button>
											<button type="submit" class="btn-md-sm btn btn-primary">Hantar</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Organization Type change handler - show/hide SSM expiry based on is_ssm
		const organizationTypeSelect = document.getElementById('organization_type');
		const ssmExpiryDiv = document.getElementById('ssm_expiry_div');
		const ssmExpiryInput = document.getElementById('ssm_expiry');

		function toggleSsmExpiry() {
			if (organizationTypeSelect && ssmExpiryDiv && ssmExpiryInput) {
				const selectedOption = organizationTypeSelect.options[organizationTypeSelect.selectedIndex];
				const isSsm = selectedOption.getAttribute('data-is-ssm');

				if (isSsm === '1') {
					ssmExpiryDiv.style.display = '';
					ssmExpiryInput.setAttribute('required', 'required');
				} else {
					ssmExpiryDiv.style.display = 'none';
					ssmExpiryInput.removeAttribute('required');
				}
			}
		}

		// Run on page load to set initial state
		if (organizationTypeSelect) {
			toggleSsmExpiry();
			organizationTypeSelect.addEventListener('change', toggleSsmExpiry);
		}

		// District change handler - show/hide state dropdown
		const districtSelect = document.getElementById('district_id');
		const stateDiv = document.getElementById('state_id_div');

		if (districtSelect && stateDiv) {
			districtSelect.addEventListener('change', function() {
				if (this.value == '0') {
					stateDiv.style.display = '';
				} else {
					stateDiv.style.display = 'none';
				}
			});
		}

		// Tab navigation buttons
		const nextButtons = document.querySelectorAll('[data-nexttab]');
		const prevButtons = document.querySelectorAll('[data-prevtab]');

		nextButtons.forEach(btn => {
			btn.addEventListener('click', function() {
				const nextTabId = this.getAttribute('data-nexttab');
				const nextTabEl = document.getElementById(nextTabId);
				if (nextTabEl) new bootstrap.Tab(nextTabEl).show();
			});
		});

		prevButtons.forEach(btn => {
			btn.addEventListener('click', function() {
				const prevTabId = this.getAttribute('data-prevtab');
				const prevTabEl = document.getElementById(prevTabId);
				if (prevTabEl) new bootstrap.Tab(prevTabEl).show();
			});
		});

		// Progress bar update
		const barWrap = document.getElementById('custom-progress-bar');
		const tabButtons = barWrap ? Array.from(barWrap.querySelectorAll('[data-bs-toggle="pill"]')) : [];
		const bar = barWrap ? barWrap.querySelector('.progress-bar') : null;

		function updateProgress(targetBtn) {
			if (!bar || !tabButtons.length) return;
			const idx = tabButtons.indexOf(targetBtn);
			const pct = (idx / Math.max(1, tabButtons.length - 1)) * 100;
			bar.style.width = pct + '%';
			bar.setAttribute('aria-valuenow', pct);
		}

		tabButtons.forEach(btn => {
			btn.addEventListener('shown.bs.tab', (e) => updateProgress(e.target));
		});

		const initialActive = tabButtons.find(b => b.classList.contains('active')) || tabButtons[0];
		if (initialActive) updateProgress(initialActive);
	});
</script>
