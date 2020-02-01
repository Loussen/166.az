@extends('site.index')

@section('og')
    <meta property="og:title" content="166">
    <meta property="og:image" content="{{ media( 'site/' . $site -> og_image ) }}">
    <meta property="keywords" content="{{ $site -> seo_keywords }}">
    <meta property="description" content="{{ $site -> seo_description }}">
@endsection

@section('content')

    <main>
        <section class="page-header about-header" style="background-image: url({{ media( 'site/' . $site -> background ) }})">
            <div class="page-header__over"></div>
            <div class="page-header__title">
                <h1 class="title">{{ __('message.About_us') }}</h1>
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
            <a class="breadcrumb-item active" aria-current="page">{{ __('message.About_us') }}</a>
        </nav>
        <section class="about">
            <div class="container">
                <div class="about-section">
                    <div class="about-section__left">

                        <h2>{{ __('message.About_us') }}</h2>
                        <img src="{{ media( 'site/' . $site -> background ) }}" alt="">
                        <h3>{{ $site -> title }}</h3>
                        {!! $site -> text !!}
                        <a class="btn" href="{{ route('site.contact') }}">{{ __('message.CONTACT_US') }}</a>
                    </div>
                    <div class="about-section__right">
                        <div>
                            <h4>{{ __('message.Useful_links') }}</h4>
                            <a class="btn" href="{{ route('site.services') }}">
                                {{ __('message.Our_Services') }}
                                <i class="fas fa-angle-right"></i>
                            </a>
                            <a class="btn" href="{{ route('site.campaigns.page') }}">
                                {{ __('message.Campaigns') }}
                                <i class="fas fa-angle-right"></i>
                            </a>
                            <a class="btn" href="{{ route('site.media.page') }}">
                                {{ __('message.We_at_media') }}
                                <i class="fas fa-angle-right"></i>
                            </a>
                            <a class="btn" href="{{ route('site.faq.page') }}">
                                {{ __('message.Faq') }}
                                <i class="fas fa-angle-right"></i>
                            </a>
                            <a class="btn" href="{{ route('site.team.page') }}">
                                {{ __('message.Our_team') }}
                                <i class="fas fa-angle-right"></i>
                            </a>
                        </div>
                        <div>
                            <h4>{{ __('message.Blog') }}</h4>
                            @foreach( $posts as $post )
                                <div class="about-blog">
                                    <div>
                                        <img src="{{ \App\Http\Controllers\Controller::_AVATAR( $post -> photo , 'post'  ) }}" alt="">
                                    </div>
                                    <div>
                                        <div class="about-blog__date">
                                            <div class="number">{{ date ('d' , strtotime($post -> date) ) }}</div>
                                            <div class="month">{{ \App\Http\Controllers\Controller::_DATE( $post -> date , true ) }}</div>
                                        </div>
                                        <p>{{ $post -> title }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>


            <div class="about-point" id="about">
                <div class="about-point__cover"></div>
                <div class="flex-row about-point__body">
                    <div class="container">
                        <div>
                            <p>{{ __('message.MORE_THAN') }}</p>
                            <h5 class="count">{{ $site -> transported_objects }}</h5>
                            <span>{{ __('message.Transported_offices_and_homes') }}</span>
                        </div>
                        <div>
                            <p>{{ __('message.MORE_THAN') }}</p>
                            <h5 class="count">{{ $site -> cleaned_places }}</h5>
                            <span>{{ __('message.Cleaned_places') }}</span>
                        </div>
                        <div>
                            <p>{{ __('message.MORE_THAN') }}</p>
                            <h5 class="count">{{ $site -> customer_reviews }}</h5>
                            <span>{{ __('message.Customer_reviews') }}</span>
                        </div>
                        <div>
                            <p>{{ __('message.MORE_THAN') }}</p>
                            <h5 class="count">{{ $site -> satisfied_customers }}</h5>
                            <span>{{ __('message.Satisfied_customers') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="about-mission">
                    <div class="about-mission__left">
                        @foreach( $missions as $mission )
                            <div>
                                <img src="{{ media( 'site/' . $mission -> photo ) }}" alt="">
                                <p>{{ $mission -> title }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="about-mission__right">
                        <h6>{{ __('message.Our_mission') }}</h6>
                        {!! $site -> mission !!}
                    </div>
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
        @include('site.subscribe')
    </main>

@endsection
