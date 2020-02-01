<!DOCTYPE html>
<html lang="{{ app() -> getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('message.Project') }} | Admin</title>

        <link rel="shortcut icon" type="image/png" href="{{ asset('site/img/Logo.png') }}"/>

        <!--begin::Base Styles -->
        <link href="{{ asset('admin/css/vendors.bundle.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('admin/css/style.bundle.css') }}" rel="stylesheet" type="text/css"/>
        <!--end::Base Styles -->

        <!--begin::Custom Styles -->
        <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet" type="text/css"/>
        <!--end::Custom Styles -->

        <script src='https://www.google.com/recaptcha/api.js?hl={{ app() -> getLocale() }}'></script>
    </head>


    <body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

        <!-- begin:: Page -->
        <div class="m-grid m-grid--hor m-grid--root m-page">
            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-1" id="m_login" style="background-image: url({{ asset('site/img/keepface-loading.png') }}); overflow-y: hidden; height: 100%;">
                <div class="m-grid__item m-grid__item--fluid m-login__wrapper">
                    <div class="m-login__container">
                        <div class="m-login__logo">
                            <img style="width: 55%;" alt="{{ __('message.Project') }}" src="{{ asset('site/img/Logo.png') }}"/>
                        </div>
                        <div class="m-login__signin">
                            <div class="m-login__head">
                            </div>
                            <form class="m-login__form m-form" method="POST" action="{{ route('login') }}">
                                @csrf
                                @if( $errors -> has('username') || $errors -> has('password') )
                                    <div class="m-alert m-alert--outline alert alert-danger alert-dismissible animated fadeIn" role="alert">
                                        <span>{{ __('message.UsernameOrPasswordIsNotValid') }}</span>
                                    </div>
                                @endif
                                <div class="form-group m-form__group">
                                    <input class="form-control m-input" type="text" name="username" placeholder="{{ __('message.Username') }}" value="{{ old('username') }}" style="color: #333537;" autofocus>
                                </div>
                                <div class="form-group m-form__group">
                                    <input class="form-control m-input m-login__form-input--last" type="password" name="password" placeholder="{{ __('message.Password') }}" style="color: #333537;">
                                </div>
                                <div class="m-login__form-action">
                                    <button id="m_login_signin_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn m-login__btn--primary">
                                        {{ __('message.Login') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="m-login__account">
                            {!! __( 'message.DevelopedBy' , [ 'span_class' => 'm-login__account-msg' , 'a_class' => '' , 'href' => env('URL') , 'name' => env('COMPANY') , 'years' => ( env('START_YEAR') . ( date('Y') > env('START_YEAR') ? ( ' - ' . date('Y') ) : '' ) ) ] ) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end:: Page -->

        <!--jQuery -->
        <script src="{{ asset('admin/js/jquery-3.3.1.min.js') }}"></script>

        <script>
            $( document ).ready( function()
            {
                $( document ).on( 'submit' , 'form' , function( e )
                {
                    if( !$( '[name="g-recaptcha-response"]' ).val().length ) e.preventDefault();
                } );
            } );
        </script>
    </body>
</html>
