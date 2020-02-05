<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\CarType;
use App\Models\Service;
use App\Models\ServiceInput;
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
        try
        {
            $id = $this -> validator -> validateId( $request -> request -> get( 'parent' ) );
            $serviceInputs = $request -> request -> get( 'service' );

            $data = [];

            $validations = [
                'parent' => [ 'type' => 'numeric' , 'required' => true , 'exist' => Service::TABLE , 'db' => 'id' ] ,
                'name'    => [ 'type' => 'string' , 'required' => true , 'max' => 55 ] ,
                'phone'   => [ 'type' => 'string' , 'required' => true , 'max' => 22 ] ,
            ];

            $validations = $this -> validator -> validateForm( $request , $validations );

            $formula = 0;

            if( ! count( $validations ) )
            {
                $serviceInputsIdsArr = [];
                $inArraySql = [];
                foreach ($serviceInputs as $value)
                {
                    foreach ($value as $key=>$val)
                    {
                        if(intval($key)!=0)
                        {

                            $inArraySql[] = $key;
                        }

                        $serviceInputsIdsArr[$key]['key'] = $key;
                        $serviceInputsIdsArr[$key]['value'] = $val;
                    }
                }

                $service = DB ::table( Service::TABLE )
                    -> where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 )
                    -> where( 'id' , $id )
                    -> select(
                        'price' )
                    -> first()
                ;

                if($service -> price > 0)
                {
                    $formula += $service -> price; // base price service formula
                }

                $formula += 2*5; // map formula

                $serviceInputs = ServiceInput ::where( 'is_deleted' , 0 ) -> whereIn('id', $inArraySql) -> where( 'is_active' , 1 ) -> where( 'service_id' , $id ) -> select( 'id' , 'type' , 'step' , 'coefficient' , static ::LANG_PARAM( 'name' ) ) -> get();

                foreach( $serviceInputs as $k => $input )
                {

                    if($input -> coefficient > 0 && $input -> type == 'number')
                    {
                        $formula += $input -> coefficient * $serviceInputsIdsArr[$input -> id]['value'];
                    }
                }
            }

            return response() -> json( [ 'status' => 'success' , 'total' => $formula , 'validations' => $validations ] );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }


    public function order( Request $request )
    {
        try
        {
            $parent = Service ::where( 'id' , $request -> request -> get( 'parent' ) ) -> select( self ::LANG_PARAM( 'name' ) ) -> first();

            $service = Service ::where( 'id' , $request -> request -> get( 'service' ) ) -> select( self ::LANG_PARAM( 'name' ) ) -> first();

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


            $requestFactory = new TranzWarePaymentGatewayRequestFactory(
                'https://e-commerce.kapitalbank.az:5443/Exec',
                'E1000010',
                route('order_approved'),
                route('order_declined'),
                route('order_canceled'),
                strtoupper(Config::get('app.locale'))
            );
            $keyFile = resource_path('certificate/birlinkde.key');
            $keyPass = '123456';
            $certFile = resource_path('certificate/birlinkde.crt');
            $requestFactory
                ->setCertificate($certFile, $keyFile, $keyPass)
                ->disableSSLVerification() // for dev environment or if no need to validate SSL host
                ->setDebugFile(resource_path('debug.log'));
            $orderRequest = $requestFactory->createOrderRequest(1, CurrencyCodes::AZN, 'TEST PAYMENT $0.01', OrderTypes::PURCHASE);
            /**
             * or shorthand:
             * $orderRequest = $requestFactory->createPurchaseOrderRequest(1, CurrencyCodes::USD, 'TEST PAYMENT $0.01');
             */
            $orderRequestResult = $orderRequest->execute();
            if ($orderRequestResult->success()) {
                $orderData = $orderRequestResult->getData();
                return response() -> json( [ 'status' => 'success', 'redirect' =>  $orderData['PaymentUrl']] );
            } else {
                return response() -> json( [ 'status' => 'false'] );
            }

        }
        catch( \Exception $exception )
        {
            //return $this -> getException( $exception );
        }
    }

    public function order_approved()
    {
        $handlerFactory = new TranzWarePaymentGatewayHandlerFactory();
        $orderCallbackHandler = $handlerFactory->createOrderCallbackHandler();
        $orderStatusData = $orderCallbackHandler->handle();
        var_dump($orderStatusData);
    }

    public function order_canceled()
    {
        $handlerFactory = new TranzWarePaymentGatewayHandlerFactory();
        $orderCallbackHandler = $handlerFactory->createOrderCallbackHandler();
        $orderStatusData = $orderCallbackHandler->handle();
        var_dump($orderStatusData);
    }

    public  function order_declined()
    {
        $handlerFactory = new TranzWarePaymentGatewayHandlerFactory();
        $orderCallbackHandler = $handlerFactory->createOrderCallbackHandler();
        $orderStatusData = $orderCallbackHandler->handle();
        var_dump($orderStatusData);
    }
}
