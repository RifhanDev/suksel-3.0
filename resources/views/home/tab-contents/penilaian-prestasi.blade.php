<style>
    .prestasi-accordion-btn {
        display: flex;
        align-items: center;
        gap: 14px;
        width: 100%;
        background: #fff;
        border: none;
        padding: 14px 16px;
        cursor: pointer;
        text-align: left;
        transition: background 0.15s;
    }
    .prestasi-accordion-btn:hover { background: #fafafa; }
    .prestasi-accordion-btn.collapsed .prestasi-chevron { transform: rotate(0deg); }
    .prestasi-accordion-btn .prestasi-chevron { transform: rotate(180deg); transition: transform 0.2s; }
    .prestasi-index {
        flex-shrink: 0;
        width: 28px; height: 28px;
        background: rgba(196,30,58,0.08);
        color: #c41e3a;
        border-radius: 50%;
        font-size: 0.72rem;
        font-weight: 800;
        display: flex; align-items: center; justify-content: center;
    }
    .prestasi-info { flex: 1; min-width: 0; }
    .prestasi-ref { font-size: 0.82rem; font-weight: 700; color: #111827; }
    .prestasi-name { font-size: 0.72rem; color: #6b7280; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .prestasi-badge-ok  { display:inline-flex; align-items:center; gap:4px; font-size:0.68rem; font-weight:700; padding:3px 10px; border-radius:20px; background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; white-space:nowrap; }
    .prestasi-badge-nil { display:inline-flex; align-items:center; gap:4px; font-size:0.68rem; font-weight:700; padding:3px 10px; border-radius:20px; background:#fef2f2; color:#991b1b; border:1px solid #fecaca; white-space:nowrap; }

    .prestasi-table thead th {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
        background: #f8fafc;
        border-color: #e5e7eb;
        padding: 10px 14px;
    }
    .prestasi-table tbody td {
        font-size: 0.82rem;
        color: #374151;
        border-color: #e5e7eb;
        padding: 10px 14px;
        vertical-align: middle;
    }
    .prestasi-score-pill {
        display: inline-block;
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }
    .prestasi-papar-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.72rem;
        font-weight: 600;
        color: #0369a1;
        text-decoration: none;
        padding: 4px 12px;
        background: #f0f9ff;
        border-radius: 5px;
        border: 1px solid #bae6fd;
        cursor: pointer;
    }
    .prestasi-papar-btn:hover { background: #e0f2fe; }
</style>

<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="fw-bold text-dark" style="font-size:0.9rem;">Senarai Tender Syarikat</div>
            <div class="text-muted" style="font-size:0.72rem;">Rekod penilaian prestasi bagi tender yang telah dimenangi.</div>
        </div>
    </div>

    <div class="accordion d-flex flex-column gap-2" id="accordionPrestasi">
        @forelse (Auth::user()->vendor->winningParticipations->sortByDesc('created_at') as $participation)
            @php $count = $participation->tender->petenderPerformances->count(); @endphp
            <div style="border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; background:#fff; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">

                {{-- Accordion header --}}
                <h2 class="m-0">
                    <button class="prestasi-accordion-btn collapsed" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse{{ $loop->index }}"
                        aria-expanded="false"
                        aria-controls="collapse{{ $loop->index }}">
                        <div class="prestasi-index">{{ $loop->index + 1 }}</div>
                        <div class="prestasi-info">
                            <div class="prestasi-ref">{{ $participation->tender->ref_number }}</div>
                            <div class="prestasi-name">{{ $participation->tender->name }}</div>
                        </div>
                        <div style="text-align:right; flex-shrink:0;">
                            @if ($count > 0)
                                <div style="font-size:0.72rem; color:#6b7280;">Rekod Penilaian</div>
                                <div style="font-size:1rem; font-weight:800; color:#166534; line-height:1.2;">{{ $count }}</div>
                            @else
                                <div style="font-size:0.72rem; color:#9ca3af; font-style:italic;">Tiada penilaian</div>
                            @endif
                        </div>
                        <svg class="prestasi-chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                </h2>

                {{-- Accordion body --}}
                <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse" data-bs-parent="#accordionPrestasi">
                    <div style="border-top: 1px solid #f3f4f6;">
                        <div class="table-responsive">
                            <table class="prestasi-table table table-hover align-middle mb-0 w-100">
                                <thead>
                                    <tr>
                                        <th style="width:50px;">Bil.</th>
                                        <th>Tarikh Penilaian</th>
                                        <th class="text-center">Jumlah Markah</th>
                                        <th class="text-center" style="width:110px;">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($participation->tender->petenderPerformances as $petenderPerformance)
                                        <tr>
                                            <td class="text-muted">{{ $loop->index + 1 }}.</td>
                                            <td>
                                                <div class="fw-semibold" style="font-size:0.82rem; color:#1f2937;">
                                                    {{ \Carbon\Carbon::parse($petenderPerformance->acquisition_date)->format('j M Y') }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @php $score = $petenderPerformance->total_score; @endphp
                                                @if ($score > 80)
                                                    @php $scoreColor = '#166534'; $dotColor = '#22c55e'; $scoreLabel = 'Baik'; @endphp
                                                @elseif ($score >= 50)
                                                    @php $scoreColor = '#92400e'; $dotColor = '#f59e0b'; $scoreLabel = 'Sederhana'; @endphp
                                                @else
                                                    @php $scoreColor = '#991b1b'; $dotColor = '#ef4444'; $scoreLabel = 'Lemah'; @endphp
                                                @endif
                                                <div style="display:inline-flex; align-items:baseline; gap:3px;">
                                                    <span style="font-size:1.05rem; font-weight:800; color:{{ $scoreColor }}; line-height:1;">{{ number_format($score, 1) }}</span>
                                                    <span style="font-size:0.65rem; font-weight:700; color:{{ $scoreColor }};">%</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="prestasi-papar-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal{{ $petenderPerformance->id }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                                    Papar
                                                </button>
                                                @include('tenders.petender-performance.modal.petender-performance')
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted" style="font-size:0.82rem;">
                                                Tiada rekod penilaian untuk tender ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        @empty
            <div class="d-flex align-items-center gap-3 p-4 rounded-2" style="background:#f0f9ff; border:1px solid #bae6fd;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0369a1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span style="font-size:0.82rem; color:#0c4a6e; font-weight:600;">Tiada rekod tender yang dimenangi.</span>
            </div>
        @endforelse
    </div>
</div>
