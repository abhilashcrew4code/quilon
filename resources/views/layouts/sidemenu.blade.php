@section('sidemenu')

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('home') }}" class="app-brand-link">

            {{-- <span class="app-brand-text demo menu-text fw-bold ms-2 ps-1">Onesuite</span> --}}
            <span class="app-brand-logo demo">
                <span style="color: var(--bs-primary)">
                    <img width="160" height="200" src="{{ asset('assets/img/logo.png') }}" alt="CRM">
                </span>
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M11.4854 4.88844C11.0081 4.41121 10.2344 4.41121 9.75715 4.88844L4.51028 10.1353C4.03297 10.6126 4.03297 11.3865 4.51028 11.8638L9.75715 17.1107C10.2344 17.5879 11.0081 17.5879 11.4854 17.1107C11.9626 16.6334 11.9626 15.8597 11.4854 15.3824L7.96672 11.8638C7.48942 11.3865 7.48942 10.6126 7.96672 10.1353L11.4854 6.61667C11.9626 6.13943 11.9626 5.36568 11.4854 4.88844Z"
                    fill="currentColor" fill-opacity="0.6" />
                <path
                    d="M15.8683 4.88844L10.6214 10.1353C10.1441 10.6126 10.1441 11.3865 10.6214 11.8638L15.8683 17.1107C16.3455 17.5879 17.1192 17.5879 17.5965 17.1107C18.0737 16.6334 18.0737 15.8597 17.5965 15.3824L14.0778 11.8638C13.6005 11.3865 13.6005 10.6126 14.0778 10.1353L17.5965 6.61667C18.0737 6.13943 18.0737 5.36568 17.5965 4.88844C17.1192 4.41121 16.3455 4.41121 15.8683 4.88844Z"
                    fill="currentColor" fill-opacity="0.38" />
            </svg>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        <li class="menu-item {{ (Route::is('home') ? 'active' : '') }}">
            <a href="{{ route('home') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                <div data-i18n="Home">Home</div>
            </a>
        </li>

        @if( auth()->user()->can('dashboard'))
        <li class="menu-item {{ (Route::is('dashboard') ? 'active' : '') }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>
        @endif

        @if(auth()->user()->can('users.list') || auth()->user()->can('announcements') )

        <li class="menu-item {{ (Route::is('users.index') || Route::is('announcements.index') ? 'active open' : '') }}">

            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-account-cog-outline"></i>
                <div data-i18n="Administration"> Administration</div>
                <div class="badge bg-primary rounded-pill ms-auto"></div>
            </a>

            <ul class="menu-sub">
                @if(auth()->user()->can('users.list'))
                <li class="menu-item {{ (Route::is('users.index') ? 'active' : '') }}">
                    <a href="{{ route('users.index') }}" class="menu-link">
                        <div data-i18n="Manage Users">Manage Users</div>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('announcements'))
                <li class="menu-item {{ (Route::is('announcements.index') ? 'active' : '') }}">
                    <a href="{{ route('announcements.index') }}" class="menu-link">
                        <div data-i18n="Announcements">Announcements</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

    
        @if( auth()->user()->can('settings'))
        <li class="menu-item {{ (Route::is('settings.index') ? 'active' : '') }}">
            <a href="{{ route('settings.index') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-cog"></i>
                <div data-i18n="Configurations">Configurations</div>
            </a>
        </li>
        @endif

        @if(auth()->user()->can('acl.roles.manage') || auth()->user()->can('acl.permissions.manage') ||
        auth()->user()->can('acl.users.manage') )
        <li
            class="menu-item {{ (Route::is('roles.index') || Route::is('permissions.index') || Route::is('acl.user.permissions.view') || Route::is('acl.role.assign.permissions') || Route::is('acl.user.assign.permissions') ? 'active open' : '') }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-shield-outline"></i>
                <div data-i18n="Role Management">Role Management</div>
                <div class="badge bg-primary rounded-pill ms-auto"></div>
            </a>
            <ul class="menu-sub">
                @if(auth()->user()->can('acl.roles.manage'))
                <li
                    class="menu-item {{ (Route::is('roles.index') || Route::is('acl.role.assign.permissions') ? 'active' : '') }}">
                    <a href="{{ route('roles.index') }}" class="menu-link">
                        <div data-i18n="Manage Role">Manage Role</div>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('acl.users.manage'))
                <li
                    class="menu-item {{ (Route::is('acl.user.permissions.view') || Route::is('acl.user.assign.permissions') ? 'active' : '') }}">
                    <a href="{{ route('acl.user.permissions.view') }}" class="menu-link">
                        <div data-i18n="Manage User Permissions">Manage User Permissions</div>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('acl.permissions.manage'))
                <li class="menu-item {{ (Route::is('permissions.index') ? 'active' : '') }}">
                    <a href="{{ route('permissions.index') }}" class="menu-link ">
                        <div data-i18n="Manage  Permissions">Manage Permissions</div>
                    </a>
                </li>
                @endif
            </ul>

        </li>
        @endif

         @if(auth()->user()->can('products') || auth()->user()->can('orders') || auth()->user()->can('enquiry'))
        <li class="menu-item {{ (Route::is('products.index') || Route::is('orders.index') ||
        Route::is('enquiry.index') ? 'active open' : '') }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-account-cog-outline"></i>
                <div data-i18n="Manage">  Manage</div>
                <div class="badge bg-primary rounded-pill ms-auto"></div>
            </a>
            <ul class="menu-sub">
                @if(auth()->user()->can('products'))
                <li class="menu-item {{ (Route::is('products.index') ? 'active' : '') }}">
                    <a href="{{ route('products.index') }}" class="menu-link">
                        <div data-i18n="Products">Products</div>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('orders'))
                <li class="menu-item {{ (Route::is('orders.index') ? 'active' : '') }}">
                    <a href="{{ route('orders.index') }}" class="menu-link">
                        <div data-i18n="Orders">Orders</div>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('enquiry'))
                <li class="menu-item {{ (Route::is('enquiry.index') ? 'active' : '') }}">
                    <a href="{{ route('enquiry.index') }}" class="menu-link">
                        <div data-i18n="Enquiry">Enquiry</div>
                    </a>
                </li>
                @endif


            </ul>
        </li>
        @endif

        
        @if(auth()->user()->can('expenses'))
         <li class="menu-item {{ (Route::is('expenses.index') ? 'active' : '') }}">
            <a href="{{ route('expenses.index') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                <div data-i18n="Expenses">Expenses</div>
            </a>
        </li>
        @endif

       
        @if(auth()->user()->can('reports.login.logs.list'))
        <li class="menu-item {{ (Route::is('reports.login.logs.list') ? 'active open' : '') }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-account-cog-outline"></i>
                <div data-i18n="Reports And Logs">  Reports And Logs</div>
                <div class="badge bg-primary rounded-pill ms-auto"></div>
            </a>
            <ul class="menu-sub">
                @if(auth()->user()->can('reports.login.logs.list'))
                <li class="menu-item {{ (Route::is('reports.login.logs.list') ? 'active' : '') }}">
                    <a href="{{ route('reports.login.logs.list') }}" class="menu-link">
                        <div data-i18n="Login Logs">Login Logs</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif
    </ul>
</aside>

@endsection