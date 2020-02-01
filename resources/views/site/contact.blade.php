@extends('site.index')

@section('content')

    <main>
        <section class="page-header contact-header" style="background-image: url({{ media( 'site/' . $site -> contact_background ) }})">
            <div class="page-header__over"></div>
            <div class="page-header__title">
                <h1 class="title">{{ __('message.Contact') }}</h1>
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
            <a class="breadcrumb-item active" aria-current="page">{{ __('message.Contact') }}</a>
        </nav>
        <section class="contact">
            <div class="container">
                <div class="contact-body">
                    <div class="contact-body__leftside" x-apply-area>
                        <h2>{{ __('message.Send_us_message') }}</h2>
                        <p>{{ __('message.Contact_headline') }}</p>
                        <form x-edit-form x-target="afterApply" method="post" action="{{ route('apply') }}">
                            <div class="row">
                                <div class="col pl-0">
                                    <label for="name">{{ __('message.NameSurname') }} *</label>
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>
                                <div class="col pr-0">
                                    <label for="email">{{ __('message.Email') }} *</label>
                                    <input type="text" class="form-control" id="email" name="email">
                                </div>
                            </div>
                            <div class="row">
                                <label for="title">{{ __('message.Subject') }} *</label>
                                <input type="text" class="form-control" id="title" name="subject">
                            </div>
                            <div class="row">
                                <label for="text">{{ __('message.Message') }} *</label>
                                <input type="text" class="form-control" id="text" name="text">
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('message.Send_MESSAGE') }}</button>
                        </form>


                    </div>
                    <div class="contact-body__rightside">
                        <div>
                            <h3>{{ __('message.Contact_with_office') }}</h3>
                            <h4>{{ __('message.Address') }}:</h4>
                            <p>{{ $site -> address }}</p>
                            <h4>{{ __('message.Post_index') }}:</h4>
                            <p>{{ $site -> index }}</p>
                            <h4>{{ __('message.Corporate_contact') }}:</h4>
                            <p>{{ $site -> corporate_contact }}</p>
                            <h4>{{ __('message.Phone') }}:</h4>
                            <p>{{ $site -> mobile }}</p>
                            <h4>{{ __('message.Hotline') }}:</h4>
                            <p>{!! __('message.Call_166') !!}</p>
                            <h4>{{ __('message.Email') }}:</h4>
                            <span>{{ $site -> email }}</span>
                        </div>
                        <div>
                            <h3>{{ __('message.For_advertising_cooperation') }}</h3>
                            <h4>{{ __('message.Phone') }}: </h4>
                            <p>{{ $site -> ad_mobile }}
                                <br>
                                ( {{ __('message.only_for_promotional_offers') }} )
                            </p>
                            <h4>{{ __('message.Email') }}:</h4>
                            <span>{{ $site -> ad_email }}</span>
                        </div>
                        <div>
                            <h3>{{ __('message.For_order') }}</h3>
                            <h4>{{ __('message.Phone') }}: </h4>
                            <p>{{ $site -> order_mobile }}</p>
                            <h4>{{ __('message.Email') }}:</h4>
                            <span>{{ $site -> order_email }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="contact-map">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3038.6889014476264!2d49.80037431527079!3d40.39358706497081!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40308764866c1bef%3A0x32e66a8d53344d74!2s168%20Abbas%20Mirza%20Sharifzadeh%20St%2C%20Baku%201122%2C%20Azerbaijan!5e0!3m2!1sen!2s!4v1575802092395!5m2!1sen!2s"
                    width="100%" height="333" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
            </div>
        </section>
    </main>

@endsection


@section('extra_js')

    <script>
        function afterApply( response )
        {
            $( '[x-target="afterApply"]' ).fadeOut( function()
            {
                $( this ).remove();
            } );

            $( '[x-apply-area]' ).append( '<span>{{ __('message.Thank_you_for_message') }}</span>' );
        }

    </script>

@endsection
