<?php
/**
 * Created by PhpStorm.
 * User: Khayyam
 * Date: 11-Oct-19
 * Time: 11:12
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HourlyCampaign;
use App\Models\HourlyCampaignActivity;
use App\Models\HourlyCampaignAlert;
use App\Models\HourlyCampaignInclude;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HourlyCampaignController extends Controller
{
    public function showHourlyCampaignPage( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'hourlyCampaign.view' ) ) return redirect() -> route( 'login' );

            $services = Service ::where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 ) -> whereNull( 'parent_id' ) -> select( 'id' , 'name_en AS name' ) -> get();

            return view( 'admin.hourly-campaign' , compact( [ 'services' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getHourlyCampaignList( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'hourlyCampaign.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $SELECT = ' SELECT c.id , hour , name_en `service` , c.is_active ';

            $FROM = ' FROM ' . HourlyCampaign::TABLE . ' c JOIN ' . Service::TABLE . ' s ON c.service_id = s.id ';

            $WHERE = ' WHERE c.is_deleted = 0 AND s.is_deleted = 0 ';

            $filter = [
                'hour'    => [ 'db' => 'hour' ] ,
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


    public function getHourlyCampaign( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'hourlyCampaign.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $campaign = HourlyCampaign ::where( 'id' , $id ) -> first();

            $campaign -> activities = $this -> getHourlyCampaignActivities( $id , $campaign -> service_id );

            $campaign -> alerts = HourlyCampaignAlert ::where( 'campaign_id' , $id ) -> get();

            return response() -> json( [ 'status' => 'success' , 'data' => $campaign ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function editHourlyCampaign( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'hourlyCampaign.edit' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $validations = [
                'hour'                 => [ 'type' => 'numeric' , 'required' => true ] ,
                'individual_text_en'   => [ 'type' => 'string' , 'required' => true , 'max' => 55555 ] ,
                'individual_text_az'   => [ 'type' => 'string' , 'required' => true , 'max' => 55555 ] ,
                'individual_text_ru'   => [ 'type' => 'string' , 'required' => true , 'max' => 55555 ] ,
                'corporate_text_en' => [ 'type' => 'string' , 'required' => true , 'max' => 55555 ] ,
                'corporate_text_az' => [ 'type' => 'string' , 'required' => true , 'max' => 55555 ] ,
                'corporate_text_ru' => [ 'type' => 'string' , 'required' => true , 'max' => 55555 ] ,
                'day_1_price'          => [ 'type' => 'string' , 'required' => true , 'max' => 11 ] ,
                'day_2_price'          => [ 'type' => 'string' , 'required' => true , 'max' => 11 ] ,
                'day_3_price'          => [ 'type' => 'string' , 'required' => true , 'max' => 11 ] ,
                'service'              => [ 'type' => 'numeric' , 'required' => true , 'exist' => Service::TABLE , 'db' => 'id' ]
            ];

            $validations = $this -> validator -> validateForm( $request , $validations );

            if( ! count( $validations ) )
            {
                $campaign = HourlyCampaign ::where( 'id' , $id ) -> first();

                $parameters = [
                    'hour'                 => $request -> request -> get( 'hour' ) ,
                    'individual_text_en'   => $request -> request -> get( 'individual_text_en' ) ,
                    'individual_text_az'   => $request -> request -> get( 'individual_text_az' ) ,
                    'individual_text_ru'   => $request -> request -> get( 'individual_text_ru' ) ,
                    'corporate_text_en' => $request -> request -> get( 'corporate_text_en' ) ,
                    'corporate_text_az' => $request -> request -> get( 'corporate_text_az' ) ,
                    'corporate_text_ru' => $request -> request -> get( 'corporate_text_ru' ) ,
                    'day_1_price'          => $request -> request -> get( 'day_1_price' ) ,
                    'day_2_price'          => $request -> request -> get( 'day_2_price' ) ,
                    'day_3_price'          => $request -> request -> get( 'day_3_price' ) ,
                    'service_id'           => $request -> request -> get( 'service' )
                ];

                if( $campaign && is_object( $campaign ) )
                {
                    DB ::table( HourlyCampaign::TABLE ) -> where( 'id' , $id ) -> update( $parameters );
                }
                else
                {
                    $id = DB ::table( HourlyCampaign::TABLE ) -> insertGetId( $parameters );
                }


                $activities = explode( ',' , $request -> request -> get( 'activities' ) );

                HourlyCampaignInclude ::where( 'campaign_id' , $id ) -> delete();

                if( is_array( $activities ) )
                {
                    foreach( $activities as $activity )
                    {
                        $activity = $this -> validator -> validateId( $activity );

                        if( $activity ) DB ::table( HourlyCampaignInclude::TABLE ) -> insert( [ 'campaign_id' => $id , 'activity_id' => $activity ] );
                    }
                }


                $alerts = $request -> request -> get( 'alerts' );

                HourlyCampaignAlert ::where( 'campaign_id' , $id ) -> delete();

                if( is_array( $alerts ) )
                {
                    foreach( $alerts as $alert )
                    {
                        DB ::table( HourlyCampaignAlert::TABLE ) -> insert(
                            [
                                'text_en'     => $alert[ 'text_en' ] ?? '' ,
                                'text_az'     => $alert[ 'text_az' ] ?? '' ,
                                'text_ru'     => $alert[ 'text_ru' ] ?? '' ,
                                'campaign_id' => $id
                            ]
                        )
                        ;
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


    public function activateHourlyCampaign( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'hourlyCampaign.activate' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $active = $this -> validator -> validateBool( $request -> request -> get( 'active' ) );

            HourlyCampaign ::where( 'id' , $id ) -> update( [ 'is_active' => $active ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function deleteHourlyCampaign( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'hourlyCampaign.delete' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            HourlyCampaign ::where( 'id' , $id ) -> update( [ 'is_deleted' => 1 ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getHourlyCampaignIncludes( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'hourlyCampaign.edit' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $campaign = $this -> validator -> validateId( $request -> request -> get( 'campaign' ) );

            $service = $this -> validator -> validateId( $request -> request -> get( 'service' ) );

            return response() -> json( [ 'status' => 'success' , 'data' => $this -> getHourlyCampaignActivities( $campaign , $service ) ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    private function getHourlyCampaignActivities( $campaign , $service )
    {
        $included = [];

        $includes = HourlyCampaignInclude ::where( 'campaign_id' , $campaign ) -> get();

        foreach( $includes as $include ) $included[ $include -> activity_id ] = 1;

        $activities = HourlyCampaignActivity ::where( 'service_id' , $service ) -> where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 ) -> select( 'id' , 'name_en AS name' ) -> get();

        foreach( $activities as $key => $activity ) $activities[ $key ] -> included = intval( isset( $included[ $activity -> id ] ) );

        return $activities;
    }
}
