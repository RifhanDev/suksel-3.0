@if (App\Models\Refund::canList())
    <div class="row">
        <div class="col-sm-4">
            <div class="panel panel-primary text-center">
                <div class="panel-body">
                    <h1>{{ number_format(App\Models\Refund::pendingRefundComplaintCount(), 0) }}</h1>
                </div>
                <div class="panel-heading">
                    <a href="{{ action('RefundController@pendingRefundComplaintIndex') }}" style="color:white">
                        Aduan Baru
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-primary text-center">
                <div class="panel-body">
                    <h1>{{ number_format(App\Models\Refund::rejectRefundComplaintCount(), 0) }}</h1>
                </div>
                <div class="panel-heading">
                    <a href="{{ action('RefundController@rejectRefundComplaintIndex') }}" style="color:white">
                        Aduan Ditolak
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-primary text-center">
                <div class="panel-body">
                    <h1>{{ number_format(App\Models\Refund::successRefundComplaintCount(), 0) }}</h1>
                </div>
                <div class="panel-heading">
                    <a href="{{ asset('refunds/complaint') }}" style="color:white">
                        Selesai Pemulangan Semula
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
