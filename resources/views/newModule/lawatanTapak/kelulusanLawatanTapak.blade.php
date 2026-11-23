@extends('layouts.v3.master')

@section('styles')
<link href="{{ asset('css/components/tender-show.css') }}" rel="stylesheet">
<style>
    .lawatan-tabs .nav-tabs { border-bottom: 1px solid #f1f5f9; padding: 0 1rem; overflow-y: hidden; }
    .lawatan-tabs .nav-tabs .nav-link { flex-direction: column; align-items: flex-start; gap: 2px; padding: 0.85rem 1.25rem; }
    .lawatan-tab-title { font-size: 0.88rem; }
    .lawatan-tab-sub { font-size: 0.72rem; font-weight: 500; color: #94a3b8; }
    .lawatan-tabs .nav-link.active .lawatan-tab-sub { color: #c41e3a; opacity: 0.75; }

    .lawatan-agenda { display: grid; grid-template-columns: 132px minmax(0, 1fr); border-bottom: 1px solid #f1f5f9; }
    .lawatan-agenda-date { padding: 1.1rem 1.5rem; border-right: 1px solid #f1f5f9; }
    .lawatan-agenda-dow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.6px; text-transform: uppercase; color: #94a3b8; }
    .lawatan-agenda-day { font-size: 2.1rem; font-weight: 800; color: #0f172a; line-height: 1.05; letter-spacing: -1px; font-variant-numeric: tabular-nums; }
    .lawatan-agenda-my { font-size: 0.8rem; font-weight: 600; color: #475569; }
    .lawatan-agenda-time { font-size: 0.8rem; color: #64748b; margin-top: 6px; }
    .lawatan-agenda-body { padding: 1.1rem 1.5rem; display: flex; flex-direction: column; justify-content: center; }
    .lawatan-stop { display: grid; grid-template-columns: 20px minmax(0, 1fr); column-gap: 12px; }
    .lawatan-stop + .lawatan-stop { margin-top: 14px; }
    .lawatan-stop-mark { color: #c41e3a; padding-top: 2px; position: relative; display: flex; justify-content: center; }
    .lawatan-stop.has-next .lawatan-stop-mark::after { content: ''; position: absolute; left: 9px; top: 24px; height: 100%; border-left: 2px dashed #e2e8f0; }
    .lawatan-stop-label { font-size: 0.75rem; font-weight: 600; color: #64748b; }
    .lawatan-stop-place { font-size: 1rem; font-weight: 700; color: #0f172a; line-height: 1.4; }
    .lawatan-stop-meet { font-size: 0.92rem; font-weight: 600; color: #1e293b; }
    .lawatan-stop-badge { margin: 8px 0 0 32px; }
    @media (max-width: 767.98px) {
        .lawatan-agenda { grid-template-columns: 1fr; }
        .lawatan-agenda-date { border-right: none; border-bottom: 1px solid #f1f5f9; }
    }
</style>
@endsection

@section('content')

<div class="mb-3 d-flex justify-content-between align-items-center gap-2 flex-wrap">
    <a href="{{ route('lawatanTapakUrusetia') }}" class="btn-form btn-form-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        Kembali
    </a>
    <a href="{{ route('pengesahanLawatanTapak', $tender->id) }}" class="btn-form btn-form-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 20h9"></path>
            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
        </svg>
        Kemaskini Kehadiran
    </a>
</div>

<div class="tender-header-card pb-4 mb-4">
    <div class="tender-page-header">
        <div class="tender-ref-label">
            <span class="tender-type-label">{{ App\Tender::$types[$tender->type] ?? 'Tender' }}</span>
            <span class="tender-ref-sep">·</span>
            <span class="tender-ref-no">{{ $tender->ref_number ?: $tender->no_tender ?: $tender->id }}</span>
        </div>
        <h2 class="tender-title-main">{{ $tender->name }}</h2>
        <div class="text-muted small mt-1">{{ $tender->tenderer?->name ?? '-' }}</div>
    </div>
</div>

<div class="content-card p-0 mb-4">
    <div class="content-card-header">
        <div class="d-flex align-items-center gap-3">
            <div class="content-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 11l3 3L22 4"></path>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                </svg>
            </div>
            <div>
                <h3 class="content-card-title" style="font-size: 1rem;">Ringkasan Kehadiran Lawatan Tapak</h3>
                <div class="text-muted small">Paparan wakil yang telah didaftarkan / disahkan hadir.</div>
            </div>
        </div>
    </div>

    <div class="tender-top-tabs lawatan-tabs">
        <ul class="nav nav-tabs" role="tablist">
            @foreach ($visitTabs as $i => $tab)
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $i === 0 ? 'active' : '' }}" data-bs-toggle="tab" href="#ringkasan-{{ $tab['visit']->id }}" role="tab">
                        <span class="lawatan-tab-title">{{ $tab['label'] }}</span>
                        <span class="lawatan-tab-sub">
                            {{ \Carbon\Carbon::parse($tab['visit']->datetime)->locale('ms')->translatedFormat('j M Y') }} ·
                            {{ $tab['attended'] }}/{{ $tab['total'] }} hadir
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="tab-content">
        @foreach ($visitTabs as $i => $tab)
            @php
                $visit = $tab['visit'];
                $when = \Carbon\Carbon::parse($visit->datetime)->locale('ms');
            @endphp
            <div class="tab-pane {{ $i === 0 ? 'active' : '' }}" id="ringkasan-{{ $visit->id }}" role="tabpanel">
                <div class="lawatan-agenda">
                    <div class="lawatan-agenda-date">
                        <div class="lawatan-agenda-dow">{{ $when->translatedFormat('l') }}</div>
                        <div class="lawatan-agenda-day">{{ $when->format('j') }}</div>
                        <div class="lawatan-agenda-my">{{ $when->translatedFormat('M Y') }}</div>
                        <div class="lawatan-agenda-time">{{ $when->translatedFormat('g:i a') }}</div>
                    </div>
                    <div class="lawatan-agenda-body">
                        <div class="lawatan-stop {{ $visit->meetpoint ? 'has-next' : '' }}">
                            <div class="lawatan-stop-mark">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            </div>
                            <div>
                                <div class="lawatan-stop-label">Alamat lawatan</div>
                                <div class="lawatan-stop-place">{!! $visit->address ? nl2br(e($visit->address)) : 'Alamat lawatan tidak dinyatakan' !!}</div>
                            </div>
                        </div>
                        @if ($visit->meetpoint)
                            <div class="lawatan-stop">
                                <div class="lawatan-stop-mark">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg>
                                </div>
                                <div>
                                    <div class="lawatan-stop-label">Tempat berkumpul</div>
                                    <div class="lawatan-stop-meet">{{ $visit->meetpoint }}</div>
                                </div>
                            </div>
                        @endif
                        <div class="lawatan-stop-badge">
                            @if ($visit->required)
                                <span class="badge-status badge-status-info">Wajib hadir</span>
                            @else
                                <span class="badge-status badge-status-neutral">Tidak wajib hadir</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 w-100">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-center text-muted small fw-bold py-3 ps-4" width="60px">Bil</th>
                                <th class="text-uppercase text-muted small fw-bold py-3">ROC Syarikat</th>
                                <th class="text-uppercase text-muted small fw-bold py-3">Nama Syarikat</th>
                                <th class="text-uppercase text-muted small fw-bold py-3">No. IC</th>
                                <th class="text-uppercase text-muted small fw-bold py-3">Nama Wakil</th>
                                <th class="text-uppercase text-center text-muted small fw-bold py-3 pe-4">Status Hadir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tab['rows'] as $row)
                                <tr>
                                    <td class="text-center ps-4 text-muted">{{ $loop->iteration }}</td>
                                    <td>{{ $row['vendor']->registration ?? '-' }}</td>
                                    <td class="fw-semibold">{{ $row['vendor']->name ?? '-' }}</td>
                                    <td>{{ $row['rep']?->ic_no ?: '—' }}</td>
                                    <td>{{ $row['rep']?->name ?: '—' }}</td>
                                    <td class="text-center pe-4">
                                        @if ($row['rep']?->attended || $row['attended'])
                                            <span class="badge-status badge-status-success">Hadir</span>
                                        @else
                                            <span class="badge-status badge-status-neutral">Belum Hadir</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Tiada rekod kehadiran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection
