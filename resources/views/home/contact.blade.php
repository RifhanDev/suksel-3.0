@extends('layouts.default')

@section('content')
	<div class="row">
    	<div class="col-lg-9">
        	<h1>Hubungi Kami</h1>
        	<p>Untuk sebarang pertanyaan mengenai Sistem Tender Online Selangor, sila guna borang di bawah ke</p>

        	{!! Former::vertical_open(action('HomeController@doContact')) !!}
          	{!! Former::populate($comment) !!}
          	{!! Former::text('name')
              	->label('Nama Anda')
              	->required() !!}
          	{!! Former::text('company_name')
              	->label('Nama Syarikat') !!}
          	{!! Former::text('email')
              	->label('Alamat Emel Anda')
              	->required() !!}
          	{!! Former::text('subject')
              	->label('Tajuk / Perkara')
             	->required() !!}
          	{!! Former::textarea('body')
              	->label('Pesanan')
              	->required()
              	->rows(10) !!}

          	{!! Former::submit('Hantar')
              	->class('btn bg-blue-selangor') !!}
        	{!! Former::close() !!}
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
