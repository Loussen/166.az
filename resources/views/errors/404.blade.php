@extends('site.index')

@section('content')

    <main class="container error">
        <img src="{{ asset('site/img/404.png') }}">
        <h1>{{ __('message.No_page_found') }}</h1>
        <p>{{ __('message.Sorry_for_disturbing') }}</p>
        <a href="{{ route('site.home') }}">{{ __('message.GO_TO_HOMEPAGE') }}</a>
    </main>

@endsection
