@extends('layouts.v3.master')

@section('content')
@php
    $actionLabels = [
        'vendor_upload' => 'Muat Naik',
        'download_upload' => 'Muat Turun & Muat Naik',
        'download_only' => 'Muat Turun',
        'key_in' => 'Isi Maklumat',
        'view_specification' => 'Spesifikasi',
        'online_form' => 'Borang Atas Talian',
    ];
@endphp

<div class="container-fluid py-4">
    <div class="d-flex align-items-start justify-content-between gap-3 mb-4">
        <div>
            <a href="{{ route('demo.vendorSubmissions.index') }}" class="text-decoration-none small">&larr; Kembali ke senarai</a>
            <h1 class="h4 mt-2 mb-1">Demo: Penyerahan Petender</h1>
            <p class="text-muted mb-0">
                <strong>{{ $tender->no_tender ?: $tender->ref_number }}</strong>
                — {{ $tender->name }}
            </p>
        </div>
        <span class="badge bg-warning text-dark">Dummy / Rujukan</span>
    </div>

    {{-- Summary cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-semibold">Pembeli Dokumen</div>
                    <div class="display-6 fw-bold text-primary">{{ $buyerCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-semibold">Item Senarai Semak</div>
                    <div class="display-6 fw-bold">{{ count($checklistItems) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-semibold">Jumlah Fail Dimuat Naik</div>
                    <div class="display-6 fw-bold">{{ count($allUploads) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Flat list: all uploaded files across vendors --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold">Semua Fail Dimuat Naik (flat list)</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Vendor</th>
                            <th>Item</th>
                            <th>Fail</th>
                            <th class="text-end pe-3">Muat Turun</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($allUploads as $upload)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-semibold">{{ $upload['vendor_name'] }}</div>
                                    <div class="text-muted" style="font-size:0.72rem;">ID {{ $upload['vendor_id'] }}</div>
                                </td>
                                <td>{{ $upload['item_title'] }}</td>
                                <td>{{ $upload['file_name'] }}</td>
                                <td class="text-end pe-3">
                                    <a href="{{ $upload['download_url'] }}" class="btn btn-sm btn-outline-primary" target="_blank">Muat Turun</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Tiada fail dimuat naik lagi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Per-vendor breakdown --}}
    @foreach ($submissions as $submission)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-bold">{{ $submission['vendor_name'] }}</div>
                    <div class="text-muted small">
                        Vendor ID: {{ $submission['vendor_id'] }}
                        @if ($submission['kod_pembekal'])
                            &middot; Kod: {{ $submission['kod_pembekal'] }}
                        @endif
                    </div>
                </div>
                <span class="badge bg-light text-dark border">{{ count($submission['items']) }} item</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="width:28%;">Item</th>
                                <th style="width:14%;">Tindakan</th>
                                <th style="width:12%;">Status</th>
                                <th>Penyerahan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($submission['items'] as $item)
                                @php
                                    $content = $item['vendor_content'] ?? [];
                                    $action = $item['action'] ?? '';
                                    $status = $item['vendor_status'] ?? 'draft';
                                @endphp
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-semibold">{{ $item['title'] ?? $item['nama'] ?? '-' }}</div>
                                        <div class="text-muted" style="font-size:0.72rem;">{{ $item['section'] ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $actionLabels[$action] ?? $item['tindakan'] ?? $action }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $status === 'submitted' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $status === 'submitted' ? 'Hantar' : 'Draf' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if (in_array($action, ['vendor_upload', 'download_upload'], true))
                                            @forelse ($content['files'] ?? [] as $file)
                                                <div>
                                                    <a href="{{ $file['url'] ?? '#' }}" target="_blank">{{ $file['name'] ?? 'Dokumen' }}</a>
                                                </div>
                                            @empty
                                                <span class="text-muted">Tiada fail</span>
                                            @endforelse
                                        @elseif ($action === 'key_in')
                                            <span class="text-muted">{{ filled($content['key_in'] ?? null) ? $content['key_in'] : 'Belum diisi' }}</span>
                                        @elseif ($action === 'view_specification')
                                            <span class="text-muted">
                                                {{ $status === 'submitted' ? 'Spesifikasi dihantar' : 'Belum dihantar' }}
                                            </span>
                                            @if (! empty($content['specification']['item_prices']))
                                                <div class="small text-muted mt-1">
                                                    Harga item: {{ count($content['specification']['item_prices']) }} rekod
                                                </div>
                                            @endif
                                        @elseif ($action === 'online_form')
                                            @php $form = $item['admin_content']['form'] ?? []; @endphp
                                            <div>{{ $form['summary'] ?? ($status === 'submitted' ? 'Borang dihantar' : 'Belum dihantar') }}</div>
                                            @if (! empty($form['url']))
                                                <a href="{{ $form['url'] }}" class="small" target="_blank">Papar borang</a>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach

    @if (count($submissions) === 0)
        <div class="alert alert-info">Tiada vendor yang membeli dokumen tender ini.</div>
    @endif

    {{-- Developer note --}}
    <div class="card border border-dashed bg-light">
        <div class="card-body small text-muted">
            <strong>Controller:</strong> <code>VendorSubmissionsDemoController@show</code><br>
            <strong>Presenter:</strong> <code>TenderDokumenPresenter::for($tender)->items('vendor', $vendorId)</code><br>
            <strong>Tables:</strong> <code>tender_vendor_dokumen_files</code>, <code>tender_vendor_dokumen_responses</code>, <code>tender_vendor_online_form_statuses</code>
        </div>
    </div>
</div>
@endsection
