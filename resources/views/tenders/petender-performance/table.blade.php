<div class="vendor-tender-card">
    <div class="vendor-tender-card-header">
        <div class="header-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 12h3v8h-3zM9 5h3v15h-3zM15 8h3v12h-3z"/>
            </svg>
        </div>
        <h6>Senarai Prestasi Syarikat</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead style="background:#f8fafc;">
                <tr>
                    <th style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-color:#e5e7eb;padding:10px 20px;width:50px;">Bil.</th>
                    <th style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-color:#e5e7eb;padding:10px 20px;">Tarikh</th>
                    <th style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-color:#e5e7eb;padding:10px 20px;">Penilai</th>
                    <th style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-color:#e5e7eb;padding:10px 20px;">Keseluruhan Markah</th>
                    <th style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#6b7280;border-color:#e5e7eb;padding:10px 20px;width:100px;text-align:center;">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tender->petenderPerformances as $petenderPerformance)
                    @php
                        $score = $petenderPerformance->total_score;
                        if ($score > 80)      { $sc = '#166534'; }
                        elseif ($score >= 50) { $sc = '#92400e'; }
                        else                  { $sc = '#991b1b'; }
                    @endphp
                    <tr style="border-color:#f1f5f9;">
                        <td style="font-size:0.78rem;color:#9ca3af;padding:12px 20px;border-color:#f1f5f9;">{{ $loop->index + 1 }}.</td>
                        <td style="font-size:0.82rem;color:#374151;padding:12px 20px;border-color:#f1f5f9;">
                            {{ \Carbon\Carbon::parse($petenderPerformance->acquisition_date)->format('j M Y') }}
                        </td>
                        <td style="padding:12px 20px;border-color:#f1f5f9;">
                            <div style="font-size:0.82rem;font-weight:600;color:#1f2937;">{{ optional($petenderPerformance->user)->name ?? '-' }}</div>
                            <div style="font-size:0.72rem;color:#6b7280;">{{ optional(optional($petenderPerformance->user)->agency)->name ?? '-' }}</div>
                        </td>
                        <td style="padding:12px 20px;border-color:#f1f5f9;">
                            <div style="display:inline-flex;align-items:baseline;gap:2px;">
                                <span style="font-size:1rem;font-weight:800;color:{{ $sc }};line-height:1;">{{ number_format($score, 1) }}</span>
                                <span style="font-size:0.65rem;font-weight:700;color:{{ $sc }};">%</span>
                            </div>
                        </td>
                        <td style="padding:12px 20px;border-color:#f1f5f9;text-align:center;">
                            <button type="button"
                                style="display:inline-flex;align-items:center;gap:5px;font-size:0.72rem;font-weight:600;color:#0369a1;padding:4px 12px;background:#f0f9ff;border-radius:5px;border:1px solid #bae6fd;cursor:pointer;"
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
                        <td colspan="5" class="text-center py-4 text-muted" style="font-size:0.82rem;">Tiada rekod penilaian.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
