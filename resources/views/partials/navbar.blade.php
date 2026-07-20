<div class="navbar navbar-invert navbar-fixed-top btn-raised">
    <div class="container">
        <div class="navbar-header">
            @if($user && $user->company_id)
                <a href="/" class="navbar-brand">{{$user->company->name}}</a>
            @else
                <a href="/" class="navbar-brand">VMS</a>
            @endif
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav navbar-right">
                <!-- <li>
                     <a href="#">Contacts</a>
                </li> -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">PBT Selangor<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        @foreach (App\OrganizationUnit::where('parent_id', 1)->get() as $ou)
                            @if(Request::is('organizationunits/' . $ou->id))<li class="active">@else<li>@endif
                                 <a href="{{action('OrganizationUnitsController@show', $ou->id)}}">{{$ou->name}}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                @if($user)
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">VMS<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            @if(Route::currentRouteUses('TendersController@index'))<li class="active">@else<li>@endif
                                 <a href="{{action('TendersController@index')}}">Tenders</a>
                            </li>
                            @if(Route::currentRouteUses('NotificationsController@index'))<li class="active">@else<li>@endif
                                 <a href="{{action('NotificationsController@index')}}">Notifications</a>
                            </li>
                            @if(Route::currentRouteUses('TransactionsController@index'))<li class="active">@else<li>@endif
                                 <a href="{{action('TransactionsController@index')}}">Transactions</a>
                            </li>
                        </ul>
                    </li>
                    @if($user->hasRole('Admin'))
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Administration <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                            <!--     @if($controller === 'Profile')<li class="active">@else<li>@endif
                                    <a href="{{action('UsersController@profile')}}">Profile</a>
                                </li> -->
                                <!-- <li class="divider"></li> -->
                                <li class="dropdown-header">Companies Management</li>
                                @if(Route::currentRouteUses('CompaniesController@index'))<li class="active">@else<li>@endif
                                     <a href="{{action('CompaniesController@index')}}">Companies</a>
                                </li>

                                <li class="divider"></li>
                                <li class="dropdown-header">Certification Management</li>
                                @if(Route::currentRouteUses('CertificationCodesController@index'))<li class="active">@else<li>@endif
                                     <a href="{{action('CertificationCodesController@index')}}">Certification Codes</a>
                                </li>
                                @if(Route::currentRouteUses('CertificationTypesController@index'))<li class="active">@else<li>@endif
                                     <a href="{{action('CertificationTypesController@index')}}">Certification Types</a>
                                </li>

                                <li class="divider"></li>
                                <li class="dropdown-header">User Management</li>
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
                                <li class="divider"></li>
                                <li class="dropdown-header">ACL Management</li>
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
                            </ul>
                        </li>
                    @endif
                    <li>
                         <a href="{{action('AuthController@logout')}}">Logout</a>
                    </li>
                @else
                    @if(Route::currentRouteUses('HomeController@index'))<li class="active">@else<li>@endif
                         <a href="{{action('HomeController@index')}}">Login</a>
                    </li>
                    @if(Route::currentRouteUses('AuthController@create'))<li class="active">@else<li>@endif
                         <a href="{{action('AuthController@create')}}">Register</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>