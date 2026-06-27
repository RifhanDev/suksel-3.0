@php
    $note = $content['note'] ?? 'Petender perlu mengisi maklumat.';
    $value = $dok['vendor_content']['key_in'] ?? '';
    $itemUuid = $dok['uuid'] ?? '';
    $section = $dok['section'] ?? '';
    $tenderId = $tender->id ?? null;
@endphp

<p class="text-muted mb-2" style="font-size:0.78rem;">{{ $note }}</p>

@if ($mode === 'vendor' && $vendorCanEdit)
    <div class="dokumen-key-in-form"
        data-save-url="{{ route('tenderDokumen.saveKeyIn', ['tender' => $tenderId, 'itemUuid' => $itemUuid]) }}"
        data-section="{{ $section }}"
        data-csrf="{{ csrf_token() }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-8">
                <label class="form-label small text-muted mb-1">Nilai / Maklumat</label>
                <input type="text" class="form-control form-control-sm dokumen-key-in-input"
                    value="{{ $value }}" placeholder="Sila isi...">
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-sm btn-primary dokumen-key-in-save">Simpan</button>
            </div>
        </div>
        <div class="text-success small mt-2 d-none dokumen-key-in-success">Berjaya disimpan.</div>
        <div class="text-danger small mt-2 d-none dokumen-key-in-error"></div>
    </div>
@elseif ($mode === 'admin')
    @if ($value !== '')
        <div class="border rounded p-2 bg-light small">
            <span class="text-muted">Contoh penyerahan:</span>
            <div class="fw-semibold mt-1">{{ $value }}</div>
        </div>
    @else
        <div class="text-muted small">Petender akan mengisi maklumat selepas membeli tender.</div>
    @endif
@else
    <div class="text-muted small">Sila beli dokumen tender untuk mengisi maklumat.</div>
@endif
