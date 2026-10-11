@extends('layouts.v3.master')

@section('content')
    <div class="content-card p-5">
        <div class="text-center py-4">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                style="width:72px;height:72px;background:#fef2f2;color:#b91c1c;">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
            <h3 class="fw-bold text-dark mb-2">Tempoh Bidaan Telah Tamat</h3>
            <p class="text-muted mb-1" style="max-width:520px;margin-left:auto;margin-right:auto;">
                {{ $message ?? 'Proses e-bidding untuk tender ini telah ditutup. Anda tidak lagi boleh menghantar atau mengemaskini harga bidaan.' }}
            </p>
            @if (! empty($tender))
                <p class="small text-secondary mb-4">
                    {{ $tender->ref_number ?: $tender->no_tender ?: ('#' . $tender->id) }}
                    @if ($tender->name)
                        · {{ $tender->name }}
                    @endif
                </p>
            @endif
            <a href="{{ url('/dashboard?tab=ebidding') }}" class="btn btn-selangor">
                Kembali ke Dashboard
            </a>
        </div>
    </div>
@endsection
