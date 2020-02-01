<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Test
Route ::get( 'test' , 'TestController@test' ) -> name( 'test' );

// Auth
Route ::get( 'login' , 'Auth\LoginController@showLoginForm' ) -> name( 'login' );
Route ::post( 'login' , 'Auth\LoginController@login' );
Route ::post( 'logout' , 'Auth\LoginController@logout' ) -> name( 'logout' );

// Change locale
Route ::get( 'locale/{locale}' , 'LanguageController@changeLanguage' ) -> name( 'locale' );


/*********************************************** Admin **********************************************/

// Dashboard
Route ::get( '/panel' , 'Admin\DashboardController@dashboard' ) -> name( 'admin.dashboard' );

Route ::middleware( [ 'auth' ] ) -> prefix( 'admin' ) -> name( 'admin.' ) -> group( function()
{
    // Admin
    Route ::get( 'admins' , 'Admin\AdminController@showAdminPage' ) -> name( 'admin.page' );
    Route ::post( 'admin/list' , 'Admin\AdminController@getAdminList' ) -> name( 'admin.list' );
    Route ::post( 'admin' , 'Admin\AdminController@getAdmin' ) -> name( 'admin.get' );
    Route ::post( 'admin/edit' , 'Admin\AdminController@editAdmin' ) -> name( 'admin.edit' );
    Route ::post( 'admin/activate' , 'Admin\AdminController@activateAdmin' ) -> name( 'admin.activate' );
    Route ::post( 'admin/delete' , 'Admin\AdminController@deleteAdmin' ) -> name( 'admin.delete' );

    // Service
    Route ::get( 'services' , 'Admin\ServiceController@showServicePage' ) -> name( 'service.page' );
    Route ::post( 'service/list' , 'Admin\ServiceController@getServiceList' ) -> name( 'service.list' );
    Route ::post( 'service' , 'Admin\ServiceController@getService' ) -> name( 'service.get' );
    Route ::post( 'service/edit' , 'Admin\ServiceController@editService' ) -> name( 'service.edit' );
    Route ::post( 'service/activate' , 'Admin\ServiceController@activateService' ) -> name( 'service.activate' );
    Route ::post( 'service/delete' , 'Admin\ServiceController@deleteService' ) -> name( 'service.delete' );
    Route ::post( 'service/search' , 'Admin\ServiceController@getServiceSearch' ) -> name( 'service.search' );
    Route ::post( 'service/step' , 'Admin\ServiceController@getServiceStep' ) -> name( 'service.step' );

    // Service input
    Route ::get( 'service-inputs' , 'Admin\ServiceInputController@showServiceInputPage' ) -> name( 'service-input.page' );
    Route ::post( 'service-input/list' , 'Admin\ServiceInputController@getServiceInputList' ) -> name( 'service-input.list' );
    Route ::post( 'service-input' , 'Admin\ServiceInputController@getServiceInput' ) -> name( 'service-input.get' );
    Route ::post( 'service-input/edit' , 'Admin\ServiceInputController@editServiceInput' ) -> name( 'service-input.edit' );
    Route ::post( 'service-input/activate' , 'Admin\ServiceInputController@activateServiceInput' ) -> name( 'service-input.activate' );
    Route ::post( 'service-input/delete' , 'Admin\ServiceInputController@deleteServiceInput' ) -> name( 'service-input.delete' );

    // Service extra info
    Route ::get( 'service-extra-info' , 'Admin\ExtraInfoController@showExtraInfoPage' ) -> name( 'extra-info.page' );
    Route ::post( 'extra-info/list' , 'Admin\ExtraInfoController@getExtraInfoList' ) -> name( 'extra-info.list' );
    Route ::post( 'extra-info' , 'Admin\ExtraInfoController@getExtraInfo' ) -> name( 'extra-info.get' );
    Route ::post( 'extra-info/edit' , 'Admin\ExtraInfoController@editExtraInfo' ) -> name( 'extra-info.edit' );
    Route ::post( 'extra-info/activate' , 'Admin\ExtraInfoController@activateExtraInfo' ) -> name( 'extra-info.activate' );
    Route ::post( 'extra-info/delete' , 'Admin\ExtraInfoController@deleteExtraInfo' ) -> name( 'extra-info.delete' );

    // FAQ
    Route ::get( 'faq' , 'Admin\FaqController@showFaqPage' ) -> name( 'faq.page' );
    Route ::post( 'faq/list' , 'Admin\FaqController@getFaqList' ) -> name( 'faq.list' );
    Route ::post( 'faq' , 'Admin\FaqController@getFaq' ) -> name( 'faq.get' );
    Route ::post( 'faq/edit' , 'Admin\FaqController@editFaq' ) -> name( 'faq.edit' );
    Route ::post( 'faq/activate' , 'Admin\FaqController@activateFaq' ) -> name( 'faq.activate' );
    Route ::post( 'faq/delete' , 'Admin\FaqController@deleteFaq' ) -> name( 'faq.delete' );

    // Car type
    Route ::get( 'car-types' , 'Admin\CarTypeController@showCarTypePage' ) -> name( 'car-type.page' );
    Route ::post( 'car-type/list' , 'Admin\CarTypeController@getCarTypeList' ) -> name( 'car-type.list' );
    Route ::post( 'car-type' , 'Admin\CarTypeController@getCarType' ) -> name( 'car-type.get' );
    Route ::post( 'car-type/edit' , 'Admin\CarTypeController@editCarType' ) -> name( 'car-type.edit' );
    Route ::post( 'car-type/activate' , 'Admin\CarTypeController@activateCarType' ) -> name( 'car-type.activate' );
    Route ::post( 'car-type/delete' , 'Admin\CarTypeController@deleteCarType' ) -> name( 'car-type.delete' );

    // Car
    Route ::get( 'car' , 'Admin\CarController@showCarPage' ) -> name( 'car.page' );
    Route ::post( 'car/list' , 'Admin\CarController@getCarList' ) -> name( 'car.list' );
    Route ::post( 'car' , 'Admin\CarController@getCar' ) -> name( 'car.get' );
    Route ::post( 'car/edit' , 'Admin\CarController@editCar' ) -> name( 'car.edit' );
    Route ::post( 'car/activate' , 'Admin\CarController@activateCar' ) -> name( 'car.activate' );
    Route ::post( 'car/delete' , 'Admin\CarController@deleteCar' ) -> name( 'car.delete' );

    // Post ( Blog , Media )
    Route ::get( 'blog' , 'Admin\PostController@showPostPage' ) -> name( 'post-blog.page' );
    Route ::get( 'media' , 'Admin\PostController@showPostPage' ) -> name( 'post-media.page' );
    Route ::post( 'post/list' , 'Admin\PostController@getPostList' ) -> name( 'post.list' );
    Route ::post( 'post' , 'Admin\PostController@getPost' ) -> name( 'post.get' );
    Route ::post( 'post/edit' , 'Admin\PostController@editPost' ) -> name( 'post.edit' );
    Route ::post( 'post/activate' , 'Admin\PostController@activatePost' ) -> name( 'post.activate' );
    Route ::post( 'post/delete' , 'Admin\PostController@deletePost' ) -> name( 'post.delete' );

    // Campaign
    Route ::get( 'campaigns' , 'Admin\CampaignController@showCampaignPage' ) -> name( 'campaign.page' );
    Route ::post( 'campaign/list' , 'Admin\CampaignController@getCampaignList' ) -> name( 'campaign.list' );
    Route ::post( 'campaign' , 'Admin\CampaignController@getCampaign' ) -> name( 'campaign.get' );
    Route ::post( 'campaign/edit' , 'Admin\CampaignController@editCampaign' ) -> name( 'campaign.edit' );
    Route ::post( 'campaign/activate' , 'Admin\CampaignController@activateCampaign' ) -> name( 'campaign.activate' );
    Route ::post( 'campaign/delete' , 'Admin\CampaignController@deleteCampaign' ) -> name( 'campaign.delete' );

    // Econom Campaign Activity
    Route ::get( 'econom-campaign-activities' , 'Admin\EconomCampaignActivityController@showEconomCampaignActivityPage' ) -> name( 'econom-campaign-activity.page' );
    Route ::post( 'econom-campaign-activity/list' , 'Admin\EconomCampaignActivityController@getEconomCampaignActivityList' ) -> name( 'econom-campaign-activity.list' );
    Route ::post( 'econom-campaign-activity' , 'Admin\EconomCampaignActivityController@getEconomCampaignActivity' ) -> name( 'econom-campaign-activity.get' );
    Route ::post( 'econom-campaign-activity/edit' , 'Admin\EconomCampaignActivityController@editEconomCampaignActivity' ) -> name( 'econom-campaign-activity.edit' );
    Route ::post( 'econom-campaign-activity/activate' , 'Admin\EconomCampaignActivityController@activateEconomCampaignActivity' ) -> name( 'econom-campaign-activity.activate' );
    Route ::post( 'econom-campaign-activity/delete' , 'Admin\EconomCampaignActivityController@deleteEconomCampaignActivity' ) -> name( 'econom-campaign-activity.delete' );

    // Econom Campaign
    Route ::get( 'econom-campaigns' , 'Admin\EconomCampaignController@showEconomCampaignPage' ) -> name( 'econom-campaign.page' );
    Route ::post( 'econom-campaign/list' , 'Admin\EconomCampaignController@getEconomCampaignList' ) -> name( 'econom-campaign.list' );
    Route ::post( 'econom-campaign' , 'Admin\EconomCampaignController@getEconomCampaign' ) -> name( 'econom-campaign.get' );
    Route ::post( 'econom-campaign/edit' , 'Admin\EconomCampaignController@editEconomCampaign' ) -> name( 'econom-campaign.edit' );
    Route ::post( 'econom-campaign/activate' , 'Admin\EconomCampaignController@activateEconomCampaign' ) -> name( 'econom-campaign.activate' );
    Route ::post( 'econom-campaign/delete' , 'Admin\EconomCampaignController@deleteEconomCampaignActivity' ) -> name( 'econom-campaign.delete' );
    Route ::post( 'econom-campaign/includes' , 'Admin\EconomCampaignController@getEconomCampaignIncludes' ) -> name( 'econom-campaign.includes' );

    // Hourly Campaign Activity
    Route ::get( 'hourly-campaign-activities' , 'Admin\HourlyCampaignActivityController@showHourlyCampaignActivityPage' ) -> name( 'hourly-campaign-activity.page' );
    Route ::post( 'hourly-campaign-activity/list' , 'Admin\HourlyCampaignActivityController@getHourlyCampaignActivityList' ) -> name( 'hourly-campaign-activity.list' );
    Route ::post( 'hourly-campaign-activity' , 'Admin\HourlyCampaignActivityController@getHourlyCampaignActivity' ) -> name( 'hourly-campaign-activity.get' );
    Route ::post( 'hourly-campaign-activity/edit' , 'Admin\HourlyCampaignActivityController@editHourlyCampaignActivity' ) -> name( 'hourly-campaign-activity.edit' );
    Route ::post( 'hourly-campaign-activity/activate' , 'Admin\HourlyCampaignActivityController@activateHourlyCampaignActivity' ) -> name( 'hourly-campaign-activity.activate' );
    Route ::post( 'hourly-campaign-activity/delete' , 'Admin\HourlyCampaignActivityController@deleteHourlyCampaignActivity' ) -> name( 'hourly-campaign-activity.delete' );

    // Hourly Campaign
    Route ::get( 'hourly-campaigns' , 'Admin\HourlyCampaignController@showHourlyCampaignPage' ) -> name( 'hourly-campaign.page' );
    Route ::post( 'hourly-campaign/list' , 'Admin\HourlyCampaignController@getHourlyCampaignList' ) -> name( 'hourly-campaign.list' );
    Route ::post( 'hourly-campaign' , 'Admin\HourlyCampaignController@getHourlyCampaign' ) -> name( 'hourly-campaign.get' );
    Route ::post( 'hourly-campaign/edit' , 'Admin\HourlyCampaignController@editHourlyCampaign' ) -> name( 'hourly-campaign.edit' );
    Route ::post( 'hourly-campaign/activate' , 'Admin\HourlyCampaignController@activateHourlyCampaign' ) -> name( 'hourly-campaign.activate' );
    Route ::post( 'hourly-campaign/delete' , 'Admin\HourlyCampaignController@deleteHourlyCampaignActivity' ) -> name( 'hourly-campaign.delete' );
    Route ::post( 'hourly-campaign/includes' , 'Admin\HourlyCampaignController@getHourlyCampaignIncludes' ) -> name( 'hourly-campaign.includes' );

    // Vacancy
    Route ::get( 'vacancies' , 'Admin\VacancyController@showVacancyPage' ) -> name( 'vacancy.page' );
    Route ::post( 'vacancy/list' , 'Admin\VacancyController@getVacancyList' ) -> name( 'vacancy.list' );
    Route ::post( 'vacancy' , 'Admin\VacancyController@getVacancy' ) -> name( 'vacancy.get' );
    Route ::post( 'vacancy/edit' , 'Admin\VacancyController@editVacancy' ) -> name( 'vacancy.edit' );
    Route ::post( 'vacancy/activate' , 'Admin\VacancyController@activateVacancy' ) -> name( 'vacancy.activate' );
    Route ::post( 'vacancy/delete' , 'Admin\VacancyController@deleteVacancy' ) -> name( 'vacancy.delete' );

    // Employee
    Route ::get( 'employees' , 'Admin\EmployeeController@showEmployeePage' ) -> name( 'employee.page' );
    Route ::post( 'employee/list' , 'Admin\EmployeeController@getEmployeeList' ) -> name( 'employee.list' );
    Route ::post( 'employee' , 'Admin\EmployeeController@getEmployee' ) -> name( 'employee.get' );
    Route ::post( 'employee/edit' , 'Admin\EmployeeController@editEmployee' ) -> name( 'employee.edit' );
    Route ::post( 'employee/activate' , 'Admin\EmployeeController@activateEmployee' ) -> name( 'employee.activate' );
    Route ::post( 'employee/delete' , 'Admin\EmployeeController@deleteEmployee' ) -> name( 'employee.delete' );

    // Slider
    Route ::get( 'sliders' , 'Admin\SliderController@showSliderPage' ) -> name( 'slider.page' );
    Route ::post( 'slider/list' , 'Admin\SliderController@getSliderList' ) -> name( 'slider.list' );
    Route ::post( 'slider' , 'Admin\SliderController@getSlider' ) -> name( 'slider.get' );
    Route ::post( 'slider/edit' , 'Admin\SliderController@editSlider' ) -> name( 'slider.edit' );
    Route ::post( 'slider/activate' , 'Admin\SliderController@activateSlider' ) -> name( 'slider.activate' );
    Route ::post( 'slider/delete' , 'Admin\SliderController@deleteSlider' ) -> name( 'slider.delete' );

    // Site info
    Route ::get( 'site-info' , 'Admin\SiteController@showSitePage' ) -> name( 'site.page' );
    Route ::post( 'edit-site-info' , 'Admin\SiteController@editSiteInfo' ) -> name( 'site.edit' );

    // Mission
    Route ::get( 'missions' , 'Admin\MissionController@showMissionPage' ) -> name( 'mission.page' );
    Route ::post( 'mission/list' , 'Admin\MissionController@getMissionList' ) -> name( 'mission.list' );
    Route ::post( 'mission' , 'Admin\MissionController@getMission' ) -> name( 'mission.get' );
    Route ::post( 'mission/edit' , 'Admin\MissionController@editMission' ) -> name( 'mission.edit' );
    Route ::post( 'mission/activate' , 'Admin\MissionController@activateMission' ) -> name( 'mission.activate' );
    Route ::post( 'mission/delete' , 'Admin\MissionController@deleteMission' ) -> name( 'mission.delete' );

    // Settings
    Route ::get( 'settings' , 'Admin\AdminController@showSettings' ) -> name( 'settings' );
    Route ::post( 'change-password' , 'Admin\AdminController@changePassword' ) -> name( 'changePassword' );
} )
;


