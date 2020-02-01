<?php
/**
 * Created by PhpStorm.
 * User: Khayyam
 * Date: 09-Oct-19
 * Time: 17:43
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CarTypeController extends Controller
{
    public function showCarTypePage()
    {
        try
        {
            if( ! AdminController ::CAN( 'carType.view' ) ) return redirect() -> route( 'login' );

            return view( 'admin.car-type' );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getCarTypeList( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'carType.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $SELECT = " SELECT id , name_en `name` , is_active ";

            $FROM = " FROM " . CarType::TABLE;

            $WHERE = " WHERE is_deleted = 0 ";

            $filter = [
                'name'   => [ 'type' => 'search' , 'db' => [ 'name_en' , 'name_az' , 'name_ru' ] ] ,
                'active' => [ 'db' => 'is_active' ]
            ];

            return $this -> returnList( $request , $SELECT , $FROM , $WHERE , $filter , [] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getCarType( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'carType.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $type = CarType ::where( 'id' , $id ) -> first();

            return response() -> json( [ 'status' => 'success' , 'data' => $type ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function editCarType( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'carType.edit' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $validations = $this -> validator -> validateForm(
                $request ,
                [
                    'name_az' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                    'name_en' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                    'name_ru' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ]
                ]
            );

            if( ! count( $validations ) )
            {
                $type = CarType ::where( 'id' , $id ) -> first();

                $parameters = [
                    'name_en' => $request -> request -> get( 'name_en' ) ,
                    'name_az' => $request -> request -> get( 'name_az' ) ,
                    'name_ru' => $request -> request -> get( 'name_ru' )
                ];

                if( $type && is_object( $type ) )
                {
                    DB ::table( CarType::TABLE ) -> where( 'id' , $id ) -> update( $parameters );
                }
                else
                {
                    DB ::table( CarType::TABLE ) -> insert( $parameters );
                }
            }

            return response() -> json( [ 'status' => 'success' , 'validations' => $validations ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function activateCarType( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'carType.activate' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $active = $this -> validator -> validateBool( $request -> request -> get( 'active' ) );

            CarType ::where( 'id' , $id ) -> update( [ 'is_active' => $active ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function deleteCarType( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'carType.delete' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            CarType ::where( 'id' , $id ) -> update( [ 'is_deleted' => 1 ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
