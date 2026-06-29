@php
    $form = $content['form'] ?? null;
@endphp

@if ($form)
    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
        <div class="flex-grow-1">
            <div class="fw-semibold" style="font-size:0.84rem;">{{ $form['label'] ?? 'Borang Atas Talian' }}</div>
            <div class="mt-2 d-flex flex-wrap align-items-center gap-2">
                <span class="badge-status {{ $form['status_class'] ?? 'badge-status-warning' }}">
                    {{ $form['status'] ?? 'Belum Selesai' }}
                </span>
                @if ($mode === 'admin')
                    <span class="text-muted" style="font-size:0.72rem;">Status PTJ / templat penilaian</span>
                @endif
            </div>
            @if (! empty($form['summary']))
                <div class="text-muted mt-2" style="font-size:0.78rem;">
                    {{ $form['summary'] }}
                </div>
            @elseif ($mode === 'admin' && ($form['status_key'] ?? '') === 'draft')
                <div class="text-muted mt-2" style="font-size:0.78rem;">
                    Borang belum diisi oleh PTJ semasa pengurusan spesifikasi.
                </div>
            @endif
        </div>
        <div class="d-flex flex-column align-items-stretch gap-1">
            @if ($mode === 'vendor' && $vendorCanEdit && ! empty($form['url']))
                <button type="button" class="btn btn-sm btn-primary"
                    data-online-form-modal
                    data-form-url="{{ $form['url'] }}"
                    data-form-title="{{ $form['label'] ?? 'Borang Atas Talian' }}"
                    data-reload-on-complete="1"
                    data-reload-hash="vt-dokumen-tawaran">
                    {{ ($form['status_key'] ?? '') === 'submitted' ? 'Kemaskini Borang' : 'Isi Borang' }}
                </button>
            @elseif ($mode === 'admin' && ! empty($form['url']))
                @php
                    $adminViewUrl = ($form['url'] ?? '') . (str_contains($form['url'] ?? '', '?') ? '&' : '?') . 'mode=view&modal=1';
                @endphp
                <button type="button" class="btn btn-sm btn-outline-secondary"
                    data-online-form-modal
                    data-form-url="{{ $adminViewUrl }}"
                    data-form-title="{{ $form['label'] ?? 'Borang Atas Talian' }}"
                    data-reload-on-complete="0"
                    data-reload-hash="tf-dokumen-tawaran">
                    Lihat Borang
                </button>
            @else
                <span class="text-muted small text-center">Borang atas talian</span>
            @endif
            @if ($mode === 'vendor' && $vendorCanEdit && ($form['status_key'] ?? '') === 'submitted')
                <span class="text-success text-center" style="font-size:0.72rem;">Anda telah menghantar borang ini</span>
            @endif
        </div>
    </div>
@else
    <p class="text-muted small mb-0">Maklumat borang tidak tersedia.</p>
@endif
