{!! Former::text('title')
    ->label('Tajuk')
    ->required() !!}

	<div class="form-group required">
    	<label for="notification" class="control-label col-lg-3 col-sm-3">Kandungan <sup>*</sup></label>
    	<div class="col-lg-9 col-sm-9">
        	<textarea class="form-control" rows="4" required="true" id="notification" name="notification">{!! isset($news) ? $news->notification : '' !!}</textarea>
        	<div id="notification-editor" class="summernote">{!! isset($news) ? $news->notification : '' !!}</div>
    	</div>
	</div>

	@if(Auth::user()->hasRole('Admin'))
		{!! Former::select('organization_unit_id')
		    	->label('Agensi')
		    	->options(App\OrganizationUnit::all()->pluck('name', 'id'))
		    	->required() !!}
	@endif
	{!! Former::select('tender_id')
    	->label('Tender')
    	->options([])
 		->placeholder('Sila cari menggunakan nama tender atau no rujukan...') !!}

@section('scripts')	
    {{-- <script src="https://cdn.ckeditor.com/4.20.2/full/ckeditor.js"></script> --}}
    <script src="{{ asset('custom_library/ckeditor/ckeditor.js') }}"></script>

	<script type="text/javascript">
		$("#organization_unit_id").selectize();
		$("#tender_id").selectize({
			valueField: 'id',
			labelField: 'name',
			searchField: 'name',
			create: false,
			render: {
				option: function(item, escape){
					return '<div><strong>' + escape(item.ref_number) + '</strong> ' + escape(item.name) + '</div>';
				}
			},
			load: function(query, callback) {
				if(!query.length) return callback();
				$.ajax({
					url: '/tenders/select?q=' + query,
					type: 'GET',
					success: function(res){
						callback(res);
					},
					error: function(){
						callback();
					}
				})
			}
		});
		// $('#notification').hide();
		// $('#notification-editor').summernote({
		//   toolbar: [
		// 		['style', ['bold', 'italic', 'underline', 'clear']],
		// 		['font', ['strikethrough']],
		// 		['fontsize', ['fontsize']],
		// 		['color', ['color']],
		// 		['para', ['ul', 'ol', 'paragraph']],
		// 		['table', ['table']],
		//   	],
		//   	onChange: function(contents) {
		//     	$('#notification').val(contents);
		//   	}
		// });

		CKEDITOR.replace('notification', {
            toolbarGroups: [
                { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                { name: 'forms', groups: [ 'forms' ] },
                { name: 'insert', groups: [ 'insert' ] },
                '/',
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                { name: 'links', groups: [ 'links' ] },
                '/',
                { name: 'styles', groups: [ 'styles' ] },
                { name: 'colors', groups: [ 'colors' ] },
                { name: 'tools', groups: [ 'tools' ] },
                { name: 'others', groups: [ 'others' ] },
                { name: 'about', groups: [ 'about' ] }
            ],
            removeButtons: 'Flash,Iframe,Form,TextField,Checkbox,Radio,Textarea,Select,Button,ImageButton,HiddenField'
        });
		
	</script>
@endsection