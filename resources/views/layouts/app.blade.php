<!-- 存放在 resources/views/layouts/app.blade.php -->
<html lang="{{ app()->getLocale() }}">
<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="/favicon.png" title="Favicon">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css')}}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('dist/css/skins/_all-skins.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/flat/blue.css')}}">
    <!-- Morris chart -->
    <link rel="stylesheet" href="{{ asset('plugins/morris/morris.css')}}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">


    <!-- jQuery 2.2.3 -->
    <script src="{{ asset('plugins/jQuery/jquery-2.2.3.min.js')}}"></script>

    <!-- Bootstrap 3.3.6 -->
    <script src="{{ asset('bootstrap/js/bootstrap.min.js')}}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/app.min.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('dist/js/demo.js')}}"></script>

</head>
<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">
    <header class="main-header">
        <a href="{{ url('site/ebay') }}" class="logo">
            <span class="logo-lg"><b>iBay</b>365</span>
        </a>
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
        </nav>
    </header>
    <aside class="main-sidebar">
        @section('sidebar')
            <section class="sidebar">

                <ul class="sidebar-menu">
                    <li class="active treeview site-ebay">
                        <a href="{{ url('site/ebay') }}">
                            <i class="fa fa-dashboard"></i> <span>eBay</span>
                        </a>
                    </li>
                    <li class="treeview site-wish">
                        <a href="{{ url('site/wish') }}">
                            <i class="fa fa-diamond"></i> <span>Wish</span>
                        </a>
                    </li>
                </ul>
            </section>
        @show
    </aside>


    @yield('content')

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 1.0
        </div>
        <strong>Copyright &copy; 2018.5 <a href="http://www.shyouranindustry.com/">上海幽然实业有限公司</a>.</strong> 版权所有
        reserved.
    </footer>

</div>
</body>
</html>