<div class="row g-3">
    <!-- Title -->
    <div class="col-12">
        <label for="title" class="form-label fw-medium small">Tajuk <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="title" name="title"
            value="{{ old('title', $circular->title ?? '') }}" required>
        {!! $errors->first('title', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <!-- File Upload -->
    <div class="col-12">
        <label for="file" class="form-label fw-medium small">Fail Pekeliling (PDF)</label>
        <input type="file" class="form-control" id="file" name="file" accept="application/pdf">
        <small class="text-muted mt-1 d-block">Muat naik fail PDF pekeliling. Diperlukan jika tiada URL PDF.</small>
        {!! $errors->first('file', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <!-- PDF Link -->
    <div class="col-12">
        <label for="pdf_link" class="form-label fw-medium small">URL ke PDF</label>
        <input type="text" class="form-control" id="pdf_link" name="pdf_link"
            value="{{ old('pdf_link', $circular->pdf_link ?? '') }}" placeholder="https://contoh.com/pekeliling.pdf">
        <small class="text-muted mt-1 d-block">Pautan terus ke PDF. Diperlukan jika tiada fail dimuat naik.</small>
        {!! $errors->first('pdf_link', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <!-- Published Toggle -->
    <div class="col-12">
        <div class="form-check form-switch d-flex align-items-start gap-3">
            <input class="form-check-input mt-1" type="checkbox" id="published" name="published" value="1"
                {{ old('published', $circular->published ?? false) ? 'checked' : '' }}
                style="width: 2.5em; height: 1.25em; flex-shrink: 0;">
            <div>
                <label class="form-check-label fw-medium small mb-0" for="published">Siar pekeliling ini</label>
                <small class="text-muted d-block mt-1">Aktifkan untuk memaparkan pekeliling di laman awam</small>
            </div>
        </div>
    </div>
</div>
