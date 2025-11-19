<div class="well">
	@if(isset($has_submit))
		<button class="btn btn-primary">Submit</button>
	@endif
	@if(!isset($is_list) && App\Comment::canList())
		<a href="{{ asset('comments')}}" class="btn btn-default">List</a>  
	@endif
	@if(App\Comment::canCreate())
		<a href="{{ asset('comments/create')}}" class="btn btn-default">Create</a>
	@endif
	{!! Former::close() !!}
	@if(isset($comment))
		@if($comment->canShow())
			<a href="{{ asset('comments/'.$comment->id) }}" class="btn btn-default">Details</a>
		@endif
		@if($comment->canUpdate())
			<a href="{{ asset('comments/'.$comment->id.'/edit') }}" class="btn btn-default">Edit</a>
		@endif
		@if($comment->canDelete())
			{!! Former::open( url('comments/'.$comment->id))->class('form-inline') !!}
			{!! Former::hidden('_method', 'DELETE') !!}
				<button type="button" class="btn btn-default confirm-delete">Delete</button>
			{!! Former::close() !!}
		@endif
	@endif
</div>