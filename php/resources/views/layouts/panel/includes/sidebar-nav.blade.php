<div class="aside">
    <div class="aside-nav">
        <div class="aside-nav-item"><h4 class="heading-title">Navigation</h4></div>
        <hr>
        <div class="aside-nav-item">
            <a class="{{ $view_page == 'dashboard' ? 'active' : '' }} aside-nav-link" href="{{ url('/panel/dashboard') }}">
                <em class="aside-nav-icon mdi mdi-speedometer"></em>
                Dashboard
            </a>
        </div>
        <div class="aside-nav-item">
            <a class="{{ $view_page == 'csv-parser' ? 'active' : '' }} aside-nav-link" href="{{ url('/panel/csv-parser') }}">
                <em class="aside-nav-icon mdi mdi-database"></em>
                CSV parser
            </a>
        </div>
        <div class="aside-nav-item">
            <a class="{{ $view_page == 'categories-list' ? 'active' : '' }} aside-nav-link" href="{{ url('/panel/categories') }}">
                <em class="aside-nav-icon mdi mdi-view-list"></em>
                Categories
            </a>
        </div>
        <div class="aside-nav-item">
            <a class="{{ $view_page == 'citizens-list' ? 'active' : '' }} aside-nav-link" href="{{ url('/panel/citizens') }}">
                <em class="aside-nav-icon mdi mdi-account-multiple"></em>
                Citizens
            </a>
        </div>
        <div class="aside-nav-item">
            <a class="{{ $view_page == 'shopers-list' ? 'active' : '' }} aside-nav-link" href="{{ url('/panel/shopers') }}">
                <em class="aside-nav-icon mdi mdi-shopping"></em>
                Shopers
            </a>
        </div>
        <div class="aside-nav-item">
            <a class="{{ $view_page == 'bugets-list' ? 'active' : '' }} aside-nav-link" href="{{ url('/panel/bugets') }}">
                <em class="aside-nav-icon mdi mdi-coins"></em>
                Bugets
            </a>
        </div>
        <div class="aside-nav-item">
            <a class="{{ $view_page == 'users-list-admins' ? 'active' : '' }} aside-nav-link" href="{{ url('/panel/users') }}">
                <em class="aside-nav-icon mdi mdi-account-circle"></em>
                System Users
            </a>
        </div>
        <div class="aside-nav-item">
            <a class="{{ $view_page == 'permissions' ? 'active' : '' }} aside-nav-link" href="{{ url('/panel/permissions') }}">
                <em class="aside-nav-icon mdi mdi-lock"></em>
                Permissions
            </a>
        </div>
        <div class="aside-nav-item">
            <a class="aside-nav-link" href="{{ url('/auth/logout') }}">
                <em class="aside-nav-icon mdi mdi-logout"></em>
                Log out
            </a>
        </div>
    </div>
</div>