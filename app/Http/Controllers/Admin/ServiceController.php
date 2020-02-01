<?php
/**
 * Created by PhpStorm.
 * User: Khayyam
 * Date: 05-Oct-19
 * Time: 14:32
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function showServicePage()
    {
        try
        {
            if( ! AdminController ::CAN( 'service.view' ) ) return redirect() -> route( 'login' );

            return view( 'admin.service' );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getServiceList( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'service.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $SELECT = " SELECT s.id , s.name_en `name` , p.name_en parent_name , s.is_active ";

            $FROM = " FROM " . Service::TABLE . " s LEFT JOIN " . Service::TABLE . " p ON s.parent_id = p.id ";

            $WHERE = " WHERE s.is_deleted = 0 ";

            $filter = [
                'name'   => [ 'type' => 'search' , 'db' => [ 's.name_en' , 's.name_az' , 's.name_ru' ] ] ,
                'parent' => [ 'db' => 's.parent_id' ] ,
                'active' => [ 'db' => 's.is_active' ]
            ];

            return $this -> returnList( $request , $SELECT , $FROM , $WHERE , $filter , [] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getService( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'service.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $service = Service ::where( 'id' , $id ) -> first();

            $service -> photo      = media( 'service/' . $service -> photo );
            $service -> background = media( 'service/' . $service -> background );
            $service -> og_image   = media( 'service/' . $service -> og_image );

            $serviceImages = ServiceImage ::where( 'is_deleted' , 0 ) -> where( 'service_id' , $id ) -> get();

            $images = [];

            foreach( $serviceImages as $image )
            {
                $image -> path = media( 'service/images/' . $image -> path );

                $images[] = $image;
            }

            $service -> images = $images;

            $parent_name = Service ::where( 'id' , $service -> parent_id ) -> select( 'name_en' ) -> first();

            $service -> parent_name = isset( $parent_name -> name_en ) ? $parent_name -> name_en : 'Parent';

            return response() -> json( [ 'status' => 'success' , 'data' => $service ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function editService( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'service.edit' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $validations = $this -> validator -> validateForm(
                $request ,
                [
                    'name_az' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                    'name_en' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                    'name_ru' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,

                    'individual_headline_en' => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,
                    'individual_headline_az' => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,
                    'individual_headline_ru' => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,

                    'corporate_headline_en' => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,
                    'corporate_headline_az' => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,
                    'corporate_headline_ru' => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,

                    'include_headline_en' => [ 'type' => 'string' , 'required' => false , 'max' => 5555 ] ,
                    'include_headline_az' => [ 'type' => 'string' , 'required' => false , 'max' => 5555 ] ,
                    'include_headline_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 5555 ] ,

                    'extra_info_headline_en' => [ 'type' => 'string' , 'required' => false , 'max' => 5555 ] ,
                    'extra_info_headline_az' => [ 'type' => 'string' , 'required' => false , 'max' => 5555 ] ,
                    'extra_info_headline_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 5555 ] ,

                    'individual_description_en' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'individual_description_az' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'individual_description_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,

                    'corporate_description_en' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'corporate_description_az' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'corporate_description_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,

                    'seo_keywords_en'    => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'seo_keywords_az'    => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'seo_keywords_ru'    => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'seo_description_en' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'seo_description_az' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'seo_description_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,

                    'price' => [ 'type' => 'numeric' , 'required' => false ]
                ]
            );

            if( ! count( $validations ) )
            {
                $service = Service ::where( 'id' , $id ) -> first();

                $parent = $this -> validator -> validateId( $request -> request -> get( 'parent' ) );
                if( ! $parent ) $parent = null;

                $parameters = [
                    'name_en' => $request -> request -> get( 'name_en' ) ,
                    'name_az' => $request -> request -> get( 'name_az' ) ,
                    'name_ru' => $request -> request -> get( 'name_ru' ) ,

                    'individual_headline_en' => $request -> request -> get( 'individual_headline_en' ) ,
                    'individual_headline_az' => $request -> request -> get( 'individual_headline_az' ) ,
                    'individual_headline_ru' => $request -> request -> get( 'individual_headline_ru' ) ,

                    'corporate_headline_en' => $request -> request -> get( 'corporate_headline_en' ) ,
                    'corporate_headline_az' => $request -> request -> get( 'corporate_headline_az' ) ,
                    'corporate_headline_ru' => $request -> request -> get( 'corporate_headline_ru' ) ,

                    'include_headline_en' => $request -> request -> get( 'include_headline_en' ) ,
                    'include_headline_az' => $request -> request -> get( 'include_headline_az' ) ,
                    'include_headline_ru' => $request -> request -> get( 'include_headline_ru' ) ,

                    'extra_info_headline_en' => $request -> request -> get( 'extra_info_headline_en' ) ,
                    'extra_info_headline_az' => $request -> request -> get( 'extra_info_headline_az' ) ,
                    'extra_info_headline_ru' => $request -> request -> get( 'extra_info_headline_ru' ) ,

                    'individual_description_en' => $request -> request -> get( 'individual_description_en' ) ,
                    'individual_description_az' => $request -> request -> get( 'individual_description_az' ) ,
                    'individual_description_ru' => $request -> request -> get( 'individual_description_ru' ) ,

                    'corporate_description_en' => $request -> request -> get( 'corporate_description_en' ) ,
                    'corporate_description_az' => $request -> request -> get( 'corporate_description_az' ) ,
                    'corporate_description_ru' => $request -> request -> get( 'corporate_description_ru' ) ,

                    'seo_keywords_en'    => $request -> request -> get( 'seo_keywords_en' ) ,
                    'seo_keywords_az'    => $request -> request -> get( 'seo_keywords_az' ) ,
                    'seo_keywords_ru'    => $request -> request -> get( 'seo_keywords_ru' ) ,
                    'seo_description_en' => $request -> request -> get( 'seo_description_en' ) ,
                    'seo_description_az' => $request -> request -> get( 'seo_description_az' ) ,
                    'seo_description_ru' => $request -> request -> get( 'seo_description_ru' ) ,

                    'price' => $request -> request -> get( 'price' ) ,

                    'parent_id' => $parent
                ];

                if( $service && is_object( $service ) )
                {
                    DB ::table( Service::TABLE ) -> where( 'id' , $id ) -> update( $parameters );
                }
                else
                {
                    $id = DB ::table( Service::TABLE ) -> insertGetId( $parameters );
                }

                $this -> upload( $request , 'service' , Service::TABLE , $id , [ [ 'photo' ] , 'background' , 'og_image' ] );

                $this -> uploadMultipleFiles( $request , [ 'id' => $id ] );
            }

            return response() -> json( [ 'status' => 'success' , 'validations' => $validations ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function activateService( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'service.activate' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $active = $this -> validator -> validateBool( $request -> request -> get( 'active' ) );

            Service ::where( 'id' , $id ) -> update( [ 'is_active' => $active ] );

            if( ! $active ) Service ::where( 'parent_id' , $id ) -> update( [ 'is_active' => $active ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function deleteService( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'service.delete' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            Service ::where( 'id' , $id ) -> update( [ 'is_deleted' => 1 ] );

            Service ::where( 'parent_id' , $id ) -> update( [ 'is_deleted' => 1 ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getServiceSearch( Request $request )
    {
        $query = ' SELECT s.id , s.name_en `text` FROM ' . Service::TABLE . ' s LEFT JOIN ' . Service::TABLE . " p ON s.parent_id = p.id AND s.is_deleted = 0 AND s.is_active = 1 WHERE ( s.parent_id IS NULL OR ( p.is_deleted = 0 AND p.is_active = 1 AND p.parent_id IS NULL ) ) ";

        $parent = $this -> validator -> validateId( $request -> request -> get( 'parent' ) );

        $except = $this -> validator -> validateId( $request -> request -> get( 'except' ) );

        $search = $this -> clear( $request -> request -> get( 'search' ) );

        if( $parent ) $query .= ' AND s.parent_id IS NULL ';

        if( $except ) $query .= " AND s.id <> $except ";

        if( $search ) $query .= " AND ( s.name_en LIKE '%$search%' OR s.name_az LIKE '%$search%' OR s.name_ru LIKE '%$search%' ) ";

        $services = [];

        if( $request -> request -> get( 'all' ) == 1 ) $services = [ (object)[ 'id' => 'All' , 'text' => 'All' ] ];

        if( $request -> request -> get( 'service' ) == 1 ) $services = [ (object)[ 'id' => 'Parent' , 'text' => 'Parent' ] ];

        $services = array_merge( $services , DB ::select( " $query LIMIT 20 ; " ) );

        return response() -> json( [ 'status' => 'success' , 'data' => $services ] );
    }


    public function getServiceStep( Request $request )
    {
        $service = Service ::where( 'id' , $request -> request -> get( 'id' ) ) -> select( 'id' , 'parent_id' ) -> first();

        $step = isset( $service -> id ) && $service -> id ? ( $service -> parent_id ? 2 : 1 ) : 0;

        return response() -> json( [ 'status' => 'success' , 'data' => $step ] );
    }
}
