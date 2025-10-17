@extends('layouts.default')
@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('css/form.css') }}" rel="stylesheet">
    <style>.hr-with-label {
        text-align: center;
        position: relative;
      }
      
      .hr-with-label hr {
        display: block;
        margin: 0;
        border: none;
        border-top: 1px solid #eee; /* You can adjust the color here */
        position: relative;
      }
      
      .hr-with-label .label {
        position: absolute;
        top: 50%;
        background-color: #fcfcfa; /* Background color of the label */
        padding: 0 10px; /* Adjust the padding as needed */
        color: #818078; /* Color of the label text */
        transform: translate(0, -50%);
      }
      
    </style>
@endsection
@section('content')
    <h2 class="tender-title">
        Tambah Tender / Sebutharga
        @if (Auth::user()->hasRole('Admin'))
            <a href="{{ asset('tenders') }}" class="btn btn-sm blue pull-right"><i class="fa fa-tags"></i> Senarai Tender</a>
            </li>
        @else
            <a href="{{ asset('agencies/' . Auth::user()->organization_unit_id) }}" class="btn btn-sm blue pull-right"><i
                    class="fa fa-tags"></i> Senarai Tender</a></li>
        @endif
    </h2>
    {!! Former::open_for_files(url('tenders'))->addClass('jq-validate') !!}
    @include('tenders.form')
    <div class="well">
        <a href="#" type="submit" id="submit" class="btn btn-primary">Hantar</a>
        <button type="button" id="next" class="btn btn-primary">Seterusnya</button>
    </div>
    {!! Former::close() !!}
@endsection
