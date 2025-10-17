<div class="modal fade" id="exampleModal{{ $petenderPerformance -> id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Penilaian Prestasi</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="panel-body">
                    <table class="table table-bordered v-table" style="margin:0 !important; border-bottom:none;">
                        <thead>
                            <tr>
                                <td style="border:none">
                                    <strong>BAHAGIAN / SEKSYEN / UNIT:</strong>
                                </td>
                            </tr>
                        </thead>
                        <tbody style="border-bottom:none;">
                            <tr>
                                <td class="col-xs-3 pl-2">
                                    <span>1. Nama Pembekal</span>
                                </td>
                                <td>
                                    <input type="text" value="{{ $petenderPerformance -> vendor -> name }}" class="form-control bg-gray" readonly/>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-xs-3 pl-2">
                                    <span>2. Jenis Bekalan</span>
                                </td>
                                <td>
                                    <input value="{{ $petenderPerformance -> type }}" type="text" class="form-control bg-gray" readonly/>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-xs-3 pl-2">
                                    <span>3. Alamat Pembekal</span>
                                </td>
                                <td>
                                    <textarea type="text" class="form-control" rows="3" readonly>{{ $petenderPerformance -> vendor -> address }}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-xs-3 pl-2">
                                    <span>4. Nama Perolehan</span>
                                </td>
                                <td>
                                    <input name="nama_perolehan" type="text" value="{{ $petenderPerformance -> tender -> ref_number }}" class="form-control bg-gray" readonly/>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-xs-3 pl-2">
                                    <span>5. Kuantiti</span>
                                </td>
                                <td>
                                    <div class="col-xs-6" style="padding: 0">
                                        <input name="quantity" value="{{ $petenderPerformance -> quantity }}" type="text" class="form-control bg-gray" step="1" min="0" readonly/>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-xs-3 pl-2">
                                    <span>6. Jumlah Kos (RM)</span>
                                </td>
                                <td>
                                    <input name="cost" value="{{ $petenderPerformance -> cost }}" type="text" class="form-control bg-gray" readonly/>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-xs-3 pl-2">
                                    <span>7. Tarikh Perolehan</span>
                                </td>
                                <td>
                                    <div class="col-xs-6" style="padding: 0">
                                        <input name="acquisition_date" value="{{ $petenderPerformance -> acquisition_date }}" type="text" class="form-control bg-gray" readonly/>
                                    </div>
                                </td>
                            </tr>
                            <tr style="border-bottom:none;"><td colspan="2" rowspan="2" style="padding: 2rem; border-bottom: none"></td></tr>
                        </tbody>
                    </table>

                    <table class="table table-bordered v-table" style="margin:0 !important; border-top:none;">
                        <col>
                        <colgroup span="5"></colgroup>
                        <tr>
                            <th class="text-center" rowspan="2">Bil.</th>
                            <th class="text-center" rowspan="2" scope="colgroup">Kriteria Penilaian</th>
                            <th class="text-center" rowspan="2" scope="colgroup">Skala</th>
                            <th class="text-center" rowspan="2" scope="colgroup">Ulasan</th>
                        </tr>
                        <tbody>
                            <tr>
                                <td>1.</td>
                                <td>Tindakan terhadap maklum balas / permintaan</td>
                                <td class="text-center">{{ $petenderPerformance -> performanceCriteria -> scale_1 ?? ''}} / 5</td>
                                <td>{{ $petenderPerformance -> performanceCriteria -> review_1 ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>2.</td>
                                <td>Harga yang berpatutan</td>
                                <td class="text-center">{{ $petenderPerformance -> performanceCriteria -> scale_2 ?? ''}} / 5</td>
                                <td>{{ $petenderPerformance -> performanceCriteria -> review_2 ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>3.</td>
                                <td>Kuantiti menepati Pesanan Tempatan (LO)</td>
                                <td class="text-center">{{ $petenderPerformance -> performanceCriteria -> scale_3 ?? ''}} / 5</td>
                                <td>{{ $petenderPerformance -> performanceCriteria -> review_3 ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>4.</td>
                                <td>Kualiti produk / perkhidmatan /kerja</td>
                                <td class="text-center">{{ $petenderPerformance -> performanceCriteria -> scale_4 ?? ''}} / 5</td>
                                <td>{{ $petenderPerformance -> performanceCriteria -> review_4 ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>5.</td>
                                <td>Penghantaran mengikut jadual</td>
                                <td class="text-center">{{ $petenderPerformance -> performanceCriteria -> scale_5 ?? ''}} / 5</td>
                                <td>{{ $petenderPerformance -> performanceCriteria -> review_5 ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>6.</td>
                                <td>Kerjasama yang diberikan</td>
                                <td class="text-center">{{ $petenderPerformance -> performanceCriteria -> scale_6 ?? ''}} / 5</td>
                                <td>{{ $petenderPerformance -> performanceCriteria -> review_6 ?? '-' }}</td>
                            </tr>
                        </tbody>
                        <tr>
                            <td style="border:none;"scope="row" colspan="2"></td>
                            <td style="border:none;"colspan="2">
                                <strong>Jumlah Markah : &nbsp;</strong>
                                <input id='calc' name="total_score" value="{{ number_format($petenderPerformance -> total_score, 2) }} %" readonly/>
                            </td>
                        </tr>
                    </table>
                    <div  style="border: 1px solid #ddd; padding:.5rem; margin:0 !important; border-top:none;">
                        <h4 style="padding-left: 1rem"><strong>Cadangan Pegawai Penilaian</strong></h4>
                        @if ($petenderPerformance -> opinion == 'is_listed')
                            <label>Kekalkan dalam Senarai Pembekal (> 80%)</label>
                        @elseif ($petenderPerformance -> opinion == 'is_conditional')
                            <label>Kekalkan dalam Senarai Pembekal dengan bersyarat (50% - 79%)</label>
                        @else
                            <label>Kekalkan dalam Senarai Pembekal (< 50%)</label>
                        @endif
                    </div>
                    <div  style="border: 1px solid #ddd; padding:.5rem; margin:0 !important; border-top:none;">
                        <h4 style="padding-left: 1rem"><strong>Ulasan</strong></h4>
                        <div style="padding-left: 1rem; margin-bottom:1rem">
                            <textarea name="review" class="form-control" rows="3" readonly>{{ $petenderPerformance -> overall_review }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>