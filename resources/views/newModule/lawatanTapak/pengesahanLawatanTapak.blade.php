@extends('layouts.v3.master')

@section('styles')
<link href="{{ asset('css/components/tender-show.css') }}" rel="stylesheet">
<style>
    .lawatan-tabs .nav-tabs { border-bottom: 1px solid #f1f5f9; padding: 0 1rem; overflow-y: hidden; }
    .lawatan-tabs .nav-tabs .nav-link { flex-direction: column; align-items: flex-start; gap: 2px; padding: 0.85rem 1.25rem; }
    .lawatan-tab-title { font-size: 0.88rem; }
    .lawatan-tab-sub { font-size: 0.72rem; font-weight: 500; color: #94a3b8; }
    .lawatan-tabs .nav-link.active .lawatan-tab-sub { color: #c41e3a; opacity: 0.75; }

    .lawatan-agenda { display: grid; grid-template-columns: 132px minmax(0, 1fr); }
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

    .lawatan-hadir { width: 1.2rem; height: 1.2rem; cursor: pointer; }
    .lawatan-total { font-size: 1.05rem; font-weight: 700; color: #1e293b; }
    .lawatan-list-pane tfoot td { border-top: 2px solid #0f172a; border-bottom: 2px solid #0f172a; }
    .lawatan-total.is-complete { color: #15803d; }

    .lawatan-tambah-label { font-size: 0.72rem; font-weight: 700; letter-spacing: 0.4px; text-transform: uppercase; color: #64748b; margin-bottom: 6px; }
    .lawatan-tambah-switch .form-check-input { width: 2.2em; height: 1.2em; cursor: pointer; }
    .lawatan-tambah-switch .form-check-input:checked { background-color: #c41e3a; border-color: #c41e3a; }
</style>
@endsection

@section('content')

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <i class="ti ti-alert-triangle"></i> {{ $errors->first() }}
    </div>
@endif

<div class="mb-3">
    <a href="{{ route('lawatanTapakUrusetia') }}" class="btn-form btn-form-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        Kembali
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

{{-- Card 1: visits (tabs + details) --}}
<div class="content-card p-0 mb-4">
    <div class="content-card-header">
        <div class="d-flex align-items-center gap-3">
            <div class="content-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
            </div>
            <div>
                <h3 class="content-card-title" style="font-size: 1rem;">Pengesahan Kehadiran Lawatan Tapak</h3>
                <div class="text-muted small">Tandakan hadir bagi wakil syarikat yang menghadiri lawatan tapak.</div>
            </div>
        </div>
    </div>

    <div class="tender-top-tabs lawatan-tabs">
        <ul class="nav nav-tabs" role="tablist">
            @foreach ($visitTabs as $i => $tab)
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $i === 0 ? 'active' : '' }}" data-bs-toggle="tab" href="#lawatan-{{ $tab['visit']->id }}" role="tab"
                        data-visit-id="{{ $tab['visit']->id }}" data-list-subtitle="{{ $tab['label'] }} · {{ $tab['total'] }} syarikat">
                        <span class="lawatan-tab-title">{{ $tab['label'] }}</span>
                        <span class="lawatan-tab-sub">
                            {{ \Carbon\Carbon::parse($tab['visit']->datetime)->locale('ms')->translatedFormat('j M Y') }} ·
                            <span data-count-for="{{ $tab['visit']->id }}">{{ $tab['attended'] }}/{{ $tab['total'] }} hadir</span>
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
            <div class="tab-pane {{ $i === 0 ? 'active' : '' }}" id="lawatan-{{ $visit->id }}" role="tabpanel">
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
            </div>
        @endforeach
    </div>
</div>

{{-- Card 2: company list for the selected visit --}}
<form method="post" action="{{ route('pengesahanLawatanTapak.update', $tender->id) }}" id="formPengesahanLawatan">
    @csrf
    <div class="content-card p-0 mb-4">
        <div class="content-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="content-card-title" style="font-size: 1rem;">Senarai Syarikat</h3>
                    <div class="text-muted small" data-list-subtitle-target>
                        {{ $visitTabs->first() ? $visitTabs->first()['label'] . ' · ' . $visitTabs->first()['total'] . ' syarikat' : '' }}
                    </div>
                </div>
            </div>
        </div>

        @foreach ($visitTabs as $i => $tab)
            <div class="lawatan-list-pane {{ $i === 0 ? '' : 'd-none' }}" data-visit-list="{{ $tab['visit']->id }}">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 w-100">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-muted small fw-bold py-3 ps-4" width="60px">No.</th>
                                <th class="text-uppercase text-muted small fw-bold py-3" width="160px">No. ROC</th>
                                <th class="text-uppercase text-muted small fw-bold py-3">Syarikat</th>
                                <th class="text-uppercase text-muted small fw-bold py-3" width="200px">No. IC Wakil</th>
                                <th class="text-uppercase text-muted small fw-bold py-3" width="260px">Nama Wakil</th>
                                <th class="text-uppercase text-center text-muted small fw-bold py-3 pe-4" width="90px">Hadir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tab['rows'] as $idx => $row)
                                <tr>
                                    <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                                    <td>{{ $row['vendor']->registration ?? '-' }}</td>
                                    <td class="fw-semibold">{{ $row['vendor']->name ?? '-' }}</td>
                                    <td>
                                        <input type="hidden" name="rows[{{ $idx }}][visit_id]" value="{{ $row['visit']->id }}">
                                        <input type="hidden" name="rows[{{ $idx }}][vendor_id]" value="{{ $row['vendor']->id }}">
                                        @if ($row['rep'])
                                            <input type="hidden" name="rows[{{ $idx }}][rep_id]" value="{{ $row['rep']->id }}">
                                        @endif
                                        <input type="text" name="rows[{{ $idx }}][ic_no]" class="form-control form-control-sm"
                                            value="{{ $row['rep']?->ic_no }}" placeholder="12 digit"
                                            data-ic-input maxlength="12" inputmode="numeric"
                                            title="Masukkan 12 digit No. IC (sempang akan dibuang automatik)">
                                    </td>
                                    <td>
                                        <input type="text" name="rows[{{ $idx }}][name]" class="form-control form-control-sm"
                                            value="{{ $row['rep']?->name }}" placeholder="Nama wakil" maxlength="255">
                                    </td>
                                    <td class="text-center pe-4">
                                        <input type="hidden" name="rows[{{ $idx }}][attended]" value="0">
                                        <input type="checkbox" name="rows[{{ $idx }}][attended]" value="1" class="form-check-input lawatan-hadir"
                                            data-vendor="{{ $row['vendor']->id }}" @checked($row['attended'])>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Tiada syarikat untuk {{ $tab['label'] }} lagi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($tab['total'] > 0)
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-center fw-bold py-3">Jumlah hadir {{ $tab['label'] }}</td>
                                    <td class="text-center text-nowrap pe-4 py-3">
                                        <span class="lawatan-total {{ $tab['attended'] === $tab['total'] ? 'is-complete' : '' }}" data-total-for="{{ $tab['visit']->id }}" data-total="{{ $tab['total'] }}">{{ $tab['attended'] }} / {{ $tab['total'] }}</span>
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        @endforeach

        @if (count($attendanceRows) > 0)
            <div class="d-flex justify-content-end gap-2 px-4 py-3">
                <a href="{{ route('kelulusanLawatanTapak', $tender->id) }}" class="btn-form btn-form-secondary">Ringkasan Kelulusan</a>
                <button type="submit" class="btn-form btn-form-primary">Simpan</button>
            </div>
        @endif
    </div>
</form>

<div class="content-card p-0 mb-4">
    <div class="content-card-header">
        <div class="d-flex align-items-center gap-3">
            <div class="content-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M19 8v6M22 11h-6"></path>
                </svg>
            </div>
            <div>
                <h3 class="content-card-title" style="font-size: 1rem;">Tambah Syarikat / Wakil</h3>
                <div class="text-muted small">Untuk syarikat yang hadir tetapi belum mendaftar wakil melalui portal vendor.</div>
            </div>
        </div>
    </div>
    <form method="post" action="{{ route('pengesahanLawatanTapak.update', $tender->id) }}" class="p-4">
        @csrf
        <div class="row g-3">
            <div class="col-12 col-lg-4">
                <label for="tambahLawatan" class="lawatan-tambah-label d-block">Lawatan tapak <span class="text-danger">*</span></label>
                <select id="tambahLawatan" name="rows[999][visit_id]" class="form-select" required>
                    @foreach ($visitTabs as $tab)
                        <option value="{{ $tab['visit']->id }}">{{ $tab['label'] }} · {{ \Carbon\Carbon::parse($tab['visit']->datetime)->locale('ms')->translatedFormat('j M Y, g:i a') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-lg-3">
                <label for="tambahRoc" class="lawatan-tambah-label d-block">No. ROC syarikat <span class="text-danger">*</span></label>
                <input type="text" id="tambahRoc" name="rows[999][vendor_registration]" class="form-control" placeholder="Contoh: 123456-A" required>
            </div>
            <div class="col-12 col-lg-2">
                <label for="tambahIc" class="lawatan-tambah-label d-block">No. IC wakil <span class="text-danger">*</span></label>
                <input type="text" id="tambahIc" name="rows[999][ic_no]" class="form-control" placeholder="12 digit"
                    data-ic-input maxlength="12" inputmode="numeric" pattern="\d{12}" required
                    title="Masukkan 12 digit No. IC (sempang akan dibuang automatik)">
            </div>
            <div class="col-12 col-lg-3">
                <label for="tambahNama" class="lawatan-tambah-label d-block">Nama wakil <span class="text-danger">*</span></label>
                <input type="text" id="tambahNama" name="rows[999][name]" class="form-control" placeholder="Nama penuh wakil" maxlength="255" required>
            </div>
        </div>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-4 pt-3 border-top">
            <div class="form-check form-switch lawatan-tambah-switch m-0">
                <input type="hidden" name="rows[999][attended]" value="0">
                <input type="checkbox" class="form-check-input" id="tambahHadir" name="rows[999][attended]" value="1" checked>
                <label class="form-check-label ms-1" for="tambahHadir">Sahkan hadir lawatan ini</label>
            </div>
            <button type="submit" class="btn-form btn-form-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"></path></svg>
                Tambah Syarikat
            </button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
    (function () {
        var tabKey = 'lawatan-tab-{{ $tender->id }}';
        var subtitle = document.querySelector('[data-list-subtitle-target]');

        // A visit tab also switches the company list card to that visit
        document.querySelectorAll('.lawatan-tabs a[data-bs-toggle="tab"]').forEach(function (link) {
            link.addEventListener('shown.bs.tab', function () {
                document.querySelectorAll('.lawatan-list-pane').forEach(function (pane) {
                    pane.classList.toggle('d-none', pane.dataset.visitList !== link.dataset.visitId);
                });
                if (subtitle) subtitle.textContent = link.dataset.listSubtitle;
                try { sessionStorage.setItem(tabKey, link.getAttribute('href')); } catch (e) {}
            });
        });

        // Reopen the visit tab that was open before Simpan
        try {
            var saved = sessionStorage.getItem(tabKey);
            var savedLink = saved && document.querySelector('.lawatan-tabs a[href="' + saved + '"]');
            if (savedLink && window.bootstrap) bootstrap.Tab.getOrCreateInstance(savedLink).show();
        } catch (e) {}

        // Live "x / y hadir" while ticking
        document.querySelectorAll('.lawatan-list-pane').forEach(function (pane) {
            var visitId = pane.dataset.visitList;
            var total = pane.querySelector('[data-total-for="' + visitId + '"]');
            var tabCount = document.querySelector('[data-count-for="' + visitId + '"]');
            pane.addEventListener('change', function (e) {
                if (!e.target.classList.contains('lawatan-hadir') || !total) return;
                var attended = {};
                pane.querySelectorAll('.lawatan-hadir[data-vendor]:checked').forEach(function (box) { attended[box.dataset.vendor] = true; });
                var count = Object.keys(attended).length;
                total.textContent = count + ' / ' + total.dataset.total;
                total.classList.toggle('is-complete', count === Number(total.dataset.total));
                if (tabCount) tabCount.textContent = count + '/' + total.dataset.total + ' hadir';
            });
        });
    })();
</script>
@endsection
