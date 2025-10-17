@extends('layouts.default')

@section('styles')
    <style>
        .pdfobject-container {
            height: 60rem;
        }
    </style>
@endsection

@section('content')
    <div id="left-pane">
        <h2>
            Maklumat Pemulangan Semula
            <span class="label label-default">{{ $refund->ref_no }}</span>
            <span class="label label-success pull-right">{{ $refund->refundStatus() }}</span>
        </h2>
        <br>
        @include('refunds.refund')
        <div class="well">
            @if ($refund->page == 'vendor')
                <a href="/dashboard" class="btn btn-default">Kembali</a>
                @if ($refund->status == 2)
                    <a href="{{ route('refunds.edit',$refund->id) }}" class="btn btn-primary">Kemaskini</a>
                @endif
            @endif

            @if ($refund->canApprove() && !in_array($refund->status, [2, 3, 4]))
                @if ($refund->status == 0 && $refund->page == 'request')
                    <a href="{{ action('RefundController@approve_request', [$refund->id]) }}"
                        class="btn btn-primary link-confirm">Lulus</a>
                    <button type="button" id="reject" class="btn btn-danger">Tolak</button>
                @elseif ($refund->status == 1 && $refund->page == 'complaint')
                    <a href="{{ action('RefundController@approve_complaint', [$refund->id]) }}"
                        class="btn btn-primary link-confirm">Terima Bukti</a>
                    <button type="button" id="reject" class="btn btn-danger">Tolak</button>
                @endif
            @endif

            @if ($refund->canList())
                <a href="{{ action('RefundController@index_request') }}" class="btn btn-default">Senarai
                    Permohonan Pemulangan Semula</a>
            @endif

            @if ($refund->isRoleBKP())
                <a href="{{ action('RefundController@index_complaint') }}" class="btn btn-default">Senarai
                    Aduan Pemulangan Semula</a>
            @endif
        </div>
    </div>


    <div id="right-pane" style="display: none;">
        <button class="btn btn-sm btn-danger pull-right mb-1 btn-file-close">Tutup</button>
        <div id="doc-view"></div>
    </div>

    @if ($refund->canApprove())
        @include('refunds.reject-modal')
    @endif
@endsection

@section('scripts')
    <script src="{{ asset('js/pdfobject.min.js') }}"></script>
    <script src="{{ asset('js/show.js') }}"></script>
    <script src="{{ asset('js/displayfile.js') }}"></script>
    <script src="{{ asset('js/vendor.js') }}"></script>
    <script type="text/javascript">
        $('input:not([type=hidden]),select,textarea', 'form').attr({
            disabled: false,
            readonly: false
        });
        $('body').tooltip({
            selector: '[data-toggle="tooltip"]'
        });
    </script>
    <script>
        var form = $("#rejectForm").html();

        $('#reject').click(function(e) {
            dialog = bootbox.confirm({
                message: form,
                buttons: {
                    'cancel': {
                        label: 'Batal',
                        className: 'btn-default'
                    },
                    'confirm': {
                        label: 'Tolak',
                        className: 'btn-primary'
                    }
                },
                callback: function(result) {
                    var reason = dialog[0].querySelector("[name=reason]").value;
                    var template = Array.from(dialog[0].querySelectorAll(
                        "input[type=checkbox][name=template]:checked"), e => e.value);

                    var page = '{{ $refund->page }}';
                    if (page == 'request') {
                        var post = '{{ route('refunds.request.reject', $refund->id) }}';
                        var redirect = '{{ route('refunds.request.show', $refund->id) }}';
                    } else if (page == 'complaint') {
                        var post = '{{ route('refunds.complaint.reject', $refund->id) }}';
                        var redirect = '{{ route('refunds.complaint.show', $refund->id) }}';
                    }

                    if (result && (reason != '' || template.length != 0)) {
                        $.post(post, {
                                reason: reason,
                                template: template
                            })
                            .success(function() {
                                window.location.href = redirect;
                            })
                    }
                }
            });
        });
    </script>
@endsection
