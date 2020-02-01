<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function blogPage( Request $request )
    {
        try
        {
            return view( 'site.blog.blog' , [ 'services' => ServiceController ::ALL_SERVICES() ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getBlogPostList( Request $request )
    {
        try
        {
            $SELECT = ' SELECT p.id , ' . self ::LANG_PARAM( 'title' ) . ' , ' . self ::LANG_PARAM( 'headline' , 'p' ) . ' , p.photo ';

            $FROM = ' FROM ' . Post::TABLE . ' p JOIN ' . Service::TABLE . ' s ON p.service_id = s.id ';

            $WHERE = " WHERE p.is_deleted = 0 AND s.is_deleted = 0 AND p.is_active = 1 AND s.is_active = 1 AND p.type = 'blog' ";

            $filter = [
                'search'  => [ 'type' => 'search' , 'db' => [ self ::LANG_PARAM( 'title' ) ] ] ,
                'service' => [ 'db' => 'service_id' ]
            ];

            $posts = $this -> configureList( $request , $SELECT , $FROM , $WHERE , $filter , [ 'default' => 'p.created_at' ] );

            foreach( $posts[ 'data' ] as $key => $post )
            {
                $posts[ 'data' ][ $key ] -> photo = $this -> avatar( $post -> photo );
            }

            return response() -> json( array_merge( [ 'status' => 'success' ] , $posts ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function blogPostPage( $locale , $id = 0 )
    {
        try
        {
            $id = $this -> validator -> validateId( $id );

            $post = DB ::select( 'SELECT '
                                 . self ::LANG_PARAM( 'title' )
                                 . ' , ' . self ::LANG_PARAM( 'text' , 'p' )
                                 . ' , ' . self ::LANG_PARAM( 'seo_keywords' , 'p' )
                                 . ' , ' . self ::LANG_PARAM( 'seo_description' , 'p' )
                                 . ' , IF( s.parent_id IS NULL , ' . self ::LANG_PARAM( 'name' , 's' , false ) . ' , ' . self ::LANG_PARAM( 'name' , 'par' , false ) . ' ) service , '
                                 . self ::LANG_PARAM( 'text' , 'p' ) . ' , view_count , date , p.photo , p.background , p.og_image , service_id FROM ' . Post::TABLE . ' p JOIN ' . Service::TABLE . ' s ON p.service_id = s.id LEFT JOIN ' . Service::TABLE . ' par ON s.parent_id = par.id WHERE p.is_deleted = 0 AND p.is_active = 1 AND s.is_deleted = 0 AND s.is_active = 1 AND ( s.parent_id IS NULL OR ( par.is_deleted = 0 AND par.is_active = 1 ) ) AND p.id = ' . $id )[ 0 ] ?? false;

            if( ! ( $post && isset( $post -> title ) ) ) return $this -> _404();

            DB ::table( Post::TABLE ) -> where( 'id' , $id ) -> update( [ 'view_count' => ( $post -> view_count + 1 ) ] );

            $similarPosts = DB ::table( Post::TABLE . ' AS p' )
                               -> join( Service::TABLE . ' AS s' , 'p.service_id' , 's.id' )
                               -> where( 's.is_deleted' , 0 ) -> where( 's.is_active' , 1 )
                               -> where( 'p.is_deleted' , 0 ) -> where( 'p.is_active' , 1 )
                               -> where( 'p.type' , 'blog' ) -> where( 'service_id' , $post -> service_id ) -> where( 'p.id' , '<>' , $id )
                               -> orderBy( 'date' , 'DESC' )
                               -> limit( 4 )
                               -> select( 'p.id' , 'p.photo' , 'date' , self ::LANG_PARAM( 'title' ) )
                               -> get()
            ;

            return view( 'site.blog.post' , [ 'post' => $post , 'similarPosts' => $similarPosts , 'services' => ServiceController ::ALL_SERVICES() ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function mediaPage( Request $request )
    {
        try
        {
            $post = DB ::select( 'SELECT id , '
                                 . self ::LANG_PARAM( 'title' )
                                 . ' , ' . self ::LANG_PARAM( 'headline' )
                                 . ' , is_new , view_count , date, photo FROM ' . Post::TABLE . " WHERE is_deleted = 0 AND is_active = 1 AND type = 'media' ORDER BY date DESC LIMIT 1 " )[ 0 ] ?? false;

            $popularPosts = DB ::select( 'SELECT id , '
                                         . self ::LANG_PARAM( 'title' )
                                         . ' , ' . self ::LANG_PARAM( 'headline' )
                                         . ' , is_new , view_count , date , photo FROM ' . Post::TABLE . " WHERE is_deleted = 0 AND is_active = 1 AND type = 'media' AND id <> " . ( isset( $post -> id ) ? $post -> id : 0 ) . ' ORDER BY view_count DESC LIMIT 3 ' );

            return view( 'site.media.media' , compact( [ 'post' , 'popularPosts' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getMediaPostList( Request $request )
    {
        try
        {
            $SELECT = ' SELECT id , ' . self ::LANG_PARAM( 'title' ) . ' , ' . self ::LANG_PARAM( 'headline' ) . ' , photo ';

            $FROM = ' FROM ' . Post::TABLE;

            $WHERE = " WHERE is_deleted = 0 AND is_active = 1 AND type = 'media' ";

            $filter = [
                'search' => [ 'type' => 'search' , 'db' => [ self ::LANG_PARAM( 'title' ) ] ]
            ];

            $posts = $this -> configureList( $request , $SELECT , $FROM , $WHERE , $filter , [ 'default' => 'created_at' ] , '' , '' , '' , 1 );

            foreach( $posts[ 'data' ] as $key => $post )
            {
                $posts[ 'data' ][ $key ] -> photo = $this -> avatar( $post -> photo );
            }

            return response() -> json( array_merge( [ 'status' => 'success' ] , $posts ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function mediaPostPage( $locale , $id = 0 )
    {
        try
        {
            $id = $this -> validator -> validateId( $id );

            $post = DB ::select( 'SELECT '
                                 . self ::LANG_PARAM( 'title' )
                                 . ' , ' . self ::LANG_PARAM( 'text' )
                                 . ' , ' . self ::LANG_PARAM( 'seo_keywords' )
                                 . ' , ' . self ::LANG_PARAM( 'seo_description' )
                                 . ' , is_new , view_count , date , photo , background , og_image FROM ' . Post::TABLE . ' WHERE is_deleted = 0 AND is_active = 1 AND type = \'media\' AND id = ' . $id )[ 0 ] ?? false;

            if( ! ( $post && isset( $post -> title ) ) ) return $this -> _404();

            DB ::table( Post::TABLE ) -> where( 'id' , $id ) -> update( [ 'view_count' => ( $post -> view_count + 1 ) ] );

            $popularPosts = DB ::select( 'SELECT id , '
                                         . self ::LANG_PARAM( 'title' )
                                         . ' , ' . self ::LANG_PARAM( 'headline' )
                                         . ' , is_new , view_count , date , photo FROM ' . Post::TABLE . ' WHERE is_deleted = 0 AND is_active = 1 AND type = \'media\' AND id <> ' . $id . ' ORDER BY view_count DESC LIMIT 3 ' );

            return view( 'site.media.post' , compact( [ 'post' , 'popularPosts' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public static function LAST_BLOGS( $service = null )
    {
        $posts = DB ::table( Post::TABLE . ' AS p' )
                    -> join( Service::TABLE . ' AS s' , 'p.service_id' , 's.id' )
                    -> where( 's.is_deleted' , 0 ) -> where( 's.is_active' , 1 )
                    -> where( 'p.is_deleted' , 0 ) -> where( 'p.is_active' , 1 )
                    -> where( 'p.type' , 'blog' )
                    -> orderBy( 'date' , 'DESC' )
                    -> limit( 2 )
                    -> select( 'p.id' , 'p.photo' , 'date' , self ::LANG_PARAM( 'title' ) )
        ;

        if( $service ) $posts = $posts -> where( 'service_id' , $service );

        return $posts -> get();
    }
}
