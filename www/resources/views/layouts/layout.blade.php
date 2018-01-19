<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8" />
        <title>{{config('app.name')}}</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/global/css/components.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
        
        @yield('page-styles')
        <link href="{{asset('assets/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/layouts/layout/css/layout.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/layouts/layout/css/themes/default.min.css')}}" rel="stylesheet" type="text/css" id="style_color" />
        <link href="{{asset('assets/layouts/layout/css/custom.min.css')}}" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="favicon.ico" />

        <link href="{{asset(mix('assets/customs/css/app.css')) }}" rel="stylesheet">

        <!-- Scripts -->
        <script>
            window.Laravel = {!! json_encode([
                    'csrfToken' => csrf_token(),
            ]) !!}
            ;
        </script>
    </head>
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
        <div class="page-wrapper">
            <div class="page-header navbar navbar-fixed-top">
                <div class="page-header-inner ">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo">
                        <a href="{{route('home')}}">
                            <img src="" alt="ManTech" class="logo-default" /> <!-- DODAĆ LOGO -->
                        </a>
                        <div class="menu-toggler sidebar-toggler">
                            <span></span>
                        </div>
                    </div>
                    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                        <span></span>
                    </a>
                    <div class="top-menu">
                        <ul class="nav navbar-nav pull-right">
                            <li class="dropdown dropdown-user dropdown-dark">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <img alt="avatar" class="img-circle" src="{{asset('assets/layouts/layout/img/avatar.png')}}">
                                    <span class="username username-hide-mobile">
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default">
                                  <li>
                                      <a href="{{route('profile.index')}}">
                                          <i class="fa fa-user"></i> Profil
                                      </a>
                                  </li>
                                    <li>
                                        <a href="{{route('logout')}}">
                                            <i class="icon-key"></i> Wyloguj się
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="clearfix">
            </div>
            <div class="page-container">
                <div class="page-sidebar-wrapper">
                    <!-- BEGIN SIDEBAR -->
                    <div class="page-sidebar navbar-collapse collapse">
                        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                            <li class="sidebar-toggler-wrapper hide">
                                <div class="sidebar-toggler">
                                    <span></span>
                                </div>
                            </li>
                            <li class="nav-item {!! BladeHelper::isRouteSelected('/') !!}">
                                <a href="{{route('home')}}" class="nav-link nav-toggle">
                                    <i class='fa fa-home'></i>
                                    <span class="title">Strona główna</span>
                                </a>
                            </li>
                            <!-- ITEM IN MENU -->
                            @can('list sections')
                            <li class="nav-item {!! BladeHelper::isRouteSelected('*section') !!}">
                                <a href="{{route('section.index')}}" class="nav-link nav-toggle">
                                    <i class='fa fa-list'></i>
                                    <span class="title">Działy</span>
                                </a>
                            </li>
                            @endcan
                            @can('list users')
                            <li class="nav-item {!! BladeHelper::isRouteSelected('*user') !!}">
                                <a href="{{route('user.index')}}" class="nav-link nav-toggle">
                                    <i class='fa fa-users'></i>
                                    <span class="title">Pracownicy</span>
                                </a>
                            </li>
                          @endcan
                             <li class="nav-item {!! BladeHelper::isRouteSelected('*item') !!}">
                                <a href="{{route('item.index')}}" class="nav-link nav-toggle">
                                    <i class='fa fa-list'></i>
                                    <span class="title">Przedmioty</span>
                                </a>
                            </li>
                            @can('list warehouse_document')
                            <li class="nav-item {!! BladeHelper::isRouteSelected('*warehouse_document') !!}">
                                <a href="{{route('warehouse_document.index')}}" class="nav-link nav-toggle">
                                    <i class='fa fa-tasks'></i>
                                    <span class="title">Dokumenty magazynowe</span>
                                </a>
                            </li>
                            @endcan
                            @can('list roles')
                            <li class="nav-item {!! BladeHelper::isRouteSelected('*role') !!}">
                                <a href="{{route('role.index')}}" class="nav-link nav-toggle">
                                    <i class='fa fa-lock'></i>
                                    <span class="title">Uprawnienia</span>
                                </a>
                            </li>
                            @endcan
                            <li class="nav-item {!! BladeHelper::isRouteSelected('*task') !!}">
                                <a href="{{route('task.index')}}" class="nav-link nav-toggle">
                                    <i class='fa fa-tasks'></i>
                                    <span class="title">Zadania</span>
                                </a>
                            </li>
                            <li class="heading">
                                <h3 class="uppercase">Słowniki</h3>
                            </li>           
                            <li class="nav-item {!! BladeHelper::isRouteSelected('*item_category*') !!}">                                
                                <a href="{{route('item_category.index')}}" class="nav-link nav-toggle">
                                    <i class='fa fa-book'></i>
                                    <span class="title">Kategorie przedmiotów</span>
                                </a>                        
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="page-content-wrapper">
                    <div class="page-content">
                        <h1 class="page-title">
                            @yield('title')
                        </h1>
                        @include('layouts.partial.messages')<!--widoki z wiadomościami-->
                        @yield('content')
                    </div>
                </div>
            </div>
            <div class="page-footer">
                <div class="page-footer-inner container"> 2017 &copy; Katalog
                    <img class="footer-image" src="" alt="ManTech"/> <!-- DODAĆ LOGO-->
                </div>
                <div class="scroll-to-top">
                    <i class="icon-arrow-up"></i>
                </div>
            </div>
            <!-- END FOOTER -->
        </div>
        <!--[if lt IE 9]>
        <script src="{{asset('assets/global/plugins/respond.min.js')}}"></script>
        <script src="{{asset('assets/global/plugins/excanvas.min.js')}}"></script>
        <script src="{{asset('assets/global/plugins/ie8.fix.min.js')}}"></script>
        <![endif]-->
        <script src="{{asset('assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/js.cookie.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/jquery.blockui.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
        <script src="{{asset(mix('assets/customs/js/app.js'))}}"></script>
        @yield('plugin-js')
        <script src="{{asset('assets/global/scripts/app.min.js')}}" type="text/javascript"></script>
        @yield('page-js')
        <script src="{{asset('assets/layouts/layout/scripts/layout.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/layouts/layout/scripts/demo.min.js')}}" type="text/javascript"></script>
    </body>
</html>
