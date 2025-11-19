{{-- New Addition 25/11/2022 --}}

@php $user = Auth::user(); @endphp

@if($user->ability(['Admin', 'Registration Assessor'], ['Vendor:approve']))
	
	<div class="row">
		<div class="col-sm-4">
			<div class="panel panel-primary text-center">
				<div class="panel-body">
					<h1 id="success_trans_count"><span style="font-size:12px">Sedang diproses...</span></h1>
				</div>
				<div class="panel-heading">
					<a href="{{action('TransactionsController@successTransIndex')}}" style="color:white">
						Berjaya
					</a>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="panel panel-primary text-center">
				<div class="panel-body">
					<h1 id="pending_trans_count"><span style="font-size:12px">Sedang diproses...</span></h1>
				</div>
				<div class="panel-heading">
					<a href="{{action('TransactionsController@pendingTransIndex')}}" style="color:white">
						Belum Diterima
					</a>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="panel panel-primary text-center">
				<div class="panel-body">
					<h1 id="pending_authorization_trans_count"><span style="font-size:12px">Sedang diproses...</span></h1>
				</div>
				<div class="panel-heading">
					<a href="{{action('TransactionsController@pendingAuthTransIndex')}}" style="color:white">
						Dalam Proses Pengesahan
					</a>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="panel panel-primary text-center">
				<div class="panel-body">
					<h1 id="failed_trans_count"><span style="font-size:12px">Sedang diproses...</span></h1>
				</div>
				<div class="panel-heading">
					<a href="{{action('TransactionsController@failedTransIndex')}}" style="color:white">
						Gagal
					</a>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="panel panel-primary text-center">
				<div class="panel-body">
					<h1 id="declined_trans_count"><span style="font-size:12px">Sedang diproses...</span></h1>
				</div>
				<div class="panel-heading">
					<a href="{{action('TransactionsController@declinedTransIndex')}}" style="color:white">
						Ditolak
					</a>
				</div>
			</div>
		</div>
	</div>
@endif