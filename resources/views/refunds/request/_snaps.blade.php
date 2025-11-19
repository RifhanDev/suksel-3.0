@if (App\Models\Refund::canList())
    <div class="row">
        <div class="col-sm-3">
            <div class="panel panel-primary text-center">
                <div class="panel-body">
                    <h1>{{ number_format(App\Models\Refund::pendingRefundRequestCount(), 0) }}</h1>
                </div>
                <div class="panel-heading">
                    <a href="{{ action('RefundController@pendingRefundRequestIndex') }}" style="color:white">
                        Permohonan Baru
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel panel-primary text-center">
                <div class="panel-body">
                    <h1>{{ number_format(App\Models\Refund::processRefundRequestCount(), 0) }}</h1>
                </div>
                <div class="panel-heading">
                    <a href="{{ action('RefundController@processRefundRequestIndex') }}" style="color:white">
                        Permohonan Dalam Proses
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel panel-primary text-center">
                <div class="panel-body">
                    <h1>{{ number_format(App\Models\Refund::rejectRefundRequestCount(), 0) }}</h1>
                </div>
                <div class="panel-heading">
                    <a href="{{ action('RefundController@rejectRefundRequestIndex') }}" style="color:white">
                        Permohonan Ditolak
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel panel-primary text-center">
                <div class="panel-body">
                    <h1>{{ number_format(App\Models\Refund::successRefundComplaintCount(), 0) }}</h1>
                </div>
                <div class="panel-heading">
                    <a href="{{ asset('refunds/request') }}" style="color:white">
                        Selesai Pemulangan Semula
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
