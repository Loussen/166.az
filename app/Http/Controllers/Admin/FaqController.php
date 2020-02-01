<?php
/**
 * Created by PhpStorm.
 * User: Khayyam
 * Date: 06-Oct-19
 * Time: 16:47
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FaqController extends Controller
{
    public function showFaqPage()
    {
        try
        {
            if( ! AdminController ::CAN( 'faq.view' ) ) return redirect() -> route( 'login' );

            $services = Service ::where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 ) -> whereNull( 'parent_id' ) -> select( 'id' , 'name_en AS name' ) -> get();

            return view( 'admin.faq' , compact( [ 'services' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getFaqList( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'faq.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $SELECT = " SELECT f.id , individual_question_en individual_question , corporate_question_en corporate_question , name_en `service` , f.is_active ";

            $FROM = " FROM " . Faq::TABLE . " f JOIN " . Service::TABLE . " s ON f.service_id = s.id ";

            $WHERE = " AND f.is_deleted = 0 AND s.is_deleted = 0 ";

            $filter = [
                'question' => [ 'type' => 'search' , 'db' => [ 'individual_question_en' , 'individual_question_az' , 'corporate_question_en' , 'corporate_question_az' , 'question_ru' ] ] ,
                'service'  => [ 'db' => 'service_id' ] ,
                'active'   => [ 'db' => 'f.is_active' ]
            ];

            return $this -> returnList( $request , $SELECT , $FROM , $WHERE , $filter , [] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getFaq( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'faq.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $question = Faq ::where( 'id' , $id ) -> first();

            return response() -> json( [ 'status' => 'success' , 'data' => $question ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function editFaq( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'faq.edit' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $validations = $this -> validator -> validateForm(
                $request ,
                [
                    'individual_question_az' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'individual_question_en' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'individual_question_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,

                    'corporate_question_az' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'corporate_question_en' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,
                    'corporate_question_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 555 ] ,

                    'individual_answer_en' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'individual_answer_az' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'individual_answer_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,

                    'corporate_answer_en' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'corporate_answer_az' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'corporate_answer_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,

                    'service' => [ 'type' => 'numeric' , 'required' => true , 'exist' => Service::TABLE , 'db' => 'id' ]
                ]
            );

            if( ! count( $validations ) )
            {
                $question = Faq ::where( 'id' , $id ) -> first();

                $parameters = [
                    'individual_question_en' => $request -> request -> get( 'individual_question_en' ) ,
                    'individual_question_az' => $request -> request -> get( 'individual_question_az' ) ,
                    'individual_question_ru' => $request -> request -> get( 'individual_question_ru' ) ,

                    'corporate_question_en' => $request -> request -> get( 'corporate_question_en' ) ,
                    'corporate_question_az' => $request -> request -> get( 'corporate_question_az' ) ,
                    'corporate_question_ru' => $request -> request -> get( 'corporate_question_ru' ) ,

                    'individual_answer_en' => $request -> request -> get( 'individual_answer_en' ) ,
                    'individual_answer_az' => $request -> request -> get( 'individual_answer_az' ) ,
                    'individual_answer_ru' => $request -> request -> get( 'individual_answer_ru' ) ,

                    'corporate_answer_en' => $request -> request -> get( 'corporate_answer_en' ) ,
                    'corporate_answer_az' => $request -> request -> get( 'corporate_answer_az' ) ,
                    'corporate_answer_ru' => $request -> request -> get( 'corporate_answer_ru' ) ,

                    'service_id' => $request -> request -> get( 'service' )
                ];

                if( $question && is_object( $question ) )
                {
                    DB ::table( Faq::TABLE ) -> where( 'id' , $id ) -> update( $parameters );
                }
                else
                {
                    DB ::table( Faq::TABLE ) -> insert( $parameters );
                }
            }

            return response() -> json( [ 'status' => 'success' , 'validations' => $validations ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function activateFaq( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'faq.activate' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $active = $this -> validator -> validateBool( $request -> request -> get( 'active' ) );

            Faq ::where( 'id' , $id ) -> update( [ 'is_active' => $active ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function deleteFaq( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'faq.delete' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            Faq ::where( 'id' , $id ) -> update( [ 'is_deleted' => 1 ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
