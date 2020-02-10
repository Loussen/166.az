@php
    $currentRoute = Route::currentRouteName();

    $locale = app() -> getLocale();
@endphp

@if( \App\Http\Controllers\Admin\AdminController::CAN( 'dashboard' ) )
    <li class="m-menu__item {{ ( $currentRoute == 'admin.dashboard' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
        <a href="{{ route( 'admin.dashboard' ) }}" class="m-menu__link">
            <i class="m-menu__link-icon flaticon-dashboard"></i>
            <span class="m-menu__link-title">
                <span class="m-menu__link-wrap">
                    <span class="m-menu__link-text">Dashboard</span>
                </span>
            </span>
        </a>
    </li>
@endif

@if( \App\Http\Controllers\Admin\AdminController::CAN( 'admin.view' ) )
    <li class="m-menu__item {{ ( $currentRoute == 'admin.admin.page' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
        <a href="{{ route( 'admin.admin.page' ) }}" class="m-menu__link">
            <i class="m-menu__link-icon flaticon-user-add"></i>
            <span class="m-menu__link-title">
                <span class="m-menu__link-wrap">
                    <span class="m-menu__link-text">Admins</span>
                </span>
            </span>
        </a>
    </li>
@endif

@if( \App\Http\Controllers\Admin\AdminController::CAN( 'order.view' ) )
    <li class="m-menu__item {{ ( $currentRoute == 'admin.order.page' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
        <a href="{{ route( 'admin.order.page' ) }}" class="m-menu__link">
            <i class="m-menu__link-icon flaticon-cart"></i>
            <span class="m-menu__link-title">
                <span class="m-menu__link-wrap">
                    <span class="m-menu__link-text">Orders</span>
                </span>
            </span>
        </a>
    </li>
@endif

@if( \App\Http\Controllers\Admin\AdminController::CAN( 'callback.view' ) )
    <li class="m-menu__item {{ ( $currentRoute == 'admin.callback.page' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
        <a href="{{ route( 'admin.callback.page' ) }}" class="m-menu__link">
            <i class="m-menu__link-icon flaticon-refresh"></i>
            <span class="m-menu__link-title">
                <span class="m-menu__link-wrap">
                    <span class="m-menu__link-text">Callback</span>
                </span>
            </span>
        </a>
    </li>
@endif

@if( \App\Http\Controllers\Admin\AdminController::CAN( 'coupon.view' ) )
    <li class="m-menu__item {{ ( $currentRoute == 'admin.coupon.page' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
        <a href="{{ route( 'admin.coupon.page' ) }}" class="m-menu__link">
            <i class="m-menu__link-icon flaticon-refresh"></i>
            <span class="m-menu__link-title">
                <span class="m-menu__link-wrap">
                    <span class="m-menu__link-text">Coupon</span>
                </span>
            </span>
        </a>
    </li>
@endif

@if( \App\Http\Controllers\Admin\AdminController::CAN( 'service.view' ) )
    <li class="m-menu__item {{ ( $currentRoute == 'admin.service.page' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
        <a href="{{ route( 'admin.service.page' ) }}" class="m-menu__link">
            <i class="m-menu__link-icon flaticon-coins"></i>
            <span class="m-menu__link-title">
                <span class="m-menu__link-wrap">
                    <span class="m-menu__link-text">Services</span>
                </span>
            </span>
        </a>
    </li>
@endif

@if( \App\Http\Controllers\Admin\AdminController::CAN( 'service.extraInfo.view' ) )
    <li class="ml-3 m-menu__item {{ ( $currentRoute == 'admin.extra-info.page' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
        <a href="{{ route( 'admin.extra-info.page' ) }}" class="m-menu__link">
            <i class="m-menu__link-icon flaticon-apps"></i>
            <span class="m-menu__link-title">
                <span class="m-menu__link-wrap">
                    <span class="m-menu__link-text">Service extra info</span>
                </span>
            </span>
        </a>
    </li>
@endif

@if( \App\Http\Controllers\Admin\AdminController::CAN( 'serviceInput.view' ) )
    <li class="m-menu__item {{ ( $currentRoute == 'admin.service-input.page' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
        <a href="{{ route( 'admin.service-input.page' ) }}" class="m-menu__link">
            <i class="m-menu__link-icon flaticon-analytics"></i>
            <span class="m-menu__link-title">
                <span class="m-menu__link-wrap">
                    <span class="m-menu__link-text">Service inputs</span>
                </span>
            </span>
        </a>
    </li>
