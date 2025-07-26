<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Logger</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{asset('logger/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('logger/css/mdi/css/materialdesignicons.min.css')}}">
    <link rel="shortcut icon" href="{{asset('logger/images/favicon.png')}}" />
</head>
<body>
<div class="container-scroller">
    <!-- partial:../../partials/_navbar.html -->
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
            <div class="me-3">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>
            </div>
            <div>
                <a class="navbar-brand brand-logo" href="{{route('log.index')}}">
                    <img src="{{asset('logger/images/logo.svg')}}" alt="logo" />
                </a>
                <a class="navbar-brand brand-logo-mini" href="{{route('log.index')}}">
                    <img src="{{asset('logger/images/logo.svg')}}" alt="logo" />
                </a>
            </div>
        </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item {{url()->current() == route('log.index') ? 'active' : ''}}">
                    <a class="nav-link" href="{{route('log.index')}}">
                        <i class="mdi mdi-grid-large menu-icon"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="" aria-expanded="false" aria-controls="charts">
                        <i class="menu-icon mdi mdi-chart-line"></i>
                        <span class="menu-title">Charts</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- partial -->
        <div class="main-panel">
            @yield('content')
            <footer class="footer">
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                    <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
                        <a href="https://github.com/mockingbitch/laravel-logger" target="_blank">Logger Laravel</a>
                        made by Phong Tran.
                    </span>
                    <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Copyright © 2024. All rights reserved.</span>
                </div>
            </footer>
        </div>
    </div>
</div>
<script src="{{asset('logger/js/vendor.bundle.base.js')}}"></script>
</body>
</html>
