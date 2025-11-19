<table class="table table-striped table-bordered table-condensed table-hover">
	<thead class="bg-blue-selangor">
		<tr>
			<th>Nama</th>
			<th>Jenis</th>
			<th>Saiz</th>
			<th>Tarikh Dimuat Naik</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		@if(isset($uploads) && count($uploads) > 0)
			@foreach($uploads as $upload)
				<tr>
					<td>{{$upload->name}}</td>
					<td>{{$upload->type}}</td>
					<td>{{$upload->size}}</td>
					<td>{{Carbon\Carbon::parse($upload->created_at)->format('d/m/Y')}}
					<td>
						{{-- <a href="{{$upload->url}}/{{$upload->name}}" class="btn btn-primary" download>Muat Turun</a> --}}
                        <button class="btn btn-warning btn-file-view" data-url="{{ $upload->url . '/' . $upload->name }}">Lihat</button>
					</td>
				</tr>
			@endforeach
		@else
			<tr>
				<td colspan="5">Tiada fail</td>
			</tr>
		@endif
	</tbody>
</table>
