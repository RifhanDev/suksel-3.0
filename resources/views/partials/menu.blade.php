<ul class="nav nav-pills nav-stacked">
    @if($controller === 'Home')<li class="active">@else<li>@endif
        <a href="/">Home</a>
    </li>
    @if($controller === 'Profile')<li class="active">@else<li>@endif
        <a href="{{action('UsersController@profile')}}">Profile</a>
    </li>
    @if(App\User::canList())
        @if($controller === 'UsersController')<li class="active">@else<li>@endif
            <a href="{{action('UsersController@index')}}">Users</a>
        </li>
    @endif
    @if(App\OrganizationUnit::canList())
        @if($controller === 'OrganizationUnitsController')<li class="active">@else<li>@endif
            <a href="{{action('OrganizationUnitsController@index')}}">Organizations</a>
        </li>
    @endif
    @if(App\Role::canList())
        @if($controller === 'RolesController')<li class="active">@else<li>@endif
            <a href="{{action('RolesController@index')}}">Roles</a>
        </li>
    @endif
    @if(App\Permission::canList())
        @if($controller === 'PermissionsController')<li class="active">@else<li>@endif
            <a href="{{action('PermissionsController@index')}}">Permissions</a>
        </li>
    @endif
    <li>
        <a href="{{action('AuthController@logout')}}">Logout</a>
    </li>
</ul>
