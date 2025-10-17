@extends('layouts.default')
@section('styles')

	<link href="{{ asset('css/form.css') }}" rel="stylesheet">

@endsection
@section('content')
	<h1 class="tender-title">Kemaskini Berita</h1>

	{!! Former::open(url('news/'.$news->id)) !!}
	{!! Former::populate($news) !!}
	{!! Former::hidden('_method', 'PUT') !!}
	@include('news.form')

	<div class="well">
	  <input type="submit" value="Simpan" class="btn btn-primary">
	  {!! Former::close() !!}
	  @if($news->canUpdate())
	  @if($news->publish)<a href="{{ asset('news/'.$news->id.'/publish') }}" class="btn btn-warning link-confirm">Siar</a>@endif
	  @if(!$news->publish)<a href="{{ asset('news/'.$news->id.'/publish') }}" class="btn btn-danger link-confirm">Batal Siar</a>@endif

	  @if($news->canDelete())
	      {!! Former::open(action('NewsController@destroy', $news->id))->class('form-inline') !!}
          	{!! Former::hidden('_method', 'DELETE') !!}
          	<button type="button" class="btn btn-danger confirm-delete">Padam</button>
	      {!! Former::close() !!}
	  @endif
	@endif

	  <a href="{{ asset('news') }}" class="btn btn-default pull-right">Arkib Berita</a>  
	</div>
@endsection