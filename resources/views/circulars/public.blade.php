@extends('layouts.default')

@section('styles')
    <style>
        .pdfobject-container {
            height: 80rem;
        }
    </style>
@endsection
@section('content')
    <h2 class="tender-title">Senarai Pekeliling</h2>

    <div class="row">
        <div class="col-md-3">
            <ul class="list-group">
                @forelse ($circulars as $circular)
                    <a class="list-group-item list-group-item-primary btn-circular-view" id="circular-{{ $circular->id }}"
                        data-id="{{ $circular->id }}"
                        data-url="{{ ($circular->pdf_link) ? $circular->pdf_link : $circular->file->url . '/' . $circular->file->name }}">{{ $circular->title }}
                        <i class="fa fa-caret-right pull-right"></i></a>
                @empty
                    <div class="alert alert-info">Tiada Pekeliling</div>
                @endforelse
            </ul>
        </div>
        <div class="col-md-9">
            <div id="doc-view"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.2.8/pdfobject.min.js"></script>
    <script src="{{ asset('js/displayfile.js') }}"></script>
@endsection
