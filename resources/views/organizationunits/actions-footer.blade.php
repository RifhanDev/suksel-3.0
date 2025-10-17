<div class="well">
	@if(isset($has_submit))
		<button class="btn btn-primary">Simpan</button>
	@endif
	@if(App\OrganizationUnit::canList())
	<div class="btn-group dropup">
		<a href="{{ asset('agencies') }}" class="btn btn-default">Senarai Agensi</a>
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		<span class="caret"></span>
		<span class="sr-only">Papar Mengikut Kategori</span>
		</button>
		<ul class="dropdown-menu pull-right" role="menu">
			@foreach(App\OrganizationType::all() as $type)
				<li><a href="{{ route('agencies.index', ['type' => $type->id]) }}">{{$type->name}}</a></li>
			@endforeach
		</ul>
	</div>
	@endif
	@if(App\OrganizationUnit::canCreate())
		<a href="{{ asset('agencies/create')}}" class="btn btn-default">Masukan Agensi Baru</a>
	@endif
	{!! Former::close() !!}
	@if(isset($organizationunit))
		@if($organizationunit->canShow())
			<a href="{{ asset('agencies/'.$organizationunit->id) }}" class="btn btn-default">Tender Agensi</a>
		@endif
		@if($organizationunit->canUpdate())
			<a href="{{ asset('agencies/'.$organizationunit->id.'/edit') }}" class="btn btn-default">Kemaskini</a>
		@endif
		@if($organizationunit->canDelete())
			{!! Former::open(url('agencies/'.$organizationunit->id))->class('form-inline')  !!}
				{!! Former::hidden('_method', 'DELETE')  !!}
				<button type="button" class="btn btn-danger confirm-delete">Padam</button>
			{!! Former::close()  !!}
		@endif
	@endif
</div>
