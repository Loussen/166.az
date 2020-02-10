@extends('site.index')

@section('og')
    <meta property="og:title" content="166">
    <meta property="og:image" content="{{ media( 'site/' . $site -> og_image ) }}">
    <meta property="keywords" content="{{ $site -> seo_keywords }}">
    <meta property="description" content="{{ $site -> seo_description }}">
@endsection

@section('content')

    <main>
        <section class="page-header about-header" style="background-image: url({{ media( 'site/' . $site -> background ) }})">
            <div class="page-header__over"></div>
            <div class="page-header__title">
                <h1 class="title">{{ __('message.Payment') }}</h1>
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
            <a class="breadcrumb-item" href="javascript:void(0);">{{ __('message.Order_Service') }}</a>
            <a class="breadcrumb-item active" aria-current="page">{{ __('message.Payment') }}</a>
        </nav>
        <section class="about">
            <div class="container">
                <div class="about-section">
                    <div class="about-section__left">
                        <h2>{{ __('message.Payment') }}</h2>
                        <div class="alert alert-success">Ödənişiniz uğurla həyata keçirildi. Sizinlə tezliklə əlaqə saxlayacayıq</div>
                    </div>
                </div>
            </div>
        </section>
        @include('site.subscribe')
    </main>

@endsection
