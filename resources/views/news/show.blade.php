@extends('layouts.default')
@section('content')
	<div class="news-huge">
    	<a href="{{ asset('news/'.$news->id) }}"><h1>{{ $news->title }}</h1></a>	
    	<p class="meta">
        	{{ \Carbon\Carbon::parse($news->published_at ?: $news->created_at)->format('j M Y') }} &bullet;
        	Oleh <a href="{{ asset('agencies/'.$news->agency->id) }}">{{ $news->agency->name }}</a>	
        	@if($news->tender) untuk <a href="{{ asset('tenders/'.$news->tender->id) }}">{{ $news->tender->name }}</a>@endif
    	</p>

    	<div class="content">{!! $news->notification !!}</div>

    	<div class="well">
      	<a href="{{ asset('news') }}" class="btn btn-default">Arkib Berita</a>
      		@if($news->canUpdate())
        			<a href="{{ asset('news/'.$news->id.'/edit') }}" class="btn btn-primary">Kemaskini</a>
	        		@if(!$news->publish)<a href="{{ asset('news/'.$news->id.'/publish') }}" class="btn btn-warning link-confirm">Siar</a>@endif
	        		@if($news->publish)<a href="{{ asset('news/'.$news->id.'/publish') }}" class="btn btn-danger link-confirm">Batal Siar</a>@endif

	        		@if($news->canDelete())
	            	{!! Former::open(action('NewsController@destroy', $news->id))->class('form-inline') !!}
	                	{!! Former::hidden('_method', 'DELETE') !!}
	                	<button type="button" class="btn btn-danger confirm-delete">Padam</button>
	            	{!! Former::close() !!}
	        		@endif
      		@endif
    	</div>
	</div>
@endsection