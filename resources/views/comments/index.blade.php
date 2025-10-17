@extends('layouts.default')
@section('content')
	<h2>Comments</h2>
	<hr>
	<table data-path="/comments" class="DT table table-striped table-hover table-bordered">
		<thead class="bg-blue-selangor">
			<tr>
			
				<th>Organization Unit</th>
				<th>Email</th>
				<th>Body</th>
				
				<th width="200px">Actions</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
	<br>
	@include('comments.actions-footer', ['is_list' => true])
@endsection
@section('scripts')

	<script src="{{ asset('js/datatables.js') }}"></script>

@endsection