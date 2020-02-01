@extends('site.index')

@section('content')

    <main class="main">
        <section class="main-header">
            <div class="container main-header__container">
                <div class="main-header__left order">
                    <div class="order-left">
                        <h1>{{ __('message.Order_Service') }}</h1>
                        <p>
                            <i class="fas fa-check-circle"></i>
                            <span>{{ __('message.Step_1') }}</span>
                        </p>
                        <div class="form-row">
                            <label for="service">{{ __('message.Service') }}</label>
                            <select class="select-service" name="service">
                                @foreach( $services as $service )
                                    <option value="{{ $service -> id }}">{{ $service -> name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <hr>
                        <div class="form-row">
                            <label for="name">{{ __('message.Your_name') }}</label>
                            <input type="text" name="name" placeholder="{{ __('message.Your_name') }}">
                        </div>
                        <div class="form-row">
                            <label for="phone">{{ __('message.Phone') }}</label>
                            <input type="text" name="phone" placeholder="{{ __('message.Phone') }}">
                        </div>
                        <div class="form-submit form-animated">
                            <button data-target="#newOrder" data-toggle="modal">
                                <span>{{ __('message.Continue') }}</span>
                                <div class="form-animated__over">
                                    <i class="fa fa-long-arrow-right"></i>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="main-header__right">
                    <div id="topCarousel" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            @foreach( $sliders as $k => $slider )
                                <li data-target="#topCarousel" data-slide-to="{{ $k }}" @if( !$k ) class="active" @endif></li>
                            @endforeach
                        </ol>
                        <div class="carousel-inner">
                            @foreach( $sliders as $k => $slider )
                                <div class="carousel-item @if( !$k ) active @endif">
                                    <a href="{{ $slider -> link }}">
                                        <img src="{{ media( 'slider/' . $slider -> photo )  }}" alt="{{ $slider -> link }}">
                                    </a>
                                    <div class="image-overlay"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="page-services">
            <h2 class="title">{{ __('message.Our_Services') }}</h2>
            <div></div>
            <div class="title-after">
                <div class="title-after--item"></div>
                <div class="title-after--item"></div>
                <div class="title-after--item"></div>
                <div class="title-after--item"></div>
                <div class="title-after--item"></div>
            </div>
            <div class="container">
                <div class="page-services__body">
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
                                    <p x-customer-show x-corporate class="hidden">{{ $service -> corporate_headline }}</p>
                                </div>
                                <div class="more-dots">
                                    <div class="more-dots--item"></div>
                                    <div class="more-dots--item"></div>
                                    <div class="more-dots--item"></div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="page-services__footer">
                    <a href="{{ route('site.services') }}">
                        <button class="button-all">{{ __('message.All_services') }}</button>
                    </a>
                </div>
            </div>
        </section>
        <section class="main-autopark">
            <h4 class="title">{{ __('message.All_Autopark') }}</h4>
            <div class="title-after">
                <div class="title-after--item"></div>
                <div class="title-after--item"></div>
                <div class="title-after--item"></div>
                <div class="title-after--item"></div>
                <div class="title-after--item"></div>
            </div>
            <div class="container wrapper" id="autoparkWrapper">
                <div class="main-autopark__body" id="autoparkCarousel">
                    @foreach( $cars as $car )
                        <div class="main-autopark__body--item">
                            <img src="{{ media( 'car/' . $car -> photo ) }}" alt="">
                            <h4>{{ $car -> title }}</h4>
                            <p>({{ $car -> length }} {{ __('message.meters') }} / {{ $car -> weight }} {{ __('message.tons') }})</p>
                            <div class="autopark-body__item--description">
                                <div>
                                    <p>
                                        <span>{{ __('message.Width') }}</span>
                                        <span>{{ $car -> width }} {{ __('message.meters') }}</span>
                                    </p>
                                </div>
                                <div>
                                    <p>
                                        <span>{{ __('message.Length') }}</span>
                                        <span>{{ $car -> length }} {{ __('message.meters') }}</span>
                                    </p>
                                </div>
                                <div>
                                    <p>
                                        <span>{{ __('message.Height') }}</span>
                                        <span>{{ $car -> height }} {{ __('message.meters') }}</span>
                                    </p>
                                </div>
                                <div>
                                    <p>
                                        <span>{{ __('message.Weight') }}</span>
                                        <span>{{ $car -> weight }} {{ __('message.tons') }}</span>
                                    </p>
                                </div>
                                <div>
                                    <p>
                                        <span>{{ __('message.Volume') }}</span>
                                        <span>{{ number_format( floatval( str_replace( ',' , '.' , $car -> length ) ) * floatval( str_replace( ',' , '.' , $car -> height ) ) * floatval( str_replace( ',' , '.' , $car -> width ) ) , 1 , '.' , ',' ) }} {{ __('message.m3') }}</span>
                                    </p>
                                </div>
                                <div>
                                    <p>
                                        <span>{{ __('message.Palets') }}</span>
                                        <span>{{ $car -> palet }} {{ __('message.pieces') }}</span>
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route( 'site.car.page' , [ 'id' => $car -> id ] ) }}">{{ __('message.ORDER') }}</a>
                        </div>
                    @endforeach
                </div>
                <a id="autoparkPrev" href="#" class="prev">
                    <i class="fa fa-chevron-left"></i>
                </a>
                <a id="autoparkNext" href="#" class="next">
                    <i class="fa fa-chevron-right"></i>
                </a>
            </div>
        </section>
        <section class="main-media">
            <h5 class="title">{{ __('message.We_at_media') }}</h5>
            <div class="title-after">
                <div class="title-after--item"></div>
                <div class="title-after--item"></div>
                <div class="title-after--item"></div>
                <div class="title-after--item"></div>
                <div class="title-after--item"></div>
            </div>
            <div class="container wrapper" id="mediaWrapper">
                <div class="main-media__body" id="mediaCarousel">
                    @foreach( $posts as $post )
                        <a class="news-item media-item" href="{{ route( 'site.media-post.page' , [ 'id' => $post -> id ] ) }}">
                            <img src="{{ \App\Http\Controllers\Controller::_AVATAR( $post -> photo , 'post'  ) }}" alt="">
                            <p class="media-item__date">{{ \App\Http\Controllers\Controller::_DATE($post -> date) }}</p>
                            <h6 class="media-item__title news-item__title">{{ $post -> title }}</h6>
                        </a>
                    @endforeach
                </div>
                <a id="mediaPrev" href="#" class="prev">
                    <i class="fa fa-chevron-left"></i>
                </a>
                <a id="mediaNext" href="#" class="next">
                    <i class="fa fa-chevron-right"></i>
                </a>
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
                        <div class="comments-controller comments-controller-prev enabled" href="#commentsCarousel" data-slide="prev">
                            <i class="fa fa-long-arrow-left"></i>
                        </div>
                        <div class="comments-controller comments-controller-next enabled" href="#commentsCarousel" data-slide="next">
                            <i class="fa fa-long-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @include('site.subscribe')

    </main> <!-- ========================= Section - Main ============================== -->

@endsection

@section('extra_js') @endsection
