<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebCustomerController extends Controller
{
    public function index(){
        return view('web.customer');
    }

    public function detailOrder($id){
        return view('web.detailOrder', compact('id'));
    }
}
