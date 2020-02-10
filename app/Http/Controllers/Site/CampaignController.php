<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\EconomCampaign;
use App\Models\EconomCampaignActivity;
use App\Models\EconomCampaignInclude;
use App\Models\HourlyCampaign;
use App\Models\HourlyCampaignActivity;
use App\Models\HourlyCampaignInclude;
use App\Models\HourlyCampaignAlert;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{
    public function campaignsPage( Request $request )
    {
        try
        {
            $economCampaigns = DB ::table( EconomCampaign::TABLE . ' AS c' )
                -> join( Service::TABLE . ' AS s' , 'c.service_id' , 's.id' )
                -> leftJoin( EconomCampaignInclude::TABLE . ' AS i' , 'c.id' , 'i.campaign_id' )
                -> where( 'c.is_deleted' , 0 ) -> where( 'c.is_active' , 1 )
                -> where( 's.is_deleted' , 0 ) -> where( 's.is_active' , 1 )
                -> groupBy( 'c.id' , self ::LANG_PARAM( 'title' , '' , false ) , self ::LANG_PARAM( 'individual_text' , '' , false ) , self ::LANG_PARAM( 'corporate_text' , '' , false ) , 'c.price' , 'service_id' , 'c.photo' )
                -> select( 'c.id' , self ::LANG_PARAM( 'title' ) , self ::LANG_PARAM( 'individual_text' ) , self ::LANG_PARAM( 'corporate_text' ) , 'c.price' , 'service_id' , DB ::raw( "GROUP_CONCAT( i.activity_id SEPARATOR ',' ) AS includes" ) , 'c.photo' )
                -> get()
            ;

            $economCampaignActivities = DB ::table( EconomCampaignActivity::TABLE . ' AS a' )
                -> join( Service::TABLE . ' AS s' , 'a.service_id' , 's.id' )
                -> where( 'a.is_deleted' , 0 ) -> where( 'a.is_active' , 1 )
                -> where( 's.is_deleted' , 0 ) -> where( 's.is_active' , 1 )
                -> select( 'a.id' , self ::LANG_PARAM( 'name' , 'a' ) , 'service_id' )
                -> get()
            ;

            foreach( $economCampaigns as $key => $economCampaign )
            {
                foreach( $economCampaignActivities as $economCampaignActivity )
                {
                    if( $economCampaign -> service_id == $economCampaignActivity -> service_id )
                    {
                        $economCampaigns[ $key ] -> activities[] = (object)[
                            'id'       => $economCampaignActivity -> id ,
                            'name'     => $economCampaignActivity -> name ,
                            'included' => in_array( $economCampaignActivity -> id , explode( ',' , $economCampaign -> includes ) )
                        ];
                    }
                }
            }


            $hourlyCampaigns = DB ::table( HourlyCampaign::TABLE . ' AS c' )
                -> join( Service::TABLE . ' AS s' , 'c.service_id' , 's.id' )
                -> leftJoin( HourlyCampaignInclude::TABLE . ' AS i' , 'c.id' , 'i.campaign_id' )
                -> where( 'c.is_deleted' , 0 ) -> where( 'c.is_active' , 1 )
                -> where( 's.is_deleted' , 0 ) -> where( 's.is_active' , 1 )
                -> groupBy( 'c.id' , self ::LANG_PARAM( 'individual_text' , '' , false ) , self ::LANG_PARAM( 'corporate_text' , '' , false ) , 'hour' , 'day_1_price' , 'day_2_price' , 'day_3_price' , 'service_id' )
                -> select( 'c.id' , self ::LANG_PARAM( 'individual_text' ) , self ::LANG_PARAM( 'corporate_text' ) , 'hour' , 'day_1_price' , 'day_2_price' , 'day_3_price' , 'service_id' , DB ::raw( "GROUP_CONCAT( i.activity_id SEPARATOR ',' ) AS includes" ) )
                -> get()
            ;

            $hourlyCampaignActivities = DB ::table( HourlyCampaignActivity::TABLE . ' AS a' )
                -> join( Service::TABLE . ' AS s' , 'a.service_id' , 's.id' )
                -> where( 'a.is_deleted' , 0 ) -> where( 'a.is_active' , 1 )
                -> where( 's.is_deleted' , 0 ) -> where( 's.is_active' , 1 )
                -> select( 'a.id' , self ::LANG_PARAM( 'name' , 'a' ) , 'service_id' )
                -> get()
            ;

            foreach( $hourlyCampaigns as $key => $hourlyCampaign )
            {
                foreach( $hourlyCampaignActivities as $hourlyCampaignActivity )
                {
                    if( $hourlyCampaign -> service_id == $hourlyCampaignActivity -> service_id )
                    {
                        $hourlyCampaigns[ $key ] -> activities[] = (object)[
                            'id'       => $hourlyCampaignActivity -> id ,
                            'name'     => $hourlyCampaignActivity -> name ,
                            'included' => in_array( $hourlyCampaignActivity -> id , explode( ',' , $hourlyCampaign -> includes ) )
                        ];

                        $hourlyCampaigns[ $key ] -> alerts = DB ::table( HourlyCampaignAlert::TABLE )
                            -> where( 'campaign_id' , $hourlyCampaign -> id )
                            -> select( 'id' , self ::LANG_PARAM( 'text' ) )
                            -> get()
                        ;
                    }
                }
            }

            return view( 'site.campaign.campaigns' , compact( [ 'economCampaigns' , 'hourlyCampaigns' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getCampaignList( Request $request )
    {
        try
        {
            $SELECT = ' SELECT c.id , ' . self ::LANG_PARAM( 'title' ) . ' , ' . self ::LANG_PARAM( 'individual_headline' , 'c' ) . ' , ' . self ::LANG_PARAM( 'corporate_headline' , 'c' ) . ' , input , c.photo ';

            $FROM = ' FROM ' . Campaign::TABLE . ' c JOIN ' . Service::TABLE . ' s ON c.service_id = s.id ';

            $WHERE = ' WHERE c.is_deleted = 0 AND c.is_active = 1 AND s.is_deleted = 0 AND s.is_active = 1 ';

            $filter = [
                'search' => [ 'type' => 'search' , 'db' => [ self ::LANG_PARAM( 'title' ) ] ]
            ];

            $campaigns = $this -> configureList( $request , $SELECT , $FROM , $WHERE , $filter , [ 'default' => 'c.start_date' ] );

            foreach( $campaigns[ 'data' ] as $key => $campaign )
            {
                $campaigns[ 'data' ][ $key ] -> photo = $this -> avatar( $campaign -> photo , 'campaign' );
            }

            return response() -> json( array_merge( [ 'status' => 'success' ] , $campaigns ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function campaignPage( $locale , $id = 0 )
    {
        try
        {
            $id = $this -> validator -> validateId( $id );

            $campaign = '';

            if( ! ( $campaign && isset( $campaign -> title ) ) ) return $this -> _404();

            $campaigns = [];

            return view( 'site.campaign.campaign' , compact( [ 'campaign' , 'campaigns' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
