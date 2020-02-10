<?php
/**
 * Created by PhpStorm.
 * User: Khayyam
 * Date: 05-Oct-19
 * Time: 14:32
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function showCouponPage()
    {
        try {
            if (!AdminController::CAN('coupon.view')) return redirect()->route('login');
            $services = static ::BUILD_TREE( Service ::where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 ) -> select( 'id' , 'parent_id' , static ::LANG_PARAM( 'name' ) ) -> get() );
            return view('admin.coupon', compact('services'));
        } catch (\Exception $exception) {
            return $this->getException($exception);
        }
    }


    public function getCouponList(Request $request)
    {
        try {
            if (!AdminController::CAN('coupon.view')) return response()->json(['status' => 'success', 'warning' => 'Access denied']);

            $SELECT = " SELECT * ";

            $FROM = " FROM " . Coupon::TABLE . " o ";

            $WHERE = " WHERE o.is_deleted = 0 ";


            $filter = [
                'code' => ['type' => 'search', 'db' => ['o.code']],
                'phone' => ['type' => 'search', 'db' => ['o.phone']],
                'is_active' => ['db' => 'o.is_active']
            ];

            return $this->returnList($request, $SELECT, $FROM, $WHERE, $filter, []);
        } catch (\Exception $exception) {
            return $this->getException($exception);
        }
    }

    public function activateCoupon( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'callback.activate' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $active = $this -> validator -> validateBool( $request -> request -> get( 'active' ) );

            $code = $active ? $this->generateRandomString(6) : '';

            Coupon ::where( 'id' , $id ) -> update( [ 'is_active' => $active, 'code' => $code ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public function deleteCoupon( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'coupon.delete' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            Coupon ::where( 'id' , $id ) -> update( [ 'is_deleted' => 1 ] );

            return response() -> json( [ 'status' => 'success' ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
