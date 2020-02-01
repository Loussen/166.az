<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CarController extends Controller
{
    public function carsPage( $locale , $id = 0 )
    {
        try
        {
            $types = [];

            $cars = DB ::table( Car::TABLE . ' AS c' )
                       -> join( CarType::TABLE . ' AS t' , 'c.type_id' , 't.id' )
                       -> where( 'c.is_deleted' , 0 ) -> where( 'c.is_active' , 1 )
                       -> where( 't.is_deleted' , 0 ) -> where( 't.is_active' , 1 )
                       -> select( 'c.id' , self ::LANG_PARAM( 'title' ) , self ::LANG_PARAM( 'headline' ) , 'length' , 'height' , 'width' , 'palet' , 'weight' , 'photo' , 'palet_photo' , 'type_id' , self ::LANG_PARAM( 'name' ) )
                       -> get()
            ;

            foreach( $cars as $car )
            {
                if( ! isset( $types[ $car -> type_id ] ) ) $types[ $car -> type_id ] = [ 'id' => $car -> type_id , 'name' => $car -> name ];

                $types[ $car -> type_id ][ 'cars' ][] = $car;
            }

            return view( 'site.car.cars' , compact( [ 'types' , 'id' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function carPage( $locale , $id = 0 )
    {
        try
        {
            $id = $this -> validator -> validateId( $id );

            $car = DB ::table( Car::TABLE . ' AS c' )
                      -> join( CarType::TABLE . ' AS t' , 'c.type_id' , 't.id' )
                      -> where( 'c.is_deleted' , 0 ) -> where( 'c.is_active' , 1 )
                      -> where( 't.is_deleted' , 0 ) -> where( 't.is_active' , 1 )
                      -> where( 'c.id' , $id )
                      -> select(
                          self ::LANG_PARAM( 'title' ) ,
                          self ::LANG_PARAM( 'text' ) ,
                          'length' , 'height' , 'width' , 'palet' , 'weight' , 'photo' , 'background' , 'og_image' , 'palet_photo' , 'type_id' ,
                          self ::LANG_PARAM( 'seo_keywords' ) ,
                          self ::LANG_PARAM( 'seo_description' )
                      )
                      -> first()
            ;

            if( ! ( $car && isset( $car -> title ) ) ) return $this -> _404();

            $similarCars = DB ::table( Car::TABLE . ' AS c' )
                              -> join( CarType::TABLE . ' AS t' , 'c.type_id' , 't.id' )
                              -> where( 'c.is_deleted' , 0 ) -> where( 'c.is_active' , 1 )
                              -> where( 't.is_deleted' , 0 ) -> where( 't.is_active' , 1 )
                              -> where( 'c.type_id' , $car -> type_id )
                              -> where( 'c.id' , '<>' , $id )
                              -> select( 'c.id' , self ::LANG_PARAM( 'title' ) , self ::LANG_PARAM( 'headline' ) , 'length' , 'height' , 'width' , 'palet' , 'weight' , 'photo' , 'palet_photo' )
                              -> get()
            ;

            $types = DB ::table( Car::TABLE . ' AS c' )
                        -> join( CarType::TABLE . ' AS t' , 'c.type_id' , 't.id' )
                        -> where( 'c.is_deleted' , 0 ) -> where( 'c.is_active' , 1 )
                        -> where( 't.is_deleted' , 0 ) -> where( 't.is_active' , 1 )
                        -> groupBy( 't.id' , self ::LANG_PARAM( 'name' , '' , false ) )
                        -> select( 't.id' , self ::LANG_PARAM( 'name' ) )
                        -> get()
            ;

            return view( 'site.car.car' , compact( [ 'car' , 'types' , 'similarCars' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
