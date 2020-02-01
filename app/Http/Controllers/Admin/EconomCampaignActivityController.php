<?php
/**
 * Created by PhpStorm.
 * User: Khayyam
 * Date: 10-Oct-19
 * Time: 17:05
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EconomCampaignActivity;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EconomCampaignActivityController extends Controller
{
    public function showEconomCampaignActivityPage()
    {
        try
        {
            if( ! AdminController ::CAN( 'economCampaignActivity.view' ) ) return redirect() -> route( 'login' );

            $services = Service ::where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 ) -> whereNull( 'parent_id' ) -> select( 'id' , 'name_en AS name' ) -> get();

            return view( 'admin.econom-campaign-activity' , compact( [ 'services' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getEconomCampaignActivityList( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'economCampaignActivity.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $SELECT = ' SELECT a.id , a.name_en `name` , s.name_en `service` , a.is_active ';

            $FROM = ' FROM ' . EconomCampaignActivity::TABLE . ' a JOIN ' . Service::TABLE . ' s ON a.service_id = s.id ';

            $WHERE = ' WHERE a.is_deleted = 0 AND s.is_deleted = 0 ';

            $filter = [
                'name'    => [ 'type' => 'search' , 'db' => [ 'a.name_en' , 'a.name_az' , 'a.name_ru' ] ] ,
                'service' => [ 'db' => 'service_id' ] ,
                'active'  => [ 'db' => 'a.is_active' ]
            ];

            return $this -> returnList( $request , $SELECT , $FROM , $WHERE , $filter , [] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getEconomCampaignActivity( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'economCampaignActivity.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $activity = EconomCampaignActivity ::where( 'id' , $id ) -> first();

            return response() -> json( [ 'status' => 'success' , 'data' => $activity ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function editEconomCampaignActivity( Request $request )
    {
        try
        {
//            if( ! AdminController ::CAN( 'economCampaignActivity.edit' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $validations = $this -> validator -> validateForm(
                $request ,
                [
                    'name_az' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                    'name_en' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                    'name_ru' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                    'service' => [ 'type' => 'numeric' , 'required' => true , 'exist' => Service::TABLE , 'db' => 'id' ]
                ]
            );

            if( ! count( $validations ) )
            {
                $activity = EconomCampaignActivity ::where( 'id' , $id ) -> first();

                $parameters = [
                    'name_en'    => $request -> request -> get( 'name_en' ) ,
                    'name_az'    => $request -> request -> get( 'name_az' ) ,
                    'name_ru'    => $request -> request -> get( 'name_ru' ) ,
                    'service_id' => $request -> request -> get( 'service' )
                ];

                if( $activity && is_object( $activity ) )
                {
                    DB ::table( EconomCampaignActivity::TABLE ) -> where( 'id' , $id ) -> update( $parameters );
                }
                else
                {
                    DB ::table( EconomCampaignActivity::TABLE ) -> insert( $parameters );
                }
            }

            return response() -> json( [ 'status' => 'success' , 'validations' => $validations ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function activateEconomCampaignActivity( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'economCampaignActivity.activate' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $active = $this -> validator -> validateBool( $request -> request -> get( 'active' ) );

            EconomCampaignActivity ::where( 'id' , $id ) -> update( [ 'is_active' => $active ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function deleteEconomCampaignActivity( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'economCampaignActivity.delete' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            EconomCampaignActivity ::where( 'id' , $id ) -> update( [ 'is_deleted' => 1 ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
