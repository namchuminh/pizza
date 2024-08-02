<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebProductController extends Controller
{
    public function index(){
        return view('web.list');
    }

    public function detail(){
        return view('web.detail');
    }
}
