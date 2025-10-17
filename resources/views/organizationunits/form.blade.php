{!! Former::text('name')
	->label('Nama')
	->required() !!}
{!! Former::textarea('address')
	->label('Alamat') !!}
{!! Former::text('tel')
	->label('No Telefon') !!}
{!! Former::text('email')
	->label('Alamat Emel') !!}
{!! Former::checkbox('confirmation_agency')
	->label('Agensi Pengesahan')
	->checked(isset($organizationunit) && $organizationunit->confirmation_agency)
	->forceValue(1) !!}
{!! Former::select('type_id')
	->label('Kategori')
	->options(App\OrganizationType::all()->pluck('name', 'id'))
	->placeholder('Sila pilih kategori agensi...')
	->required() !!}

@php
	$default_pilihan = array("id" => "Tiada Parent");
	$pilihan_agensi_parent = App\OrganizationUnit::all()->pluck('name', 'id');
	$pilihan_agensi_parent[0] = "Tiada Parent";
	
	// dd($pilihan_agensi_parent->sortKeys());
@endphp

{!! Former::select('parent_id')
	->label('Agensi Utama')
	->options($pilihan_agensi_parent->sortKeys())
	->placeholder('Sila pilih agensi utama kepada agensi ini...') !!}

@section('scripts')

	<script type="text/javascript">
		$("#type_id").selectize();
		$('#parent_id').selectize();
	</script>
	
@endsection