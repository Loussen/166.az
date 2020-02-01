<?php
/**
 * Created by PhpStorm.
 * User: Khayyam
 * Date: 30-May-19
 * Time: 16:36
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Language;
use App\Models\Translation;
use App\Models\TranslationTrans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TranslationController extends Controller
{
    public static function VIEW_ROLES()
    {
        $roles = [];

        foreach( Language ::LOCALES() as $locale ) $roles[] = "translation.$locale.view";

        return $roles;
    }


    public function showTranslationPage()
    {
        try
        {
            if( AdminController ::CAN( self ::VIEW_ROLES() ) )
            {
                $modules = DB ::select( " SELECT screen_url FROM CONTENTS WHERE active = 1 GROUP BY screen_url ; " );

                return view( 'admin.translations' , [ 'locales' => Language ::LOCALES() , 'defaultLocale' => Language::DEFAULT_LOCALE , 'modules' => $modules ] );
            }

            else return redirect() -> route( 'login' );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getTranslationList( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( self ::VIEW_ROLES() ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $SELECT = " SELECT c.label_key ";

            $FROM = " FROM CONTENTS c LEFT JOIN CONTENTS_TRANSLATIONS ct ON c.id = ct.content_id ";

            $filter = [
                'key'         => [ 'type' => 'search' , 'db' => 'c.label_key' ] ,
                'description' => [ 'type' => 'search' , 'db' => 'c.description' ] ,
                'translation' => [ 'type' => 'search' , 'db' => 'ct.`value`' ] ,
                'module'      => [ 'db' => 'c.screen_url' ]
            ];

            $havingClause = $request -> request -> get( 'status' ) == 1 ? ' COUNT( 0 ) < ' . count( Language ::LOCALES() ) . ' ' : '';

            $lang = $request -> request -> get( 'lang' );

            $WHERE = ' WHERE c.active = 1 AND c.label_key IS NOT NULL AND LENGTH( c.label_key ) > 1  ';

            $WHERE .= strlen( $havingClause ) ? ( $lang ? " AND ( SELECT COUNT( 0 ) FROM CONTENTS_TRANSLATIONS WHERE content_id = c.id AND lang_code = '" . $this -> clear( $lang ) . "' ) = 0 " : '' ) : '';

            $data = $this -> configureList( $request , $SELECT , $FROM , $WHERE , $filter , [] , 'c.label_key' , '' , $havingClause );

            if( count( $data[ 'data' ] ) )
            {
                $IN = '';

                $newData = $check = [];

                foreach( $data[ 'data' ] as $datum )
                {
                    $key = $datum -> label_key;

                    $IN .= strlen( $IN ) ? ',' : '';
                    $IN .= "'$key'";
                }

                $translations = DB ::select( " SELECT c.label_key , c.screen_url , c.description , ct.lang_code , ct.`value` FROM " . Translation::TABLE . " c LEFT JOIN " . TranslationTrans::TABLE . " ct ON c.id = ct.content_id WHERE c.label_key IN ( $IN ) " );

                foreach( $translations as $translation )
                {
                    if( ! isset( $newData[ $translation -> label_key ][ 'completed' ] ) )
                        $newData[ $translation -> label_key ][ 'completed' ] = false;

                    $newData[ $translation -> label_key ][ 'page' ] = $translation -> screen_url;

                    foreach( Language ::LOCALES() as $locale )
                    {
                        if( ! isset( $newData[ $translation -> label_key ][ 'translations' ][ $locale ][ 'value' ] ) )
                            $newData[ $translation -> label_key ][ 'translations' ][ $locale ][ 'value' ] = '';

                        if( ! isset( $newData[ $translation -> label_key ][ 'translations' ][ $locale ][ 'completed' ] ) )
                            $newData[ $translation -> label_key ][ 'translations' ][ $locale ][ 'completed' ] = false;
                    }

                    $newData[ $translation -> label_key ][ 'description' ] = trim( $translation -> description );

                    if( in_array( $translation -> lang_code , Language ::LOCALES() ) )
                    {
                        $newData[ $translation -> label_key ][ 'translations' ][ $translation -> lang_code ][ 'value' ] = trim( $translation -> value );

                        if( strlen( $newData[ $translation -> label_key ][ 'translations' ][ $translation -> lang_code ][ 'value' ] ) )
                        {
                            $newData[ $translation -> label_key ][ 'translations' ][ $translation -> lang_code ][ 'completed' ] = true;

                            if( ! isset( $check[ $translation -> label_key ] ) )
                                $check[ $translation -> label_key ] = 0;
                            $check[ $translation -> label_key ]++;

                            if( $check[ $translation -> label_key ] == count( Language ::LOCALES() ) ) $newData[ $translation -> label_key ][ 'completed' ] = true;
                        }
                    }
                }

                $obj = [];

                foreach( $newData as $key => $value )
                {
                    if( ! $value[ 'translations' ][ Language::DEFAULT_LOCALE ][ 'completed' ] )
                        $value[ 'translations' ][ Language::DEFAULT_LOCALE ][ 'value' ] = $value[ 'description' ];

                    $obj[] = [
                        'key'          => $key ,
                        'translations' => $value[ 'translations' ] ,
                        'completed'    => $value[ 'completed' ] ,
                        'page'         => $value[ 'page' ] ,
                        'description'  => $value[ 'description' ]
                    ];
                }

                $data[ 'data' ] = $obj;
            }

            return response() -> json( array_merge( [ 'status' => 'success' ] , $data ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function editTranslation( Request $request )
    {
        try
        {
            $validations = $this -> validator -> validateForm(
                $request ,
                [
                    'key'         => [ 'type' => 'string' , 'required' => true , 'max' => 555 , 'exist' => 'CONTENTS' , 'db' => 'label_key' ] ,
                    'locale'      => [ 'type' => 'string' , 'required' => true , 'max' => 5 , 'array' => self ::LANGUAGES() ] ,
                    'translation' => [ 'type' => 'string' , 'required' => true , 'max' => 55555 ]
                ]
            );

            if( ! count( $validations ) )
            {
                $translation = Translation ::where( 'label_key' , $request -> request -> get( 'key' ) ) -> first();

                $id = $translation -> id;

                $locale = $request -> request -> get( 'locale' );

                if( ! AdminController ::CAN( "translation.$locale.edit" ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

                $value = $request -> request -> get( 'translation' );

                if( $locale == self::DEFAULT_LANGUAGE )
                {
                    $translation -> update( [ 'description' => $value ] );
                }

                $parameters = [ 'value' => $value ];

                $translationTrans = TranslationTrans ::where( 'content_id' , $id ) -> where( 'lang_code' , $locale ) -> first();

                if( $translationTrans )
                {
                    $translationTrans -> update( $parameters );
                }
                else
                {
                    $parameters[ 'content_id' ] = $id;
                    $parameters[ 'lang_code' ]  = $locale;

                    TranslationTrans ::create( $parameters );
                }
            }

            return response() -> json( [ 'status' => 'success' , 'validations' => $validations ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
