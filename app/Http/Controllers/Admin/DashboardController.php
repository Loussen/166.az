<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function dashboard()
    {
        try
        {
            if( ! AdminController ::CAN( 'dashboard' ) ) return redirect() -> route( 'admin.settings' );

            return view( 'admin.dashboard' );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
