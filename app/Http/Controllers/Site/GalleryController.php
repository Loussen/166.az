<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Service;
use App\Models\ServiceImage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class GalleryController extends Controller
{
    public function gallery()
    {
        try
        {
            $services = [];

            $images = DB ::table( ServiceImage::TABLE . ' AS i' )
                         -> join( Service::TABLE . ' AS s' , 'i.service_id' , 's.id' )
                         -> where( 'i.is_deleted' , 0 )
                         -> where( 's.is_deleted' , 0 ) -> where( 's.is_active' , 1 )
                         -> select( 'i.path' , 'service_id' , self ::LANG_PARAM( 'name' ) )
                         -> get()
            ;

            foreach( $images as $image )
            {
                if( ! isset( $services[ $image -> service_id ] ) ) $services[ $image -> service_id ] = [ 'id' => $image -> service_id , 'name' => $image -> name ];

                $services[ $image -> service_id ][ 'images' ][] = media( 'service/images/' . $image -> path );
            }

            $posts = DB ::select( 'SELECT id , '
                                  . self ::LANG_PARAM( 'title' ) . ' , ' . self ::LANG_PARAM( 'headline' )
                                  . ' , date , photo FROM ' . Post::TABLE . " WHERE is_deleted = 0 AND is_active = 1 AND type = 'media' ORDER BY date DESC LIMIT 10 " );

            return view( 'site.gallery' , compact( [ 'services' , 'posts' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function galleryPage()
    {
        try
        {
            return view( 'site.gallery' , [ 'services' => ServiceController ::ALL_SERVICES() ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getImageList( Request $request )
    {
        try
        {
            $SELECT = ' SELECT i.path ';

            $FROM = ' FROM ' . ServiceImage::TABLE . ' i JOIN ' . Service::TABLE . ' s ON i.service_id = s.id ';

            $WHERE = ' WHERE i.is_deleted = 0 AND s.is_deleted = 0 AND s.is_active = 1 ';

            $filter = [ 'service' => [ 'db' => 'service_id' ] ];

            $images = $this -> configureList( $request , $SELECT , $FROM , $WHERE , $filter , [ 'default' => 'i.created_at' ] );

            foreach( $images[ 'data' ] as $key => $image )
            {
                $images[ 'data' ][ $key ] -> path = media( 'service/images/' . $image -> path );

                //$images[ 'data' ][ $key ] -> avatar = $this -> avatar( $image -> path , 'service/images' );
            }

            return response() -> json( array_merge( [ 'status' => 'success' ] , $images ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
