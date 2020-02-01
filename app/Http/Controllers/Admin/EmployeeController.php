<?php
/**
 * Created by PhpStorm.
 * User: Khayyam
 * Date: 11-Oct-19
 * Time: 14:04
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function showEmployeePage()
    {
        try
        {
            if( ! AdminController ::CAN( 'employee.view' ) ) return redirect() -> route( 'login' );

            return view( 'admin.employee' );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getEmployeeList( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'employee.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $SELECT = ' SELECT id , name_en `name` , is_active ';

            $FROM = ' FROM ' . Employee::TABLE;

            $WHERE = ' WHERE is_deleted = 0 ';

            $filter = [
                'name'   => [ 'type' => 'search' , 'db' => [ 'name_en' , 'name_az' , 'name_ru' ] ] ,
                'active' => [ 'db' => 'is_active' ]
            ];

            return $this -> returnList( $request , $SELECT , $FROM , $WHERE , $filter , [] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getEmployee( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'employee.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $employee = Employee ::where( 'id' , $id ) -> first();

            $employee -> photo = media( 'employee/' . $employee -> photo );

            return response() -> json( [ 'status' => 'success' , 'data' => $employee ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function editEmployee( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'employee.edit' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $validations = $this -> validator -> validateForm(
                $request ,
                [
                    'name_az'     => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                    'name_en'     => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                    'name_ru'     => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                    'position_az' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                    'position_en' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                    'position_ru' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                    'mobile'      => [ 'type' => 'string' , 'required' => false , 'max' => 22 ] ,
                    'email'       => [ 'type' => 'email' , 'required' => false , 'max' => 55 ] ,
                    'facebook'    => [ 'type' => 'string' , 'required' => false , 'max' => 111 ] ,
                    'instagram'   => [ 'type' => 'string' , 'required' => false , 'max' => 111 ] ,
                    'twitter'     => [ 'type' => 'string' , 'required' => false , 'max' => 111 ] ,
                    'linkedin'    => [ 'type' => 'string' , 'required' => false , 'max' => 111 ]
                ]
            );

            if( ! count( $validations ) )
            {
                $employee = Employee ::where( 'id' , $id ) -> first();

                $parameters = [
                    'name_en'     => $request -> request -> get( 'name_en' ) ,
                    'name_az'     => $request -> request -> get( 'name_az' ) ,
                    'name_ru'     => $request -> request -> get( 'name_ru' ) ,
                    'position_en' => $request -> request -> get( 'position_en' ) ,
                    'position_az' => $request -> request -> get( 'position_az' ) ,
                    'position_ru' => $request -> request -> get( 'position_ru' ) ,
                    'mobile'      => $request -> request -> get( 'mobile' ) ,
                    'email'       => $request -> request -> get( 'email' ) ,
                    'facebook'    => $request -> request -> get( 'facebook' ) ,
                    'instagram'   => $request -> request -> get( 'instagram' ) ,
                    'twitter'     => $request -> request -> get( 'twitter' ) ,
                    'linkedin'    => $request -> request -> get( 'linkedin' )
                ];

                if( $employee && is_object( $employee ) )
                {
                    DB ::table( Employee::TABLE ) -> where( 'id' , $id ) -> update( $parameters );
                }
                else
                {
                    $id = DB ::table( Employee::TABLE ) -> insertGetId( $parameters );
                }

                $this -> upload( $request , 'employee' , Employee::TABLE , $id , [ [ 'photo' ] ] );
            }

            return response() -> json( [ 'status' => 'success' , 'validations' => $validations ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function activateEmployee( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'employee.activate' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $active = $this -> validator -> validateBool( $request -> request -> get( 'active' ) );

            Employee ::where( 'id' , $id ) -> update( [ 'is_active' => $active ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function deleteEmployee( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'employee.delete' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            Employee ::where( 'id' , $id ) -> update( [ 'is_deleted' => 1 ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
