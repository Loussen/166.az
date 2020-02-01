<?php


namespace App\Http\Service;


use Illuminate\Support\Facades\Storage;

class Exception
{
    public static function log( \Exception $exception )
    {
        $message = date( 'Y-m-d H:i:s' ) . " \n" . $exception -> getMessage() . " \n in " . $exception -> getFile() . ' on line ' . $exception -> getLine() . "\n--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- \n";

        Storage ::put( 'log/exception.txt' , $message . Storage ::get( 'log/exception.txt' ) );
    }
}
