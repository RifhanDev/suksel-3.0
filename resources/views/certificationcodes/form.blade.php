<div class="row g-3">
    <div class="col-md-4">
        <label for="code" class="form-label fw-medium small">Kod <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="code" name="code"
            value="{{ old('code', $certificationcode->code ?? '') }}" required>
        {!! $errors->first('code', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <div class="col-md-8">
        <label for="name" class="form-label fw-medium small">Nama <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="name" name="name"
            value="{{ old('name', $certificationcode->name ?? '') }}" required>
        {!! $errors->first('name', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <div class="col-md-12">
        <label for="type" class="form-label fw-medium small">Agensi / Jenis <span class="text-danger">*</span></label>
        <select class="form-select" id="type" name="type" required>
            <option value="">-- Pilih Jenis --</option>
            @foreach (App\Code::$type as $value => $label)
                <option value="{{ $value }}"
                    {{ old('type', $certificationcode->type ?? '') == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        {!! $errors->first('type', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>
</div>
