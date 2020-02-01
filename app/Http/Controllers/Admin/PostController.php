<?php
/**
 * Created by PhpStorm.
 * User: Khayyam
 * Date: 09-Oct-19
 * Time: 11:21
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function showPostPage( Request $request )
    {
        try
        {
            $type = $request -> route() -> getName() == 'admin.post-media.page' ? 'media' : 'blog';

            if( ! AdminController ::CAN( 'post' . ucfirst( $type ) . '.view' ) ) return redirect() -> route( 'login' );

            $services = Service ::where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 ) -> whereNull( 'parent_id' ) -> select( 'id' , 'name_en AS name' ) -> get();

            return view( 'admin.post' , compact( [ 'services' , 'type' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getPostList( Request $request )
    {
        try
        {
            $type = $request -> request -> get( 'type' );

//            if( ! AdminController ::CAN( 'post' . ucfirst( $type ) . '.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $SELECT = ' SELECT p.id , title_en title ' . ( $type == 'blog' ? ' , name_en `service`' : '' ) . ' , p.is_active ';

            $FROM = ' FROM ' . Post::TABLE . ' p ' . ( $type == 'blog' ? ' JOIN ' . Service::TABLE . ' s ON p.service_id = s.id ' : '' );

            $WHERE = ' WHERE p.is_deleted = 0 ' . ( $type == 'blog' ? 'AND s.is_deleted = 0 ' : '' );

            $filter = [
                'title'   => [ 'type' => 'search' , 'db' => [ 'title_en' , 'title_az' , 'title_ru' ] ] ,
                'service' => [ 'db' => 'service_id' ] ,
                'type'    => [ 'db' => 'type' ] ,
                'active'  => [ 'db' => 'p.is_active' ]
            ];

            return $this -> returnList( $request , $SELECT , $FROM , $WHERE , $filter , [ 'default' => 'p.created_at' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getPost( Request $request )
    {
        try
        {
//            if( ! AdminController ::CAN( 'post.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $post = Post ::where( 'id' , $id ) -> first();

            $post -> photo      = media( 'post/' . $post -> photo );
            $post -> background = media( 'post/' . $post -> background );
            $post -> og_image   = media( 'post/' . $post -> og_image );

            return response() -> json( [ 'status' => 'success' , 'data' => $post ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function editPost( Request $request )
    {
        try
        {
            $type = $request -> request -> get( 'type' );
            if( ! $type ) $type = 'blog';

            //if( ! AdminController ::CAN( 'post' . ucfirst( $type ) . '.edit' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $post = Post ::where( 'id' , $id ) -> first();

            $type = isset( $post -> type ) ? $post -> type : $type;

            $new = $this -> validator -> validateChecked( $request -> request -> get( 'new' ) );

            $validations = [
                'title_az'    => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                'title_en'    => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                'title_ru'    => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                'headline_en' => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,
                'headline_az' => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,
                'headline_ru' => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,
                'text_en'     => [ 'type' => 'string' , 'required' => true , 'max' => 55555 ] ,
                'text_az'     => [ 'type' => 'string' , 'required' => true , 'max' => 55555 ] ,
                'text_ru'     => [ 'type' => 'string' , 'required' => true , 'max' => 55555 ] ,
                'date'        => [ 'type' => 'datetime' , 'required' => true ] ,

                'seo_keywords_en'    => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                'seo_keywords_az'    => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                'seo_keywords_ru'    => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                'seo_description_en' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                'seo_description_az' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                'seo_description_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ]
            ];

            if( $type == 'blog' ) $validations[ 'service' ] = [ 'type' => 'numeric' , 'required' => true , 'exist' => Service::TABLE , 'db' => 'id' ];

            $validations = $this -> validator -> validateForm( $request , $validations );

            if( ! count( $validations ) )
            {
                $parameters = [
                    'title_en'    => $request -> request -> get( 'title_en' ) ,
                    'title_az'    => $request -> request -> get( 'title_az' ) ,
                    'title_ru'    => $request -> request -> get( 'title_ru' ) ,
                    'headline_en' => $request -> request -> get( 'headline_en' ) ,
                    'headline_az' => $request -> request -> get( 'headline_az' ) ,
                    'headline_ru' => $request -> request -> get( 'headline_ru' ) ,
                    'text_en'     => $request -> request -> get( 'text_en' ) ,
                    'text_az'     => $request -> request -> get( 'text_az' ) ,
                    'text_ru'     => $request -> request -> get( 'text_ru' ) ,
                    'date'        => $request -> request -> get( 'date' ) ,
                    'is_new'      => $new ,

                    'seo_keywords_en'    => $request -> request -> get( 'seo_keywords_en' ) ,
                    'seo_keywords_az'    => $request -> request -> get( 'seo_keywords_az' ) ,
                    'seo_keywords_ru'    => $request -> request -> get( 'seo_keywords_ru' ) ,
                    'seo_description_en' => $request -> request -> get( 'seo_description_en' ) ,
                    'seo_description_az' => $request -> request -> get( 'seo_description_az' ) ,
                    'seo_description_ru' => $request -> request -> get( 'seo_description_ru' )
                ];

                $parameters[ 'service_id' ] = $request -> request -> get( 'service' );

                if( ! $id ) $parameters[ 'type' ] = $type;

                if( $post && is_object( $post ) )
                {
                    DB ::table( Post::TABLE ) -> where( 'id' , $id ) -> update( $parameters );
                }
                else
                {
                    $id = DB ::table( Post::TABLE ) -> insertGetId( $parameters );
                }

                $this -> upload( $request , 'post' , Post::TABLE , $id , [ [ 'photo' ] , 'background' , 'og_image' ] );
            }

            return response() -> json( [ 'status' => 'success' , 'validations' => $validations ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function activatePost( Request $request )
    {
        try
        {
//            if( ! AdminController ::CAN( 'post.activate' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $active = $this -> validator -> validateBool( $request -> request -> get( 'active' ) );

            Post ::where( 'id' , $id ) -> update( [ 'is_active' => $active ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function deletePost( Request $request )
    {
        try
        {
//            if( ! AdminController ::CAN( 'post.delete' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            Post ::where( 'id' , $id ) -> update( [ 'is_deleted' => 1 ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
