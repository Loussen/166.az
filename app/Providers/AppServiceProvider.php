<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if( ( $_SERVER[ 'HTTP_HOST' ] ?? '' ) != 'localhost' )
        {
            //$this -> app[ 'request' ] -> server -> set( 'HTTPS' , true );
        }

        Schema ::defaultStringLength( 191 );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
