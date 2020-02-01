@php
    $locale = app() -> getLocale();

    $currentRoute = Route::currentRouteName();

    $languages = \App\Http\Middleware\Language::LANGUAGES();

    $user = Auth::user();

    $services = \App\Http\Controllers\Site\ServiceController::ALL_SERVICES();

    $site = \App\Http\Controllers\Site\SiteController::SITE();
@endphp

    <!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">

        <title> @section('title') 166 @show </title>

        @section('og')
            <meta property="og:image" content="{{ asset('site/img/Logo.png') }}">
        @show

        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Template Basic Images Start -->
        <link rel="icon" type="image/png" href="{{ asset('site/img/Logo.png') }}">
        <link rel="manifest" href="/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('site/img/Logo.png') }}">
        <!-- Template Basic Images End -->

        <!-- Custom Browsers Color Start -->
        <meta name="theme-color" content="#000">
        <!-- Custom Browsers Color End -->

        <!-- Overall minimized Css -->
        <link rel="stylesheet" href="{{ asset('site/css/app.min.css') }}">
        <link rel="stylesheet" href="{{ asset('site/css/tempus-dominus.css') }}"/>

        <style>
            .hidden
            {
                display : none !important;
            }
        </style>

        <!-- Jquery Plugin -->
        <script src="{{ asset('admin/js/jquery-3.3.1.min.js') }}"></script>

        <!-- Carousel Plugin -->
        <script src="{{ asset('site/js/jquery.carouFredSel-6.0.4-packed.js') }}" type="text/javascript"></script>

        <!-- Fontawesome Library -->
        <script src="https://kit.fontawesome.com/17a7821e99.js"></script>

        <!-- Popper.js Library -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="https://api-maps.yandex.ru/2.1/?apikey=3f1a52d9-03c8-4183-acc6-536b8994866f&lang=en_US" type="text/javascript"></script>
        <script type="text/javascript">
            // The ymaps.ready() function will be called when
            // all the API components are loaded and the DOM tree is generated.
            ymaps.ready( init );

            function init()
            {
                // Creating the map.
                var myMap = new ymaps.Map( "mapBody" , {
                    // The map center coordinates.
                    // Default order: “latitude, longitude”.
                    // To not manually determine the map center coordinates,
                    // use the Coordinate detection tool.
                    center : [ 55.76 , 37.64 ] ,
                    // Zoom level. Acceptable values:
                    // from 0 (the entire world) to 19.
                    zoom : 7
                } );
            }
        </script>
    </head>
    <body id="body">
        <header class="header" id="header">
            <div class="header-primary">
                <div class="container header-container header-primary__container">
                    <div class="header-primary__left">
                        <div class="header-primary__tab tab-primary active" x-customer="individual">
                            <div class="header-primary__tab--before">
                                <div class="circle"></div>
                            </div>
                            <a>{{ __('message.Individual_Customers') }}</a>
                            <div class="header-primary__tab--after">
                                <div class="circle"></div>
                            </div>
                        </div>
                        <div class="header-primary__tab tab-secondary" x-customer="corporate">
                            <div class="header-primary__tab--before">
                                <div class="circle"></div>
                            </div>
                            <a>{{ __('message.Corporate_Clients') }}</a>
                            <div class="header-primary__tab--after">
                                <div class="circle"></div>
                            </div>
                        </div>
                    </div>
                    <div class="header-primary__right d-none d-lg-flex">
                        <a href="#" data-toggle="modal" data-target="#callBack">
                            <div>
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <span>{{ __('message.Call_back') }}</span>
                        </a>
                        <a href="#">
                            <div>
                                <i class="far fa-credit-card"></i>
                            </div>
                            <span>{{ __('message.Online_payment') }}</span>
                        </a>
                        <a href="#">
                            <div>
                                <i class="fas fa-user"></i>
                            </div>
                            <span>{{ __('message.Login') }}</span>
                        </a>
                    </div>

                    <div id="callback-success-modal" class="modal fade modal-service">
                        <div class="modal-dialog modal-service__dialog">
                            <div class="modal-content modal-service__content">
                                <div class="modal-header modal-service__header">
                                    <h6>{{ __('message.We_will_call_you') }}</h6>
                                </div>
                                <div class="modal-close" data-dismiss="modal">
                                    <i class="fa fa-times"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="callBack" class="modal fade modal-service">
                        <div class="modal-dialog modal-service__dialog">
                            <div class="modal-content modal-service__content">
                                <div class="modal-header modal-service__header">
                                    <h6>{{ __('message.Call_back') }}</h6>
                                </div>
                                <div class="modal-body modal-service__body">
                                    <form action="{{ route( 'callback' ) }}" class="form" method="post" x-edit-form x-target="afterCallback">
                                        <div class="form-row">
                                            <label for="">{{ __('message.Your_name') }}</label>
                                            <input type="text" name="name">
                                        </div>
                                        <div class="form-row">
                                            <div class="form-row--left form-element--short">
                                                <label for="">{{ __('message.Country_code') }}</label>
                                                <input readonly="readonly" type="text" id="country_code" value="AZE (+994)">
                                            </div>
                                            <div class="form-row--right form-element--long">
                                                <label for="">{{ __('message.Phone') }}</label>
                                                <input data-inputmask="'mask': '(99) 999-99-99'" id="mobile_number" name="phone" type="text" value="">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label for="">{{ __('message.Service') }}</label>
                                            <select name="service" id="">
                                                <option value="">{{ __('message.Service') }}</option>
                                                @foreach( $services as $service )
                                                    @if( count( $service -> children ) )
                                                        <optgroup label="{{ $service -> name }}">
                                                            @foreach( $service -> children as $childService )
                                                                <option value="{{ $childService -> id }}">{{ $childService -> name }}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-row">
                                            <label for="">{{ __('message.City') }}</label>
                                            <input name="city" type="text" placeholder="">
                                        </div>
                                        <div class="form-submit modal-service__submit">
                                            <button type="submit">
                                                <span>{{ __('message.Call_me') }}</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-close" data-dismiss="modal">
                                    <i class="fa fa-times"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="header-profile d-lg-none">
                        <div class="dropdown header-profile__dropdown">
                            <a href="#" data-toggle="dropdown">
                                <i class="fa fa-user"></i>
                            </a>
                            <div class="dropdown-menu header-profile__dropdown--menu">
                                <div class="header-primary__right">
                                    <a href="#" data-toggle="modal" data-target="#callBack">
                                        <div>
                                            <i class="fas fa-phone-alt"></i>
                                        </div>
                                        <span>{{ __('message.Call_back') }}</span>
                                    </a>
                                    <a href="#">
                                        <div>
                                            <i class="far fa-credit-card"></i>
                                        </div>
                                        <span>{{ __('message.Online_payment') }}</span>
                                    </a>
                                    <a href="#">
                                        <div>
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span>{{ __('message.Login') }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-secondary">
                <div class="container header-container header-secondary__container">
                    <div class="header-logo">
                        <a href="{{ route('site.home') }}">
                            <img src="{{ asset('site/img/Logo.png') }}" alt="">
                        </a>
                    </div>
                    <nav class="header-navbar d-none d-lg-flex">
                        <div class="header-dropdown header-navbar__dropdown">
                            <a class="header-navbar__item">{{ __('message.Services') }}</a>
                            <div class="header-dropdown__menu header-navbar__dropdown--menu header-maindropdown__menu">
                                <ul class="list-unstyled">
                                    @foreach( $services as $service )
                                        <li class="header-dropdown header-subdropdown">
                                            <i class="fa fa-chevron-right"></i>
                                            <a href="{{ route( 'site.service' , [ 'id' => $service -> id ] ) }}">{{ $service -> name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <a class="header-navbar__item"
                           href="{{ route('site.campaigns.page') }}">{{ __('message.Campaigns') }}</a>
                        <a class="header-navbar__item" href="{{ route('site.cars.page') }}">{{ __('message.Autopark') }}</a>
                        <div class="header-dropdown header-navbar__dropdown">
                            <a class="header-navbar__item">{{ __('message.Our_Company') }}</a>
                            <div class="header-dropdown__menu header-navbar__dropdown--menu header-maindropdown__menu">
                                <ul class="list-unstyled">
                                    <li>
                                        <i class="fa fa-chevron-right"></i>
                                        <a href="{{ route('site.about') }}">{{ __('message.About_us') }}</a>
                                    </li>
                                    <li>
                                        <i class="fa fa-chevron-right"></i>
                                        <a href="{{ route('site.team.page') }}">{{ __('message.Our_team') }}</a>
                                    </li>
                                    <li>
                                        <i class="fa fa-chevron-right"></i>
                                        <a href="{{ route('site.media.page') }}">{{ __('message.We_at_media') }}</a>
                                    </li>
                                    <li>
                                        <i class="fa fa-chevron-right"></i>
                                        <a href="{{ route('site.blog.page') }}">{{ __('message.Blog') }}</a>
                                    </li>
                                    <li>
                                        <i class="fa fa-chevron-right"></i>
                                        <a href="{{ route('site.faq.page') }}">{{ __('message.FAQ') }}</a>
                                    </li>
                                    <li>
                                        <i class="fa fa-chevron-right"></i>
                                        <a href="{{ route('site.gallery.page') }}">{{ __('message.Gallery') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <a class="header-navbar__item" href="{{ route('site.career.page') }}">{{ __('message.Career') }}</a>
                        <a class="header-navbar__item" href="{{ route('site.contact') }}">{{ __('message.Contact') }}</a>
                    </nav>
                    <form class="header-search" id="header-search" method="get" action="{{ route( 'site.services' ) }}">
                        <button type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                        <input name="search" type="text" placeholder="{{ __('message.Search') }}..." value="{{ urldecode( app('request') -> input('search') ) }}">
                    </form>
                    <div class="header-dropdown header-language__dropdown">
                        <div class="header-language">
                            <img src="{{ asset( 'site/img/' . $locale . '.svg' ) }}" alt="">
                            <a>{{ strtoupper( $locale ) }}</a>
                        </div>
                        <div class="header-dropdown__menu header-language__dropdown--menu">
                            <ul>
                                @foreach( $languages as $lang => $language )
                                    @if( $lang !== $locale )
                                        <li>
                                            <img width="10" src="{{ asset( 'site/img/' . $lang . '.svg' ) }}" alt="" style="margin-right: 5px;">
                                            <a href="{{ \App\Http\Controllers\Site\SiteController::ROUTE( $lang ) }}">{{ strtoupper( $lang ) }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="header-nav d-lg-none" onclick="openSecondaryMenu()">
                        <a href="#">
                            <i class="fa fa-bars"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="header-sidenav" id="headerSecondaryMenu">
                <a href="javascript:void(0)" class="header-sidenav__close" onclick="closeSecondaryMenu()">
                    <i class="fa fa-close"></i>
                </a>
                <ul class="list-unstyled">
                    <li>
                        <a class="header-sidenav__item" href="javascript:void(0)">
                            {{ __('message.Services') }}
                            <i class="fa fa-chevron-down"></i>
                        </a>
                        <ul>
                            @foreach( $services as $service )
                                <li>
                                    <a class="header-sidenav__subitem"
                                       href="{{ route( 'site.service' , [ 'id' => $service -> id ] ) }}">
                                        {{ $service -> name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li>
                        <a class="header-sidenav__item"
                           href="{{ route('site.campaigns.page') }}">{{ __('message.Campaigns') }}</a>
                    </li>
                    <li>
                        <a class="header-sidenav__item" href="{{ route('site.cars.page') }}">{{ __('message.Autopark') }}</a>
                    </li>
                    <li>
                        <a class="header-sidenav__item" href="javascript:void(0)">
                            {{ __('message.Our_Company') }}
                            <i class="fa fa-chevron-down"></i>
                        </a>
                        <ul>
                            <li>
                                <a class="header-sidenav__subitem"
                                   href="{{ route('site.about') }}">{{ __('message.About_us') }}</a>
                            </li>
                            <li>
                                <a class="header-sidenav__subitem"
                                   href="{{ route('site.team.page') }}">{{ __('message.Our_team') }}</a>
                            </li>
                            <li>
                                <a class="header-sidenav__subitem"
                                   href="{{ route('site.media.page') }}">{{ __('message.We_at_media') }}</a>
                            </li>
                            <li>
                                <a class="header-sidenav__subitem"
                                   href="{{ route('site.blog.page') }}">{{ __('message.Blog') }}</a>
                            </li>
                            <li>
                                <a class="header-sidenav__subitem"
                                   href="{{ route('site.faq.page') }}">{{ __('message.FAQ') }}</a>
                            </li>
                            <li>
                                <a class="header-sidenav__subitem"
                                   href="{{ route('site.gallery.page') }}">{{ __('message.Gallery') }}</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="header-sidenav__item" href="{{ route('site.career.page') }}">{{ __('message.Career') }}</a>
                    </li>
                    <li>
                        <a class="header-sidenav__item" href="{{ route('site.contact') }}">{{ __('message.Contact') }}</a>
                    </li>
                </ul>
            </div>
        </header>
        <div class="bot">
            <div class="bot-callback">
                <div class="bot-callback__inner">
                    <a href="#" class="bot-callback__icon" data-toggle="modal" data-target="#callBack">
                        <i class="fas fa-phone-alt"></i>
                    </a>
                </div>
            </div>
        </div> <!-- ========================= Section - Header ============================== -->

        @yield('content')

        <footer class="footer">
            <div class="footer-primary">
                <div class="container footer-primary__container">
                    <div class="footer-primary__left">
                        <div class="footer-primary__tab tab-primary active" x-customer="individual">
                            <a>{{ __('message.Individual_Customers') }}</a>
                        </div>
                        <div class="footer-primary__tab tab-secondary" x-customer="corporate">
                            <a>{{ __('message.Corporate_Clients') }}</a>
                        </div>
                    </div>
                    <div class="footer-primary__right d-none d-lg-flex">
                        <a href="#" data-toggle="modal" data-target="#callBack">
                            <div>
                                <i class="fa fa-phone-alt"></i>
                            </div>
                            <span>{{ __('message.Call_back') }}</span>
                        </a>
                        <a href="#">
                            <div>
                                <i class="far fa-credit-card"></i>
                            </div>
                            <span>{{ __('message.Online_payment') }}</span>
                        </a>
                        <a href="#">
                            <div>
                                <i class="fas fa-user"></i>
                            </div>
                            <span>{{ __('message.Login') }}</span>
                        </a>
                    </div>
                    <div class="footer-profile d-lg-none">
                        <div class="dropdown footer-profile__dropdown">
                            <a href="#" data-toggle="dropdown">
                                <i class="fa fa-user"></i>
                            </a>
                            <div class="dropdown-menu footer-profile__dropdown--menu">
                                <div class="footer-primary__right">
                                    <a href="#" data-toggle="modal" data-target="#callBack">
                                        <div>
                                            <i class="fas fa-phone-alt"></i>
                                        </div>
                                        <span>{{ __('message.Call_back') }}</span>
                                    </a>
                                    <a href="#">
                                        <div>
                                            <i class="far fa-credit-card"></i>
                                        </div>
                                        <span>{{ __('message.Online_payment') }}</span>
                                    </a>
                                    <a href="#">
                                        <div>
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span>{{ __('message.Login') }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!---Footer Primary-->
            <div class="footer-secondary">
                <div class="container footer-secondary__container">
                    <div class="footer-secondary__left">
                        <h6>{{ __('message.CONTACT') }}</h6>
                        <div class="footer-contact">
                            <p>{{ __('message.Address') }}: {{ $site -> address }}</p>
                            <p>{{ __('message.Phone') }}: {{ $site -> mobile }}</p>
                            <p>{{ __('message.Email') }}: {{ $site -> email }}</p>
                            <ul class="list-unstyled social-network">
                                @if( $site -> facebook )
                                    <li class="social-network-fb">
                                        <a target="_blank" href="{{ $site -> facebook }}">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                    </li>
                                @endif
                                @if( $site -> linkedin )
                                    <li class="social-network-in">
                                        <a target="_blank" href="{{ $site -> linkedin }}">
                                            <i class="fab fa-linkedin-in"></i>
                                        </a>
                                    </li>
                                @endif
                                @if( $site -> instagram )
                                    <li class="social-network-ins">
                                        <a target="_blank" href="{{ $site -> instagram }}">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                    </li>
                                @endif
                                @if( $site -> youtube )
                                    <li class="social-network-ytb">
                                        <a target="_blank" href="{{ $site -> youtube }}">
                                            <i class="fab fa-youtube"></i>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="footer-secondary__center">
                        <h6>{{ __('message.SERVICES') }}</h6>
                        <ul class="list-unstyled">
                            @foreach( $services as $service )
                                <li>
                                    <a href="{{ route( 'site.service' , [ 'id' => $service -> id ] ) }}">
                                        {{ $service -> name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="footer-secondary__center">
                        <h6>{{ __('message.USEFUL_LINKS') }}</h6>
                        <ul class="list-unstyled">
                            <li>
                                <a href="{{ route('site.about') }}">{{ __('message.About_us') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('site.team.page') }}">{{ __('message.Our_team') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('site.media.page') }}">{{ __('message.We_at_media') }}</a>
                            </li>
                            <li>
                                <a href="#">{{ __('message.Blog') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('site.faq.page') }}">{{ __('message.FAQ') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('site.gallery.page') }}">{{ __('message.Gallery') }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="footer-secondary__right">
                        <h6>{{ __('message.BLOG') }}</h6>
                        <div class="footer-blog">
                            @foreach( \App\Http\Controllers\Site\PostController::LAST_BLOGS() as $post )
                                <a class="footer-blog__item"
                                   href="{{ route( 'site.blog-post.page' , [ 'id' => $post -> id ] ) }}">
                                    <div class="footer-blog__item--left">
                                        <img src="{{ \App\Http\Controllers\Controller::_AVATAR( $post -> photo ) }}" alt="">
                                    </div>
                                    <div class="footer-blog__item--right">
                                        <div class="footer-blog__item--date">
                                            <div class="day">{{ date ('d' , strtotime($post -> date) ) }}</div>
                                            <div
                                                class="month">{{ \App\Http\Controllers\Controller::_DATE( $post -> date , true , true ) }}</div>
                                        </div>
                                        <p>{{ $post -> title }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div> <!---Footer Secondary-->
            <div class="footer-tertiary">
                <p>
                    <span>© 2019 | 166.az</span><strong>&nbsp;|&nbsp;</strong><span>{{ __('message.All_rights_reserved') }}</span><strong>&nbsp;|&nbsp;</strong>
                    <span>Design by:&nbsp;</span><a href="https://crocusoft.com/" target="_blank">CROCUSOFT</a>
                </p>
            </div> <!---Footer Tertiary-->
        </footer> <!-- ========================= Section - Footer ============================== -->

        <script src="{{ asset('site/js/libs.min.js') }}"></script>
        <script src="{{ asset('site/js/tempus-dominus.js') }}"></script>
        <script src="{{ asset('site/js/locale-en-gb.js') }}"></script>
        <script src="{{ asset('site/js/locale-az.js') }}"></script>
        <script src="{{ asset('site/js/locale-ru.js') }}"></script>
        <script src="{{ asset('site/js/common.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>

               <!-- BEGIN JIVOSITE CODE {literal} -->
        <script type='text/javascript'>
            ( function()
            {
                var widget_id = 'NKYZyC3L4N';
                var d = document;
                var w = window;

                function l()
                {
                    var s = document.createElement( 'script' );
                    s.type = 'text/javascript';
                    s.async = true;
                    s.src = '//code.jivosite.com/script/widget/' + widget_id
                    ;var ss = document.getElementsByTagName( 'script' )[ 0 ];
                    ss.parentNode.insertBefore( s , ss );
                }

                if( d.readyState == 'complete' )
                {
                    l();
                } else
                {
                    if( w.attachEvent )
                    {
                        w.attachEvent( 'onload' , l );
                    } else
                    {
                        w.addEventListener( 'load' , l , false );
                    }
                }
            } )();

            /*function rere($number) {

            }*/
        </script>
               <!-- {/literal} END JIVOSITE CODE -->


        <script>
            function afterCallback()
            {
                $( "#callBack" ).modal( 'hide' );
                $( "#callback-success-modal" ).modal( 'show' );
            }

            $( "#mobile_number" ).inputmask();
            $( "#time" ).inputmask();
        </script>

        @include( 'includes' )

        @include( 'site.order-front' )

        @section('extra_js')

        @show


        @section('templates')

        @show
    </body>
</html>
