@extends('site.index')

@section('og')
    <meta property="og:title" content="{{ __('message.Services') }}">
    <meta property="og:image" content="{{ \App\Http\Controllers\Site\SiteController::BACKGROUND('service') }}">
    <meta property="keywords" content="{{ \App\Http\Controllers\Site\SiteController::HEADLINE('service_seo_keywords') }}">
    <meta property="description" content="{{ \App\Http\Controllers\Site\SiteController::HEADLINE('service_seo_description') }}">
@endsection

@section('content')

    <main class="services">
        <section class="page-header services-header" style="background-image: url({{ \App\Http\Controllers\Site\SiteController::BACKGROUND('service') }})">
            <div class="page-header__over"></div>
            <div class="page-header__title">
                <h1 class="title">{{ __('message.Services') }}</h1>
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
            <a class="breadcrumb-item active" aria-current="page">{{ __('message.Services') }}</a>
        </nav>
        <section class="page-description">
            <div class="container">
                <h2>{{ __('message.Our_Services') }}</h2>
                <p>{{ \App\Http\Controllers\Site\SiteController::HEADLINE('services_headline') }}</p>
            </div>
        </section>
        <section class="page-services">
            <div class="container">
                <div class="page-services__body">
                    @if( count( $services ) )
                        @foreach( $services as $service )
                            <div class="service-item">
                                <a href="{{ route( 'site.service' , [ 'id' => $service -> id ] ) }}">
                                    <img src="{{ \App\Http\Controllers\Controller::_AVATAR( $service -> photo , 'service' ) }}" alt="">
                                    <div class="service-image--hover">
                                        <img src="{{ asset('site/img/share.png') }}" alt="">
                                    </div>
                                    <div class="service-description">
                                        <h3>{{ $service -> name }}</h3>
                                        <p x-customer-show x-individual>{{ $service -> individual_headline }}</p>
                                        <p x-customer-show x-corporate corporate>{{ $service -> corporate_headline }}</p>
                                    </div>
                                    <div class="more-dots">
                                        <div class="more-dots--item"></div>
                                        <div class="more-dots--item"></div>
                                        <div class="more-dots--item"></div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <h5>{{ __('message.No_result_found') }}</h5>
                    @endif
                </div>
            </div>
        </section>
        <section class="main-comments">
            <h6 class="title">{{ __('message.Customer_reviews') }}</h6>
            <div class="title-after">
                <div class="title-after--item"></div>
                <div class="title-after--item"></div>
                <div class="title-after--item"></div>
                <div class="title-after--item"></div>
                <div class="title-after--item"></div>
            </div>
            <div class="container">
                <div class="comments-header">
                    <div class="comments-header__left">
                        <div class="comments-header__tab tab-primary active">
                            <a>{{ __('message.Individual_Customers') }}</a>
                        </div>
                        <div class="comments-header__tab tab-secondary">
                            <a>{{ __('message.Corporate_Clients') }}</a>
                        </div>
                    </div>
                    <div class="comments-header__right">
                        <a href="{{ route('site.contact') }}">{{ __('message.Send_review') }}</a>
                    </div>
                </div>
                <hr>
            </div>
            <div class="container carousel slide" id="commentsCarousel" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="comments-body carousel-item active">
                        <div class="comments-body__item">
                            <div class="comment-header">
                                <img src="img/com-1.png" alt="">
                                <div class="comment-header__description">
                                    <h6>Arif Məmmədov</h6>
                                    <p>12 April 2019</p>
                                </div>
                                <div class="comment-header__rating">
                                    <img src="{{ asset('site/img/star.png') }}" alt="">
                                    <img src="{{ asset('site/img/star.png') }}" alt="">
                                    <img src="{{ asset('site/img/star.png') }}" alt="">
                                    <img src="{{ asset('site/img/star.png') }}" alt="">
                                    <img src="{{ asset('site/img/star.png') }}" alt="">
                                </div>
                            </div>
                            <div class="comment-body">
                                <p>Yükdaşıma xidmətindən dəfələrlə istifadə etmişəm, çox razıyam. Bu yaxınlarda da 6 manatlıq maşınlardan istifadə etdim. Çox sərfəlidir. İşçilər də çox mədənidirlər.</p>
                            </div>
                        </div>
                        <div class="comments-body__item">
                            <div class="comment-header">
                                <img src="img/com-1.png" alt="">
                                <div class="comment-header__description">
                                    <h6>Cəlalə Rzayeva</h6>
                                    <p>12 April 2019</p>
                                </div>
                                <div class="comment-header__rating">
                                    <img src="{{ asset('site/img/star.png') }}" alt="">
                                    <img src="{{ asset('site/img/star.png') }}" alt="">
                                    <img src="{{ asset('site/img/star.png') }}" alt="">
                                    <img src="{{ asset('site/img/star.png') }}" alt="">
                                    <img src="{{ asset('site/img/star.png') }}" alt="">
                                </div>
                            </div>
                            <div class="comment-body">
                                <p>Təmizlik xidmətindən 2-ci dəfədir istifadə edirəm. Hər dəfə də çox razı qalmışam. Axırıncı dəfə divanlarımı da təmizlədilər. Xalça yuma xidmətindən də qohumum istifadə edib çox razıdır.</p>
                            </div>
                        </div>
                    </div>
                    <div class="comments-body carousel-item">
                        <div class="comments-body__item">
                            <div class="comment-header">
                                <img src="img/com-1.png" alt="">
                                <div class="comment-header__description">
                                    <h6>Kamal Əhmədov</h6>
                                    <p>12 April 2019</p>
                                </div>
                                <div class="comment-header__rating">
                                    <img src="{{ asset('site/img/star.png') }}" alt="">
                                    <img src="{{ asset('site/img/star.png') }}" alt="">
                                    <img src="{{ asset('site/img/star.png') }}" alt="">
                                    <img src="{{ asset('site/img/star.png') }}" alt="">
                                    <img src="{{ asset('site/img/star.png') }}" alt="">
                                </div>
                            </div>
                            <div class="comment-body">
                                <p>Özüm sahibkaram. 3 aydır anbarınızda yüklərinizi qoymuşam. Şükür allaha hər şey yaxşıdır. Necə qoymuşam elə də qalıb. İşlərinizində uğurlar diləyirəm.</p>
                            </div>
                        </div>
                        <div class="comments-body__item">
                            <div class="comment-header">
                                <img src="img/com-1.png" alt="">
                                <div class="comment-header__description">
                                    <h6>Mahirə Həsənova</h6>
                                    <p>12 April 2019</p>
                                </div>
                                <div class="comment-header__rating">
                                    <img src="{{ asset('site/img/star.png') }}" alt="">
                                    <img src="{{ asset('site/img/star.png') }}" alt="">
                                    <img src="{{ asset('site/img/star.png') }}" alt="">
                                    <img src="{{ asset('site/img/star.png') }}" alt="">
                                    <img src="{{ asset('site/img/star.png') }}" alt="">
                                </div>
                            </div>
                            <div class="comment-body">
                                <p>Kombimiz xarab idi. 2 həftə əvvəl ustanız təmir etdi. Evimizin istiliyi bərpa olundu. Çox sağolun.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="comments-footer">
                    <div class="comments-footer--left">
                        <div class="comments-controller comments-controller-prev" href="#commentsCarousel" data-slide="prev">
                            <i class="fa fa-long-arrow-left"></i>
                        </div>
                        <div class="comments-controller comments-controller-next enabled" href="#commentsCarousel" data-slide="next">
                            <i class="fa fa-long-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection
