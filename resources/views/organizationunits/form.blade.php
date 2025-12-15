@php
	$isEdit = isset($organizationunit);
	$selectedType = old('type_id', $isEdit ? $organizationunit->type_id : '');
	$selectedParent = old('parent_id', $isEdit ? $organizationunit->parent_id : '0');
	$isConfirmationAgency = old('confirmation_agency', $isEdit ? $organizationunit->confirmation_agency : false);
	$organizationTypes = App\OrganizationType::all();
	$organizationParents = $isEdit
	    ? App\OrganizationUnit::where('id', '!=', $organizationunit->id)->get()
	    : App\OrganizationUnit::all();
@endphp

<style>
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
</style>

<div class="header-band d-flex flex-wrap align-items-center gap-4 mb-3">
	<div class="item">
		<small>Status</small>
		<span class="val ok">{{ $isEdit ? 'Kemaskini' : 'Pendaftaran Baharu' }}</span>
	</div>

	<div class="item flex-grow-1">
		<small>Agensi</small>
		<span class="val">{{ $isEdit ? $organizationunit->name : 'Agensi Baharu' }}</span>
	</div>
</div>

<div class="col-12">
	<div class="card">
		<form action="{{ $isEdit ? route('agencies.update', $organizationunit->id) : route('agencies.store') }}" method="POST">
			@csrf
			@if ($isEdit)
				@method('PUT')
			@endif

			<div class="card-body">
				<div class="row mb-3">
					<div class="col-12 d-flex align-items-center justify-content-between">
						<div class="h5 mb-0">{{ $isEdit ? 'Kemaskini Agensi' : 'Pendaftaran Agensi' }}</div>
						<div class="item ms-auto text-end">
							<small>ID</small>
							<span class="val">{{ $isEdit ? $organizationunit->id : 'Baharu' }}</span>
						</div>
					</div>
				</div>

				<hr class="my-3">

				<div class="row">
					<div class="col-md-6 mb-3">
						<label class="form-label">Nama Agensi <span class="text-danger">*</span></label>
						<input class="form-control" type="text" name="name"
							value="{{ old('name', $isEdit ? $organizationunit->name : '') }}" required>
					</div>

					<div class="col-md-6 mb-3">
						<label class="form-label">No Telefon</label>
						<input class="form-control" type="text" name="tel"
							value="{{ old('tel', $isEdit ? $organizationunit->tel : '') }}" placeholder="Contoh: 03xxxxxxx atau 01xxxxxxxx">
					</div>

					<div class="col-md-6 mb-3">
						<label class="form-label">Alamat Emel</label>
						<input class="form-control" type="email" name="email"
							value="{{ old('email', $isEdit ? $organizationunit->email : '') }}" placeholder="nama@agensi.gov.my">
					</div>

					<div class="col-md-6 mb-3">
						<label class="form-label">Kategori <span class="text-danger">*</span></label>
						<select class="form-control" name="type_id" id="type_id" required>
							<option value="">- Sila pilih kategori -</option>
							@foreach ($organizationTypes as $type)
								<option value="{{ $type->id }}" {{ $selectedType == $type->id ? 'selected' : '' }}>
									{{ $type->name }}
								</option>
							@endforeach
						</select>
					</div>

					<div class="col-md-12 mb-3">
						<label class="form-label">Alamat</label>
						<textarea class="form-control" name="address" rows="3" placeholder="Alamat penuh agensi">{{ old('address', $isEdit ? $organizationunit->address : '') }}</textarea>
					</div>

					<div class="col-md-6 mb-3">
						<label class="form-label">Agensi Utama</label>
						<select class="form-control" name="parent_id" id="parent_id">
							<option value="0" {{ $selectedParent == '0' ? 'selected' : '' }}>Tiada Parent</option>
							@foreach ($organizationParents as $parent)
								<option value="{{ $parent->id }}" {{ $selectedParent == $parent->id ? 'selected' : '' }}>
									{{ $parent->name }}
								</option>
							@endforeach
						</select>
					</div>

					<div class="col-md-6 mb-3 d-flex align-items-end">
						<div class="form-check">
							<input class="form-check-input" type="checkbox" id="confirmation_agency" name="confirmation_agency"
								value="1" {{ $isConfirmationAgency ? 'checked' : '' }}>
							<label class="form-check-label" for="confirmation_agency">Agensi Pengesahan</label>
						</div>
					</div>
				</div>

				<div class="row mt-4">
					<div class="col-12 d-flex justify-content-between">
						<a href="{{ route('agencies.index') }}" class="btn btn-outline-secondary">Senarai Agensi</a>
						<div class="d-flex gap-2">
							<button type="submit" class="btn btn-success">{{ $isEdit ? 'Simpan' : 'Hantar' }}</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

@section('scripts')
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			if (typeof $ !== 'undefined' && $.fn.selectize) {
				$('#type_id').selectize();
				$('#parent_id').selectize();
			}
		});
	</script>
@endsection
