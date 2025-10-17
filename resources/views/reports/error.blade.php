@extends('layouts.report')
@section('styles')

	<link href="{{ asset('css/report.css') }}" rel="stylesheet">

@endsection
@section('content')
	<h4 class="tender-title">Laporan Sistem Tender Online: {{ $title }}</h4>

	<div class="alert alert-danger">
	  {{ $error }}
	</div>
@endsection