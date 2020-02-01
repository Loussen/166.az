<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Vacancy;
use App\Models\VacancyDetail;
use App\Models\VacancyRequirement;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class VacancyController extends Controller
{
    public function careerPage()
    {
        try
        {
            $vacancies = DB ::table( Vacancy::TABLE )
                            -> where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 )
                            -> select( 'id' , self ::LANG_PARAM( 'title' ) , self ::LANG_PARAM( 'text' ) , 'photo' )
                            -> get()
            ;

            return view( 'site.vacancy.career' , compact( [ 'vacancies' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function vacancyPage( $locale , $id = 0 )
    {
        try
        {
            $id = $this -> validator -> validateId( $id );

            $vacancy = DB ::table( Vacancy::TABLE )
                          -> where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 )
                          -> where( 'id' , $id )
                          -> select( self ::LANG_PARAM( 'title' ) , self ::LANG_PARAM( 'note' ) , 'photo' , 'background' , 'og_image' , 'id' )
                          -> first()
            ;

            if( ! ( $vacancy && isset( $vacancy -> title ) ) ) return $this -> _404();

            $vacancy -> details      = VacancyDetail ::where( 'vacancy_id' , $id ) -> select( self ::LANG_PARAM( 'name' ) ) -> get();
            $vacancy -> requirements = VacancyRequirement ::where( 'vacancy_id' , $id ) -> select( self ::LANG_PARAM( 'name' ) ) -> get();

            $vacancies = DB ::table( Vacancy::TABLE )
                            -> where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 )
                            -> where( 'id' , '<>' , $id )
                            -> orderBy( 'created_at' , 'DESC' )
                            -> limit( 3 )
                            -> select( 'id' , self ::LANG_PARAM( 'title' ) , self ::LANG_PARAM( 'text' ) , 'photo' )
                            -> get()
            ;

            return view( 'site.vacancy.vacancy' , compact( [ 'vacancy' , 'vacancies' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> _404();
        }
    }


    public function apply( Request $request )
    {
        try
        {
            $validations = $this -> validator -> validateForm(
                $request ,
                [
                    'name'    => [ 'type' => 'string' , 'required' => true , 'max' => 55 ] ,
                    'email'   => [ 'type' => 'email' , 'required' => true , 'max' => 55 ] ,
                    'phone'   => [ 'type' => 'string' , 'required' => true , 'max' => 22 ] ,
                    'vacancy' => [ 'type' => 'numeric' , 'required' => true , 'exists' => Vacancy::TABLE , 'db' => 'id' ]
                ]
            );

            if( ! count( $validations ) )
            {
                $id = DB ::table( Candidate::TABLE )
                         -> insertGetId(
                             [
                                 'name'       => $request -> request -> get( 'name' ) ,
                                 'email'      => $request -> request -> get( 'email' ) ,
                                 'phone'      => $request -> request -> get( 'phone' ) ,
                                 'vacancy_id' => $request -> request -> get( 'vacancy' )
                             ]
                         )
                ;

                $this -> upload( $request , 'candidate' , Candidate::TABLE , $id , [ 'cv' ] , [ 'doc' , 'docx' , 'pdf' ] );
            }

            return response() -> json( [ 'status' => 'success' , 'validations' => $validations ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
