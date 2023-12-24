<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(){
        return view('front.account.login');
    }
    
    public function register(){
        return view('front.account.register');
    }
}
