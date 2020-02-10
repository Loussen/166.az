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
        try
        {
            if( ! AdminController ::CAN( 'order.edit' ) ) return response() -> json( [ 'status' => 'success' , 'warning' => 'Access denied' ] );

            $id = $this -> validator -> validateId( $request -> request -> get( 'id' ) );

            $validations = $this -> validator -> validateForm(
                $request ,
                [
                    'name_az' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                    'name_en' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,
                    'name_ru' => [ 'type' => 'string' , 'required' => true , 'max' => 555 ] ,

                    'individual_headline_en' => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,
                    'individual_headline_az' => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,
                    'individual_headline_ru' => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,

                    'corporate_headline_en' => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,
                    'corporate_headline_az' => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,
                    'corporate_headline_ru' => [ 'type' => 'string' , 'required' => true , 'max' => 5555 ] ,

                    'include_headline_en' => [ 'type' => 'string' , 'required' => false , 'max' => 5555 ] ,
                    'include_headline_az' => [ 'type' => 'string' , 'required' => false , 'max' => 5555 ] ,
                    'include_headline_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 5555 ] ,

                    'extra_info_headline_en' => [ 'type' => 'string' , 'required' => false , 'max' => 5555 ] ,
                    'extra_info_headline_az' => [ 'type' => 'string' , 'required' => false , 'max' => 5555 ] ,
                    'extra_info_headline_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 5555 ] ,

                    'individual_description_en' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'individual_description_az' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'individual_description_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,

                    'corporate_description_en' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'corporate_description_az' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'corporate_description_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,

                    'seo_keywords_en'    => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'seo_keywords_az'    => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'seo_keywords_ru'    => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'seo_description_en' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'seo_description_az' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,
                    'seo_description_ru' => [ 'type' => 'string' , 'required' => false , 'max' => 55555 ] ,

                    'price' => [ 'type' => 'numeric' , 'required' => false ]
                ]
            );

            if( ! count( $validations ) )
            {
                $order = Orders ::where( 'id' , $id ) -> first();

                $parent = $this -> validator -> validateId( $request -> request -> get( 'parent' ) );
                if( ! $parent ) $parent = null;

                $parameters = [
                    'name_en' => $request -> request -> get( 'name_en' ) ,
                    'name_az' => $request -> request -> get( 'name_az' ) ,
                    'name_ru' => $request -> request -> get( 'name_ru' ) ,

                    'individual_headline_en' => $request -> request -> get( 'individual_headline_en' ) ,
                    'individual_headline_az' => $request -> request -> get( 'individual_headline_az' ) ,
                    'individual_headline_ru' => $request -> request -> get( 'individual_headline_ru' ) ,

                    'corporate_headline_en' => $request -> request -> get( 'corporate_headline_en' ) ,
                    'corporate_headline_az' => $request -> request -> get( 'corporate_headline_az' ) ,
                    'corporate_headline_ru' => $request -> request -> get( 'corporate_headline_ru' ) ,

                    'include_headline_en' => $request -> request -> get( 'include_headline_en' ) ,
                    'include_headline_az' => $request -> request -> get( 'include_headline_az' ) ,
                    'include_headline_ru' => $request -> request -> get( 'include_headline_ru' ) ,

                    'extra_info_headline_en' => $request -> request -> get( 'extra_info_headline_en' ) ,
                    'extra_info_headline_az' => $request -> request -> get( 'extra_info_headline_az' ) ,
                    'extra_info_headline_ru' => $request -> request -> get( 'extra_info_headline_ru' ) ,

                    'individual_description_en' => $request -> request -> get( 'individual_description_en' ) ,
                    'individual_description_az' => $request -> request -> get( 'individual_description_az' ) ,
                    'individual_description_ru' => $request -> request -> get( 'individual_description_ru' ) ,

                    'corporate_description_en' => $request -> request -> get( 'corporate_description_en' ) ,
                    'corporate_description_az' => $request -> request -> get( 'corporate_description_az' ) ,
                    'corporate_description_ru' => $request -> request -> get( 'corporate_description_ru' ) ,

                    'seo_keywords_en'    => $request -> request -> get( 'seo_keywords_en' ) ,
                    'seo_keywords_az'    => $request -> request -> get( 'seo_keywords_az' ) ,
                    'seo_keywords_ru'    => $request -> request -> get( 'seo_keywords_ru' ) ,
                    'seo_description_en' => $request -> request -> get( 'seo_description_en' ) ,
                    'seo_description_az' => $request -> request -> get( 'seo_description_az' ) ,
                    'seo_description_ru' => $request -> request -> get( 'seo_description_ru' ) ,

                    'price' => $request -> request -> get( 'price' ) ,

                    'parent_id' => $parent
                ];

                if( $order && is_object( $order ) )
                {
                    DB ::table( Orders::TABLE ) -> where( 'id' , $id ) -> update( $parameters );
                }
                else
                {
                    $id = DB ::table( Orders::TABLE ) -> insertGetId( $parameters );
                }
            }

            return response() -> json( [ 'status' => 'success' , 'validations' => $validations ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
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
