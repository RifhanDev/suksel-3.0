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

<div class="col-12">
    <div class="content-card" style="overflow: visible;">

        <form action="{{ $isEdit ? route('agencies.update', $organizationunit->id) : route('agencies.store') }}" method="POST">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <!-- Section header -->
            <div class="content-card-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="content-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 21l18 0"/><path d="M9 8l1 0"/><path d="M9 12l1 0"/>
                            <path d="M9 16l1 0"/><path d="M14 8l1 0"/><path d="M14 12l1 0"/>
                            <path d="M14 16l1 0"/><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"/>
                        </svg>
                    </div>
                    <h3 class="content-card-title">{{ $isEdit ? 'Kemaskini Agensi' : 'Pendaftaran Agensi Baru' }}</h3>
                </div>
            </div>

            <div class="content-card-body p-4">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-medium small">Nama Agensi <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="name"
                            value="{{ old('name', $isEdit ? $organizationunit->name : '') }}" required>
                        {!! $errors->first('name', '<div class="text-danger small mt-1">:message</div>') !!}
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium small">No Telefon</label>
                        <input class="form-control" type="text" name="tel"
                            value="{{ old('tel', $isEdit ? $organizationunit->tel : '') }}" placeholder="Contoh: 03xxxxxxx atau 01xxxxxxxx">
                        {!! $errors->first('tel', '<div class="text-danger small mt-1">:message</div>') !!}
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium small">Alamat Emel</label>
                        <input class="form-control" type="email" name="email"
                            value="{{ old('email', $isEdit ? $organizationunit->email : '') }}" placeholder="nama@agensi.gov.my">
                        {!! $errors->first('email', '<div class="text-danger small mt-1">:message</div>') !!}
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium small">Kategori <span class="text-danger">*</span></label>
                        <select name="type_id" id="type_id" required>
                            <option value="">- Sila pilih kategori -</option>
                            @foreach ($organizationTypes as $type)
                                <option value="{{ $type->id }}" {{ $selectedType == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        {!! $errors->first('type_id', '<div class="text-danger small mt-1">:message</div>') !!}
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-medium small">Alamat</label>
                        <textarea class="form-control" name="address" rows="3" placeholder="Alamat penuh agensi">{{ old('address', $isEdit ? $organizationunit->address : '') }}</textarea>
                        {!! $errors->first('address', '<div class="text-danger small mt-1">:message</div>') !!}
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium small">Agensi Utama</label>
                        <select name="parent_id" id="parent_id">
                            <option value="0" {{ $selectedParent == '0' ? 'selected' : '' }}>Tiada Parent</option>
                            @foreach ($organizationParents as $parent)
                                <option value="{{ $parent->id }}" {{ $selectedParent == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        {!! $errors->first('parent_id', '<div class="text-danger small mt-1">:message</div>') !!}
                    </div>

                    <div class="col-md-6 d-flex align-items-end pb-1">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirmation_agency" name="confirmation_agency"
                                value="1" {{ $isConfirmationAgency ? 'checked' : '' }}>
                            <label class="form-check-label small fw-medium" for="confirmation_agency">Agensi Pengesahan</label>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Footer actions -->
            <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top bg-light rounded-bottom">
                <a href="{{ route('agencies.index') }}" class="btn-form btn-form-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Senarai Agensi
                </a>
                <button type="submit" class="btn-form btn-form-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    {{ $isEdit ? 'Simpan' : 'Hantar' }}
                </button>
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
