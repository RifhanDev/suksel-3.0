@extends('layouts.default')
@section('styles')

	<link href="{{ asset('css/form.css') }}" rel="stylesheet">

@endsection
@section('content')

	<div class="row">
    	<div class="col-lg-9">
        	<h1 class="tender-title">
            Bantuan

            @if(Auth::user() && Auth::user()->hasRole('Admin'))
            	<div class="btn-group pull-right">
                	<a href="{{ asset('helpcategories') }}" class="btn btn-sm btn-warning"><i class="fa fa-tags"></i> Kategori</a>
                	<a href="{{ asset('helpcategories') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah Soalan Lazim</a>
            	</div>
            @endif
        	</h1>

        	<div class="row stacked-form">
            {!! Former::open(action('HelpsController@search'))->class('form-inline pull-right form-search')->method('GET') !!}
                @csrf
                <input type="text" name="q" class="form-control" placeholder="Carian...">
            {!! Former::close() !!}
            <div class="clearfix"></div>

            @if(count($categories) == 0)
            	<div class="alert alert-info">Tiada soalan lazim.</div>
            @else
            	<div class="row">
                	@foreach($categories as $category)
                		<div class="col-lg-6"><a href="{{ asset('helps/'.$category->id) }}"><div class="well">
                    		<strong>{{ $category->name }}</strong> <small>({{ $category->helps->count() }} artikel)</small><br>
                    		{{ $category->description }}
                		</div></a></div>
                	@endforeach
            	</div>
            @endif
        	</div>
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