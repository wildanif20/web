<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

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
        $email = $request->email;
        $password = $request->password;

        $client = new \GuzzleHttp\Client(['http_errors' => false]);
        $req = $client->post('localhost:8000/api/auth/login', [
            'form_params' => [
                "email" => $email,
                "password" => $password,
                //Dari Postman yg sudah terdaftar
            ]
        ]);
        $response = $req->getBody()->getContents();
        $user = json_decode($response, true);

        $code = $user['code'];
        if ($code == 200) { 
            $access_token = 'bearer '. $user['content']['access_token'];
            session(['authenticate' => $user['content']]);
            return redirect()->route('home');
        } else {
            return redirect()->route('login');
        }
    }
}
