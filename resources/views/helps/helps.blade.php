<div class="panel panel-default">
    	<div class="panel-heading" role="tab" id="help-title-{{ $help->id }}">
        	<h4 class="panel-title">
        		<a data-toggle="collapse" data-parent="#helps" href="#help-{{ $help->id }}" aria-expanded="true" aria-controls="collapseOne">
           	 	{{ $help->question }}
        		</a>
        	</h4>

        	@if(Auth::user() && Auth::user()->hasRole('Admin'))
	        	<br>
	        	{{ link_to_route('helps.edit', 'Kemaskini', $help->id, ['class' => 'btn btn-xs btn-primary'])}}
	        	{!! Former::open(url('helps/'.$help->id))->class('form-inline') !!}
	            {!! Former::hidden('_method', 'DELETE') !!}
	            {!! Former::button('action')->class('btn btn-xs btn-danger confirm-delete')->value('Padam') !!}
	        	{!! Former::close() !!}
        	@endif
    	</div>
    	<div id="help-{{ $help->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="help-title-{{ $help->id }}">
        	<div class="panel-body">
            {!! $help->answer !!}
        	</div>
    	</div>      
</div>