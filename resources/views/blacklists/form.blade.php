<div class="row g-3">
    
    <!-- Agency Select -->
    <div class="col-12">
        <label for="organization_unit_id" class="form-label">Agensi</label>
        <!-- NOTE: Selectize will attach here automatically -->
        <select id="organization_unit_id" name="organization_unit_id" placeholder="Pilihan agensi...">
            <option value="">Pilihan agensi...</option>
            @foreach(App\OrganizationUnit::pluck('name', 'id') as $id => $name)
                <option value="{{ $id }}" {{ (isset($blacklist) && $blacklist->organization_unit_id == $id) ? 'selected' : '' }}>
                    {{ $name }}
                </option>
            @endforeach
        </select>
        <div class="form-text small text-muted">
            Pilih agensi yang ingin disenarai hitam atau <strong>kosongkan</strong> jika mahu syarikat di senarai hitam untuk kesemua tender / sebut harga.
        </div>
    </div>

    <!-- Reason -->
    <div class="col-12">
        <label for="reason" class="form-label">Sebab <span class="text-danger">*</span></label>
        <textarea class="form-control" id="reason" name="reason" rows="5" required>{{ isset($blacklist) ? $blacklist->reason : old('reason') }}</textarea>
    </div>

    <!-- Date Range -->
    <div class="col-md-6">
        <label for="start" class="form-label">Tarikh Mula <span class="text-danger">*</span></label>
        <div class="input-group input-group-sm">
            {{-- <span class="input-group-text"><i class="ti ti-calendar"></i></span> --}}
            <input type="text" class="form-control" id="start" name="start" 
                   value="{{ isset($blacklist) ? $blacklist->start_date : old('start') }}" required>
        </div>
    </div>

    <div class="col-md-6">
        <label for="end" class="form-label">Tarikh Tamat <span class="text-danger">*</span></label>
        <div class="input-group input-group-sm">
            {{-- <span class="input-group-text"><i class="ti ti-calendar"></i></span> --}}
            <input type="text" class="form-control" id="end" name="end" 
                   value="{{ isset($blacklist) ? $blacklist->end_date : old('end') }}" required>
        </div>
    </div>

    <div class="col-12"><hr class="text-muted opacity-25 my-1"></div>

    <!-- File Attachment -->
    <div class="col-12">
        <label for="file" class="form-label">Lampiran @if(!$blacklist->exists()) <span class="text-danger">*</span> @endif</label>
        <input class="form-control form-control-sm" type="file" id="file" name="file" accept="application/pdf" @if(!$blacklist->exists()) required @endif>
        <div class="form-text" style="font-size: 0.7rem;">Format PDF sahaja.</div>
    </div>

</div>

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // Initialize Selectize
            $("#organization_unit_id").selectize();
            
            // Initialize Datepicker
            $('input[name="start"], input[name="end"]').datepicker({
                format: 'd M yyyy',
                autoclose: true,
                todayHighlight: true
            });
        });
    </script>
@endsection