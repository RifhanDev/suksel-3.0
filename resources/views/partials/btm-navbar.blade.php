<!-- BEGIN HEADER MENU -->
<div class="page-header-menu">
    <div class="container">
        <!-- BEGIN MEGA MENU -->
        <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
        <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
        <div class="hor-menu ">
            <ul class="nav navbar-nav">
                <li>
                    <a href="/">Utama</a>
                </li>
                @if($user->ability(['Admin', 'User'], []))
                <li class="menu-dropdown mega-menu-dropdown mega-menu-full ">
                    <a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;" class="dropdown-toggle">
                        Modul <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <div class="mega-menu-content">
                                <div class="row">
                                    <div class="col-md-3">
                                        <ul class="mega-menu-submenu">
                                            <li>
                                                <h3>Pengurusan Tender</h3>
                                            </li>
                                            <li>
                                                <a href="{{action('TendersController@index')}}">
                                                    <i class="fa fa-angle-right"></i>
                                                    Senarai
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-3">
                                        <ul class="mega-menu-submenu">
                                            <li>
                                                <h3>Pengurusan Pembekal</h3>
                                            </li>
                                            <li>
                                                <a href="{{action('VendorsController@index')}}">
                                                    <i class="fa fa-angle-right"></i>
                                                    Senarai
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{action('ChangeRequestsController@all')}}">
                                                    <i class="fa fa-angle-right"></i>
                                                    Permintaan Kemaskini
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-3">
                                        <ul class="mega-menu-submenu">
                                            <li>
                                                <h3>Pengurusan Agensi</h3>
                                            </li>
                                            <li>
                                                <a href="{{action('OrganizationUnitsController@index')}}">
                                                    <i class="fa fa-angle-right"></i>
                                                    Senarai
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-3">
                                        <ul class="mega-menu-submenu">
                                            <li>
                                                <h3>Pengurusan Transaksi</h3>
                                            </li>
                                            <li>
                                                <a href="{{action('TransactionsController@index')}}">
                                                    <i class="fa fa-angle-right"></i>
                                                    Senarai
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="menu-dropdown mega-menu-dropdown mega-menu-full ">
                    <a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;" class="dropdown-toggle">
                        Laporan <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <div class="mega-menu-content">
                                <div class="row">
                                    <div class="col-md-3">
                                        <ul class="mega-menu-submenu">
                                            <li>
                                                <h3>Laporan Tender</h3>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-3">
                                        <ul class="mega-menu-submenu">
                                            <li>
                                                <h3>Laporan Pembekal</h3>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-3">
                                        <ul class="mega-menu-submenu">
                                            <li>
                                                <h3>Laporan Agensi</h3>
                                            </li> 
                                        </ul>
                                    </div>
                                    <div class="col-md-3">
                                        <ul class="mega-menu-submenu">
                                            <li>
                                                <h3>Laporan Transaksi</h3>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="menu-dropdown mega-menu-dropdown mega-menu-full ">
                    <a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;" class="dropdown-toggle">
                        Ketetapan <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <div class="mega-menu-content">
                                <div class="row">
                                    <div class="col-md-3">
                                        <ul class="mega-menu-submenu">
                                            <li>
                                                <h3>Pengurusan Tender</h3>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <h3>Pengurusan Pembekal</h3>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-3">
                                        <ul class="mega-menu-submenu">
                                            <li>
                                                <h3>Pengurusan Agensi</h3>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <h3>Pengurusan Transaksi</h3>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-3">
                                        <ul class="mega-menu-submenu">
                                            <li>
                                                <h3>Pengurusan Pengguna</h3>
                                            </li>
                                            @if(App\User::canList())
                                                <li>
                                                    <a href="{{action('UsersController@index')}}">
                                                        <i class="fa fa-angle-right"></i>
                                                        Pengguna
                                                    </a>
                                                </li>
                                            @endif
                                            @if(App\OrganizationUnit::canList())
                                                <li>
                                                    <a href="{{action('OrganizationUnitsController@index')}}">
                                                        <i class="fa fa-angle-right"></i>
                                                        Organisasi
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="col-md-3">
                                        <ul class="mega-menu-submenu">
                                            <li>
                                                <h3>Pengurusan ACL</h3>
                                            </li>
                                            @if(App\Role::canList())
                                                <li>
                                                    <a href="{{action('RolesController@index')}}">
                                                        <i class="fa fa-angle-right"></i>
                                                        Peranan
                                                    </a>
                                                </li>
                                            @endif
                                            @if(App\Permission::canList())
                                                <li>
                                                    <a href="{{action('PermissionsController@index')}}">
                                                        <i class="fa fa-angle-right"></i>
                                                        Kebenaran
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
                @endif
                <!-- <li class="menu-dropdown">
                    <a href="angularjs" target="_blank" class="tooltips" data-container="body" data-placement="bottom" data-html="true" data-original-title="AngularJS version demo">
                    AngularJS Version </a>
                </li> -->
            </ul>
        </div>
        <!-- END MEGA MENU -->
    </div>
</div>
<!-- END HEADER MENU -->