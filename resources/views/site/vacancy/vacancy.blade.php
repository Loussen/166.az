@extends('site.index')

@section('title') {{ $vacancy -> title }} @endsection

@section('og')
    <meta property="og:title" content="{{ $vacancy -> title }}">
    <meta property="og:image" content="{{ media( 'vacancy/' . $vacancy -> og_image ) }}">
@endsection

@section('content')

    <main>
        <section class="page-header career-header" style="background-image: url( {{ media( 'vacancy/' . $vacancy -> background ) }} ) !important;">
            <div class="page-header__over"></div>
            <div class="page-header__title">
                <h1 class="title">{{ $vacancy -> title }}</h1>
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
            <a class="breadcrumb-item" href="{{ route('site.career.page') }}">{{ __('message.Career') }}</a>
            <a class="breadcrumb-item active" aria-current="page">{{ $vacancy -> title }}</a>
        </nav>
        <section class="vacancies-info">
            <div class="container">
                <h2>{{ __('message.About_vacancy') }}</h2>
                @foreach( $vacancy -> details as $detail )
                    <p><img src="{{ asset('site/img/check.png') }}" alt="">{{ $detail -> name }}</p>
                @endforeach

                <h2 class="mt-5">{{ __('message.Vacancy_requirements') }}</h2>
                @foreach( $vacancy -> requirements as $requirement )
                    <p><img src="{{ asset('site/img/check.png') }}" alt="">{{ $requirement -> name }}</p>
                @endforeach

                <h2>{{ __('message.Note') }}</h2>
                <p>{!! $vacancy -> note !!}</p>

                <button type="button" class="btn" data-toggle="modal" data-target=".online-apply">{{ __('message.APPLY_ONLINE') }}</button>
                <div class="modal fade online-apply" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <button type="button" class="close" data-dismiss="modal">
                            <img src="{{ asset('site/img/cancel.png') }}" alt="">
                        </button>
                        <div class="modal-content">
                            <h3>{{ $vacancy -> title }}</h3>
                            <p>{{ __('message.APPLY_ONLINE') }}</p>
                            <form x-edit-form x-target="afterApply" method="post" action="{{ route('apply-to-vacancy') }}">
                                <input type="hidden" name="vacancy" value="{{ $vacancy -> id }}">
                                <div class="form-group">
                                    <label for="inputName">{{ __('message.NameSurname') }} *</label>
                                    <input type="text" class="form-control" id="inputName" name="name">
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail">{{ __('message.Email') }} *</label>
                                    <input type="text" class="form-control" id="inputEmail" name="email">
                                </div>
                                <div class="form-group">
                                    <label for="inputMobile">{{ __('message.Phone') }} *</label>
                                    <input type="text" class="form-control" id="inputMobile" name="phone">
                                </div>
                                <div class="form-group">
                                    <label for="inputCv">{{ __('message.Upload_CV') }} *</label>
                                    <label style=" width: 100%;
                                height: 38px;
                                border-radius: 10px;
                                border: 1px solid #efefef;
                                background-color: #f6f7f7;
                                display: inline-block;
                                padding: 6px 12px;
            " for="file-upload" class="form-group__label">
                                        <span></span>
                                        <img src="{{ asset('site/img/download.png') }}">
                                    </label>
                                    <input id="file-upload" class="d-none" name="cv" type="file" accept=".doc , .docx , .pdf">
                                </div>
                                <button type="submit" class="btn ">{{ __('message.Apply') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="apply-success-modal" class="modal fade modal-service">
                    <div class="modal-dialog modal-service__dialog">
                        <div class="modal-content modal-service__content">
                            <div class="modal-header modal-service__header">
                                <h6>{{ __('message.Thank_you_for_applying') }}</h6>
                            </div>
                            <div class="modal-close" data-dismiss="modal">
                                <i class="fa fa-times"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="vacancies">
            <div class="container vacancies-container">
                <h6>{{ __('message.You_can_take_advantage_of_open_vacancies_to_join_our_team') }}</h6>
                @foreach( $vacancies as $vacancy )
                    <div class="vacancies-item">
                        <img src="{{ \App\Http\Controllers\Controller::_AVATAR( $vacancy -> photo , 'vacancy'  ) }}" alt="">
                        <div class="vacancies-item__text">
                            <h6>{{ $vacancy -> title }}</h6>
                            <p>{{ $vacancy -> text }}</p>
                        </div>
                        <a class="btn" href="{{ route( 'site.vacancy.page' , [ 'id' => $vacancy -> id ] ) }}">{{ __('message.READ_MORE') }}</a>
                    </div>
                @endforeach
            </div>
        </section>
    </main>

@endsection


@section('extra_js')

    <script>
        function afterApply( response )
        {
            $( '[x-target="afterApply"]' ).trigger( 'reset' );

            $( '[x-target="afterApply"]' ).find( '.form-group__label' ).html( '<span></span><img src="{{ asset('site/img/download.png') }}">' );

            $( '.online-apply' ).modal( 'hide' );

            $( '#apply-success-modal' ).modal( 'show' );
        }

    </script>

@endsection
