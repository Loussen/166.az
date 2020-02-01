<?php
/**
 * Created by PhpStorm.
 * User: Khayyam
 * Date: 08-Nov-19
 * Time: 01:01
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SliderController extends Controller
{
    public function showSliderPage()
    {
        try
        {
            if( ! AdminController ::CAN( 'slider.view' ) ) return redirect() -> route( 'login' );

            return view( 'admin.slider' );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getSliderList( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'slider.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $SELECT = ' SELECT id , link , is_active ';

            $FROM = ' FROM ' . Slider::TABLE;

            $WHERE = ' WHERE is_deleted = 0 ';

            $filter = [
                'link'   => [ 'type' => 'search' , 'db' => 'link' ] ,
                'active' => [ 'db' => 'is_active' ]
            ];

            return $this -> returnList( $request , $SELECT , $FROM , $WHERE , $filter , [] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getSlider( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'slider.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $slider = Slider ::where( 'id' , $id ) -> first();

            $slider -> photo = media( 'slider/' . $slider -> photo );

            return response() -> json( [ 'status' => 'success' , 'data' => $slider ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function editSlider( Request $request )
    {
        try
        {
//            if( ! AdminController ::CAN( 'slider.edit' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $validations = $this -> validator -> validateForm( $request , [ 'link' => [ 'type' => 'string' , 'required' => true , 'max' => 222 ] ] );

            if( ! count( $validations ) )
            {
                $slider = Slider ::where( 'id' , $id ) -> first();

                $parameters = [ 'link' => $request -> request -> get( 'link' ) ];

                if( $slider && is_object( $slider ) )
                {
                    DB ::table( Slider::TABLE ) -> where( 'id' , $id ) -> update( $parameters );
                }
                else
                {
                    $id = DB ::table( Slider::TABLE ) -> insertGetId( $parameters );
                }

                $this -> upload( $request , 'slider' , Slider::TABLE , $id , [ 'photo' ] );
            }

            return response() -> json( [ 'status' => 'success' , 'validations' => $validations ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function activateSlider( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'slider.activate' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $active = $this -> validator -> validateBool( $request -> request -> get( 'active' ) );

            Slider ::where( 'id' , $id ) -> update( [ 'is_active' => $active ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function deleteSlider( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'slider.delete' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            Slider ::where( 'id' , $id ) -> update( [ 'is_deleted' => 1 ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
