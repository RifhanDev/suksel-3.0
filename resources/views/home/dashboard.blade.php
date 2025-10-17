@extends('layouts.default')

@section('styles')
    <link href="{{ asset('css/form.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-9 col-xs-12">
            <ul class="nav nav-tabs nav-justified">
                <li class="active"><a href="{{ asset('dashboard') }}">Maklumat Tender / Sebut Harga</a></li>
                <li><a href="{{ asset('vendor') }}">Maklumat Syarikat</a></li>
            </ul>

            <div class="row stacked-form">
                <div class="col-lg-2">
                    <ul class="nav nav-pills nav-stacked">
                        <li class="active"><a href="#db-recom" data-toggle="pill">Anggaran Tender / Sebut Harga Layak <span
                                    class="badge">{{ count($eligibles) }}</a></li>
                        <li><a href="#db-docs" data-toggle="pill">Dokumen Dibeli <span
                                    class="badge">{{ count($purchases) }}</a></li>
                        <li><a href="#db-invites" data-toggle="pill">Tender Terhad <span
                                    class="badge">{{ count($invites) }}</a></li>
                        <li><a href="#db-refund" data-toggle="pill">Pemulangan Semula </a></li>
                        <li>
                            <a href="#db-penilaian-prestasi" data-toggle="pill">Penilaian Prestasi</a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content col-lg-10">
                    <div class="tab-pane active" id="db-recom">
                        @if (count($eligibles) > 0)
                            <table class="DT2 table table-bordered table-condensed">
                                <thead class="bg-blue-selangor">
                                    <tr>
                                        <th>Tender / Sebut Harga</th>
                                        <th>Tarikh Tutup</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($eligibles as $tender)
                                        <tr>
                                            <td>
                                                {{ $tender->tenderer->name }}<br>
                                                <small><strong>{{ $tender->ref_number }}</strong></small><br>
                                                <a href="{{ asset('tenders/' . $tender->id) }}">{{ $tender->name }}</a>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($tender->submission_datetime)->format('j M Y') }}
                                                12:00 PM</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info">Tiada tender yang layak buat masa ini.</div>
                        @endif
                    </div>

                    <div class="tab-pane" id="db-docs">
                        @if (count($purchases) > 0)
                            <table class="DT3 table table-bordered table-condensed">
                                <thead class="bg-blue-selangor">
                                    <tr>
                                        <th>Tender / Sebut Harga</th>
                                        <th class="col-lg-2">Tarikh Tutup</th>
                                        <th class="col-lg-2">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchases as $purchase)
                                        <tr>
                                            <td>
                                                {{ $purchase->tender->tenderer->name }}<br>
                                                <small><strong>{{ $purchase->tender->ref_number }}</strong></small><br>
                                                <a
                                                    href="{{ asset('tenders/' . $purchase->tender->id) }}">{{ $purchase->tender->name }}</a>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($purchase->tender->submission_datetime)->format('j M Y') }}
                                                12:00 PM</td>
                                            <td>
                                                <a href="{{ asset('tenders/' . $purchase->tender_id . '/receipt/' . $purchase->id) }}"
                                                    target="_blank"><i class="icon-printer"> Resit</i></a><br><br>
                                                <a href="{{ asset('tenders/' . $purchase->tender_id . '/document/' . $purchase->id) }}"
                                                    target="_blank"><i class="icon-doc"> No. Siri Dokumen</i></a><br><br>
                                                <a href="{{ asset('tenders/' . $purchase->tender_id) }}#tf-doc2"
                                                    target="_blank"><i class="icon-list"> Muat Turun</i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info">Tiada dokumen yang dibeli.</div>
                        @endif
                    </div>

                    <div class="tab-pane" id="db-invites">
                        @if (count($invites) > 0)
                            <table class="DT2 table table-bordered table-condensed">
                                <thead class="bg-blue-selangor">
                                    <tr>
                                        <th>Tender / Sebut Harga</th>
                                        <th class="col-lg-2">Tarikh Tutup</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invites as $invite)
                                        <tr>
                                            <td>
                                                {{ $invite->tender->tenderer->name }}<br>
                                                <small><strong>{{ $invite->tender->ref_number }}</strong></small><br>
                                                <a
                                                    href="{{ asset('tenders/' . $invite->tender->id) }}">{{ $invite->tender->name }}</a>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($invite->tender->submission_datetime)->format('j M Y') }}
                                                12:00 PM</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info">Tiada jemputan tender.</div>
                        @endif
                    </div>

                    <div class="tab-pane" id="db-refund">
                        <div class="alert alert-info">
                            <h5><b>Arahan/Makluman Berkaitan</b></h5>
                            <ol>
                                <li>Muat turun 'Templat Surat Permohonan' yang disediakan.</li>
                                <li>Sila <b>tukar</b> kandungan dokumen tersebut yang berwarna <span style="color: red;">merah</span> dengan maklumat pemohon dan <b>hitamkan</b> semula</li>
                                <li>Selepas permohonan diluluskan oleh BPM, <span style="text-decoration: underline">semua penyata, resit, surat dan borang yang lengkap wajib perlu dicetak dan dihantar secara pos / fizikal</span> ke:
                                <br>
                            <b>Bahagian Khidmat Pengurusan,<br>Unit Kewangan, TIngkat 17,<br>Bangunan Sultan Salahuddin Abdul Aziz Shah,<br>40503 Shah Alam,Selangor Darul Ehsan</b></li>
                            </ol>
                        </div>
                        <a href="{{ route('refunds.create') }}" type="button" class="btn btn-primary ">Permohonan Baru</a>
                        <a download href="{{ asset('file/Template Surat Permohonan Pelanggan 2022.docx') }}" type="button" class="btn btn-warning ">Templat Surat Permohonan</a>
                        <hr>
                        <div class="table-responsive">
                        <table class="DT4 table table-bordered table-condensed">
                            <thead class="bg-blue-selangor">
                                <tr>
                                    <th>No Rujukan</th>
                                    <th>Tarikh Dimohon</th>
                                    <th>No Resit</th>
                                    <th>Tarikh Dikemaskini</th>
                                    <th>Status</th>
                                    <th>Amaun</th>
                                    <th class="col-lg-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($refunds as $refund)
                                    <tr>
                                        <td>{{ $refund->ref_num }}</td>
                                        <td>{{ date('d-m-Y', strtotime($refund->created_at)) }}</td>
                                        <td>{{ $refund->receipt }}</td>
                                        <td>{{ date('d-m-Y', strtotime($refund->updated_at)) }}</td>
                                        <td>{{ $refund->status }}</td>
                                        <td>{{ $refund->amount }}</td>
                                        <td><a href="{{ route('refunds.show',$refund->id) }}"  class="btn btn-xs btn-primary">Papar</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table></div>
                    </div>

                    {{-- Content Tab - Penilaian Prestasi Syarikat --}}
                    @include('home.tab-contents.penilaian-prestasi')

                </div>
            </div>
        </div>

        <div class="col-lg-3 col-xs-12">
            @include('layouts._news')
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script src="{{ asset('js/news.js') }}"></script>
    <script>
        $('.DT2').DataTable();
        $('.DT3').DataTable({
            order: [
                [1, 'desc']
            ]
        });
        $('.DT4').DataTable({
            order: [
                [1, 'desc']
            ]
        });
    </script>
@endsection
