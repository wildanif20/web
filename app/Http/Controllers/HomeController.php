<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = session('authenticate');
        $token = 'bearer '.$data['access_token'];

        $client = new \GuzzleHttp\Client(['http_errors' => false]);
        $req = $client->get('localhost:8000/api/auth/me', [
            'headers' => [
                'Authorization' => $token,
                //Dari Postman yg sudah terdaftar
            ]
        ]);
        $response = $req->getBody()->getContents();
        $user = json_decode($response, true);
        $code = $user['code'];

        if ($code == 200) { 
            $datauser = $user['content'];
            return view('home', compact('datauser'));
        } elseif ($code == 401) {
            return redirect()->route('logout');
        }
    }

    public function login()
    {
        $client = new \GuzzleHttp\Client(['http_errors' => false]);
        $req = $client->post('localhost:8000/api/auth/login', [
            'form_params' => [
                "email" => "1@1.com",
                "password" => "1234",
                //Dari Postman yg sudah terdaftar
            ]
        ]);
        $response = $req->getBody()->getContents();
        $user = json_decode($response, true);

        // $code = $user['code'];
        // $message = $user['message'];

        dd($user);
    }
}
