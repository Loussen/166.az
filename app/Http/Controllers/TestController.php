<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function __construct()
    {
        parent ::__construct();
    }


    public function test()
    {
        try
        {


            return response() -> json( 'success' );
        }
        catch( \Exception $exception )
        {
            return $this -> getException( $exception );
        }
    }
}
