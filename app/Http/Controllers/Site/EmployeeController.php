<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function teamPage()
    {
        try
        {
            $employees = DB ::table( Employee::TABLE )
                            -> where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 )
                            -> select( self ::LANG_PARAM( 'name' ) , self ::LANG_PARAM( 'position' ) , 'mobile' , 'email' , 'facebook' , 'instagram' , 'twitter' , 'linkedin' , 'photo' )
                            -> get()
            ;

            $vacancies = DB ::table( Vacancy::TABLE )
                            -> where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 )
                            -> select( 'id' , self ::LANG_PARAM( 'title' ) , self ::LANG_PARAM( 'text' ) , 'photo' )
                            -> get()
            ;

            $site = SiteController ::SITE();

            return view( 'site.team' , compact( [ 'employees' , 'vacancies' , 'site' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
