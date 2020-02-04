<?php
/**
 * Created by PhpStorm.
 * User: Khayyam
 * Date: 16-Dec-19
 * Time: 05:01
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceInput;
use App\Models\ServiceInputOption;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceInputController extends Controller
{
    public function showServiceInputPage( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'serviceInput.view' ) ) return redirect() -> route( 'login' );

            $services = DB ::table( Service::TABLE . ' as s' )
                           -> leftJoin( Service::TABLE . ' AS p' , 's.parent_id' , 'p.id' )
                           -> where( 's.is_deleted' , 0 ) -> where( 's.is_active' , 1 )
                           -> whereNull( 'p.parent_id' )
                           -> select( 's.id' , 's.name_en AS name' ) -> get()
            ;

//            $services = Service ::where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 ) -> whereNull( 'parent_id' ) -> select( 'id' , 'name_en AS name' ) -> get();

            return view( 'admin.service-input' , compact( [ 'services' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getServiceInputList( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'serviceInput.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $SELECT = ' SELECT i.id , i.name_en name , s.name_en `service` , i.is_active ';

            $FROM = ' FROM ' . ServiceInput::TABLE . ' i JOIN ' . Service::TABLE . ' s ON i.service_id = s.id ';

            $WHERE = ' WHERE i.is_deleted = 0 AND s.is_deleted = 0 ';

            $filter = [
                'name'    => [ 'type' => 'search' , 'db' => [ 'i.name_en' , 'i.name_az' , 'i.name_ru' ] ] ,
                'service' => [ 'db' => 'service_id' ] ,
                'active'  => [ 'db' => 'i.is_active' ]
            ];

            return $this -> returnList( $request , $SELECT , $FROM , $WHERE , $filter , [ 'default' => 'i.created_at' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getServiceInput( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'serviceInput.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $input = ServiceInput ::where( 'id' , $id ) -> first();

            $input -> options = ServiceInputOption ::where( 'input_id' , $id ) -> get();

            return response() -> json( [ 'status' => 'success' , 'data' => $input ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function editServiceInput( Request $request )
    {
        try
        {
//            if( ! AdminController ::CAN( 'serviceInput.edit' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $validations = [
                'name_az' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                'name_en' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                'name_ru' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,

                'type' => [ 'type' => 'string' , 'required' => true , 'array' => array_keys( ServiceInput::TYPES ) ] ,

                'step' => [ 'type' => 'numeric' , 'required' => true , 'array' => ServiceInput::STEPS ] ,

                'coefficient' => [ 'type' => 'numeric', 'required' => false ] ,

                'service' => [ 'type' => 'numeric' , 'required' => true , 'exist' => Service::TABLE , 'db' => 'id' ]
            ];

            $validations = $this -> validator -> validateForm( $request , $validations );

            if( ! count( $validations ) )
            {
                $input = ServiceInput ::where( 'id' , $id ) -> first();

                $type = $request -> request -> get( 'type' );

                $parameters = [
                    'name_en' => $request -> request -> get( 'name_en' ) ,
                    'name_az' => $request -> request -> get( 'name_az' ) ,
                    'name_ru' => $request -> request -> get( 'name_ru' ) ,

                    'type' => $type ,

                    'step' => $request -> request -> get( 'step' ) ,

                    'coefficient' => $request -> request -> get( 'coefficient' ) ,

                    'service_id' => $request -> request -> get( 'service' )
                ];

                if( $input && is_object( $input ) ) DB ::table( ServiceInput::TABLE ) -> where( 'id' , $id ) -> update( $parameters );

                else $id = DB ::table( ServiceInput::TABLE ) -> insertGetId( $parameters );

                if( $type == 'select' )
                {
                    $options = $request -> request -> get( 'options' );

                    ServiceInputOption ::where( 'input_id' , $id ) -> delete();

                    if( is_array( $options ) )
                    {
                        foreach( $options as $option )
                        {
                            DB ::table( ServiceInputOption::TABLE ) -> insert(
                                [
                                    'name_en'  => $option[ 'name_en' ] ?? '' ,
                                    'name_az'  => $option[ 'name_az' ] ?? '' ,
                                    'name_ru'  => $option[ 'name_ru' ] ?? '' ,
                                    'coefficient'  => $option[ 'coefficient' ] ?? '' ,
                                    'input_id' => $id
                                ]
                            )
                            ;
                        }
                    }
                }
            }

            return response() -> json( [ 'status' => 'success' , 'validations' => $validations ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function activateServiceInput( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'serviceInput.activate' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $active = $this -> validator -> validateBool( $request -> request -> get( 'active' ) );

            ServiceInput ::where( 'id' , $id ) -> update( [ 'is_active' => $active ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function deleteServiceInput( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'serviceInput.delete' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            ServiceInput ::where( 'id' , $id ) -> update( [ 'is_deleted' => 1 ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
