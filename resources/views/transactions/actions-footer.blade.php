<div class="well">
	@if(isset($has_submit))
		<button class="btn btn-primary">Submit</button>
	@endif
	@if(!isset($is_list) && App\Transaction::canList())
		<a href="{{route('transactions.index')}}" class="btn btn-default">List</a>  
	@endif
	@if(App\Transaction::canCreate())
		<a href="{{route('transactions.create')}}" class="btn btn-default">Create</a>
	@endif
	{!! Former::close() !!}
	@if(isset($transaction))
		@if($transaction->canShow())
			<a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-default">Details</a>
		@endif
		@if($transaction->canUpdate())
			<a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-default">Edit</a>
		@endif
		@if($transaction->canDelete())
			{!! Former::open(url('transactions/'.$transaction->id))->class('form-inline')}}
			{!! Former::hidden('_method', 'DELETE') !!}
			<button type="button" class="btn btn-default confirm-delete">Delete</button>
			{!! Former::close() !!}
		@endif
	@endif
</div>