@endif

@if( \App\Http\Controllers\Admin\AdminController::CAN( 'slider.view' ) )
    <li class="m-menu__item {{ ( $currentRoute == 'admin.slider.page' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
        <a href="{{ route( 'admin.slider.page' ) }}" class="m-menu__link">
            <i class="m-menu__link-icon flaticon-arrows"></i>
            <span class="m-menu__link-title">
                <span class="m-menu__link-wrap">
                    <span class="m-menu__link-text">Sliders</span>
                </span>
            </span>
        </a>
    </li>
@endif

@if( \App\Http\Controllers\Admin\AdminController::CAN( 'faq.view' ) )
    <li class="m-menu__item {{ ( $currentRoute == 'admin.faq.page' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
        <a href="{{ route( 'admin.faq.page' ) }}" class="m-menu__link">
            <i class="m-menu__link-icon flaticon-questions-circular-button"></i>
            <span class="m-menu__link-title">
                <span class="m-menu__link-wrap">
                    <span class="m-menu__link-text">FAQ</span>
                </span>
            </span>
        </a>
    </li>
@endif

@if( \App\Http\Controllers\Admin\AdminController::CAN( 'carType.view' ) )
    <li class="m-menu__item {{ ( $currentRoute == 'admin.car-type.page' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
        <a href="{{ route( 'admin.car-type.page' ) }}" class="m-menu__link">
            <i class="m-menu__link-icon flaticon-transport"></i>
            <span class="m-menu__link-title">
                <span class="m-menu__link-wrap">
                    <span class="m-menu__link-text">Car types</span>
                </span>
            </span>
        </a>
    </li>
@endif

@if( \App\Http\Controllers\Admin\AdminController::CAN( 'car.view' ) )
    <li class="m-menu__item {{ ( $currentRoute == 'admin.car.page' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
        <a href="{{ route( 'admin.car.page' ) }}" class="m-menu__link">
            <i class="m-menu__link-icon flaticon-truck"></i>
            <span class="m-menu__link-title">
                <span class="m-menu__link-wrap">
                    <span class="m-menu__link-text">Cars</span>
                </span>
            </span>
        </a>
    </li>
@endif

@if( \App\Http\Controllers\Admin\AdminController::CAN( 'postBlog.view' ) )
    <li class="m-menu__item {{ ( $currentRoute == 'admin.post-blog.page' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
        <a href="{{ route( 'admin.post-blog.page' ) }}" class="m-menu__link">
            <i class="m-menu__link-icon flaticon-imac"></i>
            <span class="m-menu__link-title">
                <span class="m-menu__link-wrap">
                    <span class="m-menu__link-text">Blog</span>
                </span>
            </span>
        </a>
    </li>
@endif

@if( \App\Http\Controllers\Admin\AdminController::CAN( 'postMedia.view' ) )
    <li class="m-menu__item {{ ( $currentRoute == 'admin.post-media.page' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
        <a href="{{ route( 'admin.post-media.page' ) }}" class="m-menu__link">
            <i class="m-menu__link-icon flaticon-confetti"></i>
            <span class="m-menu__link-title">
                <span class="m-menu__link-wrap">
                    <span class="m-menu__link-text">We at media</span>
                </span>
            </span>
        </a>
    </li>
@endif

@if( \App\Http\Controllers\Admin\AdminController::CAN( 'campaign.view' ) )
    <li class="m-menu__item {{ ( $currentRoute == 'admin.campaign.page' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
        <a href="{{ route( 'admin.campaign.page' ) }}" class="m-menu__link">
            <i class="m-menu__link-icon flaticon-event-calendar-symbol"></i>
            <span class="m-menu__link-title">
                <span class="m-menu__link-wrap">
                    <span class="m-menu__link-text">Campaigns</span>
                </span>
            </span>
        </a>
    </li>
@endif

@if( \App\Http\Controllers\Admin\AdminController::CAN( 'economCampaignActivity.view' ) )
    <li class="m-menu__item {{ ( $currentRoute == 'admin.econom-campaign-activity.page' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
        <a href="{{ route( 'admin.econom-campaign-activity.page' ) }}" class="m-menu__link">
            <i class="m-menu__link-icon flaticon-folder"></i>
            <span class="m-menu__link-title">
                <span class="m-menu__link-wrap">
                    <span class="m-menu__link-text">Econom campaign activities</span>
                </span>
            </span>
        </a>
    </li>
