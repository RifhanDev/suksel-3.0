@extends('layouts.default')
@section('content')
	<div class="row">
		<div class="col-lg-9">
			<h1 class="tender-title">
				Tambah Kategori Soalan Lazim
				
				@if(Auth::user() && Auth::user()->hasRole('Admin'))
					<div class="btn-group pull-right">
						<a href="{{ asset('helps')!!}" class="btn btn-sm btn-warning"><i class="fa fa-tags"></i> Soalan Lazim</a>
						<a href="{{ asset('helpcategories')!!}" class="btn btn-sm btn-primary"><i class="fa fa-chevron-up"></i> Senarai Kategori</a>
					</div>
				@endif
			</h1>
			
			{!! Former::open( url('helpcategories')) !!}
				@include('helpcategories.form')
				<div class="form-group">
					<div class="col-lg-9 col-lg-offset-3">
					{!! Former::submit()
						->class('btn btn-md btn-primary')
						->value('Hantar') !!}
					</div>
				</div>
			{!! Former::close() !!}
		</div>
		
		<div class="col-lg-3">
			@include('layouts._register')
			@include('layouts._news')
		</div>
	</div>
@endsection