<!doctype html>
<html lang="en">
@include('subs.header')
@include('subs.sidebar')
@include('subs.footer')
<head>
    @include('subs.header-meta')
    @stack('styles')
</head>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!--  Sidebar -->
        @stack('sidebar-content')
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            @stack('content-header')
            <div class="container-fluid">
                @yield('content')
                @stack('content-footer')
            </div>
        </div>
        <!--  Main wrapper End -->
    </div>
    @include('subs.footer-script')
    @stack('scripts')
</body>

</html>