<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Language;
use App\Models\Application;
use App\Models\Callback;
use App\Models\Car;
use App\Models\CarType;
use App\Models\Mission;
use App\Models\Post;
use App\Models\Service;
use App\Models\Site;
use App\Models\Slider;
use App\Models\Subscribe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

class SiteController extends Controller
{
    public function homepage()
    {
        try
        {
            $sliders = Slider ::where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 ) -> select( 'photo' , 'link' ) -> get();

            $services = Service ::where( 'is_deleted' , 0 )
                                -> where( 'is_active' , 1 )
                                -> whereNull( 'parent_id' )
                                -> select( 'id' , self ::LANG_PARAM( 'name' ) , self ::LANG_PARAM( 'individual_headline' ) , self ::LANG_PARAM( 'corporate_headline' ) , 'photo' )
                                -> get()
            ;

            $cars = DB ::table( Car::TABLE . ' AS c' )
                       -> join( CarType::TABLE . ' AS t' , 'c.type_id' , 't.id' )
                       -> where( 'c.is_deleted' , 0 ) -> where( 'c.is_active' , 1 )
                       -> where( 't.is_deleted' , 0 ) -> where( 't.is_active' , 1 )
                       -> select( 'c.id' , self ::LANG_PARAM( 'title' ) , self ::LANG_PARAM( 'headline' ) , 'photo' , 'length' , 'height' , 'width' , 'palet' , 'weight' )
                       -> get()
            ;

            $posts = Post ::where( 'is_deleted' , 0 )
                          -> where( 'is_active' , 1 )
                          -> where( 'type' , 'media' )
                          -> select( 'id' , self ::LANG_PARAM( 'title' ) , 'photo' , 'date' )
                          -> get()
            ;

            return view( 'site.homepage' , compact( [ 'sliders' , 'services' , 'cars' , 'posts' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function about()
    {
        try
        {
            $site = self ::SITE();

            $posts = DB ::select( 'SELECT id , '
                                  . self ::LANG_PARAM( 'title' )
                                  . ' , ' . self ::LANG_PARAM( 'headline' )
                                  . ' , date , photo FROM ' . Post::TABLE . " WHERE is_deleted = 0 AND is_active = 1 AND type = 'blog' ORDER BY date DESC LIMIT 3 " );

            $missions = DB ::select( 'SELECT '
                                     . self ::LANG_PARAM( 'title' )
                                     . ' , photo FROM ' . Mission::TABLE . " WHERE is_deleted = 0 AND is_active = 1 ; " );

            return view( 'site.about' , compact( [ 'site' , 'posts' , 'missions' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function contact()
    {
        try
        {
            $site = self ::SITE();

            return view( 'site.contact' , compact( [ 'site' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }

    //KENAN
    public function callback( Request $request )
    {
        try
        {
            $validations = [
                'name'    => [ 'type' => 'string' , 'required' => true , 'max' => 55 ] ,
                'phone'   => [ 'type' => 'string' , 'required' => true , 'max' => 22 ] ,
                'city'    => [ 'type' => 'string' , 'required' => true , 'max' => 55 ] ,
                'service' => [ 'type' => 'numeric' , 'required' => true , 'exist' => Service::TABLE , 'db' => 'id' ]
            ];

            $validations = $this -> validator -> validateForm( $request , $validations );

            if( ! count( $validations ) )
            {
                $parameters = [
                    'name'       => $request -> request -> get( 'name' ) ,
                    'phone'      => $request -> request -> get( 'phone' ) ,
                    'city'       => $request -> request -> get( 'city' ) ,
                    'service_id' => $request -> request -> get( 'service' )
                ];

                $service = Service ::where( 'id' , $request -> request -> get( 'service' ) ) -> select( self ::LANG_PARAM( 'name' ) ) -> first();

                Mail ::send( 'mail.call-back' , [
                    'name'    => $request -> request -> get( 'name' ) ,
                    'phone'   => $request -> request -> get( 'phone' ) ,
                    'city'    => $request -> request -> get( 'city' ) ,
                    'service' => $service -> name
                ] , function( $message )
                {
                    $message -> to( 'info@166.az' )
                             -> subject( 'Call back' )
                    ;
                } );

                DB ::table( Callback::TABLE ) -> insert( $parameters );
            }

            return response() -> json( [ 'status' => 'success' , 'validations' => $validations ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }

    //KENAN
    public function subscribe( Request $request )
    {
        try
        {
            $validations = $this -> validator -> validateForm( $request , [ 'email' => [ 'type' => 'email' , 'required' => true , 'max' => 55 ] ] );

            if( ! count( $validations ) )
            {
                $email = $request -> request -> get( 'email' );

                $subscriber = Subscribe ::where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 ) -> where( 'email' , $email ) -> select( 'id' ) -> first();

                if( ! ( $subscriber && isset( $subscriber -> id ) ) ) DB ::table( Subscribe::TABLE ) -> insert( [ 'email' => $email ] );
            }

            return response() -> json( [ 'status' => 'success' , 'validations' => $validations ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public static function HEADLINE( $parameter )
    {
        return Site ::select( self ::LANG_PARAM( $parameter ) ) -> first() -> $parameter;
    }


    public static function BACKGROUND( $parameter )
    {
        $parameter = "{$parameter}_background";

        return media( 'site/' . Site ::select( $parameter ) -> first() -> $parameter );
    }


    public static function SITE()
    {
        return Site ::select(
            self ::LANG_PARAM( 'text' ) ,
            self ::LANG_PARAM( 'title' ) ,
            self ::LANG_PARAM( 'mission' ) ,
            self ::LANG_PARAM( 'address' ) ,
            self ::LANG_PARAM( 'corporate_contact' ) ,
            self ::LANG_PARAM( 'about_seo_keywords' ) ,
            self ::LANG_PARAM( 'about_seo_description' ) ,
            'index' ,
            'mobile' , 'email' ,
            'ad_mobile' , 'ad_email' ,
            'order_mobile' , 'order_email' ,
            'facebook' , 'instagram' , 'youtube' , 'linkedin' ,
            'transported_objects' , 'cleaned_places' , 'customer_reviews' , 'satisfied_customers' ,
            'background' , 'contact_background'
        ) -> first()
            ;
    }


    public static function ROUTE( $locale = 'en' )
    {
        $url = url() -> current();

        $currentLocale = Route ::current() ? Route ::current() -> parameter( 'locale' ) : App ::getLocale();

        if( $currentLocale ) $url = str_replace( "/$currentLocale" , "/$locale" , $url );

        else $url = str_replace( '/' . App ::getLocale() , "/$locale" , route( 'site.home' ) );

        return $url;
    }


    public function apply( Request $request )
    {
        try
        {
            $validations = $this -> validator -> validateForm(
                $request ,
                [
                    'name'    => [ 'type' => 'string' , 'required' => true , 'max' => 55 ] ,
                    'email'   => [ 'type' => 'email' , 'required' => true , 'max' => 55 ] ,
                    'subject' => [ 'type' => 'string' , 'required' => true , 'max' => 333 ] ,
                    'text'    => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ]
                ]
            );

            if( ! count( $validations ) )
                DB ::table( Application::TABLE )
                   -> insert(
                       [
                           'name'    => $request -> request -> get( 'name' ) ,
                           'email'   => $request -> request -> get( 'email' ) ,
                           'subject' => $request -> request -> get( 'subject' ) ,
                           'text'    => $request -> request -> get( 'text' )
                       ]
                   )
                ;

            return response() -> json( [ 'status' => 'success' , 'validations' => $validations ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
