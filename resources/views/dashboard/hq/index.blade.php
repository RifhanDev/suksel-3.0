@extends('layouts.default')

@section('styles')
    <style>
        @media print {
            .default-dashboard {
                page-break-after: always;
            }

            .chart-dashboard {
                page-break-after: always;
            }

            .panel {
                page-break-inside: avoid;
            }
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <a onclick="window.print()" class="pull-right print hidden-print" target="_new"><i class="fa fa-print"></i>
                Cetak</a>
        </div>
    </div>
    <ul class="nav nav-tabs nav-justified hidden-print">
        <li id="li_default" class="active"><a href="{{ asset('dashboard/hq') }}"> Dashboard Ringkasan</a></li>
        <li id="li_chart"><a href="{{ asset('dashboard/hq?view=chart') }}"> Dashboard Carta</a></li>
    </ul>
    <div class="row">
        <div class="col-sm-6">
            <h3 class="tender-title">Pengguna</h3>
            <div class="default-dashboard">
                <div class="col-sm-6">
                    <div class="panel panel-primary text-center">
                        <div class="panel-body">
                            <h2>{{ number_format(App\User::active()->count(), 0) }}</h2>
                        </div>
                        <div class="panel-heading">
                            <span style="color:white">
                                Aktif
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-primary text-center">
                        <div class="panel-body">
                            <h2>{{ number_format(App\User::notActive()->count(), 0) }}</h2>
                        </div>
                        <div class="panel-heading">
                            <span style="color:white">
                                Tidak Aktif
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="panel panel-primary text-center">
                        <div class="panel-body">
                            <h2>{{ number_format(App\User::count(), 0) }}</h2>
                        </div>
                        <div class="panel-heading">
                            <span style="color:white">
                                Jumlah
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="chart-dashboard">
                <div class="col-sm-12">
                    <div id="chart_users" style="width: 100%; height: 350px;" class="mt-3 mb-5"></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <h3 class="tender-title">Syarikat</h3>
            <div class="default-dashboard">
                <div class="col-sm-6">
                    <div class="panel panel-primary text-center">
                        <div class="panel-body">
                            <h2>{{ number_format(App\Vendor::activeSubscriptionCount(), 0) }}</h2>
                        </div>
                        <div class="panel-heading">
                            <span style="color:white">
                                Aktif
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-primary text-center">
                        <div class="panel-body">
                            <h2>{{ number_format(App\Vendor::nonActiveSubscriptionCount(), 0) }}</h2>
                        </div>
                        <div class="panel-heading">
                            <span style="color:white">
                                Tidak Aktif
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-primary text-center">
                        <div class="panel-body">
                            <h2>{{ number_format(App\Vendor::pendingRegistrationCount(), 0) }}</h2>
                        </div>
                        <div class="panel-heading">
                            <span style="color:white">
                                Belum Daftar
                            </span>
                        </div>
                    </div>
                </div>
                <div class=col-sm-6>
                    <div class="panel panel-primary text-center">
                        <div class="panel-body">
                            <h2>{{ number_format(App\Vendor::count(), 0) }}</h2>
                        </div>
                        <div class="panel-heading">
                            <span style="color:white">
                                Jumlah
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="chart-dashboard">
                <div class="col-sm-12">
                    <div id="chart_vendors" style="width: 100%; height: 350px;" class="mt-3 mb-5"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h3 class="tender-title">Tender & Sebutharga</h3>
            <div class="default-dashboard">
                <div class="col-sm-12 mb-2">
                    <form id="tender_summary" class="form-inline">
                        <div class="form-group">
                            <label>Tahun : </label>
                            <input class="form-control" id="year_summary" type="text" name="year_summary">
                            <button class="btn btn-primary btn-sm hidden-print">Jana</button>
                        </div>
                    </form>
                </div>
                <div class="col-sm-4">
                    <div class="panel panel-primary text-center">
                        <div class="panel-body">
                            <div id="tenderCount">
                                <h2></h2>
                            </div>
                        </div>
                        <div class="panel-heading">
                            <span style="color:white">
                                Tender
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="panel panel-primary text-center">
                        <div class="panel-body">
                            <div id="quotationCount">
                                <h2></h2>
                            </div>
                        </div>
                        <div class="panel-heading">
                            <span style="color:white">
                                Sebutharga
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="panel panel-primary text-center">
                        <div class="panel-body">
                            <div id="tenderTotalCount">
                                <h2></h2>
                            </div>
                        </div>
                        <div class="panel-heading">
                            <span style="color:white">
                                Jumlah
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="chart-dashboard">
                <div class="col-sm-12">
                    <form id="tender_form" class="form-inline">
                        <div class="form-group">
                            <label>Lihat : </label>
                            <select class="form-control" name="tender_view_type" id="tender_view_type">
                                <option value="tender_yearly" selected>Tahunan</option>
                                <option value="tender_monthly">Bulanan</option>
                                <option value="tender_weekly">Mingguan</option>
                            </select>
                        </div>
                        <div class="form-group ml-5">
                            <div id="tender_yearly">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span class="input-group-text" id="basic-addon1">Tahun</span>
                                    </div>
                                    <div class="input-group">
                                        <input class="form-control" id="year_start" type="text" name="year_start">
                                    </div>
                                </div>
                            </div>
                            <div id="tender_weekly" class="hide">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span class="input-group-text" id="basic-addon1">Suku</span>
                                    </div>
                                    <input class="form-control x-uppercase" id="quarter_start" type="number"
                                        name="quarter_start" min="1" max="4" value="1">
                                    <div class="input-group-addon">Tahun</div>
                                    <input class="form-control" id="year_quarter" type="text" name="year_quarter">
                                </div>
                            </div>
                            <div id="tender_monthly" class="hide">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span class="input-group-text" id="basic-addon1">Tarikh</span>
                                    </div>
                                    <input class="form-control x-uppercase" id="monthly_start" type="text"
                                        name="monthly_start">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-sm hidden-print">Jana</button>
                    </form>
                    <div id="chart_tenders" style="width: 100%; height: 350px;" class="mt-3 mb-5"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h3 class="tender-title">Transaksi</h3>
            <div class="default-dashboard">
                <div class="col-sm-12 mb-2">
                    <form id="transaction_summary" class="form-inline">
                        <div class="form-group">
                            <label>Tahun : </label>
                            <input class="form-control" id="year_summary" type="text" name="year_summary">
                            <button class="btn btn-primary btn-sm hidden-print">Jana</button>
                        </div>
                    </form>
                </div>
                <div class="col-sm-4">
                    <div class="panel panel-primary text-center">
                        <div class="panel-body">
                            <div id="subscriptionCount">
                                <h2></h2>
                            </div>
                        </div>
                        <div class="panel-heading">
                            <span style="color:white">
                                # Langganan
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="panel panel-primary text-center">
                        <div class="panel-body">
                            <div id="purchaseCount">
                                <h2></h2>
                            </div>
                        </div>
                        <div class="panel-heading">
                            <span style="color:white">
                                # Pembelian Dokumen
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="panel panel-primary text-center">
                        <div class="panel-body">
                            <div id="transactionTotal">
                                <h2></h2>
                            </div>
                        </div>
                        <div class="panel-heading">
                            <span style="color:white">
                                Jumlah Transaksi
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="panel panel-primary text-center">
                        <div class="panel-body">
                            <div id="subscriptionValueSum">
                                <h2></h2>
                            </div>
                        </div>
                        <div class="panel-heading">
                            <span style="color:white">
                                Nilai Langganan
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="panel panel-primary text-center">
                        <div class="panel-body">
                            <div id="purchaseValueSum">
                                <h2></h2>
                            </div>
                        </div>
                        <div class="panel-heading">
                            <span style="color:white">
                                Nilai Pembelian
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="panel panel-primary text-center">
                        <div class="panel-body">
                            <div id="transactionValueTotal">
                                <h2></h2>
                            </div>
                        </div>
                        <div class="panel-heading">
                            <span style="color:white">
                                Jumlah Nilai
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="chart-dashboard">
                <div class="col-sm-12">
                    <form id="transaction_form" class="form-inline">
                        <div class="form-group">
                            <label>Lihat : </label>
                            <select class="form-control" name="transaction_view_type" id="transaction_view_type">
                                <option value="transaction_yearly" selected>Tahunan</option>
                                <option value="transaction_monthly">Bulanan</option>
                                <option value="transaction_weekly">Mingguan</option>
                            </select>
                        </div>
                        <div class="form-group ml-5">
                            <div id="transaction_yearly">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span class="input-group-text" id="basic-addon1">Tahun</span>
                                    </div>
                                    <div class="input-group">
                                        <input class="form-control" id="year_start" type="text" name="year_start">
                                    </div>
                                </div>
                            </div>
                            <div id="transaction_weekly" class="hide">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span class="input-group-text" id="basic-addon1">Suku</span>
                                    </div>
                                    <input class="form-control x-uppercase" id="quarter_start" type="number"
                                        name="quarter_start" min="1" max="4" value="1">
                                    <div class="input-group-addon">Tahun</div>
                                    <input class="form-control" id="year_quarter" type="text" name="year_quarter">
                                </div>
                            </div>
                            <div id="transaction_monthly" class="hide">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span class="input-group-text" id="basic-addon1">Tarikh</span>
                                    </div>
                                    <input class="form-control x-uppercase" id="monthly_start" type="text"
                                        name="monthly_start">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-sm hidden-print">Jana</button>
                    </form>
                    <div id="chart_transactions" style="width: 100%; height: 350px;" class="mt-3 mb-5"></div>
                    <hr>
                    <div id="chart_transactions_value" style="width: 100%; height: 350px;" class="mt-3 mb-5"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.14.3/xlsx.full.min.js"></script>
    <script src="{{ asset('js/jspdf.umd.min.js') }}"></script>
    <script src="{{ asset('js/html2canvas.min.js') }}"></script>
    <script src="{{ asset('packages/echarts/dist/echarts.js') }}"></script>
    <script src="{{ asset('packages/echarts/theme/shine.js') }}"></script>
    <script src="{{ asset('js/dashboard-chart.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            @if ($view == 'chart')
                var user_active = "{{ App\User::active()->count() }}";
                var user_unactive = "{{ App\User::notActive()->count() }}";
                var vendor_active = "{{ App\Vendor::activeSubscriptionCount() }}";
                var vendor_unactive = "{{ App\Vendor::nonActiveSubscriptionCount() }}";
                var vendor_unregister = "{{ App\Vendor::pendingRegistrationCount() }}";

                $('#li_default').removeClass('active');
                $('#li_chart').addClass('active');
                $('.default-dashboard').hide();
                $('.chart-dashboard').show();

                $('#tender_view_type').change(function() {
                    var view = $(this).val();
                    const ids = ['tender_yearly', 'tender_weekly', 'tender_monthly'];
                    ids.forEach(function(item, index) {
                        if (ids[index] == view) {
                            $('#' + ids[index]).removeClass('hide');
                        } else {
                            $('#' + ids[index]).addClass('hide');
                        }
                    });
                });

                $('#transaction_view_type').change(function() {
                    var view = $(this).val();
                    const ids = ['transaction_yearly', 'transaction_weekly', 'transaction_monthly'];
                    ids.forEach(function(item, index) {
                        if (ids[index] == view) {
                            $('#' + ids[index]).removeClass('hide');
                        } else {
                            $('#' + ids[index]).addClass('hide');
                        }
                    });
                });

                userChart(user_active, user_unactive);
                vendorChart(vendor_active, vendor_unactive, vendor_unregister);
                dashboardTender();
                dashboardTransaction();
            @else
                $('#li_default').addClass('active');
                $('#li_chart').removeClass('active');
                $('.default-dashboard').show();
                $('.chart-dashboard').hide();

                dashboardTenderSummary();
                dashboardTransactionSummary();
            @endif

        });

        $('#tender_summary').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            dashboardTenderSummary(formData);
        });

        $('#tender_form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            dashboardTender(formData);
        });

        $('#transaction_summary').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            dashboardTransactionSummary(formData);
        });

        $('#transaction_form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            dashboardTransaction(formData);
        });

        function dashboardTenderSummary(formData) {

            $.ajax({
                url: "{{ route('dashboard.tender.summary') }}",
                type: "post",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $("#tenderCount h2").text('Loading..');
                    $("#quotationCount h2").text('Loading..');
                    $("#tenderTotalCount h2").text('Loading..');
                },
                success: function(response) {
                    $("#tenderCount h2").text(response.tender_count);
                    $("#quotationCount h2").text(response.quotation_count);
                    $("#tenderTotalCount h2").text(response.total_tender);
                }
            })
        }

        function dashboardTender(formData) {

            $.ajax({
                url: "{{ route('dashboard.tender') }}",
                type: "post",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    tendersChart.showLoading();
                },
                success: function(response) {
                    tenderChart(response);
                    tendersChart.hideLoading();
                }
            })
        }

        function dashboardTransactionSummary(formData) {

            $.ajax({
                url: "{{ route('dashboard.transaction.summary') }}",
                type: "post",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $("#subscriptionCount h2").text('Loading..');
                    $("#purchaseCount h2").text('Loading..');
                    $("#transactionTotal h2").text('Loading..');
                },
                success: function(response) {
                    $("#subscriptionCount h2").text(response.subscription_count);
                    $("#purchaseCount h2").text(response.purchase_count);
                    $("#transactionTotal h2").text(response.total_transaction);
                }
            });

            $.ajax({
                url: "{{ route('dashboard.transaction-value.summary') }}",
                type: "post",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $("#subscriptionValueSum h2").text('Loading..');
                    $("#purchaseValueSum h2").text('Loading..');
                    $("#transactionValueTotal h2").text('Loading..');
                },
                success: function(response) {
                    $("#subscriptionValueSum h2").text('RM' + response.subscription_sum);
                    $("#purchaseValueSum h2").text('RM' + response.purchase_sum);
                    $("#transactionValueTotal h2").text('RM' + response.total_transaction);
                }
            });
        }

        function dashboardTransaction(formData) {

            $.ajax({
                url: "{{ route('dashboard.transaction') }}",
                type: "post",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    transactionsChart.showLoading();
                },
                success: function(response) {
                    transactionChart(response);
                    transactionsChart.hideLoading();
                }
            });

            $.ajax({
                url: "{{ route('dashboard.transaction-value') }}",
                type: "post",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    transactionsValueChart.showLoading();
                },
                success: function(response) {
                    transactionValueChart(response);
                    transactionsValueChart.hideLoading();
                }
            });
        }
    </script>
@endsection
