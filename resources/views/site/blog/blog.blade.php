@extends('site.index')

@section('content')

    <main class="blog">
        <section class="page-header blog-header" style="background-image: url({{ \App\Http\Controllers\Site\SiteController::BACKGROUND('blog') }})">
            <div class="page-header__over"></div>
            <div class="page-header__title">
                <h1 class="title">{{ __('message.Blog') }}</h1>
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
            <a class="breadcrumb-item active" aria-current="page">{{ __('message.Blog') }}</a>
        </nav>
        <form action="{{ route( 'site.blog-post.list' ) }}" method="post" x-list-form="main">
            <section class="filter">
                <div class="container">
                    <ul>
                        <li class="{{ request() -> route() -> parameter('service') ? '' : 'active' }} all" x-service="All">
                            <a href="#">{{ __('message.All') }}</a>
                        </li>
                        @foreach( $services as $service )
                            <li class="{{ request() -> route() -> parameter('service') == $service -> id ? 'active' : '' }}" x-service="{{ $service -> id }}">
                                <a href="#">{{ $service -> name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>
            <section class="blog-content">
                <div class="container">
                    <div class="blog-content__body" x-list-tbody></div>
                    <div class="blog-content__footer">
                        <a href="javascript:" class="button-all" x-more><span>{{ __('message.MORE') }}</span></a>
                    </div>
                </div>
            </section>
        </form>
        @include('site.subscribe')
    </main>

@endsection

@section('templates')

    <script type="text/template" x-tr>
        <a class="blog-item media-item first" href="{{ route('site.blog-post.page') }}/<%= rc.id %>">
            <img src="<%= media(rc.photo) %>" alt="">
            <p class="media-item__date"><%= rc.date %></p>
            <div class="blog-item__content">
                <h6 class="media-item__title"><%= rc.title %></h6>
                <p class="media-item__description"><%= rc.headline %></p>
            </div>
        </a>
    </script>

@endsection
