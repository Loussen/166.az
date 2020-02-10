<?php
/**
 * Created by PhpStorm.
 * User: Khayyam
 * Date: 05-Oct-19
 * Time: 14:32
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\OrderServiceInputs;
use App\Models\OrderServices;
use App\Models\OrderTransactions;
use App\Models\Service;
use App\Models\ServiceInput;
use App\Models\ServiceInputOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function showOrderPage()
    {
        try
        {
            if( ! AdminController ::CAN( 'order.view' ) ) return redirect() -> route( 'login' );

            return view( 'admin.order' );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getOrderList( Request $request )
    {
        try
        {
            if( ! AdminController ::CAN( 'order.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $SELECT = " SELECT * ";

            $FROM = " FROM " . Orders::TABLE . " o ";

            $WHERE = " WHERE o.is_deleted = 0 ";


            $filter = [
                'name'   => [ 'type' => 'search' , 'db' => [ 'o.name'] ] ,
                'phone'   => [ 'type' => 'search' , 'db' => [ 'o.phone'] ] ,
                'total'   => ['db' =>  'o.total' ] ,
                'status' => [ 'db' => 'o.status' ],
                'is_order' => [ 'db' => 'o.is_order' ]
            ];

            return $this -> returnList( $request , $SELECT , $FROM , $WHERE , $filter , [] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function getOrder( Request $request )
    {
//        try
//        {
            if( ! AdminController ::CAN( 'order.view' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $order = Orders ::where( 'id' , $id ) -> first();
            $order_transaction = OrderTransactions ::where( 'order_id' , $id ) -> first();
            $order->transaction = $order_transaction;

            $order_services = OrderServices::where('order_id', $order->id)->get();

            $order_service_list = [];
            foreach($order_services as $order_service) {
                $order_service_inputs = OrderServiceInputs::where('order_service_id', $order_service->id)->get();
                $order_service_input_list = [];
                foreach($order_service_inputs as $order_service_input) {
                    $service_input = ServiceInput::where('id', $order_service_input->input_id)->first();
                    if($service_input->type == 'select') {
                        $value = ServiceInputOption::where('id', $order_service_input->value)->first()->name_az;
                    } else {
                        $value = $order_service_input->value;
                    }

                    $order_service_input_list[] = [
                        'name'  => $service_input->name_az,
                        'value' => $value
                    ];
                }


                $order_service_list[] = [
                  'service' => Service::where('id', $order_service->service_id)->first()->name_az,
                  'child_service' => Service::where('id', $order_service->child_service_id)->first()->name_az,
                  'inputs'  => $order_service_input_list
                ];
            }

            return response() -> json( [ 'status' => 'success' , 'data' => ['order' => $order, 'services' => $order_service_list]]);
//        }
//        catch( \Exception $exception )
//        {
//            return $this -> getException( $exception );
//        }
    }


    public function editOrder( Request $request )
    {

    }

    public function deleteOrder( Request $request )
    {

            if( ! AdminController ::CAN( 'order.delete' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            Orders ::where( 'id' , $id ) -> update( [ 'is_deleted' => 1 ] );
            OrderServices ::where( 'order_id' , $id ) -> update( [ 'is_deleted' => 1 ] );
            OrderServiceInputs ::where( 'order_id' , $id ) -> update( [ 'is_deleted' => 1 ] );
            OrderTransactions ::where( 'order_id' , $id ) -> update( [ 'is_deleted' => 1 ] );

            return response() -> json( [ 'status' => 'success' ] );

    }


    public function getOrderSearch( Request $request )
    {
        $query = ' SELECT s.id , s.name_en `text` FROM ' . Orders::TABLE . ' s LEFT JOIN ' . Orders::TABLE . " p ON s.parent_id = p.id AND s.is_deleted = 0 AND s.is_active = 1 WHERE ( s.parent_id IS NULL OR ( p.is_deleted = 0 AND p.is_active = 1 AND p.parent_id IS NULL ) ) ";

        $parent = $this -> validator -> validateId( $request -> request -> get( 'parent' ) );

        $except = $this -> validator -> validateId( $request -> request -> get( 'except' ) );

        $search = $this -> clear( $request -> request -> get( 'search' ) );

        if( $parent ) $query .= ' AND s.parent_id IS NULL ';

        if( $except ) $query .= " AND s.id <> $except ";

        if( $search ) $query .= " AND ( s.name_en LIKE '%$search%' OR s.name_az LIKE '%$search%' OR s.name_ru LIKE '%$search%' ) ";

        $orders = [];

        if( $request -> request -> get( 'all' ) == 1 ) $orders = [ (object)[ 'id' => 'All' , 'text' => 'All' ] ];

        if( $request -> request -> get( 'order' ) == 1 ) $orders = [ (object)[ 'id' => 'Parent' , 'text' => 'Parent' ] ];

        $orders = array_merge( $orders , DB ::select( " $query LIMIT 20 ; " ) );

        return response() -> json( [ 'status' => 'success' , 'data' => $orders ] );
    }
}
