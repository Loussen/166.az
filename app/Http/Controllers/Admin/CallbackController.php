<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Callback;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CallbackController extends Controller
{
    public function showCallbackPage()
    {
        try {
            if (!AdminController::CAN('callback.view')) return redirect()->route('login');
            $services = static ::BUILD_TREE( Service ::where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 ) -> select( 'id' , 'parent_id' , static ::LANG_PARAM( 'name' ) ) -> get() );


            return view('admin.callback', compact('services'));
        } catch (\Exception $exception) {
            return $this->getException($exception);
        }
    }


    public function getCallbackList(Request $request)
    {
        try {
            if (!AdminController::CAN('callback.view')) return response()->json(['status' => 'success', 'warning' => 'Access denied']);

            $SELECT = " SELECT o.id , o.name `name` , o.phone phone , o.city `city` , o.phone service_id , o.is_active is_active, p.name_az service  ";

            $FROM = " FROM " . Callback::TABLE . " o LEFT JOIN " . Service::TABLE . " p ON o.service_id = p.id ";

            $WHERE = " WHERE o.is_deleted = 0 ";


            $filter = [
                'name' => ['type' => 'search', 'db' => ['o.name']],
                'phone' => ['type' => 'search', 'db' => ['o.phone']],
                'city' => ['db' => 'o.city'],
                'service_id' => ['db' => 'o.service_id'],
                'is_active' => ['db' => 'o.is_active']
            ];

            return $this->returnList($request, $SELECT, $FROM, $WHERE, $filter, []);
        } catch (\Exception $exception) {
            return $this->getException($exception);
        }
    }

    public function activateCallback( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'callback.activate' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $active = $this -> validator -> validateBool( $request -> request -> get( 'active' ) );

            Callback ::where( 'id' , $id ) -> update( [ 'is_active' => $active ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function deleteCallback( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'callback.delete' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            Callback ::where( 'id' , $id ) -> update( [ 'is_deleted' => 1 ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
