<div class="well">
    @if(isset($has_submit))
        <button class="btn btn-primary">Submit</button>
    @endif
    @if(!isset($is_list) && Notification::canList())
        <a href="{{route('notifications.index')}}" class="btn btn-default">List</a>  
    @endif
    @if(Notification::canCreate())
        <a href="{{route('notifications.create')}}" class="btn btn-default">Create</a>
    @endif
    {{Former::close()}}
    @if(isset($notification))
        @if($notification->canShow())
          <a href="{{ route('notifications.show', $notification->id) }}" class="btn btn-default">Details</a>
        @endif
        @if($notification->canUpdate())
          <a href="{{ route('notifications.edit', $notification->id) }}" class="btn btn-default">Edit</a>
        @endif
        @if($notification->canDelete())
          {{Former::open(route('notifications.destroy', $notification->id))->class('form-inline')}}
            {{Former::hidden('_method', 'DELETE')}}
            <button type="button" class="btn btn-default confirm-delete">Delete</button>
          {{Former::close()}}
        @endif
    @endif
</div>