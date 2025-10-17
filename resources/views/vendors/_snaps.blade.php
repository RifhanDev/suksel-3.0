@if(Auth::user()->ability(['Admin', 'Registration Assessor'], ['Vendor:approve']))
	<div class="row">
		<div class="col-sm-3">
			<div class="panel panel-primary text-center">
				<div class="panel-body">
					<h1>{{number_format(App\Vendor::pendingRegistrationCount(), 0)}}</h1>
				</div>
				<div class="panel-heading">
					<a href="{{action('VendorsController@pendingRegistrationIndex')}}" style="color:white">
						Pendaftaran<br>
						Belum Selesai
					</a>
				</div>
			</div>
		</div>
	<div class="col-sm-3">
		<div class="panel panel-primary text-center">
			<div class="panel-body">
			<h1>{{number_format(App\Vendor::pendingNewApproval1Count(), 0)}}</h1>
			</div>
			<div class="panel-heading">
				<a href="{{action('VendorsController@approvalNew1Index')}}" style="color:white">
					Pendaftaran<br>
					Belum Diluluskan
				</a>
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="panel panel-primary text-center">
			<div class="panel-body">
				<h1>{{number_format(App\CodeRequest::pendingCount(), 0)}}</h1>
			</div>
			<div class="panel-heading">
				<a href="{{ asset('requests') }}" style="color:white">
					Permintaan<br>
					Kemaskini
				</a>
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="panel panel-primary text-center">
			<div class="panel-body">
				<h1>{{number_format(App\Vendor::count(), 0)}}</h1>
			</div>
			<div class="panel-heading">
				<a href="{{action('VendorsController@index')}}" style="color:white">
					Jumlah<br>
					Syarikat
				</a>
			</div>
		</div>
	</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<div class="panel panel-primary text-center">
				<div class="panel-body">
					<h2>{{number_format(App\Vendor::activeSubscriptionCount(), 0)}}</h2>
				</div>
				<div class="panel-heading">
					<a href="" style="color:white">
						Syarikat Aktif
					</a>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="panel panel-primary text-center">
				<div class="panel-body">
					<h2>{{number_format(App\Vendor::nonActiveSubscriptionCount(), 0)}}</h2>
				</div>
				<div class="panel-heading">
					<a href="" style="color:white">
						Syarikat Tidak Aktif
					</a>
				</div>
			</div>
		</div>
	</div>
@endif