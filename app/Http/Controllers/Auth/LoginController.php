<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Admin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers
    {
        logout as performLogout;
    }

    public function logout( Request $request )
    {
        $this -> performLogout( $request );

        return redirect( '/login' );
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/panel';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this -> middleware( 'guest' ) -> except( 'logout' );
    }

    protected function credentials( Request $request )
    {
        return array_merge( $request -> only( $this -> username() , 'password' ) , [ 'is_active' => 1 , 'is_deleted' => 0 ] );
    }

    /**
     * Get the failed login response instance.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    protected function sendFailedLoginResponse( Request $request )
    {
        $errors = [ $this -> username() => trans( 'auth.failed' ) ];

        // Load user from database
        $user = Admin ::where( $this -> username() , $request ->{$this -> username()} ) -> first();

        // Check if user was successfully loaded, that the password matches
        // and is_active is not 1 and is_deleted is not 0. If so, override the default error message.
        if( $user && Hash ::check( $request -> password ?? '' , $user -> password ) && $user -> is_active != 1 && $user -> is_deleted != 0 )
        {
            $errors = [ $this -> username() => trans( 'auth.failed' ) ];
        }

        if( $request -> expectsJson() )
        {
            return response() -> json( $errors , 422 );
        }

        return redirect() -> back()
                          -> withInput( $request -> only( $this -> username() , 'remember' ) )
                          -> withErrors( $errors )
            ;
    }

    private function username()
    {
        return 'username';
    }


    public function login( Request $request )
    {
        $this -> validateLogin( $request );


        $grecaptcha = json_decode( $this -> getGRecapchaResponse( $request -> request -> get( 'g-recaptcha-response' ) ) );

        if( ! ( $grecaptcha && $grecaptcha -> success ) )
        {
            //return $this -> sendFailedLoginResponse( $request );
        }


        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if( $this -> hasTooManyLoginAttempts( $request ) )
        {
            $this -> fireLockoutEvent( $request );

            return $this -> sendLockoutResponse( $request );
        }

        if( $this -> attemptLogin( $request ) )
        {
            return $this -> sendLoginResponse( $request );
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this -> incrementLoginAttempts( $request );

        return $this -> sendFailedLoginResponse( $request );
    }
}
