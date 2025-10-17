{{ App\Libraries\Asset::push('css', 'form') }}
<div class="row stacked-form">
    <div class="col-lg-2">
        <ul class="nav nav-pills nav-stacked">
            <li class="active"><a href="#vf-main" data-toggle="pill">Maklumat Pemulangan Semula</a></li>
            <li><a href="#vf-files" data-toggle="pill">Fail</a></li>
            @if (in_array($refund->status, [2, 4]))
                <li><a href="#vf-reject" data-toggle="pill">Alasan Penolakan</a></li>
            @endif
        </ul>
    </div>

    <div class="tab-content col-lg-10">
        <div class="tab-pane active" id="vf-main">
            <div class="row">
                <div class="col-lg-6">
                    <table class="table table-condensed table-bordered">
                        <tr>
                            <th colspan="2" class="text-center">Maklumat Transaksi</th>
                        </tr>
                        <tr>
                            <th>Nama Perniagaan / Syarikat</th>
                            <td>{{ $transaction->vendor->name }}</td>
                        </tr>
                        <tr>
                            <th class="col-lg-3">No Resit</th>
                            <td>{{ $transaction->receipt != 'old' ? $transaction->receipt : $transaction->vendor_id . '-' . $transaction->gateway_reference }}
                            </td>
                        </tr>
                        <tr>
                            <th class="col-lg-3">Tarikh Transaksi</th>
                            <td>{{ $transaction->transaction_date }}</td>
                        </tr>
                        <tr>
                            <th>Pembayaran Melalui</th>
                            <td>{{ $transaction->method }}</td>
                        </tr>
                        <tr>
                            <th>Langganan/ Tajuk Sebut Harga/ Tender yang dibeli</th>
                            <td>{{ $transaction->type == 'purchase' ? $transaction->purchases[0]->tender->name : 'Langganan' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Agensi</th>
                            <td>{{ $transaction->type == 'purchase' ? $transaction->agency->name : 'SUK Selangor' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Amaun (RM)</th>
                            <td><b>{{ $transaction->amount }}</b></td>
                        </tr>
                    </table>
                </div>

                <div class="col-lg-6">
                    <table class="table table-condensed table-bordered">
                        <tr>
                            <th colspan="2" class="text-center">Maklumat Pemohon</th>
                        </tr>
                        <tr>
                            <th class="col-lg-3">Nama</th>
                            <td>{{ $refund->name }}</td>
                        </tr>
                        <tr>
                            <th>No. Kad Pengenalan</th>
                            <td>{{ $refund->ic }}</td>
                        </tr>
                        <tr>
                            <th>No. Telefon</th>
                            <td>{{ $refund->tel }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{!! nl2br($refund->address) !!}</td>
                        </tr>
                    </table>

                    <table class="table table-condensed table-bordered">
                        <tr>
                            <th colspan="2" class="text-center">Maklumat Bank Pemohon</th>
                        </tr>
                        <tr>
                            <th class="col-lg-3">No. Akaun Bank</th>
                            <td>{{ $refund->bank_acc }}</td>
                        </tr>
                        <tr>
                            <th>Jenis Bank</th>
                            <td>{{ $refund->banks->name }}</td>
                        </tr>
                        <tr>
                            <th>Alamat Bank</th>
                            <td>{!! nl2br($refund->bank_address) !!}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-condensed table-bordered">
                        <tr>
                            <th class="text-center">Catatan Pemohon</th>
                        </tr>
                        <tr>
                            <td>{!! nl2br($refund->remark) !!}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="tab-pane" id="vf-files">
            <table class="table table-condensed table-bordered">
                <tr>
                    <th colspan="2" class="text-center">Fail Berkenaan</th>
                </tr>

                @forelse ($refund->files as $key => $value)
                    <tr>
                        <th class="col-lg-3">{{ $value->label }}</th>
                        <td><button class="btn btn-warning btn-file-view"
                                data-url="{{ $value->url . '/' . $value->name }}">Lihat</button></td>
                    </tr>
                @empty
                    <td colspan="2">Tiada Fail</td>
                @endforelse
            </table>
        </div>

        @if (in_array($refund->status, [2, 4]))
            <div class="tab-pane" id="vf-reject">
                <table class="table table-condensed table-bordered">
                    <tr>
                        <th colspan="2" class="text-center">
                            {{ $refund->status == 2 ? 'Alasan Penolakan Permohonan' : 'Alasan Penolakan Aduan' }}
                        </th>
                    </tr>
                    @if ($refund->rejection_reason)
                        <tr>
                            <th class="col-lg-3">Catatan</th>
                            <td>{{ $refund->rejection_reason }}</td>
                        </tr>
                    @endif
                    @if ($refund->rejection_template_id)
                        <tr>
                            <th class="col-lg-3">Alasan</th>
                            <td>
                                <ol>
                                    @foreach (json_decode($refund->rejection_template_id, true) as $reject_id)
                                        @foreach ($templates as $template)
                                            @if ($template['id'] == $reject_id)
                                                <li style="text-decoration: underline;">{{ $template['title'] }}
                                                </li>
                                                {!! $template['content'] !!}
                                            @endif
                                        @endforeach
                                    @endforeach
                                </ol>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        @endif
    </div>
</div>
