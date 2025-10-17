@extends('layouts.default')
@section('content')
	<h2>Pendaftaran Syarikat</h2>
	<ul class="nav nav-tabs nav-justified">
	    	<li class="active">
	        	<a href="#"><span class="badge">1</span> Pengesahan Alamat Emel</a>
	    	</li>
	    	<li class="disabled">
	        	<a href="#"><span class="badge">2</span> Lengkapkan Maklumat Syarikat</a>
	    	</li>
	    	<li class="disabled">
	        	<a href="#"><span class="badge">3</span> Pembayaran Pendaftaran</a>
	    	</li>
	</ul>

	<div class="alert alert-info">
	   Pernah mendaftar dengan Sistem Tender Online Selangor?&nbsp;&nbsp;&nbsp;<a href="{{ action('HomeController@companySearch') }}" class="btn btn-xs btn-primary">Semak Pendaftaran Syarikat</a>
	</div>

	<div class="portlet box">
	   <div class="portlet-body">
	        	{!! Former::open(url('register'))->addClass('form-uppercase')->autocomplete("false") !!} 
	            <div>
	                	{!! Former::text('company_no')
	                    ->label('No Pendaftaran Syarikat')
	                    ->required() !!}
	                	{!! Former::text('company_name')
	                    ->label('Nama Syarikat')
	                    ->required() !!}
	                	{!! Former::text('name')
	                    ->label('Nama Pendaftar')
	                    ->required() !!}
	                	{!! Former::email('email')
	                    ->label('Alamat Emel')
	                    ->required()
	                    ->addClass('x-uppercase') !!}
	                	{!! Former::password('password')
	                    ->label('Kata Laluan')
	                    ->help('Sekurang-kurangnya 8 aksara, satu simbol, satu nombor, satu huruf besar dan satu huruf kecil')
	                    ->required()
	                    ->addClass('x-uppercase') !!}
	                	{!! Former::password('password_confirmation')
	                    ->label('Pengesahan Kata Laluan')
	                    ->help('Masukan semula kata laluan')
	                    ->required()
	                    ->addClass('x-uppercase') !!}
	                	<div class="form-group required">
							<div class="col-lg-3 col-sm-3"></div>
							<div class="col-lg-9 col-sm-9">
								<input type="submit" value="Sahkan Alamat Emel" class="btn btn-lg blue">
							</div>
	                	</div>
	            </div>
	        	{!! Former::close() !!}
	   </div>
	</div>
@endsection

