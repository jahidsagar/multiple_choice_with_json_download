@include('admin/static/htmlhead')

    <div id="wrapper">

        <!-- Navigation -->
        @include('admin/static/header');

        <div id="page-wrapper" style="margin-top: -1.2%;">
            @yield('content')
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    @include('admin/static/footerscript')