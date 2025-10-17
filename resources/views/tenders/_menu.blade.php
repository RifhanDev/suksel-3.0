<div class="btn-group pull-left btn-actions">
	<a href="{{ asset('agencies/'.$tender->tenderer->id) }}" class="btn btn-warning btn-sm">
		<i class="fa fa-group"></i> {{ App\Tender::$types[$tender->type] }} oleh {{ $tender->tenderer->name }}
	</a>
</div>

@if(Auth::check() && !Auth::user()->hasRole('Vendor') && $tender->canUpdate())
	<div class="btn-group pull-right btn-actions">
		@if($tender->canAllowEdit() && Route::currentRouteAction() != 'TendersController@edit')
			<a href="{{ asset('tenders/'.$tender->id.'/edit')}}" class="btn btn-primary btn-sm">
				<i class="fa fa-pencil-square-o"></i> Kemaskini
			</a>
		@endif

		<button type="button" class="btn btn-link btn-sm pull-right hidden-print" data-toggle="dropdown" aria-expanded="false">
			<span class="caret"></span>
			<span class="sr-only">Papar Menu Tambahan</span>
		</button>
		<ul class="dropdown-menu pull-right" role="menu">
			@if(empty($tender->publish_prices))

				@if($tender->canUpdate() && empty($tender->approver_id))
					<li><a href="{{ asset('tenders/'.$tender->id.'/publish') }}" class="publish-tender"><i class="fa fa-upload"></i> Siar</a></li>
				@endif
				@if($tender->canCancel() && $tender->approver_id)
					<li><a href="{{ asset('tenders/'.$tender->id.'/cancel') }}"><i class="fa fa-times"></i> Batal Siar</a></li>
				@endif

			@endif

			@if($tender->canShowPrices())

				@if($tender->canUpdate() && $tender->approver_id > 0 && empty($tender->publish_prices))
				<li><a href="{{ asset('tenders/'.$tender->id.'/publishPrices') }}"><i class="fa fa-check"></i> Umum Carta Tender</a></li>
				@endif

				@if($tender->canCancel() && $tender->publish_prices > 0 && empty($tender->publish_winner))
				<li><a href="{{ asset('tenders/'.$tender->id.'/publishPrices') }}"><i class="fa fa-times"></i> Batal Umum Carta Tender</a></li>
				@endif

			@endif

			@if($tender->canShowWinner() && empty($tender->publish_winner))
				<li><a href="{{ asset('tenders/'.$tender->id.'/publishWinner') }}"><i class="fa fa-check"></i> Umum Penender Berjaya</a></li>
			@else
				<li><a href="{{ asset('tenders/'.$tender->id.'/publishWinner') }}"><i class="fa fa-check"></i> Batal Umum Penender Berjaya</a></li>
			@endif
		</ul>
	</div>

	<div class="tender-status pull-right">
		@if($tender->invitation)<span class="label label-default"><i class="fa fa-lock"></i> Tender Terhad</span>@endif
		@if($tender->approver_id)<span class="label label-success"><i class="fa fa-check"></i> Siar</span>@endif
		@if($tender->publish_prices)<span class="label label-default"><i class="fa fa-check"></i> Carta Tender</span>@endif
		@if($tender->publish_winner)<span class="label label-warning"><i class="fa fa-check"></i> Keputusan</span>@endif
	</div>
@endif

@if(Route::currentRouteAction() == 'TendersController@vendors')
	<a href="{{ asset('tenders/'.$tender->id.'/vendors/print') }}" class="pull-right print hidden-print" target="_new"><i class="fa fa-print"></i> Cetak</a>
@else
	<a href="javascript:window.print()" class="pull-right print hidden-print"><i class="fa fa-print"></i> Cetak</a>
@endif
<div class="clearfix"></div>
