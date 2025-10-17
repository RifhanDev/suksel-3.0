@extends('layouts.default')
@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('css/form.css') }}" rel="stylesheet">
@endsection
@section('content')
    @include('tenders._menu')

    <div class="tender-ref-number">{{ $tender->ref_number }}</div>
    <h2 class="tender-title">{{ $tender->name }}</h2>

    <hr>

    {!! Former::open_for_files(url('tenders/' . $tender->id))->addClass('jq-validate') !!}
    {!! Former::populate($tender) !!}
    {!! Former::hidden('_method', 'PUT') !!}
    @include('tenders.form')

    <div class="well">
        <a href="#" type="submit" id="submit" class="btn btn-primary">Simpan</a>
        <button type="button" id="next" class="btn btn-primary">Seterusnya</button>
    </div>
    {!! Former::close() !!}
@endsection
