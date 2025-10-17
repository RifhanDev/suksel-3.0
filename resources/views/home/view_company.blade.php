@extends('layouts.default')

@section('content')
	<div class="row">
    	<div class="col-lg-9">
        	<h1>Semakan Syarikat</h1>
        	<p>Status Semakan Syarikat yang Berdaftar dengan Sistem Tender Online Selangor.</p>

        	@if($vendor)
        		<table class="table table-bordered table-striped">
          		<tr>
		            <th class="col-xs-3">Nama Syarikat</th>
		            <td>{{ $vendor->name }}</td>
          		</tr>
	          	<tr>
		            <th>No. SSM</th>
		            <td>{{ $vendor->registration }}</td>
	          	</tr>
	          	<tr>
		            <th>Status Langganan</th>
		            <td>{{ $vendor->status }}</td>
	          	</tr>
	          	@if($vendor->expiry_date && $vendor->expiry_date != '1970-01-01')
		          	<tr>
			            <th>Tarikh Tamat Langganan</th>
			            <td>{{ Carbon\Carbon::parse($vendor->expiry_date)->format('d/m/Y') }}</td>
		          	</tr>
	          	@endif
	          	<tr>
	            	<th>Alamat Emel Didaftarkan</th>
	            	<td>{{ $vendor->user->hidden_email }}</td>
	          	</tr>
       		</table>
        	@else
        		<div class="alert alert-info">Tiada syarikat yang didaftarkan berdasarkan maklumat yang diberikan.</div>
        	@endif

        	<a href="{{ asset('company_search') }}" class="btn bg-blue-selangor">Semak Syarikat Lain</a>
        	<a href="{{ asset('change_email') }}" class="btn bg-blue-selangor">Kemaskini Alamat Emel</a>
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
