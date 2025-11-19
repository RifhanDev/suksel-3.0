{!! Former::select('category_id')
		->label('Kategori')
		->required()
		->options(App\HelpCategory::pluck('name', 'id')) !!}
{!! Former::text('question')
		->label('Soalan')
		->required() !!}
{!! Former::textarea('answer')
		->label('Jawapan')
		->required()
		->rows(5) !!}

@section('scripts')
	<script type="text/javascript">
	    $("#category_id").selectize();
	</script>
@endsection