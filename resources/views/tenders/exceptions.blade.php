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
        @include('tenders._menu')

        <div class="tender-ref-number">{{ $tender->ref_number }}</div>
        <h2 class="tender-title">{{ $tender->name }}</h2>

        @include('tenders._notification')

        @if (Auth::user() && $tender->canShowTabs())
            <ul class="nav nav-tabs nav-justified">
                <li><a href="{{ asset('tenders/' . $tender->id) }}">Maklumat Tender / Sebut Harga</a></li>
                <li><a href="{{ asset('tenders/' . $tender->id . '/vendors') }}">Maklumat Syarikat</a></li>
                @if (Auth::check() &&
                        $tender->canException() &&
                        auth()->user()->ability(['Admin', 'Agency Admin', 'Agency User'], ['ExceptionTender:list']))
                    <li class="active"><a href="{{ asset('tenders/' . $tender->id . '/exceptions') }}">Maklumat Kebenaran Khas
                            <span class="badge">{{ $tender->exceptions()->where('status', 0)->count() }}</span></a></li>
                @endif
            </ul>
        @endif

        <br>
        @if (count($exceptions) > 0)
            <table class="table table-bordered">
                <thead class="bg-blue-selangor">
                    <tr>
                        <th>Bil.</th>
                        <th>Nama Syarikat</th>
                        <th>Tarikh Permohonan</th>
                        <th>Status</th>
                        <th>Fail</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($exceptions as $exception)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>
                                <strong>{{ $exception->vendor->name }}</strong>
                            </td>
                            <td>
                                {{ $exception->updated_at ? Carbon\Carbon::parse($exception->updated_at)->format('d/m/Y') : Carbon\Carbon::parse($exception->created_at)->format('d/m/Y') }}
                            </td>
                            <td>
                                @if ($exception->status == 2)
                                    {{ $exception->getStatus() }} <br> Alasan :- <br>
                                    @if ($exception->rejection_reason)
                                        Catatan : {{ $exception->rejection_reason }}
                                    @endif
                                    @if ($exception->rejection_template_id)
                                        <br>
                                        <ol>
                                            @foreach (json_decode($exception->rejection_template_id, true) as $reject_id)
                                                @foreach ($templates as $template)
                                                    @if ($template['id'] == $reject_id)
                                                        <li style="text-decoration: underline;">
                                                            {{ $template['title'] }}
                                                        </li>
                                                        {!! $template['content'] !!}
                                                    @endif
                                                @endforeach
                                            @endforeach
                                    @endif
                                @else
                                    {{ $exception->getStatus() }}
                                @endif
                            </td>
                            </td>
                            <td>
                                @forelse ($exception->files as $key => $value)
                                    <button class="btn btn-warning btn-file-view"
                                        data-url="{{ $value->url . '/' . $value->name }}">Lihat</button>
                                @empty
                                    Tiada Fail
                                @endforelse
                            </td>
                            <td>
                                @if ($exception->status == 0 && $exception->canApprove())
                                    <a href="{{ action('TendersController@approve_exception', [$exception->id]) }}"
                                        class="btn btn-primary link-confirm">Lulus</a>
                                    <button type="button" id="reject" data-id="{{ $exception->id }}"
                                        class="btn btn-danger">Tolak</button>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($exception->canApprove())
                @include('tenders.reject-modal')
            @endif
        @else
            <div class="alert alert-info">Tiada Syarikat Memohon Kebenaran Khas.</div>
        @endif

    </div>
    <div id="right-pane" style="display: none;">
        <button class="btn btn-sm btn-danger pull-right mb-1 btn-file-close">Tutup</button>
        <div id="doc-view"></div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.2.8/pdfobject.min.js"></script>
    <script src="{{ asset('js/displayfile.js') }}"></script>
    <script type="text/javascript">
        $('body').tooltip({
            selector: '[data-toggle="tooltip"]'
        });
    </script>
    <script>
        var form = $("#rejectForm").html();

        $('#reject').click(function(e) {
            var exception_id = $(this).data("id");
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

                    var post = '/tenders/{{ $tender->id }}/reject/' + exception_id;
                    var redirect = '{{ route('tender.exceptions', $tender->id) }}';

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
