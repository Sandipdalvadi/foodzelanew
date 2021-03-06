<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr'}}">
@include('admin.common.meta')

<body class="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr'}}">
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <!-- Page Header Start-->
        @include('admin.common.header')
        <!-- Page Header Ends   -->
        <!-- Page Body Start-->
        <div class="page-body-wrapper sidebar-icon">
            <!-- Page Sidebar Start-->
            @include('admin.common.sidebar')
            <!-- Page Sidebar Ends-->
            @yield('content')
            <!-- footer start-->
            @include('admin.common.footer')
        </div>
    </div>
    
    @include('admin.common.script')
</body>

</html>
