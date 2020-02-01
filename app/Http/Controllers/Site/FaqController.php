<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class FaqController extends Controller
{
    public function faqPage( $locale , $id = 0 )
    {
        try
        {
            $services = [];

            $questions = DB ::table( Faq::TABLE . ' AS f' )
                            -> join( Service::TABLE . ' AS s' , 'f.service_id' , 's.id' )
                            -> where( 'f.is_deleted' , 0 ) -> where( 'f.is_active' , 1 )
                            -> where( 's.is_deleted' , 0 ) -> where( 's.is_active' , 1 )
                            -> select(
                                self ::LANG_PARAM( 'individual_question' ) ,
                                self ::LANG_PARAM( 'corporate_question' ) ,
                                self ::LANG_PARAM( 'individual_answer' ) ,
                                self ::LANG_PARAM( 'corporate_answer' ) ,
                                'service_id' , self ::LANG_PARAM( 'name' )
                            )
                            -> get()
            ;

            foreach( $questions as $question )
            {
                if( ! isset( $services[ $question -> service_id ] ) ) $services[ $question -> service_id ] = [ 'id' => $question -> service_id , 'name' => $question -> name ];

                $services[ $question -> service_id ][ 'questions' ][] = $question;
            }

            return view( 'site.faq' , compact( [ 'services' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
