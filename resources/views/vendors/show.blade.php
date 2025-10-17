@extends('layouts.default')

@section('styles')
    <style>
        .pdfobject-container {
            height: 70rem;
        }
    </style>
@endsection
@section('content')

    <div id="left-pane">
        <h2>
            Maklumat Syarikat
            <span class="label label-default">{{ $vendor->name }}</span>
            <span class="label label-success pull-right">{{ $vendor->status }}</span>
        </h2>
        <br>
        @include('vendors.vendor')

        <div class="well">
            @if ($vendor->canUpdate())
                <div class="btn-group">
                    <a href="{{ action('VendorsController@edit', $vendor->id) }}" class="btn btn-default">Kemaskini</a>

                    @if ($vendor->canUpdate2())
                        <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="{{ action('VendorsController@editEmail', $vendor->id) }}"
                                    class="btn btn-default">Emel
                                    / No. Pendaftaran</a></li>
                        </ul>
                    @endif
                </div>
            @endif

            @if ($vendor->canConfirm())
                <a href="{{ action('UsersController@resendConfirmation', $vendor->user->id) }}"
                    class="btn btn-default">Hantar
                    Emel Pengesahan</a>
            @endif

            @if ($vendor->canApprove())
                <a href="{{ action('VendorsController@approve', [$vendor->id]) }}"
                    class="btn btn-primary link-confirm">Lulus</a>
                <button type="button" id="reject" class="btn btn-danger">Tolak</button>
            @endif

            @if (Auth::user()->can('User:login'))
                <a href="{{ asset('users/' . $vendor->user->id . '/login') }}" class="btn btn-danger link-confirm">Login
                    Sebagai</a>
            @endif

            @if ($vendor->canUpdate())
                <a href="{{ action('UsersController@getSetPassword', $vendor->user->id) }}" class="btn btn-warning">Tukar
                    Kata
                    Laluan</a>
            @endif

            @if (Auth::user()->can('CodeRequest:list'))
                <a href="{{ asset('vendor/' . $vendor->id . '/requests') }}" class="btn btn-primary">Permintaan
                    Kemaskini</a>
            @endif

            @if (App\VendorBlacklist::canList())
                <a href="{{ asset('vendor/' . $vendor->id . '/blacklists') }}" class="btn btn-default">Senarai Hitam</a>
            @endif

            @if (Auth::user()->can('Vendor:histories'))
                <div class="btn-group">
                    <a href="{{ action('VendorsController@histories', $vendor->id) }}" class="btn btn-default">Sejarah
                        Kemaskini</a>

                    <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="{{ action('UsersController@histories', $vendor->user->id) }}"
                                class="btn btn-default">Aktiviti Pengguna</a></li>
                    </ul>
                </div>
            @endif


            @if (App\Vendor::canList())
                <a href="{{ action('VendorsController@index') }}" class="btn btn-default pull-right">Senarai Syarikat</a>
            @endif
        </div>
    </div>

    <div id="right-pane" style="display: none;">
        <button class="btn btn-sm btn-danger pull-right mb-1 btn-file-close">Tutup</button>
        <div id="doc-view"></div>
    </div>

    @if ($vendor->canApprove())
    @include('vendors.reject-modal')
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

                    if (result && (reason != '' || template.length != 0)) {
                        // console.log($(".bootbox-body #myForm").serialize());
                        $.post('/vendor/{{ $vendor->id }}/reject', {
                                reason: reason,
                                template: template
                            })
                            .success(function() {
                                window.location.href = '{{ route('vendors.show', $vendor->id) }}';
                            })
                    }
                }
            });
        });
    </script>
@endsection
