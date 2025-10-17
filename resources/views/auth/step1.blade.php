@extends('layouts.default')
@section('content')
<h2>Step 1: UPEN Status Check</h2>
<hr>
<?php $current_step = 1; ?>
@include('auth.registration_steps')
<br>
<br>
{{Former::open(action('AuthController@post_step1'))}}
    
    {{Former::text('registration_number')
        ->label('Company Registration Number')
        ->placeholder('12345X')
        ->required()
        ->help('Key in your company registration number for us to check registration status with UPEN')}}
    
    {{Former::email('email')
        ->label('Company Email')
        ->required()
        ->help('Your company\'s email address. A verification link will be sent to this address to verify your email.')}}

    {{Former::submit('Check Status')
        ->class('btn btn-primary btn-raised')}}

{{Former::close()}}
<br>
<p class="text-center">
    {{link_to_action('AuthController@forgotPassword', 'Forgot Password')}} |
    {{link_to_action('HomeController@index', 'Login')}}
</p>
@stop
