@extends('site.index')

@section('og')
    <meta property="og:title" content="{{ __('message.Autopark') }}">
    <meta property="og:image" content="{{ \App\Http\Controllers\Site\SiteController::BACKGROUND('autopark') }}">
    <meta property="keywords" content="{{ \App\Http\Controllers\Site\SiteController::HEADLINE('autopark_seo_keywords') }}">
    <meta property="description" content="{{ \App\Http\Controllers\Site\SiteController::HEADLINE('autopark_seo_description') }}">
@endsection

@section('content')

    <main>
        <section class="page-header autopark-header" style="background-image: url({{ \App\Http\Controllers\Site\SiteController::BACKGROUND('autopark') }})">
            <div class="page-header__over"></div>
            <div class="page-header__title">
                <h1 class="title">{{ __('message.Autopark') }}</h1>
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
            <a class="breadcrumb-item active" aria-current="page">{{ __('message.Autopark') }}</a>
        </nav>
        <section class="autopark">
            <div class="autopark_1">
                <div class="container">
                    <h2>{{ __('message.Autopark') }}</h2>
                    <p>{{ \App\Http\Controllers\Site\SiteController::HEADLINE('cars_headline') }}</p>
                    <ul class="mt-5" id="autoparkFilterButton">
                        <li class="active"><a href="javascript:" class="all">{{ __('message.All') }}</a></li>
                        @foreach( $types as $type )
                            <li><a href="javascript:" class="class_{{ $type['id'] }}">{{ $type['name'] }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div class="autopark-body">
                    <div id="autopark-body" class="container">
                        @foreach( $types as $type )
                            @foreach( $type['cars'] as $car )
                                <a class="item class_{{ $type['id'] }}" href="{{ route( 'site.car.page' , [ 'id' => $car -> id ] ) }}">
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
                                            <img src="{{ media( 'car/' . $car -> photo  ) }}" alt="">
                                        </div>
                                        <img src="{{ media( 'car/' . $car -> palet_photo  ) }}" class="area-small">
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
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection

@section('extra_js')

    @if( $id )
        <script>
            $( document ).ready( function()
            {
                $( '#autoparkFilterButton .class_{{ $id }}' ).click();
            } );
        </script>
    @endif

@endsection
