@extends('layouts.default')

@section('content')
	<div class="row">
	   <div class="col-lg-9">
	        	<h1 class="tender-title">
	            Bantuan

	            @if(Auth::user() && Auth::user()->hasRole('Admin'))
	            <div class="btn-group pull-right">
	                	<a href="{{ asset('helpcategories') }}" class="btn btn-sm btn-warning"><i class="fa fa-tags"></i> Kategori</a>
	                	<a href="{{ asset('helps/create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah Soalan Lazim</a>
	            </div>
	            @endif
	        	</h1>

	       	<h4 class="pull-left">{{ $category->name }} <small>({{ $helps->count() }} artikel) <br> {{ $category->description }}</small></h4>

	        	{!! Former::open(action('HelpsController@search'))->class('form-inline pull-right form-search')->method('GET') !!}
	            <input type="text" name="q" class="form-control" placeholder="Carian...">
	         {!! Former::close() !!}
	        	<div class="clearfix"></div>

	        		@if(count($helps) == 0)
	        			<div class="alert alert-info">Tiada soalan lazim.</div>
	        		@else

		        		<div class="panel-group" id="helps" role="tablist" aria-multiselectable="true">
		            	@foreach($helps as $help)
		            		@include('helps.helps')
		            	@endforeach
		        		</div>
	       		 @endif
	   </div>

	   <div class="col-lg-3">
	        	@include('layouts._register')
	        	@include('layouts._news')
	   </div>
	</div>
@endsection


@section('scripts')

	<script src="{{ asset('js/news.js') }}"></script>

@endsection