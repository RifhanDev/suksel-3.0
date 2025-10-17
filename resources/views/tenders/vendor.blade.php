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
        @include('tenders._menu')

        <div class="tender-ref-number">{{ $tender->ref_number }}</div>
        <h2 class="tender-title">{{ $tender->name }}</h2>

        @include('tenders._notification')

        @if ($tender->canShowTabs())
            <ul class="nav nav-tabs nav-justified hidden-print">
                <li class=" @if (isset($active_sebut_harga_tab)) {{ $active_sebut_harga_tab }} @endif"><a
                        href="{{ asset('tenders/' . $tender->id) }}">Maklumat {{ App\Tender::$types[$tender->type] }}</a>
                </li>
                <li class="@if (isset($active_sebut_harga_tab)) {{ $active_sebut_harga_tab }} @endif"><a
                        href="{{ asset('tenders/' . $tender->id . '/vendors') }}">Maklumat Syarikat</a></li>
                @if (Auth::check() &&
                        $tender->canException() &&
                        auth()->user()->ability(['Admin'], ['ExceptionTender:list']))
                    <li><a href="{{ asset('tenders/' . $tender->id . '/vendors') }}">Maklumat Kebenaran Khas</a></li>
                @endif
            </ul>
        @endif

        <h2 class="pull-left">Maklumat Syarikat <span class="label label-default">{{ $vendor->name }}</span></h2>
        <a href="{{ asset('tenders/' . $tender->id . '/vendors') }}" class="btn btn-primary pull-right"><span
                class="glyphicon glyphicon-chevron-left"></span> Kembali</a>
        <div class="clearfix"></div>
        <br>

        @include('vendors.vendor')
    </div>

    <div id="right-pane" style="display: none;">
        <button class="btn btn-sm btn-danger pull-right mb-1 btn-file-close">Tutup</button>
        <div id="doc-view"></div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/pdfobject.min.js') }}"></script>
    <script src="{{ asset('js/show.js') }}"></script>
    <script src="{{ asset('js/displayfile.js') }}"></script>
    <script type="text/javascript">
        $("#vendor_ids").selectize({
            valueField: 'id',
            labelField: 'name',
            searchField: 'name',
            create: false,
            render: {
                option: function(item, escape) {
                    return '<div><strong>' + escape(item.registration) + '</strong> ' + escape(item.name) +
                        '</div>';
                }
            },
            load: function(query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/vendors/select?q=' + query,
                    type: 'GET',
                    success: function(res) {
                        callback(res);
                    },
                    error: function() {
                        callback();
                    }
                })
            }
        });
    </script>
@endsection
