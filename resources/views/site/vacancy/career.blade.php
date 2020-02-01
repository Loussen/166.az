@extends('site.index')

@section('content')

    <main>
        <section class="page-header career-header" style="background-image: url({{ \App\Http\Controllers\Site\SiteController::BACKGROUND('career') }})">
            <div class="page-header__over"></div>
            <div class="page-header__title">
                <h1 class="title">{{ __('message.Career') }}</h1>
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
            <a class="breadcrumb-item active" aria-current="page">{{ __('message.Career') }}</a>
        </nav>
        <section class="vacancies">
            <div class="container vacancies-container">
                <h6>{{ __('message.You_can_take_advantage_of_open_vacancies_to_join_our_team') }}</h6>
                @foreach( $vacancies as $vacancy )
                    <div class="vacancies-item">
                        <img src="{{ \App\Http\Controllers\Controller::_AVATAR( $vacancy -> photo , 'vacancy' ) }}" alt="">
                        <div class="vacancies-item__text">
                            <h6>{{ $vacancy -> title }}</h6>
                            <p>{{ $vacancy -> text }}</p>
                        </div>
                        <a class="btn" href="{{ route( 'site.vacancy.page' , [ 'id' => $vacancy -> id ] ) }}">{{ __('message.READ_MORE') }}</a>
                    </div>
                @endforeach
            </div>
        </section>
        @include('site.subscribe')
    </main>

@endsection
