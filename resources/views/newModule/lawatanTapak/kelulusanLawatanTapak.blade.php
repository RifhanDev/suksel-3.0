@extends('layouts.v3.master')

@section('content')
<style>
    .table-modern thead th {
        background-color: #f8fafc; color: #64748b; font-weight: 700;
        text-transform: uppercase; font-size: 0.7rem; padding: 14px 12px;
        border-bottom: 2px solid #e2e8f0;
    }
    .table-modern tbody td {
        padding: 12px; vertical-align: middle; font-size: 0.85rem;
        border-bottom: 1px solid #f1f5f9;
    }
</style>

<div class="mb-3 d-flex gap-2 flex-wrap">
    <a href="{{ route('lawatanTapakUrusetia') }}" class="btn btn-sm btn-light border">&larr; Senarai Tender</a>
    <a href="{{ route('pengesahanLawatanTapak', $tender->id) }}" class="btn btn-sm btn-info text-white">Kemaskini Kehadiran</a>
</div>

<div class="card border shadow-sm mb-3 rounded-3">
    <div class="card-body p-3">
        <div class="row g-2">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Tender</label>
                <h6 class="text-primary mb-0">{{ $tender->ref_number ?: $tender->no_tender ?: $tender->id }}</h6>
            </div>
            <div class="col-md-5">
                <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tajuk</label>
                <h6 class="mb-0">{{ $tender->name }}</h6>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary text-uppercase mb-1">PTJ</label>
                <h6 class="mb-0">{{ $tender->tenderer?->name ?? '-' }}</h6>
            </div>
        </div>
    </div>
</div>

<div class="card border shadow-sm mb-4 rounded-3">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Ringkasan Kehadiran Lawatan Tapak</h5>
        <p class="text-muted small mb-0">Paparan wakil yang telah didaftarkan / disahkan hadir.</p>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern mb-0">
                <thead>
                    <tr>
                        <th class="text-center">Bil</th>
                        <th>ROC Syarikat</th>
                        <th>Nama Syarikat</th>
                        <th>Lokasi Lawatan</th>
                        <th>Tarikh</th>
                        <th>No. IC</th>
                        <th>Nama Wakil</th>
                        <th class="text-center">Status Hadir</th>
                    </tr>
                </thead>
                <tbody>
                    @php $bil = 0; @endphp
                    @forelse ($attendanceRows as $row)
                        @php $bil++; @endphp
                        <tr>
                            <td class="text-center">{{ $bil }}</td>
                            <td>{{ $row['vendor']->registration ?? '-' }}</td>
                            <td>{{ $row['vendor']->name ?? '-' }}</td>
                            <td><small>{!! nl2br(e($row['visit']->meetpoint)) !!}</small></td>
                            <td>{{ \Carbon\Carbon::parse($row['visit']->datetime)->format('j M Y H:i') }}</td>
                            <td>{{ $row['rep']?->ic_no ?: '—' }}</td>
                            <td>{{ $row['rep']?->name ?: '—' }}</td>
                            <td class="text-center">
                                @if ($row['rep']?->attended || $row['attended'])
                                    <span class="badge bg-success">Hadir</span>
                                @else
                                    <span class="badge bg-secondary">Belum Hadir</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Tiada rekod kehadiran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
