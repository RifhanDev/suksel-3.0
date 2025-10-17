@extends('layouts.default')

@section('content')
	<div class="row">
	    	<div class="col-lg-9">
	        	<h1>Kemaskini Alamat Emel</h1>

	        	<table class="table table-bordered table-striped">
	          	<tr>
	            	<th class="col-xs-3">Nama Syarikat</th>
	            	<td>{{ $vendor->name }}</td>
	          	</tr>
	          	<tr>
	            	<th>No. SSM</th>
	            	<td>{{ $vendor->registration }}</td>
	          	</tr>
	        	</table>

	        	<p>Sila masukkan alamat emel baru dan dokumen-dokumen berikut</p>
	        	<ul>
	          	<li>Salinan Sijil SSM</li>
	          	<li>Salinan Kad Pengenalan Pemilik/Pengarah</li>
	        	</ul>

	        	{!! Former::vertical_open_for_files(action('HomeController@doChangeEmail')) !!}
	          	{!! Former::text('new_email')
							->label('Alamat Emel Baru')
							->required() !!}
	          	{!! Former::file('sijil_ssm')
							->label('Salinan Sijil SSM')
							->accept('application/pdf')
							->required() !!}
	          	{!! Former::file('sijil_ic')
							->label('Salinan Kad Pengenalan Pemilik / Pengarah')
							->accept('application/pdf')
							->required() !!}
	          	{!! Former::submit('Kemaskini')
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
