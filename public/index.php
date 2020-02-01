<?php

ini_set( 'session.gc_maxlifetime' , '604800' );

ini_set( 'max_execution_time' , 1200 );

date_default_timezone_set( 'Asia/Baku' );

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

define( 'LARAVEL_START' , microtime( true ) );


/**
 * Generate an asset path for the application with version set to file modification time.
 *
 * @param string $path
 * @param bool $secure
 * @return string
 */
function asset( $path , $secure = null )
{
    return app( 'url' ) -> asset( $path , $secure ) . '?v=' . ( is_file( __DIR__ . '/' . $path ) ? filemtime( __DIR__ . '/' . $path ) : now() );
}


function media( $path , $secure = null )
{
    return is_file( __DIR__ . '/uploads/' . $path ) ? app( 'url' ) -> asset( 'uploads/' . $path , $secure ) . '?v=' . filemtime( __DIR__ . '/uploads/' . $path ) : ( app( 'url' ) -> asset( 'uploads/default.png' , $secure ) . '?v=' . ( is_file( __DIR__ . '/uploads/default.png' ) ? filemtime( __DIR__ . '/uploads/default.png' ) : now() ) );
}


/**
 * Generate the URL to a named route.
 *
 * @param array|string $name
 * @param mixed $parameters
 * @param bool $absolute
 * @return string
 */
function route( $name , $parameters = [] , $absolute = true )
{
    if( strpos( $name , 'admin.' ) === false ) $parameters[ 'locale' ] = \Illuminate\Support\Facades\App ::getLocale();

    return app( 'url' ) -> route( $name , $parameters , $absolute );
}


/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__ . '/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app -> make( Illuminate\Contracts\Http\Kernel::class );

$response = $kernel -> handle(
    $request = Illuminate\Http\Request ::capture()
);

$response -> send();

$kernel -> terminate( $request , $response );
