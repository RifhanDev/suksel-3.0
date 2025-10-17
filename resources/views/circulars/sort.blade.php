@extends('layouts.default')
@section('styles')
    <style>
        li:hover {
            cursor: move;
        }
    </style>
@endsection
@section('content')
    <h2 class="tender-title">Kemaskini Susunan Pekeliling</h2>

    <ul id="simpleList" class="list-group">
        @foreach ($circulars as $circular)
            <li data-id="{{ $circular->id }}" class="list-group-item">{{ $circular->title }}</li>
        @endforeach
    </ul>

    <div class="well">
        <button class="btn btn-primary" id="saveCurrOrder">Simpan</button>
        <button class="btn btn-danger" id="resetOrder">Set Semula</button>
        <a href="{{ asset('circulars') }}" class="btn btn-default pull-right">Senarai Pekeliling</a>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/sortable.js') }}"></script>
    <script type="text/javascript">
        var simpleList = document.getElementById('simpleList');

        // create sortable and save instance
        var sortable = Sortable.create(simpleList, {
            animation: 150
        });

        // save initial order
        var initialOrder = sortable.toArray();

        document.getElementById('saveCurrOrder').addEventListener('click', function(e) {
            var order = sortable.toArray();

            $.post('{{ route('circulars.update.position') }}', {
                order: order
            }).success(function(response) {
                window.location.href = '{{ route("circulars.index") }}';
            });
        });

        document.getElementById('resetOrder').addEventListener('click', function(e) {
            sortable.sort(initialOrder);
        })
    </script>
@endsection
