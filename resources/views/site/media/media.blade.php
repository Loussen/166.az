@extends('site.index')

@section('content')

    <main>
        <section class="page-header newsAll-header" style="background-image: url({{ \App\Http\Controllers\Site\SiteController::BACKGROUND('media') }})">
            <div class="page-header__over"></div>
            <div class="page-header__title">
                <h1 class="title">{{ __('message.We_at_media') }}</h1>
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
            <a class="breadcrumb-item active" aria-current="page">{{ __('message.We_at_media') }}</a>
        </nav>
        <section class="newsAll">
            <div class="container newsAll-container">
                <div class="newsAll-left">
                    <div class="newsAll-left__top">
                        @if( $post )
                            <a href="{{ route( 'site.media-post.page' , [ 'id' => $post -> id ] ) }}" class="news-item__large media-item">
                                <div class="news-item__large--left">
                                    <img src="{{ \App\Http\Controllers\Controller::_AVATAR( $post -> photo , 'post'  ) }}" alt="">
                                    @if( $post -> is_new )
                                        <div class="news-item__label">
                                            <p>{{ __('message.NEW') }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="news-item__large--right">
                                    <div class="news-item__large--content">
                                        <h2 class="media-item__title">{{ $post -> title }}</h2>
                                        <p class="news-item__large--description">{{ $post -> headline }}</p>
                                    </div>
                                    <p class="media-item__date">{{ \App\Http\Controllers\Controller::_DATE($post -> date) }}</p>
                                </div>
                            </a>
                        @endif
                    </div>
                    <form action="{{ route( 'site.media-post.list' ) }}" method="post" x-list-form="main">
                        <div class="newsAll-left__bottom" x-list-tbody></div>
                        <div class="blog-content__footer">
                            <a href="javascript:" class="button-all" x-more><span>{{ __('message.MORE') }}</span></a>
                        </div>
                    </form>
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

@section('templates')

    <script type="text/template" x-tr>
        <a class="news-item media-item" href="{{ route('site.media-post.page') }}/<%= rc.id %>">
            <% if( rc.is_new ) { %>
            <div class="news-item__label">
                <p>{{ __('message.NEW') }}</p>
            </div>
            <% } %>
            <img src="<%= media(rc.photo) %>" alt="">
            <p class="media-item__date"><%= rc.date %></p>
            <h5 class="media-item__title news-item__title"><%= rc.title %></h5>
        </a>
    </script>

@endsection
