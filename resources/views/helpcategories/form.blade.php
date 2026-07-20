<div class="row g-3">
    <div class="col-12">
        <label for="name" class="form-label fw-medium small">Nama <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="name" name="name"
            value="{{ old('name', $category->name ?? '') }}" required>
        {!! $errors->first('name', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <div class="col-12">
        <label for="description" class="form-label fw-medium small">Keterangan <span
                class="text-danger">*</span></label>
        <input type="text" class="form-control" id="description" name="description"
            value="{{ old('description', $category->description ?? '') }}" required>
        {!! $errors->first('description', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>
</div>
