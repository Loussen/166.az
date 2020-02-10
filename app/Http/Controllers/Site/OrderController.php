<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\CarType;
use App\Models\Orders;
use App\Models\OrderServiceInputs;
use App\Models\OrderServices;
use App\Models\OrderTransactions;
use App\Models\Service;
use App\Models\ServiceInput;
use App\Models\ServiceInputOption;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use \OpenPaymentSolutions\TranzWarePaymentGateway\TranzWarePaymentGatewayRequestFactory;
use \OpenPaymentSolutions\TranzWarePaymentGateway\CurrencyCodes;
use \OpenPaymentSolutions\TranzWarePaymentGateway\OrderTypes;
use \OpenPaymentSolutions\TranzWarePaymentGateway\TranzWarePaymentGatewayHandlerFactory;
use Illuminate\Support\Facades\Config;

class OrderController extends Controller
{
    public function orderPage()
    {
        try
        {
            $services = ServiceController ::ALL_SERVICES();

            $carTypes = CarType ::where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 ) -> select( 'id' , self ::LANG_PARAM( 'name' ) ) -> get();

            return view( 'site.order' , compact( [ 'services' , 'carTypes' ] ) );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function calculate( Request $request )
    {
//        echo "<pre>".print_r($_POST, true)."</pre>"; exit;
//        $id = $this -> validator -> validateId( $request -> request -> get( 'parent' ) );
//        try
//        {
            $validations = [
                'name'    => [ 'type' => 'string' , 'required' => true , 'max' => 55 ] ,
                'phone'   => [ 'type' => 'string' , 'required' => true , 'max' => 22 ] ,
            ];

            $validations = $this -> validator -> validateForm( $request , $validations );

            $formula = [];

            $checkOrder = Orders ::where( '_token' , $request -> request -> get( 'csrf_token' ) ) -> first();

            if( !count($validations) )
            {
                $parameters = [
                    'name'      => $request -> request -> get( 'name' ) ,
                    'phone'     => $request -> request -> get( 'phone' ) ,
                    'is_order'  => 0 ,
                    '_token'    => $request -> request -> get( 'csrf_token' ) ,
                ];

                if( !$checkOrder && !is_object( $checkOrder ) )
                {
                    DB ::table( Orders::TABLE ) -> insert( $parameters );
                    $checkOrder = Orders ::where( '_token' , $request -> request -> get( 'csrf_token' ) ) -> first();

                }
            }

            if( $checkOrder && is_object( $checkOrder ) )
            {
                if( !count($validations) )
                {
                    $servicesInfo = [];

                    OrderServices::where('order_id', $checkOrder -> id)->delete();

                    foreach($request -> request -> get( 'services' ) as $keyService=>$valueService)
                    {
                        $id = $this -> validator -> validateId( $valueService[ 'parent' ] );

                        if(isset($valueService[ 'children' ]) && !empty($valueService[ 'children' ]))
                            $children = $this -> validator -> validateId( $valueService[ 'children' ] );

                        $serviceInputs = $valueService;

                        $data = [];

                        $service = DB ::table( Service::TABLE )
                            -> where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 )
                            -> where( 'id' , $id )
                            -> select(
                                'price',
                                static ::LANG_PARAM( 'name')
                            )
                            -> first()
                        ;

                        $formula[$keyService][$id] = 0;

                        if( $service )
                        {
                            $serviceInputsIdsArr = [];
                            $inArraySql = [];
                            foreach ($serviceInputs as $key=>$val)
                            {
                                if(intval($key)!=0)
                                {
                                    $inArraySql[] = $key;
                                }

                                $serviceInputsIdsArr[$key]['key'] = $key;
                                $serviceInputsIdsArr[$key]['value'] = $val;
                            }

                            if($service -> price > 0)
                            {
                                $formula[$keyService][$id] += $service -> price; // base price service formula
                            }

                            if(isset($valueService['addressInfo']) && !empty($valueService['addressInfo']))
                            {
                                $distanceCoefficient = 1;
                                $durationCoefficient = 1.5;
                                $mapInfo = explode("-",$valueService['addressInfo']); // 0=>distance, 1=>minute
                                $formula[$keyService][$id] += $mapInfo[0] * $distanceCoefficient + $mapInfo[1] * $durationCoefficient; // map formula
                            }

                            $serviceInputs = ServiceInput ::where( 'is_deleted' , 0 ) -> whereIn('id', $inArraySql) -> where( 'is_active' , 1 ) -> where( 'service_id' , $id ) -> select( 'id' , 'type'  , 'step' , 'coefficient' , static ::LANG_PARAM( 'name' ) ) -> get();

                            foreach( $serviceInputs as $k => $input )
                            {
                                if($input -> type == 'select')
                                {
                                    $serviceInputsOptions = ServiceInputOption ::where( 'id' , $serviceInputsIdsArr[$input -> id]['value'] ) -> select( 'id' , 'coefficient' , static ::LANG_PARAM( 'name' ) ) -> get();

                                    foreach ($serviceInputsOptions as $kOption => $inputsOption)
                                    {
                                        if($inputsOption -> coefficient > 0)
                                        {
                                            $formula[$keyService][$id] += $inputsOption -> coefficient;
                                        }
                                    }
                                }

                                if($input -> coefficient > 0 && $input -> type == 'number')
                                {
                                    $formula[$keyService][$id] += $input -> coefficient * $serviceInputsIdsArr[$input -> id]['value'];
                                }

                                $servicesInfo[$keyService]['name'] = $service -> name;
                                $servicesInfo[$keyService]['id'] = $service -> name;
                                $servicesInfo[$keyService]['total'] = $formula[$keyService][$id];
                            }

                            $parametersOrders = [
                                'order_id'  => $checkOrder -> id ,
                                'service_id'  => $id ,
                                'price'  => $formula[$keyService][$id] ,
                                'child_service_id'  => $children ,
                            ];

                            $orderServiceId = DB ::table( OrderServices::TABLE ) -> insertGetId( $parametersOrders );

                            OrderServiceInputs::where('order_id', $checkOrder -> id) -> delete();

                            foreach ($serviceInputsIdsArr as $valueIdsArr)
                            {
                                if(intval($valueIdsArr['key']) > 0)
                                {
                                    $parametersOrdersInputs = [
                                        'order_id'  => $checkOrder -> id ,
                                        'order_service_id'  =>  $orderServiceId,
                                        'input_id'  => $valueIdsArr['key'] ,
                                        'value'  => $valueIdsArr['value'] ,
                                    ];

                                    DB ::table( OrderServiceInputs::TABLE ) -> insert( $parametersOrdersInputs );
                                }
                            }
                        }
                    }

                    $totalServices = 0;
                    foreach ($formula as $keyTotal=>$valueTotal)
                    {
                        foreach ($valueTotal as $valueTotal2)
                        {
                            $totalServices += $valueTotal2;
                        }
                    }

                    $parameters['total'] = $totalServices;

                    $checkOrder -> update( $parameters );

                    return response() -> json( [ 'status' => 'success' , 'total' => $totalServices , 'servicesInfo' => $servicesInfo , 'validations' => $validations ] );

                }
                else
                {
                    return response() -> json( [ 'status' => 'false' , 'total' => 0 , 'servicesInfo' => [] , 'validations' => $validations ] );
                }
            }


//        }
//        catch( \Exception $exception )
//        {
//            return $this -> getException( $exception );
//        }
    }


