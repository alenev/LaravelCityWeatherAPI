<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('welcome');
    }

    public function login()
    {
        return view('loginVUE');
    }

    public function emailVerifySuccess()
    {
        return view('email_verify_success');
    }

}
