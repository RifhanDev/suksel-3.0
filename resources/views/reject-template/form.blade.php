<div class="row g-3">
    <!-- Title -->
    <div class="col-12">
        <label for="title" class="form-label fw-medium small">Tajuk <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="title" name="title"
            value="{{ old('title', $template->title ?? '') }}" required>
        {!! $errors->first('title', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <!-- Content -->
    <div class="col-12">
        <label for="content-editor" class="form-label fw-medium small">Kandungan <span class="text-danger">*</span></label>
        <textarea class="form-control" id="content-editor" name="content" rows="8" required>{{ old('content', $template->content ?? '') }}</textarea>
        {!! $errors->first('content', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <!-- Applicable -->
    <div class="col-12">
        <label class="form-label fw-medium small">Digunapakai</label>
        <div class="d-flex flex-column gap-2">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="applicable_0" value="1" id="applicable_0"
                    {{ old('applicable_0', $template->applicable_0 ?? false) ? 'checked' : '' }}>
                <label class="form-check-label small" for="applicable_0">Pendaftaran/Kemaskini</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="applicable_1" value="1" id="applicable_1"
                    {{ old('applicable_1', $template->applicable_1 ?? false) ? 'checked' : '' }}>
                <label class="form-check-label small" for="applicable_1">Pemulangan Semula</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="applicable_2" value="1" id="applicable_2"
                    {{ old('applicable_2', $template->applicable_2 ?? false) ? 'checked' : '' }}>
                <label class="form-check-label small" for="applicable_2">Kebenaran Khas</label>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    @parent
    <script src="https://cdn.ckeditor.com/4.20.0/standard-all/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('content-editor');
    </script>
@endsection
