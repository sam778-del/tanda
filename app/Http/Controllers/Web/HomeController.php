<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth:employer');
    }

    public function __invoke()
    {
        return view('frontend.auth.register');
    }
}
