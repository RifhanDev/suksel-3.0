<div class="eval-form-card" id="accordion">
    {{-- START:Form - Penialian Prestasi Syarikat --}}
    <form method="post" action="{{ route('store.PetenderPerformance', [$tender, $tender_winner->vendor]) }}">
        @csrf
        <div class="eval-header" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion"
            data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            <h4><svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="18" height="18"
                    viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2M9 5a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2M9 14l2 2l4 -4" />
                </svg>
                BORANG PENILAIAN SYARIKAT / PEMBEKAL / PERKHIDMATAN</h4>
        </div>
        <div id="collapseOne" class="collapse @if (Session::get('ErrorRequest')) in @endif" role="tabpanel"
            aria-labelledby="headingOne">
            <div class="eval-body">
                {{-- Supplier Info Section --}}
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
                                    <input type="text" value="{{ $tender_winner->vendor->name }}"
                                        class="form-control bg-gray" readonly />
                                </td>
                            </tr>
                            <tr>
                                <th>2. Jenis Bekalan</th>
                                <td>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <select id="jenis-select" name="type1" class="form-select">
                                                <option disabled selected>Sila pilih jenis bekalan</option>
                                                <option value="Makanan">Makanan</option>
                                                <option value="Penginapan">Penginapan</option>
                                                <option value="Perkhidmatan">Perkhidmatan</option>
                                                <option>Lain - lain</option>
                                            </select>
                                            <input id="jenis-input" name="type2" type="text"
                                                class="form-control hidden mt-2" placeholder="" />
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>3. Alamat Pembekal</th>
                                <td>
                                    <textarea type="text" class="form-control" rows="3" readonly>{{ $tender_winner->vendor->address }}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <th>4. Nama Perolehan</th>
                                <td>
                                    <input name="nama_perolehan" type="text" value="{{ $tender->ref_number }}"
                                        class="form-control bg-gray" readonly />
                                </td>
                            </tr>
                            <tr>
                                <th>5. Kuantiti</th>
                                <td>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input name="quantity" type="text" class="form-control" step="1"
                                                min="0" />
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>6. Jumlah Kos (RM)</th>
                                <td>
                                    <input name="cost" type="number" class="form-control" />
                                </td>
                            </tr>
                            <tr>
                                <th>7. Tarikh Perolehan</th>
                                <td>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input name="acquisition_date" type="date" class="form-control"
                                                placeholder="Tarikh Perolehan" />
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Evaluation Criteria Table --}}
                <div class="table-responsive">
                    <table class="table tender-doc-table mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" rowspan="2" style="width:40px;">Bil.</th>
                                <th class="text-center" rowspan="2">Kriteria Penilaian</th>
                                <th class="text-center" colspan="5">Skala</th>
                                <th class="text-center" rowspan="2">Ulasan</th>
                            </tr>
                            <tr>
                                <th class="text-center" style="width:40px;">5</th>
                                <th class="text-center" style="width:40px;">4</th>
                                <th class="text-center" style="width:40px;">3</th>
                                <th class="text-center" style="width:40px;">2</th>
                                <th class="text-center" style="width:40px;">1</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center fw-bold">1.</td>
                                <td>Tindakan terhadap maklum balas / permintaan</td>
                                @for ($i = 5; $i >= 1; $i--)
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" name="scale_1" id="scale_1"
                                                value="{{ $i }}">
                                        </div>
                                    </td>
                                @endfor
                                <td><input name="review_1" class="form-control form-control-sm" /></td>
                            </tr>
                            <tr>
                                <td class="text-center fw-bold">2.</td>
                                <td>Harga yang berpatutan</td>
                                @for ($i = 5; $i >= 1; $i--)
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" name="scale_2"
                                                id="scale_2" value="{{ $i }}">
                                        </div>
                                    </td>
                                @endfor
                                <td><input name="review_2" class="form-control form-control-sm" /></td>
                            </tr>
                            <tr>
                                <td class="text-center fw-bold">3.</td>
                                <td>Kuantiti menepati Pesanan Tempatan (LO)</td>
                                @for ($i = 5; $i >= 1; $i--)
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" name="scale_3"
                                                id="scale_3" value="{{ $i }}">
                                        </div>
                                    </td>
                                @endfor
                                <td><input name="review_3" class="form-control form-control-sm" /></td>
                            </tr>
                            <tr>
                                <td class="text-center fw-bold">4.</td>
                                <td>Kualiti produk / perkhidmatan /kerja</td>
                                @for ($i = 5; $i >= 1; $i--)
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" name="scale_4"
                                                id="scale_4" value="{{ $i }}">
                                        </div>
                                    </td>
                                @endfor
                                <td><input name="review_4" class="form-control form-control-sm" /></td>
                            </tr>
                            <tr>
                                <td class="text-center fw-bold">5.</td>
                                <td>Penghantaran mengikut jadual</td>
                                @for ($i = 5; $i >= 1; $i--)
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" name="scale_5"
                                                id="scale_5" value="{{ $i }}">
                                        </div>
                                    </td>
                                @endfor
                                <td><input name="review_5" class="form-control form-control-sm" /></td>
                            </tr>
                            <tr>
                                <td class="text-center fw-bold">6.</td>
                                <td>Kerjasama yang diberikan</td>
                                @for ($i = 5; $i >= 1; $i--)
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" name="scale_6"
                                                id="scale_6" value="{{ $i }}">
                                        </div>
                                    </td>
                                @endfor
                                <td><input name="review_6" class="form-control form-control-sm" /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Score --}}
                <div class="eval-score-row">
                    <strong>Jumlah Markah : &nbsp;</strong>
                    <span id="sum" name="sum">00</span>
                    <strong>/ 30 x 100 = </strong>
                    <input id='calc' name="total_score" value="0" />
                </div>

                {{-- Officer Suggestion --}}
                <div class="eval-suggestion-box">
                    <h5><svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="16" height="16"
                            viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2"
                                d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12M12 8v3M12 14v.01" />
                        </svg>
                        Cadangan Pegawai Penilaian</h5>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="opinion" value="is_listed"
                            id="opinion_listed">
                        <label class="form-check-label" for="opinion_listed">Kekalkan dalam Senarai Pembekal (>
                            80%)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="opinion" value="is_conditional"
                            id="opinion_conditional">
                        <label class="form-check-label" for="opinion_conditional">Kekalkan dalam Senarai Pembekal
                            dengan bersyarat (50% - 79%)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="opinion" value="is_not_listed"
                            id="opinion_notlisted">
                        <label class="form-check-label" for="opinion_notlisted">Kekalkan dalam Senarai Pembekal (<
                                50%)</label>
                    </div>
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
                    <textarea name="review" class="form-control" rows="3"></textarea>
                </div>
            </div>

            {{-- Hidden Input --}}
            <input name="action" type="hidden" value="store" class="form-control bg-gray" />
            {{-- Button --}}
            <div class="p-3 text-end border-top">
                <button type="submit" class="btn btn-selangor confirm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="16" height="16"
                        viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 14l11 -11M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />
                    </svg>
                    Hantar
                </button>
            </div>
        </div>
    </form>
    {{-- END:Form - Penilaian Prestasi Syarikat --}}
</div>
