{{ App\Libraries\Asset::push('css', 'form') }}
<div class="row stacked-form">
	<div class="col-lg-2">
		<ul class="nav nav-pills nav-stacked">
			<li class="@if (!isset($active_prestasi_tab)) active @endif"><a href="#vf-main" data-toggle="pill">Maklumat Syarikat</a></li>
			<li><a href="#vf-officer" data-toggle="pill">Maklumat Pegawai</a></li>
			<li><a href="#vf-mof" data-toggle="pill">MOF</a></li>
			<li><a href="#vf-cidb" data-toggle="pill">CIDB</a></li>
			<li><a href="#vf-shareholders" data-toggle="pill">Pemegang Saham</a></li>
			<li><a href="#vf-directors" data-toggle="pill">Pengarah</a></li>
			@if(isset($vendor) && $vendor->approval_1_id > 0)
				<li><a href="#vf-contacts" data-toggle="pill">Kakitangan</a></li>
				<li><a href="#vf-awards" data-toggle="pill">Anugerah</a></li>
				<li><a href="#vf-assets" data-toggle="pill">Aset</a></li>
				<li><a href="#vf-projects" data-toggle="pill">Projek</a></li>
				<li><a href="#vf-products" data-toggle="pill">Produk</a></li>
			@endif
			<li><a href="#vf-files" data-toggle="pill">Fail</a></li>
			<li><a href="#vf-subscriptions" data-toggle="pill">Bayaran Pendaftaran</a></li>
			<li class="@if (isset($active_prestasi_tab)) active @endif"><a href="#vf-prestasi-syarikat" data-toggle="pill">Rekod Penilaian Prestasi Syarikat</a></li>
			<!--<li><a href="#vf-transactions" data-toggle="pill">Transaksi Pembayaran</a></li>-->
		</ul>
	</div>

