<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OverviewController extends Controller
{
    public function __construct(Request $request) 
    {
        $this->middleware('employer');
    }

    public function index()
    {
       return view('frontend.overview.index'); 
    }
}
