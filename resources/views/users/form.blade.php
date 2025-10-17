{!! Former::text('name')
		->label('Nama')
		->required() !!}
{!! Former::email('email')
		->label('Alamat Emel')
		->required() !!}
{!! Former::multiselect('roles')
		->label('Peranan')
		->required()
		->options(App\Role::where('name', '!=', 'Vendor')->availableRoles()->pluck('name', 'id'), (isset($currentUser) ? $currentUser->roles->pluck('id') : [])) !!}
@if(Auth::user()->hasRole('Admin'))
 	{!! Former::select('organization_unit_id')
			->label('Agensi')
			->required()
			->placeholder('Pilih Agensi bagi pengguna dengan peranan Agency Admin atau Agency User')
			->options(App\OrganizationUnit::all()->pluck('name', 'id'), isset($currentUser) ? $currentUser->organization_unit_id : '') !!}
@endif

@section('scripts')

	@parent
	<script type ="text/javascript">
		$('#roles').selectize()
		$('#organization_unit_id').selectize()
	</script>
	
@endsection
