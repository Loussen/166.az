<?php
/**
 * Created by PhpStorm.
 * User: Khayyam
 * Date: 17-Nov-19
 * Time: 12:52
 */

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use App\Models\AdminRole;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SiteController extends Controller
{
    public function showSitePage()
    {
        try
        {
            if( ! AdminController ::CAN( 'site.view' ) ) return redirect() -> route( 'login' );

            return view( 'admin.site' , [ 'site' => Site ::first() ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function editSiteInfo( Request $request )
    {
        try
        {
            $validations = [
                1 => [
                    'title_en' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'title_az' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'title_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,

                    'text_en' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'text_az' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'text_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,

                    'mission_en' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'mission_az' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'mission_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,

                    'transported_objects' => [ 'type' => 'numeric' , 'required' => false ] ,
                    'cleaned_places'      => [ 'type' => 'numeric' , 'required' => false ] ,
                    'customer_reviews'    => [ 'type' => 'numeric' , 'required' => false ] ,
                    'satisfied_customers' => [ 'type' => 'numeric' , 'required' => false ]
                ] ,
                2 => [
                    'address_en' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'address_az' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'address_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,

                    'corporate_contact_en' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'corporate_contact_az' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'corporate_contact_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,

                    'index' => [ 'type' => 'string' , 'required' => false , 'max' => 22 ] ,

                    'mobile' => [ 'type' => 'string' , 'required' => false , 'max' => 22 ] ,
                    'email'  => [ 'type' => 'string' , 'required' => false , 'max' => 55 ] ,

                    'ad_mobile' => [ 'type' => 'string' , 'required' => false , 'max' => 22 ] ,
                    'ad_email'  => [ 'type' => 'string' , 'required' => false , 'max' => 55 ] ,

                    'order_mobile' => [ 'type' => 'string' , 'required' => false , 'max' => 22 ] ,
                    'order_email'  => [ 'type' => 'string' , 'required' => false , 'max' => 55 ] ,

                    'facebook'  => [ 'type' => 'string' , 'required' => false , 'max' => 111 ] ,
                    'instagram' => [ 'type' => 'string' , 'required' => false , 'max' => 111 ] ,
                    'youtube'   => [ 'type' => 'string' , 'required' => false , 'max' => 111 ] ,
                    'linkedin'  => [ 'type' => 'string' , 'required' => false , 'max' => 111 ]
                ] ,
                3 => [
                    'services_headline_en' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'services_headline_az' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'services_headline_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,

                    'cars_headline_en' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'cars_headline_az' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'cars_headline_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,

                    'campaigns_headline_en' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'campaigns_headline_az' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'campaigns_headline_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,

                    'customers_headline_en' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'customers_headline_az' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'customers_headline_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,

                    'faq_headline_en' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'faq_headline_az' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'faq_headline_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,

                    'academy_text_en' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'academy_text_az' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'academy_text_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,

                    'team_text_en' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'team_text_az' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'team_text_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ]
                ] ,
                4 => [
                    'about_seo_keywords_az' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'about_seo_keywords_en' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'about_seo_keywords_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,

                    'about_seo_description_az' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'about_seo_description_en' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'about_seo_description_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,

                    'service_seo_keywords_az' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'service_seo_keywords_en' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'service_seo_keywords_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,

                    'service_seo_description_az' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'service_seo_description_en' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'service_seo_description_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,

                    'campaign_seo_keywords_az' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'campaign_seo_keywords_en' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'campaign_seo_keywords_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,

                    'campaign_seo_description_az' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'campaign_seo_description_en' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'campaign_seo_description_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                ]
            ];

            $form = $request -> request -> getInt( 'form' );

            $validations = $this -> validator -> validateForm(
                $request ,
                array_merge(
                    [ 'form' => [ 'type' => 'numeric' , 'required' => true , 'array' => [ 1 , 2 , 3 , 4 , 5 ] ] ] ,
                    ( $validations[ $form ] ?? [] )
                )
            );

            if( ! count( $validations ) )
            {
                $parameters = [
                    1 => [
                        'title_en'            => $request -> request -> get( 'title_en' ) ,
                        'title_az'            => $request -> request -> get( 'title_az' ) ,
                        'title_ru'            => $request -> request -> get( 'title_ru' ) ,
                        'text_en'             => $request -> request -> get( 'text_en' ) ,
                        'text_az'             => $request -> request -> get( 'text_az' ) ,
                        'text_ru'             => $request -> request -> get( 'text_ru' ) ,
                        'mission_en'          => $request -> request -> get( 'mission_en' ) ,
                        'mission_az'          => $request -> request -> get( 'mission_az' ) ,
                        'mission_ru'          => $request -> request -> get( 'mission_ru' ) ,
                        'transported_objects' => $request -> request -> get( 'transported_objects' ) ,
                        'cleaned_places'      => $request -> request -> get( 'cleaned_places' ) ,
                        'customer_reviews'    => $request -> request -> get( 'customer_reviews' ) ,
                        'satisfied_customers' => $request -> request -> get( 'satisfied_customers' )
                    ] ,
                    2 => [
                        'address_en'           => $request -> request -> get( 'address_en' ) ,
                        'address_az'           => $request -> request -> get( 'address_az' ) ,
                        'address_ru'           => $request -> request -> get( 'address_ru' ) ,
                        'corporate_contact_en' => $request -> request -> get( 'corporate_contact_en' ) ,
                        'corporate_contact_az' => $request -> request -> get( 'corporate_contact_az' ) ,
                        'corporate_contact_ru' => $request -> request -> get( 'corporate_contact_ru' ) ,
                        'index'                => $request -> request -> get( 'index' ) ,
                        'mobile'               => $request -> request -> get( 'mobile' ) ,
                        'email'                => $request -> request -> get( 'email' ) ,
                        'ad_mobile'            => $request -> request -> get( 'ad_mobile' ) ,
                        'ad_email'             => $request -> request -> get( 'ad_email' ) ,
                        'order_mobile'         => $request -> request -> get( 'order_mobile' ) ,
                        'order_email'          => $request -> request -> get( 'order_email' ) ,
                        'facebook'             => $request -> request -> get( 'facebook' ) ,
                        'instagram'            => $request -> request -> get( 'instagram' ) ,
                        'youtube'              => $request -> request -> get( 'youtube' ) ,
                        'linkedin'             => $request -> request -> get( 'linkedin' )
                    ] ,
                    3 => [
                        'services_headline_en'  => $request -> request -> get( 'services_headline_en' ) ,
                        'services_headline_az'  => $request -> request -> get( 'services_headline_az' ) ,
                        'services_headline_ru'  => $request -> request -> get( 'services_headline_ru' ) ,
                        'cars_headline_en'      => $request -> request -> get( 'cars_headline_en' ) ,
                        'cars_headline_az'      => $request -> request -> get( 'cars_headline_az' ) ,
                        'cars_headline_ru'      => $request -> request -> get( 'cars_headline_ru' ) ,
                        'campaigns_headline_en' => $request -> request -> get( 'campaigns_headline_en' ) ,
                        'campaigns_headline_az' => $request -> request -> get( 'campaigns_headline_az' ) ,
                        'campaigns_headline_ru' => $request -> request -> get( 'campaigns_headline_ru' ) ,
                        'customers_headline_en' => $request -> request -> get( 'customers_headline_en' ) ,
                        'customers_headline_az' => $request -> request -> get( 'customers_headline_az' ) ,
                        'customers_headline_ru' => $request -> request -> get( 'customers_headline_ru' ) ,
                        'faq_headline_en'       => $request -> request -> get( 'faq_headline_en' ) ,
                        'faq_headline_az'       => $request -> request -> get( 'faq_headline_az' ) ,
                        'faq_headline_ru'       => $request -> request -> get( 'faq_headline_ru' ) ,
                        'academy_text_en'       => $request -> request -> get( 'academy_text_en' ) ,
                        'academy_text_az'       => $request -> request -> get( 'academy_text_az' ) ,
                        'academy_text_ru'       => $request -> request -> get( 'academy_text_ru' ) ,
                        'team_text_en'          => $request -> request -> get( 'team_text_en' ) ,
                        'team_text_az'          => $request -> request -> get( 'team_text_az' ) ,
                        'team_text_ru'          => $request -> request -> get( 'team_text_ru' )
                    ] ,
                    4 => [
                        'about_seo_keywords_az' => $request -> request -> get( 'about_seo_keywords_az' ) ,
                        'about_seo_keywords_en' => $request -> request -> get( 'about_seo_keywords_en' ) ,
                        'about_seo_keywords_ru' => $request -> request -> get( 'about_seo_keywords_ru' ) ,

                        'about_seo_description_az' => $request -> request -> get( 'about_seo_description_az' ) ,
                        'about_seo_description_en' => $request -> request -> get( 'about_seo_description_en' ) ,
                        'about_seo_description_ru' => $request -> request -> get( 'about_seo_description_ru' ) ,

                        'service_seo_keywords_az' => $request -> request -> get( 'service_seo_keywords_az' ) ,
                        'service_seo_keywords_en' => $request -> request -> get( 'service_seo_keywords_en' ) ,
                        'service_seo_keywords_ru' => $request -> request -> get( 'service_seo_keywords_ru' ) ,

                        'service_seo_description_az' => $request -> request -> get( 'service_seo_description_az' ) ,
                        'service_seo_description_en' => $request -> request -> get( 'service_seo_description_en' ) ,
                        'service_seo_description_ru' => $request -> request -> get( 'service_seo_description_ru' ) ,

                        'campaign_seo_keywords_az' => $request -> request -> get( 'campaign_seo_keywords_az' ) ,
                        'campaign_seo_keywords_en' => $request -> request -> get( 'campaign_seo_keywords_en' ) ,
                        'campaign_seo_keywords_ru' => $request -> request -> get( 'campaign_seo_keywords_ru' ) ,

                        'campaign_seo_description_az' => $request -> request -> get( 'campaign_seo_description_az' ) ,
                        'campaign_seo_description_en' => $request -> request -> get( 'campaign_seo_description_en' ) ,
                        'campaign_seo_description_ru' => $request -> request -> get( 'campaign_seo_description_ru' ) ,
                    ] ,
                ];

                if( isset( $parameters[ $form ] ) ) DB ::table( Site::TABLE ) -> update( $parameters[ $form ] );

                $this -> upload(
                    $request , 'site' , Site::TABLE , 1 ,
                    [
                        [ 'photo' ] , 'background' , 'og_image' ,
                        'service_background' ,
                        'campaign_background' ,
                        'autopark_background' ,
                        'team_background' ,
                        'blog_background' ,
                        'media_background' ,
                        'faq_background' ,
                        'gallery_background' ,
                        'career_background' ,
                        'contact_background' ,
                    ]
                );
            }

            return response() -> json( [ 'status' => 'success' , 'validations' => $validations ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
