<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\CarType;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function orderPage()
    {
        try
        {
            $services = ServiceController ::ALL_SERVICES();

            $carTypes = CarType ::where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 ) -> select( 'id' , self ::LANG_PARAM( 'name' ) ) -> get();

            return view( 'site.order' , compact( [ 'services' , 'carTypes' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function calculate( Request $request )
    {
        try
        {
            $services = [];

            $data = [ 'price' => 0 ];

            $validations = [
                'services' => [ 'type' => 'array' , 'required' => true ] ,
                'name'     => [ 'type' => 'string' , 'required' => true , 'max' => 55 ] ,
                'phone'    => [ 'type' => 'string' , 'required' => true , 'max' => 22 ]
            ];

            $validations = $this -> validator -> validateForm( $request , $validations );

            if( ! count( $validations ) )
            {
                foreach( $request -> request -> get( 'services' ) as $parent => $inputs )
                {
                    $parentData = Service ::where( 'id' , $parent ) -> select( 'price' , self ::LANG_PARAM( 'name' ) ) -> first();

                    $service = $inputs[ 'service' ] ?? '';

                    $serviceData = Service ::where( 'id' , $service ) -> select( 'price' , self ::LANG_PARAM( 'name' ) ) -> first();

                    $data[ 'price' ] += $services[ $parent ][ 'price' ] = $data[ 'services' ][ $parent ][ 'price' ] = ( isset( $parentData -> price ) ? $parentData -> price : 0 ) + ( isset( $serviceData -> price ) ? $serviceData -> price : 0 );
                }

                if( $request -> request -> get( 'order' ) == 1 )
                {
                    foreach( $request -> request -> get( 'services' ) as $parent => $inputs )
                    {
                        $parentData = Service ::where( 'id' , $parent ) -> select( 'price' , self ::LANG_PARAM( 'name' ) ) -> first();

                        $service = $inputs[ 'service' ] ?? '';

                        $serviceData = Service ::where( 'id' , $service ) -> select( 'price' , self ::LANG_PARAM( 'name' ) ) -> first();

                        $data[ 'price' ] += $data[ 'services' ][ $parent ][ 'price' ] = ( isset( $parentData -> price ) ? $parentData -> price : 0 ) + ( isset( $serviceData -> price ) ? $serviceData -> price : 0 );
                    }

                    Mail ::send( 'mail.order-admin' , [
                        'name'     => $request -> request -> get( 'name' ) ,
                        'phone'    => $request -> request -> get( 'phone' ) ,
                        'services' => $services ,
                        'price'    => $data[ 'price' ] ,
                    ] , function( $message )
                    {
                        $message -> to( env( 'EMAIL' ) )
                                 -> subject( 'New order' )
                        ;
                    } );

                    return response() -> json( [ 'status' => 'success' ] );
                }
            }

            return response() -> json( [ 'status' => 'success' , 'data' => $data , 'validations' => $validations ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
