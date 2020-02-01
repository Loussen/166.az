@php
    $locale = app() -> getLocale();

    $currentRoute = Route::currentRouteName();

    $languages = \App\Http\Middleware\Language::LANGUAGES();

    $user = Auth::user();
@endphp

    <!DOCTYPE html>
<html lang="{{ $locale }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@section('title') Dashboard @show | {{ __('message.Project') }} - Admin</title>

        <link rel="shortcut icon" type="image/png" href="{{ asset('site/img/Logo.png') }}"/>

        <!--begin::Base Styles -->
        <link href="{{ asset('admin/css/vendors.bundle.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('admin/css/style.bundle.css') }}" rel="stylesheet" type="text/css"/>
        <!--end::Base Styles -->

        <!--begin::Custom Styles -->
        <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet" type="text/css"/>
        <!--end::Custom Styles -->

        <link href="{{ asset('admin/css/bootstrap-tagsinput.css') }}" rel="stylesheet" type="text/css"/>

        <!--begin::Frontend Styles -->
        <link href="{{ asset('admin/css/front-style.css') }}" rel="stylesheet" type="text/css"/>
        <!--end::Frontend Styles -->
    </head>

    <body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default" style="overflow: hidden;">

        <!-- begin:: Page -->
        <div class="m-grid m-grid--hor m-grid--root m-page">

            <!-- BEGIN: Header -->
            <header id="m_header" class="m-grid__item m-header" m-minimize-offset="200" m-minimize-mobile-offset="200">
                <div class="m-container m-container--fluid m-container--full-height">
                    <div class="m-stack m-stack--ver m-stack--desktop">
                        <!-- BEGIN: Brand -->
                        <div class="m-stack__item m-brand  m-brand--skin-dark">
                            <div class="m-stack m-stack--ver m-stack--general">
                                <div class="m-stack__item m-stack__item--middle m-brand__logo">
                                    <a href="{{ route( 'site.home' ) }}" class="m-brand__logo-wrapper" target="_blank">
                                        <img class="logo-img" alt="{{ __('message.Project') }}" src="{{ asset('site/img/Logo.png') }}"/>
                                    </a>
                                </div>
                                <div class="m-stack__item m-stack__item--middle m-brand__tools">

                                    <!-- BEGIN: Left Aside Minimize Toggle -->
                                    <a href="javascript:;" id="m_aside_left_minimize_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block">
                                        <span></span>
                                    </a>
                                    <!-- END -->

                                    <!-- BEGIN: Responsive Aside Left Menu Toggler -->
                                    <a href="javascript:;" id="m_aside_left_offcanvas_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
                                        <span></span>
                                    </a>
                                    <!-- END -->


                                    <!-- BEGIN: Responsive Header Menu Toggler -->
                                    <a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">
                                        <span></span>
                                    </a>
                                    <!-- END -->


                                    <!-- BEGIN: Topbar Toggler -->
                                    <a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
                                        <i class="flaticon-more"></i>
                                    </a>
                                    <!-- BEGIN: Topbar Toggler -->
                                </div>
                            </div>
                        </div>
                        <!-- END: Brand -->
                        <div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav" style="height: 70px;">
                            <!-- BEGIN: Horizontal Menu -->
                            <button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-dark " id="m_aside_header_menu_mobile_close_btn">
                                <i class="la la-close"></i></button>

                            <div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-light m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-dark m-aside-header-menu-mobile--submenu-skin-dark ">
                                <ul class="m-menu__nav">
                                    <li class="m-menu__item">
                                        <h3 style="color: #ffc425;">@section('title') Dashboard @show</h3>
                                    </li>
                                </ul>
                            </div>
                            <!-- END: Horizontal Menu -->
                            <!-- BEGIN: Topbar -->
                            <div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general m-stack--fluid">
                                <div class="m-stack__item m-topbar__nav-wrapper">
                                    <ul class="m-topbar__nav m-nav m-nav--inline">
                                        <li class="m-nav__item not-show">@section('menu-header') @show</li>
                                        <li class="m-nav__item m-topbar__user-profile m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" m-dropdown-toggle="click" aria-expanded="true">
                                            <a href="#" class="m-nav__link m-dropdown__toggle">
                                                <span class="m-nav__link-icon m-topbar__usericon">
                                                    <span class="m-nav__link-icon-wrapper">
                                                        <i class="flaticon-user-ok"></i>
                                                    </span>
                                                </span>
                                                <span class="m-topbar__username m--hide">{{ $user -> username }}</span>
                                            </a>
                                            <div class="m-dropdown__wrapper">
                                                <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust" style="left: auto; right: 5px;"></span>
                                                <div class="m-dropdown__inner">
                                                    <div class="m-dropdown__header m--align-center">
                                                        <div class="m-card-user m-card-user--skin-light">
                                                            <div class="m-card-user__details">
                                                                <span class="m-card-user__name m--font-weight-500">{{ $user -> name }} {{ $user -> surname }}</span>
                                                                <a class="m-card-user__email m--font-weight-300 m-link">{{ $user -> username }}</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="m-dropdown__body">
                                                        <div class="m-dropdown__content">
                                                            <ul class="m-nav m-nav--skin-light">
                                                                <li class="m-nav__separator m-nav__separator--fit"></li>
                                                                <li class="m-nav__item">
                                                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">{{ __('message.Logout') }}</a>
                                                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                                        @csrf
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- END: Topbar -->
                        </div>
                    </div>
                </div>
            </header>
            <!-- END: Header -->

            <!-- begin::Body -->
            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">

                <!-- BEGIN: Left Aside -->
                <button class="m-aside-left-close  m-aside-left-close--skin-dark" id="m_aside_left_close_btn">
                    <i class="la la-close"></i>
                </button>

                <div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark">
                    <!-- BEGIN: Aside Menu -->
                    <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark" m-menu-vertical="1" m-menu-scrollable="1" m-menu-dropdown-timeout="500" style="position: relative;">
                        <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
                            {!! \App\Http\Controllers\Controller::menu() !!}
                        </ul>
                    </div>
                    <!-- END: Aside Menu -->
                </div>
                <!-- END: Left Aside -->
                <div class="m-grid__item m-grid__item--fluid m-wrapper" style="position:relative;">
                    <div id="loading" class="loading">
                        <div class="cssload-container">
                            <div class="cssload-loading"><i></i><i></i><i></i><i></i></div>
                        </div>
                    </div>
                    <div class="m-content scroll" style="height: calc(100vh - 130px);" id="content">
                        @yield('content')
                    </div>
                </div>
            </div>
            <!-- end:: Body -->

            <!-- begin::Footer -->
            <footer class="m-grid__item m-footer" style="position:fixed; bottom: 0; z-index:100; height: 60px;">
                <div class="m-container m-container--fluid m-container--full-height m-page__container">
                    <div class="m-stack m-stack--flex-tablet-and-mobile m-stack--ver m-stack--desktop">
                        <div class="m-stack__item m-stack__item--left m-stack__item--middle m-stack__item--last" style="width: 300px;">
                            {!! __( 'message.DevelopedBy' , [ 'span_class' => 'm-footer__copyright' , 'a_class' => 'm-link' , 'href' => env('COMPANY_URL') , 'name' => env('COMPANY') , 'years' => ( env('START_YEAR') . ( date('Y') > env('START_YEAR') ? ( ' - ' . date('Y') ) : '' ) ) ] ) !!}
                        </div>
                        <div class="pull-right mt-2 mr-5 not-show">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="padding-left: 15px;">
                                            <div class="m-section__content m_datatable m-datatable m-datatable--default m-datatable--loaded table-responsive" style="margin-top: -25px !important;">
                                                <div class="m-datatable__pager m-datatable--paging-loaded clearfix" x-pagination="main"></div>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end::Footer -->
        </div>
        <!-- end:: Page -->

        <!-- begin::Scroll Top -->
        <div id="m_scroll_top" class="m-scroll-top not-show">
            <i class="la la-arrow-up"></i>
        </div>
        <!-- end::Scroll Top -->


        @include( 'includes' )


        @section('extra_js')

        @show


        @section('templates')

            <script type="text/template" x-image>
                <div class="col-sm-3 image-list" x-image-id="<%= rc.id %>">
                    <img src="<%= media(rc.path) %>">
                    <i class="fa fa-times" x-image-delete></i>
                </div>
            </script>

        @show
    </body>
</html>
