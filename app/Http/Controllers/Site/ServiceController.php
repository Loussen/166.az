<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Service\Validator;
use App\Models\Car;
use App\Models\CarType;
use App\Models\ExtraInfo;
use App\Models\Service;
use App\Models\ServiceInput;
use App\Models\ServiceInputOption;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function services( Request $request )
    {
        try
        {
            $search = $this -> clear( urldecode( $request -> query -> get( 'search' ) ) );

            $services = DB ::table( Service::TABLE . ' AS s' )
                           -> join( Service::TABLE . ' AS p' , 's.parent_id' , 'p.id' )
                           -> whereNull( 'p.parent_id' )
                           -> where( 's.is_deleted' , 0 ) -> where( 's.is_active' , 1 )
                           -> where( 'p.is_deleted' , 0 ) -> where( 'p.is_active' , 1 )
            ;

            if( $search )
            {
                $services = $services -> where( function( $sq ) use ( $search )
                {
                    $sq -> where( self ::LANG_PARAM( 'name' , 's' , false ) , 'LIKE' , "'%$search%'" )
                        -> orWhere( function( $sq_ ) use ( $search )
                        {
                            $sq_ -> where( self ::LANG_PARAM( 'individual_headline' , 's' , false ) , 'LIKE' , "'%$search%'" );
                        } )
                        -> orWhere( function( $sq_code ) use ( $search )
                        {
                            $sq_code -> where( self ::LANG_PARAM( 'corporate_headline' , 's' , false ) , 'LIKE' , "'%$search%'" );
                        } )
                        -> orWhere( function( $sq_m ) use ( $search )
                        {
                            $sq_m -> where( self ::LANG_PARAM( 'name' , 'p' , false ) , 'LIKE' , "'%$search%'" );
                        } )
                        -> orWhere( function( $sq_m_ ) use ( $search )
                        {
                            $sq_m_ -> where( self ::LANG_PARAM( 'individual_headline' , 'p' , false ) , 'LIKE' , "'%$search%'" );
                        } )
                        -> orWhere( function( $sq_c ) use ( $search )
                        {
                            $sq_c -> where( self ::LANG_PARAM( 'corporate_headline' , 'p' , false ) , 'LIKE' , "'%$search%'" );
                        } )
                    ;
                } );
            }

            $services = $services -> select( 'p.id' , self ::LANG_PARAM( 'name' , 'p' ) , self ::LANG_PARAM( 'individual_headline' , 'p' ) , self ::LANG_PARAM( 'corporate_headline' , 'p' ) , 'p.photo' )
                                  -> groupBy( 'p.id' , self ::LANG_PARAM( 'name' , 'p' , false ) , self ::LANG_PARAM( 'individual_headline' , 'p' , false ) , self ::LANG_PARAM( 'corporate_headline' , 'p' , false ) , 'p.photo' )
                                  -> get()
            ;

            $services = DB ::select(
                " SELECT
                            p.id,
                            " . self ::LANG_PARAM( 'name' , 'p' ) . ",
                            " . self ::LANG_PARAM( 'individual_headline' , 'p' ) . ",
                            " . self ::LANG_PARAM( 'corporate_headline' , 'p' ) . ",
                            p.photo
                        FROM
                            services AS p
                            LEFT JOIN services AS s ON p.id = s.parent_id
                        WHERE
                            p.parent_id IS NULL
                            AND p.is_deleted = 0 AND p.is_active = 1
                            AND (
                                " . self ::LANG_PARAM( 'name' , 'p' , false ) . " LIKE '%$search%'
                                OR ( " . self ::LANG_PARAM( 'individual_headline' , 'p' , false ) . " LIKE '%$search%' )
                                OR ( " . self ::LANG_PARAM( 'corporate_headline' , 'p' , false ) . " LIKE '%$search%' )
                                OR ( " . self ::LANG_PARAM( 'name' , 'p' , false ) . " LIKE '%$search%' )
                                OR ( " . self ::LANG_PARAM( 'individual_headline' , 'p' , false ) . " LIKE '%$search%' )
                                OR ( " . self ::LANG_PARAM( 'corporate_headline' , 'p' , false ) . " LIKE '%$search%' )
                            )
                        GROUP BY
                            p.id,
                            " . self ::LANG_PARAM( 'name' , 'p' , false ) . ",
                            " . self ::LANG_PARAM( 'individual_headline' , 'p' , false ) . ",
                            " . self ::LANG_PARAM( 'corporate_headline' , 'p' , false ) . ",
                            p.photo ;
                       "
            );

            return view( 'site.service.services' , compact( [ 'services' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function service( $locale , $id = 0 )
    {
        try
        {
            $id = $this -> validator -> validateId( $id );

            $service = DB ::table( Service::TABLE )
                          -> where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 )
                          -> where( 'id' , $id )
                          -> select(
                              self ::LANG_PARAM( 'name' ) ,
                              self ::LANG_PARAM( 'individual_headline' ) ,
                              self ::LANG_PARAM( 'corporate_headline' ) ,
                              self ::LANG_PARAM( 'individual_description' ) ,
                              self ::LANG_PARAM( 'corporate_description' ) ,
                              self ::LANG_PARAM( 'seo_keywords' ) ,
                              self ::LANG_PARAM( 'seo_description' ) ,
                              self ::LANG_PARAM( 'extra_info_headline' ) ,
                              'photo' , 'background' , 'og_image' , 'parent_id' , 'id' )
                          -> first()
            ;

            if( ! ( $service && isset( $service -> name ) ) ) return $this -> _404();

            $service -> children = DB ::select( " SELECT " . self ::LANG_PARAM( 'name' ) . " , " . self ::LANG_PARAM( 'individual_headline' ) . " , " . self ::LANG_PARAM( 'corporate_headline' ) . " , " . self ::LANG_PARAM( 'include_headline' ) . " , " . self ::LANG_PARAM( 'individual_description' ) . " , " . self ::LANG_PARAM( 'corporate_description' ) . " , photo , background , og_image , id FROM " . Service::TABLE . " WHERE is_deleted = 0 AND is_active = 1 AND parent_id = $id " );

            if( is_array( $service -> children ) && count( $service -> children ) )
            {
                foreach( $service -> children as $k => $child )
                {
                    $service -> children[ $k ] -> includes = DB ::select( " SELECT " . self ::LANG_PARAM( 'name' ) . " , " . self ::LANG_PARAM( 'individual_headline' ) . " , " . self ::LANG_PARAM( 'corporate_headline' ) . " , photo FROM " . Service::TABLE . " WHERE is_deleted = 0 AND is_active = 1 AND parent_id = " . $child -> id );
                }
            }

            $cars = false;

            if( $id == 1 || $service -> parent_id == 1 )
                $cars = DB ::table( Car::TABLE . ' AS c' )
                           -> join( CarType::TABLE . ' AS t' , 'c.type_id' , 't.id' )
                           -> where( 'c.is_deleted' , 0 ) -> where( 'c.is_active' , 1 )
                           -> where( 't.is_deleted' , 0 ) -> where( 't.is_active' , 1 )
                           -> select( 'c.id' , self ::LANG_PARAM( 'title' ) , self ::LANG_PARAM( 'headline' ) , 'length' , 'height' , 'width' , 'palet' , 'weight' , 'photo' )
                           -> get()
                ;

            $service -> extra = DB ::select( " SELECT " . self ::LANG_PARAM( 'title' ) . " , photo FROM " . ExtraInfo::TABLE . " WHERE is_deleted = 0 AND is_active = 1 AND service_id = $id " );

            return view( 'site.service.service' , compact( [ 'service' , 'cars' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public static function ALL_SERVICES()
    {
        return static ::BUILD_TREE( Service ::where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 ) -> select( 'id' , 'parent_id' , static ::LANG_PARAM( 'name' ) ) -> get() );
    }


    public function getServiceInputs( Request $request )
    {
        $validator = new Validator();

        $id = $validator -> validateId( $request -> request -> get( 'service' ) );

        $serviceInputs = ServiceInput ::where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 ) -> where( 'service_id' , $id ) -> select( 'id' , 'type' , 'step' , static ::LANG_PARAM( 'name' ) ) -> get();

        foreach( $serviceInputs as $k => $input )
            if( $input -> type == 'select' )
                $serviceInputs[ $k ] -> options = ServiceInputOption ::where( 'input_id' , $input -> id ) -> select( 'id' , static ::LANG_PARAM( 'name' ) ) -> get();

        $services = Service ::where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 ) -> where( 'parent_id' , $id ) -> select( 'id' , self ::LANG_PARAM( 'name' ) ) -> get();

        return response() -> json( [ 'status' => 'success' , 'data' => $serviceInputs , 'children' => $services ] );
    }
}