@endif

@if( \App\Http\Controllers\Admin\AdminController::CAN( 'economCampaign.view' ) )
    <li class="m-menu__item {{ ( $currentRoute == 'admin.econom-campaign.page' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
        <a href="{{ route( 'admin.econom-campaign.page' ) }}" class="m-menu__link">
            <i class="m-menu__link-icon flaticon-bell"></i>
            <span class="m-menu__link-title">
                <span class="m-menu__link-wrap">
                    <span class="m-menu__link-text">Econom campaigns</span>
                </span>
            </span>
        </a>
    </li>
@endif

@if( \App\Http\Controllers\Admin\AdminController::CAN( 'hourlyCampaignActivity.view' ) )
    <li class="m-menu__item {{ ( $currentRoute == 'admin.hourly-campaign-activity.page' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
        <a href="{{ route( 'admin.hourly-campaign-activity.page' ) }}" class="m-menu__link">
            <i class="m-menu__link-icon flaticon-clock-2"></i>
            <span class="m-menu__link-title">
                <span class="m-menu__link-wrap">
                    <span class="m-menu__link-text">Hourly campaign activities</span>
                </span>
            </span>
        </a>
    </li>
@endif

@if( \App\Http\Controllers\Admin\AdminController::CAN( 'hourlyCampaign.view' ) )
    <li class="m-menu__item {{ ( $currentRoute == 'admin.hourly-campaign.page' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
        <a href="{{ route( 'admin.hourly-campaign.page' ) }}" class="m-menu__link">
            <i class="m-menu__link-icon flaticon-clock"></i>
            <span class="m-menu__link-title">
                <span class="m-menu__link-wrap">
                    <span class="m-menu__link-text">Hourly campaigns</span>
                </span>
            </span>
        </a>
    </li>
@endif

@if( \App\Http\Controllers\Admin\AdminController::CAN( 'vacancy.view' ) )
    <li class="m-menu__item {{ ( $currentRoute == 'admin.vacancy.page' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
        <a href="{{ route( 'admin.vacancy.page' ) }}" class="m-menu__link">
            <i class="m-menu__link-icon flaticon-user-add"></i>
            <span class="m-menu__link-title">
                <span class="m-menu__link-wrap">
                    <span class="m-menu__link-text">Vacancies</span>
                </span>
            </span>
        </a>
    </li>
@endif

@if( \App\Http\Controllers\Admin\AdminController::CAN( 'employee.view' ) )
    <li class="m-menu__item {{ ( $currentRoute == 'admin.employee.page' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
        <a href="{{ route( 'admin.employee.page' ) }}" class="m-menu__link">
            <i class="m-menu__link-icon flaticon-users"></i>
            <span class="m-menu__link-title">
                <span class="m-menu__link-wrap">
                    <span class="m-menu__link-text">Employees</span>
                </span>
            </span>
        </a>
    </li>
@endif

<li class="m-menu__item {{ ( $currentRoute == 'admin.site.page' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
    <a href="{{ route( 'admin.site.page' ) }}" class="m-menu__link">
        <i class="m-menu__link-icon flaticon-information"></i>
        <span class="m-menu__link-title">
            <span class="m-menu__link-wrap">
                <span class="m-menu__link-text">Site info</span>
            </span>
        </span>
    </a>
</li>

@if( \App\Http\Controllers\Admin\AdminController::CAN( 'site.mission.view' ) )
    <li class="ml-3 m-menu__item {{ ( $currentRoute == 'admin.mission.page' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
        <a href="{{ route( 'admin.mission.page' ) }}" class="m-menu__link">
            <i class="m-menu__link-icon flaticon-add-label-button"></i>
            <span class="m-menu__link-title">
                <span class="m-menu__link-wrap">
                    <span class="m-menu__link-text">Missions</span>
                </span>
            </span>
        </a>
    </li>
@endif

<li class="m-menu__item {{ ( $currentRoute == 'admin.settings' ? 'm-menu__item--active' : '' ) }}" aria-haspopup="true">
    <a href="{{ route( 'admin.settings' ) }}" class="m-menu__link">
        <i class="m-menu__link-icon flaticon-user-settings"></i>
        <span class="m-menu__link-title">
            <span class="m-menu__link-wrap">
                <span class="m-menu__link-text">{{ __('message.Settings') }}</span>
            </span>
        </span>
    </a>
</li>
