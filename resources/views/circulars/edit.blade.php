@extends('layouts.default')
@section('styles')
    <link href="{{ asset('css/form.css') }}" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.2.8/pdfobject.min.js"></script>
    <style>
        .pdfobject-container {
            height: 60rem;
        }
    </style>
@endsection
@section('content')
    <h2 class="tender-title">Kemaskini Pekeliling</h2>

    {!! Former::open_for_files(url('circulars/' . $circular->id)) !!}
    {!! Former::hidden('_method', 'PUT') !!}
    @include('circulars.form')

    <div class="well">
        {!! Former::submit('Kemaskini')->class('btn btn-primary') !!}
        @if ($circular->file)
            <a href=" {{ $circular->file->url . '/' . $circular->file->name }}" class="btn btn-success btn-show-circular"
                target="_blank">Lihat Pekeliling</a>
        @endif
        <a href="{{ asset('circulars') }}" class="btn btn-default pull-right">Senarai Pekeliling</a>
    </div>
    {!! Former::close() !!}
@endsection
