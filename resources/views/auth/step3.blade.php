@extends('layouts.default')
@section('content')
<h2>Step 3: Details Verification</h2>
<hr>
<?php $current_step = 3; ?>
@include('auth.registration_steps')
<br>
@stop