    public function order( Request $request )
    {
//        try
//        {
            //$parent = Service ::where( 'id' , $request -> request -> get( 'parent' ) ) -> select( self ::LANG_PARAM( 'name' ) ) -> first();

            //$service = Service ::where( 'id' , $request -> request -> get( 'service' ) ) -> select( self ::LANG_PARAM( 'name' ) ) -> first();

//            Mail ::send( 'mail.order-admin' , [
//                'name'      => $request -> request -> get( 'name' ) ,
//                'phone'     => $request -> request -> get( 'phone' ) ,
//                'address_1' => $request -> request -> get( 'address_1' ) ,
//                'address_2' => $request -> request -> get( 'address_2' ) ,
//                'date'      => $request -> request -> get( 'date' ) ,
//                'hour'      => $request -> request -> get( 'hour' ) ,
//                'parent'    => $parent -> name ,
//                'service'   => isset( $service -> name ) ? $service -> name : null ,
//            ] , function( $message )
//            {
//                $message -> to( env( 'EMAIL' ) )
//                         -> subject( 'New order' )
//                ;
//            } );

            $checkOrder = Orders ::where( '_token' , $request -> request -> get( 'csrf_token' ) ) -> first();

            if( $checkOrder && is_object($checkOrder) )
            {
                $parameters = [
                    'is_order'  => 1
                ];

                $checkOrder -> update( $parameters );

                $requestFactory = new TranzWarePaymentGatewayRequestFactory(
                    'https://e-commerce.kapitalbank.az:5443/Exec',
                    'E1000010',
                    route('site.order_approved'),
                    route('site.order_declined'),
                    route('site.order_canceled'),
                    strtoupper(Config::get('app.locale'))
                );
                $keyFile = resource_path('certificate/birlinkde1.pem');
                $keyPass = '123456';
                $certFile = resource_path('certificate/birlinkde.pem');

                $requestFactory
                    ->setCertificate($certFile, $keyFile, $keyPass)
                    //->disableSSLVerification() // for dev environment or if no need to validate SSL host
                    ->setDebugFile(resource_path('debug.log'));
                $orderRequest = $requestFactory->createOrderRequest($checkOrder -> total * 100, CurrencyCodes::AZN, $checkOrder -> id, OrderTypes::PURCHASE);
                /**
                 * or shorthand:
                 * $orderRequest = $requestFactory->createPurchaseOrderRequest(1, CurrencyCodes::USD, 'TEST PAYMENT $0.01');
                 */
                $orderRequestResult = $orderRequest->execute();
                if ($orderRequestResult->success()) {
                    $orderData = $orderRequestResult->getData();

                    $parametersTransaction = [
                        'order_id'  => $checkOrder->id ,
                        'OrderId'   => $orderData['OrderId'] ,
                        'SessionId' => $orderData['SessionId']
                    ];

                    DB ::table( OrderTransactions::TABLE ) -> insert( $parametersTransaction );

                    return response() -> json( [ 'status' => 'success', 'redirect' =>  $orderData['PaymentUrl']] );
                } else {
                    return response() -> json( [ 'status' => 'false'] );
                }
            }
            else
            {
                return response() -> json( [ 'status' => 'false'] );
            }

//        }
//        catch( \Exception $exception )
//        {
//            return $this -> getException( $exception );
//        }
    }

