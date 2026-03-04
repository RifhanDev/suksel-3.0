@extends('layouts.v3.master')
@section('content')
	<h2>New Transaction</h2>
	<hr>
	{{ Former::open(route('transactions.store')) }}
	@include('transactions.form')
	@include('transactions.actions-footer', ['has_submit' => true])
@stop
