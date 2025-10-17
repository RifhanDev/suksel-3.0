@extends('layouts.default')
@section('content')
    <h2>Pemulangan Semula {{ isset($subtitle) ? ': ' . $subtitle : ': Selesai Pemulangan Semula' }}</h2>
    <br>
    @include('refunds.request._snaps')
    <table data-path="{{ action('RefundController@index_request') }}{{ isset($status) ? '?state=' . $status : '' }}"
        class="DT-index table table-striped table-hover table-bordered">
        <thead class="bg-blue-selangor">
            <tr>
                <th>Bil.</th>
                <th>No. Rujukan</th>
                <th>Tarikh Permohonan</th>
                <th>
                    {{ (isset($status)) ? $date_col : 'Tarikh Terima Bukti' }}
                </th>
                <th>Status</th>
                <th>Amaun</th>
                <th width="80px">&nbsp;</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
@endsection

@section('scripts')
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script type="text/javascript">
        $('.DT-index').each(function() {
            var target = $(this);
            var path = target.data('path');
            var DT = target.DataTable({
                ajax: path,
                columns: [{
                        data: 'id',
                        name: null
                    },
                    {
                        data: 'number',
                        name: 'number'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ],
                // processing: true,
                serverSide: true,
                stateSave: true,
                language: {
                    sEmptyTable: "Tiada data",
                    sInfo: "Paparan dari _START_ hingga _END_ dari _TOTAL_ rekod",
                    sInfoEmpty: "Paparan 0 hingga 0 dari 0 rekod",
                    sInfoFiltered: "(Ditapis dari jumlah _MAX_ rekod)",
                    sInfoPostFix: "",
                    sInfoThousands: ",",
                    sLengthMenu: "Papar _MENU_ rekod",
                    sLoadingRecords: "Diproses...",
                    sProcessing: "Sedang diproses...",
                    sSearch: "Carian:",
                    sZeroRecords: "Tiada padanan rekod yang dijumpai.",
                    oPaginate: {
                        sFirst: "Pertama",
                        sPrevious: "Sebelum",
                        sNext: "Kemudian",
                        sLast: "Akhir"
                    },
                    oAria: {
                        sSortAscending: ": diaktifkan kepada susunan lajur menaik",
                        sSortDescending: ": diaktifkan kepada susunan lajur menurun"
                    }
                },
                aaSorting: [],
                // columnDefs: [{
                // "searchable": false,
                // "orderable": false,
                // "targets": 0
                // }, {
                // "targets": 1,
                // "name" : "vendors.registration"
                // }],
                // "order": [[1, 'asc']],
                fnDrawCallback: function(oSettings) {
                    start = oSettings.oAjaxData.start + 1;
                    DT.column(0).nodes().to$().each(function(index) {
                        $(this).text(start + index);
                    });
                }
            });
        });
    </script>
@endsection
