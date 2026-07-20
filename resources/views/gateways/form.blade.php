<div class="row g-3">
    <div class="col-12">
        <label for="organization_unit_id" class="form-label fw-medium small">Agensi <span
                class="text-danger">*</span></label>
        <select id="organization_unit_id" name="organization_unit_id" required
            placeholder="Pilihan Agensi Penerima Bayaran...">
            <option value="">Pilihan Agensi Penerima Bayaran...</option>
            @foreach (App\OrganizationUnit::pluck('name', 'id') as $id => $name)
                <option value="{{ $id }}"
                    {{ old('organization_unit_id', $gateway->organization_unit_id ?? '') == $id ? 'selected' : '' }}>
                    {{ $name }}
                </option>
            @endforeach
        </select>
        {!! $errors->first('organization_unit_id', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <div class="col-md-6">
        <label for="type" class="form-label fw-medium small">Saluran <span class="text-danger">*</span></label>
        <select id="type" name="type" required>
            <option value="">-- Pilih Saluran --</option>
            @foreach (array_slice(App\Gateway::$methods, 1) as $value => $label)
                <option value="{{ $value }}"
                    {{ old('type', $gateway->type ?? '') == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        {!! $errors->first('type', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <div id="fpxVersion" class="col-md-6" style="display:none">
        <label for="version" class="form-label fw-medium small">Versi FPX</label>
        <input type="text" class="form-control" id="version" name="version"
            value="{{ old('version', $gateway->version ?? '') }}">
        {!! $errors->first('version', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <div class="col-md-6">
        <label for="merchant_code" class="form-label fw-medium small">ID Merchant</label>
        <input type="text" class="form-control" id="merchant_code" name="merchant_code"
            value="{{ old('merchant_code', $gateway->merchant_code ?? '') }}">
        {!! $errors->first('merchant_code', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <div class="col-md-6">
        <label for="private_key" class="form-label fw-medium small">Kekunci Rahsia</label>
        <input type="text" class="form-control" id="private_key" name="private_key"
            value="{{ old('private_key', $gateway->private_key ?? '') }}">
        {!! $errors->first('private_key', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <div class="col-md-6">
        <label for="endpoint_url" class="form-label fw-medium small">URL Transaksi</label>
        <input type="text" class="form-control" id="endpoint_url" name="endpoint_url"
            value="{{ old('endpoint_url', $gateway->endpoint_url ?? '') }}">
        {!! $errors->first('endpoint_url', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <div class="col-md-6">
        <label for="daemon_url" class="form-label fw-medium small">URL Daemon</label>
        <input type="text" class="form-control" id="daemon_url" name="daemon_url"
            value="{{ old('daemon_url', $gateway->daemon_url ?? '') }}">
        {!! $errors->first('daemon_url', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <div class="col-md-6">
        <label for="transaction_prefix" class="form-label fw-medium small">Label Transaksi</label>
        <input type="text" class="form-control" id="transaction_prefix" name="transaction_prefix"
            value="{{ old('transaction_prefix', $gateway->transaction_prefix ?? '') }}">
        {!! $errors->first('transaction_prefix', '<div class="text-danger small mt-1">:message</div>') !!}
    </div>

    <div class="col-md-6 d-flex gap-4 align-items-center pt-2">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="active" name="active" value="1"
                {{ old('active', $gateway->active ?? false) ? 'checked' : '' }}>
            <label class="form-check-label fw-medium small" for="active">Aktif</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="default" name="default" value="1"
                {{ old('default', $gateway->default ?? false) ? 'checked' : '' }}>
            <label class="form-check-label fw-medium small" for="default">Akaun Utama</label>
        </div>
    </div>
</div>

@section('scripts')
    @parent
    <script type="text/javascript">
        $(document).ready(function() {
            $("#organization_unit_id").selectize();
            $("#type").selectize();

            if (($('#type').val()) === 'fpx') {
                $('#fpxVersion').show();
            } else {
                $('#fpxVersion').hide();
            }
        });
    </script>
@endsection
