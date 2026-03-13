<div class="modal fade modal-tender" id="exampleModal{{ $petenderPerformance->id }}" tabindex="-1"
    aria-labelledby="exampleModalLabel{{ $petenderPerformance->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel{{ $petenderPerformance->id }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="18" height="18"
                        viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M3 12h3v8h-3zM9 5h3v15h-3zM15 8h3v12h-3z" />
                    </svg>
                    Penilaian Prestasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                {{-- Supplier Info --}}
                <div class="table-responsive">
                    <table class="table tender-info-table mb-0">
                        <thead>
                            <tr>
                                <td colspan="2" style="border:none; background: transparent;">
                                    <strong class="text-muted text-uppercase"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;">BAHAGIAN / SEKSYEN /
                                        UNIT:</strong>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>1. Nama Pembekal</th>
                                <td>
                                    <input type="text" value="{{ $petenderPerformance->vendor->name }}"
                                        class="form-control bg-gray" readonly />
                                </td>
                            </tr>
                            <tr>
                                <th>2. Jenis Bekalan</th>
                                <td>
                                    <input value="{{ $petenderPerformance->type }}" type="text"
                                        class="form-control bg-gray" readonly />
                                </td>
                            </tr>
                            <tr>
                                <th>3. Alamat Pembekal</th>
                                <td>
                                    <textarea type="text" class="form-control" rows="3" readonly>{{ $petenderPerformance->vendor->address }}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <th>4. Nama Perolehan</th>
                                <td>
                                    <input name="nama_perolehan" type="text"
                                        value="{{ $petenderPerformance->tender->ref_number }}"
                                        class="form-control bg-gray" readonly />
                                </td>
                            </tr>
                            <tr>
                                <th>5. Kuantiti</th>
                                <td>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input name="quantity" value="{{ $petenderPerformance->quantity }}"
                                                type="text" class="form-control bg-gray" step="1"
                                                min="0" readonly />
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>6. Jumlah Kos (RM)</th>
                                <td>
                                    <input name="cost" value="{{ $petenderPerformance->cost }}" type="text"
                                        class="form-control bg-gray" readonly />
                                </td>
                            </tr>
                            <tr>
                                <th>7. Tarikh Perolehan</th>
                                <td>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input name="acquisition_date"
                                                value="{{ $petenderPerformance->acquisition_date }}" type="text"
                                                class="form-control bg-gray" readonly />
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Criteria Table --}}
                <div class="table-responsive">
                    <table class="table tender-doc-table mb-0">
                        <thead>
                            <tr>
                                <th class="text-center">Bil.</th>
                                <th>Kriteria Penilaian</th>
                                <th class="text-center">Skala</th>
                                <th>Ulasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1.</td>
                                <td>Tindakan terhadap maklum balas / permintaan</td>
                                <td class="text-center fw-bold">
                                    {{ $petenderPerformance->performanceCriteria->scale_1 ?? '' }} / 5</td>
                                <td>{{ $petenderPerformance->performanceCriteria->review_1 ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">2.</td>
                                <td>Harga yang berpatutan</td>
                                <td class="text-center fw-bold">
                                    {{ $petenderPerformance->performanceCriteria->scale_2 ?? '' }} / 5</td>
                                <td>{{ $petenderPerformance->performanceCriteria->review_2 ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">3.</td>
                                <td>Kuantiti menepati Pesanan Tempatan (LO)</td>
                                <td class="text-center fw-bold">
                                    {{ $petenderPerformance->performanceCriteria->scale_3 ?? '' }} / 5</td>
                                <td>{{ $petenderPerformance->performanceCriteria->review_3 ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">4.</td>
                                <td>Kualiti produk / perkhidmatan /kerja</td>
                                <td class="text-center fw-bold">
                                    {{ $petenderPerformance->performanceCriteria->scale_4 ?? '' }} / 5</td>
                                <td>{{ $petenderPerformance->performanceCriteria->review_4 ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">5.</td>
                                <td>Penghantaran mengikut jadual</td>
                                <td class="text-center fw-bold">
                                    {{ $petenderPerformance->performanceCriteria->scale_5 ?? '' }} / 5</td>
                                <td>{{ $petenderPerformance->performanceCriteria->review_5 ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">6.</td>
                                <td>Kerjasama yang diberikan</td>
                                <td class="text-center fw-bold">
                                    {{ $petenderPerformance->performanceCriteria->scale_6 ?? '' }} / 5</td>
                                <td>{{ $petenderPerformance->performanceCriteria->review_6 ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Score --}}
                <div class="eval-score-row">
                    <strong>Jumlah Markah : &nbsp;</strong>
                    <input id='calc' name="total_score"
                        value="{{ number_format($petenderPerformance->total_score, 2) }} %" readonly
                        class="border-0 bg-transparent fw-bold text-center" style="width: 80px;" />
                </div>

                {{-- Officer Suggestion --}}
                <div class="eval-suggestion-box">
                    <h5><svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="16" height="16"
                            viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12M12 8v3M12 14v.01" />
                        </svg>
                        Cadangan Pegawai Penilaian</h5>
                    @if ($petenderPerformance->opinion == 'is_listed')
                        <span class="badge bg-success">Kekalkan dalam Senarai Pembekal (> 80%)</span>
                    @elseif ($petenderPerformance->opinion == 'is_conditional')
                        <span class="badge bg-warning text-dark">Kekalkan dalam Senarai Pembekal dengan bersyarat (50% -
                            79%)</span>
                    @else
                        <span class="badge bg-danger">Kekalkan dalam Senarai Pembekal (< 50%)</span>
                    @endif
                </div>

                {{-- Review --}}
                <div class="eval-suggestion-box border-top">
                    <h5><svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="16" height="16"
                            viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2"
                                d="M5 5a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2l0 -14M9 7l6 0M9 11l6 0M9 15l4 0" />
                        </svg>
                        Ulasan</h5>
                    <textarea name="review" class="form-control" rows="3" readonly>{{ $petenderPerformance->overall_review }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
