<?php
/**
 * Created by PhpStorm.
 * User: Khayyam
 * Date: 05-Oct-18
 * Time: 14:34
 */

namespace App\Http\Service;

use App\Http\Controllers\LanguageController;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Validator
{
    private $errors = [];


    public function validateForm( Request $request , $credentials )
    {
        foreach( $credentials as $parameterName => $rules )
        {
            $parameter = $request -> request -> get( $parameterName );

            if( $parameter == null && isset( $rules[ 'required' ] ) && $rules[ 'required' ] )
            {
                $this -> errors[ $parameterName ][ 'required' ] = 'Parameter<' . $parameterName . '>IsNotPassed';

                if( ( $rules[ 'type' ] ?? '' ) == 'repeat' ) $this -> errors[ $parameterName . 'Repeat' ][ 'required' ] = 'Parameter<' . $parameterName . 'Repeat>IsNotPassed';
            }
            elseif( $parameter !== null && ( ( is_string( $parameter ) && strlen( $parameter ) ) || ( is_array( $parameter ) && count( $parameter ) ) ) )
            {
                if( isset( $rules ) && isset( $rules[ 'type' ] ) )
                {
                    if( $rules[ 'type' ] == 'parent' )
                    {
                        $this -> validateParent( $parameterName , $parameter , $rules );
                    }

                    elseif( $rules[ 'type' ] == 'foreign' )
                    {
                        $this -> validateForeign( $parameterName , $parameter , $rules );
                    }

                    elseif( $rules[ 'type' ] == 'link' )
                    {
                        $this -> validateLink( $parameterName , $parameter , $rules );
                    }

                    elseif( $rules[ 'type' ] == 'email' )
                    {
                        $this -> validateEmail( $parameterName , $parameter );
                    }

                    elseif( $rules[ 'type' ] == 'string' )
                    {
                        $this -> validateString( $parameterName , $parameter , $rules );
                    }

                    elseif( $rules[ 'type' ] == 'numeric' )
                    {
                        $this -> validateNumeric( $parameterName , $parameter );
                    }

                    elseif( $rules[ 'type' ] == 'boolean' )
                    {
                        $this -> validateBoolean( $parameterName , $parameter );
                    }

                    elseif( $rules[ 'type' ] == 'array' )
                    {
                        $this -> validateArray( $parameterName , $parameter , $rules );
                    }

                    elseif( $rules[ 'type' ] == 'date' || $rules[ 'type' ] == 'datetime' )
                    {
                        if( ! isset( $rules[ 'format' ] ) ) $rules[ 'format' ] = $rules[ 'type' ] == 'date' ? 'Y-m-d' : 'Y-m-d H:i:s';

                        $this -> validateDatetime( $parameterName , $parameter , $rules );
                    }

                    elseif( $rules[ 'type' ] == 'repeat' )
                    {
                        $repeatParameter = $request -> request -> get( $parameterName . 'Repeat' );

                        $this -> validateRepeat( $parameterName , $parameter , $repeatParameter , $rules );
                    }

                    if( isset( $rules[ 'unique' ] ) )
                    {
                        $this -> validateUniqueness( $parameterName , $parameter , $rules , $request );
                    }

                    if( isset( $rules[ 'exist' ] ) )
                    {
                        $this -> validateExistence( $parameterName , $parameter , $rules );
                    }

                    if( isset( $rules[ 'array' ] ) && is_array( $rules[ 'array' ] ) )
                    {
                        $this -> validateInArray( $parameterName , $parameter , $rules[ 'array' ] );
                    }
                }
            }
        }

        return $this -> errors;
    }


    public function validateId( $id = 0 )
    {
        return isset( $id ) && is_numeric( $id ) && (int)$id > 0 ? (int)$id : 0;
    }


    public function validateBool( $id = 0 )
    {
        return isset( $id ) && is_numeric( $id ) && (int)$id > 0 ? 1 : 0;
    }


    public function validateChecked( $state = '' )
    {
        return $state == 'on';
    }


    public function validateParent( $parameterName , $parameter , $rules )
    {
        $object = DB ::table( $rules[ 'table' ] )
                     -> select( 'id' )
                     -> where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 )
                     -> where( 'id' , '<>' , $rules[ 'except' ] )
                     -> where( 'id' , '=' , $parameter )
        ;

        $object = $object -> first();

        if( ! ( $object || ( isset( $rules[ 'nullable' ] ) && $rules[ 'nullable' ] && $parameter == 0 ) ) )
        {
            $this -> errors[ $parameterName ][ 'parent' ] = 'Parameter<' . $parameterName . '>IsNotValid';
        }
    }


    public function validateForeign( $parameterName , $parameter , $rules )
    {
        $object = DB ::table( $rules[ 'table' ] )
                     -> where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 )
                     -> select( 'id' )
                     -> where( 'id' , '=' , $parameter )
                     -> first()
        ;

        if( ! $object )
        {
            $this -> errors[ $parameterName ][ 'foreign' ] = 'Parameter<' . $parameterName . '>IsNotValid';
        }
    }


    public function validateLink( $parameterName , $parameter , $rules )
    {
        if( ! ( isset( $parameter ) && is_string( $parameter ) && strlen( $parameter ) && strlen( $parameter ) < $rules[ 'max' ] ?? 1111111 ) )
        {
            $this -> errors[ $parameterName ][] = 'Parameter<' . $parameterName . '>IsNotValid';
        }
    }


    public function validateEmail( $parameterName , $parameter )
    {
        if( ! ( isset( $parameter ) && is_string( $parameter ) && strlen( $parameter ) && filter_var( $parameter , FILTER_VALIDATE_EMAIL ) ) )
        {
            $this -> errors[ $parameterName ][] = 'Parameter<' . $parameterName . '>IsNotValid';
        }
    }


    public function validateString( $parameterName , $parameter , $rules )
    {
        if( ! ( isset( $parameter ) && is_string( $parameter ) && strlen( trim( $parameter ) ) && strlen( $parameter ) < ( $rules[ 'max' ] ?? 55 ) ) )
        {
            $this -> errors[ $parameterName ][] = 'Parameter<' . $parameterName . '>IsNotValid';
        }
    }


    public function validateNumeric( $parameterName , $parameter )
    {
        if( ! ( isset( $parameter ) && is_numeric( $parameter ) ) )
        {
            $this -> errors[ $parameterName ][] = 'Parameter<' . $parameterName . '>IsNotValid';
        }
    }


    public function validateBoolean( $parameterName , $parameter )
    {
        if( ! ( isset( $parameter ) && ( $parameter === 0 || $parameter === 1 ) ) )
        {
            $this -> errors[ $parameterName ][] = 'Parameter<' . $parameterName . '>IsNotValid';
        }
    }


    public function validateArray( $parameterName , $parameter , $rules )
    {
        if( ! ( isset( $parameter ) && is_array( $parameter ) ) )
        {
            $this -> errors[ $parameterName ][] = 'Parameter<' . $parameterName . '>IsNotValid';
        }
        elseif( isset( $rules[ 'count' ] ) && $rules[ 'count' ] && ! count( $parameter ) )
        {
            $this -> errors[ $parameterName ][] = 'Parameter<' . $parameterName . '>IsNotValid';
        }
    }


    public function validateDatetime( $parameterName , $parameter , $rules = [] )
    {
        $format = $rules[ 'format' ] ?? 'Y-m-d';

        $d = DateTime ::createFromFormat( $format , $parameter );

        if( ! ( $d && $d -> format( $format ) == $parameter ) )
        {
            if( strlen( $parameterName ) ) $this -> errors[ $parameterName ][] = 'Parameter<' . $parameterName . '>IsNotValid';

            return false;
        }

        return true;
    }


    public function validateRepeat( $parameterName , $parameter , $repeatParameter , $rules )
    {
        if( ! ( is_string( $parameter ) && strlen( $parameter ) && strlen( $parameter ) < $rules[ 'max' ] ?? 1111111 ) )
        {
            $this -> errors[ $parameterName ][] = 'Parameter<' . $parameterName . '>IsNotValid';
        }

        if( ! ( is_string( $repeatParameter ) && strlen( $repeatParameter ) && strlen( $repeatParameter ) < $rules[ 'max' ] ?? 1111111 ) )
        {
            $this -> errors[ $parameterName . 'Repeat' ][] = 'Parameter<' . $parameterName . 'Repeat>IsNotValid';
        }

        if( $parameter !== $repeatParameter )
        {
            $this -> errors[ $parameterName ][]            = 'Parameter<' . $parameterName . '>NotMatched';
            $this -> errors[ $parameterName . 'Repeat' ][] = 'Parameter<' . $parameterName . 'Repeat>NotMatched';
        }
    }


    public function validateUniqueness( $parameterName , $parameter , $rules , $request )
    {
        $user = DB ::table( $rules[ 'unique' ] ) -> where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 ) -> where( $rules[ 'db' ] ?? $parameterName , '=' , $parameter ) -> get();

        if( ( count( $user ) > 1 ) || ( count( $user ) == 1 && isset( $user[ 0 ] ) && $user[ 0 ] -> id && $user[ 0 ] -> id != $request -> request -> get( 'id' ) ) )
        {
            $this -> errors[ $parameterName ][] = 'Parameter<' . $parameterName . '>IsNotValid';
        }
    }


    public function validateExistence( $parameterName , $parameter , $rules )
    {
        $object = DB ::table( $rules[ 'exist' ] ) -> where( 'is_deleted' , 0 ) -> where( 'is_active' , 1 ) -> where( $rules[ 'db' ] ?? $parameterName , '=' , $parameter ) -> get();

        if( ! count( $object ) )
        {
            $this -> errors[ $parameterName ][] = 'Parameter<' . $parameterName . '>DoesNotExist';
        }
    }


    public function validateInArray( $parameterName , $parameter , $array )
    {
        if( ! in_array( $parameter , $array ) )
        {
            $this -> errors[ $parameterName ][] = 'Parameter<' . $parameterName . '>IsNotValid';
        }
    }
}
