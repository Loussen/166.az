<?php
/**
 * Created by PhpStorm.
 * User: Khayyam
 * Date: 10-jan-19
 * Time: 10:24
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExtraInfo;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExtraInfoController extends Controller
{
    public function showExtraInfoPage( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'service.extraInfo.view' ) ) return redirect() -> route( 'login' );

            $services = Service ::where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 ) -> whereNull( 'parent_id' ) -> select( 'id' , 'name_en AS name' ) -> get();

            return view( 'admin.extra-info' , compact( [ 'services' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getExtraInfoList( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'service.extraInfo.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $SELECT = ' SELECT c.id , title_en title , name_en `service` , c.is_active ';

            $FROM = ' FROM ' . ExtraInfo::TABLE . ' c JOIN ' . Service::TABLE . ' s ON c.service_id = s.id ';

            $WHERE = ' WHERE c.is_deleted = 0 AND s.is_deleted = 0 ';

            $filter = [
                'title'   => [ 'type' => 'search' , 'db' => [ 'title_en' , 'title_az' , 'title_ru' ] ] ,
                'service' => [ 'db' => 'service_id' ] ,
                'active'  => [ 'db' => 'c.is_active' ]
            ];

            return $this -> returnList( $request , $SELECT , $FROM , $WHERE , $filter , [ 'default' => 'c.created_at' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getExtraInfo( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'service.extraInfo.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $extraInfo = ExtraInfo ::where( 'id' , $id ) -> first();

            $extraInfo -> photo = media( 'service/' . $extraInfo -> photo );

            return response() -> json( [ 'status' => 'success' , 'data' => $extraInfo ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function editExtraInfo( Request $request )
    {
        try
        {
//            if( ! AdminController ::CAN( 'service.extraInfo.edit' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $validations = [
                'title_az' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                'title_en' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                'title_ru' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,

                'service' => [ 'type' => 'numeric' , 'required' => true , 'exist' => Service::TABLE , 'db' => 'id' ]
            ];

            $validations = $this -> validator -> validateForm( $request , $validations );

            if( ! count( $validations ) )
            {
                $extraInfo = ExtraInfo ::where( 'id' , $id ) -> first();

                $parameters = [
                    'title_en' => $request -> request -> get( 'title_en' ) ,
                    'title_az' => $request -> request -> get( 'title_az' ) ,
                    'title_ru' => $request -> request -> get( 'title_ru' ) ,

                    'service_id' => $request -> request -> get( 'service' )
                ];

                if( $extraInfo && is_object( $extraInfo ) ) DB ::table( ExtraInfo::TABLE ) -> where( 'id' , $id ) -> update( $parameters );

                else $id = DB ::table( ExtraInfo::TABLE ) -> insertGetId( $parameters );

                $this -> upload( $request , 'service' , ExtraInfo::TABLE , $id , [ 'photo' ] );
            }

            return response() -> json( [ 'status' => 'success' , 'validations' => $validations ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function activateExtraInfo( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'service.extraInfo.activate' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $active = $this -> validator -> validateBool( $request -> request -> get( 'active' ) );

            ExtraInfo ::where( 'id' , $id ) -> update( [ 'is_active' => $active ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function deleteExtraInfo( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'service.extraInfo.delete' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            ExtraInfo ::where( 'id' , $id ) -> update( [ 'is_deleted' => 1 ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
