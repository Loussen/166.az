<?php
/**
 * Created by PhpStorm.
 * User: Khayyam
 * Date: 10-jan-19
 * Time: 11:35
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MissionController extends Controller
{
    public function showMissionPage( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'site.mission.view' ) ) return redirect() -> route( 'login' );

            return view( 'admin.mission' );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getMissionList( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'site.mission.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $SELECT = ' SELECT id , title_en title , is_active ';

            $FROM = ' FROM ' . Mission::TABLE;

            $WHERE = ' WHERE is_deleted = 0 AND is_deleted = 0 ';

            $filter = [
                'title'  => [ 'type' => 'search' , 'db' => [ 'title_en' , 'title_az' , 'title_ru' ] ] ,
                'active' => [ 'db' => 'is_active' ]
            ];

            return $this -> returnList( $request , $SELECT , $FROM , $WHERE , $filter , [ 'default' => 'created_at' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getMission( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'site.mission.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $mission = Mission ::where( 'id' , $id ) -> first();

            $mission -> photo = media( 'site/' . $mission -> photo );

            return response() -> json( [ 'status' => 'success' , 'data' => $mission ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function editMission( Request $request )
    {
        try
        {
//            if( ! AdminController ::CAN( 'site.mission.edit' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $validations = [
                'title_az' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                'title_en' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                'title_ru' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ]
            ];

            $validations = $this -> validator -> validateForm( $request , $validations );

            if( ! count( $validations ) )
            {
                $mission = Mission ::where( 'id' , $id ) -> first();

                $parameters = [
                    'title_en' => $request -> request -> get( 'title_en' ) ,
                    'title_az' => $request -> request -> get( 'title_az' ) ,
                    'title_ru' => $request -> request -> get( 'title_ru' )
                ];

                if( $mission && is_object( $mission ) ) DB ::table( Mission::TABLE ) -> where( 'id' , $id ) -> update( $parameters );

                else $id = DB ::table( Mission::TABLE ) -> insertGetId( $parameters );

                $this -> upload( $request , 'site' , Mission::TABLE , $id , [ 'photo' ] );
            }

            return response() -> json( [ 'status' => 'success' , 'validations' => $validations ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function activateMission( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'site.mission.activate' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $active = $this -> validator -> validateBool( $request -> request -> get( 'active' ) );

            Mission ::where( 'id' , $id ) -> update( [ 'is_active' => $active ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function deleteMission( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'site.mission.delete' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            Mission ::where( 'id' , $id ) -> update( [ 'is_deleted' => 1 ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