<div class="tab-content col-lg-10">
	<div class="tab-pane @if (!isset($active_prestasi_tab)) active @endif" id="vf-main">
		<div class="row">
			<div class="col-lg-6">
				<table class="table table-condensed table-bordered">
					<tr>
						<th class="col-lg-3">Alamat Emel</th>
						<td>{{$vendor->user->email}}</td>
					</tr>
					<tr>
						<th class="col-lg-3">No Pendaftaran</th>
						<td>{{$vendor->registration}}</td>
					</tr>
					<tr>
						<th>Nama Perniagaan / Syarikat</th>
						<td>{{$vendor->name}}</td>
					</tr>
					<tr>
						<th>Alamat</th>
						<td>{!! nl2br($vendor->address) !!}</td>
					</tr>
					<tr>
						<th>Daerah</th>
						<td>
							@if($vendor->district_id)
								{{ App\Vendor::$districts[$vendor->district_id] }}
							@elseif( ($vendor->state_id ?? 0) == 0 && ($vendor->district_id ?? 0) == 0 )
								Sila Kemaskini
							@else
								Luar Negeri Selangor
							@endif
						</td>
					</tr>
					<tr>
						<th>Negeri</th>
						<td>
							@if($vendor->state_id && ($vendor->district_id ?? 0) == 0)
							{{ App\Vendor::$states[$vendor->state_id] }}
							@elseif( ($vendor->state_id ?? 0) == 0 && ($vendor->district_id ?? 0) == 0 )
								Sila Kemaskini
							@else
								Selangor
							@endif
						</td>
					</tr>
					<tr>
						<th>No. Telefon</th>
						<td>@if($vendor->tel){{$vendor->tel}}@else<span class="glyphicon glyphicon-remove"></span>@endif</td>
					</tr>
					<tr>
						<th>No. Faks</th>
						<td>@if($vendor->fax){{$vendor->fax}}@else<span class="glyphicon glyphicon-remove"></span>@endif</td>
					</tr>
				</table>

				@if($vendor->canCertificate())
					@if(Auth::user()->can('Vendor:certificate'))
						<table class="table table-condensed table-bordered">
							<tr>
								<th class="col-lg-6">Kod Pengesahan Sijil</th>
								<td>{{ $vendor->token }}</td>
							</tr>
						</table>
					@endif
					<a href="{{action('VendorsController@certificate', $vendor->id)}}" target="_blank" class="btn btn-xs btn-danger pull-right">Papar Sijil Pengesahan</a>
				@endif
				<a href="{{ action('ReportVendorSummaryController@index', ['year' => date('Y'),'vendor_id' => $vendor->id]) }}" target="_blank" class="btn btn-xs btn-primary pull-right">Laporan Transaksi Syarikat</a>
			</div>

			<div class="col-lg-6">
				<table class="table table-condensed table-bordered">
					<tr>
						<th class="col-lg-3">Jenis Perniagaan</th>
						<td>{{$vendor->organization_type}}</td>
					</tr>
					<tr>
						<th>Tarikh Penubuhan</th>
						<td>{{$vendor->incorporation_date}}</td>
					</tr>
					<tr>
						<th>Modal Dibenarkan</th>
						<td>{{$vendor->authorized_capital_currency}} {{$vendor->authorized_capital}}</td>
					</tr>
					<tr>
						<th>Modal Berbayar</th>
						<td>{{$vendor->paidup_capital_currency}} {{$vendor->paidup_capital}}</td>
					</tr>
					<tr>
						<th>No. Rujukan Cukai</th>
						<td>@if($vendor->tax_no){{$vendor->tax_no}}@else<span class="glyphicon glyphicon-remove"></span>@endif</td>
					</tr>
					<tr>
						<th>No. Pendaftaran GST</th>
						<td>@if($vendor->gst_no){{$vendor->gst_no}}@else<span class="glyphicon glyphicon-remove"></span>@endif</td>
					</tr>
					<tr>
						<th>Laman Web</th>
						<td>@if($vendor->website){{$vendor->website}}@else<span class="glyphicon glyphicon-remove"></span>@endif</td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<div class="tab-pane" id="vf-officer">
		<table class="table table-condensed table-bordered">
			<tr>
				<th class="col-lg-3">Nama Pegawai</th>
				<td>{{$vendor->user->name}}</td>
			</tr>
			<tr>
				<th>Jawatan Pegawai</th>
				<td>{{$vendor->officer_designation}}</td>
			</tr>
			<tr>
				<th>No. Telefon</th>
				<td>{{$vendor->officer_tel}}</td>
			</tr>
		</table>
	</div>

	<div class="tab-pane" id="vf-mof">
		<table class="table table-condensed table-bordered">
			<tr>
				<th class="col-lg-3">No Rujukan Pendaftaran MOF</th>
				<td>
					@if($vendor->mof_ref_no)
						{{$vendor->mof_ref_no}}
					@else
						<span class="glyphicon glyphicon-remove"></span>
					@endif
				</td>
			</tr>
			<tr>
				<th>Tarikh Aktif MOF</th>
				<td>
					@if($vendor->mof_start_date && $vendor->mof_end_date)
						{{ Carbon\Carbon::parse($vendor->mof_start_date)->format('d M Y') }} - {{ Carbon\Carbon::parse($vendor->mof_end_date)->format('d M Y') }}
					@else
						<span class="glyphicon glyphicon-remove"></span>
					@endif
				</td>
			</tr>
			<tr>
				<th>Syarikat Bumiputera</th>
				<td>
					@if($vendor->mof_bumi)
						<span class="glyphicon glyphicon-ok"></span>
					@else
						<span class="glyphicon glyphicon-remove"></span>
					@endif
				</td>
			</tr>
			<tr>
				<th>Kod Bidang MOF</th>
				<td>
					<div style="max-height: 500px;overflow-y:auto;">
						@if(count($vendor->mofCodes) > 0)
							<u>Jumlah Kod Bidang: {{ count($vendor->mofCodes) }}</u><br>
							<ul>
								@foreach($vendor->mofCodes->sortBy('code.code') as $code)
									<li>{!! $code->code->label2 !!}</li>
								@endforeach
							</ul>
						@else
							<span class="glyphicon glyphicon-remove"></span>
						@endif
					</div>
				</td>
			</tr>
		</table>
	</div>

	<div class="tab-pane" id="vf-cidb">
		<table class="table table-condensed table-bordered">
			<tr>
				<th class="col-lg-3">No Sijil CIDB</th>
				<td>
					@if($vendor->cidb_ref_no)
						{!! $vendor->cidb_ref_no !!}
					@else
						<span class="glyphicon glyphicon-remove"></span>
					@endif
				</td>
			</tr>
			<tr>
				<th>Tarikh Aktif CIDB</th>
				<td>
					@if($vendor->cidb_start_date && $vendor->cidb_end_date)
						{{ Carbon\Carbon::parse($vendor->cidb_start_date)->format('d M Y') }} - {{ Carbon\Carbon::parse($vendor->cidb_end_date)->format('d M Y') }}
					@else
						<span class="glyphicon glyphicon-remove"></span>
					@endif
				</td>
			</tr>
			<tr>
				<th>Syarikat Bumiputera</th>
				<td>
					@if($vendor->cidb_bumi)
						<span class="glyphicon glyphicon-ok"></span>
					@else
						<span class="glyphicon glyphicon-remove"></span>
					@endif
				</td>
			</tr>
			<tr>
				<th>Gred &amp; Bidang Pengkhususan</th>
				<td>
					<div style="max-height: 500px;overflow-y:auto;">
						@forelse($vendor->cidbGrades()->orderBy('id')->get() as $grade)
							<u><b>{{ $grade->code->label }}</b></u><br>
							<small>Jumlah Bidang Pengkhususan: {{ count($grade->children) }}</small><br><br>

							<?php $a_codes = App\Code::whereIn('id', $grade->children->pluck('code_id'))->where('code', 'LIKE', 'A%')->orderBy('code')->get(); ?>
							@if(count($a_codes) > 0)
								<u><b>A</b></u>
								<ul>
									@foreach($a_codes as $code)
										<li>{!! $code->label2 !!}</li>
									@endforeach
								</ul>
							@endif

							<?php $b_codes = App\Code::whereIn('id', $grade->children->pluck('code_id'))->where('code', 'LIKE', 'B%')->orderBy('code')->get(); ?>
							@if(count($b_codes) > 0)
								<u><b>B</b></u>
								<ul>
									@foreach($b_codes as $code)
										<li>{!! $code->label2 !!}</li>
									@endforeach
								</ul>
							@endif

							<?php $ce_codes = App\Code::whereIn('id', $grade->children->pluck('code_id'))->where('code', 'LIKE', 'CE%')->orderBy('code')->get(); ?>
							@if(count($ce_codes) > 0)
								<u><b>CE</b></u>
								<ul>
									@foreach($ce_codes as $code)
										<li>{!! $code->label2 !!}</li>
									@endforeach
								</ul>
							@endif

							<?php $f_codes = App\Code::whereIn('id', $grade->children->pluck('code_id'))->where('code', 'LIKE', 'F%')->orderBy('code')->get(); ?>
							@if(count($f_codes) > 0)
								<u><b>F</b></u>
								<ul>
									@foreach($f_codes as $code)
										<li>{!! $code->label2 !!}</li>
									@endforeach
								</ul>
							@endif

							<?php $me_codes = App\Code::whereIn('id', $grade->children->pluck('code_id'))->where('code', 'REGEXP', '^[ME]')->orderBy('code')->get(); ?>
							@if(count($me_codes) > 0)
								<u><b>ME</b></u>
								<ul>
									@foreach($me_codes as $code)
										<li>{!! $code->label2 !!}</li>
									@endforeach
								</ul>
							@endif

							<?php $p_codes = App\Code::whereIn('id', $grade->children->pluck('code_id'))->where('code', 'LIKE', 'P%')->orderBy('code')->get(); ?>
							@if(count($p_codes) > 0)
								<u><b>P</b></u>
								<ul>
									@foreach($p_codes as $code)
										<li>{!! $code->label2 !!}</li>
									@endforeach
								</ul>
							@endif

							<?php $it_codes = App\Code::whereIn('id', $grade->children->pluck('code_id'))->where('code', 'LIKE', 'IT%')->orderBy('code')->get(); ?>
							@if(count($it_codes) > 0)
								<u><b>IT</b></u>
								<ul>
									@foreach($it_codes as $code)
										<li>{!! $code->label2 !!}</li>
									@endforeach
								</ul>
							@endif

						@empty
							<span class="glyphicon glyphicon-remove"></span>
						@endforelse
					</div>
				</td>
			</tr>
		</table>
	</div>

	<div class="tab-pane" id="vf-shareholders">
		@if(count($vendor->shareholders) > 0)
			<table class="table table-striped table-bordered table-hover">
				<thead class="bg-blue-selangor">
					<tr>
						<th>Nama</th>
						<th>IC / Pasport</th>
						<th>Kewarganegaraan</th>
						<th>Taraf</th>
					</tr>
				</thead>
				<tbody>
					@foreach($vendor->shareholders as $sd)
						<tr>
						<td>{{$sd->name}}</td>
						<td>{{$sd->identity}}</td>
						<td>{{$sd->nationality}}</td>
						<td>{{$sd->bumiputera_status}}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		@else
			<div class="alert alert-warning">Tiada maklumat pemegang saham.</div>
		@endif

		<h4>Ringkasan</h4>
		<table class="table table-bordered">
			<thead class="bg-blue-selangor">
				<tr>
					<th>Bumiputera</th>
					<th>Bukan Bumiputera</th>
					<th>Warga Asing</th>
					<th>Jumlah</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>{{$vendor->bumi_percentage}} %</td>
					<td>{{$vendor->nonbumi_percentage}} %</td>
					<td>{{$vendor->foreigner_percentage}} %</td>
					<td>{{ sprintf('%.2f', $vendor->bumi_percentage + $vendor->nonbumi_percentage + $vendor->foreigner_percentage) }} %</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="tab-pane" id="vf-directors">
		@if(count($vendor->directors) > 0)
			<table class="table table-striped table-bordered table-hover">
				<thead class="bg-blue-selangor">
					<tr>
						<th>Nama</th>
						<th>IC / Pasport</th>
						<th>Kewarganegaraan</th>
						<th>Jawatan</th>
					</tr>
				</thead>
				<tbody>
					@foreach($vendor->directors as $sd)
						<tr>
							<td>{{$sd->name}}</td>
							<td>{{$sd->identity}}</td>
							<td>{{$sd->nationality}}</td>
							<td>{{$sd->designation}}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		@else
			<div class="alert alert-warning">Tiada maklumat pengarah.</div>
		@endif
	</div>

	<div class="tab-pane" id="vf-contacts">
		@if(count($vendor->contacts) > 0)
			<table class="table table-striped table-bordered table-hover">
				<thead class="bg-blue-selangor">
					<tr>
						<th>Nama</th>
						<th>Jawatan</th>
						<th>Warga Negara</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
				@foreach($vendor->contacts()->orderBy('name', 'asc')->get() as $contact)
					<tr>
						<td>{{$contact->name}}</td>
						<td>{{$contact->designation}}</td>
						<td>{{$contact->nationality}}</td>
						<td>{{$contact->status}}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		@else
			<div class="alert alert-warning">Tiada maklumat kakitangan.</div>
		@endif
	</div>

	<div class="tab-pane" id="vf-awards">
		@if(count($vendor->awards) > 0)
			<table class="table table-striped table-bordered table-hover">
				<thead class="bg-blue-selangor">
					<tr>
						<th>Nama</th>
						<th>Keterangan</th>
						<th>Pemberi Anugerah</th>
					</tr>
				</thead>
				<tbody>
					@foreach($vendor->awards()->orderBy('name', 'asc')->get() as $award)
						<tr>
							<td>{{$award->name}}</td>
							<td>{{$award->description}}</td>
							<td>{{$award->by}}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		@else
			<div class="alert alert-warning">Tiada maklumat anugerah.</div>
		@endif
	</div>

	<div class="tab-pane" id="vf-assets">
		@if(count($vendor->assets) > 0)
			<table class="table table-striped table-bordered table-hover">
				<thead class="bg-blue-selangor">
					<tr>
						<th>Nama</th>
						<th class="col-lg-2">Nilai (RM)</th>
					</tr>
				</thead>
				<tbody>
					@foreach($vendor->assets()->orderBy('name', 'asc')->get() as $asset)
						<tr>
							<td>{{$asset->name}}</td>
							<td>{{$asset->value}}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		@else
			<div class="alert alert-warning">Tiada maklumat aset.</div>
		@endif
	</div>

	<div class="tab-pane" id="vf-projects">
		@if(count($vendor->projects) > 0)
			<table class="table table-striped table-bordered table-hover">
				<thead class="bg-blue-selangor">
					<tr>
						<th>Nama</th>
						<th>Pelanggan</th>
						<th>Tempoh Projek</th>
						<th>Nilai Projek (RM)</th>
						<th>Projek Siap</th>
					</tr>
				</thead>
				<tbody>
					@foreach($vendor->projects()->orderBy('name', 'asc')->get() as $project)
						<tr>
							<td>{{$project->name}}</td>
							<td>{{$project->customer}}</td>
							<td>{{$project->period}}</td>
							<td>{{$project->value}}</td>
							<td>{!! boolean_icon($project->done) !!}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		@else
			<div class="alert alert-warning">Tiada maklumat projek.</div>
		@endif
	</div>

	<div class="tab-pane" id="vf-products">
		@if(count($vendor->products) > 0)
			<table class="table table-striped table-bordered table-hover">
				<thead class="bg-blue-selangor">
					<tr>
						<th>Nama</th>
						<th>Keterangan</th>
						<th>Pengguna</th>
					</tr>
				</thead>
				<tbody>
					@foreach($vendor->products()->orderBy('name', 'asc')->get() as $product)
						<tr>
							<td>{{$product->name}}</td>
							<td>{{$product->description}}</td>
							<td>{{$product->implementations}}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		@else
			<div class="alert alert-warning">Tiada maklumat produk.</div>
		@endif
	</div>

		<div class="tab-pane" id="vf-files">
			{!! $vendor->uploadsTable() !!}
		</div>

		<div class="tab-pane" id="vf-subscriptions">
			@if($vendor->subscriptions()->count() > 0)
				<table class="table table-condensed table-bordered table-striped">
					<thead class="bg-blue-selangor">
						<tr>
							<th>No Tranksaksi</th>
							<th>No Resit</th>
							<th>Tempoh Langganan</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						{{-- @foreach($vendor->subscriptions()->orderBy('start_date', 'asc')->get() as $sub)
							<tr>
								<td>{{ $sub->transaction->number }}  | {{ $sub->id }} | {{ $sub->transaction->id }} </td>
								<td>{{ $sub->transaction->receipt_number }}</td>
								<td>{{\Carbon\Carbon::parse($sub->start_date)->format('d/m/Y')}} - {{\Carbon\Carbon::parse($sub->end_date)->format('d/m/Y')}}</td>
								<td>{{ link_to_route('vendors.subscriptions.receipt', 'Resit', [$vendor->id, $sub->id], ['target' => 'new'])}}</td>
							</tr>
						@endforeach --}}
						@foreach($transactions as $transaction)
							<tr>
								<td>{{ $transaction->number }} </td>
								<td>{{($transaction->receipt!='old') ? $transaction->receipt : $transaction->receipt_number}}</td>
								<td>{{ $transaction->start_date }} - {{ $transaction->end_date }}</td>
								<td>{{ link_to_route('vendors.subscriptions.receipt', 'Resit', [$vendor->id, $transaction->subscription_id], ['target' => 'new'])}}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@else
				<div class="alert alert-info">Tiada maklumat langganan.</div>
			@endif
		</div>

		{{-- START: Tab Content - Rekod Penilaian Prestasi Syarikat --}}
		@include('vendors.tab-contents.prestasi-syarikat')
		{{-- END: Tab Content - Rekod Penilaian Prestasi Syarikat --}}

	</div>
</div>