
{!! Former::text('name')->label('Nama') ->required() !!}

@if($process == 'update')
<div class="form-group">
	<label for="sort_no" class="control-label col-lg-3 col-sm-3">Susunan <sup>*</sup></label>
	<div class="col-lg-9 col-sm-9">
			<ul id="simpleList" class="list-group">
				@foreach ($list_organization_type as $row_org_type)
					<li style="{{ $row_org_type->id == $type->id ? 'background-color: rgb(16, 92, 234); color: white' : ''}}" data-id="{{ $row_org_type->id }}" class="list-group-item">{{ $row_org_type->name }}</li>
				@endforeach
			</ul>
			
			<br>
			<input type="hidden" name="org_type_id" id="org_type_id" value="{{ $type->id ?? '' }}">
			<button type="button" class="btn btn-danger" id="resetOrder">Set Semula Susunan</button>
	</div>
</div>
@endif

@section('scripts')
    <script src="{{ asset('js/sortable.js') }}"></script>
    <script type="text/javascript">
        var simpleList = document.getElementById('simpleList');

        // create sortable and save instance
        var sortable = Sortable.create(simpleList, {
            animation: 150
        });

        // save initial order
        var initialOrder = sortable.toArray();

        document.getElementById('btn-simpan').addEventListener('click', function(e) {
            var order = sortable.toArray();

			data = {
				"org_type_id" : $("#org_type_id").val(),
				"order"	: order,
			};

			$.ajax({
				type: "post",
				url: "{{ route('org_type_custom_save') }}" ,
				data: data,
				success: function (response) {
					if(response.status = "success")
					{
						alert(response.message);
						window.location.href = response.redirect;
					}
				}
			});
        });

        document.getElementById('resetOrder').addEventListener('click', function(e) {
            sortable.sort(initialOrder);
        })
    </script>
@endsection
