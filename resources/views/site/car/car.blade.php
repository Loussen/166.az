@extends('site.index')

@section('title') {{ $car -> title }} @endsection

@section('og')
    <meta property="og:title" content="{{ $car -> title }}">
    <meta property="og:image" content="{{ media( 'car/' . $car -> og_image ) }}">
    <meta property="keywords" content="{{ $car -> seo_keywords }}">
    <meta property="description" content="{{ $car -> seo_description }}">
@endsection

@section('content')

    <main>
        <section class="page-header autopark-inner-header" style="background-image: url( {{ media( 'car/' . $car -> background ) }} ) !important;">
            <div class="page-header__over"></div>
            <div class="page-header__title">
                <h1 class="title">{{ $car -> title }}</h1>
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
            <a class="breadcrumb-item" href="{{ route('site.cars.page') }}">{{ __('message.Autopark') }}</a>
            <a class="breadcrumb-item active" aria-current="page">{{ $car -> title }}</a>
        </nav>
        <section class="autopark">
            <div class="autopark_2">
                <div class="container d-flex flex-wrap">
                    <div class="autopark-leftside">
                        <h2>{{ $car -> title }}</h2>
                        <div class="information">
                            <div>
                                <div class="image">
                                    <img src="{{ media( 'car/' . $car -> photo ) }}" alt="">
                                </div>
                            </div>
                            <img src="{{ media( 'car/' . $car -> palet_photo ) }}" class="area-small">
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
                        {!! $car -> text !!}
                        <a class="btn" href="{{ route('site.order.page') }}?service=1">{{ __('message.ORDER') }}</a>
                    </div>
                    <div class="autopark-rightside">
                        <div class="autopark-rightside__first">
                            <h4>{{ __('message.Auto_Categories') }}</h4>
                            <ul id="autoparkFilterButton">
                                <li>
                                    <a href="{{ route('site.cars.page') }}" class="all">{{ __('message.All') }}</a>
                                </li>
                                @foreach( $types as $type )
                                    <li @if( $type -> id == $car -> type_id ) class="active" @endif>
                                        <a href="{{ route( 'site.cars.page' , [ 'id' => $type -> id ] ) }}">{{ $type -> name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div>
                            <h4>{{ __('message.Auto_Blog') }}</h4>
                            @foreach( \App\Http\Controllers\Site\PostController::LAST_BLOGS() as $post )
                                <div class="about-blog">
                                    <div>
                                        <img src="{{ \App\Http\Controllers\Controller::_AVATAR( $post -> photo ) }}" alt="">
                                    </div>
                                    <div>
                                        <div class="about-blog__date">
                                            <div class="number">{{ date ('d' , strtotime($post -> date) ) }}</div>
                                            <div class="month">{{ \App\Http\Controllers\Controller::_DATE( $post -> date , true , true ) }}</div>
                                        </div>
                                        <p>{{ $post -> title }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="autopark-body ">
                <div id="autopark-body" class="container autopark-bottom">
                    <h2>{{ __('message.Useful_Cars') }}</h2>
                    @foreach( $similarCars as $car )
                        <a class="item" href="{{ route( 'site.car.page' , [ 'id' => $car -> id ] ) }}">
                            <div class="autopark-item__header">
                                <div>
                                    <h3>{{ $car -> title }}</h3>
                                </div>
                                <div>
                                    <p class="btn">{{ __('message.ORDER') }}</p>
                                </div>
                            </div>
                            <p>{{ $car -> headline }}</p>
                            <div class="autopark-item__info">
                                <div class="image">
                                    <img src="{{ media( 'car/' . $car -> photo ) }}">
                                </div>
                                <img src="{{ media( 'car/' . $car -> palet_photo ) }}" class="area-small">
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
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
        @include('site.subscribe')
    </main>

@endsection
