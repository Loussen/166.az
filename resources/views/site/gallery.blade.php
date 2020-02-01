@extends('site.index')

@section('content')

    <main>
        <section class="page-header gallery-header" style="background-image: url({{ \App\Http\Controllers\Site\SiteController::BACKGROUND('gallery') }})">
            <div class="page-header__over"></div>
            <div class="page-header__title">
                <h1 class="title">{{ __('message.Gallery') }}</h1>
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
            <a class="breadcrumb-item active" aria-current="page">{{ __('message.Gallery') }}</a>
        </nav>
        <section class="filter">
            <div class="container">
                <ul class="mt-5" id="galleryFilterButton">
                    <li class="active"><a href="javascript:" class="all">{{ __('message.All') }}</a></li>
                    @foreach( $services as $service )
                        <li><a href="javascript:" class="class_{{ $service['id'] }}">{{ $service['name'] }}</a></li>
                    @endforeach
                </ul>
            </div>
        </section>
        <section class="gallery-content">
            <div class="container">
                <div id="aniimated-thumbnials" class="gallery-content__body">
                    @foreach( $services as $service )
                        @foreach( $service['images'] as $image )
                            <a class="gallery-item media-item class_{{ $service['id'] }}" href="{{ $image }}">
                                <img src="{{ $image }}" alt="">
                            </a>
                        @endforeach
                    @endforeach
                </div>
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
                            <img src="{{ \App\Http\Controllers\Controller::_AVATAR( $post -> photo  ) }}" alt="">
                            <p class="media-item__date">{{ \App\Http\Controllers\Controller::_DATE($post -> date) }}</p>
                            <h6 class="media-item__title news-item__title">{{ $post -> headline }}</h6>
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
        @include('site.subscribe')
    </main>

@endsection
