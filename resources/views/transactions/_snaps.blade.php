<?php $user = Auth::user();?>

@if($user->ability(['Admin', 'Registration Assessor'], ['Vendor:approve']))
	<div class="row">
		<div class="col-sm-4">
			<div class="panel panel-primary text-center">
				<div class="panel-body">
					<h1 id="subscribe_trans_count"><span style="font-size:12px">Sedang diproses...</span></h1>
				</div>
				<div class="panel-heading">
					<a href="{{action('TransactionsController@subscriptionIndex')}}" style="color:white">
						Langganan
					</a>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="panel panel-primary text-center">
				<div class="panel-body">
					<h1 id="purchase_trans_count"><span style="font-size:12px">Sedang diproses...</span></h1>
				</div>
				<div class="panel-heading">
					<a href="{{action('TransactionsController@purchaseIndex')}}" style="color:white">
						Pembelian Dokumen
					</a>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="panel panel-primary text-center">
				<div class="panel-body">
					<h1 id="total_trans_count"><span style="font-size:12px">Sedang diproses...</span></h1>
				</div>
				<div class="panel-heading">
					<a href="/transactions" style="color:white">
						 Transaksi
					</a>
				</div>
			</div>
		</div>
	</div>
@endif