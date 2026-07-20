<!-- BEGIN HEADER TOP -->
<div class="page-header-top">
    <div class="container">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="/">
                <!-- <img src="" alt="logo" class="logo-default"> -->
            </a>
        </div>
        <a href="javascript:;" class="menu-toggler"></a>
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <li class="dropdown dropdown-user dropdown-dark">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <span class="username">Agensi SUK Selangor</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default" role="menu">
                        @foreach (App\OrganizationUnit::where('parent_id', 1)->get() as $ou)
                        @if(Request::is('organizationunits/' . $ou->id))<li class="active">@else<li>@endif
                            <a href="{{action('OrganizationUnitsController@show', $ou->id)}}">{{$ou->name}}</a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                <li class="dropdown dropdown-user dropdown-dark">
                    @if($user)
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <span class="username username-hide-mobile">{{$user->email}}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="{{action('UsersController@profile')}}">
                            <i class="icon-user"></i> My Profile </a>
                        </li>
                        <li>
                            <a href="#">
                            <i class="icon-calendar"></i> My Calendar </a>
                        </li>
                        <li class="divider">
                        </li>
                        <li>
                            <a href="{{action('AuthController@logout')}}">
                            <i class="icon-key"></i> Log Out </a>
                        </li>
                    </ul>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- END HEADER TOP -->