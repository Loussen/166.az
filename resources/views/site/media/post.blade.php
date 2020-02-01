@extends('site.index')

@section('title') {{ $post -> title }} @endsection

@section('og')
    <meta property="og:title" content="{{ $post -> title }}">
    <meta property="og:image" content="{{ media( 'post/' . $post -> og_image ) }}">
    <meta property="keywords" content="{{ $post -> seo_keywords }}">
    <meta property="description" content="{{ $post -> seo_description }}">
@endsection

@section('content')

    <main>
        <section class="page-header newsInner-header" style="background-image: url( {{ media( 'post/' . $post -> background ) }} ) !important;">
            <div class="page-header__over"></div>
            <div class="page-header__title">
                <h1 class="title">{{ $post -> title }}</h1>
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
            <a class="breadcrumb-item" href="{{ route('site.media.page') }}">{{ __('message.We_at_media') }}</a>
            <a class="breadcrumb-item active" href="javascript:void(0);">{{ $post -> title }}</a>
        </nav>
        <section class="newsAll">
            <div class="container newsAll-container">
                <div class="newsAll-left">
                    <img src="{{ media( 'post/' . $post -> photo ) }}" alt="">
                    <div class="news-inner__date">
                        <div class="news-inner__date--icon">
                            <i class="far fa-calendar-alt"></i>
                        </div>
                        <p>
                            {{ \App\Http\Controllers\Controller::_DATE($post -> date) }}
                        </p>
                    </div>
                    <h2>{{ $post -> title }}</h2>
                    <p>{!! $post -> text !!}</p>
                    <ul class="list-unstyled social-network">
                        <li class="social-network-fb">
                            <a target="_blank" onclick="window.open(this.href, 'share-facebook','left=50,top=50,width=600,height=320,toolbar=0'); return false;" href="https://www.facebook.com/sharer.php?u={{ url() -> current() }}">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        </li>
                        <li class="social-network-in">
                            <a target="_blank" onclick="window.open(this.href, 'share-linkedin','left=50,top=50,width=600,height=320,toolbar=0'); return false;" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<{{ url() -> current() }}&amp;title={{ $post -> title }}">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </li>
                        <li></li>
                        <li></li>
                    </ul>
                </div>
                <aside class="newsAll-right aside">
                    <h3>{{ __('message.Most_read') }}</h3>
                    @foreach( $popularPosts as $popularPost )
                        <a class="aside-item media-item" href="{{ route( 'site.media-post.page' , [ 'id' => $popularPost -> id ] ) }}">
                            <p class="media-item__date">{{ \App\Http\Controllers\Controller::_DATE($popularPost -> date) }}</p>
                            <h4 class="media-item__title">{{ $popularPost -> title }}</h4>
                            <p>{{ $popularPost -> headline }}</p>
                        </a>
                    @endforeach
                </aside>
            </div>
        </section>
        @include('site.subscribe')
    </main>

@endsection
