@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-sm-6 col-sm-offset-3">
        <h1 class="text-center">Ralat! Sistem Menghadapi Masalah.</h1>
        <br>
        <p class="text-center">Sila hubungi kami melalui email ke tenderadmin@selangor.gov.my.</p>
        <div class="well">{{ $exception }}</div>
    </div>
@stop
