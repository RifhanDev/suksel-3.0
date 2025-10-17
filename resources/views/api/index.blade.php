@extends('layouts.default')

@section('styles')
    <style>
        .pdfobject-container {
            height: 60rem;
        }
    </style>
@endsection
@section('content')
    <h2 class="tender-title">Senarai API Token</h2>

    <table data-path="/apitoken" class="DT-index table table-striped table-hover table-bordered">
        <thead class="bg-blue-selangor">
            <tr>
                <th>Nama Agensi</th>
                <th>Token</th>
                <th>Status</th>
                <th>Tarikh Dijana</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <div class="well">
        <a href="{{ asset('apitoken/create') }}" class="btn btn-default">Masukkan Token Agensi Baru</a>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/datatables.js') }}"></script>

    <script>
        $('.DT-index').each(function() {
            var target = $(this);
            var path = target.data('path');

            var DT = target.DataTable({
                order: [
                    [2, 'asc']
                ],
                ajax: path,
                columns: [{
                        data: 'organization_unit_id',
                        name: 'organization_unit_id'
                    },
                    {
                        data: 'token',
                        name: 'token'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    }
                ],
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
                aaSorting: []
            });
        });
    </script>
@endsection
