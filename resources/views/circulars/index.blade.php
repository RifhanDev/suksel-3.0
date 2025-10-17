@extends('layouts.default')

@section('styles')
    <style>
        .pdfobject-container {
            height: 60rem;
        }
    </style>
@endsection
@section('content')
    <h2 class="tender-title">Senarai Pekeliling</h2>

    <table data-path="/circulars" class="DT-index table table-striped table-hover table-bordered">
        <thead class="bg-blue-selangor">
            <tr>
                <th>Tajuk</th>
                <th>Siar</th>
                <th>Susunan</th>
                <th>Tarikh Muat Naik</th>
                <th width="200px">&nbsp;</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <div class="well">
        <a href="{{ asset('circulars/create') }}" class="btn btn-default">Masukkan Pekeliling Baru</a>
        <a href="{{ asset('circulars/sort') }}" class="btn btn-default">Kemaskini Susunan Pekeliling</a>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.2.8/pdfobject.min.js"></script>
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
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'published',
                        name: 'published'
                    },
                    {
                        data: 'position',
                        name: 'position'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'actions',
                        name: 'actions'
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
