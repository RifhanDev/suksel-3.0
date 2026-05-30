<div class="modal fade modal-tender" id="exampleModal{{ $petenderPerformance->id }}" tabindex="-1"
    aria-labelledby="exampleModalLabel{{ $petenderPerformance->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius:12px;overflow:hidden;">

            {{-- Header --}}
            <div class="modal-header border-0 px-4 py-3" style="background:linear-gradient(135deg,#c41e3a,#9b1830);">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-2 flex-shrink-0"
                        style="width:36px;height:36px;background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.2);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 12h3v8h-3zM9 5h3v15h-3zM15 8h3v12h-3z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="fw-bold text-white" style="font-size:0.95rem;">Penilaian Prestasi</div>
                        <div style="font-size:0.7rem;color:rgba(255,255,255,0.65);">{{ $petenderPerformance->tender->ref_number }}</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0" style="background:#f8fafc;">

                {{-- ── MAKLUMAT PEMBEKAL ── --}}
                <div class="mx-3 mt-3">
                    <div class="px-3 py-2 d-flex align-items-center gap-2" style="background:#334155;border-radius:8px 8px 0 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span style="font-size:0.63rem;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;color:#e2e8f0;">Maklumat Pembekal</span>
                    </div>
                    <div style="background:#fff;border:1px solid #e2e8f0;border-top:none;border-radius:0 0 8px 8px;overflow:hidden;">

                        <div class="row g-0" style="border-bottom:1px solid #f1f5f9;">
                            <div class="col-md-6 px-3 py-3" style="border-right:1px solid #f1f5f9;">
                                <div style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;margin-bottom:5px;">1. Nama Pembekal</div>
                                <div style="font-size:0.88rem;font-weight:600;color:#1e293b;">{{ $petenderPerformance->vendor->name }}</div>
                                <input type="hidden" value="{{ $petenderPerformance->vendor->name }}" />
                            </div>
                            <div class="col-md-6 px-3 py-3">
                                <div style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;margin-bottom:5px;">2. Jenis Bekalan</div>
                                <div class="text-uppercase" style="font-size:0.88rem;font-weight:600;color:#1e293b;">{{ $petenderPerformance->type }}</div>
                            </div>
                        </div>

                        <div class="px-3 py-3" style="border-bottom:1px solid #f1f5f9;background:#f8fafc;">
                            <div style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;margin-bottom:5px;">3. Alamat Pembekal</div>
                            <div style="font-size:0.85rem;color:#475569;line-height:1.65;">{{ $petenderPerformance->vendor->address }}</div>
                            <textarea class="d-none" readonly>{{ $petenderPerformance->vendor->address }}</textarea>
                        </div>

                        <div class="px-3 py-3" style="border-bottom:1px solid #f1f5f9;">
                            <div style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;margin-bottom:5px;">4. Nama Perolehan</div>
                            <div style="font-size:0.88rem;font-weight:600;color:#1e293b;">{{ $petenderPerformance->tender->ref_number }}</div>
                            <input name="nama_perolehan" type="hidden" value="{{ $petenderPerformance->tender->ref_number }}" />
                        </div>

                        <div class="row g-0">
                            <div class="col-md-4 px-3 py-3" style="border-right:1px solid #f1f5f9;">
                                <div style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;margin-bottom:5px;">5. Kuantiti</div>
                                <div style="font-size:0.88rem;font-weight:600;color:#1e293b;">{{ $petenderPerformance->quantity }}</div>
                                <input name="quantity" type="hidden" value="{{ $petenderPerformance->quantity }}" />
                            </div>
                            <div class="col-md-4 px-3 py-3" style="border-right:1px solid #f1f5f9;">
                                <div style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;margin-bottom:5px;">6. Jumlah Kos (RM)</div>
                                <div style="font-size:0.88rem;font-weight:600;color:#1e293b;">{{ $petenderPerformance->cost }}</div>
                                <input name="cost" type="hidden" value="{{ $petenderPerformance->cost }}" />
                            </div>
                            <div class="col-md-4 px-3 py-3">
                                <div style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#94a3b8;margin-bottom:5px;">7. Tarikh Perolehan</div>
                                <div style="font-size:0.88rem;font-weight:600;color:#1e293b;">{{ $petenderPerformance->acquisition_date }}</div>
                                <input name="acquisition_date" type="hidden" value="{{ $petenderPerformance->acquisition_date }}" />
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ── KRITERIA PENILAIAN ── --}}
                <div class="mx-3 mt-3">
                    <div class="px-3 py-2 d-flex align-items-center gap-2" style="background:#334155;border-radius:8px 8px 0 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                        <span style="font-size:0.63rem;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;color:#e2e8f0;">Kriteria Penilaian</span>
                    </div>
                    <div style="background:#fff;border:1px solid #e2e8f0;border-top:none;border-radius:0 0 8px 8px;overflow:hidden;">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background:#f1f5f9;">
                                <tr>
                                    <th class="text-center" style="font-size:0.62rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;border-color:#e2e8f0;padding:10px 14px;width:40px;">Bil.</th>
                                    <th style="font-size:0.62rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;border-color:#e2e8f0;padding:10px 14px;">Kriteria Penilaian</th>
                                    <th class="text-center" style="font-size:0.62rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;border-color:#e2e8f0;padding:10px 14px;width:80px;">Skala</th>
                                    <th style="font-size:0.62rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;border-color:#e2e8f0;padding:10px 14px;width:35%;">Ulasan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="border-color:#f1f5f9;"><td class="text-center" style="font-size:0.78rem;color:#94a3b8;padding:10px 14px;border-color:#f1f5f9;">1.</td><td style="font-size:0.82rem;color:#334155;padding:10px 14px;border-color:#f1f5f9;">Tindakan terhadap maklum balas / permintaan</td><td class="text-center fw-bold" style="color:#1e293b;padding:10px 14px;border-color:#f1f5f9;">{{ $petenderPerformance->performanceCriteria->scale_1 ?? '' }} / 5</td><td style="font-size:0.78rem;color:#64748b;padding:10px 14px;border-color:#f1f5f9;">{{ $petenderPerformance->performanceCriteria->review_1 ?? '-' }}</td></tr>
                                <tr style="border-color:#f1f5f9;"><td class="text-center" style="font-size:0.78rem;color:#94a3b8;padding:10px 14px;border-color:#f1f5f9;">2.</td><td style="font-size:0.82rem;color:#334155;padding:10px 14px;border-color:#f1f5f9;">Harga yang berpatutan</td><td class="text-center fw-bold" style="color:#1e293b;padding:10px 14px;border-color:#f1f5f9;">{{ $petenderPerformance->performanceCriteria->scale_2 ?? '' }} / 5</td><td style="font-size:0.78rem;color:#64748b;padding:10px 14px;border-color:#f1f5f9;">{{ $petenderPerformance->performanceCriteria->review_2 ?? '-' }}</td></tr>
                                <tr style="border-color:#f1f5f9;"><td class="text-center" style="font-size:0.78rem;color:#94a3b8;padding:10px 14px;border-color:#f1f5f9;">3.</td><td style="font-size:0.82rem;color:#334155;padding:10px 14px;border-color:#f1f5f9;">Kuantiti menepati Pesanan Tempatan (LO)</td><td class="text-center fw-bold" style="color:#1e293b;padding:10px 14px;border-color:#f1f5f9;">{{ $petenderPerformance->performanceCriteria->scale_3 ?? '' }} / 5</td><td style="font-size:0.78rem;color:#64748b;padding:10px 14px;border-color:#f1f5f9;">{{ $petenderPerformance->performanceCriteria->review_3 ?? '-' }}</td></tr>
                                <tr style="border-color:#f1f5f9;"><td class="text-center" style="font-size:0.78rem;color:#94a3b8;padding:10px 14px;border-color:#f1f5f9;">4.</td><td style="font-size:0.82rem;color:#334155;padding:10px 14px;border-color:#f1f5f9;">Kualiti produk / perkhidmatan /kerja</td><td class="text-center fw-bold" style="color:#1e293b;padding:10px 14px;border-color:#f1f5f9;">{{ $petenderPerformance->performanceCriteria->scale_4 ?? '' }} / 5</td><td style="font-size:0.78rem;color:#64748b;padding:10px 14px;border-color:#f1f5f9;">{{ $petenderPerformance->performanceCriteria->review_4 ?? '-' }}</td></tr>
                                <tr style="border-color:#f1f5f9;"><td class="text-center" style="font-size:0.78rem;color:#94a3b8;padding:10px 14px;border-color:#f1f5f9;">5.</td><td style="font-size:0.82rem;color:#334155;padding:10px 14px;border-color:#f1f5f9;">Penghantaran mengikut jadual</td><td class="text-center fw-bold" style="color:#1e293b;padding:10px 14px;border-color:#f1f5f9;">{{ $petenderPerformance->performanceCriteria->scale_5 ?? '' }} / 5</td><td style="font-size:0.78rem;color:#64748b;padding:10px 14px;border-color:#f1f5f9;">{{ $petenderPerformance->performanceCriteria->review_5 ?? '-' }}</td></tr>
                                <tr style="border-color:#f1f5f9;"><td class="text-center" style="font-size:0.78rem;color:#94a3b8;padding:10px 14px;border-color:#f1f5f9;">6.</td><td style="font-size:0.82rem;color:#334155;padding:10px 14px;border-color:#f1f5f9;">Kerjasama yang diberikan</td><td class="text-center fw-bold" style="color:#1e293b;padding:10px 14px;border-color:#f1f5f9;">{{ $petenderPerformance->performanceCriteria->scale_6 ?? '' }} / 5</td><td style="font-size:0.78rem;color:#64748b;padding:10px 14px;border-color:#f1f5f9;">{{ $petenderPerformance->performanceCriteria->review_6 ?? '-' }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ── JUMLAH MARKAH ── --}}
                <div class="mx-3 mt-3">
                    <div class="px-3 py-2 d-flex align-items-center gap-2" style="background:#334155;border-radius:8px 8px 0 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span style="font-size:0.63rem;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;color:#e2e8f0;">Jumlah Markah</span>
                    </div>
                    <div class="px-3 py-3 d-flex align-items-center gap-2" style="background:#fff;border:1px solid #e2e8f0;border-top:none;border-radius:0 0 8px 8px;">
                        <span style="font-size:0.85rem;font-weight:500;color:#64748b;">Jumlah Markah :</span>
                        <input id='calc' name="total_score"
                            value="{{ number_format($petenderPerformance->total_score, 2) }} %"
                            readonly class="border-0 fw-bold text-center bg-transparent"
                            style="width:80px;font-size:1rem;" />
                    </div>
                </div>

                {{-- ── CADANGAN PEGAWAI PENILAIAN ── --}}
                @php
                    if ($petenderPerformance->opinion == 'is_listed') {
                        $opHdr = '#166534'; $opBg = '#f0fdf4'; $opBorder = '#86efac'; $opColor = '#14532d';
                    } elseif ($petenderPerformance->opinion == 'is_conditional') {
                        $opHdr = '#b45309'; $opBg = '#fffbeb'; $opBorder = '#fcd34d'; $opColor = '#78350f';
                    } else {
                        $opHdr = '#b91c1c'; $opBg = '#fef2f2'; $opBorder = '#fca5a5'; $opColor = '#7f1d1d';
                    }
                @endphp
                <div class="mx-3 mt-3">
                    <div class="px-3 py-2 d-flex align-items-center gap-2" style="background:{{ $opHdr }};border-radius:8px 8px 0 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.7)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1-3 3h-5l-5 3v-3h-2a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h12M12 8v3M12 14v.01"/></svg>
                        <span style="font-size:0.63rem;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;color:rgba(255,255,255,0.9);">Cadangan Pegawai Penilaian</span>
                    </div>
                    <div class="px-3 py-3" style="background:{{ $opBg }};border:1px solid {{ $opBorder }};border-top:none;border-radius:0 0 8px 8px;">
                        @if ($petenderPerformance->opinion == 'is_listed')
                            <div style="font-size:0.88rem;font-weight:600;color:{{ $opColor }};">Kekalkan dalam Senarai Pembekal (> 80%)</div>
                        @elseif ($petenderPerformance->opinion == 'is_conditional')
                            <div style="font-size:0.88rem;font-weight:600;color:{{ $opColor }};">Kekalkan dalam Senarai Pembekal dengan bersyarat (50% - 79%)</div>
                        @else
                            <div style="font-size:0.88rem;font-weight:600;color:{{ $opColor }};">Kekalkan dalam Senarai Pembekal (< 50%)</div>
                        @endif
                    </div>
                </div>

                {{-- ── ULASAN ── --}}
                <div class="mx-3 mt-3 mb-3">
                    <div class="px-3 py-2 d-flex align-items-center gap-2" style="background:#334155;border-radius:8px 8px 0 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5M9 7h6M9 11h6M9 15h4"/></svg>
                        <span style="font-size:0.63rem;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;color:#e2e8f0;">Ulasan</span>
                    </div>
                    <div style="background:#fff;border:1px solid #e2e8f0;border-top:none;border-radius:0 0 8px 8px;padding:12px 14px;">
                        <textarea name="review" rows="3"
                            style="width:100%;resize:none;font-size:0.85rem;color:#334155;background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;padding:10px 12px;line-height:1.7;" readonly>{{ $petenderPerformance->overall_review }}</textarea>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="modal-footer py-2 px-4" style="background:#f1f5f9;border-top:1px solid #e2e8f0;">
                <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>
