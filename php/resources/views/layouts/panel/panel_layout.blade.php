<!DOCTYPE html>
<html lang="en">
@include('layouts.panel.includes.head')

<body>
    <!-- include includes/dev-pages-->
    @include('layouts.panel.includes.header')
    @include('layouts.panel.includes.sidebar-nav')
    
    <div class="content">
        <div class="content-inner">
            @yield('content')
        </div>
    </div>
    
    @include('layouts.panel.includes.footer')
    @include('layouts.panel.includes.scripts')
</body>
</html>