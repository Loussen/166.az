<?php
/**
 * Created by PhpStorm.
 * User: Khayyam
 * Date: 10-Oct-19
 * Time: 17:25
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EconomCampaign;
use App\Models\EconomCampaignActivity;
use App\Models\EconomCampaignInclude;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EconomCampaignController extends Controller
{
    public function showEconomCampaignPage( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'economCampaign.view' ) ) return redirect() -> route( 'login' );

            $services = Service ::where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 ) -> whereNull( 'parent_id' ) -> select( 'id' , 'name_en AS name' ) -> get();

            return view( 'admin.econom-campaign' , compact( [ 'services' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getEconomCampaignList( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'economCampaign.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $SELECT = ' SELECT c.id , title_en title , name_en `service` , c.is_active ';

            $FROM = ' FROM ' . EconomCampaign::TABLE . ' c JOIN ' . Service::TABLE . ' s ON c.service_id = s.id ';

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


    public function getEconomCampaign( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'economCampaign.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $campaign = EconomCampaign ::where( 'id' , $id ) -> first();

            $campaign -> activities = $this -> getEconomCampaignActivities( $id , $campaign -> service_id );

            return response() -> json( [ 'status' => 'success' , 'data' => $campaign ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function editEconomCampaign( Request $request )
    {
        try
        {
//            if( ! AdminController ::CAN( 'economCampaign.edit' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $validations = [
                'title_az'            => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                'title_en'            => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                'title_ru'            => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                'individual_text_en'  => [ 'type' => 'string' , 'required' => true , 'max' => 55555 ] ,
                'individual_text_az'  => [ 'type' => 'string' , 'required' => true , 'max' => 55555 ] ,
                'individual_text_ru'  => [ 'type' => 'string' , 'required' => true , 'max' => 55555 ] ,
                'corporate_text_en' => [ 'type' => 'string' , 'required' => true , 'max' => 55555 ] ,
                'corporate_text_az' => [ 'type' => 'string' , 'required' => true , 'max' => 55555 ] ,
                'corporate_text_ru' => [ 'type' => 'string' , 'required' => true , 'max' => 55555 ] ,
                'price'               => [ 'type' => 'string' , 'required' => true , 'max' => 11 ] ,
                'service'             => [ 'type' => 'numeric' , 'required' => true , 'exist' => Service::TABLE , 'db' => 'id' ]
            ];

            $validations = $this -> validator -> validateForm( $request , $validations );

            if( ! count( $validations ) )
            {
                $campaign = EconomCampaign ::where( 'id' , $id ) -> first();

                $parameters = [
                    'title_en'            => $request -> request -> get( 'title_en' ) ,
                    'title_az'            => $request -> request -> get( 'title_az' ) ,
                    'title_ru'            => $request -> request -> get( 'title_ru' ) ,
                    'individual_text_en'  => $request -> request -> get( 'individual_text_en' ) ,
                    'individual_text_az'  => $request -> request -> get( 'individual_text_az' ) ,
                    'individual_text_ru'  => $request -> request -> get( 'individual_text_ru' ) ,
                    'corporate_text_en' => $request -> request -> get( 'corporate_text_en' ) ,
                    'corporate_text_az' => $request -> request -> get( 'corporate_text_az' ) ,
                    'corporate_text_ru' => $request -> request -> get( 'corporate_text_ru' ) ,
                    'price'               => $request -> request -> get( 'price' ) ,
                    'service_id'          => $request -> request -> get( 'service' )
                ];

                if( $campaign && is_object( $campaign ) )
                {
                    DB ::table( EconomCampaign::TABLE ) -> where( 'id' , $id ) -> update( $parameters );
                }
                else
                {
                    $id = DB ::table( EconomCampaign::TABLE ) -> insertGetId( $parameters );
                }

                $activities = explode( ',' , $request -> request -> get( 'activities' ) );

                EconomCampaignInclude ::where( 'campaign_id' , $id ) -> delete();

                if( is_array( $activities ) )
                {
                    foreach( $activities as $activity )
                    {
                        $activity = $this -> validator -> validateId( $activity );

                        if( $activity ) DB ::table( EconomCampaignInclude::TABLE ) -> insert( [ 'campaign_id' => $id , 'activity_id' => $activity ] );
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


    public function activateEconomCampaign( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'economCampaign.activate' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $active = $this -> validator -> validateBool( $request -> request -> get( 'active' ) );

            EconomCampaign ::where( 'id' , $id ) -> update( [ 'is_active' => $active ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function deleteEconomCampaign( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'economCampaign.delete' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            EconomCampaign ::where( 'id' , $id ) -> update( [ 'is_deleted' => 1 ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getEconomCampaignIncludes( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'campaign.edit' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $campaign = $this -> validator -> validateId( $request -> request -> get( 'campaign' ) );

            $service = $this -> validator -> validateId( $request -> request -> get( 'service' ) );

            return response() -> json( [ 'status' => 'success' , 'data' => $this -> getEconomCampaignActivities( $campaign , $service ) ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    private function getEconomCampaignActivities( $campaign , $service )
    {
        $included = [];

        $includes = EconomCampaignInclude ::where( 'campaign_id' , $campaign ) -> get();

        foreach( $includes as $include ) $included[ $include -> activity_id ] = 1;

        $activities = EconomCampaignActivity ::where( 'service_id' , $service ) -> where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 ) -> select( 'id' , 'name_en AS name' ) -> get();

        foreach( $activities as $key => $activity ) $activities[ $key ] -> included = intval( isset( $included[ $activity -> id ] ) );

        return $activities;
    }
}
