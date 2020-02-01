<?php

namespace App\Http\Controllers\Auth;

use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this -> middleware( 'guest' );
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator( array $data )
    {
        return Validator ::make( $data , [
            'name'     => 'required|string|max:50' ,
            'surname'  => 'required|string|max:50' ,
            'username' => 'required|string|max:50|unique:users' ,
            'email'    => 'required|string|email|max:50|unique:users' ,
            'password' => 'required|string|min:6|confirmed' ,
        ] );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\Admin
     */
    protected function create( array $data )
    {
        return Admin ::create( [
                                  'username' => $data[ 'username' ] ,
                                  'name'     => $data[ 'name' ] ,
                                  'surname'  => $data[ 'surname' ] ,
                                  'email'    => $data[ 'email' ] ,
                                  'password' => Hash ::make( $data[ 'password' ] ) ,
                              ] );
    }
}
