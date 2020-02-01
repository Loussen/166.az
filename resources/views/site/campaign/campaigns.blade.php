@extends('site.index')

@section('og')
    <meta property="og:title" content="{{ __('message.Campaigns') }}">
    <meta property="og:image" content="{{ \App\Http\Controllers\Site\SiteController::BACKGROUND('campaign') }}">
    <meta property="keywords" content="{{ \App\Http\Controllers\Site\SiteController::HEADLINE('campaign_seo_keywords') }}">
    <meta property="description" content="{{ \App\Http\Controllers\Site\SiteController::HEADLINE('campaign_seo_description') }}">
@endsection

@section('content')

    <main>
        <section class="page-header campaigns-header" style="background-image: url({{ \App\Http\Controllers\Site\SiteController::BACKGROUND('campaign') }})">
            <div class="page-header__over"></div>
            <div class="page-header__title">
                <h1 class="title">{{ __('message.Campaigns') }}</h1>
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
            <a class="breadcrumb-item active" aria-current="page">{{ __('message.Campaigns') }}</a>
        </nav>
        <section class="page-description">
            <div class="container">
                <h2>{{ __('message.Campaigns') }}</h2>
                <p>{{ \App\Http\Controllers\Site\SiteController::HEADLINE('campaigns_headline') }}</p>
            </div>
        </section>
        <section class="filter">
            <div class="container">
                <ul id="campaignFilterButton">
                    <li class="active campaigns-discount">
                        <a href="#">{{ __('message.Discount_Packages') }}</a>
                    </li>
                    <li class="campaigns-econom">
                        <a href="#">{{ __('message.Economic_proposals') }}</a>
                    </li>
                    <li class="campaigns-hourly">
                        <a href="#">{{ __('message.Hourly_packages') }}</a>
                    </li>
                </ul>
            </div>
        </section>
        <section class="campaigns-body" id="campaigns-body">
            <div class="___ campaigns-discount" style="display: block;">
                <div class="container" x-list-tbody></div>
                <div class="campaigns-body__bottom">
                    <form action="{{ route( 'site.campaign.list' ) }}" method="post" x-list-form="main">
                        <input type="hidden" name="per" value="3">
                        <button class="button-all" x-more>{{ __('message.MORE') }}</button>
                    </form>
                </div>
            </div>
            <div class="___ campaigns-econom" style="display: none;">
                <div class="container campaigns-econom__container">
                    @foreach( $economCampaigns as $economCampaign )
                        <div class="campaigns-econom__item">
                            <div class="campaigns-econom__item--title">
                                <h3>{{ $economCampaign -> title }}</h3>
                            </div>
                            <div class="campaigns-econom__item--details">
                                <div class="campaigns-econom__item--price">
                                    <h4>{{ $economCampaign -> price }}<img src="{{ asset('site/img/azn.png') }}"></h4>
                                </div>
                                <p x-customer-show x-individual>{{ $economCampaign -> individual_text }}</p>
                                <p x-customer-show x-corporate class="hidden">{{ $economCampaign -> corporate_text }}</p>
                                <a href="#">{{ __('message.ORDER') }}</a>
                                <ul>
                                    @if( isset( $economCampaign -> activities ) )
                                        @foreach( $economCampaign -> activities as $activity )
                                            <li @if( ! $activity -> included ) class="passive" @endif>
                                                <i class="fas fa-check-circle"></i><span>{{ $activity -> name }}</span>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="___ campaigns-hourly" style="display: none;">
                <div class="container campaigns-hourly__container">
                    @foreach( $hourlyCampaigns as $hourlyCampaign )
                        <div class="campaigns-hourly__element">
                            <div class="campaigns-hourly__item">
                                <div class="campaigns-hourly__item--title">
                                    <h3>{{ $hourlyCampaign -> hour }} {{ __('message.HOURLY_PACKAGE') }}</h3>
                                </div>
                                <div class="campaigns-hourly__item--prices">
                                    <div class="campaigns-hourly__item--price">
                                        <h4>{{ $hourlyCampaign -> day_1_price }}
                                            <img src="{{ asset('site/img/azn.png') }}">
                                        </h4>
                                        <p>{{ __('message.One_day_a_week') }}</p>
                                    </div>
                                    <div class="campaigns-hourly__item--price">
                                        <h4>{{ $hourlyCampaign -> day_2_price }}
                                            <img src="{{ asset('site/img/azn.png') }}">
                                        </h4>
                                        <p>{{ __('message.Two_days_a_week') }}</p>
                                    </div>
                                    <div class="campaigns-hourly__item--price">
                                        <h4>{{ $hourlyCampaign -> day_3_price }}
                                            <img src="{{ asset('site/img/azn.png') }}">
                                        </h4>
                                        <p>{{ __('message.Three_days_a_week') }}</p>
                                    </div>
                                </div>
                                <p x-customer-show x-individual>{{ $hourlyCampaign -> individual_text }}</p>
                                <p x-customer-show x-corporate class="hidden">{{ $hourlyCampaign -> corporate_text }}</p>
                                <ul class="list-unstyled">
                                    @if( isset( $hourlyCampaign -> activities ) )
                                        @foreach( $hourlyCampaign -> activities as $activity )
                                            <li @if( ! $activity -> included ) class="passive" @endif>
                                                <i class="fas fa-check-circle"></i><span>{{ $activity -> name }}</span>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                                <a href="#">{{ __('message.ORDER') }}</a>
                            </div>
                            <div class="campaigns-hourly__alert">
                                @if( isset( $hourlyCampaign -> alerts ) )
                                    @foreach( $hourlyCampaign -> alerts as $alert )
                                        <p>
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <span>{!! $alert -> text !!}</span>
                                        </p>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>

@endsection

@section('extra_js')

    <script>
        function translate( word )
        {
            if( word == 'phone' ) return '{{ __('message.Placeholder_phone') }}';

            return '{{ __('message.Placeholder_email') }}';
        }
    </script>

@endsection

@section('templates')

    <script type="text/template" x-tr>
        <div class="campaigns-discount__item">
            <img src="<%= media(rc.photo) %>" alt="">
            <div class="campaigns-discount__item--right">
                <h3><%= rc.title %></h3>
                <p x-customer-show x-individual><%= rc.individual_headline %></p>
                <p x-customer-show x-corporate class="hidden"><%= rc.corporate_headline %></p>
                <form action="" class="campaigns-discount__item--form">
                    <input type="hidden" name="campaign" value="<%= rc.id %>">
                    <input name="<%= rc.input %>" placeholder="<%= translate(rc.input) %>">
                    <button>
                        <span>{{ __('message.SEND') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </script>

@endsection
