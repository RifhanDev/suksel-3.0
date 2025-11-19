<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    {{-- START:Form - Penialian Prestasi Syarikat --}}
    <form method="post" action="{{ route('store.PetenderPerformance', [$tender, $tender_winner -> vendor]) }}" class="panel panel-default p-0">
        @csrf
        <div class="panel-heading border" role="tab" id="headingOne">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <h4 class="my-3">BORANG PENILAIAN SYARIKAT / PEMBEKAL / PERKHIDMATAN</h4>
                </a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse border-black collapse @if (Session::get('ErrorRequest')) in @endif" role="tabpanel" aria-labelledby="headingOne">
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
                            <td class="col-xs-3 px-0 pl-2">
                                <span>1. Nama Pembekal</span>
                            </td>
                            <td>
                                <input type="text" value="{{ $tender_winner -> vendor -> name }}" class="form-control bg-gray" readonly/>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-xs-3 px-0 pl-2">
                                <span>2. Jenis Bekalan</span>
                            </td>
                            <td>
                                <div class="col-xs-6" style="padding: 0">
                                    <select id="jenis-select" name="type1" class="form-control bg-gray">
                                        <option disabled selected>Sila pilih jenis bekalan</option>
                                        <option value="Makanan">Makanan</option>
                                        <option value="Penginapan">Penginapan</option>
                                        <option value="Perkhidmatan">Perkhidmatan</option>
                                        <option>Lain - lain</option>
                                    </select>
                                    <input id="jenis-input" name="type2" type="text" class="form-control bg-gray hidden" placeholder=""/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-xs-3 px-0 pl-2">
                                <span>3. Alamat Pembekal</span>
                            </td>
                            <td>
                                <textarea type="text" class="form-control" rows="3" readonly>{{ $tender_winner -> vendor -> address }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-xs-3 px-0 pl-2">
                                <span>4. Nama Perolehan</span>
                            </td>
                            <td>
                                <input name="nama_perolehan" type="text" value="{{ $tender -> ref_number }}" class="form-control bg-gray" readonly/>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-xs-3 px-0 pl-2">
                                <span>5. Kuantiti</span>
                            </td>
                            <td>
                                <div class="col-xs-6" style="padding: 0">
                                    <input name="quantity" type="text" class="form-control bg-gray" step="1" min="0" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-xs-3 px-0 pl-2">
                                <span>6. Jumlah Kos (RM)</span>
                            </td>
                            <td>
                                <input name="cost" type="number" class="form-control bg-gray" />
                            </td>
                        </tr>
                        <tr>
                            <td class="col-xs-3 px-0 pl-2">
                                <span>7. Tarikh Perolehan</span>
                            </td>
                            <td>
                                <div class="col-xs-6" style="padding: 0">
                                    <input name="acquisition_date" type="date" class="form-control bg-gray" placeholder="Tarikh Perolehan" />
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
                        <th class="text-center" colspan="5" scope="colgroup">Skala</th>
                        <th class="text-center" rowspan="2" scope="colgroup">Ulasan</th>
                    </tr>
                    <tr>
                        <th class="text-center" scope="col">5</th>
                        <th class="text-center" scope="col">4</th>
                        <th class="text-center" scope="col">3</th>
                        <th class="text-center" scope="col">2</th>
                        <th class="text-center" scope="col">1</th>
                    </tr>
                    <tr>
                        <th class="text-center" scope="row">1.</th>
                        <td>Tindakan terhadap maklum balas / permintaan</td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_1" id="scale_1" value="5">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_1" id="scale_1" value="4">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_1" id="scale_1" value="3">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_1" id="scale_1" value="2">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_1" id="scale_1" value="1">
                            </div>
                        </td>
                        <td>
                            <input name="review_1" class="form-control bg-gray" />
                        </td>
                    </tr>
                    <tr>
                        <th class="text-center" scope="row">2.</th>
                        <td>Harga yang berpatutan</td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_2" id="scale_2" value="5">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_2" id="scale_2" value="4">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_2" id="scale_2" value="3">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_2" id="scale_2" value="2">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_2" id="scale_2" value="1">
                            </div>
                        </td>
                        <td>
                            <input name="review_2" class="form-control bg-gray" />
                        </td>
                    </tr>
                    <tr>
                        <th class="text-center" scope="row">3.</th>
                        <td>Kuantiti menepati Pesanan Tempatan (LO)</td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_3" id="scale_3" value="5">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_3" id="scale_3" value="4">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_3" id="scale_3" value="3">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_3" id="scale_3" value="2">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_3" id="scale_3" value="1">
                            </div>
                        </td>
                        <td>
                            <input name="review_3" class="form-control bg-gray" />
                        </td>
                    </tr>
                    <tr>
                        <th class="text-center" scope="row">4.</th>
                        <td>Kualiti produk / perkhidmatan /kerja</td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_4" id="scale_4" value="5">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_4" id="scale_4" value="4">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_4" id="scale_4" value="3">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_4" id="scale_4" value="2">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_4" id="scale_4" value="1">
                            </div>
                        </td>
                        <td>
                            <input name="review_4" class="form-control bg-gray" />
                        </td>
                    </tr>
                    <tr>
                        <th class="text-center" scope="row">5.</th>
                        <td>Penghantaran mengikut jadual</td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_5" id="scale_5" value="5">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_5" id="scale_5" value="4">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_5" id="scale_5" value="3">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_5" id="scale_5" value="2">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_5" id="scale_5" value="1">
                            </div>
                        </td>
                        <td>
                            <input name="review_5" class="form-control bg-gray" />
                        </td>
                    </tr>
                    <tr>
                        <th class="text-center" scope="row">6.</th>
                        <td>Kerjasama yang diberikan</td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_6" id="scale_6" value="5">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_6" id="scale_6" value="4">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_6" id="scale_6" value="3">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_6" id="scale_6" value="2">
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="scale_6" id="scale_6" value="1">
                            </div>
                        </td>
                        <td>
                            <input name="review_6" class="form-control bg-gray" />
                        </td>
                    </tr>
                    <tr>
                        <td style="border:none;"scope="row" colspan="6"></td>
                        <td style="border:none;"colspan="2">
                            <strong>Jumlah Markah : &nbsp;</strong>
                            <span id="sum" name="sum">00</span>
                            <strong>/ 30 x 100 = </strong>
                            <input id='calc' name="total_score" value="0"/>
                        </td>
                    </tr>
                </table>
                <div  style="border: 1px solid #ddd; padding:.5rem; margin:0 !important; border-top:none;">
                    <h4 style="padding-left: 1rem"><strong>Cadangan Pegawai Penilaian</strong></h4>
                    <div style="padding-left: 2.5rem">
                        <input class="form-check-input" type="radio" name="opinion" value="is_listed">
                        <label>Kekalkan dalam Senarai Pembekal (> 80%)</label>
                    </div>
                    <div style="padding-left: 2.5rem">
                        <input class="form-check-input" type="radio" name="opinion" value="is_conditional">
                        <label>Kekalkan dalam Senarai Pembekal dengan bersyarat (50% - 79%)</label>
                    </div>
                    <div style="padding-left: 2.5rem">
                        <input class="form-check-input" type="radio" name="opinion" value="is_not_listed">
                        <label>Kekalkan dalam Senarai Pembekal (< 50%)</label>
                    </div>
                </div>
                <div  style="border: 1px solid #ddd; padding:.5rem; margin:0 !important; border-top:none;">
                    <h4 style="padding-left: 1rem"><strong>Ulasan</strong></h4>
                    <div style="padding-left: 1rem; margin-bottom:1rem">
                        <textarea name="review" class="form-control" rows="3"></textarea>
                    </div>
                </div>
            </div>
            {{-- Hidden Input --}}
            <input name="action" type="hidden" value="store" class="form-control bg-gray"/>
            {{-- Button --}}
            <div class="mt-6 pull-right mb-4">
                <input type="submit" value="Hantar" class="btn btn-primary confirm">
            </div>
        </div>
    </form>
    {{-- END:Form - Penilaian Prestasi Syarikat --}}
</div>