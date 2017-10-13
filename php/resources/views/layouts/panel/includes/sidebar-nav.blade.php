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
        
        @can('upload_budgets')
        <div class="aside-nav-item">
            <a class="{{ $view_page == 'csv-parser' ? 'active' : '' }} aside-nav-link" href="{{ url('/panel/csv-parser') }}">
                <em class="aside-nav-icon mdi mdi-database"></em>
                CSV parser
            </a>
        </div>
        @endcan

        @can('manage_citizens')
        <div class="aside-nav-item">
            <a class="{{ strpos($view_page, 'citizens') === 0 ? 'active' : '' }} aside-nav-link" href="{{ url('/panel/citizens') }}">
                <em class="aside-nav-icon mdi mdi-account-multiple"></em>
                Citizens
            </a>
        </div>
        @endcan

        @can('manage_budgets')
        <div class="aside-nav-item">
            <a class="{{ strpos($view_page, 'budgets') === 0 ? 'active' : '' }} aside-nav-link" href="{{ url('/panel/budgets') }}">
                <em class="aside-nav-icon mdi mdi-coins"></em>
                Budgets
            </a>
        </div>
        @endcan

        @can('manage_shop-keepers')
        <div class="aside-nav-item">
            <a class="{{ strpos($view_page, 'shop_keepers') === 0 ? 'active' : '' }} aside-nav-link" href="{{ url('/panel/shop-keepers') }}">
                <em class="aside-nav-icon mdi mdi-shopping"></em>
                ShopKeepers
            </a>
        </div>
        @endcan
        
        @can('manage_categories')
        <div class="aside-nav-item">
            <a class="{{ strpos($view_page, 'categories') === 0 ? 'active' : '' }} aside-nav-link" href="{{ url('/panel/categories') }}">
                <em class="aside-nav-icon mdi mdi-view-list"></em>
                Categories
            </a>
        </div>
        @endcan

        @can('manage_vouchers')
        <div class="aside-nav-item">
            <a class="{{ strpos($view_page, 'vouchers') === 0 ? 'active' : '' }} aside-nav-link" href="{{ url('/panel/vouchers') }}">
                <em class="aside-nav-icon mdi mdi-ticket"></em>
                Vouchers
            </a>
        </div>
        @endcan

        @if(FALSE)
        @can('manage_budgets')
        <div class="aside-nav-item">
            <a class="{{ strpos($view_page, 'users') === 0 ? 'active' : '' }} aside-nav-link" href="{{ url('/panel/users') }}">
                <em class="aside-nav-icon mdi mdi-account-circle"></em>
                System Users
            </a>
        </div>
        @endcan
        @endif

        @can('manage_permissions')
        <div class="aside-nav-item">
            <a class="{{ strpos($view_page, 'permissions') === 0 ? 'active' : '' }} aside-nav-link" href="{{ url('/panel/permissions') }}">
                <em class="aside-nav-icon mdi mdi-lock"></em>
                Permissions
            </a>
        </div>
        @endcan

        <div class="aside-nav-item">
            {!! Form::open(['url' => route('logout')]) !!}
            <label for="submit_logout" class="aside-nav-link">
                <em class="aside-nav-icon mdi mdi-logout"></em>
                Log out
            </label>
            {!! Form::submit('', ['hidden', 'id' => 'submit_logout']) !!}
            {!! Form::close() !!}
        </div>
    </div>
</div>