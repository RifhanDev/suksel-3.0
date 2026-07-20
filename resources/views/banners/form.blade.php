<div class="row g-3">
    <!-- Title -->
    <div class="col-12">
        <label for="title" class="form-label fw-medium small">Tajuk <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="title" name="title"
            value="{{ old('title', $banner->title ?? '') }}" placeholder="Masukkan tajuk banner" required>
        {!! $errors->first('title', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <!-- File Upload -->
    <div class="col-12">
        <label for="file" class="form-label fw-medium small">
            Fail Banner
            @if (!$banner->exists())
                <span class="text-danger">*</span>
            @endif
        </label>
        <input type="file" class="form-control" id="file" name="file"
            accept="image/png,image/jpg,image/jpeg" {{ !$banner->exists() ? 'required' : '' }}>
        <small class="text-muted mt-1 d-block">Format yang diterima: PNG, JPG, JPEG (Maksimum 5MB)</small>
        {!! $errors->first('file', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    @if ($banner->exists() && $banner->file)
        <div class="col-12">
            <label class="form-label fw-medium small">Preview Banner Semasa:</label>
            <div>
                <img src="{{ $banner->file->url }}/{{ $banner->file->name }}" alt="{{ $banner->title }}"
                    class="img-fluid rounded shadow-sm" style="max-height: 200px;">
            </div>
        </div>
    @endif

    <!-- Link -->
    <div class="col-12">
        <label for="link" class="form-label fw-medium small">Pautan (URL)</label>
        <input type="text" class="form-control" id="link" name="link"
            value="{{ old('link', $banner->link ?? '') }}" placeholder="https://contoh.com">
        <small class="text-muted mt-1 d-block">URL yang akan dibuka apabila banner diklik</small>
        {!! $errors->first('link', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <!-- Date Range -->
    <div class="col-md-6">
        <label for="start" class="form-label fw-medium small">Tarikh Mula Paparan</label>
        <input type="text" class="form-control datepicker" id="start" name="start"
            value="{{ old('start', $banner->start ? \Carbon\Carbon::parse($banner->start)->format('j M Y') : '') }}"
            placeholder="Pilih tarikh mula">
        {!! $errors->first('start', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <div class="col-md-6">
        <label for="end" class="form-label fw-medium small">Tarikh Tamat Paparan</label>
        <input type="text" class="form-control datepicker" id="end" name="end"
            value="{{ old('end', $banner->end ? \Carbon\Carbon::parse($banner->end)->format('j M Y') : '') }}"
            placeholder="Pilih tarikh tamat">
        {!! $errors->first('end', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <!-- Published Toggle -->
    <div class="col-12">
        <div class="form-check form-switch d-flex align-items-start gap-3">
            <input class="form-check-input mt-1" type="checkbox" id="published" name="published" value="1"
                {{ old('published', $banner->published ?? false) ? 'checked' : '' }}
                style="width: 2.5em; height: 1.25em; flex-shrink: 0;">
            <div>
                <label class="form-check-label fw-medium small mb-0" for="published">Siar banner ini</label>
                <small class="text-muted d-block mt-1">Aktifkan untuk memaparkan banner di laman utama</small>
            </div>
        </div>
    </div>
</div>

<style>
    /* Datepicker Custom Styling - Light Theme */
    .datepicker {
        background: white !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 8px !important;
        padding: 10px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    }

    .datepicker table {
        background: white !important;
    }

    .datepicker table tr th,
    .datepicker table tr td {
        background: white !important;
        color: #495057 !important;
        border-radius: 4px !important;
    }

    .datepicker table tr th {
        font-weight: 600 !important;
        color: #667eea !important;
        background: #f8f9fa !important;
    }

    .datepicker thead tr:first-child th {
        background: #667eea !important;
        color: white !important;
        font-weight: 500 !important;
    }

    .datepicker thead tr:first-child th:hover {
        background: #5568d3 !important;
    }

    .datepicker table tr td.day:hover,
    .datepicker table tr td.focused {
        background: #e9ecef !important;
        color: #495057 !important;
        cursor: pointer !important;
    }

    .datepicker table tr td.active,
    .datepicker table tr td.active:hover,
    .datepicker table tr td.active.highlighted {
        background: #667eea !important;
        color: white !important;
        font-weight: 600 !important;
    }

    .datepicker table tr td.today,
    .datepicker table tr td.today:hover {
        background: #ffc107 !important;
        color: white !important;
        font-weight: 600 !important;
    }

    .datepicker table tr td.today.active {
        background: #667eea !important;
        color: white !important;
    }

    .datepicker table tr td.old,
    .datepicker table tr td.new {
        color: #adb5bd !important;
        background: white !important;
    }

    .datepicker table tr td.disabled,
    .datepicker table tr td.disabled:hover {
        color: #dee2e6 !important;
        background: white !important;
        cursor: not-allowed !important;
    }

    .datepicker table tr td span {
        background: white !important;
        color: #495057 !important;
    }

    .datepicker table tr td span:hover,
    .datepicker table tr td span.focused {
        background: #e9ecef !important;
    }

    .datepicker table tr td span.active,
    .datepicker table tr td span.active:hover {
        background: #667eea !important;
        color: white !important;
    }

    .datepicker .datepicker-switch,
    .datepicker .prev,
    .datepicker .next,
    .datepicker tfoot tr th {
        color: #495057 !important;
        background: white !important;
    }

    .datepicker .datepicker-switch:hover,
    .datepicker .prev:hover,
    .datepicker .next:hover,
    .datepicker tfoot tr th:hover {
        background: #e9ecef !important;
    }
</style>

@section('scripts')
    @parent
    <script type="text/javascript">
        $(document).ready(function() {
            // File input enhancement
            $('input[type="file"]').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                if (fileName) {
                    var $hint = $(this).siblings('.text-muted');
                    if ($hint.length) {
                        $hint.first().text('File dipilih: ' + fileName);
                    }
                }
            });

            // Initialize datepicker for date fields
            $('.datepicker').datepicker({
                format: 'd M yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: 'bottom auto'
            });

            // Set minimum end date based on start date
            $('input[name="start"]').on('change', function() {
                var startDate = $(this).datepicker('getDate');
                $('input[name="end"]').datepicker('setStartDate', startDate);
            });
        });
    </script>
@endsection
