@extends('site.index')

@section('content')

    <main class="faq">
        <section class="page-header faq-header" style="background-image: url({{ \App\Http\Controllers\Site\SiteController::BACKGROUND('faq') }})">
            <div class="page-header__over"></div>
            <div class="page-header__title">
                <h1 class="title">{{ __('message.FAQ') }}</h1>
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
            <a class="breadcrumb-item active" aria-current="page">{{ __('message.FAQ') }}</a>
        </nav>
        <section class="page-description">
            <div class="container">
                <h2>{{ __('message.Faq') }}</h2>
                <p>{{ \App\Http\Controllers\Site\SiteController::HEADLINE('faq_headline') }}</p>
                <a href="{{ route('site.contact') }}">{{ __('message.HAVE_QUESTION') }}</a>
            </div>
        </section>
        <section class="filter">
            <div class="container">
                <ul id="faqFilterButton">
                    <li class="active all">
                        <a href="javascript:">{{ __('message.All') }}</a>
                    </li>
                    @foreach( $services as $service )
                        <li class="class_{{ $service['id'] }}">
                            <a href="javascript:">{{ $service['name'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
        <section class="faq-body">
            <div class="container faq-body__container" id="faq-body">
                @foreach( $services as $service )
                    @foreach( $service['questions'] as $question )
                        @if(
                                ( strlen( $question -> individual_question ) && strlen( $question -> individual_answer ) ) ||
                                ( strlen( $question -> corporate_question ) && strlen( $question -> corporate_answer ) )
                            )
                            <div class="___ class_{{ $service['id'] }}" @if( strlen( $question -> individual_question ) && strlen( $question -> individual_answer ) ) x-customer-show x-individual @endif @if( strlen( $question -> corporate_question ) && strlen( $question -> corporate_answer ) ) x-customer-show x-corporate @if( ! ( strlen( $question -> individual_question ) && strlen( $question -> individual_answer ) ) ) class="hidden" @endif @endif>
                                <div class="faq-item">
                                    <span x-customer-show x-individual>{{ $question -> individual_question }}</span>
                                    <span x-customer-show x-corporate class="hidden">{{ $question -> corporate_question }}</span>
                                    <i class="fas fa-angle-right"></i>
                                </div>
                                <div class="answer">
                                    <p x-customer-show x-individual>{{ $question -> individual_answer }}</p>
                                    <p x-customer-show x-corporate class="hidden">{{ $question -> corporate_answer }}</p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </section>
        @include('site.subscribe')
    </main>

@endsection
