<?php
/**
 * Created by PhpStorm.
 * User: Khayyam
 * Date: 27-May-19
 * Time: 09:29
 */

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use App\Models\AdminRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    const ADMIN_ROLE = 'admin';


    public function showAdminPage()
    {
        try
        {
            if( self ::CAN( 'admin.view' ) ) return view( 'admin.admins' , [ 'roles' => self ::ROLES() ] );

            else return redirect() -> route( 'login' );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getAdminList( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'admin.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $SELECT = " SELECT id , name , username , is_active ";

            $FROM = " FROM " . Admin::TABLE;

            $WHERE = " WHERE is_deleted = 0 AND id <> 1 ";

            $filter = [
                'name'     => [ 'type' => 'search' ] ,
                'username' => [ 'type' => 'search' ] ,
                'active'   => [ 'db' => 'is_active' ]
            ];

            return $this -> returnList( $request , $SELECT , $FROM , $WHERE , $filter , [] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getAdmin( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'admin.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $admin = Admin ::where( 'id' , $id ) -> where( 'is_deleted' , false ) -> first();

            $admin -> roles = implode( ',' , self ::CAN( '' , true , $id ) );

            return response() -> json( [ 'status' => 'success' , 'data' => $admin ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function editAdmin( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'admin.edit' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $validations = $this -> validator -> validateForm(
                $request ,
                [
                    'name'     => [ 'type' => 'string' , 'required' => true , 'max' => 55 ] ,
                    'username' => [ 'type' => 'string' , 'required' => true , 'max' => 55 , 'unique' => Admin::TABLE ] ,
                    'password' => [ 'type' => 'repeat' , 'required' => ! $id , 'max' => 55 ] ,
                    'roles'    => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ]
                ]
            );

            if( ! count( $validations ) )
            {
                $admin = Admin ::where( 'is_deleted' , false ) -> where( 'id' , $id ) -> first();

                $parameters = [
                    'name'     => $request -> request -> get( 'name' ) ,
                    'username' => $request -> request -> get( 'username' )
                ];

                $password = $request -> request -> get( 'password' );

                if( $password ) $parameters[ 'password' ] = Hash ::make( $password );

                if( $admin && is_object( $admin ) )
                {
                    DB ::table( Admin::TABLE ) -> where( 'id' , $admin -> id ) -> update( $parameters );
                }
                else
                {
                    $id = DB ::table( Admin::TABLE ) -> insertGetId( $parameters );
                }


                /** Roles */

                $requestRoles = explode( ',' , $request -> request -> get( 'roles' ) );

                DB ::select( " DELETE FROM " . AdminRole::TABLE . " WHERE admin_id = $id ; " );

                if( ( $admin && isset( $admin -> id ) && Auth ::id() == $admin -> id ) || in_array( self::ADMIN_ROLE , $requestRoles ) )
                {
                    DB ::table( AdminRole::TABLE ) -> insert( [ 'admin_id' => $id , 'role' => self::ADMIN_ROLE ] );
                }
                else
                {
                    $this -> addRolesToAdmin( self ::ROLES() , $requestRoles , $id );
                }

                /** END OF Roles */
            }

            return response() -> json( [ 'status' => 'success' , 'validations' => $validations ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function activateAdmin( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'admin.activate' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $active = $this -> validator -> validateBool( $request -> request -> get( 'active' ) );

            Admin ::where( 'id' , $id ) -> where( 'id' , '<>' , Auth ::id() ) -> update( [ 'is_active' => $active ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function deleteAdmin( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'admin.delete' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            Admin ::where( 'id' , $id ) -> where( 'id' , '<>' , Auth ::id() ) -> update( [ 'is_deleted' => 1 ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function showSettings()
    {
        try
        {
            return view( 'admin.settings' );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }

    public function changePassword( Request $request )
    {
        try
        {
            $validations = $this -> validator -> validateForm(
                $request ,
                [
                    'oldPassword' => [ 'type' => 'string' , 'required' => true , 'max' => 55 ] ,
                    'newPassword' => [ 'type' => 'repeat' , 'required' => true , 'max' => 55 ]
                ]
            );

            if( ! count( $validations ) )
            {
                $oldPassword = $request -> request -> get( 'oldPassword' );
                $newPassword = $request -> request -> get( 'newPassword' );

                $currentPassword = Auth ::User() -> password;

                if( ! Hash ::check( $oldPassword , $currentPassword ) ) $validations[ 'oldPassword' ][] = 'PasswordIsNotValid';

                else
                {
                    $id = Auth ::id();

                    $admin = Admin ::find( $id );

                    $admin -> password = Hash ::make( $newPassword );

                    $admin -> save();
                }
            }

            return response() -> json( [ 'status' => 'success' , 'validations' => $validations ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public static function ROLES()
    {
        return [
            self::ADMIN_ROLE         => [
                'name'  => 'Admins' ,
                'roles' => [
                    'admin.view'     => [ 'name' => 'View' ] ,
                    'admin.add'      => [ 'name' => 'Add' ] ,
                    'admin.edit'     => [ 'name' => 'Edit' ] ,
                    'admin.activate' => [ 'name' => 'Activate/deactivate' ] ,
                    'admin.delete'   => [ 'name' => 'Delete' ]
                ]
            ] ,
            'dashboard'              => [ 'name' => 'Dashboard' ] ,
            'service'                => [
                'name'  => 'Services' ,
                'roles' => [
                    'service.view'      => [ 'name' => 'View' ] ,
                    'service.add'       => [ 'name' => 'Add' ] ,
                    'service.edit'      => [ 'name' => 'Edit' ] ,
                    'service.activate'  => [ 'name' => 'Activate/deactivate' ] ,
                    'service.delete'    => [ 'name' => 'Delete' ] ,
                    'service.extraInfo' => [
                        'name'  => 'Extra info' ,
                        'roles' => [
                            'service.extraInfo.view'     => [ 'name' => 'View' ] ,
                            'service.extraInfo.add'      => [ 'name' => 'Add' ] ,
                            'service.extraInfo.edit'     => [ 'name' => 'Edit' ] ,
                            'service.extraInfo.activate' => [ 'name' => 'Activate/deactivate' ] ,
                            'service.extraInfo.delete'   => [ 'name' => 'Delete' ]
                        ]
                    ]
                ]
            ] ,
            'serviceInput'           => [
                'name'  => 'Service inputs' ,
                'roles' => [
                    'serviceInput.view'     => [ 'name' => 'View' ] ,
                    'serviceInput.add'      => [ 'name' => 'Add' ] ,
                    'serviceInput.edit'     => [ 'name' => 'Edit' ] ,
                    'serviceInput.activate' => [ 'name' => 'Activate/deactivate' ] ,
                    'serviceInput.delete'   => [ 'name' => 'Delete' ]
                ]
            ] ,
            'faq'                    => [
                'name'  => 'FAQ' ,
                'roles' => [
                    'faq.view'     => [ 'name' => 'View' ] ,
                    'faq.add'      => [ 'name' => 'Add' ] ,
                    'faq.edit'     => [ 'name' => 'Edit' ] ,
                    'faq.activate' => [ 'name' => 'Activate/deactivate' ] ,
                    'faq.delete'   => [ 'name' => 'Delete' ]
                ]
            ] ,
            'carType'                => [
                'name'  => 'Car types' ,
                'roles' => [
                    'carType.view'     => [ 'name' => 'View' ] ,
                    'carType.add'      => [ 'name' => 'Add' ] ,
                    'carType.edit'     => [ 'name' => 'Edit' ] ,
                    'carType.activate' => [ 'name' => 'Activate/deactivate' ] ,
                    'carType.delete'   => [ 'name' => 'Delete' ]
                ]
            ] ,
            'car'                    => [
                'name'  => 'Car' ,
                'roles' => [
                    'car.view'     => [ 'name' => 'View' ] ,
                    'car.add'      => [ 'name' => 'Add' ] ,
                    'car.edit'     => [ 'name' => 'Edit' ] ,
                    'car.activate' => [ 'name' => 'Activate/deactivate' ] ,
                    'car.delete'   => [ 'name' => 'Delete' ]
                ]
            ] ,
            'postBlog'               => [
                'name'  => 'Blog' ,
                'roles' => [
                    'postBlog.view'     => [ 'name' => 'View' ] ,
                    'postBlog.add'      => [ 'name' => 'Add' ] ,
                    'postBlog.edit'     => [ 'name' => 'Edit' ] ,
                    'postBlog.activate' => [ 'name' => 'Activate/deactivate' ] ,
                    'postBlog.delete'   => [ 'name' => 'Delete' ]
                ]
            ] ,
            'postMedia'              => [
                'name'  => 'We at media' ,
                'roles' => [
                    'postMedia.view'     => [ 'name' => 'View' ] ,
                    'postMedia.add'      => [ 'name' => 'Add' ] ,
                    'postMedia.edit'     => [ 'name' => 'Edit' ] ,
                    'postMedia.activate' => [ 'name' => 'Activate/deactivate' ] ,
                    'postMedia.delete'   => [ 'name' => 'Delete' ]
                ]
            ] ,
            'campaign'               => [
                'name'  => 'Campaigns' ,
                'roles' => [
                    'campaign.view'     => [ 'name' => 'View' ] ,
                    'campaign.add'      => [ 'name' => 'Add' ] ,
                    'campaign.edit'     => [ 'name' => 'Edit' ] ,
                    'campaign.activate' => [ 'name' => 'Activate/deactivate' ] ,
                    'campaign.delete'   => [ 'name' => 'Delete' ]
                ]
            ] ,
            'economCampaignActivity' => [
                'name'  => 'Econom campaign activities' ,
                'roles' => [
                    'economCampaignActivity.view'     => [ 'name' => 'View' ] ,
                    'economCampaignActivity.add'      => [ 'name' => 'Add' ] ,
                    'economCampaignActivity.edit'     => [ 'name' => 'Edit' ] ,
                    'economCampaignActivity.activate' => [ 'name' => 'Activate/deactivate' ] ,
                    'economCampaignActivity.delete'   => [ 'name' => 'Delete' ]
                ]
            ] ,
            'economCampaign'         => [
                'name'  => 'Econom campaigns' ,
                'roles' => [
                    'economCampaign.view'     => [ 'name' => 'View' ] ,
                    'economCampaign.add'      => [ 'name' => 'Add' ] ,
                    'economCampaign.edit'     => [ 'name' => 'Edit' ] ,
                    'economCampaign.activate' => [ 'name' => 'Activate/deactivate' ] ,
                    'economCampaign.delete'   => [ 'name' => 'Delete' ]
                ]
            ] ,
            'hourlyCampaignActivity' => [
                'name'  => 'Hourly campaign activities' ,
                'roles' => [
                    'hourlyCampaignActivity.view'     => [ 'name' => 'View' ] ,
                    'hourlyCampaignActivity.add'      => [ 'name' => 'Add' ] ,
                    'hourlyCampaignActivity.edit'     => [ 'name' => 'Edit' ] ,
                    'hourlyCampaignActivity.activate' => [ 'name' => 'Activate/deactivate' ] ,
                    'hourlyCampaignActivity.delete'   => [ 'name' => 'Delete' ]
                ]
            ] ,
            'hourlyCampaign'         => [
                'name'  => 'Hourly campaigns' ,
                'roles' => [
                    'hourlyCampaign.view'     => [ 'name' => 'View' ] ,
                    'hourlyCampaign.add'      => [ 'name' => 'Add' ] ,
                    'hourlyCampaign.edit'     => [ 'name' => 'Edit' ] ,
                    'hourlyCampaign.activate' => [ 'name' => 'Activate/deactivate' ] ,
                    'hourlyCampaign.delete'   => [ 'name' => 'Delete' ]
                ]
            ] ,
            'vacancy'                => [
                'name'  => 'Vacancies' ,
                'roles' => [
                    'vacancy.view'     => [ 'name' => 'View' ] ,
                    'vacancy.add'      => [ 'name' => 'Add' ] ,
                    'vacancy.edit'     => [ 'name' => 'Edit' ] ,
                    'vacancy.activate' => [ 'name' => 'Activate/deactivate' ] ,
                    'vacancy.delete'   => [ 'name' => 'Delete' ]
                ]
            ] ,
            'employee'               => [
                'name'  => 'Employees' ,
                'roles' => [
                    'employee.view'     => [ 'name' => 'View' ] ,
                    'employee.add'      => [ 'name' => 'Add' ] ,
                    'employee.edit'     => [ 'name' => 'Edit' ] ,
                    'employee.activate' => [ 'name' => 'Activate/deactivate' ] ,
                    'employee.delete'   => [ 'name' => 'Delete' ]
                ]
            ] ,
            'slider'                 => [
                'name'  => 'Sliders' ,
                'roles' => [
                    'slider.view'     => [ 'name' => 'View' ] ,
                    'slider.add'      => [ 'name' => 'Add' ] ,
                    'slider.edit'     => [ 'name' => 'Edit' ] ,
                    'slider.activate' => [ 'name' => 'Activate/deactivate' ] ,
                    'slider.delete'   => [ 'name' => 'Delete' ]
                ]
            ] ,
            'site'                   => [
                'name'  => 'Site info' ,
                'roles' => [
                    'site.view'    => [ 'name' => 'View' ] ,
                    'site.edit'    => [ 'name' => 'Edit' ] ,
                    'site.mission' => [
                        'name'  => 'Mission' ,
                        'roles' => [
                            'site.mission.view'     => [ 'name' => 'View' ] ,
                            'site.mission.add'      => [ 'name' => 'Add' ] ,
                            'site.mission.edit'     => [ 'name' => 'Edit' ] ,
                            'site.mission.activate' => [ 'name' => 'Activate/deactivate' ] ,
                            'site.mission.delete'   => [ 'name' => 'Delete' ]
                        ]
                    ]
                ]
            ]
        ];
    }


    private function addRolesToAdmin( $roles , $requestRoles , $id )
    {
        foreach( $roles as $role => $data )
        {
            if( in_array( $role , $requestRoles ) && $role != self::ADMIN_ROLE )
                DB ::table( AdminRole::TABLE ) -> insert( [ 'admin_id' => $id , 'role' => $role ] );

            elseif( isset( $data[ 'roles' ] ) && is_array( $data[ 'roles' ] ) && count( $data[ 'roles' ] ) )
                $this -> addRolesToAdmin( $data[ 'roles' ] , $requestRoles , $id );
        }
    }


    public static $roles = [];


    public static function CAN( $role , $all = false , $id = 0 )
    {
        $id = $id ? $id : Auth ::id();

        $userRoles = [];

        $roles = AdminRole ::where( 'admin_id' , $id ) -> select( 'role' ) -> get();

        foreach( $roles as $a ) $userRoles[] = $a -> role;

        if( $id == 1 || in_array( self::ADMIN_ROLE , $userRoles ) )
        {
            if( $all ) self ::addRolesToCan_( self ::ROLES() );

            else return true;
        }

        self ::addRolesToCan( self ::ROLES() , $userRoles );

        if( $all ) return self ::$roles;

        else
        {
            if( is_array( $role ) )
            {
                foreach( $role as $a )
                    if( in_array( $a , self ::$roles ) ) return true;
            }

            elseif( in_array( $role , self ::$roles ) ) return true;
        }

        return false;
    }


    public static function addRolesToCan( $roles , $userRoles )
    {
        foreach( $roles as $role => $data )
        {
            if( in_array( $role , $userRoles ) )
            {
                self ::$roles[] = $role;

                if( isset( $data[ 'roles' ] ) && is_array( $data[ 'roles' ] ) && count( $data[ 'roles' ] ) )
                    self ::addRolesToCan_( $data[ 'roles' ] );
            }

            elseif( isset( $data[ 'roles' ] ) && is_array( $data[ 'roles' ] ) && count( $data[ 'roles' ] ) )
                self ::addRolesToCan( $data[ 'roles' ] , $userRoles );
        }
    }

    public static function addRolesToCan_( $roles )
    {
        foreach( $roles as $role => $data )
        {
            self ::$roles[] = $role;

            if( isset( $data[ 'roles' ] ) && is_array( $data[ 'roles' ] ) && count( $data[ 'roles' ] ) )
                self ::addRolesToCan_( $data[ 'roles' ] );
        }
    }
}