    public function order_approved()
    {
        $handlerFactory = new TranzWarePaymentGatewayHandlerFactory();
        $orderCallbackHandler = $handlerFactory->createOrderCallbackHandler();
        $orderStatusData = $orderCallbackHandler->handle();

       if($orderStatusData) {
           $OrderId = $orderStatusData['OrderId'];

           $getTransaction = OrderTransactions ::where( 'OrderId' , $OrderId ) -> first();

           $getTransaction -> update( ['result' => $orderStatusData['xmlmsg']] );

           if($orderStatusData['OrderStatus'] == 'APPROVED')
           {
               Orders::where( 'id', $getTransaction['order_id'] ) -> first() -> update( ['status' => 1 ] ); // Paid
           }
           else
           {
               Orders::where( 'id', $getTransaction['order_id'] ) -> first() -> update( ['status' => 2 ] ); // UnPaid
           }
           $site = self ::SITE();
           return view( 'site.order_approved' , compact( ['site'] ) );
       } else {
           return $this -> _404();
       }

    }

    public function order_canceled()
    {
        $handlerFactory = new TranzWarePaymentGatewayHandlerFactory();
        $orderCallbackHandler = $handlerFactory->createOrderCallbackHandler();
        $orderStatusData = $orderCallbackHandler->handle();

        if($orderStatusData) {
            $OrderId = $orderStatusData['OrderId'];

            $getTransaction = OrderTransactions ::where( 'OrderId' , $OrderId ) -> first();

            $getTransaction -> update( ['result' => $orderStatusData['xmlmsg']] );

            if($orderStatusData['OrderStatus'] == 'CANCELED')
            {
                Orders::where( 'id', $getTransaction['order_id'] ) -> first() -> update( ['status' => 2 ] ); // UnPaid
            }
            $site = self ::SITE();
            return view( 'site.order_failed' , compact( ['site'] ) );
        } else
        {
            return $this -> _404();
        }
    }

    public  function order_declined()
    {
        $handlerFactory = new TranzWarePaymentGatewayHandlerFactory();
        $orderCallbackHandler = $handlerFactory->createOrderCallbackHandler();
        $orderStatusData = $orderCallbackHandler->handle();
        if($orderStatusData) {
            $OrderId = $orderStatusData['OrderId'];

            $getTransaction = OrderTransactions::where('OrderId', $OrderId)->first();

            $getTransaction->update(['result' => $orderStatusData['xmlmsg']]);

            if ($orderStatusData['OrderStatus'] == 'DECLINED') {
                Orders::where('id', $getTransaction['order_id'])->first()->update(['status' => 2]); // UnPaid
            }
            $site = self ::SITE();
            return view( 'site.order_failed' , compact( ['site'] ) );

        }
        else {
            return $this -> _404();
        }
    }

    public static function SITE()
    {
        return Site ::select(
            self ::LANG_PARAM( 'text' ) ,
            self ::LANG_PARAM( 'title' ) ,
            self ::LANG_PARAM( 'mission' ) ,
            self ::LANG_PARAM( 'address' ) ,
            self ::LANG_PARAM( 'corporate_contact' ) ,
            self ::LANG_PARAM( 'about_seo_keywords' ) ,
            self ::LANG_PARAM( 'about_seo_description' ) ,
            'index' ,
            'mobile' , 'email' ,
            'ad_mobile' , 'ad_email' ,
            'order_mobile' , 'order_email' ,
            'facebook' , 'instagram' , 'youtube' , 'linkedin' ,
            'transported_objects' , 'cleaned_places' , 'customer_reviews' , 'satisfied_customers' ,
            'background' , 'contact_background'
        ) -> first()
            ;
    }
}
