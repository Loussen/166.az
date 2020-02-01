<?php
/**
 * Created by PhpStorm.
 * User: Khayyam
 * Date: 09-Oct-19
 * Time: 10:01
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CarController extends Controller
{
    public function showCarPage()
    {
        try
        {
            if( ! AdminController ::CAN( 'car.view' ) ) return redirect() -> route( 'login' );

            $types = CarType ::where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 ) -> select( 'id' , 'name_en AS name' ) -> get();

            return view( 'admin.car' , compact( [ 'types' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getCarList( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'car.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $SELECT = ' SELECT c.id , title_en title , c.is_active , name_en `type` ';

            $FROM = ' FROM ' . Car::TABLE . ' c JOIN ' . CarType::TABLE . ' t ON c.type_id = t.id ';

            $WHERE = ' WHERE c.is_deleted = 0 AND t.is_deleted = 0 ';

            $filter = [
                'title'  => [ 'type' => 'search' , 'db' => [ 'title_en' , 'title_az' , 'title_ru' ] ] ,
                'type'   => [ 'db' => 'type_id' ] ,
                'active' => [ 'db' => 'is_active' ]
            ];

            return $this -> returnList( $request , $SELECT , $FROM , $WHERE , $filter , [] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getCar( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'car.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $car = Car ::where( 'id' , $id ) -> first();

            $car -> photo       = media( 'car/' . $car -> photo );
            $car -> background  = media( 'car/' . $car -> background );
            $car -> og_image    = media( 'car/' . $car -> og_image );
            $car -> palet_photo = media( 'car/' . $car -> palet_photo );

            return response() -> json( [ 'status' => 'success' , 'data' => $car ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function editCar( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'car.edit' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $validations = $this -> validator -> validateForm(
                $request ,
                [
                    'title_az'    => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                    'title_en'    => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                    'title_ru'    => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                    'text_en'     => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,
                    'text_az'     => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,
                    'text_ru'     => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,
                    'headline_en' => [ 'type' => 'string' , 'required' => true , 'max' => 55555 ] ,
                    'headline_az' => [ 'type' => 'string' , 'required' => true , 'max' => 55555 ] ,
                    'headline_ru' => [ 'type' => 'string' , 'required' => true , 'max' => 55555 ] ,
                    'length'      => [ 'type' => 'string' , 'required' => true , 'max' => 11 ] ,
                    'height'      => [ 'type' => 'string' , 'required' => true , 'max' => 11 ] ,
                    'width'       => [ 'type' => 'string' , 'required' => true , 'max' => 11 ] ,
                    'palet'       => [ 'type' => 'numeric' , 'required' => true ] ,
                    'weight'      => [ 'type' => 'string' , 'required' => true , 'max' => 11 ] ,

                    'seo_keywords_en'    => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'seo_keywords_az'    => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'seo_keywords_ru'    => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'seo_description_en' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'seo_description_az' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'seo_description_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,

                    'type' => [ 'type' => 'numeric' , 'required' => true , 'exist' => CarType::TABLE , 'db' => 'id' ]
                ]
            );

            if( ! count( $validations ) )
            {
                $car = Car ::where( 'id' , $id ) -> first();

                $parameters = [
                    'title_en'    => $request -> request -> get( 'title_en' ) ,
                    'title_az'    => $request -> request -> get( 'title_az' ) ,
                    'title_ru'    => $request -> request -> get( 'title_ru' ) ,
                    'text_en'     => $request -> request -> get( 'text_en' ) ,
                    'text_az'     => $request -> request -> get( 'text_az' ) ,
                    'text_ru'     => $request -> request -> get( 'text_ru' ) ,
                    'headline_en' => $request -> request -> get( 'headline_en' ) ,
                    'headline_az' => $request -> request -> get( 'headline_az' ) ,
                    'headline_ru' => $request -> request -> get( 'headline_ru' ) ,
                    'length'      => $request -> request -> get( 'length' ) ,
                    'height'      => $request -> request -> get( 'height' ) ,
                    'width'       => $request -> request -> get( 'width' ) ,
                    'palet'       => $request -> request -> get( 'palet' ) ,
                    'weight'      => $request -> request -> get( 'weight' ) ,

                    'seo_keywords_en'    => $request -> request -> get( 'seo_keywords_en' ) ,
                    'seo_keywords_az'    => $request -> request -> get( 'seo_keywords_az' ) ,
                    'seo_keywords_ru'    => $request -> request -> get( 'seo_keywords_ru' ) ,
                    'seo_description_en' => $request -> request -> get( 'seo_description_en' ) ,
                    'seo_description_az' => $request -> request -> get( 'seo_description_az' ) ,
                    'seo_description_ru' => $request -> request -> get( 'seo_description_ru' ) ,

                    'type_id' => $request -> request -> get( 'type' )
                ];

                if( $car && is_object( $car ) )
                {
                    DB ::table( Car::TABLE ) -> where( 'id' , $id ) -> update( $parameters );
                }
                else
                {
                    $id = DB ::table( Car::TABLE ) -> insertGetId( $parameters );
                }

                $this -> upload( $request , 'car' , Car::TABLE , $id , [ [ 'photo' ] , 'background' , 'og_image' , 'palet_photo' ] );
            }

            return response() -> json( [ 'status' => 'success' , 'validations' => $validations ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function activateCar( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'car.activate' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $active = $this -> validator -> validateBool( $request -> request -> get( 'active' ) );

            Car ::where( 'id' , $id ) -> update( [ 'is_active' => $active ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function deleteCar( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'car.delete' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            Car ::where( 'id' , $id ) -> update( [ 'is_deleted' => 1 ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
