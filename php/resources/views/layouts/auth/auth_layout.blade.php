<!DOCTYPE html>
<html lang="en">
@include('layouts.panel.includes.head')

<body>
    <!-- include includes/dev-pages-->
    @include('layouts.panel.includes.header')
    
    @yield('content')
    
    @include('layouts.panel.includes.footer')
    @include('layouts.panel.includes.scripts')
</body>
</html>