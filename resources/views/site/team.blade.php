@extends('site.index')

@section('content')

    <main>
        <section class="page-header team-header" style="background-image: url({{ \App\Http\Controllers\Site\SiteController::BACKGROUND('team') }})">
            <div class="page-header__over"></div>
            <div class="page-header__title">
                <h1 class="title">{{ __('message.Our_team') }}</h1>
                <div class="title-after">
                    <div class="title-after--item"></div>
                    <div class="title-after--item"></div>
                    <div class="title-after--item"></div>
                    <div class="title-after--item"></div>
                    <div class="title-after--item"></div>
                </div>
            </div>
        </section>
        <nav class="breadcrumb container mb-0 py-0">
            <a class="breadcrumb-item" href="{{ route('site.home') }}">{{ __('message.Homepage') }}</a>
            <a class="breadcrumb-item" href="javascript:void(0);">{{ __('message.Our_Company') }}</a>
            <a class="breadcrumb-item active" aria-current="page">{{ __('message.Our_team') }}</a>
        </nav>
        <section class="page-description">
            <div class="container">
                <h2>{{ __('message.Meet_out_team') }}</h2>
                {!! \App\Http\Controllers\Site\SiteController::HEADLINE('team_text') !!}
            </div>
        </section>
        <section class="team-body">
            <div class="container">
                <div class="team-body__container">
                    @foreach( $employees as $employee )
                        <div class="team-body__item">
                            <div class="team-body__item--header">
                                <img src="{{ \App\Http\Controllers\Controller::_AVATAR( $employee -> photo , 'employee'  ) }}">
                                <div class="team-body__item--over">
                                    <ul class="list-unstyled social-network">
                                        @if( $employee -> facebook )
                                            <li class="social-network-fb">
                                                <a target="_blank" href="{{ $employee -> facebook }}">
                                                    <i class="fab fa-facebook-f"></i>
                                                </a>
                                            </li>
                                        @endif
                                        @if( $employee -> linkedin )
                                            <li class="social-network-in">
                                                <a target="_blank" href="{{ $employee -> linkedin }}">
                                                    <i class="fab fa-linkedin-in"></i>
                                                </a>
                                            </li>
                                        @endif
                                        @if( $employee -> instagram )
                                            <li class="social-network-ins">
                                                <a target="_blank" href="{{ $employee -> instagram }}">
                                                    <i class="fab fa-instagram"></i>
                                                </a>
                                            </li>
                                        @endif
                                        @if( $employee -> twitter )
                                            <li class="social-network-twt">
                                                <a target="_blank" href="{{ $employee -> twitter }}">
                                                    <i class="fab fa-twitter"></i>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                    @if( $employee -> mobile )
                                        <p>
                                            <i class="fa fa-phone-alt"></i>
                                            {{ $employee -> mobile }}
                                        </p>
                                    @endif
                                    @if( $employee -> email )
                                        <p>
                                            <i class="fa fa-envelope"></i>
                                            {{ $employee -> email }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <h3>{{ $employee -> name }}</h3>
                            <p>{{ $employee -> position }}</p>
                            <div class="more-service ml-4">
                                <div class="more-service--item"></div>
                                <div class="more-service--item"></div>
                                <div class="more-service--item"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <section class="how-work">
            <div class="container">
                <h4 class="title">{{ __('message.How_we_work') }}</h4>
                <div class="title-after">
                    <div class="title-after--item"></div>
                    <div class="title-after--item"></div>
                    <div class="title-after--item"></div>
                    <div class="title-after--item"></div>
                    <div class="title-after--item"></div>
                </div>
                <div class="how-work__body">
                    <img class="arrow arrow-top arrow-top__first" src="{{ asset('site/img/icon-first.png') }}" alt="">
                    <img class="arrow arrow-top arrow-top__second d-none d-xl-block" src="{{ asset('site/img/icon-first.png') }}" alt="">
                    <img class="arrow arrow-right arrow-right__first" src="{{ asset('site/img/icon-second.png') }}" alt="">
                    <img class="arrow arrow-right arrow-right__second d-xl-none" src="{{ asset('site/img/icon-second.png') }}" alt="">
                    <img class="arrow arrow-bottom arrow-bottom__first" src="{{ asset('site/img/icon-third.png') }}" alt="">
                    <img class="arrow arrow-bottom arrow-bottom__second d-none d-xl-block" src="{{ asset('site/img/icon-third.png') }}" alt="">
                    <img class="arrow arrow-left arrow-left__first d-xl-none" src="{{ asset('site/img/icon-third.png') }}" alt="">
                    <div class="how-work__body--item order-xl-1 order-md-1 order-1">
                        <div>
                            <img src="{{ asset('site/img/mobile.png') }}" alt="">
                        </div>
                        <h5>{{ __('message.HOW_WORK_1_title') }}</h5>
                        <p>{{ __('message.HOW_WORK_1_headline') }}</p>
                        <p class="mb-0"><span>{{ $site -> order_mobile }}</span> {{ __('message.or') }}</p>
                        <a href="#" data-toggle="modal" data-target="#callBack">{{ __('message.Call_back') }}</a>
                    </div>
                    <div class="how-work__body--item order-xl-2 order-md-2 order-2">
                        <div>
                            <img src="{{ asset('site/img/operation.png') }}" alt="">
                        </div>
                        <h5>{{ __('message.HOW_WORK_2_title') }}</h5>
                        <p>{{ __('message.HOW_WORK_2_headline') }}</p>
                    </div>
                    <div class="how-work__body--item order-xl-3 order-md-4 order-3">
                        <div>
                            <img src="{{ asset('site/img/document.png') }}" alt="">
                        </div>
                        <h5>{{ __('message.HOW_WORK_3_title') }}</h5>
                        <p>{{ __('message.HOW_WORK_3_headline') }}</p>
                    </div>
                    <div class="how-work__body--item order-xl-4 order-md-3 order-6">
                        <div>
                            <img src="{{ asset('site/img/raiting.png') }}" alt="">
                        </div>
                        <h5>{{ __('message.HOW_WORK_4_title') }}</h5>
                        <p>{{ __('message.HOW_WORK_4_headline') }}</p>
                    </div>
                    <div class="how-work__body--item order-xl-5 order-md-5 order-5">
                        <div>
                            <img src="{{ asset('site/img/payment.png') }}" alt="">
                        </div>
                        <h5>{{ __('message.HOW_WORK_5_title') }}</h5>
                        <p>{{ __('message.HOW_WORK_5_headline') }}</p>
                    </div>
                    <div class="how-work__body--item order-xl-6 order-md-6 order-4">
                        <div>
                            <img src="{{ asset('site/img/service.png') }}" alt="">
                        </div>
                        <h5>{{ __('message.HOW_WORK_6_title') }}</h5>
                        <p>{{ __('message.HOW_WORK_6_headline') }}</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="vacancies">
            <div class="container vacancies-container">
                <h6>{{ __('message.You_can_take_advantage_of_open_vacancies_to_join_our_team') }}</h6>
                @foreach( $vacancies as $vacancy )
                    <div class="vacancies-item">
                        <img src="{{ \App\Http\Controllers\Controller::_AVATAR( $vacancy -> photo , 'vacancy'  ) }}" alt="">
                        <div class="vacancies-item__text">
                            <h6>{{ $vacancy -> title }}</h6>
                            <p>{{ $vacancy -> text }}</p>
                        </div>
                        <a class="btn" href="{{ route( 'site.vacancy.page' , [ 'id' => $vacancy -> id ] ) }}">{{ __('message.READ_MORE') }}</a>
                    </div>
                @endforeach
            </div>
        </section>
    </main>

@endsection
