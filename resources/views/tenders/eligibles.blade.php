@extends('layouts.default')

@section('styles')

	<link href="{{ asset('css/application.css') }}" rel="stylesheet">
	<link href="{{ asset('css/form.css') }}" rel="stylesheet">

@endsection

@section('content')

	@include('tenders._menu')

	<div class="tender-ref-number">{{ $tender->ref_number }}</div>
	<h2 class="tender-title">{{ $tender->name }}</h2>

	@include('tenders._notification')

	@if(Auth::user() && $tender->canShowTabs())
		<ul class="nav nav-tabs nav-justified">
			<li><a href="{{ asset('tenders/'.$tender->id) }}">Maklumat Tender / Sebut Harga</a></li>
			<li class="active"><a href="{{ asset('tenders/'.$tender->id.'/vendors') }}">Maklumat Syarikat</a></li>
            @if (Auth::check() &&
                $tender->canException() && auth()->user()->ability(['Admin', 'Agency Admin', 'Agency User'], ['ExceptionTender:list']))
                <li><a href="{{ asset('tenders/' . $tender->id . '/exceptions') }}">Maklumat Kebenaran Khas <span
                    class="badge">{{ $tender->exceptions()->where('status',0)->count() }}</span></a></li>
            @endif
		</ul>
	@endif

	<br>

	@if(Auth::user()->hasRole('Admin'))
		<ul class="nav nav-pills">
			<li{{ !isset($purchases) ? ' class="active"' : '' }}><a href="{{ asset('tenders/'.$tender->id.'/eligibles') }}">Senarai Layak</a></li>
			<li{{ isset($purchases) ? ' class="active"' : '' }}><a href="{{ asset('tenders/'.$tender->id.'/vendors') }}">Pembelian Dokumen</a></li>
		</ul>
	@endif
		<br>

	<table data-path="{{ route('tenders.eligibles', $tender->id) }}" class="DT-index table table-striped table-hover table-bordered">
		<thead class="bg-blue-selangor">
			<tr>
				<th>Bil.</th>
				<th>No. Pendaftaran</th>
				<th>Nama Syarikat</th>
				<th>Alamat Emel</th>
				<th>Tarikh Janaan</th>
				<th>Tarikh Email</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
@endsection

@section('scripts')

	<script src="{{ asset('js/application.js') }}"></script>
	<script src="{{ asset('js/datatables.js') }}"></script>
	<script type="text/javascript">
		$('.DT-index').each(function(){
			var target = $(this);
			var path = target.data('path');
			var DT = target.DataTable({
				ajax: path,
		    	columns: [
					{ data: null },
		            { data: 'vendor_registration', name: 'vendor.registration'},
		            { data: 'vendor_name', name: 'vendor.name'},
		            { data: 'user_email', name: 'users.email'},
		            { data: 'created_at', name: 'created_at' },
		            { data: 'sent_at', name: 'sent_at' }
		      ],
				serverSide: true,
				stateSave: true,
				language: {
					sEmptyTable: "Tiada data",
					sInfo: "Paparan dari _START_ hingga _END_ dari _TOTAL_ rekod",
					sInfoEmpty: "Paparan 0 hingga 0 dari 0 rekod",
					sInfoFiltered: "(Ditapis dari jumlah _MAX_ rekod)",
					sInfoPostFix: "",
					sInfoThousands: ",",
					sLengthMenu: "Papar _MENU_ rekod",
					sLoadingRecords: "Diproses...",
					sProcessing: "Sedang diproses...",
					sSearch: "Carian:",
					sZeroRecords: "Tiada padanan rekod yang dijumpai.",
					oPaginate: {
						sFirst: "Pertama",
						sPrevious: "Sebelum",
						sNext: "Kemudian",
						sLast: "Akhir"
					},
					oAria: {
						sSortAscending: ": diaktifkan kepada susunan lajur menaik",
						sSortDescending: ": diaktifkan kepada susunan lajur menurun"
					}
				},
				aaSorting: [],
				// columnDefs: [{
				// "searchable": false,
				// "orderable": false,
				// "targets": 0
				// }],
				"order": [[1, 'asc']],
				fnDrawCallback: function(oSettings) {
					start = oSettings.oAjaxData.start + 1;
					DT.column(0).nodes().to$().each(function(index){
						$(this).text(start+index);
					});
				}
			});
		});
	</script>
@endsection