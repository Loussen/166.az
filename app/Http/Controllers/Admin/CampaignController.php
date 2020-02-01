<?php
/**
 * Created by PhpStorm.
 * User: Khayyam
 * Date: 09-Oct-19
 * Time: 23:01
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignActivity;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{
    public function showCampaignPage( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'campaign.view' ) ) return redirect() -> route( 'login' );

            $services = Service ::where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 ) -> whereNull( 'parent_id' ) -> select( 'id' , 'name_en AS name' ) -> get();

            return view( 'admin.campaign' , compact( [ 'services' ] ) );
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
            if( ! AdminController ::CAN( 'campaign.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $SELECT = ' SELECT c.id , title_en title , start_date , end_date , name_en `service` , c.is_active ';

            $FROM = ' FROM ' . Campaign::TABLE . ' c JOIN ' . Service::TABLE . ' s ON c.service_id = s.id ';

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


    public function getCampaign( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'campaign.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $campaign = Campaign ::where( 'id' , $id ) -> first();

            $campaign -> photo = media( 'campaign/' . $campaign -> photo );

            $campaign -> activities = CampaignActivity ::where( 'campaign_id' , $id ) -> get();

            return response() -> json( [ 'status' => 'success' , 'data' => $campaign ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function editCampaign( Request $request )
    {
        try
        {
//            if( ! AdminController ::CAN( 'campaign.edit' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $validations = [
                'title_az' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                'title_en' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                'title_ru' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,

                'individual_headline_en' => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,
                'individual_headline_az' => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,
                'individual_headline_ru' => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,

                'corporate_headline_en' => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,
                'corporate_headline_az' => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,
                'corporate_headline_ru' => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,

                'individual_text_en' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                'individual_text_az' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                'individual_text_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,

                'corporate_text_en' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                'corporate_text_az' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                'corporate_text_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,

                'start_date' => [ 'type' => 'date' , 'required' => true ] ,
                'end_date'   => [ 'type' => 'date' , 'required' => true ] ,
                'price'      => [ 'type' => 'string' , 'required' => true , 'max' => 11 ] ,
                'input'      => [ 'type' => 'string' , 'required' => true , 'max' => 11 , 'array' => [ 'email' , 'phone' ] ] ,
                'service'    => [ 'type' => 'numeric' , 'required' => true , 'exist' => Service::TABLE , 'db' => 'id' ]
            ];

            $validations = $this -> validator -> validateForm( $request , $validations );

            if( ! count( $validations ) )
            {
                $campaign = Campaign ::where( 'id' , $id ) -> first();

                $parameters = [
                    'title_en'               => $request -> request -> get( 'title_en' ) ,
                    'title_az'               => $request -> request -> get( 'title_az' ) ,
                    'title_ru'               => $request -> request -> get( 'title_ru' ) ,
                    'individual_headline_en' => $request -> request -> get( 'individual_headline_en' ) ,
                    'individual_headline_az' => $request -> request -> get( 'individual_headline_az' ) ,
                    'individual_headline_ru' => $request -> request -> get( 'individual_headline_ru' ) ,
                    'corporate_headline_en'  => $request -> request -> get( 'corporate_headline_en' ) ,
                    'corporate_headline_az'  => $request -> request -> get( 'corporate_headline_az' ) ,
                    'corporate_headline_ru'  => $request -> request -> get( 'corporate_headline_ru' ) ,
                    'individual_text_en'     => $request -> request -> get( 'individual_text_en' ) ,
                    'individual_text_az'     => $request -> request -> get( 'individual_text_az' ) ,
                    'individual_text_ru'     => $request -> request -> get( 'individual_text_ru' ) ,
                    'corporate_text_en'      => $request -> request -> get( 'corporate_text_en' ) ,
                    'corporate_text_az'      => $request -> request -> get( 'corporate_text_az' ) ,
                    'corporate_text_ru'      => $request -> request -> get( 'corporate_text_ru' ) ,
                    'start_date'             => $request -> request -> get( 'start_date' ) ,
                    'end_date'               => $request -> request -> get( 'end_date' ) ,
                    'price'                  => $request -> request -> get( 'price' ) ,
                    'input'                  => $request -> request -> get( 'input' ) ,
                    'service_id'             => $request -> request -> get( 'service' )
                ];

                if( $campaign && is_object( $campaign ) )
                {
                    DB ::table( Campaign::TABLE ) -> where( 'id' , $id ) -> update( $parameters );
                }
                else
                {
                    $id = DB ::table( Campaign::TABLE ) -> insertGetId( $parameters );
                }

                $this -> upload( $request , 'campaign' , Campaign::TABLE , $id , [ [ 'photo' ] ] );


                $activities = $request -> request -> get( 'activities' );

                CampaignActivity ::where( 'campaign_id' , $id ) -> delete();

                if( is_array( $activities ) )
                {
                    foreach( $activities as $activity )
                    {
                        DB ::table( CampaignActivity::TABLE ) -> insert(
                            [
                                'name_en'     => $activity[ 'name_en' ] ?? '' ,
                                'name_az'     => $activity[ 'name_az' ] ?? '' ,
                                'name_ru'     => $activity[ 'name_ru' ] ?? '' ,
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


    public function activateCampaign( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'campaign.activate' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $active = $this -> validator -> validateBool( $request -> request -> get( 'active' ) );

            Campaign ::where( 'id' , $id ) -> update( [ 'is_active' => $active ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function deleteCampaign( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'campaign.delete' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            Campaign ::where( 'id' , $id ) -> update( [ 'is_deleted' => 1 ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
