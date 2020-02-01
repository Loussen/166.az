@extends('site.index')

@section('title') {{ $service -> name }} @endsection

@section('og')
    <meta property="og:title" content="{{ $service -> name }}">
    <meta property="og:image" content="{{ media( 'service/' . $service -> og_image ) }}">
    <meta property="keywords" content="{{ $service -> seo_keywords }}">
    <meta property="description" content="{{ $service -> seo_description }}">
@endsection

@section('content')

    <main>
        <section class="page-header career-header" style="background-image: url( {{ media( 'service/' . $service -> background ) }} ) !important;">
            <div class="page-header__over"></div>
            <div class="page-header__title">
                <h1 class="title">{{ $service -> name }}</h1>
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
            <a class="breadcrumb-item" href="{{ route('site.services') }}">{{ __('message.Services') }}</a>
            <a class="breadcrumb-item active" aria-current="page">{{ $service -> name }}</a>
        </nav>
        <section class="yukdashima_1">
            <div class="container">
                <ul class="" id="yukdashimaFilterButton">
                    @foreach( $service -> children as $k => $childService )
                        <li @if( ! $k ) class="active" @endif>
                            <a href="#" class="class_{{ $childService -> id }}">{{ $childService -> name }}</a>
                        </li>
                    @endforeach
                </ul>
                <div id="yukdashima-body">
                    @if( count( $service -> children ) )
                        @foreach( $service -> children as $k => $childService )
                            <div class="item class_{{ $childService -> id }} @if( ! $k ) active @endif">
                                <h2>{{ $childService -> name }}</h2>
                                <p x-customer-show x-individual>{!! $childService -> individual_headline !!}</p>
                                <p x-customer-show x-corporate class="hidden">{!! $childService -> corporate_headline !!}</p>
                                <a class="btn" href="{{ route('site.order.page') }}?service={{ $service -> id }}">{{ __('message.ORDER') }}</a>
                                <div class="line"></div>
                                <h2>{{ $childService -> include_headline }}</h2>
                                <div class="loads row">
                                    @if( isset( $childService -> includes ) && is_array( $childService -> includes ) && count( $childService -> includes ) )
                                        @foreach( $childService -> includes as $include )
                                            <div class="loads-item">
                                                <img src="{{ media( 'service/' . $include -> photo ) }}" alt="">
                                                <img src="{{ asset('site/img/yellow-check.png') }}" alt="">
                                                <h3>{{ $include -> name }}</h3>
                                                <p x-customer-show x-individual>{{ $include -> individual_headline }}</p>
                                                <p x-customer-show x-corporate class="hidden">{{ $include -> corporate_headline }}</p>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div x-customer-show x-individual>{!! $service -> individual_description !!}</div>
                        <div x-customer-show x-corporate class="hidden">{!! $service -> corporate_description !!}</div>
                    @endif
                </div>
            </div>
        </section>
        @if( $cars )
            <section class="yukdashima-avtopark">
                <div class="container">
                    <h4>{{ __('message.All_Autopark') }}</h4>
                    <div class="main-title mb-0">
                        <div class="main-title--item"></div>
                        <div class="main-title--item"></div>
                        <div class="main-title--item"></div>
                        <div class="main-title--item"></div>
                        <div class="main-title--item"></div>
                    </div>
                    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach( $cars as $k => $car )
                                <div class="carousel-item @if( ! $k ) active @endif">
                                    <div class="item">
                                        <div class="autopark-item__header">
                                            <div>
                                                <h3>{{ $car -> title }}</h3>
                                            </div>
                                            <div>
                                                <a href="{{ route( 'site.car.page' , [ 'id' => $car -> id ] ) }}" class="btn">{{ __('message.ORDER') }}</a>
                                            </div>
                                        </div>
                                        <p>{{ $car -> headline }}</p>
                                        <div class="autopark-item__info">
                                            <div class="image">
                                                <img src="{{ media( 'car/' . $car -> photo  ) }}" alt="">
                                            </div>
                                            <div class="size">
                                                <div class="size-options2">
                                                    <div class="borderRight"></div>
                                                    <div class="size-options2__top">
                                                        <div class="top-center">
                                                            <p>{{ $car -> length }} {{ __('message.meters') }}</p>
                                                        </div>
                                                        <div class="top-right">
                                                            <p>{{ $car -> height }} {{ __('message.meters') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="size-options2__center">
                                                        <div class="leftside">
                                                            <p>{{ $car -> width }} {{ __('message.meters') }}</p>
                                                        </div>
                                                        <div class="center">
                                                            <div class="trapeze">
                                                                <div class="line1"></div>
                                                            </div>
                                                            <div class="horizontal">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="size-options2__bottom">
                                                        <div class="bottom-center"></div>
                                                    </div>
                                                    <p> {{ number_format( floatval( str_replace( ',' , '.' , $car -> length ) ) * floatval( str_replace( ',' , '.' , $car -> height ) ) * floatval( str_replace( ',' , '.' , $car -> width ) ) , 1 , '.' , ',' ) }} {{ __('message.m3') }} / {{ $car -> palet }} {{ __('message.palets') }}</p>
                                                </div>
                                            </div>
                                            <div class="table">
                                                <div class="table-body">
                                                    <div class="table-body__item">
                                                        <p>
                                                            <span>{{ __('message.Width') }}</span><span> {{ $car -> width }} {{ __('message.meters') }}</span>
                                                        </p>
                                                    </div>
                                                    <div class="table-body__item">
                                                        <p>
                                                            <span>{{ __('message.Length') }}</span><span> {{ $car -> length }} {{ __('message.meters') }}</span>
                                                        </p>
                                                    </div>
                                                    <div class="table-body__item">
                                                        <p>
                                                            <span>{{ __('message.Height') }}</span><span> {{ $car -> height }} {{ __('message.meters') }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="table-body">
                                                    <div class="table-body__item">
                                                        <p>
                                                            <span>{{ __('message.Weight') }}</span><span> {{ $car -> weight }} {{ __('message.tons') }}</span>
                                                        </p>
                                                    </div>
                                                    <div class="table-body__item">
                                                        <p>
                                                            <span>{{ __('message.Volume') }}</span><span> {{ number_format( floatval( str_replace( ',' , '.' , $car -> length ) ) * floatval( str_replace( ',' , '.' , $car -> height ) ) * floatval( str_replace( ',' , '.' , $car -> width ) ) , 1 , '.' , ',' ) }} {{ __('message.m3') }}</span>
                                                        </p>
                                                    </div>
                                                    <div class="table-body__item">
                                                        <p>
                                                            <span>{{ __('message.Palets') }}</span><span> {{ $car -> palet }} {{ __('message.pieces') }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                            <i class="fas fa-chevron-left fa-2x"></i>
                            <span class="sr-only">{{ __('message.Previous') }}</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                            <i class="fas fa-chevron-right fa-2x"></i>
                            <span class="sr-only">{{ __('message.Next') }}</span>
                        </a>
                    </div>
                    <div class="d-flex justify-content-center">
                        <a class="btn" href="{{ route('site.cars.page') }}">{{ __('message.GO_TO_AUTOPARK') }}</a>
                    </div>
                </div>
            </section>
        @endif
        @if( count( $service -> extra ) )
            <section class="main-choose">
                <h5 class="title">{{ $service -> extra_info_headline }}</h5>
                <div class="title-after">
                    <div class="title-after--item"></div>
                    <div class="title-after--item"></div>
                    <div class="title-after--item"></div>
                    <div class="title-after--item"></div>
                    <div class="title-after--item"></div>
                </div>
                <div class="container">
                    <div class="main-choose__body">
                        @foreach( $service -> extra as $i )
                            <div class="main-choose__item">
                                <div class="main-choose__logo">
                                    <img src="{{ media( 'service/' . $i -> photo ) }}" alt="">
                                </div>
                                <h3>{{ $i -> title }}</h3>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </main>

@endsection
