<?php
/**
 * Created by PhpStorm.
 * User: Khayyam
 * Date: 09-Jun-18
 * Time: 13:58
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class Language
{
    public const DEFAULT_LOCALE   = 'az';
    public const DEFAULT_LANGUAGE = 'Azərbaycan dili';


    public static function LANGUAGES()
    {
        return [
            self::DEFAULT_LOCALE => self::DEFAULT_LANGUAGE ,
            'en'                 => 'English' ,
            'ru'                 => 'Russian'
        ];
    }


    public static function LOCALES()
    {
        return array_keys( self ::LANGUAGES() );
    }


    /**
     *
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle( Request $request , Closure $next )
    {
        $locale = $request -> route( 'locale' );

        if( $locale && ! in_array( $locale , self ::LOCALES() ) )
        {
            /*$url = URL ::current();

            $url = str_replace( "/$locale" , '/' . self::DEFAULT_LOCALE , $url );

            return response() -> redirectTo( $url );*/

            return response() -> redirectTo( '/404' );
        }

        $locale = in_array( $locale , self ::LOCALES() ) ? $locale : ( $request -> session() -> has( 'locale' ) ? $request -> session() -> get( 'locale' ) : self::DEFAULT_LOCALE );

        session( [ 'locale' => $locale ] );

        App ::setLocale( $locale );

        return $next( $request );
    }

}