/**************************************** Site pages with locale ***************************************/

// 404
Route ::get( '404' , 'Site\SiteController@_404' ) -> name( '_404' );

Route ::middleware( [ 'language' ] ) -> prefix( '{locale?}' ) -> name( 'site.' ) -> group( function()
{
    // Home
    Route ::get( '/' , 'Site\SiteController@homepage' ) -> name( 'home' );

    // About
    Route ::get( 'about' , 'Site\SiteController@about' ) -> name( 'about' );

    // Contact
    Route ::get( 'contact' , 'Site\SiteController@contact' ) -> name( 'contact' );

    // Services
    Route ::get( 'services' , 'Site\ServiceController@services' ) -> name( 'services' );
    Route ::get( 'service/{id}' , 'Site\ServiceController@service' ) -> name( 'service' );

    // Blog
    Route ::get( 'blog/{service?}' , 'Site\PostController@blogPage' ) -> name( 'blog.page' );
    Route ::post( 'blog-posts' , 'Site\PostController@getBlogPostList' ) -> name( 'blog-post.list' );
    Route ::get( 'blog-post/{id?}' , 'Site\PostController@blogPostPage' ) -> name( 'blog-post.page' );

    // We at media
    Route ::get( 'media' , 'Site\PostController@mediaPage' ) -> name( 'media.page' );
    Route ::post( 'media-posts' , 'Site\PostController@getMediaPostList' ) -> name( 'media-post.list' );
    Route ::get( 'media-post/{id?}' , 'Site\PostController@mediaPostPage' ) -> name( 'media-post.page' );

    // Car
    Route ::get( 'cars/{id?}' , 'Site\CarController@carsPage' ) -> name( 'cars.page' );
    Route ::get( 'car/{id?}' , 'Site\CarController@carPage' ) -> name( 'car.page' );

    // FAQ
    Route ::get( 'faq' , 'Site\FaqController@faqPage' ) -> name( 'faq.page' );

    // Career
    Route ::get( 'career' , 'Site\VacancyController@careerPage' ) -> name( 'career.page' );
    Route ::get( 'vacancy/{id}' , 'Site\VacancyController@vacancyPage' ) -> name( 'vacancy.page' );

    // Team
    Route ::get( 'team' , 'Site\EmployeeController@teamPage' ) -> name( 'team.page' );

    // Campaign
    Route ::get( 'campaigns' , 'Site\CampaignController@campaignsPage' ) -> name( 'campaigns.page' );
    Route ::post( 'campaigns' , 'Site\CampaignController@getCampaignList' ) -> name( 'campaign.list' );
    Route ::get( 'campaign/{id}' , 'Site\CampaignController@campaignPage' ) -> name( 'campaign.page' );

    // Gallery
    Route ::get( 'gallery' , 'Site\GalleryController@gallery' ) -> name( 'gallery.page' );
    //Route ::get( 'gallery' , 'Site\GalleryController@galleryPage' ) -> name( 'gallery.page' );
    Route ::post( 'gallery' , 'Site\GalleryController@getImageList' ) -> name( 'gallery.list' );

    // Order
    Route ::get( 'order' , 'Site\OrderController@orderPage' ) -> name( 'order.page' );
} )
;


/******************************************** Site actions *******************************************/

//Callback time
Route ::post( 'callback' , 'Site\SiteController@callback' ) -> name( 'callback' );

//Subscribe
Route ::post( 'subscribe' , 'Site\SiteController@subscribe' ) -> name( 'subscribe' );

// Apply
Route ::post( 'apply' , 'Site\SiteController@apply' ) -> name( 'apply' );

// Apply to vacancy
Route ::post( 'apply-to-vacancy' , 'Site\VacancyController@apply' ) -> name( 'apply-to-vacancy' );

// Order
Route ::post( 'service-inputs' , 'Site\ServiceController@getServiceInputs' ) -> name( 'service-inputs' );
Route ::post( 'calculate' , 'Site\OrderController@calculate' ) -> name( 'calculate' );
Route ::post( 'order' , 'Site\OrderController@order' ) -> name( 'order' );

