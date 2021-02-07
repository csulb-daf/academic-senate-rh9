<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Redirect;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Input;
use App\User;
use Session;
use Illuminate\Support\Facades\Auth;


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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email','password');

        $success = $this->verify($credentials['email'],$credentials['password']);

        if($success)
        {
            $eml = $credentials['email'];
            $user = User::where('email', '=', $eml)->first();

            if ($user) {
                Auth::login($user);
                return redirect('/');
            }

            else {
                session()->flash('msg', 'You are not authorized to access this application.');
                return redirect()->back();
                //return Redirect::back()->withErrors(['msg', 'You are not authorized to access this application.']);
            }
        }
        else {
            //$errors = new MessageBag(['password' => ['Email and/or password invalid.']]);
            $errors = new MessageBag;
            $errors->add ('password', 'Invalid email or password.');
            $errors->add('email','Invalid email or password');
            return Redirect::back()->withErrors($errors)->withInput(Input::except('password'));
        }
    }

    public function verify($eml, $pwd)
    {
        $host = env('ADLDS_HOST');
        $ldapport = env('ADLDS_PORT');
        $connection = @ldap_connect($host,$ldapport);
        $success = false;

        if ($connection) {
            ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($connection, LDAP_OPT_REFERRALS, 0);

            $bind = @ldap_bind($connection, $eml, $pwd);
            if ($bind) {
                $success = true;

                ldap_close($connection);
            }
        }
        return $success;
    }
}
