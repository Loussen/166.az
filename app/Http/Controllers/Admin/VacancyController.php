<?php
/**
 * Created by PhpStorm.
 * User: Khayyam
 * Date: 11-Oct-19
 * Time: 13:28
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Vacancy;
use App\Models\VacancyDetail;
use App\Models\VacancyRequirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VacancyController extends Controller
{
    public function showVacancyPage( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'vacancy.view' ) ) return redirect() -> route( 'login' );

            return view( 'admin.vacancy' );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getVacancyList( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'vacancy.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $SELECT = ' SELECT id , title_en title , is_active ';

            $FROM = ' FROM ' . Vacancy::TABLE;

            $WHERE = ' WHERE is_deleted = 0 ';

            $filter = [
                'title'  => [ 'type' => 'search' , 'db' => [ 'title_en' , 'title_az' , 'title_ru' ] ] ,
                'active' => [ 'db' => 'is_active' ]
            ];

            return $this -> returnList( $request , $SELECT , $FROM , $WHERE , $filter , [ 'default' => 'created_at' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getVacancy( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'vacancy.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $vacancy = Vacancy ::where( 'id' , $id ) -> first();

            $vacancy -> photo      = media( 'vacancy/' . $vacancy -> photo );
            $vacancy -> background = media( 'vacancy/' . $vacancy -> background );
            $vacancy -> og_image   = media( 'vacancy/' . $vacancy -> og_image );

            $vacancy -> details = VacancyDetail ::where( 'vacancy_id' , $id ) -> get();

            $vacancy -> requirements = VacancyRequirement ::where( 'vacancy_id' , $id ) -> get();

            return response() -> json( [ 'status' => 'success' , 'data' => $vacancy ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function editVacancy( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'vacancy.edit' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $validations = [
                'title_az' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                'title_en' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                'title_ru' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                'text_en'  => [ 'type' => 'string' , 'required' => true , 'max' => 55555 ] ,
                'text_az'  => [ 'type' => 'string' , 'required' => true , 'max' => 55555 ] ,
                'text_ru'  => [ 'type' => 'string' , 'required' => true , 'max' => 55555 ] ,
                'note_en'  => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,
                'note_az'  => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,
                'note_ru'  => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ]
            ];

            $validations = $this -> validator -> validateForm( $request , $validations );

            if( ! count( $validations ) )
            {
                $vacancy = Vacancy ::where( 'id' , $id ) -> first();

                $parameters = [
                    'title_en' => $request -> request -> get( 'title_en' ) ,
                    'title_az' => $request -> request -> get( 'title_az' ) ,
                    'title_ru' => $request -> request -> get( 'title_ru' ) ,
                    'text_en'  => $request -> request -> get( 'text_en' ) ,
                    'text_az'  => $request -> request -> get( 'text_az' ) ,
                    'text_ru'  => $request -> request -> get( 'text_ru' ) ,
                    'note_en'  => $request -> request -> get( 'note_en' ) ,
                    'note_az'  => $request -> request -> get( 'note_az' ) ,
                    'note_ru'  => $request -> request -> get( 'note_ru' )
                ];

                if( $vacancy && is_object( $vacancy ) )
                {
                    DB ::table( Vacancy::TABLE ) -> where( 'id' , $id ) -> update( $parameters );
                }
                else
                {
                    $id = DB ::table( Vacancy::TABLE ) -> insertGetId( $parameters );
                }

                $this -> upload( $request , 'vacancy' , Vacancy::TABLE , $id , [ [ 'photo' ] , 'background' , 'og_image' ] );


                $details = $request -> request -> get( 'details' );

                VacancyDetail ::where( 'vacancy_id' , $id ) -> delete();

                if( is_array( $details ) )
                {
                    foreach( $details as $detail )
                    {
                        DB ::table( VacancyDetail::TABLE ) -> insert(
                            [
                                'name_en'    => $detail[ 'name_en' ] ?? '' ,
                                'name_az'    => $detail[ 'name_az' ] ?? '' ,
                                'name_ru'    => $detail[ 'name_ru' ] ?? '' ,
                                'vacancy_id' => $id
                            ]
                        )
                        ;
                    }
                }


                $requirements = $request -> request -> get( 'requirements' );

                VacancyRequirement ::where( 'vacancy_id' , $id ) -> delete();

                if( is_array( $requirements ) )
                {
                    foreach( $requirements as $requirement )
                    {
                        DB ::table( VacancyRequirement::TABLE ) -> insert(
                            [
                                'name_en'    => $requirement[ 'name_en' ] ?? '' ,
                                'name_az'    => $requirement[ 'name_az' ] ?? '' ,
                                'name_ru'    => $requirement[ 'name_ru' ] ?? '' ,
                                'vacancy_id' => $id
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


    public function activateVacancy( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'vacancy.activate' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $active = $this -> validator -> validateBool( $request -> request -> get( 'active' ) );

            Vacancy ::where( 'id' , $id ) -> update( [ 'is_active' => $active ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function deleteVacancy( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'vacancy.delete' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            Vacancy ::where( 'id' , $id ) -> update( [ 'is_deleted' => 1 ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
