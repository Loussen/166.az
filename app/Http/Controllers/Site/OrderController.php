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
            var_dump($_POST); exit;
            $service = $request -> request -> getInt( 'service' );

            $data = [];

            $validations = [
                'service' => [ 'type' => 'numeric' , 'required' => true , 'exist' => Service::TABLE , 'db' => 'id' ] ,
                'name'    => [ 'type' => 'string' , 'required' => true , 'max' => 55 ] ,
                'phone'   => [ 'type' => 'string' , 'required' => true , 'max' => 22 ]
            ];

            $validations = $this -> validator -> validateForm( $request , $validations );

            if( ! count( $validations ) )
            {

            }

            return response() -> json( [ 'status' => 'success' , 'data' => $data , 'validations' => $validations ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function order( Request $request )
    {
        try
        {
            $parent = Service ::where( 'id' , $request -> request -> get( 'parent' ) ) -> select( self ::LANG_PARAM( 'name' ) ) -> first();

            $service = Service ::where( 'id' , $request -> request -> get( 'service' ) ) -> select( self ::LANG_PARAM( 'name' ) ) -> first();

//            Mail ::send( 'mail.order-admin' , [
//                'name'      => $request -> request -> get( 'name' ) ,
//                'phone'     => $request -> request -> get( 'phone' ) ,
//                'address_1' => $request -> request -> get( 'address_1' ) ,
//                'address_2' => $request -> request -> get( 'address_2' ) ,
//                'date'      => $request -> request -> get( 'date' ) ,
//                'hour'      => $request -> request -> get( 'hour' ) ,
//                'parent'    => $parent -> name ,
//                'service'   => isset( $service -> name ) ? $service -> name : null ,
//            ] , function( $message )
//            {
//                $message -> to( env( 'EMAIL' ) )
//                         -> subject( 'New order' )
//                ;
//            } );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
