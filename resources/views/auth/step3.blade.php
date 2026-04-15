@extends('layouts.v3.master')
@section('content')
	<h2>Step 3: Details Verification</h2>
	<hr>
	<?php $current_step = 3; ?>
	@include('auth.registration_steps')
	<br>
@stop
