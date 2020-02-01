@extends('site.index')

@section('title') {{ $post -> title }} @endsection

@section('og')
    <meta property="og:title" content="{{ $post -> title }}">
    <meta property="og:image" content="{{ media( 'post/' . $post -> og_image ) }}">
    <meta property="keywords" content="{{ $post -> seo_keywords }}">
    <meta property="description" content="{{ $post -> seo_description }}">
@endsection

@section('content')

    <main class="main">
        <section class="page-header main-blog__header" style="background-image: url( {{ media( 'post/' . $post -> background ) }} ) !important;">
            <div class="page-header__over"></div>
            <div class="page-header__title">
                <h1 class="title">{{ $post -> service }}</h1>
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
            <a class="breadcrumb-item" href="{{ route('site.blog.page') }}">{{ __('message.Blog') }}</a>
            <a class="breadcrumb-item active" href="javascript:void(0);">{{ $post -> title }}</a>
        </nav>
        <section class="main-blog">
            <div class="container">
                <div class="main-blog__body">
                    <div class="main-blog__left">
                        <article class="main-blog__article">
                            <div class="main-blog__image">
                                <img src="{{ media( 'post/' . $post -> photo ) }}" alt="">
                            </div>
                            <div class="main-blog__date">
                                <img src="{{ asset('site/img/dateImg.png') }}" alt="">
                                <span>{{ \App\Http\Controllers\Controller::_DATE( $post -> date ) }}</span>
                            </div>
                            <div class="main-blog__about">
                                <h3>{{ $post -> title }}</h3>
                                {!! $post -> text !!}
                            </div>
                        </article>
                        <div class="main-social">
                            <a target="_blank" onclick="window.open(this.href, 'share-facebook','left=50,top=50,width=600,height=320,toolbar=0'); return false;" href="https://www.facebook.com/sharer.php?u={{ url() -> current() }}">
                                <img src="{{ asset('site/img/facebook-icon.png') }}" alt="facebook-icon">
                            </a>
                            <a target="_blank" onclick="window.open(this.href, 'share-linkedin','left=50,top=50,width=600,height=320,toolbar=0'); return false;" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<{{ url() -> current() }}&amp;title={{ $post -> title }}">
                                <img src="{{ asset('site/img/linkedin-icon.png') }}" alt="linkedin-icon">
                            </a>
                        </div>
                    </div>
                    <div class="main-blog__right">
                        @if( count( $similarPosts ) )
                            <h3 class="main-blog__right-head">{{ __('message.Similar_Blogs') }}</h3>
                            <div class="main-blog__right-other">
                                @foreach( $similarPosts as $similarPost )
                                    <article class="main-article">
                                        <div class="main-article__left">
                                            <img src="{{ \App\Http\Controllers\Controller::_AVATAR( $similarPost -> photo ) }}" alt="">
                                        </div>
                                        <div class="main-article__right">
                                            <div class="main-article__date">
                                                <span class="main-article__day">{{ date ('d' , strtotime($similarPost -> date) ) }}</span>
                                                <span class="main-article__month">{{ \App\Http\Controllers\Controller::_DATE( $similarPost -> date , true ) }}</span>
                                            </div>
                                            <div class="main-article__paragraph">
                                                <p>{{ $similarPost -> title }}</p>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @endif
                        <div class="main-blog__category">
                            <h3>{{ __('message.Blog_Categories') }}</h3>
                            <div class="main-blog__category-menu">
                                @foreach( $services as $service )
                                    <a href="{{ route('site.blog.page') }}/{{ $service -> id }}">{{ $service -> name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection
