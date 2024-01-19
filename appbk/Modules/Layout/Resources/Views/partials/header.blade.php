<header class="main-header">
    <a href="{{ route('admin.dashboard.index') }}" class="logo">
    <img src="{{ asset('images/backend-logo.svg') }}" width="150" height="100%">
    </a>
    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <span class="hidden-xs">{{ trans('layout::admin.header.logout') }}</span>
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>                    
                </li>
            </ul>
        </div>
    </nav>  
</header>